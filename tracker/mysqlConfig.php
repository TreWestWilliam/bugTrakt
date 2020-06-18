<?php
 //Establishing a Database connection
    $mysqli = new mysqli("127.0.0.1:3308", "root", "", "bugtrakt");
    // Checking for connection, exiting out otherwise
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        exit;
    }  

    //This is a useful function that I made to reduce the need for repetitive code
    function getUserFromID($UID) 
    {
        $mysqli = new mysqli("127.0.0.1:3308", "root", "", "bugtrakt");
        return $mysqli->query("SELECT `UNAME` FROM `user` WHERE `UID` = $UID");
    }
    function getUserNameFromID($UID) 
    {
        $result = getUserFromID($UID);
        $user = $result->fetch_assoc();
        return $user["UNAME"];
        
    }


?>