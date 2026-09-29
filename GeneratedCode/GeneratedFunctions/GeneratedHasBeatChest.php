<?php
function GeneratedHasBeatChest($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"assault_and_battery_red" => true,
"assault_and_battery_yellow" => true,
"assault_and_battery_blue" => true,
"bare_destruction_red" => true,
"bare_swing_red" => true,
"bare_swing_yellow" => true,
"bonebreaker_bellow_red" => true,
"bonebreaker_bellow_yellow" => true,
"bonebreaker_bellow_blue" => true,
"pound_town_red" => true,
"pound_town_yellow" => true,
"pound_town_blue" => true,
"rawhide_rumble_red" => true,
"rawhide_rumble_yellow" => true,
"rawhide_rumble_blue" => true,
"smell_fear_yellow" => true,
"smell_fear_blue" => true,
default => false
};
}
?>