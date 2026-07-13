<?php

/**
 * Typed boundary for the state sent to React clients.
 *
 * The engine still assembles this response from legacy globals.
 * It makes the transport contract explicit and giving new fields one place to
 * be typed. Dynamic properties are temporarily allowed for legacy fields and
 * will be migrated into this DTO incrementally.
 */
#[AllowDynamicProperties]
final class GameStateResponse implements JsonSerializable
{
  public int $lastUpdate = 0;
  public array $playerInventory = [];
  public bool $inactive = false;

  public function jsonSerialize(): array
  {
    return get_object_vars($this);
  }
}
