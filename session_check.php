<?php
if(session_id() == "")
	session_start();
if(!isset($_SESSION['login']))
{
	include_once "login.php";
	exit;
}
else 
{
	if(time() >= $_SESSION['login_time'])
	{
		session_destroy();
		echo "Session expired!<br/>";
		include_once "login.php";
		exit;
	}
	else
	{
		$_SESSION['login_time'] = time()+30*60;
	}
}
?>