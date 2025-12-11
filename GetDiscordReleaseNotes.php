<?php
/**
 * GetDiscordReleaseNotes.php
 * 
 * Fetches the latest messages from the Discord #release-notes channel
 * 
 * Requires:
 * - DISCORD_BOT_TOKEN environment variable
 * - DISCORD_CHANNEL_ID environment variable
 * 
 * Usage: GET /GetDiscordReleaseNotes.php?maxMessages=5
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $maxMessages = isset($_GET['maxMessages']) ? (int)$_GET['maxMessages'] : 5;
    $maxMessages = min($maxMessages, 100); // Cap at 100 messages (Discord API max per request)
    
    // Try to get from environment, then from $_ENV, then from direct file
    $botToken = getenv('DISCORD_BOT_TOKEN') ?: ($_ENV['DISCORD_BOT_TOKEN'] ?? null);
    $channelId = getenv('DISCORD_CHANNEL_ID') ?: ($_ENV['DISCORD_CHANNEL_ID'] ?? null);
    
    // Fallback: check if there's a config file
    if (!$botToken || !$channelId) {
        // Try multiple .env file locations
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
                    if (strpos($line, 'DISCORD_BOT_TOKEN=') === 0) {
                        $botToken = trim(str_replace('DISCORD_BOT_TOKEN=', '', $line), '"\'');
                    }
                    if (strpos($line, 'DISCORD_CHANNEL_ID=') === 0) {
                        $channelId = trim(str_replace('DISCORD_CHANNEL_ID=', '', $line), '"\'');
                    }
                }
                if ($botToken && $channelId) {
                    break; // Found both values, stop searching
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
    
    // Fetch channel info to get the channel name
    $channelInfo = null;
    $channelUrl = "https://discord.com/api/v10/channels/{$channelId}";
    
    $ch = curl_init($channelUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bot {$botToken}",
        "User-Agent: Talishar-Discord-Bot"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $channelResponse = curl_exec($ch);
    $channelHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($channelHttpCode === 200) {
        $channelInfo = json_decode($channelResponse, true);
    }
    
    // Fetch messages from Discord API
    $url = "https://discord.com/api/v10/channels/{$channelId}/messages?limit={$maxMessages}";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bot {$botToken}",
        "User-Agent: Talishar-Discord-Bot"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
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
        $hasEmbeds = isset($msg['embeds']) && !empty($msg['embeds']);
        
        if ($hasContent || $hasEmbeds) {
            // Get author display name (prefer global_name, then username)
            $authorName = $msg['author']['global_name'] ?? $msg['author']['username'];
            
            // Get attachments (images, files, etc.)
            $attachments = isset($msg['attachments']) ? $msg['attachments'] : [];
            
            // Get reactions
            $reactions = isset($msg['reactions']) ? $msg['reactions'] : [];
            
            // Replace channel mentions in content
            $content = $msg['content'] ?? '';
            $content = str_replace('<#868488473378684938>', '#release-notes', $content);
            $content = str_replace('<#1014193064736194691>', '#talishar-content', $content);
            $content = str_replace('<#865423689976774656>', '#bug-reports', $content);
            $content = str_replace('<#971928581267664936>', '#feature-requests', $content);

            // Replace user mentions with usernames
            $content = str_replace('<@387720067640459275>', '@THESPIRITOFLIFE', $content);
            $content = str_replace('<@256263154915475456>', '@Aegisworn', $content);
            $content = str_replace('<@478621285786845196>', '@PvtVoid', $content);
            $content = str_replace('<@695767191022338070>', '@hoodwill', $content);
            $content = str_replace('<@214191542292840448>', '@looseleaftea', $content);

            // Preserve line breaks - normalize to \n first, then convert to HTML
            $content = preg_replace('/\r\n|\r/', "\n", $content);
            $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
            $content = nl2br($content, false);
            
            $data[] = [
                'id' => $msg['id'],
                'content' => $content,
                'author' => $authorName,
                'timestamp' => $msg['timestamp'],
                'embeds' => $msg['embeds'] ?? [],
                'attachments' => $attachments,
                'reactions' => $reactions
            ];
        }
    }
    
    // Sort by newest first (Discord returns newest first already, but ensure it)
    usort($data, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });
    
    http_response_code(200);
    echo json_encode([
        'messages' => array_slice($data, 0, $maxMessages),
        'channelName' => $channelInfo ? '#' . $channelInfo['name'] : '#release-notes'
    ]);
    
} catch (Exception $e) {
    error_log("Discord fetch error: " . $e->getMessage());
    http_response_code(200);
    echo json_encode(['messages' => [], 'channelName' => '#release-notes']);
}
