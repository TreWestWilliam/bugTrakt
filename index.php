<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<title>BugTrakt Home</title>
	<meta name="author" content="William West">
	<meta name="description" content="A simple web bug tracker">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<link rel="icon" type="image/x-icon" href=""/>
</head>

<body>
<?php 
    include_once("head.php");
    include_once("tracker/mysqlConfig.php");
    $tickets = $mysqli->query("SELECT * FROM `ticket`")->num_rows;
    $users = $mysqli->query("SELECT * FROM `user`")->num_rows;
    ?>
    
	<main>
          <!--This is an "inspirational" theme.  Hopefully I'll be able to implement it with pictures are paralax scrolling later, and integrate this theme into a single page site.-->
        <div id="inspireOne">
            <h1 id="inspireOne">For all your bug hunting needs,</h1>
        </div>
        <div id="inspireTwo">
            <h1 id="inspireTwo">We're here.</h1>
        </div>
        <div id="inspireThree">
            <p id="inspireThree">Having been around since 20XX we've been in the game for a long time in the future.  <br>That makes us the perfect choice for all your needs.<br>Companies that haven't even been made yet already use us.<br>So why doesn't yours?<br>Probably related to the fact that this isn't a real company.</p>
        </div>
        
        <div id="bodyContainer">
            <div id="centered">
            <h1>Tracking our stats live!</h1>
                <h2><?php echo $tickets; ?> Tickets made <br>
                <?php echo $users; ?> Users <br>
                What's your hold up?</h2>
                </div>
        </div>
    </main>
    <?php 
    include_once("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
</body>

</html>