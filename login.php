<?php 
session_start();
require_once "workingconnection.php";
require_once "functions.php";

$allowed_attempts = 3;
$lockout_time = 180; // 3 minutes in seconds
$now = time();

//check if logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
  header("location: index.php");
  exit;
}

// Check if the user has been locked out
if (isset($_SESSION[$failed_attempts_key]) && $_SESSION[$failed_attempts_key] >= $allowed_attempts) {
  $remaining_time = $lockout_time - ($now - $_SESSION[$last_attempt_key]);
  echo "Account locked out. Please try again in $remaining_time seconds.";
}


if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $user_agent = $_SERVER['HTTP_USER_AGENT'];

  //authenticate user
  if(authenticate($conn, $username,$password)){

    unset($_SESSION[$failed_attempts_key]);
    unset($_SESSION[$last_attempt_key]);
    session_regenerate_id();
    set_session($username, $ip_address, $user_agent);
    echo "Login successful!<br>";
	  header("Location: index.php");

  } else {
    $_SESSION['failed_attempts'] = isset($_SESSION['failed_attempts']) ? $_SESSION['failed_attempts'] + 1 : 1;
    $_SESSION['last_attempt'] = $now;
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
