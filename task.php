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

// Get single project
$prj = $db->getProjectById(1);

// Get all task lists
if(!isset($_GET['stat'])) $_GET['stat']=1;
$tasks = $db->getTaskList($prj['id'],$_GET['stat'],1,1000);


// print_r($prj);

// while($row = $tasks->fetchArray(SQLITE3_ASSOC) ) {
//   print_r($row);
// }

?>


<!DOCTYPE html>
<html>
<head>
	<title>Tasks - Task Lists</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/main.css">
	<script src="assets/js/main.js"></script>
</head>
<body>

	<table>
		<tr class="col col-3">
			<td>&nbsp;</td>
			<td><h2><?php echo $prj['name']; ?></h2></td>
			<td><a href="logout.php"><button>Logout</button></a></td>
		</tr>
		<tr>
			<td colspan="3">
				<table class="tbl">
					<tr>
						<td>No</td>
						<td>Date</td>
						<td>Task List Name</td>
						<td>Added By</td>
						<td>Status</td>
						<td>View</td>
					</tr>
					<?php
						$no=1;
						while($row = $tasks->fetchArray(SQLITE3_ASSOC) ) {
						  echo "<tr>";
						  echo "<td>".$no."</td>";
						  echo "<td>".$row['date']."</td>";
						  echo "<td>".$row['name']."</td>";
						  echo "<td>".$utility->resolveUser($row['user_id'])."</td>";
						  echo "<td>".$utility->resolveStatus($row['status'])."</td>";
						  echo "<td><a href='details.php?tlid=".$row['id']."'><button>View</button><a/></td>";
						  echo "</tr>";
						  $no++;
						}
					?>
					<tr class="login">
						<form method="POST" action="taskListAdd.php">
							<td><?php echo $no; ?></td>
							<td>
								<?php echo date("Y-m-d"); ?>
							</td>
							<td>
								<input type="text" placeholder="Task List Name" name="tlname" required>
							</td>
							<td>
								<?php echo $_SESSION['user']['name']; ?>
							</td>
							<td>
								<select class="form-control" name="status" required>
									<?php echo $utility->getStatusOptions(); ?>
								</select>
							</td>
							<td>
								<button>Add</button>
							</td>
						</form>
					</tr>
					<tr class="login">
						<td>Status</td>
						<td>
							<a href="?stat=1"><button>Active</button></a>
						</td>
						<td>
							<a href="?stat=2"><button>Inactive</button></a>
						</td>
						<td>
							<a href="?stat=3"><button>Testing</button></a>
						</td>
						<td>
							<a href="?stat=4"><button>Resolved</button></a>
						</td>
						<td>
							<a href="?stat=5"><button>Closed</button></a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
</body>
</html>


<?php $db->close(); ?>
