<?php 
    require_once "mysqlConfig.php";
    session_start();
//Checking logged in user
    if(isset($_SESSION["UID"])) 
    {
        $UID = $_SESSION["UID"];
        // checking for comment
        if (!empty($_POST)) 
        {
            if (isset($_POST["comment"]) && isset($_POST["text"])) 
            {
                $CID = $_POST["comment"];
                $text = $_POST["text"];
                $commentQuery = $mysqli->query("SELECT * FROM `comments` WHERE `ID` = $CID");
                //Making suer we got a row
                if ($commentQuery->num_rows == 1) 
                {
                    $comment= $commentQuery->fetch_assoc();
                    $TID = $comment["TID"];
                    $ticketQuery = $mysqli->query("SELECT * FROM `ticket` WHERE `TID` = $TID");
                    
                    //Checking if person is the creator of the comment
                    if ($CID == $comment["TID"]) 
                    {
                        $changeCommentQuery = "UPDATE `comments` SET `CONTENT` = '$text' WHERE `comments`.`ID` = $CID";
                        $mysqli->query($changeCommentQuery);
                        header("location:ticket.php?tid=$TID");
                    }//Otherwise check the user's permissions
                    else if ($ticketQuery->num_rows == 1) 
                    {
                        $ticket = $ticketQuery->fetch_assoc();
                        $PID = $ticket["PID"];
                        $permQuery = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $PID");
                        if ($permQuery->num_rows == 1) 
                        {
                            $perm = $permQuery->fetch_assoc();
                            if ($perm["rank"] >=2) 
                            {
                                $changeCommentQuery = "UPDATE `comments` SET `CONTENT` = '$text' WHERE `comments`.`ID` = $CID";
                                $mysqli->query($changeCommentQuery);
                                header("location:ticket.php?tid=$TID");
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
                        echo "USER DOES NOT HAVE PERMISSION TO EXECUTE THIS";
                    }
                }
                else 
                {
                    echo "No comment";
                }
            }
            else 
            {
                echo "No comment";
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