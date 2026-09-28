<?php
function GeneratedHasSpectra($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"arc_light_sentinel_yellow" => true,
"genesis_yellow" => true,
"haze_bending_blue" => true,
"merciful_retribution_yellow" => true,
"ode_to_wrath_yellow" => true,
"parable_of_humility_yellow" => true,
"passing_mirage_blue" => true,
"pierce_reality_blue" => true,
"shimmers_of_silver_blue" => true,
default => false
};
}
?>