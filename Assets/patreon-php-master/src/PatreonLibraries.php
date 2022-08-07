<?php

  use Patreon\API;
  use Patreon\OAuth;

function PatreonLogin($access_token)
{
  if($access_token == "") return;
	$api_client = new API($access_token);
	$api_client->api_return_format = 'object';

	// Now get the current user:
	$patron_response = $api_client->fetch_user();
	$patron = $patron_response->data;
	$relationships = $patron->relationships;
	if(isset($relationships)) $memberships = $relationships->memberships;
	if(isset($memberships)) $_SESSION["isPatron"] = true;
}

?>
