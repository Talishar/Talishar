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
  case TeamEmperorsRome = "10";
  case SunflowerSamurai = "11";
  case ColdFoilControl = "12";
  case RighteousGaming = "13";
  case TeamTalishar = "14";
  case TeamTideBreakers = "15";
  case CupofTCG = "16";
  case ScowlingFleshBag = "17";
  case ThaiCardsShop = "18";
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
      case "17": return "ScowlingFleshBag";
      case "18": return "Thai Cards Shop";
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
      case "14": return IsTeamTalishar($userName);
      case "15": return IsTeamTideBreakers($userName);
      case "16": return IsTeamCupofTCG($userName);
      case "17": return IsTeamScowlingFleshBag($userName);
      case "18": return IsTeamThaiCardsShop($userName);
      case "7198186": return ($userName == "OotTheMonk");
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
      case "9734205": return ($userName == "tog" || $userName == "bnet" || $userName == "balakay" || $userName == "PvtVoid");
      case "9574942": return ($userName == "Azor" || $userName == "PvtVoid");
      case "11561507": return ($userName == "PvtVoid");
      case "9555916": return ($userName == "Cathardigan" || $userName == "GorganianTome" || $userName == "PvtVoid");
      case "8716783": return IsTeamFABChaos($userName);
      case "11266104": return ($userName == "Lupinefiasco" || $userName == "PvtVoid");
      case "12144126": return ($userName == "Pentregarth" || $userName == "Archon Alters" || $userName == "PvtVoid");
      case "12245100": return ($userName == "PvtVoid" || $userName == "Smithel" || $userName == "Poopking" || $userName == "HeyLookItsBrice");
      case "12300349": return ($userName == "PvtVoid");
      case "10956423": return ($userName == "ChantsAndDaggers" || $userName == "OotTheMonk");
      case "12977197": return ($userName == "PvtVoid" || $userName == "dazzyfizzle" || $userName == "OotTheMonk");
      case "13586904": return ($userName == "PvtVoid" || $userName == "Nikobru");
      default: return "";
    }
  }

  public function AltArts(): string
  {
    switch($this->value)
    {
      //Talishar
      case "7198186": return "lightning_press_red=lightning_press_red-T,rosetta_thorn=rosetta_thorn-T,mask_of_recurring_nightmares=mask_of_recurring_nightmares-T,sacred_art_undercurrent_desires_blue=sacred_art_undercurrent_desires_blue-T,sacred_art_immortal_lunar_shrine_blue=sacred_art_immortal_lunar_shrine_blue-T,twelve_petal_kasaya=twelve_petal_kasaya-T,sacred_art_jade_tiger_domain_blue=sacred_art_jade_tiger_domain_blue-T,traverse_the_universe=traverse_the_universe-T,koi_blessed_kimono=koi_blessed_kimono-T,a_drop_in_the_ocean_blue=a_drop_in_the_ocean_blue-T,homage_to_ancestors_blue=homage_to_ancestors_blue-T,pass_over_blue=pass_over_blue-T,path_well_traveled_blue=path_well_traveled_blue-T,preserve_tradition_blue=preserve_tradition_blue-T,rising_sun_setting_moon_blue=rising_sun_setting_moon_blue-T,stir_the_pot_blue=stir_the_pot_blue-T,the_grain_that_tips_the_scale_blue=the_grain_that_tips_the_scale_blue-T,mistcloak_gully_inner_chi_blue=mistcloak_gully_inner_chi_blue-T,sacred_art_undercurrent_desires_blue_inner_chi_blue=sacred_art_undercurrent_desires_blue_inner_chi_blue-T,sacred_art_immortal_lunar_shrine_blue_inner_chi_blue=sacred_art_immortal_lunar_shrine_blue_inner_chi_blue-T,sacred_art_jade_tiger_domain_blue_inner_chi_blue=sacred_art_jade_tiger_domain_blue_inner_chi_blue-T,MST095_inner_chi_blue=MST095_inner_chi_blue-T,MST096_inner_chi_blue=MST096_inner_chi_blue-T,pass_over_blue_inner_chi_blue=pass_over_blue_inner_chi_blue-T,path_well_traveled_blue_inner_chi_blue=path_well_traveled_blue_inner_chi_blue-T,preserve_tradition_blue_inner_chi_blue=preserve_tradition_blue_inner_chi_blue-T,rising_sun_setting_moon_blue_inner_chi_blue=rising_sun_setting_moon_blue_inner_chi_blue-T,stir_the_pot_blue_inner_chi_blue=stir_the_pot_blue_inner_chi_blue-T,the_grain_that_tips_the_scale_blue_inner_chi_blue=the_grain_that_tips_the_scale_blue_inner_chi_blue-T,sigil_of_brilliance_yellow=sigil_of_brilliance_yellow-T,barkskin_of_the_millennium_tree=barkskin_of_the_millennium_tree-T,earth_form_red=earth_form_red-T,rootbound_carapace_red=rootbound_carapace_red-T,rootbound_carapace_yellow=rootbound_carapace_yellow-T,rootbound_carapace_blue=rootbound_carapace_blue-T,sigil_of_sanctuary_blue=sigil_of_sanctuary_blue-T,sigil_of_earth_blue=sigil_of_earth_blue-T,lightning_form_red=lightning_form_red-T,sigil_of_conductivity_blue=sigil_of_conductivity_blue-T,flittering_charge_red=flittering_charge_red-T,flittering_charge_yellow=flittering_charge_yellow-T,flittering_charge_blue=flittering_charge_blue-T,electrostatic_discharge_red=electrostatic_discharge_red-T,electrostatic_discharge_yellow=electrostatic_discharge_yellow-T,electrostatic_discharge_blue=electrostatic_discharge_blue-T,sigil_of_lightning_blue=sigil_of_lightning_blue-T,face_purgatory=face_purgatory-T,sigil_of_the_arknight_blue=sigil_of_the_arknight_blue-T,runerager_swarm_red=runerager_swarm_red-T,deadwood_dirge_red=deadwood_dirge_red-T,sigil_of_deadwood_blue=sigil_of_deadwood_blue-T,sigil_of_aether_blue=sigil_of_aether_blue-T,glyph_overlay_red=glyph_overlay_red-T,sigil_of_temporal_manipulation_blue=sigil_of_temporal_manipulation_blue-T,sigil_of_forethought_blue=sigil_of_forethought_blue-T,arcanite_fortress=arcanite_fortress-T,ten_foot_tall_and_bulletproof_red=ten_foot_tall_and_bulletproof_red-T,call_to_the_grave_blue=call_to_the_grave_blue-T,sigil_of_cycles_blue=sigil_of_cycles_blue-T,sigil_of_fyendal_blue=sigil_of_fyendal_blue-T,chivalry_blue=chivalry_blue-T,fyendals_fighting_spirit_red=fyendals_fighting_spirit_red-T,flic_flak_red=flic_flak_red-T,timesnap_potion_blue=timesnap_potion_blue-T,sigil_of_solace_red=sigil_of_solace_red-T,command_and_conquer_red=command_and_conquer_red-T,bare_fangs_red=bare_fangs_red-T,bare_fangs_yellow=bare_fangs_yellow-T,bare_fangs_blue=bare_fangs_blue-T,swarming_gloomveil_red=swarming_gloomveil_red-T,exude_confidence_red=exude_confidence_red-T,erase_face_red=erase_face_red-T,rhinar_reckless_rampage=rhinar_reckless_rampage-T,rhinar=rhinar-T,head_jab_red=head_jab_red-T,head_jab_yellow=head_jab_yellow-T,head_jab_blue=head_jab_blue-T,fyendals_spring_tunic=fyendals_spring_tunic-T,crazy_brew_blue=crazy_brew_blue-T,cracked_bauble_yellow=cracked_bauble_yellow-T,ravenous_rabble_red=ravenous_rabble_red-T,twinning_blade_yellow=twinning_blade_yellow-T,galaxxi_black=galaxxi_black-T,blood_tribute_red=blood_tribute_red-T,blood_tribute_yellow=blood_tribute_yellow-T,blood_tribute_blue=blood_tribute_blue-T,blasmophet_the_soul_harvester=blasmophet_the_soul_harvester-T,ursur_the_soul_reaper=ursur_the_soul_reaper-T,channel_lake_frigid_blue=channel_lake_frigid_blue-T,earthlore_bounty=earthlore_bounty-T,invoke_dracona_optimai_red=invoke_dracona_optimai_red-T,invoke_tomeltai_red=invoke_tomeltai_red-T,invoke_dominia_red=invoke_dominia_red-T,invoke_azvolai_red=invoke_azvolai_red-T,invoke_cromai_red=invoke_cromai_red-T,invoke_kyloria_red=invoke_kyloria_red-T,invoke_miragai_red=invoke_miragai_red-T,invoke_nekria_red=invoke_nekria_red-T,invoke_ouvia_red=invoke_ouvia_red-T,invoke_themai_red=invoke_themai_red-T,invoke_vynserakai_red=invoke_vynserakai_red-T,invoke_yendurai_red=invoke_yendurai_red-T,aether_ashwing=aether_ashwing-T,ash=ash-T,rewind_blue=rewind_blue-T,dracona_optimai=dracona_optimai-T,tomeltai=tomeltai-T,dominia=dominia-T,azvolai=azvolai-T,cromai=cromai-T,kyloria=kyloria-T,miragai=miragai-T,nekria=nekria-T,ouvia=ouvia-T,themai=themai-T,vynserakai=vynserakai-T,yendurai=yendurai-T,emperor_dracai_of_aesir=emperor_dracai_of_aesir-T,rok=rok-T,seasoned_saviour=seasoned_saviour-T,blazen_yoroi=blazen_yoroi-T,crouching_tiger=crouching_tiger-T,merciless_battleaxe=merciless_battleaxe-T,hanabi_blaster=hanabi_blaster-T,construct_nitro_mechanoid_yellow=construct_nitro_mechanoid_yellow-T,regicide_blue=regicide_blue-T,sandscour_greatbow=sandscour_greatbow-T,amethyst_tiara=amethyst_tiara-T,surgent_aethertide=surgent_aethertide-T,invoke_suraya_yellow=invoke_suraya_yellow-T,celestial_kimono=celestial_kimono-T,crown_of_dominion=crown_of_dominion-T,nitro_mechanoida=nitro_mechanoida-T,nitro_mechanoidb=nitro_mechanoidb-T,nitro_mechanoidc=nitro_mechanoidc-T,suraya_archangel_of_knowledge=suraya_archangel_of_knowledge-T,figment_of_erudition_yellow=figment_of_erudition_yellow-T,figment_of_judgment_yellow=figment_of_judgment_yellow-T,figment_of_protection_yellow=figment_of_protection_yellow-T,figment_of_ravages_yellow=figment_of_ravages_yellow-T,figment_of_rebirth_yellow=figment_of_rebirth_yellow-T,figment_of_tenacity_yellow=figment_of_tenacity_yellow-T,figment_of_triumph_yellow=figment_of_triumph_yellow-T,figment_of_war_yellow=figment_of_war_yellow-T,levia_redeemed=levia_redeemed-T,levia_redeemed=levia_redeemed-T,suraya_archangel_of_erudition=suraya_archangel_of_erudition-T,themis_archangel_of_judgment=themis_archangel_of_judgment-T,aegis_archangel_of_protection=aegis_archangel_of_protection-T,sekem_archangel_of_ravages=sekem_archangel_of_ravages-T,avalon_archangel_of_rebirth=avalon_archangel_of_rebirth-T,metis_archangel_of_tenacity=metis_archangel_of_tenacity-T,victoria_archangel_of_triumph=victoria_archangel_of_triumph-T,bellona_archangel_of_war=bellona_archangel_of_war-T,singularity_red=singularity_red-T,evo_steel_soul_memory_blue=evo_steel_soul_memory_blue-T,evo_steel_soul_processor_blue=evo_steel_soul_processor_blue-T,evo_steel_soul_controller_blue=evo_steel_soul_controller_blue-T,evo_steel_soul_tower_blue=evo_steel_soul_tower_blue-T,evo_circuit_breaker_red=evo_circuit_breaker_red-T,evo_atom_breaker_red=evo_atom_breaker_red-T,evo_face_breaker_red=evo_face_breaker_red-T,evo_mach_breaker_red=evo_mach_breaker_red-T,teklovossen_the_mechropotent=teklovossen_the_mechropotent-T,teklovossen_the_mechropotenta=teklovossen_the_mechropotenta-T,teklovossen_the_mechropotentb=teklovossen_the_mechropotentb-T,evo_steel_soul_memory_blue_equip=evo_steel_soul_memory_blue_equip-T,evo_steel_soul_processor_blue_equip=evo_steel_soul_processor_blue_equip-T,evo_steel_soul_controller_blue_equip=evo_steel_soul_controller_blue_equip-T,evo_steel_soul_tower_blue_equip=evo_steel_soul_tower_blue_equip-T,peace_of_mind_red=peace_of_mind_red-T,in_the_swing_red=in_the_swing_red-T,wrecker_romp_blue=wrecker_romp_blue-T,electrify_blue=electrify_blue-T,sap_red=sap_red-T,sap_yellow=sap_yellow-T,sap_blue=sap_blue-T,iris_of_reality=iris_of_reality-T,zero_to_sixty_red=zero_to_sixty_red-T,stir_the_wildwood_red=stir_the_wildwood_red-T,fate_foreseen_red=fate_foreseen_red-T,scar_for_a_scar_red=scar_for_a_scar_red-T,boneyard_marauder_red=boneyard_marauder_red-T,sand_cover_red=sand_cover_red-T,oasis_respite_red=oasis_respite_red-T,energy_potion_blue=energy_potion_blue-T";
      //PvtVoid
      case "9408649": return "lightning_press_red=lightning_press_red-T,rosetta_thorn=rosetta_thorn-T,mask_of_recurring_nightmares=mask_of_recurring_nightmares-T,sacred_art_undercurrent_desires_blue=sacred_art_undercurrent_desires_blue-T,sacred_art_immortal_lunar_shrine_blue=sacred_art_immortal_lunar_shrine_blue-T,twelve_petal_kasaya=twelve_petal_kasaya-T,sacred_art_jade_tiger_domain_blue=sacred_art_jade_tiger_domain_blue-T,traverse_the_universe=traverse_the_universe-T,koi_blessed_kimono=koi_blessed_kimono-T,a_drop_in_the_ocean_blue=a_drop_in_the_ocean_blue-T,homage_to_ancestors_blue=homage_to_ancestors_blue-T,pass_over_blue=pass_over_blue-T,path_well_traveled_blue=path_well_traveled_blue-T,preserve_tradition_blue=preserve_tradition_blue-T,rising_sun_setting_moon_blue=rising_sun_setting_moon_blue-T,stir_the_pot_blue=stir_the_pot_blue-T,the_grain_that_tips_the_scale_blue=the_grain_that_tips_the_scale_blue-T,mistcloak_gully_inner_chi_blue=mistcloak_gully_inner_chi_blue-T,sacred_art_undercurrent_desires_blue_inner_chi_blue=sacred_art_undercurrent_desires_blue_inner_chi_blue-T,sacred_art_immortal_lunar_shrine_blue_inner_chi_blue=sacred_art_immortal_lunar_shrine_blue_inner_chi_blue-T,sacred_art_jade_tiger_domain_blue_inner_chi_blue=sacred_art_jade_tiger_domain_blue_inner_chi_blue-T,MST095_inner_chi_blue=MST095_inner_chi_blue-T,MST096_inner_chi_blue=MST096_inner_chi_blue-T,pass_over_blue_inner_chi_blue=pass_over_blue_inner_chi_blue-T,path_well_traveled_blue_inner_chi_blue=path_well_traveled_blue_inner_chi_blue-T,preserve_tradition_blue_inner_chi_blue=preserve_tradition_blue_inner_chi_blue-T,rising_sun_setting_moon_blue_inner_chi_blue=rising_sun_setting_moon_blue_inner_chi_blue-T,stir_the_pot_blue_inner_chi_blue=stir_the_pot_blue_inner_chi_blue-T,the_grain_that_tips_the_scale_blue_inner_chi_blue=the_grain_that_tips_the_scale_blue_inner_chi_blue-T,sigil_of_brilliance_yellow=sigil_of_brilliance_yellow-T,barkskin_of_the_millennium_tree=barkskin_of_the_millennium_tree-T,earth_form_red=earth_form_red-T,rootbound_carapace_red=rootbound_carapace_red-T,rootbound_carapace_yellow=rootbound_carapace_yellow-T,rootbound_carapace_blue=rootbound_carapace_blue-T,sigil_of_sanctuary_blue=sigil_of_sanctuary_blue-T,sigil_of_earth_blue=sigil_of_earth_blue-T,lightning_form_red=lightning_form_red-T,sigil_of_conductivity_blue=sigil_of_conductivity_blue-T,flittering_charge_red=flittering_charge_red-T,flittering_charge_yellow=flittering_charge_yellow-T,flittering_charge_blue=flittering_charge_blue-T,electrostatic_discharge_red=electrostatic_discharge_red-T,electrostatic_discharge_yellow=electrostatic_discharge_yellow-T,electrostatic_discharge_blue=electrostatic_discharge_blue-T,sigil_of_lightning_blue=sigil_of_lightning_blue-T,face_purgatory=face_purgatory-T,sigil_of_the_arknight_blue=sigil_of_the_arknight_blue-T,runerager_swarm_red=runerager_swarm_red-T,deadwood_dirge_red=deadwood_dirge_red-T,sigil_of_deadwood_blue=sigil_of_deadwood_blue-T,sigil_of_aether_blue=sigil_of_aether_blue-T,glyph_overlay_red=glyph_overlay_red-T,sigil_of_temporal_manipulation_blue=sigil_of_temporal_manipulation_blue-T,sigil_of_forethought_blue=sigil_of_forethought_blue-T,arcanite_fortress=arcanite_fortress-T,ten_foot_tall_and_bulletproof_red=ten_foot_tall_and_bulletproof_red-T,call_to_the_grave_blue=call_to_the_grave_blue-T,sigil_of_cycles_blue=sigil_of_cycles_blue-T,sigil_of_fyendal_blue=sigil_of_fyendal_blue-T,chivalry_blue=chivalry_blue-T,fyendals_fighting_spirit_red=fyendals_fighting_spirit_red-T,flic_flak_red=flic_flak_red-T,timesnap_potion_blue=timesnap_potion_blue-T,rhinar_reckless_rampage=rhinar_reckless_rampage-T,rhinar=rhinar-T,head_jab_red=head_jab_red-T,head_jab_yellow=head_jab_yellow-T,head_jab_blue=head_jab_blue-T,fyendals_spring_tunic=fyendals_spring_tunic-T,crazy_brew_blue=crazy_brew_blue-T,sigil_of_solace_red=sigil_of_solace_red-T,cracked_bauble_yellow=cracked_bauble_yellow-T,ravenous_rabble_red=ravenous_rabble_red-T,twinning_blade_yellow=twinning_blade_yellow-T,galaxxi_black=galaxxi_black-T,blood_tribute_red=blood_tribute_red-T,blood_tribute_yellow=blood_tribute_yellow-T,blood_tribute_blue=blood_tribute_blue-T,blasmophet_the_soul_harvester=blasmophet_the_soul_harvester-T,ursur_the_soul_reaper=ursur_the_soul_reaper-T,channel_lake_frigid_blue=channel_lake_frigid_blue-T,earthlore_bounty=earthlore_bounty-T,invoke_dracona_optimai_red=invoke_dracona_optimai_red-T,invoke_tomeltai_red=invoke_tomeltai_red-T,invoke_dominia_red=invoke_dominia_red-T,invoke_azvolai_red=invoke_azvolai_red-T,invoke_cromai_red=invoke_cromai_red-T,invoke_kyloria_red=invoke_kyloria_red-T,invoke_miragai_red=invoke_miragai_red-T,invoke_nekria_red=invoke_nekria_red-T,invoke_ouvia_red=invoke_ouvia_red-T,invoke_themai_red=invoke_themai_red-T,invoke_vynserakai_red=invoke_vynserakai_red-T,invoke_yendurai_red=invoke_yendurai_red-T,aether_ashwing=aether_ashwing-T,ash=ash-T,rewind_blue=rewind_blue-T,dracona_optimai=dracona_optimai-T,tomeltai=tomeltai-T,dominia=dominia-T,azvolai=azvolai-T,cromai=cromai-T,kyloria=kyloria-T,miragai=miragai-T,nekria=nekria-T,ouvia=ouvia-T,themai=themai-T,vynserakai=vynserakai-T,yendurai=yendurai-T,emperor_dracai_of_aesir=emperor_dracai_of_aesir-T,rok=rok-T,seasoned_saviour=seasoned_saviour-T,blazen_yoroi=blazen_yoroi-T,crouching_tiger=crouching_tiger-T,merciless_battleaxe=merciless_battleaxe-T,hanabi_blaster=hanabi_blaster-T,construct_nitro_mechanoid_yellow=construct_nitro_mechanoid_yellow-T,regicide_blue=regicide_blue-T,sandscour_greatbow=sandscour_greatbow-T,amethyst_tiara=amethyst_tiara-T,surgent_aethertide=surgent_aethertide-T,invoke_suraya_yellow=invoke_suraya_yellow-T,celestial_kimono=celestial_kimono-T,crown_of_dominion=crown_of_dominion-T,nitro_mechanoida=nitro_mechanoida-T,nitro_mechanoidb=nitro_mechanoidb-T,nitro_mechanoidc=nitro_mechanoidc-T,suraya_archangel_of_knowledge=suraya_archangel_of_knowledge-T,figment_of_erudition_yellow=figment_of_erudition_yellow-T,figment_of_judgment_yellow=figment_of_judgment_yellow-T,figment_of_protection_yellow=figment_of_protection_yellow-T,figment_of_ravages_yellow=figment_of_ravages_yellow-T,figment_of_rebirth_yellow=figment_of_rebirth_yellow-T,figment_of_tenacity_yellow=figment_of_tenacity_yellow-T,figment_of_triumph_yellow=figment_of_triumph_yellow-T,figment_of_war_yellow=figment_of_war_yellow-T,levia_redeemed=levia_redeemed-T,levia_redeemed=levia_redeemed-T,suraya_archangel_of_erudition=suraya_archangel_of_erudition-T,themis_archangel_of_judgment=themis_archangel_of_judgment-T,aegis_archangel_of_protection=aegis_archangel_of_protection-T,sekem_archangel_of_ravages=sekem_archangel_of_ravages-T,avalon_archangel_of_rebirth=avalon_archangel_of_rebirth-T,metis_archangel_of_tenacity=metis_archangel_of_tenacity-T,victoria_archangel_of_triumph=victoria_archangel_of_triumph-T,bellona_archangel_of_war=bellona_archangel_of_war-T,singularity_red=singularity_red-T,evo_steel_soul_memory_blue=evo_steel_soul_memory_blue-T,evo_steel_soul_processor_blue=evo_steel_soul_processor_blue-T,evo_steel_soul_controller_blue=evo_steel_soul_controller_blue-T,evo_steel_soul_tower_blue=evo_steel_soul_tower_blue-T,evo_circuit_breaker_red=evo_circuit_breaker_red-T,evo_atom_breaker_red=evo_atom_breaker_red-T,evo_face_breaker_red=evo_face_breaker_red-T,evo_mach_breaker_red=evo_mach_breaker_red-T,teklovossen_the_mechropotent=teklovossen_the_mechropotent-T,teklovossen_the_mechropotenta=teklovossen_the_mechropotenta-T,teklovossen_the_mechropotentb=teklovossen_the_mechropotentb-T,evo_steel_soul_memory_blue_equip=evo_steel_soul_memory_blue_equip-T,evo_steel_soul_processor_blue_equip=evo_steel_soul_processor_blue_equip-T,evo_steel_soul_controller_blue_equip=evo_steel_soul_controller_blue_equip-T,evo_steel_soul_tower_blue_equip=evo_steel_soul_tower_blue_equip-T,peace_of_mind_red=peace_of_mind_red-T,in_the_swing_red=in_the_swing_red-T,wrecker_romp_blue=wrecker_romp_blue-T,electrify_blue=electrify_blue-T,sap_red=sap_red-T,sap_yellow=sap_yellow-T,sap_blue=sap_blue-T,iris_of_reality=iris_of_reality-T,zero_to_sixty_red=zero_to_sixty_red-T,stir_the_wildwood_red=stir_the_wildwood_red-T,fate_foreseen_red=fate_foreseen_red-T,scar_for_a_scar_red=scar_for_a_scar_red-T,boneyard_marauder_red=boneyard_marauder_red-T,sand_cover_red=sand_cover_red-T,oasis_respite_red=oasis_respite_red-T,energy_potion_blue=energy_potion_blue-T";      
      //ManSant
      case "8955846": return "levia_shadowborn_abomination=levia_shadowborn_abomination-ManSant,levia=levia-ManSant,agility=agility-ManSant,might=might-ManSant";
      //Brandao
      case "279086": return "dash_inventor_extraordinaire=dash_inventor_extraordinaire-Brandao,kano_dracai_of_aether=kano_dracai_of_aether-Brandao,kassai_cintari_sellsword=kassai_cintari_sellsword-Brandao,lexi_livewire=lexi_livewire-Brandao,riptide_lurker_of_the_deep=riptide_lurker_of_the_deep-Brandao,dromai_ash_artist=dromai_ash_artist-Brandao,bravo_showstopper=bravo_showstopper-Brandao,teklo_plasma_pistol=teklo_plasma_pistol-Brandao,cintari_saber=cintari_saber-Brandao,cintari_saber=cintari_saber-Brandao,copper=copper-Brandao,frostbite=frostbite-Brandao,storm_of_sandikai=storm_of_sandikai-Brandao,aether_ashwing=aether_ashwing-Brandao,ash=ash-Brandao,anothos=anothos-Brandao,seismic_surge=seismic_surge-Brandao,runechant=runechant_Brandao,harmonized_kodachi=harmonized_kodachi_Brandao,vynnset=vynnset_Brandao,flail_of_agony=flail_of_agony_Brandao,maxx_the_hype_nitro=maxx_the_hype_nitro_Brandao,banksy=banksy_Brandao,hyper_driver=hyper_driver_Brandao,katsu_the_wanderer=katsu_the_wanderer_Brandao,harmonized_kodachi=harmonized_kodachi_Brandao,mandible_claw=mandible_claw_Brandao,mandible_claw=mandible_claw_Brandao,courage=courage_Brandao,rhinar_reckless_rampage=rhinar_reckless_rampage_Brandao,dorinthea_ironsong=dorinthea_ironsong_Brandao,dawnblade=dawnblade_Brandao";
      //TeamTalishar
      case "14": return "embodiment_of_earth=embodiment_of_earth-Promo,embodiment_of_lightning=embodiment_of_lightning-Promo";
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
      case "10": return "67";
      case "11": return "70";
      case "12": return "75";
      case "13": return "77,78";
      case "14": return "82,83";
      case "15": return "84";
      case "16": return "87";
      case "17": return "88";
      case "18": return "96";
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
      case "8458487": return "31";
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
      case "12977197": return "89,90,91,92,93,94,95";
      case "13586904": return "97";
      default: return "";
    }
  }
}
