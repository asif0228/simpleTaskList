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

// Get Data
$id = intval(filter_var(strip_tags(trim($_POST['id'])),FILTER_SANITIZE_STRING));
$tl_id = intval(filter_var(strip_tags(trim($_POST['tl_id'])),FILTER_SANITIZE_STRING));
$stat_type = intval(filter_var(strip_tags(trim($_POST['stat_type'])),FILTER_SANITIZE_STRING));

// Change Status
if($stat_type==1){ // Changing Task list status
	$tl_status = intval(filter_var(strip_tags(trim($_POST['tl_status'])),FILTER_SANITIZE_STRING));
	$db->updateTaskListStatus($id,$tl_status);
}else if($stat_type==2){
	$utility = new Utility();
	$task_status = intval(filter_var(strip_tags(trim($_POST['task_status'])),FILTER_SANITIZE_STRING));
	$uname = filter_var(strip_tags(trim($_POST['uname'])),FILTER_SANITIZE_STRING);
	$db->updateTaskStatus($id,$task_status,$uname,$utility->getStatusList());
}else if($stat_type==3){
	$uid = intval(filter_var(strip_tags(trim($_POST['uid'])),FILTER_SANITIZE_STRING));
	$task_name = filter_var(strip_tags(trim($_POST['task_name'])),FILTER_SANITIZE_STRING);
	$status = intval(filter_var(strip_tags(trim($_POST['status'])),FILTER_SANITIZE_STRING));
	$uname = filter_var(strip_tags(trim($_POST['uname'])),FILTER_SANITIZE_STRING);
	$db->addTaskInTaskList($tl_id,$uid,$task_name,$status,$uname);
}else if($stat_type==4){
	$comment = filter_var(strip_tags(trim($_POST['comment'])),FILTER_SANITIZE_STRING);
	$uname = filter_var(strip_tags(trim($_POST['uname'])),FILTER_SANITIZE_STRING);
	$db->addCommentInTask($id,$comment,$uname);
}


?>

<script>window.location.replace('details.php?tlid=<?php echo $tl_id; ?>');</script>

<?php $db->close(); ?>
