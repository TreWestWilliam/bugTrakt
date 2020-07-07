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

<body onload="start()">
<?php
    //calling our header
    include_once("head.php");
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
                             echo "<p>Created by:" . $userName['UNAME'] . " On: " . $ticket['CREATED'] ."  Assigned to:". getUserNameFromID($ticket["ASSIGNED_ID"]) ." </p>";
                            ?>
                        </div>
                        <div id="ticketControl">
                            <?php 
                            echo "<a onClick='showReplyBox(\"options$TID\")'>
                            <img id=controlIcon src=options.png alt=options width=25px height=25px>
                            </a>
                            <div id=\"options$TID\" class=\"optionsListContent\" style=\"display:none;\">
                                <ul>
                                    <a onClick='startTicketEdit()'><li>Edit</li></a>
                                    <a  onClick=confirmDelete(\"ticketDelete.php?ticket=$TID\")><li>Delete</li></a>
                                    <a onClick='startAssign()'><li>Assign User</li></a>
                                </ul>
                            </div>"
                            ?>
                        </div>
                    </div>
                    <?php 
                    echo "<h1>".$ticket['TITLE']."</h1>";

                     echo "<p>".$ticket['CONTENT']."</p>";
                    echo "<div id=\"ticketInfo\">Views:" . $ticket['VIEWS'] . " Status: ".translateStatus($ticket["STATUS"]). " Priority:".translateDiff($ticket["PRIORITY"])." Difficulty:".translateDiff($ticket["DIFFICULTY"])."</div>"
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
                        <img id=controlIcon src=reply.png alt=options width=25px height=25px>
                        </a>
                        
                        <a onClick='showReplyBox(\"options$CID\")'>
                            <img id=controlIcon src=options.png alt=options width=25px height=25px>
                            </a>
                            <div id=\"options$CID\" class=\"optionsListContent\" style=\"display:none;\">
                                <ul>
                                    <a onClick='startEdit($CID,\"".  $comment['CONTENT'] . "\")'><li>Edit</li></a>
                                    <a  onClick=confirmDelete(\"commentDelete.php?comment=$CID\")><li>Delete</li></a>
                                </ul>
                            </div>
                            
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
                                        <img onclick='' id=controlIcon src=reply.png alt=cog width=25px height=25px>
                                        </a>
                                        <a onClick='showReplyBox(\"options$RID\")'>
                                        <img id=controlIcon src=options.png alt=options width=25px height=25px>
                                        </a>
                                        <div id=\"options$RID\" class=\"optionsListContent\" style=\"display:none;\">
                                            <ul>
                                                <a onClick='startEdit($RID,\"".  $replySearch['CONTENT'] . "\")'><li>Edit</li></a>
                                                <a  onClick=confirmDelete(\"commentDelete.php?comment=$RID\")><li>Delete</li></a>
                                            </ul>
                                        </div>
                                    </div>
                                    <p> " . $replySearch['CONTENT'] . " </p>

                                    <div id=\"reply" . $RID . "\" class=replyBox style=\"display:none; \">
                                    <p>Replying to " . $commentUser['UNAME'] . " </p>
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
        <div id="editPopup" class="popupBG" style="display:none;">
            <span class="helper"></span>
            <div class="popupEdit">
                <h2>Editing comment</h2>
                <form method="post" action="commentEdit.php">
                    <input id="formCID" type="hidden" name="comment" value="">
                    <textarea id="editingText" name="text" rows=10 cols=50></textarea><br>
                        <input type=submit>
                </form><br>
                <a>
                <button onClick=cancelEdit() >Cancel</button>
                </a>
            </div>
        </div>
        
        <div id="assignPopup" class="popupBG" style="display:none;">
            <span class="helper"></span>
            <div class="popupEdit">
                <h2>Assigning User</h2>
                <form method="post" action="assignTicket.php">
                    <input id="TID" type="hidden" name="TID" value="<?php echo $TID; ?>">
                    <select name="UID">
                        <option value="0">No one</option>
                        <?php 
                        $PID = $ticket['PID'];
                        $candidates = $mysqli->query("SELECT * FROM `user-project` WHERE `PID` = $PID AND `rank` >= 1");
                        while ( $candidate = $candidates->fetch_assoc() ) 
                        {
                            
                            echo "<option value='".$candidate["UID"]."'>".getUserNameFromID($candidate["UID"])." </option>";
                        }
                        ?>
                    
                    </select>
                    
                        <input type=submit>
                </form><br>
                <button onClick=cancelAssign()>Cancel</button>

            </div>
        </div>
        
        <div id="ticketEditPopup" class="popupBG" style="display:none;">
            <span class="helper"></span>
            <div class="popupEdit">
                <h2>Editing ticket</h2>
                <form method="post" action="ticketEdit.php">
                    <input id="formTID" type="hidden" name="TID" value="<?php echo $TID; ?>">
                    <textarea id="ticketEditingText" name="text" rows=10 cols=50><?php echo $ticket["CONTENT"] ?></textarea><br>
                        <input type=submit>
                </form><br>
                <a>
                <button onClick=cancelTicketEdit() >Cancel</button>
                </a>
            </div>
        </div>

    </main>
    <?php 
    
    //calling our footer
    chdir("..");
    include_once("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
    <script>
        var editPopup = document.getElementById("editPopup");
        var assignPopup = document.getElementById("assignPopup");
        var ticketEditPopup = document.getElementById("ticketEditPopup");
    function start() {
        var editPopup = document.getElementById("editPopup");
        var assignPopup = document.getElementById("assignPopup");
        var ticketEditPopup = document.getElementById("ticketEditPopup");
        }
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
    function confirmDelete (deleteURL)
        {
         if (confirm("Are you sure you want to delete this?")) 
         {
             window.location.href= deleteURL;
         }   
        }
    function startEdit(CID, current) 
        {
            var editTextArea = document.getElementById("editingText");
            editTextArea.value = current;
            var editCID = document.getElementById("formCID");
            editCID.value = CID;
            editPopup.style.display = "block";
        }
    function cancelEdit()  
        {
            editPopup.style.display = "none";
            ticketToEdit="";
            var editTextArea = document.getElementById("editingText");
            editTextArea.value = "";
        }
    function startTicketEdit() 
        {
            var ticketEditPopup = document.getElementById("ticketEditPopup");
            ticketEditPopup.style.display = "block";
        }
    function cancelTicketEdit()  
        {
            var ticketEditPopup = document.getElementById("ticketEditPopup");
            ticketEditPopup.style.display = "none";
        }
    function startAssign() 
        {
            var assignPopup = document.getElementById("assignPopup");
            assignPopup.style.display = "block";
        }
    function cancelAssign()  
        {
            var assignPopup = document.getElementById("assignPopup");
            assignPopup.style.display = "none";
        }
    </script>
</body>

</html>
