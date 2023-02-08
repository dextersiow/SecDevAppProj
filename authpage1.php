<?php
	session_start();
	$encryptedUsername = $_SESSION['encrypted_username'];
	$key = "YourEncryptionKey";
	$username = openssl_decrypt(base64_decode($encryptedUsername), 'AES-128-ECB', $key);
	echo "Welcome, " . $username;

     $pageTitle = "My Web Page";
    $pageContent = "Welcome to my web page! This is a simple PHP generated page.";
    $headerText = "My Web Page";
    $headerColor = "#000000";
    $headerSize = "36px";
    $headerFont = "Helvetica";
    
    echo "<html>";
    echo "<head>";
    echo "<title>".$pageTitle."</title>";
    echo "<style> 
            h1 {
                color: ".$headerColor.";
                font-size: ".$headerSize.";
                font-family: ".$headerFont.";
                text-align: center;
            }
            p {
                text-align: center;
                margin: 20px;
                padding: 20px;
                font-size: 18px;
            }
            </style>";
    echo "</head>";
    echo "<body>";
    echo "<h1>".$headerText."</h1>";
    echo "<p>".$pageContent."</p>";
    echo "</body>";
    echo "</html>";
?>
