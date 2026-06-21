<?php

if (!function_exists('GetCardEffectLabel')) {
  function GetCardEffectLabel($uniqueID, $currentTurnEffects) {
    if ($uniqueID == "" || $uniqueID == "-") return "";
    
    $effectName = "";
    $effectsCount = count($currentTurnEffects);
    for ($j = 0; $j < $effectsCount; ++$j) {
      $effect = $currentTurnEffects[$j];
      $p1 = strpos($effect, "-");
      if ($p1 === false) continue;
      $p2 = strpos($effect, "-", $p1 + 1);
      $effectID = ($p2 !== false) ? substr($effect, $p1 + 1, $p2 - $p1 - 1) : substr($effect, $p1 + 1);
      if ($effectID == $uniqueID) {
        $effectName = substr($effect, 0, $p1);
        break;
      }
    }

    if ($effectName === "") return "";

    switch ($effectName) {
      case "beseech_the_demigon_red":
      case "beseech_the_demigon_yellow":
      case "beseech_the_demigon_blue":
        return "Power +" . EffectPowerModifier($effectName);
      case "tear_through_the_portal_red":
      case "tear_through_the_portal_yellow":
      case "tear_through_the_portal_blue":
        return "Go Again";
      default:
        return "";
    }
  }
}

function BuildPlayerInputPopupFull($playerID, $turnPhase, $turn, $gameName) {
  global $myHand, $myPitch, $myDeck, $theirDeck, $myDiscard, $theirDiscard;
  global $myBanish, $theirBanish, $myArsenal, $theirArsenal;
  global $myCharacter, $theirCharacter, $myAuras, $theirAuras;
  global $myItems, $theirItems, $mySoul, $theirSoul;
  global $combatChain, $layers, $dqVars, $currentPlayer;
  global $TheirCardBack, $MyCardBack, $otherPlayer, $mainPlayer;
  global $combatChainState, $CCS_AttackTargetUID, $CCS_WeaponIndex;
  global $CombatChain, $chainLinks, $landmarks, $currentTurnEffects;
  global $theirHand, $myPermanents, $theirPermanents, $myPitch, $theirPitch;
  global $theirAllies, $myAllies, $attackQueue, $Stack;

  $playerInputPopup = new stdClass();
  $playerInputButtons = [];
  $playerInputPopup->active = false;
  $myPitchZone = new PitchZone($playerID);

  switch ($turnPhase) {
    case "BUTTONINPUT":
    case "BUTTONINPUTNOPASS":
    case "CHOOSEARCANE":
    case "CHOOSETRIGGERS":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $options = explode(",", $turn[2]);
        $caption = "";
        if ($turnPhase == "CHOOSEARCANE") {
          $vars = explode("-", $dqVars[0]);
          $caption .= "Source: " . CardLink($vars[1], $vars[1]) . "&nbsp | &nbspTotal Damage: " . $vars[0];
          if(!CanDamageBePrevented($playerID, $vars[0], "ARCANE", $vars[1])) {
            $caption .= "&nbsp | &nbsp <span style='font-size: 0.8em; color:red;'>**WARNING: THIS DAMAGE IS UNPREVENTABLE**</span><br>";
          } else {
            $caption .= "<br>";
          }
        }

        foreach ($options as $option) {
          $playerInputButtons[] = CreateButtonAPI($playerID, str_replace("_", " ", $option), 17, strval($option), "24px");
        }

        if(isset($vars[1]) && $vars[1] == "runechant") {
          $playerInputButtons[] = CreateButtonAPI($playerID, "Skip All Runechants", 105, 0, "24px");
        }

        $playerInputPopup->popup = CreatePopupAPI("BUTTONINPUT", [], 0, 1, $caption . GetPhaseHelptext(), 1, "");
      }
      break;

    case "YESNO":
    case "DOCRANK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $playerInputButtons[] = CreateButtonAPI($playerID, "Yes", 20, "YES", "20px");
        $playerInputButtons[] = CreateButtonAPI($playerID, "No", 20, "NO", "20px");
        $playerInputPopup->popup = CreatePopupAPI("YESNO", [], 0, 1, GetPhaseHelptext(), 1, "");
      }
      break;

    case "PDECK":
      if ($currentPlayer == $playerID) {
        $playerInputPopup->active = true;
        $pitchingCards = [];
        $myPitchCount = count($myPitch);
        $pitchPieces = PitchPieces();
        for ($i = 0; $i < $myPitchCount; $i += $pitchPieces) {
          $card = $myPitch[$i];
          $uniqueID = $myPitch[$i+1];
          $pitchingCards[] = JSONRenderedCard($card, action: 6, actionDataOverride: $card, uniqueID:$uniqueID);
        }
        $playerInputPopup->popup = CreatePopupAPI("PITCH", [], 0, 1, "Choose a card to place on the bottom of your deck, or pass to shortcut", 1, cardsArray: $pitchingCards);
      }
      break;

    case "DYNPITCH":
    case "CHOOSENUMBER":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $options = explode(",", $turn[2]);
        foreach ($options as $option) {
          $playerInputButtons[] = CreateButtonAPI($playerID, $option, 7, $option, "24px");
        }
        $playerInputPopup->popup = CreatePopupAPI($turn[0], [], 0, 1, GetPhaseHelptext(), 1, "");
      }
      break;

    case "OK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $playerInputButtons[] = CreateButtonAPI($playerID, "Ok", 99, "OK", "20px");
        $playerInputPopup->popup = CreatePopupAPI("OK", [], 0, 1, GetPhaseHelptext(), 1, "");
      }
      break;

    case "CHOOSETOP":
    case "CHOOSEBOTTOM":
    case "CHOOSECARD":
    case "MAYCHOOSECARD":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $options = explode(",", $turn[2]);
        $optCards = [];
        $buttonText = match($turn[0]) {
          "CHOOSETOP" => "Top",
          "CHOOSEBOTTOM" => "Bottom",
          default => "Choose"
        };
        $buttonAction = match($turn[0]) {
          "CHOOSETOP" => 8,
          "CHOOSEBOTTOM" => 9,
          default => 23
        };
        foreach ($options as $option) {
          $optCards[] = JSONRenderedCard($option, action: 0);
          $playerInputButtons[] = CreateButtonAPI($playerID, $buttonText, $buttonAction, $option, "20px");
        }
        $playerInputPopup->popup = CreatePopupAPI("OPT", [], 0, 1, GetPhaseHelptext(), 1, "", cardsArray: $optCards);
      }
      break;

    case "OPT":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $options = explode(";", $turn[2]);
        $topOptions = array_filter(explode(",", $options[0] ?? ""));
        $bottomOptions = array_filter(explode(",", $options[1] ?? ""));
        
        $topOptCards = array_map(fn($option) => JSONRenderedCard($option, action: 0), $topOptions);
        $bottomOptCards = array_map(fn($option) => JSONRenderedCard($option, action: 0), $bottomOptions);

        $playerInputPopup->popup = CreatePopupAPI("NEWOPT", [], 0, 1, GetPhaseHelptext(), 1, "", topCards: $topOptCards, bottomCards: $bottomOptCards);
      }
      break;

    case "COERCIVE":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $options = explode(";", $turn[2]);
        $topOptions = array_filter(explode(",", $options[0] ?? ""));
        
        $topOptCards = array_map(fn($option) => JSONRenderedCard($option, action: 0), $topOptions);

        $playerInputPopup->popup = CreatePopupAPI("REARRANGETOP", [], 0, 1, GetPhaseHelptext(), 1, "", topCards: $topOptCards);
      }
      break;

    case "ORDERTRIGGERS":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $orderedLayers = [];
        $layersList = array_filter(explode(",", $turn[2] ?? ""));
        foreach($layersList as $layer) {
          $layerParts = explode("|", $layer);
          $ID = $layerParts[0] ?? "-";
          $UID = $layerParts[1] ?? "-";
          $orderedLayers[] = JSONRenderedCard($ID, uniqueID:$UID, action: 0);
        }

        $playerInputPopup->popup = CreatePopupAPI("TRIGGERORDER", [], 0, 1, GetPhaseHelptext(), 1, "Order your triggers. The rightmost trigger will resolve first.", topCards: $orderedLayers);
      }
      break;

    case "CHOOSETOPOPPONENT":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $otherPlayer = $playerID == 1 ? 2 : 1;
        $options = explode(",", $turn[2]);
        $optCards = [];
        foreach ($options as $option) {
          $optCards[] = JSONRenderedCard($option, action: 0, isOpponent: true);
          $playerInputButtons[] = CreateButtonAPI($otherPlayer, "Top", 29, $option, "20px");
        }
        $playerInputPopup->popup = CreatePopupAPI("CHOOSETOPOPPONENT", [], 0, 1, GetPhaseHelptext(), 1, "", cardsArray: $optCards);
      }
      break;

    case "INPUTCARDNAME":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $playerInputPopup->popup = CreatePopupAPI("INPUTCARDNAME", [], 0, 1, "Name a card", 1, "");
      }
      break;

    case "HANDTOPBOTTOM":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $cardsArray = [];
        foreach ($myHand as $card) {
          $cardsArray[] = JSONRenderedCard($card, action: 0);
          $playerInputButtons[] = CreateButtonAPI($playerID, "Top", 12, $card, "20px");
          $playerInputButtons[] = CreateButtonAPI($playerID, "Bottom", 13, $card, "20px");
        }
      $playerInputPopup->popup = CreatePopupAPI("HANDTOPBOTTOM", [], 0, 1, GetPhaseHelptext(), 1, "", cardsArray: $cardsArray);
    }
    break;

    case "CHOOSECARDID":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $options = explode(",", $turn[2]);
        $cardList = [];
        $optionsCount = count($options);
        for ($i = 0; $i < $optionsCount; ++$i) {
          $cardList[] = JSONRenderedCard($options[$i], action: 16, actionDataOverride: strval($options[$i]));
        }
        $playerInputPopup->popup = CreatePopupAPI("CHOOSEZONE", [], 0, 1, GetPhaseHelptext(), 1, "", cardsArray: $cardList);
      }
      break;

    case "MAYCHOOSEDECK":
    case "CHOOSEDECK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your deck:";
        $playerInputPopup->popup = ChoosePopup($myDeck, $turn[2] ?? "", 11, $caption, "(You can click your deck to see its content during this card resolution)");
      }
      break;

    case "MAYCHOOSETHEIRDECK":
    case "CHOOSETHEIRDECK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your opponent deck:";
        $playerInputPopup->popup = ChoosePopup($theirDeck, $turn[2] ?? "", 11, $caption);
      }
      break;

    case "CHOOSEBANISH":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your banish:";
        $playerInputPopup->popup = ChoosePopup($myBanish, $turn[2] ?? "", 16, $caption);
      }
      break;

    case "MAYCHOOSEARSENAL":
    case "CHOOSEARSENAL":
    case "CHOOSEARSENALCANCEL":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your arsenal:";
        $playerInputPopup->popup = ChoosePopup($myArsenal, $turn[2] ?? "", 16, $caption, "", "ARSENAL");
      }
      break;

    case "CHOOSEPERMANENT":
    case "MAYCHOOSEPERMANENT":
      if ($turn[1] == $playerID) {
        $myPermanents = &GetPermanents($playerID);
        $playerInputPopup->active = true;
        $playerInputPopup->popup = ChoosePopup($myPermanents, $turn[2] ?? "", 16, GetPhaseHelptext());
      }
      break;

    case "CHOOSETHEIRHAND":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your opponent's hand:";
        $playerInputPopup->popup = ChoosePopup($theirHand, $turn[2] ?? "", 16, $caption);
      }
      break;

    case "CHOOSEMYAURA":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose one of your auras:";
        $playerInputPopup->popup = ChoosePopup($myAuras, $turn[2] ?? "", 16, $caption);
      }
      break;

    case "CHOOSEDISCARD":
    case "MAYCHOOSEDISCARD":
    case "CHOOSEDISCARDCANCEL":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your graveyard:";
        $playerInputPopup->popup = ChoosePopup($myDiscard, $turn[2] ?? "", 16, $caption);
      }
      break;

    case "MAYCHOOSETHEIRDISCARD":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your opponent's graveyard:";
        $playerInputPopup->popup = ChoosePopup($theirDiscard, $turn[2] ?? "", 16, $caption);
      }
      break;

    case "CHOOSECOMBATCHAIN":
    case "MAYCHOOSECOMBATCHAIN":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from the combat chain:";
        $playerInputPopup->popup = ChoosePopup($combatChain, $turn[2], 16, $caption);
      }
      break;

    case "CHOOSECHARACTER":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your character/equipment:";
        $playerInputPopup->popup = ChoosePopup($myCharacter, $turn[2] ?? "", 16, $caption);
      }
      break;

    case "CHOOSETHEIRCHARACTER":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose a card from your opponent character/equipment:";
        $playerInputPopup->popup = ChoosePopup($theirCharacter, $turn[2] ?? "", 16, $caption);
      }
      break;

    case "MULTICHOOSETHEIRDISCARD":
    case "MULTICHOOSEDISCARD":
    case "MULTICHOOSEHAND":
    case "MAYMULTICHOOSEHAND":
    case "MULTICHOOSEDECK":
    case "MULTICHOOSETEXT":
    case "MAYMULTICHOOSETEXT":
    case "MULTICHOOSETHEIRDECK":
    case "MULTICHOOSEBANISH":
    case "MULTICHOOSEITEMS":
    case "MULTICHOOSESUBCARDS":
      if ($currentPlayer == $playerID) {
      $playerInputPopup->active = true;
      $formOptions = new stdClass();
      $cardsArray = [];

      $content = "";
      $turnData2 = $turn[2] ?? "";
      $params = explode("-", $turnData2);
      $options = isset($params[1]) ? explode(",", $params[1]) : [];
      $maxNumber = intval($params[0]);
      $minNumber = $params[2] ?? 0;
      $title = "Choose " . ($minNumber > 0 ? $maxNumber : "up to " . $maxNumber ) . " card" . ($maxNumber > 1 ? "s and click Submit:" : " and click Submit:");
      $subtitles = "";

      if($turnPhase == "MULTICHOOSEDECK"){
        $subtitles = "(You can click your deck to see its content during this card resolution)";
      }

      $dqHint = GetDQHelpText();
      $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : $title;

      $formOptions->playerID = $playerID;
      $formOptions->caption = "Submit";
      $formOptions->mode = 19;
      $optionsCount = count($options);
      $formOptions->maxNo = $optionsCount;
      $playerInputPopup->formOptions = $formOptions;

      $choiceOptions = "checkbox";
      $playerInputPopup->choiceOptions = $choiceOptions;

      if ($turnPhase == "MULTICHOOSETEXT" || $turnPhase == "MAYMULTICHOOSETEXT") {
        $defaultChecked = CheckboxDefaultState($options, $minNumber, $maxNumber);
        $multiChooseText = [];
        for ($i = 0; $i < $optionsCount; ++$i) {
          $multiChooseText[] = CreateCheckboxAPI($i, $i, -1, $defaultChecked, GamestateUnsanitize(strval($options[$i])));
        }
        $playerInputPopup->popup =  CreatePopupAPI("MULTICHOOSE", [], 0, 1, $caption, 1, $content);
        $playerInputPopup->multiChooseText = $multiChooseText;
      } else {
        $isMultiChooseDiscard  = ($turnPhase === "MULTICHOOSEDISCARD");
        $isMultiChooseSubcards = ($turnPhase === "MULTICHOOSESUBCARDS");
        $isMultiChooseItems    = ($turnPhase === "MULTICHOOSEITEMS");
        $multiZoneRef = null;
        if (!$isMultiChooseDiscard && !$isMultiChooseSubcards && !$isMultiChooseItems) {
          $multiZoneRef = match($turnPhase) {
            "MULTICHOOSETHEIRDISCARD" => $theirDiscard,
            "MULTICHOOSEHAND", "MAYMULTICHOOSEHAND" => $myHand,
            "MULTICHOOSEDECK" => $myDeck,
            "MULTICHOOSETHEIRDECK" => $theirDeck,
            "MULTICHOOSEBANISH" => $myBanish,
            default => null,
          };
        }

        for ($i = 0; $i < $optionsCount; ++$i) {
          if ($options[$i] != "") {
            if ($isMultiChooseDiscard) {
              $wateryGraveCounter = SearchLayersForTargetUniqueID($myDiscard[$options[$i]+1]) != -1;
              $cardsArray[] = JSONRenderedCard($myDiscard[$options[$i]], actionDataOverride: $i, wateryGraveIcon: $wateryGraveCounter);
            } else if ($isMultiChooseSubcards) {
              $cardsArray[] = JSONRenderedCard($options[$i], actionDataOverride: $i);
            } else if ($isMultiChooseItems) {
              $cardsArray[] = JSONRenderedCard($myItems[$options[$i]], overlay:$myItems[$options[$i]+2] != 2 ? 'disabled' : 'none', counters: $myItems[$options[$i]+1], actionDataOverride: $i);
            } else if ($multiZoneRef !== null) {
              $cardsArray[] = JSONRenderedCard($multiZoneRef[$options[$i]], actionDataOverride: $i);
            }
          }
        }
        $playerInputPopup->popup = CreatePopupAPI("MULTICHOOSE", [], 0, 1, $caption, 1, additionalComments: $subtitles, cardsArray: $cardsArray);
      }
      break;
    }

    case "MULTISHOWCARDSDECK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $cardsToShow = [];
        $options = explode(",", $turn[2]);
        $dqHint = GetDQHelpText();
        $caption = $dqHint !== "-" ? GamestateUnsanitize($dqHint) : "Cards from deck:";

      foreach ($options as $i => $option) {
        $cardsToShow[] = JSONRenderedCard($myDeck[$i], borderColor: 0, actionDataOverride: $i);
      }

      $playerInputPopup->popup = CreatePopupAPI("OK", [], 0, 1, $caption, 1, cardsArray: $cardsToShow);
        $playerInputButtons[] = CreateButtonAPI($playerID, "Ok", 99, "OK", "20px");
      }
      break;

    case "MULTISHOWCARDSTHEIRDECK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $cardsToShow = [];
        $options = explode(",", $turn[2]);
        $dqHint = GetDQHelpText();
        $caption = $dqHint !== "-" ? GamestateUnsanitize($dqHint) : "Cards from opponent's deck:";

      foreach ($options as $i => $option) {
        $cardsToShow[] = JSONRenderedCard($theirDeck[$i], borderColor: 0, actionDataOverride: $i);
      }

        $playerInputPopup->popup = CreatePopupAPI("OK", [], 0, 1, $caption, 1, cardsArray: $cardsToShow);
        $playerInputButtons[] = CreateButtonAPI($playerID, "Ok", 99, "OK", "20px");
      }
      break;

    case "CHOOSEMYSOUL":
    case "MAYCHOOSEMYSOUL":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $dqHint = GetDQHelpText();
        $caption = ($dqHint !== "-") ? GamestateUnsanitize($dqHint) : "Choose one of your soul:";
        $playerInputPopup->popup = ChoosePopup($mySoul, $turn[2], 16, $caption);
      }
      break;

    case "MAYCHOOSEMULTIZONE":
    case "CHOOSEMULTIZONE":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $turnData = $turn[2] ?? "";
        $options = $turnData != "" ? explode(",", $turnData) : [];
        $otherPlayer = $playerID == 2 ? 1 : 2;
        $theirAllies = &GetAllies($otherPlayer);
        $myAllies = &GetAllies($playerID);
        $cardsMultiZone = [];
        $maxCount = 0;
        $minCount = 0;
        $countOffset = 0;
        $subtitles = "";
        $source = [];
        $optionsCount = count($options);
        $attackingPermanentsSet = ['THEIRALLY' => true, 'THEIRAURAS' => true, 'MYALLY' => true, 'MYAURAS' => true];
        $combatChainCount = count($combatChain);
        $layerCheckCount = count($layers);
        $turnDataStartsWithMyDeck = (substr($turnData, 0, 6) === "MYDECK");
        $singleMyDeckInTurnData = (substr_count($turnData, "MYDECK") == 1);
        $hasCombatChainLink = $CombatChain->HasCurrentLink();
        $weaponIndexValue = intval($combatChainState[$CCS_WeaponIndex]);
        $otherIsMain = ($otherPlayer == $mainPlayer);
        $combatChainPieces = CombatChainPieces();
        $layerPieces = LayerPieces();
        $layersActive = ($layerCheckCount > 0 && $layers[0] != "");
        $isMayChooseMultizone = ($turnPhase === "MAYCHOOSEMULTIZONE");
        $combatChainFirst = $combatChainCount > 0 ? $combatChain[0] : null;
        $combatChainLast = $combatChainCount > 0 ? $combatChain[$combatChainCount - $combatChainPieces] : null;
        for ($i = 0; $i < $optionsCount; ++$i) {
          $option = explode("-", $options[$i]);
          $option0 = $option[0]; // cache zone key — accessed 30+ times per iteration
          $isMyPrefix = str_starts_with($option0, "MY");
          $isTheirPrefix = str_starts_with($option0, "THEIR");
          switch($option0) {
            case "MYAURAS":
              $source = $myAuras;
              break;
            case "THEIRAURAS":
              $source = $theirAuras;
              break;
            case "MYCHAR":
              $source = $myCharacter;
              break;
            case "THEIRCHAR":
              $source = $theirCharacter;
              break;
            case "MYITEMS":
              $source = $myItems;
              break;
            case "THEIRITEMS":
              $source = $theirItems;
              break;
            case "LAYER":
              $source = $layers;
              break;
            case "MYHAND":
              $source = $myHand;
              break;
            case "THEIRHAND":
              $source = $theirHand;
              break;
            case "MYARSENAL":
              $source = $myArsenal;
              break;
            case "THEIRARSENAL":
              $source = $theirArsenal;
              break;
            case "MYDISCARD":
              $source = $myDiscard;
              break;
            case "THEIRDISCARD":
              $source = $theirDiscard;
              break;
            case "MYBANISH":
              $source = $myBanish;
              break;
            case "THEIRBANISH":
              $source = $theirBanish;
              break;
            case "MYALLY":
              $source = $myAllies;
              break;
            case "THEIRALLY":
              $source = $theirAllies;
              break;
            case "MYARS":
              $source = $myArsenal;
              break;
            case "THEIRARS":
              $source = $theirArsenal;
              break;
            case "MYPERM":
              $source = $myPermanents;
              break;
            case "THEIRPERM":
              $source = $theirPermanents;
              break;
            case "MYPITCH":
              $source = $myPitch;
              break;
            case "THEIRPITCH":
              $source = $theirPitch;
              break;
            case "MYDECK":
              $source = $myDeck;
              break;
            case "THEIRDECK":
              $source = $theirDeck;
              break;
            case "MYSOUL":
              $source = $mySoul;
              break;
            case "THEIRSOUL":
              $source = $theirSoul;
              break;
            case "LANDMARK":
              $source = $landmarks;
              break;
            case "CC":
              $source = $combatChain;
              break;
            case "COMBATCHAINLINK":
              $source = $combatChain;
              break;
            case "COMBATCHAINATTACKS":
              $source = GetCombatChainAttacks();
              break;
            case "PASTCHAINLINK":
              $source = $chainLinks[$option[2]];
              break;
            case "PRELAYERS":
              $source = GetPreLayers();
              break;
            case "MAXCOUNT":
              $maxCount = intval($option[1]);
              $countOffset++;
              continue 2;
            case "MINCOUNT":
              $minCount = intval($option[1]);
              $countOffset++;
              continue 2;
            case "CURRENTTURNEFFECTS":
              $source = $currentTurnEffects;
              break;
            case "ATTACKQUEUE":
              $source = $attackQueue;
              break;
            case "CARDID":
              break;
            default:
              WriteLog("An unexpected input $option0 was sent to CHOOSEMULTIZONE, please submit a bug report", highlight:true);
              break;
          }
          $counters = 0;
          $lifeCounters = 0;
          $enduranceCounters = 0;
          $powerCounters = 0;
          $steamCounters = 0;
          $borderColor = 0;
          $uniqueIDIndex = -1;
          $label = "";
          $tapped = false;
          $overlay = 0;
          $holoCounters = null;
          //Add indication for token copies
          if (str_contains($option0, "AURAS")) {
            $Card = MZIndexToObject($playerID, $options[$i]);
            if ($Card->IsToken() && !TypeContains($Card->CardID(), "T")) $label = "Token Copy";
          }
          
          //Add indication for attacking Allies and Auras with an open combat chain
          if (isset($attackingPermanentsSet[$option0]) && intval($option[1]) == $weaponIndexValue && $hasCombatChainLink && $otherIsMain) {
            $AttackingCard = $CombatChain->AttackCard();
            $Card = MZIndexToObject($playerID, $options[$i]);
            if ($AttackingCard->OriginUniqueID() == $Card->UniqueID())
              $label = "Attacking";
          }

          //Add indication for attacking Allies and Auras in the layer step
          if ($layersActive && (DelimStringContains($option0, "ALLY", true) || DelimStringContains($option0, "AURAS", true))) {
            $searchType = str_contains($option0, "ALLY") ? "Ally" : "Aura";
            $index = explode(",", SearchLayer($otherPlayer, subtype: $searchType));
            if (count($index) > 0) {
              $params = explode("|", $layers[intval($index[0]) + 2]);
              $originUID = $params[3] ?? "-";
              $Card = MZIndexToObject($playerID, $options[$i]);
              if ($originUID == $Card->UniqueID()) {
                $label = "Attacking";
              }
            }
          }
          //Add indication for layers targets
          if ($layersActive && ($option0 == "MYDISCARD" || $option0 == "THEIRDISCARD")) {
            for ($j = 0; $j < $layerCheckCount; $j += $layerPieces) {
              $target = $option0."-".($option[1] ?? "");
              $cardID = GetMZCard($currentPlayer, $target);
              $params = explode("-", $layers[$j + 3]);
              if(isset($params[1])) {
                $uniqueIDIndex = ($option0 == "MYDISCARD") ? SearchDiscardForUniqueID($params[1], $currentPlayer) : SearchDiscardForUniqueID($params[1], $layers[$j + 1]);
              }
              if($uniqueIDIndex != -1 && isset($source[$uniqueIDIndex]) && $cardID == $source[$uniqueIDIndex]) {
                $label = "Targeted";
                continue;
              }
            }   
          }

          if ($layersActive && $option0 == "LAYER" && $option[1] == 0) {
            // $params = explode("-", $layers[$j + 3]);
            $params = explode("-", $layers[3]);
            $target = $option0."-".$option[1];
            $cardID = GetMZCard($currentPlayer, $target);
            if($cardID == "runechant") {
              $label = "Amp " . CurrentEffectArcaneModifier($source, $otherPlayer, skipRemove:true);
            }
          }

          //Bonds of Agony - add indication for hand, graveyard and deck
          if ($isMayChooseMultizone && $combatChainCount > 0) {
            if ($combatChainFirst == "bonds_of_agony_blue") {
              switch ($option0) {
                  case "THEIRHAND":
                      $label = "Hand";
                      break;
                  case "THEIRDECK":
                      $label = "Deck";
                      break;
                  case "THEIRDISCARD":
                      $label = "Graveyard";
                      break;
              }  
            }
            if ($combatChainLast == "hunter_or_hunted_blue") {
              switch ($option0) {
                  case "THEIRHAND":
                      $label = "Hand";
                      break;
                  case "THEIRDECK":
                      $label = "Deck";
                      break;
                  case "THEIRDISCARD":
                      $label = "Graveyard";
                      break;
                  case "THEIRARSENAL":
                      $label = "Arsenal";
                      break;
              }  
            }
          }

          //Add indication for Crown of Providence if you have the same card in hand and in the arsenal.
          if ($option0 == "MYARS") $label = "Arsenal";
          //Add indication for past chain links
          if ($option0 == "PASTCHAINLINK") $label = "Chain link " . ($option[2] + 1);
          //Add indication for Attacking Mechanoid
          if ($option0 == "CC" || $option0 == "LAYER") {
            $mzCardID = GetMZCard($currentPlayer, $options[$i]);
            if ($mzCardID == "nitro_mechanoida" || $mzCardID == "teklovossen_the_mechropotenta") $label = "Attacking";
          }

          $index = intval($option[1] ?? 0);
          $card = ($option0 != "CARDID" && isset($source[$index])) ? $source[$index] : ($option[1] ?? 0);
          if (($option0 == "LAYER" || $option0 == "PRELAYERS") && ($card == "TRIGGER" || $card == "MELD" || $card == "PRETRIGGER" || $card == "ABILITY" || $card == "ATTACK")) $card = $source[$index + 2];

          if ($option0 == "THEIRBANISH") {
            $mod = explode("-", $theirBanish[$index + 1], 2)[0];
            $action = IsPlayable($card, $turn[0], "BANISH", $index, player:$otherPlayer) ? 14 : 0;
            $borderColor = CardBorderColor($card, "BANISH", $action > 0, $playerID, $mod);
            if($borderColor == 7) $label = "Playable";
            if (isFaceDownMod($source[$index + 1])) $card = $TheirCardBack;
          }
          else if ($isMyPrefix) $borderColor = 1;
          else if ($isTheirPrefix) $borderColor = 2;
          else if ($option0 == "CC") $borderColor = $combatChain[$index + 1] == $playerID ? 1 : 2;
          else if ($option0 == "LAYER" || $option0 == "PRELAYERS") {
            $borderColor = $source[$index + 1] == $playerID ? 1 : 2;
          }
          else if ($option0 == "COMBATCHAINATTACKS") {
            $borderColor = 1;
          }
          if ($option0 == "COMBATCHAINLINK"){
            $borderColor = $combatChain[$index + 1] == $playerID ? 1 : 2;
            if ($combatChain[$index + 6] > 0) $enduranceCounters = $combatChain[$index + 6];
          }

          if ($option0 == "THEIRCHAR" || $option0 == "MYCHAR") {
            $isTheirChar = ($option0 == "THEIRCHAR");
            $charArr = $isTheirChar ? $theirCharacter : $myCharacter;
            $tapped = $charArr[$index + 14] == 1;
            $powerCounters = $charArr[$index + 3];
            $overlay = $charArr[$index + 1] != 2;
          }
          if ($option0 == "THEIRARS" && $theirArsenal[$index + 1] == "DOWN" || $option0 == "THEIRCHAR" && $theirCharacter[$option[1] + 12] == "DOWN") {
            $card = $TheirCardBack;
            switch ($option0) {
              case "THEIRARS":
                $label = "Arsenal";
                break;
              case "THEIRCHAR":
                $label = "Equip-".CardSubType($theirCharacter[$option[1]]);
                break;
              default:
                break;
            }
          }

          if($option0 == "CURRENTTURNEFFECTS") {
            $cardID = explode("-", $source[$index], 2)[0];
            $card = $cardID;
          }

          //Show Life and Def counters on allies in the popups
          if ($option0 == "THEIRALLY" || $option0 == "MYALLY") {
            $isTheirAlly = ($option0 == "THEIRALLY");
            $allyArr = $isTheirAlly ? $theirAllies : $myAllies;
            $player = $isTheirAlly ? $otherPlayer : $playerID;
            $index = intval($option[1]);
            $lifeCounters = $allyArr[$index + 2];
            $enduranceCounters = $allyArr[$index + 6];
            $powerCounters = $allyArr[$index + 9];
            $uniqueID = $allyArr[$index + 5];
            $tapped = $allyArr[$index + 11] == 1;
            if (SearchCurrentTurnEffectsForUniqueID($uniqueID) != -1) {
                $powerCounters = EffectPowerModifier(SearchUniqueIDForCurrentTurnEffects($uniqueID)) + PowerValue($allyArr[$index], $player, "ALLY");
            }
          }

          if ($option0 == "THEIRAURAS" || $option0 == "MYAURAS") {
            $AuraCard = $isTheirPrefix ? new AuraCard($index, $otherPlayer) : new AuraCard($index, $playerID);
            //Show power counters on Auras in the popups
            $powerCounters = $AuraCard->NumPowerCounters();
            //Show various counters on Auras in the popups
            $counters = $AuraCard->NumCounters();
            //Show holo counters on Auras in the popups
            $holoCounters = $AuraCard->HoloCounters() > 0 ? true : null;
            //Show "stolen" modifier
            if ($AuraCard->GetModalities() == "Temporary") $label = "stolen";
            //Show if it's been targeted
            $numStackLayers = $Stack->NumLayers();
            for ($j = 0; $j < $numStackLayers; ++$j) {
              $Layer = $Stack->Card($j, true);
              if (str_contains($Layer->Target(), $AuraCard->UniqueID())) {
                $label = "Targeted";
                continue;
              }
            }
          }
          //Show Steam Counters on items
          if ($option0 == "THEIRITEMS" || $option0 == "MYITEMS") {
            $isTheirItems = ($option0 == "THEIRITEMS");
            $itemArr = $isTheirItems ? $theirItems : $myItems;
            $steamCounters = $itemArr[$index + 1];
            $tapped = $itemArr[$index + 10] == 1;
            $itemLabel = $itemArr[$index + 8];
            $label = ($itemLabel !== "" && $itemLabel !== "-") ? GamestateUnsanitize($itemLabel) : "";
          }

          if ($option0 == "MYBANISH") {
            $index = intval($option[1]);
            $cardID = GetMZCard($currentPlayer, $option0."-".$option[1]);
            $uniqueID = $myBanish[$index + 2];
            $label = GetCardEffectLabel($uniqueID, $currentTurnEffects);
          }
          
          //Show Subtitles on MyDeck
          if($turnDataStartsWithMyDeck && $turnData != "MYDECK-0"){
            $subtitles = "(You can click your deck to see its content during this card resolution)";
          }

          if($option0 == "MYDECK" && $option[1] == "0" && $isMayChooseMultizone && $singleMyDeckInTurnData) {
            $card = $MyCardBack;
          }
          if ($maxCount < 2)
            $cardsMultiZone[] = JSONRenderedCard($card, action: 16, overlay: $overlay, borderColor: $borderColor, counters: $counters, actionDataOverride: $options[$i], lifeCounters: $lifeCounters, defCounters: $enduranceCounters, powerCounters: $powerCounters, controller: $borderColor, label: $label, steamCounters: $steamCounters, tapped: $tapped, isOpponent: $isTheirPrefix, holoCounters: $holoCounters);
          else
            $cardsMultiZone[] = JSONRenderedCard($card, overlay: $overlay, actionDataOverride: $i - $countOffset);
        }
        if ($maxCount >= 2) {
          $formOptions = new stdClass();
          $formOptions->playerID = $playerID;
          $formOptions->caption = "Submit";
          $formOptions->mode = 19;
          $formOptions->maxNo = count($options);
          $playerInputPopup->formOptions = $formOptions;
          $choiceOptions = "checkbox";
          $playerInputPopup->choiceOptions = $choiceOptions;
        }
        $playerInputPopup->popup = CreatePopupAPI("CHOOSEMULTIZONE", [], 0, 1, GetPhaseHelptext(), 1, additionalComments: $subtitles,cardsArray: $cardsMultiZone);
    }
    break;
  }

  $playerInputPopup->buttons = $playerInputButtons;
  return $playerInputPopup;
}


function CheckboxDefaultState($options, $minNumber = 0, $maxNumber = 0) {
  static $presets = [
    "blood_on_her_hands" => [
      "min" => 0,
      "max" => 6,
      "options" => ["Buff_Weapon", "Buff_Weapon", "Go_Again", "Go_Again", "Attack_Twice", "Attack_Twice"]
    ],
    "battlefield_beacon_yellow" => [
      "min" => 9,
      "max" => 9,
      "options" => ["Create_a_Courage_token", "Create_a_Courage_token", "Create_a_Courage_token", "Create_a_Toughness_token", "Create_a_Toughness_token", "Create_a_Toughness_token", "Create_a_Vigor_token", "Create_a_Vigor_token", "Create_a_Vigor_token"]
    ],
  ];

  $optionsCount = count($options);
  foreach ($presets as $preset) {
    if ($maxNumber === $preset["max"] && $minNumber === $preset["min"] && $optionsCount === count($preset["options"])) {
      return true;
    }
  }

  return false;
}

/**
 * Helper for creating popups
 */
function ChoosePopup($zone, $options, $mode, $caption = "", $additionalComments = "", $MZName = "", $label = "")
{
  $options = explode(",", $options);
  $optionsCount = count($options);
  $cardList = [];
  for ($i = 0; $i < $optionsCount; ++$i) {
    if($MZName == "ARSENAL" && isset($zone[$options[$i]+1]) && $zone[$options[$i]+1] == "DOWN") $label = "Face Down";
    if (isset($zone[$options[$i]])) {
      $cardList[] = JSONRenderedCard($zone[$options[$i]], action: $mode, actionDataOverride: strval($options[$i]), label: $label);
    }
  }

  return CreatePopupAPI("CHOOSEZONE", [], 0, 1, $caption, 1, "", additionalComments: $additionalComments, cardsArray: $cardList);
}
