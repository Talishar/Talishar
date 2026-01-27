<?php


//Return 1 if the effect should be removed
function EffectHitEffect($cardID, $from, $source = "-", $effectSource  = "-")
{
  global $combatChainState, $CCS_GoesWhereAfterLinkResolves, $defPlayer, $mainPlayer, $CCS_WeaponIndex, $CombatChain, $CCS_DamageDealt;
  global $CID_BloodRotPox, $CID_Frailty, $CID_Inertia, $Card_LifeBanner, $Card_ResourceBanner, $layers, $EffectContext;
  global $chainLinks, $chainLinkSummary, $CCS_AttackTargetUID;
  $attackID = $CombatChain->AttackCard()->ID();
  if ($source == "-") {
    if (CardType($attackID) == "AA" && SearchCurrentTurnEffects("tarpit_trap_yellow", $mainPlayer, count($layers) < LayerPieces())) {
      WriteLog("Hit effect prevented by " . CardLink("tarpit_trap_yellow", "tarpit_trap_yellow"));
      return true;
    }
  }
  else if (CardType($source) == "AA" && SearchCurrentTurnEffects("tarpit_trap_yellow", $mainPlayer, count($layers) < LayerPieces())) {
    WriteLog("Hit effect prevented by " . CardLink("tarpit_trap_yellow", "tarpit_trap_yellow"));
    return true;
  }
  $effectArr = explode(",", $cardID);
  $cardID = $effectArr[0];
  $mode = explode("-", $cardID)[1] ?? "-";
  $card = GetClass($cardID, $mainPlayer);
  if ($card != "-") return $card->EffectHitEffect($from, $source, $effectSource, $effectArr[1] ?? "-", $mode);
  switch ($cardID) {
    case "warriors_valor_red":
    case "warriors_valor_yellow":
    case "warriors_valor_blue":
      GiveAttackGoAgain();
      break;
    case "natures_path_pilgrimage_red":
    case "natures_path_pilgrimage_yellow":
    case "natures_path_pilgrimage_blue":
      NaturesPathPilgrimageHit();
      break;
    case "pummel_red":
    case "pummel_yellow":
    case "pummel_blue":
      if (IsHeroAttackTarget() && CardType($attackID) == "AA") PummelHit();
      break;
    case "razor_reflex_red":
    case "razor_reflex_yellow":
    case "razor_reflex_blue":
      if (CardType($attackID) == "AA") GiveAttackGoAgain();
      break;
    case "plunder_run_red-1":
    case "plunder_run_yellow-1":
    case "plunder_run_blue-1":
      Draw($mainPlayer);
      return 1;
    case "poison_the_tips_yellow":
      if (IsHeroAttackTarget()) PummelHit();
      break;
    case "mauvrion_skies_red":
    case "mauvrion_skies_yellow":
    case "mauvrion_skies_blue":
      if (ClassContains($attackID, "RUNEBLADE", $mainPlayer)) {
        if ($cardID == "mauvrion_skies_red") $amount = 3;
        else if ($cardID == "mauvrion_skies_yellow") $amount = 2;
        else $amount = 1;
        PlayAura("runechant", $mainPlayer, $amount);
      }
      break;
    case "succumb_to_temptation_yellow":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to discard", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDISCARD", $mainPlayer, "HAND," . $mainPlayer, 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      return 1;
    case "spoils_of_war_red-2":
      PutItemIntoPlayForPlayer("copper", $mainPlayer, 0, 2);
      break;
    case "lumina_ascension_yellow":
      $deck = new Deck($mainPlayer);
      if (!$deck->Reveal()) return;
      $top = $deck->Top(remove: true);
      if (TalentContains($top, "LIGHT", $mainPlayer)) {
        AddSoul($top, $mainPlayer, "DECK");
        GainHealth(1, $mainPlayer);
      } else $deck->AddBottom($top, "DECK");
      break;
    case "seek_enlightenment_red":
    case "seek_enlightenment_yellow":
    case "seek_enlightenment_blue":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "SOUL";
      break;
    case "dusk_path_pilgrimage_red":
    case "dusk_path_pilgrimage_yellow":
    case "dusk_path_pilgrimage_blue":
      DuskPathPilgrimageHit();
      break;
    case "shadow_puppetry_red":
      ShadowPuppetryHitEffect();
      break;
    case "eclipse_existence_blue":
      if (count(GetSoul($defPlayer)) > 0) {
        BanishFromSoul($defPlayer);
        LoseHealth(1, $defPlayer);
      }
      break;
    case "warmongers_recital_red":
    case "warmongers_recital_yellow":
    case "warmongers_recital_blue":
      if (substr($from, 0, 5) != "THEIR") $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      else $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "THEIRBOTDECK";
      break;
    case "oaken_old_red":
      if (IsHeroAttackTarget()) {
        $hand = &GetHand($defPlayer);
        $cards = "";
        for ($i = 0; $i < 2 && count($hand) > 0; ++$i) {
          $index = GetRandom() % count($hand);
          if ($cards != "") $cards .= ",";
          $cards .= $hand[$index];
          unset($hand[$index]);
          $hand = array_values($hand);
        }
        if ($cards != "") AddDecisionQueue("CHOOSEBOTTOM", $defPlayer, $cards);
      }
      break;   
    case "mulch_red":
    case "mulch_yellow":
    case "mulch_blue":
      if (IsHeroAttackTarget()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to put on the bottom of the deck", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZADDZONE", $mainPlayer, "THEIRBOTDECK", 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "snow_under_red":
    case "snow_under_yellow":
    case "snow_under_blue":
      if (IsHeroAttackTarget()) PlayAura("frostbite", $defPlayer, effectController: $mainPlayer);
      break;
    case "frost_lock_blue-2":
      if (IsHeroAttackTarget()) {
        AddCurrentTurnEffect("frost_lock_blue-3", $defPlayer);
        AddNextTurnEffect("frost_lock_blue-3", $defPlayer);
      }
      break;
    case "ice_storm_red-2":
      if (IsHeroAttackTarget()) DamageTrigger($defPlayer, 1, "ATTACKHIT", "ice_storm_red", $mainPlayer);
      break;
    case "buzz_bolt_red":
    case "buzz_bolt_yellow":
    case "buzz_bolt_blue":
      if (IsHeroAttackTarget()) DamageTrigger($defPlayer, 1, "ATTACKHIT", $cardID, $mainPlayer);
      break;
    case "force_of_nature_blue-TRIGGER":
      if (HasIncreasedAttack()) Draw($mainPlayer);
      break;
    case "flashfreeze_red-BUFF":
      if (IsHeroAttackTarget()) DamageTrigger($defPlayer, 3, "ATTACKHIT", $cardID, $mainPlayer);
      break;
    case "ice_quake_red-HIT":
    case "ice_quake_yellow-HIT":
    case "ice_quake_blue-HIT":
      if (IsHeroAttackTarget()) PlayAura("frostbite", $defPlayer, effectController: $mainPlayer);
      break;
    case "chill_to_the_bone_red":
      PlayAura("frostbite", $defPlayer, 3, effectController: $mainPlayer);
      SearchCurrentTurnEffects($cardID, $mainPlayer, true);
      break;
    case "chill_to_the_bone_yellow":
      PlayAura("frostbite", $defPlayer, 2, effectController: $mainPlayer);
      SearchCurrentTurnEffects($cardID, $mainPlayer, true);
      break;
    case "chill_to_the_bone_blue":
      PlayAura("frostbite", $defPlayer, 1, effectController: $mainPlayer);
      SearchCurrentTurnEffects($cardID, $mainPlayer, true);
      break;
    case "shock_charmers":
      if (IsHeroAttackTarget()) DamageTrigger($defPlayer, 1, "ATTACKHIT", $cardID, $mainPlayer);
      return 1;
    case "shock_striker_red":
    case "shock_striker_yellow":
    case "shock_striker_blue":
      if (IsHeroAttackTarget()) DamageTrigger($defPlayer, 1, "ATTACKHIT", $cardID, $mainPlayer);
      break;
    case "electrify_red":
    case "electrify_yellow":
    case "electrify_blue":
      if (IsHeroAttackTarget()) {
        if ($cardID == "electrify_red") $damage = 3;
        else if ($cardID == "electrify_yellow") $damage = 2;
        else $damage = 1;
        DamageTrigger($defPlayer, $damage, "ATTACKHIT", $cardID, $mainPlayer);
        return 1;
      }
      break;
    case "tear_asunder_blue":
      if (IsHeroAttackTarget()) {
        PummelHit();
        PummelHit();
      }
      break;
    case "seek_and_destroy_red":
      if (IsHeroAttackTarget()) {
        AddNextTurnEffect($cardID . "-1", $defPlayer);
      }
      break;
    case "twin_twisters_red-1":
    case "twin_twisters_yellow-1":
    case "twin_twisters_blue-1":
      $idArr = explode("-", $cardID);
      AddCurrentTurnEffectFromCombat($idArr[0] . "-2", $mainPlayer);
      break;
    case "outland_skirmish_red-1":
    case "outland_skirmish_yellow-1":
    case "outland_skirmish_blue-1":
      PutItemIntoPlayForPlayer("copper", $mainPlayer);
      return 1;
    case "life_of_the_party_red-1":
    case "life_of_the_party_yellow-1":
    case "life_of_the_party_blue-1":
      GainHealth(2, $mainPlayer);
      break;
    case "high_striker_red":
    case "high_striker_yellow":
    case "high_striker_blue":
      if ($cardID == "high_striker_red") $amount = 6;
      else if ($cardID == "high_striker_yellow") $amount = 4;
      else $amount = 2;
      PutItemIntoPlayForPlayer("copper", $mainPlayer, 0, $amount);
      return 1;
    case "smashing_good_time_red-1":
    case "smashing_good_time_yellow-1":
    case "smashing_good_time_blue-1":
      if (IsHeroAttackTarget()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS:minCost=0;maxCost=2");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
        return 1;
      }
      break;
    case "glistening_steelblade_yellow-1":
      $char = &GetPlayerCharacter($mainPlayer);
      if (IsHeroAttackTarget()) {
        ++$char[$combatChainState[$CCS_WeaponIndex] + 3];
      }
      break;
    case "buckle_blue":
      if(IsHeroAttackTarget()) Mangle();
      break;
    case "cleave_red":
      $indices = SearchMultizone($mainPlayer, "THEIRALLY");
      if (strlen($indices) > 0) {
        //remove the current target from the list of choices
        $filtIndices = [];
        $targetUID = $combatChainState[$CCS_AttackTargetUID];
        $allies = GetAllies($defPlayer);
        foreach(explode(",", $indices) as $index) {
          $ind = explode("-", $index)[1];
          if ($allies[$ind + 5] != $targetUID) array_push($filtIndices, $index);
        }
        $indices = implode(",", $filtIndices);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $indices);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a target to deal " . $combatChainState[$CCS_DamageDealt] . " damage.");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDAMAGE", $mainPlayer, $combatChainState[$CCS_DamageDealt] . ",DAMAGE," . $cardID, 1);
      }
      break;
    case "dead_eye_yellow":
      if (IsHeroAttackTarget() && HasAimCounter()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRHAND");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want your opponent to discard", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDISCARD", $mainPlayer, "HAND," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "mask_of_perdition":
      $deck = new Deck($defPlayer);
      if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
      $deck->BanishTop("Source-" . $attackID, banishedBy:$attackID);
      break;
    case "runic_reaping_red-HIT":
    case "runic_reaping_yellow-HIT":
    case "runic_reaping_blue-HIT":
      if (ClassContains($attackID, "RUNEBLADE", $mainPlayer)) {
        if ($cardID == "runic_reaping_red-HIT") $amount = 3;
        else if ($cardID == "runic_reaping_yellow-HIT") $amount = 2;
        else $amount = 1;
        PlayAura("runechant", $mainPlayer, $amount, true);
      }
      break;
    case "spike_with_bloodrot_red":
      // The spikes *give* the ability to the attack
      if ($source == "-") $atkSource = $CombatChain->AttackCard()->ID();
      else $atkSource = $source;
      PlayAura($CID_BloodRotPox, $defPlayer, effectController: $mainPlayer, isToken:true, effectSource:$atkSource);
      break;
    case "spike_with_frailty_red":
      // The spikes *give* the ability to the attack
      if ($source == "-") $atkSource = $CombatChain->AttackCard()->ID();
      else $atkSource = $source;
      PlayAura($CID_Frailty, $defPlayer, effectController: $mainPlayer, isToken:true, effectSource:$atkSource);
      break;
    case "spike_with_inertia_red":
      // The spikes *give* the ability to the attack
      if ($source == "-") $atkSource = $CombatChain->AttackCard()->ID();
      else $atkSource = $source;
      PlayAura($CID_Inertia, $defPlayer, effectController: $mainPlayer, isToken:true, effectSource:$atkSource);
      break;
    case "melting_point_red":
      if (IsHeroAttackTarget() && HasAimCounter()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRCHAR:minAttack=1;maxAttack=1;type=W;is1h=true");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a weapon to destroy");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
      }
      break;
    case "lace_with_bloodrot_red":
      if (IsHeroAttackTarget()) PlayAura($CID_BloodRotPox, $defPlayer, effectController: $mainPlayer);
      break;
    case "lace_with_frailty_red":
      if (IsHeroAttackTarget()) PlayAura($CID_Frailty, $defPlayer, effectController: $mainPlayer);
      break;
    case "lace_with_inertia_red":
      if (IsHeroAttackTarget()) PlayAura($CID_Inertia, $defPlayer, effectController: $mainPlayer);
      break;
    case "mask_of_shifting_perspectives":
      WriteLog(CardLink($cardID, $cardID) . " lets you sink a card");
      BottomDeck($mainPlayer, true, shouldDraw: true);
      break;
    case "concealed_blade_blue":
      $weapons = "";
      $char = &GetPlayerCharacter($mainPlayer);
      $inventory = &GetInventory($mainPlayer);
      $numHands = NumOccupiedHands($mainPlayer);
      if ($numHands < 2) { //Only Equip if there is a broken weapon/off-hand
        foreach ($inventory as $cardID) {
          if (TypeContains($cardID, "W", $mainPlayer) && SubtypeContains($cardID, "Dagger")) {
            if ($weapons != "") $weapons .= ",";
            $weapons .= $cardID;
          };
        }
        if ($weapons == "") {
          WriteLog("Player " . $mainPlayer . " doesn't have any dagger in their inventory");
          return;
        }
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a dagger to equip");
        AddDecisionQueue("MAYCHOOSECARD", $mainPlayer, $weapons);
        AddDecisionQueue("APPENDLASTRESULT", $mainPlayer, "-INVENTORY");
        AddDecisionQueue("EQUIPCARDINVENTORY", $mainPlayer, "<-");
      }
      break;
    case "toxic_tips":
      if (IsHeroAttackTarget()) {
        AddDecisionQueue("CHOOSECARD", $mainPlayer, $CID_BloodRotPox . "," . $CID_Frailty . "," . $CID_Inertia);
        AddDecisionQueue("PUTPLAY", $defPlayer, $mainPlayer, 1);
      }
      break;
    case "toxicity_red":
      LoseHealth(5, $defPlayer);
      break;
    case "toxicity_yellow":
      LoseHealth(4, $defPlayer);
      break;
    case "toxicity_blue":
      LoseHealth(3, $defPlayer);
      break;
    case "premeditate_red-1":
      if (IsHeroAttackTarget()) {
        PlayAura("ponder", $mainPlayer);
        return 1;
      }
      break;
    case "beckoning_light_red":
      MZMoveCard($mainPlayer, "MYDISCARD:type=AA", "MYTOPDECK", may: true);
      break;
    case "spirit_of_war_red":
      PlayAura("courage", $mainPlayer);
      break;
    case "light_the_way_red":
    case "light_the_way_yellow":
    case "light_the_way_blue":
      GiveAttackGoAgain();
      break;
    case "lumina_lance_yellow-2":
      Draw($mainPlayer);
      break;
    case "lumina_lance_yellow-3":
      GiveAttackGoAgain();
      break;
    case "ironsong_versus":
      if (IsHeroAttackTarget()) PlayAura("courage", $mainPlayer);
      break;
    case $Card_LifeBanner:
      GainHealth(1, $mainPlayer);
      return 1;
    case $Card_ResourceBanner:
      GainResources($mainPlayer, 1);
      return 1;
    case "hack_to_reality_yellow-HIT":
      if (IsHeroAttackTarget()) {
        MZChooseAndDestroy($mainPlayer, "THEIRAURAS:type=A;maxCost=" . $combatChainState[$CCS_DamageDealt] . "&THEIRAURAS:type=I;maxCost=" . $combatChainState[$CCS_DamageDealt]);
        return 1;
      }
      break;
    case "smash_and_grab_red":
      if (IsHeroAttackTarget()) {
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to take");
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZOP", $mainPlayer, "GAINCONTROL", 1);
      }
      break;
    case "evo_command_center_yellow_equip":
      Draw($mainPlayer);
      break;
    case "kassai_of_the_golden_sand":
    case "kassai":
      if (IsHeroAttackTarget()) PutItemIntoPlayForPlayer("gold", $mainPlayer, effectController: $mainPlayer);
      return 1;
    case "hood_of_red_sand":
      Draw($mainPlayer);
      break;
    case "talk_a_big_game_blue":
      if ($combatChainState[$CCS_DamageDealt] >= $effectArr[1]) {
        PlayAura("might", $mainPlayer, $effectArr[1]);
        return 1;
      }
      break;
    case "just_a_nick_red-HIT":
      if (IsHeroAttackTarget()) {
        $deck = new Deck($defPlayer);
        $deck->BanishTop("Source-" . $attackID, banishedBy: $attackID);
      }
      break;
    case "maul_yellow-HIT":
      BanishCardForPlayer("crouching_tiger", $mainPlayer, "-", "TT", $mainPlayer);
      BanishCardForPlayer("crouching_tiger", $mainPlayer, "-", "TT", $mainPlayer);
      break;
    case "target_totalizer":
      Draw($mainPlayer);
      break;
    case "burn_up__shock_red":
      if (IsHeroAttackTarget()) DealArcane(4, 1, "PLAYCARD", $cardID, false, $mainPlayer);
      return 1;
    case "arakni_black_widow-HIT":
      AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
      AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card to banish", 1);
      AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $defPlayer, "-", 1);
      AddDecisionQueue("BANISHCARD", $defPlayer, "HAND,-", 1);
      AddDecisionQueue("WRITELOGCARDLINK", $defPlayer, "<-", 1);
      return 0;
    case "arakni_funnel_web-HIT":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card to banish", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      AddDecisionQueue("BANISHCARD", $defPlayer, "MYARS,-,$source", 1);
      return 0;
    case "two_sides_to_the_blade_red-ATTACK":
      if (IsHeroAttackTarget()) MarkHero($defPlayer);
      break;
    case "long_whisker_loyalty_red-MARK":
      $character = &GetPlayerCharacter($mainPlayer);
      if (IsHeroAttackTarget() && $character[$combatChainState[$CCS_WeaponIndex] + 11] == $effectArr[1]) {
        MarkHero($defPlayer);
        return 1;
      }
      break;
    case "searing_gaze_red":
    case "stabbing_pain_red":
      MarkHero($defPlayer);
      break;
    case "twist_and_turn_red":
    case "twist_and_turn_yellow":
    case "twist_and_turn_blue":
      $character = &GetPlayerCharacter($mainPlayer);
      if ($combatChainState[$CCS_WeaponIndex] != -1) {
        $character[$combatChainState[$CCS_WeaponIndex] + 1] = 2;
        ++$character[$combatChainState[$CCS_WeaponIndex] + 5];
      }
      else WriteLog("A strange error has happened with twist and turn. Please submit a bug report", highlight: true);
      return 0;
    case "hunt_a_killer_red":
    case "hunt_a_killer_yellow":
    case "hunt_a_killer_blue":
      if (IsHeroAttackTarget()) MarkHero($defPlayer);
      break;
    case "sworn_vengeance_red":
    case "sworn_vengeance_yellow":
    case "sworn_vengeance_blue":
      if (IsHeroAttackTarget()) MarkHero($defPlayer);
      break;
    case "poisoned_blade_red":
    case "poisoned_blade_yellow":
    case "poisoned_blade_blue":
      WriteLog("The " . CardLink($cardID, $cardID) . " drains 1 health");
      LoseHealth(1, $defPlayer);
      break;
    case "savor_bloodshed_red-HIT":
      Draw($mainPlayer, effectSource:"savor_bloodshed_red");
      return 1;
    case "scar_tissue_red":
    case "scar_tissue_yellow":
    case "scar_tissue_blue":
      MarkHero($defPlayer);
      return 1;
    case "take_a_stab_red":
    case "take_a_stab_yellow":
    case "take_a_stab_blue":
      //don't add attacks if it wasn't a weapon
      if (TypeContains($CombatChain->AttackCard()->ID(), "W")) {
        $character = &GetPlayerCharacter($mainPlayer);
        $character[$combatChainState[$CCS_WeaponIndex] + 1] = 2;
        ++$character[$combatChainState[$CCS_WeaponIndex] + 5];
      }
      return 1;
    case "imperial_seal_of_command_red-HIT":
      DestroyArsenal($defPlayer, effectController:$mainPlayer);
      return 1;
    case "gold_baited_hook":
      if (TypeContains($effectSource, "AA")) $EffectContext = $effectSource;
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS:type=T;cardID=gold");
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $mainPlayer, "GAINCONTROL", 1);
      AddDecisionQueue("ELSE", $mainPlayer, "-");
      AddDecisionQueue("PLAYITEM", $mainPlayer, "gold", 1);
      break;
    case "avast_ye_blue":
    case "heave_ho_blue":
    case "yo_ho_ho_blue":
      PutItemIntoPlayForPlayer("gold", $mainPlayer);
      break;
    case "drop_the_anchor_red":
      WriteLog(CardLink($cardID, $cardID) . " tap Player " . $defPlayer . ", and all the allies they control.");
      Tap("THEIRCHAR-0", $mainPlayer);
      AddDecisionQueue("TAPALL", $mainPlayer, "THEIRALLY", 1);
      break;
    case "big_game_trophy_shot_yellow":
      PutItemIntoPlayForPlayer("gold", $mainPlayer);
      return 1;
    case "regain_composure_blue":
      $inds = GetTapped($mainPlayer, "MYCHAR", "type=C");
      if(empty($inds)) return 1;
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "You may untap your hero");
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, $inds);
      AddDecisionQueue("MZTAP", $mainPlayer, "0", 1);
      return 1;
    case "bam_bam_yellow":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to destroy", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
      break;
    case "loot_the_hold_blue":
      $hand = GetHand($defPlayer);
      if (count($hand) > 0) PutItemIntoPlayForPlayer("gold", $mainPlayer, effectController:$mainPlayer, isToken:true);
      PummelHit();
      break;
    case "loot_the_arsenal_blue":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRARS", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card you want to destroy from their arsenal", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZDESTROY", $mainPlayer, false, 1);
      AddDecisionQueue("PLAYITEM", $mainPlayer, "gold", 1);
      break;
    case "legacy_of_ikaru_blue":
      if (count($chainLinks) > 0) {
        $lastAttackNames = explode(",", $chainLinkSummary[count($chainLinkSummary) - ChainLinkSummaryPieces() + 4]);
        for ($i = 0; $i < count($lastAttackNames); ++$i) {
          $lastAttackName = GamestateUnsanitize($lastAttackNames[$i]);
          if ($lastAttackName == "Edge of Autumn") {
            WriteLog("You have learned well from the " . CardLink($cardID, $cardID) . " and drew a card.");
            Draw($mainPlayer, effectSource:$CombatChain->AttackCard()->ID());
          }
        }
      }
      break;
    default:
      break;
  }
  return 0;
}

function EffectPowerModifier($cardID, $attached=false)
{
  global $mainPlayer;
  $cid = explode("-", $cardID)[0];
  $param = explode("-", $cardID)[1] ?? "-";
  $card = GetClass($cid, $mainPlayer);
  if ($card != "-") return $card->EffectPowerModifier($param, $attached);
  $set = CardSet($cardID);
  if ($set == "WTR") return WTREffectPowerModifier($cardID);
  else if ($set == "ARC") return ARCEffectPowerModifier($cardID);
  else if ($set == "CRU") return CRUEffectPowerModifier($cardID);
  else if ($set == "MON") return MONEffectPowerModifier($cardID);
  else if ($set == "ELE") return ELEEffectPowerModifier($cardID);
  else if ($set == "EVR") return EVREffectPowerModifier($cardID);
  else if ($set == "DVR") return DVREffectPowerModifier($cardID);
  else if ($set == "RVD") return RVDEffectPowerModifier($cardID);
  else if ($set == "UPR") return UPREffectPowerModifier($cardID);
  else if ($set == "DYN") return DYNEffectPowerModifier($cardID);
  else if ($set == "OUT") return OUTEffectPowerModifier($cardID, $attached);
  else if ($set == "DTD") return DTDEffectPowerModifier($cardID);
  else if ($set == "TCC") return TCCEffectPowerModifier($cardID);
  else if ($set == "EVO") return EVOEffectPowerModifier($cardID);
  else if ($set == "HVY") return HVYEffectPowerModifier($cardID);
  else if ($set == "MST") return MSTEffectPowerModifier($cardID);
  else if ($set == "AAZ") return AAZEffectPowerModifier($cardID);
  else if ($set == "TER") return TEREffectPowerModifier($cardID);
  else if ($set == "AUR") return AUREffectPowerModifier($cardID);
  else if ($set == "ROS") return ROSEffectPowerModifier($cardID);
  else if ($set == "AJV") return AJVEffectPowerModifier($cardID);
  else if ($set == "HNT") return HNTEffectPowerModifier($cardID, $attached);
  else if ($set == "AST") return ASTEffectPowerModifier($cardID);
  else if ($set == "AMX") return AMXEffectPowerModifier($cardID);
  else if ($set == "SEA") return SEAEffectPowerModifier($cardID);
  else if ($set == "MPG") return MPGEffectPowerModifier($cardID);
  else if ($set == "ASR") return ASREffectPowerModifier($cardID);
  else if ($set == "SUP") return SUPEffectPowerModifier($cardID);
  else if ($set == "APS") return APSEffectPowerModifier($cardID);
  else if ($set == "AAC") return AACEffectPowerModifier($cardID);
  else if ($set == "ARR") return ARREffectPowerModifier($cardID);
  else if ($set == "PEN") return PENEffectPowerModifier($cardID);
  switch ($cardID) {
    case "ira_scarlet_revenger":
      return 1;
    case "bravo_flattering_showman":
      return 2;
    default:
      return 0;
  }
}

function EffectHasBlockModifier($cardID)
{
  switch ($cardID) {
    case "phantasmal_footsteps":
    case "korshem_crossroad_of_elements-2":
    case "amulet_of_earth_blue":
    case "rampart_of_the_rams_head":
    case "spiders_bite":
    case "spiders_bite_r":
    case "nerve_scalpel":
    case "nerve_scalpel_r":
    case "orbitoclast":
    case "orbitoclast_r":
    case "scale_peeler":
    case "scale_peeler_r":
    case "fletch_a_red_tail_red":
    case "fletch_a_yellow_tail_yellow":
    case "fletch_a_blue_tail_blue":
    case "defender_of_daybreak_red":
    case "defender_of_daybreak_yellow":
    case "defender_of_daybreak_blue":
    case "lay_down_the_law_red":
    case "stonewall_impasse":
    case "headliner_helm":
    case "stadium_centerpiece":
    case "ticket_puncher":
    case "grandstand_legplates":
    case "bloodied_oval":
    case "graven_call":
      return true;
    default:
      return false;
  }
}

function EffectBlockModifier($cardID, $index, $from)
{
  global $CombatChain, $defPlayer, $mainPlayer;
  $blockModifier = 0;
  switch ($cardID) {
    case "phantasmal_footsteps":
      if ($CombatChain->Card($index)->ID() == $cardID) $blockModifier += 1;
      $blockModifier += 0;
      break;
    case "korshem_crossroad_of_elements-2":
      $blockModifier += 1;
      break;
    case "amulet_of_earth_blue":
      $blockModifier += 1;
      break;
    case "rampart_of_the_rams_head":
      $blockModifier += ($CombatChain->Card($index)->ID() == $cardID ? 1 : 0);
      break;
    case "fletch_a_red_tail_red":
      $blockModifier += (PitchValue($CombatChain->Card($index)->ID()) == 1 && HasAimCounter() ? -1 : 0);
      break;
    case "fletch_a_yellow_tail_yellow":
      $blockModifier += (PitchValue($CombatChain->Card($index)->ID()) == 2 && HasAimCounter() ? -1 : 0);
      break;
    case "fletch_a_blue_tail_blue":
      $blockModifier += (PitchValue($CombatChain->Card($index)->ID()) == 3 && HasAimCounter() ? -1 : 0);
      break;
    case "defender_of_daybreak_red":
    case "defender_of_daybreak_yellow":
    case "defender_of_daybreak_blue":
      $blockModifier += (CardType($CombatChain->Card($index)->ID()) != "E" && TalentContains($CombatChain->Card($index)->ID(), "LIGHT", $defPlayer) && TalentContains($CombatChain->AttackCard()->ID(), "SHADOW", $mainPlayer) ? 1 : 0);
      break;
    case "lay_down_the_law_red":
      $blockModifier += (CachedTotalPower() >= 13 && !TypeContains($CombatChain->Card($index)->ID(), "E") && !DelimStringContains(CardSubType($CombatChain->Card($index)->ID()), "Evo")) ? -1 : 0;
      break;
    case "ratchet_up_red":
    case "ratchet_up_yellow":
    case "ratchet_up_blue":
      $blockModifier += IsActionCard($CombatChain->Card($index)->ID()) ? -1 : 0;break;
    case "headliner_helm":
    case "stadium_centerpiece":
    case "ticket_puncher":
    case "grandstand_legplates":
    case "bloodied_oval":
      $blockModifier += $CombatChain->Card($index)->ID() == $cardID && PlayerHasLessHealth($defPlayer) ? 1 : 0;break;
    case "wide_blue_yonder_blue":
      $blockModifier += SearchPitchForColor($mainPlayer, 3);
      break;
    case "heavy_industry_surveillance":
    case "heavy_industry_ram_stop":
    case "breaker_helm_protos":
      $blockModifier += 1;
      break;
    default:
      break;
  }
  if ($blockModifier > 0 && !CanGainBlock($cardID)) $blockModifier = 0;
  return $blockModifier;
}

function RemoveEffectsFromCombatChain($cardID = "")
{
  global $currentTurnEffects, $mainPlayer;
  $searchedEffect = "";
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if($cardID == "") {
      $effectArr = explode("-", $currentTurnEffects[$i]);
      $effectArr2 = explode(",", $effectArr[0]);
      $searchedEffect = $effectArr2[0];  
    }
    else $searchedEffect = $cardID;
    $card = GetClass($searchedEffect, $mainPlayer);
    if ($card != "-") $remove = $card->RemoveEffectFromCombatChain();
    switch ($searchedEffect) {
      case "mask_of_momentum":
      case "high_speed_impact_red":
      case "high_speed_impact_yellow":
      case "high_speed_impact_blue": 
      case "combustible_courier_red":
      case "combustible_courier_yellow":
      case "combustible_courier_blue":
      case "v_of_the_vanguard_yellow":
      case "exude_confidence_red":
      case "explosive_growth_red":
      case "explosive_growth_yellow":
      case "explosive_growth_blue":
      case "spreading_flames_red":
      case "brand_with_cinderclaw_red":
      case "brand_with_cinderclaw_yellow":
      case "brand_with_cinderclaw_blue":
      case "shred_red":
      case "shred_yellow":
      case "shred_blue":
      case "scramble_pulse_red":
      case "scramble_pulse_yellow":
      case "scramble_pulse_blue": 
      case "prowl_red":
      case "prowl_yellow":
      case "prowl_blue": 
      case "head_leads_the_tail_red": 
      case "deadly_duo_red":
      case "deadly_duo_yellow":
      case "deadly_duo_blue": 
      case "spirit_of_war_red":
      case "growl_red":
      case "growl_yellow":
      case "coercive_tendency_blue":
      case "tiger_taming_khakkara":
      case "chase_the_tail_red":
      case "untamed_red":
      case "untamed_yellow":
      case "untamed_blue": 
      case "stonewall_gauntlet": 
      case "water_the_seeds_red":
      case "water_the_seeds_yellow":
      case "water_the_seeds_blue": 
      case "ignite_red":
      case "wrath_of_retribution_red":
      case "fire_tenet_strike_first_red":
      case "poisoned_blade_red":
      case "poisoned_blade_yellow":
      case "poisoned_blade_blue":
      case "quickdodge_flexors":
        $remove = 1;
        break;
      default:
        break;
    }
    if ($remove && SearchCurrentTurnEffectsForIndex($searchedEffect, $currentTurnEffects[$i + 1]) != -1) RemoveCurrentTurnEffect($i);
  }
}

function RemoveThisLinkEffects($cardID="")
{
  global $currentTurnEffects, $combatChainState, $CCS_EclecticMag, $CCS_NextInstantBouncesAura;
  $searchedEffect = "";
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if($cardID == "") {
      $effectArr = explode("-", $currentTurnEffects[$i]);
      $effectArr2 = explode(",", $effectArr[0]);
      $searchedEffect = $effectArr2[0];  
    }
    else $searchedEffect = $cardID;
    switch ($searchedEffect) {
      case "gone_in_a_flash_red":
        $remove = 1;
        break;
      default:
        break;
    }
    if ($remove && SearchCurrentTurnEffectsForIndex($searchedEffect, $currentTurnEffects[$i + 1]) != -1) RemoveCurrentTurnEffect($i);
  }
  $combatChainState[$CCS_NextInstantBouncesAura] = 0;
  $combatChainState[$CCS_EclecticMag] = 0;
}

function OnAttackEffects($power)
{
  global $currentTurnEffects, $mainPlayer, $defPlayer;
  $attackType = CardType($power);
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "bramble_spark_red":
        case "bramble_spark_yellow":
        case "bramble_spark_blue":
          if ($attackType == "AA") {
            SetArcaneTarget($mainPlayer, $currentTurnEffects[$i], 0, 1);
            AddDecisionQueue("SHOWSELECTEDTARGET", $mainPlayer, "-", 1);
            AddDecisionQueue("ADDTRIGGER", $mainPlayer, $currentTurnEffects[$i], 1);
            $remove = true;
          }
          break;
        case "flashfreeze_red-DOM":
          AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Do you want to pay 2 to prevent this attack from getting dominate?", 1);
          AddDecisionQueue("BUTTONINPUT", $defPlayer, "0,2", 0, 1);
          AddDecisionQueue("PAYRESOURCES", $defPlayer, "<-", 1);
          AddDecisionQueue("GREATERTHANPASS", $defPlayer, "0", 1);
          AddDecisionQueue("ADDCURRENTTURNEFFECT", $mainPlayer, $currentTurnEffects[$i] . "ATK!PLAY", 1);
          break;
        case "warband_of_bellona":
          Charge(may: true, player: $mainPlayer, mod: "NOTCOST");
          AddDecisionQueue("ALLCARDPITCHORPASS", $mainPlayer, "2", 1);
          AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
          $remove = true;
          break;
        case "good_time_chapeau-PAID":
          if (IsCombatEffectActive($currentTurnEffects[$i]) && IsHeroAttackTarget()) {
            AskWager(ExtractCardID($currentTurnEffects[$i]));
          }
          break;
        case "big_bop_red-BUFF":
        case "big_bop_yellow-BUFF":
        case "big_bop_blue-BUFF":
          if (IsCombatEffectActive($currentTurnEffects[$i]) && IsHeroAttackTarget()) {
            AskWager(ExtractCardID($currentTurnEffects[$i]));
          }
          break;
        case "bigger_than_big_red-BUFF":
        case "bigger_than_big_yellow-BUFF":
        case "bigger_than_big_blue-BUFF":
          if (IsCombatEffectActive($currentTurnEffects[$i]) && IsHeroAttackTarget()) {
            AskWager(ExtractCardID($currentTurnEffects[$i]));
          }
          break;
        case "edge_ahead_red-BUFF":
        case "edge_ahead_yellow-BUFF":
        case "edge_ahead_blue-BUFF":
          if (IsCombatEffectActive($currentTurnEffects[$i]) && IsHeroAttackTarget()) {
            AskWager(ExtractCardID($currentTurnEffects[$i]));
          }
          break;
        case "hold_em_red-BUFF":
        case "hold_em_yellow-BUFF":
        case "hold_em_blue-BUFF":
          if (IsCombatEffectActive($currentTurnEffects[$i]) && IsHeroAttackTarget()) {
            AskWager(ExtractCardID($currentTurnEffects[$i]));
          }
          break;
        case "money_where_ya_mouth_is_red-BUFF":
        case "money_where_ya_mouth_is_yellow-BUFF":
        case "money_where_ya_mouth_is_blue-BUFF":
          if (IsCombatEffectActive($currentTurnEffects[$i]) && IsHeroAttackTarget()) {
            AskWager(ExtractCardID($currentTurnEffects[$i]));
          }
          break;
        case "shifting_winds_of_the_mystic_beast_blue":
          if (CardNameContains($power, "Crouching Tiger", $mainPlayer)) {
            AddDecisionQueue("INPUTCARDNAME", $mainPlayer, "-");
            AddDecisionQueue("SETDQVAR", $mainPlayer, "0");
            AddDecisionQueue("PREPENDLASTRESULT", $mainPlayer, "crouching_tiger-");
            AddDecisionQueue("ADDCURRENTTURNEFFECT", $mainPlayer, "<-");
            AddDecisionQueue("WRITELOG", $mainPlayer, "📣<b>{0}</b> was chosen");
          }
          break;
        case "first_tenet_of_chi_moon_blue":
          if (PitchValue($power) == 3) {
            Draw($mainPlayer);
            $remove = true;
          }
        case "unsheathed_red":
          if (IsCombatEffectActive($currentTurnEffects[$i])){
            AddLayer("TRIGGER", $mainPlayer, $currentTurnEffects[$i], additionalCosts:"ATTACKTRIGGER");
          }
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
}

// function CurrentEffectBaseAttackSet()
// {
//   global $currentPlayer, $currentTurnEffects;
//   $mod = -1;
//   for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
//     if ($currentTurnEffects[$i + 1] == $currentPlayer && IsCombatEffectActive($currentTurnEffects[$i])) {
//       switch ($currentTurnEffects[$i]) {
//         case "transmogrify_red":
//           if ($mod < 8) $mod = 8;
//           break;
//         case "transmogrify_yellow":
//           if ($mod < 7) $mod = 7;
//           break;
//         case "transmogrify_blue":
//           if ($mod < 6) $mod = 6;
//           break;
//         default:
//           break;
//       }
//     }
//   }
//   return $mod;
// }

function CurrentEffectCostModifiers($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $CS_PlayUniqueID;
  $costModifier = 0;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      if (DelimStringContains($currentTurnEffects[$i], "art_of_the_dragon_blood_red", true)) {
        $cardType = CardType($cardID);
        if(TalentContains($cardID, "DRACONIC", $currentPlayer) && !IsStaticType($cardType, $from, $cardID)) {
          $costModifier -= 1;
          --$currentTurnEffects[$i + 3];
          if ($currentTurnEffects[$i + 3] <= 0) $remove = true;
        }
      }
      $card = GetClass($currentTurnEffects[$i], $currentPlayer);
      if ($card != "-") $costModifier += $card->CurrentEffectCostModifier($cardID, $remove);
      switch ($currentTurnEffects[$i]) {
        case "cartilage_crush_red":
        case "cartilage_crush_yellow":
        case "cartilage_crush_blue":
          if (IsAction($cardID, $from)) {
            $costModifier += 1;
            $remove = true;
          }
          break;
        case "seismic_surge":
          if (ClassContains($cardID, "GUARDIAN", $currentPlayer) && CardType($cardID) == "AA") {
            $costModifier -= 1;
            $remove = true;
          }
          break;
        case "heartened_cross_strap":
          if (CardType($cardID) == "AA" && (GetResolvedAbilityType($cardID, $from) == "AA" || GetResolvedAbilityType($cardID, $from) == "")) {
            $costModifier -= 2;
            $remove = true;
          }
          break;
        case "courage_of_bladehold":
          if (TypeContains($cardID, "W", $currentPlayer) && CardSubType($cardID) == "Sword") {
            $costModifier -= 1;
          }
          break;
        case "dauntless_red-2":
        case "dauntless_yellow-2":
        case "dauntless_blue-2":
          if (CardType($cardID) == "DR" && (GetResolvedAbilityType($cardID, $from) == "DR" || GetResolvedAbilityType($cardID, $from) == "")) {
            $costModifier += 1;
            $remove = true;
          }
          break;
        case "bloodsheath_skeleta-AA":
          if (CardType($cardID) == "AA") {
            $costModifier -= CountAura("runechant", $currentPlayer);
            $remove = true;
          }
          break;
        case "bloodsheath_skeleta-NAA":
          if (CardType($cardID) == "A") {
            $costModifier -= CountAura("runechant", $currentPlayer);
            $remove = true;
          }
          break;
        case "hamstring_shot_red":
        case "hamstring_shot_yellow":
        case "hamstring_shot_blue":
          if ((CardType($cardID) == "AA" || GetResolvedAbilityType($cardID, $from) == "AA") && (GetResolvedAbilityType($cardID, $from) == "AA" || GetResolvedAbilityType($cardID, $from) == "")) {
            $costModifier += 1;
            $remove = true;
          }
          break;
        case "frost_lock_blue-1":
          $costModifier += 1;
          break;
        case "cold_wave_red":
        case "cold_wave_yellow":
        case "cold_wave_blue":
          $costModifier += 1;
          break;
        case "heart_of_ice":
          $costModifier += 1;
          break;
        case "amulet_of_ignition_yellow":
          if (IsStaticType(CardType($cardID), $from, $cardID)) {
            $costModifier -= 1;
            $remove = true;
          }
          break;
        case "blood_of_the_dracai_red":
          if (TalentContains($cardID, "DRACONIC", $currentPlayer) && $from != "PLAY" && $from != "EQUIP") {
            $costModifier -= 1;
            --$currentTurnEffects[$i + 3];
            if ($currentTurnEffects[$i + 3] <= 0) $remove = true;
          }
          break;
        case "rising_resentment_red":
        case "rising_resentment_yellow":
        case "rising_resentment_blue":
          if (GetClassState($currentPlayer, $CS_PlayUniqueID) == $currentTurnEffects[$i + 2]) {
            --$costModifier;
            $remove = true;
          }
          break;
        case "alluvion_constellas":
          if (IsStaticType(CardType($cardID), $from, $cardID) && DelimStringContains(CardSubType($cardID), "Staff")) {
            $costModifier -= 3;
            $remove = true;
          }
          break;
        case "redback_shroud":
          if (CardType($cardID) == "AR") {
            $costModifier -= 1;
            $remove = true;
          }
          break;
        case "silken_gi-1":
          if (CardType($cardID) == "AA") {
            $costModifier -= 1;
            $remove = true;
          }
          break;
        case "empyrean_rapture":
          if (CardType($cardID) == "C") {
            $costModifier -= 2;
            $remove = true;
          }
          break;
        case "bequest_the_vast_beyond_red":
          if (CardType($cardID) == "AA" && ClassContains($cardID, "RUNEBLADE", $currentPlayer)) {
            $costModifier -= CountAura("runechant", $currentPlayer);
            $remove = true;
          }
          break;
        case "earthlore_empowerment_red":
        case "earthlore_empowerment_yellow":
          if (ClassContains($cardID, "GUARDIAN", $currentPlayer) && CardType($cardID) == "AA") $costModifier -= 1;
          break;
        case "evo_engine_room_yellow_equip":
          if (TypeContains($cardID, "W", $currentPlayer)) {
            $costModifier -= 1;
            $remove = true;
          }
          break;
        case "savage_sash":
          $power = 0;
          for ($j = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $j >= 0; $j -= CurrentTurnEffectsPieces()) {
            if (IsCombatEffectActive($currentTurnEffects[$j], $cardID)) {
              if ($currentTurnEffects[$j + 1] == $currentPlayer) {
                $power += EffectPowerModifier($currentTurnEffects[$j]);
              }
            }
          }  
          $power += ModifiedPowerValue($cardID, $currentPlayer, $from);
          if (CardType($cardID) == "AA" && $power >= 6) $costModifier -= 1;
          break;
        case "evo_heartdrive_blue":
          if (CardType($cardID) == "AA") {
            $costModifier -= 1;
            $remove = true;
          }
          break;
        case "ignite_red":
          if (TalentContains($cardID, "DRACONIC", $currentPlayer)) {
            $costModifier -= 1;
            $remove = true;
            if ($cardID == "fealty") {
              WriteLog(CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]) . " is discounting your fealty, this is not a bug. You need to use the fealty in response to your " . CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]) . " ability.");
            }
          }
          break;
        case "wrath_of_retribution_red":
          if (SubtypeContains($cardID, "Dagger", $currentPlayer)) {
            $costModifier -= 1;
          }
          break;
        case "solar_plexus":
          if (PitchValue($cardID) == 2) $costModifier -= 1;
          break;
        case "calming_cloak":
          if (SubtypeContains($cardID, "Aura") && $from != "PLAY") {
            $costModifier -= 2;
            $remove = true;
          }
          break;
        case "heart_of_vengeance":
          $otherChar = &GetPlayerCharacter(player: $otherPlayer);
          $isAttack = GetResolvedAbilityType($cardID, $from) == "AA" || (GetResolvedAbilityType($cardID, $from) == "" && CardType($cardID) == "AA");
          if (CardNameContains($otherChar[0], "Arakni") && $isAttack) {
            $costModifier -= 1;
            $remove = true;
          }
          break;
        case "perforate_yellow":
          if (GetClassState($currentPlayer, $CS_PlayUniqueID) == $currentTurnEffects[$i + 2]) $costModifier -= 1;
          break;
        case "give_no_quarter_blue":
          if (SubtypeContains($cardID, "Ally", $currentPlayer) && HasWateryGrave($cardID) && $from != "PLAY") {
            $costModifier -= 3;
            --$currentTurnEffects[$i + 3];
            if ($currentTurnEffects[$i + 3] <= 0) $remove = true;
          }
          break;
        case "authority_of_ataya_blue":
          if (TypeContains($cardID, "DR", $currentPlayer) && IsPlayed($cardID, $from, $currentPlayer)) ++$costModifier;
          break;
        default:
          break;
      }
      if ($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return CostCantBeModified($cardID) ? 0 : $costModifier;
}

function CurrentEffectPreventDamagePrevention($player, $damage, $source, $skip=false, $preventable=true) //$skip is used to check the damage prevention without using it. Mostly for Front-end and UI work
{
  global $currentTurnEffects;
  $preventedDamage = 0;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    $remove = false;
    if ($preventedDamage < $damage && $currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        case "essence_of_ancestry_body_red":
          if (PitchValue($source) == 1 && !$skip && $preventable) {
            $preventedDamage += $damage;
            RemoveCurrentTurnEffect($i);
          }
          break;
        case "essence_of_ancestry_soul_yellow":
          if (PitchValue($source) == 2 && !$skip && $preventable) {
            $preventedDamage += $damage;
            RemoveCurrentTurnEffect($i);
          }
          break;
        case "essence_of_ancestry_mind_blue":
          if (PitchValue($source) == 3 && !$skip && $preventable) {
            $preventedDamage += $damage;
            RemoveCurrentTurnEffect($i);
          }
          break;
        case "shelter_from_the_storm_red":
        case "calming_breeze_red":
          if ($preventable) $preventedDamage += 1;
          if (!$skip) --$currentTurnEffects[$i + 3];
          if ($currentTurnEffects[$i + 3] == 0) $remove = true;
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
  if ($preventedDamage > 0 && SearchCurrentTurnEffects("vambrace_of_determination", $player) != "" && !$skip) {
    $preventedDamage -= 1;
    SearchCurrentTurnEffects("vambrace_of_determination", $player, remove:true);
  }
  if($skip) return $preventedDamage;
  else {
    $damage -= $preventedDamage;
    return $damage;  
  }
}

function CurrentTurnEffectDamagePreventionAmount($player, $index, $damage, $type, $source, $preventable=true)
{
  global $currentTurnEffects;
  $otherPlayer = $player == 1 ? 2 : 1;
  $effects = explode("-", $currentTurnEffects[$index]);
  $source = explode("|", $source)[0] ?? $source;
  $card = GetClass($effects[0], $player);
  if ($card != "-") {
    $remove = false;
    return $card->CurrentEffectDamagePrevention($type, $damage, $source, $remove);
  }
  switch ($effects[0]) {
    case "dissipation_shield_yellow":
      return intval($effects[1]);
    case "blessing_of_serenity_red":
      if ($type == "COMBAT") {
        return 3;
      }
      break;
    case "blessing_of_serenity_yellow":
      if ($type == "COMBAT") {
        return 2;
      }
      break;
    case "blessing_of_serenity_blue":
      if ($type == "COMBAT") {
        return 1;
      }
      break;
    case "steadfast_red":
      if ($source == $currentTurnEffects[$index + 2]) {
        return 6;
      }
      break;
    case "steadfast_yellow":
      if ($source == $currentTurnEffects[$index + 2]) {
        return 5;
      }
      break;
    case "steadfast_blue":
      if ($source == $currentTurnEffects[$index + 2]) {
        return 4;
      }
      break;
    case "amulet_of_intervention_blue":
      return 1;
    case "helios_mitre":
      if ($source == $currentTurnEffects[$index + 2]) {
          return 1;
        }
      break;
    case "oasis_respite_red":
      if ($source == $currentTurnEffects[$index + 2]) {
          return $currentTurnEffects[$index + 3];
        }
      break;
    case "oasis_respite_yellow":
      if ($source == $currentTurnEffects[$index + 2]) {
          return $currentTurnEffects[$index + 3];
        }
      break;
    case "oasis_respite_blue":
      if ($source == $currentTurnEffects[$index + 2]) {
          return $currentTurnEffects[$index + 3];
        }
      break;
    case "seekers_hood":
    case "seekers_gilet":
    case "seekers_mitts":
    case "seekers_leggings":
      return 1;
    case "brush_off_red":
      if ($damage <= 3) {
        return $damage;
      }
      break;
    case "brush_off_yellow":
      if ($damage <= 2) {
        return $damage;
      }
      break;
    case "brush_off_blue":
      if ($damage == 1) {
        return $damage;
      }
      break;
    case "peace_of_mind_red":
      if ($type == "COMBAT") {
        return 4;
      }
      break;
    case "peace_of_mind_yellow":
      if ($type == "COMBAT") {
        return 3;
      }
      break;
    case "peace_of_mind_blue":
      if ($type == "COMBAT") {
        return 2;
      }
      break;
    case "break_of_dawn_red":
    case "break_of_dawn_yellow":
    case "break_of_dawn_blue":
      $prevention = match ($effects[0]) {
        "break_of_dawn_red" => 4,
        "break_of_dawn_yellow" => 3,
        "break_of_dawn_blue" => 2,
      };
      if (TalentContains($source, "SHADOW", $otherPlayer)) {
        return $prevention;
      }
      break;
    case "interlude_red":
      return 3;
    case "interlude_yellow":
      return 2;
    case "interlude_blue":
      return 1;
    case "evo_circuit_breaker_red":
    case "evo_atom_breaker_red":
    case "evo_face_breaker_red":
    case "evo_mach_breaker_red": //Card
    case "evo_circuit_breaker_red_equip":
    case "evo_atom_breaker_red_equip":
    case "evo_face_breaker_red_equip":
    case "evo_mach_breaker_red_equip": //Equipment
      if (!isset($effects[1]) || $effects[1] != "BUFF") {
        return intval($effects[1]);
      }
      break;
    case "no_fear_red":
    case "seeds_of_tomorrow_blue":
    case "hold_the_line_blue":
    case "trip_the_light_fantastic_red":
    case "trip_the_light_fantastic_yellow":
    case "trip_the_light_fantastic_blue":
    case "radiant_view": case "radiant_raiment": case "radiant_touch": case "radiant_flow":
    case "twinkle_toes":
    case "well_grounded":
    case "oldhim_grandfather_of_eternity": case "oldhim":
    case "bone_head_barrier_yellow":
      return intval($effects[1]);
    case "haunting_rendition_red":
    case "mental_block_blue":
      if (!$preventable) return 0;
      return intval($effects[1]);
    case "battered_not_broken_red":
    case "take_it_on_the_chin_red":
    case "slap_happy_red":
    case "sheltered_cove":
      return 2;
    case "dissolving_shield_red":
    case "dissolving_shield_yellow":
    case "dissolving_shield_blue":
    case "battlefront_bastion_red":
    case "battlefront_bastion_yellow":
    case "battlefront_bastion_blue":
    case "skycrest_keikoi":
    case "skybody_keikoi":
    case "skyhold_keikoi":
    case "skywalker_keikoi":
    case "runaways":
    case "hood_of_second_thoughts":
    case "bruised_leather":
    case "four_finger_gloves":
    case "crown_of_seeds":
      return 1;
    case "moon_chakra_red":
      return match ($currentTurnEffects[$index]) {
        "moon_chakra_red-1" => 3,
        default => 5,
      };
    case "moon_chakra_yellow":
      return match ($currentTurnEffects[$index]) {
        "moon_chakra_yellow-1" => 2,
        default => 4,
      };
    case "moon_chakra_blue":
      return match ($currentTurnEffects[$index]) {
        "moon_chakra_blue-1" => 1,
        default => 3,
      };
    case "cloud_cover_yellow":
    case "sigil_of_shelter_yellow":
        return 2;
    case "sigil_of_shelter_blue":
        return 1;
    case "sanctuary_of_aria":
      if ($source == $currentTurnEffects[$index + 2]) {
        return $damage;
      }
      break;
    case "misfire_dampener":
      return $type == "ARCANE" ? intval($effects[1]) : 0;
    case "sawbones_dock_hand_yellow":
      return 1;
    case "throw_caution_to_the_wind_blue":
      return intval($effects[1]);
    case "light_up_the_leaves_red":
      if ($source == $currentTurnEffects[$index + 2] && $type == "ARCANE") {
        return $damage;
      }
      break;
    case "soulbond_resolve":
      return 1;
    default:
      break;
  }
  return 0;
}

function CurrentEffectDamagePrevention($player, $index, $type, $damage, $source, $preventable)
{
  global $currentTurnEffects;
  $otherPlayer = $player == 1 ? 2 : 1;
  $vambraceAvailable = SearchCurrentTurnEffects("vambrace_of_determination", $player) != "";
  $vambraceRemove = false;
  $source = ExtractCardID($source);
  $remove = false;
  $preventedDamage = 0;
  $effects = explode("-", $currentTurnEffects[$index]);
  $card = GetClass($effects[0], $player);
  if ($card != "-") {
    $prevention = $card->CurrentEffectDamagePrevention($type, $damage, $source, $remove);
    if ($preventable) $preventedDamage += $prevention;
    if ($remove) RemoveCurrentTurnEffect($index);
  }
  switch ($effects[0]) {
    case "soulbond_resolve":
      $char = &GetPlayerCharacter($player);
      if ($preventable) $preventedDamage += 1;
      $charIndex = FindCharacterIndex($player, $effects[0]);
      --$char[$charIndex + 5];
      RemoveCurrentTurnEffect($index);
      break;
    case "dissipation_shield_yellow":
      if ($preventable) $preventedDamage += intval($effects[1]);
      RemoveCurrentTurnEffect($index);
      break;
    case "blessing_of_serenity_red":
      if ($type == "COMBAT") {
        if ($preventable) $preventedDamage += 3;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "blessing_of_serenity_yellow":
      if ($type == "COMBAT") {
        if ($preventable) $preventedDamage += 2;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "blessing_of_serenity_blue":
      if ($type == "COMBAT") {
        if ($preventable) $preventedDamage += 1;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "steadfast_red":
    case "steadfast_yellow":
    case "steadfast_blue":
      if ($source == $currentTurnEffects[$index + 2]) {
        if ($preventable) {
          $origDamage = $damage;
          $preventedDamage += $currentTurnEffects[$index + 3];
          if ($preventedDamage > $damage) $preventedDamage = $damage;
          $currentTurnEffects[$index + 3] -= $origDamage;
        }
        if ($currentTurnEffects[$index + 3] <= 0) RemoveCurrentTurnEffect($index);
      }
      break;
    case "amulet_of_intervention_blue":
    case "skycrest_keikoi":
    case "skybody_keikoi":
    case "skyhold_keikoi":
    case "skywalker_keikoi":
    case "runaways":
    case "hood_of_second_thoughts":
    case "bruised_leather":
    case "four_finger_gloves":
    case "crown_of_seeds":
      if ($preventable) $preventedDamage += 1;
      RemoveCurrentTurnEffect($index);
      break;
    case "helios_mitre":
      if ($source == $currentTurnEffects[$index + 2]) {
        if ($preventable) {
          $preventedDamage += $currentTurnEffects[$index + 3];
          $currentTurnEffects[$index + 3] -= $damage;
        }
        else $remove = true;
        if ($currentTurnEffects[$index + 3] <= 0) $remove = true;
        if ($source == "runechant" || $source == "aether_ashwing") $remove = true; //To be removed when coded with Unique ID instead of cardID name as $source
      }
      if ($remove) RemoveCurrentTurnEffect($index);
      break;
    case "oasis_respite_red":
    case "oasis_respite_yellow":
    case "oasis_respite_blue":
      if ($source == $currentTurnEffects[$index + 2]) {
        if ($preventable) {
          $preventedDamage += $currentTurnEffects[$index + 3];
          $currentTurnEffects[$index + 3] -= $damage;
        }
        else $remove = true;
        if ($currentTurnEffects[$index + 3] <= 0) $remove = true;
        $multiAttack = match($source) {
          "explosive_growth_red", "explosive_growth_yellow", "explosive_growth_blue", "art_of_the_dragon_fire_red" => true,
          "vexing_malice_red", "vexing_malice_yellow", "vexing_malice_blue", "reckless_stampede_red" => true,
          default => false,
        };
        if (SubtypeContains($source, "Dagger")) $multiAttack = true;
        if (TypeContains($source, "AA") && !$multiAttack) $remove = true; //To be removed when coded with Unique ID instead of cardID name as $source
        if ($source == "spectral_shield" || $source == "runechant" || $source == "aether_ashwing") $remove = true; //To be removed when coded with Unique ID instead of cardID name as $source
      }
      if ($remove) RemoveCurrentTurnEffect($index);
      break;
    case "seekers_hood":
    case "seekers_gilet":
    case "seekers_mitts":
    case "seekers_leggings":
      if ($preventable) {
        $preventedDamage += 1;
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "brush_off_red":
      if ($preventable && $damage <= 3) {
        $preventedDamage = $damage;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "brush_off_yellow":
      if ($preventable && $damage <= 2) {
        $preventedDamage = $damage;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "brush_off_blue":
      if ($preventable && $damage == 1) {
        $preventedDamage = $damage;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "peace_of_mind_red":
      if ($type == "COMBAT") {
        if ($preventable) $preventedDamage += 4;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "peace_of_mind_yellow":
      if ($type == "COMBAT") {
        if ($preventable) $preventedDamage += 3;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "peace_of_mind_blue":
      if ($type == "COMBAT") {
        if ($preventable) $preventedDamage += 2;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "break_of_dawn_red":
    case "break_of_dawn_yellow":
    case "break_of_dawn_blue":
      if ($effects[0] == "break_of_dawn_red") $prevention = 4;
      else if ($effects[0] == "break_of_dawn_yellow") $prevention = 3;
      else if ($effects[0] == "break_of_dawn_blue") $prevention = 2;
      if (TalentContains($source, "SHADOW", $otherPlayer)) {
        if ($preventable) $preventedDamage += $prevention;
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "interlude_red":
      if ($preventable) $preventedDamage += 3;
      RemoveCurrentTurnEffect($index);
      break;
    case "interlude_yellow":
      if ($preventable) $preventedDamage += 2;
      RemoveCurrentTurnEffect($index);
      break;
    case "interlude_blue":
      if ($preventable) $preventedDamage += 1;
      RemoveCurrentTurnEffect($index);
      break;
    case "evo_circuit_breaker_red":
    case "evo_atom_breaker_red":
    case "evo_face_breaker_red":
    case "evo_mach_breaker_red": //Card
    case "evo_circuit_breaker_red_equip":
    case "evo_atom_breaker_red_equip":
    case "evo_face_breaker_red_equip":
    case "evo_mach_breaker_red_equip": //Equipment
      if (!isset($effects[1]) || $effects[1] != "BUFF") {
        if ($preventable) $preventedDamage += intval($effects[1]);
        RemoveCurrentTurnEffect($index);
      }
      break;
    case "no_fear_red":
      if ($preventable) $preventedDamage += intval($effects[1]);
      RemoveCurrentTurnEffect($index);
      break;
    case "battlefront_bastion_blue":
    case "battlefront_bastion_red":
    case "battlefront_bastion_yellow":
      if ($preventable) {
        $preventedDamage += 1;
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "battered_not_broken_red":
      if ($preventable) {
        $preventedDamage += 2;
        PlayAura("might", $player); 
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "take_it_on_the_chin_red":
      if ($preventable) {
        $preventedDamage += 2;
        PlayAura("agility", $player); 
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "slap_happy_red":
      if ($preventable) {
        $preventedDamage += 2;
        PlayAura("vigor", $player); 
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "sheltered_cove":
      if ($preventable) $preventedDamage += 2;
      RemoveCurrentTurnEffect($index);
      break;
    case "trip_the_light_fantastic_red":
    case "trip_the_light_fantastic_yellow":
    case "trip_the_light_fantastic_blue":
    case "dissolving_shield_red":
    case "dissolving_shield_yellow":
    case "dissolving_shield_blue":
    case "radiant_view": case "radiant_raiment": case "radiant_touch": case "radiant_flow":
    case "hold_the_line_blue":
    case "twinkle_toes":
    case "well_grounded":
    case "oldhim_grandfather_of_eternity": case "oldhim":
    case "bone_head_barrier_yellow":
    case "seeds_of_tomorrow_blue":
      if ($preventable) {
        $damageToPrevent = min($damage, $effects[1]);
        $preventedDamage += $damageToPrevent;
        $effects[1] -= $damageToPrevent;
        $currentTurnEffects[$index] = $effects[0] . "-" . $effects[1];
      }
      if ($effects[1] <= 0 || !$preventable) RemoveCurrentTurnEffect($index);
      break;
    case "haunting_rendition_red":
      if ($preventable) {
        $damageToPrevent = min($damage, $effects[1]);
        $preventedDamage += $damageToPrevent;
        if($effects[1] == 2) PlayAura("runechant", $player); 
        $effects[1] -= $damageToPrevent;
        $currentTurnEffects[$index] = $effects[0] . "-" . $effects[1];
      }
      if ($effects[1] <= 0 || !$preventable) RemoveCurrentTurnEffect($index);
      break;
    case "mental_block_blue":
      if ($preventable) {
        $damageToPrevent = min($damage, $effects[1]);
        $preventedDamage += $damageToPrevent;
        if($effects[1] == 2) PlayAura("ponder", $player); 
        $effects[1] -= $damageToPrevent;
        $currentTurnEffects[$index] = $effects[0] . "-" . $effects[1];
      }
      if ($effects[1] <= 0) RemoveCurrentTurnEffect($index);
      break;
    case "moon_chakra_red":
      if ($preventable) {
        $preventedDamage = match ($currentTurnEffects[$index]) {
          "moon_chakra_red-1" => 3,
          default => 5,
        };
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "moon_chakra_yellow":
      if ($preventable) {
        $preventedDamage = match ($currentTurnEffects[$index]) {
          "moon_chakra_yellow-1" => 2,
          default => 4,
        };
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "moon_chakra_blue":
      if ($preventable) {
        $preventedDamage = match ($currentTurnEffects[$index]) {
          "moon_chakra_blue-1" => 1,
          default => 3,
        };
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "cloud_cover_yellow":
    case "sigil_of_shelter_yellow":
      if ($preventable) {
        $preventedDamage += 2;
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "sigil_of_shelter_blue":
      if ($preventable) {
        $preventedDamage += 1;
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "sanctuary_of_aria":
      if ($source == $currentTurnEffects[$index + 2]) {
        if ($preventable) {
          $preventedDamage += $currentTurnEffects[$index + 3];
          $currentTurnEffects[$index + 3] -= $damage;
        }
        if ($currentTurnEffects[$index + 3] <= 0) RemoveCurrentTurnEffect($index);
      }
      break;
    case "misfire_dampener":
      if ($preventable) {
        $preventedDamage += intval($effects[1]);
        RemoveCurrentTurnEffect($index);
        break;
      }
      break;
    case "sawbones_dock_hand_yellow":
      $preventedDamage += 1;
      RemoveCurrentTurnEffect($index);
      break;
    case "throw_caution_to_the_wind_blue":
      if ($preventable) {
        $preventedDamage += intval($effects[1]);
      }
      RemoveCurrentTurnEffect($index);
      break;
    case "light_up_the_leaves_red":
      if ($source == $currentTurnEffects[$index + 2]) {
        if ($preventable && $type == "ARCANE") {
          $preventedDamage += $currentTurnEffects[$index + 3];
          $currentTurnEffects[$index + 3] -= $damage;
          if ($currentTurnEffects[$index + 3] <= 0) $remove = true;
          elseif ($source == "spectral_shield" || $source == "runechant" || $source == "aether_ashwing") $remove = true;
          elseif (!IsStaticType(CardType($source))) $remove = true;
        }
        elseif (!$preventable) {
          $remove = true;
        }
      }
      if ($remove) RemoveCurrentTurnEffect($index);
      break;
    default:
      break;
  }
  //apply vambrace only once and only after the first instance of prevention
  if ($type == "COMBAT" && $vambraceAvailable && $preventedDamage > 0) {
    $preventedDamage -= 1;
    $vambraceAvailable = false;
    $vambraceRemove = true;
  }
  $damage -= $preventedDamage;
  if ($vambraceRemove) SearchCurrentTurnEffects("vambrace_of_determination", $player, remove:true);
  return $damage;
}

function CurrentEffectAttackAbility($attackIndex=-1)
{
  global $currentTurnEffects, $CombatChain, $mainPlayer;
  global $CS_PlayIndex, $defPlayer;
  if (!$CombatChain->HasCurrentLink()) return;
  $attackID = $CombatChain->AttackCard()->ID();
  $attackType = CardType($attackID);
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "oath_of_steel_red":
          if ($attackType == "W" && $attackIndex == 0) { //don't trigger when cards are blocking
            $character = &GetPlayerCharacter($mainPlayer);
            ++$character[GetClassState($mainPlayer, $CS_PlayIndex) + 3];
          }
          break;
        case "seeds_of_agony_red":
        case "seeds_of_agony_yellow":
        case "seeds_of_agony_blue":
          if ($currentTurnEffects[$i] == "seeds_of_agony_red") $maxCost = 2;
          else if ($currentTurnEffects[$i] == "seeds_of_agony_yellow") $maxCost = 1;
          else $maxCost = 0;
          if ($attackType == "AA" && CardCost($attackID) <= $maxCost) {
            SetArcaneTarget($mainPlayer, $currentTurnEffects[$i]);
            AddDecisionQueue("SHOWSELECTEDTARGET", $mainPlayer, "-", 1);
            AddDecisionQueue("ADDTRIGGER", $mainPlayer, $currentTurnEffects[$i], 1);
            $remove = true;
          }
          break;
        case "knife_through_butter_red-GOAGAIN":
        case "knife_through_butter_yellow-GOAGAIN":
        case "knife_through_butter_blue-GOAGAIN":
          if (IsHeroAttackTarget() && CheckMarked($defPlayer)) GiveAttackGoAgain();
          break;
        case "public_bounty_red-UNSET":
        case "public_bounty_yellow-UNSET":
        case "public_bounty_blue-UNSET":
          if (IsHeroAttackTarget() && CheckMarked($defPlayer)) {
            $cardID = explode("-", $currentTurnEffects[$i])[0];
            $currentTurnEffects[$i] = $cardID;
          }
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
}

function CurrentEffectPlayAbility($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $actionPoints, $CS_LastDynCost;

  if (DynamicCost($cardID) != "") $cost = GetClassState($currentPlayer, $CS_LastDynCost);
  else $cost = CardCost($cardID, $from);
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    $card = GetClass($currentTurnEffects[$i], $currentPlayer);
      if ($card !=  "-") $card->PlayCardEffectAbility($cardID, $from, $remove);
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "lead_the_charge_red":
          $cardType = CardType($cardID);
          if ((DelimStringContains($cardType, "A") || $cardType == "AA") && $cost >= 0) {
            ++$actionPoints;
            $remove = true;
          }
          break;
        case "lead_the_charge_yellow":
          $cardType = CardType($cardID);
          if ((DelimStringContains($cardType, "A") || $cardType == "AA") && $cost >= 1) {
            ++$actionPoints;
            $remove = true;
          }
          break;
        case "lead_the_charge_blue":
          $cardType = CardType($cardID);
          if ((DelimStringContains($cardType, "A") || $cardType == "AA") && $cost >= 2) {
            ++$actionPoints;
            $remove = true;
          }
          break;
        default:
          break;
      }
      if ($remove) RemoveCurrentTurnEffect($i);
    }
  }
  return false;
}

function CurrentEffectPlayOrActivateAbility($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      $remove = false;
      $effectArr = explode(",", $currentTurnEffects[$i]);
      switch ($effectArr[0]) {
        case "chane_bound_by_shadow":
        case "chane":
          $cardType = CardType($cardID);
          $abilityType = GetResolvedAbilityType($cardID, $from);
          if ($abilityType == "AA" || $abilityType == "") {
            if (($cardType == "AA" || $cardType == "W" || $cardType == "T") && (ClassContains($cardID, "RUNEBLADE", $currentPlayer) || TalentContains($cardID, "SHADOW", $currentPlayer))) {
              GiveAttackGoAgain();
              $remove = true;
            }
          }
          break;
        default:
          break;
      }
      if ($remove) RemoveCurrentTurnEffect($i);
    }
  }
  $currentTurnEffects = array_values($currentTurnEffects); //In case any were removed
  return false;
}

function CurrentEffectAfterPlayOrActivateAbility($cache = true)
{
  global $currentTurnEffects, $currentPlayer;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      $remove = false;
      $effectArr = explode(",", $currentTurnEffects[$i]);
      switch ($effectArr[0]) {
        case "gauntlets_of_iron_will":
          if ($cache) CacheCombatResult();
          if ($effectArr[1] != "ACTIVE" && CachedTotalPower() > intval($effectArr[1]) + LinkBasePower()) $currentTurnEffects[$i] = "gauntlets_of_iron_will,ACTIVE";
          break;
        default:
          break;
      }
      if ($remove) RemoveCurrentTurnEffect($i);
    }
  }
  $currentTurnEffects = array_values($currentTurnEffects); //In case any were removed
  return false;
}

function CurrentEffectGrantsInstantGoAgain($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $layers;
  $hasGoAgain = false;
  $usedGreaves = false;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "lightning_greaves": 
          if ($cardID == $currentTurnEffects[$i + 2] && !$usedGreaves) {
            $hasGoAgain = true;
            $usedGreaves = true;
            RemoveCurrentTurnEffect($i);
          }
          break;
        default:
          break;
      }
    }
  }
  return $hasGoAgain;
}

function CurrentEffectGrantsNonAttackActionGoAgain($cardID, $from)
{
  global $currentTurnEffects, $currentPlayer, $CS_AdditionalCosts;
  $hasGoAgain = false;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      if (strlen($currentTurnEffects[$i]) > 6) $turnEffects = explode(",", $currentTurnEffects[$i]);
      else $turnEffects[0] = $currentTurnEffects[$i];
      switch ($turnEffects[0]) {
        case "bloodrush_bellow_yellow-GOAGAIN":
          $hasGoAgain = true;
          $remove = true;
          break;
        case "chane_bound_by_shadow":
        case "chane":
          if ((ClassContains($cardID, "RUNEBLADE", $currentPlayer) || TalentContains($cardID, "SHADOW", $currentPlayer)) && $cardID != $currentTurnEffects[$i]) {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "flash_red":
          if (CardCost($cardID) >= 0) {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "flash_yellow":
          if (CardCost($cardID) >= 1) {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "flash_blue":
          if (CardCost($cardID) >= 2) {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "amulet_of_lightning_blue":
          $hasGoAgain = true;
          $remove = true;
          break;
        case "moon_wish_red-GA":
          $hasGoAgain = ($cardID == "sun_kiss_red" || $cardID == "sun_kiss_yellow" || $cardID == "sun_kiss_blue");
          break;
        case "tear_through_the_portal_red":
        case "tear_through_the_portal_yellow":
        case "tear_through_the_portal_blue":
          if (SearchCurrentTurnEffects($turnEffects[0] . "," . $cardID, $currentPlayer) && $from == "BANISH") {
            $hasGoAgain = true;
            $remove = true;
          }
          break;
        case "first_tenet_of_chi_wind_blue":
          if (ColorContains($cardID, 3, $currentPlayer)) {
            $hasGoAgain = true;
            if ($cardID != $turnEffects[0]) $remove = true;
          }
          break;
        case "arc_lightning_yellow-GOAGAIN":
          if (IsStaticType(CardType($cardID), $from)) break;
          if(SearchCurrentTurnEffects("arc_lightning_yellow", $currentPlayer) && !IsMeldInstantName(GetClassState($currentPlayer, $CS_AdditionalCosts)) && (GetClassState($currentPlayer, $CS_AdditionalCosts) != "Both" || $from == "MELD")) {
            // this is a bandaid fix, go again is getting checked twice for meld cards when only the left side is played
            if (!HasMeld($cardID)) $hasGoAgain = true;
            if ($cardID != "arc_lightning_yellow") $remove = true;
          }
          break;
        case "goldkiss_rum":
          $hasGoAgain = true;
          $remove = true;
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
  return $hasGoAgain;
}

function CurrentEffectGrantsGoAgain()
{
  global $currentTurnEffects, $mainPlayer;
  global $CCS_GoesWhereAfterLinkResolves, $CombatChain;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if (!isset($currentTurnEffects[$i + 1])) continue;
    if ($currentTurnEffects[$i + 1] == $mainPlayer && IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i)) {
      if (strlen($currentTurnEffects[$i]) > 6) $turnEffects = explode(",", $currentTurnEffects[$i]);
      else $turnEffects[0] = $currentTurnEffects[$i];
      $card = GetClass($turnEffects[0], $mainPlayer);
      if ($card != "-") {
        $param = explode("-", $turnEffects[0])[1] ?? "-";
        if ($card->CurrentEffectGrantsGoAgain($param)) return true;
      }
      if (DoesCurrentTurnEffectGrantGoAgain($turnEffects[0])) return true;
    }
  }
  $activeEffects = explode(",", $CombatChain->AttackCard()->StaticBuffs());
  foreach ($activeEffects as $effectSetID) {
    $effect = ConvertToCardID($effectSetID);
    if (IsCombatEffectActive($effect)) {
      $card = GetClass($effect, $mainPlayer);
      if ($card != "-") {
        if ($card->CurrentEffectGrantsGoAgain("-")) return true;
      }
      if (DoesCurrentTurnEffectGrantGoAgain($effect)) return true;
    }
  }
  return false;
}

function DoesCurrentTurnEffectGrantGoAgain($effectID) {
  global $combatChainState, $CCS_AttackFused, $CS_NumAuras, $mainPlayer;
  global $CCS_GoesWhereAfterLinkResolves;
  switch ($effectID) {
    case "driving_blade_red":
    case "driving_blade_yellow":
    case "driving_blade_blue":
    case "snapdragon_scalers":
    case "rapid_fire_yellow":
    case "art_of_war_yellow-3":
    case "breeze_rider_boots":
    case "flood_of_force_yellow":
    case "spoils_of_war_red":
    case "hit_and_run_red-1":
    case "hit_and_run_yellow-1":
    case "hit_and_run_blue-1":
    case "perch_grapplers":
    case "mauvrion_skies_red":
    case "mauvrion_skies_yellow":
    case "mauvrion_skies_blue":
    case "dread_screamer_red":
    case "dread_screamer_yellow":
    case "dread_screamer_blue":
    case "seeping_shadows_red":
    case "seeping_shadows_yellow":
    case "seeping_shadows_blue":
    case "shadow_puppetry_red":
    case "rouse_the_ancients_blue":
    case "captains_call_red-2":
    case "captains_call_yellow-2":
    case "captains_call_blue-2":
    case "lexi-1":
    case "lexi_livewire-1":
    case "voltaire_strike_twice-2":
    case "fulminate_yellow-GA":
    case "flash_red":
    case "flash_yellow":
    case "flash_blue":
    case "amulet_of_lightning_blue":
    case "bravo_star_of_the_show":
    case "ride_the_tailwind_red":
    case "ride_the_tailwind_yellow":
    case "ride_the_tailwind_blue":
    case "life_of_the_party_red-3":
    case "life_of_the_party_yellow-3":
    case "life_of_the_party_blue-3":
    case "glistening_steelblade_yellow":
    case "on_a_knife_edge_yellow":
    case "soaring_strike_red":
    case "soaring_strike_yellow":
    case "soaring_strike_blue":
    case "burn_away_red":
    case "precision_press_red":
    case "precision_press_yellow":
    case "precision_press_blue":
    case "tear_through_the_portal_red":
    case "tear_through_the_portal_yellow":
    case "tear_through_the_portal_blue":
    case "agility":
    case "coercive_tendency_blue":
    case "beckoning_mistblade":
    case "slither":
    case "first_tenet_of_chi_wind_blue":
    case "shadowrealm_horror_red-2":
    case "flight_path":
    case "arc_lightning_yellow-GOAGAIN":
    case "agility_stance_yellow":
    case "dragonscaler_flight_path":
    case "path_of_vengeance":
    case "trot_along_blue":
    case "the_hand_that_pulls_the_strings":
    case "bank_breaker":
    case "flying_high_red": case "flying_high_yellow": case "flying_high_blue":
    case "peg_leg": case "goldkiss_rum":
    case "sealace_sarong":
    case "cogwerx_blunderbuss":
    case "avast_ye_blue":
    case "jittery_bones_red": case "jittery_bones_yellow": case "jittery_bones_blue":
    case "restless_bones_red": case "restless_bones_yellow": case "restless_bones_blue":
    case "line_blue":
    case "swift_shot_red":
    case "mutiny_on_the_swiftwater_blue":
    case "quick_clicks":
    case "breakwater_undertow-GOAGAIN":
      return true;
    case "weave_lightning_red":
    case "weave_lightning_yellow":
    case "weave_lightning_blue":
      if ($combatChainState[$CCS_AttackFused] == 1) return true;
      else break;
    case "luminaris_angels_glow-1":
    case "luminaris_angels_glow-2":
      if ($combatChainState[$CCS_GoesWhereAfterLinkResolves] == "-") break;
      if (SearchPitchForColor($mainPlayer, 2) > 0) return true;
      else break;
    case "machinations_of_dominion_blue":
      if(GetClassState($mainPlayer, $CS_NumAuras) >= 1) return true;
      else break;
    default:
      break;
  }
  return false;
}

function CurrentEffectPreventsGoAgain($cardID, $from="-", $additionalCosts="-")
{
  global $currentTurnEffects, $mainPlayer, $CS_AdditionalCosts;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if (!isset($currentTurnEffects[$i + 1])) continue;
    if ($currentTurnEffects[$i + 1] == $mainPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "spinal_crush_red":
          $additionalCosts = $additionalCosts == "-" ? GetClassState($mainPlayer, $CS_AdditionalCosts) : $additionalCosts;
          //this call to cardtype needs "Where the card is currently, not where was it played from
          $cardType = CardType($cardID, "LAYER", additionalCosts:$additionalCosts);
          $resolvedAbilityType = GetResolvedAbilityType($cardID, $from);
          if(HasMeld($cardID) && !IsMeldInstantName($additionalCosts)
          || DelimStringContains($cardType, "AA") 
          || DelimStringContains($cardType, "A") 
          || $resolvedAbilityType == "AA"
          || $resolvedAbilityType == "A" ){
            return true;
          }
          break;
        case "no_tall_tales_yellow":
          if (!str_contains($from, "THEIR")) return true;
          break;
        case "no_tall_tales_yellow-SELF":
          if (str_contains($from, "THEIR")) return true;
          break;
        default:
          break;
      }
    }
  }
  return false;
}

function CurrentEffectPreventsDefenseReaction($from)
{
  global $currentTurnEffects, $currentPlayer;
  $reactionPrevented = false;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if (!isset($currentTurnEffects[$i + 1])) continue;
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "remorseless_red":
          if ($from == "ARS" && IsCombatEffectActive($currentTurnEffects[$i])) $reactionPrevented = true;
          break;
        case "increase_the_tension_red-1":
        case "increase_the_tension_yellow-1":
        case "increase_the_tension_blue-1":
          if ($from == "HAND" && IsCombatEffectActive($currentTurnEffects[$i])) $reactionPrevented = true;
          break;
        case "release_the_tension_red-1":
        case "release_the_tension_yellow-1":
        case "release_the_tension_blue-1":
          if ($from == "ARS" && IsCombatEffectActive($currentTurnEffects[$i])) $reactionPrevented = true;
          break;
        default:
          break;
      }
    }
  }
  return $reactionPrevented;
}

function CurrentEffectPreventsDraw($player, $isMainPhase)
{
  global $currentTurnEffects;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if ($currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        case "cranial_crush_blue":
          if ($isMainPhase) WriteLog("Draw prevented by " . CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]));
          return $isMainPhase;
        default:
          break;
      }
    }
  }
  return false;
}

function CurrentEffectIntellectModifier($remove = false)
{
  global $currentTurnEffects, $mainPlayer;
  $intellectModifier = 0;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    if ($currentTurnEffects[$i + 1] == $mainPlayer) {
      $cardID = ExtractCardID($currentTurnEffects[$i]);
      switch ($cardID) {
        case "helm_of_isens_peak":
        case "pursuit_of_knowledge_blue":
        case "stamp_authority_blue":
        case "great_library_of_solana":
        case "nourishing_emptiness_red":
        case "evo_steel_soul_memory_blue":
        case "evo_steel_soul_memory_blue_equip":
        case "sapphire_amulet_blue":
          if($remove){// Handle transformations (Blasmophet, Dishonor, etc) restarting Intellect
            RemoveCurrentTurnEffect($i);
            break;
          }
          $intellectModifier += 1;
          break;
        case "knucklehead":
          if($remove){// Handle transformations (Blasmophet, Dishonor, etc) restarting Intellect
            RemoveCurrentTurnEffect($i);
            break;
          }
          $characters = GetPlayerCharacter($mainPlayer);
          $intellectModifier -= CharacterIntellect($characters[0]) - substr($currentTurnEffects[$i], -1);
          break;
        case "ten_foot_tall_and_bulletproof_red":
          if($remove){// Handle transformations (Blasmophet, Dishonor, etc) restarting Intellect
            RemoveCurrentTurnEffect($i);
            break;
          }
          $intellectModifier -= 2;
          break;
        default:
          break;
      }
    }
  }
  return $intellectModifier;
}

function CurrentEffectStartTurnAbilities() {
  global $mainPlayer, $currentTurnEffects;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if ($currentTurnEffects[$i + 1] != $mainPlayer) continue;
    switch ($currentTurnEffects[$i]) {
      case "blinding_of_the_old_ones_red":
        BlindPlayer($mainPlayer);
        break;
      default:
        break;
    }
  }
}

function CurrentEffectEndTurnAbilities()
{
  global $currentTurnEffects, $mainPlayer, $defPlayer;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    $cardID = ExtractCardID($currentTurnEffects[$i]);
    if (SearchCurrentTurnEffects($cardID . "-UNDER", $currentTurnEffects[$i + 1])) {
      AddNextTurnEffect($currentTurnEffects[$i], $currentTurnEffects[$i + 1]);
    }
    $card = GetClass($cardID, $currentTurnEffects[$i + 1]);
    if ($card != "-") $card->CurrentEffectEndTurnAbilities($i, $remove);
    switch ($cardID) {
      case "glisten_red":
      case "glisten_yellow":
      case "glisten_blue":
      case "oath_of_steel_red":
        if ($mainPlayer == $currentTurnEffects[$i + 1]) {
          $char = &GetPlayerCharacter($currentTurnEffects[$i + 1]);
          for ($j = 0; $j < count($char); $j += CharacterPieces()) {
            if (TypeContains($char[$j], "W", $mainPlayer)) $char[$j + 3] = 0;
          }
          $remove = true;
        }
        break;
      case "adaptive_plating": case "adaptive_dissolver": case "adaptive_alpha_mold": case "frostbite":
        AddNextTurnEffect($currentTurnEffects[$i], $currentTurnEffects[$i + 1]);
        break;
      case "blinding_of_the_old_ones_red":
        BlindPlayer($mainPlayer, unblind: true);
        $remove = true;
        break;
      case "annexation_of_all_things_known_yellow":
        if (str_contains($currentTurnEffects[$i], "-MAIN") && $currentTurnEffects[$i+1] == $defPlayer) {
          AddNextTurnEffect($currentTurnEffects[$i], $defPlayer);
        }
        break;
      default:
        break;
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
}

function IsCombatEffectActive($cardID, $defendingCard = "", $SpectraTarget = false, $flicked = false)
{
  global $CombatChain, $mainPlayer;
  if ($SpectraTarget) return;
  if ($cardID == "AIM") return true;
  $cardID = ShiyanaCharacter($cardID);
  if ($defendingCard == "") $cardToCheck = $CombatChain->AttackCard()->ID();
  else $cardToCheck = $defendingCard;
  $trimID = ExtractCardID($cardID);
  $card = GetClass($trimID, $mainPlayer);
  if ($card != "-") {
    $parameter = explode("-", $cardID)[1] ?? "-";
    return $card->CombatEffectActive($parameter, $defendingCard, $flicked);
  }
  $set = CardSet($cardID);
  if ($set == "WTR") return WTRCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "ARC") return ARCCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "CRU") return CRUCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "MON") return MONCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "ELE") return ELECombatEffectActive($cardID, $cardToCheck);
  else if ($set == "EVR") return EVRCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "DVR") return DVRCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "UPR") return UPRCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "DYN") return DYNCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "OUT") return OUTCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "DTD") return DTDCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "TCC") return TCCCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "EVO") return EVOCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "HVY") return HVYCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "MST") return MSTCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "AAZ") return AAZCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "TER") return TERCombatEffectActive($cardID);
  else if ($set == "AUR") return AURCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "ROS") return ROSCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "AIO") return AIOCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "AJV") return AJVCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "HNT") return HNTCombatEffectActive($cardID, $cardToCheck, $flicked);
  else if ($set == "AST") return ASTCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "AMX") return AMXCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "SEA") return SEACombatEffectActive($cardID, $cardToCheck);
  else if ($set == "AGB") return AGBCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "MPG") return MPGCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "ASR") return ASRCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "SUP") return SUPCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "ARR") return ARRCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "AAC") return AACCombatEffectActive($cardID, $cardToCheck);
  else if ($set == "PEN") return PENCombatEffectActive($cardID, $cardToCheck);
  switch ($cardID) {
    case "banneret_of_salvation_yellow":
      return DTDCombatEffectActive($cardID, $cardToCheck);
    case "banneret_of_vigor_yellow":
      return DTDCombatEffectActive($cardID, $cardToCheck);
    case "ira_scarlet_revenger":
    case "bravo_flattering_showman":
      return true;
    default:
      return;
  }
}

function IsCombatEffectPersistent($cardID)
{
  global $Card_LifeBanner, $Card_ResourceBanner;
  $effectArr = explode(",", $cardID);
  $cardID = ShiyanaCharacter($effectArr[0]);
  if (DelimStringContains($cardID, "art_of_the_dragon_blood_red", true)) return true;
  $mode = explode("-", $cardID)[1] ?? "-";
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->IsCombatEffectPersistent($mode);
  switch ($cardID) {
    case "bloodrush_bellow_yellow":
    case "bravo_showstopper":
    case "bravo":
    case "rapid_fire_yellow":
    case "art_of_war_yellow-1":
    case "plunder_run_red-1":
    case "plunder_run_yellow-1":
    case "plunder_run_blue-1":
    case "crater_fist":
    case "breeze_rider_boots":
    case "spoils_of_war_red-2":
    case "plasma_purifier_red":
    case "perch_grapplers":
    case "poison_the_tips_yellow":
    case "cash_in_yellow":
    case "lumina_ascension_yellow":
    case "v_of_the_vanguard_yellow":
    case "ray_of_hope_yellow":
    case "phantasmal_footsteps":
    case "gallantry_gold":
    case "spill_blood_red":
    case "eclipse_existence_blue":
    case "stubby_hammerers":
    case "exude_confidence_red":
    case "blizzard_bolt_red":
    case "blizzard_bolt_yellow":
    case "blizzard_bolt_blue":
    case "buzz_bolt_red":
    case "buzz_bolt_yellow":
    case "buzz_bolt_blue":
    case "chilling_icevein_red":
    case "chilling_icevein_yellow":
    case "chilling_icevein_blue":
    case "frazzle_red":
    case "frazzle_yellow":
    case "frazzle_blue":
    case "force_of_nature_blue-HIT":
    case "explosive_growth_red":
    case "explosive_growth_yellow":
    case "explosive_growth_blue":
    case "fulminate_yellow-BUFF":
    case "fulminate_yellow-GA":
    case "flashfreeze_red-DOM":
    case "flashfreeze_red-BUFF":
    case "amulet_of_earth_blue":
    case "ice_quake_red-HIT":
    case "ice_quake_yellow-HIT":
    case "ice_quake_blue-HIT":
    case "shock_charmers":
    case "electrify_red":
    case "electrify_yellow":
    case "electrify_blue":
    case "rampart_of_the_rams_head":
    case "skull_crushers":
    case "valda_brightaxe":
    case "valda_seismic_impact":
    case "outland_skirmish_red-1":
    case "outland_skirmish_yellow-1":
    case "outland_skirmish_blue-1":
    case "rain_razors_yellow":
    case "this_rounds_on_me_blue":
    case "high_striker_red":
    case "high_striker_yellow":
    case "high_striker_blue":
    case "smashing_good_time_red-1":
    case "smashing_good_time_yellow-1":
    case "smashing_good_time_blue-1":
    case "potion_of_ironhide_blue":
    case "glistening_steelblade_yellow-1":
    case "skittering_sands_red":
    case "skittering_sands_yellow":
    case "skittering_sands_blue":
    case "heat_wave":
    case "spreading_flames_red":
    case "berserk_yellow":
    case "roar_of_the_tiger_yellow":
    case "visit_the_imperial_forge_red":
    case "visit_the_imperial_forge_yellow":
    case "visit_the_imperial_forge_blue":
    case "immobilizing_shot_red":
    case "head_leads_the_tail_red":
    case "mask_of_shifting_perspectives":
    case "blade_cuff":
    case "knives_out_blue":
    case "premeditate_red-1":
    case "figment_of_triumph_yellow":
    case "beckoning_light_red":
    case "spirit_of_war_red":
    case "blood_dripping_frenzy_blue":
    case "call_down_the_lightning_yellow":
    case "chorus_of_ironsong_yellow":
    case "hack_to_reality_yellow-HIT":
    case "metis_archangel_of_tenacity":
    case "victoria_archangel_of_triumph":
    case "evo_steel_soul_memory_blue_equip":
    case $Card_LifeBanner:
    case $Card_ResourceBanner:
    case "stonewall_impasse":
    case "kassai_of_the_golden_sand":
    case "kassai":
    case "commanding_performance_red":
    case "talk_a_big_game_blue":
    case "fabricate_red":
    case "double_down_red":
    case "coercive_tendency_blue":
    case "ancestral_harmony_blue":
    case "sacred_art_jade_tiger_domain_blue":
    case "dense_blue_mist_blue-DEBUFF":
    case "dense_blue_mist_blue-HITPREVENTION":
    case "stonewall_gauntlet":
    case "target_totalizer":
    case "thrive_yellow":
    case "burn_up__shock_red":
    case "succumb_to_temptation_yellow":
    case "gauntlets_of_the_boreal_domain-E":
    case "gauntlets_of_the_boreal_domain-I":
    case "wrath_of_retribution_red":
    case "fire_and_brimstone_red":
    case "agility_stance_yellow":
    case "power_stance_blue":
    case "knife_through_butter_red-GOAGAIN":
    case "knife_through_butter_yellow-GOAGAIN":
    case "knife_through_butter_blue-GOAGAIN":
    case "point_of_engagement_red-MARKEDBUFF":
    case "point_of_engagement_yellow-MARKEDBUFF":
    case "point_of_engagement_blue-MARKEDBUFF":
    case "rake_over_the_coals_red":
    case "poisoned_blade_red":
    case "poisoned_blade_yellow":
    case "poisoned_blade_blue":
    case "savor_bloodshed_red-HIT":
    case "imperial_seal_of_command_red":
    case "imperial_seal_of_command_red-HIT":
    case "war_cry_of_bellona_yellow-BUFF":
    case "war_cry_of_bellona_yellow-DMG":
    case "fist_pump":
    case "bam_bam_yellow":
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
    case "chill_to_the_bone_red":
    case "chill_to_the_bone_yellow":
    case "chill_to_the_bone_blue":
      return true;
    default:
      return false;
  }
}

function BeginEndPhaseEffects()
{
  global $currentTurnEffects, $mainPlayer, $EffectContext, $defPlayer;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectsPieces()) {
    $EffectContext = $currentTurnEffects[$i];
    switch ($currentTurnEffects[$i]) {
      case "revel_in_runeblood_red":
        if (CountAura("runechant", $mainPlayer) > 0) {
          WriteLog(CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]) . " destroyed your ".CardLink("runechant", "runechant")." tokens");
          DestroyAllThisAura($currentTurnEffects[$i + 1], "runechant");
        }
        break;
      case "helios_mitre-1":
        $attackCharIndex = FindCharacterIndex($mainPlayer, "helios_mitre");
        $defendCharIndex = FindCharacterIndex($defPlayer, "helios_mitre");
        if ($attackCharIndex > -1) {
          DestroyCharacter($mainPlayer, $attackCharIndex);
        } elseif ($defendCharIndex > -1) {
          DestroyCharacter($defPlayer, $defendCharIndex);
        }
      case "strategic_planning_red":
      case "strategic_planning_yellow":
      case "strategic_planning_blue":
        Draw($currentTurnEffects[$i + 1], false);
        break;
      case "hidden_agenda":
        $attackCharIndex = FindCharacterIndex($mainPlayer, "hidden_agenda");
        $defendCharIndex = FindCharacterIndex($defPlayer, "hidden_agenda");
        if ($attackCharIndex > -1) {
          DestroyCharacter($mainPlayer, $attackCharIndex);
        } elseif ($defendCharIndex > -1) {
          DestroyCharacter($defPlayer, $defendCharIndex);
        }
        break;
      case "sanctuary_of_aria-1":
        $player = $currentTurnEffects[$i+1];
        $sanctuaryIndex = GetItemIndex("sanctuary_of_aria", $player);
        DestroyItemForPlayer($player, $sanctuaryIndex, true);
        break;
      default:
        break;
    }
  }
}

function BeginEndPhaseEffectTriggers()
{
  global $currentTurnEffects, $mainPlayer, $defPlayer;
  if (SearchCurrentTurnEffects("liars_charm_yellow", $mainPlayer, true)) {
    $mainChar = &GetPlayerCharacter($mainPlayer);
    $mainChar[1] = 2;
  }
  if (SearchCurrentTurnEffects("liars_charm_yellow", $defPlayer, true)) {
    $defChar = &GetPlayerCharacter($defPlayer);
    $defChar[1] = 2;
  }
  $numBloodDebt = SearchCount(SearchBanish($mainPlayer, "", "", -1, -1, "", "", true));
  if (!IsImmuneToBloodDebt($mainPlayer) && $numBloodDebt > 0) AddLayer("TRIGGER", $mainPlayer, "BLOODDEBT");
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectsPieces()) {
    switch ($currentTurnEffects[$i]) {
      case "seek_and_destroy_red-1": 
        AddLayer("TRIGGER", $defPlayer, "seek_and_destroy_red", $currentTurnEffects[$i + 1], "-", "-");
        break;
      case "heat_seeker_red":
        AddLayer("TRIGGER", $mainPlayer, "heat_seeker_red", $currentTurnEffects[$i + 1], "-", "-");
        break;
      case "plan_for_the_worst_blue-1":
        AddLayer("TRIGGER", $defPlayer, "plan_for_the_worst_blue", $currentTurnEffects[$i + 1], "-", "-");
        break;
      default:
        break;
    }
  }
}

function ActivateAbilityEffects()
{
  global $currentPlayer, $currentTurnEffects, $mainPlayer;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      switch ($currentTurnEffects[$i]) {
        case "endless_winter_red-HIT":
          WriteLog(CardLink("endless_winter_red", "endless_winter_red") . " created a frostbite");
          PlayAura("frostbite", $currentPlayer, effectController:$mainPlayer);
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
  $currentTurnEffects = array_values($currentTurnEffects);
}

function CurrentEffectNameModifier($effectID, $effectParameter, $player)
{
  $name = "";
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return $name;
  switch ($effectID) {
    case "mask_of_many_faces":
      $name = $effectParameter;
      break;
    case "be_like_water_red":
    case "be_like_water_yellow":
    case "be_like_water_blue":
      $name = $effectParameter;
      break;
    case "crouching_tiger":
      $name = $effectParameter;
      break;
    case "retrace_the_past_blue":
      $name = $effectParameter;
      break;
    default:
      break;
  }
  return $name;
}

function EffectDefenderPowerModifiers($cardID)
{
  $mod = 0;
  global $defPlayer, $currentTurnEffects;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    $remove = false;
    if ($currentTurnEffects[$i + 1] == $defPlayer && IsCombatEffectActive($currentTurnEffects[$i], $cardID)) {
      switch ($currentTurnEffects[$i]) {
        case "herald_of_triumph_red":
        case "herald_of_triumph_yellow":
        case "herald_of_triumph_blue":
          $mod -= 1;
          break;
        case "figment_of_triumph_yellow":
        case "victoria_archangel_of_triumph":
          $mod -= 1;
          break;
        default:
          break;
      }
    }
    if ($remove) RemoveCurrentTurnEffect($i);
  }
  $currentTurnEffects = array_values($currentTurnEffects);
  return $mod;
}

function EffectAttackRestricted($cardID, $type, $from, $revertNeeded = false, $index = -1, $overrideType = "-")
{
  global $mainPlayer, $currentTurnEffects, $p2IsAI;
  $powerValue = PowerValue($cardID, $mainPlayer, "LAYER", $index);
  $hasNoAbilityTypes = GetAbilityTypes($cardID, from: $from) == "";
  $resolvedAbilityType = $overrideType == "-" ? GetResolvedAbilityType($cardID) : $overrideType;
  $abilityType = GetAbilityType($cardID, from: $from);

  if ($p2IsAI) return false;
  $restrictedBy = "";
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    if ($currentTurnEffects[$i + 1] == $mainPlayer) {
      $effectArr = explode(",", $currentTurnEffects[$i]);
      $effectID = $effectArr[0];
      switch ($effectID) {
        case "star_struck_yellow":
          if ($powerValue <= $effectArr[1] && ($type == "AA" || $resolvedAbilityType == "AA" || $abilityType == "AA") && ($hasNoAbilityTypes || $resolvedAbilityType == "AA")) {
            $restrictedBy = "star_struck_yellow";
          }
          break;
        case "crush_the_weak_red":
          if ($hasNoAbilityTypes || $resolvedAbilityType == "AA") {
            if (TypeContains($cardID, "AA") && PowerValue($cardID, $mainPlayer, "LAYER") <= 3) $restrictedBy = $effectID;
          }
          break;
        case "WarmongersPeace":
          if (($type == "AA" && !str_contains(GetAbilityTypes($cardID, from:$from), "I") || (TypeContains($cardID, "W", $mainPlayer) && $resolvedAbilityType != "I"))) $restrictedBy = "warmongers_diplomacy_blue";
          break;
        default:
          break;
      }
    }
  }
  if ($revertNeeded && $restrictedBy != "") {
    WriteLog("The attack is restricted by " . CardLink($restrictedBy, $restrictedBy) . ". Reverting the gamestate.", highlight: true);
    RevertGamestate();
    return true;
  }
  return $restrictedBy;
}

function EffectPlayCardConstantRestriction($cardID, $type, &$restriction, $phase, $modalCheck = false, $from="-")
{
  global $currentTurnEffects, $currentPlayer, $turn;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      $effectArr = explode(",", $currentTurnEffects[$i]);
      $effectID = $effectArr[0];
      switch ($effectID) {
        case "burdens_of_the_past_blue":
          // handle modal cards separately
          if ($modalCheck || GetAbilityTypes($cardID) == "") {
            if (in_array(GamestateSanitize(NameOverride($cardID, $currentPlayer)), $effectArr) && CardType($cardID) == "DR" && ($turn[0] == "A" || $turn[0] == "D" || $turn[0] == "INSTANT")) $restriction = "burdens_of_the_past_blue";
          }
          elseif(GetAbilityNames($cardID, from:$from) == "-,Defense Reaction" || GetAbilityNames($cardID, from:$from) == "Defense Reaction") {//if dreact is the only available mode
            if (in_array(GamestateSanitize(NameOverride($cardID, $currentPlayer)), $effectArr) && CardType($cardID) == "DR" && ($turn[0] == "A" || $turn[0] == "D" || $turn[0] == "INSTANT")) $restriction = "burdens_of_the_past_blue";
          }
          break;
        default:
          break;
      }
    }
  }
  return $restriction != "";
}

function EffectPlayCardRestricted($cardID, $type, $from, $revertNeeded = false, $resolutionCheck = false)
{
  global $currentTurnEffects, $currentPlayer;
  $restrictedBy = "";
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  for ($i = count($currentTurnEffects) - CurrentTurnEffectsPieces(); $i >= 0; $i -= CurrentTurnEffectsPieces()) {
    if ($currentTurnEffects[$i + 1] == $currentPlayer) {
      $effectArr = explode(",", $currentTurnEffects[$i]);
      $effectID = $effectArr[0];
      switch ($effectID) {
        case "chains_of_eminence_red":
          if ($from != "PLAY" && !IsStaticType(CardType($cardID)) && GamestateSanitize(NameOverride($cardID)) == $effectArr[1]) $restrictedBy = "chains_of_eminence_red";
          break;
        case "censor_red":
          if ($from != "PLAY" && !IsStaticType(CardType($cardID)) && GamestateSanitize(NameOverride($cardID)) == $effectArr[1]) $restrictedBy = "censor_red";
          break;
        case "WarmongersWar":
          // warmongers processing for meld cards handled in AddPrePitchDecisionQueue
          if (DelimStringContains($type, "A") && !HasMeld($cardID) && CardType($cardID) != "W") $restrictedBy = "warmongers_diplomacy_blue";
          break;
        case "WarmongersPeace":
          // str_contains(GetAbilityTypes($cardID, from:$from), "I") should allow discarding attack actions for instant abilities under peace
          if (($type == "AA" && !str_contains(GetAbilityTypes($cardID, from:$from), "I")) || (TypeContains($cardID, "W", $currentPlayer) && GetResolvedAbilityType($cardID) != "I")) $restrictedBy = "warmongers_diplomacy_blue";
          break;
        case "kabuto_of_imperial_authority":
          if (IsWeapon($cardID, $from) && !WeaponWithNonAttack($cardID, $from)) $restrictedBy = "kabuto_of_imperial_authority";
          break;
        case "coat_of_allegiance":
        case "oath_of_loyalty_red":
          if (!$resolutionCheck) {
            if (!SearchCurrentTurnEffects("fealty", $currentPlayer) && !TalentContains($cardID, "DRACONIC", $currentPlayer) && $from != "PLAY" && $from != "EQUIP" && $from != "CHAR" && !str_contains(GetAbilityTypes($cardID, from:$from), "I")) {
              if (TypeContains($cardID, "AA")) {
                // this case is needed because brand with cinderclaw isn't set to become active until after the attack is played
                $restrict = true;
                for ($j = 0; $j < count($currentTurnEffects); $j += CurrentTurnEffectPieces()) {
                  switch ($currentTurnEffects[$j]) {
                    case "brand_with_cinderclaw_red":
                    case "brand_with_cinderclaw_yellow":
                    case "brand_with_cinderclaw_blue":
                    case "enflame_the_firebrand_red":
                      $restrict = false;
                      break;
                    default:
                      break;
                    }
                }
                if ($restrict) $restrictedBy = $effectID;
              }
              else $restrictedBy = $effectID;
            }
          }
          break;
        default:
          break;
      }
    }
  }
  $foundNullTime = FindNullTime(GamestateSanitize(NameOverride($cardID)));
  // handle discarded modal cards elsewhere
  if($foundNullTime && $from == "HAND" && GetAbilityTypes($cardID, from:$from) == ""){
    $restrictedBy = "null_time_zone_blue";
    return true;
  }
  if ($revertNeeded && $restrictedBy != "") {
    WriteLog("The attack is restricted by " . CardLink($restrictedBy, $restrictedBy) . ". Reverting the gamestate.", highlight: true);
    RevertGamestate();
    return true;
  }
  return $restrictedBy;
}

function EffectCardID($effect)
{
  if ($effect == "") return $effect;
  $arr = explode(",", $effect);
  $id = $arr[0];
  $arr = explode("-", $id);
  return $arr[0];
}

function EffectsAttackYouControlModifiers($cardID, $player)
{
  global $currentTurnEffects;
  $powerModifier = 0;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    if ($currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        case "art_of_war_yellow-1":
          if (CardType($cardID) == "AA") $powerModifier += 1;
        default:
          break;
      }
    }
  }
  return $powerModifier;
}

function AdministrativeEffect($effectID)
{
  $cardID = ExtractCardID($effectID);
  switch ($cardID) {
    case "frostbite":
    case "barbed_castaway":
    case "adaptive_plating":
    case "adaptive_dissolver":
    case "adaptive_alpha_mold":
    case "marked":
      return true;
    default:
      return false;
  }
}
