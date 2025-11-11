<?php
/**
 * GetDiscordContentCarousel.php
 * 
 * Fetches video links from the Discord #talishar-content channel
 * Returns YouTube and Twitch videos in a carousel format
 * 
 * Graceful error handling - returns empty carousel on any error (HTTP 200)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

http_response_code(200);

try {
    $maxMessages = isset($_GET['maxMessages']) ? (int)$_GET['maxMessages'] : 100;
    $maxMessages = min($maxMessages, 100);
    
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
            if (file_exists($envFile) && is_readable($envFile)) {
                foreach (file($envFile, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES) as $line) {
                    if (strpos($line, 'DISCORD_BOT_TOKEN=') === 0) {
                        $botToken = trim(str_replace('DISCORD_BOT_TOKEN=', '', $line), '"\'');
                    } elseif (strpos($line, 'DISCORD_CONTENT_CHANNEL_ID=') === 0) {
                        $channelId = trim(str_replace('DISCORD_CONTENT_CHANNEL_ID=', '', $line), '"\'');
                    }
                }
                if ($botToken && $channelId) {
                    break; // Found both values, stop searching
                }
            }
        }
    }
    
    if (!$botToken || !$channelId) {
        echo json_encode(['success' => true, 'count' => 0, 'videos' => [], 'channelName' => '#talishar-content']);
        exit;
    }
    
    // Fetch channel info to get the channel name
    $channelInfo = null;
    $channelUrl = "https://discord.com/api/v10/channels/{$channelId}";
    
    $ch = curl_init($channelUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bot {$botToken}", "User-Agent: Talishar"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $channelResponse = curl_exec($ch);
    $channelHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($channelHttpCode === 200) {
        $channelInfo = json_decode($channelResponse, true);
    }
    
    // Fetch from Discord with pagination to get more messages
    $messages = [];
    $before = null;
    $totalFetched = 0;
    $maxTotal = 50; // Fetch up to 100 messages total
    
    while ($totalFetched < $maxTotal) {
        $url = "https://discord.com/api/v10/channels/{$channelId}/messages?limit=100";
        if ($before) {
            $url .= "&before={$before}";
        }
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bot {$botToken}", "User-Agent: Talishar"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            break;
        }
        
        $batch = json_decode($response, true);
        if (!is_array($batch) || count($batch) === 0) {
            break;
        }
        
        $messages = array_merge($messages, $batch);
        $totalFetched += count($batch);
        $before = $batch[count($batch) - 1]['id'];
    }
    
    if (!is_array($messages)) {
        echo json_encode(['success' => true, 'count' => 0, 'videos' => [], 'channelName' => $channelInfo ? '#' . $channelInfo['name'] : '#talishar-content']);
        exit;
    }
    
    // Extract videos
    $videos = [];
    $ytRegex = '/(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/)?([a-zA-Z0-9_-]{11})/';
    $twRegex = '/twitch\.tv\/(?:videos\/(\d+)|([a-zA-Z0-9_]+))/';
    
    foreach ($messages as $msg) {
        if (!is_array($msg)) continue;
        $content = $msg['content'] ?? '';
        // Replace channel mentions in content
        $content = str_replace('<#868488473378684938>', '#release-notes', $content);
        $content = str_replace('<#1014193064736194691>', '#talishar-content', $content);
        // Preserve line breaks
        $content = nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'), false);
        
        $author = $msg['author']['username'] ?? 'Unknown';
        $timestamp = $msg['timestamp'] ?? date('c');
        
        if (preg_match($ytRegex, $content, $m)) {
            $videos[] = ['videoId' => $m[1], 'type' => 'youtube', 'title' => $content, 'author' => $author, 'timestamp' => $timestamp];
        } elseif (preg_match($twRegex, $content, $m)) {
            $vid = $m[1] ?? $m[2] ?? null;
            if ($vid) $videos[] = ['videoId' => $vid, 'type' => 'twitch', 'title' => $content, 'author' => $author, 'timestamp' => $timestamp];
        }
    }
    
    // Limit to 1 per author, most recent first
    $byAuthor = [];
    foreach ($videos as $v) {
        if (!isset($byAuthor[$v['author']]) || strtotime($v['timestamp']) > strtotime($byAuthor[$v['author']]['timestamp'])) {
            $byAuthor[$v['author']] = $v;
        }
    }
    
    $final = array_values($byAuthor);
    usort($final, fn($a, $b) => strtotime($b['timestamp'] ?? '0') - strtotime($a['timestamp'] ?? '0'));
    
    echo json_encode([
        'success' => true,
        'count' => count($final),
        'videos' => array_slice($final, 0, 12),
        'channelName' => $channelInfo ? '#' . $channelInfo['name'] : '#talishar-content'
    ]);
    
} catch (Exception $e) {
    error_log('GetDiscordContentCarousel: ' . $e->getMessage());
    echo json_encode(['success' => true, 'count' => 0, 'videos' => [], 'channelName' => '#talishar-content']);
}
?>
