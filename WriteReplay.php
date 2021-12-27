<?php

  $stepCounter = 0;

  function WriteReplay($playerID, $cardID, $from, $destination)
  {
    global $gameName, $stepCounter;
    $filename = "./Games/" . $gameName . "/Replay.txt";
    $handler = fopen($filename, "a+");
    $stepCounter = $stepCounter+1;
    $output = ($stepCounter . "ID" . $playerID . " " . $cardID . " ". $from . " ". $destination);
    fwrite($handler, $output . "\r\n");
    fclose($handler);
  }

?>
