<?php
session_start();
if(isset($_SESSION['admin']))
{
	if(time() >= $_SESSION['admin_login'])
	{
		include_once "admin_login.php";
		session_destroy();
	}
	else
	{
		include_once "admin_page.php";
	}
}
else
{
	include_once "admin_login.php";
}
?>