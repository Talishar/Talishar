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
    // Only start session if it's not already active
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    session_unset();
    session_destroy();

    //Also delete cookies
    if (isset($_COOKIE["rememberMeToken"])) setcookie("rememberMeToken", "", time() + 1, "/");
    if (isset($_COOKIE["lastAuthKey"])) setcookie("lastAuthKey", "", time() + 1, "/");
  }

  function CheckSession()
  {
    if (session_status() === PHP_SESSION_NONE) {
      // Set secure session parameters - do this ONCE per PHP process, not per request
      ini_set('session.cookie_httponly', 1);
      // Only set secure flag if we're on HTTPS
      if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', 1);
      }
      ini_set('session.use_strict_mode', 1);
      ini_set('session.cookie_samesite', 'Lax');
      
      session_start();
    }
    
    // Only regenerate session ID if it hasn't been done recently
    // This prevents excessive CPU usage from repeated regeneration
    if (!isset($_SESSION['last_regeneration'])) {
      $_SESSION['last_regeneration'] = time();
      session_regenerate_id(true);
    } elseif (time() - $_SESSION['last_regeneration'] > 600) { // 10 minutes instead of 5 for performance
      session_regenerate_id(true);
      $_SESSION['last_regeneration'] = time();
    }
  }
  
  function SecureSessionStart()
  {
    if (session_status() === PHP_SESSION_NONE) {
      // Set secure session parameters - do this ONCE per PHP process, not per request
      ini_set('session.cookie_httponly', 1);
      // Only set secure flag if we're on HTTPS
      if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', 1);
      }
      ini_set('session.use_strict_mode', 1);
      ini_set('session.cookie_samesite', 'Lax');
      
      session_start();
      // Regenerate once at login for security
      session_regenerate_id(true);
    }
  }
?>
