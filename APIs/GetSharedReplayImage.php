<?php

include_once "../Libraries/ReplayLibraries.php";

const SHARED_REPLAY_IMAGE_WIDTH = 600;
const SHARED_REPLAY_IMAGE_HEIGHT = 315;
const SHARED_REPLAY_IMAGE_SEAM = 30;
const SHARED_REPLAY_IMAGE_VERSION = 2;

function DecodeSharedReplayPng(string $data): ?array
{
  if (!str_starts_with($data, "\x89PNG\r\n\x1a\n")) return null;
  $header = null;
  $compressed = "";
  $offset = 8;
  while ($offset + 12 <= strlen($data)) {
    $length = unpack("N", $data, $offset)[1];
    $type = substr($data, $offset + 4, 4);
    if ($type === "IHDR") $header = unpack("Nwidth/Nheight/Cdepth/Ctype/Ccompression/Cfilter/Cinterlace", $data, $offset + 8);
    else if ($type === "IDAT") $compressed .= substr($data, $offset + 8, $length);
    else if ($type === "IEND") break;
    $offset += 12 + $length;
  }
  $channels = match ($header["type"] ?? null) { 2 => 3, 6 => 4, default => 0 };
  if ($channels === 0 || $header["depth"] !== 8 || $header["interlace"] !== 0) return null;
  $width = $header["width"];
  $height = $header["height"];
  $stride = $width * $channels;
  $raw = @gzuncompress($compressed);
  if ($raw === false || strlen($raw) !== $height * ($stride + 1)) return null;

  $pixels = [];
  $previous = array_fill(0, $stride, 0);
  for ($y = 0; $y < $height; ++$y) {
    $start = $y * ($stride + 1);
    $row = array_values(unpack("C$stride", $raw, $start + 1));
    switch (ord($raw[$start])) {
      case 0:
        break;
      case 1:
        for ($i = $channels; $i < $stride; ++$i) $row[$i] = ($row[$i] + $row[$i - $channels]) & 255;
        break;
      case 2:
        for ($i = 0; $i < $stride; ++$i) $row[$i] = ($row[$i] + $previous[$i]) & 255;
        break;
      case 3:
        for ($i = 0; $i < $stride; ++$i) {
          $row[$i] = ($row[$i] + ((($i < $channels ? 0 : $row[$i - $channels]) + $previous[$i]) >> 1)) & 255;
        }
        break;
      case 4:
        for ($i = 0; $i < $stride; ++$i) {
          $left = $i < $channels ? 0 : $row[$i - $channels];
          $up = $previous[$i];
          $upLeft = $i < $channels ? 0 : $previous[$i - $channels];
          $leftDistance = abs($up - $upLeft);
          $upDistance = abs($left - $upLeft);
          $upLeftDistance = abs($left + $up - 2 * $upLeft);
          if ($leftDistance <= $upDistance && $leftDistance <= $upLeftDistance) $predictor = $left;
          else $predictor = $upDistance <= $upLeftDistance ? $up : $upLeft;
          $row[$i] = ($row[$i] + $predictor) & 255;
        }
        break;
      default:
        return null;
    }
    array_push($pixels, ...$row);
    $previous = $row;
  }
  return ["width" => $width, "height" => $height, "channels" => $channels, "pixels" => $pixels];
}

function EncodeSharedReplayPng(int $width, int $height, string $scanlines): string
{
  $chunk = fn(string $type, string $data) => pack("N", strlen($data)) . $type . $data . pack("N", crc32($type . $data));
  return "\x89PNG\r\n\x1a\n"
    . $chunk("IHDR", pack("NNC5", $width, $height, 8, 2, 0, 0, 0))
    . $chunk("IDAT", gzcompress($scanlines, 9))
    . $chunk("IEND", "");
}

function LoadSharedReplayHeroArt(string $heroCardId): ?array
{
  foreach ([$heroCardId, "UNKNOWNHERO"] as $cardId) {
    if (preg_match('/^[A-Za-z0-9_-]+$/', $cardId) !== 1) continue;
    $curl = curl_init("https://images.talishar.net/public/crops/{$cardId}_cropped.png");
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_USERAGENT => "Talishar"]);
    $data = curl_exec($curl);
    $art = is_string($data) && curl_getinfo($curl, CURLINFO_RESPONSE_CODE) === 200 ? DecodeSharedReplayPng($data) : null;
    if ($art !== null) return $art;
  }
  return null;
}

function SharedReplayHeroLayer(?array $art, int $width, int $height, float $focusX): array
{
  if ($art === null) return array_fill(0, $width * $height * 3, 0);
  ["width" => $artWidth, "height" => $artHeight, "channels" => $channels, "pixels" => $pixels] = $art;
  $scale = max($width / $artWidth, $height / $artHeight);
  $sourceLeft = ($artWidth - $width / $scale) * $focusX;
  $sourceTop = ($artHeight - $height / $scale) / 2;
  $columns = [];
  for ($x = 0; $x < $width; ++$x) {
    $source = min(max($sourceLeft + ($x + 0.5) / $scale - 0.5, 0), $artWidth - 1);
    $column = (int)$source;
    $columns[] = [$column * $channels, min($column + 1, $artWidth - 1) * $channels, $source - $column];
  }
  $layer = [];
  for ($y = 0; $y < $height; ++$y) {
    $source = min(max($sourceTop + ($y + 0.5) / $scale - 0.5, 0), $artHeight - 1);
    $row = (int)$source;
    $upper = $row * $artWidth * $channels;
    $lower = min($row + 1, $artHeight - 1) * $artWidth * $channels;
    $rowWeight = $source - $row;
    foreach ($columns as [$left, $right, $columnWeight]) {
      for ($c = 0; $c < 3; ++$c) {
        $top = $pixels[$upper + $left + $c] + ($pixels[$upper + $right + $c] - $pixels[$upper + $left + $c]) * $columnWeight;
        $bottom = $pixels[$lower + $left + $c] + ($pixels[$lower + $right + $c] - $pixels[$lower + $left + $c]) * $columnWeight;
        $layer[] = $top + ($bottom - $top) * $rowWeight;
      }
    }
  }
  return $layer;
}

function RenderSharedReplayImage(?array $p1Art, ?array $p2Art, ?array $logo): string
{
  $width = SHARED_REPLAY_IMAGE_WIDTH;
  $height = SHARED_REPLAY_IMAGE_HEIGHT;
  $half = intdiv($width, 2);
  $seam = SHARED_REPLAY_IMAGE_SEAM;
  $layerWidth = $half + $seam;
  $rightStart = $half - $seam;
  $leftEnd = $half + $seam;
  $left = SharedReplayHeroLayer($p1Art, $layerWidth, $height, 1);
  $right = SharedReplayHeroLayer($p2Art, $layerWidth, $height, 0);

  $columnLight = [];
  for ($x = 0; $x < $width; ++$x) $columnLight[] = 0.9 - 0.8 * (1 - abs($x + 0.5 - $half) / $half) ** 2.2;
  $bottomStart = (int)($height * 0.55);
  $topEnd = (int)($height * 0.2);
  $logoLeft = $logo === null ? 0 : intdiv($width - $logo["width"], 2);
  $logoTop = $logo === null ? 0 : intdiv($height - $logo["height"], 2);

  $scanlines = "";
  $previousLine = array_fill(0, $width * 3, 0);
  for ($y = 0; $y < $height; ++$y) {
    $rowLight = 1;
    if ($y >= $bottomStart) $rowLight *= 1 - 0.65 * (($y - $bottomStart) / ($height - $bottomStart)) ** 1.5;
    if ($y < $topEnd) $rowLight *= 1 - 0.35 * (1 - $y / $topEnd) ** 2;
    $logoRow = $y - $logoTop;
    $logoRowStart = $logo !== null && $logoRow >= 0 && $logoRow < $logo["height"] ? $logoRow * $logo["width"] : -1;
    $line = [];
    for ($x = 0; $x < $width; ++$x) {
      $light = $columnLight[$x] * $rowLight;
      $rightWeight = ($x - $rightStart + 0.5) / (2 * $seam);
      $leftIndex = ($y * $layerWidth + $x) * 3;
      $rightIndex = ($y * $layerWidth + $x - $rightStart) * 3;
      $logoColumn = $x - $logoLeft;
      $logoIndex = $logoRowStart >= 0 && $logoColumn >= 0 && $logoColumn < $logo["width"] ? ($logoRowStart + $logoColumn) * 4 : -1;
      $logoAlpha = $logoIndex >= 0 ? $logo["pixels"][$logoIndex + 3] / 255 : 0;
      for ($c = 0; $c < 3; ++$c) {
        if ($x < $rightStart) $value = $left[$leftIndex + $c];
        else if ($x >= $leftEnd) $value = $right[$rightIndex + $c];
        else $value = $left[$leftIndex + $c] + ($right[$rightIndex + $c] - $left[$leftIndex + $c]) * $rightWeight;
        $value *= $light;
        if ($logoAlpha > 0) $value += ($logo["pixels"][$logoIndex + $c] - $value) * $logoAlpha;
        $line[] = (int)($value + 0.5);
      }
    }
    $filtered = [];
    foreach ($line as $i => $value) $filtered[] = ($value - $previousLine[$i]) & 255;
    $scanlines .= "\2" . pack("C*", ...$filtered);
    $previousLine = $line;
  }
  return EncodeSharedReplayPng($width, $height, $scanlines);
}

$metadata = SharedReplayMetadata((string)($_GET["token"] ?? ""));
if ($metadata === null || ($metadata["type"] ?? "") === "snapshot") {
  http_response_code(404);
  exit;
}

$p1Hero = trim((string)($metadata["p1HeroCardId"] ?? ""));
$p2Hero = trim((string)($metadata["p2HeroCardId"] ?? ""));
$cacheDirectory = "../Replays/shared/previews/";
$cacheFile = $cacheDirectory . md5(SHARED_REPLAY_IMAGE_VERSION . "|$p1Hero|$p2Hero") . ".png";
$png = is_file($cacheFile) ? file_get_contents($cacheFile) : false;

if ($png === false) {
  $p1Art = LoadSharedReplayHeroArt($p1Hero);
  $p2Art = LoadSharedReplayHeroArt($p2Hero);
  $logo = DecodeSharedReplayPng((string)@file_get_contents(__DIR__ . "/../Images/TalisharLogo.png"));
  $png = RenderSharedReplayImage($p1Art, $p2Art, $logo);
  if ($p1Art !== null && $p2Art !== null && (is_dir($cacheDirectory) || @mkdir($cacheDirectory, 0700, true))) {
    $tempFile = $cacheFile . "." . bin2hex(random_bytes(4)) . ".tmp";
    if (file_put_contents($tempFile, $png) !== false) @rename($tempFile, $cacheFile);
    else @unlink($tempFile);
  }
}

header("Content-Type: image/png");
header("Cache-Control: public, max-age=86400");
echo $png;
