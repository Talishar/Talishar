<?php

define('ROOTPATH', __DIR__);

$path = ROOTPATH . "/CardImages";
//$destPath = ROOTPATH . "/BigCardImages";
$destPath = ROOTPATH . "/SmallCardImages";

if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
      if ('.' === $file) continue;
      if ('..' === $file) continue;
      if(substr($file, -4) == ".png" || substr($file, -4) == ".PNG")
      {
        if(mime_content_type($path . "/" . $file) == "image/png") $image = imagecreatefrompng($path . "/" . $file);
        else $image = imagecreatefromjpeg($path . "/" . $file);
        if(!$image) { echo($file . " failed"); continue; }
        //$img = imagescale($image, 300);
        $img = imagescale($image, 150);
        $destFile = $destPath . "/" . substr($file, 0, -3) . "jpg";
        imagejpeg($img, $destFile, 75);
      }
    }
    closedir($handle);
}


?>

