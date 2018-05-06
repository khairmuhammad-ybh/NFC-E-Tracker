<?php include("auth.php"); //include auth.php file on all secure pages 
$session_user= $_SESSION['username'];
$session_type= $_SESSION['type'];
?>
<html>
<head>
    <title>Home</title>
    
</head>
<body style="margin: 0; border: 0;">

  <div style=" background: #006DA6; " align='center' ><b>NFCTraker Duty Checker (BETA)</b></div>

  <div style="float: left; width: 100%;">
    <div style="margin-left: 280px;">
      <div style="margin-top: 0;">
        
          <p align='center'><b>Today's date:
              <?php 
                $date = new DateTime();
                $date->setTimezone(new DateTimeZone('Asia/Singapore'));
                $sdate = $date->format('Y-m-d'); // format for storing date 
                echo "<h2 align='center' style='color:blue'>".$sdate."</h2>";
              ?></b></p>
            <?php include'tableView.php'?>
      </div>
    </div>
  </div>

  <div style="float: left; width: 280px; margin-left: -100%;">
    <div style="margin-top: 0; border-style: solid; border-right: solid double #000000;">
        <br /> <br /> <h2 align='center'>Welcome <?php echo $_SESSION['username']; ?></h2>
        <h2 align='center'>This is  <?php echo $_SESSION['type']; ?> account</h2> <br />
        <h3 align='center'>Navigation</h3>
        <h2 align='center'><a href="registration.php" style="text-decoration:none; color:#78b941">Create New Account</a></h2>
        <?php
        $query = "SELECT * FROM `user` WHERE username='$session_user'and type='1'";
        $result =mysql_query($query) or die(mysql_error());
        $rows = mysql_num_rows($result);
        if($rows==1){
            echo "<h2 align='center'><a href='updateDB.php' style='text-decoration:none; color:#78b941'>Update Database</a></h2>";
            echo "<h2 align='center'><a href='resetUser.php' style='text-decoration:none; color:#78b941'>Reset User</a></h2>";
        }else{
            echo "<h2 align='center'><a href='resetUser.php' style='text-decoration:none; color:#78b941'>Reset User</a></h2>";
        }
        ?>
        <h2 align='center'><a href="logout.php" style="text-decoration:none; color:#78b941">Logout</a></h2> 
        <br /> <br />
        <h3 align='center'>Calendar</h3>
        <?php include'calendar.php'?>
        <h2 align='center'>(<b><i>BETA VERSION</i></b>)</h2>
        <br /> <br /> <br />
        <br /> <br /> <br />
        <br /> <br /> <br />
    </div>
  </div>
</body>
</html>