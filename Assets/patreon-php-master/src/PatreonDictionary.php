<?php

enum ContentCreators : string
{
  case InstantSpeed = "0";
  case ManSant = "731";

  public function SessionName(): string
  {
    switch($this->value)
    {
      case "0": return "isInstantSpeedPatron";
      case "731": return "isManSantPatron";
      default: return "";
    }
  }

  public function PatreonLink(): string
  {
    switch($this->value)
    {
      case "0": return "https://www.patreon.com/instantspeedpod";
      case "731": return "https://www.patreon.com/ManSant";
      default: return "";
    }
  }

  public function ChannelLink(): string
  {
    switch($this->value)
    {
      case "0": return "https://www.youtube.com/playlist?list=PLIo1KFShm1L3e91QrlPG6ZdwfmqKk0NIP";
      case "731": return "https://www.youtube.com/@ManSantFaB";
      default: return "";
    }
  }

  public function BannerURL(): string
  {
    switch($this->value)
    {
      case "0": return "./Assets/patreon-php-master/assets/ContentCreatorImages/InstantSpeedBanner.webp";
      default: return "";
    }
  }

  public function HeroOverlayURL($heroID): string
  {
    switch($this->value)
    {
      case "0": //WatchFlake
        if(CardClass($heroID) == "GUARDIAN") return "./Assets/patreon-php-master/assets/ContentCreatorImages/Matt_anathos_overlay.webp";
        return "./Assets/patreon-php-master/assets/ContentCreatorImages/flakeOverlay.webp";
      case "731":
        return "./Assets/patreon-php-master/assets/ContentCreatorImages/ManSantLevia.webp";
      default: return "";
    }
  }

  public function NameColor(): string
  {
    switch($this->value)
    {
      case "0": return "rgb(2,190,253)";
      case "731": return "rgb(255,53,42)";
      default: return "";
    }
  }
}

enum PatreonCampaign : string
{
  //These ones have no patreon
  case Pummelowanko = "0";
  case DragonShieldProTeamWB = "1";
  case AscentGaming = "2";
  case EternalOracles = "3";
  case Luminaris = "4";
  case FABLAB = "5";
  case OnHit = "6";
  case SecondCycle = "7";
  case SonicDoom = "8";
  case TeamPummel = "9";
  case Talishar = "7198186";
  case PushThePoint = "7579026";
  case GoAgainGaming = "7329070";
  case RedZoneRogue = "1787491";
  case Fabrary = "8997252";
  case ManSant = "8955846";
  case AttackActionPodcast = "6839952";
  case ArsenalPass = "7285727";
  case TheTekloFoundry = "8635931";
  case FleshandCommonBlood = "8736344";
  case SinOnStream = "7593240";
  case FreshandBuds = "8458487";
  case Sloopdoop = "6996822";
  case DMArmada = "1919413";
  case InstantSpeedPodcast = "10651350";
  case TheCardGuyz = "7733166";
  case HomeTownTCG = "7009853";
  case FleshAndPod = "8338817";
  case Kappolo = "9361474";
  case LibrariansOfSolana = "3828539";
  case TheMetrixMetagame = "8951973";
  case TheTablePit = "9370276";
  case TCGTed = "9404423";
  case CardAdvantage = "8040288";
  case RavenousBabble = "10147920";
  case BlackWingStudios = "4006023";
  case OnHitEffect = "10811477";
  case DaganWhite = "9851977";
  case BrandaoTCG = "279086";
  case OffTheRailsTCG = "11184392";
  case Nxi = "11481720";
  case PvtVoid = "9408649";

  public function SessionID(): string
  {
    switch($this->value)
    {
      case "7198186": return "isPatron";
      case "7579026": return "isPtPPatron";
      case "7329070": return "isGoAgainGamingPatron";
      case "1787491": return "isRedZoneRoguePatron";
      case "8997252": return "isFabraryPatron";
      case "8955846": return "isManSantPatron";
      case "6839952": return "isAttackActionPodcastPatreon";
      case "7285727": return "isArsenalPassPatreon";
      case "8635931": return "isTheTekloFoundryPatreon";
      case "8736344": return "isFleshAndCommonBloodPatreon";
      case "7593240": return "isSinOnStreamPatreon";
      case "8458487": return "isFreshAndBudsPatreon";
      case "6996822": return "isSloopdoopPatron";
      case "1919413": return "isDMArmadaPatron";
      case "10651350": return "isInstantSpeedPatron";
      case "7733166": return "isTheCardGuyzPatron";
      case "7009853": return "isHomeTownTCGPatron";
      case "8338817": return "isFleshAndPodPatron";
      case "9361474": return "isKappoloPatron";
      case "3828539": return "isLibrariansOfSolanaPatron";
      case "8951973": return "isTheMetrixMetagamePatron";
      case "9370276": return "isTheTablePitPatron";
      case "9404423": return "isTCGTedPatron";
      case "8040288": return "isCardAdvantagePatron";
      case "10147920": return "isRavenousBabblePatron";
      case "4006023": return "isBlackWingStudiosPatron";
      case "10811477": return "isOnHitEffectPatron";
      case "9851977": return "isDaganWhitePatron";
      case "279086": return "isBrandaoTCGPatron";
      case "11184392": return "isOffTheRailsTCGPatron";
      case "11481720": return "isNxiPatron";
      case "9408649": return "isPvtVoidPatron";
      default: return "";
    }
  }

  public function CampaignName(): string
  {
    switch($this->value)
    {
      case "0": return "Pummelowanko";
      case "1": return "Dragon Shield Pro Team";
      case "2": return "AscentGaming";
      case "3": return "Eternal Oracles";
      case "4": return "Luminaris";
      case "5": return "FAB-LAB";
      case "6": return "OnHit";
      case "7": return "Second Cycle";
      case "8": return "Sonic Doom";
      case "9": return "Pummel 52100";
      case "7198186": return "Talishar";
      case "7579026": return "Push the Point";
      case "7329070": return "Go Again Gaming";
      case "1787491": return "Red Zone Rogue";
      case "8997252": return "Fabrary";
      case "8955846": return "Man Sant";
      case "6839952": return "Attack Action Podcast";
      case "7285727": return "Arsenal Pass";
      case "8635931": return "The Teklo Foundry";
      case "8736344": return "Flesh and Common Blood";
      case "7593240": return "Sin On Stream";
      case "8458487": return "Fresh and Buds";
      case "6996822": return "Sloopdoop";
      case "1919413": return "DM Armada";
      case "10651350": return "Instant Speed Podcast";
      case "7733166": return "The Card Guyz";
      case "7009853": return "HomeTownTCG";
      case "8338817": return "Flesh And Pod";
      case "9361474": return "Kappolo";
      case "3828539": return "Librarians of Solana";
      case "8951973": return "The Metrix Metagame";
      case "9370276": return "The Table Pit";
      case "9404423": return "TCG Ted";
      case "8040288": return "Card Advantage";
      case "10147920": return "Ravenous Babble";
      case "4006023": return "Black Wing Studios";
      case "10811477": return "On Hit Effect";
      case "9851977": return "Dagan White";
      case "279086": return "BrandaoTCG";
      case "11184392": return "Off the Rails TCG";
      case "11481720": return "Nxi";
      case "9408649": return "PvtVoid";
      default: return "";
    }
  }

  public function IsTeamMember($userName): string
  {
    switch($this->value)
    {
      case "0": return ($userName == "MrShub" || $userName == "duofanel" || $userName == "Matiisen" ||  $userName == "Pepowski" ||  $userName == "Seba_stian" ||  $userName == "NatAlien" ||  $userName == "dvooyas" || $userName == "Lukashu" || $userName == "Qwak" || $userName == "NatAlien");
      case "1": return ($userName == "TwitchTvFabschool" || $userName == "MattRogers" || $userName == "TariqPatel");
      case "2": return ($userName == "hometowntcg" || $userName == "ProfessorKibosh" || $userName == "criticalclover8" || $userName == "bomberman" || $userName == "woodjp64" || $userName == "TealWater" || $userName == "Bravosaur" || $userName == "DaganTheZookeeper" || $userName == "Dratylis" || $userName == "MoBogsly");
      case "3": return ($userName == "DeadSummer");
      case "4": return ($userName == "LeoLeo");
      case "5": return ($userName == "XIR");
      case "6": return ($userName == "wackzitt" || $userName == "RainyDays" || $userName == "HelpMeJace2" || $userName == "S1lverback55" || $userName == "VexingTie" || $userName == "Ragnell");
      case "7": return IsTeamSecondCycle($userName);
      case "8": return IsTeamSonicDoom($userName);
      case "9": return IsTeamPummel($userName);
      case "7198186": return ($userName == "OotTheMonk" || $userName == "PvtVoid");
      case "7285727": return ($userName == "PvtVoid");
      case "7579026": return ($userName == "Hamsack" || $userName == "BigMedSi" || $userName == "Tripp");
      case "7329070": return ($userName == "GoAgainGamingAz");
      case "1787491": return ($userName == "RedZoneRogue");
      case "8997252": return ($userName == "phillip");
      case "8955846": return ($userName == "Man_Sant");
      case "6839952": return ($userName == "chonigman" || $userName == "Ijaque");
      case "7285727": return ($userName == "Brendan" || $userName == "TheClub");
      case "8635931": return ($userName == "TheTekloFoundry");
      case "8736344": return ($userName == "Smithel");
      case "7593240": return ($userName == "SinOnStream");
      case "8458487": return ($userName == "FreshLord");
      case "6996822": return ($userName == "Sloopdoop");
      case "1919413": return ($userName == "DMArmada");
      case "10651350": return ($userName == "Flake" || $userName == "OotTheMonk");
      case "7733166": return ($userName == "NamVoTCGz" || $userName == "AlexTheCardGuy" || $userName == "RegularDegular" || $userName == "joshlau7" || $userName == "WillyB" || $userName == "Spoofy" || $userName == "ItsSebBruh" || $userName == "Knight");
      case "7009853": return ($userName == "hometowntcg");
      case "8338817": return ($userName == "imjorman" || $userName == "ADavis83" || $userName == "loganpetersen");
      case "9361474": return ($userName == "kappolo");
      case "3828539": return ($userName == "Tee");
      case "8951973": return ($userName == "Wes" || $userName == "Brandon");
      case "9370276": return ($userName == "TheTablePitYT" || $userName == "TunaTCG");
      case "9404423": return ($userName == "TCGTed");
      case "8040288": return IsTeamCardAdvantage($userName);
      case "10147920": return ($userName == "RavenousBabble" || $userName == "Arty" || $userName == "jargowsky" || $userName == "Nick52cost" || $userName == "Boomerang" || $userName == "matthias" || $userName == "Repas801");
      case "4006023": return ($userName == "BlackWingStudio");
      case "10811477": return ($userName == "Mezzo");
      case "9851977": return ($userName == "DaganTheZookeeper" || $userName == "OotTheMonk");
      case "279086": return ($userName == "brandaotcg" || $userName == "OotTheMonk");
      case "11184392": return ($userName == "PatSmashGood" || $userName == "OotTheMonk");
      case "11481720": return ($userName == "nxi");
      case "9408649": return ($userName == "PvtVoid");
      default: return "";
    }
  }

  public function AltArts(): string
  {
    switch($this->value)
    {
      case "10651350": return "WTR038=WTR038-InstantSpeed";
      case "8955846": return "MON119=MON119-ManSant,MON120=MON120-ManSant";
      case "279086": return "ARC001=ARC001-Brandao,ARC113=ARC113-Brandao,CRU077=CRU077-Brandao,ELE031=ELE031-Brandao,OUT091=OUT091-Brandao,UPR001=UPR001-Brandao,WTR038=WTR038-Brandao,ARC003=ARC003-Brandao,CRU079=CRU079-Brandao,CRU080=CRU080-Brandao,CRU197=CRU197-Brandao,ELE111=ELE111-Brandao,UPR003=UPR003-Brandao,UPR042=UPR042-Brandao,UPR043=UPR043-Brandao,WTR040=WTR040-Brandao,WTR075=WTR075-Brandao,ARC112=ARC112_Brandao,CRU049=CRU049_Brandao,DTD134=DTD134_Brandao,DTD135=DTD135_Brandao,EVO004=EVO004_Brandao,EVO006=EVO006_Brandao,EVO234=EVO234_Brandao,WTR076=WTR076_Brandao,WTR078=WTR078_Brandao";
      case "7198186": return "WTR173=WTR173-T,ARC159=ARC159-T,EVR008=EVR008-T,EVR009=EVR009-T,EVR010=EVR010-T,EVR105=EVR105-T,MON245=MON245-T,UPR187=UPR187-T, WTR001=WTR001-T,WTR002=WTR002-T,WTR098=WTR098-T,WTR099=WTR099-T,WTR100=WTR100-T,WTR150=WTR150-T,WTR162=WTR162-T,WTR224=WTR224-T,ARC191=ARC191-T,CRU082=CRU082-T,MON155=MON155-T,MON215=MON215-T,MON216=MON216-T,MON217=MON217-T,MON219=MON219-T,MON220=MON220-T,ELE146=ELE146-T,EVR020=EVR020-T,UPR006=UPR006-T,UPR007=UPR007-T,UPR008=UPR008-T,UPR009=UPR009-T,UPR010=UPR010-T,UPR011=UPR011-T,UPR012=UPR012-T,UPR013=UPR013-T,UPR014=UPR014-T,UPR015=UPR015-T,UPR016=UPR016-T,UPR017=UPR017-T,UPR042=UPR042-T,UPR043=UPR043-T,UPR169=UPR169-T,UPR406=UPR406-T,UPR407=UPR407-T,UPR408=UPR408-T,UPR409=UPR409-T,UPR410=UPR410-T,UPR411=UPR411-T,UPR412=UPR412-T,UPR413=UPR413-T,UPR414=UPR414-T,UPR415=UPR415-T,UPR416=UPR416-T,UPR417=UPR417-T,DYN001=DYN001-T,DYN005=DYN005-T,DYN026=DYN026-T,DYN045=DYN045-T,DYN065=DYN065-T,DYN068=DYN068-T,DYN088=DYN088-T,DYN092=DYN092-T,DYN121=DYN121-T,DYN151=DYN151-T,DYN171=DYN171-T,DYN192=DYN192-T,DYN212=DYN212-T,DYN213=DYN213-T,DYN234=DYN234-T,DYN492a=DYN492a-TDYN492b=DYN492b-T,DYN492c=DYN492c-T,DYN612=DYN612-T,DTD005=DTD005-T,DTD006=DTD006-T,DTD007=DTD007-T,DTD008=DTD008-T,DTD009=DTD009-T,DTD010=DTD010-T,DTD011=DTD011-T,DTD012=DTD012-T,DTD164=DTD164-T,DTD564=DTD564-T,DTD405=DTD405-T,DTD406=DTD406-T,DTD407=DTD407-T,DTD408=DTD408-T,DTD409=DTD409-T,DTD410=DTD410-T,DTD411=DTD411-T, DTD412=DTD412-T,EVO010=EVO010-T,EVO026=EVO026-T,EVO027=EVO027-T,EVO028=EVO028-T,EVO029=EVO029-T,EVO030=EVO030-T,EVO031=EVO031-T,EVO032=EVO032-T,EVO033=EVO033-T,EVO410=EVO410-T,EVO410a=EVO410a-T,EVO410b=EVO410b-T";
      case "9408649": return "WTR001=WTR001-T,WTR002=WTR002-T,WTR098=WTR098-T,WTR099=WTR099-T,WTR100=WTR100-T,WTR150=WTR150-T,WTR162=WTR162-T,WTR173=WTR173-T,WTR224=WTR224-T,ARC159=ARC159-T2,ARC191=ARC191-T,CRU082=CRU082-T,MON155=MON155-T,MON215=MON215-T,MON216=MON216-T,MON217=MON217-T,MON219=MON219-T,MON220=MON220-T,ELE146=ELE146-T,EVR020=EVR020-T,UPR006=UPR006-T,UPR007=UPR007-T,UPR008=UPR008-T,UPR009=UPR009-T,UPR010=UPR010-T,UPR011=UPR011-T,UPR012=UPR012-T,UPR013=UPR013-T,UPR014=UPR014-T,UPR015=UPR015-T,UPR016=UPR016-T,UPR017=UPR017-T,UPR042=UPR042-T,UPR043=UPR043-T,UPR169=UPR169-T,UPR406=UPR406-T,UPR407=UPR407-T,UPR408=UPR408-T,UPR409=UPR409-T,UPR410=UPR410-T,UPR411=UPR411-T,UPR412=UPR412-T,UPR413=UPR413-T,UPR414=UPR414-T,UPR415=UPR415-T,UPR416=UPR416-T,UPR417=UPR417-T,DYN001=DYN001-T,DYN005=DYN005-T,DYN026=DYN026-T,DYN045=DYN045-T,DYN065=DYN065-T,DYN068=DYN068-T,DYN088=DYN088-T,DYN092=DYN092-T,DYN121=DYN121-T,DYN151=DYN151-T,DYN171=DYN171-T,DYN192=DYN192-T,DYN212=DYN212-T,DYN213=DYN213-T,DYN234=DYN234-T,DYN492a=DYN492a-TDYN492b=DYN492b-T,DYN492c=DYN492c-T,DYN612=DYN612-T,DTD005=DTD005-T,DTD006=DTD006-T,DTD007=DTD007-T,DTD008=DTD008-T,DTD009=DTD009-T,DTD010=DTD010-T,DTD011=DTD011-T,DTD012=DTD012-T,DTD164=DTD164-T,DTD564=DTD564-T,DTD405=DTD405-T,DTD406=DTD406-T,DTD407=DTD407-T,DTD408=DTD408-T,DTD409=DTD409-T,DTD410=DTD410-T,DTD411=DTD411-T, DTD412=DTD412-T,EVO010=EVO010-T,EVO026=EVO026-T,EVO027=EVO027-T,EVO028=EVO028-T,EVO029=EVO029-T,EVO030=EVO030-T,EVO031=EVO031-T,EVO032=EVO032-T,EVO033=EVO033-T,EVO410=EVO410-T,EVO410a=EVO410a-T,EVO410b=EVO410b-T";
      default: return "";
    }
  }

  public function CardBacks(): string
  {
    switch($this->value)
    {
      case "0": return "27";
      case "1": return "28";
      case "2": return "37";
      case "3": return "42";
      case "4": return "45";
      case "5": return "46";
      case "6": return "48";
      case "7": return "49";
      case "8": return "55";
      case "9": return "64";
      case "7198186": return "1,2,3,4,5,6,7,8";
      case "7579026": return "9";
      case "7329070": return "10,11,12,13,14,15,16";
      case "1787491": return "17,18,19,20";
      case "8997252": return "21,22,57,58,59,60,61,62";
      case "8955846": return "23,52";
      case "6839952": return "24";
      case "7285727": return "25";
      case "8635931": return "26";
      case "8736344": return "29";
      case "7593240": return "30";
      case "8458487": return "31";
      case "6996822": return "32";
      case "1919413": return "33";
      case "10651350": return "34";
      case "7733166": return "35";
      case "7009853": return "36";
      case "8338817": return "38";
      case "9361474": return "39";
      case "3828539": return "40";
      case "8951973": return "41";
      case "9370276": return "43";
      case "9404423": return "44";
      case "8040288": return "47";
      case "10147920": return "50";
      case "4006023": return "51";
      case "10811477": return "53";
      case "9851977": return "54";
      case "279086": return "56";
      case "11184392": return "63";
      case "11481720": return "65";
      case "9408649": return "1,2,3,4,5,6,7,8";
      
      default: return "";
    }
  }
}


?>
