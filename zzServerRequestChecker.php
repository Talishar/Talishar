<?php

/**
 * Apache Status Tally Script
 * Fetches /sstat and counts occurrences of /game/ endpoints.
 */

$url = "https://legacy.talishar.net/sstat";

// 1. Initialize cURL to fetch the page
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Server Monitor Script)');

$output = curl_exec($ch);

if (curl_errno($ch)) {
    die('Error fetching status: ' . curl_error($ch));
}
curl_close($ch);

// 2. Use Regex to find paths starting with /game/
// This looks for a standard HTTP method (GET/POST/etc) followed by the path.
// It stops at a space or a '?' to group by endpoint rather than unique query strings.
$pattern = '/(?:GET|POST|PUT|DELETE)\s(\/game\/[^\s\?]+)/i';

preg_match_all($pattern, $output, $matches);

$endpoints = $matches[1] ?? [];

if (empty($endpoints)) {
    die("No /game/ requests found in the current status output.<BR>");
}

// 3. Tally the results
$tally = array_count_values($endpoints);

// 4. Sort from most frequent to least frequent
arsort($tally);

// 5. Output the results
echo "Request Tally for " . date('Y-m-d H:i:s') . "<BR>";
echo str_repeat("-", 60) . "<BR>";
printf("%-50s | %s<BR>", "Endpoint", "Count");
echo str_repeat("-", 60) . "<BR>";

foreach ($tally as $endpoint => $count) {
    printf("%-50s | %d<BR>", $endpoint, $count);
}

echo str_repeat("-", 60) . "<BR>";
echo "Total processed requests: " . array_sum($tally) . "<BR>";