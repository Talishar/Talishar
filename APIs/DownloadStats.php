<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$type = $_POST['type'] ?? '';
$filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $_POST['filename'] ?? 'download');
if (empty($filename)) {
    $filename = 'download';
}

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if ($type === 'csv') {
    $data = $_POST['data'] ?? '';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $data;
} elseif ($type === 'png') {
    $dataUrl = $_POST['data'] ?? '';
    $base64 = preg_replace('/^data:image\/png;base64,/', '', $dataUrl);
    $imageData = base64_decode($base64);
    if ($imageData === false) {
        http_response_code(400);
        exit('Invalid image data');
    }
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $imageData;
} else {
    http_response_code(400);
    exit('Invalid type');
}
