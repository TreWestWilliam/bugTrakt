<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<title>BugTrakt Tracker</title>
	<meta name="author" content="William West">
	<meta name="description" content="A simple web bug tracker">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="adminStyle.css">
	<link rel="icon" type="image/x-icon" href=""/>
</head>

<body>
<?php 
    //calling our header
        chdir("..");
    echo file_get_contents("head.php");
    session_start();
    ?>
    
	<main>
        <?php
        chdir("admin");
        echo file_get_contents("adminBar.php");
        require_once"../mysqlConfig.php";
        chdir("..");
        ?>
        <div id="adminRight">
        <h1>Statistics</h1>
            <h2>Your Statistics</h2>
            <h2>Project Statistics</h2>
            <div>
                <?php
                $projectID=0;
                $UID = $_SESSION["UID"];
                $query = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID");
                if ($query->num_rows >= 1) 
                {
                    echo"<form method=post action=\"\">";
                    echo " <select name=projectSelect>";
                    while ($project = $query->fetch_assoc()) 
                    {
                        $PID = $project["PID"];
                        $projectID=$PID;
                        $projQuery = $mysqli->query("SELECT * FROM `projects` WHERE `ID` = $PID");
                        $project = $projQuery->fetch_assoc();
                        $projName = $project["PNAME"];
                        echo "<option value=$PID>$projName</option>";  
                    }
                    echo "<input type=submit></select></form>";
                }
                else 
                {
                    echo "<h3>No projects found</h3>";
                } 
                if ($_SERVER["REQUEST_METHOD"] == "POST") 
                {
                    $projectID = $_POST["projectSelect"];
                }
                
                if ($projectID != 0) 
                {
                    echo "<div id=\"reportsContainer\">
                    <ul>
                        <li id=\"openBugsP\" onClick=\"show(0)\">Open bugs by Priority</li>
                        <li id=\"openBugsS\" onClick=\"show(1)\">Open bugs by Severity</li>
                        <li id=\"bugsByAssigned\" onClick=\"show(2)\">Bugs by Assignment</li>
                        <li id=\"allBugs\" onClick=\"show(3)\">All Bugs</li>
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
                    $tableOne = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = $PID AND `STATUS` = 0 ORDER BY `PRIORITY` DESC");
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
                    $tableTwo = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = $PID AND `STATUS` = 0 ORDER BY `DIFFICULTY` DESC");
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
                    $tableThree = $mysqli->query("SELECT * FROM `ticket` WHERE `ASSIGNED_ID` != 0 AND `STATUS` = 1 AND `PID` = $PID ORDER BY `ASSIGNED_ID` ASC");
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
                    $tableFour = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = $PID ORDER BY `TID` ASC");
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
    <script>
        var arr = [
        document.getElementById("bugsP"),
        document.getElementById("bugsS"),
        document.getElementById("bugsAssigned"),
        document.getElementById("bugsAll")]
        
        show(0);
        function hideAll() 
        {
            arr.forEach(hide);
        }
        function hide(item) 
        {
            item.style.display = "none";
        }
        function show(num) 
        {
            hideAll();
            arr[num].style.display="block";
        }
    </script>
</body>

</html>