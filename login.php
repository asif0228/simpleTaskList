<?php include "db.php" ?>
<?php

// check if session exists
session_start();
if (isset($_SESSION) && isset($_SESSION['user'])) {
    //session already exists
    echo "<script>window.location.replace('task.php');</script>";
	die("Not Allowed");
}

// Check Method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
}else{
	echo "<script>window.location.replace('index.php?msg=Please Login Correctly.');</script>";
	die("Not Allowed");
}

// Check username
$un = filter_var(strip_tags(trim($_POST['uname'])),FILTER_SANITIZE_STRING);
$pw = filter_var(strip_tags(trim($_POST['psw'])),FILTER_SANITIZE_STRING);
if($un==null || $un===""){
	echo "<script>window.location.replace('index.php?msg=Enter Username Correctly.');</script>";
	die("Not Allowed");
}

// Check DB
$db = new DB();
if(!$db){ // not connected
	echo "<script>window.location.replace('index.php?msg=Database Error.');</script>";
	die("Not Allowed");
}

// Get User & verify status password
$user = $db->getUserByUsername($un);
if(!$user){
	echo "<script>window.location.replace('index.php?msg=User or Password not error.');</script>";
	die("Not Allowed");
}
if($user['status']!=1){
	echo "<script>window.location.replace('index.php?msg=Inactive User.');</script>";
	die("Not Allowed");
}
if(!password_verify($pw,$user['password'])){ // Password not matching
	echo "<script>window.location.replace('index.php?msg=Username or Password error.');</script>";
	die("Not Allowed");
}
$user['password'] = "";

// Create Session
$_SESSION["user"] = $user;
print_r($_SESSION);

?>

<?php $db->close(); ?>



<script>window.location.replace('task.php');</script>
