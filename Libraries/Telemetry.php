<?php

/**
 * Privacy-preserving structured operational telemetry.
 * Events are emitted to the normal PHP error log as one JSON object per line.
 * Do not add auth keys, usernames, game IDs, card names, chat, or raw request
 * payloads here.
 */
function TelemetryEvent(string $event, array $metrics = []): void
{
  static $allowedMetrics = [
    'duration_ms' => 'int',
    'mode' => 'int',
    'player_id' => 'int',
    'expected_revision' => 'int',
    'current_revision' => 'int',
    'status' => 'int',
    'reason' => 'label',
    'outcome' => 'label',
    'sample_rate' => 'int'
  ];

  // Do not send routine successful game-loop events to the PHP error log.
  $durationMs = is_numeric($metrics['duration_ms'] ?? null) ? (int)$metrics['duration_ms'] : 0;
  $outcome = (string)($metrics['outcome'] ?? '');
  if ($event === 'state.build' && $outcome === 'ok' && $durationMs < 100) {
    return;
  } elseif ($event === 'action.completed' && $outcome === 'ok' && $durationMs < 250) {
    return;
  }

  $payload = [
    'event' => preg_replace('/[^a-z0-9._-]/', '', strtolower($event)),
    'at' => gmdate('c')
  ];
  foreach ($metrics as $name => $value) {
    if (!isset($allowedMetrics[$name])) continue;
    if ($allowedMetrics[$name] === 'int' && is_numeric($value)) {
      $payload[$name] = (int)$value;
    } elseif ($allowedMetrics[$name] === 'label' && is_scalar($value)) {
      $payload[$name] = substr(preg_replace('/[^a-z0-9._-]/i', '_', (string)$value), 0, 64);
    }
  }
  error_log('[talishar.telemetry] ' . json_encode($payload, JSON_UNESCAPED_SLASHES));
}
