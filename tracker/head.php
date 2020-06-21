<?php 
    //In this page we have our redirect to the login since it's on every page
    // Initialize the session
    session_start();

    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || !isset($_SESSION["UID"]) || $_SESSION["loggedin"] != true){
        session_destroy();
        session_write_close();
        header("location: ../auth/login.php");
        exit;
    }
?>

<header>
        <nav>
        <div id="headTop">
            <div id="headLeft">
                <h1>Home Button</h1>
            </div>
            <div id="headRight">
            <ul id="HorizontalList">
                <a href=http://localhost/BugTrakt/tracker/index.php><li>Your Home</li></a>
                <a href="http://localhost/BugTrakt/tracker/ticketlist.php"><li>Ticket List</li></a>
                <a href="http://localhost/BugTrakt/tracker/newticket.php"><li>New Ticket</li></a>
                <a href="http://localhost/BugTrakt/tracker/admin/index.php"><li>Config</li></a>
                <a href="http://localhost/BugTrakt/auth/logout.php"><li>Log out</li></a>
                </ul>
            </div>
        </div>
            </nav>
    </header>