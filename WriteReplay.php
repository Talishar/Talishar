<?php


  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $stepCounter;

  function WriteReplay($playerID, $cardID, $from, $destination)
  {
    global $gameName, $stepCounter;
    $filename = "./Games/" . $gameName . "/Replay.txt";
    $handler = fopen($filename, "a+");


    if(filesize($filename)==0)
    {
        $zoneTransitions=array();
        $stepCounter = 0;
    }
    $stepCounter = $stepCounter+1;

    $output = ($stepCounter . " ID" . $playerID . " " . $cardID . " ". $from . " ". $destination);
    array_push($zoneTransitions, $output);
    fwrite($handler, $output . "\r\n");
    fclose($handler);
  }
  echo(file_get_contents(  $filename = "./Games/" . $gameName . "/Replay.txt"));
}
?>
