<?php

define('ROOTPATH', __DIR__);

$path = ROOTPATH . "/NewCards";
$path = ROOTPATH . "/CardImages";


//$destPath = ROOTPATH . "/TestOutput";

$width = 85; $height = 120;
$destPath = ROOTPATH . "/SmallCardImages";


//$width = 213; $height = 300;
//$destPath = ROOTPATH . "/BigCardImages";

if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
      if ('.' === $file) continue;
      if ('..' === $file) continue;
      if(substr($file, -4) == ".png" || substr($file, -4) == ".PNG")
      {
        if(mime_content_type($path . "/" . $file) == "image/png") $source = imagecreatefrompng($path . "/" . $file);
        else $source = imagecreatefromjpeg($path . "/" . $file);
        if(!$source) { echo($file . " failed"); continue; }
        //$source = imagescale($source, $width);
        $img=imagecreatetruecolor($width, $height);
        imagealphablending($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        imagecopyresampled($img,$source,0,0,0,0, $width,$height,imagesx($source),imagesy($source));

        //imagealphablending($img, false);

        // save the alpha
        imagesavealpha($img,true);
        //header('Content-type: image/png');
        $destFile = $destPath . "/" . substr($file, 0, -3) . "png";
        imagepng($img, $destFile, 9);

        //$destFile = $destPath . "/" . substr($file, 0, -3) . "jfif";
        //imagejpeg($img, $destFile, 75);
        //imagepng( $img );

        // dispose
        imagedestroy($img);

        //$img = imagescale($image, 300);
        //$img = imagescale($image, 129);
        //$destFile = $destPath . "/" . substr($file, 0, -3) . "jpg";
        //imagejpeg($img, $destFile, 75);

        //$destFile = $destPath . "/" . substr($file, 0, -3) . "png";
        //imagepng($img, $destFile, 1);
      }
    }
    closedir($handle);
}



$path = ROOTPATH . "/NewCards";
$destPath = ROOTPATH . "/BigCardImages";

if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
      if ('.' === $file) continue;
      if ('..' === $file) continue;
      if(substr($file, -4) == ".png" || substr($file, -4) == ".PNG")
      {
        if(mime_content_type($path . "/" . $file) == "image/png") $image = imagecreatefrompng($path . "/" . $file);
        else $image = imagecreatefromjpeg($path . "/" . $file);
        if(!$image) { echo($file . " failed"); continue; }
        $img = imagescale($image, 300);
        $destFile = $destPath . "/" . substr($file, 0, -3) . "jpg";
        imagejpeg($img, $destFile, 75);
      }
    }
    closedir($handle);
}



?>
