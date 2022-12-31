<?php

  $jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/json/json/english/card.json";
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

  GenerateFunction($cardArray, $handler, "CardType", "type");
  GenerateFunction($cardArray, $handler, "AttackValue", "attack");
  GenerateFunction($cardArray, $handler, "BlockValue", "block");
  GenerateFunction($cardArray, $handler, "CardName", "name");
  GenerateFunction($cardArray, $handler, "PitchValue", "pitch");

  fwrite($handler, "?>");

  fclose($handler);

  function GenerateFunction(&$cardArray, $handler, $functionName, $propertyName)
  {
    echo("<BR>" . $functionName . "<BR>");
    fwrite($handler, "function Generated" . $functionName . "(\$cardID) {\r\n");
    $originalSets = ["WTR", "ARC", "CRU", "MON", "ELE", "EVR", "UPR", "DYN", "OUT", "DVR", "RVD"];
    $isString = true;
    if($propertyName == "attack" || $propertyName == "block" || $propertyName == "pitch") $isString = false;
    $trie = [];
    for($i=0; $i<count($cardArray); ++$i)
    {
      $cardPrintings = [];
      if($cardArray[$i]->name == "Nitro Mechanoid") continue;//This is due to the data set not yet differentiating faces
      for($j=0; $j<count($cardArray[$i]->printings); ++$j)
      {
        $cardID = $cardArray[$i]->printings[$j]->id;
        $set = substr($cardID, 0, 3);
        if(!in_array($set, $originalSets)) continue;
        $duplicate = false;
        for($k=0; $k<count($cardPrintings); ++$k)
        {
          if($cardPrintings[$k] == $cardID) $duplicate = true;
        }
        if($duplicate) continue;
        array_push($cardPrintings, $cardID);
        if($propertyName == "type") $data = MapType($cardArray[$i]);
        else if($propertyName == "attack") $data = $cardArray[$i]->power;
        else if($propertyName == "block")
        {
          $data = $cardArray[$i]->defense;
          if($data == "") $data = -1;
        }
        else if($propertyName == "name") $data = $cardArray[$i]->name;
        else if($propertyName == "pitch")
        {
          $data = $cardArray[$i]->pitch;
          if($data == "") $data = 0;
        }
        if($data == "-" || $data == "*") echo("Exception with property name " . $propertyName . " data " . $data . " card " . $cardID . "<BR>");
        if($data != "-" && $data != "" && $data != "*") AddToTrie($trie, $cardID, 0, $data);
      }
    }

    TraverseTrie($trie, "", $handler, $isString);

    fwrite($handler, "}\r\n\r\n");
  }

  function TraverseTrie(&$trie, $keySoFar, &$handler=null, $isString=true)
  {
    $depth = strlen($keySoFar);
    if(is_array($trie))
    {
      fwrite($handler, "switch(\$cardID[" . $depth . "]) {\r\n");
      foreach ($trie as $key => $value)
      {
        fwrite($handler, "case \"" . $key . "\":\r\n");
        TraverseTrie($trie[$key], $keySoFar . $key, $handler, $isString);
      }
      if($isString) fwrite($handler, "default: return \"\";\r\n");
      else fwrite($handler, "default: return 0;\r\n");
      fwrite($handler, "}\r\n");
    }
    else
    {
      if($handler != null)
      {
        if($isString) fwrite($handler, "return \"" . $trie . "\";\r\n");
        else fwrite($handler, "return " . $trie . ";\r\n");
      }
    }
  }

  function AddToTrie(&$trie, $cardID, $depth, $value)
  {
    if($depth < strlen($cardID)-1)
    {
      if(!array_key_exists($cardID[$depth], $trie)) $trie[$cardID[$depth]] = [];
      AddToTrie($trie[$cardID[$depth]], $cardID, $depth+1, $value);
    }
    else if(!isset($trie[$cardID[$depth]])) $trie[$cardID[$depth]] = $value;
  }

  function MapType($card)
  {
    $hasAction = false; $hasAttack = false;
    for($i=0; $i<count($card->types); ++$i)
    {
      if($card->types[$i] == "Action") $hasAction = true;
      else if($card->types[$i] == "Attack") $hasAttack = true;
      else if($card->types[$i] == "Defense") $hasDefense = true;
      else if($card->types[$i] == "Defense Reaction") return "DR";
      else if($card->types[$i] == "Attack Reaction") return "AR";
      else if($card->types[$i] == "Instant") return "I";
      else if($card->types[$i] == "Weapon") return "W";
      else if($card->types[$i] == "Hero") return "C";
      else if($card->types[$i] == "Equipment") return "E";
      else if($card->types[$i] == "Token") return "T";
      else if($card->types[$i] == "Resource") return "R";
      else if($card->types[$i] == "Mentor") return "M";
    }
    if($hasAction && $hasAttack) return "AA";
    else if($hasAction) return "A";
    else
    {
      echo("No type found for " . $card->name);
    }
    return "-";
  }

?>
