<?php
function GeneratedHasTranscend($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"a_drop_in_the_ocean_blue" => true,
"homage_to_ancestors_blue" => true,
"mistcloak_gully" => true,
"pass_over_blue" => true,
"path_well_traveled_blue" => true,
"preserve_tradition_blue" => true,
"rising_sun_setting_moon_blue" => true,
"sacred_art_immortal_lunar_shrine_blue" => true,
"sacred_art_jade_tiger_domain_blue" => true,
"sacred_art_undercurrent_desires_blue" => true,
"stir_the_pot_blue" => true,
"the_grain_that_tips_the_scale_blue" => true,
default => false
};
}
?>