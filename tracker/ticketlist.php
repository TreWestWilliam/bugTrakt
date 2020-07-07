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
    
    function drawTicket($ticket) 
    {
        $TicketID = $ticket["TID"];
        $TicketName = $ticket["TITLE"];
        $CreatedUserName=getUserNameFromID($ticket["UID"]);
        $dateTimeCreated=$ticket["CREATED"];
        $DIFFICULTY =$ticket["DIFFICULTY"];
        $PRIORITY = $ticket["PRIORITY"];
        $ASSIGNED = getUserNameFromID($ticket["ASSIGNED_ID"]);
        $STATUS = translateStatus($ticket["STATUS"]);
                    
        $PriorityText = translateDiff($PRIORITY);
        $DifficultyText = translateDiff($DIFFICULTY);
                    
        echo "<a href=ticket.php?tid=$TicketID><div id=yourTicket>
                <h2>$TicketID | $TicketName</h2>
                    <p>Created by: $CreatedUserName Created on:$dateTimeCreated</p>
                    <p>Assigned to:$ASSIGNED Difficulty: $DifficultyText Priority: $PriorityText</p>
                    <p>Status: $STATUS </p>
                </div></a>";
    }
    
    
    
    ?>
    
	<main>
        <div id="centeredBody">
            <h1 id="alignCenter">Tickets</h1>
            <p>Fitler/Search:</p>
            <form method="post" action="">
            Search: <input type="text" name="search"> <br>

                <?php
                require_once"mysqlConfig.php";
                session_start();
                $UID = $_SESSION["UID"];
                $query = $mysqli->query("SELECT * FROM `user-project` WHERE `UID` = $UID");
                if ($query->num_rows >= 1) 
                {
                    echo "Project: <select name=projectSelect>";
                    echo "<option value=\"-1\">Any</option>";
                    while ($project = $query->fetch_assoc()) 
                    {
                        $PID = $project["PID"];
                        $projName = getProjectName($PID);
                        echo "<option value=$PID>$projName</option>";  
                    }
                    echo "</select><br>";
                }
                ?>
                Active: <select id="active" name="active">
                <option value="-1">Any</option>
                <option value="0">Open</option>
                <option value="1">Active</option>
                <option value="2">Inactive</option>
                <option value="3">Closed</option>
                </select><br>
                Priority: <select id="priority" name="priority">
                <option value="-1">Any</option>
                <option value="2">High</option>
                <option value="1">Medium</option>
                <option value="0">Low</option>
                </select><br>
                Difficulty: <select id="difficulty" name="difficulty">
                <option value="-1">Any</option>
                <option value="2">High</option>
                <option value="1">Medium</option>
                <option value="0">Low</option>
                </select><br>
                <input type=hidden name=page value=1>
                <input type="submit">
            </form>
            <div id="ticketContainer">
                <?php 
                
                //SELECT * FROM `ticket` WHERE `PID` = 1 AND `CONTENT` LIKE '%test%' AND `PRIORITY` = 0 AND `DIFFICULTY` = 0 AND `STATUS` = 1
                
                $search = "";
                $projectID=-1;
                $start =0;
                $active=-1;
                $priority=-1;
                $difficulty=-1;
                $page = 1;
                $rows = 0;
                
                $query = "SELECT * FROM `ticket` WHERE ";
                $queryCount=0;
                
                if (!empty($_POST)) 
                {
                    if (isset($_POST["search"])) 
                    {
                        $search = $_POST["search"];
                    }
                    if (isset($_POST["projectSelect"])) 
                    {
                        $projectID = $_POST["projectSelect"];
                    }
                    if (isset($_POST["active"]))
                    {
                        $active = $_POST["active"];
                    }
                    if (isset($_POST["priority"]))
                    {
                        $priority = $_POST["priority"];
                    }
                    if (isset($_POST["difficulty"]))
                    {
                        $difficulty = $_POST["difficulty"];
                    }
                    if (isset ($_POST["page"])) 
                    {
                        $page = $_POST["page"];
                        $start = ($start + $page - 1) * 10;
                    }
                    if ($projectID!=-1) 
                    {
                        $query .= "`PID` = 1 ";
                        $queryCount++;
                    }
                    if ($search!="") 
                    {
                        if ($queryCount>0) 
                        {
                            $query .= "AND ";
                        }
                        $query .= "`CONTENT` LIKE '%$search%' ";
                        $queryCount++;
                    }
                    if ($priority != -1) 
                    {
                        if ($queryCount>0) 
                        {
                            $query .= "AND ";
                        }
                        $query .= "`PRIORITY` = $priority ";
                        $queryCount++;
                    }
                    if ($difficulty != -1) 
                    {
                        if ($queryCount>0) 
                        {
                            $query .= "AND ";
                        }
                        $query .= "`DIFFICULTY` = $difficulty ";
                        $queryCount++;
                    }
                    if ($active != -1) 
                    {
                        if ($queryCount>0) 
                        {
                            $query .= "AND ";
                        }
                        $query .= "`STATUS` = 1";
                        $queryCount++;
                    }
                    if ($query == "SELECT * FROM `ticket` WHERE ") 
                    {
                        $results = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = 1");
                        if ($results->num_rows > $start) 
                        {
                           $results->data_seek(intval($start)); 
                        }
                        else 
                        {
                            echo "Invalid page";
                        }

                        $limiter=10;
                        while ($ticket = $results->fetch_assoc()) 
                        {
                            if ($limiter<=0) 
                            {break;}
                            else 
                            {
                                $limiter--;
                            }
                            drawTicket($ticket);
                        }
                        $rows = $results->num_rows;
                    }
                    else 
                    {
                        $results = $mysqli->query($query);
                        if ($results->num_rows > $start) 
                        {
                           $results->data_seek(intval($start)); 
                        }
                        else 
                        {
                            echo "Invalid page";
                        }

                        //echo $query;
                        
                        $limiter=10;
                        while ($ticket = $results->fetch_assoc()) 
                        {
                            if ($limiter<=0) 
                            {break;}
                            else 
                            {
                                $limiter--;
                            }
                            drawTicket($ticket);
                        }
                        $rows = $results->num_rows;
                    }
                    
                }
                else 
                {
                    $results = $mysqli->query("SELECT * FROM `ticket` WHERE `PID` = 1");
                    if ($results->num_rows > $start) 
                    {
                           $results->data_seek(intval($start)); 
                    }
                    else 
                    {
                            echo "Invalid page";
                    }

                        $limiter=10;
                    while ($ticket = $results->fetch_assoc()) 
                    {
                        if ($limiter<=0) 
                        {break;}
                        else 
                        {
                            $limiter--;
                        }
                        drawTicket($ticket);
                    }
                    $rows = $results->num_rows;
                }
                

                ?>
            </div>
            <div id="pages">
                <div id="thirdsLeft">
                    <?php 
                        
                        $pages = ceil($rows / 10);
                        $minPage = 1; $maxPage = 5;
                        $maxPage = $page+2;
                        $minPage = $page - 2;
                        if ($minPage <= 0 ) 
                        {
                            $minPage=1;
                            $maxPage=5;
                        }
                        if($maxPage>$pages) 
                        {
                            $maxPage=$pages;
                        } 
                        $previous = $page -1;
                        if ($previous >1) 
                        {
                            $previous = 1;
                        }
                        $next = $page+1;
                        if ($next > $maxPage) 
                        {
                            $next = $maxPage;
                        }
                    
                        echo "<a onClick=\"selectPage($previous)\">Previous</a>";
                        ?>
                </div>
                <div id="thirdsCenter">
                    <p>
                    
                        <?php
                        
                        for ($i=$minPage;$i<$maxPage+1;$i++) 
                        {
                            if ($page != $i) 
                            {
                                echo "<a onClick=\"selectPage($i)\">$i</a>";
                            }
                            else 
                            {
                                echo $i;
                            }
                        }
                    ?>
                    </p>
                </div>
                <div id="thirdsRight">
                    <p>
                    <?php echo "<a onClick=\"selectPage($next)\">Next</a>"?>
                </p>
                </div>
            </div>
        </div>

        <form id=pageForm action="" method="post">
            <input type=hidden name=search value="<?php echo $search; ?>">
            <input type=hidden name=projectSelect value="<?php echo $project; ?>">
            <input type=hidden name=active value="<?php echo $active; ?>">
            <input type=hidden name=priority value="<?php echo $priority; ?>">
            <input type=hidden name=difficulty value="<?php echo $difficulty; ?>">
            <input type=hidden name=page id="pageSelect" value="<?php echo $page; ?>">
        </form>
        
    </main>
    
    
	<script type="text/javascript" src=""></script>
    <script type="text/javascript">
        var pageForm = document.getElementById("pageForm");
        var pageSelect = document.getElementById("pageSelect");
        
        function selectPage(pageNum) 
        {
            pageSelect.value = pageNum;
            pageForm.submit();
        }
    </script>
</body>
    <?php 
    
    //calling our footer
    chdir("..");
    include_once("foot.php");
    ?>

</html>