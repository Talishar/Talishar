<?php


  function ARCWizardCardType($cardID)
  {
    switch($cardID)
    {
      case "ARC113": case "ARC114": return "C";
      case "ARC115": return "W";
      case "ARC116": case "ARC117": return "E";
      case "ARC123": case "ARC124": case "ARC125": return "DR";
      default: return "A";
    }
  }

  function ARCWizardCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ARC115": return "Staff";
      case "ARC116": return "Legs";
      case "ARC117": return "Chest";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ARCWizardCardCost($cardID)
  {
    switch($cardID)
    {
      case "ARC119": return 2;
      case "ARC120": return 3;
      case "ARC121": return 1;
      case "ARC123": case "ARC124": case "ARC125": return 1;
      case "ARC126": case "ARC127": case "ARC128": return 2;
      case "ARC129": case "ARC130": case "ARC131": return 2;
      case "ARC132": case "ARC133": case "ARC134": return 1;
      case "ARC138": case "ARC139": case "ARC140": return 1;
      case "ARC141": case "ARC142": case "ARC143": return 1;
      case "ARC147": case "ARC148": case "ARC149": return 1;
      default: return 0;
    }
  }

  function ARCWizardPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ARC118": return 1;
      case "ARC119": return 2;
      case "ARC120": return 1;
      case "ARC121": return 2;
      case "ARC122": return 1;
      case "ARC123": case "ARC126": case "ARC129": case "ARC132": case "ARC135": case "ARC138": case "ARC141": case "ARC144": case "ARC147": return 1;
      case "ARC124": case "ARC127": case "ARC130": case "ARC133": case "ARC136": case "ARC139": case "ARC142": case "ARC145": case "ARC148": return 2;
      case "ARC125": case "ARC128": case "ARC131": case "ARC134": case "ARC137": case "ARC140": case "ARC143": case "ARC146": case "ARC149": return 3;
      default: return 0;
    }
  }

  function ARCWizardBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ARC113": case "ARC114": case "ARC115": case "ARC116": case "ARC117": return 0;
      case "ARC122": return 2;
      case "ARC123": return 4;
      case "ARC124": return 3;
      case "ARC125": return 2;
      case "ARC129": case "ARC130": case "ARC131": return 2;
      case "ARC135": case "ARC136": case "ARC137": return 2;
      default: return 3;
    }
  }

  function ARCWizardAttackValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function ARCWizardPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $myResources, $currentPlayer;
    switch($cardID)
    {
      case "ARC115":
        AddArcaneBonus(1, $currentPlayer);
        return "Crucible of Aetherweave gives the next card that deals arcane damage +1.";
      case "ARC117":
        $myResources[0] += 3;
        return "Robe of Rapture gives 3 resources.";
      case "ARC126": case "ARC127": case "ARC128":
        DealArcane(ArcaneDamage($cardID));
        AddDecisionQueue("OPTX", $currentPlayer, "<-", 1);
        return "";
      case "ARC141": case "ARC142": case "ARC143":
      case "ARC144": case "ARC145": case "ARC146":
      case "ARC147": case "ARC148": case "ARC149":
        DealArcane(ArcaneDamage($cardID));
        return "";
      default: return "";
    }

  }

  function ARCWizardHitEffect($cardID)
  {

  }

  function DealArcane($damage, $OpposingOnly=0, $type="PLAYCARD")
  {
    global $currentPlayer;
    if($type == "PLAYCARD")
    {
      $damage += ConsumeArcaneBonus($currentPlayer);
    }
    AddDecisionQueue("CHOOSEHERO", $currentPlayer, $OpposingOnly);
    AddDecisionQueue("DEALARCANE", $currentPlayer, $damage, 1);
  }

  function ArcaneDamage($cardID)
  {
    switch($cardID)
    {
      case "ARC119": return 3;
      case "ARC120": return 4;
      case "ARC121": return 3;
      case "ARC147": return 5;
      case "ARC126": case "ARC141": case "ARC148": return 4;
      case "ARC127": case "ARC132": case "ARC138": case "ARC142": case "ARC144": case "ARC149": return 3;
      case "ARC128": case "ARC133": case "ARC139": case "ARC143": case "ARC145": return 2;
      case "ARC134": case "ARC140": case "ARC146": return 1;
    }
  }

  function ArcaneBarrierChoices($playerID, $max)
  {
    $barrierArray = [];
    for($i=0; $i<4; ++$i)//4 is the current max arcane barrier + 1
    {
      $barrierArray[$i] = 0;
    }
    $character = GetPlayerCharacter($playerID);
    $total = 0;
    for($i=0; $i<count($character); $i+=CharacterPieces())
    {
      switch($character[$i])
      {
        case "ARC005": ++$barrierArray[1]; $total += 1; break;
        case "ARC041": ++$barrierArray[1]; $total += 1; break;
        case "ARC042": ++$barrierArray[1]; $total += 1; break;
        case "ARC116": ++$barrierArray[2]; $total += 2; break;
        case "ARC117": ++$barrierArray[1]; $total += 1; break;
        case "ARC150": if(PlayerHasLessHealth($playerID)) { ++$barrierArray[3]; $total += 3; } break;
        case "ARC155": case "ARC156": case "ARC157": case "ARC158": ++$barrierArray[1]; $total += 1; break;
        case "CRU006": ++$barrierArray[2]; $total += 2; break;
        default: break;
      }
    }
    $choiceArray = [];
    array_push($choiceArray, 0);
    if($barrierArray[1] > 0) array_push($choiceArray, 1);
    if($barrierArray[2] > 0 || $barrierArray[1] >= 2) array_push($choiceArray, 2);
    for($i=3; $i<=$max; ++$i)
    {
      if($i <= $total) array_push($choiceArray, $i);
    }
    return implode(",", $choiceArray);
  }

?>

