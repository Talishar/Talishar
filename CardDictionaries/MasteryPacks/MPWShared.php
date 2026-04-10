<?php

function PayGoldInstead($player, $cost) {
  if (CountItemByName("Gold", $player) > 0) {
			AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_a_" . CardLink("gold", "gold"), 1);
      AddDecisionQueue("NOPASS", $player, "-", 1);
      $goldIndices = GetGoldIndices($player);
      if (str_contains($goldIndices, "MYCHAR")) {
        AddDecisionQueue("PASSPARAMETER", $player, $goldIndices, 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("MZDESTROY", $player, "-", 1);
      } else AddDecisionQueue("FINDANDDESTROYITEM", $player, "gold-1", 1);
      AddDecisionQueue("ELSE", $player, "-");
			AddDecisionQueue("PASSPARAMETER", $player, $cost, 1);
			AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
		}
		else {
			AddDecisionQueue("PASSPARAMETER", $player, $cost);
			AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
		}
}