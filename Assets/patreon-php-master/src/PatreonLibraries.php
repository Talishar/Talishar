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

  if($patron_response->included == null) return;
  $yourPatronages = [];
	for($i=0; $i<count($patron_response->included); ++$i)
	{
    $_SESSION["patreonAuthenticated"] = true;
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
    if ($include->type == "campaign" && $include->id == "7285727") {
      $_SESSION["isArsenalPassPatreon"] = true;
      array_push($yourPatronages, "Arsenal Pass");
    }
    if ($include->type == "campaign" && $include->id == "8635931") {
      $_SESSION["isTheTekloFoundryPatreon"] = true;
      array_push($yourPatronages, "The Teklo Foundry");
    }
    if ($include->type == "campaign" && $include->id == "8736344") {
      $_SESSION["isFleshAndCommonBloodPatreon"] = true;
      array_push($yourPatronages, "Flesh and Common Blood");
    }
    if ($include->type == "campaign" && $include->id == "7593240") {
      $_SESSION["isSinOnStreamPatreon"] = true;
      array_push($yourPatronages, "Sin On Stream");
    }
    if ($include->type == "campaign" && $include->id == "8458487") {
      $_SESSION["isFreshAndBudsPatreon"] = true;
      array_push($yourPatronages, "Fresh and Buds");
    }
    if ($include->type == "campaign" && $include->id == "6996822") {
      $_SESSION["isSloopdoopPatron"] = true;
      array_push($yourPatronages, "Sloopdoop");
    }
    if($include->type == "campaign" && $include->id == "7342687")
    {
      $_SESSION["isDMArmadaPatron"] = true;
      array_push($yourPatronages, "DM Armada");
    }
    if ($include->type == "campaign" && $include->id == "8306478") {
      $_SESSION["isInstantSpeedPatron"] = true;
      array_push($yourPatronages, "Instant Speed Podcast");
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
