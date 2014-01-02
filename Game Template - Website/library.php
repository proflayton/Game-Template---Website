<?php


function filterUploadFile($file)
{
	$allowedTypes = array("image/jpeg","image/png");
	$filterErrors = "";
	if(!in_array($file["type"], $allowedTypes))
	{
		$filterErrors = $file["type"]." is an invalid type<br/>";
	}
	if($file["size"] > 1024*1000)
	{
		$filterErrors = "File is too big! <br/>";	
	}
	return $filterErrors;
}



function isLoggedin()
{
	if(isset($_SESSION['login']))
	{
		if(time() >= $_SESSION['login_time'])
		{
			session_destroy();
			return false;
		}
		else
		{
			return true;
			//add more time to the session. We only want to kick them if they are inactive
			$_SESSION['login_time']=time()+30*60;
		}
	}
	else
	{
		return false;
	}
}

function getUser($id)
{
	include "db.php";
	global $usertable;
	$lSql = "SELECT * FROM $usertable WHERE id=:id";
	$lStmt = $db->prepare($lSql);
	$lStmt->execute(array(
							":id"=>$id
							));
	$lRes = $lStmt->fetch();	
	$db=null;
	return $lRes;
}

function getUsersMaxHealth($id)
{
	include "db.php";
	global $usertable;
	global $owneditemstable;
	
	$lSql = "SELECT healthMax FROM $usertable WHERE id=:id";
	$lStmt = $db->prepare($lSql);
	$lStmt->execute(array(":id"=>$id));
	$health = $lStmt->fetchColumn();
	
	$lSql = "SELECT SUM(ip_item.healthBonus) FROM ip_item 
			JOIN $owneditemstable ON ip_owned_items.item_id=ip_item.id 
			WHERE ip_owned_items.user_id=:id";
	$lStmt = $db->prepare($lSql);
	$lStmt->execute(array(":id"=>$id));
	$bonus = $lStmt->fetchColumn();
	
	$db = null;
	return $health+$bonus;
}

function getUsersMaxEnergy($id)
{
	include "db.php";
	global $usertable;
	global $owneditemstable;
	
	$lSql = "SELECT energyMax FROM $usertable WHERE id=:id";
	$lStmt = $db->prepare($lSql);
	$lStmt->execute(array(":id"=>$id));
	$energy = $lStmt->fetchColumn();
	
	$lSql = "SELECT SUM(ip_item.energyBonus) FROM ip_item 
			JOIN $owneditemstable ON ip_owned_items.item_id=ip_item.id 
			WHERE ip_owned_items.user_id=:id";
	$lStmt = $db->prepare($lSql);
	$lStmt->execute(array(":id"=>$id));
	$bonus = $lStmt->fetchColumn();
	
	$db = null;
	return $energy+$bonus;
}

function addHealth($id,$health)
{
	$user = getUser($id);
	$amount = Clamp($user['health']+$health,0,getUsersMaxHealth($id));
	
	$lSql = "UPDATE ip_users SET health=:hp WHERE id=:id";
	$lStmt = $db->prepare($lSql);
	$res = $lStmt->execute(array(":hp"=>$amount,":id"=>$id));
	
	return $res;
}

function Clamp($int, $min, $max)
{
	if(is_numeric($int) && is_numeric($min) && is_numeric($max))
	{
		if($int < $min)
			$int = $min;
		if($int > $max)
			$int = $max;
		return $int;
	}
}

function nextLevelExpNeeded($curr)
{
	//exp = 25*L*L - 25*l
	//25*L2 - 25*L - exp = 0
	return 25*($curr+1)*($curr+1) - 25*($curr+1);
}

?>