<?php
include_once "db.php";
if(!isset($_GET['id'])){echo "Error!";exit;}
$id = $_GET['id'];
$uid = $_GET['uid'];

$sql = "SELECT * FROM $questtable WHERE id=:id";
$stmt = $db->prepare($sql);
$res = $stmt->execute(array( ":id"=>$id ));
if(!$res)
{echo "ERROR GETTING QUEST"; exit;}
$quest = $stmt->fetch();

$sql = "SELECT * FROM $usertable WHERE id=:uid";
$stmt = $db->prepare($sql);
$res = $stmt->execute(array(":uid"=>$uid));
if(!$res)
{echo "ERROR GETTING QUEST"; exit;}
$user = $stmt->fetch();
if($user['level'] < $quest['levelReq'])
{
echo "You aren't strong enough to do that quest yet!";
exit;	
}
if($user['energy']<$quest['energyCost'])
{
	echo "You don't have enough energy to do that quest!";
	exit;
}

$sql = "SELECT * FROM $questsdonetable WHERE qid=:qid AND uid=:uid";
$stmt = $db->prepare($sql);
$stmt->execute(array(":qid"=>$id,":uid"=>$uid));
//echo count($stmt->fetchAll());
if($stmt->fetch())
{
	$sql  = "UPDATE $questsdonetable SET count=count+1 WHERE qid=:qid AND uid=:uid";
	$stmt = $db->prepare($sql);
	$res  = $stmt->execute(array(":qid"=>$id,":uid"=>$uid));
	if(!$res)
	{echo "ERROR DOING QUEST"; exit;}
}
else
{
	$sql = "INSERT INTO $questsdonetable (qid,uid,count) VALUES (:qid,:uid,1)";
	$stmt = $db->prepare($sql);
	$res = $stmt->execute(array(":qid"=>$id,":uid"=>$uid));
	if(!$res)
	{echo "ERROR DOING QUEST"; exit;}
}

//if we just used some energy and had max, then lets reset the energy time counter
if($user['energy'] == $user['energyMax'])
{
	$sql = "UPDATE $usertable SET lastEnergyGiven=:leg WHERE id=:uid";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(":leg"=>time(), ":uid"=>$uid));
}

$sql = "UPDATE $usertable SET exp=:exp, energy=:energy, gold=:gold WHERE id=:uid";
$stmt = $db->prepare($sql);
$res = $stmt->execute(array(":exp"=>$user['exp']+$quest['expReward'],
							":energy"=>$user['energy']-$quest['energyCost'],
							":gold"=>$user['gold']+$quest['goldReward'],
							":uid"=>$uid));
	
echo $quest['winDescription'];
?>