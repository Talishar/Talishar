<?php

require_once __DIR__ . '/../../AllAltArtVariations.php';

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
      case "0": return "";
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
  case TeamEmperorsRome = "10";
  case SunflowerSamurai = "11";
  case ColdFoilControl = "12";
  case RighteousGaming = "13";
  case TeamTalishar = "14";
  case TeamTideBreakers = "15";
  case CupofTCG = "16";
  case ScowlingFleshBag = "14707687";
  case ThaiCardsShop = "18";
  case MetalFab = "19";
  case PotatoSquad = "20";
  case FabledBrazil = "21";
  case AggroBlaze = "22";
  case Null = "23";
  case PitchDevils = "24";
  case Mickz = "25";
  case SnapDragons = "26";
  case FaBlazing = "27";
  case Snow = "28";
  case RedLine = "29";
  case SkillIssue = "30";
  case WingedHussars = "31";
  case FabInsight = "32";
  case Oddwillows = "33";
  case Shine = "34";
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
  case WeMakeBest = "9734205";
  case MnRCast = "9574942";
  case OnTheBauble = "11561507";
  case GorganianTome = "9555916";
  case FABChaos = "8716783";
  case DailyFab = "11266104";
  case ThePlagueHive = "12144126";
  case Dropcast = "12245100";
  case FleshandBloodwithPablo = "12300349";
  case ChantsAndDaggers = "10956423";
  case Dazzyfizzle = "12977197";
  case Nikobru = "13586904";
  case Dashciples = "13136013";
  case BlitzkriegMeph = "13969806";
  case HamMan215 = "13035399";
  case NewHorizons = "13905903";
  case ThreeFloating = "11527692";
  case Steelfur = "7330270";
  case FleshAndBad = "13518995";
  case SilvarisGarden = "14460977";
  case FatAndFurious = "14951942";
  case OllinTogether = "15323388";
  case FabDads = "15431936";

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
      case "9734205": return "isWeMakeBestPatron";
      case "9574942": return "isMnRCastPatron";
      case "11561507": return "isOnTheBaublePatron";
      case "9555916": return "isGorganianTomePatron";
      case "8716783": return "isFABChaosPatron";
      case "11266104": return "isDailyFabPatron";
      case "12245100": return "isDropcastPatron";
      case "12300349": return "IsFleshandBloodwithPabloPatron";
      case "10956423": return "isChantsAndDaggersPatron";
      case "12977197": return "isDazzyfizzlePatron";
      case "13586904": return "isNikobruPatron";
      case "13136013": return "isDashciplesPatron";
      case "13969806": return "isBlitzkriegMephPatron";
      case "13035399": return "isHamMan215Patron";
      case "13905903": return "isNewHorizonsPatron";
      case "11527692": return "isThreeFloatingPatron";
      case "7330270": return "isSteelfurPatron";
      case "13518995": return "isFleshAndBadPatron";
      case "14460977": return "isSilvarisGardenPatron";
      case "14951942": return "isFatAndFuriousPatron";
      case "15323388": return "isOllinTogetherPatron";
      case "15431936": return "isFabDadsPatron";
      case "14707687": return "isScowlingFleshBagPatron";
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
      case "10": return "Team Emperors Rome";
      case "11": return "Sunflower Samurai";
      case "12": return "Cold Foil Control";
      case "13": return "Righteous Gaming";
      case "14": return "Team Talishar";
      case "15": return "Team Tide Breakers";
      case "16": return "Cup of TCG";
      case "14707687": return "ScowlingFleshBag";
      case "18": return "Thai Cards Shop";
      case "19": return "Metal Fab";
      case "20": return "Potato Squad";
      case "21": return "Team Fabled Brazil";
      case "22": return "Aggro Blaze";
      case "23": return "Null";
      case "24": return "Pitch Devils";
      case "25": return "Mickz";
      case "26": return "Snap Dragons";
      case "27": return "FaBlazing";
      case "28": return "Snow";
      case "29": return "Red Line";
      case "30": return "Skill Issue";
      case "31": return "Winged Hussars";
      case "32": return "Fab Insight";
      case "33": return "Oddwillows";
      case "34": return "Shine";
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
      case "9734205": return "WeMakeBest";
      case "9574942": return "MnRCast";
      case "11561507": return "OnTheBauble";
      case "9555916": return "GorganianTome";
      case "8716783": return "FABChaos";
      case "11266104": return "DailyFab";
      case "12144126": return "ThePlagueHive";
      case "12245100": return "Dropcast";
      case "12300349": return "Flesh and Blood with Pablo";
      case "10956423": return "Chants and Daggers";
      case "12977197": return "Dazzyfizzle";
      case "13586904": return "Nikobru";
      case "13136013": return "Dashciples";
      case "13969806": return "Blitzkrieg Meph";
      case "13035399": return "HamMan215";
      case "13905903": return "New Horizons FAB";
      case "11527692": return "Three Floating";
      case "7330270": return "Steelfur";
      case "13518995": return "Flesh and Bad";
      case "14460977": return "Silvaris Garden";
      case "14951942": return "Fat and Furious";
      case "15323388": return "Ollin Together";
      case "15431936": return "Fab Dads";
      default: return "";
    }
  }

  public function IsTeamMember($userName): string
  {
    switch($this->value)
    {
      case "0": return $userName == "MrShub" || $userName == "duofanel" || $userName == "Matiisen" ||  $userName == "Pepowski" ||  $userName == "Seba_stian" ||  $userName == "NatAlien" ||  $userName == "dvooyas" || $userName == "Lukashu" || $userName == "Qwak" || $userName == "NatAlien";
      case "1": return $userName == "TwitchTvFabschool" || $userName == "MattRogers" || $userName == "TariqPatel";
      case "2": return $userName == "hometowntcg" || $userName == "ProfessorKibosh" || $userName == "criticalclover8" || $userName == "bomberman" || $userName == "woodjp64" || $userName == "TealWater" || $userName == "Bravosaur" || $userName == "DaganTheZookeeper" || $userName == "Dratylis" || $userName == "MoBogsly";
      case "3": return $userName == "DeadSummer";
      case "4": return $userName == "LeoLeo";
      case "5": return $userName == "XIR";
      case "6": return $userName == "wackzitt" || $userName == "RainyDays" || $userName == "HelpMeJace2" || $userName == "S1lverback55" || $userName == "VexingTie" || $userName == "Ragnell";
      case "7": return IsTeamSecondCycle($userName);
      case "8": return IsTeamSonicDoom($userName);
      case "9": return IsTeamPummel($userName);
      case "10": return IsTeamEmperorsRome($userName);
      case "11": return IsTeamSunflowerSamurai($userName);
      case "12": return IsTeamColdFoilControl($userName);
      case "13": return IsTeamRighteousGaming($userName);
      case "14": return IsTeamTalishar($userName) || $userName == "Aegisworn";
      case "15": return IsTeamTideBreakers($userName);
      case "16": return IsTeamCupofTCG($userName);
      case "14707687": return IsTeamScowlingFleshBag($userName);
      case "18": return IsTeamThaiCardsShop($userName);
      case "19": return IsTeamMetalFab($userName);
      case "20": return IsTeamPotatoSquad($userName);
      case "21": return IsTeamFabledBrazil($userName);
      case "22": return $userName == "AggroBlaze96";
      case "23": return $userName == "Null";
      case "24": return IsTeamPitchDevils($userName);
      case "25": return $userName == "Mickz";
      case "26": return IsTeamSnapDragons($userName);
      case "27": return $userName == "AggroBlaze96" || $userName == "PvtVoid";
      case "28": return $userName == "Snow";
      case "29": return IsTeamRedLine($userName);
      case "30": return IsTeamSkillIssue($userName);
      case "31": return IsTeamWingedHussars($userName);
      case "32": return IsTeamFabInsight($userName);
      case "33": return IsTeamOddwillows($userName);
      case "34": return IsTeamShine($userName);
      case "7198186": return $userName == "OotTheMonk" || $userName == "Aegisworn" || $userName == "PvtVoid";
      case "7579026": return $userName == "Hamsack" || $userName == "BigMedSi" || $userName == "Tripp";
      case "7329070": return $userName == "GoAgainGamingAz";
      case "1787491": return $userName == "RedZoneRogue";
      case "8997252": return $userName == "phillip";
      case "8955846": return $userName == "Man_Sant" || $userName == "PollyCranka" || $userName == "Aegisworn";
      case "6839952": return $userName == "chonigman" || $userName == "Ijaque";
      case "7285727": return $userName == "Brendan" || $userName == "TheClub";
      case "8635931": return $userName == "TheTekloFoundry";
      case "8736344": return $userName == "Smithel";
      case "7593240": return $userName == "SinOnStream";
      case "8458487": return $userName == "Freshlord";
      case "6996822": return $userName == "Sloopdoop";
      case "1919413": return $userName == "DMArmada";
      case "7733166": return $userName == "NamVoTCGz" || $userName == "AlexTheCardGuy" || $userName == "RegularDegular" || $userName == "joshlau7" || $userName == "WillyB" || $userName == "Spoofy" || $userName == "ItsSebBruh" || $userName == "Knight";
      case "7009853": return $userName == "hometowntcg";
      case "8338817": return $userName == "imjorman" || $userName == "ADavis83" || $userName == "loganpetersen";
      case "9361474": return $userName == "kappolo";
      case "3828539": return $userName == "Tee";
      case "8951973": return $userName == "Wes" || $userName == "Brandon";
      case "9370276": return $userName == "TheTablePitYT" || $userName == "TunaTCG";
      case "9404423": return $userName == "TCGTed";
      case "8040288": return IsTeamCardAdvantage($userName);
      case "10147920": return $userName == "RavenousBabble" || $userName == "Arty" || $userName == "jargowsky" || $userName == "Nick52cost" || $userName == "Boomerang" || $userName == "matthias" || $userName == "Repas801";
      case "4006023": return $userName == "BlackWingStudio";
      case "10811477": return $userName == "Mezzo";
      case "9851977": return $userName == "DaganTheZookeeper" || $userName == "OotTheMonk";
      case "279086": return $userName == "brandaotcg" || $userName == "OotTheMonk" || $userName == "Aegisworn";
      case "11184392": return $userName == "PatSmashGood" || $userName == "OotTheMonk";
      case "11481720": return $userName == "nxi" || $userName == "PvtVoid";
      case "9408649": return $userName == "Aegisworn";
      case "9734205": return $userName == "tog" || $userName == "bnet" || $userName == "balakay";
      case "9574942": return $userName == "Azor";
      case "11561507": return "";
      case "9555916": return $userName == "Cathardigan" || $userName == "GorganianTome";
      case "8716783": return IsTeamFABChaos($userName);
      case "11266104": return $userName == "Lupinefiasco";
      case "12144126": return $userName == "Pentregarth" || $userName == "Archon Alters";
      case "12245100": return $userName == "Smithel" || $userName == "Poopking" || $userName == "HeyLookItsBrice";
      case "12300349": return "";
      case "10956423": return $userName == "ChantsAndDaggers" || $userName == "OotTheMonk";
      case "12977197": return $userName == "dazzyfizzle";
      case "13586904": return $userName == "Nikobru";
      case "13136013": return $userName == "WuntrikAkali";
      case "13969806": return $userName == "BlitzkriegMeph";
      case "13035399": return $userName == "HamMan215";
      case "13905903": return $userName == "Towelie" || $userName == "Abyssion" || $userName == "Siege" || $userName == "Alithos";
      case "11527692": return $userName == "Bones503" || $userName == "kwasneski" || $userName == "Hymoshi";
      case "7330270": return $userName == "Steelfur";
      case "13518995": return $userName == "Tegunn" || $userName == "AurOraOraOraOraORA";
      case "14460977": return $userName == "AlpacaSciatrice";
      case "14951942": return IsTeamFatAndFurious($userName);
      case "15323388": return $userName == "OllinTogether";
      case "15431936": return IsTeamFabDads($userName);
      default: return "";
    }
  }

  public function AltArts($heroCardNumber = ""): string
  {
    $altArts = [];

    if ($heroCardNumber !== "") {
      if(GeneratedHasEssenceOfEarth($heroCardNumber)) {
        $altArts[] = "runechant=ARC112-Earth";
        $altArts[] = "might=TER028-might";
      }
      if(GeneratedHasEssenceOfLightning($heroCardNumber)) {
        $altArts[] = "runechant=ARC112-Lightning";
      }
      if($heroCardNumber == "enigma" || $heroCardNumber == "enigma_ledger_of_ancestry") {
        $altArts[] = "spectral_shield=MON104-Blue";
      }
      if($heroCardNumber == "enigma_new_moon") {
        $altArts[] = "spectral_shield=MON104-Green";
      }
    }

    // Add campaign-specific alt arts
    $campaignAltArts = $this->getCampaignAltArts();
    if (!empty($campaignAltArts)) {
      $altArts[] = $campaignAltArts;
    }

    return implode(",", $altArts);
  }

  private function getCampaignAltArts(): string
  {
    $altArts = [];
    
    switch($this->value) {
      case "7198186": // Talishar
      case "9408649": // PvtVoid
        $altArts = GetAllAltArtVariations();
        break;
      case "8955846": // ManSant
        $altArts = [
          "levia_shadowborn_abomination=MON119-ManSant",
          "levia=MON120-ManSant",
          "agility=agility-ManSant",
          "might=might-ManSant",
          "quicken=quicken-ManSant",
          "vigor=vigor-ManSant",
        ];
        break;
      case "279086": // Brandao
        $altArts = [
          "dash_inventor_extraordinaire=ARC001-Brandao", "kano_dracai_of_aether=ARC113-Brandao",
          "kassai_cintari_sellsword=CRU077-Brandao", "lexi_livewire=ELE031-Brandao",
          "riptide_lurker_of_the_deep=OUT091-Brandao", "dromai_ash_artist=UPR001-Brandao",
          "bravo_showstopper=WTR038-Brandao", "teklo_plasma_pistol=ARC003-Brandao",
          "cintari_saber=CRU079-Brandao", "copper=CRU197-Brandao", "frostbite=ELE111-Brandao",
          "storm_of_sandikai=UPR003-Brandao", "aether_ashwing=UPR042-Brandao", "ash=UPR043-Brandao",
          "anothos=WTR040-Brandao", "seismic_surge=WTR075-Brandao", "runechant=ARC112_Brandao",
          "harmonized_kodachi=WTR078_Brandao", "vynnset=DTD134_Brandao", "flail_of_agony=DTD135_Brandao",
          "maxx_the_hype_nitro=EVO004_Brandao", "banksy=EVO006_Brandao", "hyper_driver=EVO234_Brandao",
          "katsu_the_wanderer=WTR076_Brandao", "mandible_claw=CRU004_Brandao", "courage=DTD232_Brandao",
          "rhinar_reckless_rampage=WTR001_Brandao", "dorinthea_ironsong=WTR113_Brandao",
          "dawnblade=WTR115_Brandao"
        ];
        break;
      case "14": // TeamTalishar
        $altArts = [
          "embodiment_of_earth=ELE109-Promo",
          "embodiment_of_lightning=ELE110-Promo"
        ];
        break;
      case "14707687": // Scowling
        $altArts = [
        "rhinar_reckless_rampage=WTR001-T", "romping_club=WTR003-T"
        ];
        break;
    }

    return implode(",", $altArts);
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
      case "10": return "67";
      case "11": return "70";
      case "12": return "75";
      case "13": return "77,78,120";
      case "14": return "82,83";
      case "15": return "84";
      case "16": return "87";
      case "14707687": return "88,100,130";
      case "18": return "96";
      case "19": return "105";
      case "20": return "106";
      case "21": return "112";
      case "22": return "118";
      case "23": return "122";
      case "24": return "123,132";
      case "25": return "124,125";
      case "26": return "127";
      case "27": return "129";
      case "28": return "131";
      case "29": return "133";
      case "30": return "134";
      case "31": return "135";
      case "32": return "136";
      case "33": return "138";
      case "34": return "139";
      case "7198186": return "1,2,3,4,5,6,7,8,82,83";
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
      case "8458487": return "31,121";
      case "6996822": return "32";
      case "1919413": return "33";
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
      case "11481720": return "65,137";
      case "9408649": return "1,2,3,4,5,6,7,8,82,83";
      case "9734205": return "68,69";
      case "9574942": return "71";
      case "11561507": return "72";
      case "9555916": return "73";
      case "8716783": return "74";
      case "11266104": return "76";
      case "12144126": return "79";
      case "12245100": return "80";
      case "12300349": return "81";
      case "10956423": return "85,86";
      case "12977197": return "89,90,91,92,93,94,95,98,99,114,115,116,117";
      case "13586904": return "97";
      case "13136013": return "101";
      case "13969806": return "102";
      case "13035399": return "103";
      case "13905903": return "104";
      case "11527692": return "107,108,109";
      case "7330270": return "110";
      case "13518995": return "111";
      case "14460977": return "113";
      case "14951942": return "119";
      case "15323388": return "126";
      case "15431936": return "128";
      default: return "";
    }
  }

  public function PlayMats(): string
  {
    switch($this->value)
    {
      case "21": 
        return "27";
      case "24":
        return "43";
      case "11527692": 
        return "22";
      case "8955846": 
        return "23";
      case "9370276": 
        return "24";
      case "7330270": 
        return "25";
      case "13518995": 
        return "26";
      case "13905903": 
        return "28";
      case "14460977": 
        return "29";
      case "9408649": 
      case "7198186":
        return "37,38,39,40,41,45";
      default: return "";
    }
  }
}
