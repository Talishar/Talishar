<?php
function GeneratedHeaveAmount($cardID) {
if(is_int($cardID)) return 0;
return match($cardID) {
"blinding_of_the_old_ones_red" => 2,
"disenchantment_of_the_old_ones_red" => 2,
"overswing_red" => 2,
"overswing_yellow" => 2,
"overswing_blue" => 2,
"pulverize_red" => 3,
"rubble_raiser_red" => 2,
"rubble_raiser_yellow" => 2,
"rubble_raiser_blue" => 2,
"smelting_of_the_old_ones_red" => 2,
"thunder_quake_red" => 3,
"thunder_quake_yellow" => 3,
"thunder_quake_blue" => 3,
default => 0
};
}
?>