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
    ?>
    
	<main>
        <?php
        chdir("admin");
        echo file_get_contents("adminBar.php");
        chdir("..");
        session_start();
        require_once("mysqlConfig.php");
        $userProjects = getUserAdminProjects($_SESSION["UID"]);
        ?>
        <div id="adminRight">
            <h1>User Management</h1>
            
            Project: <?php 
                echo "<form id=selectForm><select onclick=updateSelection() id=\"projectSelect\" name=\"project\">";
                foreach ($userProjects as &$proj) 
                {
                    $projName = getProjectName($proj["PID"]);
                    if ($projName!=null) 
                    {
                        echo "<option onclick=updateSelection() value=".$proj["PID"].">$projName</option>";
                    }
                }
                echo "</select></form>";
            //Making multiple for loops to make sure that we dont accidentally mess up styling
                foreach ($userProjects as &$proj) 
                {
                    $projUsers = getProjectUsers($proj["PID"]);
                    echo "<table style=\"display:none;\" class=reports id=users".$proj["PID"]." >
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
                
                echo "</table>";
                    
                }
            
                // INSERT INTO `project-invite` (`INVITE_ID`, `PID`, `RANK`, `USES`, `MAX_USES`) VALUES (NULL, '1', '2', '', '20');
            
            //SELECT * FROM `project-invite` WHERE `PID` = 1
                
                ?>
            
            <h1>Invites</h1>
            
            <?php 
               foreach ($userProjects as &$proj) 
                {
                   $projectInvites = $mysqli->query("SELECT * FROM `project-invite` WHERE `PID` = " . $proj["PID"])->fetch_all(MYSQLI_ASSOC);
                   if (count($projectInvites) > 0 ) {
                   echo "<table style=\"display:none;\" class=reports id=invites".$proj["PID"]." >
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
                       echo  "<div id=invites".$proj["PID"]." style=\"display:none;\">".getProjectName($proj["PID"]) . " has no invites.</div>";
                   }
               }
            ?>
            <a href="../inviteCreate.php"><p>Create Invite</p></a>
        </div>
    </main>
    <?php 
    //calling our footer
    chdir("..");
    echo file_get_contents("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
    <script>
        var projSelection = document.getElementById("projectSelect");
        var selectionValue = projSelection.value;
        var selectString = 'users' + selectionValue.toString();
        var inviteString = 'invites' + selectionValue.toString();
        console.log(inviteString);
        var projectTable= document.getElementById(selectString);
        var inviteTable= document.getElementById(inviteString);
        projectTable.style.display="inherit";
        inviteTable.style.display="inherit";
        
        function updateSelection() 
        {
            console.log(inviteString);
            projectTable.style.display="none";
            inviteTable.style.display="none";
            projSelection = document.getElementById("projectSelect");
            selectionValue = projSelection.value;
            selectString = 'users' + selectionValue.toString();
            inviteString = 'invites' + selectionValue.toString();
            projectTable= document.getElementById(selectString);
            inviteTable= document.getElementById(inviteString);
            projectTable.style.display="inherit";
            inviteTable.style.display="inherit";
        }
    </script>
</body>

</html>