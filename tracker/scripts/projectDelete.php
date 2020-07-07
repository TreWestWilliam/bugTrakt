<?php 
    require_once "../mysqlConfig.php";
    session_start();

function deleteProject($PID,$mysqli) 
{
    $deleteUpdates = "DELETE FROM `project-updates` WHERE `project-updates`.`PID` = $PID;";
    $deleteInvites = "DELETE FROM `project-invite` WHERE `project-invite`.`PID` = $PID;";
    $deleteUsers = "DELETE FROM `user-project` WHERE `user-project`.`PID` = $PID;";
    $mysqli->query($deleteUpdates);
    $mysqli->query($deleteInvites);
    $mysqli->query($deleteUsers);
    $tickets = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = $PID")->fetch_all(MYSQLI_ASSOC);
    foreach($tickets as &$ticket) 
    {
        $mysqli->query("DELETE FROM `comments` WHERE `comments`.`TID` = " . $ticket["TID"]);
    }
    $mysqli->query("DELETE FROM `ticket` WHERE `ticket`.`PID` = $PID");
    $mysqli->query("DELETE FROM `projects` WHERE `projects`.`ID` = $PID;");
    header("location:../index.php");
}
//Checking logged in user
    if(isset($_SESSION["UID"])) 
    {
        $UID = $_SESSION["UID"];
        // checking for comment
        if (!empty($_POST)) 
        {
            if (isset($_POST["PID"])) 
            {
                $PID = $_POST["PID"];
                $projectQuery = $mysqli->query("SELECT * FROM `projects` WHERE `ID` = $PID");
                //Making sure we got a row
                if ($projectQuery->num_rows == 1) 
                {
                    $project = $projectQuery->fetch_assoc();
                    
                    //Checking if person is the owner of the project
                    if ($UID == $project["ownerID"]) 
                    {
                        deleteProject($PID,$mysqli);
                    }//Otherwise check the user's permissions
                }
                else 
                {
                    echo "No project";
                }
            }
            else 
            {
                echo "No project";
            }
        }
        else 
        {
            echo "No post";
        }
    }
    else 
    {
        echo "COULD NOT CONFIRM USER.";
    }

?>