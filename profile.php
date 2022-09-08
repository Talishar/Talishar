<?php
require "Header.php";
?>

<?php

include_once "CardDictionary.php";
include_once "./Libraries/UILibraries2.php";

if (isset($_POST['update_profile'])) {
/*
  $user_id = $_SESSION['userid'];
  $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
  $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

  if ($update_name != $_SESSION['useruid'] || $update_email != $_SESSION['useremail']) {
    mysqli_query($conn, "UPDATE users SET usersUid = '$update_name', usersEmail = '$update_email' WHERE usersId = '$user_id'") or die('query failed');
    $_SESSION['useruid'] = $update_name;
    $_SESSION['useremail'] = $update_email;
  }

  $old_pass = mysqli_real_escape_string($conn, $_POST['old_pass']);
  $update_pass = mysqli_real_escape_string($conn, $_POST['update_pass']);
  $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
  $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

  if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
    // Check if the two new passwords are the same
    if ($new_pass != $confirm_pass) {
      $message[] = "New password doesn't matched!";
      // Verify that the password is the same as the hashed pwd in the database
    } elseif (password_verify($update_pass, $old_pass)) {
      // Hash new password for security
      $confirmed_hashedPwd = password_hash($confirm_pass, PASSWORD_DEFAULT);
      mysqli_query($conn, "UPDATE users SET usersPwd = '$confirmed_hashedPwd' WHERE usersId = '$user_id'") or die('query failed');
      $_SESSION['userspwd'] = $confirmed_hashedPwd;
      $message[] = "Your password was updated!";
    } else {
      $message[] = "Old password doesn't matched!";
    }
  }
  $message[] = "Profile saved!";
  */
}

if(isset($_SESSION['userid']))
{
  $badges = LoadBadges($_SESSION['userid']);
  if (count($badges) > 0) {
    echo ("<section class='profile-form' style='position:absolute; width:32%; left:20px; top:40px; height:200px;'>");
    echo ("<h1>Your Badges</h1><br>");

    for($i=0; $i<count($badges); $i+=6)
    {
      echo ("<div style='float:left;'>");
      echo ("<div class='container'>");
      echo ("<img class='badge' src='" . $badges[$i + 5] . "'>");
      echo ("<div class='overlay'>");
      $bottomText = str_replace("{0}", $badges[$i+2], $badges[$i+4]);
      echo ("<div class='text'>" . $badges[$i + 3] . "<br><br>" . $bottomText . "</div>");
      echo ("</img></div></div></div>");
    }
    echo ("</section>");
  }
}

if (isset($_SESSION["isPatron"])) {
  if (isset($badges) && count($badges) > 0) {
  echo ("<section class='profile-form' style='position:absolute; width: 32%; left:20px; bottom:20px; height: calc(90% - 220px);'>");
  } else {
    echo ("<section class='profile-form' style='position:absolute; width: 32%; left:20px; top:40px; height: calc(90% - 129px);'>");
  }
  echo ("<h1>Your Record</h1>");
  $forIndividual = true;
  include_once "zzGameStats.php";
  echo ("</section>");
}

?>


<script src="./jsInclude.js"></script>
<div id="cardDetail" style="z-index:100000; display:none; position:fixed;"></div>

<section class="profile-form">
  <h2>Welcome <?php echo $_SESSION['useruid'] ?>!</h2>

  <?php
  $uidExists = getUInfo($conn, $_SESSION['useruid']);
  $_SESSION["useremail"] = $uidExists["usersEmail"];
  $_SESSION["userspwd"] = $uidExists["usersPwd"];
  $_SESSION["userKarma"] = $uidExists["usersKarma"];

  ?>

  <div class="wrapper"'>
    <div class="profile-form-form">
      <form action="Profile.php" method="post">

        <img src="Images/default-avatar.jpg" class=' avatarImage' alt="Avatar">


    <?php
    if ($_SESSION["userKarma"] < 40) $repColor = "red";
    else if ($_SESSION["userKarma"] < 65) $repColor = "orange";
    else $repColor = "green";

    echo ("<div class='karma-container'>");
    echo ("<div style='margin-bottom: 3px;'>Your Reputation:");

    echo ("<div class='karma-hover'><span class='karma-title'>How does reputation score (Karma) work?</span><br>
    The Karma score (☯) is a quick way to check if a player has a good reputation on Talishar (does not quit games, is friendly, plays fair).<br><br>
    Depending on your Karma score, you may also be allowed or not allowed to use some features or to join a given game.<br><br>
    Your initial Karma score is 75☯ (on a maximum of 100☯). Then:<br>
    &#8226; Each time you finish a game, you get +1☯.<br>
    &#8226; If you quit a game in progress, you lose 10☯ (conceding does not count as quitting).
    </div></div>");

    //<br>
    //&#8226; If you receive too many 'Red thumbs-down' from other players when compared to the amount of 'Green thumbs-up' received, your karma will be reduced each time you receive a Red Thumb.<br>
    //In this case, the best is to have a good behavior to get Green thumb and restore your ratio and reputation.

    echo ("<div class='karma-light-grey'>");
    echo ("<div class='karma-container karma-" . $repColor . "' style='width:" . $_SESSION["userKarma"] . "%'>&nbsp;</div>");
    echo ("<div class='karma-amount'>☯ " . $_SESSION["userKarma"] . "</div>");
    echo ("</div></div><br>");

    ?>
<!--
    <div>Username:</div>
    <input type="text" name="update_name" value="<?php echo $_SESSION['useruid']; ?>">
    <div>Your email:</div>
    <input type="email" name="update_email" value="<?php echo $_SESSION['useremail']; ?>">

    <div>Update your avatar :</div>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png">

    <input type="hidden" name="old_pass" value="<?php echo $_SESSION['userspwd']; ?>">
    <div>Old password:</div>
    <input type="password" name="update_pass" placeholder="Enter Password">
    <div>New password:</div>
    <input type="password" name="new_pass" placeholder="Enter New Password">
    <div>Confirm password:</div>
    <input type="password" name="confirm_pass" placeholder="Confirm New Password">
    <button type="submit" name="update_profile">UPDATE PROFILE</button>
 -->
    <?php
    if (isset($message)) {
      foreach ($message as $message) {
        echo '<p>' . $message . '</p>';
      }
    }


    $client_id = 'ZUg4PrZuOwdahOIqG8YP-OrEV3KTxgCWCmFa9eYKv1iKOgOoCIooooUZh9llfEZj';      // Replace with your data
    $client_secret = 'kU1g4JpVzEEK28bgDHLFRAiL0UBRa6-wWzvGV3cjELnG2o0-VfzOwbeiOGArYTpJ';  // Replace with your data

    // Set the redirect url where the user will land after oAuth. That url is where the access code will be sent as a _GET parameter. This may be any url in your app that you can accept and process the access code and login

    // In this case, say, /patreon_login request uri
    $redirect_uri = "https://www.fleshandbloodonline.com/FaBOnline/PatreonLogin.php"; // Replace http://mydomain.com/patreon_login with the url at your site which is going to receive users returning from Patreon confirmation

    // Generate the oAuth url
    $href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);

    // You can send an array of vars to Patreon and receive them back as they are. Ie, state vars to set the user state, app state or any other info which should be sent back and forth.
    // for example lets set final page which the user needs to land at - this may be a content the user is unlocking via oauth, or a welcome/thank you page
    // Lets make it a thank you page

    $state = array();

    $state['final_page'] = 'http://fleshandbloodonline.com/FaBOnline/MainMenu.php'; // Replace http://mydomain.com/thank_you with the url that has your thank you page

    // Add any number of vars you need to this array by $state['key'] = variable value

    // Prepare state var. It must be json_encoded, base64_encoded and url encoded to be safe in regard to any odd chars
    $state_parameters = '&state=' . urlencode(base64_encode(json_encode($state)));

    // Append it to the url

    $href .= $state_parameters;

    // Lets request identity of the user, and email.
    $scope_parameters = '&scope=identity%20identity' . urlencode('[email]');

    $href .= $scope_parameters;

    // Simply echoing it here. You can present the login link/button in any other way.

    if (!isset($_SESSION["isPatron"])) {
      echo ("<BR>");
      echo ("<BR>");
      echo '<a class="containerPatreon" href="' . $href . '">';
      echo ("<img class='imgPatreon' src='./Assets/patreon-php-master/assets/images/login_with_patreon.png' alt='Login via Patreon'>");
      echo '</a>';
    }

    echo ("<section class='profile-form' style='position:fixed; display:block; width: 32%; right:20px; top:40px; padding-bottom: -0px;'>");
    echo ("<h1>Favorite Decks</h1>");
    $favoriteDecks = LoadFavoriteDecks($_SESSION["userid"]);
    if (count($favoriteDecks) > 0) {
      echo ("<table>");
      echo ("<tr><td>Hero</td><td>Deck Name</td><td>Delete</td></tr>");
      for ($i = 0; $i < count($favoriteDecks); $i += 3) {
        echo ("<tr>");
        echo ("<td>" . CardLink($favoriteDecks[$i + 2], $favoriteDecks[$i + 2], true) . "</td>");
        echo ("<td>" . $favoriteDecks[$i + 1] . "</td>");
        echo ("<td><a style='text-underline-offset:5px;' href='./MenuFiles/DeleteDeck.php?decklink=" . $favoriteDecks[$i] . "'>Delete</a></td>");
        //echo ("<div id='" . $favoriteDecks[$i] . "'>" . $favoriteDecks[$i + 1] . "(" . $favoriteDecks[$i+2] . ")</div>");
        echo ("</tr>");
      }
      echo ("</table>");
    }
    echo ("</section>");

    ?>

  </div>
  </div>
  </form>
</section>

<?php
require "Footer.php";
?>
