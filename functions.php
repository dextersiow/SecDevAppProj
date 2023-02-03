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
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);
    if ($result->num_rows == 0) {
        return false;
      } else {
        $user = $result->fetch_assoc();
        $hashSalt = $user['salt'];
        $saltedHashedPassword = hash("sha256", $hashSalt . $password);
    
        if ($saltedHashedPassword == $user['password']) {
          return true;
        } else {
          return false;
        }
      } 
}
function checkActiveSession($session){
    //check authenticated active session 
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