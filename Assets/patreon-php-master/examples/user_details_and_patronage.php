<?php

// This example shows you how to get details of the current user and other info like active membership which the user has

// We assume that you already logged the user in via Patreon to get the access token, or already logged in the user via Patreon before, and got and saved the access token, and reading it from wherever you saved it for this user

// We assume you put the access token into an $access_token var

require_once __DIR__.'/vendor/autoload.php';
 
use Patreon\API;
use Patreon\OAuth;

// Start new Patreon API client
$api_client = new API($access_token);

// Fetch the user's details
$current_member = $api_client->fetch_user();

// $current_patron now has the user details. The return should match the documentation for /identity return at https://docs.patreon.com/#get-api-oauth2-v2-identity which should be augmented by additionally requested scopes. 

// Using the default fetch_user that is in the API class and with the scopes it requests:

// Email of the user. Requires having had identity email scope during oAuth. See https://docs.patreon.com/#scopes

$email = $current_member['data']['attributes']['email'];

// This email may or may not be verified at Patreon. If you are going to do anything with it, check for is_email_verified value in the return, and only use this as account email etc if it is shown as verified.

$is_email_verified = $current_member['data']['attributes']['is_email_verified'];

// The avatar image url for the user

$image_url = $current_member['data']['attributes']['image_url'];

// The first name of the user

$first_name = $current_member['data']['attributes']['first_name'];

// The first name of the user

$last_name = $current_member['data']['attributes']['last_name'];

// The full name of the user

$full_name = $current_member['data']['attributes']['full_name'];

// The user's vanity name at Patreon

$vanity = $current_member['data']['attributes']['vanity'];

// The current active patronage/membership of the user

$currently_entitled_amount_cents = $current_member['included'][0]['attributes']['currently_entitled_amount_cents'];

// The lifetime pledge amount of the user - the relevant campaign return should default to the campaign attached to the app credential you created - ie that account's campaign

$lifetime_support_cents = $current_member['included'][0]['attributes']['lifetime_support_cents'];

// Last charge status of the user, paid, declined etc

$last_charge_status = $current_member['included'][0]['attributes']['last_charge_status'];

// Last charge date - the date which the above status was acquired

$last_charge_date = $current_member['included'][0]['attributes']['last_charge_date'];

// Membership start date of the user. If the user was a member for some time, then canceled, and then re-subscribed, this would update to the latest membership start date

$pledge_relationship_start = $current_member['included'][0]['attributes']['pledge_relationship_start'];





