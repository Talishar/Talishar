<?php
function GeneratedHasHighTide($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"battalion_barque_red" => true,
"battalion_barque_yellow" => true,
"battalion_barque_blue" => true,
"conqueror_of_the_high_seas_red" => true,
"gloves_of_azure_waves" => true,
"hms_barracuda_yellow" => true,
"hms_kraken_yellow" => true,
"hms_marlin_yellow" => true,
"swiftwater_sloop_red" => true,
"swiftwater_sloop_yellow" => true,
"swiftwater_sloop_blue" => true,
default => false
};
}
?>