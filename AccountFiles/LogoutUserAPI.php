<?php
include_once './AccountSessionAPI.php';
include_once '../Libraries/HTTPLibraries.php';

SetHeaders();

ClearLoginSession();

$response = new stdClass();
$response->message = "Logout successful";
echo(json_encode($response));

exit;

?>
