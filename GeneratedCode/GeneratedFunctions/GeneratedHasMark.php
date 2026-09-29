<?php
function GeneratedHasMark($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"cut_from_the_same_cloth_red" => true,
"cut_from_the_same_cloth_yellow" => true,
"cut_from_the_same_cloth_blue" => true,
"exposed_blue" => true,
"fresh_from_the_forge_red" => true,
"hot_on_their_heels_red" => true,
"hunt_the_hunter_red" => true,
"hunt_to_the_ends_of_rathe_red" => true,
"hunters_klaive" => true,
"hunters_klaive_r" => true,
"lair_of_the_spider_red" => true,
"mark_of_the_huntsman" => true,
"mark_of_the_huntsman_r" => true,
"mark_the_prey_red" => true,
"mark_the_prey_yellow" => true,
"mark_the_prey_blue" => true,
"mark_with_magma_red" => true,
"marked" => true,
"proclaim_vengeance_red" => true,
"public_bounty_red" => true,
"public_bounty_yellow" => true,
"public_bounty_blue" => true,
"pursue_to_the_edge_of_oblivion_red" => true,
"pursue_to_the_pits_of_despair_red" => true,
"relentless_pursuit_blue" => true,
"smoke_out_red" => true,
"tag_the_target_red" => true,
"tag_the_target_yellow" => true,
"tag_the_target_blue" => true,
"trap_and_release_red" => true,
"trap_and_release_yellow" => true,
"trap_and_release_blue" => true,
default => false
};
}
?>