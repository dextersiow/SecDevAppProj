<?php
session_start();
require_once 'functions.php';
require_once "workingconnection.php";

if(isset($_POST['register-member'])){

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
        echo "Username already exists<br>";
    } else if (!validatePassword($password)) {
        echo "Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.<br>";
    } else if ($password != $cpassword) {
        echo "Password does not match";
    } else {
        $hashSalt = hash("sha256", $salt);
        $saltedHashedPassword = hash("sha256", $hashSalt . $password);
        $role = 'user';
        $sql = "INSERT INTO users (username, password, salt, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $saltedHashedPassword, $hashSalt, $role);
        $result = $stmt->execute();

        if($result){
            echo "Registration successful!<br>";
            header("Location: login.php");
        } else {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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