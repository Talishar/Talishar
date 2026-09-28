<?php
function GeneratedHasLightningBond($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"arc_bending_red" => true,
"laden_with_lightning_red" => true,
"stormwind_sheath_red" => true,
"voltic_veil_red" => true,
default => false
};
}
?>