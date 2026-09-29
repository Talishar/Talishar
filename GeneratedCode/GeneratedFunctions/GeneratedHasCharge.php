<?php
function GeneratedHasCharge($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"beaming_bravado_red" => true,
"beaming_bravado_yellow" => true,
"beaming_bravado_blue" => true,
"beckoning_light_red" => true,
"bolt_of_courage_red" => true,
"bolt_of_courage_yellow" => true,
"bolt_of_courage_blue" => true,
"bracers_of_bellonas_grace" => true,
"bravery_of_the_blade_red" => true,
"cross_the_line_red" => true,
"cross_the_line_yellow" => true,
"cross_the_line_blue" => true,
"engulfing_light_red" => true,
"engulfing_light_yellow" => true,
"engulfing_light_blue" => true,
"express_lightning_red" => true,
"express_lightning_yellow" => true,
"express_lightning_blue" => true,
"glaring_impact_red" => true,
"glaring_impact_yellow" => true,
"glaring_impact_blue" => true,
"helm_of_halos_grace" => true,
"light_the_way_red" => true,
"light_the_way_yellow" => true,
"light_the_way_blue" => true,
"prayer_of_bellona_yellow" => true,
"roaring_beam_yellow" => true,
"saving_grace_yellow" => true,
"soulbond_resolve" => true,
"spirit_of_war_red" => true,
"take_flight_red" => true,
"take_flight_yellow" => true,
"take_flight_blue" => true,
"v_of_the_vanguard_yellow" => true,
"warpath_of_winged_grace" => true,
default => false
};
}
?>