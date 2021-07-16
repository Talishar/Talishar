<script>
function copyText() {
  gameLink = document.getElementById("gameLink");
  gameLink.select();

  // Copy it to clipboard
  document.execCommand("Copy");
}
</script>

<body onload='reload()'>

<?php
  include "HostFiles/Redirector.php";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  $gameFile = fopen("./Games/" . $gameName . "/GameFile.txt", "r");
  $playerCount = 0;
  $gameStarted = 0;
  while (($buffer = fgets($gameFile, 4096)) !== false) {
     if($playerCount < 2)
     {
       $playerData = explode(" ", $buffer);
       echo("Player ID: " . $playerData[0] . " &nbsp;&nbsp; Username: " . $playerData[1]);
       echo("<br>");
       ++$playerCount;
     }
     else
     {
       if($buffer == "1") $gameStarted = 1;
     }
  }

  fclose($gameFile);
  if($playerID == 1)
  {
    if($playerCount == 1)
    {
      echo("<div id='gameLink' style='display:none;'>" . $redirectPath . "/JoinGame.php?gameName=$gameName&playerID=2</div>");

      echo("<button onclick='copyText()'>Copy Link to Join</button>");
    }
    else
    {
      echo("<form action='./Start.php'>");
        echo("<input type='hidden' id='gameName' name='gameName' value='$gameName'>");
        echo("<input type='hidden' id='playerID' name='playerID' value='$playerID'>");
        echo("<input type='submit' value='Start Game'>");
      echo("</form>");
    }
  }
  else if($playerID == 2)
  {
    if($gameStarted)
    {
      header("Location: " . $redirectPath . "/NextTurn.php?gameName=$gameName&playerID=$playerID");
    }
  }


  echo("<script>");
  echo("function reload() { setInterval(function(){location.reload();}, 1000); }");
  echo("</script>");

?>
</body>