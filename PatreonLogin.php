<?php
include_once 'Header.php';

echo("PATREON LOGIN");


  // The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above. It will grab the auth code from Patreon and get the tokens via the oAuth client

  if ( isset($_GET['code']) && !empty($_GET['code']) ) {

  	$oauth_client = new OAuth($client_id, $client_secret);

  	$tokens = $oauth_client->get_tokens($_GET['code'], $redirect_uri);
  	$access_token = $tokens['access_token'];
  	$refresh_token = $tokens['refresh_token'];

  	// Here, you should save the access and refresh tokens for this user somewhere. Conceptually this is the point either you link an existing user of your app with his/her Patreon account, or, if the user is a new user, create an account for him or her in your app, log him/her in, and then link this new account with the Patreon account. More or less a social login logic applies here.

  }

  if (isset($access_token)){

  	// After linking an existing account or a new account with Patreon by saving and matching the tokens for a given user, you can then read the access token (from the database or whatever resource), and then just check if the user is logged into Patreon by using below code. Code from down below can be placed wherever in your app, it doesnt need to be in the redirect_uri at which the Patreon user ends after oAuth. You just need the $access_token for the current user and thats it.

  	// Lets say you read $access_token for current user via db resource, or you just acquired it through oAuth earlier like the above - create a new API client

  	$api_client = new API($access_token);

  	// Return from the API can be received in either array, object or JSON formats by setting the return format. It defaults to array if not specifically set. Specifically setting return format is not necessary. Below is shown as an example of having the return parsed as an object. If there is anyone using Art4 JSON parser lib or any other parser, they can just set the API return to JSON and then have the return parsed by that parser

  	// You dont need the below line if you simply want the result as an array
  	$api_client->api_return_format = 'object';

  	// Now get the current user:
  	$patron_response = $api_client->fetch_user();

  	// At this point you can do anything with the user return. For example, if there is no return for this user, then you can consider the user not logged into Patreon. Or, if there is return, then you can get the user's Patreon id or pledge info. For example if you are able to acquire user's id, then you can consider the user logged into Patreon.

  }



include_once 'Footer.php'
?>
