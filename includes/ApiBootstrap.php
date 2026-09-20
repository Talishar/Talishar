<?php

// Shared CORS/redirect preamble for the /APIs endpoints. Every endpoint that talks to
// the frontend opened with this same Redirector + HTTPLibraries + SetHeaders() block
// and then short-circuited the CORS preflight.

include_once __DIR__ . '/../HostFiles/Redirector.php';
include_once __DIR__ . '/../Libraries/HTTPLibraries.php';
SetHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}
