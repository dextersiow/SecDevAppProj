<?php
// Login to databse
$host = 'localhost';
$username = 'SADUSER';
$password = 'SADUSER';
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
	die('Connection Failed: ' . $conn->connect_error);
}

if (isset($_POST['delete-everything'])) {
	$sql = 'DROP DATABASE secureapp;';
	if (!$conn->query($sql) === TRUE) {
	  die('Error dropping database: ' . $conn->error);
	}
  }

$sql = 'CREATE DATABASE IF NOT EXISTS secureapp;';
if (!$conn->query($sql) === TRUE) {
	die('Error creating database: ' . $conn->error);
}

$sql = 'USE secureapp;';
if (!$conn->query($sql) == TRUE) {
	die('Error using database: ' .$conn->error);
}

$sql = 'CREATE TABLE IF NOT EXISTS users (
id int NOT NULL AUTO_INCREMENT,
username varchar(256) NOT NULL,
password varchar(256) NOT NULL,
salt varchar(256) NOT NULL,
role varchar(15) NOT NULL,
PRIMARY KEY (id));';

if (!$conn->query($sql) === TRUE) {
	die('Error creating table: ' .$con->error);
}

$sql = 'CREATE TABLE IF NOT EXISTS eventlog (
	id int NOT NULL AUTO_INCREMENT,
	username varchar(256),
	sess_id varchar(256) NOT NULL,
	ip_address varchar(20),
	user_agent varchar(20),
	action varchar(20) NOT NULL,
	description varchar(20),
	timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id));';

if (!$conn->query($sql) === TRUE) {
  die('Error creating table: ' . $conn->error);
}

$sql = "SELECT * FROM users WHERE username='ADMIN'";
$result = $conn->query($sql);
if($result->num_rows == 0){
	$sql = "INSERT INTO users (username, password, salt, role) VALUES ('ADMIN', '041a54c0664eb9cb9f3cfe349655240bb929d5ca591e0ee50aad2b6ebf380c6e','58f1ade90d4c8929ae6d3bd0b82b3596d879ec96aa5cc930faf97617b41a638f', 'admin')";
	if (!$conn->query($sql) === TRUE) {
		die('Error creating table: ' . $conn->error);
	  }
}



?>
