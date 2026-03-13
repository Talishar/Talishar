<?php

include_once 'MenuBar.php';

include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";
include_once './Libraries/CSRFLibraries.php';
include_once './includes/ModeratorList.inc.php';

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

if (!isset($_SESSION["useruid"])) {
  echo ("Please login to view this page.");
  exit;
}
$useruid = $_SESSION["useruid"];
if (!IsUserModerator($useruid)) {
  echo ("You must log in to use this page.");
  exit;
}

echo ("<div style='position:absolute; padding:10px; z-index:1; top:15%; left:35%; width:500px; height:650px;
  background-color:rgba(74, 74, 74, 0.9); border: 2px solid #1a1a1a; border-radius: 5px; overflow-y: scroll;'>");

?>
  <br>
  <form action='./BanPlayer.php' method="POST">
    <?php echo getCSRFTokenField(); ?>
    <label for="ipToBan" style='font-weight:bolder; margin-left:10px;'>Game to IP ban from:</label>
    <input type="text" id="ipToBan" name="ipToBan" value="">
    <br>
    <label for="playerNumberToBan" style='font-weight:bolder; margin-left:10px;'>Player to ban? (1 or 2):</label>
    <input type="text" id="playerNumberToBan" name="playerNumberToBan" value="">
    <input type="submit" value="Ban">
  </form>
  
  <br>
  
  <form action='./CloseGame.php' method="POST">
    <?php echo getCSRFTokenField(); ?>
    <label for="gameToClose" style='font-weight:bolder; margin-left:10px;'>Game to close:</label>
    <input type="text" id="gameToClose" name="gameToClose" value="">
    <input type="submit" value="Close Game">
  </form>

  <br>

  <form action='./BanPlayer.php' method="POST">
  <?php echo getCSRFTokenField(); ?>
  <label for="playerToBan" style='font-weight:bolder; margin-left:10px;'>Player to ban:</label>
  <input type="text" id="playerToBan" name="playerToBan" value="">
  <input type="submit" value="Ban">
  </form>

<?php

echo ("<h1>Banned players:</h1>");
$banfileHandler = fopen("./HostFiles/bannedPlayers.txt", "r");
while (!feof($banfileHandler)) {
  $bannedPlayer = fgets($banfileHandler);
  echo ($bannedPlayer . "<BR>");
}
fclose($banfileHandler);

$sql = "SELECT usersUid FROM users ORDER BY usersId DESC LIMIT 20";
$conn = GetDBConnection();
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  //header("location: ../Signup.php?error=stmtfailed");
  echo ("ERROR");
  exit();
}

//mysqli_stmt_bind_param($stmt, "ss", $username, $email);
mysqli_stmt_execute($stmt);

// "Get result" returns the results from a prepared statement
echo ("<br><h1>Most recently created accounts:</h1>");
$userData = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_array($userData, MYSQLI_NUM)) {
  echo ($row[0] . "<BR>");
}


echo ("<br><h1>Banned IPs:</h1>");
$banfileHandler = fopen("./HostFiles/bannedIPs.txt", "r");
while (!feof($banfileHandler)) {
  $bannedIP = fgets($banfileHandler);
  echo ($bannedIP . "<BR>");
}
fclose($banfileHandler);


// SQL Query Runner
echo ("<br><h1>Run SQL Query:</h1>");

$queryExecuted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sqlQuery'])) {
  try {
    $rawQuery = trim($_POST['sqlQuery']);
    if ($rawQuery !== '') {
      $queryExecuted = true;
      $conn = GetDBConnection();
      $result = mysqli_query($conn, $rawQuery);
      if ($result === false) {
        echo "<p style='color:red; margin-left:10px; padding:10px; background:rgba(255,0,0,0.2); border-radius:3px;'><strong>❌ Query Error:</strong> " . htmlspecialchars(mysqli_error($conn)) . "</p>";
      } elseif ($result === true) {
        $affected = mysqli_affected_rows($conn);
        echo "<p style='color:#00ff00; margin-left:10px; padding:10px; background:rgba(0,255,0,0.1); border-radius:3px;'>✅ Query OK. Affected rows: " . intval($affected) . "</p>";
      } else {
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $rows[] = $row;
        }
        mysqli_free_result($result);
        echo "<p style='color:#00ff00; margin-left:10px;'>✅ Query returned " . count($rows) . " row(s).</p>";
        if (count($rows) > 0) {
          echo "<div style='overflow-x:auto; margin-left:10px;'><table border='1' cellpadding='4' style='border-collapse:collapse; font-size:12px;'>";
          echo "<tr>";
          foreach (array_keys($rows[0]) as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
          }
          echo "</tr>";
          foreach ($rows as $row) {
            echo "<tr>";
            foreach ($row as $val) {
              echo "<td>" . htmlspecialchars((string)$val) . "</td>";
            }
            echo "</tr>";
          }
          echo "</table></div>";
        }
      }
    }
  } catch (Exception $e) {
    echo "<p style='color:red; margin-left:10px; padding:10px; background:rgba(255,0,0,0.2); border-radius:3px;'><strong>❌ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
  }
}

echo "<form action='./zzModPage.php' method='POST'>";
echo getCSRFTokenField();
echo "<label for='sqlQuery' style='font-weight:bolder; margin-left:10px;'>SQL Query:</label><br>";
echo "<textarea id='sqlQuery' name='sqlQuery' rows='5' style='width:480px; margin-left:10px; font-family:monospace; background:#2a2a2a; color:#fff; padding:5px; border:1px solid #666;'>";
echo isset($_POST['sqlQuery']) ? htmlspecialchars($_POST['sqlQuery']) : '';
echo "</textarea>";
echo "<br>";
echo "<input type='submit' value='Run Query' style='margin-left:10px; margin-top:5px; padding:8px 15px; background:#4CAF50; color:white; border:none; border-radius:3px; cursor:pointer;'>";
echo "</form>";

?>

</div>
