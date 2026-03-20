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

function retryConnectWithExponentialBackoff($servername, $dBUsername, $dBPassword, $dBName, $maxRetries = 5, $baseDelay = 100) {
    $attempt = 0;
    while ($attempt < $maxRetries) {
        try {
            return mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
        } catch (\Throwable $e) {
            $attempt++;

            // Bail immediately on client errors (e.g., 4xx status codes)
            if ($e instanceof \HttpException && $e->getCode() >= 400 && $e->getCode() < 500) {
                throw $e;
            }

            if ($attempt >= $maxRetries) {
                throw $e; // Re-throw if max attempts reached
            }

            // Calculate exponential backoff with jitter (milliseconds)
            $delayMs = $baseDelay * (2 ** ($attempt - 1));
            // Add randomness (jitter)
            $jitter = random_int($delayMs / 2, $delayMs);

            // Convert milliseconds to microseconds for usleep()
            usleep($jitter * 1000);
        }
    }
}

function GetDBConnection()
{
	global $servername, $dBUsername, $dBPassword, $dBName;
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	try {
		// $conn = retryConnectWithExponentialBackoff($servername, $dBUsername, $dBPassword, $dBName);
        $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
	} catch (\Exception $e) {
		error_log("DB connection failed: " . mysqli_connect_error() . " (errno: " . mysqli_connect_errno() . ")");
		return false;
	}

	return $conn;
}

function GetReportingDBConnection()
{
	global $reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName;
	try {
		$conn = mysqli_connect($reportingServername, $reportingDBUsername, $reportingDBPassword, $reportingDBName);
		// Set timezone for this connection to UTC to ensure consistent timestamps
		if ($conn) {
			$conn->set_charset("utf8mb4");
			$conn->query("SET time_zone = '+00:00'");
		}
	} catch (\Exception $e) {
		$conn = false;
	}

	return $conn;
}
