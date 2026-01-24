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
      case "22": return ($userName == "AggroBlaze96");
      case "23": return ($userName == "Null");
      case "24": return IsTeamPitchDevils($userName);
      case "25": return ($userName == "Mickz" || $userName == "PvtVoid");
      case "26": return IsTeamSnapDragons($userName);
      case "7198186": return ($userName == "OotTheMonk" || $userName == "Aegisworn");
      case "7579026": return ($userName == "Hamsack" || $userName == "BigMedSi" || $userName == "Tripp");
      case "7329070": return ($userName == "GoAgainGamingAz");
      case "1787491": return ($userName == "RedZoneRogue");
      case "8997252": return ($userName == "phillip");
      case "8955846": return ($userName == "Man_Sant" || $userName == "PollyCranka" || $userName == "Aegisworn");
      case "6839952": return ($userName == "chonigman" || $userName == "Ijaque");
      case "7285727": return ($userName == "Brendan" || $userName == "TheClub");
      case "8635931": return ($userName == "TheTekloFoundry");
      case "8736344": return ($userName == "Smithel");
      case "7593240": return ($userName == "SinOnStream");
      case "8458487": return ($userName == "Freshlord");
      case "6996822": return ($userName == "Sloopdoop");
      case "1919413": return ($userName == "DMArmada");
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
      case "279086": return ($userName == "brandaotcg" || $userName == "OotTheMonk" || $userName == "Aegisworn");
      case "11184392": return ($userName == "PatSmashGood" || $userName == "OotTheMonk");
      case "11481720": return ($userName == "nxi");
      case "9408649": return ($userName == "Aegisworn");
      case "9734205": return ($userName == "tog" || $userName == "bnet" || $userName == "balakay");
      case "9574942": return ($userName == "Azor");
      case "11561507": return "";
      case "9555916": return ($userName == "Cathardigan" || $userName == "GorganianTome");
      case "8716783": return IsTeamFABChaos($userName);
      case "11266104": return ($userName == "Lupinefiasco");
      case "12144126": return ($userName == "Pentregarth" || $userName == "Archon Alters");
      case "12245100": return ($userName == "Smithel" || $userName == "Poopking" || $userName == "HeyLookItsBrice");
      case "12300349": return "";
      case "10956423": return ($userName == "ChantsAndDaggers" || $userName == "OotTheMonk");
      case "12977197": return ($userName == "dazzyfizzle");
      case "13586904": return $userName == "Nikobru";
      case "13136013": return $userName == "WuntrikAkali";
      case "13969806": return ($userName == "BlitzkriegMeph");
      case "13035399": return ($userName == "HamMan215");
      case "13905903": return ($userName == "Towelie" || $userName == "Abyssion" || $userName == "Siege" || $userName == "Alithos");
      case "11527692": return ($userName == "Bones503" || $userName == "kwasneski" || $userName == "Hymoshi");
      case "7330270": return ($userName == "Steelfur");
      case "13518995": return ($userName == "Tegunn" || $userName == "AurOraOraOraOraORA");
      case "14460977": return ($userName == "AlpacaSciatrice");
      case "14951942": return IsTeamFatAndFurious($userName);
      case "15323388": return ($userName == "OllinTogether");
      case "15431936": return IsTeamFabDads($userName);
      default: return "";
    }
  }

  public function AltArts($playerID): string
  {
    $altArts = [];
    $char = getPlayerCharacter($playerID);

    // Add character-specific alt arts
    if(GeneratedHasEssenceOfEarth($char[0])) {
      $altArts[] = "runechant=ARC112-Earth";
      $altArts[] = "might=TER028-might";
    }
    if(GeneratedHasEssenceOfLightning($char[0])) {
      $altArts[] = "runechant=ARC112-Lightning";
    }
    if($char[0] == "enigma" || $char[0] == "enigma_ledger_of_ancestry") {
      $altArts[] = "spectral_shield=MON104-Blue";
    }
    if($char[0] == "enigma_new_moon") {
      $altArts[] = "spectral_shield=MON104-Green";
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
      $altArts = [
          "a_drop_in_the_ocean_blue=MST095-T", "a_good_clean_fight_red=GEM088-T", "adaptive_alpha_mold=SUP253-T", "aegis_archangel_of_protection=DTD407-T",
          "aether_ashwing=UPR042-T", "aether_quickening_red=FAB112-T", "aether_quickening_yellow=FAB113-T", "aether_quickening_blue=FAB114-T", "aether_wildfire_red=EVR123-T",
          "affirm_loyalty_red=GEM012-T", "agile_windup_red=KYO007-T", "agile_windup_yellow=KYO016-T", "agile_windup_blue=KYO022-T", "agility_stance_yellow=FAB313-T",
          "amethyst_amulet_blue=SEA189-T", "anaphylactic_shock_blue=HNT016-T", "angelic_attendant_yellow=SUP265-T", "angelic_wrath_red=LGS161-T",
          "angelic_wrath_yellow=LGS162-T", "angelic_wrath_blue=LGS163-T", "anka_drag_under_yellow=SEA262-T", "annexation_of_all_things_known_yellow=MPG029-T",
          "annexation_of_grandeur_yellow=MPG030-T", "annexation_of_the_forge_yellow=MPG031-T", "annihilator_engine_red=EVO054-T", "apocalypse_automaton_red=FAB175-T",
          "arakni_black_widow=HNT003-T", "arakni_funnel_web=HNT004-T",
          "arakni_marionette=HNT001-T", "arakni_orb_weaver=HNT005-T", "arakni_redback=HNT006-T", "arakni_tarantula=HNT007-T",
          "arakni_trap_door=HNT008-T", "arakni_web_of_deceit=HNT002-T", "arc_lightning_yellow=FAB268-T", "arcane_cussing_red=FLR012-T", "arcane_cussing_yellow=FLR018-T",
          "arcane_cussing_blue=FLR024-T", "arcane_lantern=EVR155-T", "arcanic_shockwave_red=LGS079-T", "arcanite_fortress=ROS211-T", "art_of_the_dragon_blood_red=FAB307-T",
          "art_of_the_dragon_claw_red=FAB308-T", "art_of_the_dragon_fire_red=FAB309-T", "art_of_the_dragon_scale_red=FAB310-T", "aurora=ROS008-T", "aurora_shooting_star=ROS007-T",
          "avalon_archangel_of_rebirth=DTD409-T", "avast_ye_blue=GEM051-T", "azvolai=UPR409-T", "backspin_thrust_red=SUP254-T", "balance_of_justice=ANQ004-T",
          "banneret_of_courage_yellow=FAB161-T", "banneret_of_gallantry_yellow=FAB162-T", "banneret_of_protection_yellow=FAB163-T",
          "banneret_of_resilience_yellow=LGS179-T", "banneret_of_salvation_yellow=LGS180-T", "banneret_of_vigor_yellow=LGS181-T", "barkskin_of_the_millennium_tree=ROS028-T",
          "barnacle_yellow=SEA074-T", "bask_in_your_own_greatness_red=FAB387-T", "bask_in_your_own_greatness_yellow=FAB388-T", "bask_in_your_own_greatness_blue=FAB389-T",
          "battalion_barque_red=SEA152-T", "battalion_barque_yellow=SEA153-T", "battalion_barque_blue=SEA154-T", "batter_to_a_pulp_red=LGS388-T", "battered_not_broken_red=LGS255-T",
          "battlefront_bastion_red=FAB235-T", "battlefront_bastion_yellow=FAB236-T", "battlefront_bastion_blue=FAB237-T", "bellona_archangel_of_war=DTD412-T", "big_blue_sky_blue=FAB238-T", "big_game_trophy_shot_yellow=FAB345-T", "biting_breeze_red=ZEN007-T", "biting_breeze_yellow=ZEN017-T",
          "biting_breeze_blue=ZEN020-T", "blasmophet_the_soul_harvester=FAB021-T", "blaze_headlong_red=FAB089-T", "blessing_of_aether_red=LGS116-T",
          "blessing_of_aether_yellow=LGS117-T", "blessing_of_aether_blue=LGS118-T", "blessing_of_deliverance_red=LGS006-T", "blessing_of_deliverance_yellow=LGS024-T",
          "blessing_of_deliverance_blue=LGS025-T", "blinding_of_the_old_ones_red=MPG026-T", "blood_runs_deep_red=FAB316-T", "blood_splattered_vest=HNT168-T", "bloodrot_pox=FAB133-T",
          "blossom_of_spring=LGS100-T", "blossoming_decay_red=VER008-T", "blossoming_decay_yellow=VER017-T", "blossoming_decay_blue=VER019-T", "blue_fin_harpoon_blue=GEM054-T",
          "board_the_ship_red=FAB347-T", "boast_blue=FAB189-T", "boltn_shot_red=GEM058-T", "bonebreaker_bellow_red=RHI016-T", "bonebreaker_bellow_yellow=RHI020-T",
          "bonebreaker_bellow_blue=RHI024-T", "boulder_trap_yellow=LGS138-T", "bravo=MPG529-T", "bravo_star_of_the_show=EVR417-T", "break_of_dawn_red=LGS169-T",
          "break_of_dawn_yellow=LGS170-T", "break_of_dawn_blue=LGS171-T", "breaking_point_red=FAB091-T",
          "brothers_in_arms_blue=FAB093-T", "bubble_to_the_surface_red=HNT154-T", "buckwild_red=GEM101-T", "bully_tactics_red=FAB380-T", "burgeoning_red=LGS069-T",
          "burgeoning_yellow=LGS070-T", "burgeoning_blue=LGS071-T", "call_to_the_grave_blue=ROS218-T", "calming_breeze_red=GEM031-T", "captains_call_blue=FAB352-T",
          "channel_lake_frigid_blue=ELE146-T", "channel_lightning_valley_yellow=FAB264-T", "channel_the_millennium_tree_red=FAB263-T", "channel_the_tranquil_domain_yellow=SUP263-T",
          "chart_the_high_seas_blue=FAB344-T", "cheaters_charm_yellow=FAB372-T", "chivalry_blue=JDG036-T", "chowder_hearty_cook_yellow=SEA075-T",
          "chum_friendly_first_mate_yellow=SEA050-T", "cintari_sellsword=FAB191-T", "cloud_skiff_red=GEM041-T",
          "cogwerx_tinker_rings=SEA008-T", "comeback_kid_red=FAB377-T", "comeback_kid_yellow=FAB378-T", "comeback_kid_blue=FAB379-T", "command_and_conquer_red=DYN000-T",
          "compounding_anger_red=FAB321-T", "conqueror_of_the_high_seas_red=SEA130-T", "construct_nitro_mechanoid_yellow=DYN092-T", "count_your_blessings_red=LGS357-T",
          "count_your_blessings_yellow=LGS358-T", "count_your_blessings_blue=LGS359-T", "courage=FAB153-T", "cries_of_encore_red=SUP012-T", "cromai=UPR410-T",
          "crouching_tiger=DYN065-T", "crowd_goes_wild_yellow=FAB376-T", "crown_of_dominion=DYN234-T", "cruel_ambition_red=GEM096-T", "cut_through_red=GEM027-T",
          "cut_to_the_chase_red=ARK014-T", "cut_to_the_chase_yellow=ARK018-T", "cut_to_the_chase_blue=ARK022-T", "cutty_shark_quick_clip_yellow=SEA076-T",
          "dead_threads=SEA080-T", "deadwood_dirge_red=LGS363-T", "death_touch_red=FAB132-T", "deep_blue_sea_blue=FAB240-T", "defang_the_dragon_red=HNT030-T",
          "demolition_protocol_red=FAB174-T", "demonstrate_devotion_red=GEM011-T", "den_of_the_spider_red=LGS383-T", "diabolic_offering_blue=FAB164-T",
          "diadem_of_dreamstate=DTD217-T", "diamond_amulet_blue=SEA190-T", "dig_in_red=FAB384-T", "dig_in_yellow=FAB385-T", "dig_in_blue=FAB386-T",
          "disenchantment_of_the_old_ones_red=MPG027-T", "display_loyalty_red=GEM010-T", "dominia=UPR408-T", "double_down_red=FAB194-T",
          "dracona_optimai=UPR406-T", "draw_swords_red=KSI015-T", "draw_swords_yellow=KSI020-T", "draw_swords_blue=KSI025-T", "draw_a_crowd_blue=LGS389-T",
          "drop_of_dragon_blood_red=HNT155-T", "dry_powder_shot_red=GEM059-T", "dusk_path_pilgrimage_red=LGS060-T", "dusk_path_pilgrimage_yellow=LGS061-T",
          "dusk_path_pilgrimage_blue=LGS062-T", "dyadic_carapace=DTD211-T", "earth_form_red=LGS307-T", "earthlore_bounty=EVR020-T", "edge_ahead_red=OLA015-T",
          "edge_ahead_yellow=OLA019-T", "edge_ahead_blue=OLA025-T", "electrostatic_discharge_red=LGS347-T", "electrostatic_discharge_yellow=LGS348-T",
          "electrostatic_discharge_blue=LGS349-T", "emeritus_scolding_red=LGS090-T", "emeritus_scolding_yellow=LGS091-T", "emeritus_scolding_blue=LGS092-T",
          "emissary_of_moon_red=LGS299-T", "emissary_of_tides_red=LGS295-T", "emissary_of_wind_red=LGS303-T", "empyrean_rapture=DTD004-T",
          "endear_devotion_red=GEM013-T", "energetic_impact_blue=GEM098-T", "energy_potion_blue=FAB223-T",
          "entangling_shot_red=GEM060-T", "etchings_of_arcana_red=OSC014-T", "etchings_of_arcana_yellow=OSC021-T", "etchings_of_arcana_blue=OSC023-T",
          "eternal_inferno_red=ROS167-T", "even_bigger_than_that_red=FAB058-T", "even_bigger_than_that_yellow=FAB059-T", "even_bigger_than_that_blue=FAB060-T",
          "expedition_to_azuro_keys_red=SEA155-T", "expedition_to_blackwater_strait_red=SEA156-T", "expedition_to_dreadfall_reach_red=SEA157-T",
          "expedition_to_horizons_mantle_red=SEA158-T", "extinguish_the_flames_red=HNT031-T", "eye_of_ophidia_blue=ANQ002-T", "face_purgatory=ROS114-T",
          "fang_strike=FAB243-T", "fast_and_furious_red=GEM104-T", "fault_line_red=FAB328-T",
          "fealty=HNT167-T", "fiddlers_green_red=GEM066-T", "figment_of_erudition_yellow=DTD005-T", "figment_of_judgment_yellow=DTD006-T",
          "figment_of_protection_yellow=DTD007-T", "figment_of_ravages_yellow=DTD008-T", "figment_of_rebirth_yellow=DTD009-T", "figment_of_tenacity_yellow=DTD010-T",
          "figment_of_triumph_yellow=DTD011-T", "figment_of_war_yellow=DTD012-T", "fire_and_brimstone_red=HNT105-T", "first_tenet_of_chi_moon_blue=LGS300-T",
          "first_tenet_of_chi_tide_blue=LGS296-T", "first_tenet_of_chi_wind_blue=LGS304-T", "flamecall_awakening_red=UPR096-T", "flex_strength_blue=GEM099-T",
          "flic_flak_red=FAB241-T", "flick_knives=SUP244-T", "flittering_charge_red=LGS337-T", "flittering_charge_yellow=LGS338-T", "flittering_charge_blue=LGS339-T",
          "flurry_stance_red=FAB312-T", "flying_high_red=LGS411-T", "flying_high_yellow=LGS412-T",
          "flying_high_blue=LGS413-T", "fools_gold_yellow=FAB350-T", "for_the_dracai_red=FAB318-T", "for_the_emperor_red=FAB319-T", "for_the_realm_red=FAB320-T",
          "frailty=FAB134-T", "fruits_of_the_forest_red=LGS342-T", "fruits_of_the_forest_yellow=LGS343-T", "fruits_of_the_forest_blue=LGS344-T", "fry_red=LGS368-T",
          "fyendals_fighting_spirit_red=FAB261-T", "fyendals_spring_tunic=FAB002-T", "galaxxi_black=MON555-T", "gallow_end_of_the_line_yellow=SUP267-T", "garland_of_spring=LGS415-T",
          "gas_up_red=LGS202-T", "gas_up_yellow=LGS203-T", "gas_up_blue=LGS204-T", "germinate_blue=FAB267-T", "ghostly_visit_red=FAB038-T", "ghostly_visit_yellow=FAB039-T",
          "ghostly_visit_blue=FAB040-T", "give_no_quarter_blue=SEA049-T", "glyph_overlay_red=FAB277-T", "goblet_of_bloodrun_wine_blue=LGS247-T", "gold=SEA244-T",
          "gold_hunter_ketch_yellow=SEA165-T", "gold_hunter_lightsail_yellow=SEA162-T", "gold_hunter_longboat_yellow=SEA163-T", "gold_hunter_marauder_yellow=SEA164-T",
          "golden_cog=SEA042-T", "golden_gait=SUP250-T", "golden_galea=SUP247-T", "golden_gauntlets=SUP249-T", "golden_heart_plate=SUP248-T", "golden_skywarden_yellow=FAB343-T",
          "goldfin_harpoon_yellow=SEA093-T", "goldkiss_rum=SEA245-T", "goldwing_turbine_red=GEM043-T", "gone_in_a_flash_red=ROS076-T", "good_natured_brutality_yellow=SUP004-T",
          "gravekeeping_red=FAB342-T", "gravy_bones=SEA044-T", "gravy_bones_shipwrecked_looter=SEA043-T", "hms_barracuda_yellow=SEA134-T", "hms_kraken_yellow=SEA135-T",
          "hms_marlin_yellow=SEA136-T", "halo_of_lumina_light=APR003-T", "hand_behind_the_pen_red=FAB271-T", "harvest_season_red=LGS367-T", "head_jab_red=LGS017-T",
          "head_jab_yellow=LGS018-T", "head_jab_blue=IRA008-T", "heartbeat_of_candlehold_blue=FAB269-T", "hearty_block_red=LGS252-T", "heave_ho_blue=GEM052-T",
          "heavy_artillery_red=LGS191-T", "heavy_artillery_yellow=LGS192-T", "heavy_artillery_blue=LGS193-T", "helm_of_hindsight=LGS414-T", "helm_of_the_adored=SUP017-T",
          "helm_of_the_arknight=AVS003-T", "hemorrhage_bore_red=LGS119-T", "hemorrhage_bore_yellow=LGS120-T", "hemorrhage_bore_blue=LGS121-T", "herald_of_erudition_yellow=MON004-T",
          "herald_of_judgment_yellow=DTD013-T", "herald_of_protection_red=DTD014-T", "herald_of_protection_yellow=DTD015-T", "herald_of_protection_blue=DTD016-T",
          "herald_of_ravages_red=DTD017-T", "herald_of_ravages_yellow=DTD018-T", "herald_of_ravages_blue=DTD019-T", "herald_of_rebirth_red=DTD020-T",
          "herald_of_rebirth_yellow=DTD021-T", "herald_of_rebirth_blue=DTD022-T", "herald_of_tenacity_red=DTD023-T", "herald_of_tenacity_yellow=DTD024-T",
          "herald_of_tenacity_blue=DTD025-T", "herald_of_triumph_red=DTD026-T", "herald_of_triumph_yellow=DTD027-T", "herald_of_triumph_blue=DTD028-T",
          "heroic_pose_red=GEM091-T", "high_striker_red=EVR164-T", "high_striker_yellow=EVR165-T", "high_striker_blue=EVR166-T", "hiss_red=NUU013-T",
          "hiss_yellow=NUU015-T", "hiss_blue=NUU024-T", "hit_the_high_notes_red=AUA011-T", "hit_the_high_notes_yellow=AUA019-T", "hit_the_high_notes_blue=AUA021-T",
          "hoist_em_up_red=FAB349-T", "homage_to_ancestors_blue=MST096-T", "horns_of_the_despised=SUP079-T", "hostile_encroachment_red=FAB329-T",
          "humble_entrance_blue=GEM092-T", "hundred_winds_red=EVR041-T", "hundred_winds_yellow=EVR042-T", "hundred_winds_blue=EVR043-T", "hungering_demigon_red=LGS165-T",
          "hungering_demigon_yellow=LGS166-T", "hungering_demigon_blue=LGS167-T", "hunt_the_hunter_red=GEM014-T", "hunt_to_the_ends_of_rathe_red=GEM015-T",
          "hunts_end_red=HNT101-T", "hyper_x3=EVO011-T", "ice_eternal_blue=LGS110-T", "ignite_red=HNT058-T", "incision_red=GEM029-T", "inertia=FAB135-T",
          "inflame_red=UPR097-T", "inner_chi_blue=MST410-T", "invigorate_red=LGS080-T", "invigorate_yellow=LGS081-T", "invigorate_blue=LGS082-T",
          "invoke_azvolai_red=UPR009-T", "invoke_cromai_red=UPR010-T", "invoke_dominia_red=UPR008-T", "invoke_dracona_optimai_red=UPR006-T", "invoke_kyloria_red=UPR011-T",
          "invoke_miragai_red=UPR012-T", "invoke_nekria_red=UPR013-T", "invoke_ouvia_red=UPR014-T", "invoke_suraya_yellow=DYN212-T", "invoke_themai_red=UPR015-T",
          "invoke_tomeltai_red=UPR007-T", "invoke_vynserakai_red=UPR016-T", "invoke_yendurai_red=UPR017-T", "ironsong_pride_red=DYN072-T",
          "ironsong_response_red=LGS008-T", "ironsong_response_yellow=LGS030-T", "ironsong_response_blue=LGS031-T", "iyslander=UPR103-T", "jack_o_lantern_red=LGS176-T",
          "jack_o_lantern_yellow=LGS177-T", "jack_o_lantern_blue=LGS178-T", "jagged_edge_red=FAB311-T", "jittery_bones_red=GEM049-T",
          "kabuto_of_imperial_authority=HNT115-T", "kayo_strong_arm=SUP064-T", "kayo_underhanded_cheat=SUP063-T", "kelpie_tangled_mess_yellow=SEA059-T", "king_kraken_harpoon_red=SEA085-T",
          "king_shark_harpoon_red=SEA086-T", "kiss_of_death_red=HNT012-T", "knick_knack_bric_a_brac_red=EVR159-T", "koi_blessed_kimono=MST068-T", "kyloria=UPR411-T",
          "lace_with_bloodrot_red=LGS134-T", "lace_with_frailty_red=LGS135-T", "lace_with_inertia_red=LGS136-T", "lair_of_the_spider_red=LGS382-T",
          "leave_them_hanging_red=SUP074-T", "levels_of_enlightenment_blue=MST077-T", "ley_line_of_the_old_ones_blue=MPG013-T", "liars_charm_yellow=FAB373-T",
          "life_of_the_party_red=LGS084-T", "life_of_the_party_yellow=LGS085-T", "life_of_the_party_blue=LGS086-T", "lightning_form_red=LGS308-T",
          "lightning_press_red=FAB262-T", "limpit_hop_a_long_yellow=SEA060-T", "liquid_cooled_mayhem_red=LGS198-T", "liquid_cooled_mayhem_yellow=LGS199-T",
          "liquid_cooled_mayhem_blue=LGS200-T", "loan_shark_yellow=SEA131-T", "long_whisker_loyalty_red=FAB317-T", "looking_for_a_scrap_red=FAB356-T",
          "looming_doom_blue=DYN175-T", "loot_the_arsenal_blue=GEM083-T", "loot_the_hold_blue=GEM084-T", "low_blow_red=GEM093-T", "loyalty_beyond_the_grave_red=HNT150-T",
          "lubricate_blue=GEM044-T", "lumina_ascension_yellow=MON034-T", "lyath_goldmane=SUP072-T", "lyath_goldmane_vile_savant=SUP071-T", "macho_grande_red=LGS087-T",
          "macho_grande_yellow=LGS088-T", "macho_grande_blue=LGS089-T", "manifestation_of_miragai_blue=MST031-T", "march_of_loyalty_red=GEM016-T",
          "mark_of_the_beast_yellow=MON124-T", "mark_of_the_black_widow_red=GEM021-T", "mark_of_the_funnel_web_red=GEM022-T", "mark_the_prey_red=GEM023-T",
          "marlynn=SEA083-T", "marlynn_treasure_hunter=SEA082-T", "mask_of_deceit=HNT011-T", "mask_of_recurring_nightmares=MST004-T", "mechanical_strength_red=LGS224-T",
          "mechanical_strength_yellow=LGS225-T", "mechanical_strength_blue=LGS226-T", "meganetic_protocol_blue=FAB172-T", "metis_archangel_of_tenacity=DTD410-T",
          "might=FAB280-T", "miragai=UPR412-T", "mocking_blow_red=FAB381-T", "mocking_blow_yellow=FAB382-T", "mocking_blow_blue=FAB383-T", "money_where_ya_mouth_is_red=BET017-T",
          "money_where_ya_mouth_is_yellow=BET018-T", "money_where_ya_mouth_is_blue=BET025-T", "moray_le_fay_yellow=SEA051-T", "nasreth_the_soul_harrower=DTD193-T",
          "nekria=UPR413-T", "new_horizon=SUP256-T", "nimblism_red=GEM065-T", "nimby_red=GEM062-T", "nip_at_the_heels_blue=FAB326-T", "nitro_mechanoid=DYN492-T",
          "not_so_fast_yellow=SEA149-T", "nullrune_boots=FAB248-T", "nullrune_gloves=FAB247-T", "nullrune_hood=FAB245-T", "nullrune_robe=FAB246-T",
          "numbskull_charm_yellow=FAB370-T", "nuu=MST402-T", "nuu_alluring_desire=HER128-T", "oasis_respite_red=FAB231-T", "oath_of_loyalty_red=HNT149-T",
          "offensive_behavior_blue=LGS420-T", "old_leather_and_vim_red=LGS418-T", "on_the_horizon_red=GEM067-T",
          "onyx_amulet_blue=SEA191-T", "opal_amulet_blue=SEA192-T", "orb_weaver_spinneret_red=FAB323-T",
          "outside_interference_blue=SUP066-T", "ouvia=UPR414-T", "over_loop_red=LGS013-T", "over_loop_yellow=LGS033-T", "over_loop_blue=LGS034-T", "overcrowded_blue=LGS426-T",
          "overturn_the_results_blue=GEM094-T", "oysten_heart_of_gold_yellow=SEA263-T", "paddle_faster_red=FAB348-T", "palantir_aeronought_red=SEA012-T",
          "pass_over_blue=MST097-T", "path_well_traveled_blue=MST098-T", "pearl_amulet_blue=SEA193-T", "pendulum_trap_yellow=LGS139-T", "performance_bonus_red=FAB202-T",
          "phantasmify_red=LGS054-T", "phantasmify_yellow=LGS055-T", "phantasmify_blue=LGS056-T", "phoenix_flame_red=LGS104-T", "phoenix_form_red=UPR048-T",
          "pick_up_the_point_red=GEM028-T", "pick_a_card_any_card_red=EVR167-T", "pick_a_card_any_card_yellow=EVR168-T", "pick_a_card_any_card_blue=EVR169-T",
          "pinion_sentry_blue=GEM045-T", "pint_of_strong_and_stout_blue=LGS246-T", "platinum_amulet_blue=SEA194-T", "pledge_fealty_red=LGS385-T", "pleiades=SUP010-T",
          "pleiades_superstar=SUP009-T", "plunge_the_prospect_red=GEM024-T", "polar_blast_red=LGS072-T", "polar_blast_yellow=LGS073-T", "polar_blast_blue=LGS074-T",
          "polly_cranka=SEA003-T", "portside_exchange_blue=SEA145-T", "pounamu_amulet_blue=SEA195-T", "power_play_blue=GEM103-T", "power_stance_blue=FAB314-T",
          "predatory_streak_red=LGS122-T", "predatory_streak_yellow=LGS123-T", "predatory_streak_blue=LGS124-T", "preserve_tradition_blue=MST099-T",
          "prism_awakener_of_sol=GEM069-T", "prism_sculptor_of_arc_light=HER069-T", "proclaim_vengeance_red=GEM018-T", "promising_terrain_blue=LGS390-T",
          "provoke_blue=HNT117-T", "puffin=SEA002-T", "puffin_hightail=SEA401-T", "pulping_red=LGS051-T", "pulping_yellow=LGS052-T", "pulping_blue=LGS053-T",
          "pulsewave_protocol_yellow=FAB173-T", "pulverize_red=EVR021-T", "pummel_red=FAB375-T", "punch_above_your_weight_red=FAB374-T", "punching_gloves=LGS416-T",
          "put_em_in_their_place_red=MPG018-T", "put_in_context_blue=FAB325-T", "quicken=FAB282-T", "raise_an_army_yellow=FAB192-T", "rake_over_the_coals_red=GEM019-T",
          "rampart_of_the_rams_head=GEM079-T", "rapturous_applause_yellow=GEM089-T", "ravenous_rabble_red=FAB190-T", "razor_reflex_red=GEM032-T",
          "read_the_ripples_blue=FAB092-T", "reapers_call_red=WOD011-T", "reapers_call_yellow=WOD019-T", "reapers_call_blue=WOD022-T", "red_fin_harpoon_blue=GEM055-T",
          "red_hot_red=LGS108-T", "redback_shroud=OUT011-T", "reduce_to_runechant_red=LGS015-T", "reduce_to_runechant_yellow=LGS039-T", "reduce_to_runechant_blue=LGS040-T",
          "regicide_blue=FAB122-T", "release_the_tension_red=LGS096-T", "release_the_tension_yellow=LGS097-T", "release_the_tension_blue=LGS098-T",
          "relentless_pursuit_blue=LGS384-T", "restless_bones_blue=GEM050-T", "rewind_blue=UPR169-T", "riggermortis_yellow=SEA077-T",
          "rise_up_red=LGS106-T", "rising_sun_setting_moon_blue=MST100-T", "rok=GEM078-T", "rootbound_carapace_red=LGS352-T", "rootbound_carapace_yellow=LGS353-T",
          "rootbound_carapace_blue=LGS354-T", "rosetta_thorn=ROS256-T", "ruby_amulet_blue=SEA196-T", "run_into_trouble_red=LGS251-T", "runeblood_incantation_red=EVR107-T",
          "runeblood_incantation_yellow=EVR108-T", "runeblood_incantation_blue=EVR109-T", "runechant=AUA028-T", "runerager_swarm_red=LGS362-T", "runner_runner_red=FAB193-T",
          "rusty_harpoon_blue=GEM057-T", "sacred_art_immortal_lunar_shrine_blue=MST032-T", "sacred_art_jade_tiger_domain_blue=MST053-T", "sacred_art_undercurrent_desires_blue=MST010-T", "saltwater_swell_red=SEA141-T", "saltwater_swell_yellow=SEA142-T",
          "saltwater_swell_blue=SEA143-T", "sand_cover_red=FAB090-T", "sapphire_amulet_blue=SEA197-T", "savage_sash=GEM082-T", "sawbones_dock_hand_yellow=SEA264-T",
          "scar_tissue_red=GEM030-T", "scar_for_a_scar_red=FAB015-T", "scarf_for_a_scarf_red=LSS019-T", "scooba_salty_sea_dog_yellow=SEA061-T", "scrub_the_deck_blue=SEA147-T",
          "scuttle_the_canal_red=GEM025-T", "sea_floor_salvage_blue=SEA146-T", "sealace_sarong=SEA095-T", "second_tenet_of_chi_moon_blue=LGS301-T",
          "second_tenet_of_chi_tide_blue=LGS297-T", "second_tenet_of_chi_wind_blue=LGS305-T", "seismic_eruption_yellow=MPG024-T", "seismic_surge=MPG112-T",
          "sekem_archangel_of_ravages=DTD408-T", "shelly_hardened_traveler_yellow=SEA078-T", "shelter_from_the_storm_red=ANQ010-T", "shining_courage_red=GEM090-T",
          "show_of_strength_red=SUP128-T", "sic_em_shot_red=LGS014-T", "sic_em_shot_yellow=LGS036-T", "sic_em_shot_blue=LGS037-T", "sigil_of_aether_blue=LGS366-T",
          "sigil_of_brilliance_yellow=FAB270-T", "sigil_of_conductivity_blue=LGS324-T", "sigil_of_cycles_blue=LGS329-T", "sigil_of_deadwood_blue=LGS326-T",
          "sigil_of_earth_blue=LGS310-T", "sigil_of_forethought_blue=LGS327-T", "sigil_of_fyendal_blue=LGS330-T", "sigil_of_lightning_blue=LGS311-T",
          "sigil_of_sanctuary_blue=LGS323-T", "sigil_of_solace_red=FAB136-T", "sigil_of_temporal_manipulation_blue=LGS328-T", "sigil_of_the_arknight_blue=LGS325-T",
          "sink_below_red=FAB332-T", "sirens_of_safe_harbor_red=GEM063-T", "sky_skimmer_red=GEM042-T", "slap_happy_red=LGS257-T", "slice_and_dice_red=EVR057-T",
          "slice_and_dice_yellow=EVR058-T", "slice_and_dice_blue=EVR059-T", "slither=FAB242-T", "smash_up_red=FAB272-T", "smashback_alehorn_blue=LGS245-T",
          "smashing_ground_blue=GEM100-T", "smelting_of_the_old_ones_red=MPG028-T", "smoke_out_red=GEM017-T", "solid_ground_blue=MPG019-T",
          "soup_up_red=LGS195-T", "soup_up_yellow=LGS196-T", "soup_up_blue=LGS197-T", "spectral_shield=FAB285-T", "spew_obscenities_yellow=LGS421-T",
          "spike_with_bloodrot_red=FAB324-T", "spike_with_frailty_red=LGS131-T", "spike_with_inertia_red=LGS132-T", "spinning_wheel_kick_red=LGS141-T",
          "spinning_wheel_kick_yellow=LGS142-T", "spinning_wheel_kick_blue=LGS143-T", "spoiled_skull=DTD106-T", "stains_of_the_redback_red=FAB322-T",
          "standing_ovation_blue=GEM072-T", "steal_victory_blue=GEM087-T", "stir_the_pot_blue=MST101-T", "stoke_the_flames_red=UPR100-T", "strength_of_sequoia_red=LGS078-T",
          "strike_gold_red=GEM064-T", "sunken_treasure_blue=SEA133-T", "suraya_archangel_of_erudition=DTD405-T", "suraya_archangel_of_knowledge=DYN612-T",
          "swabbie_yellow=SEA079-T", "swift_shot_red=GEM061-T", "swiftwater_sloop_red=SEA166-T", "swiftwater_sloop_yellow=SEA167-T", "swiftwater_sloop_blue=SEA168-T",
          "swing_big_red=LGS427-T", "sworn_vengeance_red=FNG019-T", "sworn_vengeance_yellow=FNG022-T", "sworn_vengeance_blue=FNG026-T", "tag_the_target_red=CIN020-T",
          "tag_the_target_yellow=CIN022-T", "tag_the_target_blue=CIN026-T", "taipanis_dracai_of_judgement=JDG001-T", "take_that_red=GEM095-T",
          "take_it_on_the_chin_red=LGS256-T", "tales_of_adventure_blue=LSS005-T", "talk_a_big_game_blue=FAB195-T", "tarantula_toxin_red=FAB315-T",
          "tarpit_trap_yellow=LGS140-T", "taylor=LSS003-T", "teklovossen_the_mechropotent=EVO410-T", "ten_foot_tall_and_bulletproof_red=ROS217-T",
          "terminator_tank_red=EVO055-T", "test_of_agility_red=LGS261-T", "test_of_iron_grip_red=MPG025-T", "test_of_might_red=LGS260-T", "test_of_strength_red=FAB199-T",
          "test_of_vigor_red=LGS262-T", "testament_of_valahai=MPG404-T", "the_grain_that_tips_the_scale_blue=MST102-T", "the_hand_that_pulls_the_strings=ARK007-T",
          "the_suspense_is_killing_me_blue=SUP207-T", "themai=UPR415-T", "themis_archangel_of_judgment=DTD406-T", "thespian_charm_yellow=FAB371-T",
          "thick_hide_hunter_yellow=HNT246-T", "thunder_quake_red=EVR024-T", "thunder_quake_yellow=EVR025-T", "thunder_quake_blue=EVR026-T", "thunk_red=VIC011-T",
          "thunk_yellow=VIC018-T", "thunk_blue=VIC025-T", "timesnap_potion_blue=FAB224-T", "tip_the_barkeep_blue=SEA132-T", "to_be_continued_blue=SUP208-T",
          "toby_jugs=LGS417-T", "tomeltai=UPR407-T", "tongue_tied_red=FAB273-T", "tooth_of_the_dragon_red=GEM020-T", "toxicity_red=FAB128-T", "toxicity_yellow=FAB129-T",
          "toxicity_blue=FAB130-T", "traverse_the_universe=MST066-T", "treasure_island=FAB340-T", "trench_of_sunken_treasure=OUT094-T", "trip_the_light_fantastic_red=FAB275-T",
          "trot_along_blue=FAB327-T", "truce_blue=ROS219-T", "tuffnut=SUP002-T", "tuffnut_bumbling_hulkster=SUP001-T", "turning_point_blue=GEM086-T",
          "twelve_petal_kasaya=MST048-T", "twinning_blade_yellow=CRU082-T", "unmovable_red=FAB306-T", "uplifting_performance_blue=LGS419-T", "ursur_the_soul_reaper=FAB022-T",
          "vantom_banshee_red=LGS157-T", "vantom_banshee_yellow=LGS158-T",
          "vantom_banshee_blue=LGS159-T", "verdance=ROS414-T", "verdance_thorn_of_the_rose=ROS413-T", "vexing_malice_red=LGS057-T", "vexing_malice_yellow=LGS058-T",
          "vexing_malice_blue=LGS059-T", "victoria_archangel_of_triumph=DTD411-T",
          "vigor=FAB288-T", "villainous_pose_red=GEM097-T", "voltic_bolt_red=LGS016-T", "voltic_bolt_yellow=LGS042-T",
          "voltic_bolt_blue=LGS043-T", "vynserakai=UPR416-T", "wage_gold_red=FAB196-T", "wage_gold_yellow=FAB197-T", "wage_gold_blue=FAB198-T",
          "wailer_humperdinck_yellow=SEA052-T", "wall_of_meat_and_muscle_red=LGS250-T", "waning_vengeance_red=ENG011-T", "waning_vengeance_yellow=ENG013-T",
          "waning_vengeance_blue=ENG024-T", "war_machine_red=EVO056-T", "warband_of_bellona=EVO247-T", "warmongers_diplomacy_blue=SUP270-T",
          "wartune_herald_red=DTD029-T", "wartune_herald_yellow=DTD030-T", "wartune_herald_blue=DTD031-T", "weave_lightning_red=LGS075-T",
          "weave_lightning_yellow=LGS076-T", "weave_lightning_blue=LGS077-T", "what_happens_next_blue=SUP209-T", "whelming_gustwave_red=FAB249-T",
          "whelming_gustwave_yellow=LGS027-T", "whelming_gustwave_blue=LGS028-T", "whittle_from_bone_red=GEM026-T", "wide_blue_yonder_blue=FAB239-T",
          "widespread_annihilation_blue=DTD137-T", "widespread_destruction_yellow=DTD138-T", "widespread_ruin_red=DTD139-T", "wind_up_the_crowd_blue=GEM085-T",
          "winds_of_eternity_blue=EVR040-T", "wrath_of_retribution_red=HNT061-T", "wrecker_romp_red=LGS020-T", "wrecker_romp_yellow=LGS021-T",
          "wrecker_romp_blue=FAB200-T", "yellow_fin_harpoon_blue=GEM056-T", "yendurai=UPR417-T", "yo_ho_ho_blue=GEM053-T", "zero_to_sixty_red=FAB177-T",
          "amethyst_tiara=DYN171-T", "ash=UPR443-T", "blasmophet_levia_consumed=DTD564-T", "blazen_yoroi=DYN045-T", "celestial_kimono=DYN213-T", 
          "chart_a_course_red=SEA173-T", "chart_a_course_yellow=SEA174-T", "chart_a_course_blue=SEA175-T", "divvy_up_blue=SEA144-T", 
          "emperor_dracai_of_aesir=DYN001-T", "evo_atom_breaker_red=EVO031-T", "evo_circuit_breaker_red=EVO030-T", "evo_face_breaker_red=EVO032-T", 
          "evo_mach_breaker_red=EVO033-T", "evo_steel_soul_controller_blue=EVO028-T", "evo_steel_soul_memory_blue=EVO026-T", "evo_steel_soul_processor_blue=EVO027-T",
          "evo_steel_soul_tower_blue=EVO029-T", "golden_tipple_red=SEA159-T", "golden_tipple_yellow=SEA160-T", "golden_tipple_blue=SEA161-T", 
          "hanabi_blaster=DYN088-T", "levia_redeemed=DTD164-T", "lost_in_transit_yellow=SEA151-T", "merciless_battleaxe=DYN068-T", "murderous_rabble_blue=SEA137-T", 
          "mutiny_on_the_battalion_barque_blue=SEA176-T", "mutiny_on_the_nimbus_sovereign_blue=SEA177-T", "mutiny_on_the_swiftwater_blue=SEA178-T", 
          "pilfer_the_wreck_red=SEA138-T", "pilfer_the_wreck_yellow=SEA139-T", "pilfer_the_wreck_blue=SEA140-T", "rok=DYN005-T", "sandscour_greatbow=DYN151-T", 
          "seasoned_saviour=DYN026-T", "shifting_tides_blue=SEA148-T", "singularity_red=EVO010-T", "surgent_aethertide=DYN192-T", "swindlers_grift_red=SEA169-T", 
          "swindlers_grift_yellow=SEA170-T", "swindlers_grift_blue=SEA171-T", "thievn_varmints_red=SEA172-T", "throw_caution_to_the_wind_blue=SEA150-T", "electrify_blue=ELE200-T",
          "goliath_gauntler=WTR153-T", "hidden_agenda=AAZ005-T", "goldkiss_rum=SEA245-T"
      ];

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
      case "17": // Scowling
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
      case "14707687": return "88,100";
      case "18": return "96";
      case "19": return "105";
      case "20": return "106";
      case "21": return "112";
      case "22": return "118";
      case "23": return "122";
      case "24": return "123";
      case "25": return "124,125";
      case "26": return "127";
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
      case "11481720": return "65";
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
        return "37,38,39,40,41";
      default: return "";
    }
  }
}
