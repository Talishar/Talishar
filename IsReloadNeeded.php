<?php

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  include "ParseGamestate.php";

  if($playerID == $currentPlayer) echo "1";
  else echo "0";

?>

