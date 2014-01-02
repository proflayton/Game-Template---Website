<?php
include_once "db.php";
if(isset($_POST['adminLogin']))
{
	//There shouldn't be a problem with this since nobody
	//can get into the php file anyway
	if($_POST['un']=='admin' && $_POST['pass']=='123secure321')
	{
		$_SESSION['admin'] = 1;
		$_SESSION['admin_login'] = time() + 60*60;
		header("Location: admin.php");
		exit;
	}
	else 
	{
		echo "<meta http-equiv='refresh' content='3;url=admin.php'>";
		echo "Error! Invalid login credentials! Redirecting in 3 seconds<br/>"; 
		exit;
	}
	exit;
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Admin Login</title>
	</head>
	<body>
		<form action="" method="post">
			Admin: <input type="text" name="un" /><br/>
			Password: <input type="password" name="pass" /><br/>
			<input type="submit" name="adminLogin" value="Submit" /><br/>
		</form>
	</body>
</html>
