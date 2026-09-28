<?php
function GeneratedHasAwaken($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"angelic_attendant_yellow" => true,
default => false
};
}
?>