<?php

function WriteLog($text, $playerColor = 0)
{
  global $gameName;
  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $handler = fopen($filename, "a");
  $output = ($playerColor != 0 ? "<span style='color:<PLAYER" . $playerColor . "COLOR>;'>" : "") . $text . ($playerColor != 0 ? "</span>" : "");
  fwrite($handler, $output . "\r\n");
  fclose($handler);
  /*TODO: persistent chat
  $handler = fopen("./Games/" . $gameName . "/p1gamelog.txt", "a");
  fwrite($handler, $output . "\r\n");
  fclose($handler);

  $handler = fopen("./Games/" . $gameName . "/p2gamelog.txt", "a");
  fwrite($handler, $output . "\r\n");
  fclose($handler);
  */
}

function ClearLog()
{
  global $gameName;
  /*
  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $handler = fopen($filename, "w");
  fclose($handler);
  */

  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $n = 20;
  $handle = fopen("./Games/" . $gameName . "/gamelog.txt", "r");
  $lines = array_fill(0, $n-1, '');
  if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle);
        array_push($lines, $buffer);
        array_shift($lines);
    }
    fclose($handle);
  }

  $handle = fopen($filename, "w");
  fwrite($handle, implode("", $lines));
  fclose($handle);

}

function WriteError($text)
{
  WriteLog("ERROR: " . $text);
}

function EchoLog($gameName, $playerID)
{
  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $filesize = filesize($filename);
  if ($filesize > 0) {
    $handler = fopen($filename, "r");
    $line = str_replace("\r\n", "<br>", fread($handler, $filesize));
    //$line = str_replace("<PLAYER1COLOR>", $playerID==1 ? "Blue" : "Red", $line);
    //$line = str_replace("<PLAYER2COLOR>", $playerID==2 ? "Blue" : "Red", $line);
    $red = "#cb0202";
    $blue = "#128ee5";
    $line = str_replace("<PLAYER1COLOR>", $playerID == 1 || $playerID == 3 ? $blue : $red, $line);
    $line = str_replace("<PLAYER2COLOR>", $playerID == 2 ? $blue : $red, $line);
    echo ($line);
    fclose($handler);
  }
}
