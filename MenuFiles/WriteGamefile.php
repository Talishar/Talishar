<?php

  //$filename = "./Games/" . $gameName . "/GameFile.txt";
  //$gameFileWriteHandler = fopen($filename, "w");

  if(!function_exists("WriteGameFile"))
  {
    function WriteGameFile()
    {
      global $gameFileHandler;
      global $p1Data, $p2Data, $gameStatus, $format, $visibility, $firstPlayerChooser, $firstPlayer, $p1Key, $p2Key, $p1uid, $p2uid, $p1id, $p2id;
      global $gameDescription, $hostIP, $p1IsPatron, $p2IsPatron;
      rewind($gameFileHandler);
      fwrite($gameFileHandler, implode(" ", $p1Data) . "\r\n");
      fwrite($gameFileHandler, implode(" ", $p2Data) . "\r\n");
      fwrite($gameFileHandler, $gameStatus . "\r\n");
      fwrite($gameFileHandler, $format . "\r\n");
      fwrite($gameFileHandler, $visibility . "\r\n");
      fwrite($gameFileHandler, $firstPlayerChooser . "\r\n");
      fwrite($gameFileHandler, $firstPlayer . "\r\n");
      fwrite($gameFileHandler, $p1Key . "\r\n");
      fwrite($gameFileHandler, $p2Key . "\r\n");
      fwrite($gameFileHandler, $p1uid . "\r\n");
      fwrite($gameFileHandler, $p2uid . "\r\n");
      fwrite($gameFileHandler, $p1id . "\r\n");
      fwrite($gameFileHandler, $p2id . "\r\n");
      fwrite($gameFileHandler, $gameDescription . "\r\n");
      fwrite($gameFileHandler, $hostIP . "\r\n");
      fwrite($gameFileHandler, $p1IsPatron . "\r\n");
      fwrite($gameFileHandler, $p2IsPatron . "\r\n");
      fclose($gameFileHandler);
    }
  }

?>
