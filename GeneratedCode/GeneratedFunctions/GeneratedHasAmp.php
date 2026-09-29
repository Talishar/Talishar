<?php
function GeneratedHasAmp($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"arc_ramp_red" => true,
"arc_ramp_yellow" => true,
"arc_ramp_blue" => true,
"channel_stormgarden_yellow" => true,
"channel_the_millennium_tree_red" => true,
"exploding_aether_red" => true,
"exploding_aether_yellow" => true,
"exploding_aether_blue" => true,
"high_voltage_blue" => true,
"kindle_red" => true,
"shattering_stardust_red" => true,
"sigil_of_aether_blue" => true,
"stardust_spike_red" => true,
"will_of_arcana_blue" => true,
default => false
};
}
?>