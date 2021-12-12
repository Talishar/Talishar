<?php

  function BossHealth($cardID)
  {
    switch($cardID)
    {
      case "OVRPVE001": return 60;
      case "OVRPVE002": return 90;
      case "OVRPVE003": return 90;
      default: return 40;
    }
  }

  function BossBarricade($bossID)
  {
    switch($bossID)
    {
      case "OVRPVE001": case "OVRPVE002": case "OVRPVE003": return "OVRPVE005";
      default: return "-";
    }
  }

  function BossWeapon($bossID)
  {
    switch($bossID)
    {
      case "OVRPVE001": case "OVRPVE002": case "OVRPVE003": return "OVRPVE004";
      default: return "-";
    }
  }

?>