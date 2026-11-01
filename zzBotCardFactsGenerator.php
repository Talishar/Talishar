<?php
if (PHP_SAPI !== "cli") { http_response_code(404); exit; }

$jsonPath = $argv[1] ?? "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/outbreak/json/english/card.json";
$cardArray = json_decode(file_get_contents($jsonPath));
if (!is_array($cardArray)) {
  fwrite(STDERR, "Could not read card data from $jsonPath\n");
  exit(1);
}

const BOT_DRAW_VALUE = 3.25;
const BOT_COUNT_WORD = "(a|an|one|two|three|four|\\d+)";

function BotFactsCardIdentifier($name, $pitch)
{
  if ($name == "Goldfin Harpoon") return "goldfin_harpoon_yellow";
  $cardID = strtolower($name);
  $cardID = str_replace("//", "_", $cardID);
  $cardID = str_replace(["ā", "ä", "ö", "ü", "ß", "ṣ", "α"], ["a", "a", "o", "u", "s", "s", "a"], $cardID);
  $cardID = iconv('UTF-8', 'US-ASCII//TRANSLIT', $cardID);
  $cardID = str_replace([" ", "-"], "_", $cardID);
  $cardID = preg_replace("/[^a-z0-9 _]/", "", $cardID);
  $cardID = preg_replace("/__/", "_", $cardID);
  $suffix = match(strval($pitch)) {
    "1" => "_red",
    "2" => "_yellow",
    "3" => "_blue",
    "4" => "_purple",
    default => "",
  };
  return $cardID . $suffix;
}

function BotFactsCount($word)
{
  return match ($word) {
    null, "", "a", "an", "one" => 1,
    "two" => 2,
    "three" => 3,
    "four" => 4,
    default => is_numeric($word) ? intval($word) : 1,
  };
}

function BotFactsClassifyOnHit($text)
{
  $count = BOT_COUNT_WORD;
  $parts = ["s" => 0.0, "d" => 0, "h" => 0, "hv" => 0.0, "a" => 0.0, "e" => 0, "dl" => 0.0];
  $matched = false;

  if (preg_match("/look at (?:their|the defending hero's|target hero's) hand|reveals? their hand|choose a card (?:from|in) their hand/", $text)) {
    $parts["h"] = 1;
    $parts["hv"] = BOT_DRAW_VALUE + 1.5;
    if (preg_match("/banish up to (\\w+) cards with the same name/", $text, $copies)) $parts["s"] += max(0, BotFactsCount($copies[1]) - 1);
    $matched = true;
  } else if (preg_match("/discards? (?:their hand|all but 1 card from their hand)/", $text)) {
    $parts["h"] = 3;
    $parts["hv"] = BOT_DRAW_VALUE;
    $matched = true;
  } else if (preg_match("/discards? $count random cards?|$count random cards? from their hand/", $text, $random)) {
    $parts["h"] = BotFactsCount(($random[1] ?? "") !== "" ? $random[1] : ($random[2] ?? ""));
    $parts["hv"] = BOT_DRAW_VALUE;
    $matched = true;
  } else if (preg_match("/(?:discards?|banish(?:es)?(?: face-down)?|puts?) $count cards?(?: from their hand)?|put a card from their hand on top/", $text, $forced)
    && preg_match("/discard|from their hand/", $text) && !preg_match("/from your hand/", $text)) {
    $parts["h"] = BotFactsCount($forced[1] ?? "");
    $parts["hv"] = BOT_DRAW_VALUE * 0.75;
    $matched = true;
  }

  if (preg_match("/(?:destroy|banish)[^.]{0,60}\\barsenal\\b|put[^.]{0,60}\\btheir arsenal\\b/", $text)) {
    $conditional = preg_match("/turn a card in their arsenal face[- ]up|defense reaction in their arsenal|attack action card in their arsenal with/", $text);
    $parts["a"] = $conditional ? 0.5 : 1.0;
    $matched = true;
  }

  if (preg_match("/(?:^|[^y] )draws? $count cards?/", $text, $draw)) {
    $parts["d"] += BotFactsCount($draw[1]);
    $matched = true;
  } else if (preg_match("/search your deck[^.]*(?:into|to) your hand/", $text)) {
    $parts["s"] += 3;
    $matched = true;
  } else if (preg_match("/create $count crouching tigers?/", $text, $tigers)) {
    $parts["s"] += BotFactsCount($tigers[1]) * 2;
    $matched = true;
  }

  if (preg_match("/they lose the game/", $text)) {
    $parts["s"] += 40;
    $matched = true;
  } else if (preg_match("/(?:deal|deals|loses?)\\D{0,24}(\\d+)\\s*(?:arcane\\s+)?(?:damage|life|\\{h\\})/", $text, $damage) && !preg_match("/\\byou lose\\b/", $text)) {
    $parts["s"] += intval($damage[1]);
    if (preg_match("/(?:end phase|beginning of|next turn)/", $text)) $parts["dl"] += intval($damage[1]);
    $matched = true;
  }

  $debuffs = ["bloodrot pox" => 2, "inertia" => 2, "frailty" => 1.5, "frostbite" => 1];
  if (preg_match("/create (?:a |an |\\d+ )?(bloodrot pox|inertia|frailty|frostbite)/", $text, $debuff)) {
    $parts["s"] += $debuffs[$debuff[1]];
    if ($debuff[1] == "bloodrot pox") $parts["dl"] += 2;
    $matched = true;
  } else if (preg_match("/create $count (?:runechant|embodiment of (?:earth|lightning))/", $text, $runechants)) {
    $parts["s"] += BotFactsCount($runechants[1]);
    $matched = true;
  } else if (preg_match("/creates? (?:a |an |\\d+ |two )?(?:gold|agility|might|vigor|copper|silver|quicken)/", $text)) {
    $parts["s"] += 2;
    $matched = true;
  } else if (preg_match("/create[^.]*\\btokens?\\b/", $text)) {
    $parts["s"] += 1;
    $matched = true;
  }

  if (preg_match("/equipment (?:they|the defending hero) controls?|defending equipment/", $text)) {
    $parts["s"] += preg_match("/destroy[^.]{0,30}equipment/", $text) ? 3 : 2;
    $parts["e"] = 1;
    $matched = true;
  }

  $permanent = "(?:aura|item|ally|weapon|permanent)";
  if (preg_match("/destroy all [^.]*{$permanent}s? they control/", $text)) {
    $parts["s"] += 4;
    $matched = true;
  } else if (preg_match("/(?:destroy|banish|steal|gain control of|return)[^.]{0,40}{$permanent}s?(?: tokens?)?[^.]{0,20}(?:they|the defending hero) (?:control|own)/", $text)) {
    $parts["s"] += preg_match("/token/", $text) ? 2 : 3;
    $matched = true;
  }

  if (preg_match("/their first attack during their next turn has -(\\d+)\\{p\\}/", $text, $weakened)) {
    $parts["s"] += intval($weakened[1]) * 0.6;
    $matched = true;
  } else if (preg_match("/(?:they|that hero|cards they own|attacks they control) (?:can't|lose all|lose and can't)|costs? an additional \\{r\\}|is halved/", $text)) {
    $parts["s"] += 2;
    $matched = true;
  } else if (preg_match("/-\\d+\\{d\\}(?: while defending| until end of turn)|\\{t\\} (?:them|their hero)/", $text)) {
    $parts["s"] += 1;
    $matched = true;
  }

  if (preg_match("/gain (?:life )?(\\d+)\\{h\\}/", $text, $life)) {
    $parts["s"] += intval($life[1]);
    $matched = true;
  }

  if (preg_match("/put it into its owner's hand/", $text)) {
    $parts["s"] += 2;
    $matched = true;
  } else if (preg_match("/into your (?:hero's )?soul|\\bopt \\d|look at the top card of your deck|sharpen|counter on (?:it|this|an item you control|a cog)|the crowd cheers you|equip a /", $text)) {
    $parts["s"] += 1;
    $matched = true;
  }

  if (preg_match("/(?:banish|destroy) the top (?:$count )?cards? of (?:their|the defending hero's) deck/", $text, $milled)) {
    $parts["s"] += BotFactsCount($milled[1] ?? "") * 0.5 + (preg_match("/you may play it/", $text) ? 2 : 0);
    $matched = true;
  }

  $tempo = preg_match("/go again|action points?|gain (?:\\w+ )?(?:\\{r\\}|resources?)|attack an additional time/", $text)
    && !preg_match("/can't (?:get|gain) go again/", $text);
  if ($tempo) {
    $parts["s"] += 1;
    $matched = true;
  }
  if (preg_match("/next [^.]*attack[^.]*\\+(\\d+)\\{p\\}/", $text, $pump)) {
    $parts["s"] += intval($pump[1]) * 0.6;
    $matched = true;
  }
  if (preg_match("/\\bmark (?:them|the defending hero)\\b/", $text)) {
    $parts["s"] += 0.5;
    $matched = true;
  }

  if (preg_match("/destroy this (?:zombie|ally)\\b|hits?, (?:destroy|banish) (?:this|it)\\W*$|the dagger has hit\\. destroy the dagger/", $text)) {
    $parts["s"] -= 1;
    $matched = true;
  } else if (preg_match("/put (?:this|it) on the bottom of its owner's deck\\W*$|the crowd boos you/", $text)) {
    $matched = true;
  }

  if (!$matched) $parts["s"] = 2;
  return array_filter($parts, fn($value) => $value != 0);
}

function BotFactsOnHitClauses($text)
{
  $clauses = [];
  foreach (preg_split("/\\n+/", $text) as $line) {
    if (!preg_match("/\\bhits?\\b/", $line)) continue;
    if (preg_match_all("/(?:when|whenever|if) (?:this|it|[a-z' ]{0,40}) hits?(?: a hero)?(?: [^,]{0,40})?, ([^\"]*)/", $line, $matches)) {
      foreach ($matches[1] as $clause) $clauses[] = trim($clause);
    }
  }
  return $clauses;
}

function BotFactsMergeOnHit($parts)
{
  $merged = [];
  foreach ($parts as $part) {
    foreach ($part as $key => $value) {
      if ($key == "hv") $merged[$key] = max($merged[$key] ?? 0, $value);
      else if ($key == "a" || $key == "e") $merged[$key] = max($merged[$key] ?? 0, $value);
      else $merged[$key] = ($merged[$key] ?? 0) + $value;
    }
  }
  return $merged;
}

function BotFactsPumpTarget($clause)
{
  if (preg_match("/attack action card with cost 0\b/", $clause)) return "c0";
  if (preg_match("/attack action card with cost 1 or less/", $clause)) return "c1";
  if (preg_match("/attack action card with cost 2 or more/", $clause)) return "c2";
  if (preg_match("/((?:[a-z]+(?:,| or| and) )*[a-z]+) weapon attack/", $clause, $weapon)) {
    preg_match_all("/\\b(dagger|sword|club|hammer|axe|scythe|bow|pistol|staff|claw|orb|polearm|gun)\\b/", $weapon[1], $subtypes);
    return count($subtypes[1]) > 0 ? "w:" . implode(",", $subtypes[1]) : "w";
  }
  if (preg_match("/attack action card/", $clause)) return "aa";
  return "any";
}

function BotFactsPump($text, $isAttack)
{
  if ($isAttack) return [];
  $best = [];
  foreach (preg_split("/\\n+|(?<=\\.) /", $text) as $clause) {
    if (str_contains($clause, "\"")) $clause = preg_replace("/\"[^\"]*\"?/", "", $clause);
    if (!preg_match("/\\+(\\d+)\\{p\\}/", $clause, $amount)) continue;
    if (preg_match("/counter|while|for each|hero|ally|equipment/", $clause) && !preg_match("/attack/", $clause)) continue;
    if (!preg_match("/attack/", $clause)) continue;
    $next = preg_match("/next (?:[a-z ]*)attack/", $clause) ? 1 : 0;
    $count = $next ? max(1, preg_match_all("/next attack[^.]*\\+(\\d+)\\{p\\}/", $text)) : 1;
    $value = intval($amount[1]) * $count;
    if (($best["p"] ?? 0) > $value) continue;
    $target = BotFactsPumpTarget($clause);
    if (($best["p"] ?? 0) == $value) {
      if (!in_array($target, explode("|", $best["pt"]))) $best["pt"] .= "|" . $target;
    } else {
      $best = ["p" => $value, "pt" => $target];
    }
    if ($next) $best["pn"] = 1;
    if (preg_match("/gets? go again/", $clause)) $best["pga"] = 1;
  }
  return $best;
}

function BotFactsHandPrevention($text, $isInstant)
{
  if (preg_match("/instant - [^:]*\\b(?:discard|banish) this(?: from your hand)?:\\s*prevent the next (\\d+) damage/i", $text, $mode)) return intval($mode[1]);
  if ($isInstant && preg_match("/\\bprevent (?:the next )?(\\d+)(?: of that)? damage/i", $text, $amount)) return intval($amount[1]);
  return 0;
}

function BotFactsAbilities($text, $name)
{
  $abilities = [];
  $escaped = preg_quote(strtolower($name), "/");
  $headers = "/(?:\\b(once|twice|thrice) per turn\\s+)?\\b(instant|action|attack reaction|defense reaction)\\s+-\\s+/i";
  if (!preg_match_all($headers, $text, $found, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) return [];
  foreach ($found as $index => $header) {
    $timing = strtolower($header[2][0]);
    if ($timing != "instant" && $timing != "attack reaction") continue;
    $start = $header[0][1] + strlen($header[0][0]);
    $end = $found[$index + 1][0][1] ?? strlen($text);
    $body = strtolower(substr($text, $start, $end - $start));
    $colon = strpos($body, ":");
    if ($colon === false) continue;
    $cost = trim(substr($body, 0, $colon));
    $effect = trim(substr($body, $colon + 1));
    if (preg_match("/in your graveyard/", $effect)) continue;

    $resources = 0;
    $self = 0;
    $limit = match (strtolower($header[1][0] ?? "")) { "once" => 1, "twice" => 2, "thrice" => 3, default => 6 };
    $unknownCost = false;
    foreach (preg_split("/,\\s*/", $cost) as $part) {
      if ($part === "0" || $part === "") continue;
      if (preg_match("/^(?:\\{r\\})+$/", $part)) $resources += substr_count($part, "{r}");
      else if ($part == "{t}") $self = 1;
      else if (preg_match("/^destroy (?:this|$escaped)$/", $part)) { $self = 1; $limit = 1; }
      else { $unknownCost = true; break; }
    }
    if ($unknownCost) continue;

    if (preg_match("/^(?:this|this card|$escaped) (?:gets|gains) \\+(\\d+)\\{p\\}/", $effect, $match)) {
      $abilities[] = [intval($match[1]), 0, $resources, $limit, $self, "self"];
    } else if (preg_match("/^target (?:[a-z]+ )?attack( action card)?(?: with [^:.]*?)? (?:gets|gains) \\+(\\d+)\\{p\\}/", $effect, $match)) {
      $abilities[] = [intval($match[2]), 0, $resources, $limit, $self, $match[1] ? "aa" : "any"];
    } else if (preg_match("/^attack action cards you control (?:get|gain) \\+(\\d+)\\{p\\}/", $effect, $match)) {
      $abilities[] = [intval($match[1]), 0, $resources, $limit, $self, "aa"];
    } else if (preg_match("/^target ([a-z]+) you control( that isn['’]t on the active chain link)? deals (\\d+) damage to target hero/", $effect, $match)) {
      $abilities[] = [intval($match[3]), 1, $resources, $limit, $self, "sub:" . $match[1] . ($match[2] ? ":off" : "")];
    }
  }
  return $abilities;
}

$facts = [];
foreach ($cardArray as $card) {
  $text = $card->functional_text_plain ?? "";
  if ($text === "") continue;
  $cardID = BotFactsCardIdentifier($card->name, $card->pitch);
  $lower = strtolower($text);
  $types = array_map("strtolower", $card->types ?? []);
  $isAttack = in_array("attack", $types) && (in_array("action", $types) || in_array("weapon", $types));
  $isWeapon = in_array("weapon", $types);
  $isInstant = in_array("instant", $types);
  $entry = [];

  if ($isAttack || $isWeapon) {
    $clauses = BotFactsOnHitClauses($lower);
    if (count($clauses) > 0) $entry["oh"] = BotFactsMergeOnHit(array_map("BotFactsClassifyOnHit", $clauses));
  } else if (preg_match_all("/\"([^\"]*\\bhits?\\b[^\"]*)\"/", $lower, $granted)) {
    $clauses = BotFactsOnHitClauses(implode("\n", $granted[1]));
    if (count($clauses) > 0) $entry["goh"] = BotFactsMergeOnHit(array_map("BotFactsClassifyOnHit", $clauses));
  }

  $entry += BotFactsPump($lower, $isAttack || $isWeapon);
  $prevention = BotFactsHandPrevention($text, $isInstant);
  if ($prevention > 0) $entry["pr"] = $prevention;
  if (preg_match("/\\bplayed from (?:your )?arsenal\\b/i", $text)) $entry["ap"] = 1;
  if (preg_match("/\\b(?:destroy|banish|put|turn)\\b[^.]{0,80}\\b(?:their|defending hero's|opponent's|that hero's) arsenal\\b|\\bcards? in their arsenal\\b/i", $text)) $entry["da"] = 1;
  if (preg_match("/when this hits?\\b[^.]*\\b(?:banish|destroy)\\b[^.]*\\bdefending\\b|\\bhits?\\b[^.]*\\b(?:banish|destroy)\\b[^.]*\\bdefending\\b/i", $text)) $entry["rd"] = 1;
  $abilities = BotFactsAbilities($text, $card->name);
  if (count($abilities) > 0) $entry["ab"] = $abilities;

  if (count($entry) > 0) $facts[$cardID] = $entry;
}
ksort($facts);

function BotFactsExport($value)
{
  if (!is_array($value)) return var_export($value, true);
  $isList = array_is_list($value);
  $items = [];
  foreach ($value as $key => $item) $items[] = ($isList ? "" : var_export($key, true) . "=>") . BotFactsExport($item);
  return "[" . implode(",", $items) . "]";
}

$lines = ["<?php", "// Generated by zzBotCardFactsGenerator.php from FAB Cube card text. Do not edit by hand.", "", "function BotCardFactsData()", "{", "  static \$data = ["];
foreach ($facts as $cardID => $entry) {
  $lines[] = "    " . var_export($cardID, true) . "=>" . BotFactsExport($entry) . ",";
}
$lines[] = "  ];";
$lines[] = "  return \$data;";
$lines[] = "}";
$lines[] = "";
file_put_contents(__DIR__ . "/AI/BotCardFacts.php", implode("\n", $lines));
echo count($facts) . " cards written to AI/BotCardFacts.php\n";
