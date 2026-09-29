<?php
function GeneratedHasIceBond($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"frosthaven_sheath_red" => true,
"ice_aged_oak_blue" => true,
"laden_with_frost_red" => true,
default => false
};
}
?>