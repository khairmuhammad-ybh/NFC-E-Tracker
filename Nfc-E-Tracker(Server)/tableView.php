<html>
	<head>
        <p hidden="hidden">
            <?php 
                include('calendar.php');
                include("auth.php");
            ?>
        </p>
	</head>
	<body>
        
        <form name="viewcompany" action="<?php $_SERVER['PHP_SELF']?>" method="post">
        <p align='center'>To view specific company, Please Select
			
            <select name="company">
                <option value="?">Company</option>
                <?php
                    $sql = "SELECT companies.company FROM companies";
                    $result = mysql_query($sql);
                    while($fetchcompany=mysql_fetch_assoc($result)){
                        echo "<option value='{$fetchcompany['company']}'>{$fetchcompany['company']}</option>";
                    }
                ?>
			</select>
        </p>
        <p align='center'> To view specific officer, Please Select
            <select name="officer">
                <option value="?">Officer</option>
                <?php
                    $sql = "SELECT oic.officer_name FROM oic";
                    $result = mysql_query($sql);
                    while($fetchcompany=mysql_fetch_assoc($result)){
                        echo "<option value='{$fetchcompany['officer_name']}'>{$fetchcompany['officer_name']}</option>";
                    }
                ?>
			</select>
        </p>
        <div align='center'>
			<input  type="submit" name="submit" value="OK" />
        </div>
        </form>
		<table border='1' style="font-size: 15 " align='center'>
            <tr><td colspan="50%" align='center'>
                <?php 
                    $selectedDate="?";
                    $selectedCo="?";
                    $selectedOic="?";
                    if(isset($_POST['company'])){
                        $selectedDate = $_SESSION['eventdate'];
                        echo "Date: <b><i>{$selectedDate}</i></b>, Co: <b><i>{$_POST['company']}</i></b>, Oic: <b><i>{$_POST['officer']}</i></b>";
                    }else if(isset($_GET['v'])){
                        $selectedDate = $_SESSION['eventdate'];
                        echo "Date: <b><i>{$selectedDate}</i></b>";
                    }else if($_SESSION['eventdate']==""){
                        echo "Date: <b><i>{$_SESSION['eventdate']}</i></b>";
                    } 
                ?>
                </td></tr>
			
		<?php
		require('db.php');
            
        
            
		$sql = "
		SELECT block_directories.block, oic.officer_name, event_details.username, event_details.datetime, event_details.eventDate, companies.company, event_details.tag
        FROM block_directories INNER JOIN event_details
        ON block_directories.block_id = event_details.tag
        INNER JOIN companies
        ON event_details.company=companies.company_id
        INNER JOIN oic
        ON block_directories.oic_id = oic.oic_id
        "
		;
		$records=mysql_query($sql);

        $sqlblock = "
        SELECT block_directories.block, block_directories.oic_id, oic.officer_name 
        FROM block_directories INNER JOIN oic
        ON block_directories.oic_id = oic.oic_id
        ORDER BY CAST(block AS UNSIGNED), block
        "
        ;
        $blockrecords=mysql_query($sqlblock);
        
        echo "<tr>";
        echo "<th>Block</th>";
        echo "<th>co</th>";
        echo "<th>user</th>";
        echo "<th>datetime</th>";
        echo "<th>co</th>";
        echo "<th>user</th>";
        echo "<th>datetime</th>";
        echo "<th>co</th>";
        echo "<th>user</th>";
        echo "<th>datetime</th>";
        echo "<th>co</th>";
        echo "<th>user</th>";
        echo "<th>datetime</th>";
        echo "<th>co</th>";
        echo "<th>user</th>";
        echo "<th>datetime</th>";
        echo "</tr>"; 
            
        while($blkrecords=mysql_fetch_assoc($blockrecords)){
            if(isset($_POST['officer'])){
                if($blkrecords['officer_name']==$_POST['officer']){
                    echo "<tr>";
                    echo "<td>{$blkrecords['block']}</td>";
                }else{
                   if($_POST['officer']=="?"){
                       echo "<tr>";
                       echo "<td>{$blkrecords['block']}</td>";
                   } 
                }
            }else{
                echo "<tr>";
                echo "<td>{$blkrecords['block']}</td>";
            }
            while($users=mysql_fetch_assoc($records)){
                if($users['block']==$blkrecords['block']){
                    
                    if(isset($_POST['company'])){
                        //execute query (selection for both company and officer selected)
                        if($newquery = $users['eventDate']==$selectedDate and $users['company']==$_POST['company'] and $users['officer_name']==$_POST['officer']){
                            echo "<td>{$users['company']}</td>";
                            echo "<td>{$users['username']}</td>";
                            echo "<td style='font-style: italic'>{$users['datetime']}</td>";
                        //execute query (selection for officer only selected)
                        }else if($_POST['officer']!="?" and $_POST['company']=="?"){
                            if($newquery = $users['eventDate']==$selectedDate and $users['officer_name']==$_POST['officer']){
                                echo "<td>{$users['company']}</td>";
                                echo "<td>{$users['username']}</td>";
                                echo "<td style='font-style: italic'>{$users['datetime']}</td>";
                            }
                        //execute query (selection for company only selected)
                        }else if($_POST['officer']=="?" and $_POST['company']!="?"){
                            if($newquery = $users['eventDate']==$selectedDate and $users['company']==$_POST['company']){
                                echo "<td>{$users['company']}</td>";
                                echo "<td>{$users['username']}</td>";
                                echo "<td style='font-style: italic'>{$users['datetime']}</td>";
                            }
                        }else{
                            //execute query (selection for both unselect, **Nothing will be displayed as date is not set**)
                            if($_POST['officer']=="?"){
                                if($newquery = $users['eventDate']==$selectedDate){
                                    echo "<td>{$users['company']}</td>";
                                    echo "<td>{$users['username']}</td>";
                                    echo "<td style='font-style: italic'>{$users['datetime']}</td>";
                                }
                            }
                        }
                    }else{
                        //execute query (selection for both unselect, populate table with 'date' only)
                        if($newquery = $users['eventDate']==$selectedDate){
                            echo "<td>{$users['company']}</td>";
                            echo "<td>{$users['username']}</td>";
                            echo "<td style='font-style: italic'>{$users['datetime']}</td>";
                        }
                    }
                    
                }
            }
            mysql_data_seek($records, 0);
            echo "</tr>";
        }
		?>
		</table>
	</body>
</html>
