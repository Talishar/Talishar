<?php
function GeneratedHasScrap($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"crash_site_salvage_yellow" => true,
"hydraulic_press_red" => true,
"hydraulic_press_yellow" => true,
"hydraulic_press_blue" => true,
"junkyard_dogg_red" => true,
"junkyard_dogg_yellow" => true,
"junkyard_dogg_blue" => true,
"scrap_compactor_red" => true,
"scrap_compactor_yellow" => true,
"scrap_compactor_blue" => true,
"scrap_harvester_red" => true,
"scrap_harvester_yellow" => true,
"scrap_harvester_blue" => true,
"scrap_hopper_red" => true,
"scrap_hopper_yellow" => true,
"scrap_hopper_blue" => true,
"scrap_prospector_red" => true,
"scrap_prospector_yellow" => true,
"scrap_prospector_blue" => true,
"scrap_trader_red" => true,
"speed_demon_red" => true,
default => false
};
}
?>