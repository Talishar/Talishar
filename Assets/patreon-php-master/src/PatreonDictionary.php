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
  case IndyFab = "35";
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
      case "35": return "IndyFab";
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

  private static function TeamRosters(): array
  {
    static $rosters = null;
    if ($rosters !== null) return $rosters;

    $rosters = [
      "0" => ["MrShub", "duofanel", "Matiisen", "Pepowski", "Seba_stian", "NatAlien", "dvooyas", "Lukashu", "Qwak"],
      "1" => ["TwitchTvFabschool", "MattRogers", "TariqPatel"],
      "2" => ["hometowntcg", "ProfessorKibosh", "criticalclover8", "bomberman", "woodjp64", "TealWater", "Bravosaur",
        "DaganTheZookeeper", "Dratylis", "MoBogsly"],
      "3" => ["DeadSummer"],
      "4" => ["LeoLeo"],
      "5" => ["XIR"],
      "6" => ["wackzitt", "RainyDays", "HelpMeJace2", "S1lverback55", "VexingTie", "Ragnell"],
      //Second Cycle
      "7" => ["The4thAWOL", "Beserk", "Dudebroski", "deathstalker182", "TryHardYeti", "Fledermausmann", "Loganninty7",
        "flamedog3", "Swankypants", "Blazing For Lethal?", "Jeztus", "gokkar", "Kernalxklink", "Kymo13"],
      //Sonic Doom
      "8" => ["KanoSux", "BestBoy", "CRGrey", "jujubeans", "YodasUncle", "ravenklath", "Blazing For Lethal?", "DimGuy",
        "JoeyReads", "OompaLoompaTron", "Ocean", "radiotoast", "ThePitchStack", "KanosWaterBottle", "yamsandwic",
        "ThatOneKano", "YuutoSJ", "ZorbyX", "littlsnek", "AWizardofEarthsea"],
      //Pummel
      "9" => ["MkDk", "Kutter", "Smeoz", "Fabio", "JustFonta", "M3X", "Tommaso", "PDMPLB"],
      //Emperors Rome
      "10" => ["Daniele90rm", "Excelsa", "kano90", "Maalox10", "TriangoloRotondo", "Piervillo", "Rean", "Jekpack",
        "playboikrame", "Danyr99", "ZiFrank", "Fevic"],
      //Sunflower Samurai
      "11" => ["Usagi", "HidaEishi", "kaikou", "Akuma", "Free", "yoeresel", "Kohs", "Ch3sh1r3", "NardoPotente",
        "dtitan", "Pokechtulhu", "CarlosGG", "N1MP0", "Clenyu", "juanmonzonf", "Raiswind", "Bossen"],
      //Cold Foil Control
      "12" => ["Z-Gin", "Chaco", "Kentshero", "Ardent", "PurpleHaze", "luxas", "chefwheaton"],
      //Righteous Gaming
      "13" => ["RighteousGaming", "Perodic", "zzdog", "krav", "Motley", "amodell", "TrentMcB", "pzych", "deragun",
        "Harvey0209", "f1av0r", "Vemnyx", "mclair", "FomToolery", "lostinspacefab", "SQJ", "magusoftheguild", "S1lverback55"],
      //Team Talishar
      "14" => ["HelpMeJace2", "RainyDays", "Ragnell", "Hochi", "Cwaugh", "QZXK20", "VexingTie", "Aegisworn"],
      //Tide Breakers
      "15" => ["OotTheMonk", "Yarandor", "grossmaul2130", "EggShot", "Kasadoom", "Gulto", "FinnElbe", "Stardragon",
        "DragonSlayer", "TerranceSkill", "TaddelDown", "Ilya", "PastaPaul"],
      //Cup of TCG
      "16" => ["Cody1304", "Glem", "parallaxdream", "2birds1stone"],
      //Thai Cards Shop
      "18" => ["thaicards"],
      //Metal Fab
      "19" => ["deathstalker182", "Closetnerds", "Diene9", "acroriver", "ShadowGriffin", "Kentshero", "thekingg21",
        "Lupinefiasco", "onlyrunverynoob", "Brishen", "Sinthrandir", "killerbrews", "Z-Gin", "Obliterage", "RedBeard",
        "KillerBrews"],
      //Potato Squad
      "20" => ["Corry", "Gibbie", "sycotik", "ruin", "Xandorion", "ObiJohn", "tader", "Wittman1", "enflames91",
        "SlimDrew23", "NoRaven", "middiekittie", "archangel224", "Nick56", "SCORPIO", "ArgentGrey", "SynThePanda93",
        "welpcakes", "RiptideRipper", "gilfab", "dautt", "Grublo"],
      //Fabled Brazil
      "21" => ["tetsuo", "hugodeoz", "diorge", "LGB", "mishel157", "DanielDertoni", "caduads", "DracaiBR", "gravebeat",
        "LiP", "DShima", "RodinhoTeclados"],
      "22" => ["AggroBlaze96"],
      "23" => ["Null"],
      //Pitch Devils
      "24" => ["Lestat", "elnino", "RTZ", "Schmax", "Belphegor", "FloJo", "MikeDwyer", "Dionysos", "Sosa", "TaddelDown",
        "inama", "Kanopterix"],
      "25" => ["Mickz"],
      //Snap Dragons
      "26" => ["iamtherealdylanthompson", "SpoostingBendog", "EdgeOfAir", "Matt", "Diomedesau", "Nyjin", "Manavon",
        "Trouthammer", "N3ardeath", "Snaps", "TheGlib", "TheJudester"],
      "27" => ["AggroBlaze96", "PvtVoid"],
      "28" => ["Snow"],
      //Red Line
      "29" => ["Aegisworn", "CornOnJacob", "jonam33", "Scribnibble", "Yuriiko", "Sharp", "MXBloom", "Lazaeus",
        "bloodbit", "hurricanewes", "Aljo", "Flempa", "redprairiedawn"],
      //Skill Issue
      "30" => ["Vaxildan", "kk96", "Skoupakas69", "BreakingChaos", "TheCouncillor", "JaxC", "Cubacash", "kungfoukios",
        "sudogreeko", "katsubina", "NikolasG", "LegenProMax", "sadonEmsi", "DioReformed", "AggroBlazeNo1Fan", "kenobi",
        "Giannis92", "AssassinoCapuccino"],
      //Winged Hussars
      "31" => ["Calebovitsch", "Steve119", "Lucid", "Seba", "raskoks", "Chudy", "metatron", "Dovi", "dssstefan",
        "makos", "RavenLemur", "XIR", "PvtVoid"],
      //FaB Insight
      "32" => ["FaBInsights", "PvtVoid"],
      //Oddwillows
      "33" => ["BenOddwillows", "PvtVoid", "Teari", "arcaneghost", "RadicusMaximus"],
      //Shine
      "34" => ["shine", "Baumfish", "Curryking", "DanielB", "Flixi", "malusNexx2", "Sleepless", "Thalric", "Nexre", "PvtVoid"],
      //IndyFab
      "35" => ["jtbruhn94", "PvtVoid"],
      "279086" => ["brandaotcg", "OotTheMonk", "Aegisworn"],
      "1787491" => ["RedZoneRogue"],
      "1919413" => ["DMArmada"],
      "3828539" => ["Tee"],
      "4006023" => ["BlackWingStudio"],
      "6839952" => ["chonigman", "Ijaque"],
      "6996822" => ["Sloopdoop"],
      "7009853" => ["hometowntcg"],
      "7198186" => ["OotTheMonk", "Aegisworn", "PvtVoid", "Bluffkin1"],
      "7285727" => ["Brendan", "TheClub"],
      "7329070" => ["GoAgainGamingAz"],
      "7330270" => ["Steelfur"],
      "7579026" => ["Hamsack", "BigMedSi", "Tripp"],
      "7593240" => ["SinOnStream"],
      "7733166" => ["NamVoTCGz", "AlexTheCardGuy", "RegularDegular", "joshlau7", "WillyB", "Spoofy", "ItsSebBruh", "Knight"],
      //Team Card Advantage
      "8040288" => ["JacobK", "Pastry Boi", "Brotworst", "1nigoMontoya (Cody)", "Motley", "jimmyhl1329", "Stilltzkin",
        "krav", "infamousb", "FatFabJesus", "MisterPNP"],
      "8338817" => ["imjorman", "ADavis83", "loganpetersen"],
      "8458487" => ["Freshlord"],
      "8635931" => ["TheTekloFoundry"],
      //FAB Chaos
      "8716783" => ["SaXoChaos", "nakezuma", "Broken", "Atsacus", "rkntl", "SlyNight", "Elnor", "mythen", "Enegon", "Obnoxious"],
      "8736344" => ["Smithel"],
      "8951973" => ["Wes", "Brandon"],
      "8955846" => ["Man_Sant", "PollyCranka", "Aegisworn", "PEN15"],
      "8997252" => ["phillip"],
      "9361474" => ["kappolo"],
      "9370276" => ["TheTablePitYT", "TunaTCG"],
      "9404423" => ["TCGTed"],
      "9408649" => ["Aegisworn"],
      "9555916" => ["Cathardigan", "GorganianTome"],
      "9574942" => ["Azor"],
      "9734205" => ["tog", "bnet", "balakay"],
      "9851977" => ["DaganTheZookeeper", "OotTheMonk"],
      "10147920" => ["RavenousBabble", "Arty", "jargowsky", "Nick52cost", "Boomerang", "matthias", "Repas801"],
      "10811477" => ["Mezzo"],
      "10956423" => ["ChantsAndDaggers", "OotTheMonk"],
      "11184392" => ["PatSmashGood", "OotTheMonk"],
      "11266104" => ["Lupinefiasco"],
      "11481720" => ["nxi", "PvtVoid"],
      "11527692" => ["Bones503", "kwasneski", "Hymoshi"],
      "12144126" => ["Pentregarth", "Archon Alters"],
      "12245100" => ["Smithel", "Poopking", "HeyLookItsBrice"],
      "12977197" => ["dazzyfizzle"],
      "13035399" => ["HamMan215"],
      "13136013" => ["WuntrikAkali"],
      "13518995" => ["Tegunn", "AurOraOraOraOraORA"],
      "13586904" => ["Nikobru"],
      "13905903" => ["Towelie", "Abyssion", "Siege", "Alithos"],
      "13969806" => ["BlitzkriegMeph"],
      "14460977" => ["AlpacaSciatrice"],
      //Scowling Flesh Bag
      "14707687" => ["Scowling", "PvtVoid"],
      //Fat and Furious
      "14951942" => ["OopsAllPummels", "AngelPillow", "stefchwan", "JK", "Astropeleki", "Debread", "Tilemachos27",
        "Intzah", "Cubacash", "karyo", "Ironclad", "Jorin", "anastaso73", "z4risu"],
      "15323388" => ["OllinTogether"],
      //Fab Dads
      "15431936" => ["LostInDaSpace", "Belazhul", "zaketanapareis", "thilakinos", "Debread", "mellone", "makvag",
        "Pitsirikos", "Alith0r0sKykl0pas", "Jim", "nikfabfanfatty"],
    ];

    foreach ($rosters as $campaignId => $members) {
      $rosters[$campaignId] = array_flip($members);
    }
    return $rosters;
  }

  public function IsTeamMember($userName): bool
  {
    $rosters = self::TeamRosters();
    return isset($rosters[$this->value][$userName]);
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
          "goldkiss_rhum=goldkiss_rhum-ManSant",
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
      case "35": return "140";
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
