<?php

function filter($input){
  $input = str_replace("<", "&lt;", $input);
  $input = str_replace(">", "&gt;", $input);
  $input = str_replace("'", "&apos;", $input);
  $input = str_replace('"', "&quot;", $input);
  return $input;
}

function authenticate($conn, $username, $password){
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
    $saltedHashedPassword = hash("sha256", $salt . $password);
    

    //compare hashed password
    if ($user['password'] == $saltedHashedPassword) {
      return true;
    } else{
      return false;
    }
  }
}

function logout(){
    //logout user
    $_SESSION = array();
    
    session_unset();
    session_destroy();
    session_regenerate_id();
    
    header("location: login.php");
}

function validatePassword($password){
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

function generateSalt() {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $salt = '';
  for ($i = 0; $i < 10; $i++) {
      $salt .= $characters[random_int(0, $charactersLength - 1)];
  }
  //$salt = bin2hex(openssl_random_pseudo_bytes(16));
  return $salt;
}

function set_session($username, $ip_address, $user_agent) {   
	$_SESSION['username'] = $username;
  $_SESSION['loggedin'] = TRUE;
  $_SESSION['ip_address'] = $ip_address;
  $_SESSION['user_agent'] = $user_agent;
  $_SESSION['LAST_ACTIVITY'] = time();
  $_SESSION['csrf_token'] = generateSalt();
  $_SESSION['timeout'] = time() + 3600;

}

function check_timeout() {
  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
    // last request was more than 10 minutes ago
    return true;
  }
  else{
    return false;
  }
}

function update_session() {
  $_SESSION['LAST_ACTIVITY'] = time();
}


function logEvent($conn, $username, $sess_id, $ip_address, $user_agent, $action, $description) {
 
  $sql = "INSERT INTO eventlog (username, sess_id, ip_address, user_agent, action, description) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssss", $username, $sess_id, $ip_address, $user_agent, $action, $description);
  $stmt->execute();

  $stmt->close();
}

?>