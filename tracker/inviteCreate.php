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
    include_once("head.php");
    ?>
    
	<main>
        <?php
        include_once("mysqlConfig.php");
        session_start();
        
        $PID=0;
        $uses=0;
        $rank=0;
        $usesError="";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            
            if (isset($_POST["PID"]) && isset($_POST["uses"]) && isset($_POST["rank"])) 
            {
                $PID = $_POST["PID"];
                $uses = (int) $_POST["uses"];
                $rank = $_POST["rank"];
            }
            if ($uses < 0) 
            {
                $usesError="Uses cannot be negative.";
            }
        
            if ($usesError == "" && $PID>0) 
            {
                $UID = $_SESSION["UID"];
                $query = "INSERT INTO `project-invite` (`INVITE_ID`, `PID`, `RANK`, `USES`, `MAX_USES`) VALUES (NULL, '$PID', '$rank', '', '$uses');";
                if ($execute = $mysqli->prepare($query)) 
                {
                    //Execute our executable
                    $execute->execute();
                    //redirect to the new ticket
                    header("location: admin/users.php");
                } 
            }
            
        }
        ?>
        <div id="centeredBody">
            <h1>New invite</h1>
            <form method="post" action=""> 
                
                Project: <?php 
                $userProjects = getUserAdminProjects($_SESSION["UID"]);
                echo "<select id=\"projectSelect\" name=\"PID\">";
                foreach ($userProjects as &$proj) 
                {
                    $projName = getProjectName($proj["PID"]);
                    if ($projName!=null) 
                    {
                        echo "<option value=".$proj["PID"].">$projName</option>";
                    }
                }
                echo "</select>"?>
                <?php if ($usesError != "")  {echo "<p id=warning><spawn id=warning>" .$usesError . "</span></p>";}?>
                <p>Max uses:</p><input type="number" name="uses" value="<?php echo $uses; ?>"><br>
                Rank:<select id="" name="rank">
                <option value=0>QA</option>
                <option value=1>Developer</option>
                <option value=2>Admin</option>
                </select><br>
                <input type=submit>
            </form>
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