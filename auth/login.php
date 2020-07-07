<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<title>BugTrakt Login</title>
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
    include_once("head.php");
    //Initilize session
    session_start();
    //Check if the user is logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] ===true) 
    {
        header("location: ../tracker/index.php");
        exit;
    }
    require_once "tracker/mysqlConfig.php";
    $UNAME =""; 
    $PASS="";
    $name_err="";
    $pass_err="";
    
    if($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $UNAME=trim($_POST["UNAME"]);
        $PASS=trim($_POST["PASS"]);
        
        if(empty($UNAME)) 
        {
            $name_err = "Please enter your username.";
        }
        
        if(empty($PASS)) 
        {
            $pass_err="Please enter your password";
        }
        
        if($name_err === "" && $pass_err === "") 
        {
            $getUser = $mysqli->query("SELECT * FROM `user` WHERE `UNAME` = '$UNAME'");
            $userRows = $getUser->num_rows;
            $user = $getUser->fetch_assoc();
            if ($userRows = 1) 
            {
                $userData = $getUser->fetch_assoc();
                //Getting the password to verify
                $hashedPass = $user["UPASS"];
                if (password_verify($PASS, $hashedPass)) 
                {
                    //Start the logged in session
                    session_start();
                    //Store some session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["UID"] = $user["UID"];
                    $_SESSION["UNAME"] = $UNAME;
                    
                    //Opening the tracker home
                    header("location: ../tracker/index.php");
                }
            }
            else 
            {
                $name_err="Username not found.";
            }
        }
        
    }
    
    ?>
    
	<main>
        <div id="loginBox">
        <h1>Login<br></h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                 <?php if ($name_err != "")  {echo "<p id=warning><spawn id=warning>" .$name_err . "</span></p>";}?>
                <label for="UNAME">Username:</label> <input type="text" value="<?php echo $UNAME; ?>" name="UNAME"> <br>
                <?php if ($pass_err != "")  {echo "<p id=warning><spawn id=warning>" .$pass_err . "</span></p>";}?>
                <label for="pass">Password:</label> <input type="password" value="<?php echo $PASS; ?>" name="PASS"> <br>
                <input type="submit" name="subimt">
            </form>
            <p><a href="signup.php">Create an account.</a>&nbsp; <a href="http://localhost/BugTrakt/auth/recoverPassword.php">Recover Password</a></p>
        </div>
    </main>
    <?php 
    //calling our footer
    echo file_get_contents("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
</body>

</html>