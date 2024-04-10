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
    if(isset($_SESSION["useruid"]) && $_SESSION["useruid"] == "lanczzz") return true;
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
      session_start();
    }
  }
?>
