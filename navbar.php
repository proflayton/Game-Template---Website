<html>
	<?php
	include_once "library.php";
	include "db.php";
	
	$loginRes = getUser($_SESSION['login']);
	if($loginRes['exp'] >= nextLevelExpNeeded($loginRes['level']))
	{
		$sql = "UPDATE $usertable SET level=:level WHERE id=:uid";
		$stmt = $db->prepare($sql);
		$res = $stmt->execute(array(":level"=>$loginRes['level']+1,
									":uid"=>$loginRes['id']));
		$loginRes = getUser($_SESSION['login']);
	}
	
		//echo $loginRes['lastEnergyGiven'];
		//echo "   ";
		//echo time();
	if(time() >= $loginRes['lastEnergyGiven']+5*60 && $loginRes['energy']<getUsersMaxEnergy($_SESSION['login']))
	{
		$add = floor((time()-$loginRes['lastEnergyGiven'])/(5*60));
		$sql = "UPDATE $usertable SET energy=:energy, lastEnergyGiven=:lastEnergy WHERE id=:uid";
		$stmt = $db->prepare($sql);
		$res = $stmt->execute(array(":energy"=>Clamp($loginRes['energy']+$add,0,$loginRes['energyMax']),
									":lastEnergy"=>time(),
									":uid"=>$loginRes['id']));
		$loginRes = getUser($_SESSION['login']);
	}
		
	
		
	?>
	<head>
		<script src="http://code.jquery.com/jquery-git2.js"></script>
		<script>
			var energyUpdateID = setInterval(function(){updateEnergyTime()}, 1000);
				
			function updateEnergyTime()
			{
				var xmlhttp;
				var minutes = 0;
				var seconds = 0;
				var time;
				if(window.XMLHttpRequest)
				{
					xmlhttp=new XMLHttpRequest();
				}
				else
				{
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.open("GET",
					"getEnergyTime.php?time="+$('#lastGivenTime').val(),
					false);//dont do asynchronise so we can get a response text
				xmlhttp.send();
				
				time = xmlhttp.responseText;
				if(time <= 0)
				{
					clearInterval(energyUpdateID);
					$('#energyTime').text("Ready");
					return;
				}
				//time = Math.round(time*10)/10;
				
				seconds = time*60;
				minutes = Math.floor(time);
				seconds -= minutes*60;
				seconds = Math.round(seconds);
				
				$('#energyTime').text(minutes + ":" + (seconds<10?"0":"") + seconds);
			}
			
			$('document').ready(function(){
				updateEnergyTime();//manually do it once
			});
		</script>
	</head>
	<div id="headerDiv">
		Simple Game
	</div>
	<div id="statusDiv">
		<table id='statusTable'>
		<?php
		
			echo "<input type='hidden' id='lastGivenTime' value='".$loginRes['lastEnergyGiven']."' />";
			echo "<tr>";
			echo "<td id='nameAndExp'>".$loginRes['username']." (".$loginRes['level'].")     [".
						$loginRes['exp']."/".nextLevelExpNeeded($loginRes['level'])."]</td>";
			echo "<td id='HP'>Health:".$loginRes['health']."/".getUsersMaxHealth($_SESSION['login'])."     </td>";
			echo "<td id='Gold'><span style='vertical-align:middle'>".$loginRes['gold']."</span><img src='images/goldCoin1.png' alt='gold' style='vertical-align:middle'/></td>";
			echo "<td id='Energy'>Energy:".$loginRes['energy']."/".getUsersMaxEnergy($_SESSION['login'])."     [<span id='energyTime'></span>]</td>";
			echo "</tr>";
		?>
		</table>
		<div id="logoutDiv">
			<a href="index.php?logout=1"><input type="button" id="logoutButton" name="logoutButton" value="Logout" /></a>
		</div>
		<div class="clearDiv">
			
		</div>
	</div>
	<table id="navBar">
	<tr>
		<td id="navBar_Home" href='index.php'><a href='index.php' class='unstyledLink'>Home</a></td>
		<td id="navBar_Quests" href='quests.php'><a href='quests.php' class='unstyledLink'>Quests</a></td>
		<td id="navBar_Barter" href='barter.php'><a href='barter.php' class='unstyledLink'>Barter</a></td>
		<td id="navBar_Profile"><a href='profile.php' class='unstyledLink'>Profile</a></td>
	</tr>
	</table>
</html>
<?php $db = null; ?>