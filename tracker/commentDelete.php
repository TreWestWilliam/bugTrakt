<?php 
    require_once "mysqlConfig.php";
    session_start();
//Checking logged in user
    if(isset($_SESSION["UID"])) 
    {
        $UID = $_SESSION["UID"];
        // checking for comment
        if (!empty($_GET)) 
        {
            if (isset($_GET["comment"])) 
            {
                $CID = $_GET["comment"];
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
                        $deleteCommentQuery = "DELETE FROM `comments` WHERE `comments`.`ID` = $CID";
                        $deleteChildren = "DELETE FROM `comments` WHERE `comments`.`PARENT_COMMENT` = $CID";
                        $mysqli->query($deleteChildren);
                        $mysqli->query($deleteCommentQuery);
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
                                $deleteCommentQuery = "DELETE FROM `comments` WHERE `comments`.`ID` = $CID";
                                $deleteChildren = "DELETE FROM `comments` WHERE `comments`.`PARENT_COMMENT` = $CID";
                                $mysqli->query($deleteChildren);
                                $mysqli->query($deleteCommentQuery);
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
                    
                    
                    $permQuery = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $PID");
                }
                else 
                {
                    echo "No comment";
                }

                $deleteCommentQuery = "DELETE FROM `comments` WHERE `comments`.`ID` = $CID";
                $deleteChildren = "DELETE FROM `comments` WHERE `comments`.`PARENT_COMMENT` = $CID";
            }
            else 
            {
                echo "No comment";
            }
        }
        else 
        {
            echo "No get";
        }
    }
    else 
    {
        echo "COULD NOT CONFIRM USER.";
    }

?>