# Adding OPCache and JIT to Talishar-BE
First of all, you should know what OPCache and JIT is.

Here you can learn about them:
* [OPCache introduction](https://wp-rocket.me/wordpress-cache/what-is-opcache/) - [OPCache documentation](https://www.php.net/manual/en/book.opcache.php)
* [JIT introduction](https://php.watch/articles/jit-in-depth)
### 1. OPCache
Okay so OPCache has many functionality we could use in Talishar-BE to improve performance. The thing i was most interested in is [preloading](https://www.php.net/manual/en/opcache.preloading.php). When an include happens it will be read out of RAM instead of the filesystem if it was preloaded. This improves the performance by much as the front end sends many requests and the back end can save time with this option.

#### How it works?

So OPCache is a pre-installed module by default in php 8. So we just need to [enable it](https://github.com/Talishar/Talishar/blob/339ef019f391d0f84643d83ae66178ce40591ae6/Dockerfile#L21). And to add a [configuration file](https://github.com/Talishar/Talishar/blob/339ef019f391d0f84643d83ae66178ce40591ae6/conf/opcache.ini) into our [PHP modules path](https://github.com/Talishar/Talishar/blob/339ef019f391d0f84643d83ae66178ce40591ae6/docker-compose.yml#L10).

#### How to configure OPCache?

It's hard to tell what exactly is a 'good' configuration. You should read the configuration options in the [documentation](https://www.php.net/manual/en/opcache.configuration.php). 

**NOTE:** The basic, provided configuration is just an example!

#### Preloading

You should be able to set a path to your [preloader php file](https://github.com/Talishar/Talishar/blob/339ef019f391d0f84643d83ae66178ce40591ae6/conf/preload.php). This will execute on start-up and cache the input files. Sadly talishar can't really cache all the files because you will see unknown / unset variables errors.

If you want to take look into your docker, to see if you file has been preloaded execute the following commands:
* `docker ps` it will produce an output like this:
```
CONTAINER ID   IMAGE                 COMMAND                  CREATED         STATUS         PORTS                                       NAMES
f2381ef5820b   talishar_web-server   "docker-php-entrypoi…"   2 seconds ago   Up 1 second    0.0.0.0:8080->80/tcp, :::8080->80/tcp       talishar-web-server-1
1b07a1ad2907   phpmyadmin:latest     "/docker-entrypoint.…"   3 seconds ago   Up 1 second    0.0.0.0:5001->80/tcp, :::5001->80/tcp       talishar-phpmyadmin-1
0472431ab3ca   mysql:latest          "docker-entrypoint.s…"   3 seconds ago   Up 1 second    3306/tcp, 33060/tcp                         talishar-mysql-server-1
6d05d1cb156f   redis:7.0             "docker-entrypoint.s…"   3 seconds ago   Up 2 seconds   0.0.0.0:6382->6379/tcp, :::6382->6379/tcp   app_redis

```
* Look for the `CONTAINER ID` of the `talishar_web-server` image. Then execute `docker logs -f ID`. It will throw you out all the log information you want to see.
```
> docker logs -f f2381ef5820b
AH00558: apache2: Could not reliably determine the server's fully qualified domain name, using 192.168.128.5. Set the 'ServerName' directive globally to suppress this message
AH00558: apache2: Could not reliably determine the server's fully qualified domain name, using 192.168.128.5. Set the 'ServerName' directive globally to suppress this message
Tue Sep 26 19:43:21 2023 (18): Message Cached script '$PRELOAD$'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/conf/preload.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/GameLogic.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/Search.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardLogic.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionary.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/Constants.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/WelcomeToRathe/WTRShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ArcaneRising/ARCShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ArcaneRising/ARCGeneric.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ArcaneRising/ARCMechanologist.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ArcaneRising/ARCRanger.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ArcaneRising/ARCRuneblade.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ArcaneRising/ARCWizard.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/CrucibleOfWar/CRUShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Monarch/MONShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Monarch/MONGeneric.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Monarch/MONBrute.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Monarch/MONIllusionist.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Monarch/MONRuneblade.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Monarch/MONWarrior.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Monarch/MONTalent.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/TalesOfAria/ELEShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/TalesOfAria/ELEGuardian.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/TalesOfAria/ELERanger.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/TalesOfAria/ELERuneblade.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/TalesOfAria/ELETalent.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Everfest/EVRShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Uprising/UPRShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Uprising/UPRIllusionist.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Uprising/UPRNinja.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Uprising/UPRWizard.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Uprising/UPRTalent.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/card_names.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/PlayAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/HitEffects.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/CurrentEffects.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ActivatedAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Keywords.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ClassicBattles/DVRShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/ClassicBattles/RVDShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Dynasty/DYNShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Outsiders/OUTShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/DuskTillDawn/DTDShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardDictionaries/Roguelike/ROGUEShared.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/GeneratedCode/GeneratedCardDictionaries.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/GeneratedCode/DatabaseGeneratedCardDictionaries.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CoreLogic.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardSetters.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CardGetters.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/AuraAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/ItemAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/AllyAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/PermanentAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/LandmarkAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CharacterAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/WeaponLogic.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/MZLogic.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/Classes/Banish.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/Classes/CombatChain.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/Classes/Deck.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/Classes/Discard.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/DecisionQueue/DecisionQueueEffects.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CurrentEffectAbilities.php'
Tue Sep 26 19:43:21 2023 (18): Message Cached script '/var/www/html/game/CombatChain.php'
```

### 2. JIT
Jit is part of OPCache. All you need to enable JIT is to set it's buffer size (`opcache.jit_buffer_size`). You can find all the possible configuration options on the OPCache configuration section.

### 3. How do I know it's enabled and up and running?
If you ran docker, just open the [info page](http://localhost:8080/game/zzPHPInfo.php). There should be a Zend OPcache section displaying all the set options and marking
* Opcode Caching: Up and Running
* JIT: On
* Cached scripts: 66 (this is true for the default `GameLogic.php` preloading)

### 4. Can I measure the performance? Can I profile the backend?
Yes and no. Yes, you can, for example with [XDebug](https://xdebug.org/) but at the time of writing this I'm lacking time to explain how to set it up in Docker.

**NO** because JIT is not compatible with XDebug (OpCache without JIT enabled works). So you need to turn of JIT if you want to use XDebug. 
