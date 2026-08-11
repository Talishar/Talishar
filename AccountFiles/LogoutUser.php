<?php
include_once './AccountSessionAPI.php';

ClearLoginSession();

header("location: /"); // React app home (legacy MainMenu.php no longer exists)
exit;
?>
