<?php

//Single source of truth for the game format code enum.
function FormatCodeMap()
{
  static $formatMap = [
    "cc" => 0,
    "compcc" => 1,
    "blitz" => 2,
    "compblitz" => 3,     //Currently not used
    "futurecc" => 4,
    "commoner" => 5,
    "sealed" => 6,
    "draft" => 7,
    "llcc" => 8,
    "llblitz" => 9,       //Currently not used
    "openformatblitz" => 10, //Currently not used
    "clash" => -1,
    "futurell" => 11,     //Currently not used
    "openformatllblitz" => 12, //Currently not used
    "compllcc" => 13,
    "sage" => 14,
    "compsage" => 15,
    "futuresage" => 16,
    "open" => 17,
    "gage" => 18,
    "precon" => -2,
  ];
  return $formatMap;
}

function FormatCode($format)
{
  return FormatCodeMap()[$format] ?? -1;
}

function FormatName($formatCode)
{
  static $nameMap = null;
  if ($nameMap === null) $nameMap = array_flip(FormatCodeMap());
  return $nameMap[$formatCode] ?? "-";
}

function FormatDisplayName($format)
{
  return match ($format) {
    "cc" => "Classic Constructed",
    "compcc" => "Competitive Classic Constructed",
    "blitz" => "Blitz",
    "compblitz" => "Competitive Blitz",
    "futurecc" => "Future Classic Constructed",
    "commoner" => "Commoner",
    "sealed" => "Sealed",
    "draft" => "Draft",
    "llcc" => "Living Legend",
    "llblitz" => "Living Legend Blitz",
    "openformatblitz" => "Open Blitz",
    "clash" => "Clash",
    "futurell" => "Future Living Legend",
    "openformatllblitz" => "Open Living Legend Blitz",
    "compllcc" => "Competitive Living Legend",
    "sage" => "Silver Age",
    "compsage" => "Competitive Silver Age",
    "futuresage" => "Future Silver Age",
    "open" => "Open",
    "gage" => "Golden Age",
    "precon" => "Preconstructed Deck",
    default => $format,
  };
}
