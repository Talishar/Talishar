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
