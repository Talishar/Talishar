
<head>

<?php
  $redirectPath="https://localhost/fabonlinelocal";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];

  $gameFile = fopen("./Games/" . $gameName . "/GameFile.txt", "r");
  $lineCount = 0;
  $gameStarted = 0;
  $icon = "notReady.png";
  $playerData = [];
  while (($buffer = fgets($gameFile, 4096)) !== false) {
     if($lineCount < 2)
     {
       array_push($playerData, $buffer);
       //$playerData = explode(" ", $buffer);
     }
     else if($lineCount == 2)
     {
       if($buffer == "1")
       {
         $gameStarted = 1;
       }
     }
     ++$lineCount;
  }
  if(count($playerData) == 2) $icon = "ready.png";

  fclose($gameFile);
  echo '<title>Game Lobby</title> <meta http-equiv="content-type" content="text/html; charset=utf-8" > <meta name="viewport" content="width=device-width, initial-scale=1.0">';
  echo '<link rel="shortcut icon" type="image/png" href="./HostFiles/' . $icon . '"/>';
?>


<script>
function copyText() {
  gameLink = document.getElementById("gameLink");
  gameLink.select();
  gameLink.setSelectionRange(0, 99999);

  // Copy it to clipboard
  document.execCommand("copy");
}



</script>

<style>
body {
  font-family: Garamond, serif;
  margin:0px;
  color:rgb(240, 240, 240);
}

h1 {
  text-align:center;
  width:100%;
}

h2 {
  text-align:center;
  width:100%;
}
</style>
</head>

<body <?php if($lineCount == 1 || $playerID == 2) echo("onload='reload()'"); ?>>

<div style="width:100%; height:100%; background-image: url('Images/rout.jpg'); background-size:cover; z-index=0;">

<div style="position:absolute; z-index:1; top:35%; left:2%; width:25%; height:50%; background-color:rgba(59, 59, 38, 0.7);">
<h1>Game Lobby</h1>
<?php
  echo("<div style='text-align:center;'>");
  for($i=0; $i<count($playerData); ++$i)
  {
     echo("Player ID: " . $playerData[$i]);
     echo("<br>");
  }

  if($playerID == 1)
  {
    if($lineCount == 1)
    {
      echo("<div><input type='text' id='gameLink' value='" . $redirectPath . "/JoinGame.php?gameName=$gameName&playerID=2'></div>");

      echo("<button onclick='copyText()'>Copy Link to Join</button>");
      echo("<div>(Start button will appear here when second player joins)</div>");
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
    else
    {
      echo("<div>(Waiting for Player 1 to start the game)</div>");
    }
  }
  echo("</div>");

  echo("<h2>Instructions</h2>");
  echo("<ul>");
  echo("<li>Copy link and send to your opponent, or open it yourself in another browser tab.</li>");
  echo("<li>The browser tab icon will turn green when your opponent joins.</li>");
  echo("<li>Player 1 starts the game when both players have joined.</li>");
  echo("<li>Currently sharing the link is the only way to get an opponent. An open game browser is planned for the future.</li>");
  echo("</ul>");


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
</div>
<div style="height:20px; bottom:30px; left:5%; width: 90%; position:absolute; color:white;background-color:rgba(59, 59, 38, 0.7); text-align:center;">FaB Online is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of Legend Story Studios.</div>
</body>
