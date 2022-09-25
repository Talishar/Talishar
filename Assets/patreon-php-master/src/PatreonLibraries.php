<?php

  use Patreon\API;
  use Patreon\OAuth;

function PatreonLogin($access_token)
{
  if($access_token == "") return;
  if($access_token == "PERMANENT")
  {
    $_SESSION["isPatron"] = true;
    return;
  }

  $api_client = new API($access_token);
	$api_client->api_return_format = 'object';

	$patron_response = $api_client->fetch_user();
	$patron = $patron_response->data;

	$relationships = $patron->relationships;
	if(isset($relationships)) $memberships = $relationships->memberships;

  $loginSuccessful = false;
	for($i=0; $i<count($patron_response->included); ++$i)
	{
		$include = $patron_response->included[$i];
		if($include->type == "campaign" && $include->id == "7198186")
		{
			$_SESSION["isPatron"] = true;
			$loginSuccessful = true;
		}
	}

	if($loginSuccessful) echo("Patreon login successful!<BR>");
	else echo("There was an issue logging in to patreon. Check if you're still a member and if so ask on discord for assistance.");

}

?>
