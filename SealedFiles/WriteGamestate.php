<?php

  $metadataFile = fopen("GamestateMetadata.txt", "r");
  $zones = [];
  while(!feof($metadataFile))
  {
    $array = GetArray($metadataFile);
    $zones[count($zones)] = $array;
  }
  fclose($metadataFile);

  $gameFile = fopen("./Games/" . $gameName . "/GameFile.txt", "w");
  fwrite($gameFile, $numPlayers . "\r\n");
  for($i=1; $i<=$numPlayers; ++$i)
  {
    $varName = "p" . $i . "DecisionQueue";
    fwrite($gameFile, implode(" ", $$varName) . "\r\n");
  }
  for($i=0; $i<count($zones); ++$i)
  {
    if($zones[$i][0] == "GL")
    {
      $varName = $zones[$i][1];
      fwrite($gameFile, implode(" ", $$varName) . "\r\n");
    }
    else if($zones[$i][0] == "PP")
    {
      for($j=1; $j<=$numPlayers; ++$j)
      {
        $varName = "p" . $j . $zones[$i][1];
        fwrite($gameFile, implode(" ", $$varName) . "\r\n");
      }
    }
  }
?>