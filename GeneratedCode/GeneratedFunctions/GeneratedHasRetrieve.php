<?php
function GeneratedHasRetrieve($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"pick_up_the_point_red" => true,
"pick_up_the_point_yellow" => true,
"pick_up_the_point_blue" => true,
"up_sticks_and_run_red" => true,
"up_sticks_and_run_yellow" => true,
"up_sticks_and_run_blue" => true,
default => false
};
}
?>