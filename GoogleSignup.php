<?php
include_once 'Header.php';
?>

<section class="signup-form">
  <h2>Sign Up</h2>
  <div class="signup-form-form">
    <form action="includes/google-signup.inc.php" method="post">
      <input type="text" name="uid" placeholder="Username...">
      <input autocomplete='off' tabindex='3' type="text" name="email" value="<?php echo $_GET["useremail"]; ?>" maxlength='50' size="25">
      <button type="submit" name="submit">Sign up</button>
    </form>
  </div>
  <?php
  // Error messages
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
      echo "<p>Fill in all fields!</p>";
    } else if ($_GET["error"] == "invaliduid") {
      echo "<p>Choose a proper username!</p>";
    } else if ($_GET["error"] == "invalidemail") {
      echo "<p>Choose a proper email!</p>";
    } else if ($_GET["error"] == "stmtfailed") {
      echo "<p>Something went wrong!</p>";
    } else if ($_GET["error"] == "usernametaken") {
      echo "<p>Username already taken!</p>";
    } else if ($_GET["error"] == "none") {
      echo "<p>You've signed up!</p>";
    }
  }
  ?>
</section>

<?php
include_once 'Footer.php';
?>