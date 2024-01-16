<?php
function GeneratedCardType($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "9":
return "E";
case "5":
return "T";
case "7":
return "W";
case "6":
return "W";
case "8":
return "E";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "1":
return "T";
case "4":
return "A";
case "2":
return "A";
case "3":
return "A";
case "5":
return "A";
case "9":
return "B";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "6":
return "T";
case "0":
return "E";
case "8":
return "I";
case "1":
return "E";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
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
case "M":
switch($cardID[1]) {
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
return "T";
case "6":
return "AR";
case "4":
return "R";
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
case "5":
switch($cardID[5]) {
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
case "3":
switch($cardID[5]) {
case "5":
return "A";
case "6":
return "A";
case "7":
return "A";
case "9":
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
case "2":
return "C";
case "1":
return "C";
case "9":
return "E";
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
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "4":
return "W";
case "7":
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
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "0":
return "E";
case "6":
return "I";
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
case "1":
switch($cardID[4]) {
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
case "7":
return "E";
default: return "AA";
}
case "0":
switch($cardID[5]) {
case "0":
return "E";
case "5":
return "A";
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
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "2":
return "AR";
case "3":
return "AR";
case "4":
return "AR";
default: return "AA";
}
case "6":
switch($cardID[5]) {
case "2":
return "B";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "5":
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
case "0":
switch($cardID[5]) {
case "2":
return "C";
case "3":
return "C";
case "4":
return "C";
case "0":
return "C";
case "1":
return "C";
default: return "AA";
}
default: return "AA";
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "9":
return "C";
case "4":
return "C";
case "6":
return "C";
case "8":
return "C";
case "5":
return "C";
case "7":
return "C";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "4":
return "C";
case "1":
return "C";
case "7":
return "C";
case "0":
return "C";
case "5":
return "C";
case "2":
return "C";
case "8":
return "C";
case "3":
return "C";
case "9":
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "1":
return "I";
case "2":
return "I";
case "3":
return "I";
case "8":
return "W";
case "9":
return "I";
case "4":
return "W";
case "0":
return "W";
default: return "AA";
}
case "7":
switch($cardID[5]) {
case "0":
return "I";
case "1":
return "I";
case "2":
return "I";
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
case "6":
return "W";
default: return "AA";
}
case "8":
switch($cardID[5]) {
case "4":
return "E";
case "3":
return "E";
case "2":
return "E";
case "5":
return "E";
case "8":
return "E";
case "7":
return "E";
case "6":
return "E";
case "9":
return "E";
default: return "AA";
}
case "9":
switch($cardID[5]) {
case "4":
return "W";
case "0":
return "W";
default: return "AA";
}
default: return "AA";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "W";
case "2":
return "A";
case "3":
return "A";
case "4":
return "A";
default: return "AA";
}
case "2":
switch($cardID[5]) {
case "7":
return "R";
case "0":
return "A";
case "2":
return "A";
case "1":
return "A";
case "8":
return "A";
case "3":
return "A";
default: return "AA";
}
case "1":
switch($cardID[5]) {
case "5":
return "E";
case "4":
return "E";
case "2":
return "W";
case "3":
return "E";
case "6":
return "E";
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
case "R":
switch($cardID[1]) {
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
default: return "AA";
}
default: return "AA";
}
}

function GeneratedAttackValue($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
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
case "9":
return 7;
default: return 0;
}
case "3":
switch($cardID[5]) {
case "4":
return 3;
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
case "9":
return 7;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 6;
case "1":
return 5;
default: return 0;
}
case "8":
switch($cardID[5]) {
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
case "0":
switch($cardID[5]) {
case "6":
return 3;
case "7":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "7":
return 8;
case "0":
return 4;
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
case "2":
switch($cardID[5]) {
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "3":
return 6;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "5":
return 1;
case "9":
return 5;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "6":
return 7;
case "7":
return 6;
case "8":
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
case "A":
switch($cardID[1]) {
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
return X;
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "5":
return 6;
case "4":
return 7;
case "2":
return 5;
case "3":
return 6;
case "7":
return 4;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "1":
return 4;
case "2":
return 4;
case "3":
return 4;
case "7":
return 1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "1":
return 3;
case "3":
return 6;
case "6":
return 7;
case "7":
return 6;
case "8":
return 5;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
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
default: return 0;
}
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 3;
case "0":
return 4;
default: return 0;
}
case "1":
switch($cardID[5]) {
case "2":
return 2;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "4":
return 5;
case "5":
return 4;
case "6":
return 3;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "7":
switch($cardID[5]) {
case "9":
return 3;
case "3":
return 6;
case "4":
return 5;
case "5":
return 4;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "8":
return 0;
case "4":
return 6;
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
case "6":
return 1;
case "7":
return 7;
case "8":
return 6;
case "9":
return 5;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "1":
return 6;
case "2":
return 5;
case "3":
return 4;
case "8":
return 6;
case "9":
return 5;
case "5":
return 4;
case "6":
return 3;
case "7":
return 2;
case "4":
return 2;
case "0":
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

function GeneratedBlockValue($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "9":
return 1;
case "4":
return 0;
case "5":
return -1;
case "7":
return -1;
case "6":
return -1;
case "8":
return 1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "1":
return -1;
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
case "7":
switch($cardID[5]) {
case "6":
return -1;
case "0":
return 1;
case "8":
return -1;
case "1":
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
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
case "7":
return -1;
case "0":
return 2;
case "1":
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
case "4":
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
case "7":
switch($cardID[5]) {
case "5":
return 1;
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
default: return 3;
}
case "8":
switch($cardID[5]) {
case "9":
return 2;
default: return 3;
}
default: return 3;
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "4":
return -1;
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
case "5":
return 2;
case "6":
return 2;
case "7":
return 2;
case "9":
return 4;
default: return 3;
}
case "1":
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return 2;
case "6":
return -1;
case "2":
return -1;
case "1":
return -1;
case "9":
return 2;
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
case "0":
return -1;
case "6":
return 0;
case "2":
return 1;
default: return 3;
}
case "4":
switch($cardID[5]) {
case "6":
return -1;
case "5":
return -1;
case "4":
return 2;
case "8":
return -1;
case "7":
return -1;
default: return 3;
}
case "8":
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
case "9":
switch($cardID[5]) {
case "4":
return -1;
case "7":
return 2;
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
default: return 3;
}
case "1":
switch($cardID[5]) {
case "0":
return 1;
case "6":
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
case "7":
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
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "0":
return -1;
case "1":
return -1;
default: return 3;
}
default: return 3;
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "9":
return -1;
case "4":
return -1;
case "6":
return -1;
case "8":
return -1;
case "5":
return -1;
case "7":
return -1;
default: return 3;
}
case "9":
switch($cardID[5]) {
case "4":
return -1;
case "1":
return -1;
case "7":
return -1;
case "0":
return -1;
case "5":
return -1;
case "2":
return -1;
case "8":
return -1;
case "3":
return -1;
case "9":
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
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
case "4":
return -1;
case "0":
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
case "6":
return -1;
default: return 3;
}
case "8":
switch($cardID[5]) {
case "4":
return -1;
case "3":
return -1;
case "2":
return -1;
case "5":
return -1;
case "8":
return 1;
case "7":
return 1;
case "6":
return 1;
case "9":
return 1;
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
case "4":
return -1;
case "0":
return -1;
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
case "2":
return 2;
case "3":
return 2;
case "4":
return 2;
default: return 3;
}
case "2":
switch($cardID[5]) {
case "7":
return -1;
case "3":
return -1;
default: return 3;
}
case "1":
switch($cardID[5]) {
case "5":
return -1;
case "4":
return -1;
case "2":
return -1;
case "3":
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
case "R":
switch($cardID[1]) {
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
default: return 3;
}
default: return 3;
}
}

function GeneratedCardName($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
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
return "Potion of Deja Vu";
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "9":
return "Adaptive Plating";
case "1":
return "Banneret of Courage";
case "2":
return "Banneret of Gallantry";
case "3":
return "Banneret of Protection";
case "4":
return "Diabolic Offering";
case "5":
return "Diamond";
case "7":
return "Flail of Agony";
case "6":
return "Luminaris, Celestial Fury";
case "8":
return "Shriek Razors";
default: return "";
}
case "7":
switch($cardID[5]) {
case "5":
return "Apocalypse Automaton";
case "4":
return "Demolition Protocol";
case "6":
return "Hyper Driver";
case "0":
return "Hyper-X3";
case "2":
return "Meganetic Protocol";
case "3":
return "Pulsewave Protocol";
case "8":
return "Sigil of Solace";
case "1":
return "Warband of Bellona";
case "7":
return "Zero to Sixty";
default: return "";
}
case "9":
switch($cardID[5]) {
case "1":
return "Cintari Sellsword";
case "4":
return "Double Down";
case "2":
return "Raise an Army";
case "3":
return "Runner Runner";
case "5":
return "Talk a Big Game";
case "9":
return "Test of Strength";
case "6":
return "Wage Gold";
case "7":
return "Wage Gold";
case "8":
return "Wage Gold";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Cintari Sellsword";
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "Agile Windup";
case "4":
return "Agile Windup";
case "5":
return "Agile Windup";
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
case "7":
return "Sheltered Cove";
case "0":
return "Wage Vigor";
case "1":
return "Wage Vigor";
default: return "";
}
case "0":
switch($cardID[5]) {
case "0":
return "Beckon Applause";
case "5":
return "Raise an Army";
case "3":
return "Up the Ante";
default: return "";
}
case "3":
switch($cardID[5]) {
case "4":
return "Cintari Sellsword";
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
default: return "";
}
case "4":
switch($cardID[5]) {
case "3":
return "Mighty Windup";
case "4":
return "Mighty Windup";
case "5":
return "Mighty Windup";
case "9":
return "Wage Might";
default: return "";
}
case "1":
switch($cardID[5]) {
case "2":
return "Take the Upper Hand";
case "3":
return "Take the Upper Hand";
case "4":
return "Take the Upper Hand";
default: return "";
}
case "7":
switch($cardID[5]) {
case "5":
return "Vigor Girth";
case "0":
return "Wage Agility";
case "1":
return "Wage Agility";
default: return "";
}
case "5":
switch($cardID[5]) {
case "0":
return "Wage Might";
case "1":
return "Wage Might";
default: return "";
}
case "8":
switch($cardID[5]) {
case "9":
return "Wage Vigor";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return "Agility";
case "6":
return "Coercive Tendency";
case "4":
return "Cracked Bauble";
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
case "1":
return "Overcome Adversity";
case "3":
return "Stadium Centerpiece";
case "4":
return "Ticket Puncher";
default: return "";
}
case "5":
switch($cardID[5]) {
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
case "3":
switch($cardID[5]) {
case "5":
return "Money Where Ya Mouth Is";
case "6":
return "Money Where Ya Mouth Is";
case "7":
return "Money Where Ya Mouth Is";
case "9":
return "Test of Strength";
default: return "";
}
case "1":
switch($cardID[5]) {
case "6":
return "Wage Gold";
case "7":
return "Wage Gold";
case "8":
return "Wage Gold";
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
case "2":
return "Kayo";
case "1":
return "Kayo, Armed and Dangerous";
case "9":
return "Knucklehead";
case "7":
return "Mini Meataxe";
case "4":
return "Rhinar";
case "3":
return "Rhinar, Reckless Rampage";
default: return "";
}
case "5":
switch($cardID[5]) {
case "1":
return "Aurum Aegis";
case "7":
return "Bet Big";
case "0":
return "Miller's Grindstone";
case "6":
return "Stand Ground";
case "2":
return "Stonewall Impasse";
case "9":
return "The Golden Son";
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
case "4":
return "Smashback Alehorn";
case "8":
return "Victor Goldmane";
case "7":
return "Victor Goldmane, High and Mighty";
default: return "";
}
case "8":
switch($cardID[5]) {
case "6":
return "Bigger Than Big";
case "7":
return "Bigger Than Big";
case "8":
return "Bigger Than Big";
case "9":
return "Pint of Strong and Stout";
default: return "";
}
case "6":
switch($cardID[5]) {
case "0":
return "Boast";
case "8":
return "Stacked in Your Favor";
case "9":
return "Stacked in Your Favor";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Cintari Saber";
case "7":
return "Grains of Bloodspill";
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
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Monstrous Veil";
case "6":
return "No Fear";
case "3":
return "Show No Mercy";
default: return "";
}
case "2":
switch($cardID[5]) {
case "3":
return "Rawhide Rumble";
case "4":
return "Rawhide Rumble";
case "5":
return "Rawhide Rumble";
default: return "";
}
case "7":
switch($cardID[5]) {
case "0":
return "Stacked in Your Favor";
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
case "0":
switch($cardID[5]) {
case "2":
return "Brevant, Civic Protector";
case "3":
return "Melody, Sing-along";
case "4":
return "Professor Teklovossen";
case "0":
return "Squizzy & Floof";
case "1":
return "Squizzy & Floof";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "9":
return "Dash I/O";
case "4":
return "Prism, Advent of Thrones";
case "6":
return "Prism, Awakener of Sol";
case "8":
return "Teklovossen, Esteemed Magnate";
case "5":
return "Vynnset";
case "7":
return "Vynnset, Iron Maiden";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Dash I/O";
case "1":
return "Dash, Database";
case "7":
return "Dash, Database";
case "0":
return "Maxx 'The Hype' Nitro";
case "5":
return "Maxx 'The Hype' Nitro";
case "2":
return "Maxx Nitro";
case "8":
return "Maxx Nitro";
case "3":
return "Teklovossen";
case "9":
return "Teklovossen";
case "6":
return "Teklovossen, Esteemed Magnate";
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "1":
return "Angelic Wrath";
case "2":
return "Angelic Wrath";
case "3":
return "Angelic Wrath";
case "8":
return "Beaming Blade";
case "9":
return "Break of Dawn";
case "4":
return "Hell Hammer";
case "5":
return "Hungering Demigon";
case "6":
return "Hungering Demigon";
case "7":
return "Hungering Demigon";
case "0":
return "Luminaris, Celestial Fury";
default: return "";
}
case "7":
switch($cardID[5]) {
case "9":
return "Banneret of Resilience";
case "0":
return "Break of Dawn";
case "1":
return "Break of Dawn";
case "2":
return "Genesis";
case "3":
return "Herald of Tenacity";
case "4":
return "Herald of Tenacity";
case "5":
return "Herald of Tenacity";
case "6":
return "Jack-o'-lantern";
case "7":
return "Jack-o'-lantern";
case "8":
return "Jack-o'-lantern";
default: return "";
}
case "8":
switch($cardID[5]) {
case "0":
return "Banneret of Salvation";
case "1":
return "Banneret of Vigor";
case "4":
return "Proto Base Arms";
case "3":
return "Proto Base Chest";
case "2":
return "Proto Base Head";
case "5":
return "Proto Base Legs";
case "8":
return "Teklo Base Arms";
case "7":
return "Teklo Base Chest";
case "6":
return "Teklo Base Head";
case "9":
return "Teklo Base Legs";
default: return "";
}
case "5":
switch($cardID[5]) {
case "6":
return "Flail of Agony";
case "7":
return "Vantom Banshee";
case "8":
return "Vantom Banshee";
case "9":
return "Vantom Banshee";
default: return "";
}
case "9":
switch($cardID[5]) {
case "1":
return "Heavy Artillery";
case "2":
return "Heavy Artillery";
case "3":
return "Heavy Artillery";
case "8":
return "Liquid-Cooled Mayhem";
case "9":
return "Liquid-Cooled Mayhem";
case "5":
return "Soup Up";
case "6":
return "Soup Up";
case "7":
return "Soup Up";
case "4":
return "Symbiosis Shot";
case "0":
return "Teklo Leveler";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Banksy";
case "2":
return "Gas Up";
case "3":
return "Gas Up";
case "4":
return "Gas Up";
case "0":
return "Liquid-Cooled Mayhem";
default: return "";
}
case "2":
switch($cardID[5]) {
case "7":
return "Cracked Bauble";
case "4":
return "Mechanical Strength";
case "5":
return "Mechanical Strength";
case "6":
return "Mechanical Strength";
case "0":
return "Song of Sweet Nectar";
case "2":
return "Song of Yesteryears";
case "1":
return "Song of the Wandering Mind";
case "8":
return "Starting Stake";
case "3":
return "Teklo Core";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Fiddle-dee";
case "4":
return "Heart-throb";
case "2":
return "Jinglewood, Smash Hit";
case "3":
return "Nom de Plume";
case "6":
return "Quickstep";
case "7":
return "Song of Jack-be-Quick";
case "8":
return "Song of the Rosen Matador";
case "9":
return "Song of the Shining Knight";
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
default: return "";
}
default: return "";
}
}

function GeneratedPitchValue($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "9":
return 0;
case "1":
return 2;
case "2":
return 2;
case "3":
return 2;
case "4":
return 3;
case "5":
return 0;
case "7":
return 0;
case "6":
return 0;
case "8":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "1":
return 0;
case "2":
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
case "6":
return 0;
case "0":
return 0;
case "2":
return 3;
case "3":
return 2;
case "1":
return 0;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "4":
return 2;
case "5":
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
case "5":
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
return 3;
case "1":
return 2;
case "2":
return 3;
default: return 1;
}
case "4":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 3;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "5":
return 0;
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "0":
return 2;
case "1":
return 3;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return 0;
case "6":
return 3;
case "4":
return 2;
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
case "1":
return 0;
case "3":
return 0;
case "4":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "3":
return 2;
case "4":
return 0;
case "0":
return 3;
default: return 1;
}
case "3":
switch($cardID[5]) {
case "6":
return 2;
case "7":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
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
case "2":
return 0;
case "1":
return 0;
case "9":
return 0;
case "7":
return 0;
case "4":
return 0;
case "3":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "1":
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
case "4":
return 3;
case "8":
return 0;
case "7":
return 0;
default: return 1;
}
case "8":
switch($cardID[5]) {
case "7":
return 2;
case "8":
return 3;
case "9":
return 3;
default: return 1;
}
case "6":
switch($cardID[5]) {
case "0":
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
default: return 1;
}
case "1":
switch($cardID[5]) {
case "0":
return 0;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "4":
return 2;
case "5":
return 3;
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
case "0":
switch($cardID[5]) {
case "2":
return 0;
case "3":
return 0;
case "4":
return 0;
case "0":
return 0;
case "1":
return 0;
default: return 1;
}
default: return 1;
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "9":
return 0;
case "4":
return 0;
case "6":
return 0;
case "8":
return 0;
case "5":
return 0;
case "7":
return 0;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "4":
return 0;
case "1":
return 0;
case "7":
return 0;
case "0":
return 0;
case "5":
return 0;
case "2":
return 0;
case "8":
return 0;
case "3":
return 0;
case "9":
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "8":
return 0;
case "4":
return 0;
case "6":
return 2;
case "7":
return 3;
case "0":
return 0;
default: return 1;
}
case "7":
switch($cardID[5]) {
case "9":
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
return 2;
case "4":
return 0;
case "3":
return 0;
case "2":
return 0;
case "5":
return 0;
case "8":
return 0;
case "7":
return 0;
case "6":
return 0;
case "9":
return 0;
default: return 1;
}
case "5":
switch($cardID[5]) {
case "6":
return 0;
case "8":
return 2;
case "9":
return 3;
default: return 1;
}
case "9":
switch($cardID[5]) {
case "2":
return 2;
case "3":
return 3;
case "9":
return 2;
case "6":
return 2;
case "7":
return 3;
case "4":
return 0;
case "0":
return 0;
default: return 1;
}
default: return 1;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return 0;
case "3":
return 2;
case "4":
return 3;
case "0":
return 3;
default: return 1;
}
case "2":
switch($cardID[5]) {
case "7":
return 2;
case "5":
return 2;
case "6":
return 3;
case "0":
return 3;
case "2":
return 3;
case "1":
return 3;
case "8":
return 2;
case "3":
return 3;
default: return 1;
}
case "1":
switch($cardID[5]) {
case "5":
return 0;
case "4":
return 0;
case "2":
return 0;
case "3":
return 0;
case "6":
return 0;
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
case "R":
switch($cardID[1]) {
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
default: return 1;
}
}

function GeneratedCardCost($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "9":
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
return -1;
case "7":
return -1;
case "6":
return -1;
case "8":
return -1;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "5":
return 3;
case "4":
return 3;
case "6":
return -1;
case "0":
return -1;
case "2":
return 3;
case "3":
return 3;
case "1":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "1":
return -1;
case "4":
return 2;
case "3":
return 2;
case "9":
return -1;
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
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
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
default: return 0;
}
case "3":
switch($cardID[5]) {
case "4":
return -1;
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
case "4":
switch($cardID[5]) {
case "3":
return 3;
case "4":
return 3;
case "5":
return 3;
case "9":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "5":
return -1;
case "0":
return 3;
case "1":
return 3;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "0":
return 3;
case "1":
return 3;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "9":
return 3;
default: return 0;
}
default: return 0;
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return -1;
case "4":
return -1;
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
case "3":
return -1;
case "4":
return -1;
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
case "9":
return -1;
default: return 0;
}
case "1":
switch($cardID[5]) {
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
case "7":
return -1;
case "4":
return -1;
case "3":
return -1;
default: return 0;
}
case "5":
switch($cardID[5]) {
case "1":
return -1;
case "7":
return 4;
case "0":
return -1;
case "6":
return -1;
case "2":
return -1;
case "9":
return 4;
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
case "8":
return -1;
case "7":
return -1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "6":
return 3;
case "7":
return 3;
case "8":
return 3;
default: return 0;
}
case "6":
switch($cardID[5]) {
case "0":
return -1;
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
case "7":
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
default: return 0;
}
case "1":
switch($cardID[5]) {
case "0":
return -1;
case "3":
return 3;
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
case "0":
switch($cardID[5]) {
case "2":
return -1;
case "3":
return -1;
case "4":
return -1;
case "0":
return -1;
case "1":
return -1;
default: return 0;
}
default: return 0;
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "9":
return -1;
case "4":
return -1;
case "6":
return -1;
case "8":
return -1;
case "5":
return -1;
case "7":
return -1;
default: return 0;
}
case "9":
switch($cardID[5]) {
case "4":
return -1;
case "1":
return -1;
case "7":
return -1;
case "0":
return -1;
case "5":
return -1;
case "2":
return -1;
case "8":
return -1;
case "3":
return -1;
case "9":
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
default: return 0;
}
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return -1;
case "2":
return 1;
case "3":
return 1;
case "4":
return 1;
case "0":
return 4;
default: return 0;
}
case "2":
switch($cardID[5]) {
case "7":
return -1;
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
case "5":
return -1;
case "4":
return -1;
case "2":
return -1;
case "3":
return -1;
case "6":
return -1;
default: return 0;
}
default: return 0;
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "8":
return -1;
case "4":
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
case "5":
switch($cardID[5]) {
case "6":
return -1;
case "7":
return 3;
case "8":
return 3;
case "9":
return 3;
default: return 0;
}
case "7":
switch($cardID[5]) {
case "2":
return 4;
case "3":
return 2;
case "4":
return 2;
case "5":
return 2;
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
case "8":
return 4;
case "9":
return 4;
case "4":
return -1;
case "0":
return -1;
default: return 0;
}
case "8":
switch($cardID[5]) {
case "4":
return -1;
case "3":
return -1;
case "2":
return -1;
case "5":
return -1;
case "8":
return -1;
case "7":
return -1;
case "6":
return -1;
case "9":
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
case "M":
switch($cardID[1]) {
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
case "2":
return "Young";
case "9":
return "Head";
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
case "8":
return "Young";
default: return "";
}
case "8":
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
case "0":
return "Block";
case "8":
return "Aura";
case "9":
return "Aura";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Sword";
case "7":
return "Chest";
case "5":
return "Sword";
case "1":
return "Young";
case "3":
return "Young";
case "6":
return "Sword";
default: return "";
}
case "1":
switch($cardID[5]) {
case "0":
return "Head";
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
case "6":
switch($cardID[5]) {
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
case "0":
switch($cardID[5]) {
case "2":
return "Young";
case "3":
return "Young";
case "4":
return "Young";
case "0":
return "Young";
case "1":
return "Young";
default: return "";
}
default: return "";
}
case "0":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "1":
return "Young";
case "7":
return "Young";
case "2":
return "Young";
case "8":
return "Young";
case "3":
return "Young";
case "9":
return "Young";
default: return "";
}
case "8":
switch($cardID[5]) {
case "4":
return "Young";
case "5":
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Wrench";
default: return "";
}
case "1":
switch($cardID[5]) {
case "5":
return "Arms";
case "4":
return "Chest";
case "2":
return "Fiddle";
case "3":
return "Head";
case "6":
return "Legs";
case "7":
return "Song";
case "8":
return "Song";
case "9":
return "Song";
default: return "";
}
case "2":
switch($cardID[5]) {
case "0":
return "Song";
case "2":
return "Song";
case "1":
return "Song";
case "3":
return "Item";
default: return "";
}
default: return "";
}
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "8":
return "Sword";
case "4":
return "Hammer";
case "0":
return "Scepter";
default: return "";
}
case "5":
switch($cardID[5]) {
case "6":
return "Flail";
default: return "";
}
case "7":
switch($cardID[5]) {
case "2":
return "Aura";
default: return "";
}
case "8":
switch($cardID[5]) {
case "4":
return "Base,Arms";
case "3":
return "Base,Chest";
case "2":
return "Base,Head";
case "5":
return "Base,Legs";
case "8":
return "Base,Arms";
case "7":
return "Base,Chest";
case "6":
return "Base,Head";
case "9":
return "Base,Legs";
default: return "";
}
case "9":
switch($cardID[5]) {
case "4":
return "Gun";
case "0":
return "Gun";
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "9":
switch($cardID[5]) {
case "1":
return "Mercenary,Ally";
case "9":
return "Block";
default: return "";
}
case "6":
switch($cardID[5]) {
case "5":
return "Item";
case "7":
return "Flail";
case "6":
return "Scepter";
case "8":
return "Arms";
default: return "";
}
case "7":
switch($cardID[5]) {
case "6":
return "Item";
case "0":
return "Head";
case "1":
return "Head";
default: return "";
}
default: return "";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "Mercenary,Ally";
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
case "DTD407": return 4;
case "DTD407": return 4;
case "UPR042": return 1;
case "DYN113": return 40;
case "OUT003": return 19;
case "DTD409": return 4;
case "DTD409": return 4;
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
case "CRU022": return 40;
case "WTR038": return 40;
case "EVR017": return 40;
case "ELE062": return 40;
case "MON153": return 40;
case "FAB191": return 2;
case "FAB201": return 2;
case "HVY134": return 2;
case "UPR410": return 2;
case "UPR410": return 2;
case "EVO001": return 36;
case "HER089": return 36;
case "HER094": return 36;
case "EVO002": return 18;
case "HER091": return 18;
case "HER097": return 18;
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
case "UPR044": return 40;
case "EVR120": return 18;
case "UPR103": return 18;
case "UPR102": return 36;
case "ARC114": return 15;
case "ARC113": return 30;
case "CRU158": return 30;
case "HVY090": return 40;
case "CRU045": return 40;
case "OUT045": return 40;
case "WTR076": return 40;
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
case "HER090": return 40;
case "HER095": return 40;
case "DTD410": return 4;
case "DTD410": return 4;
case "UPR412": return 4;
case "UPR412": return 4;
case "DTD193": return 6;
case "UPR413": return 7;
case "UPR413": return 7;
case "ELE001": return 40;
case "HVY092": return 40;
case "UPR414": return 6;
case "UPR414": return 6;
case "DTD002": return 16;
case "HER084": return 16;
case "DTD001": return 32;
case "HER086": return 32;
case "MON001": return 40;
case "CRU001": return 40;
case "HVY003": return 40;
case "WTR001": return 40;
case "OUT092": return 19;
case "OUT091": return 38;
case "DTD408": return 4;
case "DTD408": return 4;
case "DTD044": return 40;
case "MON029": return 40;
case "DTD405": return 4;
case "DTD405": return 4;
case "DYN612": return 4;
case "DYN612": return 4;
case "DYN612": return 4;
case "EVO007": return 40;
case "HER088": return 40;
case "HER096": return 40;
case "UPR415": return 4;
case "UPR415": return 4;
case "DTD406": return 4;
case "DTD406": return 4;
case "UPR407": return 5;
case "UPR407": return 5;
case "MON220": return 6;
case "OUT001": return 40;
case "EVR019": return 21;
case "HVY047": return 40;
case "DTD411": return 4;
case "DTD411": return 4;
case "ARC075": return 40;
case "CRU138": return 40;
case "DTD133": return 40;
case "HER087": return 40;
case "UPR416": return 1;
case "UPR416": return 1;
case "UPR417": return 3;
case "UPR417": return 3;
case "DYN025": return 22;
default: return 20;}
}

function GeneratedRarity($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "9":
return "P";
case "1":
return "P";
case "2":
return "P";
case "3":
return "P";
case "4":
return "P";
case "5":
return "P";
case "7":
return "P";
case "6":
return "P";
case "8":
return "P";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "5":
return "P";
case "4":
return "P";
case "6":
return "P";
case "0":
return "P";
case "2":
return "P";
case "3":
return "P";
case "8":
return "P";
case "1":
return "P";
case "7":
return "P";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "1":
return "P";
case "4":
return "P";
case "2":
return "P";
case "3":
return "P";
case "5":
return "P";
case "9":
return "P";
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
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
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
return "T";
case "6":
return "M";
case "4":
return "T";
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
case "5":
switch($cardID[5]) {
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
case "1":
switch($cardID[5]) {
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
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "8":
return "L";
case "6":
return "R";
case "2":
return "T";
case "1":
return "M";
case "9":
return "R";
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
case "0":
return "R";
case "2":
return "R";
case "9":
return "M";
default: return "C";
}
case "4":
switch($cardID[5]) {
case "6":
return "T";
case "5":
return "M";
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
case "8":
return "R";
case "9":
return "R";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "4":
return "T";
case "7":
return "L";
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
default: return "C";
}
case "1":
switch($cardID[5]) {
case "0":
return "R";
case "6":
return "M";
case "3":
return "M";
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
case "3":
switch($cardID[5]) {
case "4":
return "T";
default: return "C";
}
case "0":
switch($cardID[5]) {
case "5":
return "M";
case "3":
return "M";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "2":
return "R";
case "3":
return "R";
case "4":
return "R";
default: return "C";
}
case "6":
switch($cardID[5]) {
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
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return "P";
case "3":
return "P";
case "4":
return "P";
case "0":
return "P";
case "1":
return "P";
default: return "C";
}
default: return "C";
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "9":
return "P";
case "4":
return "P";
case "6":
return "P";
case "8":
return "P";
case "5":
return "P";
case "7":
return "P";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "4":
return "P";
case "1":
return "P";
case "7":
return "P";
case "0":
return "P";
case "5":
return "P";
case "2":
return "P";
case "8":
return "P";
case "3":
return "P";
case "9":
return "P";
case "6":
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "1":
return "P";
case "2":
return "P";
case "3":
return "P";
case "8":
return "P";
case "9":
return "P";
case "4":
return "P";
case "5":
return "P";
case "6":
return "P";
case "7":
return "P";
case "0":
return "P";
default: return "C";
}
case "7":
switch($cardID[5]) {
case "9":
return "P";
case "0":
return "P";
case "1":
return "P";
case "2":
return "P";
case "3":
return "P";
case "4":
return "P";
case "5":
return "P";
case "6":
return "P";
case "7":
return "P";
case "8":
return "P";
default: return "C";
}
case "8":
switch($cardID[5]) {
case "0":
return "P";
case "1":
return "P";
case "4":
return "P";
case "3":
return "P";
case "2":
return "P";
case "5":
return "P";
case "8":
return "P";
case "7":
return "P";
case "6":
return "P";
case "9":
return "P";
default: return "C";
}
case "5":
switch($cardID[5]) {
case "6":
return "P";
case "7":
return "P";
case "8":
return "P";
case "9":
return "P";
default: return "C";
}
case "9":
switch($cardID[5]) {
case "1":
return "P";
case "2":
return "P";
case "3":
return "P";
case "8":
return "P";
case "9":
return "P";
case "5":
return "P";
case "6":
return "P";
case "7":
return "P";
case "4":
return "P";
case "0":
return "P";
default: return "C";
}
default: return "C";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "P";
case "2":
return "P";
case "3":
return "P";
case "4":
return "P";
case "0":
return "P";
default: return "C";
}
case "2":
switch($cardID[5]) {
case "7":
return "P";
case "4":
return "P";
case "5":
return "P";
case "6":
return "P";
case "0":
return "P";
case "2":
return "P";
case "1":
return "P";
case "8":
return "P";
case "3":
return "P";
default: return "C";
}
case "1":
switch($cardID[5]) {
case "5":
return "P";
case "4":
return "P";
case "2":
return "P";
case "3":
return "P";
case "6":
return "P";
case "7":
return "P";
case "8":
return "P";
case "9":
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
default: return "C";
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
default: return "C";
}
}

function GeneratedIs1H($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID) {
case "HVY006": return true;
case "CRU079": return true;
case "CRU080": return true;
case "HVY094": return true;
case "DTD135": return true;
case "FAB167": return true;
case "LGS156": return true;
case "HVY245": return true;
case "TCC028": return true;
case "CRU048": return true;
case "CRU049": return true;
case "OUT048": return true;
case "WTR078": return true;
case "MON105": return true;
case "MON106": return true;
case "HVY095": return true;
case "CRU004": return true;
case "CRU005": return true;
case "HVY050": return true;
case "HVY007": return true;
case "OUT005": return true;
case "OUT006": return true;
case "OUT007": return true;
case "OUT008": return true;
case "HVY096": return true;
case "DYN069": return true;
case "DYN070": return true;
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
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "9":
return "MECHANOLOGIST";
case "1":
return "WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "BRUTE";
case "5":
return "GENERIC";
case "7":
return "RUNEBLADE";
case "6":
return "ILLUSIONIST";
case "8":
return "ASSASSIN";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "5":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "8":
return "GENERIC";
case "1":
return "WARRIOR";
case "7":
return "MECHANOLOGIST";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "1":
return "WARRIOR";
case "4":
return "GUARDIAN,WARRIOR";
case "2":
return "WARRIOR";
case "3":
return "BRUTE,WARRIOR";
case "5":
return "BRUTE,GUARDIAN";
case "9":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
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
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "3":
return "BRUTE,WARRIOR";
case "4":
return "BRUTE,WARRIOR";
case "5":
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
case "7":
return "GENERIC";
case "0":
return "GUARDIAN,WARRIOR";
case "1":
return "GUARDIAN,WARRIOR";
default: return "NONE";
}
case "0":
switch($cardID[5]) {
case "0":
return "WARRIOR";
case "5":
return "WARRIOR";
case "3":
return "WARRIOR";
default: return "NONE";
}
case "3":
switch($cardID[5]) {
case "4":
return "WARRIOR";
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
default: return "NONE";
}
case "4":
switch($cardID[5]) {
case "3":
return "BRUTE,GUARDIAN";
case "4":
return "BRUTE,GUARDIAN";
case "5":
return "BRUTE,GUARDIAN";
case "9":
return "BRUTE,GUARDIAN";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "2":
return "WARRIOR";
case "3":
return "WARRIOR";
case "4":
return "WARRIOR";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "5":
return "GUARDIAN,WARRIOR";
case "0":
return "BRUTE,WARRIOR";
case "1":
return "BRUTE,WARRIOR";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "0":
return "BRUTE,GUARDIAN";
case "1":
return "BRUTE,GUARDIAN";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "9":
return "GUARDIAN,WARRIOR";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "0":
return "GENERIC";
case "6":
return "ASSASSIN";
case "4":
return "GENERIC";
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
case "1":
return "GENERIC";
case "3":
return "GENERIC";
case "4":
return "GENERIC";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
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
case "3":
switch($cardID[5]) {
case "5":
return "GENERIC";
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "9":
return "GENERIC";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "6":
return "GENERIC";
case "7":
return "GENERIC";
case "8":
return "GENERIC";
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
case "2":
return "BRUTE";
case "1":
return "BRUTE";
case "9":
return "BRUTE";
case "7":
return "BRUTE";
case "4":
return "BRUTE";
case "3":
return "BRUTE";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
case "1":
return "GUARDIAN";
case "7":
return "GUARDIAN";
case "0":
return "GUARDIAN";
case "6":
return "GUARDIAN";
case "2":
return "GUARDIAN";
case "9":
return "GUARDIAN";
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
case "4":
return "BRUTE";
case "8":
return "GUARDIAN";
case "7":
return "GUARDIAN";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
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
case "6":
switch($cardID[5]) {
case "0":
return "GUARDIAN";
case "8":
return "GUARDIAN";
case "9":
return "GUARDIAN";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "4":
return "WARRIOR";
case "7":
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
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "0":
return "BRUTE";
case "6":
return "BRUTE";
case "3":
return "BRUTE";
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
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "0":
return "GUARDIAN";
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
case "0":
switch($cardID[5]) {
case "2":
return "GUARDIAN";
case "3":
return "BARD";
case "4":
return "MECHANOLOGIST";
case "0":
return "MERCHANT";
case "1":
return "MERCHANT";
default: return "NONE";
}
default: return "NONE";
}
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "9":
return "MECHANOLOGIST";
case "4":
return "ILLUSIONIST";
case "6":
return "ILLUSIONIST";
case "8":
return "MECHANOLOGIST";
case "5":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
default: return "NONE";
}
case "9":
switch($cardID[5]) {
case "4":
return "MECHANOLOGIST";
case "1":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "1":
return "ILLUSIONIST";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "8":
return "WARRIOR";
case "4":
return "BRUTE";
case "0":
return "ILLUSIONIST";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "9":
return "WARRIOR";
case "2":
return "ILLUSIONIST";
case "3":
return "ILLUSIONIST";
case "4":
return "ILLUSIONIST";
case "5":
return "ILLUSIONIST";
case "6":
return "RUNEBLADE";
case "7":
return "RUNEBLADE";
case "8":
return "RUNEBLADE";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "WARRIOR";
case "1":
return "WARRIOR";
case "4":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "8":
return "MECHANOLOGIST";
case "7":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "9":
return "MECHANOLOGIST";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
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
case "9":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
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
case "4":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
default: return "NONE";
}
default: return "NONE";
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return "MECHANOLOGIST";
case "2":
return "MECHANOLOGIST";
case "3":
return "MECHANOLOGIST";
case "4":
return "MECHANOLOGIST";
case "0":
return "MECHANOLOGIST";
default: return "NONE";
}
case "2":
switch($cardID[5]) {
case "7":
return "GENERIC";
case "4":
return "MECHANOLOGIST";
case "5":
return "MECHANOLOGIST";
case "6":
return "MECHANOLOGIST";
case "0":
return "BARD";
case "2":
return "BARD";
case "1":
return "BARD";
case "8":
return "GENERIC";
case "3":
return "MECHANOLOGIST";
default: return "NONE";
}
case "1":
switch($cardID[5]) {
case "5":
return "BARD";
case "4":
return "BARD";
case "2":
return "BARD";
case "3":
return "BARD";
case "6":
return "BARD";
case "7":
return "BARD";
case "8":
return "BARD";
case "9":
return "BARD";
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
default: return "NONE";
}
default: return "NONE";
}
}

function GeneratedCardTalent($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID[0]) {
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
case "L":
switch($cardID[1]) {
case "G":
switch($cardID[2]) {
case "S":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "8":
return "LIGHT";
case "9":
return "LIGHT";
case "4":
return "SHADOW";
case "5":
return "SHADOW";
case "6":
return "SHADOW";
case "7":
return "SHADOW";
case "0":
return "LIGHT";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
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
return "SHADOW";
case "7":
return "SHADOW";
case "8":
return "SHADOW";
default: return "NONE";
}
case "8":
switch($cardID[5]) {
case "0":
return "LIGHT";
case "1":
return "LIGHT";
default: return "NONE";
}
case "5":
switch($cardID[5]) {
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
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
}
default: return "NONE";
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
case "F":
switch($cardID[1]) {
case "A":
switch($cardID[2]) {
case "B":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "1":
return "LIGHT";
case "2":
return "LIGHT";
case "3":
return "LIGHT";
case "4":
return "SHADOW";
case "7":
return "SHADOW";
case "6":
return "LIGHT";
default: return "NONE";
}
case "7":
switch($cardID[5]) {
case "1":
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
case "E":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "8":
switch($cardID[5]) {
case "4":
return "LIGHT";
case "6":
return "LIGHT";
case "5":
return "SHADOW";
case "7":
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
default: return "NONE";
}
}

function GeneratedIsSpecialization($cardID) {
if(strlen($cardID) < 6) return "";
if(is_int($cardID)) return "";
switch($cardID) {
case "DTD215": return "true";
case "RVD008": return "true";
case "WTR006": return "true";
case "DTD196": return "true";
case "MON005": return "true";
case "ARC080": return "true";
case "CRU000": return "true";
case "HVY051": return "true";
case "EVO006": return "true";
case "LGS201": return "true";
case "ARC083": return "true";
case "DTD212": return "true";
case "HVY057": return "true";
case "DTD164": return "true";
case "ARC118": return "true";
case "EVR055": return "true";
case "OUT102": return "true";
case "DTD198": return "true";
case "DTD208": return "true";
case "HVY246": return "true";
case "OUT103": return "true";
case "WTR043": return "true";
case "OUT097": return "true";
case "MON189": return "true";
case "OUT098": return "true";
case "MON190": return "true";
case "ELE004": return "true";
case "DTD135": return "true";
case "FAB167": return "true";
case "LGS156": return "true";
case "ELE066": return "true";
case "UPR126": return "true";
case "DVR008": return "true";
case "DTD013": return "true";
case "MON007": return "true";
case "UPR109": return "true";
case "EVO241": return "true";
case "HVY249": return "true";
case "HVY009": return "true";
case "ARC121": return "true";
case "DTD564": return "true";
case "DTD564": return "true";
case "ELE036": return "true";
case "WTR081": return "true";
case "MON034": return "true";
case "EVR070": return "true";
case "HVY010": return "true";
case "WTR083": return "true";
case "ARC046": return "true";
case "DTD197": return "true";
case "DTD142": return "true";
case "FAB192": return "true";
case "HVY105": return "true";
case "EVR003": return "true";
case "UPR090": return "true";
case "ARC043": return "true";
case "DYN121": return "true";
case "UPR091": return "true";
case "WTR009": return "true";
case "OUT013": return "true";
case "HVY013": return "true";
case "WTR047": return "true";
case "WTR121": return "true";
case "EVO010": return "true";
case "EVO242": return "true";
case "MON198": return "true";
case "MON199": return "true";
case "ARC009": return "true";
case "OUT104": return "true";
case "EVR039": return "true";
case "DTD203": return "true";
case "WTR119": return "true";
case "EVO003": return "true";
case "LGS194": return "true";
case "ARC007": return "true";
case "LGS223": return "true";
case "HVY059": return "true";
case "HVY103": return "true";
case "MON035": return "true";
case "OUT055": return "true";
case "OUT053": return "true";
case "CRU074": return "true";
default: return "false";}
}

?>