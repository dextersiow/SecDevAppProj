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
    </body>
</html>
<?php 
require_once "connection.php";
$username = $_POST["username"]; 
$password = $_POST["password"]; 


$sql = "INSERT INTO users (username, password) 
VALUES ('$username', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
