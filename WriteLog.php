<?php

  function WriteLog($text)
  {
    global $gameName;
    $filename = "./Games/" . $gameName . "/gamelog.txt";
    $handler = fopen($filename, "a");
    fwrite($handler, $text . "\r\n");
    fclose($handler);
  }

?>

