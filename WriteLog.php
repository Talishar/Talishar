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

  function WriteError($text)
  {
    WriteLog("ERROR: " . $text);
  }

  function EchoLog($gameName, $playerID)
  {
    $filename = "./Games/" . $gameName . "/gamelog.txt";
    $filesize = filesize($filename);
    if($filesize > 0)
    {
      $handler = fopen($filename, "r");
      $line = str_replace("\r\n", "<br>", fread($handler, $filesize));
      $line = str_replace("<PLAYER1COLOR>", $playerID==1 ? "Blue" : "Red", $line);
      $line = str_replace("<PLAYER2COLOR>", $playerID==2 ? "Blue" : "Red", $line);
      echo($line);
      fclose($handler);
    }
  }

?>

