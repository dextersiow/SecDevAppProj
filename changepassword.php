<?php
session_start();
require_once('workingconnection.php');
require_once('functions.php');

//check if logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if (check_timeout()){
    logout();
    exit;
}

update_session();

if(isset($GET['submit'])){
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        logout();
        // The CSRF token is invalid, do not process the request
        exit("Invalid CSRF token");
    }

    if(isset($GET['currentPassword']) && isset($GET['newPassword']) && isset($GET['newPasswordConfirm']))
    {
        $username = $_SESSION['username'];
        $password = $_GET['currentPassword'];
        $newPassword = $_GET['newPassword'];

        //reauthenticate with username in session and password provided
        if(authenticate($username,$password)){
            $stmt = $conn->prepare("SELECT * FROM  users WHERE username =  ?"); 
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            //hash password
            $salt = generateSalt();
            $hashSalt = hash("sha256", $salt);
	        $saltedHashedPassword = hash("sha256", $hashSalt . $newPassword);

            //update database
            $stmt = $conn->prepare("UPDATE users set password = ? WHERE username =  ?"); 
            $stmt->bind_param("ss", $saltedHashedPassword, $username);
            if($stmt->execute()){
                echo "Password changed successfully";
            }
        }
                
    }
    else{
        echo "Please enter the required field!";
    }
}
?>

<html>
    <head>
        <title>Change Password</title>
    </head>
    <body>
        <h1>Change Password</h1>
        <form action="changepassword.php" method="get">
            <label for="currentPassword">Current Password:</label>
            <input type="password" name="currentPassword" type="password" required><br>
            <label for="newPassword">New Password:</label>
            <input type="password" name="newPassword" type="password" required><br>
            <label for="newPasswordConfirm">Confirm New Password:</label>
            <input type="password" name="newPasswordConfirm" required><br>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="submit" name="submit" type="password" value="Change Password">
        </form>
    </body>
</html>
