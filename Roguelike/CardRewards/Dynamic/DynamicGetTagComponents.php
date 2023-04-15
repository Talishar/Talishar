<?php

function DynamicGetIgnored($tag) //Take for example, the red tag. In the red tag, the only cards that aren't ignored are cards like Blaze Headlong. So everything else is in an array that this function returns.
{
  switch($tag)
  {
    case "CrouchingTiger": return array(
        "card"
      );
    default: return array();
  }
}

function DynamicGetRequiredBy($tag) //Take for example, Ninja. This will return an array of cards that *require* a ninja attack to be in the deck (Like Ancestral Empowerment)
{
  switch($tag)
  {
    case "Red": return array("WTR130");
    case "CrouchingTiger": return array("WTR131");
    default: return array();

  }
}

function DynamicGetInitialRemoved($tag) //Gets the inital slew of cards that are removed from a certain pool due to requirements
{
  switch($tag)
  {
    case "CrouchingTiger": return array("WTR129", "WTR130");
    default: return array();
  }
}
?>
