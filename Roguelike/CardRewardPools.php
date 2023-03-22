<?php

/*
Encounter variable
encounter[0] = Encounter ID (001-099 Special Encounters | 101-199 Combat Encounters | 201-299 Event Encounters)
encounter[1] = Encounter Subphase
encounter[2] = Position in adventure
encounter[3] = Hero ID
encounter[4] = Adventure ID
encounter[5] = A string made up of encounters that have already been visited, looks like "ID-subphase,ID-subphase,ID-subphase,etc."
encounter[6] = majesticCard% (1-100, the higher it is, the more likely a majestic card is chosen) (Whole code is based off of the Slay the Spire rare card chance)
encounter[7] = background chosen
encounter[8] = adventure difficulty (to be used later)
encounter[9] = current gold
encounter[10] = rerolls remaining //TODO: Add in a reroll system
encounter[11] = cost to heal at the shop
encounter[12] = cost to remove card at the shop
*/

//For Maindeck cards, $tag1 and $tag2 can be any tag that you want to use to filter results. For equipment, set $tag1 according to note below
function GetPool($type, $hero, $rarity, $background, $tag1="", $tag2 = "",){
  if(($hero == "Bravo" || $hero == "Dorinthea") && $type == "Talent") $type = "Class";

  if($type == "Class") return GetPoolClass(array($rarity, $background));
  else if($type == "Generic") return GetPoolGeneric(array($rarity));
  else if($type == "Talent") return GetPoolTalent(array($type, $rarity, $background));
  else if($type == "Equipment") {
    //Okay, this is a little weird, but to call for equipment, set $type to be "Equipment", and $tag1 to be either "Generic", "All", or "Hero". Default is "All". 
    if($rarity == "-"){
      return GetPoolLogicEquipment($tag1, $hero, array($tag2));
    }
    else {
      return GetPoolLogicEquipment($tag1, $hero, array($rarity, $tag2));
    }
  }
}

//See GetPool() for logic. $type would be 
function GetPoolLogicEquipment($tag1, $hero, $tags){
  if($tag1 == "Hero"){
    array_push($tags, $hero);
    return GetPoolEquipment($tags);
  }
  else if($tag1 == "Generic"){
    array_push($tags, "Generic");
    return GetPoolEquipment($tags);
  }
  else { // "All" mode as default. Can return generic and class cards with the given tags. 
    return array_merge(GetPoolLogicEquipment("Hero", $hero, $tags), GetPoolLogicEquipment("Generic", $hero, $tags));
  }

}

//Called at DecisionQueue.php at Backgrounds event
function GiveUniversalEquipment(){
  $character = &GetZone(1, "Character");
  array_push($character, "WTR156");
}

//Input a list of parameters
function GetPoolClass($arrayParameters){

  $CardRewardPool = array(
    array("WTR043", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"),
    array("WTR044", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"),
    array("WTR045", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR046", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR047", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR048", "Class", "Rare", "Anothos", "TitanFist", "Sledge"), //Disable
    array("WTR049", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR050", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR051", "Class", "Rare", "Anothos", "TitanFist", "Sledge"), //Staunch Response
    array("WTR052", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR053", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR054", "Class", "Rare", "Anothos", "TitanFist", "Sledge"), //Blessing of Deliverance
    array("WTR055", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR056", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("WTR057", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Buckling Blow
    array("WTR058", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("WTR059", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    //TODO WTR060-WTR062 Cartilage Crush - Add when the AI can have taxes
    //TODO WTR063-WTR065 Crush Confidence - Add when the AI can handle losing hero card effects and activated abilities
    array("WTR066", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Debilitate
    array("WTR067", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("WTR068", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("WTR069", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Emerging Power
    array("WTR070", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("WTR071", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("WTR072", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Stonewall Confidence
    array("WTR073", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("WTR074", "Class", "Common", "Anothos", "TitanFist", "Sledge"),

    array("WTR082", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Ancestral Empowerment
    array("WTR098", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Head Jab
    array("WTR099", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("WTR100", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("WTR101", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Leg Tap
    array("WTR102", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("WTR103", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("WTR107", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Surging Strike
    array("WTR108", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("WTR109", "Class", "Common", "Emberblade", "Kodachi", "Edge"),

    array("WTR118", "Class", "Majestic", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR119", "Class", "Majestic", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR120", "Class", "Majestic", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //120-122 are Supers. I'm putting them in the majestic queue
    array("WTR121", "Class", "Majestic", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR122", "Class", "Majestic", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR123", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Overpower
    array("WTR124", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR125", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR126", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Steelblade Shunt
    array("WTR127", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR128", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR129", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Warrior's Valor
    array("WTR130", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR131", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR132", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Ironsong Response
    array("WTR133", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR134", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR135", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Biting Blade
    array("WTR136", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR137", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR138", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Stroke of Foresight
    array("WTR139", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR140", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR141", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Sharpern Steel
    array("WTR142", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR143", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR144", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Driving Blade
    array("WTR145", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR146", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR147", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Nature's Path Pilgrimage
    array("WTR148", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("WTR149", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),

    array("ARC044", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Three of a Kind
    array("ARC045", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Endless Arrow
    array("ARC048", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Take Cover
    array("ARC049", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ARC050", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ARC054", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Take Aim
    array("ARC055", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ARC056", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ARC060", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Hamstring Shot
    array("ARC061", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ARC062", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ARC069", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Searing Shot
    array("ARC070", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ARC071", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),

    array("CRU026", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"), //Mangle
    array("CRU027", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"), //Righteous Cleansing
    array("CRU028", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"), //Stamp Authority
    array("CRU029", "Class", "Rare", "Anothos", "TitanFist", "Sledge"), //Towering Titan
    array("CRU030", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("CRU031", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("CRU032", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Crush the Weak
    array("CRU033", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("CRU034", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("CRU035", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Chokeslam
    array("CRU036", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("CRU037", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("CRU038", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Emerging Dominance
    array("CRU039", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("CRU040", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("CRU041", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Blessing of Serenity
    array("CRU042", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("CRU043", "Class", "Common", "Anothos", "TitanFist", "Sledge"),

    array("CRU063", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Flying Kick
    array("CRU064", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("CRU065", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("CRU066", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Soulbead Strike
    array("CRU067", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("CRU068", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("CRU069", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Torrent of Tempo
    array("CRU070", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("CRU071", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("CRU072", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Bittering Thorns
    array("CRU073", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Salt the Wound

    array("CRU082", "Class", "Majestic", "Saber", "Dawnblade"), //Twinning Blade - Only Swords
    array("CRU083", "Class", "Majestic", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Unified Decree
    array("CRU084", "Class", "Majestic", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Spoils of War
    //Dauntless - Can be added when the AI can handle taxes
    array("CRU088", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Out for Blood
    array("CRU089", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("CRU090", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("CRU091", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Hit and Run
    array("CRU092", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("CRU093", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("CRU094", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Push Forward
    array("CRU095", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("CRU096", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),

    array("CRU123", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Remorseless
    array("CRU124", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Poison the Tips
    array("CRU132", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Sleep Dart
    array("CRU133", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("CRU134", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),

    array("MON109", "Class", "Majestic", "Hatchet", "Battleaxe"), //Spill Blood - Only Axes
    array("MON110", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Dusk Path Pilgrimage
    array("MON111", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("MON112", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("MON113", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Plow Through
    array("MON114", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("MON115", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("MON116", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Second Swing
    array("MON117", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("MON118", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),

    array("ELE035", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Frost Lock
    array("ELE036", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Light it Up
    array("ELE037", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Ice Storm
    array("ELE038", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Cold Wave - Could be worth ommiting due to the AI never playing DRs, but it still gets the "If this is fused" and "if you've fused this turn" type of things. Also, the player doesn't know that
    array("ELE039", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE040", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE041", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Snap Shot
    array("ELE042", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE043", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE044", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Blizzard Bolt
    array("ELE045", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE046", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE047", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Buzz Bolt
    array("ELE048", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE049", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE050", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Chilling Icevein
    array("ELE051", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE052", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE053", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Dazzling Crescendo
    array("ELE054", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE055", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE056", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Flake Out
    array("ELE057", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE058", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE059", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Frazzle
    array("ELE060", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE061", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),

    array("ELE205", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"), //Tear Asunder
    array("ELE206", "Class", "Rare", "Anothos", "TitanFist", "Sledge"), //Embolden
    array("ELE207", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("ELE208", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    //ELE209 - Thump - Can the AI handle this? I haven't tested it yet. TODO Test it

    array("ELE215", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Seek and Destroy
    array("ELE216", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Bolt'n' Shot
    array("ELE217", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE218", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE219", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Over Flex
    array("ELE220", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE221", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),


    array("EVR021", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"),
    array("EVR022", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"),
    //EVR023 Nerves of Steel - I think this one is situational enough that we can omit it for now
    array("EVR024", "Class", "Rare", "Anothos", "TitanFist", "Sledge"), //Thunder Quake
    array("EVR025", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("EVR026", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("EVR027", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Macho Grande
    array("EVR028", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("EVR029", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("EVR030", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Seismic Stir
    array("EVR031", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("EVR032", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("EVR033", "Class", "Common", "Anothos", "TitanFist", "Sledge"), //Steadfast
    array("EVR034", "Class", "Common", "Anothos", "TitanFist", "Sledge"),
    array("EVR035", "Class", "Common", "Anothos", "TitanFist", "Sledge"),

    array("EVR041", "Class", "Rare", "Emberblade", "Kodachi", "Edge"), //Hundred Winds
    array("EVR042", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("EVR043", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("EVR044", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Ride the Tailwind
    array("EVR045", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("EVR046", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("EVR047", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Twin Twisters
    array("EVR048", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("EVR049", "Class", "Common", "Emberblade", "Kodachi", "Edge"),

    array("EVR054", "Class", "Majestic", "Dawnblade", "Battleaxe"), //Shatter
    //EVR055 Blood on Her Hands - Not playable in any of our heroes
    array("EVR056", "Class", "Rare", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //Oath of Steel
    array("EVR057", "Class", "Rare", "Saber", "Dawnblade"), //Slice and Dice - Sword / Dagger only
    array("EVR058", "Class", "Rare", "Saber", "Dawnblade"),
    array("EVR059", "Class", "Rare", "Saber", "Dawnblade"),
    array("EVR060", "Class", "Common", "Saber", "Hatchet"), //Blade Runner - 1H weapon
    array("EVR061", "Class", "Common", "Saber", "Hatchet"),
    array("EVR062", "Class", "Common", "Saber", "Hatchet"),
    array("EVR063", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"), //In the Swing
    array("EVR064", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("EVR065", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe"),
    array("EVR066", "Class", "Common", "Saber", "Hatchet"), //Outland Skirmish - 1H weapon
    array("EVR067", "Class", "Common", "Saber", "Hatchet"),
    array("EVR068", "Class", "Common", "Saber", "Hatchet"),

    array("EVR090", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Rain Razors
    array("EVR091", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Release the Tension - might be worth ommiting due to the AI not being able to play DRs, but it's still a buff, so it stays for now
    array("EVR092", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("EVR093", "Class", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("EVR094", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Fatigue Shot
    array("EVR095", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("EVR096", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("EVR100", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Read the Glide Path
    array("EVR101", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("EVR102", "Class", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),

    array("UPR048", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Phoenix Form
    array("UPR049", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Searing Emberblade
    array("UPR050", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Combustion Point
    array("UPR051", "Class", "Rare", "Emberblade", "Kodachi", "Edge"), //Engulfing Flamewave
    array("UPR052", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("UPR053", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("UPR054", "Class", "Rare", "Emberblade", "Kodachi", "Edge"), //Mounting Anger
    array("UPR055", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("UPR056", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("UPR057", "Class", "Rare", "Emberblade", "Kodachi", "Edge"), //Rise from the Ashes
    array("UPR058", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("UPR059", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("UPR060", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Brand with Cinderclaw
    array("UPR061", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR062", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR063", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Cinderskin Devotion
    array("UPR064", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR065", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR066", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Dust Runner Outlaw
    array("UPR067", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR068", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR069", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Lava Vein Loyalty
    array("UPR070", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR071", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR072", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Rebellious Rush
    array("UPR073", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR074", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR075", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Rising Resentment
    array("UPR076", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR077", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR078", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Ronin Renegade
    array("UPR079", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR080", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR081", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Soaring Strike
    array("UPR082", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("UPR083", "Class", "Common", "Emberblade", "Kodachi", "Edge"),

    array("UPR160", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Double Strike
    array("UPR161", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Take the Tempo
    array("UPR162", "Class", "Rare", "Emberblade", "Kodachi", "Edge"), //Rapid Reflex
    array("UPR163", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("UPR164", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),

    array("DYN028", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"), //Buckle
    array("DYN029", "Class", "Majestic", "Anothos", "TitanFist", "Sledge"), //Never Yield
    array("DYN030", "Class", "Rare", "TitanFist"), //Shield Bash - Limited to Titanfist for off-hand synergy
    array("DYN031", "Class", "Rare", "TitanFist"),
    array("DYN032", "Class", "Rare", "TitanFist"),
    array("DYN033", "Class", "Rare", "Anothos", "TitanFist", "Sledge"), //Blessing of Patience
    array("DYN034", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("DYN035", "Class", "Rare", "Anothos", "TitanFist", "Sledge"),
    array("DYN036", "Class", "Rare", "TitanFist"), //Shield Wall - Limited to Titanfist for off-hand synergy, this one honestly could be in all pools but I chose to limit for now
    array("DYN037", "Class", "Rare", "TitanFist"),
    array("DYN038", "Class", "Rare", "TitanFist"),
    array("DYN039", "Class", "Rare", "TitanFist"), //Reinforce Steel - Limited to Titanfist for off-hand synergy
    array("DYN040", "Class", "Rare", "TitanFist"),
    array("DYN041", "Class", "Rare", "TitanFist"),
    array("DYN042", "Class", "Rare", "TitanFist"), //Withstand - Limited to Titanfist for off-hand synergy
    array("DYN043", "Class", "Rare", "TitanFist"),
    array("DYN044", "Class", "Rare", "TitanFist"),

    array("DYN047", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Tiger Swipe
    array("DYN048", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Mindstate of the Tiger
    array("DYN049", "Class", "Majestic", "Emberblade", "Kodachi", "Edge"), //Roar of the Tiger
    array("DYN050", "Class", "Rare", "Emberblade", "Kodachi", "Edge"), //Flex Claws - Decide whether or not this is included in the pool
    array("DYN051", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("DYN052", "Class", "Rare", "Emberblade", "Kodachi", "Edge"),
    array("DYN056", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Pouncing Qi - Decide whether or not this is included in the pool
    array("DYN057", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("DYN058", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("DYN062", "Class", "Common", "Emberblade", "Kodachi", "Edge"), //Predatory Streak
    array("DYN063", "Class", "Common", "Emberblade", "Kodachi", "Edge"),
    array("DYN064", "Class", "Common", "Emberblade", "Kodachi", "Edge"),

    array("DYN071", "Class", "Majestic", "Hatchet", "Battleaxe"), //Cleave - Since there aren't any Ally cards yet, I could see omitting this, but the buff is still playable so I'm keeping it
    array("DYN072", "Class", "Majestic"), //Ironsong Ride - Limited to sword backgrounds
    array("DYN073", "Class", "Rare", "Hatchet", "Battleaxe"), //Blessing of Steel
    array("DYN074", "Class", "Rare", "Hatchet", "Battleaxe"),
    array("DYN075", "Class", "Rare", "Hatchet", "Battleaxe"),
    array("DYN076", "Class", "Rare", "Hatchet", "Battleaxe"), //Precision Press
    array("DYN077", "Class", "Rare", "Hatchet", "Battleaxe"),
    array("DYN078", "Class", "Rare", "Hatchet", "Battleaxe"),
    array("DYN079", "Class", "Common", "Saber", "Dawnblade"), //Puncture - Swords/Dagger only
    array("DYN080", "Class", "Common", "Saber", "Dawnblade"),
    array("DYN081", "Class", "Common", "Saber", "Dawnblade"),
    array("DYN082", "Class", "Common", "Hatchet", "Battleaxe"), //Felling Swing
    array("DYN083", "Class", "Common", "Hatchet", "Battleaxe"),
    array("DYN084", "Class", "Common", "Hatchet", "Battleaxe"),
    //DYN085-087 Visit the Imperial Forge - I've decided to omit these, since armor isn't hugely relevant right now and I don't know how the AI can handle piercing. It feels like this card would only be good in an exploitative manner

    array("DYN153", "Class", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //only Heat Seeker. No way to gain aim counters yet. Might reevaluate if we make an aim centric power.
  );

  return ProcessPool($CardRewardPool, $arrayParameters);
}
function GetPoolGeneric($arrayParameters){

  //Currently, Generics are available to all heroes equally.
  //In the future, if we want to have certain generics available to certain heroes, we can go back and tag most cards with "All", and specific ones with the name of that hero
  $CardRewardPool = array(
    array("WTR159", "Majestic"), //Enlightened Strike
    array("WTR160", "Majestic"), // Tome of Fyendal
    array("WTR161", "Majestic"), // Last Ditch Effort
    array("WTR162", "Majestic"), // Crazy Brew - Super, but tagged as Majestic
    array("WTR163", "Majestic"), // Remembrance - Super, but tagged as Majestic
    array("WTR164", "Rare"), //Drone of Brutality
    array("WTR165", "Rare"),
    array("WTR166", "Rare"),
    array("WTR167", "Rare"), //Snatch
    array("WTR168", "Rare"),
    array("WTR169", "Rare"),
    array("WTR170", "Rare"), //Energy potion
    array("WTR171", "Rare"), //Potion of Strength
    array("WTR172", "Rare"), //Timesnap Potion
    array("WTR173", "Rare"), //Sigil of Solace - Nate thinks that these shouldn't be in the pool because they're too strong.
    array("WTR174", "Rare"), // Nate, if you're reading this, feel free to remove these from the pool!
    array("WTR175", "Rare"), // Sigil of Solace (Blue)
    array("WTR176", "Common"), //Barraging Brawnhide
    array("WTR177", "Common"),
    array("WTR178", "Common"),
    array("WTR179", "Common"), //Demolition Crew
    array("WTR180", "Common"),
    array("WTR181", "Common"),
    array("WTR182", "Common"), //Flock of the Feather Walkers
    array("WTR183", "Common"),
    array("WTR184", "Common"),
    array("WTR185", "Common"), //Nimble Strike
    array("WTR186", "Common"),
    array("WTR187", "Common"),
    array("WTR188", "Common"), //Raging Onslaught
    array("WTR189", "Common"),
    array("WTR190", "Common"),
    array("WTR191", "Common"), //Scar for a Scar - Notably reprinted in UPR, and omitted there
    array("WTR192", "Common"),
    array("WTR193", "Common"),
    array("WTR194", "Common"), //Scour the Battlescape
    array("WTR195", "Common"),
    array("WTR196", "Common"),
    array("WTR197", "Common"), //Regurgitating Slog
    array("WTR198", "Common"),
    array("WTR199", "Common"),
    array("WTR200", "Common"), //Wounded Bull
    array("WTR201", "Common"),
    array("WTR202", "Common"),
    array("WTR203", "Common"), //Wounding Blow
    array("WTR204", "Common"),
    array("WTR205", "Common"),
    array("WTR206", "Common"), //Pummel
    array("WTR207", "Common"),
    array("WTR208", "Common"),
    array("WTR209", "Common"), //Razor Reflex
    array("WTR210", "Common"),
    array("WTR211", "Common"),
    array("WTR212", "Common"), //Unmovable
    array("WTR213", "Common"),
    array("WTR214", "Common"),
    array("WTR215", "Common"), //Sink Below
    array("WTR216", "Common"),
    array("WTR217", "Common"),
    array("WTR218", "Common"), //Nimblism
    array("WTR219", "Common"),
    array("WTR220", "Common"),
    array("WTR221", "Common"), //Sloggism
    array("WTR222", "Common"),
    array("WTR223", "Common"),

    array("ARC159", "Majestic"), //Command and motherflippin' Conquer
    array("ARC160", "Majestic"), //Art of War
    array("ARC161", "Majestic"), //Pursuit of Knowledge
    array("ARC162", "Majestic"), //Chains of Eminence - Super, but tagged with Majestics
    //ARC163 - Rusted Relic - No interaction with arcane dmg
    array("ARC164", "Rare"), //Life for a life
    array("ARC165", "Rare"),
    array("ARC166", "Rare"),
    array("ARC167", "Rare"), //Enchanting Melody
    array("ARC168", "Rare"),
    array("ARC169", "Rare"),
    array("ARC170", "Rare"), //Plunder Run
    array("ARC171", "Rare"),
    array("ARC172", "Rare"),
    //ARC173-175 - Eirina's Prayer - No interaction with arcane dmg
    array("ARC176", "Common"), //Back Alley Breakline
    array("ARC177", "Common"),
    array("ARC178", "Common"),
    array("ARC179", "Common"), //Cadaverous Contraband
    array("ARC180", "Common"),
    array("ARC181", "Common"),
    array("ARC182", "Common"), //Fervent Forerunner
    array("ARC183", "Common"),
    array("ARC184", "Common"),
    array("ARC185", "Common"), //Moon Wish
    array("ARC186", "Common"),
    array("ARC187", "Common"),
    array("ARC188", "Common"), //Push the Point
    array("ARC189", "Common"),
    array("ARC190", "Common"),
    array("ARC191", "Common"), //Ravenous Rabble
    array("ARC192", "Common"),
    array("ARC193", "Common"),
    array("ARC194", "Common"), //Rifting
    array("ARC195", "Common"),
    array("ARC196", "Common"),
    array("ARC197", "Common"), //Vigor Rush
    array("ARC198", "Common"),
    array("ARC199", "Common"),
    array("ARC200", "Common"), //Fate Foreseen
    array("ARC201", "Common"),
    array("ARC202", "Common"),
    array("ARC203", "Common"), //Come to Fight
    array("ARC204", "Common"),
    array("ARC205", "Common"),
    array("ARC206", "Common"), //Force Sight
    array("ARC207", "Common"),
    array("ARC208", "Common"),
    array("ARC209", "Common"), //Lead the Charge
    array("ARC210", "Common"),
    array("ARC211", "Common"),
    array("ARC212", "Common"), //Sun Kiss
    array("ARC213", "Common"),
    array("ARC214", "Common"),
    array("ARC215", "Common"), //Whisper of the Oracle
    array("ARC216", "Common"),
    array("ARC217", "Common"),

    array("CRU180", "Majestic"), //Coax a Commotion
    array("CRU181", "Majestic"), //Gorganian Tome
    array("CRU182", "Majestic"), //Snag
    array("CRU183", "Rare"), //Promise of Plenty
    array("CRU184", "Rare"),
    array("CRU185", "Rare"),
    array("CRU186", "Rare"), //Lunging Press
    array("CRU187", "Rare"), //Springboard Assault
    array("CRU188", "Rare"), //Cash In
    array("CRU189", "Rare"), //Reinforce the Line
    array("CRU190", "Rare"),
    array("CRU191", "Rare"),
    array("CRU192", "Common"), //Brutal Assault
    array("CRU193", "Common"),
    array("CRU194", "Common"),

    array("MON245", "Majestic"), //Exude Confidence
    array("MON246", "Majestic"), //Nourishing Emptiness
    array("MON247", "Majestic"), //Rouse the Ancients
    array("MON248", "Rare"), //Out Muscle
    array("MON249", "Rare"),
    array("MON250", "Rare"),
    array("MON251", "Rare"), //Seek Horizon
    array("MON252", "Rare"),
    array("MON253", "Rare"),
    array("MON254", "Rare"), //Tremor of iArathael - Honestly this one we might want to take out? I'm leaving it in for now
    array("MON255", "Rare"),
    array("MON256", "Rare"),
    array("MON257", "Rare"), //Rise Above
    array("MON258", "Rare"),
    array("MON259", "Rare"),
    array("MON260", "Rare"), //Captain's Call
    array("MON261", "Rare"),
    array("MON262", "Rare"),
    array("MON263", "Common"), //Adrenaline Rush
    array("MON264", "Common"),
    array("MON265", "Common"),
    array("MON266", "Common"), //Belittle
    array("MON267", "Common"),
    array("MON268", "Common"),
    array("MON269", "Common"), //Brandish
    array("MON270", "Common"),
    array("MON271", "Common"),
    array("MON272", "Common"), //Frontline Scout
    array("MON273", "Common"),
    array("MON274", "Common"),
    array("MON275", "Common"), //Overload
    array("MON276", "Common"),
    array("MON277", "Common"),
    array("MON278", "Common"), //Pound for Pound
    array("MON279", "Common"),
    array("MON280", "Common"),
    array("MON281", "Common"), //Rally the Rearguard
    array("MON282", "Common"),
    array("MON283", "Common"),
    array("MON284", "Common"), //Stony Woottonhog - The unofficial Roguelike Mascot
    array("MON285", "Common"),
    array("MON286", "Common"),
    array("MON287", "Common"), //Surging Militia
    array("MON288", "Common"),
    array("MON289", "Common"),
    array("MON290", "Common"), //Yinti Yanti
    array("MON291", "Common"),
    array("MON292", "Common"),
    array("MON293", "Common"), //Zealous Belting
    array("MON294", "Common"),
    array("MON295", "Common"),
    array("MON296", "Common"), //Minnowism
    array("MON297", "Common"),
    array("MON298", "Common"),
    array("MON299", "Common"), //Warmonger's Recital
    array("MON300", "Common"),
    array("MON301", "Common"),
    //MON302 - Talisman of Dousing - No need for spellvoid
    array("MON303", "Common"), //Memorial Ground
    array("MON304", "Common"),
    array("MON305", "Common"),
    //
    array("EVR156", "Majestic"), //Bingo
    array("EVR157", "Majestic"), //Firebreathing
    array("EVR158", "Majestic"), //Cash Out
    array("EVR159", "Majestic"), //Knick Knack Bric-a-brac
    array("EVR160", "Majestic"), //This Round's on Me
    array("EVR161", "Rare"), //Life of the Party
    array("EVR162", "Rare"),
    array("EVR163", "Rare"),
    //EVR164-166 - High Striker - I've decided to omit this one, but if someone wants to add it in feel free
    array("EVR167", "Rare"), //Pick a Card, Any Card
    array("EVR168", "Rare"),
    array("EVR169", "Rare"),
    array("EVR156", "Rare"), //Smashing Good Time
    array("EVR157", "Rare"),
    array("EVR158", "Rare"),
    array("EVR159", "Rare"), //Even Bigger Than That
    array("EVR160", "Rare"),
    array("EVR161", "Rare"),
    //EVER176 through EVER193 are all potions.
    //I decided to omit all the potions for now, but feel free to add some/all

    array("UPR187", "Majestic"), //Erase Face
    array("UPR188", "Majestic"), //Vipox
    array("UPR189", "Majestic"), //That All You Got?
    array("UPR190", "Majestic"), //Fog Down
    array("UPR191", "Rare"), //Flex
    array("UPR192", "Rare"),
    array("UPR193", "Rare"),
    array("UPR194", "Rare"), //Fyendal's Fighting Spirit
    array("UPR195", "Rare"),
    array("UPR196", "Rare"),
    array("UPR197", "Rare"), //Sift
    array("UPR198", "Rare"),
    array("UPR199", "Rare"),
    array("UPR200", "Rare"), //Strategic Planning
    array("UPR201", "Rare"),
    array("UPR202", "Rare"),
    array("UPR203", "Common"), //Brothers in Arms
    array("UPR204", "Common"),
    array("UPR205", "Common"),
    array("UPR206", "Common"), //Critical Strike
    array("UPR207", "Common"),
    array("UPR208", "Common"),
    //UPR209-UPR211 Scar for a Scar - Reprinted from WTR
    array("UPR212", "Common"), //Trade In
    array("UPR213", "Common"),
    array("UPR214", "Common"),
    array("UPR215", "Common"), //Healing Balm
    array("UPR216", "Common"),
    array("UPR217", "Common"),
    array("UPR218", "Common"), //Sigil of Protection
    array("UPR219", "Common"),
    array("UPR220", "Common"),
    array("UPR221", "Common"), //Oasis Respite
    array("UPR222", "Common"),
    array("UPR223", "Common"),

    array("DYN240", "Majestic"), //Imperial Edict
    array("DYN241", "Majestic"), //Imperial Ledger
    array("DYN242", "Majestic"), //Imperial Warhorn
  );

  return ProcessPool($CardRewardPool, $arrayParameters);
}
function GetPoolTalent($arrayParameters){

  $CardRewardPool = array(
    array("ELE092", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Flashfreeze
    array("ELE097", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Entwine Ice
    array("ELE098", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE099", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE100", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Entwine Lightning
    array("ELE101", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE102", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE103", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Invigorate
    array("ELE104", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE105", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE106", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Rejuvenate
    array("ELE107", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE108", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE112", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Pulse of Volthaven
    array("ELE146", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Channel Lake Frigid
    array("ELE147", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Blizzard
    array("ELE148", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Frost Fang
    array("ELE149", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE150", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE151", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Ice Quake
    array("ELE152", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE153", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE154", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Weave Ice
    array("ELE155", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE156", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE157", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Icy Encounter
    array("ELE158", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE159", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE160", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Winter's Grasp
    array("ELE161", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE162", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE163", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Chill to the Bone
    array("ELE164", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE165", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE166", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Polar Blast
    array("ELE167", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE168", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE169", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Winter's Bite
    array("ELE170", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE171", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE172", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Amulet of Ice
    array("ELE175", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Channel Thunder Steppe
    array("ELE176", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Blink
    array("ELE177", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Flash
    array("ELE178", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE179", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE180", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Weave Lightning
    array("ELE181", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE182", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE183", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Lightning Press
    array("ELE184", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE185", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE186", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Ball Lightning
    array("ELE187", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE188", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE189", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Lightning Surge
    array("ELE190", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE191", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE192", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Heaven's Claws
    array("ELE193", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE194", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE195", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Shock Striker
    array("ELE196", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE197", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE198", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Electrify
    array("ELE199", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE200", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("ELE201", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Amulet of Lightning

    array("UPR138", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Channel the Bleak Expanse
    array("UPR139", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Hypothermia
    array("UPR140", "Talent", "Majestic", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Insidious Chill
    array("UPR141", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Isenhowl Weathervane
    array("UPR142", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("UPR143", "Talent", "Rare", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("UPR144", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Arctic Incarceration
    array("UPR145", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("UPR146", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("UPR147", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"), //Cold Snap
    array("UPR148", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),
    array("UPR149", "Talent", "Common", "Shiver", "Voltaire", "DeathDealer", "RedLiner"),

    array("UPR086", "Talent", "Majestic", "Emberblade", "Kodachi", "Edge"), //Thaw
    array("UPR087", "Talent", "Majestic", "Emberblade", "Kodachi", "Edge"), //Liquify
    array("UPR088", "Talent", "Majestic", "Emberblade", "Kodachi", "Edge"), //Uprising
    array("UPR089", "Talent", "Majestic", "Emberblade", "Kodachi", "Edge"), //Tome of Firebrand
    array("UPR090", "Talent", "Rare", "Emberblade", "Kodachi", "Edge"), //Red Hot
    array("UPR091", "Talent", "Rare", "Emberblade", "Kodachi", "Edge"), //Rise Up
    array("UPR092", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Blaze Headlong
    array("UPR093", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Breaking Point
    array("UPR094", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Burn Away
    array("UPR095", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Flameborn Retribution
    array("UPR096", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Flamecall Awakening
    array("UPR097", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Inflame
    array("UPR098", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Lava Burst
    array("UPR099", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Searing Touch
    array("UPR100", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Stoke the Flames
    array("UPR101", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Phoenix Flame
    array("UPR101", "Talent", "Common", "Emberblade", "Kodachi", "Edge"), //Phoenix Flame - Putting it in twice so it has a higher rate to be pulled in card rewards

  );

  return ProcessPool($CardRewardPool, $arrayParameters);
}

//TODO: Add slot (head, chest etc)
function GetPoolEquipment($arrayParameters){
  $CardRewardPool = array(
  array("WTR041", "Bravo", "Legendary", "Chest"), //Tectonic Plating
  array("WTR042", "Bravo", "Legendary", "Head"), //Helm of Isen's Peak
  array("WTR079", "Fai", "Legendary", "Head"), //Mask of Momentum
  array("WTR080", "Fai", "Common", "Legs"), //Breaking Scales
  array("WTR116", "Dorinthea", "Legendary", "Arms"), //Braveforge Bracers
  array("WTR117", "Dorinthea", "Common", "Legs"), //Refraction Bolters
  array("WTR150", "Generic", "Legendary", "Chest"), //Fyendal's Spring Tunic
  array("WTR151", "Generic", "Common", "Head"), //Hope Merchant's Hood
  array("WTR152", "Generic", "Common", "Chest"), //Heartened Cross Strap
  array("WTR153", "Generic", "Common"), //Goliath Gauntlet
  array("WTR154", "Generic", "Common", "Legs"), //Snapdragon Scalers
  array("WTR155", "Generic", "Common", "Head"), //Ironrot Helm
  //array("WTR156", "Generic", "Common", "Chest"), //Ironrot Chest - Omitted due to being included in universal equipment
  array("WTR157", "Generic", "Common", "Arms"), //Ironrot Gauntlets
  array("WTR158", "Generic", "Common", "Legs"), //Ironrot Boots

  array("ARC041", "Lexi", "Legendary", "Head"), //Skullbone Crosswrap
  array("ARC042", "Lexi", "Common"), //Bull's Eye Bracers
  array("ARC150", "Generic", "Legendary", "Head"), //Arcanite Skullcap
  array("ARC151", "Generic", "Common", "Head"), //Talismanic Lens
  array("ARC152", "Generic", "Common", "Chest"), //Vest of the First Fist
  array("ARC153", "Generic", "Common", "Arms"), //Bracers of Belief
  array("ARC154", "Generic", "Common", "Legs"), //Mage Master Boots
  //ARC155 - 158 Nullrune Boots omitted due to being included in universal equipment

  array("CRU025", "Bravo", "Majestic", "Arms"), //Crater Fist
  array("CRU053", "Fai", "Majestic", "Legs"), //Breeze Rider Boots
  array("CRU081", "Dorinthea", "Majestic", "Chest"), //Courage of Bladehold
  array("CRU122", "Lexi", "Majestic", "Legs"), //Perch Grapplers
  //CRU179 - Omitted due to irrelevance... though there's definitely a world where this is relevant, though maybe not playable. 

  array("MON107", "Dorinthea", "Legendary", "Legs"), //Valiant Dynamo
  array("MON108", "Dorinthea", "Common", "Arms"), //Gallantry Gold
  array("MON238", "Generic", "Common", "Chest"), //Blood Drop Brocade
  array("MON239", "Generic", "Common", "Arms"), //Stubby Hammerers
  array("MON240", "Generic", "Common", "Legs"), //Time Skippers
  array("MON241", "Generic", "Common", "Head"), //Ironhide Helm
  array("MON242", "Generic", "Common", "Chest"),
  array("MON243", "Generic", "Common", "Arms"), 
  array("MON244", "Generic", "Common", "Legs"), //Ironhide Boots

  array("ELE144", "Lexi", "Legendary", "Chest"), //Heart of Ice
  array("ELE145", "Lexi", "Common", "Chest"), //Coat of Frost
  array("ELE173", "Lexi", "Legendary", "Arms"), //Shock Charmers
  array("ELE174", "Lexi", "Common", "Arms"), //Mark of Lightning
  array("ELE203", "Bravo", "Legendary", "Offhand"), //Rampart of the Ram's Head
  array("ELE204", "Bravo", "Common", "Offhand"), //Rotten Old Buckler
  array("ELE213", "Lexi", "Legendary", "Head"), //New Horizon
  array("ELE214", "Lexi", "Common", "Head"), //Honing Hood
  array("ELE233", "Generic", "Common", "Head"), //Ragamuffin's Hat
  array("ELE234", "Generic", "Common", "Chest"), //Deep Blue
  array("ELE235", "Generic", "Common"), //Cracker Jax
  array("ELE236", "Generic", "Common", "Legs"), //Runaways

  array("EVR020", "Bravo", "Majestic", "Chest"), //Earthlore Bounty
  array("EVR037", "Fai", "Majestic", "Head"), //Mask of the Pouncing Lynx
  array("EVR053", "Dorinthea", "Majestic", "Head"), //Helm of Sharp Eye
  //EVR155 - Arcane Lantern (RARE) - omitted for now. I want to be able to tag the diff between Equips that interact with Arcane and those that don't before I implement the arcane ones.

  array("UPR047", "Fai", "Common", "Arms"), //Heat Wave
  array("UPR084", "Fai", "Legendary", "Chest"), //Flamescale Furnace
  array("UPR085", "Fai", "Common", "Chest"), //Sash of Sandikai
  array("UPR136", "Lexi", "Legendary", "Head"), //Coronet Peak
  array("UPR137", "Lexi", "Common", "Head"), //Glacial Horns
  array("UPR158", "Fai", "Legendary", "Arms"), //Tiger Stripe Shuko
  array("UPR159", "Fai", "Common", "Legs"), //Tide Flippers
  array("UPR182", "Generic", "Legendary", "Head"), //Crown of Providence
  array("UPR183", "Generic", "Common", "Head"), //Heliod's Mitre - Okay, not technically a common, but I'm okay with it going in the common pool if you are *wink*
  array("UPR184", "Generic", "Common", "Chest"), //Quelling Robe
  array("UPR185", "Generic", "Common", "Arms"), //Quelling Sleeves
  array("UPR186", "Generic", "Common", "Legs"), //Quelling Slippers

  array("DYN026", "Bravo", "Majestic", "Offhand"), //Seasoned Saviour
  array("DYN027", "Bravo", "Rare", "Offhand"), //Steelbraid Buckler
  array("DYN045", "Fai", "Majestic", "Chest"), //Blazing Yoroi
  array("DYN046", "Fai", "Rare", "Arms"), //Tearing Shuko
  array("DYN152", "Lexi", "Rare", "Arms"), //Hornet's Sting
  //DYN236 thru 29 - Spellfray equipment. I do want to put these in the pool, but I'd like to tag them as arcane first and put them in my 2nd draft
  array("DYN234", "Generic", "Legendary", "Head"), //Crown of Dominion
  array("DYN235", "Generic", "Rare", "Offhand") //Ornate Tessen
  );

  return ProcessPool($CardRewardPool, $arrayParameters);
}

function ProcessPool($CardRewardPool, $arrayOfParameters){

$arrayOfParameters = array_filter($arrayOfParameters);
$arrayOfParameters = array_values($arrayOfParameters);
$returnPool = array(); // Create an empty list of cards to be returned
$sizeParameters = count($arrayOfParameters);
$paramCheck = new SplFixedArray($sizeParameters); //Create a shadow of the parameters...
for ($i = 0; $i < $sizeParameters; $i++){ //... The same length as the list of parameters
  $paramCheck[$i] = false;
}
$eligible = true;
for($i = 0; $i < count($CardRewardPool); $i++){
  $eligible = true;
  for($j = 0; $j < $sizeParameters; $j++){
    $paramCheck[$j] = false;
  }
  for($j = 0; $j < $sizeParameters; $j++){
    for($k = 1; $k < count($CardRewardPool[$i]); $k++){
      if($arrayOfParameters[$j] == $CardRewardPool[$i][$k]){
        $paramCheck[$j] = true;
      }
    }
    if($paramCheck[$j] == false){
      $eligible = false;
      break;
    }
  }
  if($eligible) {
    array_push($returnPool, $CardRewardPool[$i][0]);
  }
}
  return $returnPool;
}

function ArrayAsString($arrayToBeStringed){
  $outString = "";
  for($i = 0; $i < count($arrayToBeStringed); $i++){
    if($i != 0) $outString .= ", ";
    $outString .= $arrayToBeStringed[$i];
  }
  return $outString;
}
