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
    if(($_SESSION["isPatron"] ?? false) || ($_SESSION["isPvtVoidPatron"] ?? false)) {
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
    if (isset($_COOKIE["metafyRememberToken"])) setcookie("metafyRememberToken", "", time() + 1, "/");
  }

  function CheckSession()
  {
    if (session_status() === PHP_SESSION_NONE) {
      // Set secure session parameters
      ini_set('session.cookie_httponly', 1);
      // Only set secure flag if we're on HTTPS
      if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', 1);
      }
      ini_set('session.use_strict_mode', 1);
      ini_set('session.cookie_samesite', 'Lax');
      ini_set('session.gc_maxlifetime', 86400);
      
      session_start();
      
      if (empty($_SESSION['userid']) && isset($_COOKIE['metafyRememberToken'])) {
        RestoreMetafySession($_COOKIE['metafyRememberToken']);
      }
      
      // Note: session ID regeneration is done explicitly at login time only,
      // not periodically here, to avoid losing the new ID when Set-Cookie is
      // stripped by a reverse proxy (Cloudflare, nginx, etc.).
    }
  }
  
  function RestoreMetafySession($rememberToken)
  {
    if (empty($rememberToken)) {
      return;
    }
    
    $conn = GetDBConnection();
    if (!$conn) {
      return;
    }
    
    $sql = "SELECT usersid, usersUid, isPatron, metafyCommunities FROM users WHERE metafyRememberToken=?";
    $stmt = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, 's', $rememberToken);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      
      if ($row) {
        $_SESSION['userid'] = $row['usersid'];
        $_SESSION['useruid'] = $row['usersUid'];
        $_SESSION['isPatron'] = $row['isPatron'] ?? 0;
        $_SESSION['last_activity'] = time();
        $_SESSION['last_regeneration'] = time();
      }
    }
    
    mysqli_close($conn);
  }
  
  function SecureSessionStart()
  {
    CheckSession();
    session_regenerate_id(true);
  }
?>
