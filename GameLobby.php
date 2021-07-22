<script>
function copyText() {
  gameLink = document.getElementById("gameLink");
  gameLink.select();
  gameLink.setSelectionRange(0, 99999);

  // Copy it to clipboard
  document.execCommand("copy");
}



</script>

<body onload='reload()'>

<?php
  include "HostFiles/Redirector.php";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  $gameFile = fopen("./Games/" . $gameName . "/GameFile.txt", "r");
  $lineCount = 0;
  $gameStarted = 0;
  while (($buffer = fgets($gameFile, 4096)) !== false) {
     if($lineCount < 2)
     {
       $playerData = explode(" ", $buffer);
       echo("Player ID: " . $playerData[0] . " &nbsp;&nbsp; Username: " . $playerData[1]);
       echo("<br>");
     }
     else if($lineCount == 2)
     {
       if($buffer == "1") $gameStarted = 1;
     }
     ++$lineCount;
  }

  fclose($gameFile);
  if($playerID == 1)
  {
    if($lineCount == 1)
    {
      echo("<div><input type='text' id='gameLink' value='" . $redirectPath . "/JoinGame.php?gameName=$gameName&playerID=2'></div>");

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
  echo("var prevLineCount = " . $lineCount . ";");
  echo("function reload() { setInterval(function(){loadGamestate();}, 500); }");

  echo("function loadGamestate() {");
    echo("const xhttp = new XMLHttpRequest();");
    echo("xhttp.onload = function() {");
      echo 'if(this.responseText[0] > prevLineCount) location.reload();';
    echo("};");
    echo 'xhttp.open("GET", "GameFileLength.php?gameName=' . $gameName . '", true);';
    echo("xhttp.send();");
  echo("}");

  echo("</script>");

?>
</body>