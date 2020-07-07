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
    $UID = $_SESSION["UID"];
    // Getting the project id from the URL
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
    $rank = $userProj->fetch_assoc()["rank"];
    $isAdmin = 0;
    $isOwner =0;
    if ($rank >= 1) {
        $isAdmin = 1;
        if ($rank==3) {
            $isOwner=1;
        }
    }
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
                    <p> <?php echo $desc; ?></p><br>
                    <?php 
                 if ($userProj->num_rows >= 1) 
                    {
                        echo "<button onClick=\"leaveProject()\">Leave</button>";
                    }
                    else 
                    {
                        echo "<button onClick=\"joinProject()\">Join</button>";
                    }
                if ($isOwner==1) 
                {
                    echo " <button onClick=\"startDelete()\">Delete Project </button>";
                }
                if ($isAdmin==1) 
                {
                    echo " <button onClick=\"startUpdate()\"> Create Update</button><br>";
                    echo " <button onClick=\"startProjectEdit()\"> Edit Project</button>";
                }
                ?>
                </div>
                <ul id="reportsList">
                    <a onClick="tabsShow(0)"><li>Updates</li></a>
                    <?php 
                        if ($isAdmin==1) 
                        {
                            echo "
                            <a onClick=\"tabsShow(1)\"><li>Users</li></a>
                    <a onClick=\"tabsShow(2)\"><li>Tickets</li></a>
                            ";
                        }
                    ?>
                    
                </ul>
                <div id=projectTabs>
                <div id="updates" class="" style="display:inherit;">
                    <?php 
                    $updatesQuery = "SELECT * FROM `project-updates` WHERE `PID` = $ID ORDER BY `UPDATE_ID` DESC";
                    $updatesArray = $mysqli->query($updatesQuery);
                    if ($updatesArray->num_rows >= 1) 
                    {
                        $i = 5;
                        //If there isnt at least 5 rows we'll just fetch the ones that exist
                        if ($updatesArray->num_rows < $i) 
                        {
                            $i = $updatesArray->num_rows;
                        }
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
                        echo "<h2>There's no updates to display.</h2>";
                    }
                ?>
                    
                </div>
                <div id="users" class="projectTab" style="display:none;">
                    <h1>Users</h1>
                                        <?php 
                     $projUsers = getProjectUsers($ID);
                    echo "<table style=\"display:inherit;\" class=reports id=users".$ID." >
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Rank</th>
                        </tr>";
                    foreach ($projUsers as &$user) 
                    {
                        echo "<tr>
                            <th>".getUserNameFromID($user["UID"])."</th>
                            <th>".getUserEmailFromID($user["UID"])."</th>
                            <th>".translateRank($user["rank"])."</th>
                        </tr>";
                    }
                     echo "</table><br>
                     <button onClick=\"startUsers()\">Manage Users</button>
                     <h1>Invites</h1><br>";
                    $projectInvites = $mysqli->query("SELECT * FROM `project-invite` WHERE `PID` = " . $ID)->fetch_all(MYSQLI_ASSOC);
                   if (count($projectInvites) > 0 ) {
                        echo "<table style=\"display:inherit;\" class=reports id=invites".$ID." >
                            <tr>
                                <th>URL</th>
                                <th>Uses</th>
                                <th>Rank</th>
                            </tr>";
                    foreach ($projectInvites as &$invite) 
                       {
                        echo "<tr>
                                <a href=\"http://localhost/BugTrakt/tracker/projectInvite.php?id=". $invite["INVITE_ID"]."\"><th>projectInvite.php?id=".$invite["INVITE_ID"]."</th></a>
                                <th>".$invite["USES"]."/".$invite["MAX_USES"]."</th>
                                <th>".translateRank($invite["RANK"])."</th>
                            </tr>";

                       }
                           echo "</table>";
                   }
                    else 
                    {
                        echo "There's no invites to display";
                    }
                    ?>
                     <a href="inviteCreate.php"><p>Create Invite</p></a>
                    
                </div>
                <div id="stats" class="projectTab" style="display:none;">
                    
                <?php 
                    if ($isAdmin==1) 
                    {
                    $projectID = $ID;
                    $tableOne = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = $projectID AND `STATUS` = 0 ORDER BY `PRIORITY` DESC");
                    $tableTwo = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = $projectID AND `STATUS` = 0 ORDER BY `DIFFICULTY` DESC");
                    $tableThree = $mysqli->query("SELECT * FROM `ticket` WHERE `ASSIGNED_ID` != 0 AND `STATUS` = 1 AND `PID` = $projectID ORDER BY `ASSIGNED_ID` ASC");
                    $tableFour = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = $projectID ORDER BY `TID` ASC");
                    
                    echo "<div id=\"reportsContainer\">
                    <ul id=\"reportsList\">
                        <li id=\"openBugsP\" onClick=\"statsShow(0)\">Open bugs by Priority</li>
                        <li id=\"openBugsS\" onClick=\"statsShow(1)\">Open bugs by Severity</li>
                        <li id=\"bugsByAssigned\" onClick=\"statsShow(2)\">Bugs by Assignment</li>
                        <li id=\"allBugs\" onClick=\"statsShow(3)\">All Bugs</li>
                    </ul>
                    <table class=reports id=\"bugsP\">
                        <tr>
                            <th>Ticket ID</th>
                            <th>Creator</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Difficulty</th>
                            <th>Created</th>
                            <th>Last Updated</th>
                        </tr>
                    ";
                    while($tickets = $tableOne->fetch_assoc()) 
                    {
                        $TID = $tickets["TID"];
                        $creator = getUserNameFromID($tickets["UID"]);
                        $title = $tickets["TITLE"];
                        $priority = translateDiff($tickets["PRIORITY"]);
                        $difficulty = translateDiff($tickets["DIFFICULTY"]);
                        $created = $tickets["CREATED"];
                        $lastUpdated = $tickets["LAST_UPDATED"];
                        echo "<tr>
                            <th>$TID</th>
                            <th>$creator</th>
                            <th>$title</th>
                            <th>$priority</th>
                            <th>$difficulty</th>
                            <th>$created</th>
                            <th>$lastUpdated</th>
                        </tr>";
                    }
                    echo "</table>
                    <table class=reports id=\"bugsS\">
                        <tr>
                            <th>Ticket ID</th>
                            <th>Creator</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Difficulty</th>
                            <th>Created</th>
                            <th>Last Updated</th>
                        </tr>";
                    while($tickets = $tableTwo->fetch_assoc()) 
                    {
                        $TID = $tickets["TID"];
                        $creator = getUserNameFromID($tickets["UID"]);
                        $title = $tickets["TITLE"];
                        $priority = translateDiff($tickets["PRIORITY"]);
                        $difficulty = translateDiff($tickets["DIFFICULTY"]);
                        $created = $tickets["CREATED"];
                        $lastUpdated = $tickets["LAST_UPDATED"];
                        echo "<tr>
                            <th>$TID</th>
                            <th>$creator</th>
                            <th>$title</th>
                            <th>$priority</th>
                            <th>$difficulty</th>
                            <th>$created</th>
                            <th>$lastUpdated</th>
                        </tr>";
                    }
                    echo "</table>
                    <table class=reports id=\"bugsAssigned\">
                        <tr>
                            <th>Ticket ID</th>
                            <th>Creator</th>
                            <th>Assigned</th>
                            <th>Status</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Difficulty</th>
                            <th>Created</th>
                            <th>Last Updated</th>
                        </tr>";
                    while($tickets = $tableThree->fetch_assoc()) 
                    {
                        $TID = $tickets["TID"];
                        $creator = getUserNameFromID($tickets["UID"]);
                        $assigned = getUserNameFromID($tickets["ASSIGNED_ID"]);
                        $status = translateStatus($tickets["STATUS"]);
                        $title = $tickets["TITLE"];
                        $priority = translateDiff($tickets["PRIORITY"]);
                        $difficulty = translateDiff($tickets["DIFFICULTY"]);
                        $created = $tickets["CREATED"];
                        $lastUpdated = $tickets["LAST_UPDATED"];
                        echo "<tr>
                            <th>$TID</th>
                            <th>$creator</th>
                            <th>$assigned</th>
                            <th>$status</th>
                            <th>$title</th>
                            <th>$priority</th>
                            <th>$difficulty</th>
                            <th>$created</th>
                            <th>$lastUpdated</th>
                        </tr>";
                    }
                    echo "</table>
                    <table class=reports id=\"bugsAll\">
                        <tr>
                            <th>Ticket ID</th>
                            <th>Creator</th>
                            <th>Assigned</th>
                            <th>Status</th>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Difficulty</th>
                            <th>Created</th>
                            <th>Last Updated</th>
                            <th>Closed</th>
                        </tr>";
                    
                    while($tickets = $tableFour->fetch_assoc()) 
                    {
                        $TID = $tickets["TID"];
                        $creator = getUserNameFromID($tickets["UID"]);
                        $assigned = getUserNameFromID($tickets["ASSIGNED_ID"]);
                        $status = translateStatus($tickets["STATUS"]);
                        $title = $tickets["TITLE"];
                        $priority = translateDiff($tickets["PRIORITY"]);
                        $difficulty = translateDiff($tickets["DIFFICULTY"]);
                        $created = $tickets["CREATED"];
                        $lastUpdated = $tickets["LAST_UPDATED"];
                        $closed = $tickets["CLOSED"];
                        echo "<tr>
                            <th>$TID</th>
                            <th>$creator</th>
                            <th>$assigned</th>
                            <th>$status</th>
                            <th>$title</th>
                            <th>$priority</th>
                            <th>$difficulty</th>
                            <th>$created</th>
                            <th>$lastUpdated</th>
                            <th>$closed</th>
                        </tr>";
                    }
                    echo "</table>";
                    
                    if ($tableFour->num_rows == 0) 
                    {
                        echo "<h2>This project has no rows.</h2>";
                    }
                }
                ?>
                </div>
                </div>
            </div>
        </div>
    </main>
    <div id="projectEditPopup" class="popupBG" style="display:none;">
            <span class="helper"></span>
            <div class="popupEdit">
                <h2>Editing Project</h2>
                <form method="post" action="scripts/projectEdit.php">
                    <input id="formTID" type="hidden" name="PID" value="<?php echo $ID; ?>">
                    <textarea id="ticketEditingText" name="text" rows=10 cols=50><?php echo $desc; ?></textarea><br>
                        <input type=submit>
                </form><br>
                <a>
                <button onClick=cancelProjectEdit() >Cancel</button>
                </a>
            </div>
        </div>
    <div id="updatePopup" class="popupBG" style="display:none;">
            <span class="helper"></span>
            <div class="popupEdit">
                <h2>Creating Update</h2>
                <form method="post" action="scripts/updateCreate.php">
                    <input id="formTID" type="hidden" name="PID" value="<?php echo $ID; ?>">
                    Title:<input name="title" type="text"><br>
                    Text:<textarea id="ticketEditingText" name="text" rows=10 cols=50></textarea><br>
                        <input type=submit>
                </form><br>
                <a>
                <button onClick=cancelUpdate() >Cancel</button>
                </a>
            </div>
        </div>
     <div id="deletePopup" class="popupBG" style="display:none;">
            <span class="helper"></span>
            <div class="popupEdit">
                <h2>Are you sure you want to delete this project?</h2>
                <form method="post" action="scripts/projectDelete.php">
                    <input id="formTID" type="hidden" name="PID" value="<?php echo $ID; ?>">
                        <input type=submit>
                </form><br>
                <a>
                <button onClick=cancelDelete() >Cancel</button>
                </a>
            </div>
        </div>
         <div id="usersPopup" class="popupBG" style="display:none;">
            <span class="helper"></span>
            <div class="popupEdit">
                <h2>What would you like to do?</h2>
                <form method="post" action="scripts/userManage.php">
                    <input type="hidden" name="PID" value="<?php echo $ID; ?>">
                    User: <select id="active" name="user">
                    <?php 
                        $users = getProjectUsers($ID);
                        foreach ($users as &$user) 
                        {
                            $userId = $user["UID"];
                            $userName = getUserNameFromID($userId);
                            echo "<option value=\"$userId\"> $userName </option>";
                        }
                    ?>
                    </select>
                    Action: <select id="active" name="action">
                        <option value="-1">Remove User</option>
                        <option value="0">Make QA</option>
                        <option value="1">Make Developer</option>
                        <option value="2">Make Admin</option>
                        <option value="3">Make Owner</option>
                </select>
                        <input type=submit>
                </form><br>
                <a>
                <button onClick=cancelUsers() >Cancel</button>
                </a>
            </div>
        </div>
    
    <?php 
    
    //calling our footer
    chdir("..");
    include_once("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
    <script>
        var editPopup = document.getElementById("projectEditPopup");
        var updatePopup = document.getElementById("updatePopup");
        var deletePopup = document.getElementById("deletePopup");
        
    function startProjectEdit() 
        {
            var editPopup = document.getElementById("projectEditPopup");
            editPopup.style.display = "block";
        }
    function cancelProjectEdit()  
        {
            var editPopup = document.getElementById("projectEditPopup");
            editPopup.style.display = "none";
        }
    function startUpdate() 
        {
            var updatePopup = document.getElementById("updatePopup");
            updatePopup.style.display = "block";
        }
    function cancelUpdate()  
        {
            var updatePopup = document.getElementById("updatePopup");
            updatePopup.style.display = "none";
        }
        
    function startDelete() 
        {
            var deletePopup = document.getElementById("deletePopup");
            deletePopup.style.display = "block";
        }
    function cancelDelete()  
        {
            var deletePopup = document.getElementById("deletePopup");
            deletePopup.style.display = "none";
        }
        
    function startUsers() 
        {
            var usersPopup = document.getElementById("usersPopup");
            usersPopup.style.display = "block";
        }
    function cancelUsers()  
        {
            var usersPopup = document.getElementById("usersPopup");
            usersPopup.style.display = "none";
        }
        
    function joinProject() 
        {
            window.location.href= "scripts/projectJoin.php?pid=<?php echo $ID ?>";
        }
        function leaveProject() 
        {
            window.location.href= "scripts/projectLeave.php?pid=<?php echo $ID ?>";
        }
        
        var statsArr = [
        document.getElementById("bugsP"),
        document.getElementById("bugsS"),
        document.getElementById("bugsAssigned"),
        document.getElementById("bugsAll")];
        
        var tabsArr = [
            document.getElementById("updates"),
            document.getElementById("users"),
            document.getElementById("stats")
        ];
        
        statsShow(0);
        function hide(item) 
        {
            item.style.display = "none";
        }
        function tabsHideAll() 
        {
            tabsArr.forEach(hide);
        }
        function tabsShow(num) 
        {
            tabsHideAll();
            tabsArr[num].style.display="block";
        }
        function statsHideAll() 
        {
            statsArr.forEach(hide);
        }
        
        function statsShow(num) 
        {
            statsHideAll();
            statsArr[num].style.display="block";
        }
    </script>
</body>

</html>
