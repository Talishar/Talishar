<?php
/**
 * GetDiscordReleaseNotes.php
 * 
 * Fetches the latest messages from the Discord #release-notes channel
 * Uses APCu caching (10 minute TTL) to reduce Discord API calls
 * 
 * Requires:
 * - DISCORD_BOT_TOKEN environment variable
 * - DISCORD_CHANNEL_ID environment variable
 * 
 * Usage: GET /GetDiscordReleaseNotes.php?maxMessages=5
 */

include 'Libraries/APCuCache.php';

header('Content-Type: application/json');

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedDomains = ['localhost', 'talishar.net', 'legacy.talishar.net', 'preview.talishar.net'];
$isAllowed = false;

foreach ($allowedDomains as $domain) {
    if (strpos($origin, $domain) !== false) {
        $isAllowed = true;
        break;
    }
}

if ($isAllowed) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$mentionSearch = [
    '<#868488473378684938>', '<#1014193064736194691>', '<#865423689976774656>',
    '<#971928581267664936>', '<#865412150125789217>', '<#1363088144139812935>',
    '<@387720067640459275>', '<@256263154915475456>', '<@478621285786845196>',
    '<@695767191022338070>', '<@214191542292840448>', '<@242811065878970368>',
    '<@405057789002776578>',
];
$mentionReplace = [
    '#release-notes', '#talishar-content', '#bug-reports',
    '#feature-requests', '#general', '#mobile-ui-bug-reports',
    '@THESPIRITOFLIFE', '@Aegisworn', '@PvtVoid',
    '@hoodwill', '@looseleaftea', '@Zandrenel',
    '@Buford (Dan)',
];

$normalizePatterns     = ['/\r\n|\r/', '/<(https?:\/\/[^>]+)>/i', '/&lt;(https?:\/\/[^&]+)&gt;/i'];
$normalizeReplacements = ["\n",        '$1',                        '$1'];

try {
    // Check cache first
    $cacheKey = 'discord_release_notes';
    $cachedResult = APCuCache::get($cacheKey);
    if ($cachedResult !== null) {
        http_response_code(200);
        echo json_encode($cachedResult);
        exit;
    }

    $maxMessages = min(isset($_GET['maxMessages']) ? (int)$_GET['maxMessages'] : 5, 10);

    // Try to get from environment, then from $_ENV, then from direct file
    $botToken  = getenv('DISCORD_BOT_TOKEN') ?: ($_ENV['DISCORD_BOT_TOKEN'] ?? null);
    $channelId = getenv('DISCORD_CHANNEL_ID') ?: ($_ENV['DISCORD_CHANNEL_ID'] ?? null);
    
    // Fallback: check if there's a config file
    if (!$botToken || !$channelId) {
        $configPaths = [
            __DIR__ . '/.env',
            dirname(__DIR__) . '/.env',
            '/opt/lampp/htdocs/game/.env',
            '/opt/lampp/htdocs/.env',
        ];
        
        foreach ($configPaths as $configFile) {
            if (file_exists($configFile) && is_readable($configFile)) {
                $lines = file($configFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    // Guard prevents re-checking a key we already have, and substr is
                    // faster than str_replace for a known fixed-length prefix.
                    if (!$botToken && strpos($line, 'DISCORD_BOT_TOKEN=') === 0) {
                        $botToken = trim(substr($line, 18), '"\'');
                    }
                    if (!$channelId && strpos($line, 'DISCORD_CHANNEL_ID=') === 0) {
                        $channelId = trim(substr($line, 19), '"\'');
                    }
                }
                if ($botToken && $channelId) {
                    break;
                }
            }
        }
    }
    
    if (!$botToken || !$channelId) {
        // Return empty response if not configured
        http_response_code(200);
        echo json_encode(['messages' => []]);
        exit;
    }

    $curlHeaders = [
        "Authorization: Bot {$botToken}",
        "User-Agent: Talishar-Discord-Bot",
    ];

    $chChannel = curl_init("https://discord.com/api/v10/channels/{$channelId}");
    curl_setopt($chChannel, CURLOPT_HTTPHEADER, $curlHeaders);
    curl_setopt($chChannel, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chChannel, CURLOPT_TIMEOUT, 5);

    $chMessages = curl_init("https://discord.com/api/v10/channels/{$channelId}/messages?limit={$maxMessages}");
    curl_setopt($chMessages, CURLOPT_HTTPHEADER, $curlHeaders);
    curl_setopt($chMessages, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chMessages, CURLOPT_TIMEOUT, 5);

    $mh = curl_multi_init();
    curl_multi_add_handle($mh, $chChannel);
    curl_multi_add_handle($mh, $chMessages);

    do {
        $status = curl_multi_exec($mh, $active);
        if ($active) {
            curl_multi_select($mh);
        }
    } while ($active && $status === CURLM_OK);

    $channelResponse = curl_multi_getcontent($chChannel);
    $channelHttpCode = curl_getinfo($chChannel, CURLINFO_HTTP_CODE);
    $response        = curl_multi_getcontent($chMessages);
    $httpCode        = curl_getinfo($chMessages, CURLINFO_HTTP_CODE);

    curl_multi_remove_handle($mh, $chChannel);
    curl_multi_remove_handle($mh, $chMessages);
    curl_multi_close($mh);

    $channelInfo = ($channelHttpCode === 200) ? json_decode($channelResponse, true) : null;

    if ($httpCode !== 200) {
        // If Discord API fails, return empty
        http_response_code(200);
        echo json_encode(['messages' => []]);
        exit;
    }
    
    $messages = json_decode($response, true);
    if (!is_array($messages)) {
        http_response_code(200);
        echo json_encode(['messages' => []]);
        exit;
    }
    
    $data = [];
    foreach ($messages as $msg) {
        // Include messages with content OR messages with embeds
        $hasContent = !empty($msg['content']);
        $hasEmbeds  = isset($msg['embeds']) && !empty($msg['embeds']);

        if ($hasContent || $hasEmbeds) {
            $authorName  = $msg['author']['global_name'] ?? $msg['author']['username'];
            $attachments = $msg['attachments'] ?? [];
            $reactions   = $msg['reactions']   ?? [];

            $content = $msg['content'] ?? '';

            $content = str_replace($mentionSearch, $mentionReplace, $content);
            $content = preg_replace($normalizePatterns, $normalizeReplacements, $content);
            $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
            // Convert URLs to clickable links
            $content = preg_replace(
                '/(https?:\/\/[^\s<>"{}|\\^`\[\]]*)/i',
                '<a href="$1" target="_blank">$1</a>',
                $content
            );
            $content = nl2br($content, false);
            
            $data[] = [
                'id'          => $msg['id'],
                'content'     => $content,
                'author'      => $authorName,
                'timestamp'   => $msg['timestamp'],
                'embeds'      => $msg['embeds'] ?? [],
                'attachments' => $attachments,
                'reactions'   => $reactions,
            ];
        }
    }
    $result = [
        'messages'    => array_slice($data, 0, $maxMessages),
        'channelName' => $channelInfo ? '#' . $channelInfo['name'] : '#release-notes',
    ];
    
    // Cache the result for 10 minutes
    APCuCache::set($cacheKey, $result, 600);
    
    http_response_code(200);
    echo json_encode($result);
    
} catch (Exception $e) {
    error_log("Discord fetch error: " . $e->getMessage());
    http_response_code(200);
    echo json_encode(['messages' => [], 'channelName' => '#release-notes']);
}
