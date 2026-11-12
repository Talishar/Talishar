<?php
function GeneratedCardHeroMetatype($cardID) {
if(is_int($cardID)) return "";
return match($cardID) {
"polly_cranka" => "Puffin",
"polly_cranka_ally" => "Puffin",
"sticky_fingers" => "Scurv",
"sticky_fingers_ally" => "Scurv",
"the_hand_that_pulls_the_strings" => "Arakni",
default => ""
};
}
?>