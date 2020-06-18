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
    ?>
    
	<main>
        <div id="centeredBody">
            <h1 id="alignCenter">Tickets</h1>
            <p>Fitler/Search:</p>
            <form method="post" action="">
            Search: <input type="text" name="search"> <br>
            Active: <select id="active" name="isactive">
                <option value="either">Either</option>
                <option value="curactive">Open</option>
                <option value="inactive">Closed</option>
                </select><br>
                Amount: <select id="pages" name="pages">
                <option value="ten">10/page</option>
                <option value="five">5/page</option>
                <option value="fifteen">15/page</option>
                <option value="twenty">20/page</option>
                <option value="twentyFive">25/page</option>
                <option value="fifty">50/page</option>
                </select><br>
                Priority: <select id="priority" name="priority">
                <option value="any">Any</option>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
                </select><br>
                Difficulty: <select id="difficulty" name="difficulty">
                <option value="any">Any</option>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
                </select><br>
                <input type="submit">
            </form>
            <div id="ticketContainer">
                <?php 
                require_once"mysqlConfig.php";
                
                $results = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = 1");
                
                while($ticket = $results->fetch_assoc()) 
                {
                    $TicketID = $ticket["TID"];
                    $TicketName = $ticket["TITLE"];
                    $CreatedUserName=getUserNameFromID($ticket["UID"]);
                    $dateTimeCreated=$ticket["CREATED"];
                    $DIFFICULTY =$ticket["DIFFICULTY"];
                    $PRIORITY = $ticket["PRIORITY"];
                    
                    if ($PRIORITY == 0) 
                    {
                        $PriorityText="Low";
                    }
                    else if ($PRIORITY == 1) 
                    {
                        $PriorityText="Medium";
                    }
                    else  
                    {
                        $PriorityText="High";
                    }
                    
                    if ($DIFFICULTY == 0) 
                    {
                        $DifficultyText="Low";
                    }
                    else if ($DIFFICULTY == 1) 
                    {
                        $DifficultyText="Medium";
                    }
                    else  
                    {
                        $DifficultyText="High";
                    }
                    
                    
                echo "<a href=ticket.php?tid=$TicketID><div id=yourTicket>
                <h2>$TicketID | $TicketName</h2>
                    <p>Created by: $CreatedUserName Created on:$dateTimeCreated</p>
                    <p>Difficulty: $DifficultyText Priority: $PriorityText</p>
                </div></a>";
                    }
                ?>
            </div>
                                <div id="pages">
                <div id="thirdsLeft">
                    Previous
                </div>
                <div id="thirdsCenter">
                1 2 3 4 5
                </div>
                <div id="thirdsRight">
                Next
                </div>
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