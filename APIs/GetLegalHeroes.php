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
// ?by=setId lists set IDs (as stored for favorite decks and lobby heroes) instead of names.
$bySetId = ($_GET['by'] ?? '') === 'setId';
$heroesByFormat = [];
foreach ($formats as $format) {
  $heroes = GetLegalHeroes($format);
  $heroesByFormat[$format] = $bySetId
    ? array_values(array_unique(array_map(fn($hero) => SetID($hero['heroId']), $heroes)))
    : array_values(array_unique(array_column($heroes, 'name')));
}

echo json_encode($heroesByFormat);
