<?php
function GeneratedHasArcaneShelter($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"sigil_of_conductivity_blue" => true,
"sigil_of_sanctuary_blue" => true,
default => false
};
}
?>