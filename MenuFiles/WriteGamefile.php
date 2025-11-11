<?php

  //$filename = "./Games/" . $gameName . "/GameFile.txt";
  //$gameFileWriteHandler = fopen($filename, "w");

  if(!function_exists("WriteGameFile"))
  {
    function WriteGameFile()
    {
      global $gameFileHandler;
      global $p1Data, $p2Data, $gameStatus, $format, $visibility, $firstPlayerChooser, $firstPlayer, $p1Key, $p2Key, $p1uid, $p2uid, $p1id, $p2id;
      global $gameDescription, $hostIP, $p1IsPatron, $p2IsPatron, $p1DeckLink, $p2DeckLink;
      global $p1IsChallengeActive, $p2IsChallengeActive, $joinerIP, $p1deckbuilderID, $p2deckbuilderID, $roguelikeGameID;
      global $p1Matchups, $p2Matchups, $p1StartingHealth, $p1ContentCreatorID, $p2ContentCreatorID;
      global $p1SideboardSubmitted, $p2SideboardSubmitted, $p1IsAI, $p2IsAI, $gameGUID;
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
      fwrite($gameFileHandler, $p1DeckLink . "\r\n");
      fwrite($gameFileHandler, $p2DeckLink . "\r\n");
      fwrite($gameFileHandler, $p1IsChallengeActive . "\r\n");
      fwrite($gameFileHandler, $p2IsChallengeActive . "\r\n");
      fwrite($gameFileHandler, $joinerIP . "\r\n");
      fwrite($gameFileHandler, "\r\n");//Deprecated
      fwrite($gameFileHandler, json_encode($p1Matchups) . "\r\n");
      fwrite($gameFileHandler, json_encode($p2Matchups) . "\r\n");
      fwrite($gameFileHandler, $p1deckbuilderID . "\r\n");
      fwrite($gameFileHandler, $p2deckbuilderID . "\r\n");
      fwrite($gameFileHandler, $roguelikeGameID . "\r\n");
      fwrite($gameFileHandler, $p1StartingHealth . "\r\n");
      fwrite($gameFileHandler, $p1ContentCreatorID . "\r\n");
      fwrite($gameFileHandler, $p2ContentCreatorID . "\r\n");
      fwrite($gameFileHandler, $p1SideboardSubmitted . "\r\n");
      fwrite($gameFileHandler, $p2SideboardSubmitted . "\r\n");
      fwrite($gameFileHandler, $p1IsAI . "\r\n");
      fwrite($gameFileHandler, $p2IsAI . "\r\n");
      fwrite($gameFileHandler, $gameGUID . "\r\n");
      fclose($gameFileHandler);
    }
  }

?>
