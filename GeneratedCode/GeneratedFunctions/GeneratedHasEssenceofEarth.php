<?php
function GeneratedHasEssenceofEarth($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"bravo_star_of_the_show" => true,
"briar" => true,
"briar_warden_of_thorns" => true,
"florian" => true,
"florian_rotwood_harbinger" => true,
"jarl_vetreidi" => true,
"oldhim" => true,
"oldhim_grandfather_of_eternity" => true,
"terra" => true,
"verdance" => true,
"verdance_thorn_of_the_rose" => true,
default => false
};
}
?>