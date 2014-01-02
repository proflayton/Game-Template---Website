<?php
include_once "db.php";
include_once "library.php";
if(!isset($_POST['qname'])){echo "Error!";exit;}

//$backtrack = $_SERVER['HTTP_REFERER'];

$name = $_POST['qname'];
$description = $_POST['qdescription'];
$healthbonus = $_POST['qhealthbonus'];
$energybonus = $_POST['qenergybonus'];
$cost = $_POST['qcost'];

$sql = "INSERT INTO ip_item (name,description,healthBonus,energyBonus,cost) VALUES (:name,:desc,:hb,:eb,:c)";
$stmt = $db->prepare($sql);
$res = $stmt->execute(array(
						":name"=>$name,
						":desc"=>$description,
						":hb"=>$healthbonus,
						":eb"=>$energybonus,
						":c"=>$cost
						));
echo ($response = ($stmt->rowCount() ? "Successful!" : "Failed!"));
if($response == "Failed!")
	exit;

if(is_uploaded_file($_FILES['uploadPic']['tmp_name']))
{
	$id = $db->lastInsertId();
	
	$fErrors = filterUploadFile($_FILES["uploadPic"]);
	if(empty($fErrors))
	{
		if($_FILES['uploadPic']["error"] > 0)
		{
			echo "Error: ".$_FILES["uploadPic"]["error"]."<br />";	
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