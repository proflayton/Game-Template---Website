<!DOCTYPE HTML>
<?php session_start(); ?>
<html>
	<head>
		<meta charset="utf-8" />
		<script src="http://code.jquery.com/jquery-git2.js"></script>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Barter</title>
		<script>
			
		</script>
	</head>
	<body >
		<div id="content">
		<?php
			if(isset($_GET['buy']))
			{
				$id = $_GET['buy'];
				
				include "db.php";
				$sql = "SELECT cost FROM $itemstable WHERE id=:id";
				$stmt = $db->prepare($sql);
				$stmt->execute(array(":id"=>$id));
				$itemCost = $stmt->fetchColumn();
				
				$sql = "SELECT gold FROM $usertable WHERE id=:id";
				$stmt = $db->prepare($sql);
				$stmt->execute(array(":id"=>$_SESSION['login']));
				$myGold = $stmt->fetchColumn();
				if($itemCost > $myGold)
				{
					include "navbar.php";//we do it here so that if needed, we get all the updated user stats
					echo "<span style='font-weight:bold;color:red;'>You don't have enough gold!</span></br>";
				}
				else
				{
					$sql = "UPDATE $usertable SET gold=:gold WHERE id=:uuid;
							INSERT INTO $owneditemstable (item_id,user_id) VALUES (:iid,:uid)";
					$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);//allows multiple queries at once
					$stmt = $db->prepare($sql);
					$stmt->execute(array(
								":iid"=>$id,
								":uid"=>$_SESSION['login'],
								":uuid"=>$_SESSION['login'],
								":gold"=>$myGold-$itemCost));
					include "navbar.php";//waited till now because now we have new gold than before
					echo "<span style='font-weight:bold;color:green;'>You successfully bought the item!</span></br>";
				}
				$db = null;
			}
			else
			{
				include "navbar.php";//we do it here so that if needed, we get all the updated user stats
			}
			if(isset($_GET['shop']))
			{
				echo "<a href='barter.php' class='unstyledLink'>Leave Shop</a>";
				include "db.php";
				$id = $_GET['shop'];
				$sql = "SELECT * FROM $shopstable WHERE id=:id";
				$stmt = $db->prepare($sql);
				$stmt->execute(array(":id"=>$id));
				$shopInfo = $stmt->fetch();
				
				$sql = "SELECT ip_item.id,ip_item.name,ip_item.description,ip_item.healthBonus,ip_item.energyBonus,ip_item.cost FROM ip_item 
						JOIN ip_shop_items ON ip_shop_items.item_id = ip_item.id
						WHERE ip_shop_items.shop_id = :id";
				$stmt = $db->prepare($sql);
				$stmt->execute(array(":id"=>$id));
				$itemsInfo = $stmt->fetchAll();
				
				echo "<h1>".$shopInfo['name']."</h1>";
				echo "<h3>".$shopInfo['description']."</h3>";
				echo "<table border='1px' style='width:75%; margin:auto;'>";
				echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Health Bonus</th><th>Energy Bonus</th><th>Cost</th><th>Image</th><th></th></tr>";
				foreach($itemsInfo as $row)
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
					echo "<td><a href='barter.php?shop=$id&buy=".$row['id']."'><input type='button' id='buyItem' value='Buy!'/></td>";
					echo "<tr>";
				}
				echo "</table>";
				
				$db = null;
			}
			else
			{
				include "db.php";
				$sql = "SELECT * FROM $shopstable";
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$shops = $stmt->fetchAll();
				foreach($shops as $shop)
				{
					echo "<div style='width:75%; margin:auto; border:2px solid #000';>";
					echo "<h2>".$shop['name']."</h2>";
					echo "<h3>".$shop['description']."</h3>";
					echo "<h4><a href='barter.php?shop=".$shop['id']."'  class='unstyledLink'>ENTER SHOP</a></h4>";
					echo "</div>";
				}
				$db = null;
			}
		?>
		</div>
	</body>
</html>