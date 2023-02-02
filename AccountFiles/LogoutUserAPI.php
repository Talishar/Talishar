<?php
include_once './AccountSessionAPI.php';

ClearLoginSession();

$response = new stdClass();
$response->message = "Logout successful";
echo(json_encode($response));

exit;

?>
