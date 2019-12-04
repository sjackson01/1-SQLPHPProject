<?php
//PDO connection to database
//Create database connection object
$db = new PDO("sqlite:".__DIR__."/database.sql");

var_dump($db);

?>