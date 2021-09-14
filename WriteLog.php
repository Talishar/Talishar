<?php

  function WriteLog($text, $playerColor=0)
  {
    global $gameName;
    $filename = "./Games/" . $gameName . "/gamelog.txt";
    $handler = fopen($filename, "a");
    $output = ($playerColor != 0 ? "<span style='color:<PLAYER" . $playerColor . "COLOR>;'>" : "") . $text . ($playerColor != 0 ? "</span>" : "");
    fwrite($handler, $output . "\r\n");
    fclose($handler);
  }

?>

