<?php 
session_start();
require_once "workingconnection.php";
require_once "functions.php";

//check if role isset = is logged in and its admin
if(!isset($_SESSION["role"]) || $_SESSION['role'] != 'admin'){
    header("location: login.php");
    exit;
}

//check timeout
if (check_timeout()){
    messagebox('Session Timeout. Please Login Again.');
    logout($conn,'timeout');
    exit;
}

//update session time on new request
update_session();
?>
<!DOCTYPE html>
<html>
<head>
	<link href="css/bootstrap.css" rel="stylesheet">
	<title>Admin</title>
</head>
<body>
	<h1>This Page can only be viewed by an ADMIN</h1>	

	<div>
    	<a href='index.php'><button type='button' class="btn btn-secondary">Home</button></a>
	</div>
</body>
</html>
