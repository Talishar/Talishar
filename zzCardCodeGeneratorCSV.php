<?php
if (PHP_SAPI !== "cli") { http_response_code(404); exit; }

  // Parallel version of zzCardCodeGenerator.php that sources card data from the public card data CSV
  // instead of the-fab-cube JSON, for when the CSV is more up to date. The CSV has one row per printing
  // (in every language), so the rows are folded back into one object per card in the same shape as
  // the-fab-cube's card.json. Run it after zzCardCodeGenerator.php: it only adds cards that don't have a
  // GeneratedCardName yet, and writes them into the generated files alongside everything already there.
  // Everything from $manualPrintings down to ReverseID is copied from zzCardCodeGenerator.php, apart from the steps
  // marked "CSV only", so keep the two in sync when changing the generation logic.

  include __DIR__ . '/zzImageConverter.php';
  include __DIR__ . '/Libraries/Trie.php';

  $originalSets = ["WTR", "ARC", "CRU", "MON", "ELE", "EVR", "UPR", "DYN", "OUT", "DVR", "RVD", "DTD", "TCC", "EVO", "HVY",
                   "MST", "AKO", "ASB", "AAZ", "ROS", "TER", "AUR", "AIO", "AJV", "HNT", "ARK", "AST", "AMX", "LGS", "HER",
                   "FAB", "JDG", "SEA", "AGB", "MPG", "ASR", "APR", "AVS", "BDD", "SMP", "SUP", "APS", "ARR", "AAC", "AHA", 
                   "PEN", "OMN", "AZS", "MPW", "AOL", "DDD", "IAR", "AMA", "SAT", "SBW", "MPA", "AMO", "TNP", "SPW"];

  $csvUrl = "https://d1pn9y8e99aays.cloudfront.net/public_card_data.csv";

  // Fixes for mistakes in the CSV, using the-fab-cube's values. Keyed by the CSV's name for the card, or by
  // "name|pitch" for a single color. Fields are the CSV's true_ columns: name, pitch, cost, power, defense,
  // life, intellect, typebox, textbox.
  $cardCorrections = [
    // Names
    "Assassin Weapon - Dagger (1H)" => ["name" => "Humour Plunge"],
    "Ezy Sqeez Bookie Syndicate" => ["name" => "Ez Sqeez Bookie Syndicate"],
    "Knife through Butter" => ["name" => "Knife Through Butter"],
    "Tremor of íArathael" => ["name" => "Tremor of i'Arathael"],
    // Types
    "Aetherize" => ["typebox" => "Wizard Instant"],
    "Arknight Ascendancy" => ["typebox" => "Runeblade Action - Attack"],
    "Bloodrot Pox" => ["typebox" => "Generic Token - Disease Aura"],
    "Crumble to Eternity" => ["typebox" => "Ice Earth Action - Aura"],
    "Dragons of Legend" => ["typebox" => "Invocation Placeholder Card"],
    "Dread Triptych" => ["typebox" => "Runeblade Action - Attack"],
    "Frailty" => ["typebox" => "Generic Token - Disease Aura"],
    "Graphene Chelicera" => ["typebox" => "Assassin Token Weapon - Dagger (1H)"],
    "Inertia" => ["typebox" => "Generic Token - Disease Aura"],
    "Pulse of Isenloft" => ["typebox" => "Ice Earth Defense Reaction"],
    "Pulse of Volthaven" => ["typebox" => "Lightning Ice Instant"],
    // Stats
    "Anka, Drag Under" => ["cost" => "2"],
    "Argh... Smash!" => ["defense" => "3"],
    "Blasmophet, the Soul Harvester" => ["power" => "6"],
    "Blinding Beam" => ["defense" => ""],
    "Boneyard Marauder|3" => ["power" => "4"],
    "Break Tide" => ["cost" => "0"],
    "Burn Away" => ["cost" => "0"],
    "Carrion Husk" => ["defense" => "6"],
    "Cash In" => ["defense" => "2"],
    "Cloud Cover" => ["cost" => "0"],
    "Constella Waves" => ["defense" => "0"],
    "Drag Down" => ["cost" => "0"],
    "Embolden" => ["defense" => "3"],
    "Entangle" => ["cost" => "3"],
    "Entwine Earth|1" => ["power" => "6"],
    "Explosive Growth|1" => ["cost" => "1"],
    "Flashfreeze" => ["defense" => "2"],
    "Galaxxi Black" => ["power" => "1"],
    "Gallow, End of the Line" => ["defense" => "", "life" => "3"],
    "Haboob" => ["cost" => "0"],
    "Hatchet of Body" => ["power" => "2"],
    "Hatchet of Mind" => ["power" => "2"],
    "Honing Hood" => ["cost" => "0"],
    "Hyper Driver" => ["defense" => ""],
    "Inner Chi" => ["pitch" => "3"],
    "Mental Block" => ["cost" => ""],
    "Night's Embrace" => ["cost" => ""],
    "Oysten, Heart of Gold" => ["cost" => "0"],
    "Phantasmal Footsteps" => ["defense" => "0"],
    "Pick a Card, Any Card" => ["cost" => "0"],
    "Plasma Barrel Shot" => ["power" => "X"],
    "Pound for Pound" => ["cost" => "3"],
    "Pyroglyphic Protection" => ["defense" => "2"],
    "Ravenous Meataxe" => ["power" => "3"],
    "Rites of Replenishment|3" => ["power" => "4"],
    "Sawbones, Dock Hand" => ["cost" => "2"],
    "Searing Touch|1" => ["cost" => "1"],
    "Shitty Xmas Present" => ["defense" => "2"],
    "Skybound Shot" => ["cost" => "1"],
    "Snap Fingers" => ["defense" => "0"],
    "Stalker's Steps" => ["defense" => "0"],
    "Talisman of Dousing" => ["cost" => "0"],
    "The Librarian" => ["defense" => "3"],
    "Ursur, the Soul Reaper" => ["power" => "6"],
    "Valiant Dynamo" => ["defense" => "1"],
    "Wrecking Ball" => ["defense" => ""],
    // Text
    "Bad Beats" => ["textbox" => "Roll a 6 sided die. If the number rolled is a 4, 5, or 6, the next Brute attack action card you play this turn gets +5{p}.{br}**Go again**"],
  ];

  // Double-faced cards the CSV lists back side first, keyed by the CSV's front face name
  $swappedFaces = ["Levia, Redeemed"];

  // Fixes for printings the CSV gives the wrong rarity, keyed by print ID. Deck reprints are often listed as
  // common, and gold printings don't say which rarity they're a gold version of.
  $printingRarityCorrections = [
    "ANQ023" => "M", "ANQ026" => "M", "ANQ031" => "R", "ARK025" => "R", "BOL006" => "R", "CHN006" => "R",
    "FAB330" => "P", "HNT054" => "M", "HNT104" => "R", "IAR120" => "S", "KYO009" => "R", "LEV005" => "R",
    "PSM007" => "R", "SBA022" => "R", "SFA025" => "R", "SFA027" => "R", "SFA033" => "R", "SFA035" => "R",
    "SGB032" => "R",
  ];

  $cardArray = LoadCardArrayFromCSV($csvUrl);

  $manualPrintings = [
    "rise_to_the_challenge_red" => ["id" => "IAR050", "rarity" => "C"],
    "rise_to_the_challenge_yellow" => ["id" => "IAR051", "rarity" => "C"],
    "rise_to_the_challenge_blue" => ["id" => "IAR052", "rarity" => "C"]
  ];
  PatchMissingPrintings($cardArray, $manualPrintings);

  if(!is_dir(__DIR__ . "/GeneratedCode")) mkdir(__DIR__ . "/GeneratedCode", 777, true);

  $filename = __DIR__ . "/GeneratedCode/GeneratedCardDictionaries.php";

  // CSV only: fill in cards zzCardCodeGenerator.php missed. Any card that already has a GeneratedCardName keeps
  // the stats it has, and everything already generated is carried over into the new files.
  if(is_file($filename)) include_once $filename;
  $existingFunctions = ReadGeneratedDictionaries(dirname($filename));
  $allCards = $cardArray; // Tokens are still matched against every card
  $cardArray = array_values(array_filter($cardArray, fn($card) => !AlreadyGenerated(GetCardIdentifier($card->name, $card->pitch))));
  echo "Adding " . count($cardArray) . " cards missing from the generated dictionaries<BR>";

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

  // CSV only: tokens are matched against every card, but only new cards get entries, and everything is merged into
  // what's already generated
  $tokensFunction = GenerateCardTokensFunction($allCards);
  $tokensFunction["entries"] = array_filter($tokensFunction["entries"], fn($cardID) => !AlreadyGenerated($cardID), ARRAY_FILTER_USE_KEY);
  $generatedFunctions[] = $tokensFunction;

  WriteGeneratedDictionaries($filename, array_values(MergeGeneratedFunctions($existingFunctions, $generatedFunctions)));

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

  // Whether zzCardCodeGenerator.php (or an earlier run of this) already generated the card
  function AlreadyGenerated($cardID)
  {
    return function_exists("GeneratedCardName") && GeneratedCardName($cardID) != "";
  }

  // Reads every function in the existing generated files back into GeneratedFunction()s, keyed by name. Handles both
  // one file per function in GeneratedFunctions and a single file with every function in it.
  function ReadGeneratedDictionaries($directory)
  {
    $mainFile = $directory . "/GeneratedCardDictionaries.php";
    $files = array_merge(is_file($mainFile) ? [$mainFile] : [], glob($directory . "/GeneratedFunctions/*.php"));
    $functions = [];
    foreach ($files as $file) {
      $name = null;
      foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
        $line = rtrim($line, "\r");
        if (preg_match('/^function Generated(\w+)\(/', $line, $matches)) {
          $name = $matches[1];
          if (!isset($functions[$name])) $functions[$name] = GeneratedFunction($name, [], null, null);
        }
        else if ($name === null) continue;
        else if (preg_match('/^if\(is_int\(\$cardID\)\) return (.*);$/', $line, $matches)) $functions[$name]["intReturn"] = $matches[1];
        else if (preg_match('/^default => (.*)$/', $line, $matches)) $functions[$name]["default"] = $matches[1];
        else if (preg_match('/^"([^"]*)" => (.*),$/', $line, $matches)) $functions[$name]["entries"][$matches[1]] = $matches[2];
      }
    }
    return $functions;
  }

  // Adds each new function's entries to the existing ones, keeping the existing entry when both have one
  function MergeGeneratedFunctions($existingFunctions, $newFunctions)
  {
    foreach ($newFunctions as $function) {
      if (!isset($existingFunctions[$function["name"]])) $existingFunctions[$function["name"]] = $function;
      else $existingFunctions[$function["name"]]["entries"] += $function["entries"];
    }
    return $existingFunctions;
  }

  function LoadCardArrayFromCSV($csvUrl)
  {
    $csvFile = tempnam(sys_get_temp_dir(), "cards");
    $fileHandler = fopen($csvFile, "w");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $csvUrl);
    curl_setopt($curl, CURLOPT_FILE, $fileHandler);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    if(defined('CURLSSLOPT_NATIVE_CA')) curl_setopt($curl, CURLOPT_SSL_OPTIONS, CURLSSLOPT_NATIVE_CA);
    $succeeded = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    fclose($fileHandler);
    if(!$succeeded) {
      unlink($csvFile);
      exit("Unable to download card data from " . htmlspecialchars($csvUrl) . ". " . htmlspecialchars($error));
    }
    $cardArray = ParseCardCSV($csvFile);
    unlink($csvFile);
    return $cardArray;
  }

  function ParseCardCSV($csvFile)
  {
    global $printingRarityCorrections, $swappedFaces;
    $rarityMap = [
      "token" => "B", "basic" => "B", "common" => "C", "rare" => "R", "majestic" => "M", "legendary" => "L",
      "fabled" => "F", "marvel" => "V", "promo" => "P", "super-rare" => "S",
      "promo-marvel" => "P", "gold" => "P", "gold-marvel" => "V"
    ];
    $cards = [];
    $handler = fopen($csvFile, "r");
    $header = fgetcsv($handler);
    while(($row = fgetcsv($handler)) !== false)
    {
      $row = array_combine($header, $row);
      if($row["print_language"] != "en") continue;
      if(in_array($row["face_1_true_name"], $swappedFaces)) {
        foreach($header as $column) {
          if(!str_starts_with($column, "face_1_")) continue;
          $backColumn = "face_2_" . substr($column, 7);
          [$row[$column], $row[$backColumn]] = [$row[$backColumn], $row[$column]];
        }
      }
      $printID = NormalizePrintID($row["print_id"]);
      $rarity = $printingRarityCorrections[$printID] ?? $rarityMap[$row["rarity"]] ?? "-";
      $frontName = $row["face_1_true_name"];
      foreach(["face_1_", "face_2_"] as $face)
      {
        $name = $row[$face . "true_name"];
        if($name == "") continue;
        // A second face with its own name is the back of a double-faced card
        $isBack = $face == "face_2_" && $name != $frontName;
        $card = FaceToCard($row, $face);
        $key = $card->name . "|" . $card->pitch;
        if(!isset($cards[$key])) {
          $cards[$key] = $card;
          $cards[$key]->frontPrintings = 0;
        }
        if(!$isBack) ++$cards[$key]->frontPrintings;
        $cards[$key]->printings[] = (object)[
          "id" => $printID,
          "set_id" => substr($printID, 0, 3),
          "rarity" => $rarity,
        ];
      }
    }
    fclose($handler);

    $cardArray = [];
    foreach($cards as $card)
    {
      usort($card->printings, fn($a, $b) => strcmp($a->id, $b->id));
      // Cards that are only ever printed on the back of another card are flagged the same way
      // the-fab-cube flags them, on their first printing
      $card->printings[0]->double_sided_card_info = [(object)["is_front" => $card->frontPrintings > 0]];
      unset($card->frontPrintings);
      $cardArray[] = $card;
    }
    usort($cardArray, fn($a, $b) => strcmp($a->name, $b->name) ?: strcmp($a->pitch, $b->pitch));
    return $cardArray;
  }

  function NormalizePrintID($printID)
  {
    // Strip language (e.g. MI_), edition (e.g. U-, R-) and finish/variant (e.g. -RF, -MV, -A) markers
    if(preg_match('/^(?:[A-Z]{2}_)?(?:[A-Z]-)?([A-Z0-9]{6})/', $printID, $matches)) return $matches[1];
    return $printID;
  }

  function FaceToCard($row, $face)
  {
    global $cardCorrections;
    $name = $row[$face . "true_name"];
    $corrections = ($cardCorrections[$name] ?? []) + ($cardCorrections[$name . "|" . $row[$face . "true_pitch"]] ?? []);
    foreach($corrections as $field => $value) $row[$face . "true_" . $field] = $value;
    $text = str_replace(["\r\n", "\n"], "{br}", $row[$face . "true_textbox"]);
    $functionalText = str_replace("{br}", "\n\n", $text);
    $functionalTextPlain = str_replace(["**", "{br}"], ["", "\n"], $text);
    return (object)[
      "name" => str_replace("||", " // ", $row[$face . "true_name"]),
      "pitch" => $row[$face . "true_pitch"],
      "cost" => $row[$face . "true_cost"],
      "power" => $row[$face . "true_power"],
      "defense" => $row[$face . "true_defense"],
      "health" => $row[$face . "true_life"],
      "intelligence" => $row[$face . "true_intellect"],
      "types" => ParseTypes($row, $face),
      "card_keywords" => ParseKeywords($text),
      "functional_text" => $functionalText,
      "functional_text_plain" => $functionalTextPlain,
      "printings" => [],
    ];
  }

  function ParseTypes($row, $face)
  {
    // Read the terms off the typebox (e.g. "Light Warrior Weapon - Sword (2H)") like the-fab-cube does, since
    // the CSV's talents/classes/types/subtypes columns are sometimes empty or missing terms like "High Seas"
    $multiWordTerms = ["Defense Reaction", "Attack Reaction", "Omens of the Third Age", "High Seas", "Placeholder Card"];
    // Hybrid classes ("Guardian / Warrior") and split cards ("Action||Earth Instant") list every term
    $typebox = str_replace([" - ", " / ", "||"], " ", $row[$face . "true_typebox"]);
    foreach($multiWordTerms as $term) $typebox = str_replace($term, str_replace(" ", "_", $term), $typebox);
    $types = [];
    foreach(explode(" ", $typebox) as $type)
    {
      if($type == "") continue;
      $types[] = str_replace("_", " ", trim($type, "()")); // (1H) -> 1H
    }
    return array_values(array_unique($types));
  }

  function ParseKeywords($text)
  {
    // The CSV has no keyword list, but every keyword is bolded in the text. Only the card's own keywords
    // count, not ones it only references or hands to something else, e.g. "Your next attack gets **dominate**",
    // "cards with **combo**", "Destroy this: **Opt 1**" or "When you **boost**".
    $keywords = [];
    $text = str_replace(["“", "”"], '"', $text);
    preg_match_all('/\*\*(.+?)\*\*/', $text, $matches, PREG_OFFSET_CAPTURE);
    foreach($matches[1] as [$keyword, $offset])
    {
      $paragraph = substr($text, 0, $offset - 2);
      $paragraph = substr($paragraph, strrpos("{br}" . $paragraph, "{br}"));
      if(substr_count($paragraph, '"') % 2 == 1) {
        // Inside a quoted ability, which only counts when the card gives it to itself
        $beforeQuote = substr($paragraph, 0, strrpos($paragraph, '"'));
        if(!preg_match('/\b(this|it) (gets?|has|gains?)\b/i', LastSentence($beforeQuote))) continue;
      }
      else {
        if(str_contains($paragraph, " -- ")) continue; // The effect of an activated ability
        if(preg_match('/\b(with|you|by|a|an|the|gain)\s*$/', $paragraph)) continue;
        $sentence = LastSentence($paragraph);
        if(preg_match_all('/\b(gets?|has|gains?|loses)\b/', $sentence, $verbs, PREG_OFFSET_CAPTURE)) {
          $verbOffset = end($verbs[0])[1];
          if(!preg_match('/\b(this|it) $/i', substr($sentence, 0, $verbOffset))) continue;
        }
      }
      // A card's own combo always starts an ability ("**Combo** - ..."); a lowercase "**combo**" only refers to it,
      // e.g. "If it has **combo**, you may play it this turn"
      if(strcasecmp(trim($keyword), "combo") == 0 && trim($keyword) != "Combo") continue;
      // Granted keywords are often lowercase (e.g. "this gets **dominate**")
      $keyword =str_replace([" Of ", " And ", " And/or "], [" of ", " and ", " and/or "], ucwords(trim($keyword)));
      // A card only has ward with an amount (e.g. "**Ward 3**"); a bare "**ward**" refers to other cards' ward
      if($keyword == "Ward") continue;
      if(preg_match('/^Legendary (.+)$/', $keyword, $parts)) array_push($keywords, "Legendary", $parts[1]);
      else if(preg_match('/^Legend of the (.+)$/i', $keyword, $parts)) array_push($keywords, "Legendary", $parts[1]);
      else {
        $keywords[] = $keyword;
        if(str_ends_with($keyword, "s")) $keywords[] = substr($keyword, 0, -1); // e.g. "**wagers**"
      }
    }
    return $keywords;
  }

  function LastSentence($text)
  {
    $sentences = preg_split('/[.;] /', $text);
    return end($sentences);
  }
