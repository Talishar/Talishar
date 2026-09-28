<?php
function GeneratedHasProtect($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"bastion_of_duty" => true,
"chivalry_blue" => true,
"gesture_of_goodwill_blue" => true,
default => false
};
}
?>