<?php

  //$filename = "./Games/" . $gameName . "/GameFile.txt";
  //$gameFileWriteHandler = fopen($filename, "w");

  if(!function_exists("WriteGameFile"))
  {
    function WriteGameFile()
    {
      global $gameFileHandler;
      global $p1Data, $p2Data, $gameStatus, $format, $visibility, $firstPlayerChooser, $firstPlayer, $p1Key, $p2Key, $p1uid, $p2uid;
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
      fclose($gameFileHandler);
    }
  }

?>
