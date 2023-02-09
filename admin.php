<html>
    <head>
    </head>
    <body>
        <div style="margin-left: 10px;">
        <h2>Event Log</h2>
        <?php
        session_start();
        echo session_id()."<br>";
             $time = time();
             $time = date("Y-m-d H:i:s" ,$time+3600);
             echo $time;
        ?>
        <table class="table" style="width:80%">
        
        </table>
        </div>
        
        
    </body>
</html>
