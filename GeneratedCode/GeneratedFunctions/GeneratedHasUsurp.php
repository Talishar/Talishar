<?php
function GeneratedHasUsurp($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"bloodfrenzy_gloomblade_red" => true,
"bloodfrenzy_gloomblade_yellow" => true,
"bloodfrenzy_gloomblade_blue" => true,
"bloodsong_gloomblade_red" => true,
"cullingsong_gloomblade_red" => true,
"demonbound_gloomblade_red" => true,
"demonbound_gloomblade_yellow" => true,
"demonbound_gloomblade_blue" => true,
"murmuring_gloomblade_red" => true,
"murmuring_gloomblade_yellow" => true,
"murmuring_gloomblade_blue" => true,
"plundersong_gloomblade_red" => true,
"runic_disposition_red" => true,
"runic_disposition_yellow" => true,
"runic_disposition_blue" => true,
"runic_reaving_red" => true,
"runic_reaving_yellow" => true,
"runic_reaving_blue" => true,
"shadowake_gloomblade_red" => true,
"shadowake_gloomblade_yellow" => true,
"shadowake_gloomblade_blue" => true,
"sinspeaker_gloomblade_red" => true,
"vexing_gloomblade_red" => true,
"vexing_gloomblade_yellow" => true,
"vexing_gloomblade_blue" => true,
default => false
};
}
?>