<?php

function WriteLog($text, $playerColor = 0, $highlight=false, $path="./", $highlightColor="brown")
{
  global $gameName;
  $filename = "{$path}Games/$gameName/gamelog.txt";
  if(file_exists($filename)) $handler = fopen($filename, "a");
  else return; //File does not exist
  $playerSpan = ($playerColor != 0 ? "<span style='color:<PLAYER{$playerColor}COLOR>;'>" : "");
  $playerSpanClose = ($playerColor != 0 ? "</span>" : "");
  if($highlight) $output = $playerSpan . "<p style='background: $highlightColor;font-size: max(1em, 14px);margin-bottom:0px;'><span style='color:azure;'>" . $text . "</span></p>" . $playerSpanClose;
  else $output = $playerSpan . $text . $playerSpanClose;
  fwrite($handler, "$output\r\n");
  fclose($handler);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    $filename = "{$path}Games/$gameName/fullGamelog.txt";
    $handler = fopen($filename, "a");
    fwrite($handler, "$output\r\n");
    fclose($handler);
  }
}

function ClearLog($n=30)
{
  global $gameName;

  $filename = "./Games/$gameName/gamelog.txt";
  $handle = fopen("./Games/$gameName/gamelog.txt", "r");
  $lines = [];
  if ($handle) {
    while (!feof($handle)) {
        $lines[] = fgets($handle);
    }
    fclose($handle);
    $lines = array_slice($lines, -$n);
  }

  $handle = fopen($filename, "w");
  fwrite($handle, implode("", $lines));
  fclose($handle);

}

function WriteError($text)
{
  WriteLog("ERROR: $text");
}

function WriteSystemMessage($text, $path="./")
{
  global $gameName;
  $filename = "{$path}Games/$gameName/gamelog.txt";
  if(file_exists($filename)) $handler = fopen($filename, "a");
  else return; //File does not exist
  fwrite($handler, "$text\r\n");
  fclose($handler);
  if(function_exists("GetSettings") && (IsPatron(1) || IsPatron(2))) {
    $filename = "{$path}Games/$gameName/fullGamelog.txt";
    $handler = fopen($filename, "a");
    fwrite($handler, "$text\r\n");
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
    $line = fread($handler, $filesize);
    fclose($handler);
    $red = "#cb0202";
    $blue = "#128ee5";
    $player1Color = $playerID == 1 || $playerID == 3 ? $blue : $red;
    $player2Color = $playerID == 2 ? $blue : $red;
    $response = str_replace(["\r\n", "<PLAYER1COLOR>", "<PLAYER2COLOR>"], ["<br>", $player1Color, $player2Color], $line);
  }
  return $response;
}
