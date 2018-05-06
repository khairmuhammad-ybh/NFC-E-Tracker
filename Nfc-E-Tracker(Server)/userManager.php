<!DOCTYPE html>
<?php include("auth.php"); 
    if($_SESSION['type'] != 'Admin'){
        header("Location: index.php");
    }
?>
<html>
    <head>
        <title>User Manager</title>
    </head>
    <body>
        <form name="officerupdate" action="" method="post">
        <h2 align='center'>User Manager</h2>
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
                            echo "<option value='{$userfetch['username']}'>{$userfetch['username']}</option>";
                        }
                    }
                ?>
            </select>
        </p>
        <p align='center'>Please Assign New Company</p>
        <p align='center'>
            <select name="companyassigned">
                <option value="?">Co</option>
                <?php require('db.php');
                    include("auth.php");
                    session_start();
                    $sql = "SELECT companies.company_id, companies.company FROM companies";
                    $result = mysql_query($sql);
                    while($userfetch = mysql_fetch_assoc($result)){
                        echo "<option value='{$userfetch['company_id']}'>{$userfetch['company']}</option>";
                    }
                ?>
            </select>
        </p>
        <p align='center'>
            <input type="submit" name="Assign" value="Assign" />
            <input type="submit" name="Delete" value="Delete" />
        </p>
        </form>
        <h2 align='center'>Back to <a href='updateDB.php' style="text-decoration:none; color:#78b941">UpdateDB Menu</a></h2>
        <h2 align='center'>return to <a href='index.php' style="text-decoration:none; color:#78b941">dashboard</a></h2>
        
        <?php
            if(isset($_POST['Assign'])){
                $selecteduser = $_POST['userselection'];
                $assignedcompany = $_POST['companyassigned'];
                $selecteduser = stripslashes($selecteduser);
                $selecteduser = mysql_real_escape_string($selecteduser);
                $assignedcompany = stripslashes($assignedcompany);
                $assignedcompany = mysql_real_escape_string($assignedcompany);
                
                if($selecteduser != "?" and $assignedcompany != "?"){
                    $sql = "UPDATE user SET company = '$assignedcompany' WHERE username = '$selecteduser'";
                    if(mysql_query($sql)){
                        echo "<h3 align='center'>User's company assigned successfully</h3>";
                    }else{
                        echo "<h3 align='center'>Unable to assign user's company</h3>";
                    }
                }else if($selecteduser == "?" and $assignedcompany == "?"){
                    echo "<h3 align='center'>Please Select user and company to assign user's company</h3>";
                }else if($selecteduser == "?"){
                    echo "<h3 align='center'>Please Select user before assigning</h3>";
                }else if($assignedcompany == "?"){
                    echo "<h3 align='center'>Please Select company before assigning</h3>";
                }
            }
            if(isset($_POST['Delete'])){
                $selecteduser = $_POST['userselection'];
                $selecteduser = stripslashes($selecteduser);
                $selecteduser = mysql_real_escape_string($selecteduser);
                
                if($selecteduser != "?"){
                    $sql = "DELETE FROM user WHERE username = '$selecteduser'";
                    if(mysql_query($sql)){
                        echo "<h3 align='center'>User successfully deleted</h3>";
                    }else{
                        echo "<h3 align='center'>Unable to delete selected user</h3>";
                    }
                }else{
                    echo "<h3 align='center'>Please Select user to delete</h3>";
                }
                
            }
            
        ?>
        
    </body>
</html>	