<?php
require_once "connection.php";
function filter($input){
  $input = str_replace("<", "&lt;", $input);
  $input = str_replace(">", "&gt;", $input);
  $input = str_replace("'", "&apos;", $input);
  $input = str_replace('"', "&quot;", $input);
  return $input;
}

function authenticate($username, $password){
  $query = "SELECT * FROM users WHERE username=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    //no result of username provided found in database, return false
    return false;

  } else {
    $user = $result->fetch_assoc();
    $salt = $user['salt'];
    $saltedHashedPassword = password_hash($salt.$password, PASSWORD_DEFAULT);

    //compare hashed password
    if (password_verify($saltedHashedPassword,$user['password']) ) {
      return true;
    } else{
      return false;
    }
    }
}

function checkActiveSession($username, $session){
  $query = "SELECT * FROM users WHERE username=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

}

function logout(){
    //logout user
}

function validatePassword(){
  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number    = preg_match('@[0-9]@', $password);
  $specialChars = preg_match('@[^\w]@', $password);

  if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    return false;
  } else {
    return true;
  }
}



?>