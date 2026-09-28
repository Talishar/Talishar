<?php
function GeneratedHasTheCrowdCheers($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"cheers_blue" => true,
"comeback_kid_red" => true,
"comeback_kid_yellow" => true,
"comeback_kid_blue" => true,
"cries_of_encore_red" => true,
"fight_from_behind_red" => true,
"fight_from_behind_yellow" => true,
"fight_from_behind_blue" => true,
"good_natured_brutality_yellow" => true,
"helm_of_the_adored" => true,
"heroic_pose_red" => true,
"heroic_pose_yellow" => true,
"heroic_pose_blue" => true,
"jaws_of_victory_red" => true,
"moment_maker" => true,
"numbskull_charm_yellow" => true,
"prime_the_crowd_red" => true,
"prime_the_crowd_yellow" => true,
"prime_the_crowd_blue" => true,
"rapturous_applause_red" => true,
"rapturous_applause_yellow" => true,
"rapturous_applause_blue" => true,
"shining_courage_red" => true,
"superstar_blue" => true,
"thespian_charm_yellow" => true,
"turn_the_crowd_grateful_red" => true,
"turn_the_crowd_grateful_yellow" => true,
"turn_the_crowd_grateful_blue" => true,
"turning_point_blue" => true,
"zane_broadly_beloved" => true,
"start_swinging" => true,
default => false
};
}
?>