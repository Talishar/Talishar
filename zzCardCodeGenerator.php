<?php

  include './zzImageConverter.php';
  include './Libraries/Trie.php';

  //$jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/v6.1.1/json/english/card.json";
  //$jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/bright-lights/json/english/card.json";
  //$jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/rosetta/json/english/card.json"; //!ROS
  $jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/develop/json/english/card.json"; 
  $curl = curl_init();
  $headers = array(
    "Content-Type: application/json",
  );
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  curl_setopt($curl, CURLOPT_URL, $jsonUrl);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $cardData = curl_exec($curl);
  curl_close($curl);


  $cardArray = json_decode($cardData);

  if(!is_dir("./GeneratedCode")) mkdir("./GeneratedCode", 777, true);

  $filename = "./GeneratedCode/GeneratedCardDictionaries.php";
  $handler = fopen($filename, "w");

  fwrite($handler, "<?php\r\n");

  GenerateFunction($cardArray, $handler, "CardType", "type", "AA");
  GenerateFunction($cardArray, $handler, "AttackValue", "attack");
  GenerateFunction($cardArray, $handler, "BlockValue", "block", "3");
  GenerateFunction($cardArray, $handler, "CardName", "name");
  GenerateFunction($cardArray, $handler, "PitchValue", "pitch", "1");
  GenerateFunction($cardArray, $handler, "CardCost", "cost", "0");
  GenerateFunction($cardArray, $handler, "CardSubtype", "subtype", "");
  GenerateFunction($cardArray, $handler, "CharacterHealth", "health", "20", true);//Also images
  GenerateFunction($cardArray, $handler, "Rarity", "rarity", "C");
  GenerateFunction($cardArray, $handler, "Is1H", "1H", "false", true);
  GenerateFunction($cardArray, $handler, "CardClass", "cardClass", "NONE");
  GenerateFunction($cardArray, $handler, "CardTalent", "cardTalent", "NONE");
  GenerateFunction($cardArray, $handler, "IsSpecialization", "specialization", "", true);

  fwrite($handler, "?>");

  fclose($handler);

  function GenerateFunction(&$cardArray, $handler, $functionName, $propertyName, $defaultValue="", $sparse=false)
  {
    echo("<BR>" . $functionName . "<BR>");
    fwrite($handler, "function Generated" . $functionName . "(\$cardID) {\r\n");
    $originalSets = ["WTR", "ARC", "CRU", "MON", "ELE", "EVR", "UPR", "DYN", "OUT", "DVR", "RVD", "DTD", "LGS", "HER", "FAB", "JDG", "TCC", "EVO", "HVY", "MST", "AKO", "ASB", "AAZ", "TER", "AUR", "AIO", "ROS", "AJV"];
    $isString = true;
    $isBool = false;
    if($propertyName == "attack" || $propertyName == "block" || $propertyName == "pitch" || $propertyName == "cost" || $propertyName == "health" || $propertyName == "1H") $isString = false;
    if($propertyName == "1H" || $propertyName == "specialization") $isBool = true;
    fwrite($handler, "if(\$cardID !== null && strlen(\$cardID) < 6) return " . ($isString ? "\"\"" : "0") . ";\r\n");
    fwrite($handler, "if(is_int(\$cardID)) return " . ($isString ? "\"\"" : "0") . ";\r\n");
    if($sparse) fwrite($handler, "switch(\$cardID) {\r\n");
    $trie = [];
    $cardsSeen = [];
    for($i=0; $i<count($cardArray); ++$i)
    {
      $cardRarity = "NA";
      $cardPrintings = [];
      for($j=0; $j<count($cardArray[$i]->printings); ++$j)
      {
        $cardRarity = $cardArray[$i]->printings[$j]->rarity;
        $cardID = $cardArray[$i]->printings[$j]->id;
        $set = substr($cardID, 0, 3);
        $cardNumber = substr($cardID, 3, 3);
        if(!in_array($set, $originalSets)) continue;
        if($set == "LSS" && $cardNumber != 004) continue;
        if($set == "LGS" && $cardNumber < 176) continue;
        if($set == "LGS" && $cardNumber > 178) continue;
        if($set == "HER" && $cardNumber != 117 && $cardNumber != 100 && $cardNumber != 123) continue;
        if($set == "FAB" && $cardNumber < 300) continue;
        if(isset($cardArray[$i]->printings[0]->double_sided_card_info) && !$cardArray[$i]->printings[0]->double_sided_card_info[0]->is_front && $cardArray[$i]->printings[0]->rarity != "T") { $cardNumber += 400; $cardID = $set . $cardNumber; }
        else {
          $duplicate = false;
          for($k=0; $k<count($cardPrintings); ++$k)
          {
            if($cardPrintings[$k] == $cardID) $duplicate = true;
          }
          for($k=0; $k<count($cardsSeen); ++$k)
          {
            if($cardsSeen[$k] == $cardID) $duplicate = true;
          }
          if($duplicate && $set != "DVR" && $set != "RVD") continue;
        }
        array_push($cardPrintings, $cardID);
        array_push($cardsSeen, $cardID);
        PopulateTrie($cardArray, $handler, $trie, $propertyName, $cardID, $i, $sparse, $isBool, $isString, $defaultValue, $cardRarity);
        if(ShouldDuplicate($cardArray[$i])) PopulateTrie($cardArray, $handler, $trie, $propertyName, $set . ($cardNumber + 400), $i, $sparse, $isBool, $isString, $defaultValue, $cardRarity, true);
      }
    }
    if($sparse) fwrite($handler, "default: return " . ($isString ? "\"$defaultValue\"" : $defaultValue) . ";}\r\n");
    else TraverseTrie($trie, "", $handler, $isString, $defaultValue);

    fwrite($handler, "}\r\n\r\n");
  }

  function PopulateTrie(&$cardArray, $handler, &$trie, $propertyName, $cardID, $i, $sparse, $isBool, $isString, $defaultValue, $cardRarity, $isDuplicate=false) {
    if($propertyName == "type") $data = MapType($cardArray[$i], $cardID);
    else if($propertyName == "attack") {
      $data = $cardArray[$i]->power;
      if($data == "X") $data = 0;
    }
    else if($propertyName == "block")
    {
      $data = $cardArray[$i]->defense;
      if($data == "") $data = -1;
      if($data == "*") $data = 0;
    }
    else if($propertyName == "name") $data = $cardArray[$i]->name;
    else if($propertyName == "pitch")
    {
      $data = $cardArray[$i]->pitch;
      if($data == "") $data = 0;
    }
    else if($propertyName == "cost")
    {
      $data = $cardArray[$i]->cost;
      if($data == "") $data = -1;
      $data = intval($data);
    }
    else if($propertyName == "health")
    {
      $data = $cardArray[$i]->health;
      CheckImage($cardID, $isDuplicate);
    }
    else if($propertyName == "rarity")
    {
      $data = $cardRarity;
    }
    else if($propertyName == "rarity")
    {
      $data = $cardRarity;
    }
    else if($propertyName == "subtype")
    {
      $data = "";
      for($k=0; $k<count($cardArray[$i]->types); ++$k)
      {
        $type = $cardArray[$i]->types[$k];
        if(!IsCardType($type) && !IsClass($type) && !IsTalent($type) && !IsHandedness($type))
        {
          if($data != "") $data .= ",";
          $data .= $type;
        }
      }
    }
    else if($propertyName == "1H")
    {
      $data = "false";
      for($k=0; $k<count($cardArray[$i]->types); ++$k)
      {
        $type = $cardArray[$i]->types[$k];
        if($type == "1H") $data = "true";
      }
    }
    else if($propertyName == "specialization")
    {
      $data = "false";
      for($k=0; $k<count($cardArray[$i]->card_keywords); ++$k)
      {
        $keywordArray = explode(" ", $cardArray[$i]->card_keywords[$k]);
        for($l=0; $l<count($keywordArray); ++$l) {
          if($keywordArray[$l] == "Specialization") $data = "true";
        }
      }
    }
    else if($propertyName == "cardClass")
    {
      $data = "";
      for($k=0; $k<count($cardArray[$i]->types); ++$k)
      {
        $type = $cardArray[$i]->types[$k];
        if(IsClass($type))
        {
          if($data != "") $data .= ",";
          $data .= strtoupper($type);
        }
      }
    }
    else if($propertyName == "cardTalent")
    {
      $data = "";
      for($k=0; $k<count($cardArray[$i]->types); ++$k)
      {
        $type = $cardArray[$i]->types[$k];
        if(IsTalent($type))
        {
          if($data != "") $data .= ",";
          $data .= strtoupper($type);
        }
      }
    }
    if($isBool);
    else if(($isString == false && !is_numeric($data) && $data != "") || $data == "-" || $data == "*" || $data == "X") echo("Exception with property name " . $propertyName . " data " . $data . " card " . $cardID . "<BR>");
    if(($isBool && $data == "true") || ($data != "-" && $data != "" && $data != "*" && $data != $defaultValue))
    {
      if($sparse) fwrite($handler, "case \"" . $cardID . "\": return " . ($isString ? "\"$data\"" : $data) . ";\r\n");
      else AddToTrie($trie, $cardID, 0, $data);
    }
  }

  function MapType($card, $cardID)
  {
    $hasAction = false; $hasAttack = false; $hasInstant = false;
    $cardNumber = substr($cardID, 3, 3);
    for($i=0; $i<count($card->types); ++$i)
    {
      if($card->types[$i] == "Action") $hasAction = true;
      else if($card->types[$i] == "Attack") $hasAttack = true;
      else if($card->types[$i] == "Defense") $hasDefense = true;
      else if($card->types[$i] == "Defense Reaction") return "DR";
      else if($card->types[$i] == "Attack Reaction") return "AR";
      else if($card->types[$i] == "Instant") $hasInstant = true;
      else if($card->types[$i] == "Weapon") return "W";
      else if($card->types[$i] == "Hero") return "C";
      else if($card->types[$i] == "Equipment" && ($cardNumber >= 400 || (!$hasAction && !$hasInstant))) return "E";
      else if($card->types[$i] == "Token") return "T";
      else if($card->types[$i] == "Resource") return "R";
      else if($card->types[$i] == "Mentor") return "M";
      else if($card->types[$i] == "Ally") return "ALLY";
      else if($card->types[$i] == "Demi-Hero") return "D";
      else if($card->types[$i] == "Block") return "B";
    }
    if($hasAction && $hasAttack) return "AA";
    else if($hasAction) return "A";
    else if($hasInstant) return "I";
    else
    {
      echo("No type found for " . $card->name);
    }
    return "-";
  }

  function IsCardType($term)
  {
    switch($term)
    {
      case "Action": case "Attack": case "Defense Reaction": case "Attack Reaction":
      case "Instant": case "Weapon": case "Hero": case "Equipment": case "Token":
      case "Resource": case "Mentor": return true;
      default: return false;
    }
  }

  function IsClass($term)
  {
    switch($term)
    {
      case "Generic": case "Warrior": case "Ninja": case "Brute": case "Guardian":
      case "Wizard": case "Mechanologist": case "Ranger": case "Runeblade":
      case "Illusionist": case "Assassin": return true;
      case "Shapeshifter": case "Merchant": case "Arbiter": case "Bard": return true;
      default: return false;
    }
  }

  function IsTalent($term)
  {
    switch($term)
    {
      case "Elemental": case "Light": case "Shadow": case "Draconic": return true;
      case "Ice": case "Lightning": case "Earth": case "Mystic": return true;
      default: return false;
    }
  }

  function IsHandedness($term)
  {
    switch($term)
    {
      case "1H": case "2H": return true;
      default: return false;
    }
  }

  function ShouldDuplicate($card)
  {
    $hasAction = false; $hasEquipment = false; $hasInstant = false;
    for($i=0; $i<count($card->types); ++$i) 
    {
      if($card->types[$i] == "Action") $hasAction = true;
      else if($card->types[$i] == "Equipment") $hasEquipment = true;
      else if($card->types[$i] == "Instant") $hasInstant = true;
    }
    return ($hasAction && $hasEquipment) || ($hasInstant && $hasEquipment);
  }