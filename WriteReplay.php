<?php

{
  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $stepCounter;
  function WriteReplay($playerID, $cardID, $from, $destination)
  {
    global $gameName, $stepCounter;
    $filename = "./Games/" . $gameName . "/Replay.txt";
    $handler = fopen($filename, "a+");

  $output = ($stepCounter . " ID" . $playerID . " " . $cardID . " ". $from . " ". $destination);


    fwrite($handler, $output . "\r\n");
    fclose($handler);
  }

  function StartReplay()
  {
        global $gameName;
        $filename = "./Games/" . $gameName . "/Replay.txt";

        $handler = fopen($filename, "a+");


            fwrite($handler, "replay start" );
            fclose($handler);

  }
  //echo(file_get_contents(  $filename = "./Games/" . $gameName . "/Replay.txt"));

}

?>
