<?php
function GeneratedHasIncarnate($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"corrupted_corpse" => true,
default => false
};
}
?>