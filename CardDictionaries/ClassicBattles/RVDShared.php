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
  switch($cardID) {
    case "RVD004":
      GainResources($currentPlayer, 1);
      return "";
    case "RVD013":
      Draw($currentPlayer);
      $card = DiscardRandom();
      $rv = "Discarded " . CardLink($card, $card);
      if(AttackValue($card) >= 6) {
        Intimidate();
        $rv .= " and intimidated from discarding a card with 6 or more power";
      }
      return $rv;
    case "RVD025":
      Intimidate();
      return "";
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
