<?php
function GeneratedHasLightningFlow($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"crackling_red" => true,
"crackling_yellow" => true,
"harness_lightning_red" => true,
"harness_lightning_yellow" => true,
"photon_rush_red" => true,
"photon_rush_blue" => true,
"static_shock_red" => true,
"static_shock_yellow" => true,
default => false
};
}
?>