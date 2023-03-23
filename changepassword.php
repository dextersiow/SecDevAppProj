<?php
session_start();
require_once('workingconnection.php');
require_once('functions.php');

//check if role isset = is logged in
if(!isset($_SESSION["role"])){
    header("location: login.php");
    exit;
}

//check timeout
if (check_timeout()){
    logout($conn,'timeout');
    exit;
}

//update session time on new request
update_session();

    
if(isset($_GET['submit'])){    
    //csrf token doesn't match or exist, logout user
    if ($_GET['csrf_token'] !== $_SESSION['csrf_token'] || !isset($_GET['csrf_token'])) {

        logEvent($conn,$_SESSION['username'],session_id(),$_SESSION['ip_address'],$_SESSION['user_agent'],'Change Password','Invalid CSRF token');
        logout($conn,'Invalid CSRF Token');
        //The CSRF token is invalid, do not process the request
    }

    if(isset($_GET['currentPassword']) && isset($_GET['newPassword']) && isset($_GET['newPasswordConfirm']))
    {
        $username = $_SESSION['username'];
        $password = $_GET['currentPassword'];
        $newPassword = $_GET['newPassword'];
        $conPassword = $_GET['newPasswordConfirm'];

        //password does match
        if($newPassword == $conPassword && validatePassword($newPassword)){
            //reauthenticate with username in session and password provided
            if(authenticate($conn, $username, $password)){
                $stmt = $conn->prepare("SELECT * FROM  users WHERE username =  ?"); 
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                //hash password
                $salt = generateSalt();
                echo "Salt: ". $salt;
                $hashSalt = hash("sha256", $salt);
                $saltedHashedPassword = hash("sha256", $hashSalt . $newPassword);

                //update database
                $stmt = $conn->prepare("UPDATE users SET salt = ?, password = ? WHERE username =  ?"); 
                $stmt->bind_param("sss",$hashSalt, $saltedHashedPassword, $username);
                $stmt->execute();

                if ($stmt->error) {
                    messagebox('Unexpected error updating database. Please try again.');
                }
                else{
                    logEvent($conn,$_SESSION['username'],session_id(),$ip_address,$user_agent,'Change Password','Successful');  
                    messagebox('Password changed successfully');
                    logout($conn,'Change Password');
                }

            }else{
                messagebox("Wrong current password. Please try again!");
            }
        }
        else{
            messagebox("Invalid New Password.");
        }
        
                
    }
    else{
        messagebox("Please enter the required field!") ;
    }
}
?>

<html>
    <head>
        <link href="css/bootstrap.css" rel="stylesheet">   
        <link href="css/registerform.css" rel="stylesheet">
        <title>Change Password</title>
    </head>
    <body>
       
        <div class="form-table">
            <form action="changepassword.php" method="get"  onsubmit = "return validate()">
                <div class="form-head">
                    Change Password
                </div>   

                <div class="field-column">
                    <label>Current Password:</label>
                    <div>
                        <input type="password" name="currentPassword" type="password" required>
                    </div>
                </div>

                <div class="field-column">
                    <label>New Password:</label>
                    <div>
                    <input type="password" id='npassword' name="newPassword" type="password" oninput="validate()" required>
                    </div>
                </div>

                <div class="field-column">
                    <label>Confirm New Password:</label>
                    <div>
                    <input type="password" id='cpassword' name="newPasswordConfirm" oninput="validate()" required><br>
                    </div>
                </div>

                <span id='password_complexity'></span>

                <div class="field-column">
                    <input type="submit"
                        name="submit" value="Change Password"
                        class="btnRegister">                    
                </div>
               

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            </form> 
            <a href='index.php'><button class="btn btn-lg btn-secondary btn-block">Cancel</button></a>
    
        </div>
            
        

        <script>
        function validate() {
            var password = document.getElementById("npassword").value;
            var cpassword = document.getElementById("cpassword").value;
            var uppercase = /[A-Z]/;
            var lowercase = /[a-z]/;
            var number = /[0-9]/;
            var special = /[!@#$%^&*(),.?":{}|<>]/;
            var validate = false;

            
            if (!(password.length >= 10)) {
                document.getElementById("password_complexity").innerHTML = "Password must be at least 10 characters long.";
                validate = false;
            }
            else if (!uppercase.test(password)) {
                document.getElementById("password_complexity").innerHTML = "Password must contain at least one uppercase letter";
                validate = false;
            }
            else if (!lowercase.test(password)) {
                document.getElementById("password_complexity").innerHTML = "Password must contain at least one lowercase letter";
                validate = false;
            }
            else if (!number.test(password)) {
                document.getElementById("password_complexity").innerHTML = "Password must contain at least one number";
                validate = false;
            }
            else if (!special.test(password)) {
                document.getElementById("password_complexity").innerHTML = "Password must contain at least one special character";
                validate = false;
            }
            else if (password != cpassword) {
                document.getElementById("password_complexity").innerHTML = "Password does not match";
                validate = false;
            }
            else{
                document.getElementById("password_complexity").innerHTML = "Perfect";
                validate = true;
            }

            return validate;
        }

    </script>
    </body>
</html>
