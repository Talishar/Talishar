<html>
<head>
  <link rel="stylesheet" type="text/css" href="modStylePage.css">
</head>
<body>
  
<?php
include_once 'MenuBar.php';
include_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";

if (!isset($_SESSION["useruid"])) {
  echo ("Please login to view this page.");
  exit;
}
$useruid = $_SESSION["useruid"];
if ($useruid != "OotTheMonk" && $useruid != "Launch" && $useruid != "LaustinSpayce" && $useruid != "Star_Seraph" && $useruid != "bavverst" && $useruid != "Tower" && $useruid != "PvtVoid") {
  echo ("You must log in to use this page.");
  exit;
}
?>

<div class="mod-page">
  <h1>Mod Panel</h1>
  <form class="ban-player-form">
    <label for="ipToBan">Game to IP ban from:</label>
    <input type="text" id="ipToBan" name="ipToBan" value="">
    <label for="playerNumberToBan">Player to ban? (1 or 2):</label>
    <input type="text" id="playerNumberToBan" name="playerNumberToBan" value="">
    <input type="submit" value="Ban">
  </form>

  <br>

  <form class="close-game-form">
    <label for="gameToClose">Game to close:</label>
    <input type="text" id="gameToClose" name="gameToClose" value="">
    <input type="submit" value="Close Game">
  </form>

  <h1>Banned players:</h1>
  <ul class="banned-players-list">
    <?php
    $banfileHandler = fopen("./HostFiles/bannedPlayers.txt", "r");
    while (!feof($banfileHandler)) {
      $bannedPlayer = fgets($banfileHandler);
      echo "<li>$bannedPlayer</li>";
    }
    fclose($banfileHandler);
    ?>
  </ul>

  <br><br>

  <form class="ban-player-form">
    <label for="playerToBan">Player to ban:</label>
    <input type="text" id="playerToBan" name="playerToBan" value="">
    <input type="submit" value="Ban">
  </form>

  <br>

  <h1>Most recently created accounts:</h1>
  <ul class="recent-accounts-list">
    <?php
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
    $userData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($userData, MYSQLI_NUM)) {
      echo "<li>$row[0]</li>";
    }
    ?>
  </ul>

  <br>

  <h1>Banned IPs:</h1>
  <ul class="banned-ips-list">
    <?php
    $banfileHandler = fopen("./HostFiles/bannedIPs.txt", "r");
    while (!feof($banfileHandler)) {
      $bannedIP = fgets($banfileHandler);
      echo "<li>$bannedIP</li>";
    }
    fclose($banfileHandler);
    ?>
  </ul>
</div>
</body>
</html>
