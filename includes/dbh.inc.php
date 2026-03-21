<?php

$servername = (!empty(getenv("MYSQL_SERVER_NAME")) ? getenv("MYSQL_SERVER_NAME") : "localhost");
$dBUsername = (!empty(getenv("MYSQL_SERVER_USER_NAME")) ? getenv("MYSQL_SERVER_USER_NAME") : "root");
$dBPassword = (!empty(getenv("MYSQL_ROOT_PASSWORD")) ? getenv("MYSQL_ROOT_PASSWORD") : "");
$dBName = "fabonline";

function GetDBConnection()
{
	global $servername, $dBUsername, $dBPassword, $dBName;
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	try {
        $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
	} catch (\Exception $e) {
		error_log("DB connection failed: " . mysqli_connect_error() . " (errno: " . mysqli_connect_errno() . ")");
		return false;
	}
    if (!$conn) error_log("DB connection failed: " . mysqli_connect_error() . " (errno: " . mysqli_connect_errno() . ")");
	return $conn;
}