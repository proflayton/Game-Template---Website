<?php
	session_start();
	include_once "db.php";
	$loginSql = "SELECT * FROM $usertable WHERE username=:username AND password=:password";
	$loginStmt = $db->prepare($loginSql);
	$loginStmt->execute(array(
							":username"=>$_POST['username'],
							":password"=>hash("sha1",$_POST['password'])
							));
	$loginRes = $loginStmt->fetch();
	if($loginRes)
	{
		$_SESSION['login']=$loginRes['id'];
		$_SESSION['login_time']=time()+60*60;
		//echo "Congratz! You are logged in!<br/><a href='index.php'>Go home</a>";
		header("Location: index.php");
	}
	else
	{
		//echo "Login failed! Double check your username and password<br/><a href='index.php'>Go home</a>";
		header("Location: index.php?error=loginFailed");
	}
?>