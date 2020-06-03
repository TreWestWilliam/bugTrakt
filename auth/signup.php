<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<title>BugTrakt Sign up</title>
	<meta name="author" content="William West">
	<meta name="description" content="A simple web bug tracker">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="authStyle.css">
	<link rel="icon" type="image/x-icon" href=""/>
</head>

<body>
<?php 
    //calling our header
    chdir("..");
    echo file_get_contents("head.php");
    ?>
    
	<main>
        <div id="loginBox">
        <h1>Sign Up<br></h1>
            <form action="" method="post">
                <label for="email">E-mail:</label> <input type="email" name="email"> <br>
                <label for="name">Name:</label> <input type="text" name="name"><br>
                <label for="pass">Password:</label> <input type="password" name="pass"> <br>
                <label for="pass2">Repeat password:</label> <input type="password" name="pass2"> <br>
                <input type="submit" name="subimt">
            </form>
            <p><a href="login.php">Log in.</a>&nbsp; <a href="http://localhost/BugTrakt/auth/recoverPassword.php">Recover Password</a></p>
        </div>
    </main>
    <?php 
    //calling our footer
    echo file_get_contents("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
</body>

</html>