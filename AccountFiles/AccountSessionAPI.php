<?php
  function IsUserLoggedIn()
  {
    CheckSession();
    if (!isset($_SESSION['useruid'])) {
      return false;
    }
    if (IsSessionExpired()) {
      ClearLoginSession();
      return false;
    }
    UpdateSessionActivity();
    return true;
  }

  function LoggedInUser()
  {
    CheckSession();
    return $_SESSION["userid"];
  }

  function LoggedInUserName()
  {
    CheckSession();
    return $_SESSION["useruid"] ?? "";
  }

  function IsLoggedInUserPatron()
  {
    if(isset($_SESSION["useruid"]) && $_SESSION["useruid"] == "OotTheMonk") return true;
    if(isset($_SESSION["useruid"]) && $_SESSION["useruid"] == "PvtVoid") return true;
    
    // Check if user is a supporter
    if(isset($_SESSION["isPatron"]) || isset($_SESSION["isPvtVoidPatron"])) {
      return "1";
    }
    
    // Check if user is a Metafy Talishar supporter
    if(isset($_SESSION["useruid"])) {
      $userName = $_SESSION["useruid"];
      $conn = GetDBConnection();
      if ($conn) {
        $sql = "SELECT metafyCommunities FROM users WHERE usersUid=?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
          mysqli_stmt_bind_param($stmt, 's', $userName);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
          mysqli_stmt_close($stmt);
          
          if ($row && !empty($row['metafyCommunities'])) {
            $communities = json_decode($row['metafyCommunities'], true);
            if (is_array($communities)) {
              // Check if Talishar community (UUID: be5e01c0-02d1-4080-b601-c056d69b03f6) is in the list
              foreach($communities as $community) {
                if(isset($community['id']) && $community['id'] === 'be5e01c0-02d1-4080-b601-c056d69b03f6') {
                  return "1";
                }
              }
            }
          }
        }
      }
    }
    
    return "0";
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
    return $_SESSION["lastPlayerId"] ?? null;
  }

  function SessionLastAuthKey()
  {
    CheckSession();
    return $_SESSION["lastAuthKey"] ?? null;
  }

  function UpdateSessionActivity()
  {
    CheckSession();
    $_SESSION['last_activity'] = time();
  }

  function IsSessionExpired()
  {
    CheckSession();
    $maxInactivity = 86400; // 24 hours
    if (isset($_SESSION['last_activity'])) {
      if (time() - $_SESSION['last_activity'] > $maxInactivity) {
        return true;
      }
    }
    return false;
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
      // Only set ini options if headers haven't been sent yet
      if (!headers_sent()) {
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1);
        // Only set secure flag if we're on HTTPS
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
          ini_set('session.cookie_secure', 1);
        }
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.gc_maxlifetime', 86400);
      }
      
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
    CheckSession();
    session_regenerate_id(true);
  }
?>
