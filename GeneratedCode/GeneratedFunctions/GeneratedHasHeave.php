<?php
function GeneratedHasHeave($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"blinding_of_the_old_ones_red" => true,
"disenchantment_of_the_old_ones_red" => true,
"overswing_red" => true,
"overswing_yellow" => true,
"overswing_blue" => true,
"pulverize_red" => true,
"rubble_raiser_red" => true,
"rubble_raiser_yellow" => true,
"rubble_raiser_blue" => true,
"smelting_of_the_old_ones_red" => true,
"thunder_quake_red" => true,
"thunder_quake_yellow" => true,
"thunder_quake_blue" => true,
default => false
};
}
?>