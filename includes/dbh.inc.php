<?php

$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "fabonline";

$reportingServername = "142.11.213.202";
$reportingDBUsername = "root";
$reportingDBPassword = "";
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
	return mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
}

?>
