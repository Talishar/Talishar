<?php

include_once '../CardDictionary.php';
include_once '../Libraries/LegalHeroesHelper.php';
include_once '../Libraries/HTTPLibraries.php';

SetHeaders();
header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
  http_response_code(204);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
  header('Allow: GET, OPTIONS');
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

$formats = [
  'cc', 'compcc', 'blitz', 'compblitz', 'futurecc', 'commoner',
  'clash', 'sealed', 'draft', 'llcc', 'openformatblitz', 'futurell',
  'brawl', 'precon', 'compllcc', 'sage', 'compsage', 'futuresage',
  'open', 'gage'
];
$heroesByFormat = [];
foreach ($formats as $format) {
  $heroesByFormat[$format] = array_values(array_unique(array_column(GetLegalHeroes($format), 'name')));
}

echo json_encode($heroesByFormat);
