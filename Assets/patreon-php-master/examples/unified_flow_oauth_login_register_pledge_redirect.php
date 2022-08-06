<?php

//
// This example shows the best way to have users unlock content or features at your site/app via Patreon
//

/* 

Unified flow is a flow at Patreon to which you can send users to unlock a feature or a piece of content at your site/app.

You provide a $ amount to ask for pledge from the user, and a final redirect url to return the user to.

Patreon handles the rest - if the user is not logged in or registered, asks the user to register or login. If the user is not your patron, asks the user to become your patron. If the user doesnt have enough pledge, asks the user to pledge more. 

All happen in conjunction - if user is not logged in, then s/he is first asked to login, and then asked to pledge etc. All in a single, smooth, unified flow.

After this process is complete, the user is redirected to your oAuth redirect url. The final redirect url you sent in your state vars is also returned.

After this, you can grab the user info, grab the final redirect url and redirect the user to that url in your site/app. 

At the final location for the content/feature, you can check for user's pledge and display the content or allow the feature if s/he fulfills the requirement

A ready-made 'Unlock with Patreon' image created per official design guidelines is included in this library's assets/images folder. You can use this image as button image in your unlock links.

*/

require_once __DIR__.'/vendor/autoload.php';
 
use Patreon\API;
use Patreon\OAuth;

$client_id = '';      // Replace with your data
$client_secret = '';  // Replace with your data

// Set the redirect url where the user will land after the flow. That url is where the access code will be sent as a _GET parameter. This may be any url in your app that you can accept and process the access code and login

// In this case, say, /patreon_login request uri. This doesnt need to be your final redirect uri. You can send your final redirect uri in your state vars to Patreon, receive it back, and then send your user to that final redirect uri

$redirect_uri = "http://mydomain.com/patreon_login"; // Replace http://mydomain.com/patreon_login with the url at your site which is going to receive users returning from Patreon confirmation

// Min cents is the amount in cents that you locked your content or feature with. Say, if a feature or content requires $5 to access in your site/app, then you send 500 as min cents variable. Patreon will ask the user to pledge $5 or more.

$min_cents = '500';

// Scopes! You must request the scopes you need to have the access token.
// In this case, we are requesting the user's identity (basic user info), user's email
// For example, if you do not request email scope while logging the user in, later you wont be able to get user's email via /identity endpoint when fetching the user details
// You can only have access to data identified with the scopes you asked. Read more at https://docs.patreon.com/#scopes

// Lets request identity of the user, and email.

$scope_parameters = '&scope=identity%20identity'.urlencode('[email]');

// Generate the unified flow url - this is different from oAuth login url. oAuth login url just processes oAuth login. 
// Unified flow will do everything.

$href = 'https://www.patreon.com/oauth2/become-patron?response_type=code&min_cents=' . $min_cents . '&client_id=' . $client_id . $scope_parameters . '&redirect_uri=' . $redirect_uri;

// You can send an array of vars to Patreon and receive them back as they are. Ie, state vars to set the user state, app state or any other info which should be sent back and forth. 

$state = array();

$state['final_redirect'] = 'http://mydomain.com/locked-content'; // Replace http://mydomain.com/locked-content with the url of the content to be unlocked at your site, or whever you want the user to be directed to after returning from Patreon login/confirmation

// Add any number of vars you need to this array by $state['key'] = variable value

// Prepare state var. It must be json_encoded, base64_encoded and url encoded to be safe in regard to any odd chars. When you receive it back, decode it in reverse of the below order - urldecode, base64_decode, json_decode (as array)

$state_parameters = '&state=' . urlencode( base64_encode( json_encode( $state ) ) );

// Append it to the url 

$href .= $state_parameters;

// Now place the url into a flow link. Below is a very simple login link with just text. in assets/images folder, there is a button image made with official Patreon assets (unlock_with_patreon.png). You can also use this image as the inner html of the <a> tag instead of the text provided here

// Simply echoing it here. You can present the login link/button in any other way.

echo '<a href="'.$href.'">Unlock with Patreon</a>';

// The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above. It will grab the auth code from Patreon and get the tokens via the oAuth client

if ( $_GET['code'] != '' ) {
	
	// From this part on, its no different from oAuth login example. Just do whatever you need.
	
	$oauth_client = new OAuth($client_id, $client_secret);	
		
	$tokens = $oauth_client->get_tokens($_GET['code'], $redirect_uri);
	
	$access_token = $tokens['access_token'];
	$refresh_token = $tokens['refresh_token'];
		
	// Here, you should save the access and refresh tokens for this user somewhere. Conceptually this is the point either you link an existing user of your app with his/her Patreon account, or, if the user is a new user, create an account for him or her in your app, log him/her in, and then link this new account with the Patreon account. More or less a social login logic applies here. 
	
	// Here you can decode the state var returned from Patreon, and use the final redirect url to redirect your user to the relevant unlocked content or feature in your site/app.
	
}

// After linking an existing account or a new account with Patreon by saving and matching the tokens for a given user, you can then read the access token (from the database or whatever resource), and then just check if the user is logged into Patreon by using below code. Code from down below can be placed wherever in your app, it doesnt need to be in the redirect_uri at which the Patreon user ends after oAuth. You just need the $access_token for the current user and thats it.

// Lets say you read $access_token for current user via db resource, or you just acquired it through oAuth earlier like the above - create a new API client

$api_client = new API($access_token);

// Return from the API can be received in either array, object or JSON formats by setting the return format. It defaults to array if not specifically set. Specifically setting return format is not necessary. Below is shown as an example of having the return parsed as an object. If there is anyone using Art4 JSON parser lib or any other parser, they can just set the API return to JSON and then have the return parsed by that parser

// You dont need the below line if you simply want the result as an array
$api_client->api_return_format = 'object';

// Now get the current user:
$patron_response = $api_client->fetch_user();

// At this point you can do anything with the user return. For example, if there is no return for this user, then you can consider the user not logged into Patreon. Or, if there is return, then you can get the user's Patreon id or pledge info. For example if you are able to acquire user's id, then you can consider the user logged into Patreon. 

// For example, after redirecting the user to the final redirect url after the unified flow process earlier, you can check for user's membership at this point, and show/hide content or allow/disallow features depending on the result



