<?php
  function IsUserLoggedIn()
  {
    CheckSession();
    return isset($_SESSION['useruid']);
  }

  function LoggedInUser()
  {
    CheckSession();
    return $_SESSION["userid"];
  }

  function LoggedInUserName()
  {
    CheckSession();
    return $_SESSION["useruid"];
  }

  function IsLoggedInUserPatron()
  {
    if(isset($_SESSION["useruid"]) && $_SESSION["useruid"] == "OotTheMonk") return true;
    if(isset($_SESSION["useruid"]) && $_SESSION["useruid"] == "PvtVoid") return true;
    return (isset($_SESSION["isPatron"]) || isset($_SESSION["isPvtVoidPatron"]) ? "1" : "0");
  }

  function SessionLastGameName()
  {
    CheckSession();
    if(!isset($_SESSION["lastGameName"])) return "";
    return $_SESSION["lastGameName"];
  }

  function SessionLastGamePlayerID()
  {
    CheckSession();
    return $_SESSION["lastPlayerId"];
  }

  function SessionLastAuthKey()
  {
    CheckSession();
    return $_SESSION["lastAuthKey"];
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

  function CheckSession()
  {
    if (session_status() === PHP_SESSION_NONE) {
      // Set secure session parameters
      ini_set('session.cookie_httponly', 1);
      ini_set('session.cookie_secure', 1);
      ini_set('session.use_strict_mode', 1);
      ini_set('session.cookie_samesite', 'Strict');
      
      session_start();
      
      // Regenerate session ID periodically for security
      if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
      } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutes
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
      }
    }
  }
  
  function SecureSessionStart()
  {
    if (session_status() === PHP_SESSION_NONE) {
      // Set secure session parameters
      ini_set('session.cookie_httponly', 1);
      ini_set('session.cookie_secure', 1);
      ini_set('session.use_strict_mode', 1);
      ini_set('session.cookie_samesite', 'Strict');
      
      session_start();
      session_regenerate_id(true);
    }
  }
?>
