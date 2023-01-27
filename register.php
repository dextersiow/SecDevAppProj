<?php
include 'connection.php';
include 'header.php';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['submit']) && $_POST['submit'] == "Register"){
            $username = $password = $confirm_password = "";
            $username_err = $password_err = $confirm_password_err = "";

            if (!empty($_POST['username'])) {
                $sql = "SELECT id FROM user WHERE username = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("s", $param_username);
                    $param_username = $_POST['username'];
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->fetch_assoc()) {
                        $username_Err = 'Username has been registered.';
                    } else {        
                        $input_username = $_POST['username'];
                        $input_password = $_POST['password']
                        $iv = random_bytes(16);
                        $iv_hex = bin2hex($iv);
                        $escaped_username = $conn->real_escape_string($_POST['username']);
                        $hashed_pw = hash_data($input_password);
                            
                            $sql = "INSERT INTO `user`(`username`, `password`, `iv`) "
                                    . "VALUES "
                                    . "('$escaped_username','$hashed_pw','$iv_hex','$encrypted_fullname','$encrypted_address','$encrypted_dob','$encrypted_phoneNo','$encrypted_img')";
            
                            if ($conn->query($sql) === TRUE) {
                                header("location: index.php");
                                exit;
                            } else {
                                die('Error creating user: ' . $conn->error);
                            }
                        
                    }
                }
            }
        }
        
    }

?>

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
