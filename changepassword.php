<?php
session_start();
require_once('workingconnection.php');
if(isset($GET['submit'])){
    if(isset($GET['currentPassword']) && isset($GET['newPassword']) && isset($GET['newPassword']))
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

            $stmt = $conn->prepare("UPDATE users set password = ? WHERE username =  ?"); 
            $stmt->bind_param("ss", $username, $newPassword);
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
            <input type="submit" name="submit" type="password" value="Change Password">
        </form>
    </body>
</html>
