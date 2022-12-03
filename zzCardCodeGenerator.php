<?php

  $jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/json/json/card.json";
  $curl = curl_init();
  $headers = array(
    "Content-Type: application/json",
  );
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  curl_setopt($curl, CURLOPT_URL, $jsonUrl);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $cardData = curl_exec($curl);
  curl_close($curl);

  $cardArray = json_decode($cardData);
  for($i=0; $i<count($cardArray); ++$i)
  {
    echo($cardArray[$i]->name . "<BR>");
  }

?>
