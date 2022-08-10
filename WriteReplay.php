<?php

  //$stepCounter;
  function WriteReplay($playerID, $cardID, $from, $destination)
  {
    global $animations;
    array_push($animations, $playerID);
    array_push($animations, $cardID);
    array_push($animations, $from);
    array_push($animations, $destination);
  }

  function StartReplay()
  {
    /*
        global $gameName;
        $filename = "./Games/" . $gameName . "/Replay.txt";

        $handler = fopen($filename, "a+");


            fwrite($handler, "replay start" );
            fclose($handler);
*/
  }
  //echo(file_get_contents(  $filename = "./Games/" . $gameName . "/Replay.txt"));
?>
