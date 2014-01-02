<?php 
	if(session_id()=='')
		session_start();
?>
<?php
include_once "db.php";
include_once "library.php";

if(isset($_GET['logout']))
{
	if($_GET['logout']==1)
	{
		session_destroy();
		echo "Successfully Logged Out<br/>";
		include_once "login.php";
		exit;
	}
}
include_once "session_check.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Game Name</title>
	</head>
	<body>
		<div id="content">
			<?php include "navbar.php" ?>
			<?php
				include_once "game.php";
			?>
		</div>
	</body>
</html>

<?php
$db=null;
?>