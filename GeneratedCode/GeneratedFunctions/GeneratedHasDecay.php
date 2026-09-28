<?php
function GeneratedHasDecay($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"restless_cleric_red" => true,
"restless_commander_red" => true,
"restless_corporal_red" => true,
"restless_looter_red" => true,
"restless_magister_red" => true,
"restless_outlaw_red" => true,
"restless_plowman_red" => true,
"restless_quartermaster_red" => true,
"restless_shieldmaiden_red" => true,
"restless_steed_red" => true,
"restless_templar_red" => true,
default => false
};
}
?>