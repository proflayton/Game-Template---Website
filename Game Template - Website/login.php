<!DOCTYLE HTML>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Login/Register</title>
		<style>
		body
		{
			width:100%;
			height:100%;
			background:#B7C0C9;
			font:sans-serif;
			
			padding-left:20px;
		}
			.loginDiv, .registerDiv
			{
				border: 3px solid #30475E;
				background: #2179D1;
				width:300px;
			}
			.buffer
			{
				padding:10px;
			}
			h2
			{
				margin:5px;
			}
		</style>
		<script>
			
		</script>
	</head>
	<body>
		<div style="padding:20px"></div>
		<?php
		if(isset($_GET['error']))
		{
			if($_GET['error']=='loginFailed')
				echo "<span style='color:red; font-weight:bold;'>Failed to login! Make sure you used your correct username and password</span>";
		}
		?>
		<div class="loginDiv">
			<h2>Login:</h2>
			<form id="loginForm" method="post" action="login_auth.php">
				Username: <input type="text" name="username"/> <br/>
				Password: <input type="password" name="password"/><br/>
				<input type="submit" name="submitLogin" value="Login" /><br/>
			</form><br/>
		</div>
		<div class="buffer"></div>
		<div class="registerDiv">
			<h2>Register:</h2>
			<form id="registerForm" method="post" action="register_auth.php">
				Username: <input type="text" name="username"/><br/>
				Password: <input type="password" name="password"/><br/>
				<input type="submit" name="submitRegister" value="Register" /><br/>
			</form>
		</div>
	</body>
</html>