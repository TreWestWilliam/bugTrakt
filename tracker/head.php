<?php 
    include_once("../config.php")
?>

<header>
        <nav>
        <div id="headTop">
            <div id="headLeft">
                <a href="<?php echo $bRoot . "/tracker/index.php" ?>"><h1>BugTrakt</h1></a>
            </div>
            <div id="headRight">
            <ul id="HorizontalList">
                <a href="<?php echo $bRoot. "/tracker/index.php"; ?>" ><li>Your Home</li></a>
                <a href="<?php echo $bRoot."/tracker/projectList.php"?>"> <li>Projects</li></a>
                <a href="<?php echo $bRoot . "/tracker/ticketlist.php"?>" ><li>Ticket List</li></a>
                <a href="<?php echo $bRoot . "/tracker/newticket.php"?>"> <li>New Ticket</li></a>
                <a href="<?php echo $bRoot . "/auth/logout.php"?>" ><li>Log out</li></a>
                </ul>
            </div>
        </div>
            </nav>
    </header>