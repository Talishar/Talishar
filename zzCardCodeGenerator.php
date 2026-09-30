<?php
if (PHP_SAPI !== "cli") { http_response_code(404); exit; }

  include __DIR__ . '/zzImageConverter.php';
  include __DIR__ . '/Libraries/Trie.php';

  $originalSets = ["WTR", "ARC", "CRU", "MON", "ELE", "EVR", "UPR", "DYN", "OUT", "DVR", "RVD", "DTD", "TCC", "EVO", "HVY",
                   "MST", "AKO", "ASB", "AAZ", "ROS", "TER", "AUR", "AIO", "AJV", "HNT", "ARK", "AST", "AMX", "LGS", "HER",
                   "FAB", "JDG", "SEA", "AGB", "MPG", "ASR", "APR", "AVS", "BDD", "SMP", "SUP", "APS", "ARR", "AAC", "AHA", 
                   "PEN", "OMN", "AZS", "MPW", "AOL", "DDD", "IAR", "AMA", "SAT", "SBW", "MPA", "AMO", "TNP", "SPW"];

  // Main branch FAB Cube
  // $jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/develop/json/english/card.json";
  // Feature branch FAB Cube
  $jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/usurp-the-shadow-throne/json/english/card.json";

  $curl = curl_init();
  $headers = [ "Content-Type: application/json" ];
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_URL, $jsonUrl);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $cardData = curl_exec($curl);

  $cardArray = json_decode($cardData);

  $manualPrintings = [
    "rise_to_the_challenge_red" => ["id" => "IAR050", "rarity" => "C"],
    "rise_to_the_challenge_yellow" => ["id" => "IAR051", "rarity" => "C"],
    "rise_to_the_challenge_blue" => ["id" => "IAR052", "rarity" => "C"]
  ];
  PatchMissingPrintings($cardArray, $manualPrintings);

  if(!is_dir(__DIR__ . "/GeneratedCode")) mkdir(__DIR__ . "/GeneratedCode", 777, true);

  $filename = __DIR__ . "/GeneratedCode/GeneratedCardDictionaries.php";

  $generatedFunctions = [];
  $generatedFunctions[] = GenerateFunction($cardArray, "CardType", "type", "AA");
  $generatedFunctions[] = GenerateFunction($cardArray, "PowerValue", "attack", "0");
  $generatedFunctions[] = GenerateFunction($cardArray, "BlockValue", "block", "3");
  $generatedFunctions[] = GenerateFunction($cardArray, "CardName", "name");
  $generatedFunctions[] = GenerateFunction($cardArray, "PitchValue", "pitch", "1");
  $generatedFunctions[] = GenerateFunction($cardArray, "CardCost", "cost", "0");
  $generatedFunctions[] = GenerateFunction($cardArray, "CardSubtype", "subtype", "");
  $generatedFunctions[] = GenerateFunction($cardArray, "CharacterHealth", "health", "20");//Also images
  $generatedFunctions[] = GenerateFunction($cardArray, "CharacterIntellect", "intelligence", "4");
  $generatedFunctions[] = GenerateFunction($cardArray, "Rarity", "rarity", "C");
  $generatedFunctions[] = GenerateFunction($cardArray, "Is1H", "1H", "false");
  $generatedFunctions[] = GenerateFunction($cardArray, "CardClass", "cardClass", "NONE");
  $generatedFunctions[] = GenerateFunction($cardArray, "CardTalent", "cardTalent", "NONE");
  $generatedFunctions[] = GenerateFunction($cardArray, "SetID", "setID", "");
  $generatedFunctions[] = GenerateFunction($cardArray, "SetIDtoCardID", "SIDtoCID", "");
  $generatedFunctions[] = GenerateFunction($cardArray, "GoAgain", "goAgain", "false");

  // Generate keyword functions
  $keywordList = [
    "Ambush", "Amp", "Arcane Barrier", "Arcane Shelter", "Awaken", "Battleworn",
    "Beat Chest", "Blade Break", "Blood Debt", " Earth Bond", "Ice Bond", "Lightning Bond", "Boost", "Channel",
    "Charge", "Clash", "Cloaked", "Combo", "Contract", "Crank", "Crush", "Decompose", "Dominate", "Ephemeral", 
    "Essence of Earth", "Essence of Ice", "Essence of Lightning", "Evo Upgrade", "Lightning Flow",
    "Freeze", "Earth Fusion", "Ice Fusion", "Lightning Fusion", "Galvanize", "Go Fish", "Guardwell",
    "Heave", "Heavy", "High Tide", "Intimidate", "Legendary", "Mark", "Material",
    "Meld", "Mirage", "Modular", "Negate", "Opt", "Overpower", "Pairs", "Perched",
    "Phantasm", "Piercing", "Protect", "Quell", "Reload", "Reprise", "Retrieve",
    "Rune Gate", "Rupture", "Scrap", "Sharpen", "Solflare", "Specialization",
    "Spectra", "Spellvoid", "Steal", "Stealth", "Surge", "Suspense", "Temper",
    "The Crowd Boos", "The Crowd Cheers", "Tower", "Transcend", "Transform",
    "Unfreeze", "Unity", "Universal", "Unlimited", "Wager", "Ward", "Watery Grave",
    "Fragment", "Quickstrike", "Usurp", "Starfall", "Decay", "Incarnate"
  ];

  $hasKeywordAmount = [ "Amp", "Arcane Barrier", "Arcane Shelter", "Heave", "Opt", "Quell", "Spellvoid", "Ward"];

  $essenceElements = ["Earth", "Ice", "Lightning"];

  foreach ($keywordList as $keyword) {
    $functionName = str_replace(" ", "", $keyword); // Remove spaces for function names
    $generatedFunctions[] = GenerateKeywordFunction($cardArray, "Has" . $functionName, $keyword, false);
    if (in_array($keyword, $hasKeywordAmount)) $generatedFunctions[] = GenerateKeywordFunction($cardArray, $functionName . "Amount", $keyword, true);
  }
  $generatedFunctions[] = GenerateKeywordFunction($cardArray, "IsAndOrFuse", "and/or", false);

  $generatedFunctions[] = GenerateCardTokensFunction($cardArray);

  WriteGeneratedDictionaries($filename, $generatedFunctions);

  function GetCardIdentifier($name, $pitch, $delimiter="_")
  {
    if ($name == "Goldfin Harpoon") return "goldfin_harpoon_yellow";
    $cardID = strtolower($name);
    $cardID = str_replace("//", $delimiter, $cardID);
    $cardID = str_replace(["ā", "ä", "ö", "ü", "ß", "ṣ"], ["a", "a", "o", "u", "s", "s"], $cardID);
    $cardID = iconv('UTF-8', 'US-ASCII//TRANSLIT', $cardID);
    $cardID = str_replace(" ", $delimiter, $cardID);
    $cardID = str_replace("-", $delimiter, $cardID);
    $cardID = preg_replace("/[^a-z0-9 $delimiter]/", "", $cardID);
    $cardID = preg_replace("/$delimiter$delimiter/", $delimiter, $cardID);
    $suffix = match($pitch) {
      1 => "_red",
      "1" => "_red",
      2 => "_yellow",
      "2" => "_yellow",
      3 => "_blue",
      "3" => "_blue",
      4 => "_purple",
      "4" => "_purple",
      default => ""
    };
    return $cardID . $suffix;
  }

  function PatchMissingPrintings(&$cardArray, $manualPrintings)
  {
    for($i=0; $i<count($cardArray); ++$i)
    {
      if(count($cardArray[$i]->printings) > 0) continue;
      $cardID = GetCardIdentifier($cardArray[$i]->name, $cardArray[$i]->pitch);
      if(!isset($manualPrintings[$cardID])) continue;
      $setID = $manualPrintings[$cardID]["id"];
      $cardArray[$i]->printings[] = (object)[
        "id" => $setID,
        "set_id" => substr($setID, 0, 3),
        "rarity" => $manualPrintings[$cardID]["rarity"],
        "edition" => "N",
        "foiling" => "S",
        "art_variations" => [],
        "image_url" => "https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/large/" . $setID . ".webp"
      ];
      echo "Patched missing printing " . $setID . " onto " . $cardID . "<BR>";
    }
  }

  // Returns the earliest valid printing, for backwards compatability. Other LGS promos are only used when a card has
  // no other printing (e.g. batter_to_a_pulp_red), so reprinted cards keep their original set ID.
  function EarliestSetID($card)
  {
    global $originalSets;
    foreach ([false, true] as $allowAnyLGS) {
      $setID = "";
      $earliestSetIndex = count($originalSets) + 1;
      for ($j = 0; $j < count($card->printings); $j++) {
        $tempSetID = $card->printings[$j]->id;
        if (!ValidSet($tempSetID, $allowAnyLGS)) continue;
        $ind = array_search(substr($tempSetID, 0, 3), $originalSets);
        if ($ind < $earliestSetIndex) {
          $setID = $tempSetID;
          $earliestSetIndex = $ind;
        }
      }
      if ($setID != "") return $setID;
    }
    return "";
  }

  function ValidSet($setID, $allowAnyLGS = false)
  {
    global $originalSets;
    $set = substr($setID, 0, 3);
    $cardNumber = $cardNumber = substr($setID, 3, 3);
    if(!in_array($set, $originalSets)) return false;
    if($set == "LSS" && $cardNumber != 004) return false;
    if($set == "LGS" && $allowAnyLGS) return true;
    if($set == "LGS" && $cardNumber < 176) return false;
    if($set == "LGS" && $cardNumber == 406) return true;
    if($set == "LGS" && $cardNumber > 178) return false;
    if($set == "HER" && $cardNumber != 117 && $cardNumber != 100 && $cardNumber != 123 && $cardNumber != 130) return false;
    if($set == "FAB" && $cardNumber < 500) return false;
    if($set == "HNT" && $cardNumber == 261) return false;
    if($set == "SEA" && $cardNumber > 261) return false;
    return true;
  }

  // Returns the function's entries (key => PHP literal) and defaults instead of writing them, so zzCardCodeGeneratorCSV.php can merge in more
  function GenerateFunction(&$cardArray, $functionName, $propertyName, $defaultValue="")
  {
    $rarityDict = ["T"=>0, "B"=>0, "C"=>1, "R"=>2, "M"=>3, "L"=>4, "F"=>5, "V"=>6, "P"=>7, "S"=>8, "-"=>9];
    echo "<BR>" . $functionName . "<BR>";
    $isString = true;
    $isBool = false;
    if($propertyName == "attack" || $propertyName == "block" || $propertyName == "pitch" || $propertyName == "cost" || $propertyName == "health" || $propertyName == "intelligence" || $propertyName == "1H" || $propertyName == "goAgain") $isString = false;
    if($propertyName == "1H" || $propertyName == "specialization" || $propertyName == "legendary" || $propertyName == "goAgain") $isBool = true;
    $associativeArray = [];
    for($i=0; $i<count($cardArray); ++$i)
    {
      $cardRarity = "-";
      $cardID = GetCardIdentifier($cardArray[$i]->name, $cardArray[$i]->pitch);
      $setID = EarliestSetID($cardArray[$i]);
      switch ($cardID) {
        case "minerva_themis":
          $setID = "MON405";
          break;
        case "lady_barthimont":
          $setID = "MON406";
          break;
        case "the_librarian":
          $setID = "MON404";
          break;
        case "lord_sutcliffe":
          $setID = "MON407";
          break;
        default:
          break;
      }
      $set = substr($setID, 0, 3);
      $cardNumber = substr($setID, 3, 3);
      if (!is_numeric($cardNumber))
        $cardNumber = 0;
      // get lowest rarity printing
      for($j=0; $j<count($cardArray[$i]->printings); ++$j) {
        $printingRarity = $cardArray[$i]->printings[$j]->rarity;
        $cardRarity = $rarityDict[$printingRarity] < $rarityDict[$cardRarity] ? $printingRarity : $cardRarity;
      }
      if(isset($cardArray[$i]->printings[0]->double_sided_card_info) && !$cardArray[$i]->printings[0]->double_sided_card_info[0]->is_front && $cardArray[$i]->printings[0]->rarity != "T") {
        // inner chi needs to be handled separately because it's the back of multiple cards
        if ($cardID == "inner_chi_blue") {
          for ($j = 0; $j < count($cardArray[$i]->printings); $j++) {
            $setID = $cardArray[$i]->printings[$j]->id;
            $set = substr($setID, 0, 3);
            $cardNumber = substr($setID, 3, 3);
            $backCardID = $setID . "_" . $cardID;
            if (!ValidSet($setID)) continue;
            PopulateAssociativeArray($cardArray, $set . ($cardNumber + 400), $associativeArray, $propertyName, $backCardID, $i, $isBool, $isString, $defaultValue, $cardRarity);
          }
        }
        else {
          $set = substr($setID, 0, 3);
          $number = intval(substr($setID, 3)) + 400;
          $setID = $set . $number;
          PopulateAssociativeArray($cardArray, $setID, $associativeArray, $propertyName, $cardID, $i, $isBool, $isString, $defaultValue, $cardRarity);
        }
      }
      else {
        PopulateAssociativeArray($cardArray, $setID, $associativeArray, $propertyName, $cardID, $i, $isBool, $isString, $defaultValue, $cardRarity);
        if (ShouldDuplicate($cardArray[$i])) {
          $equippedID = $cardID . "_equip";
          PopulateAssociativeArray($cardArray, $set . ($cardNumber + 400), $associativeArray, $propertyName, $equippedID, $i, $isBool, $isString, $defaultValue, $cardRarity, true);
        }
        if (PerchDuplicate($cardID)) {
          $unperchedID = $cardID . "_ally";
          PopulateAssociativeArray($cardArray, $set . ($cardNumber + 400), $associativeArray, $propertyName, $unperchedID, $i, $isBool, $isString, $defaultValue, $cardRarity, true);
        }
        if (ReverseID($cardID) != "") {
          $reversedID = $cardID . "_r";
          PopulateAssociativeArray($cardArray, ReverseID($cardID), $associativeArray, $propertyName, $reversedID, $i, $isBool, $isString, $defaultValue, $cardRarity, true, true);
        }
      }
    }
    $entries = [];
    foreach ($associativeArray as $key => $data) $entries[$key] = $isString ? "\"$data\"" : $data;
    return GeneratedFunction($functionName, $entries, $isString ? "\"$defaultValue\"" : $defaultValue, $isString ? "\"\"" : "0");
  }

  function GenerateKeywordFunction(&$cardArray, $functionName, $keyword, $isAmountFunction)
  {
    echo "<BR>" . $functionName . "<BR>";
    $associativeArray = [];
    
    for($i=0; $i<count($cardArray); ++$i)
    {
      $cardID = GetCardIdentifier($cardArray[$i]->name, $cardArray[$i]->pitch);
      $setID = EarliestSetID($cardArray[$i]);

      switch ($cardID) {
        case "minerva_themis":
          $setID = "MON405";
          break;
        case "lady_barthimont":
          $setID = "MON406";
          break;
        case "the_librarian":
          $setID = "MON404";
          break;
        case "lord_sutcliffe":
          $setID = "MON407";
          break;
        default:
          break;
      }
      
      $set = substr($setID, 0, 3);
      $cardNumber = substr($setID, 3, 3);
      
      // Check if card has the keyword
      $hasKeyword = false;
      $keywordAmount = 0;
      
      if (isset($cardArray[$i]->card_keywords)) {
        foreach ($cardArray[$i]->card_keywords as $cardKeyword) {
          if (ExtractKeywordMatch($cardKeyword, $keyword, $keywordAmount)) {
            $hasKeyword = true;
            break;
          }
        }
      }
      
      if ($hasKeyword) {
        if(isset($cardArray[$i]->printings[0]->double_sided_card_info) && !$cardArray[$i]->printings[0]->double_sided_card_info[0]->is_front && $cardArray[$i]->printings[0]->rarity != "T") {
          if ($cardID == "inner_chi_blue") {
            for ($j = 0; $j < count($cardArray[$i]->printings); $j++) {
              $setID = $cardArray[$i]->printings[$j]->id;
              $set = substr($setID, 0, 3);
              $cardNumber = substr($setID, 3, 3);
              $backCardID = $setID . "_" . $cardID;
              if (!ValidSet($setID)) continue;
              if (!isset($associativeArray[$backCardID])) {
                $associativeArray[$backCardID] = $isAmountFunction ? $keywordAmount : "true";
              }
            }
          }
          else {
            $set = substr($setID, 0, 3);
            $number = intval(substr($setID, 3)) + 400;
            $setID = $set . $number;
            if (!isset($associativeArray[$cardID])) {
              $associativeArray[$cardID] = $isAmountFunction ? $keywordAmount : "true";
            }
          }
        }
        else {
          if (!isset($associativeArray[$cardID])) {
            $associativeArray[$cardID] = $isAmountFunction ? $keywordAmount : "true";
          }
          if (ShouldDuplicate($cardArray[$i])) {
            $equippedID = $cardID . "_equip";
            if (!isset($associativeArray[$equippedID])) {
              $associativeArray[$equippedID] = $isAmountFunction ? $keywordAmount : "true";
            }
          }
          if (PerchDuplicate($cardID)) {
            $unperchedID = $cardID . "_ally";
            if (!isset($associativeArray[$unperchedID])) {
              $associativeArray[$unperchedID] = $isAmountFunction ? $keywordAmount : "true";
            }
          }
          if (ReverseID($cardID) != "") {
            $reversedID = $cardID . "_r";
            if (!isset($associativeArray[$reversedID])) {
              $associativeArray[$reversedID] = $isAmountFunction ? $keywordAmount : "true";
            }
          }
        }
      }
    }
    
    $default = $isAmountFunction ? "0" : "false";
    $entries = [];
    foreach ($associativeArray as $cID => $value) $entries[$cID] = $isAmountFunction ? $value : "true";
    return GeneratedFunction($functionName, $entries, $default, $default);
  }

  function GenerateCardTokensFunction(&$cardArray)
  {
    echo "<BR>CardTokens<BR>";
    $tokens = [];
    for ($i = 0; $i < count($cardArray); ++$i) {
      if (!in_array("Token", $cardArray[$i]->types)) continue;
      if (!isset($tokens[$cardArray[$i]->name])) {
        $tokens[$cardArray[$i]->name] = GetCardIdentifier($cardArray[$i]->name, $cardArray[$i]->pitch);
      }
    }

    $associativeArray = [];
    for ($i = 0; $i < count($cardArray); ++$i) {
      $cardID = GetCardIdentifier($cardArray[$i]->name, $cardArray[$i]->pitch);
      if (isset($associativeArray[$cardID])) continue;
      $text = $cardArray[$i]->functional_text_plain ?? "";
      if ($text == "") continue;
      $text = str_replace($cardArray[$i]->name, "", $text);
      $matched = [];
      foreach ($tokens as $tokenName => $tokenID) {
        if ($tokenName == $cardArray[$i]->name) continue;
        if (preg_match('/\b' . preg_quote($tokenName, '/') . 's?\b/u', $text)) $matched[] = $tokenID;
      }
      if (count($matched) == 0) continue;
      $matched = array_unique($matched);
      sort($matched);
      $associativeArray[$cardID] = implode(",", $matched);
    }

    $entries = [];
    foreach ($associativeArray as $cID => $tokenList) $entries[$cID] = "\"$tokenList\"";
    return GeneratedFunction("CardTokens", $entries, "\"\"", "\"\"");
  }

  // $entries maps each card (or set ID) to the PHP literal it returns; $intReturn is what it returns for an int card ID
  function GeneratedFunction($name, $entries, $default, $intReturn)
  {
    return ["name" => $name, "entries" => $entries, "default" => $default, "intReturn" => $intReturn];
  }

  function WriteMatchFunction($handler, $functionName, $entries, $default, $intReturn = null)
  {
    fwrite($handler, "function " . $functionName . "(\$cardID) {\r\n");
    if ($intReturn !== null) fwrite($handler, "if(is_int(\$cardID)) return " . $intReturn . ";\r\n");
    fwrite($handler, "return match(\$cardID) {\r\n");
    foreach ($entries as $key => $literal) fwrite($handler, "\"$key\" => $literal,\r\n");
    fwrite($handler, "default => $default\r\n");
    fwrite($handler, "};\r\n}\r\n");
  }

  // Writes each function to GeneratedFunctions/Generated{Name}.php next to $filename, and $filename to include them all
  function WriteGeneratedDictionaries($filename, $generatedFunctions)
  {
    $functionDirectory = dirname($filename) . "/GeneratedFunctions";
    if (!is_dir($functionDirectory)) mkdir($functionDirectory, 0777, true);
    foreach (glob($functionDirectory . "/*.php") as $oldFile) unlink($oldFile); // So functions no longer generated don't linger

    $handler = fopen($filename, "w");
    fwrite($handler, "<?php\r\n");
    fwrite($handler, "// Each generated function is in its own file in GeneratedFunctions\r\n");
    foreach ($generatedFunctions as $function) {
      $functionFile = "Generated" . $function["name"] . ".php";
      fwrite($handler, "include_once __DIR__ . \"/GeneratedFunctions/" . $functionFile . "\";\r\n");
      $functionHandler = fopen($functionDirectory . "/" . $functionFile, "w");
      fwrite($functionHandler, "<?php\r\n");
      WriteMatchFunction($functionHandler, "Generated" . $function["name"], $function["entries"], $function["default"], $function["intReturn"]);
      fwrite($functionHandler, "?>");
      fclose($functionHandler);
    }
    fwrite($handler, "?>");
    fclose($handler);
  }

  function ExtractKeywordMatch($cardKeyword, $keyword, &$amount)
  {
    global $essenceElements;
    
    $cardKeyword = trim($cardKeyword);
    
    // Handle keywords with numbers (e.g., "Ward 10", "Amp 2")
    $parts = explode(" ", $cardKeyword);
    $keywordWithoutNumber = trim(preg_replace('/\s+\d+$/', '', $cardKeyword));
    
    if ($keywordWithoutNumber === $keyword) {
      // Extract the number if it exists
      $amount = (count($parts) > 0 && is_numeric(end($parts))) ? intval(end($parts)) : 1;
      return true;
    }
    
    if (strpos($keywordWithoutNumber, " " . $keyword) !== false || $keywordWithoutNumber === $keyword) {
      $amount = 1;
      return true;
    }
    
    if (strpos($keyword, "Essence of") === 0) {
      $element = substr($keyword, strlen("Essence of "));
      
      if (strpos($cardKeyword, "Essence of") === 0 && strpos($cardKeyword, $element) !== false) {
        $amount = 1;
        return true;
      }
    }
    
    if (strpos($cardKeyword, " and ") !== false) {
      $compositeKeywords = array_map('trim', explode(" and ", $cardKeyword));
      foreach ($compositeKeywords as $compositeKeyword) {
        // Remove any trailing numbers from composite keywords
        $compositeKeywordClean = trim(preg_replace('/\s+\d+$/', '', $compositeKeyword));
        if ($compositeKeywordClean === $keyword) {
          // Extract the number if it exists
          $amount = (is_numeric(substr($compositeKeyword, -1))) ? intval(substr($compositeKeyword, -1)) : 1;
          return true;
        }
      }
    }
    
    if (strpos($cardKeyword, ",") !== false || strpos($cardKeyword, " and ") !== false) {
      $normalized = str_replace(", ", " and ", $cardKeyword);
      $compositeKeywords = array_map('trim', explode(" and ", $normalized));
      foreach ($compositeKeywords as $compositeKeyword) {
        $compositeKeywordClean = trim(preg_replace('/\s+\d+$/', '', $compositeKeyword));
        if ($compositeKeywordClean === $keyword) { 
          $amount = (is_numeric(substr($compositeKeyword, -1))) ? intval(substr($compositeKeyword, -1)) : 1;
          return true;
        }
      }
    }
    
    return false;
  }

  function PopulateAssociativeArray($cardArray, $setID, &$AA, $propertyName, $cardID, $i, $isBool, $isString, $defaultValue, $cardRarity, $isDuplicate=false, $getImage=True) {
    if (!isset($AA[$cardID])) {
      $data = "";
      switch ($propertyName) {
        case "type":
          $data = MapType($cardArray[$i], $setID);
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
          if ($getImage && $cardID != "hunters_klaive_r") CheckImage($setID, $cardID, $isDuplicate);
          if (MapType($cardArray[$i], $setID) == "C") CheckImage($setID, $setID, $isDuplicate);
          break;
        case "intelligence":
          $data = $cardArray[$i]->intelligence;
          break;
        case "rarity":
          $data = $cardRarity;
          break;
        case "subtype":
          $data = "";
          for($k=0; $k<count($cardArray[$i]->types); ++$k)
          {
            $type = $cardArray[$i]->types[$k];
            if(!IsCardType($type) && !IsClass($type) && !IsTalent($type) && !IsHandedness($type) && !IsHero($type))
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
        case "setID":
          $data = $setID;
          break;
        case "SIDtoCID":
          $data = $cardID;
          break;
        case "goAgain":
          $data = "false";
          if (isset($cardArray[$i]->functional_text)) {
            if (str_contains($cardArray[$i]->functional_text, "Go again")) $data = "true";
          }
          break;
        default:
          break;
      }
      if($isBool);
      else if($isString == false && !is_numeric($data) && $data != "" || $data == "-" || $data == "*" || $data == "X") echo "Exception with property name " . $propertyName . " data " . $data . " card " . $cardID . "<BR>";
      if($isBool && $data == "true" || $data != "-" && $data != "" && $data != "*" && $data != $defaultValue)
      {
        if ($propertyName != "SIDtoCID") $AA[$cardID] = $data;
        else $AA[$setID] = $cardID;
      }
    }
  }

  function MapType($card, $setID)
  {
    $hasAction = false; $hasAttack = false; $hasInstant = false; $hasToken = false;
    $hasEvent = false; $hasEquipment = false; $hasWeapon = false;
    $cardNumber = substr($setID, 3, 3);
    for($i=0; $i<count($card->types); ++$i)
    {
      if($card->types[$i] == "Action") $hasAction = true;
      else if($card->types[$i] == "Attack") $hasAttack = true;
      else if($card->types[$i] == "Defense Reaction") return "DR";
      else if($card->types[$i] == "Attack Reaction") return "AR";
      else if($card->types[$i] == "Instant") $hasInstant = true;
      else if($card->types[$i] == "Weapon") $hasWeapon = true;
      else if($card->types[$i] == "Hero") return "C";
      else if($card->types[$i] == "Equipment") $hasEquipment = true;
      else if($card->types[$i] == "Token") $hasToken = true;
      else if($card->types[$i] == "Resource") return "R";
      else if($card->types[$i] == "Mentor") return "M";
      else if($card->types[$i] == "Demi-Hero") return "D";
      else if($card->types[$i] == "Block") return "B";
      else if($card->types[$i] == "Event") $hasEvent = true;
      else if($card->types[$i] == "Macro") return "Macro";
      else if($card->types[$i] == "Companion") return "Companion";
    }
    
    // Check for Equipment conditions after collecting all type flags
    if($hasEquipment && ($cardNumber >= 400 || !$hasAction && !$hasInstant)) {
      if($hasEvent) return "Event,E";
      if($hasWeapon) return "W,E";
      return "E";
    }
    if($hasAction && $hasAttack) return "AA";
    else if($hasWeapon && $hasToken) return "W,T";
    else if($hasWeapon) return "W";
    else if($hasAction) return "A";
    else if($hasInstant) return "I";
    else if($hasToken) return "T";
    else if($hasEvent) return "Event";
    return "-";
  }

  function IsCardType($term)
  {
    switch($term)
    {
      case "Action": case "Attack": case "Defense Reaction": case "Attack Reaction":
      case "Instant": case "Weapon": case "Hero": case "Equipment": case "Token":
      case "Resource": case "Mentor": case "Companion": case "Macro": case "Event":
         return true;
      default: return false;
    }
  }

  function IsClass($term)
  {
    switch($term)
    {
      case "Generic": case "Warrior": case "Ninja": case "Brute": case "Guardian":
      case "Wizard": case "Mechanologist": case "Ranger": case "Runeblade":
      case "Illusionist": case "Assassin": case "Necromancer": case "Pirate": 
      case "Shapeshifter": case "Merchant": case "Arbiter": case "Bard": case "Thief":
        return true;
      default: return false;
    }
  }

  function IsTalent($term)
  {
    switch($term)
    {
      case "Elemental": case "Light": case "Shadow": case "Draconic": return true;
      case "Ice": case "Lightning": case "Earth": case "Mystic": return true;
      case "Revered": case "Reviled": return true;
      case "Chaos": case "Royal": return true;
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

  function IsHero($term)
  {
    switch($term) {
      case "Arakni":
      case "Puffin":
      case "Scurv":
        return true;
      default:
        return false;
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
    return $hasAction && $hasEquipment || $hasInstant && $hasEquipment;
  }

  function PerchDuplicate($cardID)
  {
    return match($cardID) {
      "polly_cranka" =>  true,
      "sticky_fingers" => true,
      default => false
    };
  }

  function ReverseID($cardID)
  {
    switch ($cardID) {
      case "harmonized_kodachi":
        return "CRU049";
      case "mandible_claw":
        return "CRU005";
      case "zephyr_needle":
        return "CRU052";
      case "cintari_saber":
        return "CRU080";
      case "quicksilver_dagger":
        return "DYN070";
      case "spider's_bite":
        return "DYN116";
      case "nerve_scalpel":
        return "OUT006";
      case "orbitoclast":
        return "OUT008";
      case "scale_peeler":
        return "OUT010";
      case "kunai_of_retribution":
        return "GEM003";
      case "obsidian_fire_vein":
        return "GEM005";
      case "mark_of_the_huntsman":
        return "GEM007";
      case "hunters_klaive":
        return "TAL000"; //manually added the art
      default:
        return "";
    }
  }