<?php
function GeneratedCardType($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "D":
switch($cardID[1]) {
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "7":
return "E";
case "8":
return "E";
case "9":
return "E";
case "3":
return "T";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "4":
return "E";
case "5":
return "E";
case "6":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "5":
return "T";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "T";
case "4":
return "T";
case "6":
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
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "E";
case "4":
return "E";
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
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "C";
case "4":
return "C";
case "5":
return "W";
case "6":
return "W";
case "7":
return "E";
case "8":
return "E";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "1":
return "T";
case "0":
return "A";
case "2":
return "W";
case "3":
return "W";
case "4":
return "A";
case "5":
return "A";
case "6":
return "I";
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
case "4":
switch($cardID[5]) {
case "8":
return "AR";
case "9":
return "AR";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "AR";
case "1":
return "W";
case "2":
return "E";
case "5":
return "A";
case "9":
return "A";
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
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "E";
case "2":
return "W";
case "4":
return "A";
case "5":
return "A";
case "9":
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
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "C";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "W";
case "6":
return "E";
case "9":
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
case "2":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "C";
case "6":
return "E";
case "7":
return "E";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "DR";
case "1":
return "DR";
case "2":
return "DR";
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
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
case "4":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "I";
case "3":
return "I";
case "4":
return "I";
case "5":
return "E";
case "6":
return "E";
case "8":
return "A";
case "9":
return "A";
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
case "6":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "6":
return "A";
case "7":
return "W";
case "8":
return "W";
case "9":
return "W";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "A";
case "2":
return "I";
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
return "AR";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "0":
return "AR";
case "1":
return "AR";
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
return "W";
case "9":
return "E";
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
case "4":
return "A";
case "8":
return "A";
case "9":
return "A";
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
case "0":
switch($cardID[5]) {
case "1":
return "C";
case "2":
return "W";
case "4":
return "E";
case "7":
return "M";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "3":
return "AR";
case "4":
return "AR";
case "9":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "AR";
case "4":
return "DR";
case "7":
return "R";
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
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "C";
case "2":
return "C";
case "3":
return "W";
case "4":
return "E";
case "5":
return "E";
case "7":
return "A";
case "8":
return "DR";
case "9":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "I";
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
return "C";
case "9":
return "C";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "E";
case "2":
return "E";
case "6":
return "A";
case "7":
return "A";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "1":
return "DR";
case "2":
return "DR";
case "3":
return "DR";
case "4":
return "A";
case "5":
return "A";
case "6":
return "A";
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
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "T";
case "6":
return "C";
case "7":
return "C";
case "8":
return "W";
case "9":
return "E";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "0":
return "E";
case "2":
return "AR";
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
case "1":
switch($cardID[5]) {
case "3":
return "C";
case "4":
return "C";
case "5":
return "W";
case "6":
return "E";
case "7":
return "E";
case "8":
return "AR";
case "9":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "AR";
case "1":
return "AR";
case "2":
return "A";
case "3":
return "AR";
case "4":
return "AR";
case "5":
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
case "3":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "AR";
case "3":
return "AR";
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
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "AR";
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
case "5":
switch($cardID[5]) {
case "0":
return "E";
case "1":
return "E";
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
case "7":
return "E";
case "8":
return "E";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
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
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "0":
return "AR";
case "1":
return "AR";
case "2":
return "DR";
case "3":
return "DR";
case "4":
return "DR";
case "5":
return "DR";
case "6":
return "DR";
case "7":
return "DR";
case "8":
return "A";
case "9":
return "A";
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
case "4":
return "R";
case "5":
return "T";
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "C";
case "3":
return "W";
case "0":
return "R";
case "2":
return "C";
case "4":
return "W";
case "5":
return "W";
case "6":
return "E";
case "9":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "2":
return "C";
case "3":
return "W";
case "4":
return "W";
case "5":
return "E";
case "8":
return "A";
case "9":
return "A";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "4":
return "T";
case "5":
return "C";
case "8":
return "W";
case "9":
return "W";
case "0":
return "A";
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "6":
return "C";
case "7":
return "C";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "6":
return "C";
case "8":
return "W";
case "5":
return "T";
case "7":
return "C";
case "9":
return "W";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "8":
return "C";
case "0":
return "AR";
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
return "C";
case "9":
return "C";
default: return "AA";
}
case "3":
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
case "5":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "W";
case "2":
return "W";
case "3":
return "E";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "E";
case "2":
return "AR";
case "3":
return "AR";
case "4":
return "A";
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
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "8":
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
case "7":
return "W";
case "9":
return "E";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "5":
return "R";
case "6":
return "T";
case "0":
return "I";
case "1":
return "I";
case "7":
return "T";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "W";
case "2":
return "E";
case "4":
return "A";
case "5":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "9":
return "C";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "C";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "W";
case "2":
return "E";
case "4":
return "A";
case "5":
return "I";
case "6":
return "DR";
case "7":
return "DR";
case "8":
return "DR";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "8":
return "C";
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
case "5":
switch($cardID[5]) {
case "7":
return "T";
case "8":
return "C";
case "9":
return "W";
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
case "0":
return "W";
case "1":
return "E";
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
case "6":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "E";
case "2":
return "A";
case "3":
return "A";
case "4":
return "I";
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
case "8":
switch($cardID[5]) {
case "1":
return "A";
case "2":
return "I";
case "6":
return "AR";
case "7":
return "DR";
case "8":
return "A";
case "9":
return "I";
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
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "E";
case "4":
return "E";
case "5":
return "A";
case "9":
return "A";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "7":
return "R";
case "3":
return "E";
case "4":
return "E";
case "5":
return "E";
case "6":
return "E";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "W";
case "3":
return "E";
case "4":
return "E";
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
case "2":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "W";
case "3":
return "W";
case "4":
return "E";
case "5":
return "E";
case "6":
return "A";
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
case "0":
return "A";
case "1":
return "C";
case "2":
return "C";
case "3":
return "W";
case "6":
return "I";
case "7":
return "DR";
case "8":
return "DR";
case "9":
return "DR";
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
case "3":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "C";
case "2":
return "C";
case "3":
return "W";
case "4":
return "W";
case "7":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "2":
return "C";
case "3":
return "C";
case "5":
return "A";
case "6":
return "A";
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
case "9":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "I";
default: return "AA";
}
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "0":
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
return "T";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "T";
case "2":
return "I";
case "3":
return "A";
case "4":
return "DR";
case "5":
return "E";
case "6":
return "E";
case "7":
return "A";
case "8":
return "A";
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
return "I";
case "6":
return "I";
case "7":
return "I";
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
return "E";
case "5":
return "E";
case "6":
return "A";
case "7":
return "I";
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
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "E";
case "4":
return "E";
case "5":
return "A";
case "6":
return "I";
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
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
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
case "3":
switch($cardID[5]) {
case "6":
return "T";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
case "7":
return "E";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "E";
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
case "5":
switch($cardID[5]) {
case "0":
return "DR";
case "1":
return "DR";
case "2":
return "DR";
case "3":
return "E";
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
return "A";
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
case "5":
return "C";
case "6":
return "E";
case "7":
return "W";
case "9":
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
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return "T";
case "4":
return "T";
case "7":
return "T";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "5":
return "T";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "9":
return "T";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "4":
return "E";
case "3":
return "T";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "5":
return "E";
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
case "3":
return "E";
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
case "0":
return "C";
case "1":
return "W";
case "2":
return "DR";
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
return "E";
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
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
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
case "7":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
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
case "4":
switch($cardID[5]) {
case "5":
return "C";
case "6":
return "C";
case "7":
return "C";
case "2":
return "AR";
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
case "9":
switch($cardID[5]) {
case "0":
return "C";
case "3":
return "W";
case "6":
return "E";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "1":
return "C";
case "2":
return "C";
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
case "9":
return "W";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "E";
case "4":
return "AR";
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
case "0":
switch($cardID[5]) {
case "3":
return "DR";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "9":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "1":
return "E";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "4":
return "AR";
case "8":
return "E";
case "9":
return "A";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "A";
case "5":
return "A";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
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
case "2":
switch($cardID[4]) {
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
case "0":
switch($cardID[5]) {
case "0":
return "DR";
case "1":
return "DR";
case "2":
return "DR";
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "C";
case "2":
return "C";
case "3":
return "W";
case "4":
return "E";
case "5":
return "E";
case "6":
return "A";
case "7":
return "A";
case "9":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
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
return "C";
case "9":
return "C";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "W";
case "1":
return "E";
case "2":
return "E";
case "4":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "DR";
case "9":
return "DR";
default: return "AA";
}
case "5":
switch($cardID[5]) {
case "0":
return "DR";
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
case "7":
switch($cardID[5]) {
case "5":
return "C";
case "6":
return "C";
case "7":
return "W";
case "8":
return "E";
case "9":
return "E";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "1":
return "A";
case "3":
return "A";
case "4":
return "A";
case "8":
return "DR";
case "9":
return "DR";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "DR";
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
case "1":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
case "2":
return "T";
case "3":
return "C";
case "4":
return "C";
case "5":
return "W";
case "6":
return "E";
case "7":
return "E";
case "8":
return "A";
case "9":
return "A";
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
case "5":
switch($cardID[5]) {
case "0":
return "E";
case "1":
return "E";
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
case "7":
return "E";
case "8":
return "E";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "0":
return "I";
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
return "I";
case "4":
return "I";
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
default: return "AA";
}
case "M":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "R";
case "0":
return "A";
case "1":
return "A";
case "2":
return "A";
case "3":
return "I";
case "4":
return "I";
case "5":
return "I";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "C";
case "2":
return "C";
case "3":
return "W";
case "5":
return "I";
case "6":
return "I";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "9":
return "C";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "C";
case "1":
return "W";
case "3":
return "AR";
case "4":
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
case "6":
switch($cardID[5]) {
case "0":
return "E";
case "1":
return "E";
case "3":
return "DR";
case "4":
return "A";
case "5":
return "I";
case "9":
return "I";
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
case "8":
switch($cardID[5]) {
case "1":
return "A";
case "2":
return "A";
case "3":
return "A";
case "4":
return "I";
case "5":
return "I";
case "6":
return "I";
case "7":
return "I";
case "8":
return "W";
case "9":
return "E";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "0":
return "E";
case "2":
return "I";
case "3":
return "I";
case "4":
return "I";
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
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "T";
case "5":
return "W";
case "6":
return "W";
case "7":
return "E";
case "8":
return "E";
case "9":
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
case "8":
return "A";
case "9":
return "C";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "C";
case "1":
return "W";
case "2":
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
return "C";
case "4":
return "C";
case "5":
return "W";
case "7":
return "A";
case "8":
return "I";
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
case "8":
switch($cardID[5]) {
case "3":
return "A";
case "4":
return "A";
case "5":
return "A";
case "6":
return "T";
case "7":
return "E";
case "8":
return "E";
case "9":
return "I";
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
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
case "5":
return "I";
case "6":
return "I";
case "7":
return "I";
case "8":
return "I";
case "9":
return "T";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "W";
case "2":
return "A";
case "9":
return "W";
default: return "AA";
}
case "3":
switch($cardID[5]) {
case "0":
return "E";
case "1":
return "A";
case "8":
return "E";
case "9":
return "E";
default: return "AA";
}
case "4":
switch($cardID[5]) {
case "0":
return "E";
case "1":
return "E";
case "2":
return "E";
case "3":
return "E";
case "4":
return "E";
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
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return "R";
case "0":
return "A";
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
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
default: return "AA";
}
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "E";
case "2":
return "E";
case "4":
return "I";
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "8":
return "E";
case "9":
return "E";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "3":
return "C";
case "2":
return "C";
case "4":
return "A";
case "5":
return "A";
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
case "8":
return "A";
case "9":
return "A";
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
case "4":
return "A";
case "5":
return "E";
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
return "E";
case "7":
return "E";
case "8":
return "A";
case "9":
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
case "6":
switch($cardID[5]) {
case "2":
return "AR";
case "3":
return "AR";
case "4":
return "AR";
case "5":
return "W";
case "6":
return "E";
case "7":
return "E";
case "8":
return "A";
case "9":
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
case "8":
switch($cardID[5]) {
case "0":
return "A";
case "1":
return "A";
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
case "9":
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
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "C";
case "2":
return "C";
case "3":
return "W";
case "4":
return "E";
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
case "4":
switch($cardID[5]) {
case "0":
return "I";
case "1":
return "I";
case "2":
return "T";
case "3":
return "T";
case "4":
return "C";
case "5":
return "C";
case "6":
return "W";
case "7":
return "E";
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
case "5":
return "E";
case "7":
return "AR";
case "8":
return "A";
case "9":
return "I";
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
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
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
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "7":
return "R";
case "5":
return "A";
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
default: return "AA";
}
default: return "AA";
}
}

function GeneratedAttackValue($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
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
case "3":
return 4;
case "6":
return 9;
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
case "4":
switch($cardID[5]) {
case "0":
return 4;
case "3":
return 11;
case "4":
return 9;
case "5":
return 8;
case "8":
return 9;
case "9":
return 8;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 7;
case "7":
return 8;
case "8":
return 7;
case "9":
return 6;
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
case "8":
switch($cardID[5]) {
case "1":
return 2;
case "3":
return 4;
case "4":
return 4;
case "5":
return 5;
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
case "1":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
case "2":
return 1;
case "5":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "9":
return 5;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "1":
return 4;
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
case "9":
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
return 3;
case "5":
return 2;
case "6":
return 1;
case "7":
return 6;
case "8":
return 5;
case "9":
return 4;
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 4;
case "4":
return 3;
case "5":
return 3;
case "7":
return 6;
case "8":
return 6;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 4;
case "0":
return 3;
case "1":
return 2;
case "4":
return 6;
case "6":
return 8;
case "7":
return 10;
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
case "7":
switch($cardID[5]) {
case "8":
return 3;
case "0":
return 4;
case "1":
return 3;
case "2":
return 3;
case "3":
return 2;
case "4":
return 2;
case "9":
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
case "3":
switch($cardID[5]) {
case "2":
return 7;
case "3":
return 6;
case "4":
return 5;
case "5":
return 8;
case "6":
return 7;
case "7":
return 6;
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
case "4":
return 2;
case "5":
return 1;
case "6":
return 3;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
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
case "8":
switch($cardID[5]) {
case "0":
return 2;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return X;
case "3":
return 4;
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
case "2":
switch($cardID[5]) {
case "3":
return 5;
case "9":
return 4;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 4;
case "8":
return 4;
case "9":
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
case "7":
switch($cardID[5]) {
case "7":
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
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return 4;
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
case "0":
switch($cardID[4]) {
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
case "4":
switch($cardID[5]) {
case "2":
return 1;
case "6":
return 3;
case "8":
return 3;
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
case "9":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
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
case "9":
return 3;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 0;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return 13;
default: return 0;
}
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 2;
case "8":
return 10;
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
case "3":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return 5;
case "5":
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
case "7":
switch($cardID[5]) {
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
case "7":
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
default: return 0;
}
case "1":
switch($cardID[4]) {
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
case "5":
switch($cardID[5]) {
case "9":
return 6;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "1":
return 4;
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
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
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
case "1":
switch($cardID[5]) {
case "0":
return 7;
case "1":
return 6;
case "2":
return 5;
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
case "2":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "7":
return 2;
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
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "5":
return 0;
case "7":
return 3;
case "8":
return 3;
case "9":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "8":
return 5;
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
default: return 0;
}
case "1":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
case "9":
return 4;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "2":
return 4;
case "4":
return 4;
case "5":
return 3;
case "6":
return 2;
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
case "5":
switch($cardID[5]) {
case "3":
return 5;
case "4":
return 4;
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
case "0":
switch($cardID[4]) {
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
case "0":
return 5;
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
default: return 0;
}
case "3":
switch($cardID[5]) {
case "1":
return 0;
case "2":
return 7;
case "5":
return 3;
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
case "4":
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
return 3;
case "9":
return 2;
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
case "9":
switch($cardID[5]) {
case "1":
return 9;
case "8":
return 8;
case "9":
return 7;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
case "2":
return 4;
case "3":
return 3;
case "5":
return 2;
case "6":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "1":
return 6;
case "3":
return 6;
case "4":
return 6;
case "5":
return 6;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
case "9":
return 6;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 5;
case "1":
return 4;
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
case "6":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 2;
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
default: return 0;
}
case "2":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "9":
return 6;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 3;
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
return 3;
default: return 0;
}
case "3":
switch($cardID[5]) {
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
default: return 0;
}
case "4":
switch($cardID[5]) {
case "5":
return 4;
case "6":
return 6;
case "7":
return 0;
case "8":
return 6;
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
default: return 0;
}
default: return 0;
}
default: return 0;
}
case "E":
switch($cardID[1]) {
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 4;
case "4":
return 8;
case "5":
return 7;
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
case "3":
switch($cardID[5]) {
case "5":
return 3;
case "6":
return 4;
case "8":
return 5;
case "9":
return 4;
default: return 0;
}
case "4":
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
case "6":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "4":
return 6;
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
return 5;
case "3":
return 4;
case "4":
return 3;
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
default: return 0;
}
case "1":
switch($cardID[4]) {
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
case "2":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
case "8":
return 7;
case "9":
return 6;
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
case "9":
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
case "5":
return 5;
case "6":
return 4;
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
case "2":
return 3;
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
case "6":
return 4;
case "7":
return 3;
case "8":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "3":
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
return 8;
case "8":
return 6;
case "9":
return 5;
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
case "2":
switch($cardID[5]) {
case "1":
return 14;
case "4":
return 10;
case "5":
return 9;
case "6":
return 8;
case "7":
return 10;
case "8":
return 9;
case "9":
return 8;
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
case "4":
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
case "7":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 2;
case "5":
return 1;
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
case "9":
return 5;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 3;
case "8":
return 6;
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
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return 7;
case "5":
return 3;
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
case "3":
switch($cardID[5]) {
case "9":
return 7;
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
case "5":
switch($cardID[5]) {
case "6":
return 5;
case "7":
return 3;
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
case "2":
return 4;
case "9":
return 6;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 6;
case "5":
return 6;
case "8":
return 6;
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
case "0":
switch($cardID[4]) {
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
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "2":
return 3;
case "3":
return 6;
case "5":
return 3;
case "6":
return 2;
case "7":
return 1;
case "8":
return 3;
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
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 3;
case "3":
return 3;
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
case "4":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
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
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 5;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return 5;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "2":
return 2;
case "5":
return 4;
case "8":
return 3;
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
default: return 0;
}
case "8":
switch($cardID[5]) {
case "3":
return 6;
case "6":
return 7;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "5":
return 4;
case "8":
return 6;
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
case "4":
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
default: return 0;
}
}

function GeneratedBlockValue($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
case "D":
switch($cardID[1]) {
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
case "3":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "4":
return -1;
case "5":
return -1;
case "6":
return 0;
default: return 3;
}
case "4":
switch($cardID[5]) {
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
case "6":
return -1;
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
case "9":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "3":
return -1;
case "4":
return -1;
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
case "1":
switch($cardID[4]) {
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
return 1;
case "8":
return 1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "1":
return -1;
case "0":
return 2;
case "2":
return -1;
case "3":
return -1;
case "6":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "1":
return -1;
case "2":
return 1;
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
case "1":
return 1;
case "2":
return -1;
case "4":
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
case "1":
return -1;
case "5":
return -1;
case "6":
return 1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "5":
return -1;
case "7":
return 2;
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
case "4":
switch($cardID[5]) {
case "5":
return 1;
case "6":
return 1;
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
case "6":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
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
case "7":
switch($cardID[5]) {
case "0":
return -1;
case "2":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "8":
return -1;
case "9":
return 1;
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
case "0":
switch($cardID[5]) {
case "1":
return -1;
case "2":
return -1;
case "4":
return 0;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "4":
return 2;
case "9":
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
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "4":
return 2;
case "5":
return 1;
case "8":
return 4;
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
case "1":
return 2;
case "2":
return 1;
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
case "7":
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
return 2;
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
default: return 3;
}
case "1":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return 2;
case "7":
return 1;
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
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 0;
case "2":
return 0;
case "3":
return 0;
case "4":
return 0;
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "2":
return -1;
case "3":
return -1;
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
case "0":
return 2;
case "1":
return 2;
case "2":
return 7;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
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
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return -1;
case "5":
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return -1;
case "3":
return -1;
case "0":
return -1;
case "2":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return 0;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return 2;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "8":
return -1;
case "9":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "6":
return -1;
case "7":
return -1;
default: return 3;
}
case "7":
switch($cardID[5]) {
case "6":
return -1;
case "8":
return -1;
case "2":
return 2;
case "4":
return 2;
case "5":
return -1;
case "7":
return -1;
case "9":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "8":
return -1;
case "7":
return -1;
case "9":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return 1;
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
default: return 3;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "8":
return 1;
case "7":
return -1;
case "9":
return 0;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "0":
return -1;
case "1":
return -1;
case "7":
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
return 0;
case "4":
return -1;
case "5":
return -1;
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
case "2":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return 2;
case "4":
return 2;
case "5":
return -1;
case "6":
return 4;
case "8":
return 2;
default: return 3;
}
case "3":
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
default: return 3;
}
case "5":
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
default: return 3;
}
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "1":
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
case "6":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return 0;
case "4":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return -1;
case "2":
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
case "9":
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
case "E":
switch($cardID[1]) {
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return 2;
case "4":
return 0;
case "5":
return 2;
case "9":
return 2;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "7":
return -1;
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
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return -1;
case "2":
return -1;
case "3":
return 0;
case "4":
return 1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return -1;
case "3":
return -1;
case "4":
return 1;
case "5":
return 0;
case "6":
return 2;
case "8":
return 2;
case "9":
return 1;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "6":
return -1;
case "7":
return 4;
case "9":
return 2;
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
case "7":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "5":
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
return -1;
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
case "4":
return 6;
case "5":
return 0;
case "6":
return 0;
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
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
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
case "4":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return -1;
case "4":
return 1;
case "5":
return 0;
case "7":
return -1;
case "8":
return 2;
case "9":
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
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return -1;
case "3":
return 0;
case "4":
return 0;
case "6":
return -1;
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
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return 0;
case "7":
return 0;
case "8":
return 0;
case "9":
return 2;
default: return 3;
}
case "9":
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
case "3":
switch($cardID[5]) {
case "6":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "7":
return 2;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return 1;
case "8":
return -1;
case "9":
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
case "7":
return -1;
case "8":
return 2;
case "9":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
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
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 1;
case "3":
return 1;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "9":
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
case "8":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return 2;
case "7":
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
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return -1;
case "4":
return -1;
case "7":
return -1;
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
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "4":
return 1;
case "3":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "5":
return -1;
case "8":
return 2;
case "9":
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
case "3":
return 0;
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
return -1;
case "1":
return -1;
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
case "7":
return 0;
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
default: return 3;
}
case "6":
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
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
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
case "4":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "9":
return 1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return -1;
case "6":
return -1;
default: return 3;
}
case "0":
switch($cardID[5]) {
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
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return 1;
case "8":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "2":
return 2;
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
case "1":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "9":
return 1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "1":
return 1;
case "5":
return 2;
case "8":
return 2;
default: return 3;
}
case "5":
switch($cardID[5]) {
case "4":
return 2;
case "8":
return 1;
case "9":
return 2;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "8":
return 2;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "6":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "8":
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
return 2;
case "4":
return 2;
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
case "2":
switch($cardID[4]) {
case "0":
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
case "5":
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
case "2":
switch($cardID[5]) {
case "4":
return -1;
case "0":
return 2;
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
case "5":
return -1;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return 0;
case "2":
return 0;
case "3":
return -1;
case "4":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return 0;
default: return 3;
}
case "0":
switch($cardID[5]) {
case "3":
return -1;
case "1":
return -1;
case "2":
return -1;
case "6":
return 4;
case "8":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "5":
return 0;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 0;
case "8":
return 2;
case "9":
return 2;
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
case "0":
return 2;
case "5":
return -1;
case "6":
return 0;
case "7":
return 0;
case "8":
return 2;
case "9":
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
default: return 3;
}
case "8":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 0;
case "4":
return 0;
case "5":
return 0;
case "6":
return 0;
case "7":
return 2;
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
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
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
return 0;
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
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return -1;
default: return 3;
}
case "4":
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
return 0;
case "9":
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
return 0;
case "6":
return 2;
case "7":
return 2;
case "9":
return -1;
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
case "0":
switch($cardID[4]) {
case "0":
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
return 2;
case "5":
return 0;
case "7":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
case "7":
return -1;
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
case "3":
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
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return 1;
case "2":
return 0;
case "4":
return 2;
case "7":
return 2;
case "8":
return 4;
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
default: return 3;
}
case "7":
switch($cardID[5]) {
case "5":
return -1;
case "6":
return -1;
case "7":
return -1;
case "8":
return 2;
case "9":
return 0;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "4":
return 2;
case "8":
return 4;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
default: return 3;
}
default: return 3;
}
case "1":
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
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "6":
return 0;
case "7":
return 0;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 4;
case "5":
return 2;
case "9":
return 2;
default: return 3;
}
case "3":
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
default: return 3;
}
case "5":
switch($cardID[5]) {
case "0":
return 1;
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
case "8":
return 0;
default: return 3;
}
case "6":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return 2;
case "2":
return 2;
case "3":
return -1;
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
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
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
default: return 3;
}
default: return 3;
}
default: return 3;
}
case "M":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return -1;
case "0":
return 2;
case "1":
return 2;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "0":
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
default: return 3;
}
case "1":
switch($cardID[5]) {
case "1":
return -1;
case "2":
return -1;
case "3":
return -1;
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
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 0;
case "3":
return 6;
case "4":
return 2;
case "5":
return -1;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
return -1;
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
case "8":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
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
return 0;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return 0;
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
default: return 3;
}
default: return 3;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return -1;
case "5":
return -1;
case "6":
return -1;
case "7":
return 1;
case "8":
return 1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "9":
return -1;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "0":
return -1;
case "1":
return -1;
case "2":
return 1;
case "3":
return -1;
case "5":
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
case "5":
switch($cardID[5]) {
case "3":
return -1;
case "4":
return -1;
case "5":
return -1;
case "8":
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
case "8":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "6":
return -1;
case "7":
return 6;
case "8":
return 0;
case "9":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "0":
return -1;
case "2":
return 6;
case "3":
return 2;
case "4":
return -1;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
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
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
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
case "2":
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
case "8":
return -1;
case "9":
return -1;
default: return 3;
}
case "3":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 2;
case "8":
return 0;
case "9":
return 0;
default: return 3;
}
case "4":
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
return 2;
case "7":
return 2;
case "8":
return 2;
case "9":
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
return 4;
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
default: return 3;
}
default: return 3;
}
default: return 3;
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
case "4":
return 0;
case "2":
return -1;
case "3":
return 1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "3":
return -1;
case "8":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "6":
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
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "D":
switch($cardID[1]) {
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "7":
return "Spell Fray Cloak";
case "8":
return "Spell Fray Gloves";
case "9":
return "Spell Fray Leggings";
case "3":
return "Spectral Shield";
case "0":
return "Water Glow Lanterns";
case "1":
return "Water Glow Lanterns";
case "2":
return "Water Glow Lanterns";
case "4":
return "Crown of Dominion";
case "5":
return "Ornate Tessen";
case "6":
return "Spell Fray Tiara";
default: return "";
}
case "4":
switch($cardID[5]) {
case "5":
return "Silver";
case "0":
return "Imperial Edict";
case "1":
return "Imperial Ledger";
case "2":
return "Imperial Warhorn";
case "3":
return "Gold";
case "4":
return "Ponder";
case "6":
return "Spellbane Aegis";
default: return "";
}
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
case "0":
return "Tempest Aurora";
case "1":
return "Tempest Aurora";
case "2":
return "Invoke Suraya";
case "3":
return "Celestial Kimono";
case "4":
return "Wave of Reality";
case "5":
return "Phantasmal Symbiosis";
case "6":
return "Spectral Procession";
case "7":
return "Tome of Aeo";
case "8":
return "Blessing of Spirits";
case "9":
return "Blessing of Spirits";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Blessing of Spirits";
case "1":
return "Tranquil Passing";
case "2":
return "Tranquil Passing";
case "3":
return "Tranquil Passing";
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
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return "Hyper Driver";
case "1":
return "Hyper Driver";
case "2":
return "Hyper Driver";
case "3":
return "Arakni, Huntsman";
case "4":
return "Arakni";
case "5":
return "Spider's Bite";
case "6":
return "Spider's Bite";
case "7":
return "Blacktek Whisperers";
case "8":
return "Mask of Perdition";
case "9":
return "Eradicate";
default: return "";
}
case "9":
switch($cardID[5]) {
case "1":
return "Runechant";
case "0":
return "Sky Fire Lanterns";
case "2":
return "Surgent Aethertide";
case "3":
return "Seerstone";
case "4":
return "Mind Warp";
case "5":
return "Swell Tidings";
case "6":
return "Brainstorm";
case "7":
return "Aether Quickening";
case "8":
return "Aether Quickening";
case "9":
return "Aether Quickening";
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
case "2":
switch($cardID[5]) {
case "0":
return "Leave No Witnesses";
case "1":
return "Regicide";
case "2":
return "Surgical Extraction";
case "3":
return "Pay Day";
case "4":
return "Plunder the Poor";
case "5":
return "Plunder the Poor";
case "6":
return "Plunder the Poor";
case "7":
return "Rob the Rich";
case "8":
return "Rob the Rich";
case "9":
return "Rob the Rich";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Shred";
case "1":
return "Shred";
case "2":
return "Shred";
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
default: return "";
}
case "4":
switch($cardID[5]) {
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
case "8":
return "Cut to the Chase";
case "9":
return "Cut to the Chase";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Cut to the Chase";
case "1":
return "Sandscour Greatbow";
case "2":
return "Hornet's Sting";
case "3":
return "Heat Seeker";
case "4":
return "Immobilizing Shot";
case "5":
return "Dead Eye";
case "6":
return "Drill Shot";
case "7":
return "Drill Shot";
case "8":
return "Drill Shot";
case "9":
return "Blessing of Focus";
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
case "7":
switch($cardID[5]) {
case "0":
return "Point the Tip";
case "1":
return "Amethyst Tiara";
case "2":
return "Annals of Sutcliffe";
case "3":
return "Cryptic Crossing";
case "4":
return "Diabolic Ultimatum";
case "5":
return "Looming Doom";
case "6":
return "Deathly Duet";
case "7":
return "Deathly Duet";
case "8":
return "Deathly Duet";
case "9":
return "Blessing of Occult";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Blessing of Occult";
case "1":
return "Blessing of Occult";
case "2":
return "Aether Slash";
case "3":
return "Aether Slash";
case "4":
return "Aether Slash";
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
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Command and Conquer";
case "1":
return "Emperor, Dracai of Aesir";
case "2":
return "Dust from the Golden Plains";
case "3":
return "Dust from the Red Desert";
case "4":
return "Dust from the Shadow Crypts";
case "5":
return "Rok";
case "6":
return "Beaten Trackers";
case "7":
return "Savage Beatdown";
case "8":
return "Skull Crack";
case "9":
return "Berserk";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Reincarnate";
case "1":
return "Reincarnate";
case "2":
return "Reincarnate";
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
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Madcap Muscle";
case "1":
return "Madcap Muscle";
case "2":
return "Rumble Grunting";
case "3":
return "Rumble Grunting";
case "4":
return "Rumble Grunting";
case "5":
return "Yoji, Royal Protector";
case "6":
return "Seasoned Saviour";
case "7":
return "Steelbraid Buckler";
case "8":
return "Buckle";
case "9":
return "Never Yield";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Shield Bash";
case "1":
return "Shield Bash";
case "2":
return "Shield Bash";
case "3":
return "Blessing of Patience";
case "4":
return "Blessing of Patience";
case "5":
return "Blessing of Patience";
case "6":
return "Shield Wall";
case "7":
return "Shield Wall";
case "8":
return "Shield Wall";
case "9":
return "Reinforce Steel";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Reinforce Steel";
case "1":
return "Reinforce Steel";
case "2":
return "Withstand";
case "3":
return "Withstand";
case "4":
return "Withstand";
case "5":
return "Blazen Yoroi";
case "6":
return "Tearing Shuko";
case "7":
return "Tiger Swipe";
case "8":
return "Mindstate of Tiger";
case "9":
return "Roar of the Tiger";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Flex Claws";
case "1":
return "Flex Claws";
case "2":
return "Flex Claws";
case "3":
return "Blessing of Qi";
case "4":
return "Blessing of Qi";
case "5":
return "Blessing of Qi";
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
case "6":
switch($cardID[5]) {
case "0":
return "Qi Unleashed";
case "1":
return "Qi Unleashed";
case "2":
return "Predatory Streak";
case "3":
return "Predatory Streak";
case "4":
return "Predatory Streak";
case "5":
return "Crouching Tiger";
case "6":
return "Spirit of Eirina";
case "7":
return "Jubeel, Spellbane";
case "8":
return "Merciless Battleaxe";
case "9":
return "Quicksilver Dagger";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Quicksilver Dagger";
case "1":
return "Cleave";
case "2":
return "Ironsong Pride";
case "3":
return "Blessing of Steel";
case "4":
return "Blessing of Steel";
case "5":
return "Blessing of Steel";
case "6":
return "Precision Press";
case "7":
return "Precision Press";
case "8":
return "Precision Press";
case "9":
return "Puncture";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Puncture";
case "1":
return "Puncture";
case "2":
return "Felling Swing";
case "3":
return "Felling Swing";
case "4":
return "Felling Swing";
case "5":
return "Visit the Imperial Forge";
case "6":
return "Visit the Imperial Forge";
case "7":
return "Visit the Imperial Forge";
case "8":
return "Hanabi Blaster";
case "9":
return "Galvanic Bender";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Pulsewave Harpoon";
case "1":
return "Bios Update";
case "2":
return "Construct Nitro Mechanoid";
case "3":
return "Plasma Mainline";
case "4":
return "Powder Keg";
case "5":
return "Scramble Pulse";
case "6":
return "Scramble Pulse";
case "7":
return "Scramble Pulse";
case "8":
return "Blessing of Ingenuity";
case "9":
return "Blessing of Ingenuity";
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
case "1":
return "Dorinthea, Quicksilver Prodigy";
case "2":
return "Dawnblade, Resplendent";
case "4":
return "Blossom of Spring";
case "7":
return "Hala Goldenhelm";
case "8":
return "Glistening Steelblade";
case "9":
return "En Garde";
default: return "";
}
case "1":
switch($cardID[5]) {
case "3":
return "Run Through";
case "4":
return "Thrust";
case "9":
return "On a Knife Edge";
default: return "";
}
case "2":
switch($cardID[5]) {
case "2":
return "Visit the Blacksmith";
case "3":
return "Blade Flash";
case "4":
return "Toughen Up";
case "7":
return "Titanium Bauble";
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
case "0":
return "Heart of Fyendal";
case "1":
return "Rhinar, Reckless Rampage";
case "2":
return "Rhinar";
case "3":
return "Romping Club";
case "4":
return "Scabskin Leathers";
case "5":
return "Barkbone Strapping";
case "6":
return "Alpha Rampage";
case "7":
return "Bloodrush Bellow";
case "8":
return "Reckless Swing";
case "9":
return "Sand Sketched Plan";
default: return "";
}
case "1":
switch($cardID[5]) {
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
case "7":
return "Barraging Beatdown";
case "8":
return "Barraging Beatdown";
case "9":
return "Barraging Beatdown";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Savage Swing";
case "1":
return "Savage Swing";
case "2":
return "Savage Swing";
case "3":
return "Pack Hunt";
case "4":
return "Pack Hunt";
case "5":
return "Pack Hunt";
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
case "3":
switch($cardID[5]) {
case "0":
return "Wrecker Romp";
case "1":
return "Wrecker Romp";
case "2":
return "Awakening Bellow";
case "3":
return "Awakening Bellow";
case "4":
return "Awakening Bellow";
case "5":
return "Primeval Bellow";
case "6":
return "Primeval Bellow";
case "7":
return "Primeval Bellow";
case "8":
return "Bravo, Showstopper";
case "9":
return "Bravo";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Anothos";
case "1":
return "Tectonic Plating";
case "2":
return "Helm of Isen's Peak";
case "3":
return "Crippling Crush";
case "4":
return "Spinal Crush";
case "5":
return "Cranial Crush";
case "6":
return "Forged for War";
case "7":
return "Show Time!";
case "8":
return "Disable";
case "9":
return "Disable";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Disable";
case "1":
return "Staunch Response";
case "2":
return "Staunch Response";
case "3":
return "Staunch Response";
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
case "2":
return "Stonewall Confidence";
case "3":
return "Stonewall Confidence";
case "4":
return "Stonewall Confidence";
case "5":
return "Seismic Surge";
case "6":
return "Katsu, the Wanderer";
case "7":
return "Katsu";
case "8":
return "Harmonized Kodachi";
case "9":
return "Mask of Momentum";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Breaking Scales";
case "1":
return "Lord of Wind";
case "2":
return "Ancestral Empowerment";
case "3":
return "Mugenshi: RELEASE";
case "4":
return "Hurricane Technique";
case "5":
return "Pounding Gale";
case "6":
return "Fluster Fist";
case "7":
return "Fluster Fist";
case "8":
return "Fluster Fist";
case "9":
return "Blackout Kick";
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
case "5":
return "Open the Center";
case "6":
return "Open the Center";
case "7":
return "Open the Center";
case "8":
return "Head Jab";
case "9":
return "Head Jab";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "0":
return "Whelming Gustwave";
case "1":
return "Whelming Gustwave";
case "2":
return "Whelming Gustwave";
case "3":
return "Dorinthea Ironsong";
case "4":
return "Dorinthea";
case "5":
return "Dawnblade";
case "6":
return "Braveforge Bracers";
case "7":
return "Refraction Bolters";
case "8":
return "Glint the Quicksilver";
case "9":
return "Steelblade Supremacy";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Rout";
case "1":
return "Singing Steelblade";
case "2":
return "Ironsong Determination";
case "3":
return "Overpower";
case "4":
return "Overpower";
case "5":
return "Overpower";
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
case "3":
switch($cardID[5]) {
case "0":
return "Warrior's Valor";
case "1":
return "Warrior's Valor";
case "2":
return "Ironsong Response";
case "3":
return "Ironsong Response";
case "4":
return "Ironsong Response";
case "5":
return "Biting Blade";
case "6":
return "Biting Blade";
case "7":
return "Biting Blade";
case "8":
return "Stroke of Foresight";
case "9":
return "Stroke of Foresight";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Stroke of Foresight";
case "1":
return "Sharpen Steel";
case "2":
return "Sharpen Steel";
case "3":
return "Sharpen Steel";
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
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Fyendal's Spring Tunic";
case "1":
return "Hope Merchant's Hood";
case "2":
return "Heartened Cross Strap";
case "3":
return "Goliath Gauntlet";
case "4":
return "Snapdragon Scalers";
case "5":
return "Ironrot Helm";
case "6":
return "Ironrot Plate";
case "7":
return "Ironrot Gauntlet";
case "8":
return "Ironrot Legs";
case "9":
return "Enlightened Strike";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Tome of Fyendal";
case "1":
return "Last Ditch Effort";
case "2":
return "Crazy Brew";
case "3":
return "Remembrance";
case "4":
return "Drone of Brutality";
case "5":
return "Drone of Brutality";
case "6":
return "Drone of Brutality";
case "7":
return "Snatch";
case "8":
return "Snatch";
case "9":
return "Snatch";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Energy Potion";
case "1":
return "Potion of Strength";
case "2":
return "Timesnap Potion";
case "3":
return "Sigil of Solace";
case "4":
return "Sigil of Solace";
case "5":
return "Sigil of Solace";
case "6":
return "Barraging Brawnhide";
case "7":
return "Barraging Brawnhide";
case "8":
return "Barraging Brawnhide";
case "9":
return "Demolition Crew";
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
case "9":
switch($cardID[5]) {
case "0":
return "Raging Onslaught";
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
case "7":
return "Regurgitating Slog";
case "8":
return "Regurgitating Slog";
case "9":
return "Regurgitating Slog";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
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
case "6":
return "Pummel";
case "7":
return "Pummel";
case "8":
return "Pummel";
case "9":
return "Razor Reflex";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Razor Reflex";
case "1":
return "Razor Reflex";
case "2":
return "Unmovable";
case "3":
return "Unmovable";
case "4":
return "Unmovable";
case "5":
return "Sink Below";
case "6":
return "Sink Below";
case "7":
return "Sink Below";
case "8":
return "Nimblism";
case "9":
return "Nimblism";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Nimblism";
case "1":
return "Sloggism";
case "2":
return "Sloggism";
case "3":
return "Sloggism";
case "4":
return "Cracked Bauble";
case "5":
return "Quicken";
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Rhinar, Reckless Rampage";
case "3":
return "Romping Club";
case "0":
return "Arknight Shard";
case "2":
return "Kayo, Berserker Runt";
case "4":
return "Mandible Claw";
case "5":
return "Mandible Claw";
case "6":
return "Skullhorn";
case "7":
return "Beast Within";
case "8":
return "Massacre";
case "9":
return "Argh… Smash!";
default: return "";
}
case "2":
switch($cardID[5]) {
case "2":
return "Bravo, Showstopper";
case "3":
return "Anothos";
case "0":
return "Swing Fist, Think Later";
case "1":
return "Swing Fist, Think Later";
case "4":
return "Sledge of Anvilheim";
case "5":
return "Crater Fist";
case "6":
return "Mangle";
case "7":
return "Righteous Cleansing";
case "8":
return "Stamp Authority";
case "9":
return "Towering Titan";
default: return "";
}
case "4":
switch($cardID[5]) {
case "4":
return "Seismic Surge";
case "5":
return "Katsu, the Wanderer";
case "8":
return "Harmonized Kodachi";
case "9":
return "Harmonized Kodachi";
case "0":
return "Emerging Dominance";
case "1":
return "Blessing of Serenity";
case "2":
return "Blessing of Serenity";
case "3":
return "Blessing of Serenity";
case "6":
return "Ira, Crimson Haze";
case "7":
return "Benji, the Piercing Wind";
default: return "";
}
case "7":
switch($cardID[5]) {
case "6":
return "Dorinthea Ironsong";
case "8":
return "Dawnblade";
case "0":
return "Torrent of Tempo";
case "1":
return "Torrent of Tempo";
case "2":
return "Bittering Thorns";
case "3":
return "Salt the Wound";
case "4":
return "Whirling Mist Blossom";
case "5":
return "Zen State";
case "7":
return "Kassai, Cintari Sellsword";
case "9":
return "Cintari Saber";
default: return "";
}
case "9":
switch($cardID[5]) {
case "8":
return "Dash, Inventor Extraordinaire";
case "0":
return "Out for Blood";
case "1":
return "Hit and Run";
case "2":
return "Hit and Run";
case "3":
return "Hit and Run";
case "4":
return "Push Forward";
case "5":
return "Push Forward";
case "6":
return "Push Forward";
case "7":
return "Shiyana, Diamond Gemini";
case "9":
return "Data Doll MKII";
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
case "3":
switch($cardID[5]) {
case "0":
return "Towering Titan";
case "1":
return "Towering Titan";
case "2":
return "Crush the Weak";
case "3":
return "Crush the Weak";
case "4":
return "Crush the Weak";
case "5":
return "Chokeslam";
case "6":
return "Chokeslam";
case "7":
return "Chokeslam";
case "8":
return "Emerging Dominance";
case "9":
return "Emerging Dominance";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Edge of Autumn";
case "1":
return "Zephyr Needle";
case "2":
return "Zephyr Needle";
case "3":
return "Breeze Rider Boots";
case "4":
return "Find Center";
case "5":
return "Flood of Force";
case "6":
return "Heron's Flight";
case "7":
return "Crane Dance";
case "8":
return "Crane Dance";
case "9":
return "Crane Dance";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Rushing River";
case "1":
return "Rushing River";
case "2":
return "Rushing River";
case "3":
return "Flying Kick";
case "4":
return "Flying Kick";
case "5":
return "Flying Kick";
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
case "8":
switch($cardID[5]) {
case "0":
return "Cintari Saber";
case "1":
return "Courage of Bladehold";
case "2":
return "Twinning Blade";
case "3":
return "Unified Decree";
case "4":
return "Spoils of War";
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
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "8":
return "Fyendal's Spring Tunic";
case "0":
return "Foreboding Bolt";
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
case "9":
return "Gambler's Gloves";
default: return "";
}
case "9":
switch($cardID[5]) {
case "5":
return "Cracked Bauble";
case "6":
return "Quicken";
case "0":
return "Reinforce the Line";
case "1":
return "Reinforce the Line";
case "2":
return "Brutal Assault";
case "3":
return "Brutal Assault";
case "4":
return "Brutal Assault";
case "7":
return "Copper";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Teklo Plasma Pistol";
case "1":
return "Plasma Barrel Shot";
case "2":
return "Viziertronic Model i";
case "3":
return "Meganetic Shockwave";
case "4":
return "Absorption Dome";
case "5":
return "Plasma Purifier";
case "6":
return "High Speed Impact";
case "7":
return "High Speed Impact";
case "8":
return "High Speed Impact";
case "9":
return "Combustible Courier";
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
case "8":
return "Kavdaen, Trader of Skins";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Death Dealer";
case "1":
return "Red Liner";
case "2":
return "Perch Grapplers";
case "3":
return "Remorseless";
case "4":
return "Poison the Tips";
case "5":
return "Feign Death";
case "6":
return "Tripwire Trap";
case "7":
return "Pitfall Trap";
case "8":
return "Rockslide Trap";
case "9":
return "Pathing Helix";
default: return "";
}
case "3":
switch($cardID[5]) {
case "8":
return "Viserai, Rune Blood";
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
case "5":
return "Increase the Tension";
case "6":
return "Increase the Tension";
case "7":
return "Increase the Tension";
default: return "";
}
case "5":
switch($cardID[5]) {
case "7":
return "Runechant";
case "8":
return "Kano, Dracai of Aether";
case "9":
return "Crucible of Aetherweave";
case "0":
return "Consuming Volition";
case "1":
return "Meat and Greet";
case "2":
return "Meat and Greet";
case "3":
return "Meat and Greet";
case "4":
return "Sutcliffe's Research Notes";
case "5":
return "Sutcliffe's Research Notes";
case "6":
return "Sutcliffe's Research Notes";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Reaping Blade";
case "1":
return "Bloodsheath Skeleta";
case "2":
return "Dread Triptych";
case "3":
return "Rattle Bones";
case "4":
return "Runeblood Barrier";
case "5":
return "Mauvrion Skies";
case "6":
return "Mauvrion Skies";
case "7":
return "Mauvrion Skies";
case "8":
return "Consuming Volition";
case "9":
return "Consuming Volition";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Aether Conduit";
case "1":
return "Metacarpus Node";
case "2":
return "Chain Lightning";
case "3":
return "Gaze the Ages";
case "4":
return "Aetherize";
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
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Coax a Commotion";
case "1":
return "Gorganian Tome";
case "2":
return "Snag";
case "3":
return "Promise of Plenty";
case "4":
return "Promise of Plenty";
case "5":
return "Promise of Plenty";
case "6":
return "Lunging Press";
case "7":
return "Springboard Somersault";
case "8":
return "Cash In";
case "9":
return "Reinforce the Line";
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
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "Seismic Surge";
case "0":
return "Thump";
case "1":
return "Thump";
case "3":
return "New Horizon";
case "4":
return "Honing Hood";
case "5":
return "Seek and Destroy";
case "6":
return "Bolt'n' Shot";
case "7":
return "Bolt'n' Shot";
case "8":
return "Bolt'n' Shot";
case "9":
return "Over Flex";
default: return "";
}
case "3":
switch($cardID[5]) {
case "7":
return "Cracked Bauble";
case "0":
return "Singeing Steelblade";
case "1":
return "Singeing Steelblade";
case "2":
return "Singeing Steelblade";
case "3":
return "Ragamuffin's Hat";
case "4":
return "Deep Blue";
case "5":
return "Cracker Jax";
case "6":
return "Runaways";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Electrify";
case "1":
return "Amulet of Lightning";
case "2":
return "Titan's Fist";
case "3":
return "Rampart of the Ram's Head";
case "4":
return "Rotten Old Buckler";
case "5":
return "Tear Asunder";
case "6":
return "Embolden";
case "7":
return "Embolden";
case "8":
return "Embolden";
case "9":
return "Thump";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Over Flex";
case "1":
return "Over Flex";
case "2":
return "Rosetta Thorn";
case "3":
return "Duskblade";
case "4":
return "Spellbound Creepers";
case "5":
return "Sutcliffe's Suede Hides";
case "6":
return "Sting of Sorcery";
case "7":
return "Sigil of Suffering";
case "8":
return "Sigil of Suffering";
case "9":
return "Sigil of Suffering";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Korshem, Crossroad of Elements";
case "1":
return "Oldhim, Grandfather of Eternity";
case "2":
return "Oldhim";
case "3":
return "Winter's Wail";
case "4":
return "Endless Winter";
case "5":
return "Oaken Old";
case "6":
return "Awakening";
case "7":
return "Biting Gale";
case "8":
return "Biting Gale";
case "9":
return "Biting Gale";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Turn Timber";
case "1":
return "Turn Timber";
case "2":
return "Turn Timber";
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
default: return "";
}
case "2":
switch($cardID[5]) {
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
case "5":
return "Emerging Avalanche";
case "6":
return "Emerging Avalanche";
case "7":
return "Emerging Avalanche";
case "8":
return "Strength of Sequoia";
case "9":
return "Strength of Sequoia";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Strength of Sequoia";
case "1":
return "Lexi, Livewire";
case "2":
return "Lexi";
case "3":
return "Shiver";
case "4":
return "Voltaire, Strike Twice";
case "5":
return "Frost Lock";
case "6":
return "Light it Up";
case "7":
return "Ice Storm";
case "8":
return "Cold Wave";
case "9":
return "Cold Wave";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Cold Wave";
case "1":
return "Snap Shot";
case "2":
return "Snap Shot";
case "3":
return "Snap Shot";
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
case "6":
switch($cardID[5]) {
case "0":
return "Frazzle";
case "1":
return "Frazzle";
case "2":
return "Briar, Warden of Thorns";
case "3":
return "Briar";
case "4":
return "Blossoming Spellblade";
case "5":
return "Flicker Wisp";
case "6":
return "Force of Nature";
case "7":
return "Explosive Growth";
case "8":
return "Explosive Growth";
case "9":
return "Explosive Growth";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Rites of Lightning";
case "1":
return "Rites of Lightning";
case "2":
return "Rites of Lightning";
case "3":
return "Arcanic Shockwave";
case "4":
return "Arcanic Shockwave";
case "5":
return "Arcanic Shockwave";
case "6":
return "Vela Flash";
case "7":
return "Vela Flash";
case "8":
return "Vela Flash";
case "9":
return "Rites of Replenishment";
default: return "";
}
case "8":
switch($cardID[5]) {
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
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Inspire Lightning";
case "1":
return "Fulminate";
case "2":
return "Flashfreeze";
case "3":
return "Exposed to the Elements";
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
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
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
case "9":
return "Embodiment of Earth";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Embodiment of Lightning";
case "1":
return "Frostbite";
case "2":
return "Pulse of Volthaven";
case "3":
return "Pulse of Candlehold";
case "4":
return "Pulse of Isenloft";
case "5":
return "Crown of Seeds";
case "6":
return "Plume of Evergrowth";
case "7":
return "Channel Mount Heroic";
case "8":
return "Tome of Harvests";
case "9":
return "Evergreen";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Evergreen";
case "1":
return "Evergreen";
case "2":
return "Weave Earth";
case "3":
return "Weave Earth";
case "4":
return "Weave Earth";
case "5":
return "Summerwood Shelter";
case "6":
return "Summerwood Shelter";
case "7":
return "Summerwood Shelter";
case "8":
return "Autumn's Touch";
case "9":
return "Autumn's Touch";
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
case "4":
switch($cardID[5]) {
case "0":
return "Sow Tomorrow";
case "1":
return "Sow Tomorrow";
case "2":
return "Sow Tomorrow";
case "3":
return "Amulet of Earth";
case "4":
return "Heart of Ice";
case "5":
return "Coat of Frost";
case "6":
return "Channel Lake Frigid";
case "7":
return "Blizzard";
case "8":
return "Frost Fang";
case "9":
return "Frost Fang";
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
case "4":
return "Weave Ice";
case "5":
return "Weave Ice";
case "6":
return "Weave Ice";
case "7":
return "Icy Encounter";
case "8":
return "Icy Encounter";
case "9":
return "Icy Encounter";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Winter's Grasp";
case "1":
return "Winter's Grasp";
case "2":
return "Winter's Grasp";
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
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Winter's Bite";
case "1":
return "Winter's Bite";
case "2":
return "Amulet of Ice";
case "3":
return "Shock Charmers";
case "4":
return "Mark of Lightning";
case "5":
return "Channel Thunder Steppe";
case "6":
return "Blink";
case "7":
return "Flash";
case "8":
return "Flash";
case "9":
return "Flash";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Weave Lightning";
case "1":
return "Weave Lightning";
case "2":
return "Weave Lightning";
case "3":
return "Lightning Press";
case "4":
return "Lightning Press";
case "5":
return "Lightning Press";
case "6":
return "Ball Lightning";
case "7":
return "Ball Lightning";
case "8":
return "Ball Lightning";
case "9":
return "Lightning Surge";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Lightning Surge";
case "1":
return "Lightning Surge";
case "2":
return "Heaven's Claws";
case "3":
return "Heaven's Claws";
case "4":
return "Heaven's Claws";
case "5":
return "Shock Striker";
case "6":
return "Shock Striker";
case "7":
return "Shock Striker";
case "8":
return "Electrify";
case "9":
return "Electrify";
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
case "3":
switch($cardID[5]) {
case "6":
return "Seismic Surge";
case "0":
return "Seismic Stir";
case "1":
return "Seismic Stir";
case "2":
return "Seismic Stir";
case "3":
return "Steadfast";
case "4":
return "Steadfast";
case "5":
return "Steadfast";
case "7":
return "Mask of the Pouncing Lynx";
case "8":
return "Break Tide";
case "9":
return "Spring Tidings";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Grandeur of Valahai";
case "1":
return "Skull Crushers";
case "2":
return "Swing Big";
case "3":
return "Ready to Roll";
case "4":
return "Rolling Thunder";
case "5":
return "High Roller";
case "6":
return "High Roller";
case "7":
return "High Roller";
case "8":
return "Bare Fangs";
case "9":
return "Bare Fangs";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Bare Fangs";
case "1":
return "Wild Ride";
case "2":
return "Wild Ride";
case "3":
return "Wild Ride";
case "4":
return "Bad Beats";
case "5":
return "Bad Beats";
case "6":
return "Bad Beats";
case "7":
return "Bravo, Star of the Show";
case "8":
return "Stalagmite, Bastion of Isenloft";
case "9":
return "Valda Brightaxe";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Earthlore Bounty";
case "1":
return "Pulverize";
case "2":
return "Imposing Visage";
case "3":
return "Nerves of Steel";
case "4":
return "Thunder Quake";
case "5":
return "Thunder Quake";
case "6":
return "Thunder Quake";
case "7":
return "Macho Grande";
case "8":
return "Macho Grande";
case "9":
return "Macho Grande";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Winds of Eternity";
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
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Wax On";
case "1":
return "Wax On";
case "2":
return "Wax On";
case "3":
return "Helm of Sharp Eye";
case "4":
return "Shatter";
case "5":
return "Blood on Her Hands";
case "6":
return "Oath of Steel";
case "7":
return "Slice and Dice";
case "8":
return "Slice and Dice";
case "9":
return "Slice and Dice";
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
case "9":
return "Dissolution Sphere";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Micro-processor";
case "1":
return "Signal Jammer";
case "2":
return "Teklo Pounder";
case "3":
return "T-Bone";
case "4":
return "T-Bone";
case "5":
return "T-Bone";
case "6":
return "Payload";
case "7":
return "Payload";
case "8":
return "Payload";
case "9":
return "Zoom In";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Zoom In";
case "1":
return "Zoom In";
case "2":
return "Rotary Ram";
case "3":
return "Rotary Ram";
case "4":
return "Rotary Ram";
case "5":
return "Genis Wotchuneed";
case "6":
return "Silver Palms";
case "7":
return "Dreadbore";
case "8":
return "Battering Bolt";
case "9":
return "Tri-shot";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Rain Razors";
case "1":
return "Release the Tension";
case "2":
return "Release the Tension";
case "3":
return "Release the Tension";
case "4":
return "Fatigue Shot";
case "5":
return "Fatigue Shot";
case "6":
return "Fatigue Shot";
case "7":
return "Timidity Point";
case "8":
return "Timidity Point";
case "9":
return "Timidity Point";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return "Quicken";
case "4":
return "Copper";
case "7":
return "Frostbite";
case "0":
return "Talisman of Featherfoot";
case "1":
return "Talisman of Recompense";
case "2":
return "Talisman of Tithes";
case "3":
return "Talisman of Warfare";
case "5":
return "Silver";
default: return "";
}
case "1":
switch($cardID[5]) {
case "9":
return "Runechant";
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
case "6":
return "Shrill of Skullform";
case "7":
return "Shrill of Skullform";
case "8":
return "Shrill of Skullform";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Arcanite Skullcap";
case "3":
return "Spectral Shield";
case "0":
return "Veiled Intentions";
case "1":
return "Veiled Intentions";
case "2":
return "Veiled Intentions";
case "5":
return "Arcane Lantern";
case "6":
return "Bingo";
case "7":
return "Firebreathing";
case "8":
return "Cash Out";
case "9":
return "Knick Knack Bric-a-brac";
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
case "3":
return "Vexing Quillhand";
case "4":
return "Runic Reclamation";
case "5":
return "Swarming Gloomveil";
case "6":
return "Revel in Runeblood";
case "7":
return "Runeblood Incantation";
case "8":
return "Runeblood Incantation";
case "9":
return "Runeblood Incantation";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Iyslander";
case "1":
return "Kraken's Aethervein";
case "2":
return "Sigil of Parapets";
case "3":
return "Aether Wildfire";
case "4":
return "Scour";
case "5":
return "Emeritus Scolding";
case "6":
return "Emeritus Scolding";
case "7":
return "Emeritus Scolding";
case "8":
return "Pry";
case "9":
return "Pry";
default: return "";
}
case "3":
switch($cardID[5]) {
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
case "7":
return "Crown of Reflection";
case "8":
return "Fractal Replication";
case "9":
return "Miraging Metamorph";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Shimmers of Silver";
case "1":
return "Haze Bending";
case "2":
return "Passing Mirage";
case "3":
return "Pierce Reality";
case "4":
return "Coalescence Mirage";
case "5":
return "Coalescence Mirage";
case "6":
return "Coalescence Mirage";
case "7":
return "Phantasmal Haze";
case "8":
return "Phantasmal Haze";
case "9":
return "Phantasmal Haze";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "This Round's on Me";
case "1":
return "Life of the Party";
case "2":
return "Life of the Party";
case "3":
return "Life of the Party";
case "4":
return "High Striker";
case "5":
return "High Striker";
case "6":
return "High Striker";
case "7":
return "Pick a Card, Any Card";
case "8":
return "Pick a Card, Any Card";
case "9":
return "Pick a Card, Any Card";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Smashing Good Time";
case "1":
return "Smashing Good Time";
case "2":
return "Smashing Good Time";
case "3":
return "Even Bigger Than That!";
case "4":
return "Even Bigger Than That!";
case "5":
return "Even Bigger Than That!";
case "6":
return "Amulet of Assertiveness";
case "7":
return "Amulet of Echoes";
case "8":
return "Amulet of Havencall";
case "9":
return "Amulet of Ignition";
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
case "4":
return "Potion of Seeing";
case "5":
return "Potion of Deja Vu";
case "6":
return "Potion of Ironhide";
case "7":
return "Potion of Luck";
case "8":
return "Talisman of Balance";
case "9":
return "Talisman of Cremation";
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
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "5":
return "Katsu, the Wanderer";
case "6":
return "Katsu";
case "7":
return "Benji, the Piercing Wind";
case "0":
return "Wither";
case "1":
return "Wither";
case "2":
return "Razor's Edge";
case "9":
return "Mask of Many Faces";
default: return "";
}
case "8":
switch($cardID[5]) {
case "9":
return "Azalea, Ace in the Hole";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Azalea";
case "3":
return "Barbed Castaway";
case "6":
return "Quiver of Rustling Leaves";
default: return "";
}
case "0":
switch($cardID[5]) {
case "1":
return "Uzuri, Switchblade";
case "2":
return "Uzuri";
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
case "9":
return "Scale Peeler";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Scale Peeler";
case "1":
return "Redback Shroud";
case "2":
return "Infiltrate";
case "3":
return "Shake Down";
case "4":
return "Spreading Plague";
case "5":
return "Back Stab";
case "6":
return "Back Stab";
case "7":
return "Back Stab";
case "8":
return "Sneak Attack";
default: return "";
}
case "2":
switch($cardID[5]) {
case "1":
return "Spike with Bloodrot";
case "2":
return "Spike with Frailty";
case "3":
return "Spike with Inertia";
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
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Malign";
case "3":
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
case "5":
switch($cardID[5]) {
case "1":
return "Dishonor";
case "2":
return "Head Leads the Tail";
case "6":
return "Bonds of Ancestry";
case "7":
return "Bonds of Ancestry";
case "8":
return "Bonds of Ancestry";
default: return "";
}
case "6":
switch($cardID[5]) {
case "8":
return "Be Like Water";
case "9":
return "Be Like Water";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Be Like Water";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Barbed Undertow";
case "3":
return "Collapsing Trap";
default: return "";
}
case "2":
switch($cardID[5]) {
case "4":
return "Sedation Shot";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Flick Knives";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Blade Cuff";
case "2":
return "Stab Wound";
case "5":
return "Bleed Out";
case "8":
return "Hurl";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Short and Sharp";
case "8":
return "Toxic Tips";
case "9":
return "Codex of Bloodrot";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Codex of Frailty";
case "2":
return "Death Touch";
case "3":
return "Death Touch";
case "4":
return "Death Touch";
case "5":
return "Toxicity";
case "8":
return "Virulent Touch";
default: return "";
}
case "8":
switch($cardID[5]) {
case "3":
return "Amnesia";
case "6":
return "Gore Belching";
default: return "";
}
case "9":
switch($cardID[5]) {
case "5":
return "Looking for a Scrap";
case "8":
return "Wreck Havoc";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Cut Down to Size";
case "4":
return "Destructive Deliberation";
default: return "";
}
case "3":
switch($cardID[5]) {
case "1":
return "Peace of Mind";
case "2":
return "Peace of Mind";
case "3":
return "Peace of Mind";
case "4":
return "Bloodrot Pox";
case "5":
return "Frailty";
case "6":
return "Inertia";
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
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "Scar for a Scar";
case "0":
return "Strategic Planning";
case "1":
return "Strategic Planning";
case "2":
return "Strategic Planning";
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
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Scar for a Scar";
case "1":
return "Scar for a Scar";
case "2":
return "Trade In";
case "3":
return "Trade In";
case "4":
return "Trade In";
case "5":
return "Healing Balm";
case "6":
return "Healing Balm";
case "7":
return "Healing Balm";
case "8":
return "Sigil of Protection";
case "9":
return "Sigil of Protection";
default: return "";
}
case "2":
switch($cardID[5]) {
case "4":
return "Cracked Bauble";
case "0":
return "Sigil of Protection";
case "1":
return "Oasis Respite";
case "2":
return "Oasis Respite";
case "3":
return "Oasis Respite";
case "5":
return "Dragons of Legend";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "0":
return "Frostbite";
case "1":
return "Ghostly Touch";
case "2":
return "Silent Stilettos";
case "3":
return "Frightmare";
case "4":
return "Semblance";
case "5":
return "Transmogrify";
case "6":
return "Transmogrify";
case "7":
return "Transmogrify";
case "8":
return "Tiger Stripe Shuko";
case "9":
return "Tide Flippers";
default: return "";
}
case "0":
switch($cardID[5]) {
case "3":
return "Iyslander";
case "0":
return "Stoke the Flames";
case "1":
return "Phoenix Flame";
case "2":
return "Iyslander, Stormbind";
case "4":
return "Encase";
case "5":
return "Freezing Point";
case "6":
return "Sigil of Permafrost";
case "7":
return "Sigil of Permafrost";
case "8":
return "Sigil of Permafrost";
case "9":
return "Ice Eternal";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Succumb to Winter";
case "1":
return "Succumb to Winter";
case "2":
return "Succumb to Winter";
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
default: return "";
}
case "2":
switch($cardID[5]) {
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
case "5":
return "Conduit of Frostburn";
case "6":
return "Frost Hex";
case "7":
return "Aether Hail";
case "8":
return "Aether Hail";
case "9":
return "Aether Hail";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Frosting";
case "1":
return "Frosting";
case "2":
return "Frosting";
case "3":
return "Ice Bolt";
case "4":
return "Ice Bolt";
case "5":
return "Ice Bolt";
case "6":
return "Coronet Peak";
case "7":
return "Glacial Horns";
case "8":
return "Channel the Bleak Expanse";
case "9":
return "Hypothermia";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Insidious Chill";
case "1":
return "Isenhowl Weathervane";
case "2":
return "Isenhowl Weathervane";
case "3":
return "Isenhowl Weathervane";
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
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Double Strike";
case "1":
return "Take the Tempo";
case "2":
return "Rapid Reflex";
case "3":
return "Rapid Reflex";
case "4":
return "Rapid Reflex";
case "5":
return "Waning Moon";
case "6":
return "Alluvion Constellas";
case "7":
return "Spellfire Cloak";
case "8":
return "Tome of Duplicity";
case "9":
return "Rewind";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Dampen";
case "1":
return "Dampen";
case "2":
return "Dampen";
case "3":
return "Aether Dart";
case "4":
return "Aether Dart";
case "5":
return "Aether Dart";
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
case "8":
switch($cardID[5]) {
case "0":
return "Singe";
case "1":
return "Singe";
case "2":
return "Crown of Providence";
case "3":
return "Helio's Mitre";
case "4":
return "Quelling Robe";
case "5":
return "Quelling Sleeves";
case "6":
return "Quelling Slippers";
case "7":
return "Erase Face";
case "8":
return "Vipox";
case "9":
return "That All You Got?";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Fog Down";
case "1":
return "Flex";
case "2":
return "Flex";
case "3":
return "Flex";
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
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Blood of the Dracai";
case "1":
return "Dromai, Ash Artist";
case "2":
return "Dromai";
case "3":
return "Storm of Sandikai";
case "4":
return "Silken Form";
case "5":
return "Burn Them All";
case "6":
return "Invoke Dracona Optimai";
case "7":
return "Invoke Tomeltai";
case "8":
return "Invoke Dominia";
case "9":
return "Invoke Azvolai";
default: return "";
}
case "1":
switch($cardID[5]) {
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
case "8":
return "Billowing Mirage";
case "9":
return "Billowing Mirage";
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
case "3":
switch($cardID[5]) {
case "0":
return "Sweeping Blow";
case "1":
return "Sweeping Blow";
case "2":
return "Sweeping Blow";
case "3":
return "Rake the Embers";
case "4":
return "Rake the Embers";
case "5":
return "Rake the Embers";
case "6":
return "Skittering Sands";
case "7":
return "Skittering Sands";
case "8":
return "Skittering Sands";
case "9":
return "Sand Cover";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Sand Cover";
case "1":
return "Sand Cover";
case "2":
return "Aether Ashwing";
case "3":
return "Ash";
case "4":
return "Fai, Rising Rebellion";
case "5":
return "Fai";
case "6":
return "Searing Emberblade";
case "7":
return "Heat Wave";
case "8":
return "Phoenix Form";
case "9":
return "Spreading Flames";
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
case "8":
switch($cardID[5]) {
case "0":
return "Ronin Renegade";
case "1":
return "Soaring Strike";
case "2":
return "Soaring Strike";
case "3":
return "Soaring Strike";
case "4":
return "Flamescale Furnace";
case "5":
return "Sash of Sandikai";
case "6":
return "Thaw";
case "7":
return "Liquefy";
case "8":
return "Uprising";
case "9":
return "Tome of Firebrand";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Red Hot";
case "1":
return "Rise Up";
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
case "9":
return "Searing Touch";
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
case "2":
switch($cardID[4]) {
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
case "0":
switch($cardID[5]) {
case "0":
return "Fate Foreseen";
case "1":
return "Fate Foreseen";
case "2":
return "Fate Foreseen";
case "3":
return "Come to Fight";
case "4":
return "Come to Fight";
case "5":
return "Come to Fight";
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
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Eye of Ophidia";
case "1":
return "Dash, Inventor Extraordinaire";
case "2":
return "Dash";
case "3":
return "Teklo Plasma Pistol";
case "4":
return "Teklo Foundry Heart";
case "5":
return "Achilles Accelerator";
case "6":
return "High Octane";
case "7":
return "Teklo Core";
case "8":
return "Maximum Velocity";
case "9":
return "Spark of Genius";
default: return "";
}
case "1":
switch($cardID[5]) {
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
case "7":
return "Aether Sink";
case "8":
return "Cognition Nodes";
case "9":
return "Convection Amplifier";
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
case "3":
switch($cardID[5]) {
case "0":
return "Zipper Hit";
case "1":
return "Zipper Hit";
case "2":
return "Locked and Loaded";
case "3":
return "Locked and Loaded";
case "4":
return "Locked and Loaded";
case "5":
return "Dissipation Shield";
case "6":
return "Hyper Driver";
case "7":
return "Optekal Monocle";
case "8":
return "Azalea, Ace in the Hole";
case "9":
return "Azalea";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Death Dealer";
case "1":
return "Skullbone Crosswrap";
case "2":
return "Bull's Eye Bracers";
case "3":
return "Red in the Ledger";
case "4":
return "Three of a Kind";
case "5":
return "Endless Arrow";
case "6":
return "Nock the Deathwhistle";
case "7":
return "Rapid Fire";
case "8":
return "Take Cover";
case "9":
return "Take Cover";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Take Cover";
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
case "7":
return "Head Shot";
case "8":
return "Head Shot";
case "9":
return "Head Shot";
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
case "7":
switch($cardID[5]) {
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
case "5":
return "Viserai, Rune Blood";
case "6":
return "Viserai";
case "7":
return "Nebula Blade";
case "8":
return "Grasp of the Arknight";
case "9":
return "Crown of Dichotomy";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Arknight Ascendancy";
case "1":
return "Mordred Tide";
case "2":
return "Ninth Blade of the Blood Oath";
case "3":
return "Become the Arknight";
case "4":
return "Tome of the Arknight";
case "5":
return "Spellblade Assault";
case "6":
return "Spellblade Assault";
case "7":
return "Spellblade Assault";
case "8":
return "Reduce to Runechant";
case "9":
return "Reduce to Runechant";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Reduce to Runechant";
case "1":
return "Oath of the Arknight";
case "2":
return "Oath of the Arknight";
case "3":
return "Oath of the Arknight";
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
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
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
case "6":
return "Bloodspill Invocation";
case "7":
return "Bloodspill Invocation";
case "8":
return "Bloodspill Invocation";
case "9":
return "Read the Runes";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Read the Runes";
case "1":
return "Read the Runes";
case "2":
return "Runechant";
case "3":
return "Kano, Dracai of Aether";
case "4":
return "Kano";
case "5":
return "Crucible of Aetherweave";
case "6":
return "Storm Striders";
case "7":
return "Robe of Rapture";
case "8":
return "Blazing Aether";
case "9":
return "Sonic Boom";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Forked Lightning";
case "1":
return "Lesson in Lava";
case "2":
return "Tome of Aetherwind";
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
case "9":
return "Stir the Aetherwinds";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Stir the Aetherwinds";
case "1":
return "Stir the Aetherwinds";
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
case "4":
return "Zap";
case "5":
return "Zap";
case "6":
return "Zap";
case "7":
return "Voltic Bolt";
case "8":
return "Voltic Bolt";
case "9":
return "Voltic Bolt";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Arcanite Skullcap";
case "1":
return "Talismanic Lens";
case "2":
return "Vest of the First Fist";
case "3":
return "Bracers of Belief";
case "4":
return "Mage Master Boots";
case "5":
return "Nullrune Hood";
case "6":
return "Nullrune Robe";
case "7":
return "Nullrune Gloves";
case "8":
return "Nullrune Boots";
case "9":
return "Command and Conquer";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Art of War";
case "1":
return "Pursuit of Knowledge";
case "2":
return "Chains of Eminence";
case "3":
return "Rusted Relic";
case "4":
return "Life for a Life";
case "5":
return "Life for a Life";
case "6":
return "Life for a Life";
case "7":
return "Enchanting Melody";
case "8":
return "Enchanting Melody";
case "9":
return "Enchanting Melody";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Plunder Run";
case "1":
return "Plunder Run";
case "2":
return "Plunder Run";
case "3":
return "Eirina's Prayer";
case "4":
return "Eirina's Prayer";
case "5":
return "Eirina's Prayer";
case "6":
return "Back Alley Breakline";
case "7":
return "Back Alley Breakline";
case "8":
return "Back Alley Breakline";
case "9":
return "Cadaverous Contraband";
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
default: return "";
}
default: return "";
}
default: return "";
}
default: return "";
}
case "M":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return "Cracked Bauble";
case "0":
return "Warmonger's Recital";
case "1":
return "Warmonger's Recital";
case "2":
return "Talisman of Dousing";
case "3":
return "Memorial Ground";
case "4":
return "Memorial Ground";
case "5":
return "Memorial Ground";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Great Library of Solana";
case "1":
return "Prism, Sculptor of Arc Light";
case "2":
return "Prism";
case "3":
return "Luminaris";
case "4":
return "Herald of Erudition";
case "5":
return "Arc Light Sentinel";
case "6":
return "Genesis";
case "7":
return "Herald of Judgment";
case "8":
return "Herald of Triumph";
case "9":
return "Herald of Triumph";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Herald of Triumph";
case "1":
return "Parable of Humility";
case "2":
return "Merciful Retribution";
case "3":
return "Ode to Wrath";
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
return "Wartune Herald";
case "7":
return "Wartune Herald";
case "8":
return "Wartune Herald";
case "9":
return "Ser Boltyn, Breaker of Dawn";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Boltyn";
case "1":
return "Raydn, Duskbane";
case "2":
return "Bolting Blade";
case "3":
return "Beacon of Victory";
case "4":
return "Lumina Ascension";
case "5":
return "V of the Vanguard";
case "6":
return "Battlefield Blitz";
case "7":
return "Battlefield Blitz";
case "8":
return "Battlefield Blitz";
case "9":
return "Valiant Thrust";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Valiant Thrust";
case "1":
return "Valiant Thrust";
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
default: return "";
}
case "5":
switch($cardID[5]) {
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
case "7":
return "Courageous Steelhand";
case "8":
return "Courageous Steelhand";
case "9":
return "Courageous Steelhand";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Vestige of Sol";
case "1":
return "Halo of Illumination";
case "2":
return "Celestial Cataclysm";
case "3":
return "Soul Shield";
case "4":
return "Soul Food";
case "5":
return "Tome of Divinity";
case "6":
return "Invigorating Light";
case "7":
return "Invigorating Light";
case "8":
return "Invigorating Light";
case "9":
return "Glisten";
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
case "8":
switch($cardID[5]) {
case "0":
return "Rising Solartide";
case "1":
return "Seek Enlightenment";
case "2":
return "Seek Enlightenment";
case "3":
return "Seek Enlightenment";
case "4":
return "Blinding Beam";
case "5":
return "Blinding Beam";
case "6":
return "Blinding Beam";
case "7":
return "Ray of Hope";
case "8":
return "Iris of Reality";
case "9":
return "Phantasmal Footsteps";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Dream Weavers";
case "1":
return "Phantasmaclasm";
case "2":
return "Prismatic Shield";
case "3":
return "Prismatic Shield";
case "4":
return "Prismatic Shield";
case "5":
return "Phantasmify";
case "6":
return "Phantasmify";
case "7":
return "Phantasmify";
case "8":
return "Enigma Chimera";
case "9":
return "Enigma Chimera";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Enigma Chimera";
case "1":
return "Spears of Surreality";
case "2":
return "Spears of Surreality";
case "3":
return "Spears of Surreality";
case "4":
return "Spectral Shield";
case "5":
return "Hatchet of Body";
case "6":
return "Hatchet of Mind";
case "7":
return "Valiant Dynamo";
case "8":
return "Gallantry Gold";
case "9":
return "Spill Blood";
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
case "9":
return "Levia, Shadowborn Abomination";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Levia";
case "1":
return "Hexagore, the Death Hydra";
case "2":
return "Hooves of the Shadowbeast";
case "3":
return "Deep Rooted Evil";
case "4":
return "Mark of the Beast";
case "5":
return "Shadow of Blasmophet";
case "6":
return "Endless Maw";
case "7":
return "Endless Maw";
case "8":
return "Endless Maw";
case "9":
return "Writhing Beast Hulk";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Writhing Beast Hulk";
case "1":
return "Writhing Beast Hulk";
case "2":
return "Convulsions from the Bellows of Hell";
case "3":
return "Convulsions from the Bellows of Hell";
case "4":
return "Convulsions from the Bellows of Hell";
case "5":
return "Boneyard Marauder";
case "6":
return "Boneyard Marauder";
case "7":
return "Boneyard Marauder";
case "8":
return "Deadwood Rumbler";
case "9":
return "Deadwood Rumbler";
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
case "5":
switch($cardID[5]) {
case "0":
return "Unworldly Bellow";
case "1":
return "Unworldly Bellow";
case "2":
return "Unworldly Bellow";
case "3":
return "Chane, Bound by Shadow";
case "4":
return "Chane";
case "5":
return "Galaxxi Black";
case "6":
return "Shadow of Ursur";
case "7":
return "Dimenxxional Crossroads";
case "8":
return "Invert Existence";
case "9":
return "Unhallowed Rites";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Unhallowed Rites";
case "1":
return "Unhallowed Rites";
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
case "8":
return "Bounding Demigon";
case "9":
return "Bounding Demigon";
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
case "7":
return "Carrion Husk";
case "8":
return "Ebon Fold";
case "9":
return "Doomsday";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Eclipse";
case "1":
return "Mutated Mass";
case "2":
return "Guardian of the Shadowrealm";
case "3":
return "Shadow Puppetry";
case "4":
return "Tome of Torment";
case "5":
return "Consuming Aftermath";
case "6":
return "Consuming Aftermath";
case "7":
return "Consuming Aftermath";
case "8":
return "Soul Harvest";
case "9":
return "Soul Reaping";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Howl from Beyond";
case "1":
return "Howl from Beyond";
case "2":
return "Howl from Beyond";
case "3":
return "Ghostly Visit";
case "4":
return "Ghostly Visit";
case "5":
return "Ghostly Visit";
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
case "1":
switch($cardID[5]) {
case "0":
return "Void Wraith";
case "1":
return "Void Wraith";
case "2":
return "Spew Shadow";
case "3":
return "Spew Shadow";
case "4":
return "Spew Shadow";
case "5":
return "Blood Tribute";
case "6":
return "Blood Tribute";
case "7":
return "Blood Tribute";
case "8":
return "Eclipse Existence";
case "9":
return "Blasmophet, the Soul Harvester";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Ursur, the Soul Reaper";
case "1":
return "Ravenous Meataxe";
case "2":
return "Tear Limb from Limb";
case "3":
return "Pulping";
case "4":
return "Pulping";
case "5":
return "Pulping";
case "6":
return "Smash with Big Tree";
case "7":
return "Smash with Big Tree";
case "8":
return "Smash with Big Tree";
case "9":
return "Dread Scythe";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Aether Ironweave";
case "1":
return "Sonata Arcanix";
case "2":
return "Vexing Malice";
case "3":
return "Vexing Malice";
case "4":
return "Vexing Malice";
case "5":
return "Arcanic Crackle";
case "6":
return "Arcanic Crackle";
case "7":
return "Arcanic Crackle";
case "8":
return "Blood Drop Brocade";
case "9":
return "Stubby Hammerers";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Time Skippers";
case "1":
return "Ironhide Helm";
case "2":
return "Ironhide Plate";
case "3":
return "Ironhide Gauntlet";
case "4":
return "Ironhide Legs";
case "5":
return "Exude Confidence";
case "6":
return "Nourishing Emptiness";
case "7":
return "Rouse the Ancients";
case "8":
return "Out Muscle";
case "9":
return "Out Muscle";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Out Muscle";
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
case "7":
return "Rise Above";
case "8":
return "Rise Above";
case "9":
return "Rise Above";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Captain's Call";
case "1":
return "Captain's Call";
case "2":
return "Captain's Call";
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
case "9":
switch($cardID[5]) {
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
case "6":
return "Minnowism";
case "7":
return "Minnowism";
case "8":
return "Minnowism";
case "9":
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
case "4":
return "Blossom of Spring";
case "2":
return "Bone Basher";
case "3":
return "Bone Vizier";
case "7":
return "Chief Ruk'utan";
case "9":
return "Beast Mode";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Titanium Bauble";
case "5":
return "Clearing Bellow";
case "6":
return "Dodge";
default: return "";
}
case "1":
switch($cardID[5]) {
case "3":
return "Wrecking Ball";
case "5":
return "Pack Call";
case "8":
return "Muscle Mutt";
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
case "D":
switch($cardID[1]) {
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
case "3":
return 0;
case "1":
return 2;
case "2":
return 3;
case "4":
return 0;
case "5":
return 0;
case "6":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "5":
return 0;
case "3":
return 0;
case "4":
return 0;
case "6":
return 0;
default: return 1;
}
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
case "0":
return 2;
case "1":
return 3;
case "2":
return 2;
case "3":
return 0;
case "4":
return 0;
case "5":
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
default: return 1;
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "1":
return 0;
case "0":
return 3;
case "2":
return 0;
case "3":
return 0;
case "4":
return 2;
case "6":
return 3;
case "8":
return 2;
case "9":
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
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
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
case "8":
return 0;
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
case "4":
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
case "5":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 0;
case "2":
return 0;
case "5":
return 2;
case "7":
return 2;
case "8":
return 3;
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
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 0;
case "2":
return 0;
case "3":
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 0;
case "5":
return 0;
case "6":
return 0;
case "9":
return 2;
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
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
case "8":
return 3;
case "9":
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
case "4":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "5":
return 0;
case "6":
return 0;
case "8":
return 3;
case "9":
return 2;
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
case "5":
return 0;
case "6":
return 2;
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
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
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "2":
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
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 0;
case "2":
return 0;
case "4":
return 0;
case "7":
return 0;
case "8":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "3":
return 2;
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
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
case "0":
switch($cardID[5]) {
case "0":
return 3;
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
case "7":
return 2;
case "8":
return 3;
case "9":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
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
case "8":
return 2;
case "9":
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
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
case "5":
return 3;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
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
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 3;
case "3":
return 2;
case "4":
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
case "1":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
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
case "8":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "1":
return 2;
case "2":
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
case "5":
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
case "8":
return 0;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "2":
return 3;
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
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
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
case "4":
return 2;
case "5":
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 0;
case "3":
return 0;
case "0":
return 3;
case "2":
return 0;
case "4":
return 0;
case "5":
return 0;
case "6":
return 0;
case "7":
return 2;
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "2":
return 0;
case "3":
return 0;
case "0":
return 2;
case "1":
return 3;
case "4":
return 0;
case "5":
return 0;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "4":
return 0;
case "5":
return 0;
case "8":
return 0;
case "9":
return 0;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "6":
return 0;
case "7":
return 0;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "6":
return 0;
case "8":
return 0;
case "0":
return 2;
case "1":
return 3;
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 0;
case "7":
return 0;
case "9":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "8":
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
case "7":
return 0;
case "9":
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
case "5":
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
return 3;
case "5":
return 2;
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
case "8":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 2;
case "3":
return 2;
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
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "8":
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
case "7":
return 0;
case "9":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 0;
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "7":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
case "3":
return 3;
case "4":
return 2;
case "7":
return 2;
case "8":
return 3;
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
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "8":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
case "4":
return 2;
case "5":
return 2;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "8":
return 0;
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
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "7":
return 0;
case "8":
return 0;
case "9":
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
case "4":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 3;
case "4":
return 2;
case "6":
return 2;
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 2;
case "3":
return 3;
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
case "8":
switch($cardID[5]) {
case "1":
return 0;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "6":
return 3;
case "7":
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
case "E":
switch($cardID[1]) {
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return 0;
case "0":
return 2;
case "1":
return 3;
case "3":
return 0;
case "4":
return 0;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "7":
return 2;
case "1":
return 2;
case "2":
return 3;
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
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "2":
return 0;
case "3":
return 0;
case "4":
return 0;
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
case "0":
return 2;
case "1":
return 3;
case "2":
return 0;
case "3":
return 0;
case "4":
return 0;
case "5":
return 0;
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
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
case "3":
return 0;
case "6":
return 3;
case "8":
return 2;
case "9":
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
case "0":
return 3;
case "1":
return 0;
case "2":
return 0;
case "3":
return 0;
case "4":
return 0;
case "5":
return 3;
case "6":
return 2;
case "9":
return 2;
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
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "2":
return 0;
case "3":
return 0;
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
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "1":
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
case "9":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "3":
return 2;
case "4":
return 3;
case "5":
return 0;
case "6":
return 0;
case "8":
return 3;
default: return 1;
}
case "2":
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
case "3":
return 3;
case "4":
return 0;
case "5":
return 0;
case "6":
return 3;
case "7":
return 3;
case "9":
return 2;
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
case "7":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
case "2":
return 3;
case "3":
return 0;
case "4":
return 0;
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
case "3":
switch($cardID[5]) {
case "6":
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
case "8":
return 2;
case "9":
return 2;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 0;
case "3":
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
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 0;
case "2":
return 3;
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
case "1":
return 2;
case "2":
return 3;
case "3":
return 0;
case "4":
return 2;
case "5":
return 2;
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
case "9":
return 2;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
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
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
case "9":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
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
case "9":
switch($cardID[5]) {
case "6":
return 0;
case "4":
return 0;
case "7":
return 0;
case "0":
return 2;
case "1":
return 2;
case "2":
return 3;
case "3":
return 2;
case "5":
return 0;
default: return 1;
}
case "1":
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
case "4":
return 0;
case "3":
return 0;
case "1":
return 2;
case "2":
return 3;
case "5":
return 0;
case "8":
return 3;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "3":
return 0;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 3;
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
case "7":
return 0;
default: return 1;
}
case "4":
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
case "1":
return 2;
case "2":
return 3;
case "4":
return 2;
case "5":
return 3;
case "6":
return 2;
case "7":
return 3;
case "8":
return 3;
case "9":
return 2;
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
case "9":
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
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
case "0":
return 2;
case "1":
return 3;
case "9":
return 0;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "9":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 0;
case "3":
return 0;
case "6":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "1":
return 0;
case "2":
return 0;
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
case "9":
return 0;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "4":
return 2;
case "6":
return 2;
case "7":
return 3;
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
default: return 1;
}
case "3":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "1":
return 3;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "9":
return 2;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "0":
return 3;
default: return 1;
}
default: return 1;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "9":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "1":
return 0;
case "2":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "8":
return 0;
case "9":
return 2;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
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
case "2":
switch($cardID[4]) {
case "1":
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
case "4":
return 2;
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "5":
return 0;
default: return 1;
}
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
default: return 1;
}
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "0":
switch($cardID[5]) {
case "3":
return 0;
case "2":
return 0;
case "7":
return 2;
case "8":
return 3;
case "9":
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
case "0":
return 2;
case "1":
return 3;
case "3":
return 2;
case "4":
return 3;
case "5":
return 0;
case "6":
return 3;
case "8":
return 2;
case "9":
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
case "6":
return 0;
case "7":
return 0;
case "8":
return 3;
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
case "3":
return 2;
case "4":
return 3;
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
case "8":
return 3;
case "9":
return 3;
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
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
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
case "9":
return 2;
default: return 1;
}
case "9":
switch($cardID[5]) {
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
case "1":
return 0;
case "2":
return 0;
case "3":
return 0;
case "4":
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
case "4":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
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
case "8":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "4":
return 0;
case "5":
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
case "A":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
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
default: return 1;
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 3;
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
case "7":
return 3;
case "9":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "5":
return 2;
case "6":
return 3;
case "7":
return 2;
case "8":
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
case "5":
return 2;
case "7":
return 3;
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
case "6":
return 3;
case "7":
return 2;
case "9":
return 2;
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
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
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
default: return 1;
}
case "1":
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
case "0":
return 2;
case "1":
return 3;
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
case "9":
return 2;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "1":
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
case "5":
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
case "8":
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
default: return 1;
}
default: return 1;
}
default: return 1;
}
default: return 1;
}
case "M":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "6":
return 2;
case "0":
return 2;
case "1":
return 3;
case "2":
return 2;
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "0":
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
return 2;
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "9":
return 2;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 3;
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
case "9":
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
return 2;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "7":
return 2;
case "8":
return 3;
default: return 1;
}
case "4":
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
case "5":
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
case "0":
return 0;
case "1":
return 0;
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
case "7":
return 2;
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 0;
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
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "2":
return 2;
case "3":
return 3;
case "4":
return 0;
case "5":
return 0;
case "6":
return 0;
case "7":
return 0;
case "8":
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
case "9":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
case "2":
return 0;
case "3":
return 2;
case "4":
return 2;
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
case "9":
return 2;
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
case "5":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 3;
case "3":
return 0;
case "4":
return 0;
case "5":
return 0;
case "6":
return 3;
case "7":
return 2;
case "8":
return 3;
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
case "7":
return 0;
case "8":
return 0;
case "9":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "6":
return 2;
case "7":
return 3;
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
case "8":
return 3;
case "9":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "0":
return 0;
case "1":
return 0;
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
case "9":
return 0;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "0":
return 0;
case "3":
return 2;
case "4":
return 3;
case "6":
return 2;
case "7":
return 3;
case "8":
return 0;
case "9":
return 0;
default: return 1;
}
case "4":
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
case "7":
return 3;
case "9":
return 2;
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
case "9":
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
case "4":
return 0;
case "2":
return 0;
case "3":
return 0;
case "7":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "7":
return 3;
case "5":
return 3;
case "6":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "5":
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
default: return 1;
}
}

function GeneratedCardCost($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
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
case "7":
return 1;
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
case "2":
switch($cardID[5]) {
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
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return 7;
case "4":
return 5;
case "5":
return 6;
case "6":
return 2;
case "7":
return 3;
case "8":
return 5;
case "9":
return 5;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 5;
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
return 4;
case "8":
return 4;
case "9":
return 4;
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
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
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
case "9":
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
default: return 0;
}
case "1":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "9":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
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
return 1;
case "1":
return 1;
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
case "4":
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
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 3;
case "4":
return 2;
case "5":
return 2;
case "6":
return 2;
default: return 0;
}
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
case "0":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "2":
return 3;
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
case "2":
switch($cardID[5]) {
case "1":
return 3;
case "2":
return 3;
case "3":
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
case "6":
return 1;
case "8":
return 2;
case "9":
return XX;
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
case "7":
return 1;
case "8":
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
case "3":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "5":
return 2;
case "6":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "1":
return 1;
case "2":
return 1;
case "3":
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
case "7":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 6;
case "2":
return 9;
case "4":
return 1;
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
case "9":
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
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
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
case "9":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 1;
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
case "9":
return 2;
default: return 0;
}
case "3":
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
case "5":
switch($cardID[5]) {
case "9":
return 2;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "1":
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
case "7":
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
return 2;
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
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
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
case "2":
switch($cardID[5]) {
case "2":
return 2;
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
case "4":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "3":
return 1;
case "5":
return 1;
case "9":
return 1;
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
case "7":
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
case "9":
return 1;
default: return 0;
}
case "8":
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
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "5":
return 2;
case "6":
return 3;
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "7":
return 3;
case "8":
return 2;
case "9":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
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
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "8":
return 3;
case "9":
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
return 1;
case "4":
return 1;
case "5":
return 1;
case "6":
return 6;
case "7":
return 6;
case "8":
return 6;
case "9":
return 2;
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
case "3":
return 2;
case "4":
return 2;
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
case "6":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
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
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "8":
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
case "9":
switch($cardID[5]) {
case "0":
return 1;
case "2":
return 4;
case "3":
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
case "2":
return 2;
case "5":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
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
return 3;
case "8":
return 3;
case "9":
return 3;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 1;
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
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 1;
case "4":
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
case "0":
switch($cardID[5]) {
case "7":
return 3;
case "8":
return 3;
case "9":
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
case "2":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "6":
return 4;
case "7":
return 7;
case "8":
return 3;
case "9":
return 9;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 9;
case "1":
return 9;
case "2":
return 3;
case "3":
return 3;
case "4":
return 3;
case "5":
return 4;
case "6":
return 4;
case "7":
return 4;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 2;
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
case "7":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "4":
return 1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "3":
return 2;
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
case "9":
switch($cardID[5]) {
case "0":
return 1;
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
case "0":
switch($cardID[5]) {
case "3":
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
return 2;
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
default: return 0;
}
case "2":
switch($cardID[5]) {
case "3":
return 1;
case "5":
return 1;
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
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "2":
return 3;
case "3":
return 2;
case "4":
return 3;
case "8":
return 1;
case "9":
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
case "6":
switch($cardID[5]) {
case "2":
return 1;
case "4":
return 1;
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "7":
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
case "8":
return 4;
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "4":
return 2;
case "5":
return 6;
case "6":
return 4;
case "7":
return 2;
case "8":
return 2;
case "9":
return 2;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 4;
case "2":
return 4;
case "3":
return 4;
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
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "2":
return 4;
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
case "4":
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
case "6":
switch($cardID[5]) {
case "3":
return 2;
case "5":
return 4;
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
case "8":
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
case "5":
return 1;
case "6":
return 1;
case "7":
return 1;
default: return 0;
}
case "9":
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
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "9":
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
case "2":
switch($cardID[5]) {
case "3":
return 3;
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
return 2;
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
case "5":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "7":
return 2;
case "8":
return 1;
case "9":
return 1;
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
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
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
case "8":
switch($cardID[5]) {
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
case "1":
return 1;
case "2":
return 2;
case "5":
return 3;
case "6":
return 3;
case "7":
return 3;
case "8":
return 6;
case "9":
return 6;
default: return 0;
}
default: return 0;
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
case "3":
return 1;
case "4":
return 1;
case "5":
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
default: return 0;
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
default: return 0;
}
case "3":
switch($cardID[5]) {
case "1":
return XX;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
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
case "5":
switch($cardID[5]) {
case "0":
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
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
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
case "E":
switch($cardID[1]) {
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return 1;
case "4":
return 4;
case "5":
return 3;
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
case "1":
switch($cardID[5]) {
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
case "6":
return 6;
case "7":
return 6;
case "8":
return 6;
case "9":
return 4;
default: return 0;
}
case "2":
switch($cardID[5]) {
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
case "3":
switch($cardID[5]) {
case "0":
return 2;
case "5":
return 1;
case "6":
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
case "0":
return 1;
case "1":
return 1;
case "4":
return 2;
case "7":
return 1;
case "8":
return 1;
case "9":
return 1;
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
return 2;
default: return 0;
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
case "8":
return 1;
case "9":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 2;
case "2":
return 1;
case "3":
return 2;
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
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
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
case "4":
return 2;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
case "8":
return 3;
case "9":
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
case "0":
return 1;
case "1":
return 1;
case "2":
return 1;
case "6":
return 2;
case "8":
return 2;
case "9":
return 2;
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
case "0":
return 2;
case "1":
return 2;
case "2":
return 2;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "5":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
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
case "0":
return 1;
case "5":
return 3;
case "6":
return 4;
case "7":
return 4;
case "8":
return 4;
case "9":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return 4;
case "1":
return 4;
case "9":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "2":
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
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return 2;
case "4":
return 1;
case "8":
return 2;
case "9":
return 2;
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
default: return 0;
}
case "2":
switch($cardID[5]) {
case "1":
return 10;
case "2":
return X3;
case "3":
return 3;
case "4":
return 6;
case "5":
return 6;
case "6":
return 6;
case "7":
return 7;
case "8":
return 7;
case "9":
return 7;
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
return 3;
case "4":
return 3;
case "5":
return 3;
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
case "7":
switch($cardID[5]) {
case "0":
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
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 2;
case "8":
return 2;
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
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return 3;
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
default: return 0;
}
case "2":
switch($cardID[5]) {
case "2":
return 1;
case "3":
return 2;
case "4":
return X;
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
case "9":
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
return 2;
case "9":
return 3;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 2;
case "2":
return 2;
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
case "9":
return 3;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 3;
case "5":
return 3;
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
case "0":
switch($cardID[5]) {
case "6":
return 6;
case "7":
return 5;
case "8":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
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
case "8":
return 1;
case "9":
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
case "3":
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
case "5":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "9":
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
case "8":
switch($cardID[5]) {
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "6":
return 2;
case "7":
return 1;
case "9":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 1;
case "3":
return 1;
case "5":
return 1;
case "6":
return 1;
case "9":
return 1;
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
case "5":
return 3;
case "6":
return 1;
case "7":
return 1;
case "8":
return 1;
case "9":
return XX;
default: return 0;
}
case "1":
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
default: return 0;
}
case "2":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
case "6":
return 3;
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
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
case "8":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 1;
case "2":
return 1;
case "3":
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
case "6":
switch($cardID[5]) {
case "1":
return 1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "8":
return 3;
case "9":
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
case "8":
switch($cardID[5]) {
case "0":
return 1;
case "1":
return 1;
case "7":
return 2;
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
case "1":
switch($cardID[5]) {
case "8":
return 1;
case "9":
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
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 1;
case "8":
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
return 1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "2":
return 1;
case "6":
return 2;
case "7":
return 2;
case "8":
return 2;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 1;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return 1;
default: return 0;
}
case "4":
switch($cardID[5]) {
case "5":
return 2;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "4":
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
case "8":
switch($cardID[5]) {
case "3":
return 2;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "5":
return 1;
case "8":
return 2;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 2;
case "4":
return 2;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "1":
return 2;
case "2":
return 2;
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
default: return 0;
}
}

function GeneratedCardSubtype($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "D":
switch($cardID[1]) {
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "7":
return "Chest";
case "8":
return "Arms";
case "9":
return "Legs";
case "3":
return "Aura";
case "4":
return "Head";
case "5":
return "Off-Hand";
case "6":
return "Head";
default: return "";
}
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
case "2":
return "Invocation";
case "3":
return "Chest";
case "4":
return "Arms";
case "7":
return "Aura";
case "8":
return "Aura";
case "9":
return "Aura";
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
case "4":
switch($cardID[5]) {
case "0":
return "Item";
case "1":
return "Item";
case "2":
return "Item";
case "3":
return "Item";
case "4":
return "Aura";
case "6":
return "Aura";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "1":
return "Aura";
case "2":
return "Staff";
case "3":
return "Orb";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "1":
return "Item";
case "2":
return "Item";
case "4":
return "Young";
case "5":
return "Dagger";
case "7":
return "Legs";
case "8":
return "Head";
default: return "";
}
case "5":
switch($cardID[5]) {
case "1":
return "Bow";
case "2":
return "Arms";
case "3":
return "Arrow";
case "4":
return "Arrow";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
case "9":
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
case "7":
switch($cardID[5]) {
case "1":
return "Head";
case "2":
return "Book";
case "5":
return "Aura";
case "9":
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
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Royal,Young";
case "2":
return "Ash";
case "3":
return "Ash";
case "4":
return "Ash";
case "5":
return "Rock";
case "6":
return "Legs";
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
case "2":
switch($cardID[5]) {
case "5":
return "Young";
case "6":
return "Off-Hand";
case "7":
return "Off-Hand";
case "9":
return "Aura";
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
case "4":
switch($cardID[5]) {
case "5":
return "Chest";
case "6":
return "Arms";
case "8":
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
case "6":
switch($cardID[5]) {
case "6":
return "Item";
case "7":
return "Sword";
case "8":
return "Axe";
case "9":
return "Dagger";
default: return "";
}
case "7":
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
case "8":
switch($cardID[5]) {
case "8":
return "Gun";
case "9":
return "Arms";
default: return "";
}
case "9":
switch($cardID[5]) {
case "2":
return "Construct";
case "3":
return "Item";
case "4":
return "Item";
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
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Young";
case "2":
return "Sword";
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
case "0":
return "Gem";
case "2":
return "Young";
case "3":
return "Club";
case "4":
return "Legs";
case "5":
return "Chest";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Young";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Hammer";
case "1":
return "Chest";
case "2":
return "Head";
case "6":
return "Aura";
case "7":
return "Aura";
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
case "8":
return "Dagger";
case "9":
return "Head";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Arms";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "4":
return "Young";
case "5":
return "Sword";
case "6":
return "Arms";
case "7":
return "Legs";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Chest";
case "1":
return "Head";
case "2":
return "Chest";
case "3":
return "Arms";
case "4":
return "Legs";
case "5":
return "Head";
case "6":
return "Chest";
case "7":
return "Arms";
case "8":
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return "Club";
case "0":
return "Gem";
case "2":
return "Young";
case "4":
return "Claw";
case "5":
return "Claw";
case "6":
return "Head";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Hammer";
case "4":
return "Hammer";
case "5":
return "Arms";
case "8":
return "Aura";
case "9":
return "Aura";
default: return "";
}
case "4":
switch($cardID[5]) {
case "4":
return "Aura";
case "8":
return "Dagger";
case "9":
return "Dagger";
case "0":
return "Aura";
case "6":
return "Young";
case "7":
return "Young";
default: return "";
}
case "7":
switch($cardID[5]) {
case "8":
return "Sword";
case "5":
return "Aura";
case "7":
return "Young";
case "9":
return "Sword";
default: return "";
}
case "3":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "8":
return "Aura";
case "9":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Sword";
case "1":
return "Dagger";
case "2":
return "Dagger";
case "3":
return "Legs";
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
case "7":
return "Young";
case "9":
return "Young";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "8":
return "Chest";
case "7":
return "Sword";
case "9":
return "Arms";
default: return "";
}
case "9":
switch($cardID[5]) {
case "6":
return "Aura";
case "7":
return "Item";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Pistol";
case "1":
return "Gun";
case "2":
return "Head";
case "4":
return "Item";
case "5":
return "Item";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Bow";
case "1":
return "Bow";
case "2":
return "Legs";
case "3":
return "Arrow";
case "6":
return "Trap";
case "7":
return "Trap";
case "8":
return "Trap";
case "9":
return "Arrow";
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
case "5":
switch($cardID[5]) {
case "7":
return "Aura";
case "9":
return "Staff";
default: return "";
}
case "1":
switch($cardID[5]) {
case "8":
return "Young";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Sword";
case "1":
return "Chest";
case "4":
return "Aura";
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
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "Aura";
case "3":
return "Head";
case "4":
return "Head";
case "6":
return "Arrow";
case "7":
return "Arrow";
case "8":
return "Arrow";
default: return "";
}
case "0":
switch($cardID[5]) {
case "1":
return "Item";
case "2":
return "Hammer";
case "3":
return "Off-Hand";
case "4":
return "Off-Hand";
case "6":
return "Aura";
case "7":
return "Aura";
case "8":
return "Aura";
default: return "";
}
case "2":
switch($cardID[5]) {
case "2":
return "Sword";
case "3":
return "Sword";
case "4":
return "Legs";
case "5":
return "Legs";
case "6":
return "Aura";
default: return "";
}
case "3":
switch($cardID[5]) {
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
default: return "";
}
case "0":
switch($cardID[4]) {
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
case "3":
switch($cardID[5]) {
case "0":
return "Aura";
case "2":
return "Young";
case "3":
return "Bow";
case "4":
return "Bow";
case "5":
return "Arrow";
case "6":
return "Arrow";
case "8":
return "Arrow";
case "9":
return "Arrow";
default: return "";
}
case "4":
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
case "6":
switch($cardID[5]) {
case "0":
return "Arrow";
case "1":
return "Arrow";
case "3":
return "Young";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Aura";
case "5":
return "Head";
case "6":
return "Head";
case "7":
return "Aura";
default: return "";
}
case "4":
switch($cardID[5]) {
case "3":
return "Item";
case "4":
return "Chest";
case "5":
return "Chest";
case "6":
return "Aura";
default: return "";
}
case "7":
switch($cardID[5]) {
case "2":
return "Item";
case "3":
return "Arms";
case "4":
return "Arms";
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
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "6":
return "Aura";
case "7":
return "Head";
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
case "1":
switch($cardID[5]) {
case "8":
return "Off-Hand";
case "9":
return "Young";
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
case "5":
switch($cardID[5]) {
case "3":
return "Head";
default: return "";
}
case "6":
switch($cardID[5]) {
case "9":
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
case "8":
switch($cardID[5]) {
case "5":
return "Young";
case "6":
return "Arms";
case "7":
return "Bow";
case "8":
return "Arrow";
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
default: return "";
}
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "6":
return "Aura";
case "4":
return "Item";
case "7":
return "Aura";
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
default: return "";
}
case "1":
switch($cardID[5]) {
case "9":
return "Aura";
default: return "";
}
case "5":
switch($cardID[5]) {
case "3":
return "Aura";
case "5":
return "Off-Hand";
default: return "";
}
case "0":
switch($cardID[5]) {
case "3":
return "Arms";
case "7":
return "Aura";
case "8":
return "Aura";
case "9":
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
case "3":
switch($cardID[5]) {
case "1":
return "Aura";
case "2":
return "Aura";
case "3":
return "Aura";
case "7":
return "Head";
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
default: return "";
}
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
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "6":
return "Young";
case "7":
return "Young";
case "9":
return "Head";
default: return "";
}
case "9":
switch($cardID[5]) {
case "0":
return "Young";
case "3":
return "Bow";
case "6":
return "Quiver";
default: return "";
}
case "0":
switch($cardID[5]) {
case "2":
return "Young";
case "3":
return "Young";
case "5":
return "Dagger";
case "7":
return "Dagger";
case "9":
return "Dagger";
default: return "";
}
case "1":
switch($cardID[5]) {
case "1":
return "Chest";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Arrow";
case "3":
return "Trap";
default: return "";
}
case "2":
switch($cardID[5]) {
case "4":
return "Arrow";
default: return "";
}
case "3":
switch($cardID[5]) {
case "9":
return "Arms";
default: return "";
}
case "4":
switch($cardID[5]) {
case "1":
return "Arms";
default: return "";
}
case "5":
switch($cardID[5]) {
case "8":
return "Arms";
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
case "0":
return "Gem";
case "2":
return "Young";
case "3":
return "Pistol";
case "4":
return "Chest";
case "5":
return "Legs";
case "7":
return "Item";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Item";
case "7":
return "Item";
case "8":
return "Item";
case "9":
return "Item";
default: return "";
}
case "3":
switch($cardID[5]) {
case "5":
return "Item";
case "6":
return "Item";
case "7":
return "Item";
case "9":
return "Young";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Bow";
case "1":
return "Head";
case "2":
return "Arms";
case "3":
return "Arrow";
case "5":
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
case "7":
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
case "6":
return "Young";
case "7":
return "Sword";
case "8":
return "Arms";
case "9":
return "Head";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
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
case "1":
switch($cardID[5]) {
case "2":
return "Aura";
case "4":
return "Young";
case "5":
return "Staff";
case "6":
return "Legs";
case "7":
return "Chest";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Head";
case "1":
return "Head";
case "2":
return "Chest";
case "3":
return "Arms";
case "4":
return "Legs";
case "5":
return "Head";
case "6":
return "Chest";
case "7":
return "Arms";
case "8":
return "Legs";
default: return "";
}
case "6":
switch($cardID[5]) {
case "2":
return "Aura";
case "3":
return "Item";
case "7":
return "Aura";
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
case "M":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Landmark";
case "2":
return "Young";
case "3":
return "Scepter";
case "5":
return "Aura";
case "6":
return "Aura";
default: return "";
}
case "1":
switch($cardID[5]) {
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
case "0":
return "Young";
case "1":
return "Sword";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Chest";
case "1":
return "Head";
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
case "9":
switch($cardID[5]) {
case "0":
return "Arms";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "Aura";
case "5":
return "Axe";
case "6":
return "Axe";
case "7":
return "Legs";
case "8":
return "Arms";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Young";
case "1":
return "Flail";
case "2":
return "Legs";
default: return "";
}
case "5":
switch($cardID[5]) {
case "4":
return "Young";
case "5":
return "Sword";
case "7":
return "Aura";
default: return "";
}
case "8":
switch($cardID[5]) {
case "6":
return "Aura";
case "7":
return "Chest";
case "8":
return "Head";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "9":
return "Demon,Ally";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Demon,Ally";
case "1":
return "Axe";
case "9":
return "Scythe";
default: return "";
}
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
case "4":
switch($cardID[5]) {
case "0":
return "Legs";
case "1":
return "Head";
case "2":
return "Chest";
case "3":
return "Arms";
case "4":
return "Legs";
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
case "U":
switch($cardID[1]) {
case "P":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "0":
return "Aura";
case "1":
return "Arms";
case "2":
return "Legs";
case "8":
return "Arms";
case "9":
return "Legs";
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
case "3":
switch($cardID[5]) {
case "6":
return "Head";
case "7":
return "Head";
case "8":
return "Aura";
case "9":
return "Affliction,Aura";
default: return "";
}
case "4":
switch($cardID[5]) {
case "0":
return "Aura";
default: return "";
}
case "6":
switch($cardID[5]) {
case "5":
return "Staff";
case "6":
return "Chest";
case "7":
return "Chest";
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
default: return "";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "Gem";
case "2":
return "Young";
case "3":
return "Scepter";
case "4":
return "Arms";
case "5":
return "Aura";
case "6":
return "Invocation";
case "7":
return "Invocation";
case "8":
return "Invocation";
case "9":
return "Invocation";
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
case "4":
switch($cardID[5]) {
case "2":
return "Dragon,Ally";
case "3":
return "Ash";
case "5":
return "Young";
case "6":
return "Sword";
case "7":
return "Arms";
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
default: return "";
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "8":
return "Aura";
case "9":
return "Aura";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Aura";
case "5":
return "Invocation,Placeholder Card";
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
case "V":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return "Club";
case "3":
return "Head";
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
case "WTR001": return 40;
case "CRU001": return 40;
case "WTR038": return 40;
case "CRU022": return 40;
case "WTR076": return 40;
case "CRU045": return 40;
case "OUT045": return 40;
case "WTR113": return 40;
case "CRU076": return 40;
case "ARC001": return 40;
case "CRU098": return 40;
case "ARC038": return 40;
case "CRU119": return 40;
case "OUT089": return 40;
case "ARC075": return 40;
case "CRU138": return 40;
case "ARC113": return 30;
case "CRU158": return 30;
case "ARC114": return 15;
case "CRU002": return 19;
case "CRU047": return 17;
case "OUT047": return 17;
case "MON001": return 40;
case "MON029": return 40;
case "MON119": return 40;
case "MON153": return 40;
case "MON219": return 6;
case "MON220": return 6;
case "ELE001": return 40;
case "ELE031": return 40;
case "ELE062": return 40;
case "EVR017": return 40;
case "EVR019": return 21;
case "EVR120": return 18;
case "UPR103": return 18;
case "UPR001": return 40;
case "UPR042": return 1;
case "UPR044": return 40;
case "UPR102": return 36;
case "DYN001": return 15;
case "DYN025": return 22;
case "DYN113": return 40;
case "OUT001": return 40;
case "OUT003": return 19;
default: return 20;}
}

function GeneratedRarity($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
case "D":
switch($cardID[1]) {
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
case "4":
return "L";
case "5":
return "R";
case "6":
return "R";
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
case "1":
switch($cardID[5]) {
case "2":
return "L";
case "3":
return "M";
case "4":
return "R";
case "5":
return "M";
case "6":
return "M";
case "7":
return "M";
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "1":
return "L";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "R";
case "7":
return "M";
case "8":
return "M";
case "9":
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
default: return "C";
}
case "2":
switch($cardID[5]) {
case "5":
return "M";
case "6":
return "M";
case "7":
return "R";
case "8":
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
case "4":
return "R";
case "5":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "5":
return "M";
case "6":
return "R";
case "7":
return "M";
case "8":
return "M";
case "9":
return "M";
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
default: return "C";
}
case "6":
switch($cardID[5]) {
case "6":
return "L";
case "7":
return "M";
case "8":
return "M";
case "9":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "0":
return "R";
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
default: return "C";
}
case "8":
switch($cardID[5]) {
case "8":
return "M";
case "9":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "4":
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
default: return "C";
}
case "1":
switch($cardID[5]) {
case "3":
return "M";
case "4":
return "R";
case "5":
return "R";
case "6":
return "R";
case "7":
return "L";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "2":
return "M";
case "3":
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
case "5":
switch($cardID[5]) {
case "1":
return "M";
case "2":
return "R";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
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
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "1":
return "M";
case "2":
return "R";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
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
case "0":
return "R";
case "1":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "2":
return "M";
case "3":
return "R";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
case "7":
return "R";
case "8":
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
case "V":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "M";
case "2":
return "M";
case "7":
return "M";
case "8":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
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
case "0":
return "F";
case "1":
return "T";
case "2":
return "T";
case "3":
return "T";
case "4":
return "L";
case "6":
return "M";
case "7":
return "M";
case "8":
return "S";
case "9":
return "S";
default: return "C";
}
case "1":
switch($cardID[5]) {
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
case "8":
return "T";
case "9":
return "T";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "L";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "S";
case "7":
return "S";
case "8":
return "R";
case "9":
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
case "7":
switch($cardID[5]) {
case "5":
return "T";
case "6":
return "T";
case "7":
return "T";
case "8":
return "T";
case "9":
return "L";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "1":
return "M";
case "2":
return "M";
case "3":
return "S";
case "4":
return "S";
case "5":
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
default: return "C";
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "3":
return "T";
case "4":
return "T";
case "5":
return "T";
case "6":
return "L";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "S";
case "1":
return "S";
case "2":
return "S";
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
case "3":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
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
case "1":
return "S";
case "2":
return "S";
case "3":
return "S";
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
case "E":
switch($cardID[1]) {
case "L":
switch($cardID[2]) {
case "E":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "L";
case "5":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "7":
return "T";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "L";
case "5":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "M";
case "4":
return "L";
case "6":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "1":
return "T";
case "2":
return "T";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
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
case "1":
return "T";
case "2":
return "T";
case "3":
return "T";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
case "7":
return "M";
case "8":
return "R";
case "9":
return "R";
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
case "6":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "T";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
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
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return "T";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "T";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "L";
case "7":
return "M";
case "8":
return "M";
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
default: return "C";
}
case "4":
switch($cardID[5]) {
case "4":
return "L";
case "6":
return "M";
case "7":
return "M";
case "8":
return "R";
case "9":
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
case "7":
switch($cardID[5]) {
case "3":
return "L";
case "5":
return "M";
case "6":
return "M";
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
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "4":
return "L";
case "5":
return "R";
case "6":
return "M";
case "7":
return "M";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
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
return "M";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
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
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
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
case "7":
return "M";
case "8":
return "L";
case "9":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
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
case "5":
switch($cardID[5]) {
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "9":
return "M";
default: return "C";
}
case "7":
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
default: return "C";
}
case "8":
switch($cardID[5]) {
case "5":
return "M ";
case "6":
return "L";
case "7":
return "M";
case "8":
return "M";
case "9":
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
default: return "C";
}
case "O":
switch($cardID[1]) {
case "U":
switch($cardID[2]) {
case "T":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "5":
return "T";
case "6":
return "T";
case "7":
return "T";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "9":
return "T";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "T";
case "3":
return "T";
case "6":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "1":
return "T";
case "2":
return "T";
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
case "9":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "L";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "R";
case "3":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
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
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "M";
case "3":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "9":
return "L";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "2":
return "M";
case "5":
return "R";
case "8":
return "R";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "9":
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
case "4":
return "R";
case "5":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "3":
return "M";
case "6":
return "M";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "5":
return "R";
case "8":
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
case "5":
return "T";
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
case "C":
switch($cardID[1]) {
case "R":
switch($cardID[2]) {
case "U":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "9":
return "R";
case "7":
return "R";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "2":
return "R";
case "4":
return "R";
case "5":
return "R";
case "6":
return "M";
case "7":
return "M";
case "8":
return "M";
case "9":
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
case "2":
switch($cardID[5]) {
case "4":
return "R";
case "5":
return "M";
case "6":
return "M";
case "7":
return "M";
case "8":
return "M";
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
case "5":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
case "2":
return "R";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "M";
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
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
case "7":
switch($cardID[5]) {
case "5":
return "R";
case "7":
return "R";
case "9":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
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
case "7":
return "L";
case "9":
return "R";
default: return "C";
}
default: return "C";
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "8":
return "L";
case "7":
return "R";
case "9":
return "M";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "8":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "1":
return "R";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
return "R";
case "7":
return "R";
case "8":
return "R";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
case "8":
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "1":
return "T";
case "2":
return "T";
case "3":
return "T";
case "4":
return "L";
case "6":
return "M";
case "7":
return "M";
case "8":
return "S";
case "9":
return "S";
default: return "C";
}
case "1":
switch($cardID[5]) {
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
case "8":
return "T";
case "9":
return "T";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "L";
case "3":
return "M";
case "4":
return "M";
case "5":
return "S";
case "6":
return "S";
case "7":
return "S";
case "8":
return "R";
case "9":
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
case "7":
switch($cardID[5]) {
case "5":
return "T";
case "6":
return "T";
case "7":
return "T";
case "8":
return "L";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "M";
case "1":
return "M";
case "2":
return "S";
case "3":
return "S";
case "4":
return "S";
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
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "2":
return "T";
case "3":
return "T";
case "4":
return "T";
case "5":
return "T";
case "6":
return "L";
case "8":
return "M";
case "9":
return "M";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "S";
case "1":
return "S";
case "2":
return "S";
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
case "3":
switch($cardID[5]) {
case "0":
return "R";
case "1":
return "R";
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
case "1":
return "S";
case "2":
return "S";
case "3":
return "S";
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
default: return "C";
}
default: return "C";
}
default: return "C";
}
default: return "C";
}
case "M":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "1":
return "T";
case "2":
return "T";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
case "6":
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
case "2":
switch($cardID[5]) {
case "9":
return "T";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "4":
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
case "6":
switch($cardID[5]) {
case "0":
return "L";
case "2":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
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
case "0":
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
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "4":
return "T";
case "5":
return "T";
case "6":
return "T";
case "7":
return "L";
case "9":
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
case "9":
return "T";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "M";
case "3":
return "M";
case "4":
return "M";
case "5":
return "M";
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
case "3":
return "T";
case "4":
return "T";
case "5":
return "T";
case "6":
return "M";
case "7":
return "M";
case "8":
return "M";
case "9":
return "R";
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
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "6":
return "T";
case "7":
return "L";
case "9":
return "L";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "0":
return "L";
case "1":
return "M";
case "2":
return "M";
case "3":
return "M";
case "4":
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
case "9":
return "T";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "T";
case "2":
return "M";
case "3":
return "R";
case "4":
return "R";
case "5":
return "R";
case "9":
return "M";
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
case "4":
switch($cardID[5]) {
case "5":
return "M";
case "6":
return "M";
case "7":
return "M";
case "8":
return "R";
case "9":
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
case "7":
return "R";
case "8":
return "R";
case "9":
return "R";
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
case "1":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "0":
return "T";
case "1":
return "L";
case "3":
return "M";
case "4":
return "M";
case "5":
return "R";
case "6":
return "R";
case "7":
return "R";
case "8":
return "L";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "3":
return "T";
case "1":
return "T";
case "2":
return "T";
case "4":
return "M";
case "5":
return "M";
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
case "2":
switch($cardID[5]) {
case "6":
return "M";
default: return "C";
}
case "3":
switch($cardID[5]) {
case "6":
return "L";
case "8":
return "M";
case "9":
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
return "T";
case "6":
return "L";
case "8":
return "M";
case "9":
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
case "8":
switch($cardID[5]) {
case "2":
return "L";
case "3":
return "T";
case "7":
return "M";
case "8":
return "M";
case "9":
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
default: return "C";
}
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return "F";
case "1":
return "T";
case "2":
return "T";
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
case "9":
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
case "2":
return "T";
case "3":
return "T";
case "4":
return "T";
case "5":
return "T";
case "6":
return "T";
case "8":
return "M";
case "9":
return "M";
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
case "6":
return "M";
case "7":
return "M";
case "8":
return "M";
case "9":
return "M";
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
default: return "C";
}
default: return "C";
}
default: return "C";
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
case "7":
return "M";
case "9":
return "R";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "3":
return "R";
case "5":
return "R";
default: return "C";
}
case "2":
switch($cardID[5]) {
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
default: return "C";
}
default: return "C";
}
}

?>