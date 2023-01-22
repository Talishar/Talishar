<?php
include_once 'MenuBar.php';

if (isset($_SESSION['useruid'])) {
  header("Location: ./game/MainMenu.php");
}
?>

<div class="ContentWindow" style='width:30%; height:40%; top:200px; left:35%; text-align:left;'>
  <h2>Login</h2>
  <form action="includes/login.inc.php" method="post">
    <label for="uid">User name
      <input id="uid" type="text" name="uid" placeholder="Username">
    </label>
    <label for="pwd">Password
      <input type="password" id="pwd" name="pwd" placeholder="Password">
    </label>
    <label>
      <input type="checkbox" checked="checked" id="rememberMe" value="rememberMe">
      Remember Me
    </label>
    <button type="submit" name="submit">SUBMIT</button>
  </form>
  <form action="ResetPassword.php" method="post" style='text-align:center;'>
    <button type="submit" name="reset-password">FORGOT PASSWORD</button>
  </form>
</div>

<div class='ContentWindow' style='width:250px; height:400px; bottom:30px; right:30px; padding: 5px;'>
  <i>By using the Remember Me function, you consent to a cookie being stored in your browser for purpose of identifying your account on future visits.</i>
  <br>
  <div style='width:100%; text-align:center'><a href='./MenuFiles/PrivacyPolicy.php'>Privacy Policy</a></div>
</div>

<?php
include_once 'Disclaimer.php';
?>
