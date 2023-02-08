<?php 
require_once "connection.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT * FROM users WHERE username=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    echo "Username and/or password is incorrect.<br>";
  } else {
    $user = $result->fetch_assoc();
    $hashSalt = $user['salt'];
    $saltedHashedPassword = hash("sha256", $hashSalt . $password);

    if ($saltedHashedPassword == $user['password']) {
	session_start();
	$username = $_SESSION['username'];
	$key = "YourEncryptionKey";
	$encryptedUsername = base64_encode(openssl_encrypt($username, 'AES-128-ECB', $key));
	$_SESSION['encrypted_username'] = $encryptedUsername;
      echo "Login successful!<br>";
	  header("Location: authpage1.php");
    } else {
      echo "The username ". $username . "and password could not be authenticated at the moment. <br>";
    }
  }
} else {
  echo "Please enter a username and password.<br>";
}

$conn->close();
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

        <?php //if(isset($username_err))echo $Username_err ?>

        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        
        <div class="checkbox mb-1">
            <label>
            <input type="checkbox" name="rmb" value="remember-me"/> Remember me
            </label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

        <div class="mt-1">
            <div>Don't have an account? <a id="register" href="register.php">Click Here</a></div>      
        </div>

    </form>
</body>
</html>
