<?php 
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 3600);
ini_set("session.cookie_httponly", True);
session_start();
require_once "workingconnection.php";
require_once "functions.php";

$allowed_attempts = 5; //login allowed attempts
$lockout_time = 180; // 3 minutes in seconds
$now = time();

//check if logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
  header("location: index.php");
  exit;
}

// Check if the user has been locked out
  if (isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= $allowed_attempts) {
    $remaining_time = $lockout_time - ($now - $_SESSION['last_attempt']);
    $lock_msg = "<div>Account locked out. Please try again in $remaining_time seconds.</div>";
  }

if (isset($_POST['username']) && isset($_POST['password'])) {
  
  //remaning time > 0, do nothing 
  if(isset($remaining_time) && $remaining_time>0){

  }
  else{    
    //else reset login attempts
    if(isset($remaining_time) && $remaining_time<0){
      $_SESSION['failed_attempts'] = 0;
    }
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    //authenticate user
    if($role = authenticate($conn, $username,$password)){

      // Reset the failed attempts and last attempt time for this user
      unset($_SESSION['failed_attempts']);
      unset($_SESSION['last_attempt']);

      //regenetate session id
      session_regenerate_id();

      //set session variables for authenticate user
      set_session($username, $ip_address, $user_agent, $role);

      //log successfull event
      logEvent($conn,$username,session_id(),$ip_address,$user_agent,'login','successfull');

      //echo "Login successful!<br>";
      header("Location: index.php");

    } else {
      $_SESSION['failed_attempts'] = isset($_SESSION['failed_attempts']) ? $_SESSION['failed_attempts'] + 1 : 1;
      $_SESSION['last_attempt'] = $now;      
      $fail_attempts= "Failed Attempts: ".$_SESSION['failed_attempts']."<br>";
      $err_msg = "The username <b> ". filter($username) . " </b> and password could not be authenticated at the moment. <br>";
      //log fail event
      logEvent($conn,$username,session_id(),$ip_address,$user_agent,'login','unsuccessfull');
    }
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
        <span>
        <?php
        if(isset($err_msg)){
          echo $err_msg.'<br>';
        }
        if(isset($fail_attempts)){
          echo "Failed attempts: ".$fail_attempts;
        }
        if(isset($lock_msg)&& $remaining_time>0){
          echo $lock_msg;
        }
        ?>
        </span>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

        <div class="mt-1">
            <div>Don't have an account? <a id="register" href="register.php">Click Here</a></div>      
        </div>

    </form>
</body>
</html>
