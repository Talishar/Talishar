<?php

  include './zzImageConverter.php';
  include './Libraries/Trie.php';

  //$jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/v6.1.1/json/english/card.json";
  //$jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/bright-lights/json/english/card.json";
  $jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/the-hunters/json/english/card.json"; //!HNT
  //$jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/develop/json/english/card.json"; 
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
    $SEP = "_";
    $rarityDict = ["T"=>0, "C"=>1, "R"=>2, "M"=>3, "L"=>4, "F"=>5, "V"=>6, "P"=>7, "S"=>8, "-"=>9];
    echo("<BR>" . $functionName . "<BR>");
    fwrite($handler, "function Generated" . $functionName . "(\$cardID) {\r\n");
    $originalSets = ["WTR", "ARC", "CRU", "MON", "ELE", "EVR", "UPR", "DYN", "OUT", "DVR", "RVD", "DTD", "LGS", "HER", "FAB", "JDG", "TCC", "EVO", "HVY", "MST", "AKO", "ASB", "AAZ", "TER", "AUR", "AIO", "ROS", "AJV", "HNT", "ARK"];
    $isString = true;
    $isBool = false;
    if($propertyName == "attack" || $propertyName == "block" || $propertyName == "pitch" || $propertyName == "cost" || $propertyName == "health" || $propertyName == "1H") $isString = false;
    if($propertyName == "1H" || $propertyName == "specialization") $isBool = true;
    fwrite($handler, "if(\$cardID !== null && strlen(\$cardID) < 6) return " . ($isString ? "\"\"" : "0") . ";\r\n");
    fwrite($handler, "if(is_int(\$cardID)) return " . ($isString ? "\"\"" : "0") . ";\r\n");
    fwrite($handler, "return match(\$cardID) {\r\n");
    $associativeArray = [];
    for($i=0; $i<count($cardArray); ++$i)
    {
      $cardRarity = "-";
      $cardID = strtolower($cardArray[$i]->name);
      $setID = $cardArray[$i]->printings[0]->id;
      if ($cardArray[$i]->pitch != "") {
        $cardID .= $SEP . $cardArray[$i]->pitch;
      }
      for($j=0; $j<count($cardArray[$i]->printings); ++$j)
      {
        $printingRarity = $cardArray[$i]->printings[$j]->rarity;
        $cardRarity = $rarityDict[$printingRarity] < $rarityDict[$cardRarity] ? $printingRarity : $cardRarity;
      }
      PopulateAssociativeArray($cardArray, $setID, $associativeArray, $propertyName, $cardID, $i, $isBool, $isString, $defaultValue, $cardRarity);
      if(isset($cardArray[$i]->printings[0]->double_sided_card_info) && !$cardArray[$i]->printings[0]->double_sided_card_info[0]->is_front && $cardArray[$i]->printings[0]->rarity != "T") {
        $backID = strtolower($cardArray[$i]->name) . $SEP . "back";
        PopulateAssociativeArray($cardArray, $setID, $associativeArray, $propertyName, $backID, $i, $isBool, $isString, $defaultValue, $cardRarity,true);
      }
    }
    TraverseAssociativeArray($associativeArray, $handler, $isString, $defaultValue);
    fwrite($handler, "};\r\n}\r\n");
  }

function PopulateAssociativeArray($cardArray, $setID, &$AA, $propertyName, $cardID, $i, $isBool, $isString, $defaultValue, $cardRarity, $isDuplicate=false) {
  switch ($propertyName) {
    case "type":
      $data = MapType($cardArray[$i], $cardID);
      break;
    case "attack":
      $data = $cardArray[$i]->power;
      if($data == "X") $data = 0;
      break;
    case "block":
      $data = $cardArray[$i]->defense;
      if($data == "") $data = -1;
      if($data == "*") $data = 0;
      break;
    case "name":
      $data = $cardArray[$i]->name;
      break;
    case "pitch":
      $data = $cardArray[$i]->pitch;
      if($data == "") $data = 0;
      break;
    case "cost":
      $data = $cardArray[$i]->cost;
      if($data == "") $data = -1;
      $data = intval($data);
      break;
    case "health":
      $data = $cardArray[$i]->health;
      CheckImage($setID, $isDuplicate);
      break;
    case "rarity":
      $data = $cardRarity;
      break;
    case "subtype":
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
      break;
    case "1H":
      $data = "false";
      for($k=0; $k<count($cardArray[$i]->types); ++$k)
      {
        $type = $cardArray[$i]->types[$k];
        if($type == "1H") $data = "true";
      }
      break;
    case "specialization":
      $data = "false";
      for($k=0; $k<count($cardArray[$i]->card_keywords); ++$k)
      {
        $keywordArray = explode(" ", $cardArray[$i]->card_keywords[$k]);
        for($l=0; $l<count($keywordArray); ++$l) {
          if($keywordArray[$l] == "Specialization") $data = "true";
        }
      }
      break;
    case "cardClass":
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
      break;
    case "cardTalent":
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
      break;
    default:
      break;
  }
  if($isBool);
  else if(($isString == false && !is_numeric($data) && $data != "") || $data == "-" || $data == "*" || $data == "X") echo("Exception with property name " . $propertyName . " data " . $data . " card " . $cardID . "<BR>");
  if(($isBool && $data == "true") || ($data != "-" && $data != "" && $data != "*" && $data != $defaultValue))
  {
    $AA[$cardID] = $data;
  }
}

function TraverseAssociativeArray($AA, $handler, $isString, $defaultValue) {
  foreach ($AA as $cardID => $data) {
    fwrite($handler, "\"$cardID\" => \"$data\",\r\n");
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