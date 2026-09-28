<?php
function GeneratedHasReload($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"boltn_shot_red" => true,
"boltn_shot_yellow" => true,
"boltn_shot_blue" => true,
"nock_the_deathwhistle_blue" => true,
"over_flex_red" => true,
"over_flex_yellow" => true,
"over_flex_blue" => true,
"poison_the_tips_yellow" => true,
"rapid_fire_yellow" => true,
"reel_in_blue" => true,
"take_aim_red" => true,
"take_aim_yellow" => true,
"take_aim_blue" => true,
"take_cover_red" => true,
"take_cover_yellow" => true,
"take_cover_blue" => true,
default => false
};
}
?>