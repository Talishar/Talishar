<?php
function GeneratedIsAndOrFuse($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"exposed_to_the_elements_blue" => true,
"flashfreeze_red" => true,
"fulminate_yellow" => true,
default => false
};
}
?>