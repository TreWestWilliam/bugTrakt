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
        <div id="centeredBody">
            <h1 id="alignCenter">Projects</h1>
            <form method="post" action="">
            Search: <input type="text" name="search" content="<?php if (isset($_POST["search"])) {echo $_POST["search"];} ?>"> <br>
                <input type=hidden name=page value=1>
                <input type="submit">
                <a href="projectCreate.php"><p>Create a project</p></a>
            </form>
            <div id="ticketContainer">
                <?php 
                require_once"mysqlConfig.php";
                
                function drawProject ($projectArray) // Making a function to draw the project instead of having repetitive code
                {
                    //Defining the variables, Project array should be an associative array from the mysqli fetch_assoc function.
                    $title = $projectArray["PNAME"];
                    $description = trim($projectArray["DESCRIPTION"]);
                    if (strlen($description) > 50) 
                    {
                        $description = substr($description,0,72);
                        $description .= "...";
                    }
                    $id = $projectArray["ID"];
                    $owner = getUserNameFromID($projectArray["ownerID"]);
                    // For prototyping purposes we'll use the ticket list as a base.
                    echo "<a href=project.php?id=$id><div id=yourTicket>
                        <h2>$id | $title</h2>
                        <p>Created by: $owner</p>
                        <p>$description</p>
                        </div></a>";
                }
                
                $search = "";
                $start =0;
                $page = 1;
                $rows = 0;
                if (!empty($_POST)) 
                {
                    if (isset($_POST["search"])) 
                    {
                        $search = $_POST["search"];
                    }
                    if (isset ($_POST["page"])) 
                    {
                        $start = ($start + $_POST["page"] - 1) * 10;
                        $page = $_POST["page"];
                    }
                }
                
                
                if ($search === "") // If search is empty we'll use the default search
                {
                    
                    $results = $mysqli->query("SELECT * FROM `projects` WHERE `isPublic` = 1");
                    if ($results->num_rows >= 1) 
                    {
                        $rows= $results->num_rows;
                        if ($results->num_rows > $start) 
                        {
                           $results->data_seek(intval($start)); 
                        }
                        else 
                        {
                            echo "Invalid page";
                        }
                        $limiter=10;
                        while ($project = $results->fetch_assoc()) 
                        {
                            if ($limiter<=0) 
                            {break;}
                            else 
                            {
                                $limiter--;
                            }
                            drawProject($project);
                        }
                    }
                }
                else //Otherwise there's a post request and we should do a seperate operation to see
                {
                    
                    $results = $mysqli->query("SELECT * FROM `projects` WHERE (`PNAME` LIKE '%$search%' AND `isPublic` = 1) OR (`DESCRIPTION` LIKE '%$search%' AND `isPublic` = 1) AND `isPublic` = 1");
                    if ($results->num_rows >=1) //if the search has any results we then
                    {
                        $rows= $results->num_rows;
                        while ($project = $results->fetch_assoc()) 
                        {
                            drawProject($project);
                        }
                    }
                    else
                    {
                        echo "Couldn't find any projects, perhaps you should create one or change your search.";   
                    }
                }
                
                //We're echoing this to make javascript do heavy lifting in selecting a page
                echo "<form method=post action=\"\" id=pageForm>
                    <input type=hidden name=search value=\"$search\">
                <input type=hidden name=page value=1 id=pageSelect>
                </form>";
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

    </main>
    <?php 
    
    //calling our footer
    chdir("..");
    include_once("foot.php");
    ?>
    
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

</html>