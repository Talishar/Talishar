<?php
function GeneratedHasSharpen($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"brimming_blade_red" => true,
"cut_n_carve_red" => true,
"cut_n_carve_yellow" => true,
"cut_n_carve_blue" => true,
"drawn_to_the_blade_yellow" => true,
"edict_of_steel_red" => true,
"edict_of_steel_yellow" => true,
"edict_of_steel_blue" => true,
"fresh_from_the_forge_red" => true,
"honed_for_honor_blue" => true,
"indefensibly_honed_blue" => true,
"off_beat_blue" => true,
"reverent_rerebrace" => true,
"sharp_n_shine_red" => true,
"sharp_n_shine_yellow" => true,
"sharp_n_shine_blue" => true,
"sharp_incline_red" => true,
"sharp_incline_yellow" => true,
"sharp_incline_blue" => true,
"swordmasters_path_red" => true,
"swordmasters_path_yellow" => true,
"swordmasters_path_blue" => true,
"visit_the_dawnsmith_blue" => true,
default => false
};
}
?>