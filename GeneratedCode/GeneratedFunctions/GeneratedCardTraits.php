<?php
function GeneratedCardTraits($cardID) {
if(is_int($cardID)) return "";
return match($cardID) {
"arakni_black_widow" => "Agent of Chaos",
"arakni_funnel_web" => "Agent of Chaos",
"arakni_orb_weaver" => "Agent of Chaos",
"arakni_redback" => "Agent of Chaos",
"arakni_tarantula" => "Agent of Chaos",
"arakni_trap_door" => "Agent of Chaos",
default => ""
};
}
?>