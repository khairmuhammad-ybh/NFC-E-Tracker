<form name='companyselection' method='POST' action="<?php $_SERVER['PHP_SELF'];?>?year=<?php echo $year;?>&month=<?php echo $month;?>&day=<?php echo $day;?>&v=true&add=true">
    <div align="center">
    <select name='company'>
        <option value="">Select company</option>
        <option value="2">CV</option>
        <option value="1">MC</option>
	</select>
    </div>
	<div align='center'>
	   <input  type="submit" name="submit" value="Select" />
	</div>
</form>