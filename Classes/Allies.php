<?php

class Allies {

  private array $allies = [];
  private int $player = 0;

  public function __construct(int $player) {
    $this->allies = &GetAllies($player);
    $this->player = $player;
  }

  public function Card(int $index, $cardNumber = false): AllyCard {
    if ($cardNumber) $index *= AllyPieces();
    return new AllyCard($index, $this->player);
  }

  public function FindCardUID($uid): AllyCard {
    $count = count($this->allies);
    if ($count === 0) return new AllyCard(-1, $this->player);
    $allyPieces = AllyPieces();
    $allies = $this->allies;
    for ($i = 0; $i < $count; $i += $allyPieces) {
      if ($allies[$i + 5] == $uid) return new AllyCard($i, $this->player);
    }
    return new AllyCard(-1, $this->player);
  }

  public function NumAllies(): int {
    return intdiv(count($this->allies), AllyPieces());
  }
}

class AllyCard {

  private array $pieces = [];
  private int $index;
  private int $controller;

  public function __construct(int $index, int $player) {
    if ($index !== -1)
      $this->pieces = &GetAllies($player);
    else
      $this->pieces = [];
    $this->index = $index;
    $this->controller = $player;
  }

  public function Index(): int {
    return $this->index;
  }

  public function CardID(): string {
    return $this->pieces[$this->index] ?? "-";
  }

  public function Status(): int {
    return $this->pieces[$this->index + 1] ?? 0;
  }

  public function SetStatus(int $status): void {
    if (isset($this->pieces[$this->index + 1])) $this->pieces[$this->index + 1] = $status;
  }

  public function Life(): int {
    return $this->pieces[$this->index + 2] ?? 0;
  }

  public function Damage(int $damage, string $type = "DAMAGE"): int {
    if (isset($this->pieces[$this->index + 2]))
      return DamageAlly($this->controller, $this->index, $damage, $type);
    return 0;
  }

  public function Frozen(): int {
    return $this->pieces[$this->index + 3] ?? 0;
  }

  public function Subcards(): string {
    return $this->pieces[$this->index + 4] ?? "-";
  }

  public function UniqueID(): string {
    return $this->pieces[$this->index + 5] ?? "-";
  }

  public function EnduranceCounters(): int {
    return $this->pieces[$this->index + 6] ?? 0;
  }

  public function LifeCounters(): int {
    return $this->pieces[$this->index + 7] ?? 0;
  }

  public function AddLifeCounters($n) {
    if (isset($this->pieces[$this->index + 7])) {
      if ($this->Life() + $n <= 0)
        $this->Destroy();
      else
        $this->pieces[$this->index + 7] += $n;
    }
  }

  public function NumUses(): int {
    return $this->pieces[$this->index + 8] ?? 0;
  }

  public function AddUses(int $n = 1): void {
    if (isset($this->pieces[$this->index + 8]))
      $this->pieces[$this->index + 8] += $n;
  }

  public function PowerCounters(): int {
    return $this->pieces[$this->index + 9] ?? 0;
  }

  public function AddPowerCounters(int $num = 1): void {
    if (isset($this->pieces[$this->index + 9]))
      $this->pieces[$this->index + 9] += $num;
  }

  public function DamageDealtToOpponent(): int {
    return $this->pieces[$this->index + 10] ?? 0;
  }

  public function Tapped(): int {
    return $this->pieces[$this->index + 11] ?? 0;
  }

  public function Tap(int $tapState = 1): void {
    if (isset($this->pieces[$this->index + 11]))
      $this->pieces[$this->index + 11] = $tapState;
  }

  public function SteamCounters(): int {
    return $this->pieces[$this->index + 12] ?? 0;
  }

  public function From(): string {
    return $this->pieces[$this->index + 13] ?? "-";
  }

  public function Modifier(): string {
    return $this->pieces[$this->index + 14] ?? "-";
  }

  public function Destroy(bool $skipDestroy = false, bool $fromCombat = false, string $uniqueID = "", bool $toBanished = false): void {
    DestroyAlly($this->controller, $this->index, $skipDestroy, $fromCombat, $uniqueID, $toBanished);
  }
}
