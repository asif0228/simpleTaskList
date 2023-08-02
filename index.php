<?php
// check if session exists
session_start();
if (isset($_SESSION) && isset($_SESSION['user'])) {
    //session already exists
    echo "<script>window.location.replace('task.php');</script>";
	die("Not Allowed");
}

//echo password_hash("admin", PASSWORD_DEFAULT);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Tasks - Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/main.css">
	<script src="assets/js/main.js"></script>
</head>
<body>
	<table>
		<tr class="col col-3">
			<td>&nbsp;</td>
			<td><h2>Task List Login</h2></td>
			<td>&nbsp;</td>
		</tr>
		<tr class="col col-3">
			<td>&nbsp;</td>
			<td class="c_red">
				<?php
					if(isset($_GET['msg'])) echo $_GET['msg'];
				?>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr class="col col-3">
			<td>&nbsp;</td>
			<td>
				<form class="login" method="post" action="login.php">
					<div class="container">
						<label for="uname"><b>Username</b></label>
						<input type="text" placeholder="Enter Username" name="uname" required>

						<label for="psw"><b>Password</b></label>
						<input type="password" placeholder="Enter Password" name="psw" required>

						<button type="submit">Login</button>
					</div>
				</form>
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</body>
</html>
