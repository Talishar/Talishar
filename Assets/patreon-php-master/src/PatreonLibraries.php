<?php

  use Patreon\API;
  use Patreon\OAuth;

function PatreonLogin($access_token, $silent=true, $debugMode=false)
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

  if(is_string($patron_response))
  {
    if(!$silent) echo($patron_response);
    return;
  }

	$patron = $patron_response->data;

	$relationships = $patron->relationships;
	if(isset($relationships)) $memberships = $relationships->memberships;

  if(!isset($patron_response->included)) return;
  $yourPatronages = [];
	for($i=0; $i<count($patron_response->included); ++$i)
	{
    $_SESSION["patreonAuthenticated"] = true;
		$include = $patron_response->included[$i];
    if($debugMode)
    {
      echo($include->id . " ");
      if($include->attributes && isset($include->attributes->patron_status)) echo($include->attributes->patron_status . " " . $include->relationships->campaign->data->id);
      else if(isset($include->attributes->patron_status)) echo($include->attributes->creation_name);
      echo("<BR>");
    }
    //TODO: filter out inactive patrons
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
    if($include->type == "campaign" && $include->id == "1919413")
    {
      $_SESSION["isDMArmadaPatron"] = true;
      array_push($yourPatronages, "DM Armada");
    }
    if ($include->type == "campaign" && $include->id == "8306478") {
      $_SESSION["isInstantSpeedPatron"] = true;
      array_push($yourPatronages, "Instant Speed Podcast");
    }
    if ($include->type == "campaign" && $include->id == "7733166") {
      $_SESSION["isTheCardGuyzPatron"] = true;
      array_push($yourPatronages, "The Card Guyz");
    }
    if ($include->type == "campaign" && $include->id == "7009853") {
      $_SESSION["isHomeTownTCGPatron"] = true;
      array_push($yourPatronages, "HomeTownTCG");
    }
    if ($include->type == "campaign" && $include->id == "8338817") {
      $_SESSION["isFleshAndPodPatron"] = true;
      array_push($yourPatronages, "Flesh And Pod");
    }
    if ($include->type == "campaign" && $include->id == "9361474") {
      $_SESSION["isKappoloPatron"] = true;
      array_push($yourPatronages, "Kappolo");
    }
    if ($include->type == "campaign" && $include->id == "3828539") {
      $_SESSION["isLibrariansOfSolanaPatron"] = true;
      array_push($yourPatronages, "Librarians of Solana");
    }
    if ($include->type == "campaign" && $include->id == "8951973") {
      $_SESSION["isTheMetrixMetagamePatron"] = true;
      array_push($yourPatronages, "The Metrix Metagame");
    }
    if ($include->type == "campaign" && $include->id == "9370276") {
      $_SESSION["isTheTablePitPatron"] = true;
      array_push($yourPatronages, "The Table Pit");
    }
    if ($include->type == "campaign" && $include->id == "9404423") {
      $_SESSION["isTCGTedPatron"] = true;
      array_push($yourPatronages, "TCG Ted");
    }
	}

  if(!$silent)
  {
    echo("<h1>Your patronages:</h1>");
    for($i=0; $i<count($yourPatronages); ++$i)
    {
      echo("<h2>" . $yourPatronages[$i] . "</h2>");
    }
    echo("<h4>Not seeing something you expect to see?</h4>");
    echo("<h4>1. Check your patreon page to make sure it's listed in your currently supported campaigns</h4>");
    echo("<h4>2. Reach out on our discord server!</h4>");
  }

}

?>
