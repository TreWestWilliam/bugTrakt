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
        <div id="centeredBody">
            <h1>New ticket</h1>
            <form>
                Name:<input type="text" name="name"><br>
                Priority:<select id="priority" name="priority">
                <option value="med">Medium</option>
                <option value="low">Low</option>
                <option value="high">High</option>
                </select><br>
                Description:<input type="text" name="desc"><br>
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