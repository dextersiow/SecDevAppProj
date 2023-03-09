<?php
// Login to databse
$host = 'localhost';
$username = 'root';
$password = '';
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
	username varchar(256) NOT NULL,
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

?>
