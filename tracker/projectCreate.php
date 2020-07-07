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
        
        
        $name="";
        $desc="";
        $public=0;
        $rank=0;
        $UID=$_SESSION["UID"];
        $errors="";
        
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            
            if (isset($_POST["name"]) && isset($_POST["desc"]) && isset($_POST["rank"]) &&isset($_POST["public"]))
            {
                
                $name=$_POST["name"];
                $desc=$_POST["desc"];
                $public=$_POST["public"];
                $rank=$_POST["rank"];
            }
                if ($name=="") 
                {
                    $errors .= "Please write a name. ";
                }
                if ($desc=="") 
                {
                    $errors.= "Please write a description. ";
                }
                
            if ($errors == "" && $name!="") 
            {
                $query = "INSERT INTO `projects` (`ID`, `PNAME`, `DESCRIPTION`, `isPublic`, `isAutoAssign`, `DEFAULT_RANK`, `ownerID`, `subscribedDate`, `userLimit`) VALUES (NULL, '$name', '$desc', '$public', '0', '$rank', '$UID', '', '');";
                if ($execute = $mysqli->prepare($query)) 
                {
                    //Execute our executable
                    $execute->execute();
                    $PID = $execute->insert_id;
                    //Add the users permisisons - project relation
                    $mysqli->query("INSERT INTO `user-project` (`ID`, `UID`, `PID`, `rank`) VALUES (NULL, '$UID', '$PID', '3');");
                    //redirect to the new ticket
                    header("location: project.php?id=$PID");
                } 
            }
            
        }
        ?>
        <div id="centeredBody">
            <h1>New Project</h1>
            <form method="post" action=""> 
                <?php if ($errors != "") {echo $errors;} ?>
                Name: <input type="text" name="name" value="<?php echo $name; ?>"><br>
                Description: <textarea name="desc" rows=1 cols=30><?php echo $desc; ?></textarea><br>
                Public: <input type="checkbox" name="public" value="1"><br>
                Default rank:<select id="" name="rank">
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
    include_once("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
</body>

</html>