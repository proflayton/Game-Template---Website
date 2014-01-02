<?php
include_once "db.php";
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8" />
		<script src="http://code.jquery.com/jquery-git2.js"></script>
		<title>Admin Page</title>
		<style>
			#viewDiv
			{
				text-align:center;
			}
			#questTable
			{
				margin:auto;
			}
		</style>
		<script>
			$('document').ready(function(){
				$('#viewUsers').click(function(){
					window.location="admin_page.php?view=users";
				});
				$('#viewQuests').click(function(){
					window.location="admin_page.php?view=quests";
				});
				$('#viewItems').click(function(){
					window.location="admin_page.php?view=items";
				})
				//Because I need to do file uploads, AJAX is not the best idea.
				/* 
				 $('#qupdate').click(function(){
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
						"quest_edit.php?id="+$('#qid').val()+"&levelreq="+$('#qlvlreq').val()+"&title="+$('#qtitle').val()+"&description="+$('#qdescription').val()+"&ecost="+$('#qenergycost').val()+"&windescription="+$('#qwindescription').val()+"&losedescription="+$('#qlosedescription').val()+"&expreward="+$('#qexpreward').val(),
						false);//dont do asynchronise so we can get a response text
					xmlhttp.send();
					alert(xmlhttp.responseText);
					window.location=document.URL;//refresh page
				});
				$('#qadd').click(function(){
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
						"quest_add.php?levelreq="+$('#qlvlreq').val()+"&title="+$('#qtitle').val()+"&description="+$('#qdescription').val()+"&windescription="+$('#qwindescription').val()+"&losedescription="+$('#qlosedescription').val()+"&ecost="+$('#qenergycost').val()+"&expreward="+$('#qexpreward').val(),
						false);//dont do asynchronise so we can get a response text
					xmlhttp.send();
					alert(xmlhttp.responseText);
					window.location="admin_page.php?view=quests";//refresh page
				});
				*/
			});
		</script>
	</head>
	<body>
		<div id="formDiv">
			<input type="button" id="viewUsers" value="View Users"/>
			<br/>
			<input type="button" id="viewQuests" value="View Quests"/>
			<br/>
			<input type="button" id="viewItems" value="View Items"/>
			<br/>
		</div>
		<br/>
		<div id="viewDiv">
			<?php
				if(isset($_GET['view']))
				{
					if($_GET['view']=='users')
					{
						echo "<h1>Users</h1><br/>";
						$sql = "SELECT * FROM $usertable";
						$stmt = $db->prepare($sql);
						$stmt->execute();
						echo "<table id='userTable' border='1px'>";
						echo "<tr>";
						echo "<th>ID</th>";
						echo "<th>Username</th>";
						echo "<th>Health</th>";
						echo "<th>Energy</th>";
						echo "<th>Exp</th>";
						echo "<th>Level</th>";
						//echo "<th>Edit</th>";
						echo "</tr>";
						while($row = $stmt->fetch())
						{
							echo "<tr>";
							echo "<td>".$row['id']."</td>";
							echo "<td>".$row['username']."</td>";
							echo "<td>".$row['health']."/".$row['healthMax']."</td>";
							echo "<td>".$row['energy']."/".$row['energyMax']."</td>";
							echo "<td>".$row['exp']."</td>";
							echo "<td>".$row['level']."</td>";
							//echo "<td><a href='admin_page.php?view=users&edit=".$row['id']."'><input type='button' id='editQuest' value='Edit Quest'/></td>";
							echo "</tr>";
						}
						echo "</table>";
					}
					else if($_GET['view']=='quests')
					{
						echo "<h1>Quest</h1><br/>";
						echo "<a href='admin_page.php?view=quests&add'><input type='button' id='addQuest' value='Add' /></a>";
						if(isset($_GET['add']))
						{
							echo "<form method='post' action='quest_add.php' enctype=\"multipart/form-data\">";
							echo "<table id='questTable' border='1px'>";
							echo "<tr>";
							echo "<th>Level Requirement</th>";
							echo "<th>Title</th>";
							echo "<th>Description</th>";
							echo "<th>Win Description</th>";
							echo "<th>Lose Description</th>";
							echo "<th>Energy Cost</th>";
							echo "<th>Reward</th>";
							echo "<th>Gold Reward</th>";
							echo "<th>Quest Image</th>";
							echo "<th></th>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>"."<input type='text' name='qlvlreq' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qtitle' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qdescription' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qwindescription' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qlosedescription' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qenergycost' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qexpreward' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qgoldreward' value=''/>"."</td>";;
							echo "<td>"."<input type='file' id='uploadPic' name='uploadPic' value='Update Quest Image' />"."</td>";
							echo "<td>"."<input type='submit' id='qadd' value='Add' />"."</td>";
							echo "</tr>";
							echo "</form>";
							echo "</table>";
						}
						else if(isset($_GET['edit']))
						{
							$qID = $_GET['edit'];
							$sql = "SELECT * FROM $questtable WHERE id=:id";
							$stmt = $db->prepare($sql);
							$stmt->execute(array(":id"=>$qID));
							$row = $stmt->fetch();
							echo "<form method='post' action='quest_edit.php'  enctype='multipart/form-data'>";
							echo "<table id='questTable' border='1px'>";
							echo "<tr>";
							echo "<th>Level Requirement</th>";
							echo "<th>Title</th>";
							echo "<th>Description</th>";
							echo "<th>Win Description</th>";
							echo "<th>Lose Description</th>";
							echo "<th>Energy Cost</th>";
							echo "<th>Reward</th>";
							echo "<th>Gold Reward</th>";
							echo "<th>Quest Image</th>";
							echo "</tr>";
							echo "<tr>";
							echo "<input type='hidden' name='qid' value='".$qID."'/>";
							echo "<td>"."<input type='text' name='qlevelreq' value='".$row['levelReq']."'/>"."</td>";
							echo "<td>"."<input type='text' name='qtitle' value='".$row['title']."'/>"."</td>";
							echo "<td>"."<input type='text' name='qdescription' value='".$row['description']."'/>"."</td>";
							echo "<td>"."<input type='text' name='qwindescription' value='".$row['winDescription']."'/>"."</td>";
							echo "<td>"."<input type='text' name='qlosedescription' value='".$row['loseDescription']."'/>"."</td>";
							echo "<td>"."<input type='text' name='qenergycost' value='".$row['energyCost']."'/>"."</td>";
							echo "<td>"."<input type='text' name='qexpreward' value='".$row['expReward']."'/>"."</td>";
							echo "<td>"."<input type='text' name='qgoldreward' value='".$row['goldReward']."'/>"."</td>";
							echo "<td>"."<input type='file' id='uploadPic' name='uploadPic' value='Update Quest Image' />"."</td>";
							echo "<td>"."<input type='submit' id='qupdate' value='Update' />"."</td>";
							echo "</tr>";
							echo "</form>";
							echo "</table>";	
						}
						else
						{
							$sql = "SELECT * FROM $questtable";
							$stmt = $db->prepare($sql);
							$stmt->execute();
							echo "<table id='questTable' border='1px'>";
							echo "<tr>";
							echo "<th>Level Requirement</th>";
							echo "<th>Title</th>";
							echo "<th>Description</th>";
							echo "<th>Win Description</th>";
							echo "<th>Lose Description</th>";
							echo "<th>Energy Cost</th>";
							echo "<th>Reward</th>";
							echo "<th>Gold Reward</th>";
							echo "<th>Quest Image</th>";
							echo "<th>Edit</th>";
							echo "</tr>";
							while($row = $stmt->fetch())
							{
								echo "<tr>";
								echo "<td>".$row['levelReq']."</td>";
								echo "<td>".$row['title']."</td>";
								echo "<td>".$row['description']."</td>";
								echo "<td>".$row['winDescription']."</td>";
								echo "<td>".$row['loseDescription']."</td>";
								echo "<td>".$row['energyCost']."</td>";
								echo "<td>".$row['expReward']."</td>";
								echo "<td>".$row['goldReward']."</td>";
								$imgDir = 'quest_images/'.$row['id'].'.png';
								$imgExists = file_exists($imgDir);
								echo "<td><img src='".($imgExists?$imgDir:'quest_images/default.png')."' alt=''/></td>";
								echo "<td><a href='admin_page.php?view=quests&edit=".$row['id']."'><input type='button' id='editQuest' value='Edit Quest'/></td>";
								echo "</tr>";
							}
							echo "</table>";	
						}
					}
					else if($_GET['view']=='items')
					{
						echo "<h1>Items</h1>";
						echo "<a href='admin_page.php?view=items&add'><input type='button' id='addQuest' value='Add' /></a>";
						if(isset($_GET['add']))
						{
							echo "<form method='post' action='item_add.php' enctype=\"multipart/form-data\">";
							echo "<table id='itemTable' border='1px' style='width:50%;margin:auto;'>";
							echo "<tr>";
							echo "<th>Name</th>";
							echo "<th>Description</th>";
							echo "<th>Health Bonus</th>";
							echo "<th>Energy Bonus</th>";
							echo "<th>Cost</th>";
							echo "<th>Item Image</th>";
							echo "</tr>";
							echo "<tr>";
							echo "<td>"."<input type='text' name='qname' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qdescription' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qhealthbonus' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qenergybonus' value=''/ required>"."</td>";
							echo "<td>"."<input type='text' name='qcost' value=''/ required>"."</td>";
							echo "<td>"."<input type='file' id='uploadPic' name='uploadPic' value='Upload Item Image' />"."</td>";
							echo "<td>"."<input type='submit' id='qadd' value='Add' />"."</td>";
							echo "</tr>";
							echo "</form>";
							echo "</table>";
						}
						else if(isset($_GET['edit']))
						{
							$sql = "SELECT * FROM $itemstable WHERE id=:id";
							$stmt = $db->prepare($sql);
							$stmt->execute(array(":id"=>$_GET['edit']));
							$item = $stmt->fetch();
							
							echo "<form method='post' action='item_edit.php' enctype=\"multipart/form-data\">";
							echo "<input type='hidden' name='qid' value='".$_GET['edit']."' />";
							echo "<table border='1px' style='width:50%;margin:auto;'>";
							echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Health Bonus</th><th>Energy Bonus</th><th>Cost</th><th>Image</th><th></th></tr>";
							echo "<tr>";
							echo "<td>".$_GET['edit']."</td>";
							echo "<td>"."<input type='text' name='qname' value='".$item['name']."'/ required>"."</td>";
							echo "<td>"."<input type='text' name='qdescription' value='".$item['description']."'/ required>"."</td>";
							echo "<td>"."<input type='text' name='qhealthbonus' value='".$item['healthBonus']."'/ required>"."</td>";
							echo "<td>"."<input type='text' name='qenergybonus' value='".$item['energyBonus']."'/ required>"."</td>";
							echo "<td>"."<input type='text' name='qcost' value='".$item['cost']."'/ required>"."</td>";
							echo "<td>"."<input type='file' id='uploadPic' name='uploadPic' value='Update Item Image' />"."</td>";
							echo "<td>"."<input type='submit' id='qadd' value='Update' />"."</td>";
							echo "</tr>";
							echo "</form>";
							
							echo "</table>";
						}
						else
						{
							$sql = "SELECT * FROM $itemstable ORDER BY id ASC";
							$stmt = $db->prepare($sql);
							$stmt->execute();
							
							echo "<table border='1px' style='width:50%;margin:auto;'>";
							echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Health Bonus</th><th>Energy Bonus</th><th>Cost</th><th>Image</th><th></th></tr>";
							$rows = $stmt->fetchAll();
							foreach($rows as $row)
							{
								$imgDir = 'item_images/'.$row['id'].'.png';
								$imgExists = file_exists($imgDir);
								echo "<tr>";
								echo "<td>".$row['id']."</td>";
								echo "<td>".$row['name']."</td>";
								echo "<td>".$row['description']."</td>";
								echo "<td>".$row['healthBonus']."</td>";
								echo "<td>".$row['energyBonus']."</td>";
								echo "<td>".$row['cost']."</td>";
								echo "<td><img src='".($imgExists?$imgDir:"item_images/default.png")."' alt='".$row['name']."' /></td>";
								echo "<td><a href='admin_page.php?view=items&edit=".$row['id']."'><input type='button' id='editItem' value='Edit Item'/></td>";
								echo "<tr>";
							}
							echo "</table>";
						}
					}
					else if ($_GET['view']=='shops')
					{
						
					}
				}
				else
				{
					echo "No view<br/>";
				}
			?>
		</div>
	</body>
</html>
<?php
$db=null;
?>