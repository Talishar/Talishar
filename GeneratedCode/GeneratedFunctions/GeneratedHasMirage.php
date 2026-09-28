<?php
function GeneratedHasMirage($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"flicker_trick_red" => true,
"lunar_mirage_red" => true,
"mind_meets_might_red" => true,
"power_of_make_believe_red" => true,
"power_of_make_believe_yellow" => true,
"power_of_make_believe_blue" => true,
"shimmering_mirage_blue" => true,
"shimmering_specter_red" => true,
"shimmering_specter_yellow" => true,
"shimmering_specter_blue" => true,
default => false
};
}
?>