<?php
	$host="localhost";
	$database="db";
	$username="un";
	$password="password";
	$usertable="ip_users";
	$questtable="ip_quests";
	$questsdonetable="ip_quests_done";
	$owneditemstable="ip_owned_items";
	$itemstable="ip_item";
	$shopstable = "ip_shop";
	$shopitemstable = "ip_shop_items";
	
	
	$db = new PDO("mysql:host=$host;dbname=$database",$username,$password);
?>
