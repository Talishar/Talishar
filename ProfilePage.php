<?php
require "MenuBar.php";

if (!isset($_SESSION['userid'])) {
  header('Location: ./MainMenu.php');
  die();
}

include_once "CardDictionary.php";
include_once "./Libraries/UILibraries.php";
include_once "./APIKeys/APIKeys.php";



if (isset($_SESSION["isPatron"])) {
  echo ("<div class='ContentWindow' style='width: 50%; left:20px; height: calc(90% - 220px); bottom:20px; overflow-y: scroll;'>");
  echo ("<h1>Your Record</h1>");
  $forIndividual = true;
  echo ("</div>");
}

?>

<script src="./jsInclude.js"></script>
<div id="cardDetail" style="z-index:100000; display:none; position:fixed;"></div>

<div class='ContentWindow' style='left:60%; right:20px; top:60px; height:90%;'>
  <h2>Welcome <?php echo htmlspecialchars($_SESSION['useruid'], ENT_QUOTES, 'UTF-8') ?>!</h2>

  <?php

  DisplayPatreon();

  echo ("<h1>Favorite Decks</h1>");
  $favoriteDecks = LoadFavoriteDecks($_SESSION["userid"]);
  if (count($favoriteDecks) > 0) {
    echo ("<table>");
    echo ("<tr><td>Hero</td><td>Deck Name</td><td>Delete</td></tr>");
    for ($i = 0; $i < count($favoriteDecks); $i += 4) {
      echo ("<tr>");
      echo ("<td>" . CardLink($favoriteDecks[$i + 2], $favoriteDecks[$i + 2], true) . "</td>");
      echo ("<td>" . $favoriteDecks[$i + 1] . "</td>");
      echo ("<td><a style='text-underline-offset:5px;' href='./MenuFiles/DeleteDeck.php?decklink=" . $favoriteDecks[$i] . "'>Delete</a></td>");
      echo ("</tr>");
    }
    echo ("</table>");
  }

  function DisplayPatreon()
  {
    global $patreonClientID, $patreonClientSecret;
    $client_id = $patreonClientID;
    $client_secret = $patreonClientSecret;

    //$redirect_uri = "https://www.talishar.net/game/PatreonLogin.php";
    $redirect_uri = "https://legacy.talishar.net/game/PatreonLogin.php";
    $href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);
    $state = array();
    $state['final_page'] = 'http://fleshandbloodonline.com/FaBOnline/MainMenu.php';
    $state_parameters = '&state=' . urlencode(base64_encode(json_encode($state)));
    $href .= $state_parameters;
    $scope_parameters = '&scope=identity%20identity.memberships';
    $href .= $scope_parameters;
    if (!isset($_SESSION["patreonAuthenticated"])) {
      echo ("<BR>");
      echo ("<BR>");
      echo '<a class="containerPatreon" href="' . $href . '">';
      echo ("<img class='imgPatreon' src='./Assets/patreon-php-master/assets/images/login_with_patreon.png' alt='Login via Patreon'>");
      echo '</a>';
    }
  }
  ?>
</div>
