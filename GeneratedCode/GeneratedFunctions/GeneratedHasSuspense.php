<?php
function GeneratedHasSuspense($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"act_of_glory_red" => true,
"act_of_glory_yellow" => true,
"act_of_glory_blue" => true,
"dramatic_pause_red" => true,
"dramatic_pause_yellow" => true,
"dramatic_pause_blue" => true,
"edge_of_their_seats_red" => true,
"edge_of_their_seats_yellow" => true,
"edge_of_their_seats_blue" => true,
"head_banging_chorus_yellow" => true,
"hungry_for_more_red" => true,
"in_the_palm_of_your_hand_red" => true,
"leave_them_hanging_red" => true,
"superstar_blue" => true,
"tension_in_the_air_red" => true,
"tension_in_the_air_yellow" => true,
"tension_in_the_air_blue" => true,
"the_suspense_is_killing_me_blue" => true,
"to_be_continued_blue" => true,
"turn_heads_blue" => true,
"up_on_a_pedestal_blue" => true,
"what_happens_next_blue" => true,
"who_blinks_first_blue" => true,
default => false
};
}
?>