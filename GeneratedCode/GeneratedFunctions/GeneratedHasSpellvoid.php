<?php
function GeneratedHasSpellvoid($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"boo_resident_spook_yellow" => true,
"claw_of_vynserakai" => true,
"dream_weavers" => true,
"ebon_fold" => true,
"graven_gaslight" => true,
"halo_of_illumination" => true,
"halo_of_lumina_light" => true,
"helm_of_might_and_magic" => true,
"shock_charmers" => true,
"skera_strapping" => true,
"spell_fray_cloak" => true,
"spell_fray_gloves" => true,
"spell_fray_leggings" => true,
"spell_fray_tiara" => true,
"spellbane_aegis" => true,
"talisman_of_dousing_yellow" => true,
"third_eye_of_the_sphinx" => true,
"volcanic_vice" => true,
"widow_back_abdomen" => true,
"widow_claw_tarsus" => true,
"widow_veil_respirator" => true,
"widow_web_crawler" => true,
default => false
};
}
?>