# patreon-php

Interact with the Patreon API via OAuth.

## Important notice about updating to 1.0.0 from earlier versions

Patreon PHP library version 1.0.0 moves on to Patreon's v2 API, which is not compatible with old v1 calls. It also removes Art4 JSON library. Therefore directly upgrading from older versions to 1.0.0 would break compatibility of your installation. APIv1 will deprecated some time soon after APIv2 come out of beta, so its important to get your integration compatible with API v2. With API v2, you can only get access to the scopes you requested during authorization, and you need to ask for the data you want through includes. 

If you were using Art4 library before, you can separately install it and feed the return from API calls to Art4 library after getting it as JSON from this library. This will make your existing code that uses Art4 library compatible. However note that you will need to track Art4 library updates and resulting compatibility issues yourself. 

https://docs.patreon.com/#apiv2-oauth

If you have any questions or issues, please visit our developer forum at https://www.patreondevelopers.com/

NOTE: This library is made to be compatible with the maximum possible variety of different infrastructures, PHP versions and environments in order to cover a majority of potential integrations. If you would like to use more specialized versions of this lib with different amenities and additions/dependencies to better suit your environment, below is a list. If you would like your fork added to this list, please contact us [at the forums](https://www.patreondevelopers.com/).

[soatok's fork](https://github.com/soatok/patreon-php) with PHP 7.x, PHPunit, Psalm, BLAKE2b instead of MD5

## Installation

Currently the repo at packagist is at 0.3.x and API v1 only, whereas the one at Github is at 1.0.0 and API v2 due to compatibility reasons for older installations which were done via composer. Until we sync the packagist version with the Github version, please use this repo by cloning it from Github, or downloading and uploading it to your environment via file transfer.

## Usage

### Step 1. Get your client_id and client_secret

Visit the [Patreon platform documentation page](https://www.patreon.com/platform/documentation) while logged in as a Patreon creator to register your client.

This will provide you with a `client_id` and a `client_secret`.

### Step 2. Use this plugin in your code

Let's say you wanted to make a "Log In with Patreon" button.

You've read through [the directions](https://www.patreon.com/platform/documentation/oauth), and are trying to implement "Step 2: Handling the OAuth Redirect" with your server.

The user will be arriving at one of your pages *after* you have sent them to [the authorize page] (www.patreon.com/oauth2/authorize) for step 1, so in their query parameters landing on this page, they will have a parameter `'code'`.

_(If you are doing something other than the "Log In with Patreon" flow, please see [the examples folder](examples) for more examples)_

_(Especially the unified flow is a great way to have users unlock locked features or content at your site or app - it allows users to register, login, pledge and return to your app in one smooth unified flow. Check it out in [the examples folder](examples) )_


```php
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

$state = array();
 
// For example lets set final page which the user needs to land at - this may be a content the user is unlocking via oauth, or a welcome/thank you page

// Lets make it a thank you page

$state['final_page'] = 'http://mydomain.com/thank_you'; // Replace http://mydomain.com/thank_you with the url that has your thank you page

// Add any number of vars you need to this array by $state['YOURKEY'] = VARIABLE

// Prepare state var. It must be json_encoded, base64_encoded and url encoded to be safe in regard to any odd chars
$state_parameters = '&state=' . urlencode( base64_encode( json_encode( $state ) ) );

// Append it to the url

$href .= $state_parameters;

// Now place the url into a login link. Below is a very simple login link with just text. in assets/images folder, there is a button image made with official Patreon assets (login_with_patreon.png). You can also use this image as the inner html of the <a> tag instead of the text provided here

// Scopes! You must request the scopes you need to have the access token.
// In this case, we are requesting the user's identity (basic user info), user's email
// For example, if you do not request email scope while logging the user in, later you wont be able to get user's email via /identity endpoint when fetching the user details
// You can only have access to data identified with the scopes you asked. Read more at https://docs.patreon.com/#scopes

// Lets request identity of the user, and email.

$scope_parameters = '&scope=identity%20identity'.urlencode('[email]');

$href .= $scope_parameters;

// Simply echoing it here. You can present the login link/button in any other way.

echo '<a href="'.$href.'">Click here to login via Patreon</a>';

// Up to this part we handled the way to prepare a login link for users to log in via Patreon oAuth using API v2. From this point on starts the processing of a logged in user or user returning from Patreon oAuth.

// The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above. It will grab the auth code from Patreon and get the tokens via the oAuth client

if ( $_GET['code'] != '' ) {
	
	$oauth_client = new OAuth($client_id, $client_secret);	

	$tokens = $oauth_client->get_tokens($_GET['code'], $redirect_uri);
	
	$access_token = $tokens['access_token'];
	$refresh_token = $tokens['refresh_token'];
	
	// Here, you should save the access and refresh tokens for this user somewhere. Conceptually this is the point either you link an existing user of your app with his/her Patreon account, or, if the user is a new user, create an account for him or her in your app, log him or her in, and then link this new account with the Patreon account. More or less a social login logic applies here. 
	
	// Only use user's email address info coming from Patreon if the email is verified. Check for is_email_verified value in user's API return.
	
}

// After linking an existing account or a new account with Patreon by saving and matching the tokens for a given user, you can then read the access token (from the database or whatever resource), and then just check if the user is logged into Patreon by using below code. Code from down below can be placed wherever in your app, it doesnt need to be in the redirect_uri at which the Patreon user ends after oAuth. You just need the $access_token for the current user and thats it.

// Lets say you read $access_token for current user via db resource, or you just acquired it through oAuth earlier like the above - create a new API client

$api_client = new API($access_token);

// Return from the API can be received in either array, object or JSON formats by setting the return format. It defaults to array if not specifically set. Specifically setting return format is not necessary. Below is shown as an example of having the return parsed as an object. Default is array (associated) and there is no need to specifically set it if you are going to use it as an array. If there is anyone using Art4 JSON parser lib or any other parser, they can just set the API return to json and then have the return parsed by that parser

// You dont need the below line if you are going to use the return as array. 
$api_client->api_return_format = 'object';

// Now get the current user:

$patron_response = $api_client->fetch_user();

// At this point you can do anything with the user return. For example, if there is no return for this user, then you can consider the user not logged into Patreon. Or, if there is return, then you can get the user's Patreon id or pledge info. For example if you are able to acquire user's id, then you can consider the user logged into Patreon. 

```
