<?php
function GeneratedHasEssenceofIce($cardID) {
if(is_int($cardID)) return false;
return match($cardID) {
"iyslander" => true,
"iyslander_stormbind" => true,
"jarl_vetreidi" => true,
"lexi" => true,
"lexi_livewire" => true,
"oldhim" => true,
"oldhim_grandfather_of_eternity" => true,
default => false
};
}
?>