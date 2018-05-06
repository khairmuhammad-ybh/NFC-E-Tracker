<?php require('appDbConnect.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
 
    $company = $_POST['company'];
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $aid = $_POST['androidid'];
    

    $date = new DateTime();
    $date->setTimezone(new DateTimeZone('Asia/Singapore'));

    $fdate = $date->format('Y-m-d H:i:s'); // same format as NOW()
    $sdate = $date->format('Y-m-d'); // format for storing date

    $sql = "SELECT * FROM user WHERE company='$company' and username='$name' and password='".md5($pass)."' and android_id=''";
    require_once('appDbConnect.php');
    $result = mysqli_query($con,$sql);
    $rows = mysqli_num_rows($result);
    if($rows==1){
        $sql = "UPDATE user SET android_id='$aid' WHERE username='$name' and password='".md5($pass)."' and android_id=''";
        if(mysqli_query($con,$sql)){
            echo "first_access";
        }else{
            echo "update_failure";
        }
    }else{
        $sql = "SELECT * FROM user WHERE company='$company' and username='$name' and password='".md5($pass)."' and android_id='$aid'";
        $result = mysqli_query($con,$sql);
        $rows = mysqli_num_rows($result);
        if($rows==1){
            echo "verified";
        }else{
            echo "multiple_access";
        }
    }
}


