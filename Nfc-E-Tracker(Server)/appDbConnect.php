<?php
 define('HOST','localhost');
 define('USER','root');
 define('PASS','pass');
 define('DB','db_name');
 
 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');