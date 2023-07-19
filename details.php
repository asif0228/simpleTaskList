<?php
	include "db.php";
	include "Utility.php";
?>
<?php

// check if session exists
session_start();
if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    //session already exists
    echo "<script>window.location.replace('index.php');</script>";
	die("Not Allowed");
}

// Get Database
$db = new DB();
if(!$db){ // not connected
	echo "<script>window.location.replace('index.php?msg=Database Error.');</script>";
	die("Not Allowed");
}

// Get all user
$utility = new Utility();
$utility->setAllUser($db);

// Get single task list
$taskList = $db->getTaskListById($_GET['tlid']);

// Get all tasks with all events
$tasks = $db->getAllTaskWithEvents($_GET['tlid']);


// echo "<pre>";
// while($row = $tasks->fetchArray(SQLITE3_ASSOC) ) {
//   print_r($row);
// }
// echo "</pre>";

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
			<td><a href="task.php"><button>All Task List</button></a></td>
			<td>
				<h2><?php echo $taskList['name']; ?></h2>
				<p>
					Author: <?php echo $utility->resolveUser($taskList['user_id']); ?><br/>
					Date: <?php echo $taskList['date']; ?><br/>
					<form method="post" action="edit.php">
						<input type="hidden" name="tl_id" value="<?php echo $_GET['tlid']; ?>">
						<input type="hidden" name="id" value="<?php echo $_GET['tlid']; ?>">
						<input type="hidden" name="stat_type" value="1">
						Status: 
						<select class="form-control" name="tl_status" required>
							<?php echo $utility->getStatusOptions(); ?>
						</select> <button type="submit">Change</button><br/>
					</form>
					<script type="text/javascript">changeSelectElement("tl_status",0,<?php echo $taskList['status']; ?>);</script>
				</p>
			</td>
			<td><a href="logout.php"><button>Logout</button></a></td>
		</tr>
	</table>
	<table class="tbl">
		<tr class="login">
			<form method="POST" action="edit.php">
				<td>
					<input type="hidden" name="uid" value="<?php echo $_SESSION['user']['id']; ?>">
					<input type="hidden" name="uname" value="<?php echo $_SESSION['user']['name']; ?>">
					<input type="hidden" name="tl_id" value="<?php echo $_GET['tlid']; ?>">
					<input type="hidden" name="id" value="0">
					<input type="hidden" name="stat_type" value="3">
					<table>
						<tr>
							<td><input type="text" placeholder="Enter Task" name="task_name" required></td>
							<td>
								<select class="form-control" name="status" required>
									<?php echo $utility->getStatusOptions(); ?>
								</select>
							</td>
							<td>
								<button type="submit">Add Task</button>
							</td>
						</tr>
					</table>
				</td>
			</form>
		</tr>
		<?php
			$no=0;
			$last_task_id = null;
			while($row = $tasks->fetchArray(SQLITE3_ASSOC) ) {
				if($last_task_id!=$row['task_id']){
					if($last_task_id!=null){
						echo "<br/>";
						echo "<form method='post' action='edit.php'>";
						echo "<input type='hidden' name='uname' value='".$_SESSION['user']['name']."'>";
						echo "<input type='hidden' name='tl_id' value='".$_GET['tlid']."'>";
						echo "<input type='hidden' name='id' value='".$last_task_id."'>";
						echo "<input type='hidden' name='stat_type' value='4'>";
						echo "<input type='text' placeholder='Enter Comment' name='comment' required>";
						echo "<button type='submit'>Add Comment</button><br/>";
						echo "</form>";
						echo "</td></tr>";
					}
					$last_task_id=$row['task_id'];
					$no++;

					echo "<tr>";
					echo "<td>(".$no.") ".$row['name']."<br/>";
					
					echo "<br/>";
					echo "<form method='post' action='edit.php'>";
					echo "<input type='hidden' name='tl_id' value='".$_GET['tlid']."'>";
					echo "<input type='hidden' name='id' value='".$row['task_id']."'>";
					echo "<input type='hidden' name='stat_type' value='2'>";
					echo "Status: ";
					echo "<select class='form-control' name='task_status' required>";
					echo $utility->getStatusOptions();
					echo "</select>";
					echo "<button type='submit'>Change</button><br/>";
					echo "</form>";
					echo "<script type='text/javascript'>changeSelectElement('task_status',".($no-1).",".$row['status'].");</script>";

					// echo "<br/>Status: ".$utility->resolveStatus($row['status']);
					echo "<br/>Author: ".$utility->resolveUser($row['user_id']);
					echo "<br/><br/>-- Events --<br/>";
					if($row['event_type']==1) echo "<br/>".$row['date'].": ".$row['comment'];
					else if($row['event_type']==2) echo "<br/><small>".$row['date'].": ".$row['comment']."</small>";
				}else{
					if($row['event_type']==1) echo "<br/>".$row['date'].": ".$row['comment'];
					else if($row['event_type']==2) echo "<br/><small>".$row['date'].": ".$row['comment']."</small>";
				}
			}
			if($last_task_id!=null){
				echo "<br/>";
				echo "<form method='post' action='edit.php'>";
				echo "<input type='hidden' name='uname' value='".$_SESSION['user']['name']."'>";
				echo "<input type='hidden' name='tl_id' value='".$_GET['tlid']."'>";
				echo "<input type='hidden' name='id' value='".$last_task_id."'>";
				echo "<input type='hidden' name='stat_type' value='4'>";
				echo "<input type='text' placeholder='Enter Comment' name='comment' required>";
				echo "<button type='submit'>Add Comment</button><br/>";
				echo "</form>";
				echo "</td></tr>";
			}
		?>
	</table>
	
</body>
</html>


<?php $db->close(); ?>