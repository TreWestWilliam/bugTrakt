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
    require_once "tracker/mysqlConfig.php";
    //Defining empty variables to print below
    $name_err = ""; $repeatedPassError = ""; $pass_err = "";
    $reqName=""; $reqPass=""; $repeatedPass=""; $reqEmail="";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            //making all the post values into easier to read/work with variables
        if (isset($_POST["name"])) 
        {
            $reqName = trim($_POST["name"]);
        }
        else
        {
            $reqName="";
        }
        if (isset($_POST["pass"])) 
        {
            $reqPass = trim($_POST["pass"]);
        }
        else
        {
            $reqPass="";
        }

        if (isset($_POST["pass2"])) 
        {
            $repeatedPass = trim($_POST["pass2"]);
        }
        else
        {
            $repeatedPass="";
        }
        if (isset($_POST["email"])) 
        {
            $reqEmail = trim($_POST["email"]);
        }
        else
        {
            $reqEmail="";
        }
        //Now performing validity checks on the values
        if($reqName==="") 
        {
            $name_err = "Please write a username.";
        }
        else 
        {
            $alreadyUsedCheck = $mysqli->query("SELECT `UNAME` FROM `user` WHERE `UNAME` = '$reqName'");
            $rows = $alreadyUsedCheck->num_rows; 
            if ($rows) 
            {
                $name_err = "Name is already in use.";
            }
        }
        //Validating our password
        if(empty($reqPass)) 
        {
            $pass_err = "Please set your password";
        }
        else if ($reqPass != $repeatedPass) 
        {
            $repeatedPassError ="Your passwords do not match.";
        }
        
        //Now checking for errors before we do anything else.
        if (empty($name_err) && empty($pass_err) && empty($repeatedPassError)) 
        {
            // Hashing the password to make it secure
            $hashedPass = password_hash($reqPass, PASSWORD_DEFAULT);
            $insertUser = $mysqli->query("INSERT INTO `user` (`UID`, `UNAME`, `UEMAIL`, `UPASS`) VALUES (NULL, '$reqName', '$reqEmail', '$hashedPass');");
            //Redirect them to login
            header("location:login.php");
        }
        
    }
    
    ?>
    
	<main>
        <div id="loginBox">
        <h1>Sign Up<br></h1>
            <form action="" method="post">
                <label for="email">E-mail:</label> <input type="email" value="<?php echo $reqEmail; ?>" name="email"> <br>
                <?php if ($name_err != "")  {echo "<p id=warning><spawn id=warning>" .$name_err . "</span></p>";}?>
                <label for="name">Name:</label> <input type="text" value="<?php echo $reqName; ?>" name="name"><br>
                <?php if ($pass_err != "")  {echo "<p id=warning><spawn id=warning>" .$pass_err . "</span></p>";}?>
                <label for="pass">Password:</label> <input type="password" value="<?php echo $reqPass; ?>" name="pass"> <br>
                <?php if ($repeatedPassError != "")  {echo "<p id=warning><spawn id=warning>" . $repeatedPassError . "</span></p>";}?>
                <label for="pass2">Repeat password:</label> <input type="password" value="<?php echo $repeatedPass; ?>" name="pass2"> <br>
                <input type="submit" name="submit">
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