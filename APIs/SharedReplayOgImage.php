<?php

include_once "../Libraries/ReplayLibraries.php";

const OG_IMAGE_WIDTH = 1200;
const OG_IMAGE_HEIGHT = 630;
const OG_PANEL_WIDTH = 530;
const OG_PANEL_HEIGHT = 409;
const OG_PANEL_TOP = 22;
const OG_PANEL_MARGIN = 30;
const OG_LOGO_WIDTH = 380;
const OG_LOGO_TOP = 442;
const OG_CACHE_DIR = "../Replays/shared/og/";
const OG_FETCH_TIMEOUT = 6;

const OG_CROP_IMAGE_BASE = "https://images.talishar.net/public/crops/";
const OG_LOGO_PATHS = [
  "../Assets/TalisharLogo.webp",
  "/opt/project/Talishar-FE/public/TalisharLogo.webp"
];

function OgFirstExistingPath(array $candidates): ?string
{
  foreach ($candidates as $candidate) {
    if (is_file($candidate)) return $candidate;
  }
  return null;
}

function OgFetchRemote(string $url): ?string
{
  $context = stream_context_create([
    "http" => [
      "timeout" => OG_FETCH_TIMEOUT,
      "user_agent" => "Talishar/1.0 (+https://talishar.net)"
    ],
    "ssl" => ["verify_peer" => true, "verify_peer_name" => true]
  ]);

  $contents = @file_get_contents($url, false, $context);
  return $contents === false || $contents === "" ? null : $contents;
}

function OgLoadHeroCrop(string $heroCardId)
{
  $sources = [];
  if ($heroCardId !== "") $sources[] = OG_CROP_IMAGE_BASE . rawurlencode($heroCardId) . "_cropped.webp";
  $sources[] = OG_CROP_IMAGE_BASE . "UNKNOWNHERO_cropped.webp";

  foreach ($sources as $source) {
    $contents = OgFetchRemote($source);
    if ($contents === null) continue;
    $image = @imagecreatefromstring($contents);
    if ($image !== false) return $image;
  }
  return null;
}

// Scales the crop to cover the panel and centers the overflow, so a crop that
// does not share the panel's aspect ratio still fills it edge to edge.
function OgDrawCover($canvas, $source, int $destX, int $destY, int $destWidth, int $destHeight): void
{
  $sourceWidth = imagesx($source);
  $sourceHeight = imagesy($source);
  $scale = max($destWidth / $sourceWidth, $destHeight / $sourceHeight);
  $visibleWidth = (int)round($destWidth / $scale);
  $visibleHeight = (int)round($destHeight / $scale);

  imagecopyresampled(
    $canvas,
    $source,
    $destX,
    $destY,
    (int)round(($sourceWidth - $visibleWidth) / 2),
    (int)round(($sourceHeight - $visibleHeight) / 2),
    $destWidth,
    $destHeight,
    $visibleWidth,
    $visibleHeight
  );
}

function OgRenderImage(array $replay): string
{
  $canvas = imagecreatetruecolor(OG_IMAGE_WIDTH, OG_IMAGE_HEIGHT);
  imagealphablending($canvas, true);
  imagesavealpha($canvas, false);

  $background = imagecolorallocate($canvas, 11, 11, 13);
  $hairline = imagecolorallocate($canvas, 38, 38, 43);

  imagefilledrectangle($canvas, 0, 0, OG_IMAGE_WIDTH, OG_IMAGE_HEIGHT, $background);

  $panelLeft = [OG_PANEL_MARGIN, OG_IMAGE_WIDTH - OG_PANEL_MARGIN - OG_PANEL_WIDTH];
  $heroCardIds = [$replay["p1HeroCardId"], $replay["p2HeroCardId"]];

  foreach ($heroCardIds as $index => $heroCardId) {
    $x = $panelLeft[$index];

    $crop = OgLoadHeroCrop($heroCardId);
    if ($crop !== null) {
      OgDrawCover($canvas, $crop, $x, OG_PANEL_TOP, OG_PANEL_WIDTH, OG_PANEL_HEIGHT);
      imagedestroy($crop);
    }
    imagerectangle(
      $canvas,
      $x - 1,
      OG_PANEL_TOP - 1,
      $x + OG_PANEL_WIDTH,
      OG_PANEL_TOP + OG_PANEL_HEIGHT,
      $hairline
    );
  }

  imageline(
    $canvas,
    (int)(OG_IMAGE_WIDTH / 2),
    OG_PANEL_TOP + 18,
    (int)(OG_IMAGE_WIDTH / 2),
    OG_PANEL_TOP + OG_PANEL_HEIGHT - 18,
    $hairline
  );

  $logoPath = OgFirstExistingPath(OG_LOGO_PATHS);
  if ($logoPath !== null) {
    $logo = @imagecreatefromstring((string)file_get_contents($logoPath));
    if ($logo !== false) {
      $logoHeight = (int)round(imagesy($logo) * (OG_LOGO_WIDTH / imagesx($logo)));
      imagecopyresampled(
        $canvas,
        $logo,
        (int)round((OG_IMAGE_WIDTH - OG_LOGO_WIDTH) / 2),
        OG_LOGO_TOP,
        0,
        0,
        OG_LOGO_WIDTH,
        $logoHeight,
        imagesx($logo),
        imagesy($logo)
      );
      imagedestroy($logo);
    }
  }

  imagerectangle($canvas, 0, 0, OG_IMAGE_WIDTH - 1, OG_IMAGE_HEIGHT - 1, $hairline);

  ob_start();
  imagepng($canvas, null, 6);
  $png = (string)ob_get_clean();
  imagedestroy($canvas);

  return $png;
}

function OgSendImage(string $png): void
{
  header("Content-Type: image/png");
  header("Content-Length: " . strlen($png));
  header("Cache-Control: public, max-age=86400");
  echo $png;
}

function OgSendFallback(): void
{
  $fallback = OgFirstExistingPath([
    "../Assets/talisharog.png",
    "/opt/project/Talishar-FE/public/talisharog.png"
  ]);
  if ($fallback !== null) {
    OgSendImage((string)file_get_contents($fallback));
    exit;
  }
  http_response_code(404);
  exit;
}

$token = (string)($_GET["token"] ?? "");
$replay = SharedReplayMetadata($token);
if ($replay === null) OgSendFallback();

$cachePath = OG_CACHE_DIR . $token . ".png";
if (is_file($cachePath)) {
  OgSendImage((string)file_get_contents($cachePath));
  exit;
}

if (!function_exists("imagecreatetruecolor")) OgSendFallback();

$png = OgRenderImage($replay);

if (is_dir(OG_CACHE_DIR) || @mkdir(OG_CACHE_DIR, 0700, true)) {
  @file_put_contents($cachePath, $png, LOCK_EX);
}

OgSendImage($png);
