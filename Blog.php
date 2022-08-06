<?php
include_once 'Header.php';
?>

<head>
  <meta charset="utf-8">
  <title>Flesh and Blood Online</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/blog.css">
  <link rel="shortcut icon" type="image/png" href="./Images/favicon3.png" />
</head>

<div class="grid-even-collum">
  <div class="articles">
    <h2>6th August 2022</h2>
    <h3>Happy Saturday #fabtcg fam!<br>
      Today we are bringing you styles and 6 tweets of fixes!</h3>
    <p><br>- Our setting menu has received a new style
      <br>- Fix and add a new scrollbars style
      <br>- Lots of improvements to the dynamic UI Beta
      <br>- Fix Oasis Respite. e.g., It can now properly block 4 from Rosetta
      <br>- Remove the rare case where an attack would have negative power. So it shows 0 instead of -1
      <br>- Add "Mechanologist" restriction to Induction Chambers & Plasma Purifier (Important for Erase Face)
      <br>- Fix Induction chamber Attack Reaction has been playable without a valid target.
      <br>- Plow Through now correctly apply its buff once per chain link
      <br>- We made Estrike's additional cost mandatory. It was possible to skip it previously
      <br>- Added more contrast to the blood dept icons
      <br>- Fix Meganetic shockwave, not enforcing the equipment block
      <br>- Added an indicator when a block with equipment is required (e.g., T-bone)
      <br>- Fixed Zephyr Needle breaking on block, instead of on chain link resolution
      <br>- Fixed Popups not working for the spectator (e.g., looking in a discard or opening the menu)
      <br>- Refraction Bolters now correctly create a trigger layer (More equipment triggers to come)
      <br>- Teklo Core now correctly create a trigger layer (More item triggers to come)
      <br>- Priority gems have been added to more items
      <br>- Priority gems position have been fixed
      <br>- Rhinar intimidate now has a priority trigger
      <br>- Destroying allies and auras on the stack now properly cancels their attack, so they never resolve
      <br>- Fix attack target error when the target gets destroyed, but the chain link is still open
    </p>
    <div class="articles">
      <h2>4th August 2022</h2>
      <h3>Good evening #fabtcg!<br>Hard work pays off! Here's a small summary:</h3>
      <p><br>- New favorite checkbox with a small animation. You need to be logged in with the Remember me feature checked to see it.
        <br>- We remove the popup for cards like Channels and Burn Them All when there aren't enough cards in the graveyard or pitch zone. So its destroyed automatically without a popup.
        <br>- We fixed Cromai gives 2APs sometimes.
        <br>- We fixed Yendurai losing its endurance counter when attacked with a 0 power card.
        <br>- Fix lead the charge not giving an AP when Sonata or another card with a dynamic cost.
        <br>- Added better context to Tome of Aetherwild popup.
      </p>
    </div>
    <div class="articles">
      <h2>2nd August 2022</h2>
      <h3>Good evening #fabtcg players!</h3>
      <p><br>A quick review of today's updates:
        <br>- We added a way to save your decklist. (We are currently working on a way also to remove them from your favorites. 😅)
        <br>- You can visit our new blog section. It's filled with the same updates as Twitter for now.
        <br>- We made all the gem toggles use the same icons (Which previously had two different styles)
        <br>- We added the information about the space shortcut to pass.
        <br>- Fix Arknight Ascendancy giving the wrong amount of runechants.
        <br>- Improve the auras/allies layout.
        <br>- Added a scrollbar to the "Public Games".
        <br>- Added a chatlog with Opt/Top/Bottom information. To replicate flesh and blood interaction. It informs you if your opponent put a card on the top or bottom of their deck.
        <br>- Added information about which card got banished.
    </div>
    <div class="articles">
      <h2>FaB Online Roadmap</h2>
      <h3>📝 We recently wrote a roadmap and thought it would be nice to share it with you to give you an idea of what we have planned for the long-term.
        We are overwhelmed by the recent boom that created our Twitter accounts. So many news bugs and features are coming our way. Thank you!</h3>
      <p><br>1. The top priority is fixing the major outstanding bugs/missing gameplay engine features.
        <br>2. Updating the UI to be more interesting, user-friendly, and intuitive
        <br>&emsp;- Dynamic scaling of UI to better account for a wide variety of monitor/resolution
        <br>&emsp;- Better mobile support
        <br>&emsp;- Better Settings menu
        <br>3. Streamlining the game to remove unnecessary clicks and make every click meaningful
        <br>4. Ability to save favorite decks to your profile
        <br>5. Ability to see your historical matches/winrate
        <br>6. Tournament mode (disable “undo” hotkey/make “undo” and manual tools require opponent approval)
        <br>7. Better matchmaking
        <br>&emsp;- “Quick Start” button - Join or create whatever is available
        <br>&emsp;- Karma system that gives points for finishing games and being a good sport, takes away points for ragequitting, joining lobbies and leaving, reports, etc.
        <br>&emsp;- Ladder/League/ELO
        <br>&emsp;- Automated tournaments
        <br>8. Unity Client
      </p>
    </div>
    <div class="articles">
      <h2>1st August 2022</h2>
      <h3>PSA for all players. We made a big update this morning with some visual changes. You will need to clear your browser cache to see all the changes.</h3>
      <p><br>- New combat chain animation.
        <br>- New pass animation.
        <br>- New button animation.
        <br>- Better error handling for deck link errors.
        <br>- New "Select" overlay.
        <br>- Rhinar intimidate when discarding from an ice card effect (e.g. Winter's bite).
        <br>- Fix Rouse the Ancients showing "null" card and a popup when your hand is empty.
        <br>- Phantasmal Footsteps now properly get destroyed when blocking an attack that got buffed.
        <br>- Properly show the attack target when the target isn't a hero.
        <br>- We now group multiple effects from the same card (Fix for all cards). e.g. Spoils of War give 2-3 effects. It created confusion where people thought you had played a card twice.
        <br><br>Thank you all! ❤️
      </p>
    </div>
    <div class="articles">
      <h2>31st July 2022</h2>
      <h3>Good evening 🥳 It's update o'clock. Later today, those changes will be live:</h3>
      <p><br>- We added a new colour accessibility setting in the menu.
        <br>- We uploaded the alt art version of some of the cards.
        <br>- Hypothermia should be fixed.
        <br>- Find Center can now be defended by pieces of equipment,
        <br>- We change how cards like Zealous Belting/ Barraging Big Horn show Go Again's gain to be more dynamic and clearer.
        <br>- We fixed some spectator bugs. Effects showing the wrong player and both players being the same colour.
        <br>- Fix Unified Decree "May" ability with newer code.
        <br>- Fixed arsenal face-up cards not showing for player 1 when spectating.
        <br>- Improved clarity of the possibility to pass for cards that "sink." e.g. Crown of Providence, Sink below, etc.
        <br>- Improved clarity when choosing a card to reload.
        <br>- Fix Mauvrions Skies rune creation amount.
      </p>
    </div>
    <div class="articles">
      <h2>30th July 2022</h2>
      <h3>📣There will be a short downtime today morning as we prepare for a major update that will break any games in progress when the update goes live. We will disable new game creation for a short period before uploading the update to give players time to finish.</h3>
      <p><br>- New End of Turn and Resume Turn Images.
        <br>- Improved multi-choose popup UI.
        <br>- New, Improved and animated buttons.
        <br>- New animation on combat chain links, break combat chain and pass button.
      </p>
    </div>
    <div class="articles">
      <h2>29th July 2022</h2>
      <h3>Good morning!👋</h3>
      <p><br>- Your deck list in limited is now sorted.
        <br>- The card highlighted colours are now improved, with the pitch number to better support colourblindness.
        <br>- You'll be able to respond to Seismic and Embodiment destruction.
        <br>FYI: This update and the one from the last 2 days are delayed. The new code and BIG update for the triggers will break all the games in progress, so we want to make it clean for the players. We appreciate your patience! 🙂
      </p>
    </div>
    <div class="articles">
      <h2>28th July 2022</h2>
      <h3>Hello players! We pushed another great update last night!👩‍🔧</h3>
      <p><br>- We coded lines to create layers. It's currently available for Stalagmite frostbite.
        <br>Here are the ones we are currently working on:
        <br>- Runeblood Incantation.
        <br>- Mask of the Pouncing Lynx.
        <br>- Mirraging Metamorph.
        <br>- Frostbites, Seismic and Embodiment tokens.
        <br>Anything else we are missing?
      </p>
    </div>
    <div class="articles">
      <h2>27th July 2022</h2>
      <h3>Good evening community!👋</h3>
      <p><br>- We added the phantasm layer. (Icon WIP)
        <br>- We added a gem toggle to all Spellvoid equipment to turn them on/off to create a popup only when needed.
        <br>- We fixed and improved the input of multiple channels on the board at once.
        <br>- We remove the single-player games from the games in progress.
        <br>- We fixed footsteps getting destroyed when blocking a 6+ Illusionist attack.
        <br>- Blizzard should be able to target dragon attacks again. It was caught in another fix.
        <br>Thank you for all the bug reports and feedback!🐛
      </p>
    </div>
  </div>