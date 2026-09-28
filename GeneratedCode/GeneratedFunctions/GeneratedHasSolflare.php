<?php
function GeneratedHasSolflare($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"banneret_of_courage_yellow" => true,
"banneret_of_gallantry_yellow" => true,
"banneret_of_protection_yellow" => true,
"banneret_of_resilience_yellow" => true,
"banneret_of_salvation_yellow" => true,
"banneret_of_swordsmanship_yellow" => true,
"banneret_of_vigor_yellow" => true,
default => false
};
}
?>