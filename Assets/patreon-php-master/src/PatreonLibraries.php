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

  //Talishar 7198186
  //Push the Point 7579026

  $loginSuccessful = false;
  if($patron_response->included == null) return;
  $yourPatronages = [];
	for($i=0; $i<count($patron_response->included); ++$i)
	{
		$include = $patron_response->included[$i];
		if($include->type == "campaign" && $include->id == "7198186")
		{
			$_SESSION["isPatron"] = true;
			array_push($yourPatronages, "Talishar");
		}
    if($include->type == "campaign" && $include->id == "7579026")
    {
    	$_SESSION["isPtPPatron"] = true;
    	array_push($yourPatronages, "Push the Point");
    }
    if ($include->type == "campaign" && $include->id == "7329070") {
      $_SESSION["isGoAgainGamingPatron"] = true;
      array_push($yourPatronages, "Go Again Gaming");
    }
    if ($include->type == "campaign" && $include->id == "1787491") {
      $_SESSION["isRedZoneRoguePatron"] = true;
      array_push($yourPatronages, "Red Zone Rogue");
    }
    if ($include->type == "campaign" && $include->id == "8997252") {
      $_SESSION["isFabraryPatron"] = true;
      array_push($yourPatronages, "Fabrary");
    }
    if ($include->type == "campaign" && $include->id == "8955846") {
      $_SESSION["isManSantPatron"] = true;
      array_push($yourPatronages, "Man Sant");
    }
    if ($include->type == "campaign" && $include->id == "6839952") {
      $_SESSION["isAttackActionPodcastPatreon"] = true;
      array_push($yourPatronages, "Attack Action Podcast");
    }
	}
  

  echo("<h1>Your patronages:</h1>");
  for($i=0; $i<count($yourPatronages); ++$i)
  {
    echo("<h2>" . $yourPatronages[$i] . "</h2>");
  }
  echo("<h4>Not seeing something you expect to see?</h4>");
  echo("<h4>1. Check your patreon page to make sure it's listed in your currently supported campaigns</h4>");
  echo("<h4>2. Reach out on our discord server!</h4>");

}

?>
