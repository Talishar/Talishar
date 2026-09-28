<?php
function GeneratedHasNegate($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"aetherize_blue" => true,
"construct_nitro_mechanoid_yellow" => true,
"fabric_of_blossoms_blue" => true,
"fabric_of_hope_red" => true,
"fabric_of_providence_red" => true,
"fabric_of_scales_blue" => true,
"fabric_of_spring_yellow" => true,
"null__shock_yellow" => true,
"rewind_blue" => true,
"temporal_wobble_red" => true,
"venomback_fabric_yellow" => true,
default => false
};
}
?>