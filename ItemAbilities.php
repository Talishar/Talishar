<?php

  function ItemPlayAbilities($cardID, $from)
  {
    global $currentPlayer;
    $items = &GetItems($currentPlayer);
    for($i=count($items)-ItemPieces(); $i>=0; $i-=ItemPieces())
    {
      $remove = false;
      switch($items[$i])
      {
        case "EVR189": if($from == "BANISH") { TalismanOfCremationBanishPlay(); $remove = true; } break;
        default: break;
      }
      if($remove) DestroyItemForPlayer($currentPlayer, $i);
    }
  }

  function DestroyMainItem($index)
  {
    global $mainPlayer;
    DestroyItemForPlayer($mainPlayer, $index);
  }

  function DestroyMyItem($index)
  {
    global $currentPlayer;
    DestroyItemForPlayer($currentPlayer, $index);
  }

  function DestroyItemForPlayer($player, $index)
  {
    $items = &GetItems($player);
    for($i=$index+ItemPieces()-1; $i>=$index; --$i)
    {
      unset($items[$i]);
    }
    $items = array_values($items);
  }

?>
