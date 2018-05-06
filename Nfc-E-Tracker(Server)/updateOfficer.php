<!DOCTYPE html>
<?php include("auth.php"); 
    if($_SESSION['type'] != 'Admin'){
        header("Location: index.php");
    }
?>
<html>
    <head>
        <title>Update Officer</title>
    </head>
    <body>
        <form name="officerupdate" action="" method="post">
        <h2 align='center'>Update Officer</h2>
        <p align='center'>Please Select Officer and Company</p>
        <p align='center'>
            <select name="officerselection">
                <option value="?">Officer</option>
                <?php require('db.php');
                    include("auth.php");
                    $sql = "SELECT  oic.oic_id, oic.officer_name FROM oic";
                    $result = mysql_query($sql);
                    while($officerresult = mysql_fetch_assoc($result)){
                        echo "<option value='{$officerresult['oic_id']}'>{$officerresult['officer_name']}</option>";
                    }
                ?>
            </select>
        </p>
        <p align='center'>Please type in Officer name and/or select company</p>
        <p align='center'>
            <input type="text" name="officername" placeholder="Officer name"/>
        </p>
        <p align='center'>
            <input type="submit" name="Add" value="Add" />
            <input type="submit" name="Update" value="Update" />
            <input type="submit" name="Delete" value="Delete" />
        </p>
        </form>
        <h2 align='center'>Back to <a href='updateDB.php' style="text-decoration:none; color:#78b941">UpdateDB Menu</a></h2>
        <h2 align='center'>return to <a href='index.php' style="text-decoration:none; color:#78b941">dashboard</a></h2>
        
        <?php 
            if(isset($_POST['Update'])){
                $officerselect = $_POST['officerselection'];
                $modofficername = $_POST['officername'];
                $officerselect = stripslashes($officerselect);
                $officerselect = mysql_real_escape_string($officerselect);
                $modofficername = stripslashes($modofficername);
                $modofficername = mysql_real_escape_string($modofficername);
                
                if($officerselect != "?"){
                    if($modofficername != ""){
                        $sql = "UPDATE oic SET officer_name = '$modofficername' WHERE oic_id = '$officerselect'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>Officer information successfully updated</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to update information</h3>";
                        }
                    }else{
                         echo "<h3 align='center'>please type in new officer name</h3>";
                    }
                }else {
                     echo "<h3 align='center'>Please select officer name to modify</h3>";
                }
                
            }
            if(isset($_POST['Add'])){
                $newofficer = $_POST['officername'];
                $newofficer = stripslashes($newofficer);
                $newofficer = mysql_real_escape_string($newofficer);
                
                if($newofficer != ""){
                    $sql = "INSERT INTO oic (officer_name) VALUES ('$newofficer')";
                    if(mysql_query($sql)){
                        echo "<h3 align='center'>New officer successfully added</h3>";
                    }else{
                        echo "<h3 align='center'>Unable to add new officer</h3>";
                    }
                }else{
                    echo "<h3 align='center'>Please type in officer name in the field to add into dataabase.</h3>";
                }
            }
        
            if(isset($_POST['Delete'])){
                $deleteofficer = $_POST['officerselection'];
                $deleteofficer = stripslashes($deleteofficer);
                $deleteofficer = mysql_real_escape_string($deleteofficer);
                
                if($deleteofficer != "?"){
                    $sql = "DELETE FROM oic WHERE oic_id = '$deleteofficer'";
                    if(mysql_query($sql)){
                        echo "<h3 align='center'>Officer successfully deleted</h3>";
                    }else{
                        echo "<h3 align='center'>Unable to delete selected officer</h3>";
                    }
                }else{
                    echo "<h3 align='center'>Please select officer to delete</h3>";
                }
            }
            
        ?>
        
        
    </body>
</html>