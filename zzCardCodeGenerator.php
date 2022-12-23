<?php

  $jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/json/json/card.json";
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

  $filename = "./GeneratedCode/GeneratedCardDictionaries.php";
  $handler = fopen($filename, "w");

  fwrite($handler, "function CardType(\$cardID) {\r\n");
  $trie = [];
  //fwrite($handler, "  switch(\$cardID) {\r\n");
  for($i=0; $i<count($cardArray); ++$i)
  {
    $cardPrintings = [];
    //echo($cardArray[$i]->name . "<BR>");
    for($j=0; $j<count($cardArray[$i]->printings); ++$j)
    {
      $cardID = $cardArray[$i]->printings[$j]->id;
      $duplicate = false;
      for($k=0; $k<count($cardPrintings); ++$k)
      {
        if($cardPrintings[$k] == $cardID) $duplicate = true;
      }
      if($duplicate) continue;
      array_push($cardPrintings, $cardID);
      AddToTrie($trie, $cardID, 0, MapType($cardArray[$i]));
      //echo($cardID . "<BR>");
    }
  }
  //echo(var_dump($trie));
  foreach ($trie as $key1 => $value) {
    foreach ($trie[$key1] as $key2 => $value) {
      foreach ($trie[$key1][$key2] as $key3 => $value) {
        foreach ($trie[$key1][$key2][$key3] as $key4 => $value) {
          foreach ($trie[$key1][$key2][$key3][$key4] as $key5 => $value) {
            foreach ($trie[$key1][$key2][$key3][$key4][$key5] as $key6 => $value) {
              echo($key1 . $key2 . $key3 . $key4 . $key5 . $key6 . "<BR>");
            }
          }
        }
      }
    }
  }
  //fwrite($handler, "default: return \"\";");
  fwrite($handler, "}\r\n");

  fclose($handler);

  function AddToTrie(&$trie, $cardID, $depth, $value)
  {
    if($depth < strlen($cardID)-1)
    {
      if(!array_key_exists($cardID[$depth], $trie)) $trie[$cardID[$depth]] = [];
      AddToTrie($trie[$cardID[$depth]], $cardID, $depth+1, $value);
    }
    else $trie[$cardID[$depth]] = $value;
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
