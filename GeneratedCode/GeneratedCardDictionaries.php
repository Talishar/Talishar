<?php
function GeneratedCardType($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return "C";
case "5":
return "C";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "2":
return "C";
case "1":
return "C";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "7":
return "C";
case "6":
return "C";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
}

function GeneratedAttackValue($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
default: return 0;
}
}

function GeneratedBlockValue($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "2":
return -1;
case "1":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "7":
return -1;
case "6":
return -1;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
}

function GeneratedCardName($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return "Enigma";
case "5":
return "Enigma, Ledger of Ancestry";
default: return "";
}
case "0":
switch($cardID[5]) {
case "2":
return "Nuu";
case "1":
return "Nuu, Alluring Desire";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Zen";
case "6":
return "Zen, Tamer of Purpose";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
}

function GeneratedPitchValue($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return 0;
case "5":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "2":
return 0;
case "1":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "7":
return 0;
case "6":
return 0;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
}

function GeneratedCardCost($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "2":
return -1;
case "1":
return -1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return -1;
case "6":
return -1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
}

function GeneratedCardSubtype($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return "Mystic,Young";
case "5":
return "Mystic";
default: return "";
}
case "0":
switch($cardID[5]) {
case "2":
return "Mystic,Young";
case "1":
return "Mystic";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Mystic,Young";
case "6":
return "Mystic";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
}

function GeneratedCharacterHealth($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID) {
case "MST025": return 40;
case "MST001": return 40;
case "MST046": return 40;
default: return 20;}
}

function GeneratedRarity($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return "V";
case "5":
return "V";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "2":
return "V";
case "1":
return "V";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "7":
return "V";
case "6":
return "V";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
}

function GeneratedIs1H($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID) {
default: return false;}
}

function GeneratedCardClass($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "2":
return "ASSASSIN";
case "1":
return "ASSASSIN";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "7":
return "NINJA";
case "6":
return "NINJA";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
}

function GeneratedCardTalent($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
default: return "NONE";
}
}

function GeneratedIsSpecialization($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID) {
case "MST026": return "false";
case "MST025": return "false";
case "MST002": return "false";
case "MST001": return "false";
case "MST047": return "false";
case "MST046": return "false";
default: return "";}
}

?>