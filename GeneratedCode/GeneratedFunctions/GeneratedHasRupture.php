<?php
function GeneratedHasRupture($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"breaking_point_red" => true,
"lava_burst_red" => true,
"liquefy_red" => true,
"red_hot_red" => true,
"rise_up_red" => true,
"searing_touch_red" => true,
default => false
};
}
?>