<?php
include_once 'Header.php';

echo("PATREON LOGIN");

require_once './Assets/patreon-php-master/src/OAuth.php';
require_once './Assets/patreon-php-master/src/API.php';
require_once './Assets/patreon-php-master/src/PatreonLibraries.php';
require_once './includes/functions.inc.php';
include_once "./includes/dbh.inc.php";


  use Patreon\API;
  use Patreon\OAuth;

  $client_id = 'ZUg4PrZuOwdahOIqG8YP-OrEV3KTxgCWCmFa9eYKv1iKOgOoCIooooUZh9llfEZj';      // Replace with your data
  $client_secret = 'kU1g4JpVzEEK28bgDHLFRAiL0UBRa6-wWzvGV3cjELnG2o0-VfzOwbeiOGArYTpJ';  // Replace with your data
  $redirect_uri = "https://www.talishar.net/game/PatreonLogin.php";


  // The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above. It will grab the auth code from Patreon and get the tokens via the oAuth client

  if(isset($_GET['code']) && !empty($_GET['code']) ) {
  	$oauth_client = new OAuth($client_id, $client_secret);

  	$tokens = $oauth_client->get_tokens($_GET['code'], $redirect_uri);
  	$access_token = $tokens['access_token'];
  	$refresh_token = $tokens['refresh_token'];

  	// Here, you should save the access and refresh tokens for this user somewhere. Conceptually this is the point either you link an existing user of your app with his/her Patreon account, or, if the user is a new user, create an account for him or her in your app, log him/her in, and then link this new account with the Patreon account. More or less a social login logic applies here.
    SavePatreonTokens($access_token, $refresh_token);

  }

  if (isset($access_token)){
    PatreonLogin($access_token);
  }



include_once 'Footer.php'
?>
