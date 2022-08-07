<?php

  function CRUAbilityCost($cardID)
  {
    global $CS_CharacterIndex, $CS_PlayIndex, $currentPlayer;
    switch($cardID)
    {
      case "CRU101": $char = &GetPlayerCharacter($currentPlayer); return ($char[GetClassState($currentPlayer, $CS_CharacterIndex) + 2] > 0 ? 0 : 2);
      case "CRU105": $items = &GetItems($currentPlayer); return ($items[GetClassState($currentPlayer, $CS_PlayIndex) + 1] > 0 ? 0 : 1);
      case "CRU118": return 3;
      case "CRU160": return 2;
      case "CRU197": return 4;
      default: return 0;
    }
  }

  function CRUAbilityType($cardID, $index=-1)
  {
    global $myCharacter, $myClassState, $CS_CharacterIndex;
    switch($cardID)
    {
      case "CRU004": case "CRU005": return "AA";
      case "CRU006": return "A";
      case "CRU024": return "AA";
      case "CRU025": return "A";
      case "CRU050": case "CRU051": case "CRU052": return "AA";
      case "CRU079": case "CRU080": return "AA";
      case "CRU081": return "A";
      case "CRU101": if($index == -1) $index = $myClassState[$CS_CharacterIndex]; return ($myCharacter[$index + 2] > 0 ? "AA" : "A");
      case "CRU102": return "A";
      case "CRU105": return "A";
      case "CRU118": return "A";
      case "CRU121": return "A";
      case "CRU122": return "A";
      case "CRU140": return "AA";
      case "CRU141": return "I";
      case "CRU160": return "A";
      case "CRU177": return "AA";
      case "CRU197": return "A";
      default: return "";
    }
  }

  function CRUAbilityHasGoAgain($cardID)
  {
    global $myCharacter, $myClassState, $CS_CharacterIndex;
    switch($cardID)
    {
      case "CRU006": return true;
      case "CRU025": return true;
      case "CRU081": return true;
      case "CRU101": return ($myCharacter[$myClassState[$CS_CharacterIndex] + 1] > 0 ? true : false);
      case "CRU102": return true;
      case "CRU105": return true;
      case "CRU118": return true;
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
    global $combatChain, $currentPlayer;
    switch($cardID)
    {
      //Brute
      case "CRU008": return true;
      case "CRU013": case "CRU014": case "CRU015": return true;
      //Guardian
      case "CRU025": return HasCrush($attackID);
      case "CRU029": case "CRU030": case "CRU031": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $currentPlayer);
      case "CRU038": case "CRU039": case "CRU040": return CardType($attackID) == "AA" && ClassContains($attackID, "GUARDIAN", $currentPlayer);
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
      case "CRU122": return $combatChain[2] == "ARS" && CardSubtype($attackID) == "Arrow"; //The card being played from ARS and being an Arrow implies that the card is UP.
      case "CRU123": return $attackID == "CRU123";
      case "CRU124": return CardSubtype($combatChain[0]) == "Arrow";
      case "CRU135": case "CRU136": case "CRU137": return CardSubtype($attackID) == "Arrow";
      case "CRU135-1": case "CRU136-1": case "CRU137-1": return CardSubtype($attackID) == "Arrow";
      //Runeblade
      case "CRU145": case "CRU146": case "CRU147": return CardType($attackID) == "AA" && ClassContains($attackID, "RUNEBLADE", $currentPlayer);
      default: return false;
    }
  }

  function CRUCardType($cardID)
  {
    switch ($cardID)
    {
      //CRU Fable
      case "CRU000": return "R";
      //CRU Brute
      case "CRU002": return "C";
      case "CRU004": case "CRU005": return "W";
      case "CRU006": return "E";
      case "CRU007": case "CRU008": return "AA";
      case "CRU009": return "A";
      case "CRU010": case "CRU011": case "CRU012": return "AA";
      case "CRU013": case "CRU014": case "CRU015": return "AA";
      case "CRU016": case "CRU017": case "CRU018": return "AA";
      case "CRU019": case "CRU020": case "CRU021": return "AA";
      //CRU Guardian
      case "CRU024": return "W";
      case "CRU025": return "E";
      case "CRU026": case "CRU027": return "AA";
      case "CRU028": case "CRU029": case "CRU030": case "CRU031": return "A";
      case "CRU032": case "CRU033": case "CRU034": return "AA";
      case "CRU035": case "CRU036": case "CRU037": return "AA";
      case "CRU038": case "CRU039": case "CRU040": return "A";
      case "CRU041": case "CRU042": case "CRU043": return "I";
      //CRU Ninja
      case "CRU046": case "CRU047": return "C";
      case "CRU050": case "CRU051": case "CRU052": return "W";
      case "CRU053": return "E";
      case "CRU054": case "CRU055": case "CRU056": return "AA";
      case "CRU057": case "CRU058": case "CRU059": return "AA";
      case "CRU060": case "CRU061": case "CRU062": return "AA";
      case "CRU063": case "CRU064": case "CRU065": return "AA";
      case "CRU066": case "CRU067": case "CRU068": return "AA";
      case "CRU069": case "CRU070": case "CRU071": return "AA";
      case "CRU072": case "CRU073": case "CRU074": return "AA";
      case "CRU075": return "T";
      //CRU Warrior
      case "CRU077": return "C";
      case "CRU079": case "CRU080": return "W";
      case "CRU081": return "E";
      case "CRU082": case "CRU083": return "AR";
      case "CRU084": return "A";
      case "CRU085": case "CRU086": case "CRU087": return "A";
      case "CRU088": case "CRU089": case "CRU090": return "AR";
      case "CRU091": case "CRU092": case "CRU093": return "A";
      case "CRU094": case "CRU095": case "CRU096": return "A";
      case "CRU097": return "C";
      //CRU MECH:
      case "CRU099": return "C";
      case "CRU101": return "W";
      case "CRU102": return "E";
      case "CRU103": return "AA";
      case "CRU104": case "CRU105": return "A";
      case "CRU106": case "CRU107": case "CRU108": return "AA";
      case "CRU109": case "CRU110": case "CRU111": return "AA";
      case "CRU112": case "CRU113": case "CRU114": return "AA";
      case "CRU115": case "CRU116": case "CRU117": return "A";
      //CRU Merchant
      case "CRU118": return "C";
      //CRU Ranger
      case "CRU121": return "W";
      case "CRU122": return "E";
      case "CRU123": return "AA";
      case "CRU124": return "A";
      case "CRU125": return "I";
      case "CRU126": case "CRU127": case "CRU128": return "DR";
      case "CRU129": case "CRU130": case "CRU131": return "AA";
      case "CRU132": case "CRU133": case "CRU134": return "AA";
      case "CRU135": case "CRU136": case "CRU137": return "A";
      //CRU Runeblade
      case "CRU140": return "W";
      case "CRU141": return "E";
      case "CRU142": return "AA";
      case "CRU143": return "A";
      case "CRU144": return "A";
      case "CRU145": case "CRU146": case "CRU147": return "A";
      case "CRU148": case "CRU149": case "CRU150": return "AA";
      case "CRU151": case "CRU152": case "CRU153": return "AA";
      case "CRU154": case "CRU155": case "CRU156": return "A";
      //CRU Wizard
      case "CRU160": return "W";
      case "CRU161": return "E";
      case "CRU162": case "CRU163": return "A";
      case "CRU164": return "I";
      case "CRU165": case "CRU166": case "CRU167": return "A";
      case "CRU168": case "CRU169": case "CRU170": return "A";
      case "CRU171": case "CRU172": case "CRU173": return "A";
      case "CRU174": case "CRU175": case "CRU176": return "A";
      //CRU Generics
      case "CRU177": return "W";
      case "CRU179": return "E";
      case "CRU180": return "AA";
      case "CRU181": return "A";
      case "CRU182": return "I";
      case "CRU183": case "CRU184": case "CRU185": return "AA";
      case "CRU186": return "AR";
      case "CRU187": return "DR";
      case "CRU188": return "A";
      case "CRU189": case "CRU190": case "CRU191": return "I";
      case "CRU192": case "CRU193": case "CRU194": return "AA";
      case "CRU197": return "T";
      default: return "";
    }
  }

  function CRUCardSubtype($cardID)
  {
    switch ($cardID)
    {
      case "CRU000": return "Gem";
      case "CRU004": case "CRU005": return "Claw";
      case "CRU006": return "Head";
      case "CRU024": return "Hammer";
      case "CRU025": return "Arms";
      case "CRU028": case "CRU029": case "CRU030": case "CRU031": return "Aura";
      case "CRU038": case "CRU039": case "CRU040": return "Aura";
      case "CRU050": return "Sword";
      case "CRU051": case "CRU052": return "Dagger";
      case "CRU053": return "Legs";
      case "CRU075": return "Aura";
      case "CRU079": case "CRU080": return "Sword";
      case "CRU081": return "Chest";
      case "CRU101": return "Gun";
      case "CRU102": return "Head";
      case "CRU104": case "CRU105": return "Item";
      case "CRU121": return "Bow";
      case "CRU122": return "Legs";
      case "CRU123": return "Arrow";
      case "CRU126": case "CRU127": case "CRU128": return "Trap";
      case "CRU129": case "CRU130": case "CRU131": return "Arrow";
      case "CRU132": case "CRU133": case "CRU134": return "Arrow";
      case "CRU140": return "Sword";
      case "CRU141": return "Chest";
      case "CRU144": return "Aura";
      case "CRU160": return "Staff";
      case "CRU161": return "Arms";
      case "CRU177": return "Sword";
      case "CRU179": return "Arms";
      case "CRU197": return "Item";
      default: return "";
    }
  }

  function CRUCardCost($cardID)
  {
    switch ($cardID)
    {
      case "CRU001": return -1;
      //CRU Brute
      case "CRU004": case "CRU005": return 2;
      case "CRU006": return 0;
      case "CRU007": case "CRU008": case "CRU009": return 3;
      case "CRU010": case "CRU011": case "CRU012": return 2;
      case "CRU013": case "CRU014": case "CRU015": return 2;
      case "CRU016": case "CRU017": case "CRU018": return 3;
      case "CRU019": case "CRU020": case "CRU021": return 1;
      //CRU Guardian
      case "CRU024": return 4;
      case "CRU025": return 3;
      case "CRU026": return 4;
      case "CRU027": return 7;
      case "CRU028": return 3;
      case "CRU029": case "CRU030": case "CRU031": return 9;
      case "CRU032": case "CRU033": case "CRU034": return 3;
      case "CRU035": case "CRU036": case "CRU037": return 4;
      case "CRU038": case "CRU039": case "CRU040": return 2;
      case "CRU041": case "CRU042": case "CRU043": return 0;
      //CRU Ninja
      case "CRU050": case "CRU051": case "CRU052": return 1;
      case "CRU054": case "CRU055": case "CRU056": return 0;
      case "CRU057": case "CRU058": case "CRU059": return 0;
      case "CRU060": case "CRU061": case "CRU062": return 0;
      case "CRU063": case "CRU064": case "CRU065": return 2;
      case "CRU066": case "CRU067": case "CRU068": return 0;
      case "CRU069": case "CRU070": case "CRU071": return 1;
      case "CRU072": return 1;
      case "CRU073": return 0;
      case "CRU074": return 1;
      //CRU Warrior
      case "CRU079": case "CRU080": return 1;
      case "CRU081": return 0;
      case "CRU082": return 0;
      case "CRU083": return 2;
      case "CRU084": return 1;
      case "CRU085": case "CRU086": case "CRU087": return 1;
      case "CRU088": case "CRU089": case "CRU090": return 1;
      case "CRU091": case "CRU092": case "CRU093": return 0;
      case "CRU094": case "CRU095": case "CRU096": return 1;
      //CRU Mechanologist
      case "CRU103": return 2;
      case "CRU104": return 0;
      case "CRU105": return 2;
      case "CRU106": case "CRU107": case "CRU108": return 1;
      case "CRU109": case "CRU110": case "CRU111": return 2;
      case "CRU112": case "CRU113": case "CRU114": return 2;
      case "CRU115": case "CRU116": case "CRU117": return 0;
      //CRU Ranger
      case "CRU121": return 0;
      case "CRU122": return 2;
      case "CRU123": return 1;
      case "CRU124": return 0;
      case "CRU125": return 1;
      case "CRU126": case "CRU127": case "CRU128": return 0;
      case "CRU129": case "CRU130": case "CRU131": return 0;
      case "CRU132": case "CRU133": case "CRU134": return 1;
      case "CRU135": case "CRU136": case "CRU137": return 1;
      //CRU Runeblade
      case "CRU140": return 1;
      case "CRU141": return 0;
      case "CRU142": return 3;
      case "CRU143": return 2;
      case "CRU144": return 3;
      case "CRU145": case "CRU146": case "CRU147": return 0;
      case "CRU148": case "CRU149": case "CRU150": return 1;
      case "CRU151": case "CRU152": case "CRU153": return 1;
      case "CRU154": case "CRU155": case "CRU156": return 1;
      //CRU Wizard
      case "CRU160": case "CRU161": return 0;
      case "CRU162": return 1;
      case "CRU163": return 0;
      case "CRU164": return 1;
      case "CRU165": case "CRU166": case "CRU167": return 0;
      case "CRU168": case "CRU169": case "CRU170": return 1;
      case "CRU171": case "CRU172": case "CRU173": return 2;
      case "CRU174": case "CRU175": case "CRU176": return 1;
      //CRU Generic
      case "CRU177": return 2;
      case "CRU180": case "CRU181": case "CRU182": return 0;
      case "CRU183": case "CRU184": case "CRU185": return 0;
      case "CRU186": case "CRU187": return 0;
      case "CRU188": return 4;
      case "CRU189": case "CRU190": case "CRU191": return 0;
      case "CRU192": case "CRU193": case "CRU194": return 2;
      default: return 0;
    }
  }

  function CRUPitchValue($cardID)
  {
    switch ($cardID)
    {
      //CRU Guardian
      case "CRU024": case "CRU025": return 0;
      case "CRU026": return 1;
      case "CRU027": return 2;
      case "CRU028": return 3;
      case "CRU029": case "CRU032": case "CRU035": case "CRU038": case "CRU041": return 1;
      case "CRU030": case "CRU033": case "CRU036": case "CRU039": case "CRU042": return 2;
      case "CRU031": case "CRU034": case "CRU037": case "CRU040": case "CRU043": return 3;
      //CRU Brute
      case "CRU002": return 0;
      case "CRU004": case "CRU005": case "CRU006": return 0;
      case "CRU007": return 2;
      case "CRU008": return 1;
      case "CRU009": return 2;
      case "CRU010": case "CRU013": case "CRU016": case "CRU019": return 1;
      case "CRU011": case "CRU014": case "CRU017": case "CRU020": return 2;
      case "CRU012": case "CRU015": case "CRU018": case "CRU021": return 3;
      //CRU Ninja
      case "CRU046": case "CRU047": case "CRU050": case "CRU051": case "CRU052": case "CRU053": return 0;
      case "CRU054": return 3;
      case "CRU055": return 2;
      case "CRU056": return 1;
      case "CRU057": case "CRU060": case "CRU063": case "CRU066": case "CRU069": return 1;
      case "CRU058": case "CRU061": case "CRU064": case "CRU067": case "CRU070": return 2;
      case "CRU059": case "CRU062": case "CRU065": case "CRU068": case "CRU071": return 3;
      case "CRU072": case "CRU073": case "CRU074": return 2;
      //CRU Warrior
      case "CRU077": return 0;
      case "CRU079": case "CRU080": return 0;
      case "CRU081": return 0;
      case "CRU082": case "CRU083": return 2;
      case "CRU084": return 1;
      case "CRU085": case "CRU088": case "CRU091": case "CRU094": return 1;
      case "CRU086": case "CRU089": case "CRU092": case "CRU095": return 2;
      case "CRU087": case "CRU090": case "CRU093": case "CRU096": return 3;
      //CRU Shapeshifter
      case "CRU097": return 0;
      //CRU Mechanologist
      case "CRU099": case "CRU101": case "CRU102": return 0;
      case "CRU103": return 3;
      case "CRU104": return 2;
      case "CRU105": return 1;
      case "CRU106": case "CRU109": case "CRU112": case "CRU115": return 1;
      case "CRU107": case "CRU110": case "CRU113": case "CRU116": return 2;
      case "CRU108": case "CRU111": case "CRU114": case "CRU117": return 3;
      //CRU Merchant
      case "CRU118": return "0";
      //CRU Ranger
      case "CRU121": case "CRU122": return 0;
      case "CRU123": return 1;
      case "CRU124": case "CRU125": return 2;
      case "CRU126": case "CRU129": case "CRU132": case "CRU135": return 1;
      case "CRU127": case "CRU130": case "CRU133": case "CRU136": return 2;
      case "CRU126": case "CRU131": case "CRU134": case "CRU137": return 3;
      //CRU Runeblade
      case "CRU140": case "CRU141": return 0;
      case "CRU143": return 1;
      case "CRU144": return 2;
      case "CRU145": case "CRU148": case "CRU151": case "CRU154": return 1;
      case "CRU146": case "CRU149": case "CRU152": case "CRU155": return 2;
      case "CRU147": case "CRU150": case "CRU153": case "CRU156": return 3;
      //CRU Wizard
      case "CRU160": case "CRU161": return 0;
      case "CRU162": return 2;
      case "CRU163": case "CRU164": return 3;
      case "CRU165": case "CRU168": case "CRU171": case "CRU174": return 1;
      case "CRU166": case "CRU169": case "CRU172": case "CRU175": return 2;
      case "CRU167": case "CRU170": case "CRU173": case "CRU176": return 3;
      //CRU Generic
      case "CRU177": return 0;
      case "CRU179": return 0;
      case "CRU180": return 1;
      case "CRU181": return 0;
      case "CRU182": return 3;
      case "CRU183": case "CRU189": case "CRU192": return 1;
      case "CRU184": case "CRU190": case "CRU193": return 2;
      case "CRU185": case "CRU191": case "CRU194": return 3;
      case "CRU186": return 3;
      case "CRU187": case "CRU188": return 2;
      default: return 3;
    }
  }

  function CRUBlockValue($cardID)
  {
    switch ($cardID)
    {
      case "CRU002": return -1;
      //CRU Guardian
      case "CRU024": return -1;
      case "CRU025": return 2;
      case "CRU041": case "CRU042": case "CRU043": return -1;
      //CRU Brute
      case "CRU004": case "CRU005": return -1;
      case "CRU006": return 0;
      //CRU Ninja
      case "CRU046": case "CRU047": case "CRU050": case "CRU051": case "CRU052": return -1;
      case "CRU053": return 1;
      case "CRU072": case "CRU074": return 2;
      //CRU Warrior
      case "CRU077": case "CRU079": case "CRU080": return -1;
      case "CRU081": return 2;
      //CRU Shapeshifter
      case "CRU097": return -1;
      //CRU Mechanologist
      case "CRU099": case "CRU101": return -1;
      case "CRU102": return 0;
      case "CRU104": case "CRU105": return -1;
      //CRU Ranger
      case "CRU121": return -1;
      case "CRU122": return 2;
      case "CRU124": return 2;
      case "CRU125": return -1;
      case "CRU126": return 4;
      case "CRU127": return 3;
      case "CRU128": return 2;
      case "CRU135": case "CRU136": case "CRU137": return 2;
      //CRU Runeblade
      case "CRU000": return -1;
      case "CRU140": return -1;
      case "CRU141": return 2;
      case "CRU144": return 2;
      case "CRU145": case "CRU146": case "CRU147": return 2;
      case "CRU154": case "CRU155": case "CRU156": return 2;
      //CRU Wizard
      case "CRU161": return 0;
      case "CRU160": case "CRU164": return -1;
      case "CRU165": case "CRU166": case "CRU167": return 2;
      //CRU Generics
      case "CRU177": return -1;
      case "CRU179": return 0;
      case "CRU180": return 2;
      case "CRU181": case "CRU182": return -1;
      case "CRU183": case "CRU184": case "CRU185": return 2;
      case "CRU186": case "CRU187": case "CRU188": return 2;
      case "CRU189": case "CRU190": case "CRU191": return -1;
      default: return 3;
    }
  }

  function CRUAttackValue($cardID)
  {
    global $combatChainState, $CCS_NumBoosted;
    switch ($cardID)
    {
      //CRU Brute
      case "CRU004": case "CRU005": return 3;
      case "CRU007": case "CRU008": return 6;
      case "CRU010": case "CRU016": return 7;
      case "CRU011": case "CRU013": case "CRU017": return 6;
      case "CRU012": case "CRU014": case "CRU018": return 5;
      case "CRU015": case "CRU019": return 4;
      case "CRU020": return 3;
      case "CRU021": return 2;
      //CRU Ninja
      case "CRU050": return 1;
      case "CRU051": case "CRU052": return 2;
      case "CRU054": return 2;
      case "CRU055": return 1;
      case "CRU056": return 3;
      case "CRU057": case "CRU060": return 3;
      case "CRU058": case "CRU061": return 2;
      case "CRU059": case "CRU062": return 1;
      case "CRU063": case "CRU069": return 5;
      case "CRU064": case "CRU066": case "CRU070": return 4;
      case "CRU065": case "CRU067": case "CRU071": case "CRU072": return 3;
      case "CRU068": case "CRU073": case "CRU074": return 2;
      //CRU Guardian
       return 6;
      case "CRU027": return 10;
      case "CRU026": case "CRU035": return 8;
      case "CRU032": case "CRU036": return 7;
      case "CRU024": case "CRU033": case "CRU037": return 6;
      case "CRU034": return 5;
      //CRU Warrior
      case "CRU079": case "CRU080": return 2;
      //CRU Mech
      case "CRU101": return 1 + $combatChainState[$CCS_NumBoosted];
      case "CRU103": return 4;
      case "CRU112": return 5;
      case "CRU106": case "CRU109": case "CRU113": return 4;
      case "CRU107": case "CRU110": case "CRU114": return 3;
      case "CRU108": case "CRU111": return 2;
      //CRU Ranger
      case "CRU123": return 5;
      case "CRU132": return 5;
      case "CRU129": case "CRU133": return 4;
      case "CRU130": case "CRU134": return 3;
      case "CRU131": return 2;
      //CRU Runeblade
      case "CRU140": return 3;
      case "CRU142": case "CRU148": case "CRU151": return 4;
      case "CRU149": case "CRU152": return 3;
      case "CRU150": case "CRU153": return 2;
      //CRU Generic
      case "CRU177": return 4;
      case "CRU180": return 4;
      case "CRU183": return 3;
      case "CRU184": return 2;
      case "CRU185": return 1;
      case "CRU192": return 6;
      case "CRU193": return 5;
      case "CRU194": return 4;
      default: return 0;
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

function CRUPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $mainPlayer, $CS_NumBoosted, $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $currentPlayer, $defPlayer;
  global $CS_AtksWWeapon, $CS_Num6PowDisc, $CCS_WeaponIndex, $CS_NextDamagePrevented, $CS_CharacterIndex, $CS_PlayIndex;
  global $CS_NumNonAttackCards, $CS_ArcaneDamageTaken, $CS_NextWizardNAAInstant, $CS_NumWizardNonAttack;
  global $CCS_BaseAttackDefenseMax, $CCS_NumChainLinks, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement, $CCS_RequiredEquipmentBlock, $CCS_NumBoosted;
  $rv = "";
  switch ($cardID) {
      //CRU Brute
    case "CRU004":
    case "CRU005":
      if (GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $rv = "Gains go again.";
      }
      return $rv;
    case "CRU006":
      MyDrawCard();
      $discarded = DiscardRandom($currentPlayer, $cardID);
      return "Discarded " . $discarded . ".";
    case "CRU008":
      if (GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        Intimidate();
        AddCurrentTurnEffect($cardID, $currentPlayer);
      }
      return "";
    case "CRU009":
      $roll = GetDieRoll($currentPlayer);
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      for ($i = 1; $i < $roll; $i += 2) //half rounded down
      {
        AddDecisionQueue("FINDINDICES", $otherPlayer, "ITEMS", 1);
        AddDecisionQueue("CHOOSETHEIRITEM", $currentPlayer, "<-", 1);
        AddDecisionQueue("DESTROYITEM", $otherPlayer, "<-", 1);
      }
      return "Argh... Smash! rolled " . $roll . ".";
    case "CRU013":
    case "CRU014":
    case "CRU015":
      if (GetClassState($currentPlayer, $CS_Num6PowDisc) > 0) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Gains Dominate.";
      }
      return $rv;
      //CRU Guardian
    case "CRU025":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your Crush attacks +2 this turn.";
    case "CRU028":
      if (CountPitch(GetPitch($currentPlayer), 3) >= 2) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $rv = "Stamp Authority gives you +1 intellect until end of turn.";
      }
      return $rv;
    case "CRU041":
    case "CRU042":
    case "CRU043":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Prevents some of the next damage you take this turn.";
      //Ninja
    case "CRU054":
      if (ComboActive()) {
        $combatChainState[$CCS_ResourceCostDefenseMin] = $combatChainState[$CCS_NumChainLinks];
        $rv = "Cannot be defended by cards with cost less than " . $combatChainState[$CCS_NumChainLinks] . ".";
      }
      return $rv;
    case "CRU055":
      if (ComboActive()) {
        FloodOfForcePlayEffect();
        $rv = "Reveals the top card of your deck and puts it in your hand if it has combo.";
      }
      return $rv;
    case "CRU056":
      if (ComboActive()) {
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Attack_Action,Non-attack_Action");
        AddDecisionQueue("SETCOMBATCHAINSTATE", $currentPlayer, $CCS_CardTypeDefenseRequirement, 1);
      }
      return $rv;
    case "CRU057":
    case "CRU058":
    case "CRU059":
      if (ComboActive()) {
        $combatChainState[$CCS_BaseAttackDefenseMax] = $combatChainState[$CCS_NumChainLinks];
        $rv = "Cannot be defended by attacks with greater than " . $combatChainState[$CCS_NumChainLinks] . " base attack.";
      }
      return $rv;
      //CRU Warrior
    case "CRU081":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      return "Reduces the cost of your weapon attacks by 1 this turn.";
    case "CRU082":
      $character = &GetPlayerCharacter($currentPlayer);
      ++$character[$combatChainState[$CCS_WeaponIndex] + 5];
      if ($character[$combatChainState[$CCS_WeaponIndex] + 1] == 1) {
        $character[$combatChainState[$CCS_WeaponIndex] + 1] = 2;
      }
      return "Allows you to attack with target sword an additional time.";
    case "CRU083":
      if (RepriseActive()) {
        AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
        AddDecisionQueue("ALLCARDTYPEORPASS", $currentPlayer, "AR", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Do you want to banish <0> with Unified Decree?");
        AddDecisionQueue("YESNO", $currentPlayer, "whether to banish a card the card", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("PARAMDELIMTOARRAY", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "0", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,TCC", 1);
        AddDecisionQueue("SHOWBANISHEDCARD", $currentPlayer, "-", 1);
      }
      return "Gives your weapon attack +" . AttackModifier($cardID) . " and looks for an attack reaction.";
    case "CRU084":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-2", $currentPlayer); //Hit effect
      return "Gives your next weapon attack +2 and go again.";
    case "CRU085":
    case "CRU086":
    case "CRU087":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      AddCurrentTurnEffect($cardID . "-2", ($mainPlayer == 1 ? 2 : 1));
      return "Gives your next weapon attack  +" . EffectAttackModifier($cardID . "-1") . " and makes the next Defense Reaction cost +1 to play.";
    case "CRU088":
    case "CRU089":
    case "CRU090":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      if (RepriseActive()) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Gives your weapon attack +" . EffectAttackModifier($cardID . "-1") . RepriseActive() ? " and gives your next attack +1." : ".";
    case "CRU091":
    case "CRU092":
    case "CRU093":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      $atkWWpn = GetClassState($currentPlayer, $CS_AtksWWeapon) > 0;
      if ($atkWWpn) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Gives your next weapon attack go again" . ($atkWWpn ? " and +" . EffectAttackModifier($cardID . "-2") : "") . ".";
    case "CRU094":
    case "CRU095":
    case "CRU096":
      AddCurrentTurnEffect($cardID . "-1", $mainPlayer);
      $atkWWpn = GetClassState($currentPlayer, $CS_AtksWWeapon) > 0;
      if ($atkWWpn) AddCurrentTurnEffect($cardID . "-2", $mainPlayer);
      return "Gives your next weapon attack +" . EffectAttackModifier($cardID . "-1") . ($atkWWpn ? " and gives your next attack Dominate." : ".");
      //CRU Mechanologist
    case "CRU101":
      $character = &GetPlayerCharacter($currentPlayer);
      $character[GetClassState($currentPlayer, $CS_CharacterIndex) + 2] = ($character[GetClassState($currentPlayer, $CS_CharacterIndex) + 2] == 0 ? 1 : 0);
      return "";
    case "CRU102":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "CRU103":
      if ($combatChainState[$CCS_NumBoosted]) {
        $combatChainState[$CCS_RequiredEquipmentBlock] = $combatChainState[$CCS_NumBoosted];
        $rv .= "Requires you to block with " . $combatChainState[$CCS_NumBoosted] . " equipment if able.";
      }
      return $rv;
    case "CRU105":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      if ($index != -1) {
        $items = &GetItems($currentPlayer);
        $items[$index + 1] = ($items[$index + 1] == 0 ? 1 : 0);
        if ($items[$index + 1] == 0 && ClassContains($items[$index], "MECHANOLOGIST", $currentPlayer)) {
            AddCurrentTurnEffect($cardID, $currentPlayer); //Show an effect for better visualization. 
            AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
            AddDecisionQueue("CHOOSECHARACTER", $currentPlayer, "<-", 1);
            AddDecisionQueue("ADDCHARACTEREFFECT", $currentPlayer, $cardID, 1);
            $items[$index + 2 ] = 1;
            $rv = "Gives target pistol +1.";
          }
        } else {
          $rv = "Gains a steam counter.";
        }
      return $rv;
    case "CRU115": case "CRU116": case "CRU117":
      if ($cardID == "CRU115") $maxCost = 2;
      else if ($cardID == "CRU116") $maxCost = 1;
      else if ($cardID == "CRU117") $maxCost = 0;
      Opt($cardID, GetClassState($currentPlayer, $CS_NumBoosted));
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0");
      AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
      AddDecisionQueue("ALLCARDSUBTYPEORPASS", $currentPlayer, "Item", 1);
      AddDecisionQueue("ALLCARDMAXCOSTORPASS", $currentPlayer, $maxCost, 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      return "Lets you opt and put an item from the top of your deck into play.";
      //CRU Merchant
    case "CRU118":
      if (PlayerHasLessHealth(1)) {
        LoseHealth(1, 2);
        PutItemIntoPlayForPlayer("CRU197", 2);
        WriteLog("Player 2 lost a health and gained a copper from Kavdaen");
        if (PlayerHasLessHealth(1)) {
          GainHealth(1, 1);
          WriteLog("Player 1 gained a health from Kavdaen");
        }
      } else if (PlayerHasLessHealth(2)) {
        LoseHealth(1, 1);
        PutItemIntoPlayForPlayer("CRU197", 1);
        WriteLog("Player 1 lost a health and gained a copper from Kavdaen");
        if (PlayerHasLessHealth(2)) {
          GainHealth(1, 2);
          WriteLog("Player 2 gained a health from Kavdaen");
        }
      }
      return "";
      //CRU Ranger
    case "CRU121":
      if (ArsenalFull($currentPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
      AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
      return "";
    case "CRU122":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives face up arrow attacks go again this turn.";
    case "CRU124":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      Reload();
      return "Makes arrow attacks discard on hero hit, and allows you to Reload.";
    case "CRU125":
      SetClassState($currentPlayer, $CS_NextDamagePrevented, 1);
      return "Prevents the next damage you would take.";
    case "CRU126":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_allow_hit_effects_this_chain_link", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("TRIPWIRETRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU127":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_avoid_taking_2_damage", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("PITFALLTRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU128":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_pay_1_to_avoid_your_attack_getting_-1", 1, 1);
      AddDecisionQueue("FINDRESOURCECOST", $otherPlayer, $cardID, 1);
      AddDecisionQueue("PAYRESOURCES", $otherPlayer, "<-", 1);
      AddDecisionQueue("ROCKSLIDETRAP", $otherPlayer, "-", 1);
      return "";
    case "CRU135":
    case "CRU136":
    case "CRU137":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      AddCurrentTurnEffect($cardID . "-1", ($currentPlayer == 1 ? 2 : 1));
      return "Gives the next arrow attack this turn +" . EffectAttackModifier($cardID) . " and prevents defense reactions on that chain link.";
      //CRU Runeblade
    case "CRU141":
      AddCurrentTurnEffect($cardID . "-AA", $currentPlayer);
      AddCurrentTurnEffect($cardID . "-NAA", $currentPlayer);
      return "Reduces the cost of your next attack action card and non-attack action card this turn.";
    case "CRU142":
      if (GetClassState($currentPlayer, $CS_NumNonAttackCards) > 0) PlayAura("ARC112", $currentPlayer);
      AddDecisionQueue("CLASSSTATEGREATERORPASS", $defPlayer, $CS_ArcaneDamageTaken . "-1", 1);
      AddDecisionQueue("PLAYAURA", $currentPlayer, "ARC112", 1);
      return "";
    case "CRU143":
      AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("BANISH", $currentPlayer, "TT", 1);
      return "Banishes a Runeblade attack action card, which can be played this turn.";
    case "CRU144":
      PlayAura("ARC112", $currentPlayer);
      PlayAura("ARC112", $currentPlayer);
      PlayAura("ARC112", $currentPlayer);
      PlayAura("ARC112", $currentPlayer);
      return "Creates 4 Runechant.";
    case "CRU145":
    case "CRU146":
    case "CRU147":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your next Runeblade attack action go again and creates Runechants if it hits.";
    case "CRU154":
    case "CRU155":
    case "CRU156":
      if (!CanRevealCards($currentPlayer)) return "Cannot reveal cards.";
      if ($cardID == "CRU154") $count = 3;
      else if ($cardID == "CRU155") $count = 2;
      else $count = 1;
      $deck = &GetDeck($currentPlayer);
      $cards = "";
      for ($i = 0; $i < $count; ++$i) {
        if (count($deck) > 0) {
          if ($cards != "") $cards .= ",";
          $card = array_shift($deck);
          $cards .= $card;
          if (ClassContains($card, "RUNEBLADE", $currentPlayer) && CardType($card) == "AA") PlayAura("ARC112", $currentPlayer);
        }
      }
      RevealCards($cards);
      AddDecisionQueue("CHOOSETOP", $currentPlayer, $cards);
      return "";
      //CRU Wizard
    case "CRU160":
      DealArcane(2, 0, "PLAYCARD", $cardID);
      return "Deals 2 arcane damage.";
    case "CRU162":
      $rv = "Lets you play your next Wizard non-attack as an instant";
      SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 1);
      if (GetClassState($currentPlayer, $CS_NumWizardNonAttack) >= 2) {
        DealArcane(3, 1, "PLAYCARD", $cardID); //TODO: All opponents
        $rv .= " and deal 3 arcane damage to each opposing hero";
      }
      return $rv . ".";
    case "CRU163":
      Opt($cardID, 2);
      return "";
    case "CRU164":
      NegateLayer($target);
      return "Negates an instant.";
    case "CRU165":
    case "CRU166":
    case "CRU167":
      if ($cardID == "CRU165") $optAmt = 3;
      else if ($cardID == "CRU166") $optAmt = 2;
      else $optAmt = 1;
      AddArcaneBonus(1, $currentPlayer);
      Opt($cardID, $optAmt);
      return "";
    case "CRU168":
    case "CRU169":
    case "CRU170":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID);
      Opt($cardID, 1);
      return "";
    case "CRU171":
    case "CRU172":
    case "CRU173":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID);
      AddArcaneBonus(1, $currentPlayer);
      return "";
    case "CRU174":
    case "CRU175":
    case "CRU176":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID);
      return "";
      //CRU Generics
    case "CRU181":
      $count = SearchCount(CombineSearches(SearchDiscardForCard(1, "CRU181"), SearchDiscardForCard(2, "CRU181")));
      for ($i = 0; $i < $count; ++$i) {
        MyDrawCard();
      }
      return "Draws " . $count . " cards.";
    case "CRU182":
      AddCurrentTurnEffect("CRU182", ($currentPlayer == 1 ? 2 : 1));
      return "Makes attack actions unable to gain attack.";
    case "CRU183":
    case "CRU184":
    case "CRU185":
      if ($from == "ARS") {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $rv = "Gains go again.";
      }
      return $rv;
    case "CRU188":
      MyDrawCard();
      MyDrawCard();
      return "Draws 2 cards.";
    case "CRU189":
    case "CRU190":
    case "CRU191":
      $options = GetChainLinkCards($defPlayer, "AA");
      if ($options == "") return "No defending attack action cards.";
      AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
      AddDecisionQueue("COMBATCHAINBUFFDEFENSE", $currentPlayer, PlayBlockModifier($cardID), 1);
      return "";
    case "CRU197":
      if ($from == "PLAY") {
        MyDrawCard();
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      }
      return "";
    default:
      return "";
  }
}

function CRUHitEffect($cardID)
{
  global $mainPlayer, $defPlayer, $combatChainState;
  global $CCS_NextBoostBuff, $CS_ArcaneDamageTaken, $CCS_HitsInRow;
  switch ($cardID) {
    case "CRU054":
      if (ComboActive()) {
        PlayAura("CRU075", $mainPlayer);
      }
      break;
    case "CRU060":
    case "CRU061":
    case "CRU062":
      if (ComboActive()) RushingRiverHitEffect();
      break;
    case "CRU066":
    case "CRU067":
    case "CRU068":
      GiveAttackGoAgain();
      break;
    case "CRU069":
    case "CRU070":
    case "CRU071":
      GiveAttackGoAgain();
      break;
    case "CRU072":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "CRU074":
      if ($combatChainState[$CCS_HitsInRow] >= 2) {
        MainDrawCard();
        MainDrawCard();
      }
      break;
    case "CRU106":
    case "CRU107":
    case "CRU108":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "CRU109":
    case "CRU110":
    case "CRU111":
      $combatChainState[$CCS_NextBoostBuff] += 3;
      break;
    case "CRU123":
      if (IsHeroAttackTarget()) {
        AddCurrentTurnEffect("CRU123-DMG", $defPlayer);
        AddNextTurnEffect("CRU123-DMG", $defPlayer);
      }
      break;
    case "CRU129":
    case "CRU130":
    case "CRU131":
      if (!ArsenalEmpty($mainPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
      AddDecisionQueue("FINDINDICES", $mainPlayer, "MAINHAND");
      AddDecisionQueue("MAYCHOOSEHAND", $mainPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $mainPlayer, "-", 1);
      AddDecisionQueue("ADDARSENALFACEDOWN", $mainPlayer, "HAND", 1);
      break;
    case "CRU132":
    case "CRU133":
    case "CRU134":
      if (IsHeroAttackTarget()) {
        $char = &GetPlayerCharacter($defPlayer);
        $char[1] = 3;
      }
      break;
    case "CRU142":
      PlayAura("ARC112", $mainPlayer);
      break;
    case "CRU148":
    case "CRU149":
    case "CRU150":
      if (IsHeroAttackTarget() && GetClassState($defPlayer, $CS_ArcaneDamageTaken)) {
        PummelHit();
      }
      break;
    case "CRU151":
    case "CRU152":
    case "CRU153":
      PlayAura("ARC112", $mainPlayer);
      break;
    case "CRU180":
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose any number of options");
      AddDecisionQueue("MULTICHOOSETEXT", $mainPlayer, "3-Quicken_token,Draw_card,Gain_life");
      AddDecisionQueue("COAXCOMMOTION", $mainPlayer, "-", 1);
      break;
    case "CRU183":
    case "CRU184":
    case "CRU185":
      DefenderTopDeckToArsenal();
      MainTopDeckToArsenal();
      break;
    default:
      break;
  }
}

function RushingRiverHitEffect()
{
  global $combatChainState, $CCS_NumHits, $mainPlayer;
  $num = $combatChainState[$CCS_NumHits];
  for ($i = 0; $i < $num; ++$i) {
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
  if ($roll >= 5) $combatChainState[$CCS_LinkBaseAttack] *= 2;
  else $combatChainState[$CCS_LinkBaseAttack] = floor($combatChainState[$CCS_LinkBaseAttack] / 2);
}

function KassaiEndTurnAbility()
{
  global $mainPlayer, $CS_AtksWWeapon, $CS_HitsWithWeapon;
  if (GetClassState($mainPlayer, $CS_AtksWWeapon) >= 2) {
    for ($i = 0; $i < GetClassState($mainPlayer, $CS_HitsWithWeapon); ++$i) {
      PutItemIntoPlayForPlayer("CRU197", $mainPlayer);
    }
  }
}
