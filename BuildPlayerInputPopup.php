<?php

/**
 * BuildPlayerInputPopup.php
 *
 * Builds the player input popup structure for the game state.
 * Extracted from GetNextTurn.php for reuse in both HTTP and SSE contexts.
 */

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
  global $theirAllies, $myAllies;

  $playerInputPopup = new stdClass();
  $playerInputButtons = [];
  $playerInputPopup->active = false;

  switch ($turnPhase) {
    case "BUTTONINPUT":
    case "BUTTONINPUTNOPASS":
    case "CHOOSEARCANE":
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
          array_push($playerInputButtons, CreateButtonAPI($playerID, str_replace("_", " ", $option), 17, strval($option), "24px"));
        }

        if(isset($vars[1]) && $vars[1] == "runechant") {
          array_push($playerInputButtons, CreateButtonAPI($playerID, "Skip All Runechants", 105, 0, "24px"));
        }

        $playerInputPopup->popup = CreatePopupAPI("BUTTONINPUT", [], 0, 1, $caption . GetPhaseHelptext(), 1, "");
      }
      break;

    case "YESNO":
    case "DOCRANK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        array_push($playerInputButtons, CreateButtonAPI($playerID, "Yes", 20, "YES", "20px"));
        array_push($playerInputButtons, CreateButtonAPI($playerID, "No", 20, "NO", "20px"));
        $playerInputPopup->popup = CreatePopupAPI("YESNO", [], 0, 1, GetPhaseHelptext(), 1, "");
      }
      break;

    case "PDECK":
      if ($currentPlayer == $playerID) {
        $playerInputPopup->active = true;
        $pitchingCards = [];
        foreach ($myPitch as $card) {
          array_push($pitchingCards, JSONRenderedCard($card, action: 6, actionDataOverride: $card));
        }
        $playerInputPopup->popup = CreatePopupAPI("PITCH", [], 0, 1, "Choose a card from your pitch zone to put on the bottom of your deck", 1, cardsArray: $pitchingCards);
      }
      break;

    case "DYNPITCH":
    case "CHOOSENUMBER":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $options = explode(",", $turn[2]);
        foreach ($options as $option) {
          array_push($playerInputButtons, CreateButtonAPI($playerID, $option, 7, $option, "24px"));
        }
        $playerInputPopup->popup = CreatePopupAPI($turn[0], [], 0, 1, GetPhaseHelptext(), 1, "");
      }
      break;

    case "OK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        array_push($playerInputButtons, CreateButtonAPI($playerID, "Ok", 99, "OK", "20px"));
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
        foreach ($options as $option) {
          array_push($optCards, JSONRenderedCard($option, action: 0));
          $buttonText = match($turn[0]) {
            "CHOOSETOP" => "Top",
            "CHOOSEBOTTOM" => "Bottom",
            "CHOOSECARD", "MAYCHOOSECARD" => "Choose"
          };
          array_push($playerInputButtons, CreateButtonAPI($playerID, $buttonText, match($turn[0]) {
            "CHOOSETOP" => 8,
            "CHOOSEBOTTOM" => 9,
            default => 23
          }, $option, "20px"));
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

        $playerInputPopup->popup = CreatePopupAPI("NEWOPT", [], 0, 1, "Drag cards to add to the top or bottom of the deck", 1, "", topCards: $topOptCards, bottomCards: $bottomOptCards);
      }
      break;

    case "ORDERTRIGGERS":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $orderedLayers = [];
        $layersList = array_filter(explode(",", $turn[2] ?? ""));
        foreach($layersList as $layer) {
          $ID = explode("|", $layer)[0] ?? "-";
          $UID = explode("|", $layer)[1] ?? "-";
          array_push($orderedLayers, JSONRenderedCard($ID, uniqueID:$UID, action: 0));
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
          array_push($optCards, JSONRenderedCard($option, action: 0, isOpponent: true));
          array_push($playerInputButtons, CreateButtonAPI($otherPlayer, "Top", 29, $option, "20px"));
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
          array_push($cardsArray, JSONRenderedCard($card, action: 0));
          array_push($playerInputButtons, CreateButtonAPI($playerID, "Top", 12, $card, "20px"));
          array_push($playerInputButtons, CreateButtonAPI($playerID, "Bottom", 13, $card, "20px"));
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
          array_push($cardList, JSONRenderedCard($options[$i], action: 16, actionDataOverride: strval($options[$i])));
        }
        $playerInputPopup->popup = CreatePopupAPI("CHOOSEZONE", [], 0, 1, GetPhaseHelptext(), 1, "", cardsArray: $cardList);
      }
      break;

    case "MAYCHOOSEDECK":
    case "CHOOSEDECK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your deck:";
        $playerInputPopup->popup = ChoosePopup($myDeck, $turn[2], 11, $caption, "(You can click your deck to see its content during this card resolution)");
      }
      break;

    case "MAYCHOOSETHEIRDECK":
    case "CHOOSETHEIRDECK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your opponent deck:";
        $playerInputPopup->popup = ChoosePopup($theirDeck, $turn[2], 11, $caption);
      }
      break;

    case "CHOOSEBANISH":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your banish:";
        $playerInputPopup->popup = ChoosePopup($myBanish, $turn[2], 16, $caption);
      }
      break;

    case "MAYCHOOSEARSENAL":
    case "CHOOSEARSENAL":
    case "CHOOSEARSENALCANCEL":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your arsenal:";
        $playerInputPopup->popup = ChoosePopup($myArsenal, $turn[2], 16, $caption, "", "ARSENAL");
      }
      break;

    case "CHOOSEPERMANENT":
    case "MAYCHOOSEPERMANENT":
      if ($turn[1] == $playerID) {
        $myPermanents = &GetPermanents($playerID);
        $playerInputPopup->active = true;
        $playerInputPopup->popup = ChoosePopup($myPermanents, $turn[2], 16, GetPhaseHelptext());
      }
      break;

    case "CHOOSETHEIRHAND":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your opponent's hand:";
        $playerInputPopup->popup = ChoosePopup($theirHand, $turn[2], 16, $caption);
      }
      break;

    case "CHOOSEMYAURA":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose one of your auras:";
        $playerInputPopup->popup = ChoosePopup($myAuras, $turn[2], 16, $caption);
      }
      break;

    case "CHOOSEDISCARD":
    case "MAYCHOOSEDISCARD":
    case "CHOOSEDISCARDCANCEL":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your graveyard:";
        $playerInputPopup->popup = ChoosePopup($myDiscard, $turn[2], 16, $caption);
      }
      break;

    case "MAYCHOOSETHEIRDISCARD":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your opponent's graveyard:";
        $playerInputPopup->popup = ChoosePopup($theirDiscard, $turn[2], 16, $caption);
      }
      break;

    case "CHOOSECOMBATCHAIN":
    case "MAYCHOOSECOMBATCHAIN":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from the combat chain:";
        $playerInputPopup->popup = ChoosePopup($combatChain, $turn[2], 16, $caption);
      }
      break;

    case "CHOOSECHARACTER":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your character/equipment:";
        $playerInputPopup->popup = ChoosePopup($myCharacter, $turn[2], 16, $caption);
      }
      break;

    case "CHOOSETHEIRCHARACTER":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose a card from your opponent character/equipment:";
        $playerInputPopup->popup = ChoosePopup($theirCharacter, $turn[2], 16, $caption);
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
      $minNumber = count($params) > 2 ? intval($params[2]) : 0;
      $title = "Choose " . ($minNumber > 0 ? $maxNumber : "up to " . $maxNumber ) . " card" . ($maxNumber > 1 ? "s and click Submit:" : " and click Submit:");
      $subtitles = "";

      if($turnPhase == "MULTICHOOSEDECK"){
        $subtitles = "(You can click your deck to see its content during this card resolution)";
      }

      $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : $title;

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
          array_push($multiChooseText, CreateCheckboxAPI($i, $i, -1, $defaultChecked, GamestateUnsanitize(strval($options[$i]))));
        }
        $playerInputPopup->popup =  CreatePopupAPI("MULTICHOOSE", [], 0, 1, $caption, 1, $content);
        $playerInputPopup->multiChooseText = $multiChooseText;
      } else {
        for ($i = 0; $i < $optionsCount; ++$i) {
          if ($options[$i] != "") {
            if ($turnPhase == "MULTICHOOSEDISCARD") {
              $wateryGraveCounter = false;
              if (SearchLayersForTargetUniqueID($myDiscard[$options[$i]+1]) != -1) {
                $wateryGraveCounter = true;
              }
              array_push($cardsArray, JSONRenderedCard($myDiscard[$options[$i]], actionDataOverride: $i, wateryGraveIcon: $wateryGraveCounter));
            }
            else if ($turnPhase == "MULTICHOOSETHEIRDISCARD") array_push($cardsArray, JSONRenderedCard($theirDiscard[$options[$i]], actionDataOverride: $i));
            else if ($turnPhase == "MULTICHOOSEHAND" || $turnPhase == "MAYMULTICHOOSEHAND") array_push($cardsArray, JSONRenderedCard($myHand[$options[$i]], actionDataOverride: $i));
            else if ($turnPhase == "MULTICHOOSEDECK") array_push($cardsArray, JSONRenderedCard($myDeck[$options[$i]], actionDataOverride: $i));
            else if ($turnPhase == "MULTICHOOSETHEIRDECK") array_push($cardsArray, JSONRenderedCard($theirDeck[$options[$i]], actionDataOverride: $i));
            else if ($turnPhase == "MULTICHOOSEBANISH") array_push($cardsArray, JSONRenderedCard($myBanish[$options[$i]], actionDataOverride: $i));
            else if ($turnPhase == "MULTICHOOSEITEMS") array_push($cardsArray, JSONRenderedCard($myItems[$options[$i]], overlay:$myItems[$options[$i]+2] != 2 ? 'disabled' : 'none', counters: $myItems[$options[$i]+1], actionDataOverride: $i));
            else if ($turnPhase == "MULTICHOOSESUBCARDS") array_push($cardsArray, JSONRenderedCard($options[$i], actionDataOverride: $i));
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
        $caption = GetDQHelpText() != "-" ? GamestateUnsanitize(GetDQHelpText()) : "Cards from deck:";

      foreach ($options as $i => $option) {
        $cardsToShow[] = JSONRenderedCard($myDeck[$i], borderColor: 0, actionDataOverride: $i);
      }

      $playerInputPopup->popup = CreatePopupAPI("OK", [], 0, 1, $caption, 1, cardsArray: $cardsToShow);
        array_push($playerInputButtons, CreateButtonAPI($playerID, "Ok", 99, "OK", "20px"));
      }
      break;

    case "MULTISHOWCARDSTHEIRDECK":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $cardsToShow = [];
        $options = explode(",", $turn[2]);
        $caption = GetDQHelpText() != "-" ? GamestateUnsanitize(GetDQHelpText()) : "Cards from opponent's deck:";

      foreach ($options as $i => $option) {
        $cardsToShow[] = JSONRenderedCard($theirDeck[$i], borderColor: 0, actionDataOverride: $i);
      }

        $playerInputPopup->popup = CreatePopupAPI("OK", [], 0, 1, $caption, 1, cardsArray: $cardsToShow);
        array_push($playerInputButtons, CreateButtonAPI($playerID, "Ok", 99, "OK", "20px"));
      }
      break;

    case "CHOOSEMYSOUL":
    case "MAYCHOOSEMYSOUL":
      if ($turn[1] == $playerID) {
        $playerInputPopup->active = true;
        $caption = (GetDQHelpText() != "-") ? GamestateUnsanitize(GetDQHelpText()) : "Choose one of your soul:";
        $playerInputPopup->popup = ChoosePopup($mySoul, $turn[2], 16, $caption);
      }
      break;

    // For CHOOSEMULTIZONE and MAYCHOOSEMULTIZONE, we'll provide a simplified version
    // The full version is very complex and may need to be kept in GetNextTurn.php
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
        for ($i = 0; $i < $optionsCount; ++$i) {
          $option = explode("-", $options[$i]);
          if ($option[0] == "MYAURAS") $source = $myAuras;
          else if ($option[0] == "THEIRAURAS") $source = $theirAuras;
          else if ($option[0] == "MYCHAR") $source = $myCharacter;
          else if ($option[0] == "THEIRCHAR") $source = $theirCharacter;
          else if ($option[0] == "MYITEMS") $source = $myItems;
          else if ($option[0] == "THEIRITEMS") $source = $theirItems;
          else if ($option[0] == "LAYER") $source = $layers;
          else if ($option[0] == "MYHAND") $source = $myHand;
          else if ($option[0] == "THEIRHAND") $source = $theirHand;
          else if ($option[0] == "MYARSENAL") $source = $myArsenal;
          else if ($option[0] == "THEIRARSENAL") $source = $theirArsenal;
          else if ($option[0] == "MYDISCARD" || $option[0] == "MYDISCARDUID") $source = $myDiscard;
          else if ($option[0] == "THEIRDISCARD" || $option[0] == "THEIRDISCARDUID") $source = $theirDiscard;
          else if ($option[0] == "MYBANISH") $source = $myBanish;
          else if ($option[0] == "THEIRBANISH") $source = $theirBanish;
          else if ($option[0] == "MYALLY") $source = $myAllies;
          else if ($option[0] == "THEIRALLY") $source = $theirAllies;
          else if ($option[0] == "MYARS") $source = $myArsenal;
          else if ($option[0] == "THEIRARS") $source = $theirArsenal;
          else if ($option[0] == "MYPERM") $source = $myPermanents;
          else if ($option[0] == "THEIRPERM") $source = $theirPermanents;
          else if ($option[0] == "MYPITCH") $source = $myPitch;
          else if ($option[0] == "THEIRPITCH") $source = $theirPitch;
          else if ($option[0] == "MYDECK") $source = $myDeck;
          else if ($option[0] == "THEIRDECK") $source = $theirDeck;
          else if ($option[0] == "MYSOUL") $source = $mySoul;
          else if ($option[0] == "THEIRSOUL") $source = $theirSoul;
          else if ($option[0] == "LANDMARK") $source = $landmarks;
          else if ($option[0] == "CC") $source = $combatChain;
          else if ($option[0] == "COMBATCHAINLINK") $source = $combatChain;
          else if ($option[0] == "COMBATCHAINATTACKS") $source = GetCombatChainAttacks();
          else if ($option[0] == "PASTCHAINLINK") $source = $chainLinks[$option[2]];
          else if ($option[0] == "PRELAYERS") $source = GetPreLayers();
          else if ($option[0] == "MAXCOUNT") {$maxCount = intval($option[1]); $countOffset++; continue;}
          else if ($option[0] == "MINCOUNT") {$minCount = intval($option[1]); $countOffset++; continue;}
          else if ($option[0] == "CURRENTTURNEFFECTS") $source = $currentTurnEffects;
          $counters = 0;
          $lifeCounters = 0;
          $enduranceCounters = 0;
          $powerCounters = 0;
          $steamCounters = 0;
          $borderColor = 0;
          $uniqueIDIndex = -1;
          $label = "";
          $tapped = false;

          if (($option[0] == "THEIRALLY" || $option[0] == "THEIRAURAS") && intval($option[1]) == intval($combatChainState[$CCS_WeaponIndex]) && $CombatChain->HasCurrentLink() && $otherPlayer == $mainPlayer) $label = "Attacking";
          if (($option[0] == "MYALLY" || $option[0] == "MYAURAS") && intval($option[1]) == intval($combatChainState[$CCS_WeaponIndex]) && $CombatChain->HasCurrentLink() && $playerID == $mainPlayer) $label = "Attacking";

          //Add indication for attacking Allies and Auras
          $layerCheckCount = count($layers);
          if ($layerCheckCount > 0 && $layers[0] != "") {
            $searchType = $option[0] == "THEIRALLY" || $option[0] == "MYALLY" ? "Ally" : "Aura";
            $index = explode(",", SearchLayer($otherPlayer, subtype: $searchType));
            if ($index != "" && (DelimStringContains($option[0], "ALLY", true) || DelimStringContains($option[0], "AURAS", true))) {
                $params = explode("|", $layers[intval($index[0]) + 2]);              
                if (isset($params[2]) && $option[1] == $params[2]) {
                  $label = "Attacking";
                }
            }
          }
          //Add indication for layers targets
          if ($layerCheckCount > 0 && $layers[0] != "" && ($option[0] == "MYDISCARD" || $option[0] == "THEIRDISCARD")) {
            $countLayers = count($layers);
            for ($j=0; $j < $countLayers; $j += LayerPieces()) { 
              $target = $option[0]."-".$option[1];
              $cardID = GetMZCard($currentPlayer, $target);
              $params = explode("-", $layers[$j + 3]);
              if(isset($params[1])) {
                $uniqueIDIndex = ($option[0] == "MYDISCARD") ? SearchDiscardForUniqueID($params[1], $currentPlayer) : SearchDiscardForUniqueID($params[1], $layers[$j + 1]);
              }
              if($uniqueIDIndex != -1 && isset($source[$uniqueIDIndex]) && $cardID == $source[$uniqueIDIndex]) {
                $label = "Targeted";
                continue;
              }
            }   
          }

          if ($layerCheckCount > 0 && $layers[0] != "" && $option[0] == "LAYER" && $option[1] == 0) {
            $params = explode("-", $layers[$j + 3]);
            $target = $option[0]."-".$option[1];
            $cardID = GetMZCard($currentPlayer, $target);
            if($cardID == "runechant") {
              $label = "Amp " . CurrentEffectArcaneModifier($source, $otherPlayer, skipRemove:true);
            }
          }

          //Bonds of Agony - add indication for hand, graveyard and deck
          $combatChainCount = count($combatChain);
          if($combatChainCount > 0) {
            if($combatChain[0] == "bonds_of_agony_blue" && $turnPhase == "MAYCHOOSEMULTIZONE") {
              switch ($option[0]) {
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
            if($combatChain[$combatChainCount - CombatChainPieces()] == "hunter_or_hunted_blue" && $turnPhase == "MAYCHOOSEMULTIZONE") {
              switch ($option[0]) {
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
          if ($option[0] == "MYARS") $label = "Arsenal";
          //Add indication for past chain links
          if ($option[0] == "PASTCHAINLINK") $label = "Chain link " . $option[2]+1;
          //Add indication for Attacking Mechanoid
          if (($option[0] == "CC" || $option[0] == "LAYER") && (GetMZCard($currentPlayer, $options[$i]) == "nitro_mechanoida" || GetMZCard($currentPlayer, $options[$i]) == "teklovossen_the_mechropotenta")) $label = "Attacking";

          $index = intval($option[1] ?? 0);
          $card = ($option[0] != "CARDID" && isset($source[$index])) ? $source[$index] : ($option[1] ?? 0);
          if (($option[0] == "LAYER" || $option[0] == "PRELAYERS") && ($card == "TRIGGER" || $card == "MELD" || $card == "PRETRIGGER" || $card == "ABILITY")) $card = $source[$index + 2];

          if ($option[0] == "THEIRBANISH") {
            $mod = explode("-", $theirBanish[$index + 1])[0];
            $action = IsPlayable($card, $turn[0], "BANISH", $index, player:$otherPlayer) ? 14 : 0;
            $borderColor = CardBorderColor($card, "BANISH", $action > 0, $playerID, $mod);
            if($borderColor == 7) $label = "Playable";
            if (isFaceDownMod($source[$index + 1])) $card = $TheirCardBack;
          }
          else if (substr($option[0], 0, 2) == "MY") $borderColor = 1;
          else if (substr($option[0], 0, 5) == "THEIR") $borderColor = 2;
          else if ($option[0] == "CC") $borderColor = $combatChain[$index + 1] == $playerID ? 1 : 2;
          else if ($option[0] == "LAYER" || $option[0] == "PRELAYERS") {
            $borderColor = $source[$index + 1] == $playerID ? 1 : 2;
          }
          else if ($option[0] == "COMBATCHAINATTACKS") {
            $borderColor = 1;
          }
          if ($option[0] == "COMBATCHAINLINK"){
            $borderColor = $combatChain[$index + 1] == $playerID ? 1 : 2;
          }

          if ($option[0] == "THEIRCHAR" || $option[0] == "MYCHAR") {
            $tapped = $option[0] == "THEIRCHAR" ? $theirCharacter[$index + 14] == 1 : $myCharacter[$index + 14] == 1;
            $powerCounters = $option[0] == "MYCHAR" ? $myCharacter[$index + 3] : $theirCharacter[$index + 3];
          }

          if (($option[0] == "THEIRARS" && $theirArsenal[$index + 1] == "DOWN") || ($option[0] == "THEIRCHAR" && $theirCharacter[$option[1] + 12] == "DOWN")) {
            $card = $TheirCardBack;
            switch ($option[0]) {
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

          if($option[0] == "CURRENTTURNEFFECTS") {
            $cardID = explode("-", $source[$index])[0];
            $card = $cardID;
          }

          //Show Life and Def counters on allies in the popups
          if ($option[0] == "THEIRALLY" || $option[0] == "MYALLY") {
            $player = $option[0] == "THEIRALLY" ? $otherPlayer : $playerID;
            $index = intval($option[1]);
            $lifeCounters = $option[0] == "THEIRALLY" ? $theirAllies[$index + 2] : $myAllies[$index + 2];
            $enduranceCounters = $option[0] == "THEIRALLY" ? $theirAllies[$index + 6] : $myAllies[$index + 6];
            $powerCounters =  $option[0] == "THEIRALLY" ? $theirAllies[$index + 9] : $myAllies[$index + 9];
            $uniqueID = $option[0] == "THEIRALLY" ? $theirAllies[$index + 5] : $myAllies[$index + 5];
            $tapped = $option[0] == "THEIRALLY" ? $theirAllies[$index + 11] == 1 : $myAllies[$index + 11] == 1;
            if (SearchCurrentTurnEffectsForUniqueID($uniqueID) != -1) {
                $powerCounters = EffectPowerModifier(SearchUniqueIDForCurrentTurnEffects($uniqueID)) + PowerValue(($option[0] == "THEIRALLY") ? $theirAllies[$index] : $myAllies[$index], $player, "ALLY");
            }
          }
          
          if ($option[0] == "THEIRAURAS" || $option[0] == "MYAURAS") {
            //Show power counters on Auras in the popups
            $powerCounters = $option[0] == "THEIRAURAS" ? $theirAuras[$index + 3] : $myAuras[$index + 3];
            //Show various counters on Auras in the popups
            $counters = $option[0] == "THEIRAURAS" ? $theirAuras[$index + 2] : $myAuras[$index + 2];
          }
          //Show Steam Counters on items
          if ($option[0] == "THEIRITEMS" || $option[0] == "MYITEMS") {
            $steamCounters = $option[0] == "THEIRITEMS" ? $theirItems[$index + 1] : $myItems[$index + 1];
            $tapped = $option[0] == "THEIRITEMS" ? $theirItems[$index + 10] == 1 : $myItems[$index + 10] == 1;
            $label = $option[0] == "THEIRITEMS" && $theirItems[$index + 8] != "" && $theirItems[$index + 8] != "-" ? GamestateUnsanitize($theirItems[$index + 8]) : "";
            $label = $option[0] == "MYITEMS" && $myItems[$index + 8] != "" && $myItems[$index + 8] != "-" ? GamestateUnsanitize($myItems[$index + 8]) : "";
          }
          
          //Show Subtitles on MyDeck
          if(substr($turnData, 0, 6) === "MYDECK" && $turnData != "MYDECK-0"){
            $subtitles = "(You can click your deck to see its content during this card resolution)";
          }

          if($option[0] == "MYDECK" && $option[1] == "0" && $turnPhase == "MAYCHOOSEMULTIZONE" && substr_count($turnData, "MYDECK") == 1) {
            $card = $MyCardBack;
          }
          if ($maxCount < 2)
            array_push($cardsMultiZone, JSONRenderedCard($card, action: 16, overlay: 0, borderColor: $borderColor, counters: $counters, actionDataOverride: $options[$i], lifeCounters: $lifeCounters, defCounters: $enduranceCounters, powerCounters: $powerCounters, controller: $borderColor, label: $label, steamCounters: $steamCounters, tapped: $tapped, isOpponent: substr($option[0], 0, 5) == "THEIR" ? true : false));
          else
            array_push($cardsMultiZone, JSONRenderedCard($card, actionDataOverride: $i - $countOffset));
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

/**
 * Helper for checkbox default state
 */
function CheckboxDefaultState($options, $minNumber = 0, $maxNumber = 0) {
  static $presets = [
    "blood_on_her_hands" => [
      "min" => 0,
      "max" => 6,
      "options" => ["Buff_Weapon", "Buff_Weapon", "Go_Again", "Go_Again", "Attack_Twice", "Attack_Twice"]
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
      array_push($cardList, JSONRenderedCard($zone[$options[$i]], action: $mode, actionDataOverride: strval($options[$i]), label: $label));
    }
  }

  return CreatePopupAPI("CHOOSEZONE", [], 0, 1, $caption, 1, "", additionalComments: $additionalComments, cardsArray: $cardList);
}
