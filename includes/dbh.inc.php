<?php

$servername = (!empty(getenv("MYSQL_SERVER_NAME")) ? getenv("MYSQL_SERVER_NAME") : "localhost");
$dBUsername = (!empty(getenv("MYSQL_SERVER_USER_NAME")) ? getenv("MYSQL_SERVER_USER_NAME") : "root");
$dBPassword = (!empty(getenv("MYSQL_ROOT_PASSWORD")) ? getenv("MYSQL_ROOT_PASSWORD") : "");
$dBName = "fabonline2";

$reportingServername = (!empty(getenv("MYSQL_SERVER_NAME")) ? getenv("MYSQL_SERVER_NAME") : "localhost");
$reportingDBUsername = (!empty(getenv("MYSQL_SERVER_USER_NAME")) ? getenv("MYSQL_SERVER_USER_NAME") : "root");
$reportingDBPassword = (!empty(getenv("MYSQL_ROOT_PASSWORD")) ? getenv("MYSQL_ROOT_PASSWORD") : "");
$reportingDBName = "fabonline2";

/*
$conn = GetDBConnection();

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
*/

function GetDBConnection()
{
	global $servername, $dBUsername, $dBPassword, $dBName;
	try {
		$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
	} catch (\Exception $e) {
		$conn = false;
	}

	return $conn;
}

function GetReportingDBConnection()
{
	global $reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName;
	try {
		$conn = mysqli_connect($reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName);
	} catch (\Exception $e) {
		$conn = false;
	}

	return $conn;
}
