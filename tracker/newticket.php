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
        
        $TITLE="";
        $DESCRIPTION="";
        $PRIORITY=0;
        $DIFFICULTY=0;
        $TitleError=""; $DescriptionError="";
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $TITLE=trim($_POST["title"]);
            $DESCRIPTION=trim($_POST["desc"]);
            if ($TITLE == "") 
            {
                $TitleError = "No Title provided";
            }
            
            if ($DESCRIPTION == "") 
            {
                $DescriptionError = "No Description Provided";
            }
            
            if ($_POST["priority"] == "med") 
            {
                $PRIORITY = 1;
            }
            else if ($_POST["priority"] == "high") 
            {
                $PRIORITY = 2;
            }
            
            if ($_POST["difficulty"] == "med") 
            {
                $DIFFICULTY = 1;
            }
            else if ($_POST["difficulty"] == "high") 
            {
                $DIFFICULTY = 2;
            }
            if ($TitleError == "" && $DescriptionError == "") 
            {
                $UID = $_SESSION["UID"];
                $query = "INSERT INTO `ticket` (`TID`, `UID`, `PID`, `CONTENT`, `VIEWS`, `PRIORITY`, `DIFFICULTY`, `UPDATES`, `LAST_UPDATED`, `STATUS`, `CREATED`, `CLOSED`, `TITLE`) VALUES (NULL, '$UID', '1', '$DESCRIPTION', '1', '$PRIORITY', '$DIFFICULTY', '1', CURRENT_TIMESTAMP, 'OPEN', CURRENT_TIMESTAMP, NULL, '$TITLE');";
                if ($execute = $mysqli->prepare($query)) 
                {
                    //Execute our executable
                    $execute->execute();
                    //get the id
                    $id = $execute->insert_id;
                    //redirect to the new ticket
                    header("location: ticket.php?tid=$id");
                } 
            }
            
        }
        ?>
        <div id="centeredBody">
            <h1>New ticket</h1>
            <form method="post" action=""> 
                <?php if ($TitleError != "")  {echo "<p id=warning><spawn id=warning>" .$TitleError . "</span></p>";}?>
                Title:<input type="text" name="title" value="<?php echo $TITLE; ?>"><br>
                Priority:<select id="priority" name="priority">
                <option value="med">Medium</option>
                <option value="low">Low</option>
                <option value="high">High</option>
                </select><br>
                Difficulty: <select id="difficulty" name="difficulty">
                <option value="med">Medium</option>
                <option value="low">Low</option>
                <option value="high">High</option>
                </select><br>
                <?php if ($DescriptionError != "")  {echo "<p id=warning><spawn id=warning>" .$DescriptionError . "</span></p>";}?>
                Description:<textarea name="desc" rows=1 cols=30><?php echo $DESCRIPTION; ?></textarea><br>
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