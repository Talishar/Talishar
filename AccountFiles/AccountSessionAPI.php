<?php
  function IsUserLoggedIn()
  {
    return isset($_SESSION['useruid']);
  }

  function ClearLoginSession()
  {
    //First clear the session
    session_start();
    session_unset();
    session_destroy();

    //Also delete cookies
    if (isset($_COOKIE["rememberMeToken"])) setcookie("rememberMeToken", "", time() + 1, "/");
    if (isset($_COOKIE["lastAuthKey"])) setcookie("lastAuthKey", "", time() + 1, "/");
  }
?>
