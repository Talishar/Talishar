<?php
function GeneratedGoAgain($cardID) {
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