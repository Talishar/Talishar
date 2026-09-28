<?php
function GeneratedHasFreeze($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"channel_galcias_cradle_blue" => true,
"channel_iceloch_glaze_blue" => true,
"crown_of_frozen_thoughts" => true,
"northern_winds_blue" => true,
"put_on_ice_red" => true,
"put_on_ice_yellow" => true,
"put_on_ice_blue" => true,
default => false
};
}
?>