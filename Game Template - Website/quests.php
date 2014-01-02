<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Quests</title>
		<style>
		#questTable
		{
			margin:auto;
		}
		</style>
		<script src="http://code.jquery.com/jquery-git2.js"></script>
		<script>
			function doQuest(e)
			{
				var id = $(e).val();
				var xmlhttp;
				if(window.XMLHttpRequest)
				{
					xmlhttp=new XMLHttpRequest();
				}
				else
				{
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET",
					"attempt_quest.php?id="+id+"&uid="+$("#uid").val(),
					false);//dont do asynchronise so we can get a response text
				xmlhttp.send();
				var data = xmlhttp.responseText;
				alert(data);
				
				window.location="quests.php";//refresh page
			}
		</script>
	</head>
	<body>
		<div id="content">
			<?php include "navbar.php" ?>
			<?php
				include "db.php";
				
				$sql = "SELECT * FROM $questtable WHERE levelReq<=:lvl";
				$stmt = $db->prepare($sql);
				$stmt->execute(array(":lvl"=>$loginRes['level']));
				echo "<table id='questTable' border='1px'>";
				echo "<tr>";
				echo "<th></th>";
				echo "<th>Level Requirement</th>";
				echo "<th>Title</th>";
				echo "<th>Description</th>";
				echo "<th>Energy Cost</th>";
				echo "<th>Exp Reward</th>";
				echo "<th>Gold Reward</th>";
				echo "<th>Do Quest</th>";
				echo "</tr>";
				echo "<input type='hidden' id='uid' value=".$loginRes['id']." />";
				while($row = $stmt->fetch())
				{
					echo "<tr>";
					$imgDir = 'quest_images/'.$row['id'].'.png';
					$imgExists = file_exists($imgDir);
					echo "<td><img src='".($imgExists?$imgDir:'quest_images/default.png')."' alt=''/></td>";
					echo "<td>".$row['levelReq']."</td>";
					echo "<td>".$row['title']."</td>";
					echo "<td>".$row['description']."</td>";
					echo "<td>".$row['energyCost']."</td>";
					echo "<td>".$row['expReward']."</td>";
					echo "<td>".$row['goldReward']."</td>";
					echo "<td><button onclick='doQuest(this)' value='".$row['id']."'>Do Quest</button></td>";
					echo "</tr>";
				}
				echo "</table>";
				$db = null;
			?>
		</div>
	</body>
</html>