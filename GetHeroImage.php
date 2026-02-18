<?php
// GetHeroImage.php - Fetch hero cropped image and return as base64
// This allows the frontend to bypass CORS restrictions by proxying through the backend

header('Content-Type: application/json');

try {
    // Get the hero name from query parameter
    $heroName = isset($_GET['hero']) ? $_GET['hero'] : null;
    
    if (!$heroName) {
        http_response_code(400);
        echo json_encode(['error' => 'Hero name is required']);
        exit;
    }
    
    // Validate hero name to prevent directory traversal
    if (preg_match('/[^a-zA-Z0-9_-]/', $heroName)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid hero name']);
        exit;
    }
    
    // Construct the image URL
    $imageUrl = "https://images.talishar.net/public/crops/{$heroName}_cropped.webp";
    
    // Fetch the image with a timeout
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ],
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
        ]
    ]);
    
    $imageData = @file_get_contents($imageUrl, false, $context);
    
    if ($imageData === false) {
        http_response_code(404);
        echo json_encode(['error' => 'Failed to fetch image']);
        exit;
    }
    
    // Convert to base64
    $base64Image = base64_encode($imageData);
    
    // Determine MIME type
    $mimeType = 'image/png';
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        $mimeType = finfo_buffer($finfo, $imageData) ?: 'image/png';
        finfo_close($finfo);
    }
    
    // Return as data URL
    $dataUrl = "data:{$mimeType};base64,{$base64Image}";
    
    http_response_code(200);
    echo json_encode(['dataUrl' => $dataUrl]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
