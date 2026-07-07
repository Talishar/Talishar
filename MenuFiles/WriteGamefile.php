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
      global $p1SideboardSubmitted, $p2SideboardSubmitted, $p1StartingEquipment, $p2StartingEquipment, $p1IsAI, $p2IsAI, $gameGUID;
      global $p1MetafyTiers, $p2MetafyTiers;
      global $p1MetafyCommunities, $p2MetafyCommunities;
      global $p1DisplayName, $p2DisplayName;
      // Build entire payload as one string, then write in a single call.
      // Reduces ~40 fwrite() PHP function calls + stream API dispatches to 1.
      $content = implode(" ", $p1Data) . "\r\n"
          . implode(" ", $p2Data) . "\r\n"
          . $gameStatus         . "\r\n"
          . $format             . "\r\n"
          . $visibility         . "\r\n"
          . $firstPlayerChooser . "\r\n"
          . $firstPlayer        . "\r\n"
          . $p1Key              . "\r\n"
          . $p2Key              . "\r\n"
          . $p1uid              . "\r\n"
          . $p2uid              . "\r\n"
          . $p1id               . "\r\n"
          . $p2id               . "\r\n"
          . $gameDescription    . "\r\n"
          . $hostIP             . "\r\n"
          . $p1IsPatron         . "\r\n"
          . $p2IsPatron         . "\r\n"
          . $p1DeckLink         . "\r\n"
          . $p2DeckLink         . "\r\n"
          . $p1IsChallengeActive . "\r\n"
          . $p2IsChallengeActive . "\r\n"
          . $joinerIP           . "\r\n"
          . "\r\n"//Deprecated
          . json_encode($p1Matchups)          . "\r\n"
          . json_encode($p2Matchups)          . "\r\n"
          . $p1deckbuilderID    . "\r\n"
          . $p2deckbuilderID    . "\r\n"
          . $roguelikeGameID    . "\r\n"
          . $p1StartingHealth   . "\r\n"
          . $p1ContentCreatorID . "\r\n"
          . $p2ContentCreatorID . "\r\n"
          . $p1SideboardSubmitted . "\r\n"
          . $p2SideboardSubmitted . "\r\n"
          . json_encode($p1StartingEquipment) . "\r\n"
          . json_encode($p2StartingEquipment) . "\r\n"
          . $p1IsAI             . "\r\n"
          . $p2IsAI             . "\r\n"
          . $gameGUID           . "\r\n"
          . json_encode($p1MetafyTiers        ?? []) . "\r\n"
          . json_encode($p2MetafyTiers        ?? []) . "\r\n"
          . json_encode($p1MetafyCommunities  ?? []) . "\r\n"
          . json_encode($p2MetafyCommunities  ?? []) . "\r\n"
          . ($p1DisplayName ?? "")                   . "\r\n"
          . ($p2DisplayName ?? "")                   . "\r\n";

      rewind($gameFileHandler);
      fwrite($gameFileHandler, $content);
      fclose($gameFileHandler);
    }
  }

?>
