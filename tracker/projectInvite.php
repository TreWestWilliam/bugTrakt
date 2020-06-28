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
        <?php
        include_once("mysqlConfig.php");
        session_start();
        
        $PID=0;
        $uses=0;
        $maxUses=0;
        $rank=-1;
        $incrementUses=0;
        $UID = $_SESSION["UID"];
        $INVITE = $_GET["ID"];
        $isInAlready = false;
        
        $invites  = $mysqli->query("SELECT * FROM `project-invite` WHERE `INVITE_ID` = $INVITE");
        if ($invites->num_rows > 0) 
        {
            $invite = $invites->fetch_assoc();
            if ($invite["MAX_USES"]==0) 
            {
                $PID = $invite["PID"];
                $rank = $invite["RANK"];
            }
            else if ($invite["USES"]<$invite["MAX_USES"]) 
            {
                $PID = $invite["PID"];
                $rank = $invite["RANK"];
                $incrementUses=1;
            }
            
        }
        $PNAME = getProjectName($PID);
        
        if ($mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID AND `PID` = $PID")->num_rows > 0) 
        {
            $isInAlready=true;
        }
        
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            
            if (isset($_POST["PID"]) && isset($_POST["rank"]) && $uses<=$maxUses) 
            {
                $PID = $_POST["PID"];
                $rank = $_POST["rank"];
            }
        
            if ($PID>0 && $rank!=-1 && $isInAlready==false) 
            {
                if (incrementUses==1) 
                {
                    $mysqli->query("UPDATE `project-invite` SET `USES` = `USES` + 1 WHERE `project-invite`.`INVITE_ID` = $INVITE;");
                }
                $query = "INSERT INTO `user-project` (`ID`, `UID`, `PID`, `rank`) VALUES (NULL, '$UID', '$PID', '$rank');";
                if ($execute = $mysqli->prepare($query)) 
                {
                    //Execute our executable
                    $execute->execute();
                    //redirect to the new ticket
                    header("location: ticketlist.php");
                } 
            }
            
        }
        ?>
        <div id="centeredBody">
            <?php
            echo "<h1>Would you like to join $PNAME ?</h1>"; 
            ?>
            <form method="post" action=""> 
                <?php 
                    if (!$isInAlready) 
                    {
                        echo "<input type=submit>";
                    }
                else 
                {
                    echo "You are already in $PNAME";
                }
                ?>
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