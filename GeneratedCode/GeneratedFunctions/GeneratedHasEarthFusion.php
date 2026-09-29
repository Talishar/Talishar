<?php
function GeneratedHasEarthFusion($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"awakening_blue" => true,
"bramble_spark_red" => true,
"bramble_spark_yellow" => true,
"bramble_spark_blue" => true,
"entangle_red" => true,
"entangle_yellow" => true,
"entangle_blue" => true,
"entwine_earth_red" => true,
"entwine_earth_yellow" => true,
"entwine_earth_blue" => true,
"explosive_growth_red" => true,
"explosive_growth_yellow" => true,
"explosive_growth_blue" => true,
"force_of_nature_blue" => true,
"mulch_red" => true,
"mulch_yellow" => true,
"mulch_blue" => true,
"rites_of_replenishment_red" => true,
"rites_of_replenishment_yellow" => true,
"rites_of_replenishment_blue" => true,
"stir_the_wildwood_red" => true,
"stir_the_wildwood_yellow" => true,
"stir_the_wildwood_blue" => true,
"strength_of_sequoia_red" => true,
"strength_of_sequoia_yellow" => true,
"strength_of_sequoia_blue" => true,
"turn_timber_red" => true,
"turn_timber_yellow" => true,
"turn_timber_blue" => true,
default => false
};
}
?>