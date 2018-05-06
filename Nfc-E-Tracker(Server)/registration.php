<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php include("auth.php");
 require('db.php');

 $date = new DateTime();
 $date->setTimezone(new DateTimeZone('Asia/Singapore'));
 $sdate = $date->format('Y-m-d'); // format for storing date

 $session_user= $_SESSION['username'];
 $session_type= $_SESSION['type'];
 
 // If form submitted, insert values into the database.
 if (isset($_POST['username'])){
 $username = $_POST['username'];
 $password = $_POST['password'];
 $company = $_POST['company'];
 $type = $_POST['type'];
 $username = stripslashes($username);
 $username = mysql_real_escape_string($username);
 $password = stripslashes($password);
 $password = mysql_real_escape_string($password);
 $company = stripslashes($company);
 $company = mysql_real_escape_string($company);
 $type = stripslashes($type);
 $type = mysql_real_escape_string($type);
 //Username and password check
 $query = "SELECT * FROM `user` WHERE username='$username' and password='".md5($password)."'";
 $result = mysql_query($query) or die(mysql_error());
 $rows = mysql_num_rows($result);
 if($rows==1){
 echo "<div class='form'><h3>Account has already been created.</h3><br/><a href='index.php'>Back to dashboard</a></div>";
 }else{
 $query = "INSERT into `user` (company, username, password, type, created_date) VALUES ('$company','$username', '".md5($password)."', '$type', '$sdate')";
 $result = mysql_query($query);
 if($result){
 echo "<div class='form'><h3>Account successfully created.</h3><br/><a href='index.php'>Return to dashboard</a><br/><br/><a href='logout.php'>logout</div>";
 }
     
 
 }
     
 
 }else{
$query = "SELECT * FROM `user` WHERE username='$session_user'and type='1'";
$result =mysql_query($query) or die(mysql_error());
$rows = mysql_num_rows($result);
if($rows==1){
     
?>
<div class="form">
<h1>Registration</h1>
<form name="registration" action="" method="post">
<input type="text" name="username" placeholder="Username" required />
<input type="password" name="password" placeholder="Password" required />
<p></p>
<p> Select Company
<select name="company">
    <?php
        $sql = "SELECT companies.company_id, companies.company FROM companies";
        $result = mysql_query($sql);
        while($userfetch = mysql_fetch_assoc($result)){
            echo "<option value='{$userfetch['company_id']}'>{$userfetch['company']}</option>";
        }
    ?>
</select>
</p>
<p></p>
<p> Select Account Type
<select name="type">
  <option value="2">Officer</option>
  <option value="3">Worker</option>
</select>
</p>    
<p></p>
<input type="submit" name="submit" value="Register" />
</form>
</div>
<br/><h3 style="width: 300px; margin: 0 auto;">Return to <a href='index.php'>dashboard</a></h3>
<?php }else{ ?>
<div class="form">
<h1>Registration</h1>
<form name="registration" action="" method="post">
<input type="text" name="username" placeholder="Username" required />
<input type="password" name="password" placeholder="Password" required />
<p></p>
<p> Select Company
<select name="company">
    <?php
        $sql = "SELECT companies.company_id, companies.company FROM companies";
        $result = mysql_query($sql);
        while($userfetch = mysql_fetch_assoc($result)){
            echo "<option value='{$userfetch['company_id']}'>{$userfetch['company']}</option>";
        }
    ?>
</select>
</p>
<p></p>
<p> Select Account Type
<select name="type">
  <option value="3">Worker</option>
</select>
</p>    
<p></p>
<input type="submit" name="submit" value="Register" />
</form>
</div>
<br/><h3 style="width: 300px; margin: 0 auto;">Return to <a href='index.php'>dashboard</a></h3>
<?php }}?>
</body>
</html>