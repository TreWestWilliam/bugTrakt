<?php
 //Establishing a Database connection
    $mysqli = new mysqli("127.0.0.1:3308", "root", "", "bugtrakt");
    // Checking for connection, exiting out otherwise
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        exit;
    }  

    //This is a useful function that I made to reduce the need for repetitive code
    //Im putting these here because any page that needs this already imports this
    function getUserFromID($UID) 
    {
        $mysqli = new mysqli("127.0.0.1:3308", "root", "", "bugtrakt");
        return $mysqli->query("SELECT `UNAME` FROM `user` WHERE `UID` = $UID");
    }
    function getUserNameFromID($UID) 
    {
        $result = getUserFromID($UID);
        $user = $result->fetch_assoc();
        if (isset($user["UNAME"])) 
        {
        return $user["UNAME"];
        }
        else 
        {
            return "No one";
        }
        
    }
    function translateDiff($NUM) 
    {
        if ($NUM==0) 
        {
            return "Low";
        }
        else if ($NUM == 1) 
        {
            return "Medium";
        }
        return "High";
    }
    function translateStatus($NUM) 
    {
        switch($NUM) 
        {
                CASE 0: return "Open"; break;
                CASE 1: return "Active"; break;
                CASE 2: return "InActive"; break;
                CASE 3: return "Closed"; break;
        }
        return "";
    }


?>