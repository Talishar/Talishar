<?php


  function TraverseTrie(&$trie, $keySoFar, &$handler=null, $isString=true, $defaultValue="")
  {
    $default = ($defaultValue != "" ? ($isString ? "\"" . $defaultValue . "\"" : $defaultValue) : ($isString ? "\"\"" : "0"));
    $depth = strlen($keySoFar);
    if(is_array($trie))
    {
      fwrite($handler, "switch(\$cardID[" . $depth . "]) {\r\n");
      foreach ($trie as $key => $value)
      {
        fwrite($handler, "case \"" . $key . "\":\r\n");
        TraverseTrie($trie[$key], $keySoFar . $key, $handler, $isString, $defaultValue);
      }
      fwrite($handler, "default: return " . $default . ";\r\n");
      fwrite($handler, "}\r\n");
    }
    else
    {
      if($handler != null)
      {
        if($isString) fwrite($handler, "return \"" . $trie . "\";\r\n");
        else fwrite($handler, "return " . $trie . ";\r\n");
      }
    }
  }

  function AddToTrie(&$trie, $cardID, $depth, $value)
  {
    if($depth < strlen($cardID)-1)
    {
      if(!array_key_exists($cardID[$depth], $trie)) $trie[$cardID[$depth]] = [];
      AddToTrie($trie[$cardID[$depth]], $cardID, $depth+1, $value);
    }
    else if(!isset($trie[$cardID[$depth]])) $trie[$cardID[$depth]] = $value;
  }

?>
