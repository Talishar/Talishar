<?php
function GeneratedHasLightningFusion($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"arcanic_shockwave_red" => true,
"arcanic_shockwave_yellow" => true,
"arcanic_shockwave_blue" => true,
"blossoming_spellblade_red" => true,
"buzz_bolt_red" => true,
"buzz_bolt_yellow" => true,
"buzz_bolt_blue" => true,
"dazzling_crescendo_red" => true,
"dazzling_crescendo_yellow" => true,
"dazzling_crescendo_blue" => true,
"entwine_lightning_red" => true,
"entwine_lightning_yellow" => true,
"entwine_lightning_blue" => true,
"flashfreeze_red" => true,
"flicker_wisp_yellow" => true,
"frazzle_red" => true,
"frazzle_yellow" => true,
"frazzle_blue" => true,
"fulminate_yellow" => true,
"ice_storm_red" => true,
"inspire_lightning_red" => true,
"inspire_lightning_yellow" => true,
"inspire_lightning_blue" => true,
"light_it_up_yellow" => true,
"rites_of_lightning_red" => true,
"rites_of_lightning_yellow" => true,
"rites_of_lightning_blue" => true,
"snap_shot_red" => true,
"snap_shot_yellow" => true,
"snap_shot_blue" => true,
"vela_flash_red" => true,
"vela_flash_yellow" => true,
"vela_flash_blue" => true,
default => false
};
}
?>