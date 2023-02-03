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
        
        <form name="frmRegistration" method="post" action="">
        <div class="form-table">
            <div class="form-head">
                Register
            </div>   

            <div class="field-column">
                <label>Username</label>
                <div>
                    <input type="text" class="input-box" name="username" placeholder="Username" value="<?php //if(!empty($_POST['username'])){echo $_POST['userEmail'];}?>">
                </div>
                <?php //if(!empty($email_err)){echo $email_err;} ?>
            </div>

            <div class="field-column">
                <label>Password</label>
                <div>
                    <input type="password" class="input-box" name="password" placeholder="Password" value="">
                </div>
                <?php //if(!empty($password_err)){echo $password_err;} ?>
            </div>

            <div class="field-column">
                <label>Confirm Password</label>
                <div>
                    <input type="password" class="input-box" name="cpassword" placeholder="Confirm Password" maxlength="14 "value="">
                </div>
                <?php //if(!empty($confirm_password_err)){echo $confirm_password_err;} ?>
            </div>

            <div class="field-column">
                <div>
                    <input type="submit"
                        name="register-member" value="Register"
                        class="btnRegister">
                    
                </div>
            </div>

        </div>
    </form>            
    <br><br><br>
    <script>
        function checkPasswordComplexity() {
            var password = document.getElementById("password").value;
            var uppercase = /[A-Z]/;
            var lowercase = /[a-z]/;
            var number = /[0-9]/;
            var special = /[!@#$%^&*(),.?":{}|<>]/;
            
            if (password.length >= 10) {
                document.getElementById("password_complexity").innerHTML = "Password must be at least 10 characters long.";
            }
            if (uppercase.test(password)) {
                document.getElementById("password_complexity").innerHTML = "Password must contain at least one uppercase letter";

            }
            if (lowercase.test(password)) {
                document.getElementById("password_complexity").innerHTML = "Password must contain at least one lowercase letter";
            }
            if (number.test(password)) {
                document.getElementById("password_complexity").innerHTML = "Password must contain at least one number";
            }
            if (special.test(password)) {
                document.getElementById("password_complexity").innerHTML = "Password must contain at least one special character";
            }
        }

    </script>
    </body>
</html>
<?php 
require_once "connection.php";
$username = $_POST["username"]; 
$password = $_POST["password"];
$cpassword = $_POST["cpassword"]; 

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$salt = '';
for ($i = 0; $i < 32; $i++) {
    $salt .= $characters[rand(0, $charactersLength - 1)];
}

$usernameExists = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($usernameExists);

	if ($result->num_rows > 0) {
	echo "Username already exists<br>";
	} else if (strlen($password) < 10) {
      echo "Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.<br>";
    } else if (!preg_match("#[a-z]+#", $password)) {
      echo "Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.<br>";
    } else if (!preg_match("#[A-Z]+#", $password)) {
     echo "Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.<br>";
    } else if (!preg_match("#[0-9]+#", $password)) {
      echo "Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.<br>";
    } else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
      echo "Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.<br>";
    } else if ($password != $cpassword) {
      echo "Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.<br>";
    }	else {
		$hashSalt = hash("sha256", $salt);
		$saltedHashedPassword = hash("sha256", $hashSalt . $password);
		$sql = "INSERT INTO users (username, password, salt) 
		VALUES ('$username', '$saltedHashedPassword', '$hashSalt')";
		$result = $conn->query($sql);
		echo "Registration successful!<br>";
		header("Location: login.php");
	    exit();
    }
	
	
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully <br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
