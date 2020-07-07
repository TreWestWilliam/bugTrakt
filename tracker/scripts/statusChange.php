<?php 
    require_once "../mysqlConfig.php";
    session_start();

function editTicket($status,$TID,$mysqli) 
{
    $changeTicketQuery = "UPDATE `ticket` SET `STATUS` = '$status' WHERE `ticket`.`TID` = $TID;";
    $mysqli->query($changeTicketQuery);
    header("location:../ticket.php?tid=$TID");
}
//Checking logged in user
    if(isset($_SESSION["UID"])) 
    {
        $UID = $_SESSION["UID"];
        // checking for comment
        if (!empty($_POST)) 
        {
            if (isset($_POST["TID"]) && isset($_POST["status"])) 
            {
                $TID = $_POST["TID"];
                $status = $_POST["status"];
                $ticketQuery = $mysqli->query("SELECT * FROM `ticket` WHERE `TID` = $TID");
                //Making suer we got a row
                if ($ticketQuery->num_rows == 1) 
                {
                    $ticket = $ticketQuery->fetch_assoc();
                    
                    //Checking if person is the creator of the ticket
                    if ($UID == $ticket["UID"]) 
                    {
                        editTicket($status,$TID,$mysqli);
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
                                editTicket($status,$TID,$mysqli);
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
                    echo "No ticket";
                }
            }
            else 
            {
                echo "No ticket";
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