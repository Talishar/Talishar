<?php
function GeneratedHasSteal($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"cheaters_charm_yellow" => true,
"cutpurse_rapier" => true,
"gang_robbery_yellow" => true,
"jack_be_nimble_red" => true,
"jack_be_quick_red" => true,
"jolly_bludger_yellow" => true,
"liars_charm_yellow" => true,
"mutiny_on_the_battalion_barque_blue" => true,
"mutiny_on_the_nimbus_sovereign_blue" => true,
"mutiny_on_the_swiftwater_blue" => true,
"steal_victory_blue" => true,
"sticky_fingers" => true,
"sticky_fingers_ally" => true,
"tempt_over_yellow" => true,
"undercover_acquisition_red" => true,
default => false
};
}
?>