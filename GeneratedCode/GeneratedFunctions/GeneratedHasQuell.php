<?php
function GeneratedHasQuell($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"conduit_of_frostburn" => true,
"heat_wave" => true,
"quelling_robe" => true,
"quelling_sleeves" => true,
"quelling_slippers" => true,
"silken_form" => true,
"mbrio_base_walkers" => true,
default => false
};
}
?>