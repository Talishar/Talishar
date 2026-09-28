<?php
function GeneratedHasStarfall($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"astral_bridge_red" => true,
"comet_collision_red" => true,
"comet_collision_yellow" => true,
"comet_collision_blue" => true,
"constella_contemplation_yellow" => true,
"constella_flowslide_yellow" => true,
"constella_uplift_yellow" => true,
"cosmic_suture_red" => true,
"cosmic_suture_yellow" => true,
"cosmic_suture_blue" => true,
"lightning_overload_red" => true,
"lightning_overload_yellow" => true,
"lightning_overload_blue" => true,
"meteoric_impact_red" => true,
"meteoric_impact_yellow" => true,
"meteoric_impact_blue" => true,
default => false
};
}
?>