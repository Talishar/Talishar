<?php
function GeneratedSpellvoidAmount($cardID) {
if(is_int($cardID)) return 0;
return match($cardID) {
"boo_resident_spook_yellow" => 2,
"claw_of_vynserakai" => 1,
"dream_weavers" => 1,
"ebon_fold" => 2,
"graven_gaslight" => 1,
"halo_of_illumination" => 2,
"halo_of_lumina_light" => 2,
"helm_of_might_and_magic" => 1,
"shock_charmers" => 2,
"skera_strapping" => 3,
"spell_fray_cloak" => 1,
"spell_fray_gloves" => 1,
"spell_fray_leggings" => 1,
"spell_fray_tiara" => 1,
"spellbane_aegis" => 1,
"talisman_of_dousing_yellow" => 1,
"third_eye_of_the_sphinx" => 1,
"volcanic_vice" => 3,
"widow_back_abdomen" => 1,
"widow_claw_tarsus" => 1,
"widow_veil_respirator" => 1,
"widow_web_crawler" => 1,
default => 0
};
}
?>