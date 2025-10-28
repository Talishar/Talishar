<?php
include_once 'MenuBar.php';
?>

<section class="signup-form">
  <h2>SIGN UP</h2>
  <div class="signup-form-form">
    <form action="includes/signup.inc.php" method="post">
      <label for="uid">Username
        <input type="text" name="uid">
      </label>
      <label for="email">Email
        <input type="text" name="email" placeholder="name@example.com">
      </label>
      <label for="pwd">Password
        <input type="password" name="pwd" placeholder="Password...">
      </label>
      <label for="pwdrepeat">Repeat password...
        <input type="password" name="pwdrepeat" placeholder="Repeat password...">
      </label>
      <button type="submit" name="submit">SIGN UP</button>
    </form>
  </div>
  <br>
  <?php
  // Error messages
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
      echo "<p>Fill in all fields!</p>";
    } else if ($_GET["error"] == "invaliduid") {
      echo "<p>Choose a username without any special characters</p>";
    } else if ($_GET["error"] == "invalidemail") {
      echo "<p>Choose a valid email</p>";
    } else if ($_GET["error"] == "passwordsdontmatch") {
      echo "<p>Passwords doesn't match!</p>";
    } else if ($_GET["error"] == "stmtfailed") {
      echo "<p>Something went wrong!</p>";
    } else if ($_GET["error"] == "usernametaken") {
      echo "<p>Username already taken!</p>";
    } else if ($_GET["error"] == "emailtaken") {
      echo "<p>Email already taken!</p>";
    } else if ($_GET["error"] == "none") {
      echo "<h2>You've signed up!</h2>";
    }
  }
  ?>
</section>