<?php

  function RVDEffectAttackModifier($cardID)
  {
    switch($cardID)
    {
      case "RVD009": return 2;
      default: return 0;
    }
  }


  function RVDHasGoAgain($cardID)
  {
    switch($cardID)
    {
      case "RVD004": return true;
      case "RVD025": return true;
      default: return false;
    }
  }

  function RVDAbilityType($cardID)
  {
    switch($cardID)
    {
      case "RVD002": return "AA";
      case "RVD004": return "A";
      default: return "";
    }
  }

  function RVDAbilityCost($cardID)
  {
    switch($cardID)
    {
      case "RVD002": return 2;
      case "RVD004": return 0;
      default: return "";
    }
  }

function RVDPlayAbility($cardID)
{
  global $currentPlayer;
  $rv = "";
  switch ($cardID) {
    case "RVD004":
      $resources = &GetResources($currentPlayer);
      $resources[0] += 1;
      return "Gain 1 resource.";

    case "RVD013":
      WriteLog(CardLink($cardID, $cardID) . " draw a card.");
      MyDrawCard();
      $card = DiscardRandom();
      $rv = "Discarded " . CardLink($card, $card);
      if (AttackValue($card) >= 6) {
        Intimidate();
        $rv .= " and intimidate from discarding a card with 6 or more power";
      }
      $rv .= ".";
      return $rv;
    case "RVD025":
      $rv = "Intimidates";
      Intimidate();
      return $rv;
  }
}

function ChiefRukutanAbility($player, $index)
{
  $rv = CardLink("RVD007", "RVD007") . " Intimidates";
  Intimidate();
  $arsenal = &GetArsenal($player);
  ++$arsenal[$index + 3];
  if ($arsenal[$index + 3] == 2) {
    $rv .= " and searches for an Alpha Rampage";
    MentorTrigger($player, $index, specificCard:"WTR006");
  }
  WriteLog($rv);
}
