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
    $saltedHashedPassword = hash("sha256", $salt . $password);

    //compare hashed password
    if ($password == $saltedHashedPassword) {
      return true;
    } else{
      return false;
    }
    }
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

function generateSalt() {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $salt = '';
  for ($i = 0; $i < 10; $i++) {
      $randomString .= $characters[random_int(0, $charactersLength - 1)];
  }
  return $salt;
}

function check_session($sess_id) {
	$query="SELECT * FROM sessiontable WHERE sess_id = ?";
	$stmt = $conn->prepare($query);
  $stmt->bind_param("s", $sess_id);
  $stmt->execute();
  $result = $stmt->get_result();
	if($result->num_rows == 0)
	{
		$row = $result->fetch_assoc();
		return $row;
	}
	return FALSE;
}

function createSession($sess_id, $username){
  $time = time();
  $maxinactive = date("Y-m-d H:i:s" ,$time+3600);
  $time = date("Y-m-d H:i:s" ,$time);

  $creationtime = $time;
  $lastaccess = $time;



  if(!isset($username)){
    $username= 'anonymous';
  }
  $query="INSERT INTO sessiontable (id, username, lastaccess, creationtime, maxinactivetime) VALUES (?, ?, ?)";
	$stmt = $conn->prepare($query);
  $stmt->bind_param("sssss", $sess_id, $username, $lastaccess, $creationtime, $maxinactive);
  $stmt->execute();
  $result = $stmt->get_result();
}
?>