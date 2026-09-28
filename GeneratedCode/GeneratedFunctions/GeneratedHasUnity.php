<?php
function GeneratedHasUnity($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"alluring_inducement_yellow" => true,
"anthem_of_spring_blue" => true,
"bastion_of_unity" => true,
"call_down_the_lightning_yellow" => true,
"chorus_of_ironsong_yellow" => true,
"gauntlets_of_unity" => true,
"helm_of_unity" => true,
"northern_winds_blue" => true,
"pillar_of_unity" => true,
"plating_of_unity" => true,
"star_struck_yellow" => true,
"united_we_stand_yellow" => true,
default => false
};
}
?>