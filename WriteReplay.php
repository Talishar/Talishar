<?php

  $stepCounter;

  function WriteReplay($playerID, $cardID, $from, $destination)
  {
    global $gameName, $stepCounter;
    $filename = "./Games/" . $gameName . "/Replay.txt";
    $handler = fopen($filename, "a+");
    if(file_get_contents($filename, true, null, 0, 10)[0]==1)
    {
        $stepCounter = 0;
    }
    else
    {
        $stepCounter = $stepCounter+1;
    }
    $output = ($stepCounter . " ID" . $playerID . " " . $cardID . " ". $from . " ". $destination);
    fwrite($handler, $output . "\r\n");
    fclose($handler);
  }

?>
