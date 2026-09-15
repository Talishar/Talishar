<?php

include_once "../Libraries/ReplayLibraries.php";

const PREVIEW_FRONTEND_FALLBACK = "https://talishar.net";

function PreviewFrontendUrl(string $token): string
{
  $configured = trim((string)getenv("TALISHAR_FRONTEND_URL"));
  $base = $configured !== "" ? $configured : PREVIEW_FRONTEND_FALLBACK;
  return rtrim($base, "/") . "/replay/shared?token=" . rawurlencode($token);
}

function PreviewImageUrl(string $token): string
{
  $scheme = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") ? "https" : "http";
  $host = (string)($_SERVER["HTTP_HOST"] ?? "");
  $path = rtrim(dirname((string)($_SERVER["SCRIPT_NAME"] ?? "")), "/");
  return "$scheme://$host$path/SharedReplayOgImage.php?token=" . rawurlencode($token);
}

function PreviewEscape(string $value): string
{
  return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function PreviewRender(string $title, string $description, string $imageUrl, string $canonicalUrl, string $destination): void
{
  header("Content-Type: text/html; charset=utf-8");
  header("Cache-Control: public, max-age=300");

  $title = PreviewEscape($title);
  $description = PreviewEscape($description);
  $imageUrl = PreviewEscape($imageUrl);
  $canonicalUrl = PreviewEscape($canonicalUrl);
  $destination = PreviewEscape($destination);

  echo <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>$title</title>
    <meta name="description" content="$description" />
    <link rel="canonical" href="$canonicalUrl" />

    <meta property="og:site_name" content="Talishar" />
    <meta property="og:type" content="video.other" />
    <meta property="og:url" content="$canonicalUrl" />
    <meta property="og:title" content="$title" />
    <meta property="og:description" content="$description" />
    <meta property="og:image" content="$imageUrl" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="$description" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="$title" />
    <meta name="twitter:description" content="$description" />
    <meta name="twitter:image" content="$imageUrl" />

    <meta name="robots" content="noindex, follow" />
    <meta http-equiv="refresh" content="0; url=$destination" />
    <script>window.location.replace("$destination");</script>
  </head>
  <body style="background:#0b0b0d;color:#f0f0f3;font-family:system-ui,sans-serif;padding:2rem">
    <p>Opening the replay&hellip; <a style="color:#f0f0f3" href="$destination">Continue to Talishar</a>.</p>
  </body>
</html>
HTML;
}

$token = (string)($_GET["token"] ?? "");
$replay = SharedReplayMetadata($token);

if ($replay === null) {
  http_response_code(404);
  PreviewRender(
    "Replay not found | Talishar",
    "This shared replay link has expired or been removed.",
    PreviewImageUrl($token),
    PreviewFrontendUrl($token),
    PreviewFrontendUrl($token)
  );
  exit;
}

$title = $replay["p1DisplayName"] . " vs " . $replay["p2DisplayName"] . " | Talishar Replay";

$heroes = trim($replay["p1HeroName"]) !== "" && trim($replay["p2HeroName"]) !== ""
  ? $replay["p1HeroName"] . " vs " . $replay["p2HeroName"] . ". "
  : "";
$description = $heroes . "Watch the full game replay on Talishar, the free Flesh and Blood platform.";

PreviewRender(
  $title,
  $description,
  PreviewImageUrl($token),
  PreviewFrontendUrl($token),
  PreviewFrontendUrl($token)
);
