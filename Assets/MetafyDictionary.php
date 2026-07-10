<?php

require_once __DIR__ . '/AllAltArtVariations.php';
enum MetafyCommunity : string
{
  case Talishar = "be5e01c0-02d1-4080-b601-c056d69b03f6";
  case WeMakeBest = "0d3fb3b5-4cc2-4f1c-9e26-62e3c758972e";
  case FaBrary = "7d14daf6-6591-45f1-9163-8e78701423d2";
  case AggroBlaze = "54d61f4d-82f2-418a-8468-7302d56fd09b";
  case TheCardGuyz = "6d8c9a16-52af-41bc-9ae5-246ed868fcf2";
  case SunflowerSamurai = "0a44032f-2060-49e0-adae-2013f84c99c8";
  case EmperorsRome = "e0f5f68e-615f-4e52-a23b-3d5b6f9f1b8c";
  case SnapDragons = "ae238384-f748-460d-8a40-b48bf280614d";
  case FaBlazing = "005c6167-ef5c-428c-a196-d012ca300f7f";
  case FabInsight = "68794fd8-8b30-46c2-9269-de2bf8ac4157";

  public function CommunityName(): string
  {
    return match($this) {
      self::Talishar => "Talishar",
      self::WeMakeBest => "WeMakeBest",
      self::FaBrary => "FaBrary",
      self::AggroBlaze => "AggroBlaze",
      self::TheCardGuyz => "The Card Guyz",
      self::SunflowerSamurai => "Sunflower Samurai",
      self::EmperorsRome => "Emperors Rome",
      self::SnapDragons => "Snap Dragons",
      self::FaBlazing => "FaBlazing",
      self::FabInsight => "FabInsight",
    };
  }

  public function SessionName(): string
  {
    return match($this) {
      self::Talishar => "metafyTalishar",
      self::WeMakeBest => "metafyWeMakeBest",
      self::FaBrary => "metafyFaBrary",
      self::AggroBlaze => "metafyAggroBlaze",
      self::TheCardGuyz => "metafyTheCardGuyz",
      self::SunflowerSamurai => "metafySunflowerSamurai",
      self::EmperorsRome => "metafyEmperorsRome",
      self::SnapDragons => "metafySnapDragons",
      self::FaBlazing => "metafyFaBlazing",
      self::FabInsight => "metafyFabInsight",
    };
  }

  public function CardBacks(): string
  {
    return match($this) {
      self::Talishar => "1,2,3,4,5,6,7,8,82,83",
      self::WeMakeBest => "68,69",
      self::FaBrary => "21,22,57,58,59,60,61,62",
      self::AggroBlaze => "118",
      self::TheCardGuyz => "35",
      self::SunflowerSamurai => "70",
      self::EmperorsRome => "67",
      self::SnapDragons => "127",
      self::FaBlazing => "129",
      self::FabInsight => "136",
      default => "",
    };
  }

  public function Playmats(): string
  {
    return match($this) {
      self::Talishar => "16,17,18,19,20,21,37,38,39,40,41,45",
      default => "",
    };
  }

  public function AltArts(): array
  {
    return match($this) {
      self::Talishar => $this->getTalisharAltArts(),
      default => [],
    };
  }

  private function getTalisharAltArts(): array
  {
    return GetAllAltArtVariations();
  }
}
