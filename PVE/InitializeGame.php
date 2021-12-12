<?php

  include '../Libraries/HTTPLibraries.php';
  $gameName=TryGet("gameName");

  include 'ParseGamestate.php';
  include 'DecisionQueue.php';
  include "ZoneGetters.php";

  $bossCharacter = &GetGlobalZone("BossCharacter");
  array_push($bossCharacter, "OVRPVE001");
  array_push($bossCharacter, "0");
  array_push($bossCharacter, "OVRPVE002");
  array_push($bossCharacter, "0");

  $turn = &GetGlobalZone("Turn");
  array_push($turn, 0);//Whose turn it is -- 0 = player, 1 = boss
  array_push($turn, 4);//Cards per turn
  array_push($turn, 0);//Cards used this turn

  $bossStatus = &GetGlobalZone("BossStatus");
  array_push($bossStatus, 0);

  $bossHealth = &GetGlobalZone("BossHealth");
  array_push($bossHealth, 90);//TODO: Different health for each boss

  $bossDeck = &GetGlobalZone("BossDeck");
  $bossDeck = explode(" ", "CRU194 CRU194 CRU194 CRU194 CRU194 ARC205 ARC205 ARC205 ARC204 ARC204 CRU033 CRU032 CRU032 CRU034 CRU034 CRU034 WTR066 ARC189 ARC189 ARC189 ARC190 ARC190 ARC190 ARC190 WTR190 WTR190 WTR028 WTR028 WTR028 WTR200 WTR202 WTR202 WTR202 WTR201 WTR201 WTR204 WTR204 WTR205 WTR205 WTR205");

  $stanceDeck = &GetGlobalZone("StanceDeck");
  $stanceDeck = explode(" ", "OVRPVE004 OVRPVE005 OVRPVE006 OVRPVE007 OVRPVE008 OVRPVE009 OVRPVE010 OVRPVE011 OVRPVE012 OVRPVE013 OVRPVE014 OVRPVE015 OVRPVE006 OVRPVE008");

  $bossResources = &GetGlobalZone("BossResources");
  array_push($bossResources, 0);
  array_push($bossResources, 0);

  AddDecisionQueue("SHUFFLEGLOBALZONE", 1, "BossDeck");
  AddDecisionQueue("SHUFFLEGLOBALZONE", 1, "StanceDeck");

  ProcessDecisionQueue(1);

  include 'WriteGamestate.php';

  header("Location: PVETurn.php?gameName=$gameName&playerID=1");
?>