<?php
  function IsUserLoggedIn()
  {
    return isset($_SESSION['useruid']);
  }
?>
