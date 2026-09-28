<?php
function GeneratedHasWateryGrave($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"amethyst_amulet_blue" => true,
"anka_drag_under_yellow" => true,
"barnacle_yellow" => true,
"beneath_the_surface_yellow" => true,
"boo_resident_spook_yellow" => true,
"bubba_lubba_run_aground_yellow" => true,
"chowder_hearty_cook_yellow" => true,
"chum_friendly_first_mate_yellow" => true,
"cutty_shark_quick_clip_yellow" => true,
"diamond_amulet_blue" => true,
"gallow_end_of_the_line_yellow" => true,
"golden_skull_yellow" => true,
"kelpie_tangled_mess_yellow" => true,
"limpit_hop_a_long_yellow" => true,
"moray_le_fay_yellow" => true,
"onyx_amulet_blue" => true,
"opal_amulet_blue" => true,
"oysten_heart_of_gold_yellow" => true,
"pearl_amulet_blue" => true,
"platinum_amulet_blue" => true,
"pounamu_amulet_blue" => true,
"riggermortis_yellow" => true,
"ruby_amulet_blue" => true,
"sapphire_amulet_blue" => true,
"sawbones_dock_hand_yellow" => true,
"scooba_salty_sea_dog_yellow" => true,
"shelly_hardened_traveler_yellow" => true,
"swabbie_yellow" => true,
"wailer_humperdinck_yellow" => true,
default => false
};
}
?>