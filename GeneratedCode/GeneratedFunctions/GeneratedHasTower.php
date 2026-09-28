<?php
function GeneratedHasTower($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"colossal_bearing_red" => true,
"cut_off_at_the_knees_yellow" => true,
"cut_a_long_story_short_yellow" => true,
"cut_the_small_talk_yellow" => true,
"lay_down_the_law_red" => true,
"no_tall_tales_yellow" => true,
"smack_of_reality_red" => true,
default => false
};
}
?>