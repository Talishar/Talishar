<?php
function GeneratedHasReprise($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"biting_blade_red" => true,
"biting_blade_yellow" => true,
"biting_blade_blue" => true,
"glint_the_quicksilver_blue" => true,
"ironsong_response_red" => true,
"ironsong_response_yellow" => true,
"ironsong_response_blue" => true,
"out_for_blood_red" => true,
"out_for_blood_yellow" => true,
"out_for_blood_blue" => true,
"overpower_red" => true,
"overpower_yellow" => true,
"overpower_blue" => true,
"rout_red" => true,
"singing_steelblade_yellow" => true,
"stroke_of_foresight_red" => true,
"stroke_of_foresight_yellow" => true,
"stroke_of_foresight_blue" => true,
"unified_decree_yellow" => true,
default => false
};
}
?>