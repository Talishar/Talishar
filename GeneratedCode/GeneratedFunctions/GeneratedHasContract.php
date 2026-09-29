<?php
function GeneratedHasContract($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"already_dead_red" => true,
"annihilate_the_armed_red" => true,
"annihilate_the_armed_yellow" => true,
"annihilate_the_armed_blue" => true,
"defang_the_dragon_red" => true,
"eradicate_yellow" => true,
"excessive_bloodloss_red" => true,
"excessive_bloodloss_yellow" => true,
"excessive_bloodloss_blue" => true,
"extinguish_the_flames_red" => true,
"fleece_the_frail_red" => true,
"fleece_the_frail_yellow" => true,
"fleece_the_frail_blue" => true,
"hunter_or_hunted_blue" => true,
"leave_no_witnesses_red" => true,
"mist_hunter_red" => true,
"mutually_assured_destruction_red" => true,
"nix_the_nimble_red" => true,
"nix_the_nimble_yellow" => true,
"nix_the_nimble_blue" => true,
"plunder_the_poor_red" => true,
"plunder_the_poor_yellow" => true,
"plunder_the_poor_blue" => true,
"rob_the_rich_red" => true,
"rob_the_rich_yellow" => true,
"rob_the_rich_blue" => true,
"sack_the_shifty_red" => true,
"sack_the_shifty_yellow" => true,
"sack_the_shifty_blue" => true,
"slay_the_scholars_red" => true,
"slay_the_scholars_yellow" => true,
"slay_the_scholars_blue" => true,
"surgical_extraction_blue" => true,
default => false
};
}
?>