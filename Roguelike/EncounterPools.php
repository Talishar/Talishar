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
*/


function GetPool($type, $hero, $rarity, $background){
  WriteLog($type." ".$rarity." ".$background);
  if(($hero == "Bravo" || $hero == "Dorinthea") && $type == "Talent") $type = "Class";
  return GetPool2(array($type, $rarity, $background));
  /*
  if($type == "Light")
  {
    switch($rarity)
    {
      case "Common": return array();
      case "Rare": return array();
      case "Majestic": return array();
    }
  }
  if($type == "Shadow")
  {
    switch($rarity)
    {
      case "Common": return array();
      case "Rare": return array();
      case "Majestic": return array();
    }
  }
  if($type == "Earth")
  {
    switch($rarity)
    {
      case "Common": return array("ELE128", "ELE129", "ELE130", "ELE131", "ELE132", "ELE133", "ELE134", "ELE135", "ELE136", "ELE137", "ELE138", "ELE139");
      case "Rare": return array("ELE119", "ELE120", "ELE121");
      case "Majestic": return array("ELE117", "ELE118");
    }
  }
  if($type == "Ice")
  {
    switch($rarity)
    {
      case "Common": return array("ELE160", "ELE161", "ELE162", "ELE166", "ELE167", "ELE168", "ELE169", "ELE170", "ELE171", "UPR147", "UPR148", "UPR149");
      case "Rare": return array("ELE148", "ELE149", "ELE150", "ELE151", "ELE152", "ELE153");
      case "Majestic": return array("ELE147", "UPR138", "UPR139");
    }
  }
  if($type == "Lightning")
  {
    switch($rarity)
    {
      case "Common": return array("ELE189", "ELE190", "ELE191", "ELE192", "ELE193", "ELE194", "ELE195", "ELE196", "ELE197", "ELE198", "ELE199", "ELE200");
      case "Rare": return array("ELE177", "ELE178", "ELE179", "ELE181", "ELE182", "ELE183");
      case "Majestic": return array("ELE175", "ELE176");
    }
  }
  if($type == "Draconic")
  {
    switch($rarity)
    {
      case "Common":
      case "Rare": return array("UPR092", "UPR093", "UPR094", "UPR095", "UPR096", "UPR097", "UPR098", "UPR099", "UPR100");
      case "Majestic": return array("UPR086", "UPR087", "UPR088", "UPR089");
    }
  }
  switch($hero)
  {
    case "Dorinthea":
      {
        switch($type)
        {
          case "Class":
            {
              switch($background)
              {
                case "Saber":
                  switch($rarity)
                  {
                    case "Common": return array(
                      "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
                      "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
                      "MON116", "MON117", "MON118",
                      "EVR060", "EVR061", "EVR062", "EVR063", "EVR064", "EVR065", "EVR066", "EVR067", "EVR068",
                      "DVR009",
                      "DYN079", "DYN080", "DYN081", "DYN085", "DYN086", "DYN087"
                    );
                    case "Rare": return array(
                      "WTR123", "WTR124", "WTR125", "WTR126", "WTR127", "WTR128", "WTR129", "WTR130", "WTR131",
                      "CRU085", "CRU086", "CRU087",
                      "MON110", "MON111", "MON112", "MON113", "MON114", "MON115",
                      "EVR057", "EVR058", "EVR059",
                      "DVR013",
                      "DYN073", "DYN074", "DYN075", "DYN076", "DYN077", "DYN078"
                    );
                    case "Majestic": return array(
                      "WTR118", "WTR119", "WTR120", "WTR121", "WTR122",
                      "CRU082", "CRU083", "CRU084",
                      "EVR056",
                      "DYN072"
                    );
                  }
                case "Dawnblade":
                  switch($rarity)
                  {
                    case "Common": return array(
                      "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
                      "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
                      "MON116", "MON117", "MON118",
                      "EVR063", "EVR064", "EVR065",
                      "DVR009",
                      "DYN079", "DYN080", "DYN081", "DYN085", "DYN086", "DYN087"
                    );
                    case "Rare": return array(
                      "WTR123", "WTR124", "WTR125", "WTR126", "WTR127", "WTR128", "WTR129", "WTR130", "WTR131",
                      "CRU085", "CRU086", "CRU087",
                      "MON110", "MON111", "MON112", "MON113", "MON114", "MON115",
                      "EVR057", "EVR058", "EVR059",
                      "DVR013",
                      "DYN073", "DYN074", "DYN075", "DYN076", "DYN077", "DYN078"
                    );
                    case "Majestic": return array(
                      "WTR118", "WTR119", "WTR120", "WTR121", "WTR122",
                      "CRU082", "CRU083", "CRU084",
                      "EVR054", "EVR056",
                      "DVR008",
                      "DYN072"
                    );
                  }
                case "Hatchet":
                  switch($rarity)
                  {
                    case "Common": return array(
                      "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
                      "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
                      "MON116", "MON117", "MON118",
                      "EVR060", "EVR061", "EVR062", "EVR063", "EVR064", "EVR065", "EVR066", "EVR067", "EVR068",
                      "DVR009",
                      "DYN082", "DYN083", "DYN084"
                    );
                    case "Rare": return array(
                      "WTR123", "WTR124", "WTR125", "WTR126", "WTR127", "WTR128", "WTR129", "WTR130", "WTR131",
                      "CRU085", "CRU086", "CRU087",
                      "MON110", "MON111", "MON112", "MON113", "MON114", "MON115",
                      "DYN073", "DYN074", "DYN075"
                    );
                    case "Majestic": return array(
                      "WTR118", "WTR119", "WTR120", "WTR121", "WTR122",
                      "CRU083", "CRU084",
                      "MON109",
                      "EVR056",
                      "DYN071"
                    );
                  }
                case "Battleaxe":
                  switch($rarity)
                  {
                    case "Common": return array(
                      "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
                      "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
                      "MON116", "MON117", "MON118",
                      "EVR063", "EVR064", "EVR065",
                      "DVR009",
                      "DYN082", "DYN083", "DYN084"
                    );
                    case "Rare": return array(
                      "WTR123", "WTR124", "WTR125", "WTR126", "WTR127", "WTR128", "WTR129", "WTR130", "WTR131",
                      "CRU085", "CRU086", "CRU087",
                      "MON110", "MON111", "MON112", "MON113", "MON114", "MON115",
                      "DYN073", "DYN074", "DYN075"
                    );
                    case "Majestic": return array(
                      "WTR118", "WTR119", "WTR120", "WTR121", "WTR122",
                      "CRU083", "CRU084",
                      "MON109",
                      "EVR054", "EVR056",
                      "DYN071"
                    );
                  }
              }
            }
          case "Talent": return GetPool("Class", $hero, $rarity, $background);
          case "Generic":
            {
              switch($rarity)
              {
                case "Common": return array(
                  "WTR203", "WTR204", "WTR205", "WTR209", "WTR210", "WTR212", "WTR213", "WTR214",
                  "ARC182", "ARC183", "ARC184", "ARC191", "ARC192", "ARC193",
                  "MON269", "MON270", "MON271",
                  "DVR014", "DVR023"
                );
                case "Rare": return array(
                  "WTR167", "WTR166", "WTR165", "WTR170", "WTR172", "WTR173", "WTR174", "WTR175",
                  "ARC164", "ARC165", "ARC163", "ARC170", "ARC171", "ARC172",
                  "CRU183", "CRU184", "CRU185"
                );
                case "Majestic": return array(
                  "WTR159", "WTR160",
                  "ARC159",
                  "CRU180", "CRU181", "CRU182",
                  "MON245", "MON246",
                  "EVR156", "EVR157", "EVR160",
                  "UPR187", "UPR188", "UPR189", "UPR190"
                );
              }
            }
        }
      }
    case "Bravo":
      switch ($type) {
        case "Class":
          switch ($background) {
            case "Anothos":
              switch($rarity){
                case "Common": return array(
                  "WTR057", "WTR058", "WTR059", "WTR063", "WTR064", "WTR065", "WTR066", "WTR067", "WTR068", "WTR069", "WTR070", "WTR071", "WTR072", "WTR073", "WTR074",
                  "CRU032", "CRU033", "CRU034", "CRU035", "CRU036", "CRU037", "CRU038", "CRU039", "CRU040", "CRU041", "CRU041", "CRU043",
                  "ELE209", "ELE210", "ELE211",
                  "EVR024", "EVR025", "EVR026", "EVR027", "EVR028", "EVR029", "EVR030", "EVR031", "EVR032", "EVR033", "EVR034", "EVR035",
                );
                case "Rare": return array(
                  "WTR045", "WTR046", "WTR048", "WTR049", "WTR050", "WTR051", "WTR054", "WTR055", "WTR056",
                  "CRU029", "CRU030", "CRU031",
                  "ELE206", "ELE207", "ELE208",
                  "DYN033", "DYN034", "DYN035"
                );
                case "Majestic": return array(
                  "WTR043", "WTR044", "WTR047",
                  "CRU026", "CRU027", "CRU028",
                  "DYN028", "DYN029",
                  "EVR000", "EVR021", "EVR022", "EVR023",
                );
              }
            case "TitanFist":
              //Off-hand buffers are added to this pool.
              switch($rarity){
                case "Common": return array(
                  "WTR057", "WTR058", "WTR059", "WTR063", "WTR064", "WTR065", "WTR066", "WTR067", "WTR068", "WTR069", "WTR070", "WTR071", "WTR072", "WTR073", "WTR074",
                  "CRU032", "CRU033", "CRU034", "CRU035", "CRU036", "CRU037", "CRU038", "CRU039", "CRU040", "CRU041", "CRU041", "CRU043",
                  "ELE209", "ELE210", "ELE211",
                  "EVR024", "EVR025", "EVR026", "EVR027", "EVR028", "EVR029", "EVR030", "EVR031", "EVR032", "EVR033", "EVR034", "EVR035",
                  "DYN036", "DYN037", "DYN038", "DYN039", "DYN040", "DYN041", "DYN042", "DYN043", "DYN044"
                );
                case "Rare": return array(
                  "WTR045", "WTR046", "WTR048", "WTR049", "WTR050", "WTR051", "WTR054", "WTR055", "WTR056",
                  "CRU029", "CRU030", "CRU031",
                  "ELE206", "ELE207", "ELE208",
                  "DYN030", "DYN031", "DYN032", "DYN033", "DYN034", "DYN035"
                );
                case "Majestic": return array(
                  "WTR043", "WTR044", "WTR047",
                  "CRU026", "CRU027", "CRU028",
                  "DYN028", "DYN029",
                  "EVR000", "EVR021", "EVR022", "EVR023"
                );
              }
            case "Sledge":
              //This should be the same as Anothos, at least for now
              return GetPool("Class", "Bravo", $rarity, "Anothos");
          }
        case "Talent":
          return(GetPool("Class", $hero, $rarity, $background));
        case "Generic":
            switch($rarity)
              {
                case "Common": return array(
                  "WTR203", "WTR204", "WTR205", "WTR206", "WTR207", "WTR208", "WTR212", "WTR213", "WTR214",
                  "ARC182", "ARC183", "ARC184", "ARC191", "ARC192", "ARC193",
                  "MON269", "MON270", "MON271",
                  "DVR014", "DVR023"
                );
                case "Rare": return array(
                  "WTR167", "WTR166", "WTR165", "WTR170", "WTR172", "WTR173", "WTR174", "WTR175",
                  "ARC164", "ARC165", "ARC163", "ARC170", "ARC171", "ARC172",
                  "CRU183", "CRU184", "CRU185"
                );
                case "Majestic": return array(
                  "WTR159", "WTR160",
                  "ARC159",
                  "CRU180", "CRU181", "CRU182",
                  "MON245", "MON246",
                  "EVR156", "EVR157", "EVR160",
                  "UPR187", "UPR188", "UPR189", "UPR190"
                );
              }
      }
  } */
}

//Input a list of parameters
function GetPool($arrayParameters){

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
    array("WTR149", "Class", "Common", "Saber", "Dawnblade", "Hatchet", "Battleaxe")
    

  );
  
  $returnPool = array(); // Create an empty list of cards to be returned
  $sizeParameters = count($arrayParameters);
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
        if($arrayParameters[$j] == $CardRewardPool[$i][$k]){
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
    WriteLog(ArrayAsString($returnPool));
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

?>
