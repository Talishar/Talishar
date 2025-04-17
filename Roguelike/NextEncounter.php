<head>

<?php
/*
  include '../Libraries/HTTPLibraries.php';
  include '../Libraries/SHMOPLibraries.php';

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=TryGet("playerID", 3);

  //First we need to parse the game state from the file
  include "ZoneGetters.php";
  include "ParseGamestate.php";
  include "EncounterDictionary.php";
  include "../CardDictionary.php";
  include "../HostFiles/Redirector.php";
  include "../Libraries/UILibraries.php";
  include "../WriteLog.php";

?>

<style>
  td {
    text-align:center;
  }
</style>

<link rel="shortcut icon" type="image/png" href="../Images/TeenyCoin.png" />

</head>

<body style='z-index:-200;'>

<?php

$cardSize = 200;
$cardIconSize = intval($cardSize / 2.7); //40
$cardIconLeft = intval($cardSize / 4.2); //30
$cardIconTop = intval($cardSize / 4.2); //30

  //Include js files
  echo("<script src=\"../jsInclude.js\"></script>");
  echo("<script>SubmitInput = function(mode, params, fullRefresh = false){   var ajaxLink =
      'ProcessInput.php?gameName=' + document.getElementById('gameName').value;
    ajaxLink += '&playerID=' + document.getElementById('playerID').value;
    ajaxLink += '&authKey=' + document.getElementById('authKey').value;
    ajaxLink += '&mode=' + mode;
    ajaxLink += params;
    document.location = ajaxLink;
  }</script>");

  //Display hidden elements
  echo("<div id=\"cardDetail\" style=\"z-index:1000; display:none; position:absolute;\"></div>");
  echo("<div id=\"authKey\" style=\"display:none;\"></div>");


  $encounter = &GetZone($playerID, "Encounter");


  $health = &GetZone($playerID, "Health");

  $deck = &GetZone($playerID, "Deck");
  echo(CreatePopup("myDeckPopup", $deck, 1, 0, "Your Deck (" . count($deck) . " cards)", 1, "", "../", true));

  $character = &GetZone($playerID, "Character");

  //Display background
  echo("<div style='position:absolute; left:0px; top:0px; background-image:url(\"../Images/wooden-texture.jpg\"); width:100%; height:100%;'><div style='margin-left:1.5%; margin-top:1%; text-align:center;'><img style='height:94%; border: 3px solid black; border-radius: 5px;' src='../Images/map_of_rathe.jpg' /></div>");

  //Display left sidebar
  echo("<div style='position:absolute; text-align: center; z-index:100; border: 3px solid black; border-radius:5px; left:10px; top:17px; height:calc(95% - 26px); width:14%; background-color:rgba(235, 213, 179, .85);'>");
  echo("<h2 style='width:100%;'>Encounter #" . $encounter->encounterID . "</h2>");

  echo ("<div style='height:6vh; width:100%; z-index:-200;'><span title='Your remaining life' style='top: 10%; left: 50%; text-align: center; transform: translate(-50%, -50%); position:absolute; display:inline-block;'><img style='opacity: 0.9; height:" . $cardIconSize/1.5 . "; width:" . $cardIconSize/1.5 . ";' src='../Images/Life.png'>
      <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 32px; height: 32px; padding: 1px;
      text-align: center; transform: translate(-50%, -50%); line-height: 1.2;
      position:absolute; font-size:32px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $health[0] . "</div></img></span></div>");
  echo ("<div style='height:6vh; width:100%; z-index:-200;'><span title='Your remaining life' style='top: 10%; left: 50%; text-align: center; transform: translate(-50%, 70%); position:absolute; display:inline-block;'><img style='opacity: 0.9; height:" . $cardIconSize/1.5 . "; width:" . $cardIconSize/1.5 . ";' src='../Images/Arsenal.png'>
      <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 32px; height: 32px; padding: 1px;
      text-align: center; transform: translate(-50%, -50%); line-height: 1.2;
      position:absolute; font-size:32px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $encounter->gold . "</div></img></span></div>");
  /*echo ("<div style='height:6vh; width:100%; z-index:-200;'><span title='Your remaining life' style='top: 10%; left: 50%; text-align: center; transform: translate(-50%, 190%); position:absolute; display:inline-block;'><img style='opacity: 0.9; height:" . $cardIconSize/1.5 . "; width:" . $cardIconSize/1.5 . ";' src='../Images/Intellect.png'>
      <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 32px; height: 32px; padding: 1px;
      text-align: center; transform: translate(-50%, -50%); line-height: 1.2;
      position:absolute; font-size:32px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $encounter->rerolls . "</div></img></span></div>");*/
/*
  echo("<center>" . Card($character[0], "../concat", $cardSize, 0, 1) . "</center>");
  echo("<BR>");

  echo("<center><div style='cursor:pointer;' onclick='(function(){ document.getElementById(\"myDeckPopup\").style.display = \"inline\";})();'>" . Card("CardBack", "../concat", $cardSize, 0, 1, 0, 0, count($deck)) . "</div></center>");


  $myDQ = &GetZone($playerID, "DecisionQueue");

  $encounterContent = "";
  // WriteLog("playerID: " . $playerID);
  // WriteLog("encounter[0]: " . $encounter->encounterID);
  // WriteLog("encounter[1]: " . $encounter->subphase);
  // WriteLog($encounter->position);
  // WriteLog("Next encounter[0]: " . GetNextEncounter($encounter->encounterID));
  // WriteLog("myDQ: " . $myDQ[0] . ", " . $myDQ[1]);
  if(count($myDQ) > 0)
  {
    //WriteLog("myDQ[0]->".$myDQ[0]."myDQ[1]->".$myDQ[1]."myDQ[2]->".$myDQ[2]."myDQ[3]->".$myDQ[3]);
    switch($myDQ[0]){
    case "CHOOSECARD":
    {
      $options = explode(",", $myDQ[1]);
      //$encounterContent .= "<div style='position:absolute; text-align:center; top:30%; left: 250%; width:" . count($options)*155 . "; background-color: rgba(255,255,255,0.8); border: 3px solid black; border-radius: 5px;'>";
      $encounterContent .= "<h2>Choose a card</h2>";
      $encounterContent .= "<div style='display:inline;'>";
      for($i=0; $i<count($options); ++$i)
      {
        $encounterContent .= Card($options[$i], "../concat", 150, 1, 1, 0, 0, 0, strval($options[$i]));
      }
      $encounterContent .= "</div><BR>";
      $encounterContent .= "<div display:inline;'>";
      if($encounter->rerolls > 0 && $myDQ[3] != "NoReroll" && $myDQ[3] != "NoRS") {
        $rerollStringValue = "Reroll:_".$encounter->rerolls;
        $encounterContent .= CreateButton($playerID, str_replace("_", " ", $rerollStringValue), 2, strval("Reroll"), "24px");
      }
      $encounterContent .= "</div><BR>";
      //$encounterContent .= "<div>";
      break;
    }
    case "BUTTONINPUT":
    {
      $headerContent = EncounterChoiceHeader();
      $encounterContent .= "<h2>$headerContent</h2>";
      $encounterContent .= "<div style='display:inline;'>";
      $options = explode(",", $myDQ[1]);
      for ($i = 0; $i < count($options); ++$i) {
        $encounterContent .= CreateButton($playerID, str_replace("_", " ", $options[$i]), 2, strval($options[$i]), "24px");
      }
      $encounterContent .= "</div>";
      $encounterContent .= "<BR>";
      break;
    }
    /*case "CHOOSEHERO":
    {
      $options = explode(",", $myDQ[1]);
      //$encounterContent .= "<div style='position:absolute; text-align:center; top:30%; left: 250%; width:" . count($options)*155 . "; background-color: rgba(255,255,255,0.8); border: 3px solid black; border-radius: 5px;'>";
      $encounterContent .= "<h2>Choose a card</h2>";
      $encounterContent .= "<div style='display:inline;'>";
      for($i=0; $i<count($options); ++$i)
      {
        switch($options[$i])
        {

        }
        $encounterContent .= Card($options[$i], "../concat", 150, 1, 1, 0, 0, 0, strval($options[$i]));
      }
      $encounterContent .= "</div>";
      //$encounterContent .= "<div>";
      break;
    }
    case "REMOVEDECKCARD":{
      $options = explode(",", $myDQ[1]);
      $encounterContent .= "<h2>Remove a card from your deck</h2>";
      $encounterContent .= "<div style='display:inline;'>";
      for($i=0; $i<count($options); ++$i)
      {
        $encounterContent .= Card($options[$i], "../concat", 150, 1, 1, 0, 0, 0, strval($options[$i]));
      }
      $encounterContent .= "</div><BR>";
      $encounterContent .= "<div display:inline;'>";
      if($encounter->rerolls > 0 && $myDQ[3] != "NoReroll" && $myDQ[3] != "NoRS") {
        $rerollStringValue = "Reroll:_".$encounter->rerolls;
        $encounterContent .= CreateButton($playerID, str_replace("_", " ", $rerollStringValue), 2, strval("Reroll"), "24px");
      }
      $encounterContent .= "</div><BR>";
      break;
    }
    case "REMOVEALLDECKCARD":{
      $options = explode(",", $myDQ[1]);
      $encounterContent .= "<h2>Choose a card to offer</h2>";
      $encounterContent .= "<div style='display:inline;'>";
      for($i=0; $i<count($options); ++$i)
      {
        $encounterContent .= Card($options[$i], "../concat", 150, 1, 1, 0, 0, 0, strval($options[$i]));
      }
      $encounterContent .= "</div><BR>";
      $encounterContent .= "<div display:inline;'>";
      if($encounter->rerolls > 0 && $myDQ[3] != "NoReroll" && $myDQ[3] != "NoRS") {
        $rerollStringValue = "Reroll:_".$encounter->rerolls;
        $encounterContent .= CreateButton($playerID, str_replace("_", " ", $rerollStringValue), 2, strval("Reroll"), "24px");
      }
      $encounterContent .= "</div><BR>";
      break;
    }
    case "DUPLICATECARD":{
      $options = explode(",", $myDQ[1]);
      $encounterContent .= "<h2>Offer a card in your deck</h2>";
      $encounterContent .= "<div style='display:inline;'>";
      for($i=0; $i<count($options); ++$i)
      {
        $encounterContent .= Card($options[$i], "../concat", 150, 1, 1, 0, 0, 0, strval($options[$i]));
      }
      $encounterContent .= "</div><BR>";
      $encounterContent .= "<div display:inline;'>";
      if($encounter->rerolls > 0 && $myDQ[3] != "NoReroll" && $myDQ[3] != "NoRS") {
        $rerollStringValue = "Reroll:_".$encounter->rerolls;
        $encounterContent .= CreateButton($playerID, str_replace("_", " ", $rerollStringValue), 2, strval("Reroll"), "24px");
      }
      $encounterContent .= "</div><BR>";
      break;
    }
    case "SHOP":
    {
      $options = explode(",", $myDQ[1]);
      $encounterContent .= "<h2>Buy cards with gold</h2>";
      $encounterContent .= "<div style='display:inline;'>";
      for($i=0; $i<count($options)/2; ++$i)
      {
        $encounterContent .= Card($options[$i], "../concat", 150, 1, 1, 0, 0, GetShopCost($options[$i]), strval($options[$i]));
      }
      $encounterContent .= "</div><BR>";
      $encounterContent .= "<div style='display:inline;'>";
      for($i=count($options)/2; $i<count($options); ++$i)
      {
        $encounterContent .= Card($options[$i], "../concat", 150, 1, 1, 0, 0, GetShopCost($options[$i]), strval($options[$i]));
      }
      if($myDQ[3] != "NoSubchoice" && $myDQ[3] != "NoRS") {
        $encounterContent .= "</div><BR>";
        $encounterContent .= "<div display:inline;'>";
        $shopHealStringValue = "Pay_".$encounter->costToHeal."g_to_hire_a_local_healer";
        $encounterContent .= CreateButton($playerID, str_replace("_", " ", $shopHealStringValue), 2, strval("shop_heal"), "24px");
        $shopRemoveStringValue = "Offer_".$encounter->costToRemove."g_to_a_beggar";
        $encounterContent .= CreateButton($playerID, str_replace("_", " ", $shopRemoveStringValue), 2, strval("shop_reflect"), "24px");
      }
      //$encounterContent .= "</div><BR>";
      //$encounterContent .= "<div display:inline;'>";
      $encounterContent .= "</div><BR>";
      $encounterContent .= "<div display:inline;'>";
      if($encounter->rerolls > 0 && $myDQ[3] != "NoReroll" && $myDQ[3] != "NoRS") {
        $rerollStringValue = "Reroll:_".$encounter->rerolls;
        $encounterContent .= CreateButton($playerID, str_replace("_", " ", $rerollStringValue), 2, strval("Reroll"), "24px");
      }
      $encounterContent .= CreateButton($playerID, str_replace("_", " ", "Leave"), 2, strval("Leave"), "24px");
      $encounterContent .= "</div><BR>";

      break;
    }
    default: {
      $encounterContent .= "Bug. This phase not implemented: " . $myDQ[0];
      break;
    }
  }
  }
  else if($encounter->subphase != ""){
    $encounterContent .= "<form style='width:100%;display:inline-block;' action='" . $redirectPath . "/Roguelike/PlayEncounter.php'>";
    $encounterContent .= "<input type='hidden' id='gameName' name='gameName' value='$gameName' />";
    $encounterContent .= "<input type='hidden' id='playerID' name='playerID' value='$playerID' />";
    $encounterContent .= "<input type='submit' style='font-size:20px;' value='Play Encounter' />";
    $encounterContent .= "</form>";
  }

  echo("</div>");//End cards div

  echo("</div>");//End left sidebar div

  $content = "<div style='width:100%;'>";
  $content .= "<center><img src='../crops/" . EncounterImage() . "' /></center>";
  $content .= "<BR>";
  $content .= "</div>";
  $content .= "<center>" . $encounterContent . "</center>";
  echo CreatePopup("BUTTONINPUT", [], 0, 1, EncounterDescription(), 1, $content, size:2);
  //EncounterImage($encounter->encounterID, $encounter->subphase);


  echo("</div>");//End background

  echo("</div>");//End play area div

  //Display the log
  echo("<div id='gamelog' style='background-color: rgba(255,255,255,0.8); border: 3px solid black; border-radius: 5px; position:fixed; display:inline; width:12%; height: 92%; top:2%; right:1%; overflow-y: scroll;'>");

  EchoLog($gameName, $playerID);
  echo("</div>");

  echo("<div id='chatbox' style='position:fixed; display:inline; width:200px; height: 50px; right:10px;'>");
  echo("<input type='hidden' id='gameName' value='" . $gameName . "'>");
  echo("<input type='hidden' id='playerID' value='" . $playerID . "'>");

  function PlayableCardBorderColor($cardID)
  {
    if(HasReprise($cardID) && RepriseActive()) return 3;
    return 0;
  }

  function CanPassPhase($phase)
  {
    switch($phase)
    {
      case "P": return 0;
      case "PDECK": return 0;
      case "CHOOSEDECK": return 0;
      case "HANDTOPBOTTOM": return 0;
      case "CHOOSECOMBATCHAIN": return 0;
      case "CHOOSECHARACTER": return 0;
      case "CHOOSEHAND": return 0;
      case "CHOOSEHANDCANCEL": return 0;
      case "MULTICHOOSEDISCARD": return 0;
      case "CHOOSEDISCARDCANCEL": return 0;
      case "CHOOSEARCANE": return 0;
      case "CHOOSEARSENAL": return 0;
      case "CHOOSEDISCARD": return 0;
      case "MULTICHOOSEHAND": return 0;
      case "MULTICHOOSEBANISH": return 0;
      default: return 1;
    }
  }

  function IsDarkMode() { return false; } // This must exist for UILibraries2
  function IsGameOver() { return false; } // This must exist for UILibraries2

?>
</body>
</div>


<!----- Footer ----->
<div style="height:11px; bottom:8px; left:20px; width: auto;
        position:absolute;
        color:white;
        font-size: .7em;
        font-style: italic; opacity: 0.8;
        font-weight: normal;
        text-indent: 1px;
        background-color:rgba(74, 74, 74, 0.9);
        border-radius: 2px;
        text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a;
        ">Talishar is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™,
  and set names are trademarks of Legend Story Studios.
  Flesh and Blood characters, cards, logos, and art are property of Legend Story Studios.
  Card Images © Legend Story Studios
</div>
