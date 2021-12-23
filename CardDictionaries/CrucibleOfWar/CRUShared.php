<?php

  function CRUAbilityCost($cardID)
  {
    global $myCharacter, $myClassState, $myItems, $CS_CharacterIndex, $CS_PlayIndex;
    switch($cardID)
    {
      case "CRU101": return ($myCharacter[$myClassState[$CS_CharacterIndex] + 2] > 0 ? 0 : 2);
      case "CRU105": return ($myItems[$myClassState[$CS_PlayIndex] + 1] > 0 ? 0 : 1);
      case "CRU160": return 2;
      case "CRU197": return 4;
      default: return 0;
    }
  }

  function CRUAbilityType($cardID, $index=-1)
  {
    global $myCharacter, $myClassState, $myItems, $CS_CharacterIndex, $CS_PlayIndex;
    switch($cardID)
    {
      case "CRU101": if($index == -1) $index = $myClassState[$CS_CharacterIndex]; return ($myCharacter[$index + 2] > 0 ? "AA" : "A");
      case "CRU102": return "A";
      case "CRU105": return "A";
      case "CRU160": return "A";
      case "CRU197": return "A";
      default: return "";
    }
  }

  function CRUHasGoAgain($cardID)
  {
    global $defPlayer, $CS_ArcaneDamageTaken;
    switch($cardID)
    {
      //CRU Ninja
      case "CRU057": case "CRU058": case "CRU059": return ComboActive($cardID);
      case "CRU060": case "CRU061": case "CRU062": return ComboActive($cardID);
      case "CRU084": return true;
      case "CRU085": case "CRU086": case "CRU087": return true;
      case "CRU091": case "CRU092": case "CRU093": return true;
      case "CRU094": case "CRU095": case "CRU096": return true;
      //CRU Brute
      case "CRU009": return true;
      case "CRU019": case "CRU020": case "CRU021": return true;
      //CRU Ninja
      case "CRU050": case "CRU051": case "CRU052": return true;
      case "CRU072": case "CRU074": return true;
      //CRU Ranger
      case "CRU124": case "CRU135": case "CRU136": case "CRU137": return true;
      //CRU Mechanologist
      case "CRU115": case "CRU116": case "CRU117": return true;
      //CRU Runeblade
      case "CRU143": return true;
      case "CRU145": case "CRU146": case "CRU147": return true;
      case "CRU151": case "CRU152": case "CRU153":
      return GetClassState($defPlayer, $CS_ArcaneDamageTaken) > 0;
      case "CRU154": case "CRU155": case "CRU156": return true;
      //CRU Generic
      case "CRU181": case "CRU188": return true;
      default: return false;
    }
  }

  function CRUAbilityHasGoAgain($cardID)
  {
    global $myCharacter, $myClassState, $myItems, $CS_CharacterIndex, $CS_PlayIndex;
    switch($cardID)
    {
      case "CRU006": return true;
      case "CRU025": return true;
      case "CRU081": return true;
      case "CRU101": return ($myCharacter[$myClassState[$CS_CharacterIndex] + 2] > 0 ? false : true);
      case "CRU102": return true;
      case "CRU105": return true;
      case "CRU121": case "CRU122": return true;
      case "CRU197": return true;
      default: return false;
    }
  }

  function CRUEffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "CRU008": return 2;
      case "CRU025": return 2;
      case "CRU029": return 10;
      case "CRU030": return 9;
      case "CRU031": return 8;
      case "CRU038": return 3;
      case "CRU039": return 2;
      case "CRU040": return 1;
      case "CRU047": return 1;
      case "CRU055": return 3;
      case "CRU072": return 1;
      case "CRU084": return 2;
      case "CRU085-1": return 3;
      case "CRU086-1": return 2;
      case "CRU087-1": return 1;
      case "CRU088-1": return 3;
      case "CRU089-1": return 2;
      case "CRU090-1": return 1;
      case "CRU088-2": return 1;
      case "CRU089-2": return 1;
      case "CRU090-2": return 1;
      case "CRU091-2": return 3;
      case "CRU092-2": return 2;
      case "CRU093-2": return 1;
      case "CRU094-1": return 3;
      case "CRU095-1": return 2;
      case "CRU096-1": return 1;
      case "CRU135": return 3;
      case "CRU136": return 2;
      case "CRU137": return 1;
      default: return 0;
    }
  }

  function CRUCombatEffectActive($cardID, $attackID)
  {
    global $combatChain;
    switch($cardID)
    {
      //Brute
      case "CRU008": return true;
      case "CRU013": case "CRU014": case "CRU015": return true;
      //Guardian
      case "CRU025": return HasCrush($attackID);
      case "CRU029": case "CRU030": case "CRU031": return CardType($attackID) == "AA" && CardClass($attackID) == "GUARDIAN";
      case "CRU038": case "CRU039": case "CRU040": return CardType($attackID) == "AA" && CardClass($attackID) == "GUARDIAN";
      //Ninja
      case "CRU047": return true;
      case "CRU053": return HasCombo($combatChain[0]);
      case "CRU055": return true;
      case "CRU072": return true;
      //Warrior
      case "CRU084": return CardType($attackID) == "W";
      case "CRU084-2": return CardType($attackID) == "W";
      case "CRU085-1": case "CRU086-1": case "CRU087-1": return CardType($attackID) == "W";
      case "CRU088-1": case "CRU089-1": case "CRU090-1": return CardType($attackID) == "W";
      case "CRU088-2": case "CRU089-2": case "CRU090-2": return !HasEffect(substr($cardID, 0, -1) . "1");
      case "CRU091-1": case "CRU092-1": case "CRU093-1": return CardType($attackID) == "W";
      case "CRU091-2": case "CRU092-2": case "CRU093-2": return true;
      case "CRU094-1": case "CRU095-1": case "CRU096-1": return CardType($attackID) == "W";
      case "CRU094-2": case "CRU095-2": case "CRU096-2": return true;
      case "CRU106": case "CRU107": case "CRU108": return HasBoost($attackID);
      //Ranger
      case "CRU122": $combatChain[2] == "ARS" && GetClassState($mainPlayer, $CS_ArsenalFacing) == "UP" && CardSubtype($attackID) == "Arrow";
      case "CRU123": return $attackID == "CRU123";
      case "CRU124": return CardSubtype($combatChain[0]) == "Arrow";
      case "CRU135": case "CRU136": case "CRU137": return CardSubtype($attackID) == "Arrow";
      case "CRU135-1": case "CRU136-1": case "CRU137-1": return CardSubtype($attackID) == "Arrow";
      //Runeblade
      case "CRU145": case "CRU146": case "CRU147": return CardType($attackID) == "AA" && CardClass($attackID) == "RUNEBLADE";
      default: return false;
    }
  }

  function RushingRiverHitEffect()
  {
    global $combatChainState, $CCS_NumHits, $mainPlayer;
    $num = $combatChainState[$CCS_NumHits];
    for($i=0; $i<$num; ++$i)
    {
      Draw($mainPlayer);
      AddDecisionQueue("FINDINDICES", $mainPlayer, "HAND");
      AddDecisionQueue("CHOOSEHAND", $mainPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $mainPlayer, "-", 1);
      AddDecisionQueue("MULTIADDTOPDECK", $mainPlayer, "-", 1);
    }
  }

  function FloodOfForcePlayEffect()
  {
    global $mainPlayer;
    AddDecisionQueue("DECKCARDS", $mainPlayer, "0");
    AddDecisionQueue("REVEALCARDS", $mainPlayer, "-", 1);
    AddDecisionQueue("ALLCARDSCOMBOORPASS", $mainPlayer, "-", 1);
    AddDecisionQueue("FINDINDICES", $mainPlayer, "TOPDECK", 1);
    AddDecisionQueue("MULTIREMOVEDECK", $mainPlayer, "<-", 1);
    AddDecisionQueue("MULTIADDHAND", $mainPlayer, "-", 1);
    AddDecisionQueue("ADDCURRENTEFFECT", $mainPlayer, "CRU055", 1);
  }

  function KayoStaticAbility()
  {
    global $combatChainState, $CCS_LinkBaseAttack, $mainPlayer;
    $roll = GetDieRoll($mainPlayer);
    if($roll >= 5) $combatChainState[$CCS_LinkBaseAttack] *= 2;
    else $combatChainState[$CCS_LinkBaseAttack] = floor($combatChainState[$CCS_LinkBaseAttack]/2);
  }

  function KassaiEndTurnAbility()
  {
    global $mainPlayer, $CS_AtksWWeapon, $CS_HitsWithWeapon;
    if(GetClassState($mainPlayer, $CS_AtksWWeapon) >= 2)
    {
      for($i=0; $i<GetClassState($mainPlayer, $CS_HitsWithWeapon); ++$i)
      {
        PutItemIntoPlayForPlayer("CRU197", $mainPlayer);
      }
    }
  }

?>

