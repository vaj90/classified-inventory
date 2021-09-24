<?php
    $to="allanjohn.valiente@gmail.ca";
    $from="allanjohn.valiente@georgebrown.ca";
    $subject = "Testing Cron";
    $message = "<h2>Cron is Working, remove test cron job now.</h2>";
    $headers = "From: $from\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\n";

    mail($to,$subject,$message,$headers);
    echo "hello world";
?>