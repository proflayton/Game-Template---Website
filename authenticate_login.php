<?php
include_once "db.php";
session_start();

function login($pUsername,$pPassword)
{
	$loginSql = "SELECT * FROM $usertable WHERE username=:username AND password=:password";
	$loginStmt = $db->prepare($loginSql);
	$loginStmt->execute(array(
							":username"=>$pUsername,
							":password"=>hash("sha1",$pPassword)
							));
	$loginRes = $loginStmt->fetch();
	if($loginRes)
	{
		$_SESSION['login']=$loginRes['id'];
		$_SESSION['login_time']=time()+30*60;
		return true;
	}
	else
	{
		return false;
	}
}



function register($pUsername,$pPassword)
{
	$loginSql = "INSERT INTO $usertable (username,password) VALUES".
				"(:username,:password)";
	$loginStmt = $db->prepare($loginSql);
	$loginRes = $loginStmt->execute(array(
							":username"=>$pUsername,
							":password"=>hash("sha1",$pPassword)
						));
	return $loginRes;
}
?>