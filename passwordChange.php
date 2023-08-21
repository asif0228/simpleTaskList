<?php
	include "db.php";
	// include "Utility.php";
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


$db->changePassword($_SESSION['user']['id'],$_POST['newPass']);

?>

<?php $db->close(); ?>



<script>window.location.replace('<?php echo $_POST['returnPage']; ?>');</script>