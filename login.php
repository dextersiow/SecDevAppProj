<?php 
session_start();
require_once "workingconnection.php";
require_once "functions.php";

//check if logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
  header("location: index.php");
  exit;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  //authenticate user
  if(authenticate($conn, $username,$password)){

    session_regenerate_id();
    set_session($username);
    echo "Login successful!<br>";
	  header("Location: index.php");

  } else {
    echo "The username <b> ". $username . " </b> and password could not be authenticated at the moment. <br>";
  }

} 


?>


<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/login.css" rel="stylesheet">


<title>Secure App Dev</title>    

</head>
<body class="text-center">
    <form class="form-signin" action="" method="post">
        <a href="index.php"><img class="mb-4" src="img/logo.png" width="300" height="180"></a>
        <h1 class="h3 mb-3 font-weight-normal">Sign in</h1>

        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

        <div class="mt-1">
            <div>Don't have an account? <a id="register" href="register.php">Click Here</a></div>      
        </div>

    </form>
</body>
</html>
