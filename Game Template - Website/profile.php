<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8" />
		<script src="http://code.jquery.com/jquery-git2.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Profile</title>
	</head>
	<body>
		<div id="content">
			<?php include "navbar.php"; ?>
			<div id="inventoryDiv">
				<?php
				include "db.php";
				$sql = "SELECT * FROM ip_item JOIN ip_owned_items ON ip_owned_items.item_id = ip_item.id WHERE ip_owned_items.user_id=:id";
				$stmt = $db->prepare($sql);
				$stmt->execute(array(":id"=>$_SESSION['login']));
				$items = $stmt->fetchAll();
				echo "<h2>Inventory:</h2>";
				echo "<table id='items' style='margin:auto;width:50%;'>";
				foreach($items as $item)
				{
					echo "<tr>";
					echo "<td>";
						echo $item['name'];
					echo "</td>";
					echo "<td>";
						$imgDir = 'item_images/'.$item['id'].'.png';
						$imgExists = file_exists($imgDir);
					echo "<img src='".($imgExists?$imgDir:"item_images/default.png")."' alt='".$item['name']."' /> <br/>";
					echo "</td>";
					echo "<td>";
					echo "</td>";
					echo "<td>";
					echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
				$db = null;
				?>
			</div>
		</div>
	</body>
</html>