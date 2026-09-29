<?php
function GeneratedHasQuickstrike($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"dashing_flashfoot_yellow" => true,
"destructive_fleetfoot_red" => true,
"destructive_fleetfoot_yellow" => true,
"destructive_fleetfoot_blue" => true,
"electryn_mindmeld_yellow" => true,
"prophetic_quickstep_yellow" => true,
"rush_of_power_red" => true,
"rush_of_power_yellow" => true,
"rush_of_power_blue" => true,
"singeing_flowstride_red" => true,
"singeing_flowstride_yellow" => true,
"singeing_flowstride_blue" => true,
"stunning_swipe_red" => true,
"stunning_swipe_yellow" => true,
"stunning_swipe_blue" => true,
"tempestuous_kiss_red" => true,
default => false
};
}
?>