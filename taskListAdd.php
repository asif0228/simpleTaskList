<?php
	include "db.php";
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

// Get data
$tlname = filter_var(strip_tags(trim($_POST['tlname'])),FILTER_SANITIZE_STRING);
$status = intval(filter_var(strip_tags(trim($_POST['status'])),FILTER_SANITIZE_STRING));

// Add Data
$db->addNewTaskList(1,$_SESSION['user']['id'],$tlname,date("Y-m-d"),$status);

?>

<?php $db->close(); ?>



<script>window.location.replace('task.php');</script>
