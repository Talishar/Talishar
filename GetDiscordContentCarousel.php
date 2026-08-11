<?php
/**
 * GetDiscordContentCarousel.php
 * 
 * Fetches community content links from the Discord #talishar-content channel
 * Returns YouTube, Twitch, and Metafy guide items in a carousel format
 * 
 * Graceful error handling - returns empty carousel on any error (HTTP 200)
 * Uses APCu caching (10 minute TTL) to reduce Discord API calls
 */

include 'Libraries/APCuCache.php';

header('Content-Type: application/json');

// CORS validation - whitelist trusted domains
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

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

http_response_code(200);

try {
    // Check cache first
    $cacheKey = 'discord_carousel_videos';
    $cachedResult = APCuCache::get($cacheKey);
    if ($cachedResult !== null) {
        echo json_encode($cachedResult);
        exit;
    }

    // Get credentials
    $botToken = getenv('DISCORD_BOT_TOKEN') ?: ($_ENV['DISCORD_BOT_TOKEN'] ?? null);
    $channelId = getenv('DISCORD_CONTENT_CHANNEL_ID') ?: ($_ENV['DISCORD_CONTENT_CHANNEL_ID'] ?? null);
    
    if (!$botToken || !$channelId) {
        // Try multiple .env file locations
        $envPaths = [
            __DIR__ . '/.env',
            dirname(__DIR__) . '/.env',
            '/opt/lampp/htdocs/game/.env',
            '/opt/lampp/htdocs/.env',
        ];
        
        foreach ($envPaths as $envFile) {
            if (!file_exists($envFile) || !is_readable($envFile)) continue;
            foreach (file($envFile, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES) as $line) {
                if (!$botToken && strpos($line, 'DISCORD_BOT_TOKEN=') === 0) {
                    $botToken = trim(substr($line, 18), '"\'');
                } elseif (!$channelId && strpos($line, 'DISCORD_CONTENT_CHANNEL_ID=') === 0) {
                    $channelId = trim(substr($line, 27), '"\'');
                }
            }
            if ($botToken && $channelId) break;
        }
    }
    
    if (!$botToken || !$channelId) {
        echo json_encode(['success' => true, 'count' => 0, 'videos' => [], 'channelName' => '#talishar-content']);
        exit;
    }

    $headers = ["Authorization: Bot {$botToken}", "User-Agent: Talishar"];
    $curlOpts = [
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
    ];

    $chChannel  = curl_init("https://discord.com/api/v10/channels/{$channelId}");
    $chMessages = curl_init("https://discord.com/api/v10/channels/{$channelId}/messages?limit=100");
    curl_setopt_array($chChannel, $curlOpts);
    curl_setopt_array($chMessages, $curlOpts);

    $mh = curl_multi_init();
    curl_multi_add_handle($mh, $chChannel);
    curl_multi_add_handle($mh, $chMessages);

    do {
        $status = curl_multi_exec($mh, $stillRunning);
        if ($stillRunning) curl_multi_select($mh);
    } while ($stillRunning && $status === CURLM_OK);

    $channelHttpCode  = curl_getinfo($chChannel, CURLINFO_HTTP_CODE);
    $messagesHttpCode = curl_getinfo($chMessages, CURLINFO_HTTP_CODE);
    $channelResponse  = curl_multi_getcontent($chChannel);
    $messagesResponse = curl_multi_getcontent($chMessages);

    curl_multi_remove_handle($mh, $chChannel);
    curl_multi_remove_handle($mh, $chMessages);
    curl_multi_close($mh);
    curl_close($chChannel);
    curl_close($chMessages);

    $channelInfo = ($channelHttpCode === 200) ? json_decode($channelResponse, true) : null;
    $channelName = $channelInfo ? '#' . $channelInfo['name'] : '#talishar-content';

    $messages = [];
    if ($messagesHttpCode === 200) {
        $batch = json_decode($messagesResponse, true);
        if (is_array($batch) && count($batch) > 0) {
            $messages = $batch;
        }
    }

    if (empty($messages)) {
        echo json_encode(['success' => true, 'count' => 0, 'videos' => [], 'channelName' => $channelName]);
        exit;
    }
    
    // Extract content items
    $videos = [];
    $ytRegex     = '/(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/)?([a-zA-Z0-9_-]{11})/';
    $twRegex     = '/twitch\.tv\/(?:videos\/(\d+)|([a-zA-Z0-9_]+))/';
    $metafyRegex = '/https?:\/\/(?:www\.)?metafy\.gg\/guides(?:\/view)?\/[^\s]+/i';

    // Batch both channel ID substitutions into a single str_replace call
    $channelSearch  = ['<#868488473378684938>',  '<#1014193064736194691>'];
    $channelReplace = ['#release-notes', '#talishar-content'];
    foreach ($messages as $msg) {
        if (!is_array($msg)) continue;

        $content   = str_replace($channelSearch, $channelReplace, $msg['content'] ?? '');
        $author    = $msg['author']['username'] ?? 'Unknown';
        $timestamp = $msg['timestamp'] ?? date('c');
        // Pre-compute once per message; reused in both the byAuthor dedup and usort
        $tsInt     = strtotime($timestamp);
        $embeds    = is_array($msg['embeds'] ?? null) ? $msg['embeds'] : [];

        $metafyUrl   = null;
        $metafyEmbed = null;

        if (preg_match($metafyRegex, $content, $metafyMatch)) {
            $metafyUrl = preg_replace('/\?.*$/', '', $metafyMatch[0]);
        }

        foreach ($embeds as $embed) {

            if (!is_array($embed)) continue;
            $embedUrl = $embed['url'] ?? '';
            if (!$metafyUrl && is_string($embedUrl) && preg_match($metafyRegex, $embedUrl)) {
                $metafyUrl = preg_replace('/\?.*$/', '', $embedUrl);
            }

            if ($metafyUrl && !$metafyEmbed && is_string($embedUrl) && stripos($embedUrl, 'metafy.gg') !== false) {
                $metafyEmbed = $embed;
            }
        }

        if ($metafyUrl) {
            $videos[] = [
                'videoId'     => $msg['id'] ?? md5($metafyUrl . $timestamp),
                'type'        => 'metafy',
                'title'       => $metafyEmbed['title'] ?? $content,
                'author'      => $author,
                'description' => $metafyEmbed['description'] ?? '',
                'thumbnail'   => $metafyEmbed['thumbnail']['url'] ?? $metafyEmbed['image']['url'] ?? null,
                'timestamp'   => $timestamp,
                'tsInt'       => $tsInt,
                'messageUrl'  => $metafyUrl,
                'url'         => $metafyUrl,
            ];
            continue;
        }
        
        if (preg_match($ytRegex, $content, $m)) {
            $videos[] = ['videoId' => $m[1], 'type' => 'youtube', 'title' => $content, 'author' => $author, 'timestamp' => $timestamp, 'tsInt' => $tsInt];
        } elseif (preg_match($twRegex, $content, $m)) {
            $vid = $m[1] ?? $m[2] ?? null;
            if ($vid) {
                $videos[] = ['videoId' => $vid, 'type' => 'twitch', 'title' => $content, 'author' => $author, 'timestamp' => $timestamp, 'tsInt' => $tsInt];
            }
        }
    }
    
    // Limit to 1 per author, most recent first
    $byAuthor = [];
    foreach ($videos as $v) {
        if (!isset($byAuthor[$v['author']]) || $v['tsInt'] > $byAuthor[$v['author']]['tsInt']) {
            $byAuthor[$v['author']] = $v;
        }
    }
    
    $final = array_values($byAuthor);
    // Integer subtraction instead of strtotime() on every usort comparison
    usort($final, fn($a, $b) => $b['tsInt'] - $a['tsInt']);

    $sliced = array_slice($final, 0, 12);
    // Strip internal tsInt field before output so the JSON response is unchanged
    foreach ($sliced as &$v) unset($v['tsInt']);
    unset($v);

    $result = [
        'success'     => true,
        'count'       => count($final),
        'videos'      => $sliced,
        'channelName' => $channelName,
    ];
    
    // Cache the result for 10 minutes
    APCuCache::set($cacheKey, $result, 600);
    
    echo json_encode($result);
    
} catch (Exception $e) {
    error_log('GetDiscordContentCarousel: ' . $e->getMessage());
    echo json_encode(['success' => true, 'count' => 0, 'videos' => [], 'channelName' => '#talishar-content']);
}
?>
