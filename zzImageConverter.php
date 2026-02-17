<?php

function CheckImage($setID, $cardID, $isDuplicate=false)
{
  $set = substr($setID, 0, 3);
  $number = substr($setID, 3);
  $cardImagesUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardimages/english/" . $cardID . ".webp"; // !! CardImages/ to be changed for your own folder name
  $cardImagesMissingFolder = __DIR__ . "/../CardImages/media/missing/cardimages/english/" . $cardID . ".webp"; // !! CardImages/ to be changed for your own folder name
  if(!file_exists($cardImagesUploadedFolder))
  {
    if($isDuplicate) $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . ".webp";
    else if($number >= 400 && $set == "UPR") $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_A_Back.webp";
    else if($number >= 400 && $set == "DYN") $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_Back.webp";
    else if($number >= 400) $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_BACK.webp";
    else $imageURL = "https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large/$setID.webp";
    //else $imageURL = "https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large/$setID-RF.webp";
      
    echo("Image for " . $cardID . " does not exist.<BR>");
    $handler = fopen($cardImagesUploadedFolder, "w");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imageURL);
    curl_setopt($ch, CURLOPT_FILE, $handler);
    curl_exec($ch);
    if(filesize($cardImagesUploadedFolder) < 10000) { unlink($cardImagesUploadedFolder); return; }
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
  $cardCropsUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/crops/" . $cardID . "_cropped.png"; // !! CardImages/ to be changed for your own folder name
  $cardCropsMissingFolder = __DIR__ . "/../CardImages/media/missing/crops/" . $cardID . "_cropped.png"; // !! CardImages/ to be changed for your own folder name
  if(!file_exists($cardCropsUploadedFolder))
  {
    echo("Crop image for " . $cardID . " does not exist.<BR>");
    if(file_exists($cardImagesUploadedFolder))
    {
      echo("Attempting to convert image for " . $cardID . " to crops.<BR>");
      $image = imagecreatefromwebp($cardImagesUploadedFolder);
      $image = imagecrop($image, ['x' => 50, 'y' => 100, 'width' => 350, 'height' => 270]);
      if(!file_exists($cardCropsUploadedFolder)) imagepng($image, $cardCropsMissingFolder);
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
