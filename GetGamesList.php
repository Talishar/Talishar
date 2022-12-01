//
// GetGamesList.php
//
// When a user is searching for a game they should get a list of all public games in progress to join or spectate.
// We can do filter by hero / gamemode / sort inside the client.
//
// Request: Empty GET request (with user credentials in cookie etc)
//
// API expectation:
// Return list of open game lobbies and games in Progress
// Server will filter open games by rating/karma requirements.
//
// Stretch goal / future features:
// Pagination
//
<!--
 
Response Schema:

response: {
  Blitz: [
    {
      gameName: "string",
      gameID: number
    },
    {
      gameName: "string",
      gameID: number
    },
  ],
  CC: [],
  CompCC: [],
  Other: [],
  SpectateGames: {
    Blitz: [
    {
    heroA: "string",
    heroB: "string",
    gameID: number,
    },
    ],
    CC: []
    CompCC: []
    Other: []
  }
} 

-->

<?php
// array holding allowed Origin domains
SetHeaders();
header('Content-Type: application/json; charset=utf-8');

// dummy mock response
$response = new stdClass();

// blitz game waiting for player
$openBlitzArr = array();
$openBlitzGame = (object) [
  'gameName' => "Test game description",
  'gameID' => 123456,
];
array_push($openBlitzArr, $openBlitzGame);
$response->blitz = $openBlitzArr;

// CC game for spectator
$openSpectateGames = new stdClass();
$ccGameArr = array();
$spectateCCGame = (object) [
  'heroA' => "ARC001",
  'heroB' => "UPR044",
  'gameID' => 987654,
];
array_push($ccGameArr, $spectateCCGame);
$openSpectateGames->CC = $ccGameArr;
$response->spectateGames = $openSpectateGames;

// encode and send it out
echo (json_encode($response));
exit;
