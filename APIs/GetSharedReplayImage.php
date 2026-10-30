<?php

include_once "../Libraries/ReplayLibraries.php";

const SHARED_REPLAY_IMAGE_WIDTH = 1200;
const SHARED_REPLAY_IMAGE_HEIGHT = 630;
const SHARED_REPLAY_IMAGE_SEAM = 60;
const SHARED_REPLAY_IMAGE_LOGO_WIDTH = 520;
const SHARED_REPLAY_IMAGE_VERSION = 1;
const SHARED_REPLAY_IMAGE_FALLBACK = "https://talishar.net/talisharog.png";

function LoadSharedReplayHeroArt(string $heroCardId)
{
  $context = stream_context_create(["http" => ["timeout" => 5, "user_agent" => "Talishar"]]);
  foreach ([$heroCardId, "UNKNOWNHERO"] as $cardId) {
    if (preg_match('/^[A-Za-z0-9_-]+$/', $cardId) !== 1) continue;
    $data = @file_get_contents("https://images.talishar.net/public/crops/{$cardId}_cropped.png", false, $context);
    $art = $data === false ? false : @imagecreatefromstring($data);
    if ($art !== false) return $art;
  }
  return null;
}

function SharedReplayHeroLayer($art, int $width, int $height, float $focusX)
{
  $layer = imagecreatetruecolor($width, $height);
  if ($art === null) return $layer;
  $artWidth = imagesx($art);
  $artHeight = imagesy($art);
  $scale = max($width / $artWidth, $height / $artHeight);
  $sourceWidth = (int)round($width / $scale);
  $sourceHeight = (int)round($height / $scale);
  imagecopyresampled(
    $layer, $art, 0, 0,
    (int)round(($artWidth - $sourceWidth) * $focusX), intdiv($artHeight - $sourceHeight, 2),
    $width, $height, $sourceWidth, $sourceHeight
  );
  return $layer;
}

function ShadeSharedReplayImage($canvas, int $x1, int $y1, int $x2, int $y2, float $shade): void
{
  if ($shade <= 0) return;
  $alpha = 127 - (int)round(127 * min($shade, 1));
  imagefilledrectangle($canvas, $x1, $y1, $x2, $y2, imagecolorallocatealpha($canvas, 0, 0, 0, $alpha));
}

function RenderSharedReplayImage($p1Art, $p2Art): string
{
  $width = SHARED_REPLAY_IMAGE_WIDTH;
  $height = SHARED_REPLAY_IMAGE_HEIGHT;
  $half = intdiv($width, 2);
  $seam = SHARED_REPLAY_IMAGE_SEAM;
  $canvas = imagecreatetruecolor($width, $height);

  $left = SharedReplayHeroLayer($p1Art, $half + $seam, $height, 1);
  $right = SharedReplayHeroLayer($p2Art, $half + $seam, $height, 0);
  imagecopy($canvas, $left, 0, 0, 0, 0, $half + $seam, $height);
  imagecopy($canvas, $right, $half + $seam, 0, 2 * $seam, 0, $half - $seam, $height);
  for ($i = 0; $i < 2 * $seam; ++$i) {
    imagecopymerge($canvas, $right, $half - $seam + $i, 0, $i, 0, 1, $height, (int)round(($i + 0.5) * 50 / $seam));
  }

  imagealphablending($canvas, true);
  for ($x = 0; $x < $width; ++$x) {
    $towardCenter = 1 - abs($x + 0.5 - $half) / $half;
    ShadeSharedReplayImage($canvas, $x, 0, $x, $height - 1, 0.1 + 0.8 * $towardCenter ** 2.2);
  }
  $bottomStart = (int)($height * 0.55);
  for ($y = $bottomStart; $y < $height; ++$y) {
    ShadeSharedReplayImage($canvas, 0, $y, $width - 1, $y, 0.65 * (($y - $bottomStart) / ($height - $bottomStart)) ** 1.5);
  }
  $topEnd = (int)($height * 0.2);
  for ($y = 0; $y < $topEnd; ++$y) {
    ShadeSharedReplayImage($canvas, 0, $y, $width - 1, $y, 0.35 * (1 - $y / $topEnd) ** 2);
  }

  $logo = @imagecreatefrompng(__DIR__ . "/../Images/TalisharLogo.png");
  if ($logo !== false) {
    $logoWidth = SHARED_REPLAY_IMAGE_LOGO_WIDTH;
    $logoHeight = (int)round(imagesy($logo) * $logoWidth / imagesx($logo));
    imagecopyresampled(
      $canvas, $logo,
      intdiv($width - $logoWidth, 2), intdiv($height - $logoHeight, 2), 0, 0,
      $logoWidth, $logoHeight, imagesx($logo), imagesy($logo)
    );
  }

  ob_start();
  imagejpeg($canvas, null, 86);
  return (string)ob_get_clean();
}

$metadata = SharedReplayMetadata((string)($_GET["token"] ?? ""));
if ($metadata === null || ($metadata["type"] ?? "") === "snapshot") {
  http_response_code(404);
  exit;
}

if (!function_exists("imagecreatefrompng") || !function_exists("imagejpeg")) {
  header("Location: " . SHARED_REPLAY_IMAGE_FALLBACK, true, 302);
  exit;
}

$p1Hero = trim((string)($metadata["p1HeroCardId"] ?? ""));
$p2Hero = trim((string)($metadata["p2HeroCardId"] ?? ""));
$cacheDirectory = "../Replays/shared/previews/";
$cacheFile = $cacheDirectory . md5(SHARED_REPLAY_IMAGE_VERSION . "|$p1Hero|$p2Hero") . ".jpg";
$jpeg = is_file($cacheFile) ? file_get_contents($cacheFile) : false;

if ($jpeg === false) {
  $p1Art = LoadSharedReplayHeroArt($p1Hero);
  $p2Art = LoadSharedReplayHeroArt($p2Hero);
  $jpeg = RenderSharedReplayImage($p1Art, $p2Art);
  if ($p1Art !== null && $p2Art !== null && (is_dir($cacheDirectory) || @mkdir($cacheDirectory, 0700, true))) {
    $tempFile = $cacheFile . "." . bin2hex(random_bytes(4)) . ".tmp";
    if (file_put_contents($tempFile, $jpeg) !== false) @rename($tempFile, $cacheFile);
    else @unlink($tempFile);
  }
}

header("Content-Type: image/jpeg");
header("Cache-Control: public, max-age=86400");
echo $jpeg;
