<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<title>BugTrakt Tracker</title>
	<meta name="author" content="William West">
	<meta name="description" content="A simple web bug tracker">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="trackerStyle.css">
	<link rel="icon" type="image/x-icon" href=""/>
</head>

<body>
<?php 
    //calling our header
    echo file_get_contents("head.php");
    require_once "mysqlConfig.php";
    session_start();
    ?>
    
	<main>
        <div id="bodyContainer">
            <div id="bodyLeft">
                <h1>Your Tickets</h1>
                <!-- Your tickets will be displayed here-->
                <?php 
                $UID=0;
                $UID = $_SESSION["UID"];
                $projectsQuery = "SELECT * FROM `user-project` WHERE `UID` = $UID";
                $projectsArray = $mysqli->query($projectsQuery);
                $projectsString = "";
                if ($projectsArray->num_rows >= 1) 
                {
                    $temp = $projectsArray->fetch_assoc();
                    $projectsString = "".$temp["PID"];
                    
                    if ($projectsArray->num_rows > 1) 
                    {
                        while ($temp = $projectsArray->fetch_assoc()) 
                        {
                            $projectsString = $projectsString . " OR " . $temp["PID"];
                        }
                    }
                    
                    $query = "SELECT * FROM `ticket` WHERE `ASSIGNED_ID` = $UID AND `PID` = $projectsString ORDER BY `LAST_UPDATED` DESC";
                    $ticketsArray = $mysqli->query($query);
                    if ($ticketsArray->num_rows>=1) 
                    {
                        $i = 9;
                        while ($ticket = $ticketsArray->fetch_assoc()) 
                        {
                            if ($i > 0) {
                            $TID = $ticket["TID"];
                            $Name = $ticket["TITLE"];
                            $createdBy = getUserNameFromID($ticket["UID"]);
                            $dateTime_created = $ticket["CREATED"];
                        echo "<a href=ticket.php?tid=$TID><div id=yourTicket>
                        <h2>$TID | $Name</h2>
                        <p>Created by: $createdBy Created on:$dateTime_created</p>
                        </div></a>";
                            }
                            else {break;}
                            $i=$i-1;
                        } 
                    }
                    else 
                    {
                        $query = "SELECT * FROM `ticket` WHERE `PID` = $projectsString ORDER BY `LAST_UPDATED` DESC";
                        $ticketsArray = $mysqli->query($query);
                        if ($ticketsArray->num_rows >= 1) 
                        {
                            $i = 9;
                           while ($ticket = $ticketsArray->fetch_assoc()) 
                            {
                               if ($i > 0) {
                               $TID = $ticket["TID"];
                               $Name = $ticket["TITLE"];
                               $createdBy = getUserNameFromID($ticket["UID"]);
                               $dateTime_created = $ticket["CREATED"];
                            echo "<a href=ticket.php?tid=$TID><div id=yourTicket>
                            <h2>$TID | $Name</h2>
                            <p>Created by: $createdBy Created on:$dateTime_created</p>
                            </div></a>";
                               }
                               else {break;}
                               $i=$i-1;
                            } 
                        }
                    }
                }
                else 
                {
                    echo "<h2>You have no tickets.</h2>";
                }
                
                
                
                ?>

                
            </div>
            <div id="bodyRight"> 
            <span id="alignCenter"><h1>Recent updates</h1></span>
                <?php 
                
                if ($projectsString != "") 
                {
                    $updatesQuery = "SELECT * FROM `project-updates` WHERE `PID` = $projectsString ORDER BY `UPDATE_ID` DESC";
                    $updatesArray = $mysqli->query($updatesQuery);
                    $i = 2;
                    while ($update = $updatesArray->fetch_assoc()) 
                    {
                        if ($i>0) 
                        {
                            $title = $update["TITLE"];
                            $description = $update["DESCRIPTION"];
                         echo "<div id=update>
                            <h1>$title</h1>
                            <p>$description</p>
                            </div>";   
                        }
                        else 
                        {
                            break;
                        }
                    }
                }
                else 
                {
                    echo "<h2>You have no projects to display updates from.</h2>";
                }
                ?>
               
                <a><p id="alignCenter">More updates here.</p></a>
                <h1 id="alignCenter">Recently made tickets</h1>
                <?php 
                
                if ($projectsString != "") 
                {
                    $query = "SELECT * FROM `ticket` WHERE `PID` = $projectsString ORDER BY `CREATED` DESC";
                    $ticketsArray = $mysqli->query($query);
                    if ($ticketsArray->num_rows >= 1) 
                    {
                        $i = 6;
                       while ($ticket = $ticketsArray->fetch_assoc()) 
                        {
                           if ($i > 0) {
                           $TID = $ticket["TID"];
                           $Name = $ticket["TITLE"];
                           $createdBy = getUserNameFromID($ticket["UID"]);
                           $dateTime_created = $ticket["CREATED"];
                        echo "<a href=ticket.php?tid=$TID><div id=recentTicket>
                        <h2>$TID | $Name</h2>
                        <p>Created by: $createdBy Created on:$dateTime_created</p>
                        </div></a>";
                           }
                           else {break;}
                           $i=$i-1;
                        } 
                    }
                }
                
                ?>
                <!--
                <div id="recentTicket">
                <h2>$TicketID | $TicketName</h2>
                    <p>Created by: $Created_User_name Created on:$dateTime_created</p>
                </div>
                <div id="recentTicket">
                <h2>$TicketID | $TicketName</h2>
                    <p>Created by: $Created_User_name Created on:$dateTime_created</p>
                </div>
                -->
            </div>
        </div>
    </main>
    <?php 
    //calling our footer
    chdir("..");
    echo file_get_contents("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
</body>

</html>