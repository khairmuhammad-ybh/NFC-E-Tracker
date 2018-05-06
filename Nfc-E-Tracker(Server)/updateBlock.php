<!DOCTYPE html>
<?php include("auth.php"); 
    if($_SESSION['type'] != 'Admin'){
        header("Location: index.php");
    }
?>
<html>
    <head>
        <title>update Block</title>
    </head>
    <body>
        <form name="blockupdate" action="" method="post">
        <h2 align='center'>Block Update</h2>
        <p align='center'>Please Select Block to to update</p>
        <p align='center'>
            <select name="blockselection" required>
                <option value="?">Block</option>
                <?php require('db.php');
                    include("auth.php");
                
                    $sql = "SELECT block_directories.block, block_directories.block_id FROM block_directories";
                    $result = mysql_query($sql);
                while($blkrecordslist=mysql_fetch_assoc($result)){
                    echo "<option value='{$blkrecordslist['block_id']}'>{$blkrecordslist['block']}</option>";
                }
                ?>
            </select>
        </p>
        <p align='center'>Please type in Block no. and/or address to add</p>
        <p align='center'>
            <input type="text" name="blockno" placeholder="New block no."/>
        </p>
        <p align='center'>
            <input type="text" name="blockaddr" placeholder="New block address"/>
        </p>
        <p align='center'>
            <select name="officerselect">
                <option value="?">Officer</option>
                <?php
                    $sql = "SELECT oic.oic_id, oic.officer_name FROM oic";
                    $result = mysql_query($sql);
                while($blkrecordslist=mysql_fetch_assoc($result)){
                    echo "<option value='{$blkrecordslist['oic_id']}'>{$blkrecordslist['officer_name']}</option>";
                }
                ?>
            </select>
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
                $blockselect = $_POST['blockselection'];
                $blockno = $_POST['blockno'];
                $blockaddr = $_POST['blockaddr'];
                $officerselect = $_POST['officerselect'];
                $blockselect = stripslashes($blockselect);
                $blockselect = mysql_real_escape_string($blockselect);
                $blockno = stripslashes($blockno);
                $blockno = mysql_real_escape_string($blockno);
                $blockaddr = stripslashes($blockaddr);
                $blockaddr = mysql_real_escape_string($blockaddr);
                $officerselect = stripslashes($officerselect);
                $officerselect = mysql_real_escape_string($officerselect);
                
                if($blockselect != "?"){
                    if($blockaddr != "" and $blockno !="" and $officerselect != "?"){
                        $sql = "UPDATE block_directories SET block='$blockno', address='$blockaddr' ,oic_id='$officerselect' WHERE block_id='$blockselect'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>Block successfully updated</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to update block information</h3>";
                        }
                    }else if($blockaddr != "" and $blockno !=""){
                        $sql = "UPDATE block_directories SET block='$blockno', address='$blockaddr' WHERE block_id='$blockselect'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>Block successfully updated</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to update block information</h3>";
                        }
                    }else if($blockno != "" and $officerselect != "?"){
                        $sql = "UPDATE block_directories SET block='$blockno' ,oic_id='$officerselect' WHERE block_id='$blockselect'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>Block successfully updated</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to update block information</h3>";
                        }
                    }else if($blockno != ""){
                        $sql = "UPDATE block_directories SET block='$blockno' WHERE block_id='$blockselect'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>Block successfully updated</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to update block information</h3>";
                        }
                    }else if($blockaddr != "" and $officerselect != "?"){
                        $sql = "UPDATE block_directories SET address='$blockaddr' ,oic_id='$officerselect' WHERE block_id='$blockselect'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>Block successfully updated</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to update block information</h3>";
                        }
                    }else if($blockaddr != ""){
                        $sql = "UPDATE block_directories SET address='$blockaddr' WHERE block_id='$blockselect'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>Block successfully updated</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to update block information</h3>";
                        }
                    }else if($officerselect != ""){
                        $sql = "UPDATE block_directories SET oic_id='$officerselect' WHERE block_id='$blockselect'";
                        if(mysql_query($sql)){
                            echo "<h3 align='center'>Block successfully updated</h3>";
                        }else{
                            echo "<h3 align='center'>Unable to update block information</h3>";
                        }
                    }else{
                        echo "<h3 align='center'>No information to update</h3>";
                    }
                }else{
                    echo "<h3 align='center'>Please select a block from the 'block' list.</h3>";
                }
            }
            if(isset($_POST['Add'])){
                $officerselect = $_POST['officerselect'];
                $blockno = $_POST['blockno'];
                $blockaddr = $_POST['blockaddr'];
                $officerselect = stripslashes($officerselect);
                $officerselect = mysql_real_escape_string($officerselect);
                $blockno = stripslashes($blockno);
                $blockno = mysql_real_escape_string($blockno);
                $blockaddr = stripslashes($blockaddr);
                $blockaddr = mysql_real_escape_string($blockaddr);
                
                if($blockno != "" and $blockaddr != "" and $officerselect != "?"){
                    $sql = "INSERT INTO block_directories (block, address, oic_id) VALUES ('$blockno', '$blockaddr', '$officerselect')";
                    if(mysql_query($sql)){
                        echo "<h3 align='center'>New block added</h3>";
                    }else{
                        echo "<h3 align='center'>Unable to add new block</h3>";
                    }
                }else if($blockno != "" and $officerselect != "?"){
                    $sql = "INSERT INTO block_directories (block,  oic_id) VALUES ('$blockno', '$officerselect')";
                    if(mysql_query($sql)){
                        echo "<h3 align='center'>New block added</h3>";
                    }else{
                        echo "<h3 align='center'>Unable to add new block</h3>";
                    }
                }else{
                    echo "<h3 align='center'>Unable to add new block, Please fill up the requred fields.</h3>";
                }
            }
            if(isset($_POST['Delete'])){
                $deleteblock = $_POST['blockselection'];
                $deleteblock = stripslashes($deleteblock);
                $deleteblock = mysql_real_escape_string($deleteblock);
                
                if($deleteblock !="?"){
                    $sql = "DELETE FROM block_directories WHERE block_id = '$deleteblock'";
                    if(mysql_query($sql)){
                        echo "<h3 align='center'>Block successfully deleted</h3>";
                    }else{
                        echo "<h3 align='center'>Unable to delete selected block</h3>";
                    }
                }else{
                    echo "<h3 align='center'>Please select block from the list to delete</h3>";
                }
            }
            
        ?>
        
        
    </body>
</html>