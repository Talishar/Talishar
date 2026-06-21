<?php

function SonataArcanixQueue($cardID, $controller, $resourcesPaid) {
  $xVal = $resourcesPaid/2;
  $numRevealed = 3 + $xVal;
  WriteLog(CardLink($cardID, $cardID) . " reveals " . $numRevealed . " cards.");
  Await($controller, "DeckTopCards", "cardIDs", number: $numRevealed, subsequent: 0);
  Await($controller, "RevealCards", "LASTRESULT");
  Await($controller, "sonata_arcanix_red", "maxNumber|minNumber|indices", mode: "choose_cards");
  Await($controller, "MultiChooseDeck", "indices");
  Await($controller, "MultiRemoveDeck", "cardIDs");
  Await($controller, "sonata_arcanix_red", "LASTRESULT", mode: "deal_arcane");
  Await($controller, "ShuffleDeck", "LASTRESULT", final: true);
}