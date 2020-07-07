<?php 
    include_once("config.php")
?>

<head>
	<link rel="stylesheet" href="style.css">
</head>


<header>
        <nav>
        <div id="headTop">
            <div id="headLeft">
                <a href="<?php echo $bRoot . "index.php" ?>"><h1>BugTrakt</h1></a>
            </div>
            <div id="headRight">
            <ul id="HorizontalList">
                <a href="<?php echo $bRoot . "auth/login.php"?>" ><li><h1>Log in</h1></li></a>
                </ul>
            </div>
        </div>
            </nav>
    </header>