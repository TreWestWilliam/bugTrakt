<?php 
    require_once "mysqlConfig.php";
    session_start();
//Checking logged in user
    $thisUser="";
    $error="";
    if(isset($_SESSION["UID"])) 
    {
        $thisUser = $_SESSION["UID"];
    }
    else 
    {
        $error = "User is not signed in";
    }
    $TID=0;
    $UID=0;
    $PID=0;
    if (isset($_POST["UID"]) && isset($_POST["TID"])) 
    {
        $TID = $_POST["TID"];
        $UID = $_POST["UID"];
    }
    else 
    {
        $error .= " Not enough information submitted.";
    }
    if ($TID != 0) 
    {
        $PID = $mysqli->query("SELECT * FROM `ticket` WHERE `TID` = $TID")->fetch_assoc()["PID"];
    }
    if ($thisUser!="" && $PID!=0) 
    {
        $userPerms = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $thisUser AND `PID` = $PID"); 
        if ($userPerms->num_rows >= 1) 
        {
            $userPerm = $userPerms->fetch_assoc();
            if ($userPerm["rank"] >=2) 
            {
                //echo "perms passed";
            }
            else 
            {
                $error .= "You do not have permissions to execute this action.";
            }
        }
    }
    if ($error == "")
    {
        $mysqli->query("UPDATE `ticket` SET `ASSIGNED_ID` = '$UID' WHERE `ticket`.`TID` = $TID;");
        header("location:ticket.php?tid=$TID");
    }
    else 
    {
        echo $error;
    }

?>