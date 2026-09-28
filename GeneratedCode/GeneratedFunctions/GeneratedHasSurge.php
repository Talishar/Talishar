<?php
function GeneratedHasSurge($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"aether_quickening_red" => true,
"aether_quickening_yellow" => true,
"aether_quickening_blue" => true,
"destructive_aethertide_blue" => true,
"etchings_of_arcana_red" => true,
"etchings_of_arcana_yellow" => true,
"etchings_of_arcana_blue" => true,
"eternal_inferno_red" => true,
"glyph_overlay_red" => true,
"glyph_overlay_yellow" => true,
"glyph_overlay_blue" => true,
"mind_warp_yellow" => true,
"open_the_flood_gates_red" => true,
"open_the_flood_gates_yellow" => true,
"open_the_flood_gates_blue" => true,
"overflow_the_aetherwell_red" => true,
"overflow_the_aetherwell_yellow" => true,
"overflow_the_aetherwell_blue" => true,
"perennial_aetherbloom_red" => true,
"perennial_aetherbloom_yellow" => true,
"perennial_aetherbloom_blue" => true,
"pop_the_bubble_red" => true,
"pop_the_bubble_yellow" => true,
"pop_the_bubble_blue" => true,
"prognosticate_red" => true,
"prognosticate_yellow" => true,
"prognosticate_blue" => true,
"sap_red" => true,
"sap_yellow" => true,
"sap_blue" => true,
"swell_tidings_red" => true,
"trailblazing_aether_red" => true,
"trailblazing_aether_yellow" => true,
"trailblazing_aether_blue" => true,
default => false
};
}
?>