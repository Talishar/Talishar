<?php
include_once 'Header.php';

// Check to see if the user is logged in, and redirects to MainMenu.php if they are

if (isset($_SESSION['useruid'])) {
  header("Location: /FaBOnline/MainMenu.php");
}
?>

<script src="https://accounts.google.com/gsi/client" async defer></script>

<section class="signup-form">
  <h2>LOG IN</h2>
  <div class="signup-form-form">
    <form action="includes/login.inc.php" method="post" style='overflow:hidden;'>
      <input type="text" name="uid" placeholder="Username...">
      <input type="password" name="pwd" placeholder="Password...">
      <label style='display:inline;' for="rememberMe">Remember Me</label>
      <input style='display:inline; width:16px;' type="checkbox" id="rememberMe" name="rememberMe" value="rememberMe">
      <button type="submit" name="submit">SUBMIT</button>
      <a href="ResetPassword.php">
        <button type="submit" name="reset-password">FORGOT PASSWORD</button>
      </a>
      <div class="centeredGoogle">
        <div id="g_id_onload" data-client_id="1089547347578-lqbvoqb0u3grqi89ibjmti2bc9lq37pr.apps.googleusercontent.com" data-login_uri="http://localhost/FaBOnline/MainMenu.php" data-auto_prompt="false">
        </div>
        <div class="g_id_signin" data-type="standard" data-shape="pill" data-theme="outline" data-text="signin_with" data-size="large" data-logo_alignment="left">
        </div>
      </div>
    </form>
  </div>


  <section class='signup-form' style='position:absolute; bottom:30px; right:30px; padding: 5px;'>
    <i>By using the Remember Me function, you consent to a cookie being stored in your browser for purpose of identifying your account on future visits.</i>
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