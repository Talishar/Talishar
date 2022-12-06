<?php

$servername = getenv("MYSQL_SERVER_NAME") != false ? getenv("MYSQL_SERVER_NAME") : "localhost";
$dBUsername =
	getenv("MYSQL_SERVER_USER_NAME") != false ? getenv("MYSQL_SERVER_USER_NAME") : "root";
$dBPassword = getenv("MYSQL_ROOT_PASSWORD") != false ? getenv("MYSQL_ROOT_PASSWORD") : "";
$dBName = "fabonline";

$reportingServername =
	getenv("MYSQL_SERVER_NAME") != false ? getenv("MYSQL_SERVER_NAME") : "localhost";
$reportingDBUsername = getenv("MYSQL_SERVER_USER_NAME") != false ? getenv("MYSQL_SERVER_USER_NAME") : "root";
$reportingDBPassword =
	getenv("MYSQL_ROOT_PASSWORD") != false ? getenv("MYSQL_ROOT_PASSWORD") : "";
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
