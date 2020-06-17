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
    // Getting the ticket id from the URL
    $TID = $_GET["tid"];
   
    //Creating a string to hold the query
    //$queryString = ;
    //Testing the query while executing it
    if (!$result = $mysqli->query("SELECT * FROM `ticket` WHERE `TID` = $TID")) 
    {
        echo "<p>Could not communicate with the database</p>";
        exit;
                        
    }
    //Checking for a result
    if ($result->num_rows === 0) {
                        echo "<p> No ticket found.</p>";
                        exit;
    }
    // Getting results
    $ticket = $result->fetch_assoc();
    //Incrementing the views
    $newViews = $ticket['VIEWS'] + 1;
    $mysqli->query("UPDATE `ticket` SET `VIEWS` = '$newViews' WHERE `ticket`.`TID` = 1;");
    
    //Now getting the name of the user who created this
    $userID = $ticket['UID'];
    $resultTwo = $mysqli->query("SELECT `UNAME` FROM `user` WHERE `UID` = $userID");
    $userName = $resultTwo->fetch_assoc();
    //Getting the comments
    $comments = $mysqli->query("SELECT * FROM `comments` WHERE `TID` = $TID AND `is_Reply` = 0 ORDER BY `TIME_CREATED` ASC");
    
    
    ?>
    
    
	<main>
        <div id="centeredBodyQ">
            <div id="ticketBody">
                <div id="ticketItemContainer">
                    <div id="ticketMeta">
                        <div id="ticketInfo">
                            <?php 
                             echo "<p>Created by:" . $userName['UNAME'] . " On: " . $ticket['CREATED'] ." Viewed " . $ticket['VIEWS'] . " time(s)</p>";
                            ?>
                        </div>
                        <div id="ticketControl">
                        <img id="controlIcon" src=options.png alt="Settings">
                        </div>
                    </div>
                    <?php 
                    echo "<h1>".$ticket['TITLE']."</h1>";

                     echo "<p>".$ticket['CONTENT']."</p>";
                        // Lets see if it worked
                        /*echo "<p> Ticket ID=";
                        echo $TID;
                        echo "\n Title=" . $ticket['TITLE'] . " Content= " . $ticket['CONTENT'] ."</p>";
                    */
                    ?>
                </div>
                <?php 
                //Looping through the comments for the page
                while($comment = $comments->fetch_assoc()) 
                {
                    //Getting the comment's user
                    $commentUserQuery = getUserFromID($comment['UID']);
                    $commentUser = $commentUserQuery->fetch_assoc();
                    //Printing the comment.
                        echo "
                        <div id=ticketComment>
                        <div id=ticketInfo>" . $commentUser['UNAME'] . " " . $comment['TIME_CREATED'] . " </div>
                        <div id=ticketControl>
                        <img id=controlIcon src=options.png alt=cog width=25px height=25px>
                        </div>
                        <p> " . $comment['CONTENT'] . " </p>";
                    //Getting the ID for convenience
                        $CID = $comment['ID'];
                    //Creating a query for all the replies to the comment
                        $replies = $mysqli->query("SELECT * FROM `comments` WHERE `TID` = $TID AND `is_Reply` = 1 AND `Parent_Comment` = $CID ORDER BY `TIME_CREATED` ASC");
                    //looping through all replies
                        while($replySearch = $replies->fetch_assoc()) 
                        {
                            //Getting the user who made the comment
                            $replyUserQuery = getUserFromID($replySearch['UID']);
                            $replyUser = $replyUserQuery->fetch_assoc();
                            //printing the reply
                                echo "
                                <div id=commentReply>
                                <p id=ticketInfo> " . $replyUser['UNAME'] . " " . $replySearch['TIME_CREATED'] . " </p>
                                 <div id=ticketControl>
                                <img id=controlIcon src=options.png alt=cog width=25px height=25px>
                                </div>
                                <p> " . $replySearch['CONTENT'] . " </p>
                                </div>";
                        }
                    //Closing the div for the comment chain
                    echo "</div>";
                }
                
                ?>
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