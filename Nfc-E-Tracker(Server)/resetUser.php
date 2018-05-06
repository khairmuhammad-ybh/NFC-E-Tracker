<!DOCTYPE html>
<html>
    <head>
        <title>Reset User</title>
    </head>
    <body>
        <form name="officerupdate" action="" method="post">
        <h2 align='center'>Reset User</h2>
        <p align='center'>Please Select User</p>
        <p align='center'>
            <select name="userselection">
                <option value="?">User</option>
                <?php require('db.php');
                    include("auth.php");
                    session_start();
                    $sql = "SELECT user.username, user.company FROM user";
                    $result = mysql_query($sql);
                    while($userfetch = mysql_fetch_assoc($result)){
                        if($userfetch['username'] != $_SESSION['username']){
                            if($userfetch['username'] != 'admin')
                                echo "<option value='{$userfetch['username']}'>{$userfetch['username']}</option>";
                        }
                    }
                ?>
            </select>
        </p>
        <p align='center'>
            <input type="submit" name="Reset" value="Reset" />
        </p>
        </form>
        <h2 align='center'>return to <a href='index.php' style="text-decoration:none; color:#78b941">dashboard</a></h2>
        
        <?php
            if(isset($_POST['Reset'])){
                $userreset = $_POST['userselection'];
                $userreset = stripslashes($userreset);
                $userreset = mysql_real_escape_string($userreset);
                
                if($userreset != "?"){
                    $sqlcheck = "SELECT * FROM user WHERE username = '$userreset' and android_id != '' ";
                    $result = mysql_query($sqlcheck) or die(mysql_error());
                    $rows = mysql_num_rows($result);
                    if($rows==1){
                        $sql = "UPDATE user SET android_id = '' WHERE username = '$userreset'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>User successfully reset</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to reset user</h3>";
                        }
                    }else{
                        echo "<h3 align='center'>User has been reset -or- Not yet verified</h3>";
                    }
                }else{
                    echo "<h3 align='center'>Please Select user to reset</h3>";
                }
            }
        ?>
        
    </body>
</html>		