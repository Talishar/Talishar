<?php

// Paste card image URLs here to force their full card, square (concat), and crop
// images to be generated when this script or zzCardCodeGenerator.php is run.
$manualImageURLs = [
  //"https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large/IAR254.webp",
];

function CheckImage($setID, $cardID, $isDuplicate=false, $manualImageURL="")
{
  $set = substr($setID, 0, 3);
  $number = substr($setID, 3);
  $cardImagesUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardimages/english/" . $cardID . ".webp"; // !! CardImages/ to be changed for your own folder name
  $cardImagesMissingFolder = __DIR__ . "/../CardImages/media/missing/cardimages/english/" . $cardID . ".webp"; // !! CardImages/ to be changed for your own folder name
  if(!file_exists($cardImagesUploadedFolder))
  {
    if($manualImageURL != "") $imageURL = $manualImageURL;
    else if($isDuplicate) $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . ".webp";
    else if($number >= 400 && $set == "UPR") $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_A_Back.webp";
    else if($number >= 400 && $set == "DYN") $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_Back.webp";
    else if($number >= 400) $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_BACK.webp";
    else $imageURL = "https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large/$setID.webp";
    // else $imageURL = "https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large/$setID-RF.webp";
      
    echo("Image for " . $cardID . " does not exist.<BR>");
    $handler = fopen($cardImagesUploadedFolder, "w");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imageURL);
    curl_setopt($ch, CURLOPT_FILE, $handler);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    if(defined('CURLSSLOPT_NATIVE_CA')) curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);
    $downloadSucceeded = curl_exec($ch);
    if (!$downloadSucceeded && $manualImageURL == "") {
      fclose($handler);
      $handler = fopen($cardImagesUploadedFolder, "w");
      $imageURLRF = "https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large/$setID-RF.webp";
      curl_setopt($ch, CURLOPT_URL, $imageURLRF);
      curl_setopt($ch, CURLOPT_FILE, $handler);
      $downloadSucceeded = curl_exec($ch);
    }
    $downloadError = curl_error($ch);
    curl_close($ch);
    fclose($handler);
    clearstatcache(true, $cardImagesUploadedFolder);
    if(!file_exists($cardImagesUploadedFolder) || filesize($cardImagesUploadedFolder) < 10000) {
      if(file_exists($cardImagesUploadedFolder)) unlink($cardImagesUploadedFolder);
      echo("Unable to download a valid image from " . htmlspecialchars($imageURL) . ". " . htmlspecialchars($downloadError) . "<BR>");
      return;
    }
    if(file_exists($cardImagesUploadedFolder))
    {
      $image = imagecreatefromwebp($cardImagesUploadedFolder);
      $image = imagescale($image, 450, 628);
      imagewebp($image, $cardImagesUploadedFolder);
      if(!file_exists($cardImagesMissingFolder)) imagewebp($image, $cardImagesMissingFolder);
    }
  }
  $cardSquaresUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardsquares/english/" . $cardID . ".webp"; // !! CardImages/ to be changed for your own folder name
  $cardSquaresMissingFolder = __DIR__ . "/../CardImages/media/missing/cardsquares/english/" . $cardID . ".webp"; // !! CardImages/ to be changed for your own folder name
  if(!file_exists($cardSquaresUploadedFolder))
  {
    echo("Concat image for " . $cardID . " does not exist.<BR>");
    if(file_exists($cardImagesUploadedFolder))
    {
      $image = imagecreatefromwebp($cardImagesUploadedFolder);
      $imageTop = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 450, 'height' => 372]);
      $imageBottom = imagecrop($image, ['x' => 0, 'y' => 550, 'width' => 450, 'height' => 628]);

      $dest = imagecreatetruecolor(450, 450);
      imagecopy($dest, $imageTop, 0, 0, 0, 0, 450, 372);
      imagecopy($dest, $imageBottom, 0, 373, 0, 0, 450, 78);

      if(!file_exists($cardSquaresUploadedFolder)) imagewebp($dest, $cardSquaresMissingFolder);
    }
  }
  $cardCropsUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/crops/" . $cardID . "_cropped.webp"; // !! CardImages/ to be changed for your own folder name
  $cardCropsMissingFolder = __DIR__ . "/../CardImages/media/missing/crops/" . $cardID . "_cropped.webp"; // !! CardImages/ to be changed for your own folder name
  if(!file_exists($cardCropsUploadedFolder))
  {
    echo("Crop image for " . $cardID . " does not exist.<BR>");
    if(file_exists($cardImagesUploadedFolder))
    {
      echo("Attempting to convert image for " . $cardID . " to crops.<BR>");
      $image = imagecreatefromwebp($cardImagesUploadedFolder);
      $image = imagecrop($image, ['x' => 50, 'y' => 100, 'width' => 350, 'height' => 270]);
      if(!file_exists($cardCropsUploadedFolder)) imagewebp($image, $cardCropsMissingFolder);
    }
  }
}

function NormalizeCardBackID($id)
{
  if($id < 400) return $id;
  $newId = $id - 400;
  $str = $newId;
  if($newId < 100) $str = "0" . $str;
  if($newId < 10) $str = "0" . $str;
  return $str;
}

function RunManualImageDownload($input, $outputCardID="")
{
  $setID = strtoupper(trim($input));
  $imageURL = "";

  if(filter_var($input, FILTER_VALIDATE_URL)) {
    $urlParts = parse_url($input);
    $allowedHost = "legendstory-production-s3-public.s3.amazonaws.com";
    if(($urlParts['scheme'] ?? '') != 'https' || ($urlParts['host'] ?? '') != $allowedHost) {
      throw new InvalidArgumentException("Only HTTPS card images from $allowedHost are supported.");
    }
    $filename = pathinfo($urlParts['path'] ?? '', PATHINFO_FILENAME);
    if(!preg_match('/^([A-Z]{3}[0-9]{3})(?:-[A-Z0-9]+)?$/i', $filename, $matches)) {
      throw new InvalidArgumentException("The URL filename must begin with a set ID such as IAR254.");
    }
    $setID = strtoupper($matches[1]);
    $imageURL = $input;
  }

  if(!preg_match('/^[A-Z]{3}[0-9]{3}$/', $setID)) {
    throw new InvalidArgumentException("Use a set ID such as IAR254, or a full LSS card-image URL.");
  }

  if($outputCardID == "") $outputCardID = $setID;
  if(!preg_match('/^[A-Za-z0-9_-]+$/', $outputCardID)) {
    throw new InvalidArgumentException("The output card ID may only contain letters, numbers, underscores, and hyphens.");
  }

  CheckImage($setID, $outputCardID, false, $imageURL);

  // Normal generator downloads are staged in media/missing. A manual import is
  // explicitly requested, so also put every generated variant in uploaded/public.
  $generatedFiles = [
    __DIR__ . "/../CardImages/media/missing/cardimages/english/$outputCardID.webp" =>
      __DIR__ . "/../CardImages/media/uploaded/public/cardimages/english/$outputCardID.webp",
    __DIR__ . "/../CardImages/media/missing/cardsquares/english/$outputCardID.webp" =>
      __DIR__ . "/../CardImages/media/uploaded/public/cardsquares/english/$outputCardID.webp",
    __DIR__ . "/../CardImages/media/missing/crops/{$outputCardID}_cropped.webp" =>
      __DIR__ . "/../CardImages/media/uploaded/public/crops/{$outputCardID}_cropped.webp",
  ];
  foreach($generatedFiles as $source => $destination) {
    if(file_exists($source) && !file_exists($destination)) copy($source, $destination);
  }
}

foreach($manualImageURLs as $manualImageURL) RunManualImageDownload($manualImageURL);

// Run this file directly to force an image download. Including it from the card
// code generator continues to only define the functions above.
if(realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
  try {
    if(PHP_SAPI === 'cli') {
      $input = $argv[1] ?? '';
      $outputCardID = $argv[2] ?? '';
    }
    else {
      $input = $_GET['url'] ?? ($_GET['cardID'] ?? '');
      $outputCardID = $_GET['outputCardID'] ?? '';
    }

    if($input == '') {
      if(count($manualImageURLs) > 0) exit(0);
      echo "Usage: php zzImageConverter.php IAR254 [outputCardID]\n";
      echo "   or: php zzImageConverter.php https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large/IAR254.webp [outputCardID]\n";
      exit(1);
    }

    RunManualImageDownload($input, $outputCardID);
  }
  catch(Throwable $error) {
    echo "Error: " . $error->getMessage() . (PHP_SAPI === 'cli' ? "\n" : "<BR>");
    exit(1);
  }
}
