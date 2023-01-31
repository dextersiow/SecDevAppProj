<?php
 require_once "connection.php";
function filter(){
    //XSS filter
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
function authenticateSession($session){
    //check authenticated active session 
}

function logout(){
    //logout user
}

function validatePassword(){
    //validate password on creation
}



?>