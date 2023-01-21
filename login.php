<?php
include_once 'Header.php';

if (isset($_SESSION['useruid'])) {
  header("Location: /FaBOnline/MainMenu.php");
}
?>

<section class="signup-form">
  <h2>LOG IN</h2>
  <div class="signup-form-form">
    <form action="includes/login.inc.php" method="post" style='overflow:hidden;'>
      <input type="text" name="uid" placeholder="Username...">
      <input type="password" name="pwd" placeholder="Password...">
      <label style='display:inline;' for="rememberMe">Remember Me</label>
      <input style='display:inline; width:16px;' type="checkbox" checked='checked' id="rememberMe" name="rememberMe" value="rememberMe">
      <button type="submit" name="submit">SUBMIT</button>
    </form>
  </div>
  <div class="signup-form-form">
    <form action="ResetPassword.php" method="post" style='overflow:hidden;'>
          <button type="submit" name="reset-password">FORGOT PASSWORD</button>
    </form>
  </div>
</section>

  <section class='signup-form' style='position:absolute; bottom:30px; right:30px; padding: 5px;'>
    <i>By using the Remember Me function, you consent to a cookie being stored in your browser for purpose of identifying your account on future visits.</i>
    <div style='width:100%; text-align:center'><a href='./MenuFiles/PrivacyPolicy.php'>Privacy Policy</a></div>
  </section>
  </div>
  <?php
  // Error messages
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
      echo "<p>Fill in all fields!</p>";
    } else if ($_GET["error"] == "wronglogin") {
      echo "<p>Wrong login!</p>";
    }
  }
  ?>
</section>

<?php
include_once 'Footer.php';
?>
