<?php

include_once '../includes/dbh.inc.php';
include_once "CardEditorDatabase.php";
include_once '../GeneratedCode/DatabaseGeneratedCardDictionaries.php';

$sets = ["WTR", "ARC", "CRU", "MON", "ELE", "EVR", "UPR", "DYN", "OUT", "DVR", "RVD", "DTD", "LGS", "HER", "FAB", "TCC", "EVO", "HVY", "MST", "AKO", "ASB", "ROS", "AAZ", "TER", "AUR", "AIO"];

foreach($sets as &$set) {
    for($i=0; $i<800; ++$i) {
      $cardID = $set;
      if($i<100) $cardID .= "0";
      if($i<10) $cardID .= "0";
      $cardID .= $i;
      if(GeneratedGoAgain($cardID)) CreateEditCard($cardID, 1);
    }
}

?>
