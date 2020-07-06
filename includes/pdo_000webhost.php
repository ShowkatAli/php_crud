<?php
 
$dbhost = "localhost";
$dbuser = "id14274424_root";
$dbpass = 'O($O0L*$QC7_l5-m';
$dbname = "id14274424_rawphpdb";
$dbcharset = "utf8mb4";
$dsn = "mysql:host=".$dbhost.";dbname=".$dbname.";charset=".$dbcharset.";";
$pdo = new PDO($dsn, $dbuser, $dbpass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>