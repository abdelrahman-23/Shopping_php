<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

?>
<?php include '../Project/index.html'; ?>
<style>
<?php include '../Project/index.css'; ?>
</style>








