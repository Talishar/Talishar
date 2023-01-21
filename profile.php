<?php
require "Header.php";

if(!isset($_SESSION['userid'])) { header('Location: ./MainMenu.php'); die(); }
?>

<?php

include_once "CardDictionary.php";
include_once "./Libraries/UILibraries2.php";
include_once "./APIKeys/APIKeys.php";

if(isset($_SESSION['userid']))
{
  $badges = LoadBadges($_SESSION['userid']);
  if (count($badges) > 0) {
    echo ("<section class='profile-form' style='position:absolute; width:32%; left:20px; top:40px; height:200px;'>");
    echo ("<h1>Your Badges</h1><br>");

    for($i=0; $i<count($badges); $i+=7)
    {
      if($badges[$i+6] != "") echo("<a href='" . $badges[$i+6] . "'>");
      echo ("<div style='float:left;'>");
      echo ("<div class='container'>");
      echo ("<img class='badge' src='" . $badges[$i + 5] . "'>");
      echo ("<div class='overlay'>");
      $bottomText = str_replace("{0}", $badges[$i+2], $badges[$i+4]);
      echo ("<div class='text'>" . $badges[$i + 3] . "<br><br>" . $bottomText . "</div>");
      echo ("</img></div></div></div>");
      if($badges[$i+6] != "") echo("</a>");
    }
    echo ("</section>");
  }
}

if (isset($_SESSION["isPatron"])) {
  if (isset($badges) && count($badges) > 0) {
  echo ("<section class='profile-form' style='position:absolute; width: 32%; left:20px; bottom:20px; height: calc(90% - 220px); overflow-y: scroll;'>");
  } else {
    echo ("<section class='profile-form' style='position:absolute; width: 32%; left:20px; top:40px; height: calc(90% - 129px); overflow-y: scroll;'>");
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

  <div class="wrapper"'>
    <div class="profile-form-form">
      <form action="Profile.php" method="post">


    <?php

    $client_id = $patreonClientID;
    $client_secret = $patreonClientSecret;

    // Set the redirect url where the user will land after oAuth. That url is where the access code will be sent as a _GET parameter. This may be any url in your app that you can accept and process the access code and login

    // In this case, say, /patreon_login request uri
    $redirect_uri = "https://www.talishar.net/game/PatreonLogin.php"; // Replace http://mydomain.com/patreon_login with the url at your site which is going to receive users returning from Patreon confirmation

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

    // Lets request identity of the user, and their pledges
    $scope_parameters = '&scope=identity%20identity.memberships';

    $href .= $scope_parameters;

    // Simply echoing it here. You can present the login link/button in any other way.

    if (!isset($_SESSION["patreonAuthenticated"])) {
      echo ("<BR>");
      echo ("<BR>");
      echo '<a class="containerPatreon" href="' . $href . '">';
      echo ("<img class='imgPatreon' src='./Assets/patreon-php-master/assets/images/login_with_patreon.png' alt='Login via Patreon'>");
      echo '</a>';
    }
    echo ("</section>");

    echo ("<section class='profile-form' style='position:absolute; width: 32%; right:20px; top:40px; height: 90vh; overflow-y: scroll;'>");
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
