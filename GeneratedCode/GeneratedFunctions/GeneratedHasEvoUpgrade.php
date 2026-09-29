<?php
function GeneratedHasEvoUpgrade($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"apocalypse_automaton_red" => true,
"blast_rig_red" => true,
"demolition_protocol_red" => true,
"ghost_protocol_architect_red" => true,
"ghost_protocol_mainframe_blue" => true,
"heavy_artillery_red" => true,
"heavy_artillery_yellow" => true,
"heavy_artillery_blue" => true,
"liquid_cooled_mayhem_red" => true,
"liquid_cooled_mayhem_yellow" => true,
"liquid_cooled_mayhem_blue" => true,
"mechanical_strength_red" => true,
"mechanical_strength_yellow" => true,
"mechanical_strength_blue" => true,
"meganetic_protocol_blue" => true,
"pulsewave_protocol_yellow" => true,
"steel_street_enforcement_blue" => true,
default => false
};
}
?>