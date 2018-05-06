<?php 

if($_SERVER['REQUEST_METHOD']=='POST'){
 
    $company = $_POST['company'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $tag = $_POST['tag'];

    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('Asia/Singapore'));

    $fdate = $date->format('Y-m-d H:i:s'); // same format as NOW()
    $sdate = $date->format('Y-m-d'); // format for storing date

    $sql = "SELECT * FROM event_details WHERE company='$company' and eventDate='$sdate' and tag='$tag'";
    require_once('appDbConnect.php');
    $result = mysqli_query($con,$sql);
    $rows = mysqli_num_rows($result);
    if($rows==1){
        echo "duplicate";
    }else{
    //Creating sql query
        $sql = "INSERT INTO event_details (company, username, type, datetime, tag, eventDate) VALUES('$company','$name','$type','$fdate','$tag','$sdate')";

        //executing query
        $result = mysqli_query($con,$sql);

        //if we got some result 
        if($result){
            //displaying success 
            echo "success";
        }else{
            //displaying failure
            echo "failure";
        }
    }
    mysqli_close($con);
}


