# How to run Talishar locally

## Database

Start MySQL in Docker:

```
~ docker run --name fabonline-db -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root -d mysql
48c55f94772abc4370df1be53e32d499436abee66fbb07097dea8afe0d364993
```

Verify it's running correctly:

```
~ docker ps
CONTAINER ID   IMAGE     COMMAND                  CREATED         STATUS         PORTS                                                  NAMES
48c55f94772a   mysql     "docker-entrypoint.s…"   3 seconds ago   Up 2 seconds   0.0.0.0:3306->3306/tcp, :::3306->3306/tcp, 33060/tcp   fabonline-db
```

Then connect to the container to create the database and schema:

```
~ docker exec -ti fabonline-db bash
bash-4.4# mysql -u root -p
Enter password:
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 8
Server version: 8.0.30 MySQL Community Server - GPL

Copyright (c) 2000, 2022, Oracle and/or its affiliates.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> create database `fabonline`;
Query OK, 1 row affected (0.02 sec)

mysql> use `fabonline`;
Database changed

mysql> <paste the content of Database/database.sql>
...
Query OK, 0 rows affected (0.05 sec)
Records: 0  Duplicates: 0  Warnings: 0
```

Exit with `\q` and then `exit`.

## Run the Talishar server

Build the server image (Apache with some extensions):
```
~ cd dev
~ docker build . -t fabonline
...
[+] Building 1.7s (10/10) FINISHED
```

Update the database hostname and credentials:

- Open `includes/dbh.inc.php`
- Set `$servername` and `$reportingServername` to `"host.docker.internal"`
  - Might be specific to Mac, but it's likely that "localhost" won't work
- Set `$dBPassword` and `$reportingDBPassword` to `"root"`

Run the server container:

```
docker run --rm --name fabonline-server -d -p 80:80 -v `pwd`:/var/www/html/FaBOnline fabonline
```

Go to `http://localhost/FaBOnline/MainMenu.php` and it should work :)
