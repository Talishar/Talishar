<?php
function GeneratedHasGoFish($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"blue_fin_harpoon_blue" => true,
"king_kraken_harpoon_red" => true,
"king_shark_harpoon_red" => true,
"red_fin_harpoon_blue" => true,
"yellow_fin_harpoon_blue" => true,
default => false
};
}
?>