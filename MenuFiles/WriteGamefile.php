<?php

  $filename = "./Games/" . $gameName . "/GameFile.txt";
  $handler = fopen($filename, "w");

  fwrite($handler, implode(" ", $p1Data) . "\r\n");
  fwrite($handler, implode(" ", $p2Data) . "\r\n");
  fwrite($handler, $gameStatus . "\r\n");
  fwrite($handler, $format . "\r\n");
  fwrite($handler, $visibility . "\r\n");

  fclose($handler);

?>

