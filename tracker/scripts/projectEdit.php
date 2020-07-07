<?php 
    require_once "../mysqlConfig.php";
    session_start();

function editProject($content,$PID,$mysqli) 
{
    $changeProjectQuery = "UPDATE `projects` SET `DESCRIPTION` = '$content' WHERE `projects`.`ID` = $PID;";
    $mysqli->query($changeProjectQuery);
    header("location:../project.php?id=$PID");
}
//Checking logged in user
    if(isset($_SESSION["UID"])) 
    {
        $UID = $_SESSION["UID"];
        // checking for comment
        if (!empty($_POST)) 
        {
            if (isset($_POST["PID"]) && isset($_POST["text"])) 
            {
                $PID = $_POST["PID"];
                $text = $_POST["text"];
                $projectQuery = $mysqli->query("SELECT * FROM `projects` WHERE `ID` = $PID");
                //Making sure we got a row
                if ($projectQuery->num_rows == 1) 
                {
                    $project = $projectQuery->fetch_assoc();
                    
                    //Checking if person is the owner of the project
                    if ($UID == $project["ownerID"]) 
                    {
                        editProject($text,$PID,$mysqli);
                    }//Otherwise check the user's permissions
                    else
                    {
                        $permQuery = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $PID");
                        if ($permQuery->num_rows == 1) 
                        {
                            $perm = $permQuery->fetch_assoc();
                            if ($perm["rank"] >=2) 
                            {
                                editProject($text,$PID,$mysqli);
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