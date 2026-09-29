<?php
function GeneratedHasUnfreeze($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
default => false
};
}
?>