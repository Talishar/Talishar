<p align="center">
  <img src="https://github.com/Talishar/Talishar/blob/main/Images/TalisharLogo.webp?raw=true" width="623" height="278" alt="Talishar" />
</p>

<h3 align="center">Talishar is a browser-based platform to play Flesh and Blood. It is a fan-made FABTCG project not associated with Legend Story Studios.</h3>

[![license](https://flat.badgen.net/github/license/talishar/talishar)](./LICENSE)
[![discord](https://flat.badgen.net/discord/online-members/JykuRkdd5S?icon=discord)](https://discord.gg/JykuRkdd5S)
[![twitter](https://flat.badgen.net/badge/twitter/@talishar_online/1DA1F2?icon=twitter)](https://twitter.com/talishar_online)
[![bluesky](https://flat.badgen.net/badge/bluesky/pvtvoid/1185FE?icon=bluesky)](https://bsky.app/profile/pvtvoid.bsky.social)
[![patreon](https://flat.badgen.net/badge/become/a%20patreon/F96854?icon=patreon)](https://linktr.ee/Talishar)

Visit [Talishar.net](https://talishar.net/) to get playing Flesh & Blood in your browser right now!

## Getting started with Talishar

This is the back end client for Talishar.net - completely separate from the front end. In order to test Talishar locally, you will need to install the front end project.

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

## Developer / Contributor Resources

### XAMPP Development
- [Webserver Dev Quickstart Guide](https://docs.google.com/document/d/1qVlTrst58iZ_6xD9PkxIgZUiSKzV-S4eTJmK32qzaP0/edit)
- [Full Documentation](https://docs.google.com/document/d/15zRJvMOYnwrFtf-pLW3jwpYEMaUrdnNhlhmfgyE4Rs0)

### Contributing
If you would like to contribute, be sure to join the [Discord](https://discord.gg/ErmtqQQEFm) community to chat with fellow contributors.

## Disclaimer

All artwork and card images © Legend Story Studios.

Talishar.net is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of [Legend Story Studios](https://legendstory.com/).
