<?php
function GeneratedAmpAmount($cardID) {
if(is_int($cardID)) return 0;
return match($cardID) {
"arc_ramp_red" => 3,
"arc_ramp_yellow" => 2,
"arc_ramp_blue" => 1,
"channel_stormgarden_yellow" => 1,
"channel_the_millennium_tree_red" => 3,
"exploding_aether_red" => 3,
"exploding_aether_yellow" => 2,
"exploding_aether_blue" => 1,
"high_voltage_blue" => 1,
"kindle_red" => 1,
"shattering_stardust_red" => 1,
"sigil_of_aether_blue" => 1,
"stardust_spike_red" => 1,
"will_of_arcana_blue" => 1,
default => 0
};
}
?>