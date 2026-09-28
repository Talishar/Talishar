<?php
function GeneratedHasDecompose($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"blossoming_decay_red" => true,
"blossoming_decay_yellow" => true,
"blossoming_decay_blue" => true,
"cadaverous_tilling_red" => true,
"cadaverous_tilling_yellow" => true,
"cadaverous_tilling_blue" => true,
"chorus_of_rotwood_red" => true,
"felling_of_the_crown_red" => true,
"plow_under_yellow" => true,
"rootbound_carapace_red" => true,
"rootbound_carapace_yellow" => true,
"rootbound_carapace_blue" => true,
"sowing_thorns_red" => true,
"summers_fall_red" => true,
"summers_fall_yellow" => true,
"summers_fall_blue" => true,
default => false
};
}
?>