<?php 
    require_once 'header.php';
    $username = $password = "";
    $err = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        

    }
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
        <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" maxlength="12" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        
        <div class="checkbox mb-1">
            <label>
            <input type="checkbox" name="rmb" value="remember-me"/> Remember me
            </label>
        </div>

        <input class="btn btn-lg btn-primary btn-block" name="submit" type="submit">

        <div class="mt-1">
            <div>Don't have an account? <a id="register" href="registerMember.php">Click Here</a></div>      
        </div>

    </form>
</body>
</html>