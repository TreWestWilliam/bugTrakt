<?php 
require_once "../mysqlConfig.php";
$PID = $_GET["pid"];
session_start();
$UID = $_SESSION["UID"];
    
$mysqli->query("DELETE FROM `user-project` WHERE `user-project`.`PID` = $PID AND `user-project`.`UID` = $UID");

header("location:../project.php?id=$PID");

?>