<?php
function GeneratedHasPairs($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"gavel_of_natural_order" => true,
default => false
};
}
?>