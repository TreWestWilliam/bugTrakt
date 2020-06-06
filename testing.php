<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<title>BugTrakt Pricing</title>
	<meta name="author" content="William West">
	<meta name="description" content="A simple web bug tracker">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="salesStyle.css">
	<link rel="icon" type="image/x-icon" href=""/>
</head>

<body>
<?php 
    //calling our header
    echo file_get_contents("head.php");
    ?>
    
	<main>
        <!--Starting with a big bold beautiful heading.-->
        <div id="salesOne">
            <h1 id="salesOne">Testing information.</h1>
            <p>If you want to try us out first, please use this login. <br>
            Username:Test <br>
            Password:Tester<br>
            If you need any more extensive testing please call our support team at support@email.com.
            </p>
        </div>
            

    </main>
    <?php 
    //calling our footer
    echo file_get_contents("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
</body>

</html>