<?php
//INSERT INTO `project-updates` (`UPDATE_ID`, `PID`, `TITLE`, `DESCRIPTION`) VALUES (NULL, '1', 'Test', 'wsadsadsa');
    require_once "../mysqlConfig.php";
    session_start();

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
                $title = $_POST["title"];
                $text = $_POST["text"];
                $projectQuery = $mysqli->query("SELECT * FROM `projects` WHERE `ID` = $PID");
                //Making sure we got a row
                if ($projectQuery->num_rows == 1) 
                {
                    $project = $projectQuery->fetch_assoc();
                    $permQuery = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $PID");
                    if ($permQuery->num_rows == 1) 
                    {
                        $perm = $permQuery->fetch_assoc();
                        if ($perm["rank"] >=2) 
                        {// Success
                            
                            $mysqli->query("INSERT INTO `project-updates` (`UPDATE_ID`, `PID`, `TITLE`, `DESCRIPTION`) VALUES (NULL, '$PID', '$title', '$text');");
                            header("location:../project.php?id=$PID");
                            
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