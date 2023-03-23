<?php
session_start();
require_once 'functions.php';
require_once "workingconnection.php";
$allowed_attempts = 5; //login allowed attempts
$lockout_time = 180; // 3 minutes in seconds
$now = time();


// Check if the user has been locked out
if (isset($_SESSION['reg_attempts']) && $_SESSION['reg_attempts'] >= $allowed_attempts) {
    $remaining_time = $lockout_time - ($now - $_SESSION['last_reg_attempt']);
    $lock_msg = "<div>Account locked out. Please try again in $remaining_time seconds.</div>";
}

  
if(isset($_POST['register-member'])){

    if(isset($remaining_time) && $remaining_time>0){
        messagebox("You are locked out. Please try again in $remaining_time seconds");
    }
    else{  

        $username = $_POST["username"]; 
        $password = $_POST["password"];
        $cpassword = $_POST["cpassword"]; 

        $salt = generateSalt();
        $usernameExists = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($usernameExists);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['reg_attempts'] = isset($_SESSION['reg_attempts']) ? $_SESSION['reg_attempts'] + 1 : 1;
            $_SESSION['last_reg_attempt'] = $now;      
            $fail_attempts= "Failed Attempts: ".$_SESSION['reg_attempts']."<br>";
            logEvent($conn,$username,session_id(),$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],'Register','fail');
            messagebox( "Username already exists");

        } else if (!validatePassword($password)) {
            messagebox("Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.");
        
        } else if ($password != $cpassword) {
            messagebox("Password does not match");
    
        } else {
            // Reset the failed attempts and last attempt time for this user
            unset($_SESSION['failed_attempts']);
            unset($_SESSION['last_attempt']);
            $hashSalt = hash("sha256", $salt);
            $saltedHashedPassword = hash("sha256", $hashSalt . $password);
            $role = 'user';
            $sql = "INSERT INTO users (username, password, salt, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $saltedHashedPassword, $hashSalt, $role);
            $result = $stmt->execute();

            if($result){
                logEvent($conn,$username,session_id(),$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT'],'Register','successfull');
                echo "Registration successful!<br>";
                header("Location: login.php");
            } else {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            }
        }
    }
}


?>

<!DOCTYPE html> 

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
        <link href="css/bootstrap.css" rel="stylesheet">   
        <link href="css/registerform.css" rel="stylesheet">
        <title>Secure App Dev</title>  
        
    </head>
    <body>
        <br><br><br>
        
        <form name="frmRegistration" method="post" action="" onsubmit = "return validate()">
        <div class="form-table">
            <div class="form-head">
                Register
            </div>   

            <div class="field-column">
                <label>Username</label>
                <div>
                    <input type="text" class="input-box" name="username" placeholder="Username" value="<?php //if(!empty($_POST['username'])){echo $_POST['username'];}?>">
                </div>
            </div>

            <div class="field-column">
                <label>Password</label>
                <div>
                    <input type="password" class="input-box" name="password" placeholder="Password" id="password" value="" oninput="validate()" required>
                    <span id='password_complexity'></span>
                </div>
            </div>

            <div class="field-column">
                <label>Confirm Password</label>
                <div>
                    <input type="password" class="input-box" name="cpassword" id="cpassword" placeholder="Confirm Password" oninput="validate()" value="">
                </div>
            </div>

            <div class="field-column">
                <div>
                    <input id='submit' type="submit"
                        name="register-member" value="Register"
                        class="btnRegister">
                    
                </div>
            </div>

            

        </div>
    </form>            
    <br><br><br>
    <script>
        function validate() {
            var password = document.getElementById("password").value;
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