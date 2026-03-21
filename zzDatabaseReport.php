<?php

include_once 'MenuBar.php';
include_once './includes/dbh.inc.php';
include_once './includes/functions.inc.php';
include_once './includes/ModeratorList.inc.php';
include_once './Libraries/SHMOPLibraries.php';
/*
if (!isset($_SESSION["userid"])) {
    if (isset($_COOKIE["rememberMeToken"])) {
        loginFromCookie();
    }
}

if (!isset($_SESSION["useruid"])) {
    echo "Please login to view this page.";
    exit;
}

$useruid = $_SESSION["useruid"];
if (!IsUserModerator($useruid)) {
    echo "You must be a moderator to view this page.";
    exit;
}
*/
$resetRequested = isset($_POST['reset']) && $_POST['reset'] === '1';
if ($resetRequested) {
    for ($key = 1; $key <= DBL_MAX_KEY; $key++) {
        WriteCache($key, 0);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Database Connection Report</title>
<style>
  body { font-family: monospace; background: #1a1a1a; color: #ddd; padding: 20px; }
  h2 { color: #fff; }
  table { border-collapse: collapse; width: 700px; }
  th { background: #333; color: #fff; padding: 8px 12px; text-align: left; }
  td { padding: 6px 12px; border-bottom: 1px solid #333; }
  tr:hover td { background: #2a2a2a; }
  .count { text-align: right; font-weight: bold; color: #7cf; }
  .zero { color: #555; }
  .reset-btn { margin-top: 16px; padding: 8px 18px; background: #a33; color: #fff;
               border: none; cursor: pointer; border-radius: 4px; font-size: 14px; }
  .reset-btn:hover { background: #c44; }
  .note { color: #888; font-size: 12px; margin-top: 8px; }
</style>
</head>
<body>
<h2>Database Connection Counts (since last reset)</h2>
<table>
  <tr><th>#</th><th>Call Site</th><th>Count</th></tr>
<?php
$total = 0;
for ($key = 1; $key <= DBL_MAX_KEY; $key++) {
    $label = DBL_LABELS[$key] ?? "Unknown ($key)";
    $raw = ReadCache($key);
    $count = is_numeric($raw) ? (int)$raw : 0;
    $total += $count;
    $countClass = $count === 0 ? 'count zero' : 'count';
    echo "<tr><td>" . $key . "</td><td>" . htmlspecialchars($label) . "</td>"
       . "<td class='" . $countClass . "'>" . number_format($count) . "</td></tr>\n";
}
?>
  <tr><td></td><td><strong>Total</strong></td><td class="count"><?= number_format($total) ?></td></tr>
</table>

<form method="POST" onsubmit="return confirm('Reset all counters to zero?');">
  <input type="hidden" name="reset" value="1">
  <button type="submit" class="reset-btn">Reset All Counters</button>
</form>
<p class="note">Counts are stored in shared memory (SHMOP) and reset on server restart or manual reset above.</p>
</body>
</html>
