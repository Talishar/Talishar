<?php
function GeneratedQuellAmount($cardID) {
if(is_int($cardID)) return 0;
return match($cardID) {
"conduit_of_frostburn" => 1,
"heat_wave" => 1,
"quelling_robe" => 1,
"quelling_sleeves" => 1,
"quelling_slippers" => 1,
"silken_form" => 1,
"mbrio_base_walkers" => 1,
default => 0
};
}
?>