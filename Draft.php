<?php
  include "HostFiles/Redirector.php";
  include_once 'Header.php';
?>

<section class="draft-form">
  <h2>Solo Draft Practice</h2>
  <div class="draft-form-form">
    <form action="DraftFiles/CreateGame.php" method="post">
      <input type="hidden" id="numPlayers" name="numPlayers" value="8">
      <input type="submit" style="font-size:20px;" value="Tales of Aria">
      </div>
    </form>
  </div>

  <div class="draft-form-form">
    <form action="WTRDraftFiles/CreateGame.php" method="post">
      <input type="hidden" id="numPlayers" name="numPlayers" value="8">
      <input type="submit" style="font-size:20px;" value="Welcome To Rathe">
    </form>
  </div>

  <h2>Solo Sealed Practice</h2>
  <div class="draft-form-form">
    <form action="SealedFiles/CreateGame.php" method="post">
      <input type="submit" style="font-size:20px;" value="Uprising">
    </form>
  </div>
</section>
<?php
  include_once 'Footer.php'
?>
