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

function GetDBConnection($retries = 3, $delay = 100000)
{
	global $servername, $dBUsername, $dBPassword, $dBName;
	$conn = false;
	
	// Log environment variables being used (sanitized for security)
	error_log("Attempting DB connection to host: $servername, user: $dBUsername, database: $dBName");
	
	for ($attempt = 1; $attempt <= $retries; $attempt++) {
		try {
			$conn = @mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
			if ($conn) {
				error_log("Database connection successful on attempt $attempt");
				return $conn;
			}
			
			$error = mysqli_connect_error();
			error_log("Database connection failed (attempt $attempt/$retries) - Host: $servername, User: $dBUsername, Error: " . ($error ?: "Unknown error"));
			
			// Don't retry on the last attempt
			if ($attempt < $retries) {
				usleep($delay);
				$delay *= 2; // Exponential backoff
			}
		} catch (\Exception $e) {
			error_log("Database connection exception (attempt $attempt/$retries): " . $e->getMessage());
			if ($attempt < $retries) {
				usleep($delay);
				$delay *= 2;
			}
		}
	}
	
	error_log("Database connection failed after $retries attempts");
	return false;
}

function GetReportingDBConnection($retries = 3, $delay = 100000)
{
	global $reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName;
	$conn = false;
	
	error_log("Attempting reporting DB connection to host: $reportingServername, user: $reportingDBUsername, database: $reportingDBName");
	
	for ($attempt = 1; $attempt <= $retries; $attempt++) {
		try {
			$conn = @mysqli_connect($reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName);
			if ($conn) {
				error_log("Reporting database connection successful on attempt $attempt");
				return $conn;
			}
			
			$error = mysqli_connect_error();
			error_log("Reporting database connection failed (attempt $attempt/$retries) - Host: $reportingServername, User: $reportingDBUsername, Error: " . ($error ?: "Unknown error"));
			
			if ($attempt < $retries) {
				usleep($delay);
				$delay *= 2;
			}
		} catch (\Exception $e) {
			error_log("Reporting database connection exception (attempt $attempt/$retries): " . $e->getMessage());
			if ($attempt < $retries) {
				usleep($delay);
				$delay *= 2;
			}
		}
	}
	
	error_log("Reporting database connection failed after $retries attempts");
	return false;
}
