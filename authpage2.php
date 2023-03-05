<?php
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
<body>

    <script>
        // set timeout function
        var timeout;

        // start timer function
        function startTimer() {
            timeout = setTimeout(redirect, 600000); // 10 minutes in milliseconds
        }

        // reset timer function
        function resetTimer() {
            clearTimeout(timeout);
            startTimer();
        }

        // redirect function
        function redirect() {
            window.location.href = "login.php";
        }

        // start the timer on page load
        window.onload = startTimer;

        // reset the timer on mouse move or keyboard activity
        document.onmousemove = resetTimer;
        document.onkeydown = resetTimer;
		document.onwheel = resetTimer;
    </script>
</body>
