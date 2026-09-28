<?php
function GeneratedHasGuardwell($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"arcanite_fortress" => true,
"balance_of_justice" => true,
"beckoning_haunt" => true,
"blade_beckoner_boots" => true,
"blade_beckoner_gauntlets" => true,
"blade_beckoner_helm" => true,
"blade_beckoner_plating" => true,
"fortitude_of_anvilheim" => true,
"glory_plate" => true,
"magmatic_carapace" => true,
"predatory_plating" => true,
"prizeworn_gauntlet" => true,
"prizeworn_plating" => true,
"smoldering_scales" => true,
"starfield_veil" => true,
"testament_of_valahai" => true,
"tiara_of_suspense" => true,
"mbrio_base_cortex" => true,
default => false
};
}
?>