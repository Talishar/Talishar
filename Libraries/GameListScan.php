<?php

include_once __DIR__ . '/SHMOPLibraries.php';
include_once __DIR__ . '/CacheLibraries.php';

const GAME_LIST_SCAN_KEY = 'game_list_scan_v1';
const GAME_LIST_SCAN_TTL_MS = 2000;

function GetGameListScan($path, $currentTime, $useSentinels = false)
{
  if (!_apcuAvailable()) return ScanGameList($path, $currentTime, $useSentinels);
  // Never let either endpoint reuse the other endpoint's discovery results.
  $scanKey = GAME_LIST_SCAN_KEY . ($useSentinels ? '_apcu_sentinels' : '');
  $cached = @apcu_fetch($scanKey);
  $usable = is_array($cached) && isset($cached['at'], $cached['inProgress'], $cached['open'], $cached['inProgressCount']);
  if ($usable && $currentTime - $cached['at'] < GAME_LIST_SCAN_TTL_MS) return $cached;
  if (!@apcu_add($scanKey . "_lock", 1, 5) && $usable) return $cached;
  $scan = ScanGameList($path, $currentTime, $useSentinels);
  if ($scan !== null) @apcu_store($scanKey, $scan, 10);
  @apcu_delete($scanKey . "_lock");
  return $scan;
}

function GameListDirectoryTokens($handle)
{
  while (false !== ($folder = readdir($handle))) {
    if ($folder !== '.' && $folder !== '..') yield $folder;
  }
}

function ScanGameList($path, $currentTime, $useSentinels = false)
{
  global $autoDeleteGames, $gameFileHandler;
  $handle = $useSentinels ? null : opendir($path);
  if (!$useSentinels && !$handle) return null;
  $scan = ['at' => $currentTime, 'inProgressCount' => 0, 'inProgress' => [], 'open' => []];
  $checkFileCreationTime = random_int(1, 1000) == 42;
  $tokens = $useSentinels ? APCuGameSentinelTokens() : GameListDirectoryTokens($handle);
  foreach ($tokens as $folder) {
    $gameToken = $folder;
    $sentinelCacheArr = null;
    if ($useSentinels) {
      $sentinelCacheArr = ReadCacheArray($gameToken);
      if ($sentinelCacheArr === null) {
        DeleteGameCacheSentinel($gameToken);
        continue;
      }
    }
    $folder = $path . "/" . $folder . "/";
    $gs = $folder . "gamestate.txt";
    if($autoDeleteGames && $checkFileCreationTime) {
      $dirPath = realpath(rtrim($folder, "/"));
      if ($dirPath && is_dir($dirPath)) {
        $lastModified = filemtime($dirPath);
        $ageInSeconds = time() - $lastModified;
        if($ageInSeconds > 18000) {
          if (deleteDirectory($dirPath)) {
            DeleteCache($gameToken);
            continue;
          } else {
            error_log("Failed to delete directory: " . $dirPath);
          }
        }
      }
    }
    if (file_exists($gs)) {
      // Single shared-memory read; all pieces available as 0-indexed array (piece N = index N-1)
      $cacheArr = $useSentinels ? $sentinelCacheArr : ReadCacheArray($gameToken);
      $lastGamestateUpdate = ($cacheArr !== null) ? intval($cacheArr[5] ?? 0) : 0;
      if ($currentTime - $lastGamestateUpdate < 30000) {
        $visibility = $cacheArr[8] ?? "";  // piece 9
        $scan['inProgressCount'] += 1;
        if ($visibility != "1" && $visibility != "2") continue;

        // Get both player usernames from the GameFile.txt
        $gameFilePath = $folder . "GameFile.txt";
        $gameCreator = "";
        $p2Username = "";
        $p1AccountId = 0;
        $p2AccountId = 0;
        $p1ShownName = "";
        $p2ShownName = "";
        $gameFileContent = @file_get_contents($gameFilePath);
        if ($gameFileContent !== false) {
          $gameFileLines = explode("\n", $gameFileContent, 45);
          $gameCreator = trim($gameFileLines[9] ?? "");  // line 10: p1uid
          $p2Username  = trim($gameFileLines[10] ?? "");  // line 11: p2uid
          $p1AccountId = intval(trim($gameFileLines[11] ?? ""));  // line 12: p1id
          $p2AccountId = intval(trim($gameFileLines[12] ?? ""));  // line 13: p2id
          // Trailing display-name lines (43-44); missing on older game files
          $p1ShownName = trim($gameFileLines[42] ?? "");  // line 43: p1DisplayName
          $p2ShownName = trim($gameFileLines[43] ?? "");  // line 44: p2DisplayName
        }
        if ($p1ShownName === "") $p1ShownName = $gameCreator;
        if ($p2ShownName === "") $p2ShownName = $p2Username;

        $p1Hero = $cacheArr[6] ?? "";
        $p2Hero = $cacheArr[7] ?? "";
        if($p1Hero != "" && $p2Hero != "DUMMY" && $p2Hero != "") {
          $scan['inProgress'][] = [
            $gameToken, $lastGamestateUpdate, $visibility, $p1Hero, $p2Hero, $cacheArr[12] ?? "",
            $gameCreator, $p2Username, $p1ShownName, $p2ShownName, $p1AccountId, $p2AccountId,
            GetActiveSpectators($gameToken)['count'],
          ];
        }
      }
      else if ($currentTime - $lastGamestateUpdate > GAME_DELETE_TIMEOUT_MS)
      {
        if ($autoDeleteGames) {
          deleteDirectory($folder);
          DeleteCache($gameToken);
          continue;
        }
      }
      continue;
    }

    $gf = $folder . "GameFile.txt";
    $gameName = $gameToken;
    $lineCount = 0;
    $status = -1;
    $format = "";
    $gameDescription = "";
    $p1uid = "";
    $p2uid = "";
    $p1DisplayName = "";
    $p2DisplayName = "";
    if (file_exists($gf)) {
      $openCacheArr = $useSentinels ? $sentinelCacheArr : ReadCacheArray($gameName);
      $lastRefresh = ($openCacheArr !== null) ? intval($openCacheArr[1] ?? "") : 0; //Player 1 last connection time
      if ($lastRefresh != "" && $currentTime - $lastRefresh < 500) {
        include __DIR__ . '/../APIs/APIParseGamefile.php';
        $status = $gameStatus;
        UnlockGamefile();
      } else if ($lastRefresh == "" || $currentTime - $lastRefresh > 900000) // 15 minutes
      {
        deleteDirectory($folder);
        DeleteCache($gameToken);
      }
      if($status == 0 && intval($openCacheArr[10] ?? "") < 3) {
        $visibility = $openCacheArr[8] ?? "";
        if ($visibility != "1" && $visibility != "2") continue;

        $formatName = "";
        if($format == "commoner") $formatName = "Commoner";
        else if($format == "futurecc") $formatName = "Future CC";
        // else if($format == "openformatblitz") $formatName = "Open Blitz";
        else if($format == "futuresage") $formatName = "Future Silver Age";
        // else if($format == "openformatsage") $formatName = "Open Silver Age";
        else if($format == "clash") $formatName = "Clash";
        else if($format == "llcc") $formatName = "Living Legend CC";
        else if($format == "llblitz") $formatName = "Living Legend Blitz";
        else if($format == "futurell") $formatName = "Future Living Legend";
        // else if($format == "openformatllblitz") $formatName = "Open Living Legend Blitz";
        else if($format == "precon") $formatName = "Preconstructed Deck";
        else if($format == "sage") $formatName = "Silver Age";
        else if($format == "open") $formatName = "Open";
        else if($format == "gage") $formatName = "Golden Age";

        $hasHero = $format != "compcc" && $format != "compblitz" && $format != "compllcc" && $format != "compsage";
        $scan['open'][] = [
          $gameToken, $visibility, $p1uid, $hasHero, $hasHero ? ($openCacheArr[6] ?? "") : "",
          $format, $formatName, ($gameDescription == "" ? "Game #" . $gameName : $gameDescription),
          $p1DisplayName !== "" ? $p1DisplayName : $p1uid,
        ];
      }
    }
  }
  if ($handle) closedir($handle);
  return $scan;
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return @unlink($dir) || !file_exists($dir);
    }

    $dirContents = @scandir($dir);
    if ($dirContents === false && !is_dir($dir)) return true;
    if ($dirContents === false) return false;
    foreach ($dirContents as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            if (file_exists($path)) {
                @unlink($path); 
            }
        }
    }
    if (!is_dir($dir)) return false;
    return @rmdir($dir) || !is_dir($dir); // Gracefully handle race condition where directory was already deleted
}

