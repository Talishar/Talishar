<?php
function GeneratedHasAmbush($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"echoing_trap_blue" => true,
"no_hero_stands_alone_yellow" => true,
"overcrowded_blue" => true,
"stadium_security_red" => true,
"stadium_security_yellow" => true,
"stadium_security_blue" => true,
"tiger_eye_reflex_yellow" => true,
"tiger_eye_reflex_blue" => true,
default => false
};
}
?>