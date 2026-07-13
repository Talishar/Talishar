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
    'outcome' => 'label'
  ];

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
