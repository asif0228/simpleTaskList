<?php
// start session
session_start();

// remove all session variables
session_unset();

// destroy the session
session_destroy();

?>

<script>window.location.replace('index.php');</script>