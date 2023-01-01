<?php

include 'GameLogic.php';

$set = "DYN";
$count = 0;
for ($number = 0; $number < 247; ++$number) {
  if($number == 65) continue;//Tiger (ignore no attack)
  if($number == 191) continue;//Runechant
  if($number == 216) continue;//Spectral Procession (ignore no attack)
  if($number == 233) continue;
  if($number == 245) continue;
  $card = strval($number);
  if ($number < 10) $card = "0" . $card;
  if ($number < 100) $card = "0" . $card;
  $card = $set . $card;
  $type = CardType($card);
  if ($type == "") {
    echo ($card . " not found.<br>");
    ++$count;
  }
  if($type == "AR" && AttackValue($card) != 0) echo($card . " Attack Reaction has attack " . AttackValue($card) . ".<BR>");
  if ($type == "AA" && $card != "EVR138" && AttackValue($card) == 0) echo ($card . " Attack action has no attack.<br>");
  if (($type != "C" && $type != "E" && $type != "W" && $type != "T") && PitchValue($card) == 0) echo ($card . " has no pitch value.<br>");
  CheckImage($card);
}
echo ("Total missing: " . $count);

function CheckImage($cardID)
{
  $filename = "./WebpImages/" . $cardID . ".webp";
  if(!file_exists($filename))
  {
    $imageURL = "https://fabrary.net/images/cards/" . $cardID . ".webp";
    echo("Image for " . $cardID . " does not exist.<BR>");
    $handler = fopen($filename, "w");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imageURL);
    curl_setopt($ch, CURLOPT_FILE, $handler);
    curl_exec($ch);
    curl_close($ch);
    if(file_exists($filename)) echo("Image for " . $cardID . " successfully retrieved.<BR>");
    if(file_exists($filename))
    {
      echo("Normalizing file size for " . $cardID . ".<BR>");
      $image = imagecreatefromwebp($filename);
      $image = imagescale($image, 450, 628);
      imagewebp($image, $filename);
      // Free up memory
      imagedestroy($image);
    }
  }
  $concatFilename = "./concat/" . $cardID . ".webp";
  if(!file_exists($concatFilename))
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

      imagewebp($dest, $concatFilename);
      // Free up memory
      imagedestroy($image);
      imagedestroy($dest);
      imagedestroy($imageTop);
      imagedestroy($imageBottom);
      if(file_exists($concatFilename)) echo("Image for " . $cardID . " successfully converted to concat.<BR>");
    }
  }
  $cropFilename = "./crops/" . $cardID . "_cropped.png";
  if(!file_exists($cropFilename))
  {
    echo("Crop image for " . $cardID . " does not exist.<BR>");
    if(file_exists($filename))
    {
      echo("Attempting to convert image for " . $cardID . " to crops.<BR>");
      $image = imagecreatefromwebp($filename);
      $image = imagecrop($image, ['x' => 50, 'y' => 100, 'width' => 350, 'height' => 270]);
      imagepng($image, $cropFilename);
      imagedestroy($image);
      if(file_exists($cropFilename)) echo("Image for " . $cardID . " successfully converted to crops.<BR>");
    }
  }
}

?>
