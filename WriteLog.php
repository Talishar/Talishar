<?php

function WriteLog($text, $playerColor = 0, $highlight=false, $path="./")
{
  global $gameName;
  $filename = "{$path}Games/$gameName/gamelog.txt";
  if(file_exists($filename)) $handler = fopen($filename, "a");
  else return; //File does not exist
  if($highlight) $output = ($playerColor != 0 ? "<span style='color:<PLAYER{$playerColor}COLOR>;'>" : "") . "<p style='background: brown;font-size: max(1em, 14px);margin-bottom:0px;'><span style='color:azure;'>" . $text . "</span></p>" . ($playerColor != 0 ? "</span>" : "");
  else $output = ($playerColor != 0 ? "<span style='color:<PLAYER{$playerColor}COLOR>;'>" : "") . $text . ($playerColor != 0 ? "</span>" : "");
  fwrite($handler, "$output\r\n");
  fclose($handler);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    $filename = "{$path}Games/$gameName/fullGamelog.txt";
    $handler = fopen($filename, "a");
    fwrite($handler, "$output\r\n");
    fclose($handler);
  }
}

function ClearLog($n=50)
{
  global $gameName;

  $filename = "./Games/$gameName/gamelog.txt";
  $handle = fopen("./Games/$gameName/gamelog.txt", "r");
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
  WriteLog("ERROR: $text");
}

function EchoLog($gameName, $playerID)
{
  $filename = "./Games/$gameName/gamelog.txt";
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

function JSONLog($gameName, $playerID, $path="./")
{
  $response = "";
  $filename = "{$path}Games/$gameName/gamelog.txt";
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
    $response = $line;
    fclose($handler);
  }
  return $response;
}
