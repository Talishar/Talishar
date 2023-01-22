<?php
include_once './AccountSessionAPI.php';

ClearLoginSession();

header("location: ../MainMenu.php");
exit;
?>
