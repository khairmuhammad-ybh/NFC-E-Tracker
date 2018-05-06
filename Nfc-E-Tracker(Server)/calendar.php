<html>
	<head>
		<script>
			function goLastMonth(month, year){
				if(month == 1){
					--year;
					month = 13;
				}
                --month
                var monthstring = ""+month+"";
                var monthlength = monthstring.length;
				if(monthlength <=1){
                    monthstring = "0"+monthstring;
                }
                document.location.href = "<?php $_SERVER['PHP_SELF'];?>?month="+monthstring+"&year="+year;
			}
			
			function goNextMonth(month, year){
				if(month == 12){
					++year;
					month = 0;
				}
                ++month
                var monthstring = ""+month+"";
                var monthlength = monthstring.length;
				if(monthlength <=1){
                    monthstring = "0"+monthstring;
                }
				document.location.href = "<?php $_SERVER['PHP_SELF'];?>?month="+monthstring+"&year="+year;
			}
		</script>
		<style>
			.today {
				background-color : #898585;
			}
			.event {
				background-color : #F0E68C;
			}
			
		</style>
	</head>
	
	<body>
		<?php
            date_default_timezone_set('Asia/Singapore');
        
			if(isset($_GET['day'])){
				$day = $_GET['day'];
			}else{
				$day = date("j");
			}
			if(isset($_GET['month'])){
				$month = $_GET['month'];
			}else{
				$month = date("n");
			}
			if(isset($_GET['month'])){
				$year = $_GET['year'];
			}else{
				$year = date("Y");
			}
			
			
			$currentTimeStamp = strtotime("$year-$month-$day");
			$monthName = date("F", $currentTimeStamp);
			$numDays = date("t", $currentTimeStamp);
			$counter=0;
            $_SESSION['eventdate']="?";
            
            if(isset($_GET['v'])){
                $_SESSION['eventdate'] = $year."-".$month."-".$day;
            }
            
		?>
		<table border='1'>
			<tr>
				<td><input style='width:38px' type='button' value='<' name='previousbutton' onclick="goLastMonth(<?php echo $month.",".$year?>)"></td>
				<td colspan='5' align='center'> <?php echo $monthName.", ".$year ?></td>
				<td><input style='width:38px' type='button' value='>' name='nextbutton' onclick="goNextMonth(<?php echo $month.",".$year?>)"></td>
			</tr>
			<tr>
				<td width='50px' align='center'>Sun</td>
				<td width='50px' align='center'>Mon</td>
				<td width='50px' align='center'>Tue</td>
				<td width='50px' align='center'>Wed</td>
				<td width='50px' align='center'>Thu</td>
				<td width='50px' align='center'>Fri</td>
				<td width='50px' align='center'>Sat</td>
			</tr>
			<?php require('db.php');
				
				echo "<tr>";
					for($i =1; $i < $numDays+1; $i++, $counter++){
						$timeStamp = strtotime("$year-$month-$i");
						if($i == 1){
							$firstDay = date("w", $timeStamp);
							for($j = 0; $j < $firstDay; $j++, $counter++){
								//blankspace
								echo "<td>&nbsp;</td>";
							}
						}
						if($counter % 7 == 0){
							echo "</td><tr>";
						}
						$monthstring = $month;
						$monthlength = strlen($monthstring);
						$daystring = $i;
						$daylength = strlen($daystring);
						if($monthlength <= 1){
							$monthstring = "0".$monthstring;
						}
						if($daylength <= 1){
							$daystring = "0".$daystring;
						}
						$todaysDate = date("Y-m-d");
						$dateToCompare = $year . "-" . $monthstring . "-" . $daystring;
						echo "<td align='center' ";
						if($todaysDate == $dateToCompare){
							$sqlCount = "select * from event_details where eventDate='".$dateToCompare."'";
							$noOfEvent = mysql_num_rows(mysql_query($sqlCount));
							if($noOfEvent >= 1){
								echo "class='event'";
							}else{
								echo "class='today'";
							}	
						}else{
                            //to view selected company
                                $sqlCount = "select * from event_details where eventDate='".$dateToCompare."'";
                                $noOfEvent = mysql_num_rows(mysql_query($sqlCount));
                                if($noOfEvent >= 1){
                                    echo "class='event'";
                                }
                            }
						echo "><a href='".$_SERVER['PHP_SELF']."?year=".$year."&month=".$monthstring."&day=".$daystring."&v=true'>".$i."</a></td>";
					}
				echo "</tr>";
			?>
			
		</table>
	</body>
</html>