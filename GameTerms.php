<?php

// Removed function PhaseName($phase) as it was not being used. Kept it here for reference for the new developpers.
/* PhaseName
    case "M": "Main"
    case "B": "Block"
    case "A": "Attack Reaction"
    case "D": "Defense Reaction"
    case "P": "Pitch"
    case "ARS": "Arsenal"
*/

function TypeToPlay($phase)
{
  global $turn;
  switch ($phase) {
    case "M":
      return "an action";
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
    case "CHOOSETOP": case "CHOOSETOPOPPONENT":
      return "a card to add to the top of the deck";
    case "CHOOSEDECK": case "CHOOSETHEIRDECK":
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
    case "CHOOSEHAND": 
    case "CHOOSETHEIRHAND":
    case "CHOOSEHANDCANCEL":
      return "a card from hand";
    case "BUTTONINPUT":
      return "a button";
    case "BUTTONINPUTNOPASS":
      return "a button";
    case "MAYCHOOSEDISCARD":
      return "cards from the graveyard";
    case "CHOOSEDISCARDCANCEL":
      return "cards from the graveyard";
    case "CHOOSEDISCARD":
      return "cards from the graveyard";
    case "MULTICHOOSEDISCARD":
      return "cards from the graveyard";
    case "MULTICHOOSEHAND":
      return "cards from hand";
    case "MAYMULTICHOOSEHAND":
      return "cards from hand";
    case "MULTICHOOSEDECK":
      return "cards from deck";
    case "MULTICHOOSEBANISH":
      return "cards from banish";
    case "YESNO":
    case "OK":
      return str_replace("_", " ", $turn[2] ?? "");
    case "MULTICHOOSETEXT":
      return " options";
    case "MAYMULTICHOOSETEXT":
      return " options";
    case "CHOOSEARCANE":
      return "an amount to pitch to Arcane Barrier:";
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
    case "ENDPHASE":
      return "an order for triggers";
    case "CHOOSEFIRSTPLAYER":
      return "who will be the first player";
    case "MAYCHOOSETHEIRDISCARD":
      return "a card from their graveyard";
    case "CHOOSEMYAURA":
      return " an aura";
    case "DYNPITCH":
      return "how much you wish to pay";
    case "CHOOSEMYSOUL": case "MAYCHOOSEMYSOUL":
      return "a card from soul";
    case "INPUTCARDNAME":
      return "a card name";
    case "CHOOSENUMBER":
      return "a number";
  }
}

function PlayTerm($phase, $from="", $cardID="")
{
  if((canBeAddedToChainDuringDR($cardID) && $phase == "D")) return "blocked with";
  if ($cardID != "") {
    if (IsStaticType(CardType($cardID), $from, $cardID) && $phase != "B") return "activated";
  }
  return match ($phase) {
    "P" => "pitched",
    "B" => "blocked with",
    default => "played",
  };
}