<!DOCTYPE html>
<?php

$host = "localhost";
$dbname = "layt6314";
$username = "layt6314";
$password = "mypass";
$table = "test_account";
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Signup</title>
		<style>
			
		</style>
		<script>
			
		</script>
	</head>
	<body>
		<?php
		if(isset($_POST['register']))
		{
			$pwd = $_POST['passwd'];
			$user = $_POST['username'];
			echo"user: $user<br/>pass: $pwd<br />";
			
			$sql = "INSERT INTO $table (username,password) VALUES (:user,:pass)";
			$stmt = $db->prepare($sql);
			if($stmt->execute(array(':user'=>$user,':pass'=>$pwd)))
			{
				echo "SUCCESS";
			}
			else
			{
				echo "FAIL<br />".$stmt->errorCode();
			}
		}
		else
		{
		?>
		<form action="" method="post">
			<input type="hidden" name="register" value="1" />
			Usename: <input type="text" name="username" id="username" value=""/><br />
			Password: <input type="password" name="passwd" id="passwd" value=""/><br />
			Email: <input type="text" name="email" id="email" value="" /><br />
			<input type="submit" name="submit" value="Register" />
		</form>
		<?php
		}
		?>
	</body>
</html>