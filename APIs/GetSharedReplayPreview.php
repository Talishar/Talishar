<?php

include "../Libraries/HTTPLibraries.php";
include_once "../Libraries/ReplayLibraries.php";
include_once "../Libraries/FormatCodes.php";

SetHeaders();
header("Content-Type: application/json");

$metadata = SharedReplayMetadata((string)($_GET["token"] ?? ""));
if ($metadata === null || ($metadata["type"] ?? "") === "snapshot") {
  ExitJsonResponse(["error" => "Shared replay not found."], 404);
}

$format = preg_replace('/^shadow/', '', trim((string)($metadata["format"] ?? "")));

header("Cache-Control: public, max-age=300");
WriteJsonResponse([
  "p1HeroCardId" => trim((string)($metadata["p1HeroCardId"] ?? "")),
  "p2HeroCardId" => trim((string)($metadata["p2HeroCardId"] ?? "")),
  "p1HeroName" => trim((string)($metadata["p1HeroName"] ?? "")),
  "p2HeroName" => trim((string)($metadata["p2HeroName"] ?? "")),
  "format" => $format === "" ? "" : FormatDisplayName($format),
]);
