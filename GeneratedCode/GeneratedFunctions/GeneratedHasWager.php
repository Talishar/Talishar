<?php
function GeneratedHasWager($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"bet_big_red" => true,
"big_blinder_red" => true,
"big_blinder_yellow" => true,
"big_blinder_blue" => true,
"drink_em_under_the_table_red" => true,
"ez_sqeez_bookie_syndicate" => true,
"gutshot_red" => true,
"gutshot_yellow" => true,
"gutshot_blue" => true,
"hold_em_yellow" => true,
"hold_em_blue" => true,
"odds_on_favorite_blue" => true,
"pile_driver" => true,
"prized_galea" => true,
"showdown_red" => true,
"showdown_yellow" => true,
"showdown_blue" => true,
"up_the_ante_blue" => true,
"wage_agility_red" => true,
"wage_agility_yellow" => true,
"wage_agility_blue" => true,
"wage_gold_red" => true,
"wage_gold_yellow" => true,
"wage_gold_blue" => true,
"wage_might_red" => true,
"wage_might_yellow" => true,
"wage_might_blue" => true,
"wage_vigor_red" => true,
"wage_vigor_yellow" => true,
"wage_vigor_blue" => true,
"reverse_psychology" => true,
default => false
};
}
?>