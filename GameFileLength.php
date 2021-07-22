<?php

  $gameName=$_GET["gameName"];

  $gameFile = fopen("./Games/" . $gameName . "/GameFile.txt", "r");
  $lineCount = 0;
  while (($buffer = fgets($gameFile, 4096)) !== false) {
     ++$lineCount;
  }

  echo $lineCount;

?>

