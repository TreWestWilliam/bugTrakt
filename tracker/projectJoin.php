<?php 

require_once "mysqlConfig.php";
$PID = $_GET["pid"];
session_start();
$UID = $_SESSION["UID"];
$RANK = "";
$project = $mysqli->query("SELECT * FROM `projects` WHERE `ID` = $PID")->fetch_assoc();
$RANK = $project["DEFAULT_RANK"];
    
$mysqli->query("INSERT INTO `user-project` (`ID`, `UID`, `PID`, `rank`) VALUES (NULL, '$UID', '$PID', '$RANK');");

header("location:project.php?id=$PID");

//
?>