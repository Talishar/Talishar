<p align="center">
  <img src="https://github.com/Talishar/Talishar/blob/main/Images/TalisharLogo.webp?raw=true" width="623" height="278" alt="Talishar" />
</p>

<h3 align="center">Talishar is a browser-based platform to play Flesh and Blood. It is a fan-made FABTCG project not associated with Legend Story Studios.</h3>

[![license](https://flat.badgen.net/github/license/talishar/talishar)](./LICENSE)
[![discord](https://flat.badgen.net/discord/online-members/JykuRkdd5S?icon=discord)](https://discord.gg/JykuRkdd5S)
[![patreon](https://flat.badgen.net/badge/become/a%20patreon/F96854?icon=patreon)](https://www.patreon.com/talishar_online/)
[![twitter](https://flat.badgen.net/twitter/follow/talishar_online?icon=twitter)](https://twitter.com/talishar_online/)

Visit [Talishar.net](https://talishar.net/) to get playing Flesh & Blood in your browser right now!

## Getting started with Talishar

This is the back end client for Talishar.net - completely separate from the front end. In order to test Talishar locally, you will need to install the front end project.

Learn more here: [Talishar-FE](https://github.com/Talishar/Talishar-FE)

## New docker way to run Talishar.

### Prerequisites:
 - Docker

Easier to do on *nix OS than on MS OS.

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

## Developer / Contributor Resources / XAMPP dev env

- [Webserver Dev Quickstart Guide](https://docs.google.com/document/d/1qVlTrst58iZ_6xD9PkxIgZUiSKzV-S4eTJmK32qzaP0/edit)
- [Docs](https://docs.google.com/document/d/15zRJvMOYnwrFtf-pLW3jwpYEMaUrdnNhlhmfgyE4Rs0)

If you would like to contribute, be sure to join the [discord](https://discord.gg/ErmtqQQEFm) to chat with fellow contributors.

## Disclaimer

All artwork and card images © Legend Story Studios.

Talishar.net is in no way affiliated with Legend Story Studios. Legend Story Studios®, Flesh and Blood™, and set names are trademarks of Legend Story Studios. Flesh and Blood characters, cards, logos, and art are property of [Legend Story Studios](https://legendstory.com/).
