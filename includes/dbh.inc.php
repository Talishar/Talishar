<?php

$servername = (!empty(getenv("MYSQL_SERVER_NAME")) ? getenv("MYSQL_SERVER_NAME") : "localhost");
$dBUsername = (!empty(getenv("MYSQL_SERVER_USER_NAME")) ? getenv("MYSQL_SERVER_USER_NAME") : "root");
$dBPassword = (!empty(getenv("MYSQL_ROOT_PASSWORD")) ? getenv("MYSQL_ROOT_PASSWORD") : "");
$dBName = "fabonline";

$reportingServername = (!empty(getenv("MYSQL_SERVER_NAME")) ? getenv("MYSQL_SERVER_NAME") : "localhost");
$reportingDBUsername = (!empty(getenv("MYSQL_SERVER_USER_NAME")) ? getenv("MYSQL_SERVER_USER_NAME") : "root");
$reportingDBPassword = (!empty(getenv("MYSQL_ROOT_PASSWORD")) ? getenv("MYSQL_ROOT_PASSWORD") : "");
$reportingDBName = "fabonline";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

function GetDBConnection()
{
	global $servername, $dBUsername, $dBPassword, $dBName;
	return mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
}

function GetReportingDBConnection()
{
	global $reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName;
	return mysqli_connect($reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName);
}
