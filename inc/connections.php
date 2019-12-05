<?php
//PDO connection to database
//Create database connection object
try {
$db = new PDO("sqlite:".__DIR__."/database.db");
//Always throw an exception
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
    echo "Unable to connect";
    //echo $e->getMessage();
    exit;
}

?>