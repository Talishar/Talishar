<?php

// This example shows how to have your users log in via Patreon, and acquire access and refresh tokens after logging in

require_once __DIR__.'/vendor/autoload.php';
 
use Patreon\API;
use Patreon\OAuth;

$client_id = '';      // Replace with your data
$client_secret = '';  // Replace with your data

// Set the redirect url where the user will land after oAuth. That url is where the access code will be sent as a _GET parameter. This may be any url in your app that you can accept and process the access code and login

// In this case, say, /patreon_login request uri

$redirect_uri = "http://mydomain.com/patreon_login"; // Replace http://mydomain.com/patreon_login with the url at your site which is going to receive users returning from Patreon confirmation 

// Generate the oAuth url

$href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);

// You can send an array of vars to Patreon and receive them back as they are. Ie, state vars to set the user state, app state or any other info which should be sent back and forth. 

// for example lets set final page which the user needs to land at - this may be a content the user is unlocking via oauth, or a welcome/thank you page

// Lets make it a thank you page

$state = array();

$state['final_page'] = 'http://mydomain.com/thank_you'; // Replace http://mydomain.com/thank_you with the url that has your thank you page

// Add any number of vars you need to this array by $state['key'] = variable value

// Prepare state var. It must be json_encoded, base64_encoded and url encoded to be safe in regard to any odd chars
$state_parameters = '&state=' . urlencode( base64_encode( json_encode( $state ) ) );

// Append it to the url 

$href .= $state_parameters;

// Now place the url into a login link. Below is a very simple login link with just text. in assets/images folder, there is a button image made with official Patreon assets (login_with_patreon.php). You can also use this image as the inner html of the <a> tag instead of the text provided here

// Scopes! You must request the scopes you need to have the access token.
// In this case, we are requesting the user's identity (basic user info), user's email
// For example, if you do not request email scope while logging the user in, later you wont be able to get user's email via /identity endpoint when fetching the user details
// You can only have access to data identified with the scopes you asked. Read more at https://docs.patreon.com/#scopes

// Lets request identity of the user, and email.

$scope_parameters = '&scope=identity%20identity'.urlencode('[email]');

$href .= $scope_parameters;

// Simply echoing it here. You can present the login link/button in any other way.

echo '<a href="'.$href.'">Click here to login via Patreon</a>';

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
