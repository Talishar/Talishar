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
          case "CRU050": $priority = array(0, 0.85, 0.85, 0, 0, 0.5, 0.85, 0); return $priority[$type];
          case "DYN049": $priority = array(0.5, 0.9, 0.9, 0, 0, 1.5, 0.9, 0); return $priority[$type];
          case "CRU074": $priority = array(0.5, 0.82, 0.82, 0, 0, 1.5, 0.82, 0); return $priority[$type];
          case "CRU072": $priority = array(0.5, 0.8, 0.8, 0, 0, 1.5, 0.8, 0); return $priority[$type];
          case "OUT052": $priority = array(0.5, 0.81, 0.81, 0, 0, 0.5, 0.81, 0); return $priority[$type];
          case "DYN065": $priority = array(0.5, 0.7, 0.7, 0, 0, 0, 0.7, 0); return $priority[$type];
          case "ARC159": $priority = array(0.5, 0.61, 0.61, 0, 0, 0.5, 0.61, 0); return $priority[$type];
          case "CRU063": $priority = array(0.5, 0.6, 0.6, 0, 0, 0.5, 0.6, 0); return $priority[$type];
          case "WTR082": $priority = array(0.5, 0, 0, 0.9, 0.9, 0.5, 0.9, 0); return $priority[$type];
          case "CRU065": case "OUT051": case "CRU054": case "WTR088": case "CRU068": case "OUT142": case "EVR040": $priority = array(10.9, 0.1, 0.1, 0, 0, 2.5, 0, 0); return $priority[$type];
          case "WTR079": case "WTR157": case "WTR158": case "WTR156": $priority = array(0.9, 0, 0, 0, 0, 0, 0, 0); return $priority[$type];
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
      case "ROGUE021":
        switch($cardID)
        {
          case "WTR017": case "WTR018": case "WTR019": $priority = array(2.1, 0.5, 0.6, 0, 0, 1.1, 0); return $priority[$type];
          case "MON226": $priority = array(0, 0.4, 0.1, 0, 0, 0.1, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE022":
        switch($cardID)
        {
          case "MON203": $priority = array(10.3, 0.7, 0.7, 0, 0, 0.5, 0.7, 0); return $priority[$type];
          case "MON204": $priority = array(11.1, 0.5, 0.5, 0, 0, 1.5, 0.5, 0); return $priority[$type];
          case "MON205": $priority = array(11.4, 0.3, 0.3, 0, 0, 2.5, 0.3, 0); return $priority[$type];
          case "MON206": $priority = array(10.7, 0.9, 0.9, 0, 0, 0.5, 0.9, 0); return $priority[$type];
          case "MON208": $priority = array(11.6, 0.4, 0.4, 0, 0, 2.5, 0.4, 0); return $priority[$type];
          case "MON209": $priority = array(10.5, 0.8, 0.8, 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "MON211": $priority = array(11.2, 0.6, 0.6, 0, 0, 2.5, 0.6, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE023":
        switch($cardID)
        {
          case "WTR044": $priority = array(0.2, 0.8, 0.8, 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "WTR048": $priority = array(0.2, 0.8, 0.8, 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "ELE209": $priority = array(0.2, 0.8, 0.8, 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "WTR050": $priority = array(0.1, 0.5, 0.5, 0, 0, 2.5, 0.5, 0); return $priority[$type];
          case "WTR068": $priority = array(0.1, 0.5, 0.5, 0, 0, 2.5, 0.5, 0); return $priority[$type];
          case "CRU037": $priority = array(0.1, 0.5, 0.5, 0, 0, 2.5, 0.5, 0); return $priority[$type];
          case "ELE211": $priority = array(0.1, 0.5, 0.5, 0, 0, 2.5, 0,5, 0); return $priority[$type];
          case "WTR153": $priority = array(0.1, ROGUE023GauntletPrio(), 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "DYN027": $priority = array(2.6, 0, 0, 0, 0, 0, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE024":
        switch($cardID)
        {
          case "ELE044": $priority = array(0.1, 0, 0.6, 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "ELE045": $priority = array(0.1, 0, 0.6, 0, 0, 1.5, 0.7, 0); return $priority[$type];
          case "ELE046": $priority = array(0.1, 0, 0.6, 0, 0, 2.5, 0.6, 0); return $priority[$type];
          case "ELE050": $priority = array(0.1, 0, 0.6, 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "ELE051": $priority = array(0.1, 0, 0.6, 0, 0, 1.5, 0.7, 0); return $priority[$type];
          case "ELE052": $priority = array(0.1, 0, 0.6, 0, 0, 2.5, 0.6, 0); return $priority[$type];
          case "ELE035": $priority = array(0.1, 0, 0.6, 0, 0, 2.5, 0.6, 0); return $priority[$type];
          case "ELE168": $priority = array(0.2, 10.7, 0.9, 0, 0, 2.6, 0.9, 0); return $priority[$type];
          case "UPR149": $priority = array(0.2, 10.7, 0.9, 0, 0, 2.6, 0.9, 0); return $priority[$type];
          case "UPR143": $priority = array(0.2, 10.7, 0.9, 0, 0, 2.6, 0.9, 0); return $priority[$type];
          case "ELE171": $priority = array(0.2, 10.7, 0.9, 0, 0, 2.6, 0.9, 0); return $priority[$type];
          case "ELE165": $priority = array(0.2, 10.7, 0.9, 0, 0, 2.6, 0.9, 0); return $priority[$type];
          case "ELE033": $priority = array(0, ROGUE024BowPrio(), 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "UPR136": $priority = array(0.1, ROGUE024PeakPrio(), 0, 0, 0, 0, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE025":
        switch($cardID)
        {
          case "WTR017": $priority = array(0.1, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 0.5, 0.9, 0); return $priority[$type];
          case "WTR018": $priority = array(0.1, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 1.5, 0.9, 0); return $priority[$type];
          case "WTR019": $priority = array(0.1, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 2.5, 0.9, 0); return $priority[$type];
          case "RVD025": $priority = array(0.3, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 2.5, 0.8, 0); return $priority[$type];
          case "EVR005": $priority = array(0.3, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "EVR006": $priority = array(0.3, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 1.5, 0.8, 0); return $priority[$type];
          case "EVR007": $priority = array(0.3, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 2.5, 0.8, 0); return $priority[$type];
          case "EVR164": $priority = array(0.3, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "EVR165": $priority = array(0.3, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 1.5, 0.8, 0); return $priority[$type];
          case "EVR166": $priority = array(0.3, ROGUE025ActionPrio(), ROGUE025ActionPrio(), 0, 0, 2.5, 0.8, 0); return $priority[$type];
          case "ARC191": $priority = array(0.2, 0.7, 0.7, 0, 0, 0.5, 0.7, 0); return $priority[$type];
          case "ARC192": $priority = array(0.2, 0.7, 0.7, 0, 0, 1.5, 0.7, 0); return $priority[$type];
          case "ARC193": $priority = array(0.2, 0.7, 0.7, 0, 0, 2.5, 0.7, 0); return $priority[$type];
          case "DYN005": $priority = array(0, ROGUE025RokPrio(), 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "ELE224": $priority = array(2.6, 0, 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "DYN045": $priority = array(2.6, 0, 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "ELE213": $priority = array(ROGUE025HorizonsPrio(), 0, 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "CRU197": $priority = array(0, 0, 0, 0, 0, 0, 0, 0.9); return $priority[$type];
          default: return 0;
        }
      case "ROGUE026":
        switch($cardID)
        {
          case "ARC159": case "OUT201": case "UPR187": case "OUT189": case "MON284": case "MON287": $priority = array(10.6, 0.9, 0.9, 0, 0, 0.5, 0.9, 0); return $priority[$type];
          case "WTR166": case "OUT203": case "ARC161": case "OUT191": case "MON286": case "MON289": $priority = array(11.5, 0.6, 0.6, 0, 0, 2.5, 0.6, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE027":
        switch($cardID)
        {
          case "MON113": case "CRU085": case "CRU094": $priority = array(2.7, 0.9, 0.9, 0, 0, 0.5, 0.9, 0); return $priority[$type];
          case "MON114": case "CRU086": case "CRU095": $priority = array(2.8, 0.5, 0.9, 0, 0, 1.5, 0.9, 0); return $priority[$type];
          case "WTR122": $priority = array(2.6, ROGUE027IronsongPrio(), ROGUE027IronsongPrio(), 0, 0, 1.4, 0.8, 0); return $priority[$type];
          case "DYN070": $priority = array(0, 0.7, 0, 0, 0, 0, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE028":
        switch($cardID)
        {
          case "EVR150": case "MON095": $priority = array(0.4, 0.9, 0.9, 0, 0, 0.5, 0.9, 0); return $priority[$type];
          case "MON101": case "DYN224": $priority = array(0.6, 0.8, 0.8, 0, 0, 0.5, 0.8, 0); return $priority[$type];
          case "EVR152": case "MON097": $priority = array(0.4, 0.6, 0.9, 0, 0, 2.5, 0.9, 0); return $priority[$type];
          case "MON103": case "DYN226": $priority = array(0.6, 0.6, 0.8, 0, 0, 2.5, 0.8, 0); return $priority[$type];
          case "DYN227": $priority = array(0.8, 0.7, 0.7, 0, 0, 0.5, 0.7, 0); return $priority[$type];
          case "DYN229": $priority = array(0.8, 0.6, 0.7, 0, 0, 2.5, 0.7, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE029":
        switch($cardID)
        {
          case "MON109": case "DYN071": $priority = array(0.4, 0.9, 0.9, 0, 0, 0.5, 0.9, 0); return $priority[$type];
          case "CRU088": case "WTR138": $priority = array(0.2, 0, 0, 0.7, 0.7, 0.5, 0.7, 0); return $priority[$type];
          case "WTR123": $priority = array(0.3, 0, 0, 0.9, 0.9, 0.5, 0.8, 0); return $priority[$type];
          case "CRU090": case "WTR140": $priority = array(0.1, 0, 0, 0.5, 0.9, 2.5, 0.5, 0); return $priority[$type];
          case "WTR125": $priority = array(0.1, 0, 0, 0.6, 0.9, 2.5, 0.5, 0); return $priority[$type];
          case "DYN068": $priority = array(0, 0.8, 0, 0, 0, 0, 0, 0); return $priority[$type];
          default: return 0;
        }
      case "ROGUE030":
        switch($cardID)
        {
          case "WTR206": $priority = array(10.4, 0, 0, 1.7, 1.9, 0.3, 1.4); return $priority[$type];
          case "WTR208": $priority = array(11.3, 0, 0, 1.6, 1.8, 2.2, 0.4); return $priority[$type];
          case "ARC161": $priority = array(0.7, 1.7, 1.8, 0, 0, 2.1, 0.8); return $priority[$type];
          case "ARC159": $priority = array(0.8, 1.8, 1.9, 0, 0, 0.1, 2.8); return $priority[$type];
          case "CRU194": $priority = array(11.8, 1.1, 1.7, 0, 0, 2.5, 0.5); return $priority[$type];
          case "CRU192": $priority = array(2.2, 1.2, 1.6, 0, 0, 0.2, 0.4); return $priority[$type];
          case "MON263": $priority = array(2.1, 1.3, 1.5, 0, 0, 0.5, 0.9); return $priority[$type];
          case "ARC150": $priority = array(2.1, 0, 0, 0, 0, 0, 0, 0); return $priority[$type];
          case "WTR153": $priority = array(0, 1.9, 0, 0, 0, 0, 0, 1.9); return $priority[$type];
          default: return 0;
        }
      case "ROGUE031":
        switch($cardID)
        {
          case "ELE188": $priority = array(0, 0.7); return $priority[$type];
          case "ELE187": $priority = array(0, 0.6); return $priority[$type];
          case "ELE186": $priority = array(0, 0.5); return $priority[$type];
          case "UPR092": $priority = array(0.5, 0.4); return $priority[$type];
          case "UPR098": $priority = array(10.6, 0.1, 0.2, 0, 0, 0, 0.5); return $priority[$type];
          case "ROGUE032": $priority = array(0, ROG032ActionPoint()); return $priority[$type];
          case "UPR084": $priority = array(2.2, 0, 0, 0, 0); return $priority[$type];
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

function ROGUE023GauntletPrio()
{
  global $currentTurnEffects, $currentPlayer;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "CRU029": case "CRU030": case "CRU031": return 0.9;
        default:
          break;
      }
    }
  }
  return 0;
}

function ROGUE024BowPrio()
{
  global $currentPlayer;
  $hand = &GetHand($currentPlayer);
  $found = false;
  for ($i = 0; $i < count($hand); ++$i)
  {
    if(CardSubType($hand[$i]) == "Arrow") $found = true;
  }
  return $found ? 0.8 : 0;
}

function ROGUE024PeakPrio()
{
  global $currentPlayer;
  $hand = &GetHand($currentPlayer);
  $found = false;
  for ($i = 0; $i < count($hand); ++$i)
  {
    if(CardSubType($hand[$i]) == "Arrow") $found = true;
  }
  $arsenal = &GetArsenal($currentPlayer);
  if(CardSubType($arsenal[0]) == "Arrow") $found = true;
  return $found ? 0 : 0.8;
}

function ROGUE025ActionPrio()
{
  global $currentPlayer;
  $hand = &GetHand($currentPlayer);
  $permanents = &GetPermanents($currentPlayer);
  $resources = &GetResources($currentPlayer);
  $arsenal = &GetArsenal($currentPlayer);
  $found = false;
  for($i = 0; $i < count($permanents); $i += PermanentPieces())
  {
    switch($permanents[$i])
    {
      case "ROGUE803": $found = true;
      default: break;
    }
  }
  //WriteLog("TheoreticalResources->".count($hand)+$resources[0]+count($arsenal));
  if(($found && count($hand)+$resources[0]+count($arsenal)>=2) || (count($hand)+$resources[0]+count($arsenal)>=3)) return 0.8;
  else return 0;
}

function ROGUE025RokPrio()
{
  global $currentPlayer;
  $hand = &GetHand($currentPlayer);
  if(count($hand) == 0) return 0.85;
  else return 0;
}

function ROGUE025HorizonsPrio()
{
  global $currentPlayer;
  $arsenal = &GetArsenal($currentPlayer);
  if(count($arsenal) > 0) return 2.9;
  else return 0.9;
}

function ROGUE027IronsongPrio()
{
  global $currentPlayer;
  $resources = &GetResources($currentPlayer);
  if($resources[0] != 0) return 0.8;
  else return 0;
}

function ROG031Blaze()
{
  //TODO: Get Combat Chain
  return 0.4;
}

function ROG032ActionPoint()
{
  global $actionPoints;
  if($actionPoints > 1){
    return 0.9;
  }
  else {
    return 0;
  }
}
?>
