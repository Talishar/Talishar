<?php
function GeneratedHasTheCrowdBoos($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"big_bully_red" => true,
"booze_blue" => true,
"cheaters_charm_yellow" => true,
"clench_the_upper_hand_red" => true,
"clench_the_upper_hand_yellow" => true,
"clench_the_upper_hand_blue" => true,
"concealed_object_blue" => true,
"goon_beatdown_blue" => true,
"horns_of_the_despised" => true,
"killjoy_the_crooked_blade" => true,
"liars_charm_yellow" => true,
"lionclaw_maul" => true,
"mocking_blow_red" => true,
"mocking_blow_yellow" => true,
"mocking_blow_blue" => true,
"overturn_the_results_blue" => true,
"prime_the_crowd_red" => true,
"prime_the_crowd_yellow" => true,
"prime_the_crowd_blue" => true,
"turn_the_crowd_hateful_red" => true,
"turn_the_crowd_hateful_yellow" => true,
"turn_the_crowd_hateful_blue" => true,
"villainous_pose_red" => true,
"villainous_pose_yellow" => true,
"villainous_pose_blue" => true,
default => false
};
}
?>