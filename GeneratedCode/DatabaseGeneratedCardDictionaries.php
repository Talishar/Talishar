<?php
function GeneratedGoAgain($cardID) {
if(strlen($cardID) < 6) return 0;
if(is_int($cardID)) return 0;
switch($cardID[0]) {
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
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "4":
return true;
case "5":
return true;
case "6":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "4":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "1":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "2":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
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
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "2":
return true;
case "4":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "4":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "3":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "4":
return true;
case "5":
return true;
case "6":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "1":
return true;
case "8":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
case "D":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "D":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "5":
switch($cardID[5]) {
case "3":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "1":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "0":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "6":
return true;
case "8":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "2":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "7":
return true;
case "9":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
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
return true;
case "5":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "2":
return true;
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "2":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
case "Y":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "8":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "1":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "5":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "8":
return true;
case "9":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "0":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "0":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
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
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "0":
return true;
case "7":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return true;
case "4":
return true;
case "5":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "3":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "6":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "5":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "8":
return true;
case "9":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "5":
return true;
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "6":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
case "V":
switch($cardID[2]) {
case "O":
switch($cardID[3]) {
case "1":
switch($cardID[4]) {
case "4":
switch($cardID[5]) {
case "3":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "0":
return true;
case "8":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "0":
return true;
case "5":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "4":
return true;
case "5":
return true;
case "6":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "2":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "9":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "9":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "8":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "0":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
case "H":
switch($cardID[1]) {
case "V":
switch($cardID[2]) {
case "Y":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "0":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "8":
return true;
case "9":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "0":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "1":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "5":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "5":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "5":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "5":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "3":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
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
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
case "M":
switch($cardID[1]) {
case "O":
switch($cardID[2]) {
case "N":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "3":
switch($cardID[5]) {
case "4":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "2":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "7":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "3":
return true;
case "4":
return true;
case "5":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "3":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "2":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "1":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
default: return false;
}
case "3":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
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
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "0":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "5":
return true;
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "9":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "5":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "2":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
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
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "5":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
case "T":
switch($cardID[1]) {
case "C":
switch($cardID[2]) {
case "C":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "6":
switch($cardID[5]) {
case "1":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "3":
return true;
case "6":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "4":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
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
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "8":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "8":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "1":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "8":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "0":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
case "W":
switch($cardID[1]) {
case "T":
switch($cardID[2]) {
case "R":
switch($cardID[3]) {
case "0":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "6":
return true;
default: return false;
}
case "5":
switch($cardID[5]) {
case "4":
return true;
case "5":
return true;
case "6":
return true;
default: return false;
}
case "6":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
case "7":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
default: return false;
}
case "9":
switch($cardID[5]) {
case "8":
return true;
case "9":
return true;
default: return false;
}
default: return false;
}
case "1":
switch($cardID[4]) {
case "0":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
case "1":
switch($cardID[5]) {
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "2":
return true;
case "9":
return true;
default: return false;
}
case "3":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
default: return false;
}
case "4":
switch($cardID[5]) {
case "1":
return true;
case "2":
return true;
case "3":
return true;
case "4":
return true;
case "5":
return true;
case "6":
return true;
case "7":
return true;
case "8":
return true;
case "9":
return true;
default: return false;
}
default: return false;
}
case "2":
switch($cardID[4]) {
case "1":
switch($cardID[5]) {
case "8":
return true;
case "9":
return true;
default: return false;
}
case "2":
switch($cardID[5]) {
case "0":
return true;
case "1":
return true;
case "2":
return true;
case "3":
return true;
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
default: return false;
}
}

?>