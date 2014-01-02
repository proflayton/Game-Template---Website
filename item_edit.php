<?php
include_once "db.php";
include_once "library.php";
if(!isset($_POST['qid'])){echo "Error! ID is nonexistant!";exit;}

//$backtrack = $_SERVER['HTTP_REFERER'];

$name = $_POST['qname'];
$description = $_POST['qdescription'];
$healthbonus = $_POST['qhealthbonus'];
$energybonus = $_POST['qenergybonus'];
$cost = $_POST['qcost'];
$id = $_POST['qid'];

$sql = "UPDATE ip_item SET name=:name,description=:desc,healthBonus=:hb,energyBonus=:eb,cost=:c WHERE id=:id";
$stmt = $db->prepare($sql);
$res = $stmt->execute(array(
						":name"=>$name,
						":desc"=>$description,
						":hb"=>$healthbonus,
						":eb"=>$energybonus,
						":c"=>$cost,
						":id"=>$id
						));
echo ($stmt->rowCount() ? "Successful!" : "Failed!");


if(is_uploaded_file($_FILES['uploadPic']['tmp_name']))
{
	$fErrors = filterUploadFile($_FILES["uploadPic"]);
	if(empty($fErrors))
	{
		if($_FILES['uploadPic']["error"] > 0)
		{
			echo "Error: ".$_FILES["uploadPic"]["error"]."<br />";	
			exit;
		}
		else
		{
			$sourcefile = imagecreatefromstring(file_get_contents($_FILES["uploadPic"]["tmp_name"]));
			$newx = 100; $newy = 100;
			if(imagesx($sourcefile)>imagesy($sourcefile))
				$newy = round($newx/imagesx($sourcefile)*imagesy($sourcefile));
			else
				$newx = round($newy/imagesy($sourcefile)*imagesx($sourcefile));
			$thumb = imagecreatetruecolor($newx,$newy);
			imagecopyresampled($thumb,$sourcefile,0,0,0,0,$newx,$newy,imagesx($sourcefile),imagesy($sourcefile));
			clearstatcache();
			imagejpeg($thumb,"item_images/$id.png");
			chmod("item_images/$id.png",0777);
			//move_uploaded_file($_FILES["uploadPic"]["tmp_name"], .$_FILES["uploadPic"]["name"]);
		}
	}
	else
	{
		echo "Error uploading picture: ".$fErrors;
		exit;
	}
	unset($_FILES['uploadPic']);
}

header("Location: admin_page.php?view=items");
?>