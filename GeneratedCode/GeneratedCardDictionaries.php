<?php
function GeneratedCardType($cardID) {
if($cardID !== null && strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "0":
return "W";
case "7":
return "A";
case "9":
return "A";
case "8":
return "A";
case "2":
return "A";
case "3":
return "I";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "5":
return "AR";
case "0":
return "I";
case "1":
return "I";
case "2":
return "I";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "E";
case "2":
return "AR";
case "3":
return "DR";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "3":
return "A";
case "0":
return "E";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "8":
return "T";
case "9":
return "W";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "9":
return "T";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "5":
return "I";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "6":
return "I";
case "7":
return "I";
case "8":
return "I";
case "9":
return "I";
case "1":
return "DR";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "E";
case "9":
return "I";
case "2":
return "E";
case "1":
return "E";
case "3":
return "E";
case "4":
return "E";
case "8":
return "DR";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "7":
return "E";
case "8":
return "E";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "E";
case "9":
return "E";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "6":
return "E";
case "3":
return "W";
case "8":
return "AR";
case "5":
return "E";
case "4":
return "E";
case "0":
return "I";
case "2":
return "C";
case "1":
return "C";
case "9":
return "AR";
case "7":
return "E";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "6":
return "DR";
case "0":
return "I";
case "5":
return "AR";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "6":
return "C";
case "5":
return "C";
case "3":
return "AR";
case "8":
return "E";
case "7":
return "E";
case "4":
return "AR";
case "9":
return "E";
case "0":
return "AR";
case "1":
return "AR";
case "2":
return "AR";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "7":
return "I";
case "8":
return "I";
case "9":
return "I";
case "1":
return "I";
case "4":
return "I";
case "5":
return "I";
case "6":
return "I";
case "2":
return "I";
case "3":
return "I";
case "0":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "9":
return "E";
case "8":
return "E";
case "0":
return "I";
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "7":
return "C";
case "6":
return "C";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "4":
return "AR";
case "5":
return "AR";
case "6":
return "AR";
case "7":
return "AR";
case "8":
return "AR";
case "9":
return "AR";
case "0":
return "I";
case "1":
return "AR";
case "2":
return "AR";
case "3":
return "AR";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "3":
return "I";
case "2":
return "A";
case "0":
return "E";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "R";
case "0":
return "DR";
case "9":
return "I";
case "8":
return "I";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "7":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "5":
return "A";
case "8":
return "C";
case "0":
return "I";
case "1":
return "I";
case "4":
return "I";
case "2":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "8":
return "DR";
case "9":
return "DR";
default: return "AA";
}
default: return "AA";
}
case "6":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return "E";
case "8":
return "E";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "E";
case "1":
return "E";
default: return "AA";
}
default: return "AA";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "R";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "2":
return "R";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "3":
return "R";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "AA";
}
default: return "AA";
}
case "5":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "0":
return "E";
case "8":
return "E";
case "1":
return "A";
case "9":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "9":
return "T";
case "5":
return "I";
case "6":
return "I";
case "7":
return "I";
case "8":
return "I";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "9":
return "W";
case "1":
return "W";
case "2":
return "A";
case "0":
return "T";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "3":
return "E";
case "1":
return "E";
case "4":
return "E";
case "2":
return "E";
case "0":
return "E";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "7":
return "DR";
case "8":
return "DR";
case "9":
return "DR";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "I";
case "6":
return "I";
case "0":
return "A";
case "3":
return "W";
case "2":
return "C";
case "1":
return "C";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "3":
return "AR";
case "0":
return "C";
case "4":
return "A";
case "1":
return "W";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "4":
return "I";
case "5":
return "I";
case "6":
return "I";
case "8":
return "W";
case "9":
return "E";
case "7":
return "I";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "7":
return "AR";
case "8":
return "AR";
case "9":
return "AR";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "E";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "2":
return "I";
case "3":
return "I";
case "4":
return "I";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "9":
return "I";
case "1":
return "E";
case "4":
return "A";
case "3":
return "DR";
case "5":
return "I";
case "0":
return "E";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "I";
case "1":
return "I";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "2":
return "I";
case "3":
return "I";
case "1":
return "I";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "9":
return "C";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "7":
return "E";
case "9":
return "I";
case "8":
return "E";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "T";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "4":
return "C";
case "3":
return "C";
case "7":
return "A";
case "5":
return "W";
case "8":
return "I";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "9":
return "C";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "I";
case "2":
return "DR";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "8":
return "E";
case "5":
return "W";
case "6":
return "W";
case "4":
return "T";
case "9":
return "A";
case "7":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "1":
return "W";
case "2":
return "E";
case "0":
return "C";
default: return "AA";
}
default: return "AA";
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "R";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "2":
return "A";
case "0":
return "A";
case "1":
return "A";
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "DR";
case "4":
return "DR";
case "5":
return "DR";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "0":
return "A";
case "1":
return "A";
case "9":
return "A";
case "2":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "A";
case "1":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "E";
case "3":
return "E";
case "4":
return "E";
case "8":
return "E";
case "7":
return "E";
case "5":
return "E";
case "6":
return "E";
case "1":
return "E";
case "2":
return "E";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "I";
case "2":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "3":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "8":
return "A";
case "5":
return "W";
case "4":
return "C";
case "3":
return "C";
case "0":
return "A";
case "1":
return "A";
case "7":
return "E";
case "2":
return "T";
case "9":
return "A";
case "6":
return "E";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "E";
case "2":
return "C";
case "1":
return "C";
case "0":
return "R";
case "6":
return "A";
case "9":
return "A";
case "7":
return "A";
case "4":
return "E";
case "3":
return "W";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "9":
return "C";
case "8":
return "C";
case "5":
return "A";
case "6":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "7":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "3":
return "A";
case "1":
return "A";
case "8":
return "DR";
case "9":
return "DR";
case "4":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "2":
return "E";
case "0":
return "W";
case "6":
return "A";
case "7":
return "A";
case "1":
return "E";
case "8":
return "DR";
case "9":
return "DR";
case "4":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "9":
return "E";
case "8":
return "E";
case "7":
return "W";
case "6":
return "C";
case "5":
return "C";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "0":
return "DR";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "0":
return "DR";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "0":
return "DR";
case "1":
return "DR";
case "2":
return "DR";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "8":
return "R";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "E";
case "1":
return "C";
case "4":
return "E";
case "3":
return "E";
case "6":
return "E";
case "2":
return "W";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "3":
return "I";
case "7":
return "T";
case "0":
return "A";
case "6":
return "I";
case "1":
return "A";
case "9":
return "E";
case "8":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "7":
return "T";
case "8":
return "T";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "6":
return "E";
case "5":
return "E";
case "1":
return "C";
case "3":
return "E";
case "2":
return "W";
case "4":
return "E";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "C";
case "3":
return "E";
case "2":
return "W";
case "7":
return "E";
case "5":
return "E";
case "6":
return "E";
case "4":
return "E";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "T";
case "2":
return "T";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "4":
return "A";
case "6":
return "I";
case "9":
return "A";
case "0":
return "A";
case "1":
return "A";
case "5":
return "A";
case "2":
return "A";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return "A";
case "0":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "1":
return "C";
case "6":
return "E";
case "4":
return "E";
case "5":
return "E";
case "3":
return "E";
case "2":
return "W";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "E";
case "3":
return "E";
case "2":
return "W";
case "1":
return "C";
case "4":
return "E";
case "6":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "7":
return "T";
case "6":
return "A";
case "8":
return "T";
case "5":
return "DR";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "4":
return "AR";
case "5":
return "AR";
case "6":
return "DR";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "3":
return "B";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "1":
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "A";
case "1":
return "W";
case "5":
return "A";
case "0":
return "W";
case "2":
return "E";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "W";
case "4":
return "I";
case "2":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "3":
return "A";
case "1":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "9":
return "C";
case "8":
return "C";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "1":
return "E";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "3":
return "A";
case "0":
return "W";
case "4":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "8":
return "A";
case "1":
return "A";
case "6":
return "AR";
case "9":
return "I";
case "2":
return "I";
case "7":
return "DR";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "7":
return "T";
case "5":
return "R";
case "6":
return "T";
case "0":
return "I";
case "1":
return "I";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "9":
return "W";
case "8":
return "C";
case "7":
return "T";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "W";
case "5":
return "I";
case "2":
return "E";
case "7":
return "DR";
case "4":
return "A";
case "1":
return "W";
case "8":
return "DR";
case "6":
return "DR";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "8":
return "E";
case "9":
return "E";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "W";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "9":
return "W";
case "8":
return "C";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "W";
case "2":
return "C";
case "5":
return "E";
case "4":
return "W";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "9":
return "A";
case "0":
return "R";
case "2":
return "C";
case "4":
return "W";
case "5":
return "W";
case "1":
return "C";
case "3":
return "W";
case "6":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "7":
return "C";
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "0":
return "A";
case "8":
return "W";
case "9":
return "W";
case "6":
return "C";
case "5":
return "C";
case "4":
return "T";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "3":
return "E";
case "0":
return "W";
case "1":
return "W";
case "2":
return "W";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "9":
return "W";
case "8":
return "W";
case "6":
return "C";
case "7":
return "C";
case "5":
return "T";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "E";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "AR";
case "9":
return "AR";
case "4":
return "A";
case "2":
return "AR";
case "3":
return "AR";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "8":
return "C";
case "9":
return "C";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "0":
return "AR";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "C";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "8":
return "A";
case "9":
return "A";
case "0":
return "A";
case "1":
return "A";
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return "E";
case "9":
return "E";
case "7":
return "A";
case "8":
return "A";
case "0":
return "E";
case "1":
return "E";
case "2":
return "E";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "6":
return "I";
case "8":
return "R";
case "7":
return "T";
case "0":
return "A";
case "9":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "1":
return "E";
case "3":
return "E";
case "8":
return "A";
case "4":
return "E";
case "2":
return "E";
case "5":
return "E";
case "0":
return "I";
case "9":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "5":
return "C";
case "4":
return "C";
case "0":
return "E";
case "2":
return "A";
case "7":
return "T";
case "1":
return "E";
case "3":
return "A";
case "6":
return "W";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "6":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "E";
case "6":
return "A";
case "7":
return "A";
case "5":
return "E";
case "4":
return "E";
case "9":
return "B";
case "0":
return "A";
case "2":
return "T";
case "8":
return "I";
case "1":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "I";
case "1":
return "I";
case "2":
return "I";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "5":
return "E";
case "0":
return "I";
case "1":
return "I";
case "2":
return "I";
case "4":
return "E";
case "8":
return "A";
case "6":
return "E";
case "3":
return "I";
case "9":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "I";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "B";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "A";
case "2":
return "A";
case "6":
return "A";
case "9":
return "C";
case "8":
return "A";
case "7":
return "I";
case "5":
return "W";
case "1":
return "I";
case "4":
return "C";
case "3":
return "C";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "6":
return "A";
case "8":
return "C";
case "7":
return "C";
case "2":
return "C";
case "1":
return "C";
case "4":
return "A";
case "3":
return "W";
case "9":
return "W";
case "5":
return "A";
case "0":
return "R";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "8":
return "E";
case "4":
return "A";
case "5":
return "T";
case "6":
return "T";
case "9":
return "E";
case "3":
return "I";
case "0":
return "C";
case "2":
return "I";
case "1":
return "W";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "7":
return "I";
case "2":
return "E";
case "8":
return "I";
case "1":
return "E";
case "0":
return "A";
case "3":
return "E";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "I";
case "0":
return "E";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "5":
return "I";
case "6":
return "I";
case "7":
return "I";
case "8":
return "I";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "7":
return "I";
case "8":
return "I";
case "9":
return "I";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "2":
return "DR";
case "3":
return "DR";
case "4":
return "DR";
case "5":
return "I";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "4":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "1":
return "A";
case "5":
return "A";
case "4":
return "A";
case "6":
return "DR";
case "7":
return "R";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "4":
return "E";
case "2":
return "W";
case "3":
return "E";
case "7":
return "M";
case "5":
return "E";
case "6":
return "E";
case "1":
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return "E";
case "6":
return "E";
case "5":
return "E";
case "4":
return "E";
case "7":
return "E";
case "1":
return "E";
case "0":
return "A";
case "9":
return "E";
case "8":
return "E";
case "2":
return "E";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "8":
return "A";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "9":
return "A";
case "7":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "3":
return "A";
case "1":
return "A";
case "2":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "6":
return "W";
case "1":
return "C";
case "2":
return "C";
case "0":
return "R";
case "4":
return "C";
case "5":
return "C";
case "3":
return "W";
case "9":
return "W";
case "8":
return "C";
case "7":
return "C";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "1":
return "I";
case "0":
return "I";
case "4":
return "A";
case "9":
return "A";
case "5":
return "A";
case "2":
return "I";
case "3":
return "I";
case "6":
return "A";
case "8":
return "A";
case "7":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "7":
return "A";
case "9":
return "A";
case "8":
return "A";
case "6":
return "A";
case "1":
return "A";
case "0":
return "A";
case "4":
return "A";
case "3":
return "A";
case "2":
return "A";
case "5":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "0":
return "I";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "8":
return "A";
case "6":
return "A";
case "7":
return "A";
case "9":
return "A";
case "4":
return "E";
case "3":
return "E";
case "2":
return "E";
case "5":
return "E";
case "0":
return "E";
case "1":
return "E";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "5":
return "A";
case "0":
return "A";
case "6":
return "A";
case "9":
return "A";
case "8":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "7":
return "A";
case "4":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "B";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "B";
case "8":
return "B";
case "9":
return "B";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "6":
return "I";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "9":
return "B";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "B";
case "1":
return "B";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "1":
return "A";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return "A";
case "6":
return "A";
case "0":
return "A";
case "9":
return "E";
case "4":
return "I";
case "8":
return "I";
case "2":
return "A";
case "5":
return "A";
case "7":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "4":
return "T";
case "0":
return "A";
case "5":
return "E";
case "8":
return "A";
case "1":
return "B";
case "2":
return "B";
case "3":
return "B";
case "9":
return "DR";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "T";
default: return "AA";
}
default: return "AA";
}
case "4":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "E";
case "0":
return "E";
case "4":
return "E";
case "9":
return "E";
case "5":
return "E";
case "2":
return "E";
case "3":
return "E";
case "6":
return "E";
case "8":
return "E";
case "7":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "7":
return "E";
case "9":
return "E";
case "8":
return "E";
case "6":
return "E";
case "1":
return "E";
case "0":
return "E";
case "4":
return "E";
case "3":
return "E";
case "2":
return "E";
case "5":
return "E";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "1":
return "E";
case "2":
return "E";
case "3":
return "E";
case "0":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "8":
return "E";
case "6":
return "E";
case "7":
return "E";
case "9":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "D";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "0":
return "C";
case "1":
return "W";
case "8":
return "A";
case "9":
return "A";
case "4":
return "A";
case "2":
return "DR";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "4":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "5":
return "E";
case "4":
return "E";
case "8":
return "A";
case "9":
return "A";
case "3":
return "T";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "4":
return "T";
case "7":
return "T";
case "6":
return "T";
case "5":
return "T";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "7":
return "E";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "0":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "3":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "9":
return "T";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "C";
case "8":
return "E";
case "9":
return "C";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "AR";
case "1":
return "AR";
case "2":
return "AR";
case "9":
return "A";
case "3":
return "AR";
case "4":
return "AR";
case "5":
return "AR";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "5":
return "A";
case "3":
return "E";
case "6":
return "A";
case "4":
return "AR";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "DR";
case "1":
return "DR";
case "2":
return "DR";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "7":
return "W";
case "5":
return "C";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "6":
return "E";
case "9":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "E";
case "2":
return "A";
case "3":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "3":
return "A";
case "4":
return "A";
case "1":
return "E";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "7":
return "E";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "6":
return "T";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "I";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return "A";
case "7":
return "I";
case "6":
return "A";
case "5":
return "E";
case "4":
return "E";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "2":
return "A";
case "6":
return "I";
case "5":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "4":
return "E";
case "3":
return "E";
case "0":
return "A";
case "1":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "7":
return "A";
case "5":
return "E";
case "0":
return "T";
case "1":
return "T";
case "6":
return "E";
case "3":
return "A";
case "4":
return "DR";
case "2":
return "I";
case "8":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "9":
return "T";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "5":
return "I";
case "6":
return "I";
case "7":
return "I";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "A";
case "0":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "3":
return "E";
case "4":
return "E";
case "5":
return "A";
case "2":
return "W";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "7":
return "R";
case "5":
return "E";
case "4":
return "E";
case "3":
return "E";
case "6":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "3":
return "W";
case "0":
return "A";
case "1":
return "A";
case "2":
return "W";
case "7":
return "DR";
case "8":
return "DR";
case "9":
return "DR";
case "4":
return "E";
case "6":
return "A";
case "5":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "4":
return "E";
case "3":
return "E";
case "9":
return "A";
case "5":
return "A";
case "2":
return "T";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "I";
case "7":
return "DR";
case "8":
return "DR";
case "9":
return "DR";
case "0":
return "A";
case "2":
return "C";
case "1":
return "C";
case "3":
return "W";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "3":
return "C";
case "2":
return "C";
case "5":
return "A";
case "6":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "3":
return "I";
case "2":
return "A";
case "1":
return "A";
case "0":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "7":
return "A";
case "2":
return "C";
case "1":
return "C";
case "3":
return "W";
case "0":
return "A";
case "4":
return "W";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "DR";
case "1":
return "DR";
case "2":
return "DR";
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return "ALLY";
case "9":
return "ALLY";
case "8":
return "ALLY";
case "5":
return "ALLY";
case "6":
return "ALLY";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "2":
return "ALLY";
case "0":
return "ALLY";
case "1":
return "ALLY";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "2":
return "I";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "6":
return "I";
case "7":
return "I";
case "8":
return "I";
case "9":
return "I";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "6":
return "W";
case "5":
return "C";
case "0":
return "I";
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "4":
return "C";
case "7":
return "E";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "AR";
case "1":
return "I";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "8":
return "E";
case "6":
return "E";
case "7":
return "E";
case "5":
return "E";
case "0":
return "AR";
case "1":
return "AR";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "4":
return "E";
case "5":
return "I";
case "6":
return "I";
case "7":
return "I";
case "8":
return "I";
case "9":
return "I";
case "0":
return "R";
case "3":
return "W";
case "2":
return "C";
case "1":
return "C";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "I";
case "1":
return "I";
case "2":
return "I";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "3":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "9":
return "AR";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return "A";
case "8":
return "A";
case "4":
return "T";
case "5":
return "T";
case "3":
return "T";
case "7":
return "A";
case "9":
return "W";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "4":
return "D";
case "6":
return "E";
case "8":
return "E";
case "7":
return "E";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "5":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "1":
return "A";
case "0":
return "DR";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "I";
case "1":
return "I";
case "2":
return "I";
case "5":
return "W";
case "4":
return "C";
case "3":
return "C";
case "6":
return "E";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "9":
return "A";
case "0":
return "A";
case "2":
return "I";
case "1":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "5":
return "W";
case "6":
return "E";
case "4":
return "C";
case "3":
return "C";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "A";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "E";
case "8":
return "AR";
case "5":
return "W";
case "2":
return "A";
case "7":
return "E";
case "9":
return "I";
case "0":
return "E";
case "4":
return "T";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "2":
return "A";
case "7":
return "E";
case "1":
return "E";
case "8":
return "DR";
case "9":
return "A";
case "6":
return "W";
case "4":
return "T";
case "3":
return "A";
case "0":
return "W";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "T";
case "1":
return "I";
case "4":
return "T";
case "5":
return "T";
case "0":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "1":
return "E";
case "4":
return "E";
case "2":
return "E";
case "5":
return "E";
case "3":
return "E";
case "9":
return "A";
case "8":
return "DR";
case "0":
return "T";
default: return "AA";
}
default: return "AA";
}
case "5":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return "D";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "6":
return "I";
case "4":
return "A";
case "1":
return "T";
case "3":
return "W";
case "0":
return "A";
case "2":
return "W";
case "5":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "1":
return "E";
case "2":
return "W";
case "9":
return "A";
case "4":
return "A";
case "5":
return "A";
case "0":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "4":
return "C";
case "3":
return "C";
case "7":
return "E";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "8":
return "E";
case "5":
return "W";
case "6":
return "W";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "9":
return "A";
case "0":
return "AR";
case "5":
return "A";
case "2":
return "E";
case "1":
return "W";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "8":
return "AR";
case "9":
return "AR";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "3":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "AR";
case "1":
return "AR";
case "2":
return "AR";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "E";
case "9":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "1":
return "C";
case "5":
return "W";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "1":
return "A";
case "8":
return "A";
case "9":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "5":
return "E";
case "8":
return "A";
case "0":
return "A";
case "1":
return "A";
case "9":
return "A";
case "6":
return "E";
case "2":
return "I";
case "3":
return "I";
case "4":
return "I";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "9":
return "A";
case "0":
return "DR";
case "1":
return "DR";
case "2":
return "DR";
case "6":
return "DR";
case "7":
return "DR";
case "8":
return "DR";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "1":
return "A";
case "2":
return "I";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "AR";
case "0":
return "W";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "8":
return "A";
case "9":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "6":
return "E";
case "7":
return "E";
case "5":
return "C";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "9":
return "E";
case "8":
return "W";
case "0":
return "AR";
case "1":
return "AR";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "7":
return "W";
case "8":
return "W";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "9":
return "W";
case "6":
return "A";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "8":
return "A";
case "9":
return "A";
case "3":
return "E";
case "2":
return "A";
case "0":
return "A";
case "1":
return "A";
case "7":
return "A";
case "4":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "4":
return "E";
case "5":
return "E";
case "3":
return "T";
case "7":
return "E";
case "8":
return "E";
case "9":
return "E";
case "6":
return "E";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "3":
return "T";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "4":
return "T";
case "5":
return "T";
case "6":
return "T";
default: return "AA";
}
default: return "AA";
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return "W";
default: return "AA";
}
default: return "AA";
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "ALLY";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "AR";
case "1":
return "A";
case "0":
return "AR";
case "8":
return "T";
case "5":
return "I";
case "7":
return "R";
case "4":
return "DR";
case "2":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "4":
return "E";
case "2":
return "W";
case "1":
return "C";
case "9":
return "A";
case "5":
return "E";
case "8":
return "A";
case "7":
return "M";
case "3":
return "E";
case "6":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "7":
return "A";
case "5":
return "AR";
case "6":
return "AR";
case "9":
return "A";
case "3":
return "AR";
case "0":
return "A";
case "2":
return "A";
case "8":
return "A";
case "4":
return "AR";
case "1":
return "A";
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "W";
case "0":
return "I";
case "1":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "T";
case "7":
return "A";
case "6":
return "AR";
case "4":
return "R";
case "8":
return "A";
case "3":
return "T";
case "5":
return "W";
case "1":
return "T";
case "2":
return "T";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "6":
return "E";
case "0":
return "E";
case "5":
return "E";
case "2":
return "E";
case "1":
return "E";
case "3":
return "E";
case "4":
return "E";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "4":
return "AR";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "B";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "9":
return "I";
case "2":
return "I";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "I";
case "1":
return "I";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return "AR";
case "6":
return "AR";
case "7":
return "AR";
case "0":
return "AR";
case "1":
return "AR";
case "2":
return "AR";
case "3":
return "AR";
case "4":
return "AR";
case "8":
return "AR";
case "9":
return "AR";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "5":
return "E";
case "9":
return "E";
case "8":
return "E";
case "6":
return "E";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "7":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "I";
case "1":
return "B";
case "2":
return "B";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "E";
case "1":
return "AR";
case "4":
return "A";
case "6":
return "AR";
case "7":
return "AR";
case "8":
return "AR";
case "9":
return "AR";
case "5":
return "A";
case "2":
return "AR";
case "3":
return "AR";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "4":
return "T";
case "5":
return "E";
case "3":
return "A";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "6":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "6":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "AR";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "5":
return "E";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "1":
return "B";
case "0":
return "I";
case "2":
return "B";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "1":
return "B";
case "0":
return "I";
case "2":
return "B";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "E";
case "6":
return "W";
case "0":
return "A";
case "2":
return "C";
case "1":
return "C";
case "9":
return "E";
case "5":
return "W";
case "7":
return "W";
case "4":
return "C";
case "3":
return "C";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "1":
return "E";
case "3":
return "E";
case "4":
return "E";
case "5":
return "E";
case "0":
return "W";
case "6":
return "E";
case "2":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "6":
return "C";
case "5":
return "C";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "9":
return "W";
case "4":
return "A";
case "8":
return "C";
case "7":
return "C";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "B";
case "8":
return "A";
case "9":
return "A";
case "1":
return "B";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "4":
return "A";
case "0":
return "E";
case "6":
return "I";
case "1":
return "E";
case "5":
return "A";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "4":
return "W";
case "7":
return "E";
case "9":
return "E";
case "5":
return "W";
case "1":
return "C";
case "0":
return "C";
case "3":
return "C";
case "2":
return "C";
case "6":
return "W";
case "8":
return "E";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
default: return "AA";
}
default: return "AA";
}
case "6":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return "E";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "C";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "3":
return "C";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "T";
case "5":
return "C";
case "4":
return "C";
case "7":
return "E";
case "0":
return "I";
case "1":
return "I";
case "6":
return "W";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "5":
return "A";
case "2":
return "C";
case "1":
return "C";
case "9":
return "A";
case "8":
return "A";
case "6":
return "A";
case "7":
return "A";
case "4":
return "E";
case "3":
return "W";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "AR";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "4":
return "E";
case "7":
return "AR";
case "5":
return "E";
case "9":
return "I";
case "8":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "9":
return "I";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "5":
return "E";
case "6":
return "A";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "6":
return "E";
case "2":
return "AR";
case "3":
return "AR";
case "4":
return "AR";
case "9":
return "I";
case "7":
return "E";
case "8":
return "A";
case "5":
return "W";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "8":
return "A";
case "6":
return "E";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "7":
return "E";
case "9":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "2":
return "E";
case "3":
return "E";
case "4":
return "E";
case "5":
return "E";
case "6":
return "E";
case "0":
return "A";
case "1":
return "A";
case "9":
return "DR";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "4":
return "A";
case "5":
return "A";
case "9":
return "A";
case "3":
return "C";
case "2":
return "C";
case "6":
return "DR";
case "7":
return "DR";
case "8":
return "DR";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "E";
case "4":
return "I";
case "2":
return "E";
case "9":
return "E";
case "8":
return "E";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
default: return "AA";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "ALLY";
case "8":
return "ALLY";
case "6":
return "ALLY";
case "7":
return "ALLY";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "ALLY";
case "1":
return "ALLY";
case "2":
return "ALLY";
case "3":
return "ALLY";
case "4":
return "ALLY";
case "5":
return "ALLY";
case "6":
return "ALLY";
case "7":
return "ALLY";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "R";
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "0":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "2":
return "AR";
case "0":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "W";
case "6":
return "A";
case "2":
return "E";
case "7":
return "A";
case "1":
return "E";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "9":
return "C";
case "8":
return "C";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "5":
return "E";
case "7":
return "A";
case "0":
return "R";
case "8":
return "DR";
case "2":
return "C";
case "1":
return "C";
case "3":
return "W";
case "9":
return "A";
case "4":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "0":
return "I";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "1":
return "DR";
case "2":
return "DR";
case "3":
return "DR";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "9":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "8":
return "W";
case "7":
return "C";
case "6":
return "C";
case "9":
return "E";
case "5":
return "T";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "2":
return "DR";
case "3":
return "DR";
case "4":
return "DR";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return "AR";
case "6":
return "AR";
case "7":
return "AR";
case "2":
return "AR";
case "3":
return "AR";
case "4":
return "AR";
case "8":
return "AR";
case "9":
return "AR";
case "0":
return "A";
case "1":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "6":
return "E";
case "5":
return "W";
case "4":
return "C";
case "3":
return "C";
case "8":
return "AR";
case "7":
return "E";
case "9":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "I";
case "0":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "0":
return "AR";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "2":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "E";
case "3":
return "E";
case "2":
return "E";
case "1":
return "E";
case "7":
return "E";
case "5":
return "E";
case "8":
return "E";
case "6":
return "E";
case "4":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "AR";
case "4":
return "AR";
case "5":
return "AR";
case "0":
return "AR";
case "1":
return "AR";
case "6":
return "DR";
case "7":
return "DR";
case "8":
return "DR";
case "9":
return "A";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "R";
case "0":
return "A";
case "5":
return "T";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "8":
return "A";
case "9":
return "A";
case "0":
return "AR";
case "1":
return "AR";
case "5":
return "DR";
case "6":
return "DR";
case "7":
return "DR";
case "2":
return "DR";
case "3":
return "DR";
case "4":
return "DR";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "6":
return "AR";
case "7":
return "AR";
case "8":
return "AR";
case "9":
return "AR";
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "C";
case "5":
return "W";
case "6":
return "W";
case "7":
return "W";
case "8":
return "W";
case "0":
return "R";
case "9":
return "W";
case "4":
return "W";
case "2":
return "C";
case "1":
return "C";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "C";
case "3":
return "W";
case "7":
return "E";
case "8":
return "E";
case "5":
return "E";
case "6":
return "E";
case "2":
return "C";
case "1":
return "C";
case "4":
return "E";
case "9":
return "E";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "9":
return "C";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "7":
return "C";
case "8":
return "W";
case "6":
return "C";
case "5":
return "C";
case "9":
return "E";
case "2":
return "AR";
case "3":
return "AR";
case "4":
return "AR";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "1":
return "E";
case "0":
return "W";
case "4":
return "AR";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "4":
return "A";
case "5":
return "I";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "1":
return "AR";
case "2":
return "AR";
case "3":
return "AR";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "1":
return "E";
case "3":
return "AR";
case "4":
return "AR";
case "0":
return "E";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "1":
return "DR";
case "2":
return "DR";
case "3":
return "DR";
case "6":
return "E";
case "5":
return "E";
case "8":
return "E";
case "7":
return "E";
case "9":
return "E";
case "4":
return "E";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "6":
return "DR";
case "2":
return "DR";
case "3":
return "DR";
case "9":
return "A";
case "5":
return "A";
case "7":
return "DR";
case "4":
return "DR";
case "8":
return "DR";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "7":
return "A";
case "1":
return "E";
case "2":
return "E";
case "8":
return "A";
case "0":
return "E";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "9":
return "A";
case "7":
return "E";
case "4":
return "AR";
case "5":
return "AR";
case "6":
return "AR";
case "8":
return "E";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "1":
return "A";
case "0":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "9":
return "E";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return "T";
case "0":
return "I";
case "8":
return "R";
case "5":
return "T";
case "6":
return "T";
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "7":
return "T";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "8":
return "I";
case "9":
return "I";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "I";
case "5":
return "I";
case "3":
return "A";
case "6":
return "DR";
case "0":
return "E";
case "9":
return "E";
case "2":
return "AR";
case "8":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "W";
case "6":
return "B";
case "4":
return "E";
case "2":
return "E";
case "1":
return "E";
case "5":
return "I";
case "3":
return "E";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "4":
return "C";
case "2":
return "A";
case "9":
return "I";
case "5":
return "E";
case "6":
return "A";
case "0":
return "DR";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "5":
return "W";
case "9":
return "E";
case "3":
return "E";
case "4":
return "E";
case "1":
return "C";
case "2":
return "C";
case "6":
return "C";
case "8":
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
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return "E";
case "7":
return "C";
case "2":
return "B";
case "6":
return "B";
case "8":
return "W";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "1":
return "A";
case "7":
return "A";
case "8":
return "B";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "0":
return "E";
case "2":
return "E";
case "1":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "8":
return "B";
case "2":
return "A";
case "3":
return "A";
case "9":
return "C";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "1":
return "E";
case "2":
return "E";
case "0":
return "E";
case "3":
return "E";
case "7":
return "A";
case "8":
return "A";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "6":
return "B";
case "8":
return "W";
case "1":
return "A";
case "2":
return "A";
case "5":
return "I";
case "7":
return "C";
case "9":
return "E";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "B";
case "3":
return "B";
case "1":
return "A";
case "2":
return "I";
case "4":
return "A";
case "5":
return "A";
case "9":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "8":
return "A";
case "9":
return "A";
case "7":
return "A";
case "1":
return "C";
case "5":
return "E";
case "4":
return "E";
case "3":
return "E";
case "6":
return "E";
case "2":
return "W";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "A";
case "9":
return "B";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "3":
return "E";
case "2":
return "E";
case "8":
return "I";
case "0":
return "W";
case "1":
return "E";
case "4":
return "E";
case "9":
return "I";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "A";
case "3":
return "T";
case "8":
return "T";
case "5":
return "T";
case "1":
return "A";
case "6":
return "T";
case "2":
return "B";
case "7":
return "T";
default: return "AA";
}
default: return "AA";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "E";
case "9":
return "E";
case "7":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "E";
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
default: return "AA";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "E";
case "6":
return "E";
case "4":
return "E";
case "7":
return "E";
case "2":
return "W";
case "3":
return "E";
case "1":
return "C";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "7":
return "B";
case "4":
return "A";
case "8":
return "T";
case "5":
return "A";
case "0":
return "I";
case "6":
return "I";
case "9":
return "E";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "7":
return "A";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "E";
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "A";
case "7":
return "A";
case "8":
return "A";
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
if($cardID !== null && strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "8":
return 6;
case "9":
return 5;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
case "0":
return 4;
case "1":
return 2;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "5":
return 1;
case "9":
return 5;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 6;
case "9":
return 4;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
case "3":
return 1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return 7;
case "4":
return 6;
case "5":
return 5;
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
case "9":
return 7;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "4":
return 3;
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
case "6":
return 6;
case "0":
return 6;
case "1":
return 5;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
case "0":
return 6;
case "1":
return 5;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return 7;
case "4":
return 6;
case "5":
return 5;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
case "9":
return 7;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "9":
return 7;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "9":
return 6;
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "6":
return 3;
case "5":
return 3;
case "7":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 6;
case "3":
return 5;
case "4":
return 4;
case "5":
return 7;
case "6":
return 6;
case "7":
return 5;
case "8":
return 6;
case "9":
return 5;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
case "2":
return 6;
case "3":
return 6;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 8;
case "0":
return 4;
case "8":
return 9;
case "9":
return 7;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "2":
return 8;
case "3":
return 7;
case "5":
return 6;
case "6":
return 5;
case "7":
return 4;
case "4":
return 9;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
case "7":
return 8;
case "8":
return 7;
case "9":
return 6;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "9":
return 3;
case "0":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
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
case "M":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "6":
return 3;
case "7":
return 2;
case "8":
return 1;
case "9":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "9":
return 6;
case "0":
return 4;
case "1":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "8":
return 6;
case "9":
return 5;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "9":
return 3;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
case "1":
return 3;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "0":
return 6;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "5":
return 4;
case "6":
return 6;
case "8":
return 6;
case "9":
return 5;
case "7":
return 0;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "9":
return 5;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
case "2":
return 7;
case "1":
return 0;
case "5":
return 3;
case "9":
return 4;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
case "0":
return 3;
case "1":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "2":
return 7;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "8":
return 8;
case "9":
return 7;
case "1":
return 9;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "4":
return 5;
case "7":
return 6;
case "8":
return 7;
case "9":
return 6;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "4":
return 7;
case "5":
return 6;
case "6":
return 5;
case "7":
return 7;
case "8":
return 6;
case "9":
return 5;
case "0":
return 5;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 3;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return 6;
case "6":
return 5;
case "7":
return 4;
case "8":
return 8;
case "9":
return 7;
case "0":
return 5;
case "1":
return 4;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "8":
return 3;
case "9":
return 2;
case "0":
return 3;
case "1":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 3;
case "5":
return 2;
case "6":
return 1;
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "5":
return 6;
case "6":
return 5;
case "7":
return 4;
case "8":
return 6;
case "9":
return 6;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "4":
return 7;
case "5":
return 6;
case "6":
return 5;
case "7":
return 7;
case "8":
return 6;
case "9":
return 5;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 6;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
case "1":
return 6;
case "4":
return 6;
case "5":
return 6;
case "9":
return 6;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 6;
case "5":
return 2;
case "6":
return 2;
case "1":
return 5;
case "2":
return 4;
case "3":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 2;
case "9":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return 3;
case "8":
return 1;
case "7":
return 2;
case "3":
return 1;
case "9":
return 3;
case "4":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "4":
return 3;
case "6":
return 1;
case "5":
return 2;
case "1":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 2;
case "5":
return 1;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 4;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return 7;
case "5":
return 6;
case "6":
return 5;
case "7":
return 4;
case "8":
return 4;
case "9":
return 4;
case "1":
return 2;
case "2":
return 6;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "8":
return 3;
case "9":
return 2;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "8":
return 0;
case "0":
return 3;
case "1":
return 2;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 3;
case "2":
return 2;
case "3":
return 1;
case "4":
return 3;
case "5":
return 2;
case "6":
return 1;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "9":
return 2;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "5":
return 2;
case "7":
return 3;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "3":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
case "1":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "4":
return 5;
case "7":
return 2;
case "8":
return 3;
case "1":
return 5;
case "2":
return 5;
case "3":
return 5;
case "9":
return 4;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 5;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 7;
case "4":
return 6;
case "5":
return 5;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
case "9":
return 4;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "7":
return 4;
case "3":
return 6;
case "6":
return 6;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return 4;
case "9":
return 4;
case "8":
return 4;
case "5":
return 4;
case "6":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "2":
return 4;
case "0":
return 4;
case "1":
return 4;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "6":
return 5;
case "7":
return 2;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "5":
return 4;
case "1":
return 6;
case "3":
return 10;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return 4;
case "9":
return 4;
case "6":
return 0;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 4;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
case "1":
return 3;
case "2":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "1":
return 5;
case "2":
return 4;
case "3":
return 3;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "6":
return 3;
case "7":
return 2;
case "8":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 6;
case "4":
return 7;
case "5":
return 6;
case "6":
return 5;
case "7":
return 7;
case "8":
return 6;
case "9":
return 5;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "9":
return 7;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "9":
return 6;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "1":
return 7;
case "2":
return 6;
case "3":
return 5;
case "4":
return 7;
case "5":
return 6;
case "6":
return 5;
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "9":
return 5;
case "0":
return 4;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "5":
return 1;
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
case "7":
return 6;
case "8":
return 6;
case "9":
return 6;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "5":
return 6;
case "8":
return 13;
case "9":
return 6;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "2":
return 6;
case "3":
return 5;
case "4":
return 4;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "3":
return 6;
case "9":
return 6;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "2":
return 6;
case "3":
return 5;
case "4":
return 4;
case "5":
return 6;
case "6":
return 5;
case "7":
return 4;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
case "5":
return 7;
case "6":
return 6;
case "7":
return 5;
case "8":
return 6;
case "9":
return 5;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 5;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "1":
return 5;
case "2":
return 4;
case "3":
return 3;
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "3":
return 6;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "3":
return 5;
case "4":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "9":
return 4;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 4;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
case "1":
return 3;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
case "2":
return 4;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 6;
case "5":
return 7;
case "7":
return 6;
case "8":
return 6;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "5":
return 0;
case "7":
return 3;
case "8":
return 3;
case "0":
return 2;
case "1":
return 1;
case "9":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
case "6":
return 3;
case "7":
return 2;
case "8":
return 1;
case "9":
return 3;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "8":
return 5;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
case "9":
return 6;
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 4;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return 2;
default: return 0;
}
default: return 0;
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return 5;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return 6;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
case "7":
return 8;
case "8":
return 7;
case "9":
return 6;
default: return 0;
}
default: return 0;
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return 4;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "6":
return 5;
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return 1;
case "8":
return 3;
case "6":
return 3;
case "9":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 5;
case "2":
return 4;
case "3":
return 3;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
case "7":
return 8;
case "8":
return 7;
case "9":
return 6;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "2":
return 4;
case "3":
return 5;
case "4":
return 2;
case "5":
return 3;
case "6":
return 3;
case "7":
return 1;
case "8":
return 2;
case "0":
return 4;
case "1":
return 3;
case "9":
return 3;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "1":
return 5;
case "2":
return 4;
case "3":
return 3;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "6":
return 6;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
default: return 0;
}
default: return 0;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return 2;
case "8":
return 4;
case "6":
return 6;
case "7":
return 5;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 4;
case "2":
return 2;
case "3":
return 4;
case "4":
return 1;
case "5":
return 3;
case "6":
return 6;
case "7":
return 3;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
case "9":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 5;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "7":
return 6;
case "8":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 7;
case "5":
return 6;
case "6":
return 5;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return 13;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "1":
return 0;
case "0":
return 4;
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
case "A":
switch($cardID[1]) {
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return 5;
case "8":
return 5;
case "6":
return 5;
case "9":
return 5;
case "0":
return 6;
case "1":
return 6;
case "2":
return 6;
case "3":
return 6;
case "4":
return 6;
case "5":
return 6;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "7":
return 6;
case "8":
return 6;
case "9":
return 6;
case "2":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 5;
case "2":
return 5;
case "3":
return 5;
case "4":
return 5;
case "5":
return 5;
case "6":
return 6;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 5;
case "2":
return 9;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "5":
return 4;
case "3":
return 5;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
case "9":
return 4;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "8":
return 10;
case "3":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "7":
return 1;
case "0":
return 3;
case "1":
return 2;
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 5;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return 5;
case "2":
return 4;
case "3":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
case "9":
return 6;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "9":
return 6;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
case "1":
return 4;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 5;
case "2":
return 4;
case "3":
return 3;
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
case "9":
return 4;
case "0":
return 4;
case "1":
return 3;
case "2":
return 4;
case "3":
return 4;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
case "2":
return 0;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 5;
case "2":
return 5;
case "3":
return 5;
case "4":
return 5;
case "5":
return 5;
case "6":
return 4;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return 3;
case "3":
return 4;
case "8":
return 3;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
case "9":
return 5;
case "2":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 4;
case "0":
return 4;
case "8":
return 3;
case "1":
return 3;
case "2":
return 4;
case "9":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return 1;
case "2":
return 3;
case "5":
return 2;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 4;
case "9":
return 3;
case "2":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "1":
return 1;
case "2":
return 1;
case "3":
return 2;
case "4":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 10;
case "1":
return 6;
case "2":
return 4;
case "8":
return 3;
case "3":
return 5;
case "9":
return 4;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return 8;
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
case "R":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 9;
case "9":
return 6;
case "2":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return 6;
case "8":
return 6;
case "5":
return 6;
case "0":
return 6;
case "9":
return 6;
case "6":
return 6;
case "7":
return 6;
case "2":
return 6;
case "3":
return 6;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "2":
return 4;
case "0":
return 6;
case "3":
return 6;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
case "3":
return 3;
case "4":
return 2;
case "5":
return 1;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "2":
return 2;
case "3":
return 1;
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return 5;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "1":
return 7;
case "2":
return 6;
case "3":
return 5;
case "4":
return 7;
case "5":
return 6;
case "6":
return 5;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "9":
return 5;
case "0":
return 5;
case "1":
return 4;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "9":
return 4;
case "4":
return 2;
case "5":
return 5;
case "6":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "9":
return 4;
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 6;
case "3":
return 5;
case "4":
return 4;
case "5":
return 7;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "1":
return 4;
case "2":
return 2;
case "9":
return 6;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "3":
return 2;
case "9":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "6":
return 7;
case "7":
return 10;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "4":
return 8;
case "5":
return 4;
case "3":
return 6;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return 7;
case "8":
return 6;
case "9":
return 5;
case "0":
return 6;
case "1":
return 5;
case "2":
return 7;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "6":
return 2;
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return 9;
case "3":
return 4;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 4;
case "5":
return 8;
case "3":
return 11;
case "8":
return 9;
case "9":
return 8;
case "4":
return 9;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "9":
return 4;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "4":
return 4;
case "1":
return 2;
case "3":
return 4;
case "5":
return 5;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "8":
return 3;
case "9":
return 2;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 8;
case "8":
return 7;
case "9":
return 6;
case "0":
return 7;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
case "3":
return 7;
case "4":
return 6;
case "5":
return 5;
case "6":
return 8;
case "7":
return 7;
case "8":
return 6;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "8":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "9":
return 8;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "9":
return 6;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "5":
return 3;
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
case "8":
return 7;
case "9":
return 6;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
case "1":
return 4;
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "9":
return 5;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 3;
case "5":
return 2;
case "6":
return 1;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 5;
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 3;
case "5":
return 2;
case "6":
return 1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "6":
return 6;
case "7":
return 6;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 1;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
case "9":
return 5;
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "1":
return 4;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "4":
return 6;
case "7":
return 7;
case "9":
return 5;
case "8":
return 6;
case "5":
return 6;
case "6":
return 6;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "6":
return 3;
case "3":
return 2;
case "9":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
case "0":
return 3;
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return 6;
case "4":
return 5;
case "5":
return 5;
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
case "0":
return 3;
case "1":
return 3;
case "2":
return 5;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "6":
return 3;
case "7":
return 2;
case "8":
return 1;
case "9":
return 4;
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
case "8":
return 3;
case "9":
return 2;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "8":
return 5;
case "9":
return 4;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
case "2":
return 3;
case "3":
return 2;
case "4":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "8":
return 5;
case "9":
return 10;
case "0":
return 2;
case "1":
return 1;
case "2":
return 6;
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "2":
return 6;
case "3":
return 5;
case "4":
return 4;
case "0":
return 2;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
case "3":
return 7;
case "4":
return 6;
case "5":
return 5;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
default: return 0;
}
default: return 0;
}
case "4":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return 6;
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 6;
case "9":
return 5;
case "2":
return 8;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "8":
return 6;
case "0":
return 4;
case "1":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "1":
return 3;
case "2":
return 2;
case "3":
return 1;
case "4":
return 3;
case "5":
return 2;
case "6":
return 1;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
case "0":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return 10;
case "8":
return 9;
case "9":
return 8;
case "1":
return 14;
case "4":
return 10;
case "5":
return 9;
case "6":
return 8;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
case "3":
return 3;
case "4":
return 2;
case "5":
return 1;
case "9":
return 5;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "6":
return 5;
case "7":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "4":
return 7;
case "5":
return 6;
case "6":
return 5;
case "7":
return 8;
case "8":
return 7;
case "9":
return 6;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "9":
return 7;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "4":
return 7;
case "5":
return 3;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
case "9":
return 6;
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
case "0":
return 3;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "4":
return 6;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
case "0":
return 4;
case "1":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
case "9":
return 5;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "8":
return 5;
case "9":
return 4;
case "5":
return 3;
case "6":
return 4;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "4":
return 8;
case "5":
return 7;
case "3":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 7;
case "4":
return 6;
case "5":
return 5;
case "6":
return 10;
case "7":
return 9;
case "8":
return 8;
case "9":
return 8;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
case "2":
return 7;
case "3":
return 6;
case "4":
return 5;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "8":
return 7;
case "9":
return 6;
case "0":
return 6;
case "1":
return 5;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 7;
case "2":
return 6;
case "3":
return 5;
case "4":
return 6;
case "5":
return 5;
case "6":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "6":
return 3;
case "7":
return 2;
case "8":
return 1;
case "9":
return 4;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "9":
return 7;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 3;
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
case "0":
return 3;
case "1":
return 2;
case "5":
return 5;
case "6":
return 4;
case "7":
return 3;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
case "2":
return 4;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "0":
return 5;
case "1":
return 4;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "2":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 2;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "9":
return 6;
case "2":
return 3;
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "3":
return 6;
case "4":
return 6;
case "5":
return 3;
case "6":
return 7;
case "9":
return 6;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 5;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
case "8":
return 3;
case "9":
return 2;
case "2":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "2":
return 6;
case "3":
return 5;
case "4":
return 4;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
case "8":
return 6;
case "9":
return 5;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 3;
case "2":
return 2;
case "3":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
case "7":
return 5;
case "8":
return 4;
case "9":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
case "2":
return 3;
case "3":
return 4;
case "4":
return 3;
case "5":
return 2;
case "6":
return 5;
case "7":
return 4;
case "8":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "8":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 1;
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "2":
return 3;
case "0":
return 1;
case "3":
return 6;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 3;
case "2":
return 2;
case "3":
return 1;
case "4":
return 3;
case "5":
return 2;
case "6":
return 1;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "0":
return 5;
case "1":
return 2;
case "2":
return 3;
case "9":
return 3;
case "3":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "8":
return 1;
case "0":
return 2;
case "1":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return 3;
case "5":
return 2;
case "6":
return 1;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
case "0":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
case "3":
return 3;
case "4":
return 2;
case "5":
return 1;
case "6":
return 3;
case "7":
return 2;
case "8":
return 1;
case "9":
return 3;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "4":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
case "6":
return 3;
case "7":
return 2;
case "8":
return 1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
case "0":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
case "2":
return 4;
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 4;
case "6":
return 8;
case "7":
return 10;
case "4":
return 6;
case "0":
return 3;
case "1":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "7":
return 6;
case "4":
return 3;
case "5":
return 3;
case "8":
return 6;
case "3":
return 4;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "2":
return 3;
case "9":
return 2;
case "8":
return 3;
case "3":
return 2;
case "0":
return 4;
case "1":
return 3;
case "4":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "5":
return 8;
case "6":
return 7;
case "7":
return 6;
case "2":
return 7;
case "3":
return 6;
case "4":
return 5;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
case "0":
return 1;
case "4":
return 2;
case "5":
return 1;
case "6":
return 3;
case "1":
return 2;
case "2":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "3":
return 5;
case "4":
return 4;
case "5":
return 3;
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "9":
return 5;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return 6;
case "3":
return 5;
case "4":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 4;
case "3":
return 3;
case "4":
return 2;
case "5":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "9":
return 4;
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
case "3":
return 4;
case "1":
return 0;
case "0":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "8":
return 4;
case "9":
return 3;
case "2":
return 4;
case "0":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 4;
case "2":
return 3;
case "3":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "9":
return 1;
case "0":
return 3;
case "1":
return 2;
case "2":
return 5;
case "3":
return 4;
case "4":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "9":
return 4;
case "3":
return 5;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "7":
return 4;
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
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return 6;
case "2":
return 6;
case "3":
return 5;
case "4":
return 5;
case "5":
return 6;
case "6":
return 4;
case "7":
return 4;
case "8":
return 5;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "3":
return 4;
case "4":
return 4;
case "5":
return 5;
case "6":
return 3;
case "7":
return 4;
case "8":
return 5;
case "9":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "2":
return 3;
case "3":
return 3;
case "9":
return 3;
case "4":
return 2;
case "5":
return 3;
case "0":
return 3;
case "6":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "9":
return 6;
case "4":
return 8;
case "5":
return 7;
case "6":
return 9;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "4":
return 5;
case "0":
return 7;
case "5":
return 6;
case "6":
return 8;
case "1":
return 8;
case "7":
return 7;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "5":
return 4;
case "7":
return 1;
case "0":
return 2;
case "6":
return 4;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "8":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "8":
return 3;
case "3":
return 4;
case "4":
return 3;
case "0":
return 5;
case "5":
return 4;
case "1":
return 4;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "2":
return 2;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return 0;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return 6;
case "4":
return 5;
case "0":
return 6;
case "1":
return 5;
case "5":
return 4;
case "2":
return 6;
case "6":
return 5;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "1":
return 5;
case "2":
return 4;
case "3":
return 4;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "8":
return 6;
case "9":
return 6;
case "2":
return 3;
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 6;
case "7":
return 6;
case "1":
return 0;
case "8":
return 5;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "5":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return 4;
case "4":
return 7;
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

function GeneratedBlockValue($cardID) {
if($cardID !== null && strlen($cardID) < 6) return 0;
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
case "9":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "0":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
case "5":
return 2;
case "9":
return -1;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
case "4":
return -1;
case "8":
return 5;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "7":
return 1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "8":
return -1;
case "6":
return 2;
case "9":
return 1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "6":
return 1;
case "3":
return -1;
case "5":
return 2;
case "4":
return 2;
case "0":
return -1;
case "2":
return -1;
case "1":
return -1;
case "7":
return 1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "6":
return 2;
case "4":
return 2;
case "7":
return 2;
case "0":
return -1;
case "8":
return 2;
case "9":
return 2;
case "5":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "3":
return -1;
case "0":
return 1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "3":
return -1;
case "8":
return -1;
case "7":
return -1;
case "4":
return -1;
case "9":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "1":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "2":
return -1;
case "3":
return -1;
case "0":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "8":
return 2;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "7":
return -1;
case "6":
return -1;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "9":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "0":
return 1;
case "9":
return 0;
case "8":
return 0;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "7":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "8":
return -1;
case "0":
return 0;
case "1":
return 0;
case "4":
return -1;
case "2":
return 1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "8":
return -1;
case "0":
return 2;
case "1":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "9":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "3":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
default: return 3;
}
case "6":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return 0;
case "8":
return 0;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
default: return 3;
}
default: return 3;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "2":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "3":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
default: return 3;
}
case "5":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return 1;
case "8":
return 0;
case "1":
return 2;
case "9":
return 0;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "9":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "1":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "2":
return -1;
case "0":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "5":
return 2;
case "3":
return 0;
case "1":
return 0;
case "4":
return 0;
case "2":
return 0;
case "8":
return 2;
case "9":
return 2;
case "7":
return 2;
case "0":
return 0;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "7":
return 4;
case "9":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "0":
return -1;
case "3":
return -1;
case "2":
return -1;
case "1":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "8":
return -1;
case "9":
return 0;
case "7":
return -1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return 0;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "9":
return -1;
case "1":
return 0;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "4":
return 2;
case "3":
return 6;
case "5":
return -1;
case "0":
return 1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "1":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "7":
return 6;
case "9":
return -1;
case "8":
return 0;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "4":
return -1;
case "3":
return -1;
case "5":
return -1;
case "8":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return -1;
case "2":
return 6;
case "1":
return 0;
case "3":
return 2;
case "4":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "0":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "3":
return -1;
case "1":
return -1;
case "2":
return 1;
case "0":
return -1;
case "5":
return -1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "8":
return 1;
case "5":
return -1;
case "6":
return -1;
case "4":
return -1;
case "7":
return 1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
default: return 3;
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "2":
return -1;
case "0":
return 2;
case "1":
return 2;
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 4;
case "5":
return 2;
case "9":
return 2;
case "2":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "3":
return 0;
case "4":
return 0;
case "8":
return 0;
case "7":
return 0;
case "5":
return 0;
case "6":
return 0;
case "1":
return 0;
case "2":
return 0;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return -1;
case "2":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "1":
return 2;
case "3":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "0":
return 2;
case "1":
return 2;
case "7":
return 0;
case "2":
return -1;
case "6":
return 0;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return 2;
case "1":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "2":
return -1;
case "1":
return -1;
case "0":
return -1;
case "7":
return -1;
case "4":
return 2;
case "3":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "0":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "2":
return 0;
case "0":
return -1;
case "7":
return 2;
case "1":
return 1;
case "8":
return 4;
case "4":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "9":
return 0;
case "8":
return 2;
case "7":
return -1;
case "6":
return -1;
case "5":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "8":
return 4;
case "4":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "0":
return 2;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "8":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return 4;
case "2":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "1":
return -1;
case "4":
return 0;
case "8":
return 0;
case "3":
return 1;
case "6":
return 1;
case "2":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "3":
return -1;
case "7":
return -1;
case "0":
return 2;
case "6":
return -1;
case "1":
return 2;
case "2":
return 2;
case "5":
return 2;
case "9":
return 0;
case "8":
return 0;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "6":
return 0;
case "3":
return 2;
case "0":
return 2;
case "8":
return 2;
case "4":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return 2;
case "6":
return -1;
case "9":
return 2;
case "0":
return -1;
case "1":
return 2;
case "4":
return -1;
case "5":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "0":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "7":
return -1;
case "6":
return 1;
case "5":
return 1;
case "9":
return 2;
case "1":
return -1;
case "3":
return 2;
case "2":
return -1;
case "4":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return -1;
case "3":
return -1;
case "2":
return -1;
case "7":
return 1;
case "5":
return -1;
case "6":
return 1;
case "4":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "2":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "6":
return -1;
case "0":
return 2;
case "1":
return 2;
case "5":
return 2;
case "2":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return -1;
case "0":
return -1;
case "6":
return -1;
case "7":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "1":
return -1;
case "6":
return 1;
case "4":
return 2;
case "5":
return 1;
case "3":
return 1;
case "2":
return -1;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return 2;
case "3":
return 2;
case "2":
return -1;
case "1":
return -1;
case "4":
return -1;
case "6":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "4":
return 2;
case "6":
return 4;
case "2":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "3":
return 4;
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return -1;
case "1":
return -1;
case "5":
return -1;
case "0":
return -1;
case "2":
return 0;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return -1;
case "4":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "1":
return 0;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "1":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return -1;
case "4":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "8":
return 2;
case "0":
return 2;
case "1":
return -1;
case "6":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return -1;
case "2":
return -1;
case "7":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "7":
return -1;
case "5":
return -1;
case "6":
return -1;
case "0":
return -1;
case "1":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
case "7":
return -1;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return -1;
case "5":
return -1;
case "2":
return 2;
case "4":
return 2;
case "1":
return -1;
case "8":
return 2;
case "6":
return 4;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 0;
case "7":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "9":
return -1;
case "8":
return -1;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return -1;
case "2":
return -1;
case "5":
return 2;
case "4":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "2":
return -1;
case "4":
return -1;
case "5":
return -1;
case "1":
return -1;
case "3":
return -1;
case "6":
return 0;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "7":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "8":
return -1;
case "9":
return -1;
case "6":
return -1;
case "5":
return -1;
case "4":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "2":
return 2;
case "9":
return -1;
case "8":
return -1;
case "6":
return -1;
case "7":
return -1;
case "4":
return 2;
case "5":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "3":
return 1;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "8":
return -1;
case "9":
return -1;
case "7":
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return -1;
case "9":
return -1;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "8":
return -1;
case "7":
return -1;
case "0":
return 2;
case "9":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "1":
return 0;
case "3":
return 0;
case "8":
return 2;
case "6":
return 2;
case "4":
return 0;
case "2":
return 0;
case "5":
return 0;
case "0":
return -1;
case "7":
return 10;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "5":
return -1;
case "4":
return -1;
case "0":
return -1;
case "7":
return -1;
case "1":
return -1;
case "6":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "6":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return 1;
case "5":
return 0;
case "4":
return 0;
case "9":
return 2;
case "2":
return -1;
case "8":
return -1;
case "1":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "5":
return 0;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "4":
return 2;
case "6":
return 0;
case "3":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "9":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return 4;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "9":
return -1;
case "7":
return -1;
case "5":
return -1;
case "1":
return -1;
case "4":
return -1;
case "3":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "8":
return -1;
case "7":
return -1;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
case "9":
return -1;
case "0":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "8":
return 2;
case "5":
return -1;
case "6":
return -1;
case "9":
return 1;
case "3":
return -1;
case "0":
return -1;
case "7":
return -1;
case "2":
return -1;
case "1":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "9":
return 2;
case "7":
return -1;
case "2":
return 1;
case "8":
return -1;
case "1":
return 1;
case "0":
return 2;
case "3":
return 0;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "9":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "8":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "9":
return 2;
case "3":
return 2;
case "4":
return 1;
case "5":
return -1;
case "0":
return 2;
case "1":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "4":
return 2;
case "5":
return -1;
case "9":
return 2;
case "0":
return 0;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "0":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 0;
case "3":
return 0;
case "4":
return 0;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return -1;
case "8":
return 2;
case "7":
return -1;
case "2":
return -1;
case "3":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "4":
return 0;
case "2":
return -1;
case "3":
return 1;
case "5":
return 0;
case "6":
return 0;
case "1":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "6":
return 2;
case "2":
return 2;
case "0":
return 2;
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return 1;
case "6":
return -1;
case "5":
return -1;
case "4":
return -1;
case "7":
return -1;
case "1":
return 1;
case "9":
return 1;
case "8":
return 1;
case "2":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "8":
return -1;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "9":
return -1;
case "7":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "3":
return -1;
case "1":
return -1;
case "2":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "0":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "1":
return -1;
case "2":
return -1;
case "0":
return -1;
case "4":
return -1;
case "5":
return -1;
case "3":
return -1;
case "9":
return -1;
case "8":
return -1;
case "7":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "1":
return 0;
case "0":
return 0;
case "9":
return 2;
case "2":
return 0;
case "3":
return 0;
case "8":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "7":
return 2;
case "9":
return 2;
case "8":
return 2;
case "6":
return 2;
case "1":
return 2;
case "0":
return 2;
case "4":
return 2;
case "3":
return 2;
case "2":
return 2;
case "5":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "1":
return 0;
case "2":
return 0;
case "3":
return 0;
case "0":
return 0;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "5":
return -1;
case "0":
return -1;
case "6":
return -1;
case "9":
return -1;
case "8":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "7":
return -1;
case "4":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "3":
return -1;
case "2":
return -1;
case "5":
return -1;
case "0":
return 1;
case "1":
return 1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return 1;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "6":
return -1;
case "1":
return 2;
case "4":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "9":
return 4;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "1":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "1":
return 2;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return 2;
case "9":
return 1;
case "4":
return -1;
case "8":
return -1;
case "5":
return -1;
case "7":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "4":
return -1;
case "0":
return 2;
case "5":
return 1;
case "7":
return -1;
case "8":
return 2;
case "2":
return 2;
case "3":
return 1;
case "9":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return -1;
default: return 3;
}
default: return 3;
}
case "4":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return 0;
case "0":
return 0;
case "9":
return 2;
case "2":
return 0;
case "3":
return 0;
case "8":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "7":
return 2;
case "9":
return 2;
case "8":
return 2;
case "6":
return 2;
case "1":
return 2;
case "0":
return 2;
case "4":
return 2;
case "3":
return 2;
case "2":
return 2;
case "5":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "1":
return 0;
case "2":
return 0;
case "3":
return 0;
case "0":
return 0;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 6;
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "4":
return -1;
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "5":
return -1;
case "4":
return 1;
case "8":
return 2;
case "9":
return 2;
case "3":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "4":
return -1;
case "7":
return -1;
case "6":
return -1;
case "5":
return -1;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "7":
return 0;
case "8":
return 0;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "0":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "3":
return 0;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return -1;
case "9":
return -1;
case "0":
return -1;
case "1":
return 1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
case "7":
return -1;
case "8":
return 2;
case "9":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "7":
return -1;
case "5":
return -1;
case "6":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "3":
return 1;
case "1":
return 2;
case "2":
return 1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "7":
return 2;
case "6":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return -1;
case "7":
return -1;
case "5":
return 0;
case "8":
return 2;
case "9":
return 2;
case "4":
return 1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "2":
return -1;
case "6":
return -1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "4":
return 0;
case "3":
return 0;
case "0":
return 2;
case "1":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "6":
return 0;
case "7":
return 0;
case "8":
return 0;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "5":
return 0;
case "0":
return -1;
case "9":
return 2;
case "1":
return -1;
case "6":
return 0;
case "4":
return 6;
case "2":
return -1;
case "8":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "9":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return -1;
case "0":
return 2;
case "3":
return 0;
case "4":
return 1;
case "2":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "7":
return -1;
case "5":
return 0;
case "4":
return 0;
case "3":
return 0;
case "6":
return 0;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "3":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return -1;
case "8":
return 2;
case "9":
return 1;
case "4":
return 1;
case "6":
return 2;
case "5":
return 0;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "4":
return 0;
case "3":
return 2;
case "9":
return 2;
case "5":
return 2;
case "2":
return -1;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "7":
return 4;
case "9":
return 2;
case "0":
return -1;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "3":
return -1;
case "2":
return -1;
case "5":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "3":
return -1;
case "2":
return 2;
case "1":
return 2;
case "0":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "7":
return 2;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
case "4":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
case "2":
return 4;
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return -1;
case "1":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "4":
return -1;
case "8":
return 2;
case "3":
return -1;
case "5":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "6":
return 0;
case "0":
return 2;
case "5":
return 0;
case "2":
return 0;
case "1":
return 2;
case "3":
return 0;
case "4":
return 0;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "4":
return -1;
case "0":
return -1;
case "1":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return 2;
case "9":
return 4;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
case "2":
return -1;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "0":
return -1;
case "2":
return 4;
case "9":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "9":
return 2;
case "8":
return 2;
case "6":
return -1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "7":
return -1;
case "0":
return 2;
case "1":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "1":
return 4;
case "9":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return 0;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return 1;
case "3":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "5":
return 1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "0":
return 2;
case "1":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 1;
case "0":
return 2;
case "1":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "0":
return -1;
case "2":
return 4;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 2;
case "6":
return -1;
case "0":
return -1;
case "2":
return -1;
case "1":
return -1;
case "9":
return 2;
case "5":
return -1;
case "7":
return -1;
case "4":
return -1;
case "3":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "0":
return -1;
case "6":
return 0;
case "2":
return 1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "9":
return -1;
case "4":
return 2;
case "8":
return -1;
case "7":
return -1;
case "0":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "4":
return -1;
case "7":
return 2;
case "9":
return 1;
case "5":
return -1;
case "1":
return -1;
case "0":
return -1;
case "3":
return -1;
case "2":
return -1;
case "6":
return 0;
case "8":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "6":
return -1;
case "1":
return 0;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
default: return 3;
}
default: return 3;
}
case "6":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "3":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return -1;
case "9":
return -1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "2":
return -1;
case "0":
return -1;
case "1":
return -1;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "2":
return 2;
case "7":
return -1;
case "1":
return 2;
case "8":
return 5;
case "9":
return 2;
case "6":
return -1;
case "4":
return -1;
case "0":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "6":
return 1;
case "5":
return -1;
case "7":
return 2;
case "9":
return -1;
case "0":
return 2;
case "4":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "1":
return -1;
case "4":
return -1;
case "5":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "1":
return 2;
case "4":
return 2;
case "2":
return 2;
case "5":
return 2;
case "3":
return 2;
case "9":
return 2;
case "8":
return 2;
case "7":
return 2;
case "0":
return -1;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "7":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "1":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "0":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "0":
return -1;
case "3":
return -1;
case "2":
return -1;
case "1":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "8":
return -1;
case "6":
return -1;
case "7":
return -1;
case "5":
return -1;
case "9":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return 2;
case "8":
return 2;
case "4":
return -1;
case "5":
return -1;
case "3":
return -1;
case "7":
return 2;
case "9":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "4":
return -1;
case "6":
return -1;
case "8":
return -1;
case "7":
return -1;
case "5":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "7":
return 0;
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "9":
return -1;
case "6":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "1":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "9":
return 2;
case "2":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 4;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "4":
return -1;
case "3":
return -1;
default: return 3;
}
default: return 3;
}
case "5":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return -1;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "1":
return 1;
case "2":
return -1;
case "9":
return 2;
case "4":
return 2;
case "0":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "4":
return -1;
case "3":
return -1;
case "7":
return 1;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "8":
return 1;
case "5":
return -1;
case "6":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "6":
return -1;
case "1":
return -1;
case "3":
return -1;
case "0":
return 2;
case "2":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "2":
return 1;
case "1":
return -1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return 1;
case "1":
return -1;
case "5":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "5":
return -1;
case "7":
return -1;
case "8":
return -1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "9":
return -1;
case "6":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "9":
return 1;
case "8":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "2":
return -1;
case "0":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "3":
return -1;
case "4":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
case "2":
return 4;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "7":
return 2;
case "5":
return -1;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "9":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "3":
return -1;
case "0":
return 2;
case "1":
return 2;
case "7":
return 2;
case "4":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "3":
return -1;
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
case "6":
return 0;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "3":
return -1;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
default: return 3;
}
default: return 3;
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return 5;
default: return 3;
}
default: return 3;
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return -1;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "6":
return 2;
case "8":
return -1;
case "5":
return -1;
case "4":
return 4;
case "2":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "4":
return 0;
case "2":
return -1;
case "1":
return -1;
case "5":
return 1;
case "3":
return 1;
case "6":
return 1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return 2;
case "4":
return 2;
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "5":
return -1;
case "4":
return -1;
case "7":
return 0;
case "0":
return -1;
case "1":
return -1;
case "6":
return -1;
case "9":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "2":
return -1;
case "1":
return -1;
case "4":
return 0;
case "3":
return -1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "4":
return 2;
case "7":
return 2;
case "0":
return 2;
case "5":
return 0;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "6":
return 2;
case "9":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return -1;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "6":
return 0;
case "0":
return 2;
case "9":
return -1;
case "7":
return 0;
case "8":
return 2;
case "5":
return -1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "8":
return 2;
case "6":
return 2;
case "7":
return 0;
case "9":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "5":
return 0;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "2":
return 2;
case "7":
return 2;
case "3":
return 0;
case "4":
return 0;
case "5":
return 0;
case "6":
return 0;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "3":
return -1;
case "0":
return -1;
case "1":
return 0;
case "4":
return -1;
case "2":
return 0;
case "9":
return 0;
case "8":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "3":
return -1;
case "2":
return -1;
case "1":
return -1;
case "6":
return 4;
case "8":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
default: return 3;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
case "6":
return -1;
case "7":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "0":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return 2;
case "1":
return 2;
case "8":
return 2;
case "9":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "2":
return 1;
case "1":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "5":
return 1;
case "0":
return -1;
case "8":
return 4;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
case "4":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return 1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "2":
return 4;
case "4":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "8":
return -1;
case "7":
return -1;
case "6":
return -1;
case "9":
return 2;
case "5":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "1":
return 7;
case "2":
return 6;
case "3":
return 5;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return -1;
case "1":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "2":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "6":
return 2;
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "7":
return 1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "2":
return -1;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "3":
return -1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "3":
return 0;
case "2":
return 0;
case "1":
return 0;
case "7":
return 1;
case "5":
return 1;
case "8":
return 1;
case "6":
return 1;
case "4":
return 0;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "0":
return 2;
case "5":
return -1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "5":
return 4;
case "7":
return 2;
case "2":
return 7;
case "3":
return 6;
case "4":
return 5;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "0":
return -1;
case "9":
return -1;
case "4":
return -1;
case "2":
return -1;
case "1":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return -1;
case "7":
return -1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
case "2":
return -1;
case "1":
return -1;
case "4":
return 1;
case "9":
return 1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "9":
return -1;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "6":
return -1;
case "5":
return -1;
case "9":
return 1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "2":
return 2;
case "4":
return -1;
case "5":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "1":
return 1;
case "0":
return -1;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "1":
return 1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 2;
case "7":
return 1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "8":
return 1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "4":
return 2;
case "1":
return -1;
case "2":
return -1;
case "6":
return -1;
case "9":
return 2;
case "8":
return 2;
case "0":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "0":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "9":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "9":
return 1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "8":
return -1;
case "7":
return -1;
case "9":
return -1;
case "4":
return 0;
case "0":
return 2;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return -1;
case "0":
return -1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "7":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "8":
return -1;
case "9":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return 2;
case "1":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return -1;
case "5":
return -1;
case "3":
return 2;
case "6":
return 4;
case "0":
return -1;
case "9":
return -1;
case "2":
return 2;
case "8":
return 2;
case "4":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return -1;
case "4":
return 2;
case "2":
return 2;
case "1":
return 2;
case "5":
return -1;
case "3":
return 1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "2":
return 2;
case "3":
return 2;
case "9":
return -1;
case "5":
return -1;
case "1":
return 2;
case "6":
return 2;
case "0":
return 4;
case "8":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "5":
return -1;
case "0":
return -1;
case "9":
return -1;
case "3":
return -1;
case "4":
return -1;
case "1":
return -1;
case "2":
return -1;
case "6":
return -1;
case "8":
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
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return 2;
case "7":
return -1;
case "6":
return 2;
case "8":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "3":
return 2;
case "0":
return 1;
case "4":
return 2;
case "6":
return 2;
case "7":
return 2;
case "2":
return 1;
case "1":
return 1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "7":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "0":
return 2;
case "3":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "5":
return 2;
case "3":
return -1;
case "2":
return -1;
case "8":
return -1;
case "0":
return -1;
case "6":
return 2;
case "1":
return -1;
case "4":
return -1;
case "9":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "0":
return -1;
case "6":
return 2;
case "8":
return -1;
case "1":
return -1;
case "2":
return -1;
case "5":
return -1;
case "7":
return -1;
case "9":
return 0;
case "3":
return -1;
case "4":
return -1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 2;
case "2":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "7":
return 2;
case "1":
return -1;
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "6":
return -1;
case "2":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "9":
return 4;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return -1;
case "8":
return -1;
case "4":
return -1;
case "5":
return -1;
case "1":
return 2;
case "6":
return -1;
case "2":
return 2;
case "7":
return -1;
default: return 3;
}
default: return 3;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "7":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
default: return 3;
}
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "9":
return 2;
case "6":
return 0;
case "4":
return 1;
case "7":
return 1;
case "2":
return -1;
case "3":
return 1;
case "1":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "7":
return 2;
case "4":
return 2;
case "8":
return -1;
case "0":
return -1;
case "6":
return -1;
case "9":
return 0;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "7":
return 2;
case "9":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return 0;
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
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
if($cardID !== null && strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "10,000 Year Reunion";
case "4":
return "Astral Etchings";
case "5":
return "Astral Etchings";
case "6":
return "Astral Etchings";
case "0":
return "Cosmo, Scroll of Ancestral Tapestry";
case "7":
return "Essence of Ancestry: Body";
case "9":
return "Essence of Ancestry: Mind";
case "8":
return "Essence of Ancestry: Soul";
case "2":
return "Rage Specter";
case "3":
return "Restless Coalescence";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Art of Desire: Body";
case "8":
return "Art of Desire: Mind";
case "7":
return "Art of Desire: Soul";
case "3":
return "Bonds of Agony";
case "9":
return "Bonds of Attraction";
case "5":
return "Just a Nick";
case "4":
return "Persuasive Prognosis";
case "0":
return "Rising Sun, Setting Moon";
case "1":
return "Stir the Pot";
case "2":
return "The Grain that Tips the Scale";
default: return "";
}
case "6":
switch($cardID[5]) {
case "4":
return "Aspect of Tiger: Body";
case "6":
return "Aspect of Tiger: Mind";
case "5":
return "Aspect of Tiger: Soul";
case "1":
return "Chase the Tail";
case "0":
return "Mask of Wizened Whiskers";
case "2":
return "Maul";
case "7":
return "Qi Unleashed";
case "8":
return "Qi Unleashed";
case "9":
return "Qi Unleashed";
case "3":
return "Territorial Domain";
default: return "";
}
case "7":
switch($cardID[5]) {
case "3":
return "Biting Breeze";
case "4":
return "Biting Breeze";
case "5":
return "Biting Breeze";
case "0":
return "Blessing of Qi";
case "1":
return "Blessing of Qi";
case "2":
return "Blessing of Qi";
case "6":
return "Breed Anger";
case "7":
return "Breed Anger";
case "8":
return "Breed Anger";
case "9":
return "Flex Claws";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Blanch";
case "5":
return "Blanch";
case "6":
return "Blanch";
case "7":
return "Emissary of Moon";
case "8":
return "Emissary of Tides";
case "9":
return "Emissary of Wind";
case "3":
return "Prismatic Leyline";
case "1":
return "Rowdy Locals";
case "0":
return "Stonewall Gauntlet";
case "2":
return "The Weakest Link";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Bonds of Attraction";
case "1":
return "Bonds of Attraction";
case "5":
return "Bonds of Memory";
case "6":
return "Bonds of Memory";
case "7":
return "Bonds of Memory";
case "8":
return "Desires of Flesh";
case "9":
return "Desires of Flesh";
case "2":
return "Double Trouble";
case "3":
return "Double Trouble";
case "4":
return "Double Trouble";
default: return "";
}
case "8":
switch($cardID[5]) {
case "8":
return "Crouching Tiger";
case "0":
return "Flex Claws";
case "1":
return "Flex Claws";
case "2":
return "Pouncing Qi";
case "3":
return "Pouncing Qi";
case "4":
return "Pouncing Qi";
case "5":
return "Untamed";
case "6":
return "Untamed";
case "7":
return "Untamed";
case "9":
return "Zen State";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Desires of Flesh";
case "1":
return "Impulsive Desire";
case "2":
return "Impulsive Desire";
case "3":
return "Impulsive Desire";
case "4":
return "Mind's Desire";
case "5":
return "Mind's Desire";
case "6":
return "Mind's Desire";
case "7":
return "Pick to Pieces";
case "8":
return "Pick to Pieces";
case "9":
return "Pick to Pieces";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Haunting Specter";
case "1":
return "Haunting Specter";
case "2":
return "Haunting Specter";
case "3":
return "Sigil of Solitude";
case "4":
return "Sigil of Solitude";
case "5":
return "Sigil of Solitude";
case "6":
return "Single Minded Determination";
case "7":
return "Single Minded Determination";
case "8":
return "Single Minded Determination";
case "9":
return "Solitary Companion";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Solitary Companion";
case "1":
return "Solitary Companion";
case "2":
return "Spectral Manifestations";
case "3":
return "Spectral Manifestations";
case "4":
return "Spectral Manifestations";
case "8":
return "Spectral Shield";
case "9":
return "Tiger Taming Khakkara";
case "5":
return "Vengeful Apparition";
case "6":
return "Vengeful Apparition";
case "7":
return "Vengeful Apparition";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "5":
return "A Drop in the Ocean";
case "2":
return "First Tenet of Chi: Moon";
case "3":
return "First Tenet of Chi: Tide";
case "4":
return "First Tenet of Chi: Wind";
case "6":
return "Homage to Ancestors";
case "7":
return "Pass Over";
case "8":
return "Path Well Traveled";
case "9":
return "Preserve Tradition";
case "0":
return "Tidal Surge";
case "1":
return "Wash Away";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Aqua Laps";
case "5":
return "Attune with Cosmic Vibrations";
case "6":
return "Cosmic Awakening";
case "9":
return "Dense Blue Mist";
case "7":
return "Levels of Enlightenment";
case "2":
return "Skybody Keikoi";
case "1":
return "Skycrest Keikoi";
case "3":
return "Skyhold Keikoi";
case "4":
return "Skywalker Keikoi";
case "8":
return "Unravel Aggression";
default: return "";
}
case "6":
switch($cardID[5]) {
case "7":
return "Aqua Seeing Shell";
case "0":
return "Harmony of the Hunt";
case "1":
return "Harmony of the Hunt";
case "2":
return "Harmony of the Hunt";
case "8":
return "Koi Blessed Kimono";
case "3":
return "Tiger Form Incantation";
case "4":
return "Tiger Form Incantation";
case "5":
return "Tiger Form Incantation";
case "6":
return "Traverse the Universe";
case "9":
return "Waves of Aqua Marine";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Arousing Wave";
case "3":
return "Beckoning Mistblade";
case "8":
return "Gorgon's Gaze";
case "5":
return "Heirloom of Snake Hide";
case "4":
return "Mask of Recurring Nightmares";
case "0":
return "Mistcloak Gully";
case "2":
return "Nuu";
case "1":
return "Nuu, Alluring Desire";
case "9":
return "Siren's Call";
case "7":
return "Undertow Stilettos";
default: return "";
}
case "8":
switch($cardID[5]) {
case "6":
return "Big Blue Sky";
case "4":
return "Deep Blue Sea";
case "7":
return "Droplet";
case "0":
return "Orihon of Mystic Tenets";
case "8":
return "Rising Tide";
case "1":
return "Second Tenet of Chi: Moon";
case "2":
return "Second Tenet of Chi: Tide";
case "3":
return "Second Tenet of Chi: Wind";
case "9":
return "Spillover";
case "5":
return "Wide Blue Yonder";
default: return "";
}
case "5":
switch($cardID[5]) {
case "7":
return "Companion of the Claw";
case "8":
return "Companion of the Claw";
case "9":
return "Companion of the Claw";
case "3":
return "Sacred Art: Jade Tiger Domain";
case "2":
return "Shifting Winds of the Mystic Beast";
case "0":
return "Stride of Reprisal";
case "1":
return "Tooth and Claw";
case "4":
return "Wind Chakra";
case "5":
return "Wind Chakra";
case "6":
return "Wind Chakra";
default: return "";
}
case "2":
switch($cardID[5]) {
case "6":
return "Enigma";
case "5":
return "Enigma, Ledger of Ancestry";
case "3":
return "Fang Strike";
case "8":
return "Heirloom of Rabbit Hide";
case "7":
return "Meridian Pathway";
case "4":
return "Slither";
case "9":
return "Truths Retold";
case "0":
return "Venomous Bite";
case "1":
return "Venomous Bite";
case "2":
return "Venomous Bite";
default: return "";
}
case "3":
switch($cardID[5]) {
case "7":
return "Haze Shelter";
case "8":
return "Haze Shelter";
case "9":
return "Haze Shelter";
case "1":
return "Manifestation of Miragai";
case "4":
return "Moon Chakra";
case "5":
return "Moon Chakra";
case "6":
return "Moon Chakra";
case "2":
return "Sacred Art: Immortal Lunar Shrine";
case "3":
return "Three Visits";
case "0":
return "Uphold Tradition";
default: return "";
}
case "4":
switch($cardID[5]) {
case "9":
return "Heirloom of Tiger Hide";
case "8":
return "Twelve Petal Kāṣāya";
case "0":
return "Waning Vengeance";
case "1":
return "Waning Vengeance";
case "2":
return "Waning Vengeance";
case "3":
return "Waxing Specter";
case "4":
return "Waxing Specter";
case "5":
return "Waxing Specter";
case "7":
return "Zen";
case "6":
return "Zen, Tamer of Purpose";
default: return "";
}
case "1":
switch($cardID[5]) {
case "4":
return "Hiss";
case "5":
return "Hiss";
case "6":
return "Hiss";
case "7":
return "Intimate Inducement";
case "8":
return "Intimate Inducement";
case "9":
return "Intimate Inducement";
case "0":
return "Sacred Art: Undercurrent Desires";
case "1":
return "Tide Chakra";
case "2":
return "Tide Chakra";
case "3":
return "Tide Chakra";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "Battlefront Bastion";
case "4":
return "Battlefront Bastion";
case "5":
return "Battlefront Bastion";
case "6":
return "Fact-Finding Mission";
case "7":
return "Fact-Finding Mission";
case "8":
return "Fact-Finding Mission";
case "0":
return "Gravekeeping";
case "1":
return "Gravekeeping";
case "2":
return "Gravekeeping";
case "9":
return "Nimble Strike";
default: return "";
}
case "2":
switch($cardID[5]) {
case "4":
return "Cracked Bauble";
case "0":
return "Evasive Leap";
case "9":
return "Evo Heartdrive";
case "8":
return "Evo Recall";
case "1":
return "Nimblism";
case "2":
return "Nimblism";
case "3":
return "Nimblism";
case "7":
return "Supercell";
case "5":
return "Visit Goldmane Estate";
case "6":
return "Visit the Golden Anvil";
default: return "";
}
case "3":
switch($cardID[5]) {
case "5":
return "Dust from Stillwater Shrine";
case "7":
return "Eloquent Eulogy";
case "8":
return "Enigma, New Moon";
case "0":
return "Evo Shortcircuit";
case "1":
return "Evo Speedslip";
case "4":
return "Kindle";
case "2":
return "Longdraw Half-glove";
case "3":
return "Murky Water";
case "6":
return "Shadowrealm Horror";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Evasive Leap";
case "9":
return "Evasive Leap";
case "0":
return "Nimble Strike";
case "1":
return "Nimble Strike";
case "2":
return "Water the Seeds";
case "3":
return "Water the Seeds";
case "4":
return "Water the Seeds";
case "5":
return "Wounding Blow";
case "6":
return "Wounding Blow";
case "7":
return "Wounding Blow";
default: return "";
}
default: return "";
}
case "6":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return "Evo Heartdrive";
case "8":
return "Evo Recall";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Evo Shortcircuit";
case "1":
return "Evo Speedslip";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Inner Chi";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Inner Chi";
default: return "";
}
case "3":
switch($cardID[5]) {
case "2":
return "Inner Chi";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Inner Chi";
default: return "";
}
case "9":
switch($cardID[5]) {
case "5":
return "Inner Chi";
case "6":
return "Inner Chi";
case "7":
return "Inner Chi";
case "8":
return "Inner Chi";
case "9":
return "Inner Chi";
default: return "";
}
default: return "";
}
case "5":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Inner Chi";
case "1":
return "Inner Chi";
case "2":
return "Inner Chi";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "Adrenaline Rush";
case "4":
return "Adrenaline Rush";
case "5":
return "Adrenaline Rush";
case "6":
return "Belittle";
case "7":
return "Belittle";
case "8":
return "Belittle";
case "9":
return "Brandish";
case "0":
return "Captain's Call";
case "1":
return "Captain's Call";
case "2":
return "Captain's Call";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Aether Ironweave";
case "5":
return "Arcanic Crackle";
case "6":
return "Arcanic Crackle";
case "7":
return "Arcanic Crackle";
case "8":
return "Blood Drop Brocade";
case "1":
return "Sonata Arcanix";
case "9":
return "Stubby Hammerers";
case "2":
return "Vexing Malice";
case "3":
return "Vexing Malice";
case "4":
return "Vexing Malice";
default: return "";
}
case "1":
switch($cardID[5]) {
case "9":
return "Blasmophet, the Soul Harvester";
case "5":
return "Blood Tribute";
case "6":
return "Blood Tribute";
case "7":
return "Blood Tribute";
case "8":
return "Eclipse Existence";
case "2":
return "Spew Shadow";
case "3":
return "Spew Shadow";
case "4":
return "Spew Shadow";
case "0":
return "Void Wraith";
case "1":
return "Void Wraith";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Brandish";
case "1":
return "Brandish";
case "2":
return "Frontline Scout";
case "3":
return "Frontline Scout";
case "4":
return "Frontline Scout";
case "5":
return "Overload";
case "6":
return "Overload";
case "7":
return "Overload";
case "8":
return "Pound for Pound";
case "9":
return "Pound for Pound";
default: return "";
}
case "2":
switch($cardID[5]) {
case "9":
return "Dread Scythe";
case "3":
return "Pulping";
case "4":
return "Pulping";
case "5":
return "Pulping";
case "1":
return "Ravenous Meataxe";
case "6":
return "Smash with Big Tree";
case "7":
return "Smash with Big Tree";
case "8":
return "Smash with Big Tree";
case "2":
return "Tear Limb from Limb";
case "0":
return "Ursur, the Soul Reaper";
default: return "";
}
case "4":
switch($cardID[5]) {
case "5":
return "Exude Confidence";
case "3":
return "Ironhide Gauntlet";
case "1":
return "Ironhide Helm";
case "4":
return "Ironhide Legs";
case "2":
return "Ironhide Plate";
case "6":
return "Nourishing Emptiness";
case "8":
return "Out Muscle";
case "9":
return "Out Muscle";
case "7":
return "Rouse the Ancients";
case "0":
return "Time Skippers";
default: return "";
}
case "0":
switch($cardID[5]) {
case "3":
return "Ghostly Visit";
case "4":
return "Ghostly Visit";
case "5":
return "Ghostly Visit";
case "0":
return "Howl from Beyond";
case "1":
return "Howl from Beyond";
case "2":
return "Howl from Beyond";
case "6":
return "Lunartide Plunderer";
case "7":
return "Lunartide Plunderer";
case "8":
return "Lunartide Plunderer";
case "9":
return "Void Wraith";
default: return "";
}
case "9":
switch($cardID[5]) {
case "6":
return "Minnowism";
case "7":
return "Minnowism";
case "8":
return "Minnowism";
case "9":
return "Warmonger's Recital";
case "0":
return "Yinti Yanti";
case "1":
return "Yinti Yanti";
case "2":
return "Yinti Yanti";
case "3":
return "Zealous Belting";
case "4":
return "Zealous Belting";
case "5":
return "Zealous Belting";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Out Muscle";
case "7":
return "Rise Above";
case "8":
return "Rise Above";
case "9":
return "Rise Above";
case "1":
return "Seek Horizon";
case "2":
return "Seek Horizon";
case "3":
return "Seek Horizon";
case "4":
return "Tremor of íArathael";
case "5":
return "Tremor of íArathael";
case "6":
return "Tremor of íArathael";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Pound for Pound";
case "1":
return "Rally the Rearguard";
case "2":
return "Rally the Rearguard";
case "3":
return "Rally the Rearguard";
case "4":
return "Stony Woottonhog";
case "5":
return "Stony Woottonhog";
case "6":
return "Stony Woottonhog";
case "7":
return "Surging Militia";
case "8":
return "Surging Militia";
case "9":
return "Surging Militia";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "Arc Light Sentinel";
case "6":
return "Genesis";
case "0":
return "Great Library of Solana";
case "4":
return "Herald of Erudition";
case "7":
return "Herald of Judgment";
case "8":
return "Herald of Triumph";
case "9":
return "Herald of Triumph";
case "3":
return "Luminaris";
case "2":
return "Prism";
case "1":
return "Prism, Sculptor of Arc Light";
default: return "";
}
case "3":
switch($cardID[5]) {
case "6":
return "Battlefield Blitz";
case "7":
return "Battlefield Blitz";
case "8":
return "Battlefield Blitz";
case "3":
return "Beacon of Victory";
case "2":
return "Bolting Blade";
case "0":
return "Boltyn";
case "4":
return "Lumina Ascension";
case "1":
return "Raydn, Duskbane";
case "5":
return "V of the Vanguard";
case "9":
return "Valiant Thrust";
default: return "";
}
case "8":
switch($cardID[5]) {
case "4":
return "Blinding Beam";
case "5":
return "Blinding Beam";
case "6":
return "Blinding Beam";
case "8":
return "Iris of Reality";
case "9":
return "Phantasmal Footsteps";
case "7":
return "Ray of Hope";
case "0":
return "Rising Solartide";
case "1":
return "Seek Enlightenment";
case "2":
return "Seek Enlightenment";
case "3":
return "Seek Enlightenment";
default: return "";
}
case "4":
switch($cardID[5]) {
case "2":
return "Bolt of Courage";
case "3":
return "Bolt of Courage";
case "4":
return "Bolt of Courage";
case "5":
return "Cross the Line";
case "6":
return "Cross the Line";
case "7":
return "Cross the Line";
case "8":
return "Engulfing Light";
case "9":
return "Engulfing Light";
case "0":
return "Valiant Thrust";
case "1":
return "Valiant Thrust";
default: return "";
}
case "6":
switch($cardID[5]) {
case "2":
return "Celestial Cataclysm";
case "9":
return "Glisten";
case "1":
return "Halo of Illumination";
case "6":
return "Invigorating Light";
case "7":
return "Invigorating Light";
case "8":
return "Invigorating Light";
case "4":
return "Soul Food";
case "3":
return "Soul Shield";
case "5":
return "Tome of Divinity";
case "0":
return "Vestige of Sol";
default: return "";
}
case "5":
switch($cardID[5]) {
case "7":
return "Courageous Steelhand";
case "8":
return "Courageous Steelhand";
case "9":
return "Courageous Steelhand";
case "0":
return "Engulfing Light";
case "1":
return "Express Lightning";
case "2":
return "Express Lightning";
case "3":
return "Express Lightning";
case "4":
return "Take Flight";
case "5":
return "Take Flight";
case "6":
return "Take Flight";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Dream Weavers";
case "8":
return "Enigma Chimera";
case "9":
return "Enigma Chimera";
case "1":
return "Phantasmaclasm";
case "5":
return "Phantasmify";
case "6":
return "Phantasmify";
case "7":
return "Phantasmify";
case "2":
return "Prismatic Shield";
case "3":
return "Prismatic Shield";
case "4":
return "Prismatic Shield";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Glisten";
case "1":
return "Glisten";
case "2":
return "Illuminate";
case "3":
return "Illuminate";
case "4":
return "Illuminate";
case "5":
return "Impenetrable Belief";
case "6":
return "Impenetrable Belief";
case "7":
return "Impenetrable Belief";
case "8":
return "Rising Solartide";
case "9":
return "Rising Solartide";
default: return "";
}
case "1":
switch($cardID[5]) {
case "4":
return "Herald of Protection";
case "5":
return "Herald of Protection";
case "6":
return "Herald of Protection";
case "7":
return "Herald of Ravages";
case "8":
return "Herald of Ravages";
case "9":
return "Herald of Ravages";
case "0":
return "Herald of Triumph";
case "2":
return "Merciful Retribution";
case "3":
return "Ode to Wrath";
case "1":
return "Parable of Humility";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Herald of Rebirth";
case "1":
return "Herald of Rebirth";
case "2":
return "Herald of Rebirth";
case "3":
return "Herald of Tenacity";
case "4":
return "Herald of Tenacity";
case "5":
return "Herald of Tenacity";
case "9":
return "Ser Boltyn, Breaker of Dawn";
case "6":
return "Wartune Herald";
case "7":
return "Wartune Herald";
case "8":
return "Wartune Herald";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return "Boneyard Marauder";
case "6":
return "Boneyard Marauder";
case "7":
return "Boneyard Marauder";
case "2":
return "Convulsions from the Bellows of Hell";
case "3":
return "Convulsions from the Bellows of Hell";
case "4":
return "Convulsions from the Bellows of Hell";
case "8":
return "Deadwood Rumbler";
case "9":
return "Deadwood Rumbler";
case "0":
return "Writhing Beast Hulk";
case "1":
return "Writhing Beast Hulk";
default: return "";
}
case "6":
switch($cardID[5]) {
case "8":
return "Bounding Demigon";
case "9":
return "Bounding Demigon";
case "2":
return "Dimenxxional Gateway";
case "3":
return "Dimenxxional Gateway";
case "4":
return "Dimenxxional Gateway";
case "5":
return "Seeping Shadows";
case "6":
return "Seeping Shadows";
case "7":
return "Seeping Shadows";
case "0":
return "Unhallowed Rites";
case "1":
return "Unhallowed Rites";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Bounding Demigon";
case "1":
return "Piercing Shadow Vise";
case "2":
return "Piercing Shadow Vise";
case "3":
return "Piercing Shadow Vise";
case "4":
return "Rift Bind";
case "5":
return "Rift Bind";
case "6":
return "Rift Bind";
case "7":
return "Rifted Torment";
case "8":
return "Rifted Torment";
case "9":
return "Rifted Torment";
default: return "";
}
case "8":
switch($cardID[5]) {
case "7":
return "Carrion Husk";
case "9":
return "Doomsday";
case "8":
return "Ebon Fold";
case "0":
return "Rip Through Reality";
case "1":
return "Rip Through Reality";
case "2":
return "Rip Through Reality";
case "3":
return "Seeds of Agony";
case "4":
return "Seeds of Agony";
case "5":
return "Seeds of Agony";
case "6":
return "Soul Shackle";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Chane";
case "3":
return "Chane, Bound by Shadow";
case "7":
return "Dimenxxional Crossroads";
case "5":
return "Galaxxi Black";
case "8":
return "Invert Existence";
case "6":
return "Shadow of Ursur";
case "9":
return "Unhallowed Rites";
case "0":
return "Unworldly Bellow";
case "1":
return "Unworldly Bellow";
case "2":
return "Unworldly Bellow";
default: return "";
}
case "9":
switch($cardID[5]) {
case "5":
return "Consuming Aftermath";
case "6":
return "Consuming Aftermath";
case "7":
return "Consuming Aftermath";
case "0":
return "Eclipse";
case "2":
return "Guardian of the Shadowrealm";
case "1":
return "Mutated Mass";
case "3":
return "Shadow Puppetry";
case "8":
return "Soul Harvest";
case "9":
return "Soul Reaping";
case "4":
return "Tome of Torment";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Deadwood Rumbler";
case "1":
return "Dread Screamer";
case "2":
return "Dread Screamer";
case "3":
return "Dread Screamer";
case "4":
return "Graveling Growl";
case "5":
return "Graveling Growl";
case "6":
return "Graveling Growl";
case "7":
return "Hungering Slaughterbeast";
case "8":
return "Hungering Slaughterbeast";
case "9":
return "Hungering Slaughterbeast";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Deep Rooted Evil";
case "6":
return "Endless Maw";
case "7":
return "Endless Maw";
case "8":
return "Endless Maw";
case "1":
return "Hexagore, the Death Hydra";
case "2":
return "Hooves of the Shadowbeast";
case "0":
return "Levia";
case "4":
return "Mark of the Beast";
case "5":
return "Shadow of Blasmophet";
case "9":
return "Writhing Beast Hulk";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Dusk Path Pilgrimage";
case "1":
return "Dusk Path Pilgrimage";
case "2":
return "Dusk Path Pilgrimage";
case "9":
return "Levia, Shadowborn Abomination";
case "3":
return "Plow Through";
case "4":
return "Plow Through";
case "5":
return "Plow Through";
case "6":
return "Second Swing";
case "7":
return "Second Swing";
case "8":
return "Second Swing";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Enigma Chimera";
case "8":
return "Gallantry Gold";
case "5":
return "Hatchet of Body";
case "6":
return "Hatchet of Mind";
case "1":
return "Spears of Surreality";
case "2":
return "Spears of Surreality";
case "3":
return "Spears of Surreality";
case "4":
return "Spectral Shield";
case "9":
return "Spill Blood";
case "7":
return "Valiant Dynamo";
default: return "";
}
default: return "";
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "Cracked Bauble";
case "3":
return "Memorial Ground";
case "4":
return "Memorial Ground";
case "5":
return "Memorial Ground";
case "2":
return "Talisman of Dousing";
case "0":
return "Warmonger's Recital";
case "1":
return "Warmonger's Recital";
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "Absorb in Aether";
case "4":
return "Absorb in Aether";
case "5":
return "Absorb in Aether";
case "6":
return "Aether Spindle";
case "7":
return "Aether Spindle";
case "8":
return "Aether Spindle";
case "0":
return "Forked Lightning";
case "1":
return "Lesson in Lava";
case "9":
return "Stir the Aetherwinds";
case "2":
return "Tome of Aetherwind";
default: return "";
}
case "3":
switch($cardID[5]) {
case "2":
return "Aether Flare";
case "3":
return "Aether Flare";
case "4":
return "Aether Flare";
case "5":
return "Index";
case "6":
return "Index";
case "7":
return "Index";
case "8":
return "Reverberate";
case "9":
return "Reverberate";
case "0":
return "Stir the Aetherwinds";
case "1":
return "Stir the Aetherwinds";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Arcanite Skullcap";
case "3":
return "Bracers of Belief";
case "9":
return "Command and Conquer";
case "4":
return "Mage Master Boots";
case "8":
return "Nullrune Boots";
case "7":
return "Nullrune Gloves";
case "5":
return "Nullrune Hood";
case "6":
return "Nullrune Robe";
case "1":
return "Talismanic Lens";
case "2":
return "Vest of the First Fist";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Art of War";
case "2":
return "Chains of Eminence";
case "7":
return "Enchanting Melody";
case "8":
return "Enchanting Melody";
case "9":
return "Enchanting Melody";
case "4":
return "Life for a Life";
case "5":
return "Life for a Life";
case "6":
return "Life for a Life";
case "1":
return "Pursuit of Knowledge";
case "3":
return "Rusted Relic";
default: return "";
}
case "7":
switch($cardID[5]) {
case "6":
return "Back Alley Breakline";
case "7":
return "Back Alley Breakline";
case "8":
return "Back Alley Breakline";
case "9":
return "Cadaverous Contraband";
case "3":
return "Eirina's Prayer";
case "4":
return "Eirina's Prayer";
case "5":
return "Eirina's Prayer";
case "0":
return "Plunder Run";
case "1":
return "Plunder Run";
case "2":
return "Plunder Run";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Blazing Aether";
case "5":
return "Crucible of Aetherweave";
case "4":
return "Kano";
case "3":
return "Kano, Dracai of Aether";
case "0":
return "Read the Runes";
case "1":
return "Read the Runes";
case "7":
return "Robe of Rapture";
case "2":
return "Runechant";
case "9":
return "Sonic Boom";
case "6":
return "Storm Striders";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Bloodspill Invocation";
case "7":
return "Bloodspill Invocation";
case "8":
return "Bloodspill Invocation";
case "9":
return "Read the Runes";
case "0":
return "Rune Flash";
case "1":
return "Rune Flash";
case "2":
return "Rune Flash";
case "3":
return "Spellblade Strike";
case "4":
return "Spellblade Strike";
case "5":
return "Spellblade Strike";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Cadaverous Contraband";
case "1":
return "Cadaverous Contraband";
case "2":
return "Fervent Forerunner";
case "3":
return "Fervent Forerunner";
case "4":
return "Fervent Forerunner";
case "5":
return "Moon Wish";
case "6":
return "Moon Wish";
case "7":
return "Moon Wish";
case "8":
return "Push the Point";
case "9":
return "Push the Point";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Push the Point";
case "1":
return "Ravenous Rabble";
case "2":
return "Ravenous Rabble";
case "3":
return "Ravenous Rabble";
case "4":
return "Rifting";
case "5":
return "Rifting";
case "6":
return "Rifting";
case "7":
return "Vigor Rush";
case "8":
return "Vigor Rush";
case "9":
return "Vigor Rush";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Reverberate";
case "1":
return "Scalding Rain";
case "2":
return "Scalding Rain";
case "3":
return "Scalding Rain";
case "7":
return "Voltic Bolt";
case "8":
return "Voltic Bolt";
case "9":
return "Voltic Bolt";
case "4":
return "Zap";
case "5":
return "Zap";
case "6":
return "Zap";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "Achilles Accelerator";
case "2":
return "Dash";
case "1":
return "Dash, Inventor Extraordinaire";
case "0":
return "Eye of Ophidia";
case "6":
return "High Octane";
case "8":
return "Maximum Velocity";
case "9":
return "Spark of Genius";
case "7":
return "Teklo Core";
case "4":
return "Teklo Foundry Heart";
case "3":
return "Teklo Plasma Pistol";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Aether Sink";
case "8":
return "Cognition Nodes";
case "9":
return "Convection Amplifier";
case "0":
return "Induction Chamber";
case "1":
return "Pedal to the Metal";
case "2":
return "Pedal to the Metal";
case "3":
return "Pedal to the Metal";
case "4":
return "Pour the Mold";
case "5":
return "Pour the Mold";
case "6":
return "Pour the Mold";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Amplify the Arknight";
case "5":
return "Amplify the Arknight";
case "6":
return "Amplify the Arknight";
case "7":
return "Drawn to the Dark Dimension";
case "8":
return "Drawn to the Dark Dimension";
case "9":
return "Drawn to the Dark Dimension";
case "1":
return "Oath of the Arknight";
case "2":
return "Oath of the Arknight";
case "3":
return "Oath of the Arknight";
case "0":
return "Reduce to Runechant";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Arknight Ascendancy";
case "3":
return "Become the Arknight";
case "1":
return "Mordred Tide";
case "2":
return "Ninth Blade of the Blood Oath";
case "8":
return "Reduce to Runechant";
case "9":
return "Reduce to Runechant";
case "5":
return "Spellblade Assault";
case "6":
return "Spellblade Assault";
case "7":
return "Spellblade Assault";
case "4":
return "Tome of the Arknight";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Azalea";
case "8":
return "Azalea, Ace in the Hole";
case "5":
return "Dissipation Shield";
case "6":
return "Hyper Driver";
case "2":
return "Locked and Loaded";
case "3":
return "Locked and Loaded";
case "4":
return "Locked and Loaded";
case "7":
return "Optekal Monocle";
case "0":
return "Zipper Hit";
case "1":
return "Zipper Hit";
default: return "";
}
case "4":
switch($cardID[5]) {
case "2":
return "Bull's Eye Bracers";
case "0":
return "Death Dealer";
case "5":
return "Endless Arrow";
case "6":
return "Nock the Deathwhistle";
case "7":
return "Rapid Fire";
case "3":
return "Red in the Ledger";
case "1":
return "Skullbone Crosswrap";
case "8":
return "Take Cover";
case "9":
return "Take Cover";
case "4":
return "Three of a Kind";
default: return "";
}
case "7":
switch($cardID[5]) {
case "9":
return "Crown of Dichotomy";
case "8":
return "Grasp of the Arknight";
case "7":
return "Nebula Blade";
case "0":
return "Searing Shot";
case "1":
return "Searing Shot";
case "2":
return "Sic 'Em Shot";
case "3":
return "Sic 'Em Shot";
case "4":
return "Sic 'Em Shot";
case "6":
return "Viserai";
case "5":
return "Viserai, Rune Blood";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Hamstring Shot";
case "1":
return "Hamstring Shot";
case "2":
return "Hamstring Shot";
case "3":
return "Ridge Rider Shot";
case "4":
return "Ridge Rider Shot";
case "5":
return "Ridge Rider Shot";
case "6":
return "Salvage Shot";
case "7":
return "Salvage Shot";
case "8":
return "Salvage Shot";
case "9":
return "Searing Shot";
default: return "";
}
case "5":
switch($cardID[5]) {
case "7":
return "Head Shot";
case "8":
return "Head Shot";
case "9":
return "Head Shot";
case "1":
return "Silver the Tip";
case "2":
return "Silver the Tip";
case "3":
return "Silver the Tip";
case "4":
return "Take Aim";
case "5":
return "Take Aim";
case "6":
return "Take Aim";
case "0":
return "Take Cover";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Over Loop";
case "1":
return "Over Loop";
case "2":
return "Over Loop";
case "3":
return "Throttle";
case "4":
return "Throttle";
case "5":
return "Throttle";
case "6":
return "Zero to Sixty";
case "7":
return "Zero to Sixty";
case "8":
return "Zero to Sixty";
case "9":
return "Zipper Hit";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "Come to Fight";
case "4":
return "Come to Fight";
case "5":
return "Come to Fight";
case "0":
return "Fate Foreseen";
case "1":
return "Fate Foreseen";
case "2":
return "Fate Foreseen";
case "6":
return "Force Sight";
case "7":
return "Force Sight";
case "8":
return "Force Sight";
case "9":
return "Lead the Charge";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Cracked Bauble";
case "0":
return "Lead the Charge";
case "1":
return "Lead the Charge";
case "2":
return "Sun Kiss";
case "3":
return "Sun Kiss";
case "4":
return "Sun Kiss";
case "5":
return "Whisper of the Oracle";
case "6":
return "Whisper of the Oracle";
case "7":
return "Whisper of the Oracle";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "Aether Crackers";
case "1":
return "Aurora";
case "4":
return "Blossom of Spring";
case "7":
return "Crackling";
case "8":
return "Fry";
case "9":
return "Heaven's Claws";
case "3":
return "Ironrot Helm";
case "6":
return "Ironrot Legs";
case "2":
return "Star Fall";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Cloud Cover";
case "7":
return "Embodiment of Lightning";
case "0":
return "Harness Lightning";
case "6":
return "Lightning Press";
case "4":
return "Photon Rush";
case "1":
return "Sizzle";
case "2":
return "Spark Spray";
case "5":
return "Spark Spray";
case "9":
return "Spell Fray Leggings";
case "8":
return "Spell Fray Tiara";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Crackling";
case "6":
return "Fry";
case "3":
return "Harness Lightning";
case "7":
return "Heaven's Claws";
case "0":
return "Lightning Surge";
case "8":
return "Lightning Surge";
case "1":
return "Photon Rush";
case "4":
return "Sizzle";
case "2":
return "Static Shock";
case "9":
return "Static Shock";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "Agile Windup";
case "8":
return "Assault and Battery";
case "6":
return "Bare Fangs";
case "9":
return "Battlefront Bastion";
case "0":
return "Pulping";
case "1":
return "Rally the Rearguard";
case "2":
return "Savage Beatdown";
case "3":
return "Strength Rules All";
case "4":
return "Wild Ride";
case "5":
return "Wreck Havoc";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Agility";
case "8":
return "Might";
case "0":
return "Mighty Windup";
case "1":
return "Pound Town";
case "2":
return "Reincarnate";
case "3":
return "Riled Up";
case "4":
return "Run Roughshod";
case "5":
return "Smash Instinct";
case "6":
return "Wrecker Romp";
default: return "";
}
case "0":
switch($cardID[5]) {
case "7":
return "Bare Fangs";
case "6":
return "Beaten Trackers";
case "8":
return "Clash of Agility";
case "5":
return "Hide Tanner";
case "9":
return "Humble";
case "1":
return "Kayo, Armed and Dangerous";
case "3":
return "Knucklehead";
case "2":
return "Mandible Claw";
case "4":
return "Savage Sash";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Azalea, Ace in the Hole";
case "8":
return "Barbed Undertow";
case "9":
return "Bolt'n' Shot";
case "3":
return "Crow's Nest";
case "2":
return "Death Dealer";
case "7":
return "Flight Path";
case "5":
return "Hidden Agenda";
case "6":
return "Sharp Shooters";
case "4":
return "Target Totalizer";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Bloodrot Pox";
case "1":
return "Frailty";
case "2":
return "Inertia";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Drill Shot";
case "1":
return "Infecting Shot";
case "7":
return "Lace with Bloodrot";
case "8":
return "Lace with Frailty";
case "9":
return "Lace with Inertia";
case "2":
return "Ravenous Rabble";
case "3":
return "Red in the Ledger";
case "4":
return "Sedation Shot";
case "5":
return "Sleep Dart";
case "6":
return "Stone Rain";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Infecting Shot";
case "4":
return "Line It Up";
case "6":
return "Memorial Ground";
case "9":
return "Nock the Deathwhistle";
case "0":
return "Point the Tip";
case "1":
return "Read the Glide Path";
case "5":
return "Read the Glide Path";
case "2":
return "Scout the Periphery";
case "3":
return "Spire Sniping";
case "8":
return "Spire Sniping";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return "Backup Protocol: BLU";
case "0":
return "Boom Grenade";
case "6":
return "Cerebellum Processor";
case "1":
return "Data Link";
case "2":
return "Expedite";
case "7":
return "Steam Canister";
case "3":
return "Zero to Sixty";
case "4":
return "Zipper Hit";
default: return "";
}
case "1":
switch($cardID[5]) {
case "4":
return "Backup Protocol: RED";
case "5":
return "Boom Grenade";
case "6":
return "Convection Amplifier";
case "7":
return "Dissolving Shield";
case "0":
return "Maximum Velocity";
case "1":
return "Throttle";
case "2":
return "Zero to Sixty";
case "8":
return "Zero to Sixty";
case "3":
return "Zipper Hit";
case "9":
return "Zipper Hit";
default: return "";
}
case "0":
switch($cardID[5]) {
case "1":
return "Dash I/O";
case "7":
return "Data Link";
case "8":
return "Dive Through Data";
case "9":
return "Fast and Furious";
case "6":
return "Heavy Industry Gear Shift";
case "4":
return "Heavy Industry Power Plant";
case "5":
return "Heavy Industry Ram Stop";
case "3":
return "Heavy Industry Surveillance";
case "2":
return "Symbiosis Shot";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "Banneret of Resilience";
case "8":
return "Banneret of Vigor";
case "9":
return "Battlefield Blitz";
case "4":
return "Courageous Steelhand";
case "0":
return "Express Lightning";
case "1":
return "Light the Way";
case "5":
return "Resounding Courage";
case "6":
return "Sink Below";
case "2":
return "Snatch";
case "3":
return "Take Flight";
default: return "";
}
case "0":
switch($cardID[5]) {
case "7":
return "Beaming Bravado";
case "8":
return "Bolt of Courage";
case "5":
return "Bracers of Bellona's Grace";
case "9":
return "Engulfing Light";
case "3":
return "Helm of Halo's Grace";
case "2":
return "Raydn, Duskbane";
case "1":
return "Ser Boltyn, Breaker of Dawn";
case "4":
return "Solar Plexus";
case "6":
return "Warpath of Winged Grace";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Beaming Bravado";
case "1":
return "Bolt of Courage";
case "7":
return "Courage";
case "6":
return "Lumina Ascension";
case "8":
return "Quicken";
case "5":
return "Saving Grace";
case "2":
return "Take Flight";
case "3":
return "V of the Vanguard";
case "4":
return "Valiant Thrust";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "Channel Mount Isen";
case "8":
return "Crumble to Eternity";
case "1":
return "Mangle";
case "3":
return "Unforgetting Unforgiving";
default: return "";
}
case "0":
switch($cardID[5]) {
case "1":
return "Jarl Vetreiđi";
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "Absorption Dome";
case "9":
return "Combustible Courier";
case "6":
return "High Speed Impact";
case "7":
return "High Speed Impact";
case "8":
return "High Speed Impact";
case "3":
return "Meganetic Shockwave";
case "1":
return "Plasma Barrel Shot";
case "5":
return "Plasma Purifier";
case "0":
return "Teklo Plasma Pistol";
case "2":
return "Viziertronic Model i";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Aether Conduit";
case "4":
return "Aetherize";
case "2":
return "Chain Lightning";
case "5":
return "Cindering Foresight";
case "6":
return "Cindering Foresight";
case "7":
return "Cindering Foresight";
case "8":
return "Foreboding Bolt";
case "9":
return "Foreboding Bolt";
case "3":
return "Gaze the Ages";
case "1":
return "Metacarpus Node";
default: return "";
}
case "1":
switch($cardID[5]) {
case "9":
return "Azalea, Ace in the Hole";
case "0":
return "Combustible Courier";
case "1":
return "Combustible Courier";
case "8":
return "Kavdaen, Trader of Skins";
case "2":
return "Overblast";
case "3":
return "Overblast";
case "4":
return "Overblast";
case "5":
return "Teklovossen's Workshop";
case "6":
return "Teklovossen's Workshop";
case "7":
return "Teklovossen's Workshop";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Bloodsheath Skeleta";
case "8":
return "Consuming Volition";
case "9":
return "Consuming Volition";
case "2":
return "Dread Triptych";
case "5":
return "Mauvrion Skies";
case "6":
return "Mauvrion Skies";
case "7":
return "Mauvrion Skies";
case "3":
return "Rattle Bones";
case "0":
return "Reaping Blade";
case "4":
return "Runeblood Barrier";
default: return "";
}
case "9":
switch($cardID[5]) {
case "2":
return "Brutal Assault";
case "3":
return "Brutal Assault";
case "4":
return "Brutal Assault";
case "7":
return "Copper";
case "5":
return "Cracked Bauble";
case "6":
return "Quicken";
case "0":
return "Reinforce the Line";
case "1":
return "Reinforce the Line";
default: return "";
}
case "8":
switch($cardID[5]) {
case "8":
return "Cash In";
case "0":
return "Coax a Commotion";
case "1":
return "Gorganian Tome";
case "6":
return "Lunging Press";
case "3":
return "Promise of Plenty";
case "4":
return "Promise of Plenty";
case "5":
return "Promise of Plenty";
case "9":
return "Reinforce the Line";
case "2":
return "Snag";
case "7":
return "Springboard Somersault";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Consuming Volition";
case "9":
return "Crucible of Aetherweave";
case "8":
return "Kano, Dracai of Aether";
case "1":
return "Meat and Greet";
case "2":
return "Meat and Greet";
case "3":
return "Meat and Greet";
case "7":
return "Runechant";
case "4":
return "Sutcliffe's Research Notes";
case "5":
return "Sutcliffe's Research Notes";
case "6":
return "Sutcliffe's Research Notes";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Death Dealer";
case "5":
return "Feign Death";
case "9":
return "Pathing Helix";
case "2":
return "Perch Grapplers";
case "7":
return "Pitfall Trap";
case "4":
return "Poison the Tips";
case "1":
return "Red Liner";
case "3":
return "Remorseless";
case "8":
return "Rockslide Trap";
case "6":
return "Tripwire Trap";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Foreboding Bolt";
case "8":
return "Fyendal's Spring Tunic";
case "9":
return "Gambler's Gloves";
case "1":
return "Rousing Aether";
case "2":
return "Rousing Aether";
case "3":
return "Rousing Aether";
case "4":
return "Snapback";
case "5":
return "Snapback";
case "6":
return "Snapback";
case "7":
return "Talishar, the Lost Prince";
default: return "";
}
case "3":
switch($cardID[5]) {
case "5":
return "Increase the Tension";
case "6":
return "Increase the Tension";
case "7":
return "Increase the Tension";
case "9":
return "Nebula Blade";
case "0":
return "Pathing Helix";
case "1":
return "Pathing Helix";
case "2":
return "Sleep Dart";
case "3":
return "Sleep Dart";
case "4":
return "Sleep Dart";
case "8":
return "Viserai, Rune Blood";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "Anothos";
case "2":
return "Bravo, Showstopper";
case "5":
return "Crater Fist";
case "6":
return "Mangle";
case "7":
return "Righteous Cleansing";
case "4":
return "Sledge of Anvilheim";
case "8":
return "Stamp Authority";
case "0":
return "Swing Fist, Think Later";
case "1":
return "Swing Fist, Think Later";
case "9":
return "Towering Titan";
default: return "";
}
case "0":
switch($cardID[5]) {
case "9":
return "Argh… Smash!";
case "0":
return "Arknight Shard";
case "7":
return "Beast Within";
case "2":
return "Kayo, Berserker Runt";
case "4":
return "Mandible Claw";
case "5":
return "Mandible Claw";
case "8":
return "Massacre";
case "1":
return "Rhinar, Reckless Rampage";
case "3":
return "Romping Club";
case "6":
return "Skullhorn";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Barraging Big Horn";
case "1":
return "Barraging Big Horn";
case "2":
return "Barraging Big Horn";
case "3":
return "Predatory Assault";
case "4":
return "Predatory Assault";
case "5":
return "Predatory Assault";
case "6":
return "Riled Up";
case "7":
return "Riled Up";
case "8":
return "Riled Up";
case "9":
return "Swing Fist, Think Later";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Benji, the Piercing Wind";
case "1":
return "Blessing of Serenity";
case "2":
return "Blessing of Serenity";
case "3":
return "Blessing of Serenity";
case "0":
return "Emerging Dominance";
case "8":
return "Harmonized Kodachi";
case "9":
return "Harmonized Kodachi";
case "6":
return "Ira, Crimson Haze";
case "5":
return "Katsu, the Wanderer";
case "4":
return "Seismic Surge";
default: return "";
}
case "7":
switch($cardID[5]) {
case "2":
return "Bittering Thorns";
case "9":
return "Cintari Saber";
case "8":
return "Dawnblade";
case "6":
return "Dorinthea Ironsong";
case "7":
return "Kassai, Cintari Sellsword";
case "3":
return "Salt the Wound";
case "0":
return "Torrent of Tempo";
case "1":
return "Torrent of Tempo";
case "4":
return "Whirling Mist Blossom";
case "5":
return "Zen State";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Breeze Rider Boots";
case "7":
return "Crane Dance";
case "8":
return "Crane Dance";
case "9":
return "Crane Dance";
case "0":
return "Edge of Autumn";
case "4":
return "Find Center";
case "5":
return "Flood of Force";
case "6":
return "Heron's Flight";
case "1":
return "Zephyr Needle";
case "2":
return "Zephyr Needle";
default: return "";
}
case "3":
switch($cardID[5]) {
case "5":
return "Chokeslam";
case "6":
return "Chokeslam";
case "7":
return "Chokeslam";
case "2":
return "Crush the Weak";
case "3":
return "Crush the Weak";
case "4":
return "Crush the Weak";
case "8":
return "Emerging Dominance";
case "9":
return "Emerging Dominance";
case "0":
return "Towering Titan";
case "1":
return "Towering Titan";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Cintari Saber";
case "1":
return "Courage of Bladehold";
case "5":
return "Dauntless";
case "6":
return "Dauntless";
case "7":
return "Dauntless";
case "8":
return "Out for Blood";
case "9":
return "Out for Blood";
case "4":
return "Spoils of War";
case "2":
return "Twinning Blade";
case "3":
return "Unified Decree";
default: return "";
}
case "9":
switch($cardID[5]) {
case "8":
return "Dash, Inventor Extraordinaire";
case "9":
return "Data Doll MKII";
case "1":
return "Hit and Run";
case "2":
return "Hit and Run";
case "3":
return "Hit and Run";
case "0":
return "Out for Blood";
case "4":
return "Push Forward";
case "5":
return "Push Forward";
case "6":
return "Push Forward";
case "7":
return "Shiyana, Diamond Gemini";
default: return "";
}
case "6":
switch($cardID[5]) {
case "3":
return "Flying Kick";
case "4":
return "Flying Kick";
case "5":
return "Flying Kick";
case "0":
return "Rushing River";
case "1":
return "Rushing River";
case "2":
return "Rushing River";
case "6":
return "Soulbead Strike";
case "7":
return "Soulbead Strike";
case "8":
return "Soulbead Strike";
case "9":
return "Torrent of Tempo";
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return "Adaptive Dissolver";
case "9":
return "Calming Cloak";
case "4":
return "Drink 'Em Under the Table";
case "5":
return "Gustwave of the Second Wind";
case "7":
return "Plan for the Worst";
case "3":
return "Splatter Skull";
case "8":
return "Unsheathed";
case "0":
return "Widow Back Abdomen";
case "1":
return "Widow Claw Tarsus";
case "2":
return "Widow Web Crawler";
default: return "";
}
case "3":
switch($cardID[5]) {
case "1":
return "Arcane Polarity";
case "2":
return "Arcane Polarity";
case "3":
return "Arcane Polarity";
case "4":
return "Brush Off";
case "5":
return "Brush Off";
case "6":
return "Brush Off";
case "8":
return "Cracked Bauble";
case "7":
return "Ponder";
case "0":
return "Sigil of Fyendal";
case "9":
return "Widow Veil Respirator";
default: return "";
}
case "1":
switch($cardID[5]) {
case "1":
return "Arcanite Fortress";
case "3":
return "Bruised Leather";
case "8":
return "Call to the Grave";
case "6":
return "Cut Through the Facade";
case "4":
return "Four Finger Gloves";
case "2":
return "Hood of Second Thoughts";
case "5":
return "Runaways";
case "0":
return "Sigil of Forethought";
case "7":
return "Ten Foot Tall and Bulletproof";
case "9":
return "Truce";
default: return "";
}
case "5":
switch($cardID[5]) {
case "5":
return "Briar";
case "4":
return "Briar, Warden of Thorns";
case "0":
return "Calming Gesture";
case "2":
return "Dust from the Fertile Fields";
case "7":
return "Embodiment of Earth";
case "1":
return "Fluttersteps";
case "3":
return "Regrowth // Shock";
case "6":
return "Rosetta Thorn";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Count Your Blessings";
case "4":
return "Count Your Blessings";
case "5":
return "Count Your Blessings";
case "7":
return "Fyendal's Fighting Spirit";
case "8":
return "Fyendal's Fighting Spirit";
case "9":
return "Fyendal's Fighting Spirit";
case "0":
return "Hand Behind the Pen";
case "6":
return "Sigil of Cycles";
case "1":
return "Smash Up";
case "2":
return "Tongue Tied";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Overflow the Aetherwell";
case "1":
return "Perennial Aetherbloom";
case "2":
return "Perennial Aetherbloom";
case "3":
return "Perennial Aetherbloom";
case "4":
return "Photon Splicing";
case "5":
return "Photon Splicing";
case "6":
return "Photon Splicing";
case "7":
return "Trailblazing Aether";
case "8":
return "Trailblazing Aether";
case "9":
return "Trailblazing Aether";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "Aether Bindings of the Third Age";
case "6":
return "Destructive Aethertide";
case "7":
return "Eternal Inferno";
case "5":
return "Hold Focus";
case "4":
return "Ink-lined Cloak";
case "9":
return "Mental Block";
case "0":
return "Oath of the Arknight";
case "2":
return "Runechant";
case "8":
return "Sigil of Aether";
case "1":
return "Sigil of Deadwood";
default: return "";
}
case "8":
switch($cardID[5]) {
case "3":
return "Aether Quickening";
case "4":
return "Aether Quickening";
case "5":
return "Aether Quickening";
case "6":
return "Arcane Twining";
case "7":
return "Arcane Twining";
case "8":
return "Arcane Twining";
case "9":
return "Etchings of Arcana";
case "0":
return "Save the Thought";
case "1":
return "Save the Thought";
case "2":
return "Sigil of Temporal Manipulation";
default: return "";
}
case "5":
switch($cardID[5]) {
case "2":
return "Arcane Cussing";
case "3":
return "Arcane Cussing";
case "4":
return "Arcane Cussing";
case "5":
return "Deadwood Dirge";
case "6":
return "Deadwood Dirge";
case "7":
return "Deadwood Dirge";
case "8":
return "Oath of the Arknight";
case "9":
return "Oath of the Arknight";
case "0":
return "Runerager Swarm";
case "1":
return "Runerager Swarm";
default: return "";
}
case "3":
switch($cardID[5]) {
case "4":
return "Arcanic Spike";
case "5":
return "Arcanic Spike";
case "6":
return "Arcanic Spike";
case "7":
return "Consuming Volition";
case "8":
return "Consuming Volition";
case "9":
return "Consuming Volition";
case "0":
return "Malefic Incantation";
case "1":
return "Malefic Incantation";
case "2":
return "Malefic Incantation";
case "3":
return "Sigil of the Arknight";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Bloodtorn Bodice";
case "0":
return "Electrostatic Discharge";
case "1":
return "Electrostatic Discharge";
case "2":
return "Electrostatic Discharge";
case "4":
return "Face Purgatory";
case "8":
return "Machinations of Dominion";
case "6":
return "Runehold Release";
case "3":
return "Sigil of Lightning";
case "7":
return "Snuff Out";
case "9":
return "Succumb to Temptation";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Chorus of the Amphitheater";
case "1":
return "Chorus of the Amphitheater";
case "2":
return "Chorus of the Amphitheater";
case "3":
return "Glyph Overlay";
case "4":
return "Glyph Overlay";
case "5":
return "Glyph Overlay";
case "6":
return "Pop the Bubble";
case "7":
return "Pop the Bubble";
case "8":
return "Pop the Bubble";
case "9":
return "Save the Thought";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Condemn to Slaughter";
case "8":
return "Condemn to Slaughter";
case "9":
return "Condemn to Slaughter";
case "0":
return "Haunting Rendition";
case "1":
return "Splintering Deadwood";
case "2":
return "Splintering Deadwood";
case "3":
return "Splintering Deadwood";
case "4":
return "Vantage Point";
case "5":
return "Vantage Point";
case "6":
return "Vantage Point";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Etchings of Arcana";
case "1":
return "Etchings of Arcana";
case "2":
return "Exploding Aether";
case "3":
return "Exploding Aether";
case "4":
return "Exploding Aether";
case "5":
return "Open the Flood Gates";
case "6":
return "Open the Flood Gates";
case "7":
return "Open the Flood Gates";
case "8":
return "Overflow the Aetherwell";
case "9":
return "Overflow the Aetherwell";
default: return "";
}
case "0":
switch($cardID[5]) {
case "7":
return "Flash";
case "8":
return "Flash";
case "9":
return "Flash";
case "0":
return "Lightning Surge";
case "1":
return "Second Strike";
case "2":
return "Second Strike";
case "3":
return "Second Strike";
case "4":
return "Trip the Light Fantastic";
case "5":
return "Trip the Light Fantastic";
case "6":
return "Trip the Light Fantastic";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Hit the High Notes";
case "1":
return "Hit the High Notes";
case "2":
return "Hit the High Notes";
case "3":
return "Hocus Pocus";
case "4":
return "Hocus Pocus";
case "5":
return "Hocus Pocus";
case "6":
return "Meat and Greet";
case "7":
return "Meat and Greet";
case "8":
return "Meat and Greet";
case "9":
return "Runerager Swarm";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "Arc Lightning";
case "2":
return "Burn Up // Shock";
case "6":
return "Heartbeat of Candlehold";
case "9":
return "Oscilio, Constella Intelligence";
case "8":
return "Pulsing Aether // Life";
case "7":
return "Rampant Growth // Life";
case "5":
return "Staff of Verdant Shoots";
case "1":
return "Vaporize // Shock";
case "4":
return "Verdance";
case "3":
return "Verdance, Thorn of the Rose";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Arcane Seeds // Life";
case "8":
return "Aurora";
case "7":
return "Aurora, Shooting Star";
case "2":
return "Florian";
case "1":
return "Florian, Rotwood Harbinger";
case "4":
return "Germinate";
case "3":
return "Rotwood Reaper";
case "9":
return "Star Fall";
case "5":
return "Thistle Bloom // Life";
case "0":
return "Will of Arcana";
default: return "";
}
case "4":
switch($cardID[5]) {
case "6":
return "Autumn's Touch";
case "7":
return "Autumn's Touch";
case "8":
return "Autumn's Touch";
case "9":
return "Blossoming Decay";
case "2":
return "Rootbound Carapace";
case "3":
return "Rootbound Carapace";
case "4":
return "Rootbound Carapace";
case "5":
return "Sigil of Sanctuary";
case "0":
return "Summer's Fall";
case "1":
return "Summer's Fall";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Barkskin of the Millennium Tree";
case "4":
return "Comet Storm // Shock";
case "5":
return "Embodiment of Earth";
case "6":
return "Embodiment of Lightning";
case "9":
return "Helm of Lignum Vitae";
case "3":
return "Null // Shock";
case "0":
return "Oscilio";
case "7":
return "Sanctuary of Aria";
case "2":
return "Sigil of Brilliance";
case "1":
return "Volzar, the Lightning Rod";
default: return "";
}
case "7":
switch($cardID[5]) {
case "9":
return "Blast to Oblivion";
case "7":
return "Channel Lightning Valley";
case "4":
return "Current Funnel";
case "5":
return "Eclectic Magnetism";
case "2":
return "Flash of Brilliance";
case "6":
return "Gone in a Flash";
case "8":
return "High Voltage";
case "1":
return "Lightning Greaves";
case "0":
return "Sigil of Earth";
case "3":
return "Twinkle Toes";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Blast to Oblivion";
case "1":
return "Blast to Oblivion";
case "5":
return "Electromagnetic Somersault";
case "6":
return "Electromagnetic Somersault";
case "7":
return "Electromagnetic Somersault";
case "9":
return "Flittering Charge";
case "2":
return "Lightning Form";
case "3":
return "Lightning Form";
case "4":
return "Lightning Form";
case "8":
return "Sigil of Conductivity";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Blossoming Decay";
case "1":
return "Blossoming Decay";
case "2":
return "Cadaverous Tilling";
case "3":
return "Cadaverous Tilling";
case "4":
return "Cadaverous Tilling";
case "5":
return "Fruits of the Forest";
case "6":
return "Fruits of the Forest";
case "7":
return "Fruits of the Forest";
case "8":
return "Strength of Four Seasons";
case "9":
return "Strength of Four Seasons";
default: return "";
}
case "3":
switch($cardID[5]) {
case "3":
return "Channel the Millennium Tree";
case "6":
return "Earth Form";
case "7":
return "Earth Form";
case "8":
return "Earth Form";
case "4":
return "Earth's Embrace";
case "1":
return "Felling of the Crown";
case "2":
return "Plow Under";
case "5":
return "Seeds of Tomorrow";
case "9":
return "Summer's Fall";
case "0":
return "Well Grounded";
default: return "";
}
case "6":
switch($cardID[5]) {
case "7":
return "Fertile Ground";
case "8":
return "Fertile Ground";
case "9":
return "Fertile Ground";
case "1":
return "Harvest Season";
case "2":
return "Harvest Season";
case "3":
return "Harvest Season";
case "0":
return "Strength of Four Seasons";
case "4":
return "Strong Yield";
case "5":
return "Strong Yield";
case "6":
return "Strong Yield";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Flittering Charge";
case "1":
return "Flittering Charge";
case "2":
return "Fry";
case "3":
return "Fry";
case "4":
return "Fry";
case "5":
return "Heaven's Claws";
case "6":
return "Heaven's Claws";
case "7":
return "Heaven's Claws";
case "8":
return "Lightning Surge";
case "9":
return "Lightning Surge";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "Alpha Rampage";
case "9":
return "Beast Mode";
case "4":
return "Blossom of Spring";
case "2":
return "Bone Basher";
case "3":
return "Bone Vizier";
case "7":
return "Chief Ruk'utan";
case "5":
return "Ironhide Gauntlet";
case "6":
return "Ironhide Legs";
case "1":
return "Rhinar";
default: return "";
}
case "1":
switch($cardID[5]) {
case "4":
return "Awakening Bellow";
case "1":
return "Bare Fangs";
case "8":
return "Muscle Mutt";
case "5":
return "Pack Call";
case "0":
return "Pack Hunt";
case "9":
return "Raging Onslaught";
case "6":
return "Smash Instinct";
case "7":
return "Smash with Big Tree";
case "2":
return "Wild Ride";
case "3":
return "Wrecking Ball";
default: return "";
}
case "2":
switch($cardID[5]) {
case "1":
return "Barraging Beatdown";
case "5":
return "Clearing Bellow";
case "4":
return "Come to Fight";
case "6":
return "Dodge";
case "2":
return "Rally the Rearguard";
case "7":
return "Titanium Bauble";
case "0":
return "Wounded Bull";
case "3":
return "Wrecker Romp";
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return "Adaptive Plating";
case "6":
return "Cogwerx Base Arms";
case "5":
return "Cogwerx Base Chest";
case "4":
return "Cogwerx Base Head";
case "7":
return "Cogwerx Base Legs";
case "1":
return "Hyper-X3";
case "0":
return "Singularity";
case "9":
return "Teklo Base Chest";
case "8":
return "Teklo Base Head";
case "2":
return "Teklo Foundry Heart";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Annihilator Engine";
case "7":
return "Demolition Protocol";
case "1":
return "Evo Buzz Hive";
case "2":
return "Evo Whizz Bang";
case "3":
return "Evo Zip Line";
case "0":
return "Evo Zoom Call";
case "9":
return "Meganetic Protocol";
case "8":
return "Pulsewave Protocol";
case "5":
return "Terminator Tank";
case "6":
return "War Machine";
default: return "";
}
case "9":
switch($cardID[5]) {
case "8":
return "Autosave Script";
case "0":
return "Hadron Collider";
case "1":
return "Hadron Collider";
case "2":
return "Hadron Collider";
case "9":
return "Hyper Driver";
case "7":
return "MHz Script";
case "3":
return "Mini Forcefield";
case "4":
return "Mini Forcefield";
case "5":
return "Mini Forcefield";
case "6":
return "Overload Script";
default: return "";
}
case "8":
switch($cardID[5]) {
case "3":
return "Backup Protocol: BLU";
case "1":
return "Backup Protocol: RED";
case "2":
return "Backup Protocol: YEL";
case "4":
return "Boom Grenade";
case "5":
return "Boom Grenade";
case "6":
return "Boom Grenade";
case "7":
return "Dissolving Shield";
case "8":
return "Dissolving Shield";
case "9":
return "Dissolving Shield";
case "0":
return "Security Script";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Banksy";
case "1":
return "Dash I/O";
case "2":
return "Dash, Database";
case "0":
return "Master Cog";
case "4":
return "Maxx 'The Hype' Nitro";
case "5":
return "Maxx Nitro";
case "3":
return "Symbiosis Shot";
case "9":
return "Teklo Leveler";
case "8":
return "Teklovossen";
case "7":
return "Teklovossen, Esteemed Magnate";
default: return "";
}
case "3":
switch($cardID[5]) {
case "1":
return "Evo Atom Breaker";
case "0":
return "Evo Circuit Breaker";
case "4":
return "Evo Command Center";
case "9":
return "Evo Energy Matrix";
case "5":
return "Evo Engine Room";
case "2":
return "Evo Face Breaker";
case "3":
return "Evo Mach Breaker";
case "6":
return "Evo Smoothbore";
case "8":
return "Evo Tekloscope";
case "7":
return "Evo Thruster";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Evo Battery Pack";
case "9":
return "Evo Charging Rods";
case "8":
return "Evo Cogspitter";
case "6":
return "Evo Data Mine";
case "1":
return "Evo Rapid Fire";
case "0":
return "Evo Scatter Shot";
case "4":
return "Evo Sentry Base Arms";
case "3":
return "Evo Sentry Base Chest";
case "2":
return "Evo Sentry Base Head";
case "5":
return "Evo Sentry Base Legs";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Evo Steel Soul Controller";
case "6":
return "Evo Steel Soul Memory";
case "7":
return "Evo Steel Soul Processor";
case "9":
return "Evo Steel Soul Tower";
case "4":
return "Proto Base Arms";
case "3":
return "Proto Base Chest";
case "2":
return "Proto Base Head";
case "5":
return "Proto Base Legs";
case "0":
return "Teklo Base Arms";
case "1":
return "Teklo Base Legs";
default: return "";
}
case "7":
switch($cardID[5]) {
case "5":
return "Fuel Injector";
case "0":
return "Grinding Gears";
case "6":
return "Medkit";
case "9":
return "Penetration Script";
case "8":
return "Polarity Reversal Script";
case "1":
return "Prismatic Lens";
case "2":
return "Quantum Processor";
case "3":
return "Stasis Cell";
case "7":
return "Steam Canister";
case "4":
return "Tick Tock Clock";
default: return "";
}
case "6":
switch($cardID[5]) {
case "1":
return "Heavy Artillery";
case "2":
return "Heavy Artillery";
case "3":
return "Heavy Artillery";
case "4":
return "Liquid-Cooled Mayhem";
case "5":
return "Liquid-Cooled Mayhem";
case "6":
return "Liquid-Cooled Mayhem";
case "7":
return "Mechanical Strength";
case "8":
return "Mechanical Strength";
case "9":
return "Mechanical Strength";
case "0":
return "Steel Street Enforcement";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "6":
return "Already Dead";
case "4":
return "Hyper Driver";
case "0":
return "Re-Charge!";
case "5":
return "Shriek Razors";
case "7":
return "Smashing Performance";
case "8":
return "Tectonic Rift";
case "1":
return "Teklonetic Force Field";
case "2":
return "Teklonetic Force Field";
case "3":
return "Teklonetic Force Field";
case "9":
return "Wax Off";
default: return "";
}
case "4":
switch($cardID[5]) {
case "3":
return "Contest the Mindfield";
case "6":
return "Dust from the Chrome Caverns";
case "0":
return "Emboldened Blade";
case "9":
return "Fyendal's Spring Tunic";
case "1":
return "Intoxicating Shot";
case "4":
return "Phantom Tidemaw";
case "8":
return "Slay";
case "2":
return "Sonata Fantasmia";
case "5":
return "Tome of Imperial Flame";
case "7":
return "Warband of Bellona";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Expedite";
case "7":
return "Lay Waste";
case "8":
return "Lay Waste";
case "9":
return "Lay Waste";
case "1":
return "MetEx";
case "2":
return "MetEx";
case "3":
return "MetEx";
case "4":
return "Out Pace";
case "5":
return "Out Pace";
case "6":
return "Out Pace";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Fender Bender";
case "1":
return "Fender Bender";
case "2":
return "Fender Bender";
case "9":
return "Over Loop";
case "3":
return "Panel Beater";
case "4":
return "Panel Beater";
case "5":
return "Panel Beater";
case "6":
return "Under Loop";
case "7":
return "Under Loop";
case "8":
return "Under Loop";
default: return "";
}
case "2":
switch($cardID[5]) {
case "2":
return "Gas Up";
case "3":
return "Gas Up";
case "4":
return "Gas Up";
case "0":
return "Over Loop";
case "1":
return "Over Loop";
case "5":
return "Quickfire";
case "6":
return "Quickfire";
case "7":
return "Quickfire";
case "8":
return "Re-Charge!";
case "9":
return "Re-Charge!";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Quicken";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "7":
return "Big Bertha";
case "8":
return "Big Bertha";
case "9":
return "Big Bertha";
case "4":
return "Crankshaft";
case "5":
return "Crankshaft";
case "6":
return "Crankshaft";
case "0":
return "Full Tilt";
case "1":
return "Gas Guzzler";
case "2":
return "Gas Guzzler";
case "3":
return "Gas Guzzler";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Big Shot";
case "4":
return "Burn Rubber";
case "9":
return "Firewall";
case "6":
return "Gigawatt";
case "7":
return "Gigawatt";
case "8":
return "Gigawatt";
case "5":
return "Smash and Grab";
case "0":
return "Spring a Leak";
case "1":
return "Spring a Leak";
case "2":
return "Spring a Leak";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Bull Bar";
case "8":
return "Bull Bar";
case "9":
return "Bull Bar";
case "6":
return "Fabricate";
case "3":
return "Meganetic Lockwave";
case "0":
return "Moonshot";
case "1":
return "Steel Street Hoons";
case "4":
return "System Failure";
case "5":
return "System Reset";
case "2":
return "Twin Drive";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Cognition Field";
case "8":
return "Cognition Field";
case "9":
return "Cognition Field";
case "0":
return "Scrap Hopper";
case "1":
return "Soup Up";
case "2":
return "Soup Up";
case "3":
return "Soup Up";
case "4":
return "Torque Tuned";
case "5":
return "Torque Tuned";
case "6":
return "Torque Tuned";
default: return "";
}
case "8":
switch($cardID[5]) {
case "6":
return "Data Link";
case "7":
return "Data Link";
case "8":
return "Data Link";
case "9":
return "Dive Through Data";
case "0":
return "Jump Start";
case "1":
return "Jump Start";
case "2":
return "Jump Start";
case "3":
return "Rev Up";
case "4":
return "Rev Up";
case "5":
return "Rev Up";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Dive Through Data";
case "1":
return "Dive Through Data";
case "5":
return "Dumpster Dive";
case "6":
return "Dumpster Dive";
case "7":
return "Dumpster Dive";
case "8":
return "Expedite";
case "9":
return "Expedite";
case "2":
return "Sprocket Rocket";
case "3":
return "Sprocket Rocket";
case "4":
return "Sprocket Rocket";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Firewall";
case "1":
return "Firewall";
case "8":
return "Full Tilt";
case "9":
return "Full Tilt";
case "5":
return "Razzle Dazzle";
case "6":
return "Razzle Dazzle";
case "7":
return "Razzle Dazzle";
case "2":
return "Zero to Fifty";
case "3":
return "Zero to Fifty";
case "4":
return "Zero to Fifty";
default: return "";
}
case "3":
switch($cardID[5]) {
case "8":
return "Heist";
case "9":
return "Maximum Velocity";
case "0":
return "Scrap Compactor";
case "1":
return "Scrap Compactor";
case "2":
return "Scrap Harvester";
case "3":
return "Scrap Harvester";
case "4":
return "Scrap Harvester";
case "5":
return "Scrap Prospector";
case "6":
return "Scrap Prospector";
case "7":
return "Scrap Prospector";
default: return "";
}
case "0":
switch($cardID[5]) {
case "2":
return "Hydraulic Press";
case "3":
return "Hydraulic Press";
case "4":
return "Hydraulic Press";
case "0":
return "Hyper Scrapper";
case "5":
return "Ratchet Up";
case "6":
return "Ratchet Up";
case "7":
return "Ratchet Up";
case "8":
return "Scrap Hopper";
case "9":
return "Scrap Hopper";
case "1":
return "Scrap Trader";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Infuse Alloy";
case "1":
return "Infuse Alloy";
case "2":
return "Infuse Alloy";
case "3":
return "Infuse Titanium";
case "4":
return "Infuse Titanium";
case "5":
return "Infuse Titanium";
case "6":
return "Junkyard Dogg";
case "7":
return "Junkyard Dogg";
case "8":
return "Junkyard Dogg";
case "9":
return "Scrap Compactor";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "Evo Atom Breaker";
case "0":
return "Evo Circuit Breaker";
case "4":
return "Evo Command Center";
case "9":
return "Evo Energy Matrix";
case "5":
return "Evo Engine Room";
case "2":
return "Evo Face Breaker";
case "3":
return "Evo Mach Breaker";
case "6":
return "Evo Smoothbore";
case "8":
return "Evo Tekloscope";
case "7":
return "Evo Thruster";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Evo Battery Pack";
case "9":
return "Evo Charging Rods";
case "8":
return "Evo Cogspitter";
case "6":
return "Evo Data Mine";
case "1":
return "Evo Rapid Fire";
case "0":
return "Evo Scatter Shot";
case "4":
return "Evo Sentry Base Arms";
case "3":
return "Evo Sentry Base Chest";
case "2":
return "Evo Sentry Base Head";
case "5":
return "Evo Sentry Base Legs";
default: return "";
}
case "5":
switch($cardID[5]) {
case "1":
return "Evo Buzz Hive";
case "2":
return "Evo Whizz Bang";
case "3":
return "Evo Zip Line";
case "0":
return "Evo Zoom Call";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Evo Steel Soul Controller";
case "6":
return "Evo Steel Soul Memory";
case "7":
return "Evo Steel Soul Processor";
case "9":
return "Evo Steel Soul Tower";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Teklovossen, the Mechropotent";
default: return "";
}
default: return "";
}
default: return "";
}
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "Aether Wildfire";
case "5":
return "Emeritus Scolding";
case "6":
return "Emeritus Scolding";
case "7":
return "Emeritus Scolding";
case "0":
return "Iyslander";
case "1":
return "Kraken's Aethervein";
case "8":
return "Pry";
case "9":
return "Pry";
case "4":
return "Scour";
case "2":
return "Sigil of Parapets";
default: return "";
}
case "7":
switch($cardID[5]) {
case "6":
return "Amulet of Assertiveness";
case "7":
return "Amulet of Echoes";
case "8":
return "Amulet of Havencall";
case "9":
return "Amulet of Ignition";
case "3":
return "Even Bigger Than That!";
case "4":
return "Even Bigger Than That!";
case "5":
return "Even Bigger Than That!";
case "0":
return "Smashing Good Time";
case "1":
return "Smashing Good Time";
case "2":
return "Smashing Good Time";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Amulet of Intervention";
case "1":
return "Amulet of Oblation";
case "2":
return "Clarity Potion";
case "3":
return "Healing Potion";
case "5":
return "Potion of Déjà Vu";
case "6":
return "Potion of Ironhide";
case "7":
return "Potion of Luck";
case "4":
return "Potion of Seeing";
case "8":
return "Talisman of Balance";
case "9":
return "Talisman of Cremation";
default: return "";
}
case "5":
switch($cardID[5]) {
case "5":
return "Arcane Lantern";
case "4":
return "Arcanite Skullcap";
case "6":
return "Bingo";
case "8":
return "Cash Out";
case "7":
return "Firebreathing";
case "9":
return "Knick Knack Bric-a-brac";
case "3":
return "Spectral Shield";
case "0":
return "Veiled Intentions";
case "1":
return "Veiled Intentions";
case "2":
return "Veiled Intentions";
default: return "";
}
case "4":
switch($cardID[5]) {
case "4":
return "Coalescence Mirage";
case "5":
return "Coalescence Mirage";
case "6":
return "Coalescence Mirage";
case "1":
return "Haze Bending";
case "2":
return "Passing Mirage";
case "7":
return "Phantasmal Haze";
case "8":
return "Phantasmal Haze";
case "9":
return "Phantasmal Haze";
case "3":
return "Pierce Reality";
case "0":
return "Shimmers of Silver";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Copper";
case "7":
return "Frostbite";
case "6":
return "Quicken";
case "5":
return "Silver";
case "0":
return "Talisman of Featherfoot";
case "1":
return "Talisman of Recompense";
case "2":
return "Talisman of Tithes";
case "3":
return "Talisman of Warfare";
default: return "";
}
case "3":
switch($cardID[5]) {
case "7":
return "Crown of Reflection";
case "8":
return "Fractal Replication";
case "9":
return "Miraging Metamorph";
case "0":
return "Pry";
case "1":
return "Pyroglyphic Protection";
case "2":
return "Pyroglyphic Protection";
case "3":
return "Pyroglyphic Protection";
case "4":
return "Timekeeper's Whim";
case "5":
return "Timekeeper's Whim";
case "6":
return "Timekeeper's Whim";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Drowning Dire";
case "1":
return "Drowning Dire";
case "2":
return "Drowning Dire";
case "3":
return "Reek of Corruption";
case "4":
return "Reek of Corruption";
case "5":
return "Reek of Corruption";
case "9":
return "Runechant";
case "6":
return "Shrill of Skullform";
case "7":
return "Shrill of Skullform";
case "8":
return "Shrill of Skullform";
default: return "";
}
case "6":
switch($cardID[5]) {
case "4":
return "High Striker";
case "5":
return "High Striker";
case "6":
return "High Striker";
case "1":
return "Life of the Party";
case "2":
return "Life of the Party";
case "3":
return "Life of the Party";
case "7":
return "Pick a Card, Any Card";
case "8":
return "Pick a Card, Any Card";
case "9":
return "Pick a Card, Any Card";
case "0":
return "This Round's on Me";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Read the Glide Path";
case "1":
return "Read the Glide Path";
case "2":
return "Read the Glide Path";
case "6":
return "Revel in Runeblood";
case "7":
return "Runeblood Incantation";
case "8":
return "Runeblood Incantation";
case "9":
return "Runeblood Incantation";
case "4":
return "Runic Reclamation";
case "5":
return "Swarming Gloomveil";
case "3":
return "Vexing Quillhand";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "4":
return "Bad Beats";
case "5":
return "Bad Beats";
case "6":
return "Bad Beats";
case "0":
return "Bare Fangs";
case "7":
return "Bravo, Star of the Show";
case "8":
return "Stalagmite, Bastion of Isenloft";
case "9":
return "Valda Brightaxe";
case "1":
return "Wild Ride";
case "2":
return "Wild Ride";
case "3":
return "Wild Ride";
default: return "";
}
case "0":
switch($cardID[5]) {
case "8":
return "Bare Fangs";
case "9":
return "Bare Fangs";
case "0":
return "Grandeur of Valahai";
case "5":
return "High Roller";
case "6":
return "High Roller";
case "7":
return "High Roller";
case "3":
return "Ready to Roll";
case "4":
return "Rolling Thunder";
case "1":
return "Skull Crushers";
case "2":
return "Swing Big";
default: return "";
}
case "8":
switch($cardID[5]) {
case "8":
return "Battering Bolt";
case "7":
return "Dreadbore";
case "5":
return "Genis Wotchuneed";
case "2":
return "Rotary Ram";
case "3":
return "Rotary Ram";
case "4":
return "Rotary Ram";
case "6":
return "Silver Palms";
case "9":
return "Tri-shot";
case "0":
return "Zoom In";
case "1":
return "Zoom In";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Blade Runner";
case "1":
return "Blade Runner";
case "2":
return "Blade Runner";
case "9":
return "Dissolution Sphere";
case "3":
return "In the Swing";
case "4":
return "In the Swing";
case "5":
return "In the Swing";
case "6":
return "Outland Skirmish";
case "7":
return "Outland Skirmish";
case "8":
return "Outland Skirmish";
default: return "";
}
case "5":
switch($cardID[5]) {
case "5":
return "Blood on Her Hands";
case "3":
return "Helm of Sharp Eye";
case "6":
return "Oath of Steel";
case "4":
return "Shatter";
case "7":
return "Slice and Dice";
case "8":
return "Slice and Dice";
case "9":
return "Slice and Dice";
case "0":
return "Wax On";
case "1":
return "Wax On";
case "2":
return "Wax On";
default: return "";
}
case "3":
switch($cardID[5]) {
case "8":
return "Break Tide";
case "7":
return "Mask of the Pouncing Lynx";
case "0":
return "Seismic Stir";
case "1":
return "Seismic Stir";
case "2":
return "Seismic Stir";
case "6":
return "Seismic Surge";
case "9":
return "Spring Tidings";
case "3":
return "Steadfast";
case "4":
return "Steadfast";
case "5":
return "Steadfast";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Earthlore Bounty";
case "2":
return "Imposing Visage";
case "7":
return "Macho Grande";
case "8":
return "Macho Grande";
case "9":
return "Macho Grande";
case "3":
return "Nerves of Steel";
case "1":
return "Pulverize";
case "4":
return "Thunder Quake";
case "5":
return "Thunder Quake";
case "6":
return "Thunder Quake";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Fatigue Shot";
case "5":
return "Fatigue Shot";
case "6":
return "Fatigue Shot";
case "0":
return "Rain Razors";
case "1":
return "Release the Tension";
case "2":
return "Release the Tension";
case "3":
return "Release the Tension";
case "7":
return "Timidity Point";
case "8":
return "Timidity Point";
case "9":
return "Timidity Point";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Hundred Winds";
case "2":
return "Hundred Winds";
case "3":
return "Hundred Winds";
case "4":
return "Ride the Tailwind";
case "5":
return "Ride the Tailwind";
case "6":
return "Ride the Tailwind";
case "7":
return "Twin Twisters";
case "8":
return "Twin Twisters";
case "9":
return "Twin Twisters";
case "0":
return "Winds of Eternity";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Micro-processor";
case "6":
return "Payload";
case "7":
return "Payload";
case "8":
return "Payload";
case "1":
return "Signal Jammer";
case "3":
return "T-Bone";
case "4":
return "T-Bone";
case "5":
return "T-Bone";
case "2":
return "Teklo Pounder";
case "9":
return "Zoom In";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return "Amulet of Earth";
case "7":
return "Blizzard";
case "6":
return "Channel Lake Frigid";
case "5":
return "Coat of Frost";
case "8":
return "Frost Fang";
case "9":
return "Frost Fang";
case "4":
return "Heart of Ice";
case "0":
return "Sow Tomorrow";
case "1":
return "Sow Tomorrow";
case "2":
return "Sow Tomorrow";
default: return "";
}
case "7":
switch($cardID[5]) {
case "2":
return "Amulet of Ice";
case "6":
return "Blink";
case "5":
return "Channel Thunder Steppe";
case "7":
return "Flash";
case "8":
return "Flash";
case "9":
return "Flash";
case "4":
return "Mark of Lightning";
case "3":
return "Shock Charmers";
case "0":
return "Winter's Bite";
case "1":
return "Winter's Bite";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Autumn's Touch";
case "9":
return "Autumn's Touch";
case "0":
return "Evergreen";
case "1":
return "Evergreen";
case "5":
return "Summerwood Shelter";
case "6":
return "Summerwood Shelter";
case "7":
return "Summerwood Shelter";
case "2":
return "Weave Earth";
case "3":
return "Weave Earth";
case "4":
return "Weave Earth";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Autumn's Touch";
case "1":
return "Break Ground";
case "2":
return "Break Ground";
case "3":
return "Break Ground";
case "4":
return "Burgeoning";
case "5":
return "Burgeoning";
case "6":
return "Burgeoning";
case "7":
return "Earthlore Surge";
case "8":
return "Earthlore Surge";
case "9":
return "Earthlore Surge";
default: return "";
}
case "8":
switch($cardID[5]) {
case "6":
return "Ball Lightning";
case "7":
return "Ball Lightning";
case "8":
return "Ball Lightning";
case "3":
return "Lightning Press";
case "4":
return "Lightning Press";
case "5":
return "Lightning Press";
case "9":
return "Lightning Surge";
case "0":
return "Weave Lightning";
case "1":
return "Weave Lightning";
case "2":
return "Weave Lightning";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Channel Mount Heroic";
case "5":
return "Crown of Seeds";
case "0":
return "Embodiment of Lightning";
case "9":
return "Evergreen";
case "1":
return "Frostbite";
case "6":
return "Plume of Evergrowth";
case "3":
return "Pulse of Candlehold";
case "4":
return "Pulse of Isenloft";
case "2":
return "Pulse of Volthaven";
case "8":
return "Tome of Harvests";
default: return "";
}
case "6":
switch($cardID[5]) {
case "3":
return "Chill to the Bone";
case "4":
return "Chill to the Bone";
case "5":
return "Chill to the Bone";
case "6":
return "Polar Blast";
case "7":
return "Polar Blast";
case "8":
return "Polar Blast";
case "9":
return "Winter's Bite";
case "0":
return "Winter's Grasp";
case "1":
return "Winter's Grasp";
case "2":
return "Winter's Grasp";
default: return "";
}
case "9":
switch($cardID[5]) {
case "8":
return "Electrify";
case "9":
return "Electrify";
case "2":
return "Heaven's Claws";
case "3":
return "Heaven's Claws";
case "4":
return "Heaven's Claws";
case "0":
return "Lightning Surge";
case "1":
return "Lightning Surge";
case "5":
return "Shock Striker";
case "6":
return "Shock Striker";
case "7":
return "Shock Striker";
default: return "";
}
case "0":
switch($cardID[5]) {
case "9":
return "Embodiment of Earth";
case "0":
return "Entwine Lightning";
case "1":
return "Entwine Lightning";
case "2":
return "Entwine Lightning";
case "3":
return "Invigorate";
case "4":
return "Invigorate";
case "5":
return "Invigorate";
case "6":
return "Rejuvenate";
case "7":
return "Rejuvenate";
case "8":
return "Rejuvenate";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Frost Fang";
case "1":
return "Ice Quake";
case "2":
return "Ice Quake";
case "3":
return "Ice Quake";
case "7":
return "Icy Encounter";
case "8":
return "Icy Encounter";
case "9":
return "Icy Encounter";
case "4":
return "Weave Ice";
case "5":
return "Weave Ice";
case "6":
return "Weave Ice";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Amulet of Lightning";
case "0":
return "Electrify";
case "6":
return "Embolden";
case "7":
return "Embolden";
case "8":
return "Embolden";
case "3":
return "Rampart of the Ram's Head";
case "4":
return "Rotten Old Buckler";
case "5":
return "Tear Asunder";
case "9":
return "Thump";
case "2":
return "Titan's Fist";
default: return "";
}
case "1":
switch($cardID[5]) {
case "6":
return "Bolt'n' Shot";
case "7":
return "Bolt'n' Shot";
case "8":
return "Bolt'n' Shot";
case "4":
return "Honing Hood";
case "3":
return "New Horizon";
case "9":
return "Over Flex";
case "5":
return "Seek and Destroy";
case "2":
return "Seismic Surge";
case "0":
return "Thump";
case "1":
return "Thump";
default: return "";
}
case "3":
switch($cardID[5]) {
case "7":
return "Cracked Bauble";
case "5":
return "Cracker Jax";
case "4":
return "Deep Blue";
case "3":
return "Ragamuffin's Hat";
case "6":
return "Runaways";
case "0":
return "Singeing Steelblade";
case "1":
return "Singeing Steelblade";
case "2":
return "Singeing Steelblade";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Duskblade";
case "0":
return "Over Flex";
case "1":
return "Over Flex";
case "2":
return "Rosetta Thorn";
case "7":
return "Sigil of Suffering";
case "8":
return "Sigil of Suffering";
case "9":
return "Sigil of Suffering";
case "4":
return "Spellbound Creepers";
case "6":
return "Sting of Sorcery";
case "5":
return "Sutcliffe's Suede Hides";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "3":
return "Arcanic Shockwave";
case "4":
return "Arcanic Shockwave";
case "5":
return "Arcanic Shockwave";
case "0":
return "Rites of Lightning";
case "1":
return "Rites of Lightning";
case "2":
return "Rites of Lightning";
case "9":
return "Rites of Replenishment";
case "6":
return "Vela Flash";
case "7":
return "Vela Flash";
case "8":
return "Vela Flash";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Awakening";
case "7":
return "Biting Gale";
case "8":
return "Biting Gale";
case "9":
return "Biting Gale";
case "4":
return "Endless Winter";
case "0":
return "Korshem, Crossroad of Elements";
case "5":
return "Oaken Old";
case "2":
return "Oldhim";
case "1":
return "Oldhim, Grandfather of Eternity";
case "3":
return "Winter's Wail";
default: return "";
}
case "4":
switch($cardID[5]) {
case "4":
return "Blizzard Bolt";
case "5":
return "Blizzard Bolt";
case "6":
return "Blizzard Bolt";
case "7":
return "Buzz Bolt";
case "8":
return "Buzz Bolt";
case "9":
return "Buzz Bolt";
case "0":
return "Cold Wave";
case "1":
return "Snap Shot";
case "2":
return "Snap Shot";
case "3":
return "Snap Shot";
default: return "";
}
case "6":
switch($cardID[5]) {
case "4":
return "Blossoming Spellblade";
case "3":
return "Briar";
case "2":
return "Briar, Warden of Thorns";
case "7":
return "Explosive Growth";
case "8":
return "Explosive Growth";
case "9":
return "Explosive Growth";
case "5":
return "Flicker Wisp";
case "6":
return "Force of Nature";
case "0":
return "Frazzle";
case "1":
return "Frazzle";
default: return "";
}
case "8":
switch($cardID[5]) {
case "5":
return "Bramble Spark";
case "6":
return "Bramble Spark";
case "7":
return "Bramble Spark";
case "8":
return "Inspire Lightning";
case "9":
return "Inspire Lightning";
case "0":
return "Rites of Replenishment";
case "1":
return "Rites of Replenishment";
case "2":
return "Stir the Wildwood";
case "3":
return "Stir the Wildwood";
case "4":
return "Stir the Wildwood";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Chilling Icevein";
case "1":
return "Chilling Icevein";
case "2":
return "Chilling Icevein";
case "3":
return "Dazzling Crescendo";
case "4":
return "Dazzling Crescendo";
case "5":
return "Dazzling Crescendo";
case "6":
return "Flake Out";
case "7":
return "Flake Out";
case "8":
return "Flake Out";
case "9":
return "Frazzle";
default: return "";
}
case "3":
switch($cardID[5]) {
case "8":
return "Cold Wave";
case "9":
return "Cold Wave";
case "5":
return "Frost Lock";
case "7":
return "Ice Storm";
case "2":
return "Lexi";
case "1":
return "Lexi, Livewire";
case "6":
return "Light it Up";
case "3":
return "Shiver";
case "0":
return "Strength of Sequoia";
case "4":
return "Voltaire, Strike Twice";
default: return "";
}
case "2":
switch($cardID[5]) {
case "5":
return "Emerging Avalanche";
case "6":
return "Emerging Avalanche";
case "7":
return "Emerging Avalanche";
case "0":
return "Mulch";
case "1":
return "Mulch";
case "2":
return "Snow Under";
case "3":
return "Snow Under";
case "4":
return "Snow Under";
case "8":
return "Strength of Sequoia";
case "9":
return "Strength of Sequoia";
default: return "";
}
case "1":
switch($cardID[5]) {
case "3":
return "Entangle";
case "4":
return "Entangle";
case "5":
return "Entangle";
case "6":
return "Glacial Footsteps";
case "7":
return "Glacial Footsteps";
case "8":
return "Glacial Footsteps";
case "9":
return "Mulch";
case "0":
return "Turn Timber";
case "1":
return "Turn Timber";
case "2":
return "Turn Timber";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Entwine Earth";
case "5":
return "Entwine Earth";
case "6":
return "Entwine Earth";
case "7":
return "Entwine Ice";
case "8":
return "Entwine Ice";
case "9":
return "Entwine Ice";
case "3":
return "Exposed to the Elements";
case "2":
return "Flashfreeze";
case "1":
return "Fulminate";
case "0":
return "Inspire Lightning";
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "2":
return "Adrenaline Rush";
case "3":
return "Adrenaline Rush";
case "4":
return "Adrenaline Rush";
case "5":
return "Performance Bonus";
case "6":
return "Performance Bonus";
case "7":
return "Performance Bonus";
case "8":
return "Rally the Rearguard";
case "9":
return "Rally the Rearguard";
case "0":
return "Reinforce the Line";
case "1":
return "Reinforce the Line";
default: return "";
}
case "5":
switch($cardID[5]) {
case "2":
return "Aether Arc";
case "3":
return "Dissolve Reality";
case "4":
return "Luminaris, Angel's Glow";
case "0":
return "Reel In";
case "1":
return "Sonata Galaxia";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Agility";
case "7":
return "Ancestral Harmony";
case "6":
return "Coercive Tendency";
case "4":
return "Cracked Bauble";
case "8":
return "Evo Magneto";
case "3":
return "Gold";
case "5":
return "Graven Call";
case "9":
return "Judge, Jury, Executioner";
case "1":
return "Might";
case "2":
return "Vigor";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Bloodied Oval";
case "0":
return "Embrace Adversity";
case "5":
return "Grandstand Legplates";
case "2":
return "Headliner Helm";
case "7":
return "Nasty Surprise";
case "1":
return "Overcome Adversity";
case "8":
return "Pay Up";
case "9":
return "Ripple Away";
case "3":
return "Stadium Centerpiece";
case "4":
return "Ticket Puncher";
default: return "";
}
case "1":
switch($cardID[5]) {
case "3":
return "Down But Not Out";
case "4":
return "Down But Not Out";
case "5":
return "Down But Not Out";
case "9":
return "Reinforce the Line";
case "2":
return "Seduce Secrets";
case "0":
return "Standing Order";
case "1":
return "Tenacity";
case "6":
return "Wage Gold";
case "7":
return "Wage Gold";
case "8":
return "Wage Gold";
default: return "";
}
case "3":
switch($cardID[5]) {
case "4":
return "Lunging Press";
case "5":
return "Money Where Ya Mouth Is";
case "6":
return "Money Where Ya Mouth Is";
case "7":
return "Money Where Ya Mouth Is";
case "0":
return "Rally the Rearguard";
case "8":
return "Starting Stake";
case "9":
return "Test of Strength";
case "1":
return "Trade In";
case "2":
return "Trade In";
case "3":
return "Trade In";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return "Agile Engagement";
case "6":
return "Agile Engagement";
case "7":
return "Agile Engagement";
case "0":
return "Fatal Engagement";
case "1":
return "Fatal Engagement";
case "2":
return "Take the Upper Hand";
case "3":
return "Take the Upper Hand";
case "4":
return "Take the Upper Hand";
case "8":
return "Vigorous Engagement";
case "9":
return "Vigorous Engagement";
default: return "";
}
case "6":
switch($cardID[5]) {
case "3":
return "Agile Windup";
case "4":
return "Agile Windup";
case "5":
return "Agile Windup";
case "6":
return "Rising Speed";
case "7":
return "Rising Speed";
case "8":
return "Rising Speed";
case "1":
return "Run into Trouble";
case "0":
return "Take it on the Chin";
case "2":
return "Test of Agility";
case "9":
return "Wage Agility";
default: return "";
}
case "9":
switch($cardID[5]) {
case "5":
return "Balance of Justice";
case "9":
return "Confront Adversity";
case "8":
return "Face Adversity";
case "6":
return "Glory Seeker";
case "2":
return "Lead with Heart";
case "3":
return "Lead with Heart";
case "4":
return "Lead with Heart";
case "7":
return "Sheltered Cove";
case "0":
return "Wage Vigor";
case "1":
return "Wage Vigor";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Battered Not Broken";
case "3":
return "Mighty Windup";
case "4":
return "Mighty Windup";
case "5":
return "Mighty Windup";
case "6":
return "Rising Power";
case "7":
return "Rising Power";
case "8":
return "Rising Power";
case "1":
return "Test of Might";
case "9":
return "Wage Might";
case "2":
return "Wall of Meat and Muscle";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Beckon Applause";
case "1":
return "Blade Flurry";
case "4":
return "Commanding Performance";
case "6":
return "Cut the Deck";
case "7":
return "Cut the Deck";
case "8":
return "Cut the Deck";
case "9":
return "Fatal Engagement";
case "5":
return "Raise an Army";
case "2":
return "Shift the Tide of Battle";
case "3":
return "Up the Ante";
default: return "";
}
case "3":
switch($cardID[5]) {
case "4":
return "Cintari Sellsword";
case "7":
return "Clash of Might";
case "8":
return "Clash of Might";
case "9":
return "Clash of Might";
case "5":
return "Gauntlet of Might";
case "3":
return "Goblet of Bloodrun Wine";
case "0":
return "Hold 'em";
case "1":
return "Hold 'em";
case "2":
return "Hold 'em";
case "6":
return "Talk a Big Game";
default: return "";
}
case "5":
switch($cardID[5]) {
case "7":
return "Clash of Agility";
case "8":
return "Clash of Agility";
case "9":
return "Clash of Agility";
case "5":
return "Flat Trackers";
case "2":
return "Lead with Power";
case "3":
return "Lead with Power";
case "4":
return "Lead with Power";
case "6":
return "Runner Runner";
case "0":
return "Wage Might";
case "1":
return "Wage Might";
default: return "";
}
case "7":
switch($cardID[5]) {
case "7":
return "Clash of Vigor";
case "8":
return "Clash of Vigor";
case "9":
return "Clash of Vigor";
case "6":
return "Double Down";
case "2":
return "Lead with Speed";
case "3":
return "Lead with Speed";
case "4":
return "Lead with Speed";
case "5":
return "Vigor Girth";
case "0":
return "Wage Agility";
case "1":
return "Wage Agility";
default: return "";
}
case "2":
switch($cardID[5]) {
case "1":
return "Draw Swords";
case "2":
return "Draw Swords";
case "3":
return "Draw Swords";
case "4":
return "Edge Ahead";
case "5":
return "Edge Ahead";
case "6":
return "Edge Ahead";
case "7":
return "Engaged Swiftblade";
case "8":
return "Engaged Swiftblade";
case "9":
return "Engaged Swiftblade";
case "0":
return "Vigorous Engagement";
default: return "";
}
case "8":
switch($cardID[5]) {
case "1":
return "Hearty Block";
case "3":
return "Rising Energy";
case "4":
return "Rising Energy";
case "5":
return "Rising Energy";
case "0":
return "Slap-Happy";
case "2":
return "Test of Vigor";
case "6":
return "Vigorous Windup";
case "7":
return "Vigorous Windup";
case "8":
return "Vigorous Windup";
case "9":
return "Wage Vigor";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "Apex Bonebreaker";
case "6":
return "Ball Breaker";
case "0":
return "Deathmatch Arena";
case "2":
return "Kayo";
case "1":
return "Kayo, Armed and Dangerous";
case "9":
return "Knucklehead";
case "5":
return "Mandible Claw";
case "7":
return "Mini Meataxe";
case "4":
return "Rhinar";
case "3":
return "Rhinar, Reckless Rampage";
default: return "";
}
case "2":
switch($cardID[5]) {
case "6":
return "Assault and Battery";
case "7":
return "Assault and Battery";
case "8":
return "Assault and Battery";
case "9":
return "Bare Fangs";
case "0":
return "Pack Call";
case "1":
return "Pack Call";
case "2":
return "Pack Call";
case "3":
return "Rawhide Rumble";
case "4":
return "Rawhide Rumble";
case "5":
return "Rawhide Rumble";
default: return "";
}
case "5":
switch($cardID[5]) {
case "1":
return "Aurum Aegis";
case "7":
return "Bet Big";
case "3":
return "Gauntlets of Iron Will";
case "4":
return "Golden Glare";
case "5":
return "Good Time Chapeau";
case "0":
return "Miller's Grindstone";
case "8":
return "Primed to Fight";
case "6":
return "Stand Ground";
case "2":
return "Stonewall Impasse";
case "9":
return "The Golden Son";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Bare Fangs";
case "1":
return "Bare Fangs";
case "2":
return "Pack Hunt";
case "3":
return "Pack Hunt";
case "4":
return "Pack Hunt";
case "5":
return "Pound Town";
case "6":
return "Pound Town";
case "7":
return "Pound Town";
case "8":
return "Wild Ride";
case "9":
return "Wild Ride";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Beast Mode";
case "8":
return "Beast Mode";
case "9":
return "Beast Mode";
case "4":
return "Cast Bones";
case "0":
return "Monstrous Veil";
case "6":
return "No Fear";
case "1":
return "Raw Meat";
case "5":
return "Reckless Charge";
case "2":
return "Send Packing";
case "3":
return "Show No Mercy";
default: return "";
}
case "4":
switch($cardID[5]) {
case "6":
return "Betsy";
case "5":
return "Betsy, Skin in the Game";
case "1":
return "Bonebreaker Bellow";
case "2":
return "Bonebreaker Bellow";
case "3":
return "Bonebreaker Bellow";
case "9":
return "High Riser";
case "4":
return "Smashback Alehorn";
case "8":
return "Victor Goldmane";
case "7":
return "Victor Goldmane, High and Mighty";
case "0":
return "Wild Ride";
default: return "";
}
case "8":
switch($cardID[5]) {
case "3":
return "Big Bop";
case "4":
return "Big Bop";
case "5":
return "Big Bop";
case "6":
return "Bigger Than Big";
case "7":
return "Bigger Than Big";
case "8":
return "Bigger Than Big";
case "9":
return "Pint of Strong and Stout";
case "0":
return "Wallop";
case "1":
return "Wallop";
case "2":
return "Wallop";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Boast";
case "2":
return "Colossal Bearing";
case "3":
return "Lay Down the Law";
case "5":
return "Over the Top";
case "6":
return "Over the Top";
case "7":
return "Over the Top";
case "4":
return "Smack of Reality";
case "8":
return "Stacked in Your Favor";
case "9":
return "Stacked in Your Favor";
case "1":
return "Trounce";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Cintari Saber";
case "7":
return "Grains of Bloodspill";
case "9":
return "Hood of Red Sand";
case "5":
return "Hot Streak";
case "1":
return "Kassai";
case "0":
return "Kassai of the Golden Sand";
case "3":
return "Olympia";
case "2":
return "Olympia, Prized Fighter";
case "6":
return "Parry Blade";
case "8":
return "Prized Galea";
default: return "";
}
case "7":
switch($cardID[5]) {
case "1":
return "Command Respect";
case "2":
return "Command Respect";
case "3":
return "Command Respect";
case "4":
return "Concuss";
case "5":
return "Concuss";
case "6":
return "Concuss";
case "0":
return "Stacked in Your Favor";
case "7":
return "Thunk";
case "8":
return "Thunk";
case "9":
return "Thunk";
default: return "";
}
default: return "";
}
case "6":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return "Evo Magneto";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "Blaze, Firemind";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Ira, Scarlet Revenger";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Squizzy & Floof";
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return "Aegis, Archangel of Protection";
case "9":
return "Avalon, Archangel of Rebirth";
case "8":
return "Sekem, Archangel of Ravages";
case "5":
return "Suraya, Archangel of Erudition";
case "6":
return "Themis, Archangel of Judgment";
default: return "";
}
case "1":
switch($cardID[5]) {
case "2":
return "Bellona, Archangel of War";
case "0":
return "Metis, Archangel of Tenacity";
case "1":
return "Victoria, Archangel of Triumph";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return "Alluring Inducement";
case "2":
return "Bequest the Vast Beyond";
case "7":
return "Diadem of Dreamstate";
case "1":
return "Dyadic Carapace";
case "8":
return "Flicker Trick";
case "9":
return "Lost in Thought";
case "6":
return "Reality Refractor";
case "4":
return "Runechant";
case "3":
return "Runic Reckoning";
case "0":
return "Scepter of Pain";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Bastion of Unity";
case "8":
return "Chorus of Ironsong";
case "5":
return "Decimator Great Axe";
case "2":
return "Dig Up Dinner";
case "7":
return "Ironsong Versus";
case "9":
return "Morlock Hill";
case "1":
return "Numbskull";
case "0":
return "Scowling Flesh Bag";
case "4":
return "Seismic Surge";
case "3":
return "Star Struck";
default: return "";
}
case "2":
switch($cardID[5]) {
case "6":
return "Censor";
case "1":
return "Crown of Providence";
case "4":
return "Frontline Gauntlets";
case "2":
return "Frontline Helm";
case "5":
return "Frontline Legs";
case "3":
return "Frontline Plating";
case "9":
return "Hack to Reality";
case "8":
return "Hold the Line";
case "7":
return "Mischievous Meeps";
case "0":
return "Spectral Shield";
default: return "";
}
case "3":
switch($cardID[5]) {
case "2":
return "Courage";
case "3":
return "Eloquence";
case "1":
return "Poison the Well";
case "4":
return "Quicken";
case "5":
return "Spellbane Aegis";
case "0":
return "Warmonger's Diplomacy";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "2":
return "Angelic Descent";
case "3":
return "Angelic Descent";
case "4":
return "Angelic Descent";
case "5":
return "Angelic Wrath";
case "6":
return "Angelic Wrath";
case "7":
return "Angelic Wrath";
case "8":
return "Celestial Reprimand";
case "9":
return "Celestial Reprimand";
case "0":
return "Wartune Herald";
case "1":
return "Wartune Herald";
default: return "";
}
case "4":
switch($cardID[5]) {
case "8":
return "Banneret of Courage";
case "9":
return "Banneret of Gallantry";
case "6":
return "Beaming Blade";
case "5":
return "Boltyn";
case "0":
return "Celestial Reprimand";
case "1":
return "Celestial Resolve";
case "2":
return "Celestial Resolve";
case "3":
return "Celestial Resolve";
case "4":
return "Ser Boltyn, Breaker of Dawn";
case "7":
return "Soulbond Resolve";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Banneret of Protection";
case "4":
return "Banneret of Resilience";
case "5":
return "Banneret of Salvation";
case "6":
return "Banneret of Vigor";
case "7":
return "Beaming Bravado";
case "8":
return "Beaming Bravado";
case "9":
return "Beaming Bravado";
case "1":
return "Beckoning Light";
case "3":
return "Prayer of Bellona";
case "2":
return "Spirit of War";
default: return "";
}
case "8":
switch($cardID[5]) {
case "5":
return "Blessing of Salvation";
case "6":
return "Blessing of Salvation";
case "7":
return "Blessing of Salvation";
case "8":
return "Cleansing Light";
case "9":
return "Cleansing Light";
case "2":
return "Lay to Rest";
case "3":
return "Lay to Rest";
case "4":
return "Lay to Rest";
case "0":
return "Lumina Lance";
case "1":
return "Radiant Forcefield";
default: return "";
}
case "9":
switch($cardID[5]) {
case "1":
return "Blistering Assault";
case "2":
return "Blistering Assault";
case "3":
return "Blistering Assault";
case "0":
return "Cleansing Light";
case "4":
return "Defender of Daybreak";
case "5":
return "Defender of Daybreak";
case "6":
return "Defender of Daybreak";
case "7":
return "Searing Ray";
case "8":
return "Searing Ray";
case "9":
return "Searing Ray";
default: return "";
}
case "7":
switch($cardID[5]) {
case "2":
return "Charge of the Light Brigade";
case "3":
return "Charge of the Light Brigade";
case "4":
return "Charge of the Light Brigade";
case "8":
return "Radiant Flow";
case "6":
return "Radiant Raiment";
case "7":
return "Radiant Touch";
case "5":
return "Radiant View";
case "0":
return "Resounding Courage";
case "1":
return "Resounding Courage";
case "9":
return "United We Stand";
default: return "";
}
case "0":
switch($cardID[5]) {
case "4":
return "Empyrean Rapture";
case "5":
return "Figment of Erudition";
case "6":
return "Figment of Judgment";
case "7":
return "Figment of Protection";
case "8":
return "Figment of Ravages";
case "9":
return "Figment of Rebirth";
case "0":
return "Light of Sol";
case "3":
return "Luminaris, Celestial Fury";
case "2":
return "Prism, Advent of Thrones";
case "1":
return "Prism, Awakener of Sol";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Figment of Tenacity";
case "1":
return "Figment of Triumph";
case "2":
return "Figment of War";
case "3":
return "Herald of Judgment";
case "4":
return "Herald of Protection";
case "5":
return "Herald of Protection";
case "6":
return "Herald of Protection";
case "7":
return "Herald of Ravages";
case "8":
return "Herald of Ravages";
case "9":
return "Herald of Ravages";
default: return "";
}
case "6":
switch($cardID[5]) {
case "3":
return "Glaring Impact";
case "4":
return "Glaring Impact";
case "5":
return "Glaring Impact";
case "6":
return "Light the Way";
case "7":
return "Light the Way";
case "8":
return "Light the Way";
case "9":
return "Resounding Courage";
case "0":
return "V for Valor";
case "1":
return "V for Valor";
case "2":
return "V for Valor";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Herald of Rebirth";
case "1":
return "Herald of Rebirth";
case "2":
return "Herald of Rebirth";
case "3":
return "Herald of Tenacity";
case "4":
return "Herald of Tenacity";
case "5":
return "Herald of Tenacity";
case "6":
return "Herald of Triumph";
case "7":
return "Herald of Triumph";
case "8":
return "Herald of Triumph";
case "9":
return "Wartune Herald";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return "Anthem of Spring";
case "8":
return "Call Down the Lightning";
case "4":
return "Embodiment of Earth";
case "5":
return "Embodiment of Lightning";
case "3":
return "Nasreth, the Soul Harrower";
case "7":
return "Northern Winds";
case "9":
return "Rugged Roller";
case "0":
return "Tear Through the Portal";
case "1":
return "Tear Through the Portal";
case "2":
return "Tear Through the Portal";
default: return "";
}
case "2":
switch($cardID[5]) {
case "1":
return "Battlefield Breaker";
case "2":
return "Battlefield Breaker";
case "3":
return "Battlefield Breaker";
case "0":
return "Shaden Scream";
case "4":
return "Shaden Swing";
case "5":
return "Shaden Swing";
case "6":
return "Shaden Swing";
case "7":
return "Tribute to Demolition";
case "8":
return "Tribute to Demolition";
case "9":
return "Tribute to Demolition";
default: return "";
}
case "8":
switch($cardID[5]) {
case "7":
return "Beseech the Demigon";
case "8":
return "Beseech the Demigon";
case "9":
return "Beseech the Demigon";
case "1":
return "Soul Butcher";
case "2":
return "Soul Butcher";
case "3":
return "Soul Butcher";
case "4":
return "Soul Cleaver";
case "5":
return "Soul Cleaver";
case "6":
return "Soul Cleaver";
case "0":
return "Vile Inquisition";
default: return "";
}
case "6":
switch($cardID[5]) {
case "4":
return "Blasmophet, Levia Consumed";
case "6":
return "Cloak of Darkness";
case "9":
return "Dabble in Darkness";
case "8":
return "Dance of Darkness";
case "7":
return "Grasp of Darkness";
case "1":
return "Putrid Stirrings";
case "2":
return "Putrid Stirrings";
case "3":
return "Putrid Stirrings";
case "5":
return "Shroud of Darkness";
case "0":
return "Vantom Wraith";
default: return "";
}
case "1":
switch($cardID[5]) {
case "1":
return "Blood Dripping Frenzy";
case "0":
return "Expendable Limbs";
case "2":
return "Ram Raider";
case "3":
return "Ram Raider";
case "4":
return "Ram Raider";
case "8":
return "Shaden Scream";
case "9":
return "Shaden Scream";
case "5":
return "Wall Breaker";
case "6":
return "Wall Breaker";
case "7":
return "Wall Breaker";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Break of Dawn";
case "1":
return "Break of Dawn";
case "2":
return "Break of Dawn";
case "7":
return "Diabolic Offering";
case "5":
return "Hell Hammer";
case "4":
return "Levia";
case "3":
return "Levia, Shadowborn Abomination";
case "8":
return "Shaden Death Hydra";
case "9":
return "Slithering Shadowpede";
case "6":
return "Spoiled Skull";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Chains of Mephetis";
case "1":
return "Dimenxxional Vortex";
case "5":
return "Grim Feast";
case "6":
return "Grim Feast";
case "7":
return "Grim Feast";
case "2":
return "Hungering Demigon";
case "3":
return "Hungering Demigon";
case "4":
return "Hungering Demigon";
case "8":
return "Vile Inquisition";
case "9":
return "Vile Inquisition";
default: return "";
}
case "4":
switch($cardID[5]) {
case "3":
return "Deathly Delight";
case "4":
return "Deathly Delight";
case "5":
return "Deathly Delight";
case "6":
return "Deathly Wail";
case "7":
return "Deathly Wail";
case "8":
return "Deathly Wail";
case "9":
return "Envelop in Darkness";
case "0":
return "Funeral Moon";
case "2":
return "Oblivion";
case "1":
return "Requiem for the Damned";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Envelop in Darkness";
case "1":
return "Envelop in Darkness";
case "2":
return "Rift Skitter";
case "3":
return "Rift Skitter";
case "4":
return "Rift Skitter";
case "5":
return "Vantom Banshee";
case "6":
return "Vantom Banshee";
case "7":
return "Vantom Banshee";
case "8":
return "Vantom Wraith";
case "9":
return "Vantom Wraith";
default: return "";
}
case "3":
switch($cardID[5]) {
case "5":
return "Flail of Agony";
case "6":
return "Grimoire of the Haunt";
case "0":
return "Tribute to the Legions of Doom";
case "1":
return "Tribute to the Legions of Doom";
case "2":
return "Tribute to the Legions of Doom";
case "4":
return "Vynnset";
case "3":
return "Vynnset, Iron Maiden";
case "7":
return "Widespread Annihilation";
case "8":
return "Widespread Destruction";
case "9":
return "Widespread Ruin";
default: return "";
}
default: return "";
}
case "5":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return "Levia, Redeemed";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "7":
return "Aether Quickening";
case "8":
return "Aether Quickening";
case "9":
return "Aether Quickening";
case "6":
return "Brainstorm";
case "4":
return "Mind Warp";
case "1":
return "Runechant";
case "3":
return "Seerstone";
case "0":
return "Sky Fire Lanterns";
case "2":
return "Surgent Aethertide";
case "5":
return "Swell Tidings";
default: return "";
}
case "8":
switch($cardID[5]) {
case "2":
return "Aether Slash";
case "3":
return "Aether Slash";
case "4":
return "Aether Slash";
case "0":
return "Blessing of Occult";
case "1":
return "Blessing of Occult";
case "5":
return "Runic Reaping";
case "6":
return "Runic Reaping";
case "7":
return "Runic Reaping";
case "8":
return "Sky Fire Lanterns";
case "9":
return "Sky Fire Lanterns";
default: return "";
}
case "7":
switch($cardID[5]) {
case "1":
return "Amethyst Tiara";
case "2":
return "Annals of Sutcliffe";
case "9":
return "Blessing of Occult";
case "3":
return "Cryptic Crossing";
case "6":
return "Deathly Duet";
case "7":
return "Deathly Duet";
case "8":
return "Deathly Duet";
case "4":
return "Diabolic Ultimatum";
case "5":
return "Looming Doom";
case "0":
return "Point the Tip";
default: return "";
}
case "3":
switch($cardID[5]) {
case "3":
return "Annihilate the Armed";
case "4":
return "Annihilate the Armed";
case "5":
return "Annihilate the Armed";
case "6":
return "Fleece the Frail";
case "7":
return "Fleece the Frail";
case "8":
return "Fleece the Frail";
case "9":
return "Nix the Nimble";
case "0":
return "Shred";
case "1":
return "Shred";
case "2":
return "Shred";
default: return "";
}
case "1":
switch($cardID[5]) {
case "4":
return "Arakni";
case "3":
return "Arakni, Huntsman";
case "7":
return "Blacktek Whisperers";
case "9":
return "Eradicate";
case "0":
return "Hyper Driver";
case "1":
return "Hyper Driver";
case "2":
return "Hyper Driver";
case "8":
return "Mask of Perdition";
case "5":
return "Spider's Bite";
case "6":
return "Spider's Bite";
default: return "";
}
case "5":
switch($cardID[5]) {
case "9":
return "Blessing of Focus";
case "0":
return "Cut to the Chase";
case "5":
return "Dead Eye";
case "6":
return "Drill Shot";
case "7":
return "Drill Shot";
case "8":
return "Drill Shot";
case "3":
return "Heat Seeker";
case "2":
return "Hornet's Sting";
case "4":
return "Immobilizing Shot";
case "1":
return "Sandscour Greatbow";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Blessing of Focus";
case "1":
return "Blessing of Focus";
case "2":
return "Hemorrhage Bore";
case "3":
return "Hemorrhage Bore";
case "4":
return "Hemorrhage Bore";
case "5":
return "Long Shot";
case "6":
return "Long Shot";
case "7":
return "Long Shot";
case "8":
return "Point the Tip";
case "9":
return "Point the Tip";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Blessing of Ingenuity";
case "1":
return "Crankshaft";
case "2":
return "Crankshaft";
case "3":
return "Crankshaft";
case "4":
return "Jump Start";
case "5":
return "Jump Start";
case "6":
return "Jump Start";
case "7":
return "Urgent Delivery";
case "8":
return "Urgent Delivery";
case "9":
return "Urgent Delivery";
default: return "";
}
case "4":
switch($cardID[5]) {
case "8":
return "Cut to the Chase";
case "9":
return "Cut to the Chase";
case "0":
return "Nix the Nimble";
case "1":
return "Nix the Nimble";
case "2":
return "Sack the Shifty";
case "3":
return "Sack the Shifty";
case "4":
return "Sack the Shifty";
case "5":
return "Slay the Scholars";
case "6":
return "Slay the Scholars";
case "7":
return "Slay the Scholars";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Leave No Witnesses";
case "3":
return "Pay Day";
case "4":
return "Plunder the Poor";
case "5":
return "Plunder the Poor";
case "6":
return "Plunder the Poor";
case "1":
return "Regicide";
case "7":
return "Rob the Rich";
case "8":
return "Rob the Rich";
case "9":
return "Rob the Rich";
case "2":
return "Surgical Extraction";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "Beaten Trackers";
case "9":
return "Berserk";
case "0":
return "Command and Conquer";
case "2":
return "Dust from the Golden Plains";
case "3":
return "Dust from the Red Desert";
case "4":
return "Dust from the Shadow Crypts";
case "1":
return "Emperor, Dracai of Aesir";
case "5":
return "Rok";
case "7":
return "Savage Beatdown";
case "8":
return "Skull Crack";
default: return "";
}
case "9":
switch($cardID[5]) {
case "1":
return "Bios Update";
case "8":
return "Blessing of Ingenuity";
case "9":
return "Blessing of Ingenuity";
case "2":
return "Construct Nitro Mechanoid";
case "3":
return "Plasma Mainline";
case "4":
return "Powder Keg";
case "0":
return "Pulsewave Harpoon";
case "5":
return "Scramble Pulse";
case "6":
return "Scramble Pulse";
case "7":
return "Scramble Pulse";
default: return "";
}
case "4":
switch($cardID[5]) {
case "5":
return "Blazen Yoroi";
case "8":
return "Mindstate of Tiger";
case "0":
return "Reinforce Steel";
case "1":
return "Reinforce Steel";
case "9":
return "Roar of the Tiger";
case "6":
return "Tearing Shuko";
case "7":
return "Tiger Swipe";
case "2":
return "Withstand";
case "3":
return "Withstand";
case "4":
return "Withstand";
default: return "";
}
case "3":
switch($cardID[5]) {
case "3":
return "Blessing of Patience";
case "4":
return "Blessing of Patience";
case "5":
return "Blessing of Patience";
case "9":
return "Reinforce Steel";
case "0":
return "Shield Bash";
case "1":
return "Shield Bash";
case "2":
return "Shield Bash";
case "6":
return "Shield Wall";
case "7":
return "Shield Wall";
case "8":
return "Shield Wall";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Blessing of Qi";
case "4":
return "Blessing of Qi";
case "5":
return "Blessing of Qi";
case "0":
return "Flex Claws";
case "1":
return "Flex Claws";
case "2":
return "Flex Claws";
case "6":
return "Pouncing Qi";
case "7":
return "Pouncing Qi";
case "8":
return "Pouncing Qi";
case "9":
return "Qi Unleashed";
default: return "";
}
case "1":
switch($cardID[5]) {
case "3":
return "Blessing of Savagery";
case "4":
return "Blessing of Savagery";
case "5":
return "Blessing of Savagery";
case "6":
return "Madcap Charger";
case "7":
return "Madcap Charger";
case "8":
return "Madcap Charger";
case "9":
return "Madcap Muscle";
case "0":
return "Reincarnate";
case "1":
return "Reincarnate";
case "2":
return "Reincarnate";
default: return "";
}
case "7":
switch($cardID[5]) {
case "3":
return "Blessing of Steel";
case "4":
return "Blessing of Steel";
case "5":
return "Blessing of Steel";
case "1":
return "Cleave";
case "2":
return "Ironsong Pride";
case "6":
return "Precision Press";
case "7":
return "Precision Press";
case "8":
return "Precision Press";
case "9":
return "Puncture";
case "0":
return "Quicksilver Dagger";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Buckle";
case "0":
return "Madcap Muscle";
case "1":
return "Madcap Muscle";
case "9":
return "Never Yield";
case "2":
return "Rumble Grunting";
case "3":
return "Rumble Grunting";
case "4":
return "Rumble Grunting";
case "6":
return "Seasoned Saviour";
case "7":
return "Steelbraid Buckler";
case "5":
return "Yoji, Royal Protector";
default: return "";
}
case "6":
switch($cardID[5]) {
case "5":
return "Crouching Tiger";
case "7":
return "Jubeel, Spellbane";
case "8":
return "Merciless Battleaxe";
case "2":
return "Predatory Streak";
case "3":
return "Predatory Streak";
case "4":
return "Predatory Streak";
case "0":
return "Qi Unleashed";
case "1":
return "Qi Unleashed";
case "9":
return "Quicksilver Dagger";
case "6":
return "Spirit of Eirina";
default: return "";
}
case "8":
switch($cardID[5]) {
case "2":
return "Felling Swing";
case "3":
return "Felling Swing";
case "4":
return "Felling Swing";
case "9":
return "Galvanic Bender";
case "8":
return "Hanabi Blaster";
case "0":
return "Puncture";
case "1":
return "Puncture";
case "5":
return "Visit the Imperial Forge";
case "6":
return "Visit the Imperial Forge";
case "7":
return "Visit the Imperial Forge";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Blessing of Aether";
case "1":
return "Blessing of Aether";
case "2":
return "Blessing of Aether";
case "3":
return "Prognosticate";
case "4":
return "Prognosticate";
case "5":
return "Prognosticate";
case "6":
return "Sap";
case "7":
return "Sap";
case "8":
return "Sap";
case "9":
return "Tempest Aurora";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Blessing of Spirits";
case "9":
return "Blessing of Spirits";
case "3":
return "Celestial Kimono";
case "2":
return "Invoke Suraya";
case "5":
return "Phantasmal Symbiosis";
case "6":
return "Spectral Procession";
case "0":
return "Tempest Aurora";
case "1":
return "Tempest Aurora";
case "7":
return "Tome of Aeo";
case "4":
return "Wave of Reality";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Blessing of Spirits";
case "4":
return "Spectral Prowler";
case "5":
return "Spectral Prowler";
case "6":
return "Spectral Prowler";
case "7":
return "Spectral Rider";
case "8":
return "Spectral Rider";
case "9":
return "Spectral Rider";
case "1":
return "Tranquil Passing";
case "2":
return "Tranquil Passing";
case "3":
return "Tranquil Passing";
default: return "";
}
case "3":
switch($cardID[5]) {
case "4":
return "Crown of Dominion";
case "5":
return "Ornate Tessen";
case "3":
return "Spectral Shield";
case "7":
return "Spell Fray Cloak";
case "8":
return "Spell Fray Gloves";
case "9":
return "Spell Fray Leggings";
case "6":
return "Spell Fray Tiara";
case "0":
return "Water Glow Lanterns";
case "1":
return "Water Glow Lanterns";
case "2":
return "Water Glow Lanterns";
default: return "";
}
case "4":
switch($cardID[5]) {
case "3":
return "Gold";
case "0":
return "Imperial Edict";
case "1":
return "Imperial Ledger";
case "2":
return "Imperial Warhorn";
case "4":
return "Ponder";
case "5":
return "Silver";
case "6":
return "Spellbane Aegis";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return "Nitro Mechanoid";
default: return "";
}
default: return "";
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "Suraya, Archangel of Knowledge";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "Blade Flash";
case "6":
return "Flock of the Feather Walkers";
case "1":
return "Hit and Run";
case "0":
return "Out for Blood";
case "8":
return "Quicken";
case "5":
return "Sigil of Solace";
case "7":
return "Titanium Bauble";
case "4":
return "Toughen Up";
case "2":
return "Visit the Blacksmith";
default: return "";
}
case "0":
switch($cardID[5]) {
case "4":
return "Blossom of Spring";
case "2":
return "Dawnblade, Resplendent";
case "1":
return "Dorinthea, Quicksilver Prodigy";
case "9":
return "En Garde";
case "5":
return "Gallantry Gold";
case "8":
return "Glistening Steelblade";
case "7":
return "Hala Goldenhelm";
case "3":
return "Ironrot Helm";
case "6":
return "Ironrot Legs";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Driving Blade";
case "5":
return "In the Swing";
case "6":
return "Ironsong Response";
case "9":
return "On a Knife Edge";
case "3":
return "Run Through";
case "0":
return "Second Swing";
case "2":
return "Sharpen Steel";
case "8":
return "Slice and Dice";
case "4":
return "Thrust";
case "1":
return "Warrior's Valor";
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return "Aether Ashwing";
case "3":
return "Ash";
case "5":
return "Fai";
case "4":
return "Fai, Rising Rebellion";
case "7":
return "Heat Wave";
case "8":
return "Phoenix Form";
case "0":
return "Sand Cover";
case "1":
return "Sand Cover";
case "6":
return "Searing Emberblade";
case "9":
return "Spreading Flames";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Billowing Mirage";
case "9":
return "Billowing Mirage";
case "0":
return "Invoke Cromai";
case "1":
return "Invoke Kyloria";
case "2":
return "Invoke Miragai";
case "3":
return "Invoke Nekria";
case "4":
return "Invoke Ouvia";
case "5":
return "Invoke Themai";
case "6":
return "Invoke Vynserakai";
case "7":
return "Invoke Yendurai";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Billowing Mirage";
case "1":
return "Dunebreaker Cenipai";
case "2":
return "Dunebreaker Cenipai";
case "3":
return "Dunebreaker Cenipai";
case "4":
return "Dustup";
case "5":
return "Dustup";
case "6":
return "Dustup";
case "7":
return "Embermaw Cenipai";
case "8":
return "Embermaw Cenipai";
case "9":
return "Embermaw Cenipai";
default: return "";
}
case "9":
switch($cardID[5]) {
case "2":
return "Blaze Headlong";
case "3":
return "Breaking Point";
case "4":
return "Burn Away";
case "5":
return "Flameborn Retribution";
case "6":
return "Flamecall Awakening";
case "7":
return "Inflame";
case "8":
return "Lava Burst";
case "0":
return "Red Hot";
case "1":
return "Rise Up";
case "9":
return "Searing Touch";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Blood of the Dracai";
case "5":
return "Burn Them All";
case "2":
return "Dromai";
case "1":
return "Dromai, Ash Artist";
case "9":
return "Invoke Azvolai";
case "8":
return "Invoke Dominia";
case "6":
return "Invoke Dracona Optimai";
case "7":
return "Invoke Tomeltai";
case "4":
return "Silken Form";
case "3":
return "Storm of Sandikai";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Brand with Cinderclaw";
case "1":
return "Brand with Cinderclaw";
case "2":
return "Brand with Cinderclaw";
case "3":
return "Cinderskin Devotion";
case "4":
return "Cinderskin Devotion";
case "5":
return "Cinderskin Devotion";
case "6":
return "Dust Runner Outlaw";
case "7":
return "Dust Runner Outlaw";
case "8":
return "Dust Runner Outlaw";
case "9":
return "Lava Vein Loyalty";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Combustion Point";
case "1":
return "Engulfing Flamewave";
case "2":
return "Engulfing Flamewave";
case "3":
return "Engulfing Flamewave";
case "4":
return "Mounting Anger";
case "5":
return "Mounting Anger";
case "6":
return "Mounting Anger";
case "7":
return "Rise from the Ashes";
case "8":
return "Rise from the Ashes";
case "9":
return "Rise from the Ashes";
default: return "";
}
case "8":
switch($cardID[5]) {
case "4":
return "Flamescale Furnace";
case "7":
return "Liquefy";
case "0":
return "Ronin Renegade";
case "5":
return "Sash of Sandikai";
case "1":
return "Soaring Strike";
case "2":
return "Soaring Strike";
case "3":
return "Soaring Strike";
case "6":
return "Thaw";
case "9":
return "Tome of Firebrand";
case "8":
return "Uprising";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Lava Vein Loyalty";
case "1":
return "Lava Vein Loyalty";
case "2":
return "Rebellious Rush";
case "3":
return "Rebellious Rush";
case "4":
return "Rebellious Rush";
case "5":
return "Rising Resentment";
case "6":
return "Rising Resentment";
case "7":
return "Rising Resentment";
case "8":
return "Ronin Renegade";
case "9":
return "Ronin Renegade";
default: return "";
}
case "3":
switch($cardID[5]) {
case "3":
return "Rake the Embers";
case "4":
return "Rake the Embers";
case "5":
return "Rake the Embers";
case "9":
return "Sand Cover";
case "6":
return "Skittering Sands";
case "7":
return "Skittering Sands";
case "8":
return "Skittering Sands";
case "0":
return "Sweeping Blow";
case "1":
return "Sweeping Blow";
case "2":
return "Sweeping Blow";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "3":
return "Aether Dart";
case "4":
return "Aether Dart";
case "5":
return "Aether Dart";
case "0":
return "Dampen";
case "1":
return "Dampen";
case "2":
return "Dampen";
case "6":
return "Read the Ripples";
case "7":
return "Read the Ripples";
case "8":
return "Read the Ripples";
case "9":
return "Singe";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Aether Hail";
case "8":
return "Aether Hail";
case "9":
return "Aether Hail";
case "5":
return "Conduit of Frostburn";
case "6":
return "Frost Hex";
case "0":
return "Icebind";
case "1":
return "Icebind";
case "2":
return "Polar Cap";
case "3":
return "Polar Cap";
case "4":
return "Polar Cap";
default: return "";
}
case "1":
switch($cardID[5]) {
case "3":
return "Aether Icevein";
case "4":
return "Aether Icevein";
case "5":
return "Aether Icevein";
case "6":
return "Brain Freeze";
case "7":
return "Brain Freeze";
case "8":
return "Brain Freeze";
case "9":
return "Icebind";
case "0":
return "Succumb to Winter";
case "1":
return "Succumb to Winter";
case "2":
return "Succumb to Winter";
default: return "";
}
case "6":
switch($cardID[5]) {
case "6":
return "Alluvion Constellas";
case "0":
return "Double Strike";
case "2":
return "Rapid Reflex";
case "3":
return "Rapid Reflex";
case "4":
return "Rapid Reflex";
case "9":
return "Rewind";
case "7":
return "Spellfire Cloak";
case "1":
return "Take the Tempo";
case "8":
return "Tome of Duplicity";
case "5":
return "Waning Moon";
default: return "";
}
case "4":
switch($cardID[5]) {
case "4":
return "Arctic Incarceration";
case "5":
return "Arctic Incarceration";
case "6":
return "Arctic Incarceration";
case "7":
return "Cold Snap";
case "8":
return "Cold Snap";
case "9":
return "Cold Snap";
case "0":
return "Insidious Chill";
case "1":
return "Isenhowl Weathervane";
case "2":
return "Isenhowl Weathervane";
case "3":
return "Isenhowl Weathervane";
default: return "";
}
case "3":
switch($cardID[5]) {
case "8":
return "Channel the Bleak Expanse";
case "6":
return "Coronet Peak";
case "0":
return "Frosting";
case "1":
return "Frosting";
case "2":
return "Frosting";
case "7":
return "Glacial Horns";
case "9":
return "Hypothermia";
case "3":
return "Ice Bolt";
case "4":
return "Ice Bolt";
case "5":
return "Ice Bolt";
default: return "";
}
case "8":
switch($cardID[5]) {
case "2":
return "Crown of Providence";
case "7":
return "Erase Face";
case "3":
return "Helio's Mitre";
case "4":
return "Quelling Robe";
case "5":
return "Quelling Sleeves";
case "6":
return "Quelling Slippers";
case "0":
return "Singe";
case "1":
return "Singe";
case "9":
return "That All You Got?";
case "8":
return "Vipox";
default: return "";
}
case "0":
switch($cardID[5]) {
case "4":
return "Encase";
case "5":
return "Freezing Point";
case "9":
return "Ice Eternal";
case "3":
return "Iyslander";
case "2":
return "Iyslander, Stormbind";
case "1":
return "Phoenix Flame";
case "6":
return "Sigil of Permafrost";
case "7":
return "Sigil of Permafrost";
case "8":
return "Sigil of Permafrost";
case "0":
return "Stoke the Flames";
default: return "";
}
case "9":
switch($cardID[5]) {
case "1":
return "Flex";
case "2":
return "Flex";
case "3":
return "Flex";
case "0":
return "Fog Down";
case "4":
return "Fyendal's Fighting Spirit";
case "5":
return "Fyendal's Fighting Spirit";
case "6":
return "Fyendal's Fighting Spirit";
case "7":
return "Sift";
case "8":
return "Sift";
case "9":
return "Sift";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Frightmare";
case "0":
return "Frostbite";
case "1":
return "Ghostly Touch";
case "4":
return "Semblance";
case "2":
return "Silent Stilettos";
case "9":
return "Tide Flippers";
case "8":
return "Tiger Stripe Shuko";
case "5":
return "Transmogrify";
case "6":
return "Transmogrify";
case "7":
return "Transmogrify";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "Azvolai";
case "8":
return "Dominia";
case "6":
return "Dracona Optimai";
case "7":
return "Tomeltai";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Cromai";
case "1":
return "Kyloria";
case "2":
return "Miragai";
case "3":
return "Nekria";
case "4":
return "Ouvia";
case "5":
return "Themai";
case "6":
return "Vynserakai";
case "7":
return "Yendurai";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "Brothers in Arms";
case "4":
return "Brothers in Arms";
case "5":
return "Brothers in Arms";
case "6":
return "Critical Strike";
case "7":
return "Critical Strike";
case "8":
return "Critical Strike";
case "9":
return "Scar for a Scar";
case "0":
return "Strategic Planning";
case "1":
return "Strategic Planning";
case "2":
return "Strategic Planning";
default: return "";
}
case "2":
switch($cardID[5]) {
case "4":
return "Cracked Bauble";
case "5":
return "Dragons of Legend";
case "1":
return "Oasis Respite";
case "2":
return "Oasis Respite";
case "3":
return "Oasis Respite";
case "0":
return "Sigil of Protection";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Healing Balm";
case "6":
return "Healing Balm";
case "7":
return "Healing Balm";
case "0":
return "Scar for a Scar";
case "1":
return "Scar for a Scar";
case "8":
return "Sigil of Protection";
case "9":
return "Sigil of Protection";
case "2":
return "Trade In";
case "3":
return "Trade In";
case "4":
return "Trade In";
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "Alpha Rampage";
case "5":
return "Barkbone Strapping";
case "7":
return "Bloodrush Bellow";
case "0":
return "Heart of Fyendal";
case "8":
return "Reckless Swing";
case "2":
return "Rhinar";
case "1":
return "Rhinar, Reckless Rampage";
case "3":
return "Romping Club";
case "9":
return "Sand Sketched Plan";
case "4":
return "Scabskin Leathers";
default: return "";
}
case "8":
switch($cardID[5]) {
case "2":
return "Ancestral Empowerment";
case "9":
return "Blackout Kick";
case "0":
return "Breaking Scales";
case "6":
return "Fluster Fist";
case "7":
return "Fluster Fist";
case "8":
return "Fluster Fist";
case "4":
return "Hurricane Technique";
case "1":
return "Lord of Wind";
case "3":
return "Mugenshi: RELEASE";
case "5":
return "Pounding Gale";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Anothos";
case "5":
return "Cranial Crush";
case "3":
return "Crippling Crush";
case "8":
return "Disable";
case "9":
return "Disable";
case "6":
return "Forged for War";
case "2":
return "Helm of Isen's Peak";
case "7":
return "Show Time!";
case "4":
return "Spinal Crush";
case "1":
return "Tectonic Plating";
default: return "";
}
case "3":
switch($cardID[5]) {
case "2":
return "Awakening Bellow";
case "3":
return "Awakening Bellow";
case "4":
return "Awakening Bellow";
case "9":
return "Bravo";
case "8":
return "Bravo, Showstopper";
case "5":
return "Primeval Bellow";
case "6":
return "Primeval Bellow";
case "7":
return "Primeval Bellow";
case "0":
return "Wrecker Romp";
case "1":
return "Wrecker Romp";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Barraging Beatdown";
case "8":
return "Barraging Beatdown";
case "9":
return "Barraging Beatdown";
case "0":
return "Bone Head Barrier";
case "1":
return "Breakneck Battery";
case "2":
return "Breakneck Battery";
case "3":
return "Breakneck Battery";
case "4":
return "Savage Feast";
case "5":
return "Savage Feast";
case "6":
return "Savage Feast";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Blackout Kick";
case "1":
return "Blackout Kick";
case "2":
return "Flic Flak";
case "3":
return "Flic Flak";
case "4":
return "Flic Flak";
case "8":
return "Head Jab";
case "9":
return "Head Jab";
case "5":
return "Open the Center";
case "6":
return "Open the Center";
case "7":
return "Open the Center";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Blessing of Deliverance";
case "5":
return "Blessing of Deliverance";
case "6":
return "Blessing of Deliverance";
case "7":
return "Buckling Blow";
case "8":
return "Buckling Blow";
case "9":
return "Buckling Blow";
case "0":
return "Disable";
case "1":
return "Staunch Response";
case "2":
return "Staunch Response";
case "3":
return "Staunch Response";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Cartilage Crush";
case "1":
return "Cartilage Crush";
case "2":
return "Cartilage Crush";
case "3":
return "Crush Confidence";
case "4":
return "Crush Confidence";
case "5":
return "Crush Confidence";
case "6":
return "Debilitate";
case "7":
return "Debilitate";
case "8":
return "Debilitate";
case "9":
return "Emerging Power";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Emerging Power";
case "1":
return "Emerging Power";
case "8":
return "Harmonized Kodachi";
case "7":
return "Katsu";
case "6":
return "Katsu, the Wanderer";
case "9":
return "Mask of Momentum";
case "5":
return "Seismic Surge";
case "2":
return "Stonewall Confidence";
case "3":
return "Stonewall Confidence";
case "4":
return "Stonewall Confidence";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Pack Hunt";
case "4":
return "Pack Hunt";
case "5":
return "Pack Hunt";
case "0":
return "Savage Swing";
case "1":
return "Savage Swing";
case "2":
return "Savage Swing";
case "6":
return "Smash Instinct";
case "7":
return "Smash Instinct";
case "8":
return "Smash Instinct";
case "9":
return "Wrecker Romp";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "Barraging Brawnhide";
case "7":
return "Barraging Brawnhide";
case "8":
return "Barraging Brawnhide";
case "9":
return "Demolition Crew";
case "0":
return "Energy Potion";
case "1":
return "Potion of Strength";
case "3":
return "Sigil of Solace";
case "4":
return "Sigil of Solace";
case "5":
return "Sigil of Solace";
case "2":
return "Timesnap Potion";
default: return "";
}
case "3":
switch($cardID[5]) {
case "5":
return "Biting Blade";
case "6":
return "Biting Blade";
case "7":
return "Biting Blade";
case "2":
return "Ironsong Response";
case "3":
return "Ironsong Response";
case "4":
return "Ironsong Response";
case "8":
return "Stroke of Foresight";
case "9":
return "Stroke of Foresight";
case "0":
return "Warrior's Valor";
case "1":
return "Warrior's Valor";
default: return "";
}
case "1":
switch($cardID[5]) {
case "6":
return "Braveforge Bracers";
case "5":
return "Dawnblade";
case "4":
return "Dorinthea";
case "3":
return "Dorinthea Ironsong";
case "8":
return "Glint the Quicksilver";
case "7":
return "Refraction Bolters";
case "9":
return "Steelblade Supremacy";
case "0":
return "Whelming Gustwave";
case "1":
return "Whelming Gustwave";
case "2":
return "Whelming Gustwave";
default: return "";
}
case "6":
switch($cardID[5]) {
case "2":
return "Crazy Brew";
case "4":
return "Drone of Brutality";
case "5":
return "Drone of Brutality";
case "6":
return "Drone of Brutality";
case "1":
return "Last Ditch Effort";
case "3":
return "Remembrance";
case "7":
return "Snatch";
case "8":
return "Snatch";
case "9":
return "Snatch";
case "0":
return "Tome of Fyendal";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Demolition Crew";
case "1":
return "Demolition Crew";
case "2":
return "Flock of the Feather Walkers";
case "3":
return "Flock of the Feather Walkers";
case "4":
return "Flock of the Feather Walkers";
case "5":
return "Nimble Strike";
case "6":
return "Nimble Strike";
case "7":
return "Nimble Strike";
case "8":
return "Raging Onslaught";
case "9":
return "Raging Onslaught";
default: return "";
}
case "4":
switch($cardID[5]) {
case "4":
return "Driving Blade";
case "5":
return "Driving Blade";
case "6":
return "Driving Blade";
case "7":
return "Nature's Path Pilgrimage";
case "8":
return "Nature's Path Pilgrimage";
case "9":
return "Nature's Path Pilgrimage";
case "1":
return "Sharpen Steel";
case "2":
return "Sharpen Steel";
case "3":
return "Sharpen Steel";
case "0":
return "Stroke of Foresight";
default: return "";
}
case "5":
switch($cardID[5]) {
case "9":
return "Enlightened Strike";
case "0":
return "Fyendal's Spring Tunic";
case "3":
return "Goliath Gauntlet";
case "2":
return "Heartened Cross Strap";
case "1":
return "Hope Merchant's Hood";
case "7":
return "Ironrot Gauntlet";
case "5":
return "Ironrot Helm";
case "8":
return "Ironrot Legs";
case "6":
return "Ironrot Plate";
case "4":
return "Snapdragon Scalers";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Head Jab";
case "1":
return "Leg Tap";
case "2":
return "Leg Tap";
case "3":
return "Leg Tap";
case "4":
return "Rising Knee Thrust";
case "5":
return "Rising Knee Thrust";
case "6":
return "Rising Knee Thrust";
case "7":
return "Surging Strike";
case "8":
return "Surging Strike";
case "9":
return "Surging Strike";
default: return "";
}
case "2":
switch($cardID[5]) {
case "2":
return "Ironsong Determination";
case "3":
return "Overpower";
case "4":
return "Overpower";
case "5":
return "Overpower";
case "0":
return "Rout";
case "1":
return "Singing Steelblade";
case "6":
return "Steelblade Shunt";
case "7":
return "Steelblade Shunt";
case "8":
return "Steelblade Shunt";
case "9":
return "Warrior's Valor";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Raging Onslaught";
case "7":
return "Regurgitating Slog";
case "8":
return "Regurgitating Slog";
case "9":
return "Regurgitating Slog";
case "1":
return "Scar for a Scar";
case "2":
return "Scar for a Scar";
case "3":
return "Scar for a Scar";
case "4":
return "Scour the Battlescape";
case "5":
return "Scour the Battlescape";
case "6":
return "Scour the Battlescape";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "Cracked Bauble";
case "0":
return "Nimblism";
case "5":
return "Quicken";
case "1":
return "Sloggism";
case "2":
return "Sloggism";
case "3":
return "Sloggism";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Nimblism";
case "9":
return "Nimblism";
case "0":
return "Razor Reflex";
case "1":
return "Razor Reflex";
case "5":
return "Sink Below";
case "6":
return "Sink Below";
case "7":
return "Sink Below";
case "2":
return "Unmovable";
case "3":
return "Unmovable";
case "4":
return "Unmovable";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Pummel";
case "7":
return "Pummel";
case "8":
return "Pummel";
case "9":
return "Razor Reflex";
case "0":
return "Wounded Bull";
case "1":
return "Wounded Bull";
case "2":
return "Wounded Bull";
case "3":
return "Wounding Blow";
case "4":
return "Wounding Blow";
case "5":
return "Wounding Blow";
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "3":
return "Amnesia";
case "7":
return "Burdens of the Past";
case "4":
return "Down and Dirty";
case "1":
return "Fisticuffs";
case "2":
return "Fleet Foot Sandals";
case "5":
return "Give and Take";
case "6":
return "Gore Belching";
case "9":
return "Humble";
case "8":
return "Premeditate";
case "0":
return "Threadbare Tunic";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Amplifying Arrow";
case "1":
return "Barbed Undertow";
case "6":
return "Boulder Trap";
case "2":
return "Buzzsaw Trap";
case "3":
return "Collapsing Trap";
case "9":
return "Fletch a Red Tail";
case "5":
return "Melting Point";
case "7":
return "Pendulum Trap";
case "4":
return "Spike Pit Trap";
case "8":
return "Tarpit Trap";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Blade Cuff";
case "5":
return "Bleed Out";
case "6":
return "Bleed Out";
case "7":
return "Bleed Out";
case "3":
return "Concealed Blade";
case "8":
return "Hurl";
case "9":
return "Hurl";
case "4":
return "Knives Out";
case "0":
return "Mask of Shifting Perspectives";
case "2":
return "Stab Wound";
default: return "";
}
case "7":
switch($cardID[5]) {
case "1":
return "Bloodrot Trap";
case "2":
return "Frailty Trap";
case "3":
return "Inertia Trap";
case "6":
return "Seeker's Gilet";
case "5":
return "Seeker's Hood";
case "8":
return "Seeker's Leggings";
case "7":
return "Seeker's Mitts";
case "9":
return "Silken Gi";
case "4":
return "Vambrace of Determination";
case "0":
return "Virulent Touch";
default: return "";
}
case "5":
switch($cardID[5]) {
case "9":
return "Codex of Bloodrot";
case "0":
return "Hurl";
case "7":
return "Mask of Malicious Manifestations";
case "1":
return "Plunge";
case "2":
return "Plunge";
case "3":
return "Plunge";
case "4":
return "Short and Sharp";
case "5":
return "Short and Sharp";
case "6":
return "Short and Sharp";
case "8":
return "Toxic Tips";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Codex of Frailty";
case "1":
return "Codex of Inertia";
case "2":
return "Death Touch";
case "3":
return "Death Touch";
case "4":
return "Death Touch";
case "5":
return "Toxicity";
case "6":
return "Toxicity";
case "7":
return "Toxicity";
case "8":
return "Virulent Touch";
case "9":
return "Virulent Touch";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Falcon Wing";
case "6":
return "Falcon Wing";
case "7":
return "Falcon Wing";
case "1":
return "Fletch a Blue Tail";
case "0":
return "Fletch a Yellow Tail";
case "8":
return "Infecting Shot";
case "9":
return "Infecting Shot";
case "2":
return "Lace with Bloodrot";
case "3":
return "Lace with Frailty";
case "4":
return "Lace with Inertia";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Flick Knives";
case "0":
return "Spire Sniping";
case "1":
return "Spire Sniping";
case "2":
return "Spire Sniping";
case "3":
return "Widowmaker";
case "4":
return "Widowmaker";
case "5":
return "Widowmaker";
case "6":
return "Withering Shot";
case "7":
return "Withering Shot";
case "8":
return "Withering Shot";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Humble";
case "1":
return "Humble";
case "2":
return "Infectious Host";
case "3":
return "Infectious Host";
case "4":
return "Infectious Host";
case "5":
return "Looking for a Scrap";
case "6":
return "Looking for a Scrap";
case "7":
return "Looking for a Scrap";
case "8":
return "Wreck Havoc";
case "9":
return "Wreck Havoc";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Infecting Shot";
case "1":
return "Murkmire Grapnel";
case "2":
return "Murkmire Grapnel";
case "3":
return "Murkmire Grapnel";
case "4":
return "Sedation Shot";
case "5":
return "Sedation Shot";
case "6":
return "Sedation Shot";
case "7":
return "Skybound Shot";
case "8":
return "Skybound Shot";
case "9":
return "Skybound Shot";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "Arakni, Solitary Confinement";
case "5":
return "Nerve Scalpel";
case "6":
return "Nerve Scalpel";
case "7":
return "Orbitoclast";
case "8":
return "Orbitoclast";
case "0":
return "Plague Hive";
case "9":
return "Scale Peeler";
case "4":
return "Spider's Bite";
case "2":
return "Uzuri";
case "1":
return "Uzuri, Switchblade";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Azalea";
case "3":
return "Barbed Castaway";
case "7":
return "Crow's Nest";
case "8":
return "Driftwood Quiver";
case "5":
return "Quiver of Abyssal Depths";
case "6":
return "Quiver of Rustling Leaves";
case "2":
return "Riptide";
case "1":
return "Riptide, Lurker of the Deep";
case "4":
return "Trench of Sunken Treasure";
case "9":
return "Wayfinder's Crest";
default: return "";
}
case "8":
switch($cardID[5]) {
case "9":
return "Azalea, Ace in the Hole";
case "0":
return "One-Two Punch";
case "1":
return "One-Two Punch";
case "2":
return "One-Two Punch";
case "3":
return "Surging Strike";
case "4":
return "Surging Strike";
case "5":
return "Surging Strike";
case "6":
return "Twin Twisters";
case "7":
return "Twin Twisters";
case "8":
return "Twin Twisters";
default: return "";
}
case "6":
switch($cardID[5]) {
case "5":
return "Back Heel Kick";
case "6":
return "Back Heel Kick";
case "7":
return "Back Heel Kick";
case "8":
return "Be Like Water";
case "9":
return "Be Like Water";
case "0":
return "Recoil";
case "1":
return "Recoil";
case "2":
return "Spinning Wheel Kick";
case "3":
return "Spinning Wheel Kick";
case "4":
return "Spinning Wheel Kick";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Back Stab";
case "6":
return "Back Stab";
case "7":
return "Back Stab";
case "2":
return "Infiltrate";
case "1":
return "Redback Shroud";
case "0":
return "Scale Peeler";
case "3":
return "Shake Down";
case "8":
return "Sneak Attack";
case "9":
return "Sneak Attack";
case "4":
return "Spreading Plague";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Be Like Water";
case "1":
return "Deadly Duo";
case "2":
return "Deadly Duo";
case "3":
return "Deadly Duo";
case "4":
return "Descendent Gustwave";
case "5":
return "Descendent Gustwave";
case "6":
return "Descendent Gustwave";
case "7":
return "Head Jab";
case "8":
return "Head Jab";
case "9":
return "Head Jab";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Benji, the Piercing Wind";
case "8":
return "Harmonized Kodachi";
case "6":
return "Katsu";
case "5":
return "Katsu, the Wanderer";
case "9":
return "Mask of Many Faces";
case "2":
return "Razor's Edge";
case "3":
return "Razor's Edge";
case "4":
return "Razor's Edge";
case "0":
return "Wither";
case "1":
return "Wither";
default: return "";
}
case "5":
switch($cardID[5]) {
case "6":
return "Bonds of Ancestry";
case "7":
return "Bonds of Ancestry";
case "8":
return "Bonds of Ancestry";
case "0":
return "Cyclone Roundhouse";
case "1":
return "Dishonor";
case "2":
return "Head Leads the Tail";
case "9":
return "Recoil";
case "4":
return "Silverwind Shuriken";
case "5":
return "Visit the Floating Dojo";
case "3":
return "Wander With Purpose";
default: return "";
}
case "2":
switch($cardID[5]) {
case "4":
return "Infect";
case "5":
return "Infect";
case "6":
return "Infect";
case "7":
return "Isolate";
case "8":
return "Isolate";
case "9":
return "Isolate";
case "0":
return "Sneak Attack";
case "1":
return "Spike with Bloodrot";
case "2":
return "Spike with Frailty";
case "3":
return "Spike with Inertia";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Malign";
case "1":
return "Malign";
case "2":
return "Malign";
case "3":
return "Prowl";
case "4":
return "Prowl";
case "5":
return "Prowl";
case "6":
return "Sedate";
case "7":
return "Sedate";
case "8":
return "Sedate";
case "9":
return "Wither";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return "Bloodrot Pox";
case "0":
return "Brush Off";
case "8":
return "Cracked Bauble";
case "5":
return "Frailty";
case "6":
return "Inertia";
case "1":
return "Peace of Mind";
case "2":
return "Peace of Mind";
case "3":
return "Peace of Mind";
case "7":
return "Ponder";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Brush Off";
case "9":
return "Brush Off";
case "2":
return "Come to Fight";
case "3":
return "Come to Fight";
case "4":
return "Come to Fight";
case "5":
return "Scout the Periphery";
case "6":
return "Scout the Periphery";
case "7":
return "Scout the Periphery";
case "0":
return "Spring Load";
case "1":
return "Spring Load";
default: return "";
}
case "0":
switch($cardID[5]) {
case "1":
return "Cut Down to Size";
case "2":
return "Cut Down to Size";
case "3":
return "Cut Down to Size";
case "4":
return "Destructive Deliberation";
case "5":
return "Destructive Deliberation";
case "6":
return "Destructive Deliberation";
case "7":
return "Feisty Locals";
case "8":
return "Feisty Locals";
case "9":
return "Feisty Locals";
case "0":
return "Wreck Havoc";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Freewheeling Renegades";
case "1":
return "Freewheeling Renegades";
case "2":
return "Freewheeling Renegades";
case "3":
return "Ravenous Rabble";
case "4":
return "Ravenous Rabble";
case "5":
return "Ravenous Rabble";
case "6":
return "Seek Horizon";
case "7":
return "Seek Horizon";
case "8":
return "Seek Horizon";
case "9":
return "Spring Load";
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
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "Apocalypse Automaton";
case "0":
return "Evo Rapid Fire";
case "9":
return "Firewall";
case "2":
return "Liquid-Cooled Mayhem";
case "3":
return "Mechanical Strength";
case "4":
return "Over Loop";
case "5":
return "Throttle";
case "6":
return "Under Loop";
case "7":
return "Zero to Sixty";
case "8":
return "Zipper Hit";
default: return "";
}
case "2":
switch($cardID[5]) {
case "9":
return "Bastion of Duty";
case "7":
return "Brevant, Civic Protector";
case "2":
return "Firewall";
case "6":
return "Firewall";
case "8":
return "Hammer of Havenhold";
case "3":
return "Liquid-Cooled Mayhem";
case "4":
return "Mechanical Strength";
case "0":
return "Throttle";
case "5":
return "Throttle";
case "1":
return "Zipper Hit";
default: return "";
}
case "8":
switch($cardID[5]) {
case "3":
return "Bittering Thorns";
case "0":
return "Blood Scent";
case "4":
return "Flex Claws";
case "5":
return "Flying Kick";
case "6":
return "Growl";
case "7":
return "Leg Tap";
case "8":
return "Mauling Qi";
case "2":
return "Pouncing Paws";
case "9":
return "Pouncing Qi";
case "1":
return "Tearing Shuko";
default: return "";
}
case "9":
switch($cardID[5]) {
case "2":
return "Bittering Thorns";
case "1":
return "Blessing of Qi";
case "3":
return "Flex Claws";
case "9":
return "Flying Kick";
case "4":
return "Growl";
case "5":
return "Leg Tap";
case "7":
return "Predatory Streak";
case "0":
return "Qi Unleashed";
case "6":
return "Salt the Wound";
case "8":
return "Tiger Eye Reflex";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Boulder Drop";
case "1":
return "Civic Duty";
case "2":
return "Civic Guide";
case "0":
return "Civic Peak";
case "3":
return "Civic Steps";
case "4":
return "Colossal Bearing";
case "7":
return "Crash Down";
case "8":
return "Earthlore Empowerment";
case "5":
return "Lay Down the Law";
case "6":
return "Smack of Reality";
default: return "";
}
case "4":
switch($cardID[5]) {
case "4":
return "Boulder Drop";
case "8":
return "Chivalry";
case "0":
return "Chokeslam";
case "5":
return "Chokeslam";
case "6":
return "Cranial Crush";
case "2":
return "Crash Down";
case "1":
return "Disable";
case "7":
return "Disable";
case "3":
return "Earthlore Empowerment";
case "9":
return "Melody, Sing-along";
default: return "";
}
case "5":
switch($cardID[5]) {
case "5":
return "Coax a Commotion";
case "3":
return "Fiddle-dee";
case "7":
return "Final Act";
case "2":
return "Heart-throb";
case "8":
return "Interlude";
case "0":
return "Jinglewood, Smash Hit";
case "6":
return "Life of the Party";
case "1":
return "Nom de Plume";
case "4":
return "Quickstep";
case "9":
return "Sigil of Solace";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Crazy Brew";
case "6":
return "Crowd Control";
case "8":
return "Edge of Autumn";
case "1":
return "Energy Potion";
case "2":
return "Healing Potion";
case "5":
return "Interlude";
case "7":
return "Ira, Crimson Haze";
case "9":
return "Mask of Three Tails";
case "3":
return "Potion of Strength";
case "4":
return "Timesnap Potion";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Crowd Control";
case "3":
return "Crowd Control";
case "1":
return "Encore";
case "2":
return "Interlude";
case "4":
return "Song of Jack-be-Quick";
case "5":
return "Song of Sweet Nectar";
case "9":
return "Song of Yesteryears";
case "6":
return "Song of the Rosen Matador";
case "7":
return "Song of the Shining Knight";
case "8":
return "Song of the Wandering Mind";
default: return "";
}
case "0":
switch($cardID[5]) {
case "8":
return "Evo Energy Matrix";
case "9":
return "Evo Scatter Shot";
case "7":
return "Evo Tekloscope";
case "1":
return "Professor Teklovossen";
case "5":
return "Proto Base Arms";
case "4":
return "Proto Base Chest";
case "3":
return "Proto Base Head";
case "6":
return "Proto Base Legs";
case "2":
return "Teklo Blaster";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Blessing of Qi";
case "3":
return "Copper";
case "8":
return "Copper";
case "4":
return "Crouching Tiger";
case "5":
return "Might";
case "1":
return "Predatory Streak";
case "6":
return "Quicken";
case "2":
return "Tiger Eye Reflex";
case "7":
return "Vigor";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "Evo Energy Matrix";
case "9":
return "Evo Scatter Shot";
case "7":
return "Evo Tekloscope";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Evo Rapid Fire";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return "Autumn's Touch";
case "4":
return "Bracken Rap";
case "0":
return "Concuss";
case "7":
return "Flourish";
case "1":
return "Log Fall";
case "5":
return "Log Fall";
case "8":
return "Seeds of Strength";
case "2":
return "Strong Wood";
case "6":
return "Strong Wood";
case "9":
return "Thrive";
default: return "";
}
case "2":
switch($cardID[5]) {
case "1":
return "Autumn's Touch";
case "2":
return "Burgeoning";
case "7":
return "Canopy Shelter";
case "3":
return "Concuss";
case "4":
return "Flourish";
case "8":
return "Might";
case "5":
return "Seeds of Strength";
case "0":
return "Sigil of Shelter";
case "6":
return "Sigil of Shelter";
case "9":
return "Spell Fray Tiara";
default: return "";
}
case "0":
switch($cardID[5]) {
case "5":
return "Blossom of Spring";
case "8":
return "Bracken Rap";
case "9":
return "Burgeoning";
case "6":
return "Hard Knuckle";
case "4":
return "Ironrot Helm";
case "7":
return "Ironrot Legs";
case "2":
return "Redwood Hammer";
case "3":
return "Rotten Old Buckler";
case "1":
return "Terra";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Spell Fray Leggings";
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "Arc Light Sentinel";
case "5":
return "Art of War";
case "3":
return "Cash In";
case "6":
return "Fate Foreseen";
case "7":
return "Last Ditch Effort";
case "0":
return "Proclamation of Abundance";
case "9":
return "Proclamation of Production";
case "2":
return "Pummel";
case "8":
return "Smashing Good Time";
case "4":
return "Wounded Bull";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Brush of Heavenly Rites";
case "6":
return "Chivalry";
case "4":
return "Civic Steps";
case "2":
return "Heavy Industry Power Plant";
case "1":
return "Helm of Halo's Grace";
case "5":
return "Poison the Well";
case "3":
return "Sharp Shooters";
default: return "";
}
case "2":
switch($cardID[5]) {
case "4":
return "Brutus, Summa Rudis";
case "2":
return "Chains of Eminence";
case "3":
return "Drone of Brutality";
case "9":
return "Memorial Ground";
case "7":
return "Nourishing Emptiness";
case "5":
return "Proclamation of Combat";
case "1":
return "Rouse the Ancients";
case "6":
return "Shimmers of Silver";
case "0":
return "Sink Below";
case "8":
return "Zealous Belting";
default: return "";
}
case "0":
switch($cardID[5]) {
case "5":
return "Gavel of Natural Order";
case "0":
return "Intellect Penalty";
case "9":
return "Proclamation of Abundance";
case "3":
return "Proclamation of Requisition";
case "4":
return "Proclamation of Requisition";
case "1":
return "Taipanis, Dracai of Judgement";
case "2":
return "Taipanis, Dracai of Judgement";
case "6":
return "Theryon, Magister of Justice";
case "8":
return "Theryon, Magister of Justice";
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "Jack-o'-lantern";
case "7":
return "Jack-o'-lantern";
case "8":
return "Jack-o'-lantern";
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
if($cardID !== null && strlen($cardID) < 6) return 0;
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
case "9":
switch($cardID[5]) {
case "5":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
case "0":
return 3;
case "1":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 0;
case "5":
return 3;
case "6":
return 3;
case "9":
return 3;
case "7":
return 3;
case "2":
return 0;
case "1":
return 0;
case "3":
return 0;
case "4":
return 0;
case "8":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "7":
return 0;
case "1":
return 2;
case "2":
return 3;
case "8":
return 0;
case "4":
return 2;
case "5":
return 3;
case "6":
return 0;
case "9":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "6":
return 0;
case "3":
return 0;
case "8":
return 2;
case "5":
return 0;
case "4":
return 0;
case "0":
return 0;
case "2":
return 0;
case "1":
return 0;
case "7":
return 0;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "6":
return 3;
case "4":
return 3;
case "7":
return 3;
case "0":
return 3;
case "8":
return 3;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "9":
return 3;
case "5":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "3":
return 3;
case "2":
return 3;
case "0":
return 0;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "6":
return 0;
case "5":
return 0;
case "3":
return 0;
case "8":
return 0;
case "7":
return 0;
case "4":
return 0;
case "9":
return 0;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "1":
return 3;
case "5":
return 2;
case "6":
return 3;
case "2":
return 3;
case "0":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "9":
return 0;
case "8":
return 0;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 0;
case "6":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 3;
case "7":
return 2;
case "3":
return 3;
case "4":
return 3;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "6":
return 3;
case "5":
return 2;
case "0":
return 0;
case "2":
return 2;
case "8":
return 2;
case "9":
return 3;
case "3":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "0":
return 0;
case "9":
return 3;
case "8":
return 2;
case "2":
return 3;
case "3":
return 2;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "3":
return 2;
case "1":
return 3;
case "0":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "8":
return 0;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "8":
return 0;
case "9":
return 0;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "4":
return 2;
case "0":
return 3;
case "9":
return 3;
case "8":
return 3;
case "2":
return 2;
case "3":
return 3;
case "7":
return 3;
case "5":
return 3;
case "6":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "8":
return 0;
case "0":
return 3;
case "1":
return 3;
case "2":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
default: return 1;
}
case "6":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return 3;
case "8":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
default: return 1;
}
default: return 1;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "2":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "3":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
default: return 1;
}
default: return 1;
}
case "5":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 0;
case "6":
return 2;
case "7":
return 3;
case "8":
return 0;
case "9":
return 0;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "9":
return 0;
case "6":
return 2;
case "7":
return 3;
case "8":
return 3;
case "3":
return 2;
case "4":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "9":
return 0;
case "4":
return 2;
case "5":
return 3;
case "1":
return 0;
case "7":
return 2;
case "8":
return 3;
case "2":
return 3;
case "0":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "3":
return 0;
case "1":
return 0;
case "4":
return 0;
case "2":
return 0;
case "9":
return 2;
case "7":
return 3;
case "0":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 3;
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "0":
return 0;
case "4":
return 2;
case "7":
return 2;
case "9":
return 2;
case "3":
return 0;
case "2":
return 0;
case "1":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "3":
return 2;
case "2":
return 2;
case "0":
return 0;
case "4":
return 2;
case "1":
return 0;
case "5":
return 2;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 0;
case "9":
return 0;
case "7":
return 2;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "2":
return 2;
case "1":
return 0;
case "7":
return 2;
case "8":
return 3;
case "4":
return 2;
case "3":
return 2;
case "5":
return 2;
case "0":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 0;
case "9":
return 2;
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 2;
case "1":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "9":
return 0;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "9":
return 2;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "7":
return 0;
case "9":
return 3;
case "8":
return 0;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "6":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "4":
return 0;
case "3":
return 0;
case "7":
return 2;
case "5":
return 0;
case "8":
return 3;
case "6":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "0":
return 3;
case "1":
return 3;
case "8":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "7":
return 2;
case "8":
return 3;
case "1":
return 0;
case "2":
return 0;
case "0":
return 0;
case "4":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "9":
return 0;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "8":
return 0;
case "5":
return 0;
case "6":
return 0;
case "2":
return 2;
case "3":
return 3;
case "4":
return 0;
case "7":
return 0;
default: return 1;
}
default: return 1;
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return 2;
case "4":
return 2;
case "5":
return 3;
case "2":
return 2;
case "0":
return 2;
case "1":
return 3;
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 0;
case "3":
return 0;
case "4":
return 0;
case "8":
return 0;
case "7":
return 0;
case "5":
return 0;
case "6":
return 0;
case "1":
return 0;
case "2":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "8":
return 2;
case "9":
return 3;
case "5":
return 2;
case "6":
return 3;
case "1":
return 3;
case "3":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "5":
return 0;
case "4":
return 0;
case "3":
return 0;
case "0":
return 2;
case "1":
return 3;
case "7":
return 0;
case "2":
return 0;
case "9":
return 2;
case "6":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "8":
return 2;
case "9":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "2":
return 0;
case "1":
return 0;
case "0":
return 3;
case "9":
return 2;
case "7":
return 3;
case "4":
return 0;
case "3":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "0":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "9":
return 0;
case "8":
return 0;
case "5":
return 2;
case "3":
return 2;
case "4":
return 3;
case "7":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "3":
return 3;
case "2":
return 2;
case "9":
return 2;
case "6":
return 2;
case "7":
return 3;
case "4":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "2":
return 0;
case "0":
return 0;
case "6":
return 3;
case "7":
return 2;
case "1":
return 0;
case "9":
return 2;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "9":
return 0;
case "8":
return 0;
case "7":
return 0;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 0;
case "5":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "0":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "1":
return 0;
case "4":
return 0;
case "3":
return 0;
case "6":
return 0;
case "2":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "7":
return 0;
case "0":
return 2;
case "6":
return 3;
case "4":
return 3;
case "1":
return 2;
case "2":
return 2;
case "5":
return 3;
case "9":
return 0;
case "8":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
case "6":
return 2;
case "9":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "7":
return 0;
case "8":
return 0;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "6":
return 0;
case "5":
return 0;
case "1":
return 0;
case "3":
return 0;
case "2":
return 0;
case "4":
return 0;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 0;
case "3":
return 0;
case "2":
return 0;
case "7":
return 0;
case "5":
return 0;
case "6":
return 0;
case "4":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "7":
return 3;
case "4":
return 2;
case "6":
return 2;
case "9":
return 3;
case "5":
return 2;
case "3":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return 3;
case "0":
return 2;
case "6":
return 3;
case "1":
return 3;
case "2":
return 3;
case "7":
return 3;
case "3":
return 3;
case "4":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "1":
return 0;
case "6":
return 0;
case "4":
return 0;
case "5":
return 0;
case "3":
return 0;
case "2":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "7":
return 0;
case "6":
return 2;
case "8":
return 0;
case "5":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "3":
return 0;
case "2":
return 0;
case "1":
return 0;
case "4":
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
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "1":
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return 2;
case "7":
return 2;
case "8":
return 3;
case "3":
return 3;
case "1":
return 0;
case "0":
return 0;
case "2":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 0;
case "4":
return 3;
case "2":
return 2;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "3":
return 3;
case "1":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "9":
return 0;
case "0":
return 2;
case "1":
return 3;
case "8":
return 0;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "1":
return 0;
case "9":
return 2;
case "2":
return 3;
case "6":
return 2;
case "7":
return 3;
case "0":
return 0;
case "4":
return 2;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "7":
return 0;
case "5":
return 2;
case "6":
return 0;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "8":
return 2;
case "1":
return 0;
case "6":
return 3;
case "4":
return 2;
case "5":
return 3;
case "2":
return 3;
case "7":
return 2;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 3;
case "9":
return 0;
case "8":
return 0;
case "2":
return 2;
case "3":
return 3;
case "7":
return 0;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 0;
case "5":
return 2;
case "2":
return 0;
case "7":
return 2;
case "4":
return 2;
case "1":
return 0;
case "8":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "8":
return 0;
case "9":
return 0;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "7":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "9":
return 0;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "8":
return 0;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 0;
case "2":
return 0;
case "5":
return 0;
case "7":
return 2;
case "4":
return 0;
case "8":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 3;
case "7":
return 2;
case "2":
return 0;
case "4":
return 0;
case "5":
return 0;
case "1":
return 0;
case "3":
return 0;
case "6":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "7":
return 0;
case "2":
return 2;
case "3":
return 3;
case "0":
return 3;
case "8":
return 0;
case "9":
return 0;
case "6":
return 0;
case "5":
return 0;
case "4":
return 0;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "2":
return 2;
case "9":
return 0;
case "8":
return 0;
case "6":
return 0;
case "7":
return 0;
case "3":
return 2;
case "0":
return 2;
case "1":
return 3;
case "4":
return 2;
case "5":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "3":
return 0;
case "8":
return 2;
case "9":
return 3;
case "0":
return 0;
case "4":
return 3;
case "5":
return 2;
case "1":
return 0;
case "2":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "8":
return 0;
case "9":
return 0;
case "2":
return 2;
case "3":
return 3;
case "0":
return 3;
case "5":
return 2;
case "6":
return 3;
case "7":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return 0;
case "9":
return 0;
case "7":
return 3;
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "7":
return 0;
case "0":
return 3;
case "9":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "1":
return 0;
case "3":
return 0;
case "8":
return 3;
case "4":
return 0;
case "2":
return 0;
case "5":
return 0;
case "0":
return 3;
case "9":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "5":
return 0;
case "4":
return 0;
case "0":
return 0;
case "7":
return 0;
case "1":
return 0;
case "3":
return 3;
case "6":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "8":
return 2;
case "9":
return 3;
case "6":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return 0;
case "6":
return 3;
case "5":
return 0;
case "4":
return 0;
case "9":
return 3;
case "0":
return 3;
case "2":
return 0;
case "8":
return 3;
case "1":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "0":
return 2;
case "1":
return 3;
case "2":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "1":
return 2;
case "2":
return 3;
case "3":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "5":
return 0;
case "1":
return 2;
case "2":
return 3;
case "4":
return 0;
case "8":
return 3;
case "6":
return 0;
case "3":
return 3;
case "9":
return 2;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "6":
return 3;
case "9":
return 0;
case "7":
return 2;
case "5":
return 0;
case "1":
return 2;
case "4":
return 0;
case "3":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "8":
return 0;
case "7":
return 0;
case "2":
return 0;
case "1":
return 0;
case "4":
return 3;
case "3":
return 0;
case "9":
return 0;
case "5":
return 2;
case "0":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "3":
return 2;
case "4":
return 3;
case "5":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "8":
return 0;
case "5":
return 0;
case "6":
return 0;
case "9":
return 0;
case "3":
return 2;
case "0":
return 0;
case "7":
return 0;
case "2":
return 2;
case "1":
return 0;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
case "8":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "7":
return 2;
case "4":
return 3;
case "2":
return 0;
case "8":
return 3;
case "1":
return 0;
case "0":
return 3;
case "3":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "4":
return 3;
case "2":
return 2;
case "5":
return 3;
case "0":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "0":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "1":
return 2;
case "5":
return 3;
case "4":
return 3;
case "6":
return 3;
case "2":
return 3;
case "7":
return 3;
case "0":
return 2;
case "3":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "4":
return 0;
case "2":
return 0;
case "3":
return 0;
case "7":
return 0;
case "5":
return 0;
case "6":
return 0;
case "1":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "5":
return 2;
case "9":
return 2;
case "6":
return 2;
case "7":
return 2;
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return 0;
case "6":
return 0;
case "5":
return 0;
case "4":
return 0;
case "7":
return 0;
case "1":
return 0;
case "9":
return 0;
case "8":
return 0;
case "2":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "3":
return 3;
case "2":
return 2;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "6":
return 0;
case "1":
return 0;
case "2":
return 0;
case "0":
return 2;
case "4":
return 0;
case "5":
return 0;
case "3":
return 0;
case "9":
return 0;
case "8":
return 0;
case "7":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "7":
return 2;
case "9":
return 2;
case "8":
return 2;
case "6":
return 2;
case "1":
return 3;
case "0":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "0":
return 2;
case "9":
return 3;
case "8":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 2;
case "9":
return 3;
case "5":
return 2;
case "6":
return 2;
case "8":
return 3;
case "7":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "8":
return 3;
case "6":
return 3;
case "7":
return 3;
case "9":
return 3;
case "4":
return 0;
case "3":
return 0;
case "2":
return 0;
case "5":
return 0;
case "0":
return 0;
case "1":
return 0;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "5":
return 3;
case "0":
return 3;
case "6":
return 3;
case "9":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 3;
case "7":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "5":
return 2;
case "6":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "3":
return 3;
case "0":
return 2;
case "1":
return 3;
case "4":
return 2;
case "5":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "9":
return 2;
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "0":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return 3;
case "0":
return 3;
case "9":
return 0;
case "1":
return 3;
case "4":
return 3;
case "2":
return 3;
case "7":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 0;
case "0":
return 3;
case "5":
return 0;
case "7":
return 2;
case "8":
return 3;
case "2":
return 2;
case "3":
return 3;
case "9":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 0;
default: return 1;
}
default: return 1;
}
case "4":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "7":
return 2;
case "9":
return 2;
case "8":
return 2;
case "6":
return 2;
case "1":
return 3;
case "0":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "0":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 2;
case "9":
return 3;
case "5":
return 2;
case "6":
return 2;
case "8":
return 3;
case "7":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "8":
return 3;
case "6":
return 3;
case "7":
return 3;
case "9":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 0;
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "4":
return 3;
case "8":
return 3;
case "9":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "5":
return 0;
case "4":
return 0;
case "8":
return 3;
case "3":
return 0;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "1":
return 3;
case "2":
return 3;
case "8":
return 2;
case "9":
return 3;
case "3":
return 3;
case "0":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "4":
return 0;
case "7":
return 0;
case "6":
return 0;
case "5":
return 0;
case "0":
return 2;
case "1":
return 2;
case "2":
return 3;
case "3":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "7":
return 0;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "9":
return 0;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "0":
return 0;
case "1":
return 0;
case "9":
return 2;
case "4":
return 3;
case "2":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "2":
return 2;
case "3":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "8":
return 2;
case "9":
return 3;
case "3":
return 0;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "0":
return 3;
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 3;
case "6":
return 2;
case "7":
return 3;
case "3":
return 3;
case "1":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "9":
return 2;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "5":
return 2;
case "3":
return 0;
case "4":
return 2;
case "8":
return 2;
case "9":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "8":
return 2;
case "7":
return 0;
case "1":
return 2;
case "2":
return 3;
case "6":
return 0;
case "9":
return 2;
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "7":
return 0;
case "5":
return 0;
case "3":
return 2;
case "4":
return 3;
case "6":
return 0;
case "9":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 0;
case "2":
return 3;
case "8":
return 2;
case "9":
return 3;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "0":
return 2;
case "2":
return 2;
case "3":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 3;
case "4":
return 2;
case "5":
return 3;
case "2":
return 3;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return 3;
case "7":
return 3;
case "6":
return 3;
case "5":
return 0;
case "9":
return 2;
case "4":
return 0;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "2":
return 3;
case "6":
return 3;
case "5":
return 2;
case "8":
return 2;
case "9":
return 3;
case "4":
return 0;
case "3":
return 0;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "5":
return 0;
case "0":
return 0;
case "1":
return 0;
case "6":
return 0;
case "3":
return 2;
case "4":
return 3;
case "8":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "9":
return 2;
case "3":
return 2;
case "4":
return 3;
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "9":
return 0;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "8":
return 2;
case "9":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 3;
case "0":
return 3;
case "7":
return 2;
case "8":
return 3;
case "3":
return 0;
case "4":
return 0;
case "5":
return 3;
case "2":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "4":
return 0;
case "3":
return 0;
case "2":
return 0;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "7":
return 2;
case "5":
return 0;
case "4":
return 0;
case "3":
return 0;
case "6":
return 0;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "3":
return 0;
case "0":
return 2;
case "1":
return 3;
case "2":
return 0;
case "8":
return 2;
case "9":
return 3;
case "4":
return 0;
case "6":
return 3;
case "5":
return 0;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 0;
case "2":
return 0;
case "1":
return 0;
case "3":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "3":
return 0;
case "2":
return 0;
case "8":
return 2;
case "9":
return 3;
case "5":
return 2;
case "6":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "9":
return 2;
case "5":
return 3;
case "2":
return 0;
case "1":
return 0;
case "6":
return 2;
case "3":
return 0;
case "0":
return 3;
case "4":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "9":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "3":
return 3;
case "1":
return 2;
case "0":
return 3;
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "2":
return 3;
case "3":
return 2;
case "4":
return 0;
case "0":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "0":
return 0;
case "7":
return 3;
case "6":
return 3;
case "4":
return 2;
case "8":
return 3;
case "3":
return 0;
case "5":
return 0;
case "1":
return 0;
case "2":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "6":
return 0;
case "0":
return 0;
case "5":
return 0;
case "2":
return 0;
case "7":
return 3;
case "1":
return 0;
case "9":
return 3;
case "3":
return 0;
case "4":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "2":
return 2;
case "1":
return 2;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "0":
return 3;
case "8":
return 2;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "9":
return 2;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "5":
return 0;
case "9":
return 0;
case "8":
return 0;
case "6":
return 0;
case "3":
return 2;
case "4":
return 3;
case "7":
return 0;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
return 0;
case "7":
return 2;
case "8":
return 3;
case "5":
return 2;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 0;
case "8":
return 2;
case "9":
return 3;
case "5":
return 0;
case "3":
return 3;
case "1":
return 2;
case "2":
return 3;
case "6":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "5":
return 0;
case "3":
return 2;
case "4":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "3":
return 2;
case "4":
return 3;
case "5":
return 0;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 0;
case "6":
return 0;
case "0":
return 0;
case "2":
return 0;
case "1":
return 0;
case "9":
return 0;
case "5":
return 0;
case "7":
return 0;
case "4":
return 0;
case "3":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "1":
return 0;
case "3":
return 0;
case "4":
return 0;
case "5":
return 0;
case "0":
return 0;
case "6":
return 0;
case "2":
return 0;
case "9":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "0":
return 0;
case "1":
return 0;
case "5":
return 3;
case "2":
return 2;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "6":
return 0;
case "5":
return 0;
case "2":
return 2;
case "3":
return 3;
case "9":
return 0;
case "4":
return 3;
case "8":
return 0;
case "7":
return 0;
case "0":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "9":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "4":
return 0;
case "7":
return 0;
case "9":
return 0;
case "5":
return 0;
case "1":
return 0;
case "0":
return 0;
case "3":
return 0;
case "2":
return 0;
case "6":
return 0;
case "8":
return 0;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "0":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
default: return 1;
}
case "6":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return 3;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "3":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return 0;
case "9":
return 0;
case "8":
return 0;
case "5":
return 0;
case "6":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "2":
return 0;
case "0":
return 0;
case "1":
return 0;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "7":
return 0;
case "1":
return 0;
case "6":
return 0;
case "4":
return 0;
case "0":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "6":
return 0;
case "8":
return 2;
case "5":
return 0;
case "2":
return 3;
case "7":
return 0;
case "9":
return 3;
case "0":
return 0;
case "4":
return 0;
case "3":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "2":
return 0;
case "3":
return 0;
case "1":
return 3;
case "4":
return 0;
case "5":
return 0;
case "0":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "1":
return 0;
case "4":
return 0;
case "2":
return 0;
case "5":
return 0;
case "3":
return 0;
case "9":
return 2;
case "8":
return 3;
case "0":
return 0;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "6":
return 0;
case "5":
return 0;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "4":
return 0;
case "7":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "8":
return 2;
case "9":
return 3;
case "3":
return 2;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "3":
return 2;
case "4":
return 3;
case "0":
return 2;
case "1":
return 2;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "0":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "8":
return 0;
case "6":
return 0;
case "7":
return 0;
case "5":
return 0;
case "0":
return 2;
case "1":
return 3;
case "9":
return 2;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "4":
return 0;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "3":
return 0;
case "2":
return 0;
case "1":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return 3;
case "8":
return 2;
case "4":
return 0;
case "5":
return 0;
case "3":
return 0;
case "7":
return 3;
case "9":
return 0;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "0":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "0":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "4":
return 0;
case "6":
return 0;
case "8":
return 0;
case "7":
return 0;
case "2":
return 2;
case "3":
return 3;
case "5":
return 0;
case "0":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "1":
return 3;
case "0":
return 3;
case "3":
return 2;
case "4":
return 3;
case "9":
return 2;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "7":
return 3;
case "5":
return 0;
case "4":
return 0;
case "3":
return 0;
case "8":
return 2;
case "6":
return 0;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 0;
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
case "9":
return 2;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "2":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "5":
return 0;
case "6":
return 0;
case "1":
return 2;
case "2":
return 3;
case "4":
return 0;
case "3":
return 0;
case "7":
return 3;
case "8":
return 2;
default: return 1;
}
default: return 1;
}
case "5":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return 0;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "6":
return 3;
case "4":
return 2;
case "1":
return 0;
case "3":
return 0;
case "0":
return 3;
case "2":
return 0;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "1":
return 0;
case "2":
return 0;
case "3":
return 2;
case "7":
return 2;
case "8":
return 3;
case "5":
return 3;
case "0":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "4":
return 0;
case "3":
return 0;
case "7":
return 0;
case "9":
return 2;
case "1":
return 2;
case "2":
return 3;
case "8":
return 0;
case "5":
return 0;
case "6":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 3;
case "5":
return 2;
case "7":
return 2;
case "8":
return 3;
case "2":
return 0;
case "1":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "1":
return 3;
case "8":
return 2;
case "9":
return 3;
case "2":
return 3;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return 0;
case "9":
return 2;
case "1":
return 0;
case "5":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "5":
return 0;
case "8":
return 3;
case "0":
return 2;
case "1":
return 3;
case "9":
return 2;
case "6":
return 0;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "9":
return 2;
case "2":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "0":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "8":
return 3;
case "0":
return 2;
case "1":
return 3;
case "9":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 0;
case "7":
return 0;
case "5":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "5":
return 0;
case "7":
return 0;
case "8":
return 0;
case "3":
return 2;
case "4":
return 3;
case "0":
return 2;
case "1":
return 3;
case "9":
return 0;
case "6":
return 2;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "9":
return 0;
case "8":
return 0;
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "9":
return 2;
case "3":
return 0;
case "2":
return 2;
case "5":
return 2;
case "0":
return 2;
case "1":
return 3;
case "7":
return 3;
case "4":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 0;
case "5":
return 0;
case "3":
return 0;
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
case "6":
return 0;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "3":
return 0;
case "4":
return 0;
case "5":
return 0;
case "6":
return 0;
default: return 1;
}
default: return 1;
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return 0;
default: return 1;
}
default: return 1;
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return 0;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 3;
case "1":
return 3;
case "0":
return 2;
case "8":
return 0;
case "5":
return 3;
case "7":
return 3;
case "4":
return 3;
case "2":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "4":
return 0;
case "2":
return 0;
case "1":
return 0;
case "5":
return 0;
case "8":
return 2;
case "7":
return 0;
case "3":
return 0;
case "6":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "7":
return 2;
case "9":
return 2;
case "3":
return 2;
case "8":
return 2;
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return 0;
case "3":
return 0;
case "5":
return 0;
case "4":
return 0;
case "7":
return 0;
case "0":
return 2;
case "1":
return 3;
case "6":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "2":
return 0;
case "1":
return 0;
case "4":
return 0;
case "3":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "4":
return 0;
case "0":
return 3;
case "5":
return 0;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "5":
return 0;
case "6":
return 3;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "6":
return 0;
case "3":
return 2;
case "4":
return 3;
case "9":
return 3;
case "7":
return 0;
case "8":
return 3;
case "5":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "8":
return 3;
case "6":
return 0;
case "1":
return 2;
case "2":
return 3;
case "7":
return 0;
case "9":
return 3;
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "2":
return 0;
case "3":
return 0;
case "4":
return 0;
case "5":
return 0;
case "6":
return 0;
case "0":
return 2;
case "1":
return 3;
case "9":
return 2;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "0":
return 2;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "4":
return 3;
case "2":
return 0;
case "9":
return 0;
case "8":
return 0;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "9":
return 3;
case "3":
return 0;
case "2":
return 0;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return 0;
case "8":
return 0;
case "6":
return 0;
case "7":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
case "3":
return 0;
case "4":
return 0;
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 0;
case "2":
return 2;
case "3":
return 3;
case "0":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "0":
return 2;
case "1":
return 3;
case "9":
return 2;
case "3":
return 2;
case "4":
return 3;
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "6":
return 2;
case "2":
return 3;
case "3":
return 3;
case "7":
return 2;
case "4":
return 3;
case "8":
return 2;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "1":
return 0;
case "6":
return 2;
case "7":
return 3;
case "3":
return 3;
case "9":
return 2;
case "4":
return 3;
case "0":
return 0;
case "2":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "7":
return 3;
case "1":
return 0;
case "2":
return 0;
case "0":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 3;
case "7":
return 0;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "1":
return 3;
case "0":
return 2;
case "9":
return 2;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "9":
return 0;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "6":
return 0;
case "5":
return 0;
case "8":
return 0;
case "7":
return 0;
case "9":
return 0;
case "4":
return 0;
case "0":
return 3;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 0;
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
case "8":
return 0;
case "0":
return 2;
case "9":
return 0;
case "4":
return 0;
case "2":
return 0;
case "1":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 0;
case "3":
return 0;
case "7":
return 0;
case "8":
return 0;
case "5":
return 0;
case "6":
return 0;
case "2":
return 0;
case "1":
return 0;
case "4":
return 0;
case "9":
return 0;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "9":
return 0;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "1":
return 0;
case "0":
return 0;
case "9":
return 2;
case "4":
return 2;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "7":
return 0;
case "8":
return 0;
case "6":
return 0;
case "5":
return 0;
case "9":
return 0;
case "3":
return 2;
case "4":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "0":
return 2;
case "1":
return 3;
case "4":
return 3;
case "5":
return 3;
case "3":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return 0;
case "0":
return 3;
case "8":
return 2;
case "5":
return 0;
case "6":
return 0;
case "2":
return 2;
case "3":
return 3;
case "7":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "9":
return 2;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return 0;
case "5":
return 3;
case "9":
return 2;
case "6":
return 2;
case "2":
return 0;
case "7":
return 3;
case "1":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
case "9":
return 0;
case "8":
return 0;
case "6":
return 2;
case "7":
return 3;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "7":
return 2;
case "0":
return 3;
case "8":
return 3;
case "2":
return 0;
case "1":
return 0;
case "3":
return 0;
case "9":
return 3;
case "4":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 3;
case "0":
return 2;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "9":
return 2;
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 0;
case "7":
return 2;
case "8":
return 3;
case "4":
return 2;
case "1":
return 3;
case "3":
return 2;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "8":
return 0;
case "7":
return 0;
case "6":
return 0;
case "9":
return 0;
case "5":
return 0;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "2":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "0":
return 3;
case "1":
return 3;
case "4":
return 2;
case "5":
return 3;
case "2":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "6":
return 0;
case "5":
return 0;
case "4":
return 0;
case "3":
return 0;
case "8":
return 3;
case "7":
return 0;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "2":
return 3;
case "5":
return 2;
case "6":
return 3;
case "1":
return 3;
case "3":
return 2;
case "8":
return 2;
case "9":
return 3;
case "0":
return 2;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "0":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 0;
case "3":
return 0;
case "2":
return 0;
case "1":
return 0;
case "7":
return 0;
case "5":
return 0;
case "8":
return 0;
case "6":
return 0;
case "4":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "4":
return 2;
case "5":
return 3;
case "1":
return 2;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "8":
return 2;
case "9":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return 2;
case "0":
return 3;
case "5":
return 0;
case "2":
return 2;
case "3":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "9":
return 2;
case "0":
return 2;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "5":
return 2;
case "3":
return 2;
case "7":
return 3;
case "0":
return 0;
case "9":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 0;
case "6":
return 3;
case "4":
return 0;
case "2":
return 0;
case "1":
return 0;
case "5":
return 3;
case "3":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "4":
return 0;
case "5":
return 0;
case "1":
return 3;
case "6":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "0":
return 0;
case "9":
return 0;
case "3":
return 0;
case "4":
return 0;
case "1":
return 0;
case "2":
return 0;
case "6":
return 0;
case "8":
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
case "T":
switch($cardID[1]) {
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "7":
return 2;
case "5":
return 2;
case "8":
return 2;
case "6":
return 2;
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "1":
return 3;
case "2":
return 3;
case "7":
return 3;
case "3":
return 3;
case "4":
return 3;
case "8":
return 0;
case "5":
return 3;
case "0":
return 2;
case "6":
return 3;
case "9":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "5":
return 0;
case "6":
return 0;
case "4":
return 0;
case "7":
return 0;
case "2":
return 0;
case "3":
return 0;
case "1":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 0;
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return 0;
case "7":
return 0;
case "2":
return 2;
case "6":
return 3;
case "8":
return 0;
case "3":
return 3;
case "4":
return 3;
case "0":
return 2;
case "5":
return 3;
case "1":
return 2;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "9":
return 3;
case "4":
return 2;
case "5":
return 2;
case "7":
return 2;
case "6":
return 2;
case "8":
return 2;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 0;
case "2":
return 0;
case "1":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "9":
return 2;
case "1":
return 0;
case "2":
return 0;
case "0":
return 0;
case "3":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "4":
return 3;
case "8":
return 3;
case "0":
return 2;
case "5":
return 3;
case "6":
return 3;
case "2":
return 2;
case "1":
return 2;
case "7":
return 3;
case "3":
return 2;
case "9":
return 0;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "6":
return 3;
case "8":
return 0;
case "1":
return 3;
case "2":
return 3;
case "5":
return 3;
case "7":
return 0;
case "9":
return 0;
case "3":
return 3;
case "4":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "3":
return 2;
case "1":
return 2;
case "2":
return 2;
case "4":
return 3;
case "5":
return 3;
case "9":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "8":
return 3;
case "9":
return 3;
case "7":
return 3;
case "1":
return 0;
case "5":
return 0;
case "4":
return 0;
case "3":
return 0;
case "6":
return 0;
case "2":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "3":
return 0;
case "2":
return 0;
case "0":
return 0;
case "1":
return 0;
case "4":
return 0;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "3":
return 0;
case "8":
return 0;
case "4":
return 0;
case "5":
return 0;
case "1":
return 3;
case "6":
return 0;
case "2":
return 3;
case "7":
return 0;
default: return 1;
}
default: return 1;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 3;
case "9":
return 3;
case "7":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
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
if($cardID !== null && strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return 8;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "0":
return -1;
case "2":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "0":
return -1;
case "2":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "1":
return 1;
case "0":
return -1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "9":
return -1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "8":
return -1;
case "9":
return -1;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "0":
return -1;
case "6":
return 9;
case "9":
return 1;
case "7":
return 1;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
case "4":
return -1;
case "8":
return 3;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "7":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "8":
return -1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return -1;
case "9":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "3":
return -1;
case "8":
return 2;
case "5":
return -1;
case "4":
return -1;
case "0":
return 1;
case "2":
return -1;
case "1":
return -1;
case "9":
return 1;
case "7":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "3":
return 3;
case "2":
return 1;
case "0":
return -1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "4":
return 4;
case "0":
return 4;
case "8":
return 1;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "8":
return -1;
case "7":
return -1;
case "9":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "0":
return -1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "7":
return -1;
case "6":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "0":
return 3;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "9":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return -1;
case "2":
return -1;
case "3":
return 2;
case "6":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
default: return 0;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "2":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
default: return 0;
}
default: return 0;
}
case "5":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return -1;
case "8":
return -1;
case "9":
return -1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "0":
return 2;
case "1":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "8":
return 3;
case "9":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "9":
return -1;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "1":
return -1;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "2":
return 2;
case "0":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return -1;
case "1":
return -1;
case "4":
return -1;
case "2":
return -1;
case "6":
return 2;
case "8":
return 3;
case "9":
return 3;
case "7":
return 3;
case "0":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 3;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "9":
return 1;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return 6;
case "6":
return 4;
case "0":
return 2;
case "4":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "3":
return -1;
case "2":
return -1;
case "1":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "2":
return 4;
case "0":
return -1;
case "1":
return -1;
case "5":
return 1;
case "9":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "8":
return -1;
case "9":
return -1;
case "7":
return 1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return -1;
case "8":
return 2;
case "9":
return 2;
case "1":
return 3;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "9":
return 2;
case "1":
return -1;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "3":
return 2;
case "5":
return 4;
case "0":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "0":
return 2;
case "2":
return 4;
case "3":
return 4;
case "1":
return 4;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return -1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "8":
return 3;
case "9":
return 3;
case "0":
return 2;
case "1":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "6":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "4":
return -1;
case "3":
return -1;
case "7":
return 2;
case "5":
return -1;
case "8":
return 1;
case "9":
return 1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "2":
return 2;
case "1":
return 1;
case "8":
return 6;
case "9":
return 6;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "1":
return -1;
case "2":
return -1;
case "0":
return -1;
case "4":
return 2;
case "5":
return 2;
case "9":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "9":
return -1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return -1;
case "9":
return 1;
case "7":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
default: return 0;
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "0":
return 1;
case "1":
return 1;
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "0":
return 3;
case "1":
return 1;
case "9":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "8":
return 1;
case "9":
return 1;
case "0":
return 2;
case "1":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return -1;
case "9":
return 2;
case "4":
return -1;
case "8":
return -1;
case "7":
return -1;
case "5":
return -1;
case "6":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "1":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 2;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "7":
return -1;
case "2":
return -1;
case "9":
return 2;
case "6":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 1;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return -1;
case "2":
return -1;
case "1":
return -1;
case "0":
return -1;
case "6":
return 1;
case "8":
return 2;
case "4":
return -1;
case "3":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "0":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 6;
case "2":
return 9;
case "8":
return 1;
case "9":
return 1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "4":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
case "5":
return 2;
case "6":
return 1;
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "2":
return -1;
case "0":
return -1;
case "3":
return 1;
case "1":
return -1;
case "4":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
case "7":
return -1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "6":
return -1;
case "5":
return -1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return 1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "8":
return -1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return -1;
case "1":
return -1;
case "4":
return -1;
case "9":
return 1;
case "3":
return -1;
case "6":
return -1;
case "2":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return -1;
case "9":
return -1;
case "8":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return 1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
case "6":
return 2;
case "9":
return 3;
case "0":
return 2;
case "1":
return 2;
case "2":
return 3;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 1;
case "5":
return 3;
case "6":
return 2;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "7":
return 2;
case "6":
return -1;
case "8":
return 2;
case "5":
return -1;
case "9":
return 2;
case "1":
return -1;
case "3":
return -1;
case "2":
return -1;
case "4":
return -1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return -1;
case "8":
return 1;
case "3":
return -1;
case "2":
return -1;
case "7":
return -1;
case "5":
return -1;
case "6":
return -1;
case "4":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return 1;
case "4":
return 1;
case "3":
return 1;
case "8":
return 1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "9":
return 1;
case "5":
return 1;
case "3":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "5":
return -1;
case "3":
return -1;
case "2":
return -1;
case "1":
return -1;
case "4":
return -1;
case "6":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return -1;
case "8":
return 1;
case "6":
return -1;
case "4":
return -1;
case "5":
return -1;
case "3":
return -1;
case "2":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "3":
return 1;
case "9":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return 1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return 4;
case "3":
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return -1;
case "9":
return -1;
case "4":
return 4;
case "3":
return 2;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return -1;
case "3":
return -1;
case "6":
return 3;
case "4":
return -1;
case "2":
return -1;
case "5":
return -1;
case "7":
return 3;
case "9":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "5":
return -1;
case "4":
return -1;
case "0":
return -1;
case "7":
return -1;
case "1":
return -1;
case "6":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
case "0":
return 2;
case "1":
return 1;
case "2":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "8":
return -1;
case "7":
return -1;
case "9":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return -1;
case "7":
return 1;
case "5":
return -1;
case "4":
return -1;
case "9":
return -1;
case "0":
return 2;
case "2":
return -1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "0":
return 2;
case "1":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "5":
return -1;
case "4":
return -1;
case "6":
return -1;
case "7":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "0":
return -1;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return -1;
case "7":
return -1;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
case "9":
return -1;
case "0":
return -1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
case "0":
return 2;
case "1":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "8":
return -1;
case "4":
return 2;
case "5":
return -1;
case "6":
return -1;
case "9":
return -1;
case "3":
return 1;
case "0":
return -1;
case "7":
return -1;
case "1":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "3":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "4":
return 1;
case "1":
return 3;
case "2":
return 2;
case "9":
return 2;
case "0":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "5":
return 1;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "0":
return 2;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "6":
return 1;
case "9":
return -1;
case "8":
return 1;
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 3;
case "9":
return 3;
case "4":
return -1;
case "2":
return -1;
case "3":
return -1;
case "7":
return -1;
case "5":
return -1;
case "6":
return -1;
case "1":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "4":
return 1;
case "1":
return 2;
case "8":
return 3;
case "5":
return 3;
case "0":
return 2;
case "9":
return 3;
case "6":
return 3;
case "7":
return 2;
case "2":
return 2;
case "3":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return 1;
case "2":
return 2;
case "7":
return -1;
case "0":
return 3;
case "3":
return 2;
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return -1;
case "6":
return -1;
case "5":
return -1;
case "4":
return -1;
case "7":
return -1;
case "1":
return -1;
case "0":
return 6;
case "9":
return -1;
case "8":
return -1;
case "2":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "4":
return 6;
case "7":
return 3;
case "9":
return 3;
case "8":
return 3;
case "5":
return 6;
case "6":
return 6;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "1":
return -1;
case "2":
return -1;
case "0":
return -1;
case "4":
return -1;
case "5":
return -1;
case "3":
return -1;
case "9":
return -1;
case "8":
return -1;
case "7":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "4":
return 3;
case "9":
return 3;
case "5":
return 3;
case "6":
return 3;
case "8":
return 3;
case "7":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "1":
return 3;
case "0":
return 3;
case "4":
return 2;
case "3":
return 2;
case "2":
return 2;
case "5":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "8":
return 4;
case "6":
return 4;
case "7":
return 4;
case "9":
return 4;
case "4":
return -1;
case "3":
return -1;
case "2":
return -1;
case "5":
return -1;
case "0":
return -1;
case "1":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "9":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 4;
case "5":
return 4;
case "6":
return 4;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
case "0":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "9":
return 1;
case "8":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "6":
return 2;
case "4":
return -1;
case "0":
return 1;
case "5":
return -1;
case "7":
return 3;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return 2;
case "9":
return -1;
case "7":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "9":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "0":
return 2;
case "1":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return -1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "0":
return 2;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "9":
return -1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "5":
return 3;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "1":
return 3;
case "4":
return 1;
case "2":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "9":
return 1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "8":
return 2;
case "9":
return 2;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
default: return 0;
}
default: return 0;
}
case "4":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return 3;
case "9":
return 3;
case "5":
return 3;
case "6":
return 3;
case "8":
return 3;
case "7":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "1":
return 3;
case "0":
return 3;
case "4":
return 2;
case "3":
return 2;
case "2":
return 2;
case "5":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "8":
return 4;
case "6":
return 4;
case "7":
return 4;
case "9":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return -1;
case "1":
return -1;
case "2":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "5":
return -1;
case "4":
return -1;
case "6":
return 1;
case "7":
return 2;
case "9":
return 3;
case "3":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return -1;
case "7":
return -1;
case "6":
return -1;
case "5":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "7":
return -1;
case "9":
return 1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return -1;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "0":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "4":
return 3;
case "3":
return -1;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 2;
case "9":
return 2;
case "0":
return -1;
case "4":
return 1;
case "1":
return -1;
case "2":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "8":
return 2;
case "7":
return -1;
case "5":
return -1;
case "6":
return -1;
case "0":
return 2;
case "1":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return -1;
case "7":
return 7;
case "8":
return 7;
case "9":
return 7;
case "3":
return 3;
case "1":
return 10;
case "4":
return 6;
case "5":
return 6;
case "6":
return 6;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "7":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "6":
return -1;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "2":
return 2;
case "9":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "8":
return 3;
case "9":
return 3;
case "0":
return 3;
case "1":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "6":
return 2;
case "5":
return -1;
case "8":
return 2;
case "9":
return 2;
case "4":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return 3;
case "5":
return -1;
case "0":
return -1;
case "9":
return 3;
case "1":
return -1;
case "6":
return -1;
case "4":
return 2;
case "8":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "5":
return 1;
case "4":
return -1;
case "3":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "9":
return -1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "4":
return 4;
case "0":
return 1;
case "5":
return 3;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "0":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "3":
return -1;
case "2":
return -1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
case "5":
return 1;
case "2":
return -1;
case "1":
return -1;
case "6":
return 1;
case "3":
return -1;
case "0":
return 2;
case "4":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return 4;
case "1":
return 4;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 6;
case "7":
return 6;
case "8":
return 6;
case "9":
return 4;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "3":
return 2;
case "2":
return 1;
case "1":
return 2;
case "0":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "9":
return 2;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "7":
return -1;
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "6":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return -1;
case "4":
return -1;
case "5":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 1;
case "6":
return 4;
case "7":
return 4;
case "8":
return 4;
case "3":
return -1;
case "4":
return -1;
case "5":
return 3;
case "9":
return 4;
case "2":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return -1;
case "9":
return 1;
case "2":
return -1;
case "0":
return 4;
case "1":
return 4;
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "4":
return -1;
case "8":
return 1;
case "3":
return -1;
case "5":
return -1;
case "9":
return 1;
case "1":
return -1;
case "2":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "0":
return -1;
case "5":
return -1;
case "2":
return -1;
case "1":
return -1;
case "8":
return 3;
case "9":
return 2;
case "3":
return -1;
case "4":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "4":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "0":
return 2;
case "9":
return -1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "0":
return 2;
case "1":
return 2;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "1":
return -1;
case "2":
return -1;
case "9":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "5":
return -1;
case "9":
return -1;
case "8":
return -1;
case "6":
return -1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "7":
return -1;
case "0":
return 3;
case "1":
return 3;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "4":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "4":
return -1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "5":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "5":
return -1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "6":
return 2;
case "0":
return 3;
case "1":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
case "6":
return 2;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return -1;
case "0":
return 3;
case "1":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "0":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "1":
return -1;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "2":
return -1;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "1":
return -1;
case "9":
return 3;
case "2":
return -1;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return -1;
case "6":
return -1;
case "2":
return -1;
case "1":
return -1;
case "9":
return -1;
case "5":
return -1;
case "7":
return -1;
case "4":
return -1;
case "3":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "1":
return -1;
case "7":
return 4;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "0":
return -1;
case "8":
return 5;
case "6":
return -1;
case "2":
return -1;
case "9":
return 4;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
case "0":
return -1;
case "1":
return -1;
case "2":
return 3;
case "3":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "9":
return -1;
case "8":
return -1;
case "7":
return -1;
case "0":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "0":
return 4;
case "1":
return 4;
case "2":
return 4;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return -1;
case "2":
return 4;
case "3":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "4":
return 5;
case "8":
return 2;
case "9":
return 2;
case "1":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return -1;
case "7":
return -1;
case "9":
return -1;
case "5":
return -1;
case "1":
return -1;
case "0":
return -1;
case "3":
return -1;
case "2":
return -1;
case "6":
return -1;
case "8":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "0":
return 2;
case "7":
return 5;
case "8":
return 5;
case "9":
return 5;
default: return 0;
}
default: return 0;
}
case "6":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return 1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return -1;
case "9":
return -1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "2":
return -1;
case "0":
return -1;
case "1":
return -1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return 2;
case "7":
return -1;
case "1":
return -1;
case "9":
return 1;
case "6":
return -1;
case "4":
return -1;
case "3":
return 1;
case "0":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "8":
return 1;
case "5":
return -1;
case "2":
return 1;
case "7":
return -1;
case "1":
return 2;
case "0":
return -1;
case "4":
return -1;
case "3":
return 7;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "6":
return 1;
case "1":
return -1;
case "4":
return -1;
case "2":
return -1;
case "5":
return -1;
case "3":
return -1;
case "9":
return 1;
case "7":
return 1;
case "0":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
case "6":
return -1;
case "5":
return -1;
case "4":
return -1;
case "7":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "3":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 3;
case "9":
return 3;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "0":
return 3;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return 4;
case "6":
return 4;
case "7":
return 4;
case "8":
return 4;
case "9":
return 4;
case "0":
return -1;
case "3":
return -1;
case "2":
return -1;
case "1":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 4;
case "2":
return 4;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "9":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "8":
return -1;
case "6":
return -1;
case "7":
return -1;
case "5":
return -1;
case "0":
return 1;
case "1":
return 1;
case "9":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "4":
return -1;
case "6":
return -1;
case "8":
return -1;
case "7":
return -1;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "5":
return -1;
case "0":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "7":
return 1;
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "8":
return 6;
case "9":
return 1;
case "6":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "1":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "3":
return -1;
case "9":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "4":
return -1;
case "3":
return -1;
case "7":
return 4;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "0":
return 2;
default: return 0;
}
default: return 0;
}
case "5":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return -1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "6":
return 3;
case "1":
return -1;
case "3":
return -1;
case "2":
return -1;
case "5":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "0":
return 1;
case "1":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "1":
return -1;
case "2":
return -1;
case "9":
return 1;
case "3":
return 3;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "4":
return 3;
case "5":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "9":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "4":
return -1;
case "3":
return -1;
case "7":
return -1;
case "9":
return 1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "9":
return 1;
case "5":
return 1;
case "3":
return 1;
case "2":
return -1;
case "1":
return -1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "2":
return 2;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "9":
return 1;
case "0":
return 2;
case "1":
return -1;
case "5":
return -1;
case "7":
return 3;
case "8":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "5":
return -1;
case "0":
return 2;
case "1":
return 2;
case "6":
return -1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
case "2":
return 4;
case "3":
return 2;
case "0":
return 1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "9":
return 2;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "6":
return 6;
case "7":
return 6;
case "8":
return 6;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "9":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 3;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "1":
return 1;
case "2":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "0":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "8":
return 3;
case "0":
return 3;
case "1":
return 3;
case "9":
return 3;
case "6":
return -1;
case "7":
return -1;
case "5":
return -1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "9":
return -1;
case "8":
return -1;
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "0":
return 2;
case "1":
return 2;
case "9":
return -1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
case "3":
return -1;
case "2":
return 2;
case "5":
return 1;
case "7":
return 1;
case "4":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 1;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "3":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
case "6":
return -1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return -1;
case "0":
return 1;
case "2":
return 2;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
default: return 0;
}
default: return 0;
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return -1;
default: return 0;
}
default: return 0;
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return -1;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return 1;
case "6":
return 1;
case "0":
return 1;
case "8":
return -1;
case "7":
return -1;
case "4":
return 2;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "4":
return -1;
case "2":
return -1;
case "1":
return -1;
case "9":
return 1;
case "5":
return -1;
case "8":
return 1;
case "7":
return -1;
case "3":
return -1;
case "6":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "7":
return 2;
case "3":
return 1;
case "0":
return 1;
case "4":
return 1;
case "1":
return 1;
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "5":
return -1;
case "4":
return -1;
case "7":
return -1;
case "6":
return -1;
case "9":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 3;
case "4":
return 2;
case "5":
return 2;
case "6":
return 3;
case "7":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "2":
return -1;
case "1":
return -1;
case "8":
return 4;
case "6":
return 6;
case "7":
return 5;
case "4":
return -1;
case "3":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "3":
return 1;
case "5":
return 1;
case "6":
return 1;
case "0":
return 2;
case "1":
return 1;
case "9":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "4":
return -1;
case "7":
return 1;
case "5":
return -1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "6":
return 2;
case "9":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "5":
return -1;
case "6":
return 3;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "6":
return -1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "9":
return 1;
case "7":
return -1;
case "1":
return 1;
case "8":
return 3;
case "5":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "8":
return 1;
case "6":
return -1;
case "7":
return -1;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "0":
return 3;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "2":
return -1;
case "7":
return 2;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "9":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 3;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "5":
return 3;
case "3":
return -1;
case "2":
return -1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "0":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return 3;
case "0":
return -1;
case "1":
return -1;
case "4":
return 3;
case "2":
return -1;
case "9":
return -1;
case "8":
return -1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
default: return 0;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return -1;
case "8":
return -1;
case "6":
return -1;
case "7":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "0":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "0":
return -1;
case "4":
return 1;
case "2":
return 1;
case "8":
return 1;
case "9":
return 1;
case "1":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
case "0":
return 2;
case "1":
return 2;
case "8":
return -1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "1":
return -1;
case "8":
return 1;
case "9":
return 1;
case "2":
return 3;
case "3":
return 2;
case "0":
return -1;
case "4":
return 3;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "7":
return -1;
case "5":
return -1;
case "6":
return -1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "8":
return 4;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "9":
return 2;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "3":
return 2;
case "1":
return -1;
case "5":
return 2;
case "0":
return -1;
case "2":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "9":
return -1;
case "8":
return -1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "7":
return -1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return -1;
case "5":
return 1;
case "2":
return -1;
case "1":
return -1;
case "3":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
case "8":
return -1;
case "9":
return -1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "9":
return -1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "8":
return -1;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return -1;
case "2":
return -1;
case "5":
return -1;
case "6":
return 4;
case "7":
return 7;
case "4":
return -1;
case "8":
return 3;
case "0":
return 1;
case "1":
return 1;
case "9":
return 9;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "9":
return 3;
case "0":
return -1;
case "7":
return 3;
case "2":
return -1;
case "4":
return -1;
case "5":
return -1;
case "8":
return 3;
case "1":
return -1;
case "3":
return -1;
case "6":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return -1;
case "0":
return 2;
case "8":
return -1;
case "9":
return -1;
case "6":
return -1;
case "5":
return -1;
case "4":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "2":
return 1;
case "9":
return -1;
case "8":
return -1;
case "6":
return -1;
case "7":
return -1;
case "0":
return 1;
case "1":
return 1;
case "4":
return 1;
case "5":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return -1;
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "5":
return 4;
case "6":
return 4;
case "7":
return 4;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "8":
return 2;
case "9":
return 2;
case "0":
return 9;
case "1":
return 9;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "4":
return 1;
case "3":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "8":
return -1;
case "9":
return -1;
case "0":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return -1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "9":
return 1;
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return 3;
case "5":
return -1;
case "7":
return 1;
case "0":
return -1;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
case "4":
return -1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "5":
return 6;
case "3":
return 7;
case "8":
return 5;
case "9":
return 5;
case "6":
return 2;
case "2":
return -1;
case "7":
return 3;
case "4":
return 5;
case "1":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "9":
return -1;
case "8":
return -1;
case "0":
return 2;
case "1":
return 2;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "9":
return 1;
case "0":
return -1;
case "4":
return 1;
case "3":
return 1;
case "5":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 4;
case "8":
return 4;
case "9":
return 4;
case "0":
return 5;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "6":
return 4;
case "7":
return 4;
case "8":
return 4;
case "9":
return 2;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "8":
return -1;
case "7":
return -1;
case "6":
return -1;
case "9":
return -1;
case "5":
return -1;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 1;
case "9":
return 1;
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "7":
return -1;
case "9":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 3;
case "9":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
case "0":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "1":
return 3;
case "0":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return -1;
case "2":
return -1;
case "1":
return -1;
case "7":
return -1;
case "5":
return -1;
case "8":
return -1;
case "6":
return -1;
case "4":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "0":
return 2;
case "1":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "1":
return 3;
case "2":
return 3;
case "3":
return 3;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 1;
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "1":
return -1;
case "2":
return -1;
case "5":
return 1;
case "9":
return 2;
case "0":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "1":
return 1;
case "9":
return 1;
case "5":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "1":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "0":
return -1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return 1;
case "0":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "9":
return -1;
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return -1;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
case "8":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "8":
return -1;
case "7":
return -1;
case "9":
return -1;
case "4":
return -1;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return -1;
case "0":
return -1;
case "9":
return -1;
case "4":
return -1;
case "2":
return -1;
case "1":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return -1;
case "7":
return -1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
case "2":
return -1;
case "1":
return -1;
case "4":
return -1;
case "9":
return -1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "9":
return -1;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return -1;
case "8":
return -1;
case "6":
return -1;
case "5":
return -1;
case "9":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "0":
return 2;
case "2":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "1":
return -1;
case "0":
return -1;
case "3":
return 2;
case "8":
return 2;
case "9":
return 2;
case "4":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return -1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "7":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
case "0":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "9":
return 1;
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
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return 3;
case "0":
return 3;
case "9":
return -1;
case "2":
return 4;
case "3":
return 3;
case "4":
return 2;
case "5":
return 2;
case "6":
return 1;
case "8":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "9":
return -1;
case "7":
return -1;
case "2":
return -1;
case "6":
return -1;
case "8":
return -1;
case "3":
return 4;
case "4":
return 3;
case "0":
return 2;
case "5":
return 2;
case "1":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "3":
return 1;
case "0":
return -1;
case "4":
return 1;
case "5":
return 2;
case "7":
return 1;
case "8":
return 1;
case "2":
return -1;
case "1":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "2":
return 1;
case "1":
return 1;
case "3":
return 1;
case "9":
return 2;
case "5":
return 1;
case "0":
return 2;
case "8":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "9":
return 3;
case "1":
return -1;
case "2":
return -1;
case "0":
return -1;
case "3":
return -1;
case "4":
return 4;
case "7":
return 3;
case "8":
return 3;
case "5":
return 3;
case "6":
return 5;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "4":
return 3;
case "8":
return -1;
case "0":
return 4;
case "5":
return 4;
case "6":
return 6;
case "2":
return 3;
case "1":
return 5;
case "7":
return 5;
case "3":
return 3;
case "9":
return -1;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return -1;
case "1":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "6":
return -1;
case "8":
return -1;
case "7":
return -1;
case "9":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "8":
return 3;
case "9":
return 3;
case "7":
return 3;
case "1":
return -1;
case "5":
return -1;
case "4":
return -1;
case "3":
return -1;
case "6":
return -1;
case "2":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return -1;
case "7":
return 1;
case "2":
return -1;
case "0":
return -1;
case "6":
return 2;
case "1":
return -1;
case "4":
return -1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 1;
case "3":
return -1;
case "8":
return -1;
case "5":
return -1;
case "6":
return -1;
case "2":
return -1;
case "7":
return -1;
default: return 0;
}
default: return 0;
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 3;
case "9":
return 3;
case "7":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
default: return 0;
}
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "0":
return 3;
case "1":
return 3;
case "5":
return 3;
case "8":
return 2;
case "2":
return 3;
case "6":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "1":
return 3;
case "2":
return 2;
case "7":
return -1;
case "3":
return 3;
case "8":
return -1;
case "5":
return 2;
case "9":
return -1;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "5":
return -1;
case "8":
return 3;
case "9":
return 2;
case "6":
return -1;
case "4":
return -1;
case "7":
return -1;
case "2":
return -1;
case "3":
return -1;
case "1":
return -1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return 6;
case "5":
return 1;
case "3":
return 4;
case "7":
return 3;
case "0":
return -1;
case "9":
return -1;
case "2":
return 2;
case "4":
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return -1;
case "6":
return -1;
case "4":
return -1;
case "2":
return -1;
case "1":
return -1;
case "3":
return -1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "3":
return 2;
case "7":
return 2;
case "5":
return -1;
case "1":
return 3;
case "8":
return 2;
default: return 0;
}
case "0":
switch($cardID[5]) {
case "5":
return -1;
case "0":
return -1;
case "9":
return -1;
case "3":
return -1;
case "4":
return -1;
case "1":
return -1;
case "2":
return -1;
case "6":
return -1;
case "8":
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
if($cardID !== null && strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "Aura";
case "0":
return "Scroll";
case "7":
return "Aura";
case "9":
return "Aura";
case "8":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Aura";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
case "6":
return "Aura";
case "7":
return "Aura";
case "8":
return "Aura";
case "9":
return "Aura";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Head";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "8":
return "Aura";
case "9":
return "Staff";
case "5":
return "Aura";
case "6":
return "Aura";
case "7":
return "Aura";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Arms";
default: return "";
}
case "8":
switch($cardID[5]) {
case "9":
return "Aura";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "0":
return "Legs";
case "2":
return "Chest";
case "1":
return "Head";
case "3":
return "Arms";
case "4":
return "Legs";
default: return "";
}
case "6":
switch($cardID[5]) {
case "7":
return "Head";
case "8":
return "Chest";
case "6":
return "Head";
case "9":
return "Arms";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Arms";
case "3":
return "Dagger";
case "5":
return "Chest";
case "4":
return "Head";
case "0":
return "Landmark";
case "2":
return "Young";
case "7":
return "Legs";
default: return "";
}
case "2":
switch($cardID[5]) {
case "6":
return "Young";
case "8":
return "Chest";
case "7":
return "Legs";
case "9":
return "Head";
default: return "";
}
case "3":
switch($cardID[5]) {
case "7":
return "Aura";
case "8":
return "Aura";
case "9":
return "Aura";
case "1":
return "Aura";
case "3":
return "Aura";
case "0":
return "Arms";
default: return "";
}
case "4":
switch($cardID[5]) {
case "9":
return "Chest";
case "8":
return "Chest";
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
case "7":
return "Young";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Legs";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return "Ash";
case "8":
return "Young";
case "0":
return "Evo,Arms";
case "1":
return "Evo,Legs";
case "2":
return "Arms";
case "3":
return "Arrow";
default: return "";
}
case "2":
switch($cardID[5]) {
case "9":
return "Evo,Chest";
case "8":
return "Evo,Head";
default: return "";
}
default: return "";
}
case "6":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return "Evo,Chest";
case "8":
return "Evo,Head";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Evo,Arms";
case "1":
return "Evo,Legs";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Chi";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Chi";
default: return "";
}
case "3":
switch($cardID[5]) {
case "2":
return "Chi";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Chi";
default: return "";
}
case "9":
switch($cardID[5]) {
case "5":
return "Chi";
case "6":
return "Chi";
case "7":
return "Chi";
case "8":
return "Chi";
case "9":
return "Chi";
default: return "";
}
default: return "";
}
case "5":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Chi";
case "1":
return "Chi";
case "2":
return "Chi";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "0":
return "Chest";
case "8":
return "Chest";
case "9":
return "Arms";
default: return "";
}
case "1":
switch($cardID[5]) {
case "9":
return "Demon,Ally";
default: return "";
}
case "2":
switch($cardID[5]) {
case "9":
return "Scythe";
case "1":
return "Axe";
case "0":
return "Demon,Ally";
default: return "";
}
case "4":
switch($cardID[5]) {
case "3":
return "Arms";
case "1":
return "Head";
case "4":
return "Legs";
case "2":
return "Chest";
case "0":
return "Legs";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "Aura";
case "6":
return "Aura";
case "0":
return "Landmark";
case "3":
return "Scepter";
case "2":
return "Young";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Young";
case "1":
return "Sword";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Arms";
default: return "";
}
case "6":
switch($cardID[5]) {
case "1":
return "Head";
case "0":
return "Chest";
default: return "";
}
case "8":
switch($cardID[5]) {
case "8":
return "Orb";
case "9":
return "Legs";
default: return "";
}
case "1":
switch($cardID[5]) {
case "2":
return "Aura";
case "3":
return "Aura";
case "1":
return "Aura";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "7":
return "Chest";
case "8":
return "Head";
case "6":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Young";
case "7":
return "Aura";
case "5":
return "Sword";
default: return "";
}
case "0":
switch($cardID[5]) {
case "8":
return "Arms";
case "5":
return "Axe";
case "6":
return "Axe";
case "4":
return "Aura";
case "7":
return "Legs";
default: return "";
}
case "2":
switch($cardID[5]) {
case "1":
return "Flail";
case "2":
return "Legs";
case "0":
return "Young";
default: return "";
}
default: return "";
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return "Item";
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "Item";
case "1":
return "Gun";
case "5":
return "Item";
case "0":
return "Pistol";
case "2":
return "Head";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Staff";
case "1":
return "Arms";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Chest";
case "0":
return "Sword";
case "4":
return "Aura";
default: return "";
}
case "9":
switch($cardID[5]) {
case "7":
return "Item";
case "6":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "9":
return "Staff";
case "7":
return "Aura";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Bow";
case "9":
return "Arrow";
case "2":
return "Legs";
case "7":
return "Trap";
case "1":
return "Bow";
case "3":
return "Arrow";
case "8":
return "Trap";
case "6":
return "Trap";
default: return "";
}
case "7":
switch($cardID[5]) {
case "8":
return "Chest";
case "9":
return "Arms";
case "7":
return "Sword";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Young";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Sword";
case "0":
return "Arrow";
case "1":
return "Arrow";
case "2":
return "Arrow";
case "3":
return "Arrow";
case "4":
return "Arrow";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "Hammer";
case "5":
return "Arms";
case "4":
return "Hammer";
case "8":
return "Aura";
case "9":
return "Aura";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Gem";
case "2":
return "Young";
case "4":
return "Claw";
case "5":
return "Claw";
case "3":
return "Club";
case "6":
return "Head";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Young";
case "0":
return "Aura";
case "8":
return "Dagger";
case "9":
return "Dagger";
case "6":
return "Young";
case "4":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Legs";
case "0":
return "Sword";
case "1":
return "Dagger";
case "2":
return "Dagger";
default: return "";
}
case "7":
switch($cardID[5]) {
case "9":
return "Sword";
case "8":
return "Sword";
case "7":
return "Young";
case "5":
return "Aura";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Sword";
case "1":
return "Chest";
default: return "";
}
case "9":
switch($cardID[5]) {
case "9":
return "Young";
case "7":
return "Young";
default: return "";
}
case "3":
switch($cardID[5]) {
case "8":
return "Aura";
case "9":
return "Aura";
case "0":
return "Aura";
case "1":
return "Aura";
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "Legs";
case "2":
return "Young";
case "0":
return "Gem";
case "7":
return "Item";
case "4":
return "Chest";
case "3":
return "Pistol";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Item";
case "8":
return "Item";
case "9":
return "Item";
case "0":
return "Item";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Young";
case "5":
return "Item";
case "6":
return "Item";
case "7":
return "Item";
default: return "";
}
case "4":
switch($cardID[5]) {
case "2":
return "Arms";
case "0":
return "Bow";
case "5":
return "Arrow";
case "3":
return "Arrow";
case "1":
return "Head";
default: return "";
}
case "7":
switch($cardID[5]) {
case "9":
return "Head";
case "8":
return "Arms";
case "7":
return "Sword";
case "0":
return "Arrow";
case "1":
return "Arrow";
case "2":
return "Arrow";
case "3":
return "Arrow";
case "4":
return "Arrow";
case "6":
return "Young";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Arrow";
case "1":
return "Arrow";
case "2":
return "Arrow";
case "3":
return "Arrow";
case "4":
return "Arrow";
case "5":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "9":
return "Arrow";
default: return "";
}
case "5":
switch($cardID[5]) {
case "7":
return "Arrow";
case "8":
return "Arrow";
case "9":
return "Arrow";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "0":
return "Head";
case "3":
return "Arms";
case "4":
return "Legs";
case "8":
return "Legs";
case "7":
return "Arms";
case "5":
return "Head";
case "6":
return "Chest";
case "1":
return "Head";
case "2":
return "Chest";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Aura";
case "7":
return "Aura";
case "8":
return "Aura";
default: return "";
}
case "6":
switch($cardID[5]) {
case "2":
return "Aura";
case "7":
return "Aura";
case "8":
return "Aura";
case "9":
return "Aura";
case "3":
return "Item";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Staff";
case "4":
return "Young";
case "7":
return "Chest";
case "2":
return "Aura";
case "6":
return "Legs";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "Arms";
case "1":
return "Young";
case "4":
return "Chest";
case "3":
return "Head";
case "6":
return "Legs";
case "2":
return "Sword";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Aura";
case "9":
return "Legs";
case "8":
return "Head";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "7":
return "Aura";
case "8":
return "Aura";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Legs";
case "5":
return "Arms";
case "3":
return "Head";
case "2":
return "Claw";
case "4":
return "Chest";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return "Item";
case "0":
return "Item";
case "6":
return "Item";
case "7":
return "Item";
default: return "";
}
case "1":
switch($cardID[5]) {
case "4":
return "Item";
case "5":
return "Item";
case "6":
return "Item";
case "7":
return "Item";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Legs";
case "4":
return "Chest";
case "5":
return "Arms";
case "3":
return "Head";
case "2":
return "Gun";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "Arrow";
case "9":
return "Arrow";
case "3":
return "Quiver";
case "2":
return "Bow";
case "7":
return "Legs";
case "5":
return "Chest";
case "6":
return "Arms";
case "4":
return "Head";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Arrow";
case "1":
return "Arrow";
case "3":
return "Arrow";
case "4":
return "Arrow";
case "5":
return "Arrow";
case "6":
return "Arrow";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Arrow";
case "3":
return "Arrow";
case "8":
return "Arrow";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "Arms";
case "3":
return "Head";
case "2":
return "Sword";
case "4":
return "Chest";
case "6":
return "Legs";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Aura";
case "8":
return "Aura";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "Aura";
case "8":
return "Aura";
case "3":
return "Block";
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return "Base";
case "9":
return "Chest";
case "0":
return "Chest";
case "1":
return "Arms";
case "2":
return "Legs";
default: return "";
}
case "1":
switch($cardID[5]) {
case "1":
return "Chest";
case "3":
return "Chest";
case "4":
return "Arms";
case "2":
return "Head";
case "5":
return "Legs";
case "0":
return "Aura";
case "9":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "5":
return "Young";
case "0":
return "Arms";
case "2":
return "Ash";
case "7":
return "Aura";
case "1":
return "Legs";
case "6":
return "Sword";
default: return "";
}
case "3":
switch($cardID[5]) {
case "7":
return "Aura";
case "0":
return "Aura";
case "9":
return "Head";
default: return "";
}
case "2":
switch($cardID[5]) {
case "6":
return "Aura";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "Arms";
case "5":
return "Arms";
case "4":
return "Chest";
case "9":
return "Block";
case "2":
return "Aura";
case "8":
return "Aura";
case "1":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "2":
return "Aura";
case "3":
return "Aura";
case "4":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Chest";
case "4":
return "Head";
case "6":
return "Arms";
case "3":
return "Aura";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Block";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
default: return "";
}
case "8":
switch($cardID[5]) {
case "2":
return "Aura";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "Young";
case "2":
return "Young";
case "3":
return "Sword";
case "9":
return "Sword";
case "0":
return "Gem";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Chest";
case "5":
return "Aura";
case "6":
return "Aura";
case "9":
return "Head";
case "0":
return "Young";
case "7":
return "Rosetta,Macro";
case "2":
return "Aura";
case "1":
return "Staff";
default: return "";
}
case "7":
switch($cardID[5]) {
case "7":
return "Aura";
case "2":
return "Head";
case "1":
return "Legs";
case "0":
return "Aura";
case "3":
return "Legs";
default: return "";
}
case "3":
switch($cardID[5]) {
case "3":
return "Aura";
case "4":
return "Aura";
case "0":
return "Legs";
default: return "";
}
case "6":
switch($cardID[5]) {
case "1":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
case "6":
return "Aura";
default: return "";
}
case "8":
switch($cardID[5]) {
case "8":
return "Aura";
default: return "";
}
case "4":
switch($cardID[5]) {
case "5":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Staff";
case "4":
return "Young";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "Chest";
case "2":
return "Club";
case "3":
return "Head";
case "5":
return "Arms";
case "6":
return "Legs";
case "1":
return "Young";
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return "Angel,Ally";
case "9":
return "Angel,Ally";
case "8":
return "Angel,Ally";
case "5":
return "Angel,Ally";
case "6":
return "Angel,Ally";
default: return "";
}
case "1":
switch($cardID[5]) {
case "2":
return "Angel,Ally";
case "0":
return "Angel,Ally";
case "1":
return "Angel,Ally";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "Off-Hand";
case "5":
return "Axe";
case "7":
return "Arms";
case "0":
return "Head";
case "4":
return "Aura";
default: return "";
}
case "3":
switch($cardID[5]) {
case "2":
return "Aura";
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
default: return "";
}
case "2":
switch($cardID[5]) {
case "1":
return "Head";
case "4":
return "Arms";
case "2":
return "Head";
case "5":
return "Legs";
case "3":
return "Chest";
case "0":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Head";
case "1":
return "Chest";
case "6":
return "Orb";
case "4":
return "Aura";
case "0":
return "Scepter";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return "Sword";
case "5":
return "Young";
case "7":
return "Chest";
default: return "";
}
case "0":
switch($cardID[5]) {
case "4":
return "Chest";
case "5":
return "Figment";
case "6":
return "Figment";
case "7":
return "Figment";
case "8":
return "Figment";
case "9":
return "Figment";
case "0":
return "Gem";
case "3":
return "Scepter";
case "2":
return "Young";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Figment";
case "1":
return "Figment";
case "2":
return "Figment";
default: return "";
}
case "7":
switch($cardID[5]) {
case "8":
return "Legs";
case "6":
return "Chest";
case "7":
return "Arms";
case "5":
return "Head";
default: return "";
}
case "8":
switch($cardID[5]) {
case "1":
return "Aura";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Aura";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return "Demi-Hero,Demon";
case "6":
return "Chest";
case "8":
return "Legs";
case "7":
return "Arms";
case "5":
return "Head";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Aura";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Aura";
case "5":
return "Aura";
case "3":
return "Demon,Ally";
case "9":
return "Club";
default: return "";
}
case "3":
switch($cardID[5]) {
case "5":
return "Flail";
case "6":
return "Off-Hand";
case "4":
return "Young";
default: return "";
}
case "0":
switch($cardID[5]) {
case "5":
return "Hammer";
case "4":
return "Young";
case "6":
return "Head";
default: return "";
}
default: return "";
}
case "5":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return "Demi-Hero";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "1":
return "Head";
case "2":
return "Book";
case "9":
return "Aura";
case "5":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "4":
return "Young";
case "7":
return "Legs";
case "0":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
case "8":
return "Head";
case "5":
return "Dagger";
case "6":
return "Dagger";
default: return "";
}
case "5":
switch($cardID[5]) {
case "9":
return "Aura";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "3":
return "Arrow";
case "2":
return "Arms";
case "4":
return "Arrow";
case "1":
return "Bow";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Arrow";
case "3":
return "Arrow";
case "4":
return "Arrow";
case "5":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Aura";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
default: return "";
}
case "9":
switch($cardID[5]) {
case "1":
return "Aura";
case "3":
return "Orb";
case "2":
return "Staff";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "Legs";
case "2":
return "Ash";
case "3":
return "Ash";
case "4":
return "Ash";
case "1":
return "Royal,Young";
case "5":
return "Rock";
default: return "";
}
case "4":
switch($cardID[5]) {
case "5":
return "Chest";
case "8":
return "Aura";
case "6":
return "Arms";
default: return "";
}
case "9":
switch($cardID[5]) {
case "8":
return "Aura";
case "9":
return "Aura";
case "2":
return "Construct";
case "3":
return "Item";
case "4":
return "Item";
default: return "";
}
case "3":
switch($cardID[5]) {
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
default: return "";
}
case "7":
switch($cardID[5]) {
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
case "2":
return "Aura";
case "0":
return "Dagger";
default: return "";
}
case "8":
switch($cardID[5]) {
case "9":
return "Arms";
case "8":
return "Gun";
default: return "";
}
case "6":
switch($cardID[5]) {
case "7":
return "Sword";
case "8":
return "Axe";
case "9":
return "Dagger";
case "6":
return "Item";
default: return "";
}
case "2":
switch($cardID[5]) {
case "9":
return "Aura";
case "6":
return "Off-Hand";
case "7":
return "Off-Hand";
case "5":
return "Young";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Aura";
case "9":
return "Aura";
case "3":
return "Chest";
case "2":
return "Invocation";
case "7":
return "Aura";
case "4":
return "Arms";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
default: return "";
}
case "3":
switch($cardID[5]) {
case "4":
return "Head";
case "5":
return "Off-Hand";
case "3":
return "Aura";
case "7":
return "Chest";
case "8":
return "Arms";
case "9":
return "Legs";
case "6":
return "Head";
default: return "";
}
case "4":
switch($cardID[5]) {
case "3":
return "Item";
case "0":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
case "4":
return "Aura";
case "5":
return "Item";
case "6":
return "Aura";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return "Item";
default: return "";
}
default: return "";
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "Angel,Ally";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "Chest";
case "2":
return "Sword";
case "1":
return "Young";
case "5":
return "Arms";
case "3":
return "Head";
case "6":
return "Legs";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Aura";
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return "Dragon,Ally";
case "3":
return "Ash";
case "5":
return "Young";
case "7":
return "Arms";
case "6":
return "Sword";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Gem";
case "5":
return "Aura";
case "2":
return "Young";
case "9":
return "Invocation";
case "8":
return "Invocation";
case "6":
return "Invocation";
case "7":
return "Invocation";
case "4":
return "Arms";
case "3":
return "Scepter";
default: return "";
}
case "8":
switch($cardID[5]) {
case "4":
return "Chest";
case "5":
return "Chest";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Invocation";
case "1":
return "Invocation";
case "2":
return "Invocation";
case "3":
return "Invocation";
case "4":
return "Invocation";
case "5":
return "Invocation";
case "6":
return "Invocation";
case "7":
return "Invocation";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "6":
return "Chest";
case "7":
return "Chest";
case "5":
return "Staff";
default: return "";
}
case "3":
switch($cardID[5]) {
case "8":
return "Aura";
case "6":
return "Head";
case "7":
return "Head";
case "9":
return "Affliction,Aura";
default: return "";
}
case "2":
switch($cardID[5]) {
case "5":
return "Arms";
case "6":
return "Affliction,Aura";
default: return "";
}
case "8":
switch($cardID[5]) {
case "2":
return "Head";
case "3":
return "Head";
case "4":
return "Chest";
case "5":
return "Arms";
case "6":
return "Legs";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Arms";
case "2":
return "Legs";
case "9":
return "Legs";
case "8":
return "Arms";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Aura";
default: return "";
}
case "0":
switch($cardID[5]) {
case "3":
return "Young";
default: return "";
}
case "7":
switch($cardID[5]) {
case "6":
return "Aura";
case "7":
return "Aura";
case "8":
return "Aura";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "Dragon,Ally";
case "8":
return "Dragon,Ally";
case "6":
return "Dragon,Ally";
case "7":
return "Dragon,Ally";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Dragon,Ally";
case "1":
return "Dragon,Ally";
case "2":
return "Dragon,Ally";
case "3":
return "Dragon,Ally";
case "4":
return "Dragon,Ally";
case "5":
return "Dragon,Ally";
case "6":
return "Dragon,Ally";
case "7":
return "Dragon,Ally";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return "Invocation,Placeholder Card";
case "0":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Aura";
case "9":
return "Aura";
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return "Aura";
case "8":
return "Evo,Arms";
case "3":
return "Item";
case "5":
return "Dagger";
case "9":
return "Arrow";
case "1":
return "Aura";
case "2":
return "Aura";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Off-Hand";
case "0":
return "Arms";
case "5":
return "Legs";
case "2":
return "Head";
case "1":
return "Legs";
case "3":
return "Chest";
case "4":
return "Arms";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Scepter";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Block";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "Arms";
case "6":
return "Flail";
case "0":
return "Landmark";
case "2":
return "Young";
case "9":
return "Head";
case "5":
return "Claw";
case "7":
return "Axe";
case "4":
return "Young";
default: return "";
}
case "5":
switch($cardID[5]) {
case "1":
return "Off-Hand";
case "3":
return "Arms";
case "4":
return "Head";
case "5":
return "Head";
case "0":
return "Hammer";
case "6":
return "Legs";
case "2":
return "Off-Hand";
default: return "";
}
case "4":
switch($cardID[5]) {
case "6":
return "Young";
case "9":
return "Hammer";
case "8":
return "Young";
default: return "";
}
case "8":
switch($cardID[5]) {
case "3":
return "Aura";
case "4":
return "Aura";
case "5":
return "Aura";
case "6":
return "Aura";
case "7":
return "Aura";
case "8":
return "Aura";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Block";
case "8":
return "Aura";
case "9":
return "Aura";
case "1":
return "Block";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Sword";
case "7":
return "Chest";
case "9":
return "Head";
case "5":
return "Sword";
case "1":
return "Young";
case "3":
return "Young";
case "6":
return "Sword";
case "8":
return "Head";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Head";
case "1":
return "Chest";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Aura";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "5":
return "Head";
case "9":
return "Chest";
case "8":
return "Head";
case "6":
return "Head";
case "7":
return "Head";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Arms";
default: return "";
}
case "3":
switch($cardID[5]) {
case "4":
return "Mercenary,Ally";
case "5":
return "Arms";
default: return "";
}
case "5":
switch($cardID[5]) {
case "5":
return "Legs";
default: return "";
}
case "8":
switch($cardID[5]) {
case "1":
return "Block";
case "2":
return "Block";
default: return "";
}
case "6":
switch($cardID[5]) {
case "1":
return "Block";
case "2":
return "Block";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Block";
case "2":
return "Block";
default: return "";
}
case "7":
switch($cardID[5]) {
case "5":
return "Chest";
default: return "";
}
default: return "";
}
case "6":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return "Evo,Arms";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "Young";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Young";
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Arrow";
case "1":
return "Arrow";
case "6":
return "Trap";
case "2":
return "Trap";
case "3":
return "Trap";
case "7":
return "Trap";
case "4":
return "Trap";
case "8":
return "Trap";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Arms";
case "0":
return "Head";
default: return "";
}
case "7":
switch($cardID[5]) {
case "1":
return "Trap";
case "2":
return "Trap";
case "3":
return "Trap";
case "6":
return "Chest";
case "5":
return "Head";
case "8":
return "Legs";
case "7":
return "Arms";
case "9":
return "Chest";
case "4":
return "Arms";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "9":
return "Arrow";
default: return "";
}
case "8":
switch($cardID[5]) {
case "1":
return "Arms";
case "2":
return "Legs";
case "0":
return "Chest";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Arms";
case "0":
return "Arrow";
case "1":
return "Arrow";
case "2":
return "Arrow";
case "3":
return "Arrow";
case "4":
return "Arrow";
case "5":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Arrow";
case "1":
return "Arrow";
case "2":
return "Arrow";
case "3":
return "Arrow";
case "4":
return "Arrow";
case "5":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "9":
return "Arrow";
default: return "";
}
case "5":
switch($cardID[5]) {
case "7":
return "Head";
case "8":
return "Arms";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "Young";
case "5":
return "Dagger";
case "6":
return "Dagger";
case "7":
return "Dagger";
case "8":
return "Dagger";
case "0":
return "Gem";
case "9":
return "Dagger";
case "4":
return "Dagger";
case "2":
return "Young";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Young";
case "3":
return "Bow";
case "7":
return "Quiver";
case "8":
return "Quiver";
case "5":
return "Quiver";
case "6":
return "Quiver";
case "2":
return "Young";
case "4":
return "Chest";
case "9":
return "Head";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Young";
case "8":
return "Dagger";
case "6":
return "Young";
case "9":
return "Head";
default: return "";
}
case "1":
switch($cardID[5]) {
case "1":
return "Chest";
case "0":
return "Dagger";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Shuriken,Item";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return "Aura";
case "5":
return "Aura";
case "6":
return "Aura";
case "7":
return "Aura";
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "Item";
case "7":
return "Item";
case "8":
return "Item";
case "9":
return "Item";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
case "3":
return "Item";
case "5":
return "Item";
case "6":
return "Item";
case "7":
return "Item";
case "4":
return "Item";
case "8":
return "Item";
case "9":
return "Item";
default: return "";
}
case "5":
switch($cardID[5]) {
case "5":
return "Off-Hand";
case "4":
return "Head";
case "3":
return "Aura";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Item";
case "7":
return "Aura";
case "6":
return "Aura";
case "5":
return "Item";
case "0":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
case "3":
return "Item";
default: return "";
}
case "3":
switch($cardID[5]) {
case "7":
return "Head";
case "1":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
case "0":
return "Aura";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Young";
case "1":
return "Staff";
default: return "";
}
case "0":
switch($cardID[5]) {
case "7":
return "Aura";
case "8":
return "Aura";
case "9":
return "Aura";
case "3":
return "Arms";
default: return "";
}
case "1":
switch($cardID[5]) {
case "9":
return "Aura";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "8":
return "Arrow";
case "7":
return "Bow";
case "5":
return "Young";
case "6":
return "Arms";
default: return "";
}
case "6":
switch($cardID[5]) {
case "9":
return "Item";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Chest";
case "3":
return "Aura";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Arrow";
case "5":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "9":
return "Arrow";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Gem";
case "1":
return "Arms";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Head";
default: return "";
}
case "3":
switch($cardID[5]) {
case "7":
return "Head";
case "6":
return "Aura";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Off-Hand";
case "9":
return "Young";
default: return "";
}
default: return "";
}
default: return "";
}
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "8":
return "Item";
case "0":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
case "9":
return "Item";
case "7":
return "Item";
case "3":
return "Item";
case "4":
return "Item";
case "5":
return "Item";
case "6":
return "Item";
default: return "";
}
case "8":
switch($cardID[5]) {
case "3":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
case "4":
return "Item";
case "5":
return "Item";
case "6":
return "Item";
case "7":
return "Item";
case "8":
return "Item";
case "9":
return "Item";
case "0":
return "Item";
default: return "";
}
case "0":
switch($cardID[5]) {
case "6":
return "Wrench";
case "2":
return "Young";
case "0":
return "Gem";
case "5":
return "Young";
case "3":
return "Gun";
case "9":
return "Gun";
case "8":
return "Young";
default: return "";
}
case "1":
switch($cardID[5]) {
case "6":
return "Base,Arms";
case "5":
return "Base,Chest";
case "4":
return "Base,Head";
case "7":
return "Base,Legs";
case "1":
return "Head";
case "0":
return "Construct";
case "9":
return "Base,Chest";
case "8":
return "Base,Head";
case "2":
return "Chest";
default: return "";
}
case "3":
switch($cardID[5]) {
case "1":
return "Evo,Chest";
case "0":
return "Evo,Head";
case "4":
return "Evo,Head";
case "9":
return "Evo,Chest";
case "5":
return "Evo,Chest";
case "2":
return "Evo,Arms";
case "3":
return "Evo,Legs";
case "6":
return "Evo,Arms";
case "8":
return "Evo,Head";
case "7":
return "Evo,Legs";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Evo,Chest";
case "9":
return "Evo,Legs";
case "8":
return "Evo,Arms";
case "6":
return "Evo,Head";
case "1":
return "Evo,Legs";
case "0":
return "Evo,Arms";
case "4":
return "Evo,Base,Arms";
case "3":
return "Evo,Base,Chest";
case "2":
return "Evo,Base,Head";
case "5":
return "Evo,Base,Legs";
default: return "";
}
case "5":
switch($cardID[5]) {
case "1":
return "Evo,Chest";
case "2":
return "Evo,Arms";
case "3":
return "Evo,Legs";
case "0":
return "Evo,Head";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Evo,Base,Arms";
case "6":
return "Evo,Base,Head";
case "7":
return "Evo,Base,Chest";
case "9":
return "Evo,Base,Legs";
case "4":
return "Base,Arms";
case "3":
return "Base,Chest";
case "2":
return "Base,Head";
case "5":
return "Base,Legs";
case "0":
return "Base,Arms";
case "1":
return "Base,Legs";
default: return "";
}
case "7":
switch($cardID[5]) {
case "5":
return "Item";
case "0":
return "Item";
case "6":
return "Item";
case "9":
return "Item";
case "8":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
case "3":
return "Item";
case "7":
return "Item";
case "4":
return "Item";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Block";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "Block";
case "8":
return "Block";
case "9":
return "Block";
default: return "";
}
case "5":
switch($cardID[5]) {
case "9":
return "Block";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Block";
case "1":
return "Block";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return "Aura";
case "6":
return "Ash";
case "9":
return "Chest";
case "1":
return "Arrow";
case "4":
return "Aura";
case "7":
return "Head";
default: return "";
}
case "3":
switch($cardID[5]) {
case "4":
return "Item";
case "5":
return "Arms";
case "1":
return "Block";
case "2":
return "Block";
case "3":
return "Block";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Aura";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "Evo,Chest";
case "0":
return "Evo,Head";
case "4":
return "Evo,Head";
case "9":
return "Evo,Chest";
case "5":
return "Evo,Chest";
case "2":
return "Evo,Arms";
case "3":
return "Evo,Legs";
case "6":
return "Evo,Arms";
case "8":
return "Evo,Head";
case "7":
return "Evo,Legs";
default: return "";
}
case "4":
switch($cardID[5]) {
case "7":
return "Evo,Chest";
case "9":
return "Evo,Legs";
case "8":
return "Evo,Arms";
case "6":
return "Evo,Head";
case "1":
return "Evo,Legs";
case "0":
return "Evo,Arms";
case "4":
return "Evo,Base,Arms";
case "3":
return "Evo,Base,Chest";
case "2":
return "Evo,Base,Head";
case "5":
return "Evo,Base,Legs";
default: return "";
}
case "5":
switch($cardID[5]) {
case "1":
return "Evo,Chest";
case "2":
return "Evo,Arms";
case "3":
return "Evo,Legs";
case "0":
return "Evo,Head";
default: return "";
}
case "2":
switch($cardID[5]) {
case "8":
return "Evo,Base,Arms";
case "6":
return "Evo,Base,Head";
case "7":
return "Evo,Base,Chest";
case "9":
return "Evo,Base,Legs";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Demi-Hero,Evo";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return "Item";
case "6":
return "Aura";
case "5":
return "Chest";
case "4":
return "Chest";
default: return "";
}
case "7":
switch($cardID[5]) {
case "2":
return "Item";
case "5":
return "Aura";
case "4":
return "Arms";
case "3":
return "Arms";
default: return "";
}
case "1":
switch($cardID[5]) {
case "7":
return "Aura";
case "5":
return "Head";
case "0":
return "Aura";
case "1":
return "Aura";
case "6":
return "Head";
default: return "";
}
case "0":
switch($cardID[5]) {
case "9":
return "Aura";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Item";
case "6":
return "Aura";
case "7":
return "Aura";
case "8":
return "Aura";
case "3":
return "Off-Hand";
case "4":
return "Off-Hand";
case "2":
return "Hammer";
default: return "";
}
case "1":
switch($cardID[5]) {
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "4":
return "Head";
case "3":
return "Head";
case "2":
return "Aura";
default: return "";
}
case "3":
switch($cardID[5]) {
case "5":
return "Arms";
case "4":
return "Chest";
case "3":
return "Head";
case "6":
return "Legs";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Sword";
case "2":
return "Sword";
case "4":
return "Legs";
case "6":
return "Aura";
case "5":
return "Legs";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "4":
return "Arrow";
case "5":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "9":
return "Arrow";
case "0":
return "Arrow";
case "1":
return "Arrow";
case "2":
return "Arrow";
case "3":
return "Arrow";
default: return "";
}
case "6":
switch($cardID[5]) {
case "3":
return "Young";
case "0":
return "Arrow";
case "1":
return "Arrow";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Arrow";
case "1":
return "Arrow";
case "2":
return "Arrow";
case "3":
return "Arrow";
case "4":
return "Arrow";
case "5":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "9":
return "Arrow";
default: return "";
}
case "3":
switch($cardID[5]) {
case "8":
return "Arrow";
case "9":
return "Arrow";
case "5":
return "Arrow";
case "2":
return "Young";
case "6":
return "Arrow";
case "3":
return "Bow";
case "0":
return "Aura";
case "4":
return "Bow";
default: return "";
}
case "2":
switch($cardID[5]) {
case "5":
return "Aura";
case "6":
return "Aura";
case "7":
return "Aura";
case "8":
return "Aura";
case "9":
return "Aura";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Landmark";
case "2":
return "Young";
case "3":
return "Hammer";
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return "Hammer";
case "6":
return "Aura";
case "2":
return "Head";
case "7":
return "Aura";
case "1":
return "Chest";
default: return "";
}
case "0":
switch($cardID[5]) {
case "5":
return "Chest";
case "0":
return "Gem";
case "2":
return "Young";
case "3":
return "Club";
case "4":
return "Legs";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Aura";
case "5":
return "Aura";
case "6":
return "Aura";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Young";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Arms";
default: return "";
}
case "6":
switch($cardID[5]) {
case "9":
return "Aura";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "8":
return "Dagger";
case "7":
return "Young";
case "9":
return "Head";
case "5":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
case "4":
return "Aura";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "6":
return "Arms";
case "5":
return "Sword";
case "4":
return "Young";
case "7":
return "Legs";
default: return "";
}
case "6":
switch($cardID[5]) {
case "2":
return "Item";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Chest";
case "3":
return "Arms";
case "2":
return "Chest";
case "1":
return "Head";
case "7":
return "Arms";
case "5":
return "Head";
case "8":
return "Legs";
case "6":
return "Chest";
case "4":
return "Legs";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return "Aura";
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "Aura";
case "0":
return "Adjudicator,Off-Hand";
case "9":
return "Adjudicator,Off-Hand";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Adjudicator,Brush";
case "6":
return "Block";
case "4":
return "Legs";
case "2":
return "Chest";
case "1":
return "Head";
case "3":
return "Arms";
default: return "";
}
case "2":
switch($cardID[5]) {
case "4":
return "Adjudicator";
case "2":
return "Aura";
case "5":
return "Adjudicator,Off-Hand";
case "6":
return "Aura";
default: return "";
}
case "0":
switch($cardID[5]) {
case "5":
return "Adjudicator,Hammer";
case "0":
return "Macro";
case "9":
return "Adjudicator,Off-Hand";
case "3":
return "Adjudicator,Off-Hand";
case "4":
return "Adjudicator,Off-Hand";
case "1":
return "Adjudicator";
case "2":
return "Adjudicator";
case "6":
return "Adjudicator";
case "8":
return "Adjudicator";
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
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return "Off-Hand";
case "7":
return "Young";
case "2":
return "Block";
case "6":
return "Block";
case "8":
return "Hammer";
default: return "";
}
case "9":
switch($cardID[5]) {
case "1":
return "Aura";
case "8":
return "Block";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Chest";
case "2":
return "Legs";
case "1":
return "Arms";
default: return "";
}
case "4":
switch($cardID[5]) {
case "8":
return "Block";
case "2":
return "Aura";
case "3":
return "Aura";
case "9":
return "Young";
default: return "";
}
case "3":
switch($cardID[5]) {
case "1":
return "Chest";
case "2":
return "Arms";
case "0":
return "Head";
case "3":
return "Legs";
case "7":
return "Aura";
case "8":
return "Aura";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Item";
case "6":
return "Block";
case "8":
return "Sword";
case "1":
return "Item";
case "2":
return "Item";
case "7":
return "Young";
case "9":
return "Head";
case "3":
return "Item";
case "4":
return "Item";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Block";
case "3":
return "Block";
case "4":
return "Song";
case "5":
return "Song";
case "9":
return "Song";
case "6":
return "Song";
case "7":
return "Song";
case "8":
return "Song";
default: return "";
}
case "0":
switch($cardID[5]) {
case "8":
return "Evo,Chest";
case "9":
return "Evo,Arms";
case "7":
return "Evo,Head";
case "1":
return "Young";
case "5":
return "Base,Arms";
case "4":
return "Base,Chest";
case "3":
return "Base,Head";
case "6":
return "Base,Legs";
case "2":
return "Gun";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Evo,Legs";
case "9":
return "Block";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Arms";
case "2":
return "Chest";
case "0":
return "Fiddle";
case "1":
return "Head";
case "4":
return "Legs";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Aura";
case "3":
return "Item";
case "8":
return "Item";
case "5":
return "Aura";
case "6":
return "Aura";
case "2":
return "Block";
case "7":
return "Aura";
default: return "";
}
default: return "";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "Evo,Chest";
case "9":
return "Evo,Arms";
case "7":
return "Evo,Head";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Evo,Legs";
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "Chest";
case "6":
return "Arms";
case "4":
return "Head";
case "7":
return "Legs";
case "2":
return "Hammer";
case "3":
return "Off-Hand";
case "1":
return "Young";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Block";
case "8":
return "Aura";
case "9":
return "Head";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Legs";
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
if($cardID !== null && strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID) {
case "DTD407": return 4;
case "DTD407": return 4;
case "UPR042": return 1;
case "DYN113": return 40;
case "OUT003": return 19;
case "ROS007": return 40;
case "DTD409": return 4;
case "DTD409": return 4;
case "AAZ001": return 40;
case "ARC038": return 40;
case "CRU119": return 40;
case "OUT089": return 40;
case "UPR409": return 3;
case "UPR409": return 3;
case "DTD412": return 4;
case "DTD412": return 4;
case "CRU047": return 17;
case "OUT047": return 17;
case "HVY045": return 40;
case "DTD164": return 13;
case "MON219": return 6;
case "HER117": return 17;
case "CRU022": return 40;
case "WTR038": return 40;
case "EVR017": return 40;
case "ELE062": return 40;
case "ROS254": return 40;
case "MON153": return 40;
case "HVY134": return 2;
case "UPR410": return 2;
case "UPR410": return 2;
case "AIO001": return 36;
case "EVO001": return 36;
case "EVO002": return 18;
case "ARC001": return 40;
case "CRU098": return 40;
case "UPR408": return 4;
case "UPR408": return 4;
case "CRU076": return 40;
case "WTR113": return 40;
case "UPR406": return 6;
case "UPR406": return 6;
case "UPR001": return 40;
case "DYN001": return 15;
case "MST025": return 40;
case "UPR044": return 40;
case "ROS001": return 40;
case "HER123": return 40;
case "EVR120": return 18;
case "UPR103": return 18;
case "UPR102": return 36;
case "AJV001": return 40;
case "ARC114": return 15;
case "ARC113": return 30;
case "CRU158": return 30;
case "HVY090": return 40;
case "CRU045": return 40;
case "OUT045": return 40;
case "WTR076": return 40;
case "AKO001": return 40;
case "HVY001": return 40;
case "CRU002": return 19;
case "UPR411": return 2;
case "UPR411": return 2;
case "DTD564": return 8;
case "DTD564": return 8;
case "DTD103": return 40;
case "MON119": return 40;
case "ELE031": return 40;
case "EVO004": return 40;
case "DTD410": return 4;
case "DTD410": return 4;
case "UPR412": return 4;
case "UPR412": return 4;
case "DTD193": return 6;
case "UPR413": return 7;
case "UPR413": return 7;
case "MST001": return 40;
case "ELE001": return 40;
case "HVY092": return 40;
case "ROS020": return 18;
case "ROS019": return 36;
case "UPR414": return 6;
case "UPR414": return 6;
case "DTD002": return 16;
case "DTD001": return 32;
case "MON001": return 40;
case "CRU001": return 40;
case "HVY003": return 40;
case "WTR001": return 40;
case "OUT092": return 19;
case "OUT091": return 38;
case "DTD408": return 4;
case "DTD408": return 4;
case "ASB001": return 40;
case "DTD044": return 40;
case "MON029": return 40;
case "DTD405": return 4;
case "DTD405": return 4;
case "DYN612": return 4;
case "DYN612": return 4;
case "DYN612": return 4;
case "EVO007": return 40;
case "UPR415": return 4;
case "UPR415": return 4;
case "DTD406": return 4;
case "DTD406": return 4;
case "UPR407": return 5;
case "UPR407": return 5;
case "MON220": return 6;
case "OUT001": return 40;
case "EVR019": return 21;
case "ROS013": return 40;
case "HVY047": return 40;
case "DTD411": return 4;
case "DTD411": return 4;
case "ARC075": return 40;
case "CRU138": return 40;
case "DTD133": return 40;
case "UPR416": return 1;
case "UPR416": return 1;
case "UPR417": return 3;
case "UPR417": return 3;
case "DYN025": return 22;
case "MST046": return 40;
default: return 20;}
}

function GeneratedRarity($cardID) {
if($cardID !== null && strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "M";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "0":
return "T";
case "7":
return "R";
case "9":
return "R";
case "8":
return "R";
case "2":
return "M";
case "3":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "6":
return "R";
case "8":
return "R";
case "7":
return "R";
case "3":
return "M";
case "9":
return "R";
case "5":
return "M";
case "4":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "4":
return "R";
case "6":
return "R";
case "5":
return "R";
case "1":
return "M";
case "2":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "3":
return "M";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "3":
return "M";
case "1":
return "M";
case "0":
return "L";
case "2":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "8":
return "T";
case "9":
return "T";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "8":
return "T";
case "9":
return "T";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "5":
return "M";
case "6":
return "M";
case "9":
return "M";
case "7":
return "M";
case "8":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "3":
return "T";
case "8":
return "M";
case "5":
return "R";
case "4":
return "L";
case "0":
return "F";
case "2":
return "T";
case "1":
return "M";
case "9":
return "M";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "6":
return "R";
case "4":
return "R";
case "0":
return "M";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "5":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "6":
return "T";
case "5":
return "M";
case "3":
return "T";
case "8":
return "R";
case "7":
return "L";
case "4":
return "T";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "9":
return "R";
case "8":
return "L";
case "7":
return "T";
case "6":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "1":
return "M";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "2":
return "M";
case "3":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "3":
return "M";
case "2":
return "M";
case "1":
return "M";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "6":
return "L";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "T";
case "9":
return "M";
case "8":
return "M";
case "7":
return "M";
case "5":
return "M";
case "6":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "5":
return "M";
case "7":
return "M";
case "8":
return "L";
case "0":
return "M";
case "1":
return "M";
case "4":
return "M";
case "2":
return "M";
case "3":
return "M";
case "6":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
default: return "C";
}
case "6":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return "M";
case "8":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
default: return "C";
}
default: return "C";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "F";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "2":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "3":
return "M";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "5":
return "V";
case "6":
return "V";
case "7":
return "V";
case "8":
return "V";
case "9":
return "V";
default: return "C";
}
default: return "C";
}
case "5":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "V";
case "1":
return "V";
case "2":
return "V";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "M";
case "6":
return "M";
case "0":
return "F";
case "4":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "3":
return "M";
case "2":
return "T";
case "1":
return "T";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "3":
return "M";
case "2":
return "M";
case "0":
return "T";
case "4":
return "M";
case "1":
return "M";
case "5":
return "R";
case "9":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "2":
return "M";
case "9":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "4":
return "M";
case "3":
return "M";
case "5":
return "M";
case "0":
return "L";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "2":
return "R";
case "3":
return "R";
case "1":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "8":
return "T";
case "9":
return "L";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "1":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "9":
return "T";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "9":
return "T";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "9":
return "M";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "1":
return "T";
case "2":
return "M";
case "0":
return "T";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "5":
return "M";
case "6":
return "M";
case "8":
return "R";
case "9":
return "R";
case "7":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "0":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "1":
return "M";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "7":
return "L";
case "9":
return "L";
case "6":
return "T";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "4":
return "T";
case "3":
return "T";
case "7":
return "M";
case "5":
return "T";
case "8":
return "M";
case "6":
return "M";
case "9":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "0":
return "L";
case "2":
return "M";
case "1":
return "M";
case "3":
return "M";
case "8":
return "R";
case "9":
return "R";
case "4":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "3":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "1":
return "M";
case "0":
return "T";
case "4":
return "M";
case "5":
return "M";
case "9":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "9":
return "T";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "5":
return "T";
case "6":
return "T";
case "4":
return "T";
case "9":
return "M";
case "7":
return "L";
default: return "C";
}
default: return "C";
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "T";
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "0":
return "S";
case "1":
return "S";
case "9":
return "R";
case "2":
return "S";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "0":
return "L";
case "9":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "M";
case "2":
return "S";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "1":
return "S";
case "3":
return "S";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "8":
return "M";
case "5":
return "T";
case "4":
return "T";
case "3":
return "T";
case "2":
return "T";
case "9":
return "M";
case "6":
return "L";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "S";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "M";
case "3":
return "S";
case "1":
return "M";
case "2":
return "S";
case "8":
return "R";
case "9":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "4":
return "S";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "9":
return "T";
case "8":
return "T";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "2":
return "T";
case "1":
return "T";
case "0":
return "F";
case "6":
return "M";
case "8":
return "S";
case "9":
return "S";
case "7":
return "M";
case "4":
return "L";
case "3":
return "T";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "T";
case "5":
return "S";
case "6":
return "S";
case "7":
return "S";
case "3":
return "M";
case "1":
return "L";
case "8":
return "R";
case "9":
return "R";
case "4":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "8":
return "L";
case "7":
return "T";
case "6":
return "T";
case "5":
return "T";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "0":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "0":
return "R";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "8":
return "T";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "7":
return "T";
case "8":
return "T";
case "2":
return "R";
case "4":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "6":
return "R";
case "8":
return "R";
case "5":
return "M";
case "9":
return "R";
case "1":
return "M";
case "3":
return "R";
case "2":
return "R";
case "4":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "2":
return "M";
case "3":
return "M";
case "5":
return "R";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "3":
return "R";
case "7":
return "T";
case "0":
return "R";
case "6":
return "R";
case "9":
return "R";
case "8":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "3":
return "R";
case "2":
return "R";
case "9":
return "R";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "R";
case "8":
return "M";
case "9":
return "R";
case "7":
return "M";
case "5":
return "M";
case "6":
return "M";
case "4":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "T";
case "2":
return "T";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "3":
return "M";
case "6":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "4":
return "M";
case "9":
return "M";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "2":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "5":
return "M";
case "3":
return "M";
case "2":
return "M";
case "1":
return "R";
case "4":
return "M";
case "6":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "7":
return "T";
case "6":
return "M";
case "8":
return "T";
case "5":
return "M";
case "3":
return "R";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "6":
return "M";
case "7":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "6":
return "R";
case "0":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "1":
return "M";
case "9":
return "M";
case "6":
return "M";
case "4":
return "M";
case "5":
return "M";
case "3":
return "M";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "M";
case "8":
return "M";
case "1":
return "M";
case "3":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "1":
return "M";
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "3":
return "M";
case "1":
return "R";
case "5":
return "M";
case "2":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "4":
return "M";
case "2":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "3":
return "M";
case "1":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "1":
return "M";
case "2":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "3":
return "M";
case "0":
return "R";
case "4":
return "M";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "8":
return "R";
case "0":
return "M";
case "1":
return "M";
case "6":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "9":
return "R";
case "2":
return "M";
case "7":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "5":
return "M";
case "2":
return "M";
case "7":
return "R";
case "4":
return "M";
case "1":
return "R";
case "3":
return "M";
case "8":
return "R";
case "6":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "8":
return "L";
case "9":
return "M";
case "7":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "8":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "M";
case "0":
return "F";
case "7":
return "M";
case "2":
return "R";
case "4":
return "R";
case "5":
return "R";
case "8":
return "M";
case "6":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "7":
return "R";
case "9":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "3":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "R";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "9":
return "R";
case "7":
return "R";
case "5":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "4":
return "M";
case "2":
return "M";
case "3":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "5":
return "M";
case "6":
return "M";
case "7":
return "M";
case "4":
return "R";
case "8":
return "M";
case "9":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "9":
return "R";
case "7":
return "L";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return "M";
case "9":
return "M";
case "4":
return "M";
case "5":
return "M";
case "7":
return "M";
case "3":
return "M";
case "8":
return "M";
case "0":
return "M";
case "1":
return "M";
case "2":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "1":
return "L";
case "8":
return "M";
case "6":
return "M";
case "7":
return "M";
case "9":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "5":
return "V";
case "4":
return "V";
case "0":
return "M";
case "2":
return "M";
case "7":
return "T";
case "1":
return "M";
case "3":
return "M";
case "6":
return "V";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "6":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "8":
return "T";
case "7":
return "T";
case "9":
return "M";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "L";
case "6":
return "M";
case "7":
return "M";
case "9":
return "M";
case "2":
return "T";
case "8":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "M";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "4":
return "L";
case "8":
return "M";
case "7":
return "M";
case "9":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "M";
case "2":
return "R";
case "6":
return "M";
case "9":
return "M";
case "8":
return "R";
case "7":
return "M";
case "5":
return "T";
case "1":
return "M";
case "4":
return "T";
case "3":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "6":
return "R";
case "8":
return "T";
case "7":
return "M";
case "2":
return "T";
case "1":
return "M";
case "4":
return "M";
case "3":
return "T";
case "9":
return "T";
case "5":
return "M";
case "0":
return "F";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "8":
return "L";
case "4":
return "R";
case "5":
return "T";
case "6":
return "T";
case "3":
return "M";
case "0":
return "T";
case "7":
return "T";
case "2":
return "M";
case "1":
return "T";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "9":
return "R";
case "7":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
case "8":
return "M";
case "1":
return "L";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "8":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "3":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "4":
return "M";
case "1":
return "M";
case "2":
return "M";
case "5":
return "M";
case "9":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "M";
case "9":
return "R";
case "7":
return "M";
case "1":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "1":
return "R";
case "5":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "5":
return "R";
case "3":
return "R";
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return "L";
case "1":
return "L";
case "0":
return "L";
case "2":
return "L";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "4":
return "M";
case "7":
return "M";
case "9":
return "M";
case "8":
return "M";
case "5":
return "M";
case "6":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "6":
return "T";
case "1":
return "M";
case "2":
return "T";
case "0":
return "F";
case "4":
return "M";
case "5":
return "T";
case "3":
return "T";
case "9":
return "T";
case "8":
return "T";
case "7":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "1":
return "M";
case "0":
return "M";
case "4":
return "R";
case "9":
return "R";
case "5":
return "R";
case "2":
return "M";
case "3":
return "M";
case "6":
return "R";
case "8":
return "R";
case "7":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "1":
return "R";
case "0":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "8":
return "M";
case "6":
return "M";
case "7":
return "M";
case "9":
return "M";
case "4":
return "T";
case "3":
return "T";
case "2":
return "T";
case "5":
return "T";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "5":
return "R";
case "0":
return "M";
case "6":
return "R";
case "9":
return "R";
case "8":
return "R";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "7":
return "R";
case "4":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "M";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "6":
return "M";
case "4":
return "T";
case "5":
return "L";
case "7":
return "M";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "3":
return "M";
case "6":
return "M";
case "0":
return "M";
case "9":
return "L";
case "1":
return "M";
case "4":
return "M";
case "8":
return "M";
case "2":
return "M";
case "5":
return "M";
case "7":
return "L";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "0":
return "T";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "9":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "5":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "6":
return "M";
case "3":
return "M";
case "0":
return "M";
case "1":
return "M";
case "4":
return "M";
case "5":
return "M";
case "2":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "0":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "1":
return "M";
default: return "C";
}
default: return "C";
}
case "4":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "M";
case "0":
return "M";
case "4":
return "R";
case "9":
return "R";
case "5":
return "R";
case "2":
return "M";
case "3":
return "M";
case "6":
return "R";
case "8":
return "R";
case "7":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "1":
return "R";
case "0":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "8":
return "M";
case "6":
return "M";
case "7":
return "M";
case "9":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "L";
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "0":
return "M";
case "1":
return "M";
case "4":
return "M";
case "2":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "4":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "5":
return "R";
case "4":
return "L";
case "6":
return "M";
case "8":
return "M";
case "7":
return "M";
case "9":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "7":
return "M";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "0":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "6":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "4":
return "M";
case "5":
return "M";
case "3":
return "M";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "8":
return "M";
case "7":
return "M";
case "5":
return "M";
case "6":
return "L";
case "9":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "5":
return "M";
case "3":
return "M";
case "6":
return "M";
case "4":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "7":
return "M";
case "8":
return "L";
case "9":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "8":
return "M";
case "7":
return "M";
case "9":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "9":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "M";
case "2":
return "M";
case "3":
return "M";
case "1":
return "M";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "3":
return "M";
case "4":
return "M";
case "1":
return "M";
case "2":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "0":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "2":
return "M";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "4":
return "M";
case "0":
return "F";
case "5":
return "M";
case "2":
return "T";
case "1":
return "T";
case "3":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "4":
return "M";
case "3":
return "T";
case "2":
return "T";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "5":
return "M";
case "6":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "8":
return "R";
case "9":
return "R";
case "5":
return "M";
case "7":
return "M";
case "2":
return "T";
case "1":
return "T";
case "6":
return "M";
case "3":
return "T";
case "4":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "3":
return "M";
case "2":
return "M";
case "1":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "M";
case "5":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "3":
return "L";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "7":
return "M";
case "6":
return "M";
case "8":
return "R";
case "9":
return "R";
case "4":
return "L";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "7":
return "M";
case "5":
return "L";
case "0":
return "T";
case "9":
return "R";
case "1":
return "T";
case "3":
return "M";
case "4":
return "M";
case "2":
return "M";
case "8":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "9":
return "T";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "3":
return "L";
case "5":
return "M";
case "2":
return "T";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "7":
return "T";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "3":
return "M";
case "2":
return "T";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "4":
return "L";
case "6":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "3":
return "L";
case "5":
return "M";
case "2":
return "T";
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return "M";
case "9":
return "M";
case "8":
return "M";
case "5":
return "M";
case "6":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "2":
return "M";
case "0":
return "M";
case "1":
return "M";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return "M";
case "2":
return "M";
case "7":
return "L";
case "1":
return "L";
case "8":
return "M";
case "9":
return "M";
case "6":
return "R";
case "3":
return "M";
case "0":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "6":
return "M";
case "8":
return "M";
case "5":
return "R";
case "2":
return "M";
case "7":
return "L";
case "9":
return "M";
case "1":
return "M";
case "0":
return "L";
case "3":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "6":
return "M";
case "1":
return "L";
case "4":
return "R";
case "2":
return "R";
case "5":
return "R";
case "3":
return "R";
case "9":
return "M";
case "8":
return "M";
case "7":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "1":
return "M";
case "0":
return "M";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return "M";
case "8":
return "M";
case "7":
return "M";
case "9":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "4":
return "L";
case "9":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "1":
return "M";
case "0":
return "M";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "8":
return "R";
case "9":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "M";
case "2":
return "M";
case "1":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "7":
return "M";
case "5":
return "R";
case "8":
return "M";
case "9":
return "M";
case "6":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "5":
return "R";
case "6":
return "M";
case "4":
return "R";
case "3":
return "M";
case "7":
return "M";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return "M";
case "9":
return "M";
case "6":
return "R";
case "7":
return "L";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "0":
return "M";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "1":
return "M";
case "3":
return "M";
case "2":
return "M";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "0":
return "M";
case "1":
return "M";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "4":
return "L";
case "5":
return "M";
case "6":
return "M";
case "7":
return "M";
case "8":
return "M";
case "9":
return "M";
case "0":
return "F";
case "3":
return "R";
case "2":
return "M";
case "1":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "2":
return "M";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "9":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
default: return "C";
}
case "5":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return "L";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "6":
return "M";
case "4":
return "M";
case "3":
return "R";
case "2":
return "M";
case "5":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "1":
return "M";
case "2":
return "R";
case "9":
return "R";
case "3":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "4":
return "M";
case "5":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "4":
return "R";
case "3":
return "M";
case "7":
return "L";
case "9":
return "M";
case "8":
return "M";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "9":
return "R";
case "5":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "3":
return "M";
case "2":
return "R";
case "4":
return "M";
case "1":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "M";
case "3":
return "M";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "1":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "2":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "R";
case "9":
return "M";
case "0":
return "F";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "1":
return "L";
case "5":
return "M";
case "7":
return "M";
case "8":
return "M";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "1":
return "M";
case "8":
return "R";
case "9":
return "R";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "0":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "5":
return "M";
case "8":
return "M";
case "9":
return "M";
case "6":
return "R";
case "7":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "1":
return "M";
case "2":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "0":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "8":
return "M";
case "9":
return "M";
case "6":
return "M";
case "7":
return "R";
case "5":
return "M";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "9":
return "R";
case "8":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "7":
return "M";
case "8":
return "M";
case "9":
return "R";
case "6":
return "L";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "8":
return "R";
case "9":
return "R";
case "3":
return "M";
case "2":
return "L";
case "5":
return "M";
case "6":
return "M";
case "7":
return "M";
case "4":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "4":
return "L";
case "5":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "6":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "2":
return "M";
default: return "C";
}
default: return "C";
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return "M";
default: return "C";
}
default: return "C";
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "L";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return "M";
case "1":
return "M";
case "8":
return "M";
case "7":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "8":
return "T";
case "5":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "3":
return "R";
case "8":
return "R";
case "1":
return "R";
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "0":
return "M";
case "1":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "T";
case "7":
return "M";
case "6":
return "M";
case "4":
return "T";
case "8":
return "M";
case "3":
return "T";
case "5":
return "M";
case "9":
return "M";
case "1":
return "T";
case "2":
return "T";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "9":
return "R";
case "2":
return "M";
case "0":
return "M";
case "1":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "7":
return "M";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "L";
case "6":
return "R";
case "0":
return "F";
case "2":
return "T";
case "1":
return "M";
case "9":
return "R";
case "5":
return "T";
case "7":
return "R";
case "4":
return "T";
case "3":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "1":
return "L";
case "7":
return "M";
case "3":
return "L";
case "4":
return "R";
case "5":
return "R";
case "0":
return "R";
case "8":
return "M";
case "2":
return "R";
case "9":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "4":
return "M";
case "0":
return "R";
case "6":
return "M";
case "5":
return "M";
case "2":
return "M";
case "3":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "6":
return "T";
case "5":
return "M";
case "9":
return "T";
case "8":
return "T";
case "7":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "M";
case "2":
return "R";
case "3":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "4":
return "R";
case "8":
return "R";
case "9":
return "R";
case "1":
return "M";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "4":
return "T";
case "7":
return "L";
case "9":
return "R";
case "5":
return "R";
case "1":
return "T";
case "0":
return "M";
case "3":
return "T";
case "2":
return "M";
case "6":
return "R";
case "8":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "R";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "5":
return "L";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "1":
return "M";
case "4":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "5":
return "M";
case "2":
return "M";
case "3":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "4":
return "T";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "6":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "6":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "6":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "1":
return "R";
case "0":
return "R";
case "2":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "1":
return "R";
case "0":
return "R";
case "2":
return "R";
default: return "C";
}
default: return "C";
}
case "6":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return "M";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "P";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "3":
return "P";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "P";
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "T";
case "5":
return "T";
case "4":
return "T";
case "8":
return "M";
case "6":
return "T";
case "9":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "5":
return "M";
case "2":
return "T";
case "1":
return "T";
case "9":
return "R";
case "8":
return "M";
case "6":
return "M";
case "7":
return "M";
case "3":
return "T";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "4":
return "L";
case "7":
return "M";
case "6":
return "M";
case "9":
return "M";
case "8":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "6":
return "L";
case "0":
return "M";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "9":
return "M";
case "1":
return "M";
case "8":
return "M";
case "5":
return "T";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "8":
return "M";
case "6":
return "L";
case "9":
return "M";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "2":
return "L";
case "7":
return "M";
case "3":
return "T";
case "9":
return "M";
case "8":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "4":
return "M";
case "5":
return "M";
case "9":
return "R";
case "3":
return "T";
case "2":
return "T";
case "1":
return "T";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "0":
return "M";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "3":
return "M";
case "0":
return "T";
case "1":
return "L";
case "4":
return "M";
case "8":
return "L";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "6":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
default: return "C";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "R";
case "8":
return "M";
case "6":
return "M";
case "7":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "T";
case "5":
return "T";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "M";
case "7":
return "M";
case "0":
return "F";
case "8":
return "S";
case "2":
return "T";
case "1":
return "T";
case "3":
return "T";
case "9":
return "S";
case "4":
return "L";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "2":
return "M";
case "9":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "4":
return "S";
case "1":
return "M";
case "3":
return "S";
case "5":
return "S";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "T";
case "5":
return "S";
case "3":
return "M";
case "8":
return "R";
case "9":
return "R";
case "6":
return "S";
case "7":
return "S";
case "4":
return "M";
case "1":
return "L";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "S";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "9":
return "T";
case "8":
return "T";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "8":
return "T";
case "7":
return "T";
case "6":
return "T";
case "9":
return "L";
case "5":
return "T";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "6":
return "L";
case "5":
return "T";
case "4":
return "T";
case "3":
return "T";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "2":
return "S";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "1":
return "S";
case "3":
return "S";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "0":
return "M";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "2":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "9":
return "M";
case "0":
return "L";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "2":
return "S";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "0":
return "S";
case "1":
return "S";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "T";
case "5":
return "T";
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "3":
return "M";
case "7":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
case "9":
return "R";
case "8":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "6":
return "R";
case "2":
return "M";
case "3":
return "M";
case "9":
return "R";
case "5":
return "M";
case "7":
return "R";
case "4":
return "M";
case "8":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "3":
return "M";
case "8":
return "R";
case "9":
return "R";
case "4":
return "M";
case "2":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "9":
return "M";
case "0":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "1":
return "R";
case "0":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "9":
return "L";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "4":
return "L";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "T";
case "5":
return "M";
case "6":
return "M";
case "7":
return "M";
case "8":
return "M";
case "0":
return "F";
case "9":
return "M";
case "4":
return "T";
case "2":
return "T";
case "1":
return "T";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "T";
case "3":
return "T";
case "7":
return "T";
case "8":
return "T";
case "5":
return "L";
case "6":
return "M";
case "2":
return "T";
case "1":
return "T";
case "4":
return "L";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "9":
return "T";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "2":
return "M";
case "1":
return "L";
case "0":
return "M";
case "3":
return "M";
case "8":
return "R";
case "9":
return "R";
case "4":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "7":
return "T";
case "8":
return "T";
case "6":
return "T";
case "5":
return "T";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
case "0":
return "M";
case "1":
return "M";
case "2":
return "M";
case "9":
return "R";
case "4":
return "M";
case "5":
return "M";
case "3":
return "M";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return "T";
case "8":
return "T";
case "5":
return "T";
case "6":
return "T";
case "7":
return "T";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "R";
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
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "M";
case "0":
return "R";
case "9":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "9":
return "R";
case "7":
return "M";
case "2":
return "R";
case "6":
return "R";
case "8":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "1":
return "R";
case "3":
return "R";
case "4":
return "R";
case "8":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
case "4":
return "R";
case "6":
return "R";
case "8":
return "M";
case "2":
return "R";
case "1":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "9":
return "R";
case "1":
return "R";
case "2":
return "R";
case "0":
return "R";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "4":
return "R";
case "6":
return "M";
case "1":
return "R";
case "7":
return "R";
case "9":
return "M";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "5":
return "M";
case "3":
return "R";
case "7":
return "M";
case "2":
return "R";
case "0":
return "R";
case "6":
return "R";
case "1":
return "R";
case "4":
return "R";
case "9":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "M";
case "8":
return "R";
case "1":
return "R";
case "2":
return "R";
case "9":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "1":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "8":
return "R";
case "9":
return "R";
case "7":
return "R";
case "1":
return "M";
case "2":
return "R";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "3":
return "T";
case "8":
return "T";
case "4":
return "T";
case "5":
return "T";
case "6":
return "T";
case "2":
return "R";
case "7":
return "T";
default: return "C";
}
default: return "C";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "R";
case "9":
return "R";
case "7":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "7":
return "R";
case "4":
return "R";
case "8":
return "T";
case "9":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "7":
return "R";
case "9":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "2":
return "R";
case "1":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "R";
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "P";
case "5":
return "P";
case "3":
return "P";
case "6":
return "P";
case "7":
return "P";
case "0":
return "P";
case "9":
return "P";
case "2":
return "P";
case "8":
return "P";
case "4":
return "P";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "P";
case "6":
return "P";
case "4":
return "P";
case "2":
return "P";
case "1":
return "P";
case "5":
return "P";
case "3":
return "P";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "4":
return "P";
case "2":
return "P";
case "3":
return "P";
case "9":
return "P";
case "7":
return "P";
case "5":
return "P";
case "1":
return "P";
case "6":
return "P";
case "0":
return "P";
case "8":
return "P";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "5":
return "P";
case "0":
return "P";
case "9":
return "P";
case "3":
return "P";
case "4":
return "P";
case "1":
return "P";
case "2":
return "P";
case "6":
return "P";
case "8":
return "P";
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "P";
case "7":
return "P";
case "8":
return "P";
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
if($cardID !== null && strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID) {
case "HVY006": return true;
case "MST003": return true;
case "JDG030": return true;
case "CRU079": return true;
case "CRU080": return true;
case "HVY094": return true;
case "DTD135": return true;
case "JDG005": return true;
case "HVY245": return true;
case "TCC028": return true;
case "CRU048": return true;
case "CRU049": return true;
case "OUT048": return true;
case "WTR078": return true;
case "MON105": return true;
case "MON106": return true;
case "HVY049": return true;
case "HVY095": return true;
case "AKO002": return true;
case "CRU004": return true;
case "CRU005": return true;
case "HVY005": return true;
case "HVY050": return true;
case "HVY007": return true;
case "OUT005": return true;
case "OUT006": return true;
case "OUT007": return true;
case "OUT008": return true;
case "HVY096": return true;
case "DYN069": return true;
case "DYN070": return true;
case "TER002": return true;
case "OUT009": return true;
case "OUT010": return true;
case "DTD210": return true;
case "DYN115": return true;
case "DYN116": return true;
case "OUT004": return true;
case "ELE202": return true;
case "ELE003": return true;
case "CRU051": return true;
case "CRU052": return true;
default: return false;}
}

function GeneratedCardClass($cardID) {
if($cardID !== null && strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "M":
switch($cardID[1]) {
case "S":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "0":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "4":
return "ASSASSIN";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "4":
return "NINJA";
case "6":
return "NINJA";
case "5":
return "NINJA";
case "1":
return "NINJA";
case "0":
return "NINJA";
case "2":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
case "3":
return "NINJA";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "3":
return "GENERIC";
case "1":
return "GENERIC";
case "0":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "4":
return "ASSASSIN";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "8":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "4":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "NINJA";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "4":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "7":
return "ASSASSIN";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
case "3":
return "NINJA";
case "2":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "6":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "3":
return "ASSASSIN";
case "8":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "4":
return "ASSASSIN";
case "9":
return "ILLUSIONIST";
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "2":
return "ASSASSIN";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "0":
return "ILLUSIONIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "9":
return "NINJA";
case "8":
return "NINJA";
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "7":
return "NINJA";
case "6":
return "NINJA";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "4":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "3":
return "ASSASSIN";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "0":
return "GENERIC";
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "7":
return "MECHANOLOGIST";
case "5":
return "GUARDIAN";
case "6":
return "WARRIOR";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "5":
return "ILLUSIONIST";
case "7":
return "RUNEBLADE";
case "8":
return "ILLUSIONIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "4":
return "WIZARD";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "6":
return "BRUTE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "6":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "GENERIC";
case "1":
return "RUNEBLADE";
case "9":
return "GENERIC";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "9":
return "RUNEBLADE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "1":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "2":
return "BRUTE";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "5":
return "GENERIC";
case "3":
return "GENERIC";
case "1":
return "GENERIC";
case "4":
return "GENERIC";
case "2":
return "GENERIC";
case "6":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "7":
return "GENERIC";
case "0":
return "GENERIC";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "3":
return "WARRIOR";
case "2":
return "WARRIOR";
case "0":
return "WARRIOR";
case "4":
return "WARRIOR";
case "1":
return "WARRIOR";
case "5":
return "WARRIOR";
case "9":
return "WARRIOR";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "0":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "9":
return "WARRIOR";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "4":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "3":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "0":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "9":
return "BRUTE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "9":
return "BRUTE";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "8":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "9":
return "WARRIOR";
case "7":
return "WARRIOR";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
default: return "NONE";
}
default: return "NONE";
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "2":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "9":
return "WIZARD";
case "2":
return "WIZARD";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "3":
return "GENERIC";
case "9":
return "GENERIC";
case "4":
return "GENERIC";
case "8":
return "GENERIC";
case "7":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "2":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "1":
return "GENERIC";
case "3":
return "GENERIC";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "8":
return "WIZARD";
case "5":
return "WIZARD";
case "4":
return "WIZARD";
case "3":
return "WIZARD";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "7":
return "WIZARD";
case "2":
return "RUNEBLADE";
case "9":
return "WIZARD";
case "6":
return "WIZARD";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "0":
return "GENERIC";
case "6":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "9":
return "RANGER";
case "8":
return "RANGER";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "2":
return "RANGER";
case "0":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "3":
return "RANGER";
case "1":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
case "4":
return "RANGER";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "9":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "6":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "7":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "0":
return "RANGER";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "8":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "4":
return "GENERIC";
case "7":
return "RUNEBLADE";
case "3":
return "GENERIC";
case "6":
return "GENERIC";
case "2":
return "RUNEBLADE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "5":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "9":
return "GENERIC";
case "8":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "K":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "BRUTE,WARRIOR";
case "8":
return "BRUTE";
case "6":
return "BRUTE";
case "9":
return "GENERIC";
case "0":
return "BRUTE";
case "1":
return "GENERIC";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "GENERIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "0":
return "BRUTE,GUARDIAN";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "7":
return "BRUTE";
case "6":
return "BRUTE";
case "8":
return "BRUTE,WARRIOR";
case "5":
return "BRUTE";
case "9":
return "GENERIC";
case "1":
return "BRUTE";
case "3":
return "BRUTE";
case "2":
return "BRUTE";
case "4":
return "BRUTE";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "A":
switch($cardID[2]) {
case "Z":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
case "3":
return "RANGER";
case "2":
return "RANGER";
case "7":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "4":
return "RANGER";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "RANGER";
case "1":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
case "2":
return "GENERIC";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "7":
return "RANGER";
case "4":
return "RANGER";
case "6":
return "GENERIC";
case "9":
return "RANGER";
case "0":
return "RANGER";
case "1":
return "RANGER";
case "5":
return "RANGER";
case "2":
return "GENERIC";
case "3":
return "RANGER";
case "8":
return "RANGER";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "I":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "4":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "WARRIOR";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "5":
return "WARRIOR";
case "9":
return "WARRIOR";
case "3":
return "WARRIOR";
case "2":
return "WARRIOR";
case "1":
return "WARRIOR";
case "6":
return "WARRIOR";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "7":
return "GENERIC";
case "6":
return "WARRIOR";
case "8":
return "GENERIC";
case "5":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "GUARDIAN";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "1":
return "GUARDIAN";
case "3":
return "GUARDIAN";
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "WIZARD";
case "4":
return "WIZARD";
case "2":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
case "3":
return "WIZARD";
case "1":
return "WIZARD";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "9":
return "RANGER";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "8":
return "MERCHANT";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "1":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "7":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "8":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "6":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "9":
return "GENERIC";
case "2":
return "GENERIC";
case "7":
return "GENERIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "9":
return "WIZARD";
case "8":
return "WIZARD";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "RANGER";
case "5":
return "RANGER";
case "9":
return "RANGER";
case "2":
return "RANGER";
case "7":
return "RANGER";
case "4":
return "RANGER";
case "1":
return "RANGER";
case "3":
return "RANGER";
case "8":
return "RANGER";
case "6":
return "RANGER";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "WIZARD";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "GENERIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "9":
return "RUNEBLADE";
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "8":
return "RUNEBLADE";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "9":
return "GUARDIAN";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "9":
return "BRUTE";
case "0":
return "RUNEBLADE";
case "7":
return "BRUTE";
case "2":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "8":
return "BRUTE";
case "1":
return "BRUTE";
case "3":
return "BRUTE";
case "6":
return "BRUTE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "7":
return "NINJA";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "8":
return "NINJA";
case "9":
return "NINJA";
case "6":
return "NINJA";
case "5":
return "NINJA";
case "4":
return "GUARDIAN";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "2":
return "NINJA";
case "9":
return "WARRIOR";
case "8":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "3":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "3":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
case "0":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "4":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "0":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "SHAPESHIFTER";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return "MECHANOLOGIST";
case "9":
return "ILLUSIONIST";
case "4":
return "GUARDIAN";
case "5":
return "NINJA";
case "7":
return "RANGER";
case "3":
return "BRUTE";
case "8":
return "WARRIOR";
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "2":
return "ASSASSIN";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "8":
return "GENERIC";
case "7":
return "GENERIC";
case "0":
return "GENERIC";
case "9":
return "ASSASSIN";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "1":
return "GENERIC";
case "3":
return "GENERIC";
case "8":
return "GENERIC";
case "6":
return "GENERIC";
case "4":
return "GENERIC";
case "2":
return "GENERIC";
case "5":
return "GENERIC";
case "0":
return "WIZARD";
case "7":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "5":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "0":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "3":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "6":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "5":
return "WIZARD";
case "4":
return "WIZARD";
case "9":
return "WIZARD";
case "0":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "8":
return "WIZARD";
case "1":
return "RUNEBLADE";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "5":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "6":
return "WIZARD";
case "9":
return "WIZARD";
case "8":
return "WIZARD";
case "7":
return "WIZARD";
case "5":
return "WIZARD";
case "1":
return "RUNEBLADE";
case "4":
return "WIZARD";
case "3":
return "WIZARD";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "0":
return "WIZARD";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "4":
return "WIZARD";
case "3":
return "WIZARD";
case "0":
return "WIZARD";
case "2":
return "WIZARD";
case "1":
return "WIZARD";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "4":
return "GENERIC";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "7":
return "BRUTE";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "1":
return "BRUTE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "4":
return "BRUTE";
case "1":
return "BRUTE";
case "8":
return "GENERIC";
case "5":
return "BRUTE";
case "0":
return "BRUTE";
case "9":
return "GENERIC";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "1":
return "BRUTE";
case "5":
return "BRUTE";
case "4":
return "GENERIC";
case "6":
return "GENERIC";
case "2":
return "GENERIC";
case "7":
return "GENERIC";
case "0":
return "GENERIC";
case "3":
return "BRUTE";
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
case "E":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "4":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "8":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "3":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "7":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "8":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "5":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "6":
return "ASSASSIN";
case "4":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "5":
return "ASSASSIN";
case "7":
return "BRUTE";
case "8":
return "GUARDIAN";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "9":
return "NINJA";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "3":
return "WIZARD";
case "6":
return "ILLUSIONIST";
case "0":
return "WARRIOR";
case "9":
return "GENERIC";
case "1":
return "RANGER";
case "4":
return "ILLUSIONIST";
case "2":
return "RUNEBLADE";
case "7":
return "WARRIOR";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
case "4":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "7":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "8":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
case "4":
return "WIZARD";
case "2":
return "WIZARD";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "4":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "5":
return "GENERIC";
case "4":
return "GENERIC";
case "6":
return "GENERIC";
case "8":
return "GENERIC";
case "7":
return "GENERIC";
case "9":
return "GENERIC";
case "3":
return "ILLUSIONIST";
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "0":
return "ILLUSIONIST";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "6":
return "GENERIC";
case "5":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "0":
return "BRUTE";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "0":
return "GUARDIAN";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "8":
return "RANGER";
case "7":
return "RANGER";
case "5":
return "MERCHANT";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "6":
return "MERCHANT";
case "9":
return "RANGER";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "9":
return "MECHANOLOGIST";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "5":
return "WARRIOR";
case "3":
return "WARRIOR";
case "6":
return "WARRIOR";
case "4":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "8":
return "NINJA";
case "7":
return "NINJA";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "9":
return "NINJA";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
case "0":
return "NINJA";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "3":
return "GUARDIAN";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "4":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "0":
return "RANGER";
case "1":
return "RANGER";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "8":
return "RANGER";
case "9":
return "RANGER";
case "5":
return "RANGER";
case "7":
return "RANGER";
case "2":
return "RANGER";
case "1":
return "RANGER";
case "6":
return "RANGER";
case "3":
return "RANGER";
case "0":
return "GUARDIAN";
case "4":
return "RANGER";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "6":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "4":
return "RANGER";
case "3":
return "RANGER";
case "9":
return "RANGER";
case "5":
return "RANGER";
case "2":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "7":
return "GENERIC";
case "5":
return "GENERIC";
case "4":
return "GENERIC";
case "3":
return "GENERIC";
case "6":
return "GENERIC";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "3":
return "RUNEBLADE";
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "2":
return "GUARDIAN";
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "2":
return "WIZARD";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "0":
return "RANGER";
case "1":
return "RUNEBLADE";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "7":
return "NINJA";
case "6":
return "ASSASSIN";
case "4":
return "GENERIC";
case "8":
return "MECHANOLOGIST";
case "3":
return "GENERIC";
case "5":
return "ASSASSIN";
case "9":
return "RANGER";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "0":
return "GENERIC";
case "5":
return "GENERIC";
case "2":
return "GENERIC";
case "7":
return "GENERIC";
case "1":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "9":
return "GENERIC";
case "2":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "0":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "3":
return "BRUTE,WARRIOR";
case "4":
return "BRUTE,WARRIOR";
case "5":
return "BRUTE,WARRIOR";
case "6":
return "BRUTE,WARRIOR";
case "7":
return "BRUTE,WARRIOR";
case "8":
return "BRUTE,WARRIOR";
case "1":
return "BRUTE,WARRIOR";
case "0":
return "BRUTE,WARRIOR";
case "2":
return "BRUTE,WARRIOR";
case "9":
return "BRUTE,WARRIOR";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "5":
return "GENERIC";
case "9":
return "GENERIC";
case "8":
return "GENERIC";
case "6":
return "GENERIC";
case "2":
return "GUARDIAN,WARRIOR";
case "3":
return "GUARDIAN,WARRIOR";
case "4":
return "GUARDIAN,WARRIOR";
case "7":
return "GENERIC";
case "0":
return "GUARDIAN,WARRIOR";
case "1":
return "GUARDIAN,WARRIOR";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "0":
return "BRUTE,GUARDIAN";
case "3":
return "BRUTE,GUARDIAN";
case "4":
return "BRUTE,GUARDIAN";
case "5":
return "BRUTE,GUARDIAN";
case "6":
return "BRUTE,GUARDIAN";
case "7":
return "BRUTE,GUARDIAN";
case "8":
return "BRUTE,GUARDIAN";
case "1":
return "BRUTE,GUARDIAN";
case "9":
return "BRUTE,GUARDIAN";
case "2":
return "BRUTE,GUARDIAN";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "4":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "5":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "4":
return "WARRIOR";
case "7":
return "BRUTE,GUARDIAN";
case "8":
return "BRUTE,GUARDIAN";
case "9":
return "BRUTE,GUARDIAN";
case "5":
return "BRUTE,GUARDIAN";
case "3":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "6":
return "BRUTE,GUARDIAN";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "7":
return "BRUTE,WARRIOR";
case "8":
return "BRUTE,WARRIOR";
case "9":
return "BRUTE,WARRIOR";
case "5":
return "BRUTE,WARRIOR";
case "2":
return "BRUTE,GUARDIAN";
case "3":
return "BRUTE,GUARDIAN";
case "4":
return "BRUTE,GUARDIAN";
case "6":
return "BRUTE,WARRIOR";
case "0":
return "BRUTE,GUARDIAN";
case "1":
return "BRUTE,GUARDIAN";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "7":
return "GUARDIAN,WARRIOR";
case "8":
return "GUARDIAN,WARRIOR";
case "9":
return "GUARDIAN,WARRIOR";
case "6":
return "GUARDIAN,WARRIOR";
case "2":
return "BRUTE,WARRIOR";
case "3":
return "BRUTE,WARRIOR";
case "4":
return "BRUTE,WARRIOR";
case "5":
return "GUARDIAN,WARRIOR";
case "0":
return "BRUTE,WARRIOR";
case "1":
return "BRUTE,WARRIOR";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "0":
return "WARRIOR";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "1":
return "GUARDIAN,WARRIOR";
case "3":
return "GUARDIAN,WARRIOR";
case "4":
return "GUARDIAN,WARRIOR";
case "5":
return "GUARDIAN,WARRIOR";
case "0":
return "GUARDIAN,WARRIOR";
case "2":
return "GUARDIAN,WARRIOR";
case "6":
return "GUARDIAN,WARRIOR";
case "7":
return "GUARDIAN,WARRIOR";
case "8":
return "GUARDIAN,WARRIOR";
case "9":
return "GUARDIAN,WARRIOR";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "BRUTE";
case "6":
return "BRUTE";
case "0":
return "GENERIC";
case "2":
return "BRUTE";
case "1":
return "BRUTE";
case "9":
return "BRUTE";
case "5":
return "BRUTE";
case "7":
return "BRUTE";
case "4":
return "BRUTE";
case "3":
return "BRUTE";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "1":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "9":
return "GUARDIAN";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "4":
return "BRUTE";
case "0":
return "BRUTE";
case "6":
return "BRUTE";
case "1":
return "BRUTE";
case "5":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "6":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "9":
return "GUARDIAN";
case "4":
return "BRUTE";
case "8":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "0":
return "BRUTE";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "1":
return "GUARDIAN";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "4":
return "WARRIOR";
case "7":
return "WARRIOR";
case "9":
return "WARRIOR";
case "5":
return "WARRIOR";
case "1":
return "WARRIOR";
case "0":
return "WARRIOR";
case "3":
return "WARRIOR";
case "2":
return "WARRIOR";
case "6":
return "WARRIOR";
case "8":
return "WARRIOR";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
default: return "NONE";
}
default: return "NONE";
}
case "6":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "8":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "WIZARD";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "3":
return "NINJA";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "MERCHANT";
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "5":
return "NINJA";
case "4":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "6":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "5":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "6":
return "WIZARD";
case "0":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "9":
return "WIZARD";
case "7":
return "WIZARD";
case "1":
return "NINJA";
case "8":
return "WIZARD";
case "5":
return "WIZARD";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "2":
return "GENERIC";
case "7":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "9":
return "GENERIC";
case "8":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "9":
return "WIZARD";
case "3":
return "WIZARD";
case "2":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "0":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "3":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "9":
return "NINJA";
case "8":
return "NINJA";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
default: return "NONE";
}
default: return "NONE";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "0":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
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
case "D":
switch($cardID[1]) {
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
case "6":
return "WIZARD";
case "4":
return "WIZARD";
case "1":
return "RUNEBLADE";
case "3":
return "WIZARD";
case "0":
return "RUNEBLADE";
case "2":
return "WIZARD";
case "5":
return "WIZARD";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "0":
return "RANGER";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "3":
return "ASSASSIN";
case "4":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "2":
return "ASSASSIN";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "4":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "8":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "9":
return "RANGER";
case "0":
return "ASSASSIN";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "3":
return "RANGER";
case "2":
return "RANGER";
case "4":
return "RANGER";
case "1":
return "RANGER";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "4":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "4":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "2":
return "ASSASSIN";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "BRUTE";
case "9":
return "BRUTE";
case "0":
return "GENERIC";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "1":
return "WARRIOR,WIZARD";
case "5":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "5":
return "NINJA";
case "8":
return "NINJA";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "9":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "0":
return "WARRIOR";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "8":
return "GUARDIAN";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "9":
return "GUARDIAN";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "5":
return "GUARDIAN";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "5":
return "NINJA";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "9":
return "WARRIOR";
case "6":
return "WARRIOR";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "9":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "2":
return "WIZARD";
case "3":
return "WIZARD";
case "4":
return "WIZARD";
case "5":
return "WIZARD";
case "6":
return "WIZARD";
case "7":
return "WIZARD";
case "8":
return "WIZARD";
case "9":
return "WIZARD";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "0":
return "WIZARD";
case "1":
return "WIZARD";
case "7":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "3":
return "ILLUSIONIST";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "6":
return "GENERIC";
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "4":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "2":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "ILLUSIONIST";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return "SHAPESHIFTER";
case "2":
return "RUNEBLADE";
case "7":
return "ILLUSIONIST";
case "1":
return "RUNEBLADE";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "4":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "WARRIOR";
case "8":
return "WARRIOR";
case "5":
return "WARRIOR";
case "2":
return "BRUTE";
case "7":
return "WARRIOR";
case "9":
return "WARRIOR";
case "1":
return "BRUTE";
case "0":
return "BRUTE";
case "4":
return "GUARDIAN";
case "3":
return "GUARDIAN";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "1":
return "GENERIC";
case "4":
return "GENERIC";
case "2":
return "GENERIC";
case "5":
return "GENERIC";
case "3":
return "GENERIC";
case "9":
return "GENERIC";
case "8":
return "GENERIC";
case "7":
return "GENERIC";
case "0":
return "ILLUSIONIST";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "1":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "0":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "6":
return "WARRIOR";
case "5":
return "WARRIOR";
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "WARRIOR";
case "7":
return "WARRIOR";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "1":
return "WARRIOR";
case "3":
return "WARRIOR";
case "2":
return "WARRIOR";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "ILLUSIONIST";
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "7":
return "ILLUSIONIST";
case "8":
return "ILLUSIONIST";
case "9":
return "ILLUSIONIST";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "0":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "1":
return "BRUTE";
case "0":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "7":
return "BRUTE";
case "5":
return "BRUTE";
case "4":
return "BRUTE";
case "3":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "6":
return "BRUTE";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "RUNEBLADE";
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "4":
return "RUNEBLADE";
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "5":
return "RUNEBLADE";
case "6":
return "RUNEBLADE";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "4":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
case "9":
return "RUNEBLADE";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "1":
return "RUNEBLADE";
case "2":
return "RUNEBLADE";
case "3":
return "RUNEBLADE";
case "0":
return "RUNEBLADE";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "9":
return "BRUTE";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "6":
return "GENERIC";
case "1":
return "WARRIOR";
case "0":
return "WARRIOR";
case "8":
return "GENERIC";
case "5":
return "GENERIC";
case "7":
return "GENERIC";
case "4":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "2":
return "WARRIOR";
case "1":
return "WARRIOR";
case "9":
return "WARRIOR";
case "5":
return "WARRIOR";
case "8":
return "WARRIOR";
case "7":
return "WARRIOR";
case "3":
return "GENERIC";
case "6":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "7":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "9":
return "GENERIC";
case "3":
return "WARRIOR";
case "0":
return "WARRIOR";
case "2":
return "WARRIOR";
case "8":
return "WARRIOR";
case "4":
return "GENERIC";
case "1":
return "WARRIOR";
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
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "BRUTE";
case "5":
return "BRUTE";
case "7":
return "BRUTE";
case "0":
return "GENERIC";
case "8":
return "BRUTE";
case "2":
return "BRUTE";
case "1":
return "BRUTE";
case "3":
return "BRUTE";
case "9":
return "BRUTE";
case "4":
return "BRUTE";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "2":
return "NINJA";
case "9":
return "NINJA";
case "0":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "4":
return "NINJA";
case "1":
return "NINJA";
case "3":
return "NINJA";
case "5":
return "NINJA";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "0":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "1":
return "GUARDIAN";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "9":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "6":
return "BRUTE";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "8":
return "NINJA";
case "7":
return "NINJA";
case "6":
return "NINJA";
case "9":
return "NINJA";
case "5":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "3":
return "BRUTE";
case "4":
return "BRUTE";
case "5":
return "BRUTE";
case "0":
return "BRUTE";
case "1":
return "BRUTE";
case "2":
return "BRUTE";
case "6":
return "BRUTE";
case "7":
return "BRUTE";
case "8":
return "BRUTE";
case "9":
return "BRUTE";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "2":
return "GENERIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "6":
return "WARRIOR";
case "5":
return "WARRIOR";
case "4":
return "WARRIOR";
case "3":
return "WARRIOR";
case "8":
return "WARRIOR";
case "7":
return "WARRIOR";
case "9":
return "WARRIOR";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "2":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "1":
return "GENERIC";
case "3":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "0":
return "WARRIOR";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "3":
return "GENERIC";
case "2":
return "GENERIC";
case "1":
return "GENERIC";
case "7":
return "GENERIC";
case "5":
return "GENERIC";
case "8":
return "GENERIC";
case "6":
return "GENERIC";
case "4":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
case "5":
return "WARRIOR";
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "6":
return "WARRIOR";
case "7":
return "WARRIOR";
case "8":
return "WARRIOR";
case "9":
return "WARRIOR";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "0":
return "GENERIC";
case "5":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
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
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "3":
return "GENERIC";
case "7":
return "GENERIC";
case "4":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "9":
return "GENERIC";
case "8":
return "GENERIC";
case "0":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "RANGER";
case "1":
return "RANGER";
case "6":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "9":
return "RANGER";
case "5":
return "RANGER";
case "7":
return "RANGER";
case "4":
return "RANGER";
case "8":
return "RANGER";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "1":
return "ASSASSIN,NINJA";
case "5":
return "ASSASSIN,NINJA";
case "6":
return "ASSASSIN,NINJA";
case "7":
return "ASSASSIN,NINJA";
case "3":
return "ASSASSIN,NINJA";
case "8":
return "ASSASSIN,NINJA";
case "9":
return "ASSASSIN,NINJA";
case "4":
return "ASSASSIN,NINJA";
case "0":
return "ASSASSIN,NINJA";
case "2":
return "ASSASSIN,NINJA";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "1":
return "ASSASSIN,RANGER";
case "2":
return "ASSASSIN,RANGER";
case "3":
return "ASSASSIN,RANGER";
case "6":
return "GENERIC";
case "5":
return "GENERIC";
case "8":
return "GENERIC";
case "7":
return "GENERIC";
case "9":
return "GENERIC";
case "4":
return "GENERIC";
case "0":
return "ASSASSIN,RANGER";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "9":
return "ASSASSIN,RANGER";
case "0":
return "ASSASSIN,NINJA";
case "7":
return "ASSASSIN,RANGER";
case "1":
return "ASSASSIN,NINJA";
case "2":
return "ASSASSIN,NINJA";
case "3":
return "ASSASSIN,NINJA";
case "4":
return "ASSASSIN,NINJA";
case "5":
return "ASSASSIN,NINJA";
case "6":
return "ASSASSIN,NINJA";
case "8":
return "ASSASSIN,RANGER";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "ASSASSIN,RANGER";
case "1":
return "ASSASSIN,RANGER";
case "2":
return "ASSASSIN,RANGER";
case "3":
return "ASSASSIN,RANGER";
case "4":
return "ASSASSIN,RANGER";
case "5":
return "ASSASSIN,RANGER";
case "6":
return "ASSASSIN,RANGER";
case "7":
return "ASSASSIN,RANGER";
case "8":
return "ASSASSIN,RANGER";
case "9":
return "ASSASSIN,RANGER";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "1":
return "RANGER";
case "0":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "9":
return "ASSASSIN,NINJA";
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "RANGER";
case "1":
return "RANGER";
case "2":
return "RANGER";
case "3":
return "RANGER";
case "4":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "9":
return "RANGER";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "0":
return "ASSASSIN,RANGER";
case "9":
return "ASSASSIN";
case "4":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "1":
return "ASSASSIN";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "RANGER";
case "3":
return "RANGER";
case "7":
return "RANGER";
case "8":
return "RANGER";
case "5":
return "RANGER";
case "6":
return "RANGER";
case "2":
return "RANGER";
case "1":
return "RANGER";
case "4":
return "RANGER";
case "9":
return "RANGER";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "9":
return "RANGER";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "0":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "4":
return "ASSASSIN";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "3":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "9":
return "NINJA";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "7":
return "NINJA";
case "8":
return "NINJA";
case "6":
return "NINJA";
case "5":
return "NINJA";
case "9":
return "NINJA";
case "2":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "4":
return "ASSASSIN";
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "0":
return "NINJA";
case "1":
return "NINJA";
case "2":
return "NINJA";
case "9":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "3":
return "NINJA";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "4":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "3":
return "ASSASSIN";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "0":
return "ASSASSIN";
case "1":
return "ASSASSIN";
case "2":
return "ASSASSIN";
case "3":
return "ASSASSIN";
case "4":
return "ASSASSIN";
case "5":
return "ASSASSIN";
case "6":
return "ASSASSIN";
case "7":
return "ASSASSIN";
case "8":
return "ASSASSIN";
case "9":
return "ASSASSIN";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return "GENERIC";
case "0":
return "GENERIC";
case "8":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "7":
return "GENERIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "0":
return "GENERIC";
case "1":
return "GENERIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
case "0":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
case "9":
return "GENERIC";
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
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "9":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "2":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "8":
return "GUARDIAN";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "3":
return "NINJA";
case "0":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "6":
return "NINJA";
case "7":
return "NINJA";
case "8":
return "NINJA";
case "2":
return "NINJA";
case "9":
return "NINJA";
case "1":
return "NINJA";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "2":
return "NINJA";
case "1":
return "NINJA";
case "3":
return "NINJA";
case "9":
return "NINJA";
case "4":
return "NINJA";
case "5":
return "NINJA";
case "7":
return "NINJA";
case "0":
return "NINJA";
case "6":
return "NINJA";
case "8":
return "NINJA";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "9":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "4":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "9":
return "BARD";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "5":
return "GENERIC";
case "3":
return "BARD";
case "7":
return "BARD";
case "2":
return "BARD";
case "8":
return "BARD";
case "0":
return "BARD";
case "6":
return "GENERIC";
case "1":
return "BARD";
case "4":
return "BARD";
case "9":
return "GENERIC";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "6":
return "BARD";
case "8":
return "NINJA";
case "1":
return "GENERIC";
case "2":
return "GENERIC";
case "5":
return "BARD";
case "7":
return "NINJA";
case "9":
return "NINJA";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "BARD";
case "3":
return "BARD";
case "1":
return "BARD";
case "2":
return "BARD";
case "4":
return "BARD";
case "5":
return "BARD";
case "9":
return "BARD";
case "6":
return "BARD";
case "7":
return "BARD";
case "8":
return "BARD";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "NINJA";
case "3":
return "GENERIC";
case "8":
return "GENERIC";
case "4":
return "NINJA";
case "5":
return "GENERIC";
case "1":
return "NINJA";
case "6":
return "GENERIC";
case "2":
return "NINJA";
case "7":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "GENERIC";
case "8":
return "GUARDIAN";
case "6":
return "GENERIC";
case "4":
return "GENERIC";
case "7":
return "GENERIC";
case "2":
return "GUARDIAN";
case "3":
return "GUARDIAN";
case "1":
return "GUARDIAN";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "4":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "1":
return "GUARDIAN";
case "5":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "6":
return "GUARDIAN";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "3":
return "GUARDIAN";
case "8":
return "GENERIC";
case "5":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "9":
return "GENERIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "0":
return "GENERIC";
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "ILLUSIONIST";
case "5":
return "GENERIC";
case "3":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "2":
return "GENERIC";
case "8":
return "GENERIC";
case "4":
return "GENERIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "2":
return "GENERIC";
case "3":
return "GENERIC";
case "9":
return "GENERIC";
case "7":
return "GENERIC";
case "1":
return "GENERIC";
case "6":
return "ILLUSIONIST";
case "0":
return "GENERIC";
case "8":
return "GENERIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "6":
return "GUARDIAN";
case "4":
return "GUARDIAN";
case "2":
return "MECHANOLOGIST";
case "1":
return "WARRIOR";
case "5":
return "GENERIC";
case "3":
return "RANGER";
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
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
if($cardID !== null && strlen($cardID) < 6) return "";
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
case "9":
switch($cardID[5]) {
case "5":
return "MYSTIC";
case "2":
return "MYSTIC";
case "3":
return "MYSTIC";
case "4":
return "MYSTIC";
case "6":
return "MYSTIC";
case "7":
return "MYSTIC";
case "8":
return "MYSTIC";
case "9":
return "MYSTIC";
case "0":
return "MYSTIC";
case "1":
return "MYSTIC";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "MYSTIC";
case "5":
return "MYSTIC";
case "6":
return "MYSTIC";
case "9":
return "MYSTIC";
case "7":
return "MYSTIC";
case "2":
return "MYSTIC";
case "1":
return "MYSTIC";
case "3":
return "MYSTIC";
case "4":
return "MYSTIC";
case "8":
return "MYSTIC";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "7":
return "MYSTIC";
case "0":
return "MYSTIC";
case "1":
return "MYSTIC";
case "2":
return "MYSTIC";
case "8":
return "MYSTIC";
case "3":
return "MYSTIC";
case "4":
return "MYSTIC";
case "5":
return "MYSTIC";
case "6":
return "MYSTIC";
case "9":
return "MYSTIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "MYSTIC";
case "3":
return "MYSTIC";
case "8":
return "MYSTIC";
case "5":
return "MYSTIC";
case "4":
return "MYSTIC";
case "0":
return "MYSTIC";
case "2":
return "MYSTIC";
case "1":
return "MYSTIC";
case "9":
return "MYSTIC";
case "7":
return "MYSTIC";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "6":
return "MYSTIC";
case "4":
return "MYSTIC";
case "7":
return "MYSTIC";
case "0":
return "MYSTIC";
case "8":
return "MYSTIC";
case "1":
return "MYSTIC";
case "2":
return "MYSTIC";
case "3":
return "MYSTIC";
case "9":
return "MYSTIC";
case "5":
return "MYSTIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "7":
return "MYSTIC";
case "8":
return "MYSTIC";
case "9":
return "MYSTIC";
case "3":
return "MYSTIC";
case "2":
return "MYSTIC";
case "0":
return "MYSTIC";
case "1":
return "MYSTIC";
case "4":
return "MYSTIC";
case "5":
return "MYSTIC";
case "6":
return "MYSTIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "6":
return "MYSTIC";
case "5":
return "MYSTIC";
case "3":
return "MYSTIC";
case "8":
return "MYSTIC";
case "7":
return "MYSTIC";
case "4":
return "MYSTIC";
case "9":
return "MYSTIC";
case "0":
return "MYSTIC";
case "1":
return "MYSTIC";
case "2":
return "MYSTIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "7":
return "MYSTIC";
case "8":
return "MYSTIC";
case "9":
return "MYSTIC";
case "1":
return "MYSTIC";
case "4":
return "MYSTIC";
case "5":
return "MYSTIC";
case "6":
return "MYSTIC";
case "2":
return "MYSTIC";
case "3":
return "MYSTIC";
case "0":
return "MYSTIC";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "9":
return "MYSTIC";
case "8":
return "MYSTIC";
case "0":
return "MYSTIC";
case "1":
return "MYSTIC";
case "2":
return "MYSTIC";
case "3":
return "MYSTIC";
case "4":
return "MYSTIC";
case "5":
return "MYSTIC";
case "7":
return "MYSTIC";
case "6":
return "MYSTIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "4":
return "MYSTIC";
case "5":
return "MYSTIC";
case "6":
return "MYSTIC";
case "7":
return "MYSTIC";
case "8":
return "MYSTIC";
case "9":
return "MYSTIC";
case "0":
return "MYSTIC";
case "1":
return "MYSTIC";
case "2":
return "MYSTIC";
case "3":
return "MYSTIC";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return "DRACONIC";
case "7":
return "SHADOW";
case "8":
return "MYSTIC";
case "6":
return "SHADOW";
default: return "NONE";
}
default: return "NONE";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "MYSTIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "MYSTIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "2":
return "MYSTIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "3":
return "MYSTIC";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "5":
return "MYSTIC";
case "6":
return "MYSTIC";
case "7":
return "MYSTIC";
case "8":
return "MYSTIC";
case "9":
return "MYSTIC";
default: return "NONE";
}
default: return "NONE";
}
case "5":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "MYSTIC";
case "1":
return "MYSTIC";
case "2":
return "MYSTIC";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "MYSTIC";
case "1":
return "MYSTIC";
case "2":
return "MYSTIC";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "0":
return "LIGHT";
case "4":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "3":
return "LIGHT";
case "2":
return "LIGHT";
case "1":
return "LIGHT";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "3":
return "LIGHT";
case "2":
return "LIGHT";
case "0":
return "LIGHT";
case "4":
return "LIGHT";
case "1":
return "LIGHT";
case "5":
return "LIGHT";
case "9":
return "LIGHT";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "2":
return "LIGHT";
case "9":
return "LIGHT";
case "1":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "4":
return "LIGHT";
case "3":
return "LIGHT";
case "5":
return "LIGHT";
case "0":
return "LIGHT";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "0":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "1":
return "LIGHT";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "9":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "9":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "0":
return "SHADOW";
case "1":
return "SHADOW";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "SHADOW";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
case "0":
return "SHADOW";
case "1":
return "SHADOW";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "8":
return "SHADOW";
case "9":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "0":
return "SHADOW";
case "1":
return "SHADOW";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "7":
return "SHADOW";
case "9":
return "SHADOW";
case "8":
return "SHADOW";
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "4":
return "SHADOW";
case "3":
return "SHADOW";
case "7":
return "SHADOW";
case "5":
return "SHADOW";
case "8":
return "SHADOW";
case "6":
return "SHADOW";
case "9":
return "SHADOW";
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "0":
return "SHADOW";
case "2":
return "SHADOW";
case "1":
return "SHADOW";
case "3":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
case "4":
return "SHADOW";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "3":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "0":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "9":
return "SHADOW";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "9":
return "SHADOW";
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
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "7":
return "LIGHT";
case "9":
return "LIGHT";
case "8":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "2":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "6":
return "LIGHT";
case "5":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "7":
return "LIGHT";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "1":
return "LIGHT";
case "3":
return "LIGHT";
case "2":
return "LIGHT";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "0":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "8":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "5":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "9":
return "LIGHT";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "0":
return "LIGHT";
case "3":
return "LIGHT";
case "2":
return "LIGHT";
case "1":
return "LIGHT";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
case "5":
return "LIGHT";
case "6":
return "LIGHT";
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return "EARTH";
case "8":
return "LIGHTNING";
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "3":
return "SHADOW";
case "7":
return "ICE";
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "0":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "0":
return "SHADOW";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "4":
return "SHADOW";
case "6":
return "SHADOW";
case "9":
return "SHADOW";
case "8":
return "SHADOW";
case "7":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "5":
return "SHADOW";
case "0":
return "SHADOW";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "1":
return "SHADOW";
case "0":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "7":
return "SHADOW";
case "5":
return "SHADOW";
case "4":
return "SHADOW";
case "3":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
case "6":
return "SHADOW";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
case "0":
return "SHADOW";
case "2":
return "SHADOW";
case "1":
return "SHADOW";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "3":
return "SHADOW";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "0":
return "SHADOW";
case "1":
return "SHADOW";
case "2":
return "SHADOW";
case "4":
return "SHADOW";
case "3":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
case "9":
return "SHADOW";
default: return "NONE";
}
default: return "NONE";
}
case "5":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return "SHADOW";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "1":
return "DRACONIC";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "6":
return "LIGHT";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "LIGHT";
default: return "NONE";
}
default: return "NONE";
}
case "6":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "LIGHT";
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "5":
return "DRACONIC";
case "4":
return "DRACONIC";
case "7":
return "DRACONIC";
case "8":
return "DRACONIC";
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "6":
return "DRACONIC";
case "9":
return "DRACONIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "8":
return "DRACONIC";
case "9":
return "DRACONIC";
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "5":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "5":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
case "8":
return "DRACONIC";
case "9":
return "DRACONIC";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "5":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
case "8":
return "DRACONIC";
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "9":
return "DRACONIC";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "DRACONIC";
case "5":
return "DRACONIC";
case "2":
return "DRACONIC";
case "1":
return "DRACONIC";
case "9":
return "DRACONIC";
case "8":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
case "4":
return "DRACONIC";
case "3":
return "DRACONIC";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "5":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
case "8":
return "DRACONIC";
case "9":
return "DRACONIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "5":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
case "8":
return "DRACONIC";
case "9":
return "DRACONIC";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "4":
return "DRACONIC";
case "7":
return "DRACONIC";
case "0":
return "DRACONIC";
case "5":
return "DRACONIC";
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "6":
return "DRACONIC";
case "9":
return "DRACONIC";
case "8":
return "DRACONIC";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "5":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
case "8":
return "DRACONIC";
case "9":
return "DRACONIC";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "5":
return "DRACONIC";
case "9":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
case "8":
return "DRACONIC";
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "7":
return "ICE";
case "8":
return "ICE";
case "9":
return "ICE";
case "5":
return "ICE";
case "6":
return "ICE";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "3":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "4":
return "ICE";
case "5":
return "ICE";
case "6":
return "ICE";
case "7":
return "ICE";
case "8":
return "ICE";
case "9":
return "ICE";
case "0":
return "ICE";
case "1":
return "ICE";
case "2":
return "ICE";
case "3":
return "ICE";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "8":
return "ICE";
case "6":
return "ICE";
case "0":
return "ICE";
case "1":
return "ICE";
case "2":
return "ICE";
case "7":
return "ICE";
case "9":
return "ICE";
case "3":
return "ICE";
case "4":
return "ICE";
case "5":
return "ICE";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "1":
return "DRACONIC";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "0":
return "DRACONIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "ELEMENTAL";
default: return "NONE";
}
default: return "NONE";
}
case "4":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "DRACONIC";
case "8":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "DRACONIC";
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
case "3":
return "DRACONIC";
case "4":
return "DRACONIC";
case "5":
return "DRACONIC";
case "6":
return "DRACONIC";
case "7":
return "DRACONIC";
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
case "E":
switch($cardID[1]) {
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return "EARTH";
case "7":
return "ICE";
case "6":
return "ICE";
case "5":
return "ICE";
case "8":
return "ICE";
case "9":
return "ICE";
case "4":
return "ICE";
case "0":
return "EARTH";
case "1":
return "EARTH";
case "2":
return "EARTH";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "2":
return "ICE";
case "6":
return "LIGHTNING";
case "5":
return "LIGHTNING";
case "7":
return "LIGHTNING";
case "8":
return "LIGHTNING";
case "9":
return "LIGHTNING";
case "4":
return "LIGHTNING";
case "3":
return "LIGHTNING";
case "0":
return "ICE";
case "1":
return "ICE";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "8":
return "EARTH";
case "9":
return "EARTH";
case "0":
return "EARTH";
case "1":
return "EARTH";
case "5":
return "EARTH";
case "6":
return "EARTH";
case "7":
return "EARTH";
case "2":
return "EARTH";
case "3":
return "EARTH";
case "4":
return "EARTH";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "0":
return "EARTH";
case "1":
return "EARTH";
case "2":
return "EARTH";
case "3":
return "EARTH";
case "4":
return "EARTH";
case "5":
return "EARTH";
case "6":
return "EARTH";
case "7":
return "EARTH";
case "8":
return "EARTH";
case "9":
return "EARTH";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "6":
return "LIGHTNING";
case "7":
return "LIGHTNING";
case "8":
return "LIGHTNING";
case "3":
return "LIGHTNING";
case "4":
return "LIGHTNING";
case "5":
return "LIGHTNING";
case "9":
return "LIGHTNING";
case "0":
return "LIGHTNING";
case "1":
return "LIGHTNING";
case "2":
return "LIGHTNING";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "7":
return "EARTH";
case "5":
return "EARTH";
case "0":
return "ELEMENTAL";
case "9":
return "EARTH";
case "1":
return "ELEMENTAL";
case "6":
return "EARTH";
case "3":
return "EARTH,LIGHTNING";
case "4":
return "ICE,EARTH";
case "2":
return "LIGHTNING,ICE";
case "8":
return "EARTH";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "3":
return "ICE";
case "4":
return "ICE";
case "5":
return "ICE";
case "6":
return "ICE";
case "7":
return "ICE";
case "8":
return "ICE";
case "9":
return "ICE";
case "0":
return "ICE";
case "1":
return "ICE";
case "2":
return "ICE";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "8":
return "LIGHTNING";
case "9":
return "LIGHTNING";
case "2":
return "LIGHTNING";
case "3":
return "LIGHTNING";
case "4":
return "LIGHTNING";
case "0":
return "LIGHTNING";
case "1":
return "LIGHTNING";
case "5":
return "LIGHTNING";
case "6":
return "LIGHTNING";
case "7":
return "LIGHTNING";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "9":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "ICE";
case "1":
return "ICE";
case "2":
return "ICE";
case "3":
return "ICE";
case "7":
return "ICE";
case "8":
return "ICE";
case "9":
return "ICE";
case "4":
return "ICE";
case "5":
return "ICE";
case "6":
return "ICE";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "LIGHTNING";
case "0":
return "LIGHTNING";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "3":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "4":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "3":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "4":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "ELEMENTAL";
case "8":
return "ICE";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "7":
return "ELEMENTAL";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "ELEMENTAL";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "O":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return "DRACONIC";
case "8":
return "SHADOW";
case "5":
return "DRACONIC";
case "7":
return "LIGHT";
default: return "NONE";
}
default: return "NONE";
}
case "4":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "SHADOW";
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
case "J":
switch($cardID[1]) {
case "D":
switch($cardID[2]) {
case "G":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return "LIGHT";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "1":
return "LIGHT";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "1":
return "DRACONIC";
case "2":
return "DRACONIC";
case "6":
return "LIGHT";
case "8":
return "LIGHT";
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
case "R":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "LIGHTNING";
case "2":
return "LIGHTNING";
case "6":
return "EARTH";
case "9":
return "ELEMENTAL";
case "8":
return "EARTH";
case "7":
return "EARTH";
case "5":
return "EARTH";
case "1":
return "LIGHTNING";
case "4":
return "ELEMENTAL";
case "3":
return "ELEMENTAL";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "6":
return "EARTH";
case "8":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "1":
return "ELEMENTAL";
case "4":
return "EARTH";
case "3":
return "EARTH";
case "9":
return "LIGHTNING";
case "5":
return "EARTH";
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "6":
return "EARTH";
case "7":
return "EARTH";
case "8":
return "EARTH";
case "9":
return "EARTH";
case "2":
return "EARTH";
case "3":
return "EARTH";
case "4":
return "EARTH";
case "5":
return "EARTH";
case "0":
return "EARTH";
case "1":
return "EARTH";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "8":
return "EARTH";
case "4":
return "LIGHTNING";
case "5":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "9":
return "EARTH";
case "3":
return "LIGHTNING";
case "0":
return "ELEMENTAL";
case "2":
return "LIGHTNING";
case "1":
return "LIGHTNING";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "9":
return "LIGHTNING";
case "7":
return "LIGHTNING";
case "4":
return "LIGHTNING";
case "5":
return "LIGHTNING";
case "2":
return "LIGHTNING";
case "6":
return "LIGHTNING";
case "8":
return "LIGHTNING";
case "1":
return "LIGHTNING";
case "0":
return "EARTH";
case "3":
return "LIGHTNING";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "LIGHTNING";
case "1":
return "LIGHTNING";
case "5":
return "LIGHTNING";
case "6":
return "LIGHTNING";
case "7":
return "LIGHTNING";
case "9":
return "LIGHTNING";
case "2":
return "LIGHTNING";
case "3":
return "LIGHTNING";
case "4":
return "LIGHTNING";
case "8":
return "LIGHTNING";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "EARTH";
case "1":
return "EARTH";
case "2":
return "EARTH";
case "3":
return "EARTH";
case "4":
return "EARTH";
case "5":
return "EARTH";
case "6":
return "EARTH";
case "7":
return "EARTH";
case "8":
return "EARTH";
case "9":
return "EARTH";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "3":
return "EARTH";
case "6":
return "EARTH";
case "7":
return "EARTH";
case "8":
return "EARTH";
case "4":
return "EARTH";
case "1":
return "EARTH";
case "2":
return "EARTH";
case "5":
return "EARTH";
case "9":
return "EARTH";
case "0":
return "EARTH";
default: return "NONE";
}
case "6":
switch($cardID[5]) {
case "7":
return "EARTH";
case "8":
return "EARTH";
case "9":
return "EARTH";
case "1":
return "EARTH";
case "2":
return "EARTH";
case "3":
return "EARTH";
case "0":
return "EARTH";
case "4":
return "EARTH";
case "5":
return "EARTH";
case "6":
return "EARTH";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "0":
return "LIGHTNING";
case "1":
return "LIGHTNING";
case "2":
return "LIGHTNING";
case "3":
return "LIGHTNING";
case "4":
return "LIGHTNING";
case "5":
return "LIGHTNING";
case "6":
return "LIGHTNING";
case "7":
return "LIGHTNING";
case "8":
return "LIGHTNING";
case "9":
return "LIGHTNING";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "5":
return "ELEMENTAL";
case "4":
return "ELEMENTAL";
case "2":
return "DRACONIC";
case "7":
return "ELEMENTAL";
case "3":
return "EARTH,LIGHTNING";
default: return "NONE";
}
default: return "NONE";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "LIGHTNING";
case "1":
return "LIGHTNING";
case "2":
return "LIGHTNING";
case "3":
return "LIGHTNING";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "7":
return "LIGHTNING";
case "8":
return "LIGHTNING";
case "9":
return "LIGHTNING";
case "0":
return "LIGHTNING";
case "1":
return "LIGHTNING";
case "2":
return "LIGHTNING";
case "3":
return "LIGHTNING";
case "4":
return "LIGHTNING";
case "5":
return "LIGHTNING";
case "6":
return "LIGHTNING";
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
case "A":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "ELEMENTAL";
case "7":
return "ELEMENTAL";
case "8":
return "LIGHTNING";
case "9":
return "LIGHTNING";
case "2":
return "LIGHTNING";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "3":
return "LIGHTNING";
case "7":
return "ELEMENTAL";
case "0":
return "ELEMENTAL";
case "6":
return "LIGHTNING";
case "4":
return "ELEMENTAL";
case "1":
return "LIGHTNING";
case "2":
return "LIGHTNING";
case "5":
return "LIGHTNING";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "5":
return "ELEMENTAL";
case "6":
return "LIGHTNING";
case "3":
return "ELEMENTAL";
case "7":
return "LIGHTNING";
case "0":
return "LIGHTNING";
case "8":
return "LIGHTNING";
case "1":
return "ELEMENTAL";
case "4":
return "LIGHTNING";
case "2":
return "ELEMENTAL";
case "9":
return "ELEMENTAL";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "S":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "4":
return "LIGHT";
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "5":
return "LIGHT";
case "3":
return "LIGHT";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "7":
return "LIGHT";
case "8":
return "LIGHT";
case "5":
return "LIGHT";
case "9":
return "LIGHT";
case "3":
return "LIGHT";
case "2":
return "LIGHT";
case "1":
return "LIGHT";
case "4":
return "LIGHT";
case "6":
return "LIGHT";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "0":
return "LIGHT";
case "1":
return "LIGHT";
case "6":
return "LIGHT";
case "5":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "LIGHT";
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
case "J":
switch($cardID[2]) {
case "V":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return "ICE";
case "8":
return "ICE,EARTH";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "1":
return "ELEMENTAL";
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
case "T":
switch($cardID[1]) {
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return "EARTH";
case "4":
return "ELEMENTAL";
case "7":
return "EARTH";
case "1":
return "ELEMENTAL";
case "5":
return "ELEMENTAL";
case "8":
return "ELEMENTAL";
case "2":
return "ELEMENTAL";
case "6":
return "ELEMENTAL";
case "9":
return "EARTH";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "1":
return "EARTH";
case "2":
return "EARTH";
case "7":
return "EARTH";
case "4":
return "EARTH";
case "5":
return "ELEMENTAL";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "8":
return "ELEMENTAL";
case "9":
return "EARTH";
case "2":
return "EARTH";
case "1":
return "ELEMENTAL";
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "4":
return "LIGHT";
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

function GeneratedIsSpecialization($cardID) {
if($cardID !== null && strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID) {
case "MST131": return "false";
case "MST095": return "false";
case "ARC123": return "false";
case "ARC124": return "false";
case "ARC125": return "false";
case "CRU104": return "false";
case "ARC005": return "false";
case "ROS246": return "false";
case "EVO013": return "false";
case "HVY222": return "false";
case "MON263": return "false";
case "HVY223": return "false";
case "MON264": return "false";
case "HVY224": return "false";
case "MON265": return "false";
case "DTD407": return "false";
case "DTD407": return "false";
case "HVY252": return "false";
case "UPR042": return "false";
case "ROS163": return "false";
case "CRU160": return "false";
case "AUR005": return "false";
case "UPR173": return "false";
case "UPR174": return "false";
case "UPR175": return "false";
case "ARC132": return "false";
case "ARC133": return "false";
case "ARC134": return "false";
case "UPR127": return "false";
case "UPR128": return "false";
case "UPR129": return "false";
case "UPR113": return "false";
case "UPR114": return "false";
case "UPR115": return "false";
case "MON230": return "false";
case "DYN197": return "false";
case "ROS183": return "false";
case "DYN198": return "false";
case "ROS184": return "false";
case "DYN199": return "false";
case "ROS185": return "false";
case "ARC017": return "false";
case "DYN182": return "false";
case "DYN183": return "false";
case "DYN184": return "false";
case "ARC126": return "false";
case "ARC127": return "false";
case "ARC128": return "false";
case "EVR123": return "false";
case "CRU164": return "false";
case "HVY115": return "false";
case "HVY116": return "false";
case "HVY117": return "false";
case "HVY163": return "false";
case "HVY164": return "false";
case "AKO017": return "false";
case "HVY165": return "false";
case "AKO027": return "false";
case "HVY240": return "false";
case "DTD215": return "true";
case "UPR166": return "false";
case "RVD008": return "true";
case "WTR006": return "true";
case "EVO236": return "false";
case "DYN171": return "false";
case "OUT183": return "false";
case "ARC094": return "false";
case "ARC095": return "false";
case "ARC096": return "false";
case "OUT100": return "false";
case "EVR176": return "false";
case "ELE143": return "false";
case "EVR177": return "false";
case "EVR178": return "false";
case "ELE172": return "false";
case "EVR179": return "false";
case "EVR180": return "false";
case "ELE201": return "false";
case "EVR181": return "false";
case "WTR082": return "false";
case "HVY247": return "false";
case "DTD032": return "false";
case "DTD033": return "false";
case "DTD034": return "false";
case "DTD035": return "false";
case "DTD036": return "false";
case "DTD037": return "false";
case "DYN172": return "false";
case "DYN133": return "false";
case "DYN134": return "false";
case "DYN135": return "false";
case "EVO054": return "false";
case "CRU023": return "false";
case "WTR040": return "false";
case "DTD196": return "true";
case "HVY008": return "false";
case "TCC011": return "false";
case "MST070": return "false";
case "MST067": return "false";
case "DYN114": return "false";
case "DYN113": return "false";
case "OUT003": return "false";
case "JDG011": return "true";
case "MON005": return "true";
case "ROS010": return "true";
case "ROS152": return "false";
case "ROS153": return "false";
case "ROS154": return "false";
case "EVR155": return "false";
case "ROS231": return "false";
case "ROS232": return "false";
case "ROS233": return "false";
case "ROS006": return "false";
case "ROS186": return "false";
case "ROS187": return "false";
case "ROS188": return "false";
case "MON235": return "false";
case "MON236": return "false";
case "MON237": return "false";
case "ELE073": return "false";
case "ELE074": return "false";
case "ELE075": return "false";
case "ROS134": return "false";
case "ROS135": return "false";
case "ROS136": return "false";
case "ROS211": return "false";
case "ARC150": return "false";
case "EVR154": return "false";
case "UPR144": return "false";
case "UPR145": return "false";
case "UPR146": return "false";
case "CRU009": return "false";
case "ARC080": return "true";
case "CRU000": return "true";
case "MST006": return "false";
case "MST106": return "false";
case "MST108": return "false";
case "MST107": return "false";
case "ARC160": return "false";
case "JDG015": return "false";
case "UPR043": return "false";
case "MST164": return "false";
case "MST166": return "false";
case "MST165": return "false";
case "HVY026": return "false";
case "HVY027": return "false";
case "AKO018": return "false";
case "HVY028": return "false";
case "MST134": return "false";
case "MST135": return "false";
case "MST136": return "false";
case "MST075": return "false";
case "AUR001": return "false";
case "ROS008": return "false";
case "ROS007": return "false";
case "HVY051": return "true";
case "EVO098": return "false";
case "ELE128": return "false";
case "ROS046": return "false";
case "ELE129": return "false";
case "ROS047": return "false";
case "TER013": return "false";
case "ELE130": return "false";
case "ROS048": return "false";
case "TER021": return "false";
case "DTD409": return "false";
case "DTD409": return "false";
case "ELE006": return "false";
case "RVD014": return "false";
case "WTR032": return "false";
case "WTR033": return "false";
case "WTR034": return "false";
case "ARC039": return "false";
case "OUT090": return "false";
case "AAZ001": return "false";
case "ARC038": return "false";
case "CRU119": return "false";
case "OUT089": return "false";
case "UPR409": return "false";
case "UPR409": return "false";
case "ARC176": return "false";
case "ARC177": return "false";
case "ARC178": return "false";
case "OUT065": return "false";
case "OUT066": return "false";
case "OUT067": return "false";
case "OUT015": return "false";
case "OUT016": return "false";
case "OUT017": return "false";
case "AIO025": return "false";
case "EVO083": return "false";
case "AIO014": return "false";
case "EVO081": return "false";
case "EVO082": return "false";
case "EVR014": return "false";
case "EVR015": return "false";
case "EVR016": return "false";
case "HVY195": return "false";
case "HVY006": return "false";
case "ELE186": return "false";
case "ELE187": return "false";
case "ELE188": return "false";
case "EVO006": return "true";
case "DTD048": return "false";
case "DTD049": return "false";
case "DTD050": return "false";
case "ASB017": return "false";
case "DTD054": return "false";
case "DTD055": return "false";
case "ASB018": return "false";
case "DTD056": return "false";
case "OUT093": return "false";
case "AAZ008": return "false";
case "OUT101": return "false";
case "AKO007": return "false";
case "EVR008": return "false";
case "HVY029": return "false";
case "RVD011": return "false";
case "AKO016": return "false";
case "EVR009": return "false";
case "HVY030": return "false";
case "EVR010": return "false";
case "HVY031": return "false";
case "WTR005": return "false";
case "ROS028": return "false";
case "WTR017": return "false";
case "RVD021": return "false";
case "WTR018": return "false";
case "WTR019": return "false";
case "CRU010": return "false";
case "CRU011": return "false";
case "CRU012": return "false";
case "WTR176": return "false";
case "WTR177": return "false";
case "WTR178": return "false";
case "TCC029": return "false";
case "DTD206": return "false";
case "HVY140": return "false";
case "EVR088": return "false";
case "MON036": return "false";
case "ASB019": return "false";
case "MON037": return "false";
case "MON038": return "false";
case "DTD121": return "false";
case "DTD122": return "false";
case "DTD123": return "false";
case "MST203": return "false";
case "MST204": return "false";
case "AKO019": return "false";
case "MST205": return "false";
case "OUT068": return "false";
case "OUT069": return "false";
case "OUT070": return "false";
case "MON033": return "false";
case "DTD046": return "false";
case "ASB007": return "false";
case "DTD057": return "false";
case "ASB020": return "false";
case "DTD058": return "false";
case "DTD059": return "false";
case "HVY017": return "false";
case "RVD009": return "false";
case "HVY018": return "false";
case "HVY019": return "false";
case "CRU007": return "false";
case "AKO006": return "false";
case "DYN006": return "false";
case "HVY100": return "false";
case "DTD051": return "false";
case "MST003": return "false";
case "ARC083": return "true";
case "MON266": return "false";
case "MON267": return "false";
case "MON268": return "false";
case "DTD412": return "false";
case "DTD412": return "false";
case "CRU047": return "false";
case "OUT047": return "false";
case "DTD212": return "true";
case "DYN009": return "false";
case "DTD187": return "false";
case "DTD188": return "false";
case "DTD189": return "false";
case "HVY057": return "true";
case "HVY046": return "false";
case "HVY045": return "false";
case "EVO177": return "false";
case "EVO178": return "false";
case "EVO179": return "false";
case "MST086": return "false";
case "HVY083": return "false";
case "HVY084": return "false";
case "HVY085": return "false";
case "EVO153": return "false";
case "HVY086": return "false";
case "HVY087": return "false";
case "HVY088": return "false";
case "UPR018": return "false";
case "UPR019": return "false";
case "UPR020": return "false";
case "EVR156": return "false";
case "DYN091": return "false";
case "WTR135": return "false";
case "WTR136": return "false";
case "WTR137": return "false";
case "MST173": return "false";
case "MST174": return "false";
case "MST175": return "false";
case "ELE007": return "false";
case "ELE008": return "false";
case "ELE009": return "false";
case "TCC083": return "false";
case "CRU072": return "false";
case "TCC092": return "false";
case "WTR089": return "false";
case "WTR090": return "false";
case "WTR091": return "false";
case "DYN117": return "false";
case "OUT141": return "false";
case "DVR023": return "false";
case "HVY101": return "false";
case "EVR060": return "false";
case "EVR061": return "false";
case "EVR062": return "false";
case "MST194": return "false";
case "MST195": return "false";
case "MST196": return "false";
case "DTD164": return "true";
case "MON219": return "false";
case "ROS079": return "false";
case "ROS080": return "false";
case "ROS081": return "false";
case "UPR092": return "false";
case "HER117": return "false";
case "DYN045": return "false";
case "ARC118": return "true";
case "OUT145": return "false";
case "OUT146": return "false";
case "OUT147": return "false";
case "DYN200": return "false";
case "DYN201": return "false";
case "DYN202": return "false";
case "WTR054": return "false";
case "WTR055": return "false";
case "WTR056": return "false";
case "DYN159": return "false";
case "DYN160": return "false";
case "DYN161": return "false";
case "DYN098": return "false";
case "DYN099": return "false";
case "DYN100": return "false";
case "DYN179": return "false";
case "DYN180": return "false";
case "DYN181": return "false";
case "DYN033": return "false";
case "DYN034": return "false";
case "DYN035": return "false";
case "DYN053": return "false";
case "MST170": return "false";
case "TCC091": return "false";
case "DYN054": return "false";
case "MST171": return "false";
case "DYN055": return "false";
case "MST172": return "false";
case "TCC100": return "false";
case "DTD085": return "false";
case "DTD086": return "false";
case "DTD087": return "false";
case "DYN013": return "false";
case "DYN014": return "false";
case "DYN015": return "false";
case "CRU041": return "false";
case "CRU042": return "false";
case "CRU043": return "false";
case "DYN218": return "false";
case "DYN219": return "false";
case "DYN220": return "false";
case "DYN073": return "false";
case "DYN074": return "false";
case "DYN075": return "false";
case "MON084": return "false";
case "MON085": return "false";
case "MON086": return "false";
case "ELE176": return "false";
case "DTD091": return "false";
case "DTD092": return "false";
case "DTD093": return "false";
case "ELE147": return "false";
case "ELE044": return "false";
case "ELE045": return "false";
case "ELE046": return "false";
case "DTD111": return "false";
case "MON238": return "false";
case "TCC080": return "false";
case "MON215": return "false";
case "MON216": return "false";
case "MON217": return "false";
case "UPR000": return "false";
case "EVR055": return "true";
case "HVY206": return "false";
case "AAZ030": return "false";
case "OUT234": return "false";
case "OUT171": return "false";
case "WTR007": return "false";
case "CRU141": return "false";
case "ARC106": return "false";
case "ARC107": return "false";
case "ARC108": return "false";
case "ROS115": return "false";
case "AUR004": return "false";
case "DVR004": return "false";
case "RVD004": return "false";
case "TER005": return "false";
case "ROS049": return "false";
case "ROS050": return "false";
case "ROS051": return "false";
case "ELE064": return "false";
case "HVY060": return "false";
case "ASB008": return "false";
case "MON042": return "false";
case "ASB021": return "false";
case "MON043": return "false";
case "MON044": return "false";
case "AAZ009": return "false";
case "ELE216": return "false";
case "ELE217": return "false";
case "ELE218": return "false";
case "MON032": return "false";
case "DTD045": return "false";
case "MON030": return "false";
case "MST103": return "false";
case "OUT056": return "false";
case "OUT057": return "false";
case "OUT058": return "false";
case "MST109": return "false";
case "MST110": return "false";
case "MST111": return "false";
case "MST115": return "false";
case "MST116": return "false";
case "MST117": return "false";
case "RVD002": return "false";
case "WTR010": return "false";
case "RVD003": return "false";
case "HVY041": return "false";
case "HVY042": return "false";
case "HVY043": return "false";
case "MON135": return "false";
case "MON136": return "false";
case "MON137": return "false";
case "AIO015": return "false";
case "EVO084": return "false";
case "AIO020": return "false";
case "EVO085": return "false";
case "EVO086": return "false";
case "TCC039": return "false";
case "TCC044": return "false";
case "OUT106": return "false";
case "MON168": return "false";
case "MON169": return "false";
case "MON170": return "false";
case "ARC153": return "false";
case "ASB005": return "false";
case "TER008": return "false";
case "TER014": return "false";
case "UPR116": return "false";
case "UPR117": return "false";
case "UPR118": return "false";
case "DYN196": return "false";
case "ELE085": return "false";
case "ELE086": return "false";
case "ELE087": return "false";
case "UPR060": return "false";
case "UPR061": return "false";
case "UPR062": return "false";
case "MON269": return "false";
case "MON270": return "false";
case "MON271": return "false";
case "WTR116": return "false";
case "WTR039": return "false";
case "CRU022": return "false";
case "WTR038": return "false";
case "EVR017": return "false";
case "ELE131": return "false";
case "ELE132": return "false";
case "ELE133": return "false";
case "EVR038": return "false";
case "DTD100": return "false";
case "DTD101": return "false";
case "DTD102": return "false";
case "UPR093": return "false";
case "WTR080": return "false";
case "WTR011": return "false";
case "WTR012": return "false";
case "WTR013": return "false";
case "MST176": return "false";
case "MST177": return "false";
case "MST178": return "false";
case "CRU053": return "false";
case "TCC027": return "false";
case "ELE063": return "false";
case "ROS255": return "false";
case "ELE062": return "false";
case "ROS254": return "false";
case "UPR203": return "false";
case "UPR204": return "false";
case "UPR205": return "false";
case "ROS213": return "false";
case "OUT228": return "false";
case "ROS234": return "false";
case "OUT229": return "false";
case "ROS235": return "false";
case "OUT230": return "false";
case "ROS236": return "false";
case "JDG030": return "false";
case "CRU192": return "false";
case "CRU193": return "false";
case "CRU194": return "false";
case "JDG024": return "false";
case "DYN028": return "false";
case "WTR057": return "false";
case "WTR058": return "false";
case "WTR059": return "false";
case "EVO147": return "false";
case "EVO148": return "false";
case "EVO149": return "false";
case "ARC042": return "false";
case "OUT187": return "false";
case "ELE134": return "false";
case "TER009": return "false";
case "ELE135": return "false";
case "ELE136": return "false";
case "TER022": return "false";
case "UPR094": return "false";
case "EVO154": return "false";
case "UPR005": return "false";
case "ROS012": return "false";
case "ELE047": return "false";
case "ELE048": return "false";
case "ELE049": return "false";
case "OUT102": return "true";
case "ARC179": return "false";
case "ARC180": return "false";
case "ARC181": return "false";
case "ROS052": return "false";
case "ROS053": return "false";
case "ROS054": return "false";
case "DTD198": return "true";
case "ROS218": return "false";
case "ROS249": return "false";
case "ROS250": return "false";
case "TER027": return "false";
case "MON260": return "false";
case "MON261": return "false";
case "MON262": return "false";
case "MON187": return "false";
case "WTR060": return "false";
case "WTR061": return "false";
case "WTR062": return "false";
case "CRU188": return "false";
case "JDG013": return "false";
case "EVR158": return "false";
case "HVY014": return "false";
case "MON062": return "false";
case "DYN213": return "false";
case "DTD038": return "false";
case "DTD039": return "false";
case "DTD040": return "false";
case "DTD041": return "false";
case "DTD042": return "false";
case "DTD043": return "false";
case "DTD226": return "false";
case "AIO026": return "false";
case "CRU162": return "false";
case "ARC162": return "false";
case "JDG022": return "false";
case "DTD170": return "false";
case "MON154": return "false";
case "MON153": return "false";
case "ELE146": return "false";
case "ROS077": return "false";
case "ELE117": return "false";
case "AJV017": return "false";
case "ELE175": return "false";
case "UPR138": return "false";
case "ROS033": return "false";
case "DTD072": return "false";
case "DTD073": return "false";
case "DTD074": return "false";
case "MST161": return "false";
case "RVD007": return "false";
case "ELE163": return "false";
case "ELE164": return "false";
case "ELE165": return "false";
case "ELE050": return "false";
case "ELE051": return "false";
case "ELE052": return "false";
case "JDG036": return "false";
case "TCC048": return "false";
case "CRU035": return "false";
case "CRU036": return "false";
case "TCC040": return "false";
case "CRU037": return "false";
case "TCC045": return "false";
case "DTD208": return "true";
case "ROS170": return "false";
case "ROS171": return "false";
case "ROS172": return "false";
case "CRU165": return "false";
case "CRU166": return "false";
case "CRU167": return "false";
case "UPR063": return "false";
case "UPR064": return "false";
case "UPR065": return "false";
case "CRU079": return "false";
case "CRU080": return "false";
case "HVY094": return "false";
case "HVY134": return "false";
case "TCC031": return "false";
case "TCC032": return "false";
case "TCC030": return "false";
case "JDG034": return "false";
case "TCC033": return "false";
case "EVR182": return "false";
case "AKO008": return "false";
case "HVY157": return "false";
case "HVY158": return "false";
case "HVY159": return "false";
case "HVY137": return "false";
case "HVY138": return "false";
case "HVY139": return "false";
case "HVY177": return "false";
case "HVY178": return "false";
case "HVY179": return "false";
case "DTD088": return "false";
case "DTD089": return "false";
case "DTD090": return "false";
case "RVD025": return "false";
case "DYN071": return "false";
case "DTD166": return "false";
case "AUR023": return "false";
case "EVR144": return "false";
case "EVR145": return "false";
case "EVR146": return "false";
case "ELE145": return "false";
case "CRU180": return "false";
case "TCC055": return "false";
case "OUT159": return "false";
case "OUT160": return "false";
case "OUT161": return "false";
case "HVY246": return "true";
case "EVO117": return "false";
case "EVO118": return "false";
case "EVO119": return "false";
case "ARC018": return "false";
case "EVO016": return "false";
case "EVO015": return "false";
case "EVO014": return "false";
case "EVO017": return "false";
case "UPR147": return "false";
case "UPR148": return "false";
case "UPR149": return "false";
case "ELE038": return "false";
case "ELE039": return "false";
case "ELE040": return "false";
case "OUT103": return "true";
case "HVY062": return "false";
case "TCC034": return "false";
case "CRU109": return "false";
case "CRU110": return "false";
case "CRU111": return "false";
case "UPR050": return "false";
case "ARC203": return "false";
case "OUT222": return "false";
case "ARC204": return "false";
case "OUT223": return "false";
case "ARC205": return "false";
case "OUT224": return "false";
case "RVD024": return "false";
case "ROS024": return "false";
case "HVY071": return "false";
case "HVY072": return "false";
case "HVY073": return "false";
case "ARC159": return "false";
case "DYN000": return "false";
case "HVY104": return "false";
case "MST057": return "false";
case "MST058": return "false";
case "MST059": return "false";
case "OUT143": return "false";
case "HVY074": return "false";
case "TER010": return "false";
case "HVY075": return "false";
case "HVY076": return "false";
case "TER023": return "false";
case "ROS127": return "false";
case "ROS128": return "false";
case "ROS129": return "false";
case "UPR125": return "false";
case "HVY199": return "false";
case "DYN092": return "false";
case "MON195": return "false";
case "MON196": return "false";
case "MON197": return "false";
case "CRU148": return "false";
case "ROS137": return "false";
case "CRU149": return "false";
case "ROS138": return "false";
case "CRU150": return "false";
case "ROS139": return "false";
case "EVO243": return "false";
case "AIO016": return "false";
case "ARC019": return "false";
case "MON132": return "false";
case "MON133": return "false";
case "MON134": return "false";
case "CRU197": return "false";
case "EVR194": return "false";
case "TCC103": return "false";
case "TCC108": return "false";
case "UPR136": return "false";
case "MST076": return "false";
case "MST130": return "false";
case "ROS223": return "false";
case "ROS224": return "false";
case "ROS225": return "false";
case "ASB027": return "false";
case "DTD232": return "false";
case "CRU081": return "false";
case "ASB014": return "false";
case "MON057": return "false";
case "MON058": return "false";
case "MON059": return "false";
case "ARC218": return "false";
case "CRU195": return "false";
case "ELE237": return "false";
case "HVY244": return "false";
case "MON306": return "false";
case "MST224": return "false";
case "OUT238": return "false";
case "ROS238": return "false";
case "UPR224": return "false";
case "WTR224": return "false";
case "ELE235": return "false";
case "AUR007": return "false";
case "AUR015": return "false";
case "CRU057": return "false";
case "CRU058": return "false";
case "CRU059": return "false";
case "TCC046": return "false";
case "WTR045": return "false";
case "DYN101": return "false";
case "EVO174": return "false";
case "DYN102": return "false";
case "EVO175": return "false";
case "DYN103": return "false";
case "EVO176": return "false";
case "TCC037": return "false";
case "TCC042": return "false";
case "CRU025": return "false";
case "TCC070": return "false";
case "WTR162": return "false";
case "WTR043": return "true";
case "UPR206": return "false";
case "UPR207": return "false";
case "UPR208": return "false";
case "UPR410": return "false";
case "UPR410": return "false";
case "MON045": return "false";
case "MON046": return "false";
case "MON047": return "false";
case "DYN065": return "false";
case "MST188": return "false";
case "TCC104": return "false";
case "AAZ003": return "true";
case "OUT097": return "true";
case "TCC060": return "false";
case "TCC063": return "false";
case "TCC076": return "false";
case "ARC079": return "false";
case "DYN234": return "false";
case "DTD221": return "false";
case "UPR182": return "false";
case "EVR137": return "false";
case "ELE115": return "false";
case "ARC115": return "false";
case "CRU159": return "false";
case "AJV018": return "true";
case "WTR063": return "false";
case "WTR064": return "false";
case "WTR065": return "false";
case "CRU032": return "false";
case "CRU033": return "false";
case "CRU034": return "false";
case "DYN173": return "false";
case "ROS074": return "false";
case "OUT201": return "false";
case "OUT202": return "false";
case "OUT203": return "false";
case "ROS216": return "false";
case "HVY106": return "false";
case "HVY107": return "false";
case "HVY108": return "false";
case "DYN148": return "false";
case "DYN149": return "false";
case "DYN150": return "false";
case "OUT050": return "false";
case "DTD169": return "false";
case "UPR170": return "false";
case "UPR171": return "false";
case "UPR172": return "false";
case "DTD168": return "false";
case "ARC002": return "false";
case "AIO001": return "false";
case "EVO001": return "false";
case "EVO002": return "false";
case "ARC001": return "false";
case "CRU098": return "false";
case "CRU099": return "false";
case "AIO007": return "false";
case "EVO186": return "false";
case "EVO187": return "false";
case "AIO021": return "false";
case "EVO188": return "false";
case "CRU085": return "false";
case "CRU086": return "false";
case "CRU087": return "false";
case "CRU078": return "false";
case "WTR115": return "false";
case "DVR002": return "false";
case "ELE053": return "false";
case "ELE054": return "false";
case "ELE055": return "false";
case "DYN155": return "false";
case "OUT071": return "false";
case "OUT072": return "false";
case "OUT073": return "false";
case "ROS155": return "false";
case "ROS156": return "false";
case "ROS157": return "false";
case "MON138": return "false";
case "MON139": return "false";
case "MON140": return "false";
case "AAZ002": return "false";
case "ARC040": return "false";
case "CRU120": return "false";
case "OUT162": return "false";
case "OUT163": return "false";
case "OUT164": return "false";
case "DTD143": return "false";
case "DTD144": return "false";
case "DTD145": return "false";
case "DYN176": return "false";
case "DYN177": return "false";
case "DYN178": return "false";
case "DTD146": return "false";
case "DTD147": return "false";
case "DTD148": return "false";
case "HVY000": return "false";
case "WTR066": return "false";
case "WTR067": return "false";
case "WTR068": return "false";
case "DTD205": return "false";
case "ELE234": return "false";
case "MST084": return "false";
case "MON123": return "false";
case "DTD094": return "false";
case "DTD095": return "false";
case "DTD096": return "false";
case "WTR179": return "false";
case "WTR180": return "false";
case "WTR181": return "false";
case "EVO057": return "false";
case "MST079": return "false";
case "OUT074": return "false";
case "OUT075": return "false";
case "OUT076": return "false";
case "MST118": return "false";
case "MST119": return "false";
case "MST120": return "false";
case "ROS166": return "false";
case "OUT204": return "false";
case "OUT205": return "false";
case "OUT206": return "false";
case "DTD107": return "false";
case "DYN174": return "false";
case "DTD217": return "false";
case "DTD202": return "false";
case "MON157": return "false";
case "MON162": return "false";
case "MON163": return "false";
case "MON164": return "false";
case "DTD171": return "false";
case "WTR048": return "false";
case "TCC041": return "false";
case "WTR049": return "false";
case "TCC047": return "false";
case "WTR050": return "false";
case "OUT051": return "false";
case "ARC035": return "false";
case "EVR069": return "false";
case "HVY253": return "false";
case "AIO017": return "false";
case "EVO087": return "false";
case "EVO088": return "false";
case "EVO089": return "false";
case "AIO008": return "false";
case "EVO189": return "false";
case "EVO190": return "false";
case "EVO191": return "false";
case "RVD026": return "false";
case "UPR408": return "false";
case "UPR408": return "false";
case "MON189": return "true";
case "WTR114": return "false";
case "CRU076": return "false";
case "WTR113": return "false";
case "DVR001": return "false";
case "HVY176": return "false";
case "UPR160": return "false";
case "MST112": return "false";
case "MST113": return "false";
case "MST114": return "false";
case "HVY213": return "false";
case "HVY214": return "false";
case "HVY215": return "false";
case "OUT184": return "false";
case "UPR406": return "false";
case "UPR406": return "false";
case "UPR225": return "false";
case "HVY121": return "false";
case "HVY122": return "false";
case "HVY123": return "false";
case "ARC097": return "false";
case "ARC098": return "false";
case "ARC099": return "false";
case "MON141": return "false";
case "MON142": return "false";
case "MON143": return "false";
case "MON229": return "false";
case "CRU142": return "false";
case "EVR087": return "false";
case "MON090": return "false";
case "OUT098": return "true";
case "AAZ010": return "false";
case "DYN156": return "false";
case "DYN157": return "false";
case "DYN158": return "false";
case "ROS244": return "true";
case "WTR144": return "false";
case "DVR017": return "false";
case "WTR145": return "false";
case "WTR146": return "false";
case "UPR002": return "false";
case "UPR001": return "false";
case "JDG023": return "false";
case "WTR164": return "false";
case "WTR165": return "false";
case "WTR166": return "false";
case "MST087": return "false";
case "EVR110": return "false";
case "EVR111": return "false";
case "EVR112": return "false";
case "EVO195": return "false";
case "EVO196": return "false";
case "EVO197": return "false";
case "UPR021": return "false";
case "UPR022": return "false";
case "UPR023": return "false";
case "MON110": return "false";
case "MON111": return "false";
case "MON112": return "false";
case "ELE223": return "false";
case "UPR066": return "false";
case "UPR067": return "false";
case "UPR068": return "false";
case "MST235": return "false";
case "EVO246": return "false";
case "ROS252": return "false";
case "DYN002": return "false";
case "DYN003": return "false";
case "DYN004": return "false";
case "UPR024": return "false";
case "UPR025": return "false";
case "UPR026": return "false";
case "DTD211": return "false";
case "ROS036": return "false";
case "ROS037": return "false";
case "ROS038": return "false";
case "ROS034": return "false";
case "EVR020": return "false";
case "TCC038": return "false";
case "TCC043": return "false";
case "ELE137": return "false";
case "ELE138": return "false";
case "ELE139": return "false";
case "MON188": return "false";
case "ROS075": return "false";
case "MON190": return "true";
case "MON218": return "false";
case "HVY124": return "false";
case "HVY125": return "false";
case "HVY126": return "false";
case "CRU050": return "false";
case "TCC078": return "false";
case "ARC173": return "false";
case "ARC174": return "false";
case "ARC175": return "false";
case "ELE198": return "false";
case "ELE199": return "false";
case "ELE200": return "false";
case "ROS085": return "false";
case "ROS086": return "false";
case "ROS087": return "false";
case "ROS110": return "false";
case "ROS111": return "false";
case "ROS112": return "false";
case "DTD233": return "false";
case "MST237": return "false";
case "UPR027": return "false";
case "UPR028": return "false";
case "UPR029": return "false";
case "DTD194": return "false";
case "ELE109": return "false";
case "ROS025": return "false";
case "ROS257": return "false";
case "AUR027": return "false";
case "DTD195": return "false";
case "ELE110": return "false";
case "ROS026": return "false";
case "ELE206": return "false";
case "ELE207": return "false";
case "ELE208": return "false";
case "EVO240": return "false";
case "HVY200": return "false";
case "ELE025": return "false";
case "ELE026": return "false";
case "ELE027": return "false";
case "CRU038": return "false";
case "CRU039": return "false";
case "CRU040": return "false";
case "WTR069": return "false";
case "WTR070": return "false";
case "WTR071": return "false";
case "EVR125": return "false";
case "EVR126": return "false";
case "EVR127": return "false";
case "MST197": return "false";
case "MST198": return "false";
case "MST199": return "false";
case "DYN001": return "false";
case "DTD004": return "false";
case "DVR009": return "false";
case "UPR104": return "false";
case "ARC167": return "false";
case "ARC168": return "false";
case "ARC169": return "false";
case "TCC061": return "false";
case "ARC045": return "false";
case "MON126": return "false";
case "MON127": return "false";
case "MON128": return "false";
case "ELE004": return "true";
case "TCC071": return "false";
case "WTR170": return "false";
case "HVY127": return "false";
case "HVY128": return "false";
case "HVY129": return "false";
case "UPR051": return "false";
case "UPR052": return "false";
case "UPR053": return "false";
case "ASB009": return "false";
case "MON048": return "false";
case "MON049": return "false";
case "MON050": return "false";
case "MST026": return "false";
case "MON098": return "false";
case "MON099": return "false";
case "MON100": return "false";
case "MST025": return "false";
case "MST238": return "false";
case "WTR159": return "false";
case "ELE013": return "false";
case "ELE014": return "false";
case "ELE015": return "false";
case "ELE094": return "false";
case "ELE095": return "false";
case "ELE096": return "false";
case "ELE097": return "false";
case "ELE098": return "false";
case "ELE099": return "false";
case "ELE100": return "false";
case "ELE101": return "false";
case "ELE102": return "false";
case "DTD149": return "false";
case "DTD150": return "false";
case "DTD151": return "false";
case "DYN119": return "false";
case "UPR187": return "false";
case "MST137": return "false";
case "MST139": return "false";
case "MST138": return "false";
case "ROS189": return "false";
case "ROS190": return "false";
case "ROS191": return "false";
case "ROS167": return "false";
case "MST218": return "false";
case "MST219": return "false";
case "MST220": return "false";
case "EVR173": return "false";
case "EVR174": return "false";
case "EVR175": return "false";
case "ELE119": return "false";
case "ELE120": return "false";
case "ELE121": return "false";
case "EVO031": return "false";
case "EVO431": return "false";
case "EVO047": return "false";
case "EVO447": return "false";
case "EVO051": return "false";
case "EVO451": return "false";
case "EVO049": return "false";
case "EVO449": return "false";
case "EVO030": return "false";
case "EVO430": return "false";
case "EVO048": return "false";
case "EVO448": return "false";
case "EVO034": return "false";
case "EVO434": return "false";
case "EVO046": return "false";
case "EVO446": return "false";
case "EVO039": return "false";
case "EVO439": return "false";
case "TCC008": return "false";
case "TCC408": return "false";
case "EVO035": return "false";
case "EVO435": return "false";
case "EVO032": return "false";
case "EVO432": return "false";
case "MST229": return "false";
case "MST629": return "false";
case "EVO033": return "false";
case "EVO433": return "false";
case "HVY248": return "false";
case "HVY648": return "false";
case "EVO041": return "false";
case "EVO441": return "false";
case "TCC010": return "false";
case "TCC410": return "false";
case "MST228": return "false";
case "MST628": return "false";
case "EVO040": return "false";
case "EVO440": return "false";
case "TCC009": return "false";
case "TCC409": return "false";
case "EVO044": return "false";
case "EVO444": return "false";
case "EVO043": return "false";
case "EVO443": return "false";
case "EVO042": return "false";
case "EVO442": return "false";
case "EVO045": return "false";
case "EVO445": return "false";
case "MST230": return "false";
case "MST630": return "false";
case "EVO036": return "false";
case "EVO436": return "false";
case "MST231": return "false";
case "MST631": return "false";
case "EVO028": return "false";
case "EVO428": return "false";
case "EVO026": return "false";
case "EVO426": return "false";
case "EVO027": return "false";
case "EVO427": return "false";
case "EVO029": return "false";
case "EVO429": return "false";
case "EVO038": return "false";
case "EVO438": return "false";
case "TCC007": return "false";
case "TCC407": return "false";
case "EVO037": return "false";
case "EVO437": return "false";
case "EVO052": return "false";
case "EVO452": return "false";
case "EVO053": return "false";
case "EVO453": return "false";
case "EVO050": return "false";
case "EVO450": return "false";
case "EVO198": return "false";
case "EVO199": return "false";
case "AIO022": return "false";
case "EVO200": return "false";
case "DTD110": return "false";
case "ROS192": return "false";
case "ROS193": return "false";
case "ROS194": return "false";
case "ELE067": return "false";
case "ELE068": return "false";
case "ELE069": return "false";
case "ELE093": return "false";
case "ASB010": return "false";
case "MON051": return "false";
case "MON052": return "false";
case "MON053": return "false";
case "MON245": return "false";
case "ARC000": return "false";
case "EVO146": return "false";
case "HVY198": return "false";
case "ROS114": return "false";
case "MST206": return "false";
case "MST207": return "false";
case "MST208": return "false";
case "UPR045": return "false";
case "UPR044": return "false";
case "OUT115": return "false";
case "OUT116": return "false";
case "OUT117": return "false";
case "MST023": return "false";
case "AIO009": return "false";
case "HVY109": return "false";
case "HVY110": return "false";
case "HVY111": return "false";
case "ARC200": return "false";
case "JDG016": return "false";
case "ARC201": return "false";
case "ARC202": return "false";
case "EVR094": return "false";
case "EVR095": return "false";
case "EVR096": return "false";
case "CRU125": return "false";
case "OUT207": return "false";
case "OUT208": return "false";
case "OUT209": return "false";
case "DYN082": return "false";
case "DYN083": return "false";
case "DYN084": return "false";
case "ROS031": return "false";
case "EVO210": return "false";
case "EVO211": return "false";
case "EVO212": return "false";
case "ROS067": return "false";
case "ROS068": return "false";
case "ROS069": return "false";
case "ARC182": return "false";
case "ARC183": return "false";
case "ARC184": return "false";
case "TCC053": return "false";
case "DTD005": return "false";
case "DTD006": return "false";
case "DTD007": return "false";
case "DTD008": return "false";
case "DTD009": return "false";
case "DTD010": return "false";
case "DTD011": return "false";
case "DTD012": return "false";
case "TCC057": return "false";
case "CRU054": return "false";
case "EVR157": return "false";
case "EVO159": return "false";
case "TCC019": return "false";
case "EVO160": return "false";
case "TCC022": return "false";
case "EVO161": return "false";
case "TCC026": return "false";
case "MST092": return "false";
case "MST093": return "false";
case "MST094": return "false";
case "OUT181": return "false";
case "DTD135": return "true";
case "ELE056": return "false";
case "ELE057": return "false";
case "ELE058": return "false";
case "UPR095": return "false";
case "UPR096": return "false";
case "UPR084": return "false";
case "ELE177": return "false";
case "ROS107": return "false";
case "ELE178": return "false";
case "ROS108": return "false";
case "ELE179": return "false";
case "ROS109": return "false";
case "ROS072": return "false";
case "ELE092": return "false";
case "HVY155": return "false";
case "DYN136": return "false";
case "DYN137": return "false";
case "DYN138": return "false";
case "OUT182": return "false";
case "OUT111": return "false";
case "OUT109": return "false";
case "OUT110": return "false";
case "UPR191": return "false";
case "UPR192": return "false";
case "UPR193": return "false";
case "DYN050": return "false";
case "MST179": return "false";
case "TCC084": return "false";
case "DYN051": return "false";
case "MST180": return "false";
case "TCC093": return "false";
case "DYN052": return "false";
case "MST181": return "false";
case "WTR092": return "false";
case "WTR093": return "false";
case "WTR094": return "false";
case "OUT139": return "false";
case "DTD218": return "false";
case "ELE065": return "false";
case "AAZ007": return "false";
case "ROS089": return "false";
case "ROS090": return "false";
case "ROS091": return "false";
case "DVR026": return "false";
case "WTR182": return "false";
case "WTR183": return "false";
case "WTR184": return "false";
case "CRU055": return "false";
case "ROS002": return "false";
case "ROS001": return "false";
case "TER017": return "false";
case "TER024": return "false";
case "WTR086": return "false";
case "WTR087": return "false";
case "WTR088": return "false";
case "ROS251": return "false";
case "CRU063": return "false";
case "TCC085": return "false";
case "CRU064": return "false";
case "CRU065": return "false";
case "TCC099": return "false";
case "UPR190": return "false";
case "ARC206": return "false";
case "ARC207": return "false";
case "ARC208": return "false";
case "ELE066": return "true";
case "CRU168": return "false";
case "CRU169": return "false";
case "CRU170": return "false";
case "WTR046": return "false";
case "ARC120": return "false";
case "ROS214": return "false";
case "EVR138": return "false";
case "AAZ031": return "false";
case "OUT235": return "false";
case "OUT172": return "false";
case "ELE059": return "false";
case "ELE060": return "false";
case "ELE061": return "false";
case "OUT210": return "false";
case "OUT211": return "false";
case "OUT212": return "false";
case "UPR105": return "false";
case "UPR153": return "false";
case "DTD224": return "false";
case "DTD222": return "false";
case "DTD225": return "false";
case "DTD223": return "false";
case "MON272": return "false";
case "MON273": return "false";
case "MON274": return "false";
case "ELE148": return "false";
case "ELE149": return "false";
case "ELE150": return "false";
case "UPR126": return "true";
case "ELE035": return "false";
case "ELE111": return "false";
case "EVR197": return "false";
case "UPR150": return "false";
case "UPR130": return "false";
case "UPR131": return "false";
case "UPR132": return "false";
case "ROS055": return "false";
case "ROS056": return "false";
case "ROS057": return "false";
case "AUR008": return "false";
case "ROS092": return "false";
case "AUR016": return "false";
case "ROS093": return "false";
case "ROS094": return "false";
case "EVO075": return "false";
case "EVO168": return "false";
case "EVO169": return "false";
case "EVO170": return "false";
case "ELE091": return "false";
case "DTD140": return "false";
case "ROS227": return "false";
case "UPR194": return "false";
case "ROS228": return "false";
case "UPR195": return "false";
case "ROS229": return "false";
case "UPR196": return "false";
case "CRU178": return "false";
case "EVO249": return "false";
case "WTR150": return "false";
case "MON155": return "false";
case "DVR005": return "false";
case "MON108": return "false";
case "DYN089": return "false";
case "CRU179": return "false";
case "EVO171": return "false";
case "EVO172": return "false";
case "EVO173": return "false";
case "EVO222": return "false";
case "EVO223": return "false";
case "EVO224": return "false";
case "HVY135": return "false";
case "HVY053": return "false";
case "JDG005": return "false";
case "CRU163": return "false";
case "MON006": return "false";
case "EVR085": return "false";
case "ROS004": return "true";
case "UPR151": return "false";
case "MON203": return "false";
case "MON204": return "false";
case "MON205": return "false";
case "EVO156": return "false";
case "EVO157": return "false";
case "EVO158": return "false";
case "OUT185": return "false";
case "ELE016": return "false";
case "ELE017": return "false";
case "ELE018": return "false";
case "UPR137": return "false";
case "DTD063": return "false";
case "DTD064": return "false";
case "DTD065": return "false";
case "WTR118": return "false";
case "MON069": return "false";
case "MON070": return "false";
case "MON071": return "false";
case "DVR008": return "true";
case "HVY196": return "false";
case "ROS173": return "false";
case "ROS174": return "false";
case "ROS175": return "false";
case "HVY133": return "false";
case "DYN243": return "false";
case "HVY243": return "false";
case "HVY054": return "true";
case "WTR153": return "false";
case "ROS076": return "false";
case "HVY055": return "true";
case "OUT186": return "false";
case "CRU181": return "false";
case "MST008": return "false";
case "HVY097": return "false";
case "EVR000": return "false";
case "HVY205": return "false";
case "DTD167": return "false";
case "ARC078": return "false";
case "MST200": return "false";
case "MST201": return "false";
case "MST202": return "false";
case "MON144": return "false";
case "MON145": return "false";
case "MON146": return "false";
case "HVY245": return "false";
case "MON000": return "false";
case "DTD175": return "false";
case "DTD176": return "false";
case "DTD177": return "false";
case "DTD136": return "false";
case "EVO070": return "false";
case "TCC086": return "false";
case "TCC094": return "false";
case "MON192": return "false";
case "ROS245": return "false";
case "DTD229": return "false";
case "EVO090": return "false";
case "EVO091": return "false";
case "EVO092": return "false";
case "DVR007": return "false";
case "MON061": return "false";
case "TCC028": return "false";
case "ARC060": return "false";
case "ARC061": return "false";
case "ARC062": return "false";
case "DYN088": return "false";
case "ROS220": return "false";
case "TER006": return "false";
case "CRU048": return "false";
case "CRU049": return "false";
case "OUT048": return "false";
case "WTR078": return "false";
case "MST060": return "false";
case "MST061": return "false";
case "MST062": return "false";
case "AUR013": return "false";
case "AUR020": return "false";
case "ROS061": return "false";
case "ROS062": return "false";
case "ROS063": return "false";
case "MON105": return "false";
case "MON106": return "false";
case "ROS120": return "false";
case "MST140": return "false";
case "MST141": return "false";
case "MST142": return "false";
case "EVR141": return "false";
case "MST037": return "false";
case "MST038": return "false";
case "MST039": return "false";
case "OUT077": return "false";
case "WTR098": return "false";
case "OUT078": return "false";
case "WTR099": return "false";
case "OUT079": return "false";
case "WTR100": return "false";
case "OUT052": return "false";
case "ARC057": return "false";
case "ARC058": return "false";
case "ARC059": return "false";
case "HVY202": return "false";
case "UPR215": return "false";
case "UPR216": return "false";
case "UPR217": return "false";
case "EVR183": return "false";
case "TCC072": return "false";
case "WTR000": return "false";
case "ELE144": return "false";
case "TCC052": return "false";
case "ROS016": return "true";
case "WTR152": return "false";
case "HVY181": return "false";
case "DYN153": return "false";
case "UPR047": return "false";
case "AUR009": return "false";
case "ELE192": return "false";
case "ROS095": return "false";
case "AUR017": return "false";
case "ELE193": return "false";
case "ROS096": return "false";
case "ELE194": return "false";
case "ROS097": return "false";
case "EVO061": return "false";
case "EVO062": return "false";
case "EVO063": return "false";
case "AIO006": return "false";
case "AIO004": return "false";
case "JDG032": return "false";
case "AIO005": return "false";
case "AIO003": return "false";
case "MST028": return "false";
case "MST005": return "false";
case "MST049": return "false";
case "EVO138": return "false";
case "UPR183": return "false";
case "DTD105": return "false";
case "ASB003": return "false";
case "JDG031": return "false";
case "WTR042": return "false";
case "ROS029": return "false";
case "EVR053": return "false";
case "DYN162": return "false";
case "DYN163": return "false";
case "DYN164": return "false";
case "MON004": return "false";
case "DTD013": return "true";
case "MON007": return "true";
case "DTD014": return "false";
case "MON014": return "false";
case "DTD015": return "false";
case "MON015": return "false";
case "DTD016": return "false";
case "MON016": return "false";
case "DTD017": return "false";
case "MON017": return "false";
case "DTD018": return "false";
case "MON018": return "false";
case "DTD019": return "false";
case "MON019": return "false";
case "DTD020": return "false";
case "MON020": return "false";
case "DTD021": return "false";
case "MON021": return "false";
case "DTD022": return "false";
case "MON022": return "false";
case "DTD023": return "false";
case "MON023": return "false";
case "DTD024": return "false";
case "MON024": return "false";
case "DTD025": return "false";
case "MON025": return "false";
case "DTD026": return "false";
case "MON008": return "false";
case "DTD027": return "false";
case "MON009": return "false";
case "DTD028": return "false";
case "MON010": return "false";
case "CRU056": return "false";
case "MON121": return "false";
case "AAZ005": return "false";
case "AKO005": return "false";
case "ARC006": return "false";
case "HVY049": return "false";
case "EVR005": return "false";
case "EVR006": return "false";
case "EVR007": return "false";
case "CRU106": return "false";
case "CRU107": return "false";
case "CRU108": return "false";
case "EVR164": return "false";
case "EVR165": return "false";
case "EVR166": return "false";
case "ROS078": return "false";
case "MST014": return "false";
case "MST015": return "false";
case "MST016": return "false";
case "CRU091": return "false";
case "CRU092": return "false";
case "CRU093": return "false";
case "DVR021": return "false";
case "ROS140": return "false";
case "ROS141": return "false";
case "ROS142": return "false";
case "ROS143": return "false";
case "ROS144": return "false";
case "ROS145": return "false";
case "HVY130": return "false";
case "HVY131": return "false";
case "HVY132": return "false";
case "ROS165": return "false";
case "DTD228": return "false";
case "MST096": return "false";
case "ELE214": return "false";
case "HVY099": return "true";
case "ROS212": return "false";
case "MON122": return "false";
case "WTR151": return "false";
case "DYN152": return "false";
case "HVY095": return "false";
case "MON200": return "false";
case "MON201": return "false";
case "MON202": return "false";
case "AKO009": return "false";
case "OUT189": return "false";
case "OUT190": return "false";
case "OUT191": return "false";
case "EVR041": return "false";
case "EVR042": return "false";
case "EVR043": return "false";
case "DTD172": return "false";
case "DTD173": return "false";
case "DTD174": return "false";
case "MON147": return "false";
case "MON148": return "false";
case "MON149": return "false";
case "OUT148": return "false";
case "OUT149": return "false";
case "OUT150": return "false";
case "WTR084": return "false";
case "EVO102": return "false";
case "EVO103": return "false";
case "EVO104": return "false";
case "ARC036": return "false";
case "DYN110": return "false";
case "EVO099": return "false";
case "DYN111": return "false";
case "DYN112": return "false";
case "EVO234": return "false";
case "EVO100": return "false";
case "EVO011": return "false";
case "UPR139": return "false";
case "UPR133": return "false";
case "UPR134": return "false";
case "UPR135": return "false";
case "UPR109": return "true";
case "ELE151": return "false";
case "ELE152": return "false";
case "ELE153": return "false";
case "ELE037": return "false";
case "UPR119": return "false";
case "UPR120": return "false";
case "UPR121": return "false";
case "ELE157": return "false";
case "ELE158": return "false";
case "ELE159": return "false";
case "MON072": return "false";
case "MON073": return "false";
case "MON074": return "false";
case "DYN154": return "false";
case "MON075": return "false";
case "MON076": return "false";
case "MON077": return "false";
case "DYN240": return "false";
case "DYN241": return "false";
case "DYN242": return "false";
case "EVR022": return "false";
case "MST121": return "false";
case "MST122": return "false";
case "MST123": return "false";
case "DVR015": return "false";
case "EVR063": return "false";
case "EVR064": return "false";
case "EVR065": return "false";
case "CRU135": return "false";
case "CRU136": return "false";
case "CRU137": return "false";
case "ARC135": return "false";
case "ARC136": return "false";
case "ARC137": return "false";
case "ARC010": return "false";
case "AAZ032": return "false";
case "OUT236": return "false";
case "OUT173": return "false";
case "OUT024": return "false";
case "OUT025": return "false";
case "OUT026": return "false";
case "AAZ011": return "false";
case "OUT118": return "false";
case "OUT119": return "false";
case "AAZ027": return "false";
case "OUT120": return "false";
case "OUT192": return "false";
case "OUT193": return "false";
case "OUT194": return "false";
case "OUT012": return "false";
case "UPR097": return "false";
case "EVO120": return "false";
case "EVO121": return "false";
case "EVO122": return "false";
case "EVO123": return "false";
case "EVO124": return "false";
case "EVO125": return "false";
case "ROS164": return "false";
case "MST400": return "false";
case "MST400": return "false";
case "MST410": return "false";
case "MST410": return "false";
case "MST432": return "false";
case "MST432": return "false";
case "MST453": return "false";
case "MST453": return "false";
case "MST495": return "false";
case "MST495": return "false";
case "MST496": return "false";
case "MST496": return "false";
case "MST497": return "false";
case "MST497": return "false";
case "MST498": return "false";
case "MST498": return "false";
case "MST499": return "false";
case "MST499": return "false";
case "MST500": return "false";
case "MST500": return "false";
case "MST501": return "false";
case "MST501": return "false";
case "MST502": return "false";
case "MST502": return "false";
case "UPR140": return "false";
case "ELE088": return "false";
case "ELE089": return "false";
case "ELE090": return "false";
case "JDG000": return "false";
case "TCC058": return "false";
case "TCC062": return "false";
case "TCC075": return "false";
case "MST017": return "false";
case "MST018": return "false";
case "MST019": return "false";
case "EVO241": return "true";
case "MON158": return "false";
case "ELE103": return "false";
case "ELE104": return "false";
case "ELE105": return "false";
case "MON066": return "false";
case "MON067": return "false";
case "MON068": return "false";
case "UPR009": return "false";
case "UPR010": return "false";
case "UPR008": return "false";
case "UPR006": return "false";
case "UPR011": return "false";
case "UPR012": return "false";
case "UPR013": return "false";
case "UPR014": return "false";
case "DYN212": return "false";
case "UPR015": return "false";
case "UPR007": return "false";
case "UPR016": return "false";
case "UPR017": return "false";
case "CRU046": return "false";
case "TCC077": return "false";
case "HER123": return "false";
case "MON088": return "false";
case "MON243": return "false";
case "RVD005": return "false";
case "MON241": return "false";
case "MON244": return "false";
case "RVD006": return "false";
case "MON242": return "false";
case "WTR157": return "false";
case "AUR003": return "false";
case "DVR003": return "false";
case "TER004": return "false";
case "WTR155": return "false";
case "AUR006": return "false";
case "DVR006": return "false";
case "TER007": return "false";
case "WTR158": return "false";
case "WTR156": return "false";
case "WTR122": return "false";
case "DYN072": return "false";
case "DVR016": return "false";
case "WTR132": return "false";
case "WTR133": return "false";
case "WTR134": return "false";
case "DTD207": return "false";
case "UPR141": return "false";
case "UPR142": return "false";
case "UPR143": return "false";
case "OUT027": return "false";
case "OUT028": return "false";
case "OUT029": return "false";
case "EVR120": return "false";
case "UPR103": return "false";
case "UPR102": return "false";
case "LGS176": return "false";
case "LGS177": return "false";
case "LGS178": return "false";
case "AJV001": return "false";
case "TCC050": return "false";
case "DYN067": return "false";
case "HVY249": return "true";
case "DYN104": return "false";
case "EVO180": return "false";
case "DYN105": return "false";
case "EVO181": return "false";
case "DYN106": return "false";
case "EVO182": return "false";
case "EVO126": return "false";
case "EVO127": return "false";
case "EVO128": return "false";
case "MST105": return "false";
case "ARC114": return "false";
case "ARC113": return "false";
case "CRU158": return "false";
case "HVY091": return "false";
case "HVY090": return "false";
case "CRU077": return "false";
case "OUT046": return "false";
case "WTR077": return "false";
case "CRU045": return "false";
case "OUT045": return "false";
case "WTR076": return "false";
case "CRU118": return "false";
case "HVY002": return "false";
case "AKO001": return "false";
case "HVY001": return "false";
case "CRU002": return "false";
case "MST234": return "false";
case "EVR159": return "false";
case "OUT144": return "false";
case "AKO003": return "true";
case "HVY009": return "true";
case "MST068": return "false";
case "ELE000": return "false";
case "EVR121": return "false";
case "UPR411": return "false";
case "UPR411": return "false";
case "AAZ017": return "false";
case "OUT112": return "false";
case "AAZ018": return "false";
case "OUT113": return "false";
case "AAZ019": return "false";
case "OUT114": return "false";
case "JDG017": return "false";
case "WTR161": return "false";
case "UPR098": return "false";
case "UPR069": return "false";
case "UPR070": return "false";
case "UPR071": return "false";
case "HVY063": return "false";
case "TCC035": return "false";
case "EVO207": return "false";
case "EVO208": return "false";
case "EVO209": return "false";
case "DTD082": return "false";
case "DTD083": return "false";
case "DTD084": return "false";
case "ARC209": return "false";
case "ARC210": return "false";
case "ARC211": return "false";
case "HVY192": return "false";
case "HVY193": return "false";
case "HVY194": return "false";
case "HVY152": return "false";
case "HVY153": return "false";
case "HVY154": return "false";
case "HVY172": return "false";
case "HVY173": return "false";
case "HVY174": return "false";
case "DYN120": return "false";
case "TCC087": return "false";
case "WTR101": return "false";
case "TCC095": return "false";
case "WTR102": return "false";
case "WTR103": return "false";
case "ARC121": return "true";
case "MST077": return "false";
case "DTD104": return "false";
case "MON120": return "false";
case "DTD564": return "true";
case "DTD564": return "true";
case "DTD103": return "false";
case "MON119": return "false";
case "ELE032": return "false";
case "ELE031": return "false";
case "ARC164": return "false";
case "ARC165": return "false";
case "ARC166": return "false";
case "EVR161": return "false";
case "TCC056": return "false";
case "EVR162": return "false";
case "EVR163": return "false";
case "ELE036": return "true";
case "DTD000": return "false";
case "ASB011": return "false";
case "DTD066": return "false";
case "DTD067": return "false";
case "DTD068": return "false";
case "ROS082": return "false";
case "ROS083": return "false";
case "ROS084": return "false";
case "ROS071": return "false";
case "ELE183": return "false";
case "ELE184": return "false";
case "AUR026": return "false";
case "ELE185": return "false";
case "AUR010": return "false";
case "ELE189": return "false";
case "ROS098": return "false";
case "AUR018": return "false";
case "ELE190": return "false";
case "ROS099": return "false";
case "ELE191": return "false";
case "ROS100": return "false";
case "AAZ024": return "false";
case "UPR087": return "false";
case "EVO064": return "false";
case "TCC012": return "false";
case "EVO065": return "false";
case "EVO066": return "false";
case "TCC023": return "false";
case "ARC032": return "false";
case "ARC033": return "false";
case "ARC034": return "false";
case "TER011": return "false";
case "TER015": return "false";
case "DYN165": return "false";
case "DYN166": return "false";
case "DYN167": return "false";
case "MST232": return "false";
case "OUT195": return "false";
case "OUT196": return "false";
case "OUT197": return "false";
case "DYN175": return "false";
case "WTR081": return "true";
case "DTD219": return "false";
case "ASB026": return "true";
case "MON034": return "true";
case "DTD080": return "false";
case "MON003": return "false";
case "HVY254": return "false";
case "DTD003": return "false";
case "MON206": return "false";
case "MON207": return "false";
case "MON208": return "false";
case "CRU186": return "false";
case "HVY234": return "false";
case "EVO097": return "false";
case "ROS118": return "false";
case "EVR027": return "false";
case "EVR028": return "false";
case "EVR029": return "false";
case "DYN016": return "false";
case "DYN017": return "false";
case "DYN018": return "false";
case "DYN019": return "false";
case "DYN020": return "false";
case "DYN021": return "false";
case "ARC154": return "false";
case "ROS130": return "false";
case "ROS131": return "false";
case "ROS132": return "false";
case "OUT030": return "false";
case "OUT031": return "false";
case "OUT032": return "false";
case "AKO002": return "false";
case "CRU004": return "false";
case "CRU005": return "false";
case "HVY005": return "false";
case "AJV011": return "false";
case "CRU026": return "false";
case "MST031": return "false";
case "ELE174": return "false";
case "MON124": return "false";
case "OUT157": return "false";
case "OUT049": return "false";
case "WTR079": return "false";
case "DYN118": return "false";
case "MST004": return "false";
case "OUT140": return "false";
case "TCC079": return "false";
case "MST160": return "false";
case "EVR037": return "false";
case "CRU008": return "false";
case "EVO000": return "false";
case "MST162": return "false";
case "TCC088": return "false";
case "CRU145": return "false";
case "CRU146": return "false";
case "CRU147": return "false";
case "AIO010": return "false";
case "ARC008": return "false";
case "EVO139": return "false";
case "EVO004": return "false";
case "EVO005": return "false";
case "CRU151": return "false";
case "ROS146": return "false";
case "CRU152": return "false";
case "ROS147": return "false";
case "CRU153": return "false";
case "ROS148": return "false";
case "EVO067": return "false";
case "TCC013": return "false";
case "EVO068": return "false";
case "EVO069": return "false";
case "TCC024": return "false";
case "EVO076": return "false";
case "EVO143": return "false";
case "EVO059": return "false";
case "CRU103": return "false";
case "TCC049": return "false";
case "OUT105": return "false";
case "JDG029": return "false";
case "MON303": return "false";
case "AAZ026": return "false";
case "MON304": return "false";
case "MON305": return "false";
case "ROS169": return "false";
case "MON012": return "false";
case "DYN068": return "false";
case "MST027": return "false";
case "EVO201": return "false";
case "EVO202": return "false";
case "EVO203": return "false";
case "CRU161": return "false";
case "DTD410": return "false";
case "DTD410": return "false";
case "EVR070": return "true";
case "AKO028": return "false";
case "HVY241": return "false";
case "TCC105": return "false";
case "TER028": return "false";
case "HVY143": return "false";
case "HVY144": return "false";
case "AKO020": return "false";
case "HVY145": return "false";
case "HVY050": return "false";
case "DYN194": return "false";
case "MST124": return "false";
case "MST125": return "false";
case "MST126": return "false";
case "DYN048": return "false";
case "EVO093": return "false";
case "EVO094": return "false";
case "EVO095": return "false";
case "HVY007": return "false";
case "MON296": return "false";
case "MON297": return "false";
case "MON298": return "false";
case "UPR412": return "false";
case "UPR412": return "false";
case "EVR139": return "false";
case "DTD227": return "false";
case "MST000": return "false";
case "HVY235": return "false";
case "HVY236": return "false";
case "HVY237": return "false";
case "HVY010": return "true";
case "MST034": return "false";
case "MST035": return "false";
case "MST036": return "false";
case "ARC185": return "false";
case "ARC186": return "false";
case "ARC187": return "false";
case "EVO140": return "false";
case "ARC081": return "false";
case "DTD209": return "false";
case "UPR054": return "false";
case "UPR055": return "false";
case "UPR056": return "false";
case "WTR083": return "true";
case "ELE019": return "false";
case "ELE020": return "false";
case "ELE021": return "false";
case "OUT121": return "false";
case "OUT122": return "false";
case "OUT123": return "false";
case "MST233": return "true";
case "RVD018": return "false";
case "MON191": return "false";
case "DTD193": return "false";
case "HVY207": return "false";
case "WTR147": return "false";
case "WTR148": return "false";
case "WTR149": return "false";
case "ARC077": return "false";
case "CRU139": return "false";
case "UPR413": return "false";
case "UPR413": return "false";
case "OUT005": return "false";
case "OUT006": return "false";
case "EVR023": return "false";
case "DYN029": return "false";
case "ELE213": return "false";
case "MST209": return "false";
case "WTR185": return "false";
case "MST210": return "false";
case "WTR186": return "false";
case "MST211": return "false";
case "WTR187": return "false";
case "MST221": return "false";
case "WTR218": return "false";
case "MST222": return "false";
case "WTR219": return "false";
case "MST223": return "false";
case "WTR220": return "false";
case "ARC082": return "false";
case "DYN492": return "false";
case "DYN492": return "false";
case "DYN492": return "false";
case "DYN139": return "false";
case "DYN140": return "false";
case "DYN141": return "false";
case "HVY016": return "false";
case "AAZ029": return "true";
case "ARC046": return "true";
case "TCC051": return "false";
case "DTD197": return "true";
case "JDG027": return "false";
case "MON246": return "false";
case "ROS023": return "false";
case "ARC158": return "false";
case "ARC157": return "false";
case "ARC155": return "false";
case "ARC156": return "false";
case "DTD201": return "false";
case "MST002": return "false";
case "MST001": return "false";
case "ELE005": return "false";
case "UPR221": return "false";
case "UPR222": return "false";
case "UPR223": return "false";
case "EVR056": return "false";
case "ARC091": return "false";
case "ROS158": return "false";
case "ARC092": return "false";
case "ROS159": return "false";
case "ARC093": return "false";
case "ROS160": return "false";
case "DTD142": return "true";
case "MON013": return "false";
case "ELE002": return "false";
case "ELE001": return "false";
case "HVY093": return "false";
case "HVY092": return "false";
case "DVR019": return "false";
case "OUT080": return "false";
case "OUT081": return "false";
case "OUT082": return "false";
case "WTR095": return "false";
case "WTR096": return "false";
case "WTR097": return "false";
case "ROS195": return "false";
case "ROS196": return "false";
case "ROS197": return "false";
case "ARC037": return "false";
case "OUT007": return "false";
case "OUT008": return "false";
case "MST080": return "false";
case "DYN235": return "false";
case "ROS020": return "false";
case "ROS019": return "false";
case "MON248": return "false";
case "MON249": return "false";
case "MON250": return "false";
case "EVO204": return "false";
case "EVO205": return "false";
case "EVO206": return "false";
case "CRU088": return "false";
case "CRU089": return "false";
case "DVR020": return "false";
case "CRU090": return "false";
case "EVR066": return "false";
case "EVR067": return "false";
case "EVR068": return "false";
case "UPR414": return "false";
case "UPR414": return "false";
case "ELE219": return "false";
case "ELE220": return "false";
case "ELE221": return "false";
case "ARC020": return "false";
case "EVO219": return "false";
case "TCC014": return "false";
case "ARC021": return "false";
case "EVO220": return "false";
case "ARC022": return "false";
case "EVO221": return "false";
case "HVY065": return "false";
case "HVY066": return "false";
case "HVY067": return "false";
case "CRU112": return "false";
case "CRU113": return "false";
case "CRU114": return "false";
case "HVY201": return "false";
case "ROS198": return "false";
case "ROS199": return "false";
case "ROS200": return "false";
case "MON275": return "false";
case "MON276": return "false";
case "MON277": return "false";
case "EVO096": return "false";
case "WTR123": return "false";
case "WTR124": return "false";
case "WTR125": return "false";
case "HVY020": return "false";
case "HVY021": return "false";
case "RVD015": return "false";
case "HVY022": return "false";
case "HVY032": return "false";
case "RVD010": return "false";
case "WTR023": return "false";
case "HVY033": return "false";
case "WTR024": return "false";
case "HVY034": return "false";
case "WTR025": return "false";
case "EVO213": return "false";
case "EVO214": return "false";
case "EVO215": return "false";
case "MON011": return "false";
case "HVY096": return "false";
case "MST097": return "false";
case "EVR142": return "false";
case "MST098": return "false";
case "CRU129": return "false";
case "CRU130": return "false";
case "CRU131": return "false";
case "DYN123": return "false";
case "HVY208": return "false";
case "EVR076": return "false";
case "EVR077": return "false";
case "EVR078": return "false";
case "OUT231": return "false";
case "OUT232": return "false";
case "OUT233": return "false";
case "ARC011": return "false";
case "ARC012": return "false";
case "ARC013": return "false";
case "OUT107": return "false";
case "EVO079": return "false";
case "CRU122": return "false";
case "ROS201": return "false";
case "ROS202": return "false";
case "ROS203": return "false";
case "HVY225": return "false";
case "HVY226": return "false";
case "HVY227": return "false";
case "MST104": return "false";
case "MON091": return "false";
case "MON089": return "false";
case "EVR147": return "false";
case "EVR148": return "false";
case "EVR149": return "false";
case "DYN215": return "false";
case "MON095": return "false";
case "MON096": return "false";
case "MON097": return "false";
case "EVO244": return "false";
case "UPR101": return "false";
case "UPR048": return "false";
case "AUR011": return "false";
case "AUR024": return "false";
case "ROS204": return "false";
case "ROS205": return "false";
case "ROS206": return "false";
case "EVR167": return "false";
case "EVR168": return "false";
case "EVR169": return "false";
case "MST127": return "false";
case "MST128": return "false";
case "MST129": return "false";
case "EVR143": return "false";
case "MON171": return "false";
case "MON172": return "false";
case "MON173": return "false";
case "HVY089": return "false";
case "CRU127": return "false";
case "OUT000": return "false";
case "ROS247": return "false";
case "CRU101": return "false";
case "DYN093": return "false";
case "CRU105": return "false";
case "MON113": return "false";
case "MON114": return "false";
case "MON115": return "false";
case "ROS032": return "false";
case "ELE116": return "false";
case "ARC170": return "false";
case "ARC171": return "false";
case "ARC172": return "false";
case "DYN124": return "false";
case "DYN125": return "false";
case "DYN126": return "false";
case "OUT151": return "false";
case "OUT152": return "false";
case "OUT153": return "false";
case "AAZ020": return "false";
case "DYN168": return "false";
case "DYN169": return "false";
case "DYN170": return "false";
case "CRU124": return "false";
case "DTD231": return "false";
case "JDG035": return "false";
case "ELE166": return "false";
case "ELE167": return "false";
case "ELE168": return "false";
case "UPR122": return "false";
case "UPR123": return "false";
case "UPR124": return "false";
case "EVO078": return "false";
case "DYN244": return "false";
case "OUT237": return "false";
case "ROS237": return "false";
case "ROS176": return "false";
case "ROS177": return "false";
case "ROS178": return "false";
case "EVR185": return "false";
case "EVR186": return "false";
case "EVR187": return "false";
case "EVR184": return "false";
case "TCC073": return "false";
case "WTR171": return "false";
case "TCC082": return "false";
case "DYN056": return "false";
case "MST182": return "false";
case "TCC089": return "false";
case "DYN057": return "false";
case "MST183": return "false";
case "DYN058": return "false";
case "MST184": return "false";
case "HVY035": return "false";
case "HVY036": return "false";
case "AKO021": return "false";
case "HVY037": return "false";
case "MON278": return "false";
case "MON279": return "false";
case "MON280": return "false";
case "WTR085": return "false";
case "ARC014": return "false";
case "ARC015": return "false";
case "ARC016": return "false";
case "DYN094": return "false";
case "DTD053": return "false";
case "DYN076": return "false";
case "DYN077": return "false";
case "DYN078": return "false";
case "CRU013": return "false";
case "CRU014": return "false";
case "CRU015": return "false";
case "DYN062": return "false";
case "DYN063": return "false";
case "TCC097": return "false";
case "DYN064": return "false";
case "TCC101": return "false";
case "OUT188": return "false";
case "MST099": return "false";
case "HVY058": return "false";
case "WTR035": return "false";
case "WTR036": return "false";
case "WTR037": return "false";
case "MON002": return "false";
case "DTD002": return "false";
case "DTD001": return "false";
case "MON001": return "false";
case "EVO071": return "false";
case "MST193": return "false";
case "MON092": return "false";
case "MON093": return "false";
case "MON094": return "false";
case "HVY098": return "true";
case "JDG009": return "false";
case "JDG010": return "false";
case "JDG025": return "false";
case "JDG019": return "false";
case "JDG003": return "false";
case "JDG004": return "false";
case "TCC001": return "false";
case "DYN203": return "false";
case "DYN204": return "false";
case "DYN205": return "false";
case "CRU183": return "false";
case "CRU184": return "false";
case "CRU185": return "false";
case "EVO024": return "false";
case "TCC005": return "false";
case "EVO023": return "false";
case "TCC004": return "false";
case "EVO022": return "false";
case "TCC003": return "false";
case "EVO025": return "false";
case "TCC006": return "false";
case "OUT033": return "false";
case "OUT034": return "false";
case "OUT035": return "false";
case "EVR128": return "false";
case "EVR129": return "false";
case "EVR130": return "false";
case "AKO010": return "false";
case "MON223": return "false";
case "MON224": return "false";
case "MON225": return "false";
case "ELE113": return "false";
case "ELE114": return "false";
case "ELE112": return "false";
case "DYN090": return "false";
case "EVO058": return "false";
case "ROS018": return "false";
case "EVR021": return "false";
case "JDG012": return "false";
case "WTR206": return "false";
case "WTR207": return "false";
case "WTR208": return "false";
case "DYN079": return "false";
case "DYN080": return "false";
case "DYN081": return "false";
case "ARC161": return "false";
case "CRU094": return "false";
case "CRU095": return "false";
case "CRU096": return "false";
case "ARC188": return "false";
case "ARC189": return "false";
case "ARC190": return "false";
case "DTD161": return "false";
case "DTD162": return "false";
case "DTD163": return "false";
case "EVR131": return "false";
case "EVR132": return "false";
case "EVR133": return "false";
case "DYN059": return "false";
case "MST167": return "false";
case "TCC090": return "false";
case "DYN060": return "false";
case "MST168": return "false";
case "DYN061": return "false";
case "MST169": return "false";
case "EVO072": return "false";
case "UPR184": return "false";
case "UPR185": return "false";
case "UPR186": return "false";
case "ASB028": return "false";
case "CRU196": return "false";
case "DTD234": return "false";
case "DVR028": return "false";
case "EVO250": return "false";
case "EVR196": return "false";
case "TCC106": return "false";
case "WTR225": return "false";
case "EVO225": return "false";
case "EVO226": return "false";
case "EVO227": return "false";
case "DYN069": return "false";
case "DYN070": return "false";
case "TCC054": return "false";
case "OUT095": return "false";
case "OUT096": return "false";
case "DTD078": return "false";
case "DTD081": return "false";
case "DTD076": return "false";
case "DTD077": return "false";
case "DTD075": return "false";
case "ELE233": return "false";
case "MST132": return "false";
case "WTR188": return "false";
case "RVD019": return "false";
case "WTR189": return "false";
case "WTR190": return "false";
case "EVR090": return "false";
case "HVY105": return "true";
case "UPR033": return "false";
case "UPR034": return "false";
case "UPR035": return "false";
case "AKO011": return "false";
case "HVY228": return "false";
case "MON281": return "false";
case "HVY229": return "false";
case "MON282": return "false";
case "HVY230": return "false";
case "MON283": return "false";
case "RVD022": return "false";
case "DTD112": return "false";
case "DTD113": return "false";
case "DTD114": return "false";
case "ROS017": return "false";
case "ELE203": return "false";
case "ARC047": return "false";
case "UPR162": return "false";
case "UPR163": return "false";
case "UPR164": return "false";
case "EVO105": return "false";
case "EVO106": return "false";
case "EVO107": return "false";
case "CRU143": return "false";
case "MON221": return "false";
case "AAZ012": return "false";
case "ARC191": return "false";
case "OUT213": return "false";
case "ARC192": return "false";
case "OUT214": return "false";
case "ARC193": return "false";
case "OUT215": return "false";
case "HVY011": return "false";
case "HVY023": return "false";
case "HVY024": return "false";
case "HVY025": return "false";
case "MON087": return "false";
case "ASB002": return "false";
case "MON031": return "false";
case "WTR209": return "false";
case "WTR210": return "false";
case "WTR211": return "false";
case "OUT042": return "false";
case "OUT043": return "false";
case "OUT044": return "false";
case "EVO165": return "false";
case "EVO166": return "false";
case "EVO167": return "false";
case "EVO228": return "false";
case "EVO229": return "false";
case "EVO230": return "false";
case "AAZ021": return "false";
case "EVR100": return "false";
case "AAZ025": return "false";
case "EVR101": return "false";
case "EVR102": return "false";
case "UPR176": return "false";
case "UPR177": return "false";
case "UPR178": return "false";
case "ARC109": return "false";
case "ARC110": return "false";
case "ARC111": return "false";
case "EVR003": return "true";
case "DTD216": return "false";
case "CRU140": return "false";
case "UPR072": return "false";
case "UPR073": return "false";
case "UPR074": return "false";
case "HVY015": return "true";
case "WTR008": return "false";
case "OUT059": return "false";
case "OUT060": return "false";
case "OUT061": return "false";
case "UPR090": return "true";
case "CRU121": return "false";
case "AAZ013": return "true";
case "ARC043": return "true";
case "OUT011": return "false";
case "ARC088": return "false";
case "ARC089": return "false";
case "ARC090": return "false";
case "TER002": return "false";
case "EVR113": return "false";
case "EVR114": return "false";
case "EVR115": return "false";
case "HVY250": return "false";
case "WTR117": return "false";
case "DYN121": return "true";
case "ROS253": return "false";
case "WTR197": return "false";
case "WTR198": return "false";
case "WTR199": return "false";
case "DYN010": return "false";
case "DYN011": return "false";
case "AKO022": return "false";
case "DYN012": return "false";
case "DYN039": return "false";
case "DYN040": return "false";
case "DYN041": return "false";
case "CRU189": return "false";
case "HVY219": return "false";
case "CRU190": return "false";
case "HVY220": return "false";
case "CRU191": return "false";
case "HVY221": return "false";
case "ELE106": return "false";
case "ELE107": return "false";
case "ELE108": return "false";
case "EVR091": return "false";
case "EVR092": return "false";
case "EVR093": return "false";
case "WTR163": return "false";
case "CRU123": return "false";
case "DTD141": return "false";
case "ASB015": return "false";
case "DTD069": return "false";
case "DTD070": return "false";
case "DTD071": return "false";
case "MST133": return "false";
case "EVO183": return "false";
case "EVO184": return "false";
case "EVO185": return "false";
case "EVR106": return "false";
case "ARC138": return "false";
case "ARC139": return "false";
case "ARC140": return "false";
case "UPR169": return "false";
case "HVY004": return "false";
case "RVD001": return "false";
case "WTR002": return "false";
case "CRU001": return "false";
case "HVY003": return "false";
case "WTR001": return "false";
case "EVR044": return "false";
case "EVR045": return "false";
case "EVR046": return "false";
case "ARC063": return "false";
case "ARC064": return "false";
case "ARC065": return "false";
case "MON174": return "false";
case "MON175": return "false";
case "MON176": return "false";
case "DTD152": return "false";
case "DTD153": return "false";
case "DTD154": return "false";
case "MON177": return "false";
case "MON178": return "false";
case "MON179": return "false";
case "ARC194": return "false";
case "ARC195": return "false";
case "ARC196": return "false";
case "CRU027": return "false";
case "CRU016": return "false";
case "CRU017": return "false";
case "AKO023": return "false";
case "CRU018": return "false";
case "MON180": return "false";
case "MON181": return "false";
case "MON182": return "false";
case "HVY209": return "false";
case "OUT092": return "false";
case "OUT091": return "false";
case "MON257": return "false";
case "MON258": return "false";
case "MON259": return "false";
case "UPR091": return "true";
case "UPR057": return "false";
case "UPR058": return "false";
case "UPR059": return "false";
case "HVY183": return "false";
case "HVY184": return "false";
case "HVY185": return "false";
case "WTR104": return "false";
case "WTR105": return "false";
case "WTR106": return "false";
case "HVY146": return "false";
case "HVY147": return "false";
case "HVY148": return "false";
case "UPR075": return "false";
case "UPR076": return "false";
case "UPR077": return "false";
case "MON078": return "false";
case "MON079": return "false";
case "MON080": return "false";
case "HVY166": return "false";
case "HVY167": return "false";
case "HVY168": return "false";
case "MST100": return "false";
case "MST088": return "false";
case "ELE070": return "false";
case "ELE071": return "false";
case "ELE072": return "false";
case "ELE079": return "false";
case "ELE080": return "false";
case "ELE081": return "false";
case "DYN049": return "false";
case "DYN127": return "false";
case "DYN128": return "false";
case "DYN129": return "false";
case "ARC117": return "false";
case "CRU128": return "false";
case "DYN005": return "false";
case "EVR004": return "false";
case "CRU003": return "false";
case "WTR003": return "false";
case "UPR078": return "false";
case "UPR079": return "false";
case "UPR080": return "false";
case "ROS042": return "false";
case "ROS043": return "false";
case "ROS044": return "false";
case "ELE222": return "false";
case "ROS256": return "false";
case "EVR082": return "false";
case "EVR083": return "false";
case "EVR084": return "false";
case "ELE204": return "false";
case "TER003": return "false";
case "ROS003": return "false";
case "JDG021": return "false";
case "MON247": return "false";
case "CRU171": return "false";
case "CRU172": return "false";
case "CRU173": return "false";
case "WTR120": return "false";
case "MST191": return "false";
case "DTD199": return "false";
case "DYN022": return "false";
case "DYN023": return "false";
case "DYN024": return "false";
case "AKO024": return "false";
case "DVR013": return "false";
case "HVY161": return "false";
case "ELE236": return "false";
case "ROS215": return "false";
case "ARC100": return "false";
case "ARC101": return "false";
case "ARC102": return "false";
case "CRU144": return "false";
case "EVR107": return "false";
case "EVR108": return "false";
case "EVR109": return "false";
case "ARC112": return "false";
case "CRU157": return "false";
case "DTD214": return "false";
case "DYN191": return "false";
case "EVR119": return "false";
case "ROS162": return "false";
case "ROS116": return "false";
case "ROS149": return "false";
case "ROS150": return "false";
case "ROS151": return "false";
case "DYN185": return "false";
case "DYN186": return "false";
case "DYN187": return "false";
case "DTD213": return "false";
case "EVR104": return "false";
case "HVY156": return "false";
case "CRU060": return "false";
case "CRU061": return "false";
case "CRU062": return "false";
case "ARC163": return "false";
case "DYN142": return "false";
case "DYN143": return "false";
case "DYN144": return "false";
case "MST032": return "false";
case "MST053": return "false";
case "MST010": return "false";
case "CRU073": return "false";
case "TCC096": return "false";
case "ARC066": return "false";
case "ARC067": return "false";
case "ARC068": return "false";
case "ROS027": return "false";
case "UPR039": return "false";
case "UPR040": return "false";
case "UPR041": return "false";
case "WTR009": return "true";
case "DYN151": return "false";
case "DYN206": return "false";
case "DYN207": return "false";
case "DYN208": return "false";
case "UPR085": return "false";
case "AKO012": return "false";
case "DYN007": return "false";
case "WTR014": return "false";
case "WTR015": return "false";
case "WTR016": return "false";
case "AKO004": return "false";
case "WTR020": return "false";
case "WTR021": return "false";
case "WTR022": return "false";
case "ROS179": return "false";
case "ROS180": return "false";
case "ROS181": return "false";
case "ASB025": return "false";
case "WTR004": return "false";
case "ARC141": return "false";
case "ARC142": return "false";
case "ARC143": return "false";
case "OUT009": return "false";
case "OUT010": return "false";
case "UPR209": return "false";
case "WTR191": return "false";
case "UPR210": return "false";
case "WTR192": return "false";
case "UPR211": return "false";
case "WTR193": return "false";
case "DTD210": return "false";
case "EVR124": return "false";
case "WTR194": return "false";
case "WTR195": return "false";
case "WTR196": return "false";
case "AAZ022": return "false";
case "OUT225": return "false";
case "OUT226": return "false";
case "OUT227": return "false";
case "DTD200": return "false";
case "DYN095": return "false";
case "DYN096": return "false";
case "DYN097": return "false";
case "EVO129": return "false";
case "EVO130": return "false";
case "EVO131": return "false";
case "EVO132": return "false";
case "EVO133": return "false";
case "EVO134": return "false";
case "EVO108": return "false";
case "EVO109": return "false";
case "EVO110": return "false";
case "EVO135": return "false";
case "EVO136": return "false";
case "EVO137": return "false";
case "EVO101": return "false";
case "UPR046": return "false";
case "DTD097": return "false";
case "DTD098": return "false";
case "DTD099": return "false";
case "ARC069": return "false";
case "ARC070": return "false";
case "ARC071": return "false";
case "UPR099": return "false";
case "DYN026": return "false";
case "ROS101": return "false";
case "ROS102": return "false";
case "ROS103": return "false";
case "DVR010": return "false";
case "MON116": return "false";
case "MON117": return "false";
case "MON118": return "false";
case "MST081": return "false";
case "MST082": return "false";
case "MST083": return "false";
case "EVO080": return "false";
case "OUT036": return "false";
case "OUT037": return "false";
case "OUT038": return "false";
case "AAZ014": return "false";
case "OUT124": return "false";
case "OUT125": return "false";
case "OUT126": return "false";
case "HVY212": return "false";
case "MON183": return "false";
case "MON184": return "false";
case "MON185": return "false";
case "TER018": return "false";
case "TER025": return "false";
case "ROS035": return "false";
case "MON081": return "false";
case "MON082": return "false";
case "MON083": return "false";
case "MON251": return "false";
case "OUT216": return "false";
case "MON252": return "false";
case "OUT217": return "false";
case "MON253": return "false";
case "OUT218": return "false";
case "ELE215": return "false";
case "OUT176": return "false";
case "OUT175": return "false";
case "OUT178": return "false";
case "OUT177": return "false";
case "MON165": return "false";
case "MON166": return "false";
case "MON167": return "false";
case "DYN193": return "false";
case "EVR030": return "false";
case "EVR031": return "false";
case "EVR032": return "false";
case "CRU044": return "false";
case "DTD204": return "false";
case "ELE212": return "false";
case "EVR036": return "false";
case "WTR075": return "false";
case "DTD408": return "false";
case "DTD408": return "false";
case "UPR154": return "false";
case "HVY012": return "false";
case "ASB001": return "false";
case "DTD044": return "false";
case "MON029": return "false";
case "DTD108": return "false";
case "DTD118": return "false";
case "DTD119": return "false";
case "DTD120": return "false";
case "DTD124": return "false";
case "DTD125": return "false";
case "DTD126": return "false";
case "MON193": return "false";
case "MON125": return "false";
case "MON156": return "false";
case "MST236": return "false";
case "OUT013": return "true";
case "AAZ006": return "false";
case "JDG033": return "false";
case "DVR012": return "false";
case "WTR141": return "false";
case "WTR142": return "false";
case "WTR143": return "false";
case "EVR054": return "false";
case "HVY197": return "false";
case "DYN030": return "false";
case "DYN031": return "false";
case "DYN032": return "false";
case "DYN036": return "false";
case "DYN037": return "false";
case "DYN038": return "false";
case "HVY102": return "false";
case "MST052": return "false";
case "EVR140": return "false";
case "JDG026": return "false";
case "ELE033": return "false";
case "CRU097": return "false";
case "ELE173": return "false";
case "ELE195": return "false";
case "ELE196": return "false";
case "ELE197": return "false";
case "OUT154": return "false";
case "OUT155": return "false";
case "OUT156": return "false";
case "HVY013": return "true";
case "WTR047": return "true";
case "DYN130": return "false";
case "DYN131": return "false";
case "DYN132": return "false";
case "EVO235": return "false";
case "EVR116": return "false";
case "EVR117": return "false";
case "EVR118": return "false";
case "DTD165": return "false";
case "ARC072": return "false";
case "ARC073": return "false";
case "ARC074": return "false";
case "UPR197": return "false";
case "UPR198": return "false";
case "UPR199": return "false";
case "ROS168": return "false";
case "ROS022": return "true";
case "ROS088": return "false";
case "ROS226": return "false";
case "ROS161": return "false";
case "ROS070": return "false";
case "ROS210": return "false";
case "ROS230": return "false";
case "ROS113": return "false";
case "EVR122": return "false";
case "UPR106": return "false";
case "UPR107": return "false";
case "UPR108": return "false";
case "UPR218": return "false";
case "UPR219": return "false";
case "UPR220": return "false";
case "ROS045": return "false";
case "TER020": return "false";
case "TER026": return "false";
case "TCC059": return "false";
case "WTR173": return "false";
case "WTR174": return "false";
case "DVR025": return "false";
case "WTR175": return "false";
case "MST143": return "false";
case "MST144": return "false";
case "MST145": return "false";
case "ELE227": return "false";
case "ELE228": return "false";
case "ELE229": return "false";
case "ROS182": return "false";
case "ROS133": return "false";
case "EVR071": return "false";
case "UPR152": return "false";
case "UPR004": return "false";
case "OUT179": return "false";
case "DYN245": return "false";
case "EVR195": return "false";
case "EVR086": return "false";
case "ARC051": return "false";
case "ARC052": return "false";
case "ARC053": return "false";
case "OUT054": return "false";
case "UPR179": return "false";
case "UPR180": return "false";
case "UPR181": return "false";
case "ELE230": return "false";
case "ELE231": return "false";
case "ELE232": return "false";
case "WTR121": return "true";
case "MST146": return "false";
case "MST147": return "false";
case "MST148": return "false";
case "EVO010": return "true";
case "ASB016": return "false";
case "JDG020": return "false";
case "WTR215": return "false";
case "WTR216": return "false";
case "WTR217": return "false";
case "MST009": return "false";
case "AUR014": return "false";
case "AUR021": return "false";
case "UPR036": return "false";
case "UPR037": return "false";
case "UPR038": return "false";
case "DYN008": return "false";
case "EVR001": return "false";
case "ARC041": return "false";
case "CRU006": return "false";
case "DYN188": return "false";
case "DYN189": return "false";
case "DYN190": return "false";
case "MST072": return "false";
case "OUT127": return "false";
case "OUT128": return "false";
case "OUT129": return "false";
case "MST071": return "false";
case "MST073": return "false";
case "MST074": return "false";
case "HVY180": return "false";
case "EVO248": return "false";
case "DYN145": return "false";
case "DYN146": return "false";
case "DYN147": return "false";
case "CRU024": return "false";
case "AAZ015": return "false";
case "CRU132": return "false";
case "CRU133": return "false";
case "CRU134": return "false";
case "EVR057": return "false";
case "DVR018": return "false";
case "EVR058": return "false";
case "EVR059": return "false";
case "MST024": return "false";
case "DTD109": return "false";
case "WTR221": return "false";
case "WTR222": return "false";
case "WTR223": return "false";
case "HVY064": return "false";
case "TCC036": return "false";
case "WTR026": return "false";
case "RVD016": return "false";
case "WTR027": return "false";
case "AKO025": return "false";
case "WTR028": return "false";
case "ROS221": return "false";
case "EVO155": return "false";
case "MON226": return "false";
case "MON227": return "false";
case "RVD017": return "false";
case "MON228": return "false";
case "HVY044": return "false";
case "EVR170": return "false";
case "JDG018": return "false";
case "EVR171": return "false";
case "EVR172": return "false";
case "EVO237": return "false";
case "CRU182": return "false";
case "ELE041": return "false";
case "ELE042": return "false";
case "ELE043": return "false";
case "CRU174": return "false";
case "CRU175": return "false";
case "CRU176": return "false";
case "WTR154": return "false";
case "ASB012": return "false";
case "WTR167": return "false";
case "WTR168": return "false";
case "WTR169": return "false";
case "OUT018": return "false";
case "OUT019": return "false";
case "OUT020": return "false";
case "ELE022": return "false";
case "ELE023": return "false";
case "ELE024": return "false";
case "ROS117": return "false";
case "UPR081": return "false";
case "UPR082": return "false";
case "UPR083": return "false";
case "ASB004": return "false";
case "MST149": return "false";
case "MST150": return "false";
case "MST151": return "false";
case "MON231": return "false";
case "EVO242": return "true";
case "HVY251": return "false";
case "TCC064": return "false";
case "TCC065": return "false";
case "TCC069": return "false";
case "TCC066": return "false";
case "TCC067": return "false";
case "TCC068": return "false";
case "ARC119": return "false";
case "DTD181": return "false";
case "DTD182": return "false";
case "DTD183": return "false";
case "DTD184": return "false";
case "DTD185": return "false";
case "DTD186": return "false";
case "MON064": return "false";
case "MON198": return "true";
case "MON199": return "true";
case "MON186": return "false";
case "MON063": return "false";
case "CRU066": return "false";
case "CRU067": return "false";
case "CRU068": return "false";
case "DTD047": return "false";
case "EVO111": return "false";
case "EVO112": return "false";
case "EVO113": return "false";
case "ELE140": return "false";
case "ELE141": return "false";
case "ELE142": return "false";
case "AUR022": return "false";
case "AUR025": return "false";
case "ARC009": return "true";
case "MON101": return "false";
case "MON102": return "false";
case "MON103": return "false";
case "MST152": return "false";
case "MST153": return "false";
case "MST154": return "false";
case "DYN216": return "false";
case "DYN224": return "false";
case "DYN225": return "false";
case "DYN226": return "false";
case "DYN227": return "false";
case "DYN228": return "false";
case "DYN229": return "false";
case "DTD220": return "false";
case "DYN233": return "false";
case "EVR153": return "false";
case "MON104": return "false";
case "MST158": return "false";
case "DYN237": return "false";
case "DYN238": return "false";
case "AUR029": return "false";
case "DYN239": return "false";
case "TER030": return "false";
case "AUR028": return "false";
case "DYN236": return "false";
case "TER029": return "false";
case "DTD235": return "false";
case "DYN246": return "false";
case "ARC085": return "false";
case "ARC086": return "false";
case "ARC087": return "false";
case "ARC103": return "false";
case "ARC104": return "false";
case "ARC105": return "false";
case "ELE224": return "false";
case "UPR167": return "false";
case "MON212": return "false";
case "MON213": return "false";
case "MON214": return "false";
case "DYN115": return "false";
case "DYN116": return "false";
case "OUT004": return "false";
case "OUT104": return "true";
case "OUT021": return "false";
case "OUT022": return "false";
case "OUT023": return "false";
case "MON109": return "false";
case "MST089": return "false";
case "WTR044": return "false";
case "OUT062": return "false";
case "OUT063": return "false";
case "OUT064": return "false";
case "OUT130": return "false";
case "AAZ023": return "false";
case "OUT131": return "false";
case "AAZ028": return "false";
case "OUT132": return "false";
case "DYN066": return "false";
case "DTD052": return "false";
case "ROS243": return "false";
case "ROS121": return "false";
case "ROS122": return "false";
case "ROS123": return "false";
case "DTD106": return "false";
case "CRU084": return "false";
case "UPR049": return "false";
case "OUT014": return "false";
case "OUT219": return "false";
case "OUT220": return "false";
case "OUT221": return "false";
case "EVR039": return "true";
case "EVO150": return "false";
case "EVO151": return "false";
case "EVO152": return "false";
case "CRU187": return "false";
case "EVO192": return "false";
case "EVO193": return "false";
case "EVO194": return "false";
case "HER100": return "false";
case "OUT142": return "false";
case "HVY068": return "false";
case "HVY069": return "false";
case "HVY070": return "false";
case "HVY203": return "false";
case "ROS015": return "false";
case "EVR018": return "false";
case "CRU028": return "false";
case "HVY056": return "false";
case "HVY210": return "false";
case "AUR002": return "false";
case "ROS009": return "false";
case "DTD203": return "true";
case "HVY238": return "false";
case "EVO073": return "false";
case "AUR012": return "false";
case "AUR019": return "false";
case "WTR051": return "false";
case "WTR052": return "false";
case "WTR053": return "false";
case "EVR033": return "false";
case "EVR034": return "false";
case "EVR035": return "false";
case "AIO027": return "false";
case "EVO077": return "false";
case "EVO060": return "false";
case "EVO141": return "false";
case "WTR126": return "false";
case "WTR127": return "false";
case "WTR128": return "false";
case "WTR119": return "true";
case "DYN027": return "false";
case "ELE226": return "false";
case "ARC129": return "false";
case "ARC130": return "false";
case "ARC131": return "false";
case "MST101": return "false";
case "ELE082": return "false";
case "ELE083": return "false";
case "ELE084": return "false";
case "UPR100": return "false";
case "AAZ016": return "false";
case "WTR072": return "false";
case "WTR073": return "false";
case "WTR074": return "false";
case "MST190": return "false";
case "HVY052": return "false";
case "MON284": return "false";
case "MON285": return "false";
case "MON286": return "false";
case "ARC116": return "false";
case "UPR003": return "false";
case "UPR200": return "false";
case "UPR201": return "false";
case "UPR202": return "false";
case "AKO013": return "false";
case "ROS058": return "false";
case "ROS059": return "false";
case "ROS060": return "false";
case "ELE028": return "false";
case "ELE029": return "false";
case "ELE030": return "false";
case "MST050": return "false";
case "WTR138": return "false";
case "WTR139": return "false";
case "WTR140": return "false";
case "TER012": return "false";
case "TER016": return "false";
case "ROS064": return "false";
case "ROS065": return "false";
case "ROS066": return "false";
case "MON239": return "false";
case "ROS119": return "false";
case "UPR110": return "false";
case "UPR111": return "false";
case "UPR112": return "false";
case "ROS039": return "false";
case "ROS040": return "false";
case "ROS041": return "false";
case "ELE125": return "false";
case "ELE126": return "false";
case "ELE127": return "false";
case "ARC212": return "false";
case "ARC213": return "false";
case "ARC214": return "false";
case "MST227": return "false";
case "DTD405": return "false";
case "DTD405": return "false";
case "DYN612": return "false";
case "DYN612": return "false";
case "DYN612": return "false";
case "DYN192": return "false";
case "DYN122": return "false";
case "MON287": return "false";
case "MON288": return "false";
case "MON289": return "false";
case "OUT083": return "false";
case "WTR107": return "false";
case "OUT084": return "false";
case "WTR108": return "false";
case "OUT085": return "false";
case "WTR109": return "false";
case "CRU154": return "false";
case "CRU155": return "false";
case "CRU156": return "false";
case "ELE225": return "false";
case "EVR105": return "false";
case "UPR030": return "false";
case "UPR031": return "false";
case "UPR032": return "false";
case "DYN195": return "false";
case "EVR002": return "false";
case "CRU019": return "false";
case "CRU020": return "false";
case "CRU021": return "false";
case "AIO002": return "true";
case "EVO003": return "true";
case "EVO144": return "false";
case "EVO145": return "false";
case "EVR073": return "false";
case "EVR074": return "false";
case "EVR075": return "false";
case "JDG001": return "false";
case "JDG002": return "false";
case "ARC054": return "false";
case "ARC055": return "false";
case "ARC056": return "false";
case "ARC048": return "false";
case "ARC049": return "false";
case "ARC050": return "false";
case "ASB013": return "false";
case "MON054": return "false";
case "ASB022": return "false";
case "MON055": return "false";
case "MON056": return "false";
case "HVY160": return "false";
case "UPR161": return "false";
case "HVY112": return "false";
case "HVY113": return "false";
case "HVY114": return "false";
case "CRU177": return "false";
case "EVR188": return "false";
case "EVR189": return "false";
case "MON302": return "false";
case "EVR190": return "false";
case "EVR191": return "false";
case "EVR192": return "false";
case "EVR193": return "false";
case "ARC151": return "false";
case "HVY136": return "false";
case "AAZ004": return "false";
case "OUT108": return "false";
case "ELE205": return "false";
case "MON222": return "false";
case "DTD190": return "false";
case "DTD191": return "false";
case "DTD192": return "false";
case "DYN046": return "false";
case "TCC081": return "false";
case "WTR041": return "false";
case "EVO238": return "false";
case "EVO020": return "false";
case "EVO019": return "false";
case "EVO018": return "false";
case "EVO021": return "false";
case "TCC002": return "false";
case "ARC007": return "true";
case "ARC004": return "false";
case "EVO012": return "false";
case "EVO009": return "false";
case "ARC003": return "false";
case "CRU100": return "false";
case "EVR072": return "false";
case "EVO231": return "false";
case "EVO232": return "false";
case "EVO233": return "false";
case "EVO008": return "false";
case "CRU115": return "false";
case "CRU116": return "false";
case "CRU117": return "false";
case "EVO007": return "false";
case "EVO410": return "false";
case "EVO410": return "false";
case "DYN209": return "false";
case "DYN210": return "false";
case "DYN211": return "false";
case "ROS217": return "false";
case "HVY211": return "false";
case "EVO055": return "false";
case "TER001": return "false";
case "MST163": return "false";
case "HVY162": return "false";
case "HVY141": return "false";
case "HVY239": return "false";
case "HVY182": return "false";
case "UPR189": return "false";
case "UPR086": return "false";
case "HVY059": return "true";
case "MST102": return "false";
case "MST192": return "false";
case "UPR415": return "false";
case "UPR415": return "false";
case "DTD406": return "false";
case "DTD406": return "false";
case "JDG006": return "false";
case "JDG008": return "false";
case "EVR160": return "false";
case "ROS005": return "false";
case "OUT180": return "false";
case "MST033": return "false";
case "ARC044": return "false";
case "TER019": return "false";
case "AIO011": return "false";
case "ARC023": return "false";
case "TCC015": return "false";
case "ARC024": return "false";
case "TCC020": return "false";
case "ARC025": return "false";
case "TCC025": return "false";
case "DVR014": return "false";
case "ELE209": return "false";
case "ELE210": return "false";
case "ELE211": return "false";
case "EVR024": return "false";
case "EVR025": return "false";
case "EVR026": return "false";
case "HVY077": return "false";
case "HVY078": return "false";
case "HVY079": return "false";
case "EVO074": return "false";
case "HVY204": return "false";
case "MST090": return "false";
case "MST011": return "false";
case "MST012": return "false";
case "MST013": return "false";
case "UPR159": return "false";
case "TCC098": return "false";
case "TCC102": return "false";
case "MST063": return "false";
case "MST064": return "false";
case "MST065": return "false";
case "UPR158": return "false";
case "DYN047": return "false";
case "MST159": return "false";
case "MON240": return "false";
case "EVR134": return "false";
case "EVR135": return "false";
case "EVR136": return "false";
case "TCC074": return "false";
case "WTR172": return "false";
case "EVR097": return "false";
case "EVR098": return "false";
case "EVR099": return "false";
case "ELE202": return "false";
case "DVR027": return "false";
case "RVD027": return "false";
case "DYN217": return "false";
case "ARC122": return "false";
case "MON065": return "false";
case "UPR168": return "false";
case "UPR089": return "false";
case "WTR160": return "false";
case "ELE118": return "false";
case "EVO245": return "false";
case "MON194": return "false";
case "ARC084": return "false";
case "UPR407": return "false";
case "UPR407": return "false";
case "ROS222": return "false";
case "MST051": return "false";
case "EVO114": return "false";
case "EVO115": return "false";
case "EVO116": return "false";
case "CRU069": return "false";
case "CRU070": return "false";
case "CRU071": return "false";
case "DVR024": return "false";
case "CRU029": return "false";
case "CRU030": return "false";
case "CRU031": return "false";
case "OUT158": return "false";
case "OUT165": return "false";
case "OUT166": return "false";
case "OUT167": return "false";
case "HVY231": return "false";
case "UPR212": return "false";
case "HVY232": return "false";
case "UPR213": return "false";
case "HVY233": return "false";
case "UPR214": return "false";
case "ROS207": return "false";
case "ROS208": return "false";
case "ROS209": return "false";
case "DYN221": return "false";
case "DYN222": return "false";
case "DYN223": return "false";
case "UPR155": return "false";
case "UPR156": return "false";
case "UPR157": return "false";
case "MST066": return "false";
case "MON254": return "false";
case "MON255": return "false";
case "MON256": return "false";
case "OUT094": return "false";
case "EVR089": return "false";
case "DTD127": return "false";
case "DTD128": return "false";
case "DTD129": return "false";
case "DTD130": return "false";
case "DTD131": return "false";
case "DTD132": return "false";
case "ROS104": return "false";
case "ROS105": return "false";
case "ROS106": return "false";
case "CRU126": return "false";
case "HVY061": return "false";
case "ROS219": return "false";
case "MST029": return "false";
case "ELE010": return "false";
case "ELE011": return "false";
case "ELE012": return "false";
case "MST048": return "false";
case "EVO142": return "false";
case "EVR047": return "false";
case "OUT086": return "false";
case "EVR048": return "false";
case "OUT087": return "false";
case "EVR049": return "false";
case "OUT088": return "false";
case "ROS073": return "false";
case "CRU082": return "false";
case "EVO216": return "false";
case "TCC016": return "false";
case "EVO217": return "false";
case "EVO218": return "false";
case "MST007": return "false";
case "AJV013": return "true";
case "MON159": return "false";
case "MON160": return "false";
case "MON161": return "false";
case "CRU083": return "false";
case "DTD079": return "false";
case "WTR212": return "false";
case "WTR213": return "false";
case "WTR214": return "false";
case "MST078": return "false";
case "ROS248": return "false";
case "MST185": return "false";
case "MST186": return "false";
case "MST187": return "false";
case "MON150": return "false";
case "MON151": return "false";
case "MON152": return "false";
case "HVY103": return "true";
case "MST030": return "false";
case "UPR088": return "false";
case "DYN107": return "false";
case "DYN108": return "false";
case "DYN109": return "false";
case "MON220": return "false";
case "OUT002": return "false";
case "OUT001": return "false";
case "DTD060": return "false";
case "DTD061": return "false";
case "DTD062": return "false";
case "ASB023": return "true";
case "MON035": return "true";
case "EVR019": return "false";
case "MON107": return "false";
case "MON039": return "false";
case "ASB024": return "false";
case "MON040": return "false";
case "MON041": return "false";
case "OUT174": return "false";
case "ROS124": return "false";
case "ROS125": return "false";
case "ROS126": return "false";
case "DTD155": return "false";
case "DTD156": return "false";
case "DTD157": return "false";
case "DTD158": return "false";
case "DTD159": return "false";
case "DTD160": return "false";
case "ROS011": return "false";
case "EVR150": return "false";
case "EVR151": return "false";
case "EVR152": return "false";
case "ELE076": return "false";
case "ELE077": return "false";
case "ELE078": return "false";
case "MST155": return "false";
case "MST156": return "false";
case "MST157": return "false";
case "MST020": return "false";
case "MST021": return "false";
case "MST022": return "false";
case "ROS014": return "false";
case "ROS013": return "false";
case "ARC152": return "false";
case "MON060": return "false";
case "MON232": return "false";
case "MON233": return "false";
case "MON234": return "false";
case "EVR103": return "false";
case "HVY048": return "false";
case "HVY047": return "false";
case "DTD411": return "false";
case "DTD411": return "false";
case "HVY242": return "false";
case "TCC107": return "false";
case "HVY175": return "false";
case "ARC197": return "false";
case "ARC198": return "false";
case "ARC199": return "false";
case "HVY118": return "false";
case "HVY119": return "false";
case "HVY120": return "false";
case "HVY186": return "false";
case "HVY187": return "false";
case "HVY188": return "false";
case "DTD178": return "false";
case "DTD179": return "false";
case "DTD180": return "false";
case "UPR188": return "false";
case "OUT168": return "false";
case "OUT169": return "false";
case "OUT170": return "false";
case "ARC076": return "false";
case "ARC075": return "false";
case "CRU138": return "false";
case "MST225": return "true";
case "DVR022": return "false";
case "OUT055": return "true";
case "MST226": return "true";
case "DYN085": return "false";
case "DYN086": return "false";
case "DYN087": return "false";
case "CRU102": return "false";
case "MON209": return "false";
case "MON210": return "false";
case "MON211": return "false";
case "ELE034": return "false";
case "ARC147": return "false";
case "ARC148": return "false";
case "ARC149": return "false";
case "ROS021": return "false";
case "DTD134": return "false";
case "DTD133": return "false";
case "UPR416": return "false";
case "UPR416": return "false";
case "HVY169": return "false";
case "HVY170": return "false";
case "HVY171": return "false";
case "HVY216": return "false";
case "HVY217": return "false";
case "HVY218": return "false";
case "HVY149": return "false";
case "HVY150": return "false";
case "HVY151": return "false";
case "HVY189": return "false";
case "HVY190": return "false";
case "HVY191": return "false";
case "DTD115": return "false";
case "DTD116": return "false";
case "DTD117": return "false";
case "HVY142": return "false";
case "HVY080": return "false";
case "HVY081": return "false";
case "HVY082": return "false";
case "OUT053": return "true";
case "UPR165": return "false";
case "MST040": return "false";
case "MST041": return "false";
case "MST042": return "false";
case "EVO056": return "false";
case "EVO247": return "false";
case "DTD230": return "false";
case "MON299": return "false";
case "MON300": return "false";
case "MON301": return "false";
case "ASB006": return "false";
case "DVR011": return "false";
case "WTR129": return "false";
case "WTR130": return "false";
case "WTR131": return "false";
case "DTD029": return "false";
case "MON026": return "false";
case "DTD030": return "false";
case "MON027": return "false";
case "DTD031": return "false";
case "MON028": return "false";
case "MST091": return "false";
case "DYN230": return "false";
case "DYN231": return "false";
case "DYN232": return "false";
case "MST212": return "false";
case "MST213": return "false";
case "MST214": return "false";
case "DYN214": return "false";
case "MST069": return "false";
case "EVO239": return "false";
case "EVR050": return "false";
case "EVR051": return "false";
case "EVR052": return "false";
case "MST043": return "false";
case "MST044": return "false";
case "MST045": return "false";
case "OUT099": return "false";
case "ELE122": return "false";
case "ELE123": return "false";
case "ELE124": return "false";
case "ELE154": return "false";
case "ELE155": return "false";
case "ELE156": return "false";
case "ELE180": return "false";
case "ELE181": return "false";
case "ELE182": return "false";
case "ROS030": return "false";
case "WTR110": return "false";
case "WTR111": return "false";
case "WTR112": return "false";
case "CRU074": return "true";
case "ARC215": return "false";
case "ARC216": return "false";
case "ARC217": return "false";
case "MST085": return "false";
case "DTD137": return "false";
case "DTD138": return "false";
case "DTD139": return "false";
case "ROS240": return "false";
case "ROS241": return "false";
case "ROS239": return "false";
case "ROS242": return "false";
case "OUT133": return "false";
case "OUT134": return "false";
case "OUT135": return "false";
case "AKO014": return "false";
case "EVR011": return "false";
case "HVY038": return "false";
case "RVD012": return "false";
case "EVR012": return "false";
case "HVY039": return "false";
case "EVR013": return "false";
case "HVY040": return "false";
case "ROS000": return "false";
case "MST054": return "false";
case "MST055": return "false";
case "MST056": return "false";
case "EVR040": return "false";
case "ELE169": return "false";
case "ELE170": return "false";
case "ELE171": return "false";
case "ELE160": return "false";
case "ELE161": return "false";
case "ELE162": return "false";
case "ELE003": return "false";
case "OUT039": return "false";
case "OUT040": return "false";
case "OUT041": return "false";
case "OUT136": return "false";
case "OUT137": return "false";
case "OUT138": return "false";
case "DYN042": return "false";
case "DYN043": return "false";
case "DYN044": return "false";
case "JDG014": return "false";
case "WTR200": return "false";
case "RVD020": return "false";
case "WTR201": return "false";
case "WTR202": return "false";
case "MST215": return "false";
case "WTR203": return "false";
case "MST216": return "false";
case "WTR204": return "false";
case "MST217": return "false";
case "WTR205": return "false";
case "AKO015": return "false";
case "OUT198": return "false";
case "OUT199": return "false";
case "OUT200": return "false";
case "WTR029": return "false";
case "WTR030": return "false";
case "AKO026": return "false";
case "RVD023": return "false";
case "WTR031": return "false";
case "RVD013": return "false";
case "MON129": return "false";
case "MON130": return "false";
case "MON131": return "false";
case "UPR417": return "false";
case "UPR417": return "false";
case "MON290": return "false";
case "MON291": return "false";
case "MON292": return "false";
case "DYN025": return "false";
case "ARC144": return "false";
case "ARC145": return "false";
case "ARC146": return "false";
case "JDG028": return "false";
case "MON293": return "false";
case "MON294": return "false";
case "MON295": return "false";
case "MST047": return "false";
case "CRU075": return "false";
case "MST189": return "false";
case "MST046": return "false";
case "CRU051": return "false";
case "CRU052": return "false";
case "EVO162": return "false";
case "EVO163": return "false";
case "EVO164": return "false";
case "AIO012": return "false";
case "ARC026": return "false";
case "TCC017": return "false";
case "AIO018": return "false";
case "ARC027": return "false";
case "AIO023": return "false";
case "ARC028": return "false";
case "AIO013": return "false";
case "ARC029": return "false";
case "TCC018": return "false";
case "AIO019": return "false";
case "ARC030": return "false";
case "TCC021": return "false";
case "AIO024": return "false";
case "ARC031": return "false";
case "EVR079": return "false";
case "EVR080": return "false";
case "EVR081": return "false";
default: return "";}
}

?>