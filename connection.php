<?php

    $database= new mysqli("localhost","root","password","sql_database_edoc");
    if ($database->connect_error){
        die("Connection failed:  ".$database->connect_error);
    }

?>