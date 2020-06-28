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
    echo file_get_contents("head.php");
    require_once "mysqlConfig.php";
    session_start();
    $UID = $_SESSION["UID"];
    // Getting the ticket id from the URL
    $ID = $_GET["id"];
   
    //Creating a string to hold the query
    //$queryString = ;
    //Testing the query while executing it
    if (!$result = $mysqli->query("SELECT * FROM `projects` WHERE `ID` = $ID")) 
    {
        echo "<p>Could not communicate with the database</p>";
        exit;
                        
    }
    //Checking for a result
    if ($result->num_rows === 0) {
                        echo "<p> No ticket found.</p>";
                        exit;
    }
    
    $resultArr = $result->fetch_assoc();
    
    $title = $resultArr["PNAME"];
    
    $owner = getUserNameFromID($resultArr["ownerID"]);
    $desc = $resultArr["DESCRIPTION"];
    $public = $resultArr["isPublic"];
    $isAuth = false;
    //Testing to see if the user should be able to view the project
    $userProjQuery = "SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $ID";
    $userProj = $mysqli->query($userProjQuery);
    
    if ($public == 0) 
    {
        if (!($userProj->num_rows >= 1)) 
        {
            echo "User failed auth test";
            exit;
        }
        else 
        {
            $isAuth=true;
        }
    }
    else 
    {
        $isAuth=true;
    }
    
    ?>
    
    
	<main>
        <div id="centeredBodyQ">
            <div id="ticketBody">
                <div id="ticketItemContainer">
                    <div id="ticketMeta">
                        <div id=ticketBorderLeft></div>
                        <?php 
                             echo "<h1>" . $title . " </h1>";
                            ?>
                        
                            <?php 
                                echo "<p>Owned by:" . $owner . "</p>"
                            ?>
                       
                    </div>
                    <p> <?php echo $desc; ?></p>
                </div>
                <?php 
                 if ($userProj->num_rows >= 1) 
                    {
                        echo "<button onClick=\"leaveProject()\">Leave</button>";
                    }
                else 
                {
                    echo "<button onClick=\"joinProject()\">Join</button>";
                }
                ?>
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
                    
                    </select>
                    
                        <input type=submit>
                </form><br>
                <button onClick=cancelAssign()>Cancel</button>

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
        var editPopup = document.getElementById("editPopup");
        var assignPopup = document.getElementById("assignPopup");
    function start() {
        var editPopup = document.getElementById("editPopup");
        var assignPopup = document.getElementById("assignPopup");
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
    function joinProject() 
        {
            window.location.href= "projectJoin.php?pid=<?php echo $ID ?>";
        }
        function leaveProject() 
        {
            window.location.href= "projectLeave.php?pid=<?php echo $ID ?>";
        }
    </script>
</body>

</html>
