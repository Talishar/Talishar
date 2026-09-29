<?php
function GeneratedHasHeavy($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"summit_the_unforgiving" => true,
default => false
};
}
?>