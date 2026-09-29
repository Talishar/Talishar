<?php
function GeneratedHasFragment($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"astral_ambience_yellow" => true,
"blink_of_an_eye_red" => true,
"clear_conscience_red" => true,
"clear_conscience_yellow" => true,
"clear_conscience_blue" => true,
"cosmic_duality_red" => true,
"cosmic_duality_yellow" => true,
"cosmic_duality_blue" => true,
"ebbing_arcstride_red" => true,
"ebbing_arcstride_yellow" => true,
"ebbing_arcstride_blue" => true,
"erode_authority_red" => true,
"erode_authority_yellow" => true,
"erode_authority_blue" => true,
"fractal_creation_blue" => true,
"fraying_lifeforce_red" => true,
"polarus_pulse_ray_red" => true,
"polarus_pulse_ray_yellow" => true,
"polarus_pulse_ray_blue" => true,
"pulsing_cardia_red" => true,
"pulsing_cardia_yellow" => true,
"pulsing_cardia_blue" => true,
"scattering_conflux_red" => true,
"shattering_flowtide_red" => true,
"shattering_flowtide_yellow" => true,
"shattering_flowtide_blue" => true,
"shattering_stardust_red" => true,
"unwinding_finality_red" => true,
default => false
};
}
?>