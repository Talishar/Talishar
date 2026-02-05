# New Developer Guide

This is intended to be a non-comprehensive, living document that goes over how to code new cards.
This guide is *not* intended to document how to implement cards with novel effects or cards that require updates to the game engine. These kinds of effects tend to be a lot harder to implement and frequently require some creativity and deep rules knowledge that can't exactly be documented.

## Old vs New Style
In the past, most cards were added to the game engine in the form of long switch statements where the effects of every card were coded into the engine itself. This may have been easier to code when the cardpool was smaller, but as things have grown its led to logic for cards being scattered all over the repo.
To address this, starting with Super Slam we've started putting all card logic into Classes/CardObjects/{SET}Cards.php. In theory almost all aspects of cards should be able to be put in a single Card object so you can see everything that a card does in one place. The one thing that often doesn't fit in this paradigm are rules-setting cards, as these effects alter how the game-engine functions, and so need to be added to the game engine itself.
This transition to Card objects is still a work in progress, and sometimes there are effects that haven't been converted to a form that an object can immediately use, in which case you can either use the old approach and add the effect to a switch statement in the engine, or (preferably) add a new method to the base class (Classes/Card.php) and call that function right above the switch statement in the engine. This function call in engine typically looks like this:
```
$card = GetClass($card, $player);
if ($card != "-") $card->Method();
```
The "GetClass" function checks if the card has an object associated with it, so that way we don't try to create an object that doesn't exist.
### Card object methods
Here are some common methods frequently used and how they correspond to FaB rules:
`__construct`
All card objects need a construct function. Most of the time this just sets the `$cardID` (which should be the same as the object's name) and `$controller` variables, but sometimes can declare a `$baseCard` or `$archetype` to allow the card to access methods of related cards.
`$baseCard` is frequently used to store methods used by all 3 members of a color cycle, while `$archetype` is for methods used by a wide range of cards with something in common (see `windup` from HVYCards.php).
`PlayAbility`  
This method handle any resolution abilities of a card, which is anything that happens when an attack becomes attacking in the attack step, or when a non-attack resolves.  
`IsPlayRestricted`  
This method returns false when a card does not meet a condition for being played. This is only for a card's own restrictions, not for restrictions imposed by other effects. Frequently it is used to check if there is a legal target and if additional costs can be paid.  
`PayAdditionalCosts`
This method handles everything that happens as a card is put onto the stack, primarily including targeting and paying additional costs.  
`CombatEffectActive`  
Many cards create what are called in the rules "Layer Continuous Effects" that alter the power of attacks on the combat chain or apply other effects. This function returns true if an effect should apply to an attack, and false otherwise. This is *technically* incorrect for effects that specify "the next X you play gets Y" as those effects only check whether they should apply when the card is played and are not re-evaluated, but in most cases this isn't relevant. If it becomes relevant, you can add the effect to `IsLayerContinuousBuff`.  
`EffectPowerModifier`  
Returns how much power an attack should gain from a layer continuous effect if the combat effect is active.
`IsCombatEffectPersistent`
If you don't override this method, it will return false, which will cause the layer continuous effect to disappear after a chain link where CombatEffectActive was true.  
## Decision Queue
A large chunk of Talishar's logic is handled by a system called the DecisionQueue which works by setting up a sequence of operations that will execute as soon as they are able to pending user input. They are used any time a player needs to make a decision, such as targeting.  
The decision queue has 4 primary arguments:
1. the command to be executed
2. the player who will be making any decisions
3. a parameter to be passed as an argument to the command. This can either be pre-set before the decision queue begins executing, or dynamic with a few special characters.
4. subsequent, a 0/1 bit where if set will cause the decision queue to be skipped if a previous decision queue failed/was declined.  
What ties decision queues together is the `$lastResult` variable, which will always store whatever the previous decision returned.  
For example, if I run `AddDecisionQueue("FINDINDICES", $this->controller, "ARSENAL");` this will return indices that point out all cards in a players arsenal. If I follow this up with `AddDecisionQueue("MAYCHOOSEARSENAL", $this->controller, "<-", 1);`, the player is given the optional ability to choose a card from the list returned . the "<-" parameter is a special parameter that is replaced by the value of `$lastResult`. If we follow this with `AddDecisionQueue("REMOVEARSENAL", $this->controller, "-", 1);`, the choice from the last decisionqueue is stored as the $lastResult, and gets passed to the REMOVEARSENAL command which then removes the chosen arsenalled card. Because this command has `$subsequent` set to 1, if the player declined the previous MAYCHOOSEARSENAL, this decision queue (and all subsequent queues) will be skipped.