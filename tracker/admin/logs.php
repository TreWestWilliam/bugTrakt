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
        ?>
        <div id="adminRight">
        <h1>Logs</h1>
            <p>To be completed</p>
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