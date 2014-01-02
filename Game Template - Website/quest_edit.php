<?php
include_once "db.php";
include_once "library.php";
if(!isset($_POST['qid'])){echo "Error! ID is nonexistant!";exit;}

//$backtrack = $_SERVER['HTTP_REFERER'];

$id = $_POST['qid'];
$levelreq = $_POST['qlevelreq'];
$title = $_POST['qtitle'];
$description = $_POST['qdescription'];
$wdescription = $_POST['qwindescription'];
$ldescription = $_POST['qlosedescription'];
$ecost = $_POST['qenergycost'];
$expreward = $_POST['qexpreward'];
$goldreward = $_POST['qgoldreward'];


$sql = "UPDATE ip_quests SET levelReq=:lr,title=:title,description=:desc,winDescription=:wdesc,loseDescription=:ldesc,energyCost=:ec,expReward=:er,goldReward=:gr WHERE id=:id";
$stmt = $db->prepare($sql);
$res = $stmt->execute(array(
						":lr"=>$levelreq,
						":title"=>$title,
						":desc"=>$description,
						":wdesc"=>$wdescription,
						":ldesc"=>$ldescription,
						":ec"=>$ecost,
						":er"=>$expreward,
						":gr"=>$goldreward,
						":id"=>$id
						));

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
			$newx = 200; $newy = 200;
			if(imagesx($sourcefile)>imagesy($sourcefile))
				$newy = round($newx/imagesx($sourcefile)*imagesy($sourcefile));
			else
				$newx = round($newy/imagesy($sourcefile)*imagesx($sourcefile));
			$thumb = imagecreatetruecolor($newx,$newy);
			imagecopyresampled($thumb,$sourcefile,0,0,0,0,$newx,$newy,imagesx($sourcefile),imagesy($sourcefile));
			clearstatcache();
			imagejpeg($thumb,"quest_images/$id.png");
			chmod("quest_images/$id.png",0777);
			
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
echo ($stmt->rowCount() ? "Successful!" : "Failed!");

header("Location: admin_page.php?view=quests");
?>