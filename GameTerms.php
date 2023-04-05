<?php

function PhaseName($phase)
{
  switch ($phase) {
    case "M":
      return "Main";
    case "B":
      return "Block";
    case "A":
      return "Attack Reaction";
    case "D":
      return "Defense Reaction";
    case "P":
      return "Pitch";
    case "ARS":
      return "Arsenal";
  }
}

function TypeToPlay($phase)
{
  global $turn;
  switch ($phase) {
    case "M":
      return "an Action";
    case "B":
      return "a card to block";
    case "A":
      return "a reaction";
    case "D":
      return "a reaction";
    case "P":
      return "a card to pitch";
    case "ARS":
      return "a card to add to arsenal";
    case "PDECK":
      return "a card from the pitch zone";
    case "OPT":
      return "a card to add to the deck top or bottom";
    case "CHOOSETOPOPPONENT":
      return "a card to add to the top of the deck";
    case "CHOOSEDECK":
      return "a card from deck";
    case "MAYCHOOSEDECK":
      return "a card from deck";
    case "HANDTOPBOTTOM":
      return "a card from hand";
    case "CHOOSEBOTTOM":
      return "a card to put on the bottom of the deck";
    case "CHOOSECOMBATCHAIN":
      return "a card from the chain link";
    case "CHOOSECHARACTER":
      return "a card";
    case "CHOOSETHEIRCHARACTER":
      return "a card";
    case "MAYCHOOSEHAND":
      return "a card from hand";
    case "CHOOSEHAND":
      return "a card from hand";
    case "CHOOSEHANDCANCEL":
      return "a card from hand";
    case "BUTTONINPUT":
      return "a button";
    case "BUTTONINPUTNOPASS":
      return "a button";
    case "MAYCHOOSEDISCARD":
      return "cards from discard";
    case "CHOOSEDISCARDCANCEL":
      return "cards from discard";
    case "CHOOSEDISCARD":
      return "cards from discard";
    case "MULTICHOOSEDISCARD":
      return "cards from discard";
    case "MULTICHOOSEHAND":
      return "cards from hand";
    case "MAYMULTICHOOSEHAND":
      return "cards from hand";
    case "MULTICHOOSEDECK":
      return "cards from deck";
    case "YESNO":
      return str_replace("_", " ", $turn[2]);
    case "MULTICHOOSETEXT":
      return " options";
    case "MAYMULTICHOOSETEXT":
      return " options";
    case "CHOOSEARCANE":
      return "an amount to pitch to prevent arcane damage";
    case "MAYCHOOSEARSENAL":
      return "a card from arsenal";
    case "CHOOSEARSENAL":
      return "a card from arsenal";
    case "CHOOSEARSENALCANCEL":
      return "a card from arsenal";
    case "CHOOSEMULTIZONE":
      return "a card";
    case "MAYCHOOSEMULTIZONE":
      return "a card";
    case "CHOOSEBANISH":
      return "a card from banish";
    case "INSTANT":
      return "an instant";
    case "END":
      return "an order for triggers";
    case "CHOOSEFIRSTPLAYER":
      return "who will be the first player";
    case "MAYCHOOSETHEIRDISCARD":
      return "a card from their graveyard";
    case "CHOOSEMYAURA":
      return " an aura";
    case "DYNPITCH":
      return "how much you want to pay";
    case "CHOOSEMYSOUL": case "MAYCHOOSEMYSOUL":
      return "a card from soul";
    case "INPUTCARDNAME":
      return "a card name";
  }
}

function PlayTerm($phase, $from="", $cardID="")
{
  if ($cardID != "") {
    if (IsStaticType(CardType($cardID), $from, $cardID) && $phase != "B") return "activated";
  }
  switch ($phase) {
    case "P":
      return "pitched";
    case "B":
      return "blocked with";
    default:
      return "played";
  }
}

function CardTypeName($cardType)
{
  /*
    switch($cardType)
    {
      case "A": return "Action";
      case "AR"
    }
*/
}
