<?php

  function ELETalentPlayAbility($cardID, $from, $resourcesPaid, $target="-", $additionalCosts="")
  {
    global $currentPlayer, $CS_PlayIndex, $mainPlayer, $CS_DamagePrevention, $combatChain, $layers;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID)
    {
      case "ELE000":
        $rv = "Korshem is a partially manual card. Use the instant ability to destroy it when appropriate. Use the Revert Gamestate button under the Stats page if necessary.";
        if($from == "PLAY")
        {
          DestroyLandmark(GetClassState($currentPlayer, $CS_PlayIndex));
          $rv = "Korshem was destroyed";
        }
        return $rv;
      case "ELE103": case "ELE104": case "ELE105":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE106": GainHealth(3, $currentPlayer); return "";
      case "ELE107": GainHealth(2, $currentPlayer); return "";
      case "ELE108": GainHealth(1, $currentPlayer); return "";
      case "ELE112":
        if(isset($layers[0]) && count($combatChain) > 0 || CardType($layers[0]) == "AA" || GetAbilityType($layers[0]) == "AA") AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        else AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE113":
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "2-", 1);
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("WRITELOG", $currentPlayer, "Cards returned", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "1", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        return "";
      case "ELE114":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE115":
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDCLASSSTATE", $currentPlayer, $CS_DamagePrevention . "-1", 1);
        return "";
      case "ELE116":
        $params = explode("-", $target);
        $index = SearchdiscardForUniqueID($params[1], $currentPlayer);
        if ($index != -1) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "MYDISCARD-" . $index, 1);
          AddDecisionQueue("MZADDZONE", $currentPlayer, "MYHAND", 1);
          AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        } else {
          WriteLog(CardLink($cardID, $cardID) . " layer fails as there are no remaining targets for the targeted effect.");
          return "FAILED";
        }
        return "";
      case "ELE118":
        Draw($currentPlayer);
        Draw($currentPlayer);
        Draw($currentPlayer);
        return "";
      case "ELE119": case "ELE120": case "ELE121":
        return "";
      case "ELE122": case "ELE123": case "ELE124":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE125": case "ELE126": case "ELE127":
       AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
       AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
       AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $currentPlayer, PlayBlockModifier($cardID), 1);
       return "";
      case "ELE131": case "ELE132": case "ELE133":
        MZMoveCard($currentPlayer, "MYARS", "MYBOTDECK", may:true, silent:true);
        AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
        return "";
      case "ELE137": case "ELE138": case "ELE139":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE140": case "ELE141": case "ELE142":
        $params = explode("-", $target);
        $index = SearchdiscardForUniqueID($params[1], $currentPlayer);
        if ($index != -1) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "MYDISCARD-" . $index, 1);
          AddDecisionQueue("MZADDZONE", $currentPlayer, "MYBOTDECK", 1);
          AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
          if($from == "ARS") {
            AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
            WriteLog(CardLink($cardID, $cardID) . " draw a card.");
          }
          ResolveGoesWhere("BANISH", $cardID, $currentPlayer, $from);
        } 
        else {
          WriteLog(CardLink($cardID, $cardID) . " layer fails as there are no remaining targets for the targeted effect.");
          ResolveGoesWhere("GY", $cardID, $currentPlayer, $from);
          return "FAILED";
        }
        return "";
      case "ELE143":
        if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE144":
        AddCurrentTurnEffect($cardID, $otherPlayer);
        return "";
      case "ELE145":
        PlayAura("ELE111", $otherPlayer, effectController: $currentPlayer);
        return "";
      case "ELE147":
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose_to_pay_2_or_you_lose_and_can't_gain_go_again");
        AddDecisionQueue("BUTTONINPUT", $mainPlayer, "0,2", 0, 1);
        AddDecisionQueue("PAYRESOURCES", $mainPlayer, "<-", 1);
        AddDecisionQueue("GREATERTHANPASS", $mainPlayer, "0", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, $cardID, 1);
        return "";
      case "ELE151": case "ELE152": case "ELE153":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        AddCurrentTurnEffect($cardID . "-HIT", $currentPlayer);
        return "";
      case "ELE154": case "ELE155": case "ELE156":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE163": case "ELE164": case "ELE165":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE166": case "ELE167": case "ELE168":
        if($cardID == "ELE166") $cost = 3;
        else if($cardID == "ELE167") $cost = 2;
        else $cost = 1;
        AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose_if_you_want_to_pay_".$cost."_to_prevent_Dominate");
        AddDecisionQueue("BUTTONINPUT", $otherPlayer, "0," . $cost, 0, 1);
        AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
        AddDecisionQueue("GREATERTHANPASS", $otherPlayer, "0", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
        if($from == "ARS") {
          Draw($currentPlayer);
          WriteLog(CardLink($cardID, $cardID) . " draw a card.");
        }
        return "";
      case "ELE169": case "ELE170": case "ELE171": 
        if($cardID == "ELE169") $pay = 3;
        else if($cardID == "ELE170") $pay = 2;
        else $pay = 1;
        if(ShouldAutotargetOpponent($currentPlayer)) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, "Target_Opponent");
          AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "WINTERSBITE-" . $pay, 1);
        } else {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose target hero");
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Yourself");
          AddDecisionQueue("PLAYERTARGETEDABILITY", $currentPlayer, "WINTERSBITE-" . $pay, 1);
        }
        return "";
      case "ELE172":
        if($from == "PLAY") PayOrDiscard($otherPlayer, 2);
        return "";
      case "ELE173":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE176":
        GainActionPoints(1, $currentPlayer);
        return "";
      case "ELE177": case "ELE178": case "ELE179":
        AddAfterResolveEffect($cardID, $currentPlayer);
        return "";
      case "ELE180": case "ELE181": case "ELE182":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE183": case "ELE184": case "ELE185":
        $amount = 3;
        if($cardID == "ELE184") $amount = 2;
        else if($cardID == "ELE185") $amount = 1;
        $targetIndex = intval(explode("-", $target)[1]);
        if($targetIndex != 0) CombatChainPowerModifier($targetIndex, $amount);
        else AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE186": case "ELE187": case "ELE188":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE189": case "ELE190": case "ELE191":
        if($from == "ARS") GiveAttackGoAgain();
        return "";
      case "ELE195": case "ELE196": case "ELE197":
        if($from == "PLAY") AddCurrentTurnEffect($cardID, $currentPlayer, "", 1);
        return "";
      case "ELE198": case "ELE199": case "ELE200":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if($from == "ARS") {
          Draw($currentPlayer);
          WriteLog(CardLink($cardID, $cardID) . " draw a card.");
        }
        return "";
      case "ELE201":
        if($from == "PLAY") {
          if(count($combatChain) > 0) GiveAttackGoAgain();
          else AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "ELE233":
        Draw($currentPlayer);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
        AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("OPT", $currentPlayer, "<-");
        return "";
      case "ELE234":
        GainResources($currentPlayer, 3);
        return "";
      case "ELE235":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ELE236":
        IncrementClassState($currentPlayer, $CS_DamagePrevention);
        return "";
      default: return "";
    }
  }

  function ELETalentHitEffect($cardID)
  {
    global $mainPlayer, $defPlayer;
    switch($cardID)
    {
      case "ELE148": case "ELE149": case "ELE150":
        if(IsHeroAttackTarget()) PayOrDiscard($defPlayer, 2);
        break;
      case "ELE157": case "ELE158": case "ELE159":
        if(IsHeroAttackTarget()) PlayAura("ELE111", $defPlayer, effectController: $mainPlayer);
        break;
      default: break;
    }
  }

  function SowTomorrowIndices($player, $cardID)
  {
    if($cardID == "ELE140") $minCost = 0;
    else if($cardID == "ELE141") $minCost = 1;
    else $minCost = 2;
    $earth = CombineSearches(SearchDiscard($player, "A", "", -1, $minCost, "", "EARTH"), SearchDiscard($player, "AA", "", -1, $minCost, "", "EARTH"));
    $elemental = CombineSearches(SearchDiscard($player, "A", "", -1, $minCost, "", "ELEMENTAL"), SearchDiscard($player, "AA", "", -1, $minCost, "", "ELEMENTAL"));
    return CombineSearches($earth, $elemental);
  }

  function PulseOfCandleholdIndices($player)
  {
    return CombineSearches(SearchDiscard($player, "A", "", -1, -1, "", "EARTH,LIGHTNING,ELEMENTAL"), SearchDiscard($player, "AA", "", -1, -1, "", "EARTH,LIGHTNING,ELEMENTAL"));
  }

  function ExposedToTheElementsEarth($player)
  {
      $otherPlayer = $player == 1 ? 2 : 1;
      PrependDecisionQueue("MODDEFCOUNTER", $otherPlayer, "-1", 1);
      PrependDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
      PrependDecisionQueue("SETDQCONTEXT", $player, "Choose an equipment to put a -1 counter", 1);
      PrependDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP");
  }

  function ExposedToTheElementsIce($player)
  {
      $otherPlayer = $player == 1 ? 2 : 1;
      PrependDecisionQueue("DESTROYCHARACTER", $otherPlayer, "-", 1);
      PrependDecisionQueue("CHOOSETHEIRCHARACTER", $player, "<-", 1);
      PrependDecisionQueue("SETDQCONTEXT", $player, "Choose an equipment to destroy", 1);
      PrependDecisionQueue("FINDINDICES", $otherPlayer, "EQUIP0", 1);
      PrependDecisionQueue("WRITELOG", $player, "Player $otherPlayer declined_to_pay_for_".CardLink("ELE093", "ELE093").".", 1);
      PrependDecisionQueue("GREATERTHANPASS", $otherPlayer, "0", 1);
      PrependDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      PrependDecisionQueue("BUTTONINPUT", $otherPlayer, "0,2", 0);
      PrependDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose_if_you_want_to_pay_2_to_prevent_an_equipment_with_0_defense_from_being_destroyed.");
  }

  function KorshemRevealAbility($player)
  {
    WriteLog("Korshem triggered by revealing a card");
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a bonus", 1);
    AddDecisionQueue("BUTTONINPUT", $player, "Gain_a_resource,Gain_a_life,1_Attack,1_Defense");
    AddDecisionQueue("MODAL", $player, "KORSHEM", 1);
  }

?>
