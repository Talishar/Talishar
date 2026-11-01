<?php

// Reasons and notes players leave for moderators when blocking or reporting someone.

const MODERATION_REASONS = ['rude', 'insulting', 'unsporting', 'other'];

function NormalizeModerationReason($reason)
{
  return in_array($reason, MODERATION_REASONS, true) ? $reason : null;
}

function NormalizeModerationNote($note, $maxLength)
{
  $note = is_string($note) ? trim(mb_substr($note, 0, $maxLength)) : "";
  return $note === "" ? null : $note;
}


// The last chat lines of a game, oldest first, as [playerID, name, text]. playerID 0 is a moderator.
function ReadRecentChat($gameName, $maxLines)
{
  $log = @file(__DIR__ . "/../Games/" . $gameName . "/gamelog.txt", FILE_IGNORE_NEW_LINES);
  if ($log === false) return [];
  $chat = [];
  for ($i = count($log) - 1; $i >= 0 && count($chat) < $maxLines; --$i) {
    if (!preg_match("/^<span style='font-weight:bold; color:([^;]*);'>(.*?): <\/span>(.*)$/s", $log[$i], $matches)) continue;
    $playerID = $matches[1] === "<PLAYER1COLOR>" ? 1 : ($matches[1] === "<PLAYER2COLOR>" ? 2 : 0);
    $chat[] = [
      'playerID' => $playerID,
      'name' => html_entity_decode(strip_tags($matches[2]), ENT_QUOTES),
      'text' => mb_substr(html_entity_decode($matches[3], ENT_QUOTES), 0, 300),
    ];
  }
  return array_reverse($chat);
}
