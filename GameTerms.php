<?php

  function PhaseName($phase)
  {
    switch($phase)
    {
      case "M": return "Main";
      case "B": return "Block";
      case "A": return "Attack Reaction";
      case "D": return "Defense Reaction";
      case "P": return "Pitch";
      case "ARS": return "Arsenal";
    }
  }

  function TypeToPlay($phase)
  {
    global $turn;
    switch($phase)
    {
      case "M": return "an Action";
      case "B": return "a card to block";
      case "A": return "an Attack Reaction";
      case "D": return "a Defense Reaction";
      case "P": return "a card to pitch";
      case "ARS": return "a card to add to arsenal";
      case "PDECK": return "a card from your pitch zone to add to the bottom of your deck";
      case "OPT": return "a card to add to the deck top or bottom";
      case "CHOOSEDECK": return "a card from deck";
      case "HANDTOPBOTTOM": return "a card from hand";
      case "CHOOSECOMBATCHAIN": return "a card from the chain link";
      case "CHOOSECHARACTER": return "a card";
      case "CHOOSETHEIRCHARACTER": return "a card";
      case "MAYCHOOSEHAND": return "a card from hand";
      case "CHOOSEHAND": return "a card from hand";
      case "CHOOSEHANDCANCEL": return "a card from hand";
      case "BUTTONINPUT": return "a button";
      case "MAYCHOOSEDISCARD": return "cards from discard";
      case "CHOOSEDISCARDCANCEL": return "cards from discard";
      case "CHOOSEDISCARD": return "cards from discard";
      case "MULTICHOOSEDISCARD": return "cards from discard";
      case "MULTICHOOSEHAND": return "cards from hand";
      case "MULTICHOOSEDECK": return "cards from deck";
      case "YESNO": return str_replace("_", " ", $turn[2]);
      case "MULTICHOOSETEXT": return " options";
      case "CHOOSEARCANE": return "an amount to pitch to prevent arcane damage";
      case "MAYCHOOSEARSENAL": return "a card from arsenal";
      case "CHOOSEARSENAL": return "a card from arsenal";
      case "CHOOSEARSENALCANCEL": return "a card from arsenal";
      case "CHOOSEMULTIZONE": return "a card";
      case "CHOOSEBANISH": return "a card from banish";
      case "INSTANT": return "an instant";
    }
  }

  function PlayTerm($phase)
  {
    switch($phase)
    {
      case "P": return "pitched";
      case "B": return "blocked with";
      default: return "played";
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

?>

