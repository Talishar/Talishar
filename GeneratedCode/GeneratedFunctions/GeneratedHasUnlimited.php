<?php
function GeneratedHasUnlimited($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"copper_cog_blue" => true,
"figment_of_hope_yellow" => true,
default => false
};
}
?>