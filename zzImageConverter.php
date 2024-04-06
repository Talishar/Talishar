<?php

function CheckImage($cardID, $isDuplicate=false)
{
  $set = substr($cardID, 0, 3);
  $number = substr($cardID, 3);
  $filename = "./WebpImages/" . $cardID . ".webp";
  $filenameNew = "./New Cards/" . $cardID . ".webp";
  $filename2 = "../Talishar-FE/public/cardimages/" . $cardID . ".webp"; // !! Talishar-FE/ to be changed for your own folder name
  if(!file_exists($filename) || !file_exists($filename2))
  {
    //$imageURL = "https://fabrary.net/images/cards/" . $cardID . ".webp";
    //$imageURL = "https://fabrary.net/images/cards/" . $cardID . ".width-450.webp";
    if($isDuplicate) $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . ".webp";
    else if($number >= 400) $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $set . NormalizeCardBackID($number) . "_BACK.webp";
    else $imageURL= "https://d2h5owxb2ypf43.cloudfront.net/cards/" . $cardID . ".webp";
    //$imageURL = "https://dhhim4ltzu1pj.cloudfront.net/media/images/" . $cardID . "_yajPa8R.width-450.format-webp.webp";
    echo("Image for " . $cardID . " does not exist.<BR>");
    $handler = fopen($filename, "w");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imageURL);
    curl_setopt($ch, CURLOPT_FILE, $handler);
    curl_exec($ch);
    curl_close($ch);
    if(filesize($filename) < 10000) { unlink($filename); return; }
    if(file_exists($filename)) echo("Image for " . $cardID . " successfully retrieved.<BR>");
    if(file_exists($filename))
    {
      echo("Normalizing file size for " . $cardID . ".<BR>");
      $image = imagecreatefromwebp($filename);
      $image = imagescale($image, 450, 628);
      imagewebp($image, $filename);
      if(!file_exists($filename2)) imagewebp($image, $filename2);
      if(!file_exists($filenameNew)) imagewebp($image, $filenameNew);
      // Free up memory
      imagedestroy($image);
    }
  }
  $concatFilename = "./concat/" . $cardID . ".webp";
  $concatFilename2 = "../Talishar-FE/public/cardsquares/" . $cardID . ".webp"; // !! Talishar-FE/ to be changed for your own folder name
  if(!file_exists($concatFilename) || !file_exists($concatFilename2))
  {
    echo("Concat image for " . $cardID . " does not exist.<BR>");
    if(file_exists($filename))
    {
      echo("Attempting to convert image for " . $cardID . " to concat.<BR>");
      $image = imagecreatefromwebp($filename);
      $imageTop = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 450, 'height' => 372]);
      $imageBottom = imagecrop($image, ['x' => 0, 'y' => 550, 'width' => 450, 'height' => 628]);

      $dest = imagecreatetruecolor(450, 450);
      imagecopy($dest, $imageTop, 0, 0, 0, 0, 450, 372);
      imagecopy($dest, $imageBottom, 0, 373, 0, 0, 450, 78);

      if(!file_exists($concatFilename)) imagewebp($dest, $concatFilename);
      if(!file_exists($concatFilename2)) imagewebp($dest, $concatFilename2);
      // Free up memory
      imagedestroy($image);
      imagedestroy($dest);
      imagedestroy($imageTop);
      imagedestroy($imageBottom);
      if(file_exists($concatFilename)) echo("Image for " . $cardID . " successfully converted to concat.<BR>");
    }
  }
  $cropFilename = "./crops/" . $cardID . "_cropped.png";
  $cropFilename2 = "../Talishar-FE/public/crops/" . $cardID . "_cropped.png"; // !! Talishar-FE/ to be changed for your own folder name
  if(!file_exists($cropFilename) || !file_exists($cropFilename2))
  {
    echo("Crop image for " . $cardID . " does not exist.<BR>");
    if(file_exists($filename))
    {
      echo("Attempting to convert image for " . $cardID . " to crops.<BR>");
      $image = imagecreatefromwebp($filename);
      $image = imagecrop($image, ['x' => 50, 'y' => 100, 'width' => 350, 'height' => 270]);
      if(!file_exists($cropFilename)) imagepng($image, $cropFilename);
      if(!file_exists($cropFilename2)) imagepng($image, $cropFilename2);
      imagedestroy($image);
      if(file_exists($cropFilename)) echo("Image for " . $cardID . " successfully converted to crops.<BR>");
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

?>
