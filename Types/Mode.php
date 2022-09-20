<?php

enum Mode : int
{
  case SubstractHealth       = 0;
  case AddHealth             = 1;
  case PlayCardFromHand      = 2;
  case PlayEquipementAbility = 3;
  case AddToArsenal          = 4;
  case PlayFromArsenal       = 5;
  case PitchDeck             = 6;
  case InputNumber           = 7;
  case Opt                   = 8;
  case ChooseTopOrBottom     = 9;
  case UseItemAbility        = 10;
  // ...
}
