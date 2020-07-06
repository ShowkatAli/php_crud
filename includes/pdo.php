<?php
 
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "RootInNorm@1";
$dbname = "rawphpdb";
$dbcharset = "utf8mb4";
$dsn = "mysql:host=".$dbhost.";dbname=".$dbname.";charset=".$dbcharset.";";
$pdo = new PDO($dsn, $dbuser, $dbpass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>