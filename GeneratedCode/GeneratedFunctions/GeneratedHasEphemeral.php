<?php
function GeneratedHasEphemeral($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"crouching_tiger" => true,
"fang_strike" => true,
"slither" => true,
default => false
};
}
?>