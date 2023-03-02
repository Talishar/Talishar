<?php

//Priority Array Values:
//[0] = Block Priority
  //0 -> can't/won't block
  //0.1-0.9 -> will block with only if lethal
  //1.1-1.9 -> NOT IMPLEMENTED (planned to be cards that are blocked with to prevent on hits)
  //2.1-2.9 -> will willingly block with when efficient (higher prio blocked with first)
  //3.1-3.9 -> NOT IMPLEMENTED (if desired, I can add a type where these cards will be blocked with at the first opportunity)
  //10.1-10.9 -> the highest priority in this range will resolve to 0.1-0.9. additional cards in this range resolve to 2.1-2.9
  //11.1-10.9 -> exactly the same as above, just giving a second channel to work with
//[1] = Action Priority
  //0 -> can't/won't play
  //0.1-0.9 -> will play if possible (higher prio played first)
  //10.1-10.9 -> the highest priority in this range will resolve to 0. additional cards in this range resolve to 0.1-0.9
//[2] = Arsenal Action Priority (Implemented for arrows and other cards that change priority based on zone)
  //0 -> can't/won't play
  //0.1-0.9 -> will play if possible (higher prio played first)
//[3] = Reaction Priority
  //0 -> can't/won't play
  //0.1-0.9 -> will play if possible (higher prio played first)
  //10.1-10.9 -> the highest priority in this range will resolve to 0. additional cards in this range resolve to 0.1-0.9
//[4] = Arsenal Reaction Priority
  //0 -> can't/won't play
  //0.1-0.9 -> will play if possible (higher prio played first)
//[5] = Pitch Priority
  //0 -> can't/won't pitch
  //0.1-X.Y Will pitch if possible (higher prio pitched first) (There is no need to implement specific value channels here, I personally use 0.X for reds, 1.X for yellows, and 2.X for blues. Go wild if you want though)
//[6] = Arsenal Priority
  //0 -> can't/won't arsenal
  //0.1-0.9 -> will arsenal if possible (higher prio put in arsenal first)
//[7] = Permanent Priority
  //0 -> can't/won't activate
  //0.1->0.9 -> will activate if possible (higher prio activated first)

//TODO: Implement the following priority values: (These might not be the final indexes. Consider the above indexes final though)
//[8] = Banish Priority

function GetPriority($cardID, $heroID, $type)
{
  switch($heroID)
  {
      case "ROGUE001":
        switch($cardID)
        {
          case "ROGUE002": $priority = array(0, 0.2, 0, 0, 0, 0, 0); return $priority[$type];
          case "MON284": $priority = array(2.3, 0.8, 0.8, 0, 0, 0.5, 0.8); return $priority[$type];
          case "MON285": $priority = array(2.5, 0.6, 0.6, 0, 0, 1.5, 0.6); return $priority[$type];
          case "MON286": $priority = array(2.7, 0.4, 0.4, 0, 0, 2.5, 0.4); return $priority[$type];
          default: return 0;
        }
      case "ROGUE004":
        switch($cardID)
        {
          case "WTR176": $priority = array(10.4, 0.8, 0.8, 0, 0, 0.5, 0.5, 0); return $priority[$type];
          case "WTR178": $priority = array(11.5, 0.6, 0.6, 0, 0, 2.5, 0.3, 0); return $priority[$type];
          case "ROGUE005": $priority = array(0, 0.4, 0, 0, 0, 0, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE003":
        switch($cardID)
        {
          case "ARC191": $priority = array(0.1, 0.3, 0.3, 0, 0, 0.1, 0.3, 0); return $priority[$type];
          case "ARC192": $priority = array(0.2, 0.2, 0.2, 0, 0, 0.2, 0.2, 0); return $priority[$type];
          case "ARC193": $priority = array(0.3, 0.1, 0.1, 0, 0, 0.3, 0.1, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE006":
        switch($cardID)
        {
          case "ELE197": $priority = array(0.2, 0.5, 0.5, 0, 0, 2.6, 0.5, 0); return $priority[$type];
          case "ELE183": $priority = array(0, 0, 0, 0.9, 0.9, 0.5, 0.4, 0); return $priority[$type];
          case "ELE184": $priority = array(0, 0, 0, 0.8, 0.8, 1.5, 0.3, 0); return $priority[$type];
          case "ELE185": $priority = array(0, 0, 0, 0.7, 0.7, 2.5, 0.2, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE008":
        switch($cardID)
        {
          case "CRU065": $priority = array(10.3, 0.2, 0.2, 0, 0, 2.9, 0.2, 0); return $priority[$type];
          case "WTR100": $priority = array(10.5, 0.55, 0.55, 0, 0, 2.8, 0.55, 0); return $priority[$type];
          case "EVR043": $priority = array(10.4, 0.35, 0.35, 0, 0, 2.7, 0.35, 0); return $priority[$type];
          case "WTR103": $priority = array(10.6, 0.7, 0.7, 0, 0, 2.6, 0.7, 0); return $priority[$type];
          case "CRU063": $priority = array(11.2, 0.25, 0.25, 0, 0, 0.9, 0.25, 0); return $priority[$type];
          case "CRU073": $priority = array(11.1, 0.3, 0.3, 0, 0, 1.2, 0.3, 0); return $priority[$type];
          case "WTR082": $priority = array(0.1, 0, 0, 0.9, 0.9, 0.4, 0.1, 0); return $priority[$type];
          case "CRU074": $priority = array(0.1, 0.75, 0.75, 0, 0, 1.1, 0.75, 0); return $priority[$type];
          case "WTR209": $priority = array(0.3, 0, 0, 0.8, 0.8, 0.8, 0.15, 0); return $priority[$type];
          case "WTR098": $priority = array(0.4, 0.65, 0.65, 0, 0, 0.7, 0.65, 0); return $priority[$type];
          case "WTR099": $priority = array(0.8, 0.6, 0.6, 0, 0, 1.7, 0.6, 0); return $priority[$type];
          case "EVR041": $priority = array(0.4, 0.45, 0.45, 0, 0, 0.6, 0.45, 0); return $priority[$type];
          case "EVR042": $priority = array(0.8, 0.4, 0.4, 0, 0, 1.6, 0.4, 0); return $priority[$type];
          case "WTR101": $priority = array(0.6, 0.89, 0.89, 0, 0, 0.5, 0.89, 0); return $priority[$type];
          case "WTR102": $priority = array(0.9, 0.85, 0.85, 0, 0, 1.5, 0.85, 0); return $priority[$type];
          case "CRU072": $priority = array(0.4, 0.8, 0.8, 0, 0, 1.4, 0.8, 0); return $priority[$type];
          case "CRU050": $priority = array(0, 0.9, 0, 0, 0, 0, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE009":
        switch($cardID)
        {
          case "ARC045": $priority = array(0.1, 0, 0.5, 0, 0, 0.1, 0.5, 0); return $priority[$type];
          case "ARC069": $priority = array(0.2, 0, 0.4, 0, 0, 0.1, 0.4, 0); return $priority[$type];
          case "ARC054": $priority = array(0.5, 0.9, 0.9, 0, 0, 0.5, 0.9, 0); return $priority[$type];
          case "EVR091": $priority = array(0.3, 0.8, 0.8, 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "EVR100": $priority = array(0.4, 0.8, 0.7, 0, 0, 0.5, 0.7, 0); return $priority[$type];
          case "WTR218": $priority = array(0.6, 0.7, 0.6, 0, 0, 0.5, 0.6, 0); return $priority[$type];
          case "CRU121": $priority = array(0, 0.4, 0, 0, 0, 0, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE010":
        switch($cardID)
        {
          case "ARC106": $priority = array(0.4, 0.9, 0, 0, 0, 0.2, 0, 0); return $priority[$type];
          case "ARC107": $priority = array(0.6, 0.8, 0, 0, 0, 1.2, 0, 0); return $priority[$type];
          case "ARC108": $priority = array(0.5, 0.3, 0, 0, 0, 2.2, 0, 0); return $priority[$type];
          case "EVR107": $priority = array(0.45, 0.85, 0, 0, 0, 0.3, 0, 0); return $priority[$type];
          case "EVR108": $priority = array(0.65, 0.75, 0, 0, 0, 1.3, 0, 0); return $priority[$type];
          case "EVR109": $priority = array(0.55, 0.2, 0, 0, 0, 2.3, 0, 0); return $priority[$type];
          case "EVR113": $priority = array(0.1, 0.7, 0, 0, 0, 0.4, 0, 0); return $priority[$type];
          case "EVR114": $priority = array(0.3, 0.6, 0, 0, 0, 1.4, 0, 0); return $priority[$type];
          case "EVR115": $priority = array(0.2, 0.5, 0, 0, 0, 2.4, 0, 0); return $priority[$type];
          case "ARC085": $priority = array(0.15, 0.65, 0, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "ARC086": $priority = array(0.35, 0.6, 0, 0, 0, 1.5, 0, 0); return $priority[$type];
          case "ARC087": $priority = array(0.25, 0.4, 0, 0, 0, 2.5, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE013":
        switch($cardID)
        {
          case "WTR098": $priority = array(1.7, 0.8, 0.9, 0, 0, 0.9, 0.7, 0); return $priority[$type];
          case "WTR099": $priority = array(2.2, 0.4, 0.9, 0, 0, 1.9, 0.5, 0); return $priority[$type];
          case "WTR100": $priority = array(0.3, 0.1, 0.1, 0, 0, 2.9, 0.3, 0); return $priority[$type];
          case "WTR101": $priority = array(11.1, 0.7, 0.9, 0, 0, 0.8, 0.8, 0); return $priority[$type];
          case "WTR102": $priority = array(11.2, 0.3, 0.9, 0, 0, 1.8, 0.4, 0); return $priority[$type];
          case "WTR107": $priority = array(1.9, 0.6, 0.9, 0, 0, 0.7, 0.9, 0); return $priority[$type];
          case "WTR108": $priority = array(2.1, 0.2, 0.9, 0, 0, 1.7, 0.6, 0); return $priority[$type];
          case "WTR078": $priority = array(0, 0.9, 0, 0, 0, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE014":
        switch($cardID)
        {
          case "DYN138": $priority = array(10.3, 0.2, 0.2, 0, 0, 2.2, 1.1, 0); return $priority[$type];
          case "DYN126": $priority = array(10.3, 0.2, 0.2, 0, 0, 2.2, 1.1, 0); return $priority[$type];
          case "DYN147": $priority = array(10.3, 0.2, 0.2, 0, 0, 2.2, 1.1, 0); return $priority[$type];
          case "DYN144": $priority = array(10.3, 0.2, 0.2, 0, 0, 2.2, 1.1, 0); return $priority[$type];
          case "DYN122": $priority = array(10.2, 0.9, 0.9, 0, 0, 2.1, 1.2, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE015":
        switch($cardID)
        {
          case "DYN065": $priority = array(0, 0.5, 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "WTR218": $priority = array(0, 0.8, 0, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "WTR219": $priority = array(0, 0.7, 0, 0, 0, 1.5, 0, 0); return $priority[$type];
          case "WTR220": $priority = array(0, 0.6, 0, 0, 0, 2.5, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE016":
        switch($cardID)
        {
          case "ARC054": $priority = array(0.2, 0.9, 0.9, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "ARC055": $priority = array(0.4, 0.8, 0.8, 0, 0, 1.5, 0, 0); return $priority[$type];
          case "ARC056": $priority = array(0.6, 0.7, 0.7, 0, 0, 2.5, 0, 0); return $priority[$type];
          case "EVR091": $priority = array(0.2, 0.9, 0.9, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "EVR092": $priority = array(0.4, 0.8, 0.8, 0, 0, 1.5, 0, 0); return $priority[$type];
          case "EVR093": $priority = array(0.6, 0.7, 0.7, 0, 0, 2.5, 0, 0); return $priority[$type];
          case "EVR100": $priority = array(0.2, 0.9, 0.9, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "EVR101": $priority = array(0.4, 0.8, 0.8, 0, 0, 1.5, 0, 0); return $priority[$type];
          case "EVR102": $priority = array(0.6, 0.7, 0.7, 0, 0, 2.5, 0, 0); return $priority[$type];
          case "CRU121": $priority = array(0, 0.4, 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "ARC069": $priority = array(0, 0, 0.6, 0, 0, 0, 0.6, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE017":
        switch($cardID)
        {
          case "CRU181": $priority = array(0, ROGUE017GorgPrio(), ROGUE017GorgPrio(), 0, 0, 0, 1.9, 0); return $priority[$type];
          case "EVR041": $priority = array(0, 0.7, 0.7, 0, 0, 0.4, 0, 0); return $priority[$type];
          case "EVR042": $priority = array(0, 0.6, 0.6, 0, 0, 1.4, 0, 0); return $priority[$type];
          case "WTR098": $priority = array(0, 0.5, 0.5, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "WTR099": $priority = array(0, 0.4, 0.4, 0, 0, 1.5, 0, 0); return $priority[$type];
          case "EVR044": $priority = array(0, 0.5, 0.5, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "EVR045": $priority = array(0, 0.4, 0.4, 0, 0, 1.5, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE018":
        switch($cardID)
        {
          case "ELE094": $priority = array(11.1, 0.8, 0.9, 0, 0, 0.1, 0.8, 0); return $priority[$type];
          case "ELE137": $priority = array(10.9, 0.9, 0.9, 0, 0, 0.8, 0.7, 0); return $priority[$type];
          case "ELE139": $priority = array(10.2, 0.6, 0.1, 0, 0, 2.6, 0.5, 0); return $priority[$type];
          case "ELE128": $priority = array(10.7, 0.7, 0.9, 0, 0, 0.7, 0.8, 0); return $priority[$type];
          case "ELE130": $priority = array(10.3, 0.5, 0.1, 0, 0, 2.5, 0.5, 0); return $priority[$type];
          case "ELE119": $priority = array(10.5, 0.4, 0.9, 0, 0, 0.6, 0.9, 0); return $priority[$type];
          case "ELE121": $priority = array(10.4, 0.3, 0.1, 0, 0, 2.4, 0.6, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE019":
        switch($cardID)
        {
          case "CRU066": $priority = array(0, 0.9, 0, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "CRU067": $priority = array(0, 0.9, 0, 0, 0, 1.5, 0, 0); return $priority[$type];
          case "CRU068": $priority = array(0, 0.9, 0, 0, 0, 2.5, 0, 0); return $priority[$type];
          case "CRU057": $priority = array(0, 0.9, 0, 0, 0, 0.5, 0, 0); return $priority[$type];
          case "CRU058": $priority = array(0, 0.9, 0, 0, 0, 1.5, 0, 0); return $priority[$type];
          case "CRU059": $priority = array(0, 0.9, 0, 0, 0, 2.5, 0, 0); return $priority[$type];
          case "CRU054": $priority = array(0, 0.9, 0, 0, 0, 2.5, 0, 0); return $priority[$type];
          case "CRU056": $priority = array(0, 0.9, 0, 0, 0, 0.5, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE020":
        switch($cardID)
        {
          case "EVR075": $priority = array(0.2, 0.2, 0.9, 0, 0, 2.1, 0.8, 0); return $priority[$type];
          case "ARC028": $priority = array(0.1, 0.1, 0.9, 0, 0, 2.1, 0.8, 0); return $priority[$type];
          case "ARC031": $priority = array(10.2, 0.3, 0.9, 0, 0, 2.1, 0.8, 0); return $priority[$type];
          case "CRU111": $priority = array(10.2, 0.9, 0.9, 0, 0, 2.1, 0.8, 0); return $priority[$type];
          case "CRU108": $priority = array(10.2, 0.4, 0.9, 0, 0, 2.1, 0.8, 0); return $priority[$type];
          case "CRU103": $priority = array(10.2, 0.7, 0.9, 0, 0, 2.1, 0.8, 0); return $priority[$type];
          case "ARC022": $priority = array(10.2, 0.8, 0.9, 0, 0, 2.1, 0.8, 0); return $priority[$type];
          case "ARC013": $priority = array(10.2, 0.6, 0.9, 0, 0, 2.2, 0.8, 0); return $priority[$type];
          case "DYN097": $priority = array(10.2, 0.5, 0.9, 0, 0, 2.2, 0.9, 0); return $priority[$type];
          case "ARC025": $priority = array(10.2, 0.7, 0.9, 0, 0, 2.1, 0.8, 0); return $priority[$type];
          default: return 0;
        }
      default: return 0;
  }
}

function EncounterBlocksFirstTurn($character) //This is a way to allow encounters to block or not block on the first turn. All encounters block out unless specifically asked not to.
{
  switch($character)
  {
    default: return true;
  }
}

function ROGUE017GorgPrio()
{
  global $currentPlayer;
  $hand = &GetHand($currentPlayer);
  $arsenal = &GetArsenal($currentPlayer);
  $grave = &GetDiscard($currentPlayer);
  $totalTomes = 0;
  for($i = 0; $i < count($hand); ++$i)
  {
    if($hand[$i] == "CRU181") ++$totalTomes;
  }
  for($i = 0; $i < count($arsenal); ++$i)
  {
    if($arsenal[$i] == "CRU181") ++$totalTomes;
  }
  for($i = 0; $i < count($grave); ++$i)
  {
    if($grave[$i] == "CRU181") ++$totalTomes;
  }
  if($totalTomes >= 3) return 1.9;
  else return 0;
}
?>
