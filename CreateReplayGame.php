<?php
session_start();

ob_start();
include "HostFiles/Redirector.php";
include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
include_once "Libraries/PlayerSettings.php";
ob_end_clean();

$replayNumber = TryGet("replayNumber", "");


$gameName = GetGameCounter();

if ( (!file_exists("Games/$gameName")) && (mkdir("Games/$gameName", 0700, true)) ){
} else {
  print_r("Encountered a problem creating a game. Please return to the main menu and try again");
}

$p1Data = [1];
$p2Data = [2];

$gameStatus = 5; //Game started

$firstPlayerChooser = "";
$firstPlayer = 1;
$p1Key = hash("sha256", rand() . rand());
$p2Key = hash("sha256", rand() . rand() . rand());
$p1uid = "-";
$p2uid = "-";
$p1id = "-";
$p2id = "-";
$hostIP = $_SERVER['REMOTE_ADDR'];
$p1StartingHealth = $startingHealth;

$filename = "./Games/" . $gameName . "/GameFile.txt";
$gameFileHandler = fopen($filename, "w");
include "MenuFiles/WriteGamefile.php";
WriteGameFile();

$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "w");
fclose($handler);

$currentTime = round(microtime(true) * 1000);
WriteCache($gameName, 1 . "!" . $currentTime . "!" . $currentTime . "!0!-1!" . $currentTime . "!!!0"); //Initialize SHMOP cache for this game

copy("./Replays/" . $replayNumber . "/origGamestate.txt", "./Games/" . $gameName . "/gamestate.txt");
copy("./Replays/" . $replayNumber . "/commandfile.txt", "./Games/" . $gameName . "/replayCommands.txt");

header("Location: NextTurn4.php?gameName=$gameName&playerID=3");
