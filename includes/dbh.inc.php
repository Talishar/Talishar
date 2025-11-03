<?php

$servername = (!empty(getenv("MYSQL_SERVER_NAME")) ? getenv("MYSQL_SERVER_NAME") : "localhost");
$dBUsername = (!empty(getenv("MYSQL_SERVER_USER_NAME")) ? getenv("MYSQL_SERVER_USER_NAME") : "root");
$dBPassword = (!empty(getenv("MYSQL_ROOT_PASSWORD")) ? getenv("MYSQL_ROOT_PASSWORD") : "");
$dBName = "fabonline";

$reportingServername = (!empty(getenv("MYSQL_SERVER_NAME")) ? getenv("MYSQL_SERVER_NAME") : "localhost");
$reportingDBUsername = (!empty(getenv("MYSQL_SERVER_USER_NAME")) ? getenv("MYSQL_SERVER_USER_NAME") : "root");
$reportingDBPassword = (!empty(getenv("MYSQL_ROOT_PASSWORD")) ? getenv("MYSQL_ROOT_PASSWORD") : "");
$reportingDBName = "fabonline";

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
		// Set a connection timeout to prevent hangs
		$conn = @mysqli_connect($servername, $dBUsername, $dBPassword, $dBName, 3306);
		
		// Check if connection was successful
		if (!$conn || mysqli_connect_errno()) {
			error_log("Database connection failed: " . mysqli_connect_error());
			return false;
		}
		
		// Set charset to UTF-8
		mysqli_set_charset($conn, "utf8mb4");
		
		return $conn;
	} catch (\Exception $e) {
		error_log("Database connection exception: " . $e->getMessage());
		return false;
	}
}

function GetReportingDBConnection()
{
	global $reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName;
	try {
		// Set a connection timeout to prevent hangs
		$conn = @mysqli_connect($reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName, 3306);
		
		// Check if connection was successful
		if (!$conn || mysqli_connect_errno()) {
			error_log("Reporting database connection failed: " . mysqli_connect_error());
			return false;
		}
		
		// Set charset to UTF-8
		mysqli_set_charset($conn, "utf8mb4");
		
		return $conn;
	} catch (\Exception $e) {
		error_log("Reporting database connection exception: " . $e->getMessage());
		return false;
	}
}
