<?php
// Start the session
session_start();
require_once "workingconnection.php";
require_once "functions.php";
date_default_timezone_set('Europe/London');

// Check if the user is logged in, if not redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if (check_timeout()){
    logout();
    exit;
}

update_session();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo filter($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
    
    <p><?php echo "Last Activity: ".date("Y-m-d h:i:sa", $_SESSION['LAST_ACTIVITY']) ?></p>
    <p><?php echo "Next Timeout: ".date("Y-m-d h:i:sa", $_SESSION['LAST_ACTIVITY']+600) ?></p>
    <p><?php echo "Max Timeout: ".date("Y-m-d h:i:sa", $_SESSION['timeout']) ?></p>
    
    <p>

        <?php if($_SESSION['role'] != 'admin') {
            echo '<a href="admin.php" class="btn btn-info">Admin Page</a>';
            }
        ?>
        <a href="changepassword.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        
    </p>
</body>
</html>