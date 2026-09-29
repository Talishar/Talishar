<?php
function GeneratedArcaneShelterAmount($cardID) {
if(is_int($cardID)) return 0;
return match($cardID) {
"sigil_of_conductivity_blue" => 1,
"sigil_of_sanctuary_blue" => 1,
default => 0
};
}
?>