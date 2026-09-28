<?php
function GeneratedHasPiercing($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"drill_shot_red" => true,
"drill_shot_yellow" => true,
"drill_shot_blue" => true,
"graven_call" => true,
"humour_plunge" => true,
"hunters_klaive" => true,
"hunters_klaive_r" => true,
"nerve_scalpel" => true,
"nerve_scalpel_r" => true,
"orbitoclast" => true,
"orbitoclast_r" => true,
"scale_peeler" => true,
"scale_peeler_r" => true,
"spiders_bite" => true,
default => false
};
}
?>