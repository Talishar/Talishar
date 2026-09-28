<?php
function GeneratedHasCrank($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"assembly_module_blue" => true,
"autosave_script_blue" => true,
"backup_protocol_blu_blue" => true,
"backup_protocol_red_red" => true,
"backup_protocol_yel_yellow" => true,
"boom_grenade_red" => true,
"boom_grenade_yellow" => true,
"boom_grenade_blue" => true,
"cerebellum_processor_blue" => true,
"clamp_press_blue" => true,
"copper_cog_blue" => true,
"dissolving_shield_red" => true,
"dissolving_shield_yellow" => true,
"dissolving_shield_blue" => true,
"golden_cog" => true,
"grinding_gears_blue" => true,
"hadron_collider_red" => true,
"hadron_collider_yellow" => true,
"hadron_collider_blue" => true,
"mhz_script_yellow" => true,
"mini_forcefield_red" => true,
"mini_forcefield_yellow" => true,
"mini_forcefield_blue" => true,
"null_time_zone_blue" => true,
"overload_script_red" => true,
"penetration_script_yellow" => true,
"polarity_reversal_script_red" => true,
"polly_cranka" => true,
"polly_cranka_ally" => true,
"prismatic_lens_yellow" => true,
"quantum_processor_yellow" => true,
"security_script_blue" => true,
"tick_tock_clock_red" => true,
default => false
};
}
?>