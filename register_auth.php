<?php
	include_once "db.php";
	$loginSql = "SELECT * FROM $usertable WHERE username=:username";
	$loginStmt = $db->prepare($loginSql);
	$loginStmt->execute(array(
							":username"=>$_POST['username'],
							));
	$loginRes = $loginStmt->fetch();
	if($loginRes)
	{
		echo "Error! That username exists!<br/>";
		exit;
	}
	else
	{	
		$regSql = "INSERT INTO $usertable (username,password) VALUES".
					"(:username,:password)";
		$regStmt = $db->prepare($regSql);
		$regRes = $regStmt->execute(array(
								":username"=>$_POST['username'],
								":password"=>hash("sha1",$_POST['password'])
							));
		if($regRes)
		{
			echo "Congratz! You are registered!<br/><a href='index.php'>Go home</a>";
		}
		else
		{
			echo "Registration failed! Please try again later<br/><a href='index.php'>Go home</a>";
		}
	}
?>