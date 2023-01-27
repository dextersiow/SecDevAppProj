<?php
$host = 'localhost';
$username = 'SADUSER';
$password = 'SADUSER';
$conn = new mysqli($host, $username, $password);

$cipher = 'AES-128-CBC';

if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

if (isset($_POST['delete-everything'])) {
  $sql = 'DROP DATABASE secapp;';
  if (!$conn->query($sql) === TRUE) {
    die('Error dropping database: ' . $conn->error);
  }
}

$sql = 'CREATE DATABASE IF NOT EXISTS secapp;';
if (!$conn->query($sql) === TRUE) {
  die('Error creating database: ' . $conn->error);
}

$sql = 'USE secapp;';
if (!$conn->query($sql) === TRUE) {
  die('Error using database: ' . $conn->error);
}

$sql = 'CREATE TABLE IF NOT EXISTS user (
username varchar(20) NOT NULL,
pwd varchar(256) NOT NULL,
salt varchar(32) NOT NULL,
user_role varchar(10) NOT NULL,
PRIMARY KEY (username));';
if (!$conn->query($sql) === TRUE) {
  die('Error creating table: ' . $conn->error);
}

/*
$sql = 'CREATE TABLE IF NOT EXISTS active_session (
    sess_id varchar(64) NOT NULL,
    username varchar(20),
    PRIMARY KEY (sess_id)),
    FOREIGN KEY (username) REFERENCES user(username));'; 
if (!$conn->query($sql) === TRUE) {
    die('Error creating table: ' . $conn->error);
}
    
$sql = 'CREATE TABLE IF NOT EXISTS event_log (
    id int NOT NULL AUTO_INCREMENT,
    username varchar(20) NOT NULL,
    attempt varchar(20) NOT NULL,
    PRIMARY KEY (id)),
    FOREIGN KEY (username) REFERENCES active_session(username));';

if (!$conn->query($sql) === TRUE) {
    die('Error creating table: ' . $conn->error);
}
*/
?>
