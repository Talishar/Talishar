<?php

/**
 * Folder names under cardimages/, cardsquares/, and crops/ => Legend Story S3 path segment under .../media/cards/large/
 * Empty string = default (no extra segment): .../large/{setID}.webp
 */
$ZZ_IMAGE_CONVERTER_LANGUAGES = [
  'english' => '',
  'japanese' => 'JA',
  'french' => 'FR',
];

function LegendStoryLargeCardImageUrl($setID, $countryCode)
{
  $base = 'https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large';
  if ($countryCode !== '') {
    return $base . '/' . $countryCode . '/' . $setID . '.webp';
  }
  return $base . '/' . $setID . '.webp';
}

function CheckImage($setID, $cardID, $isDuplicate = false)
{
  global $ZZ_IMAGE_CONVERTER_LANGUAGES;
  foreach ($ZZ_IMAGE_CONVERTER_LANGUAGES as $langFolder => $countryCode) {
    CheckImageForLanguage($setID, $cardID, $isDuplicate, $langFolder, $countryCode);
  }
}

function CheckImageForLanguage($setID, $cardID, $isDuplicate, $langFolder, $countryCode)
{
  $set = substr($setID, 0, 3);
  $number = substr($setID, 3);
  $cardImagesUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardimages/{$langFolder}/" . $cardID . ".webp";
  $cardImagesMissingFolder = __DIR__ . "/../CardImages/media/missing/cardimages/{$langFolder}/" . $cardID . ".webp";
  if (!file_exists($cardImagesUploadedFolder)) {
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
    }

    echo("Image for " . $cardID . " ({$langFolder}) does not exist.<BR>");
    $handler = fopen($cardImagesUploadedFolder, "w");
    if ($handler === false) {
      return;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imageURL);
    curl_setopt($ch, CURLOPT_FILE, $handler);
    curl_exec($ch);
    curl_close($ch);
    fclose($handler);
    if (!file_exists($cardImagesUploadedFolder) || filesize($cardImagesUploadedFolder) < 10000) {
      if (file_exists($cardImagesUploadedFolder)) {
        unlink($cardImagesUploadedFolder);
      }
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
    echo("Concat image for " . $cardID . " ({$langFolder}) does not exist.<BR>");
    if (file_exists($cardImagesUploadedFolder)) {
      $image = imagecreatefromwebp($cardImagesUploadedFolder);
      $imageTop = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 450, 'height' => 372]);
      $imageBottom = imagecrop($image, ['x' => 0, 'y' => 550, 'width' => 450, 'height' => 628]);

      $dest = imagecreatetruecolor(450, 450);
      imagecopy($dest, $imageTop, 0, 0, 0, 0, 450, 372);
      imagecopy($dest, $imageBottom, 0, 373, 0, 0, 450, 78);

      if (!file_exists($cardSquaresUploadedFolder)) {
        imagewebp($dest, $cardSquaresMissingFolder);
      }
    }
  }
  $cardCropsUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/crops/{$langFolder}/" . $cardID . "_cropped.webp";
  $cardCropsMissingFolder = __DIR__ . "/../CardImages/media/missing/crops/{$langFolder}/" . $cardID . "_cropped.webp";
  if (!file_exists($cardCropsUploadedFolder)) {
    echo("Crop image for " . $cardID . " ({$langFolder}) does not exist.<BR>");
    if (file_exists($cardImagesUploadedFolder)) {
      echo("Attempting to convert image for " . $cardID . " to crops.<BR>");
      $image = imagecreatefromwebp($cardImagesUploadedFolder);
      $image = imagecrop($image, ['x' => 50, 'y' => 100, 'width' => 350, 'height' => 270]);
      if (!file_exists($cardCropsUploadedFolder)) {
        imagewebp($image, $cardCropsMissingFolder);
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
