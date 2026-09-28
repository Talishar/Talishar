<?php
function GeneratedHasModular($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"adaptive_alpha_mold" => true,
"adaptive_dissolver" => true,
"adaptive_plating" => true,
default => false
};
}
?>