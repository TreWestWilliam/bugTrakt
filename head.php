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
                <h1>BugTrakt</h1>
            </div>
            <div id="headRight">
            <ul id="HorizontalList">
                <a href="<?php echo $bRoot . "index.php"; ?>"><li>Home</li></a>
                <a href="<?php echo $bRoot . "about.php"; ?>"><li>About Us</li></a>
                <a href="<?php echo $bRoot . "sales.php"; ?>"><li>Pricing</li></a>
                <a href="<?php echo $bRoot . "/auth/login.php"; ?>"><li>Log in</li></a>
                </ul>
            </div>
        </div>
            </nav>
    </header>