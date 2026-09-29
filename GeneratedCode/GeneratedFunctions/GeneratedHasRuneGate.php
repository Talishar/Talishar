<?php
function GeneratedHasRuneGate($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"deathly_delight_red" => true,
"deathly_delight_yellow" => true,
"deathly_delight_blue" => true,
"deathly_wail_red" => true,
"deathly_wail_yellow" => true,
"deathly_wail_blue" => true,
"deep_recesses_of_existence_blue" => true,
"eloquent_eulogy_red" => true,
"rift_skitter_red" => true,
"rift_skitter_yellow" => true,
"rift_skitter_blue" => true,
"vantom_banshee_red" => true,
"vantom_banshee_yellow" => true,
"vantom_banshee_blue" => true,
"vantom_wraith_red" => true,
"vantom_wraith_yellow" => true,
"vantom_wraith_blue" => true,
"widespread_annihilation_blue" => true,
"widespread_destruction_yellow" => true,
"widespread_ruin_red" => true,
default => false
};
}
?>