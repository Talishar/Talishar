<?php

$AI_DEBUG_ENABLED = false;
$AI_DEBUG_VISUALIZE = false;

function AIDebugLog($message, $data = null)
{
  global $AI_DEBUG_ENABLED;
  
  if(!$AI_DEBUG_ENABLED) return;
  
  if($data !== null) {
    WriteLog("[AI DEBUG] $message: " . json_encode($data));
  } else {
    WriteLog("[AI DEBUG] $message");
  }
}

function VisualizePriorityArray($priorityArray, $title = "Priority Array")
{
  global $AI_DEBUG_VISUALIZE;
  
  if(!$AI_DEBUG_VISUALIZE) return;
  
  WriteLog("=== $title ===");
  WriteLog("Total cards: " . count($priorityArray));
  
  for($i = 0; $i < count($priorityArray); ++$i) {
    $node = $priorityArray[$i];
    $cardName = CardName($node[0]);
    $zone = $node[1];
    $index = $node[2];
    $priority = $node[3];

    WriteLog("  [$i] $cardName ($zone) - Priority: $priority");
  }
}

function AnalyzeCardPriority($cardID, $heroID, $priorityType)
{
  $behavior = GetCardBehavior($cardID, $heroID);
  
  $typeNames = [
    0 => "Block",
    1 => "Action", 
    2 => "Arsenal Action",
    3 => "Reaction",
    4 => "Arsenal Reaction",
    5 => "Pitch",
    6 => "Arsenal",
    7 => "Permanent"
  ];
  
  $typeName = $typeNames[$priorityType] ?? "Unknown";
  $priority = $behavior[$priorityType] ?? 0;
  $cardName = CardName($cardID);
  
  return [
    "card" => $cardName,
    "type" => $typeName,
    "priority" => $priority,
    "playable" => $priority > 0,
  ];
}

function GetHandStats($playerID)
{
  $hand = &GetHand($playerID);
  $character = &GetPlayerCharacter($playerID);
  $heroID = $character[0];
  
  $stats = [
    "hand_size" => count($hand),
    "average_priority" => 0,
    "high_value_count" => 0,
    "zero_priority_count" => 0,
  ];
  
  $totalPriority = 0;
  foreach($hand as $cardID) {
    $behavior = GetCardBehavior($cardID, $heroID);
    $actionPriority = $behavior[1];

    $totalPriority += $actionPriority;
    if($actionPriority >= 0.8) $stats["high_value_count"]++;
    if($actionPriority == 0) $stats["zero_priority_count"]++;
  }
  
  $stats["average_priority"] = count($hand) > 0 ? $totalPriority / count($hand) : 0;
  
  return $stats;
}

function TestAIDecision($playerID, $scenario = "Action")
{
  $hand = &GetHand($playerID);
  $character = &GetPlayerCharacter($playerID);
  $arsenal = &GetArsenal($playerID);
  $items = &GetItems($playerID);
  $allies = &GetAllies($playerID);
  $banish = &GetBanish($playerID);
  
  $priorityArray = GeneratePriorityValues($hand, $character, $arsenal, $items, $allies, $banish, $scenario);

  $result = [
    "scenario" => $scenario,
    "best_card" => null,
    "best_priority" => 0,
    "top_3" => []
  ];
  for($i = max(0, count($priorityArray) - 3); $i < count($priorityArray); ++$i) {
    if($i >= 0) {
      $node = $priorityArray[$i];
      $result["top_3"][] = [
        "card" => CardName($node[0]),
        "priority" => $node[3],
        "zone" => $node[1]
      ];
    }
  }
  
  if(count($priorityArray) > 0) {
    $best = $priorityArray[count($priorityArray) - 1];
    $result["best_card"] = CardName($best[0]);
    $result["best_priority"] = $best[3];
  }
  
  return $result;
}

function PrintHeroBehaviorTable($heroID)
{
  $behaviors = GetCardBehaviorForHero($heroID);
  
  $output = "HERO: $heroID\n";
  $output .= str_repeat("=", 100) . "\n";
  $output .= sprintf("%-30s %6s %6s %6s %6s %6s %6s %6s %6s\n", 
    "Card", "Block", "Action", "ArsAct", "React", "ArsReact", "Pitch", "Ars", "Perm");
  $output .= str_repeat("-", 100) . "\n";
  
  foreach($behaviors as $cardID => $priority) {
    $output .= sprintf("%-30s %6.2f %6.2f %6.2f %6.2f %6.2f %6.2f %6.2f %6.2f\n",
      substr($cardID, 0, 29),
      $priority[0], $priority[1], $priority[2], $priority[3],
      $priority[4], $priority[5], $priority[6], $priority[7]
    );
  }
  
  return $output;
}

function ValidateCardBehaviors()
{
  $errors = [];
  
  $heroes = ["ira_crimson_haze", "fai_rising_rebellion", "lexi_rowdeez"];
  
  foreach($heroes as $heroID) {
    $behaviors = GetCardBehaviorForHero($heroID);
    
    foreach($behaviors as $cardID => $priority) {
      // Validate each priority index
      for($i = 0; $i < 8; ++$i) {
        $val = $priority[$i];
        
        // Check range: either 0, 0.1-0.9, or 10.1-10.9
        $valid = ($val == 0) || 
                 ($val >= 0.1 && $val <= 0.9) ||
                 ($val >= 10.1 && $val <= 10.9);
        
        if(!$valid) {
          $errors[] = "Invalid priority for $cardID (index $i): $val";
        }
      }
    }
  }
  
  return $errors;
}

function GenerateAIPerformanceReport($gameLimit = 10)
{
  
  return [
    "games_analyzed" => 0,
    "win_rate" => 0,
    "average_turns" => 0,
    "common_strategies" => []
  ];
}

function SetAIDebugMode($enabled, $visualize = false)
{
  global $AI_DEBUG_ENABLED, $AI_DEBUG_VISUALIZE;
  
  $AI_DEBUG_ENABLED = $enabled;
  $AI_DEBUG_VISUALIZE = $visualize;
  
  if($enabled) {
    WriteLog("AI Debug Mode: ENABLED");
    if($visualize) WriteLog("AI Debug Visualization: ENABLED");
  }
}

function QuickCheckHero($heroID)
{
  $result = [
    "hero" => $heroID,
    "cards_configured" => 0,
    "cards_missing_action" => 0,
  ];
  
  $behaviors = GetCardBehaviorForHero($heroID);
  $result["cards_configured"] = count($behaviors);
  
  foreach($behaviors as $cardID => $priority) {
    if($priority[1] == 0) {
      $result["cards_missing_action"]++;
    }
  }
  
  return $result;
}

?>
