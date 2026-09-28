<?php
function GeneratedHasPerched($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"polly_cranka" => true,
"polly_cranka_ally" => true,
"sticky_fingers" => true,
"sticky_fingers_ally" => true,
default => false
};
}
?>