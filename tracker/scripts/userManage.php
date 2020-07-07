<?php 
    require_once "../mysqlConfig.php";
    session_start();

function editProject($user,$action,$PID,$mysqli) 
{
    if ($action==-1) 
    {
        $removeUserQuery="DELETE FROM `user-project` WHERE `user-project`.`PID` = $PID AND `user-project`.`UID` = $user;";
        $mysqli->query($removeUserQuery);
    }
    else 
    {
        $setRankQuery = "UPDATE `user-project` SET `rank` = '$action' WHERE `user-project`.`PID` = $PID AND `user-project`.`UID` = $user;";
        $mysqli->query($setRankQuery);
    }
    header("location:../project.php?id=$PID");
}
//Checking logged in user
    if(isset($_SESSION["UID"])) 
    {
        $UID = $_SESSION["UID"];
        // checking for comment
        if (!empty($_POST)) 
        {
            if (isset($_POST["PID"]) && isset($_POST["user"]) && isset($_POST["action"])) 
            {
                $PID = $_POST["PID"];
                $user = $_POST["user"];
                $action = $_POST["action"];
                $projectQuery = $mysqli->query("SELECT * FROM `projects` WHERE `ID` = $PID");
                //Making sure we got a row
                if ($projectQuery->num_rows == 1) 
                {
                    $project = $projectQuery->fetch_assoc();
                    
                    //Checking if person is the owner of the project
                    if ($UID == $project["ownerID"]) 
                    {
                        editProject($user,$action,$PID,$mysqli);
                    }//Otherwise check the user's permissions
                    else
                    {
                        $permQuery = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $PID");
                        if ($permQuery->num_rows == 1) 
                        {
                            $perm = $permQuery->fetch_assoc();
                            if ($perm["rank"] >=2) 
                            {
                                editProject($user,$action,$PID,$mysqli);
                            }
                            else 
                            {
                                echo "User does not have enough permissions";
                            }
                        }
                        else
                        {
                            echo "Could not find permissions";
                        }
                    }
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