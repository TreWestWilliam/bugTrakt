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
        <p>This page will be dynamic in the future</p>
        <div id="bodyContainer">
            <div id="bodyLeft">
                <h1>Your Tickets</h1>
                <!-- Your tickets will be displayed here-->
            <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>Created by: $Created_User_name Created on:$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
                <div id="yourTicket">
            <h2>$TicketID | $TicketName</h2>
                <p>$dateTime_created</p>
            </div>
            </div>
            <div id="bodyRight"> 
            <span id="alignCenter">    <h1>Recent updates</h1></span>
                <div id="update">
                    <h1>$updateTitle</h1>
                    <p>text here</p>
                </div>
                <div id="update">
                    <h1>Update 2.0.4x going live</h1>
                    <p>On January 20th 2020 we will be pushing Update 2.0.4x.  All updates done from today onwards will be included in update 2.0.5.</p>
                </div>
                <a><p id="alignCenter">More updates here.</p></a>
                <h1 id="alignCenter">Recently made tickets</h1>
                
                <div id="recentTicket">
                <h2>$TicketID | $TicketName</h2>
                    <p>Created by: $Created_User_name Created on:$dateTime_created</p>
                </div>
                <div id="recentTicket">
                <h2>$TicketID | $TicketName</h2>
                    <p>Created by: $Created_User_name Created on:$dateTime_created</p>
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