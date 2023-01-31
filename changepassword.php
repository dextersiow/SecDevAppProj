<?php 

require_once "connection.php";

if (isset($_POST['username']) && isset($_POST['currentPassword']) && isset($_POST['newPassword'])) {
  $username = $_POST['username'];
  $currentPassword = $_POST['currentPassword'];
  $newPassword = $_POST['newPassword'];

  if (strlen($newPassword) < 10) {
    echo "Password must be at least 10 characters long and include at least 1 Upper Case, 1 Lower Case, 1 Special character and 1 number.<br>";
  } else if (!preg_match("#[a-z]+#", $newPassword)) {
    echo "Password must include at least 1 lowercase letter.<br>";
  } else if (!preg_match("#[A-Z]+#", $newPassword)) {
    echo "Password must include at least 1 uppercase letter.<br>";
  } else if (!preg_match("#[0-9]+#", $newPassword)) {
    echo "Password must include at least 1 number.<br>";
  } else if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $newPassword)) {
    echo "Password must include at least 1 special character.<br>";
  } else {
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows == 0) {
      echo "Username and/or password is incorrect.<br>";
    } else {
      $user = $result->fetch_assoc();
      $hashSalt = $user['salt'];
      $saltedHashedPassword = hash("sha256", $hashSalt . $currentPassword);

      if ($saltedHashedPassword == $user['password']) {
        $saltedHashedNewPassword = hash("sha256", $hashSalt . $newPassword);
        $sql = "UPDATE users SET password='$saltedHashedNewPassword' WHERE username='$username'";
        if ($conn->query($sql) === TRUE) {
          echo "Password updated successfully.<br>";
        } else {
          echo "Error updating password: " . $conn->error . "<br>";
        }
      } else {
        echo "Incorrect password.<br>";
      }
    }
  }
} else {
  echo "Please enter a username, current password and new password.<br>";
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
        <h1 class="h3 mb-3 font-weight-normal">Change Password</h1>

        <?php //if(isset($username_err))echo $Username_err ?>

        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" maxlength="12" required autofocus>

        <label for="inputPassword" class="sr-only">Old Password</label>
        <input type="password" name="currentPassword" id="inputPassword" class="form-control" placeholder="Password" required>
		
		 <label for="inputPassword" class="sr-only">New Password</label>
        <input type="password" name="newPassword" id="inputPassword" class="form-control" placeholder="Password" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">change password</button>

    </form>
</body>
</html>
