<?php
//PDO connection to database
//Create database connection object
try {
$db = new PDO("sqlite:".__DIR__."/database.db");
var_dump($db);
}catch(Exception $e){
    echo "Unable to connect";
    exit;
}

echo "Connected to the database";

?>