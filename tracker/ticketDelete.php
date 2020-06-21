<?php 
    require_once "mysqlConfig.php";
    session_start();
//Checking logged in user
    //Creating the functin to delete tickets, reducing code.
    if(isset($_SESSION["UID"])) 
    {
        $UID = $_SESSION["UID"];
        // checking for comment
        if (!empty($_GET)) 
        {
            if (isset($_GET["ticket"])) 
            {
                $TID = $_GET["ticket"];
                $ticketQuery = $mysqli->query("SELECT * FROM `ticket` WHERE `TID` = $TID");
                $ticket= $ticketQuery->fetch_assoc();
                //Making suer we got a row
                if ($ticketQuery->num_rows == 1) 
                {   
                    //Checking if person is the creator of the comment
                    if ($TID == $ticket["UID"]) 
                    {
                        deleteTicket($TID,$mysqli);
                        header("location:index.php");
                    }//Otherwise check the user's permissions
                    else
                    {
                        $PID = $ticket["PID"];
                        $permQuery = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $PID");
                        if ($permQuery->num_rows == 1) 
                        {
                            $perm = $permQuery->fetch_assoc();
                            if ($perm["rank"] >=2) 
                            {
                                deleteTicket($TID,$mysqli);
                                header("location:index.php");
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
                    
                    
                    $permQuery = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $PID");
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
            echo "No get";
        }
    }
    else 
    {
        echo "COULD NOT CONFIRM USER.";
    }

function deleteTicket($TID,$mysqli) 
    {
        //We have to clean up the tickets comments before deleting the ticket
        $comments = $mysqli->query("SELECT * FROM `comments` WHERE `TID` = $TID AND `is_Reply` = 0");
        while($parent = $comments->fetch_assoc()) 
        {
            //Parents are the comments, children are replies to the parents.
            $parentID = $parent["ID"];
            $replies = $mysqli->query("SELECT * FROM `comments` WHERE `is_Reply` = 1 AND `Parent_Comment` = $parentID");
            //Delete all children before we delete the parent so that we dont have junk data left over.
            while ($child = $replies->fetch_assoc()) 
            {
                $childID = $child["ID"];
                $mysqli->query("FROM `comments` WHERE `comments`.`ID` = $childID");
            }
            //Now delete the parent.
            $mysqli->query("FROM `comments` WHERE `comments`.`ID` = $parentID");
        }
        //Now delete the commment
        $mysqli->query("DELETE FROM `ticket` WHERE `ticket`.`TID` = $TID");
    }

?>