<?php

  function GetLocalMySQLConnection()
  {
     $hostname = (!empty(getenv("MYSQL_SERVER_NAME")) ? getenv("MYSQL_SERVER_NAME") : "localhost");
     $username = (!empty(getenv("MYSQL_SERVER_USER_NAME")) ? getenv("MYSQL_SERVER_USER_NAME") : "root");
     $password = (!empty(getenv("MYSQL_ROOT_PASSWORD")) ? getenv("MYSQL_ROOT_PASSWORD") : "");
     $database = "fabonline";
	   try {
       return mysqli_connect($hostname, $username, $password, $database);
     } catch (\Exception $e) {

     }
     return false;
   }

 ?>
