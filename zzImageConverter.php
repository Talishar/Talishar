<?php

/**
 * Local folders: cardimages/{english|japanese|french|german|spanish|italian}/ — LSS large cards on S3:
 * English: .../large/{setID}.webp
 * Other:   .../large/{CC}_{setID}.webp  (e.g. JA_, FR_, DE_, ES_, IT_)
 *
 * Saved files are always named by Talishar cardID (e.g. absorb_in_aether_red.webp), matching FE URLs.
 *
 * For non-english face cards: if the localized LSS asset is missing, reuse the already saved
 * english/{cardID}.webp (no second download from the English LSS URL).
 *
 * Per-language folders under CardImages/media are created on first use (see ZzEnsureCardImageLanguageDirs).
 */
$ZZ_IMAGE_CONVERTER_LANGUAGES = [
  'english' => '',
  'japanese' => 'JA',
  'french' => 'FR',
  'german' => 'DE',
  'spanish' => 'ES',
  'italian' => 'IT',
];

if (PHP_SAPI === 'cli') {
  @ini_set('output_buffering', '0');
  @ini_set('zlib.output_compression', '0');
  if (function_exists('ob_implicit_flush')) {
    ob_implicit_flush(true);
  }
}

/** Browser: append BR. CLI: newline + flush. */
function zz_image_converter_log($message)
{
  if (PHP_SAPI === 'cli') {
    echo $message . PHP_EOL;
  } else {
    echo $message . '<BR>';
  }
  if (function_exists('flush')) {
    flush();
  }
}

function LegendStoryLargeCardImageUrl($setID, $countryCode)
{
  $base = 'https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large';
  if ($countryCode !== '') {
    return $base . '/' . $countryCode . '_' . $setID . '.webp';
  }
  return $base . '/' . $setID . '.webp';
}

/**
 * @return bool true if file exists and is at least 10000 bytes (same threshold as before)
 */
function DownloadLargeCardWebp($imageURL, $destPath)
{
  $handler = fopen($destPath, "w");
  if ($handler === false) {
    return false;
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $imageURL);
  curl_setopt($ch, CURLOPT_FILE, $handler);
  curl_exec($ch);
  curl_close($ch);
  fclose($handler);
  if (!file_exists($destPath) || filesize($destPath) < 10000) {
    if (file_exists($destPath)) {
      unlink($destPath);
    }
    return false;
  }
  return true;
}

/** @return string Absolute path to processed English card image (public/uploaded). */
function ZzEnglishCardImagePublicPath($cardID)
{
  return __DIR__ . "/../CardImages/media/uploaded/public/cardimages/english/" . $cardID . ".webp";
}

/** Ensure CardImages output dirs exist for one language (e.g. german, japanese). */
function ZzEnsureCardImageLanguageDirs($langFolder)
{
  static $ensured = [];
  if (isset($ensured[$langFolder])) {
    return;
  }
  $root = __DIR__ . "/../CardImages/media";
  $subdirs = [
    "uploaded/public/cardimages/{$langFolder}",
    "missing/cardimages/{$langFolder}",
    "uploaded/public/cardsquares/{$langFolder}",
    "missing/cardsquares/{$langFolder}",
    "uploaded/public/crops/{$langFolder}",
    "missing/crops/{$langFolder}",
  ];
  foreach ($subdirs as $rel) {
    $path = $root . "/" . $rel;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }
  }
  $ensured[$langFolder] = true;
}

function CheckImage($setID, $cardID, $isDuplicate = false)
{
  static $cliCheckImageCount = 0;
  if (PHP_SAPI === 'cli') {
    $cliCheckImageCount++;
    if ($cliCheckImageCount % 25 === 0) {
      zz_image_converter_log('[CheckImage] ' . $cliCheckImageCount . ' invocations (card × languages / variants)...');
    }
  }
  global $ZZ_IMAGE_CONVERTER_LANGUAGES;
  foreach ($ZZ_IMAGE_CONVERTER_LANGUAGES as $langFolder => $countryCode) {
    CheckImageForLanguage($setID, $cardID, $isDuplicate, $langFolder, $countryCode);
  }
}

function CheckImageForLanguage($setID, $cardID, $isDuplicate, $langFolder, $countryCode)
{
  ZzEnsureCardImageLanguageDirs($langFolder);
  $set = substr($setID, 0, 3);
  $number = substr($setID, 3);
  $cardImagesUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardimages/{$langFolder}/" . $cardID . ".webp";
  $cardImagesMissingFolder = __DIR__ . "/../CardImages/media/missing/cardimages/{$langFolder}/" . $cardID . ".webp";
  if (!file_exists($cardImagesUploadedFolder)) {
    $legendStoryFaceCard = false;
    if ($isDuplicate) {
      $imageURL = "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . ".webp";
    } else if ($number >= 400 && $set == "UPR") {
      $imageURL = "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_A_Back.webp";
    } else if ($number >= 400 && $set == "DYN") {
      $imageURL = "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_Back.webp";
    } else if ($number >= 400) {
      $imageURL = "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_BACK.webp";
    } else {
      $imageURL = LegendStoryLargeCardImageUrl($setID, $countryCode);
      $legendStoryFaceCard = true;
    }

    zz_image_converter_log("Image for " . $cardID . " ({$langFolder}) does not exist.");
    $ok = DownloadLargeCardWebp($imageURL, $cardImagesUploadedFolder);
    if (!$ok && $legendStoryFaceCard && $countryCode !== '') {
      $englishPath = ZzEnglishCardImagePublicPath($cardID);
      if (file_exists($englishPath) && filesize($englishPath) >= 10000) {
        zz_image_converter_log("Legend Story localized large card unavailable; copying saved English file for {$langFolder}: " . $cardID);
        $ok = copy($englishPath, $cardImagesUploadedFolder);
      } else {
        zz_image_converter_log("Legend Story localized large card failed; no saved English file yet for {$langFolder}: " . $cardID);
      }
    }
    if (!$ok) {
      return;
    }
    $image = imagecreatefromwebp($cardImagesUploadedFolder);
    $image = imagescale($image, 450, 628);
    imagewebp($image, $cardImagesUploadedFolder);
    if (!file_exists($cardImagesMissingFolder)) {
      imagewebp($image, $cardImagesMissingFolder);
    }
  }
  $cardSquaresUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardsquares/{$langFolder}/" . $cardID . ".webp";
  $cardSquaresMissingFolder = __DIR__ . "/../CardImages/media/missing/cardsquares/{$langFolder}/" . $cardID . ".webp";
  if (!file_exists($cardSquaresUploadedFolder)) {
    zz_image_converter_log("Concat image for " . $cardID . " ({$langFolder}) does not exist.");
    if (file_exists($cardImagesUploadedFolder)) {
      $image = imagecreatefromwebp($cardImagesUploadedFolder);
      $imageTop = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 450, 'height' => 372]);
      $imageBottom = imagecrop($image, ['x' => 0, 'y' => 550, 'width' => 450, 'height' => 628]);

      $dest = imagecreatetruecolor(450, 450);
      imagecopy($dest, $imageTop, 0, 0, 0, 0, 450, 372);
      imagecopy($dest, $imageBottom, 0, 373, 0, 0, 450, 78);

      if (!file_exists($cardSquaresUploadedFolder)) {
        imagewebp($dest, $cardSquaresUploadedFolder);
        if (!file_exists($cardSquaresMissingFolder)) {
          imagewebp($dest, $cardSquaresMissingFolder);
        }
      }
    }
  }
  $cardCropsUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/crops/{$langFolder}/" . $cardID . "_cropped.webp";
  $cardCropsMissingFolder = __DIR__ . "/../CardImages/media/missing/crops/{$langFolder}/" . $cardID . "_cropped.webp";
  if (!file_exists($cardCropsUploadedFolder)) {
    zz_image_converter_log("Crop image for " . $cardID . " ({$langFolder}) does not exist.");
    if (file_exists($cardImagesUploadedFolder)) {
      zz_image_converter_log("Attempting to convert image for " . $cardID . " to crops.");
      $image = imagecreatefromwebp($cardImagesUploadedFolder);
      $image = imagecrop($image, ['x' => 50, 'y' => 100, 'width' => 350, 'height' => 270]);
      if (!file_exists($cardCropsUploadedFolder)) {
        imagewebp($image, $cardCropsUploadedFolder);
        if (!file_exists($cardCropsMissingFolder)) {
          imagewebp($image, $cardCropsMissingFolder);
        }
      }
    }
  }
}

function NormalizeCardBackID($id)
{
  if ($id < 400) {
    return $id;
  }
  $newId = $id - 400;
  $str = $newId;
  if ($newId < 100) {
    $str = "0" . $str;
  }
  if ($newId < 10) {
    $str = "0" . $str;
  }
  return $str;
}
