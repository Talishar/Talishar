<?php
function GeneratedHasMeld($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"arcane_seeds__life_red" => true,
"burn_up__shock_red" => true,
"comet_storm__shock_red" => true,
"consign_to_cosmos__shock_yellow" => true,
"everbloom__life_blue" => true,
"null__shock_yellow" => true,
"pulsing_aether__life_red" => true,
"rampant_growth__life_yellow" => true,
"regrowth__shock_blue" => true,
"thistle_bloom__life_yellow" => true,
"vaporize__shock_yellow" => true,
default => false
};
}
?>