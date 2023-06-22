<?php

	include_once "../Libraries/Trie.php";
	include_once '../includes/dbh.inc.php';
	include_once "CardEditorDatabase.php";


	if(!is_dir("../GeneratedCode")) mkdir("../GeneratedCode", 777, true);
	$filename = "../GeneratedCode/DatabaseGeneratedCardDictionaries.php";
	$handler = fopen($filename, "w");
	fwrite($handler, "<?php\r\n");

	$cards = LoadDatabaseCards();
	GenerateFunction($cards, $handler, "GoAgain", "goagain", "false");
	foreach($cards as $card) {
	    echo $card->cardID . ", " . ($card->hasGoAgain ? "has go again" : "no go again") . "<br>";
	}

	fwrite($handler, "?>");
	fclose($handler);



	  function GenerateFunction(&$cardArray, $handler, $functionName, $propertyName, $defaultValue="", $sparse=false)
	  {
	    echo("<BR>" . $functionName . "<BR>");
	    fwrite($handler, "function Generated" . $functionName . "(\$cardID) {\r\n");
	    $isString = true;
	    if($propertyName == "goagain") $isString = false;
			$isBoolean = false;
			if($propertyName == "goagain") $isBoolean = true;
	    fwrite($handler, "if(strlen(\$cardID) < 6) return " . ($isString ? "\"\"" : "0") . ";\r\n");
	    fwrite($handler, "if(is_int(\$cardID)) return " . ($isString ? "\"\"" : "0") . ";\r\n");
	    if($sparse) fwrite($handler, "switch(\$cardID) {\r\n");
	    $trie = [];
	    for($i=0; $i<count($cardArray); ++$i)
	    {
					$cardID = $cardArray[$i]->cardID;
	        if($propertyName == "goagain") $data = $cardArray[$i]->hasGoAgain ? "true" : "false";
	        if(!$isBoolean && ($isString == false && !is_numeric($data) && $data != "") || $data == "-" || $data == "*" || $data == "X") echo("Exception with property name " . $propertyName . " data " . $data . " card " . $cardID . "<BR>");
	        if($data != "-" && $data != "" && $data != "*" && $data != $defaultValue)
	        {
	          if($sparse) fwrite($handler, "case \"" . $cardID . "\": return " . ($isString ? "\"$data\"" : $data) . ";\r\n");
	          else AddToTrie($trie, $cardID, 0, $data);
	        }
	    }
	    if($sparse) fwrite($handler, "default: return " . ($isString ? "\"$defaultValue\"" : $defaultValue) . ";}\r\n");
	    else TraverseTrie($trie, "", $handler, $isString, $defaultValue);

	    fwrite($handler, "}\r\n\r\n");
	  }

?>
