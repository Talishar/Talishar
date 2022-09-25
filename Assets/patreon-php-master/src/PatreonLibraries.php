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

	for($i=0; $i<count($patron_response->included); ++$i)
	{
		$include = $patron_response->included[$i];
		if($include->type == "campaign" && $include->id == "7198186")
		{
			echo("Patreon login successful!<BR>");
			$_SESSION["isPatron"] = true;
		}
	}

}

?>
