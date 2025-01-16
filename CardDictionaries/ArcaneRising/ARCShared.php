<?php

  function ARCAbilityCost($cardID)
  {
    global $CS_PlayIndex, $currentPlayer, $CombatChain;
    switch($cardID)
    {
      case "ARC003":
        $abilityType = GetResolvedAbilityType($cardID);
        return ($abilityType == "A" ? 1 : 0);
      case "ARC004": return 1;
      case "ARC010":
        $abilityType = GetResolvedAbilityType($cardID);
        return $CombatChain->HasCurrentLink() ? 0 : 1;
      case "ARC017":
        $items = &GetItems($currentPlayer);
        return ($items[GetClassState($currentPlayer, $CS_PlayIndex) + 1] > 0 ? 0 : 1);
      case "ARC018":
        $abilityType = GetResolvedAbilityType($cardID);
        return $CombatChain->HasCurrentLink() ? 0 : 1;
      case "ARC040": return 1;
      case "ARC077": return 2;
      case "ARC078": return 2 + NumRunechants($currentPlayer);
      case "ARC079": return 1;
      case "ARC113": case "ARC114": return 3;
      case "ARC115": return 1;
      case "ARC116": return 1;
      case "ARC117": return 0;
      case "ARC154": return 1;
      default: return 0;
    }
  }

  function ARCAbilityType($cardID, $index=-1)
  {
    global $currentPlayer, $CS_PlayIndex, $CombatChain;
    $items = &GetItems($currentPlayer);
    switch($cardID)
    {
      case "ARC003":
        return "A";
      case "ARC004": return "A";
      case "ARC005": return "I";
      case "ARC010": return $CombatChain->HasCurrentLink() ? "AR" : "A";
      case "ARC017":
        if($index == -1) $index = GetClassState($currentPlayer, $CS_PlayIndex);
        if(isset($items[$index + 1])) return ($items[$index+1] > 0 ? "I" : "A");
        else return "A";
      case "ARC018":
        if($index == -1) $index = GetClassState($currentPlayer, $CS_PlayIndex);
        return ($CombatChain->HasCurrentLink() ? "AR" : "A");
      case "ARC019": return "A";
      case "ARC035": return "I";
      case "ARC037": return "A";
      case "ARC038": case "ARC039": case "ARC040": case "ARC041": case "ARC042": return "A";
      case "ARC077": return "AA";
      case "ARC078": return "A";
      case "ARC079": return "A";
      case "ARC113": case "ARC114": case "ARC115": case "ARC116": return "I";
      case "ARC117": return "A";
      case "ARC151": return "I";
      case "ARC153": case "ARC154": return "A";
      default: return "";
    }
  }

  function ARCAbilityHasGoAgain($cardID)
  {
    global $currentPlayer, $CS_PlayIndex, $CombatChain;
    switch($cardID)
    {
      case "ARC003":
        $abilityType = GetResolvedAbilityType($cardID);
        return $abilityType == "A";
      case "ARC004": return true;
      case "ARC010":
        return !$CombatChain->HasCurrentLink();
      case "ARC017":
        $items = &GetItems($currentPlayer);
        return ($items[GetClassState($currentPlayer, $CS_PlayIndex)+1] > 0 ? true : false);
      case "ARC018":
        $items = &GetItems($currentPlayer);
        return ($items[GetClassState($currentPlayer, $CS_PlayIndex)+1] > 0 ? true : false);
      case "ARC019": return true;
      case "ARC037": return true;
      case "ARC038": case "ARC039": case "ARC040": case "ARC041": case "ARC042": return true;
      case "ARC078": return true;
      case "ARC153": case "ARC154": return true;
      default: return false;
    }
  }

  function ARCEffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "ARC032": return 3;
      case "ARC033": return 2;
      case "ARC034": return 1;
      case "ARC042": return 1;
      case "ARC054": return 3;
      case "ARC055": return 2;
      case "ARC056": return 1;
      case "ARC057": case "ARC058": case "ARC059": return 2;
      case "ARC091": return 3;
      case "ARC092": return 2;
      case "ARC093": return 1;
      case "ARC153-1": return 1; case "ARC153-2": return 2; case "ARC153-3": return 3;
      case "ARC160-1": return 1;
      case "ARC170-2": return 3;
      case "ARC171-2": return 2;
      case "ARC172-2": return 1;
      case "ARC203": return 3;
      case "ARC204": return 2;
      case "ARC205": return 1;
      case "ARC206": return 3;
      case "ARC207": return 2;
      case "ARC208": return 1;
      default: return 0;
    }
  }

function ARCCombatEffectActive($cardID, $attackID)
{
  global $combatChainState, $CCS_AttackPlayedFrom, $mainPlayer;
  switch($cardID) {
    case "ARC011": case "ARC012": case "ARC013": return true;
    case "ARC019": return CardType($attackID) == "AA";
    case "ARC032": case "ARC033": case "ARC034": return CardType($attackID) == "AA" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer);
    case "ARC038": case "ARC039": return CardSubType($attackID) == "Arrow" && $combatChainState[$CCS_AttackPlayedFrom] == "ARS";
    case "ARC042": return CardSubType($attackID) == "Arrow" && $combatChainState[$CCS_AttackPlayedFrom] == "ARS";
    case "ARC047": return CardSubType($attackID) == "Arrow";
    case "ARC054": case "ARC055": case "ARC056": return ClassContains($attackID, "RANGER", $mainPlayer) && CardType($attackID) == "AA";
    case "ARC057": case "ARC058": case "ARC059": return $cardID == $attackID;
    case "ARC091": case "ARC092": case "ARC093": return ClassContains($attackID, "RUNEBLADE", $mainPlayer);
    case "ARC153-1": case "ARC153-2": case "ARC153-3": return CardType($attackID) == "AA";
    case "ARC160-1": case "ARC160-3": return CardType($attackID) == "AA";
    case "ARC170-1": case "ARC171-1": case "ARC172-1": return CardType($attackID) == "AA";
    case "ARC170-2": case "ARC171-2": case "ARC172-2": return CardType($attackID) == "AA";
    case "ARC203": case "ARC204": case "ARC205": return CardType($attackID) == "AA";
    case "ARC206": case "ARC207": case "ARC208": return CardType($attackID) == "AA";
    default: return false;
  }
}

?>
