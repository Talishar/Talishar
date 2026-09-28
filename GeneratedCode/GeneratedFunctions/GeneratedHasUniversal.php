<?php
function GeneratedHasUniversal($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"wage_gold_red" => true,
"wage_gold_yellow" => true,
"wage_gold_blue" => true,
default => false
};
}
?>