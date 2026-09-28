<?php
function GeneratedHasCloaked($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"aqua_laps" => true,
"aqua_seeing_shell" => true,
"concealed_nerve_gas" => true,
"concealed_pathogen" => true,
"concealed_sedative" => true,
"heirloom_of_rabbit_hide" => true,
"heirloom_of_snake_hide" => true,
"heirloom_of_tiger_hide" => true,
"kimono_of_layered_lessons" => true,
"koi_blessed_kimono" => true,
"rippling_wave" => true,
"skybody_keikoi" => true,
"skycrest_keikoi" => true,
"skyhold_keikoi" => true,
"skywalker_keikoi" => true,
"truths_retold" => true,
"uphold_tradition" => true,
"waves_of_aqua_marine" => true,
default => false
};
}
?>