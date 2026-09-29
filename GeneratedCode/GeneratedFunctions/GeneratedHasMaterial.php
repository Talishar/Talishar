<?php
function GeneratedHasMaterial($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"ash" => true,
"dust_from_stillwater_shrine_red" => true,
"dust_from_the_chrome_caverns_red" => true,
"dust_from_the_fertile_fields_red" => true,
"dust_from_the_golden_plains_red" => true,
"dust_from_the_red_desert_red" => true,
"dust_from_the_shadow_crypts_red" => true,
"galvanic_bender" => true,
default => false
};
}
?>