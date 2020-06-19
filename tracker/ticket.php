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
    $mysqli->query("UPDATE `ticket` SET `VIEWS` = VIEWS + 1 WHERE `ticket`.`TID` = $TID;");
    
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
                        <div id=ticketBorderLeft></div>
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
                    //Getting the ID for convenience
                        $CID = $comment['ID'];
                    //Printing the comment.
                        echo "
                        <div id=ticketComment>
                        <div id=ticketInfo>" . $commentUser['UNAME'] . " " . $comment['TIME_CREATED'] . " </div>
                        <div id=ticketControl>
                        <a onClick='showReplyBox(\"reply$CID\")'>
                        <img id=controlIcon src=options.png alt=options width=25px height=25px>
                        </a>
                        </div>
                        <p> " . $comment['CONTENT'] . " </p>
                            <div id=\"reply$CID\" class=replyBox style=\"display:none; \">
                                <p>Replying to " . $commentUser['UNAME'] . " </p>
                                <form method=post action='". "ticket.php?tid=" . $TID . "'>
                                    Description:<br><textarea name=comment rows=2 cols=100></textarea><br>
                                    <input type=hidden name=isReply value=1>
                                    <input type=hidden name=parentComment value=" . $CID . ">
                                    <input type=submit>
                                </form>
                            </div>
                        ";
                    
                    //Creating a query for all the replies to the comment
                        $replies = $mysqli->query("SELECT * FROM `comments` WHERE `TID` = $TID AND `is_Reply` = 1 AND `Parent_Comment` = $CID ORDER BY `TIME_CREATED` ASC");
                    //looping through all replies
                        while($replySearch = $replies->fetch_assoc()) 
                        {
                            //Getting the user who made the comment
                            $replyUserQuery = getUserFromID($replySearch['UID']);
                            $replyUser = $replyUserQuery->fetch_assoc();
                            $RID=$replySearch['ID'];
                            //printing the reply
                                echo "
                                <div id=commentReply>
                                    <p id=ticketInfo> " . $replyUser['UNAME'] . " " . $replySearch['TIME_CREATED'] . " </p>
                                    <div id=ticketControl>
                                        <a onClick='showReplyBox(\"reply$RID\")'>
                                        <img onclick='' id=controlIcon src=options.png alt=cog width=25px height=25px>
                                        </a>
                                    </div>
                                    <p> " . $replySearch['CONTENT'] . " </p>

                                    <div id=\"reply" . $RID . "\" class=replyBox style=\"display:none; \">
                                        <form method=post action='". "ticket.php?tid=" . $TID . "'>
                                            Description:<br><textarea name=comment rows=2 cols=100></textarea><br>
                                            <input type=hidden name=isReply value=1>
                                            <input type=hidden name=parentComment value=" . $CID . ">
                                            <input type=submit>
                                        </form>
                                    </div>
                                </div>";
                        }
                    //Closing the div for the comment chain
                    echo "</div>";
                }
                
                $commentError = "";
                $COMMENT = "";
                if ($_SERVER["REQUEST_METHOD"] == "POST")
                {
                    $UID = $_SESSION["UID"];
                    $COMMENT = $_POST["comment"];
                    $isReply = $_POST["isReply"];
                    $parentComment = $_POST["parentComment"];
                    $commentQuery = "INSERT INTO `comments` (`ID`, `TID`, `UID`, `CONTENT`, `TIME_CREATED`, `is_Reply`, `Parent_Comment`) VALUES (NULL, '$TID', '$UID', '$COMMENT', CURRENT_TIMESTAMP, '$isReply', '$parentComment');";
                    $mysqli->query($commentQuery);
                    $updateQuery = "UPDATE `ticket` SET `UPDATES` = UPDATES + 1, `LAST_UPDATED` = CURRENT_TIMESTAMP WHERE `ticket`.`TID` = $TID;";
                    $mysqli->query($updateQuery);
                    //header("Refresh:0");
                    //Emptying the comment to stop spam
                    $COMMENT="";
                    
                }
                ?>
                <div id=replyForm>
                    <h2>Post a comment</h2>
                    <form method=post action="">
                         <?php if ($commentError != "")  {echo "<p id=warning><spawn id=warning>" . $commentError . "</span></p>";}?>
                Description:<br><textarea name="comment" rows=10 cols=100><?php echo $COMMENT; ?></textarea><br>
                        <input type="hidden" name="isReply" value=0>
                        <input type="hidden" name="parentComment" value=0>
                        <input type=submit>
                    
                    </form>
                
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
    <script>
    function showReplyBox(myString) 
    {
        myObject = document.getElementById(myString);
        if (myObject.style.display == "none") 
        {
            myObject.style.display = "block";
        }
        else 
        {
            myObject.style.display = "none";
        }
    }
    </script>
</body>

</html>