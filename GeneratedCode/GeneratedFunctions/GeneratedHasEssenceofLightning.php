<?php
function GeneratedHasEssenceofLightning($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"aurora" => true,
"aurora_shooting_star" => true,
"briar" => true,
"briar_warden_of_thorns" => true,
"lexi" => true,
"lexi_livewire" => true,
"oscilio" => true,
"oscilio_constella_intelligence" => true,
default => false
};
}
?>