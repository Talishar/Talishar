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
