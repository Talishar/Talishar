<?php
function GeneratedCharacterIntellect($cardID) {
if(is_int($cardID)) return 0;
return match($cardID) {
"baalghor_omen_of_the_end" => 3,
"data_doll_mkii" => 3,
"lyath_goldmane" => 5,
"lyath_goldmane_vile_savant" => 5,
"teklovossen_the_mechropotent" => 3,
"tuffnut" => 3,
"tuffnut_bumbling_hulkster" => 3,
default => 4
};
}
?>