<?php
    require('db.php');
    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('TimeZone'));

    $fdate = $date->format('Y-m-d H:i:s'); // same format as NOW()
    $sdate = $date->format('Y-m-d'); // format for storing date

    $sql = "DELETE FROM event_details WHERE eventDate < DATE_SUB(NOW(), INTERVAL 2 MONTH)";
    $execution = mysql_query($sql);
    if($execution){
        echo "success";
    }
?>