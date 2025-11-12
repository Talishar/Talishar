<p align="center">
  <img src="https://github.com/Talishar/Talishar/blob/main/Images/TalisharLogo.webp?raw=true" width="623" height="278" alt="Talishar" />
</p>

<p align="center">
  <img src="https://readme-typing-svg.herokuapp.com?font=Fira+Code&pause=1000&color=F75C7E&center=true&vCenter=true&width=435&lines=Play+Flesh+and+Blood;Browser-Based+Platform;Community+Driven" alt="Typing SVG" />
</p>

<h3 align="center">Talishar is a fan-made Flesh and Blood project, and is not associated with Legend Story Studios.</h3>

[![license](https://flat.badgen.net/github/license/talishar/talishar)](./LICENSE)
[![discord](https://flat.badgen.net/discord/online-members/JykuRkdd5S?icon=discord)](https://discord.gg/JykuRkdd5S)
[![twitter](https://flat.badgen.net/badge/twitter/@talishar_online/1DA1F2?icon=twitter)](https://twitter.com/talishar_online)
[![bluesky](https://flat.badgen.net/badge/bluesky/pvtvoid/1185FE?icon=bluesky)](https://bsky.app/profile/pvtvoid.bsky.social)
[![patreon](https://flat.badgen.net/badge/become/a%20patreon/F96854?icon=patreon)](https://linktr.ee/Talishar)

## Getting started with Talishar

This is the backend client for Talishar.net - completely separate from the frontend. In order to test Talishar locally, you will need to install both the frontend and backend projects.

### Related Repositories
- [Talishar-FE](https://github.com/Talishar/Talishar-FE) - Frontend client built with TypeScript and React
- [CardImages](https://github.com/Talishar/CardImages) - Card image processing and management

### Quick Links
- **Players**: Visit [Talishar.net](https://talishar.net/) to start playing
- **Developers**: See Docker setup instructions below
- **Contributors/Bug Reports**: Join our [Discord](https://discord.gg/ErmtqQQEFm) community
- **Documentation**: [Quickstart Guide](https://docs.google.com/document/d/1qVlTrst58iZ_6xD9PkxIgZUiSKzV-S4eTJmK32qzaP0/edit) and [Full Docs](https://docs.google.com/document/d/15zRJvMOYnwrFtf-pLW3jwpYEMaUrdnNhlhmfgyE4Rs0)

## New docker way to run Talishar.

### Prerequisites:
 - Docker
 - Talishar-FE

Easier to do on *nix OS than on MS OS.
It's important if you want to run ad-hoc scripts like zzCardCodeGenerator.php to have Talishar-FE and CardImages repositories located in the same directory as Talishar. 

The docker-compose file is set up to mount the ../Talishar-FE directory into the container.
In order to generate new cards and images, those are generated in ../CardImages directory.

First clone the repo:
```
git clone https://github.com/Talishar/Talishar.git
```
Into the directory we go:
```
cd Talishar
```
Run the script to do the setup and start the docker containers
```
bash start.sh
```

- NOTE: If you're on windows, the newline characters might mess up this script. It's only two lines so you can just run them manually in Windows Powershell or git Bash:
```
cp -n HostFiles/RedirectorTemplate.php HostFiles/Redirector.php
docker compose up -d
```

The containers are running in detached (background) mode. If you need to stop them:
```
bash stop.sh
```
or
```
docker compose down
```

### What do the scripts do?
- As noted in the [Quickstart Guide](https://docs.google.com/document/d/1qVlTrst58iZ_6xD9PkxIgZUiSKzV-S4eTJmK32qzaP0/edit) below you need a unique Redirector file setup. You can customise it, it's required, but it's not checked into the repo. So the script makes a copy of the Redirector file before launching the containers.
- The stop script just stops the containers by calling `docker compose down`
- After the first time making the Redirector file, you can bring the docker containers up and down using `docker compose up` and `docker compose down` and whatever else you're used to.

### Debugging
- Xdebug is added to the container and can be configured through the `docker/docker-php-ext-xdebug.ini` file. The xdebug port is `9003`. You can use the Xdebug helper browser extension to set breakpoints and debug the code. You can also attach to the debugger from your IDE.
- The `idekey` allows you to filter out requests to the Xdebug server. The `idekey` is set to `PHPSTORM` by default. You can change this to anything you want. Just make sure it matches the `idekey` in your IDE settings.

### PHPStorm debugging
1. Create a PHP Remote Debug configuration in PHPStorm
2. Check to Filter debug connection by IDE key and set the IDE key to PHPSTORM (or whatever you've changed this to in the xdebug configuration)
3. Open up the `...` for the Server and create a new Server
4. Set the Host to `0.0.0.0` and the port to `9003` (or whatever you've set the xdebug port to) as well as the debugger to Xdebug
5. Check the `Use path mappings` and map the root of the project to the root of the container `/var/www/html/game`
6. Now you should be able to run this configuration, and it will wait for a connection from the xdebug server in the container. You can set a breakpoint somewhere in the code and it should stop there when you make a request to the server.
- Xdebug halts execution and can cause issues in the files where we have infinite loops. You can skip these files in PHPStorm by going to `Settings -> Languages & Frameworks -> PHP -> Debug -> Skipped Paths` and adding the paths to the files you want to skip as well as to the `Settings -> Languages & Frameworks -> PHP -> Debug -> Step Filters -> Skipped Files`.
- OPCache is included to speed up performance, but it can have issues with Xdebug. You can always disable OPCache in the ini file
### VSCode Debugging
1. Install the PHP Debug extension
2. Create a launch.json file in the .vscode directory
3. Add the following configuration to the launch.json file:
```
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for XDebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html/game": "${workspaceFolder}"
            }
        }
    ]
}
```
4. Set a breakpoint in the code
5. Start the debugger by selecting the `Listen for XDebug` configuration and clicking the green play button

## Project Architecture

Talishar consists of three main components working together:
- **Talishar** (PHP) - Game engine, logic, and API
- **Talishar-FE** (TypeScript/React) - User interface and state management
- **CardImages** - Card definition and image processing

## Key Backend Files

### Core Game Logic
- **`GetNextTurn.php`** - Main API endpoint that serializes and returns the current game state to the frontend
- **`ProcessInput.php`** - Handles all user input and triggers appropriate game logic
- **`GameLogic.php`** - Core game mechanics and turn flow
- **`CoreLogic.php`** - Essential game engine functions
- **`Constants.php`** - Game constants, zones (HAND, BANISH, GRAVEYARD, PLAY, DECK), and player IDs

### Card System
- **`CardDictionary.php`** - Card data lookup and utilities
- **`CardDictionaries/`** - Card definitions organized by set
  - Each set folder/files (e.g., `WelcomeToRathe/`, `WTRShared.php`) contains card abilities
  - Card logic uses switch statements keyed by `cardNumber` (lowercase with underscores)
  - Example: `"fyendals_spring_tunic"`, `"command_and_conquer_red"`, `"energy_potion_blue"`

### Ability System
- **`CardLogic.php`** - Card-specific ability implementations
- **`CharacterAbilities.php`** - Hero character abilities
- **`ItemAbilities.php`** - Item card abilities
- **`AuraAbilities.php`** - Aura card abilities
- **`AllyAbilities.php`** - Ally card abilities
- **`PermanentAbilities.php`** - Permanent card abilities
- **`LandmarkAbilities.php`** - Landmark card abilities
- **`WeaponLogic.php`** - Weapon card logic

### Game State Management
- **`ParseGamestate.php`** - Parses persisted game state files
- **`WriteGamestate.php`** - Persists game state to files
- **`WriteLog.php`** - Writes game event logs for debugging

### Frontend Communication
- **`GetNextTurn.php`** - Main response to frontend with current game state
- **`ProcessInputAPI.php`** - API version of ProcessInput for frontend requests

## Game State Flow

```
Frontend Component
    ↓ (User Action)
ProcessInput.php (validates & processes)
    ↓ (Triggers logic)
GameLogic.php / CardLogic.php / Ability Files (executes game rules)
    ↓ (Updates state)
WriteGamestate.php (persists to file)
    ↓ (Frontend polls)
GetNextTurn.php (serializes current state)
    ↓ (Returns JSON)
ParseGameState.ts (transforms backend data)
    ↓ (Updates Redux)
GameSlice.ts (Redux store)
    ↓ (Components subscribe)
React Components (render UI)
```

### Key State Variables
- **`$currentPlayer`** - Indicates who has priority (1 or 2)
- **`$turn[0]`** - Current phase (M=Main, A=Action, D=Defense, etc.)

## Developer / Contributor Resources

### XAMPP Development
- [Webserver Dev Quickstart Guide](https://docs.google.com/document/d/1qVlTrst58iZ_6xD9PkxIgZUiSKzV-S4eTJmK32qzaP0/edit)
- [Full Documentation](https://docs.google.com/document/d/15zRJvMOYnwrFtf-pLW3jwpYEMaUrdnNhlhmfgyE4Rs0)

### Contributing
If you would like to contribute, be sure to join the [Discord](https://discord.gg/ErmtqQQEFm) community to chat with fellow contributors.

## Production Deployment

For information on deploying Talishar to production, including SSL/TLS certificate configuration, reverse proxy setup, and security best practices, see [PRODUCTION_SSL_SETUP.md](./PRODUCTION_SSL_SETUP.md).

**Key Production Considerations:**
- SSL certificate must cover all domains: `talishar.net`, `www.talishar.net`, `api.talishar.net`, `fe.talishar.net`, `legacy.talishar.net`
- Use Let's Encrypt for free, auto-renewable certificates
- Configure Apache/Nginx reverse proxy for SSL termination
- Docker containers handle HTTP only - SSL handled by host machine

## Disclaimer

All artwork and card images © Legend Story Studios.

Talishar.net is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of [Legend Story Studios](https://legendstory.com/).
