<?php

$servername = "host.docker.internal";
$dBUsername = "root";
$dBPassword = "root";
$dBName = "fabonline";

$reportingServername = "host.docker.internal";
$reportingDBUsername = "root";
$reportingDBPassword = "root";
$reportingDBName = "fabonline";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
	die("Connection failed: ".mysqli_connect_error());
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

?>
