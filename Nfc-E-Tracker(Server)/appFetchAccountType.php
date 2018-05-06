<?php 
 
 if($_SERVER['REQUEST_METHOD']=='GET'){
 
 $username = $_GET['username'];
 $password = $_GET['password'];
 
 require_once('appDbConnect.php');
 
 $sql = "SELECT * FROM user WHERE username='".$username."' and password='".md5($password)."'";
 
 $r = mysqli_query($con, $sql);
 
 $res = mysqli_fetch_array($r);
 
 $result = array(); 
 
 if($res){
     array_push($result,array(
 "name"=>$res['username'],
 "type"=>$res['type'],
 "company"=>$res['company']
 )
 );
 }else{
 array_push($result,array(
 "name"=>$res['username'],
 "type"=>$res['type'],
 "company"=>$res['company']
 )
 );
 }
 echo json_encode(array("result"=>$result));    
 mysqli_close($con);
 
 }



