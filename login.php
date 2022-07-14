<?php
  include_once 'Header.php';
?>

<section class="signup-form">
  <h2>Log In</h2>
  <div class="signup-form-form">
    <form action="includes/login.inc.php" method="post">
      <input type="text" name="uid" placeholder="Username...">
      <input type="password" name="pwd" placeholder="Password...">
      <button type="submit" name="submit">Submit</button>
      <label style='display:inline;' for="rememberMe">Remember Me?</label>
      <input style='display:inline; width:16px;' type="checkbox" id="rememberMe" name="rememberMe" value="rememberMe">
      <a href="ResetPassword.php">
        <button type="submit" name="reset-password">Forgot Password</input>
      </a>
    </form>
  </div>

  <section class='signup-form' style='position:absolute; bottom:30px; right:30px;'>
    <i>By using the Remember Me function, you consent to a cookie being stored in your browser for purpose of identifying your account on future visits.</i>
  </div>
  <?php
    // Error messages
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "emptyinput") {
        echo "<p>Fill in all fields!</p>";
      }
      else if ($_GET["error"] == "wronglogin") {
        echo "<p>Wrong login!</p>";
      }
    }
  ?>
</section>

<?php
  include_once 'Footer.php';
?>
