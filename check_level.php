<?php
$supposedToBe = floor(25 + sqrt(625+100*$loginRes['exp']))/50;//formula to get the level
if($loginRes['level'] != $supposedToBe)
{
	$sql = "UPDATE $usertable SET level=:level WHERE id=:uid";
	$stmt = $db->prepare($sql);
	$res = $stmt->execute(array(":level"=>$supposedToBe,
								":uid"=>$loginRes['id']));
	$loginSql = "SELECT * FROM $usertable WHERE id=:id";
	$loginStmt = $db->prepare($loginSql);
	$loginStmt->execute(array(
							":id"=>$_SESSION['login']
							));
	$loginRes = $loginStmt->fetch();
}
?>