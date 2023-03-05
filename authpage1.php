<?php
    $pageTitle = "My Web Page";
    $pageContent = "Welcome to my web page! This is a simple PHP generated page.";
    $headerText = "My Web Page";
    $headerColor = "#000000";
    $headerSize = "36px";
    $headerFont = "Helvetica";
?>

<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <style> 
        h1 {
            color: <?php echo $headerColor; ?>;
            font-size: <?php echo $headerSize; ?>;
            font-family: <?php echo $headerFont; ?>;
            text-align: center;
        }
        p {
            text-align: center;
            margin: 20px;
            padding: 20px;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <h1><?php echo $headerText; ?></h1>
    <p><?php echo $pageContent; ?></p>

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
</html>
