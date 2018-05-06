<?php include("auth.php"); 
    if($_SESSION['type'] != 'Admin'){
        header("Location: index.php");
    }
?>
<html>
    <head>
        <title>Update DB</title>
    </head>
    <body>
        <h2 align='center'>Update Database</h2>
        <h2 align='center'><a href='updateBlock.php' style="text-decoration:none; color:#78b941">Update Block</a></h2>
        <h2 align='center'><a href='updateOfficer.php' style="text-decoration:none; color:#78b941">Update Officer Name</a></h2>
        <h2 align='center'><a href='userManager.php' style="text-decoration:none; color:#78b941">Manage Users</a></h2>
        <h2 align='center'>return to <a href='index.php' style="text-decoration:none; color:#78b941">dashboard</a></h2>
    </body>
</html>