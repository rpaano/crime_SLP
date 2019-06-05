<?php 

	$id = $_GET['id'] or die("Illegal Entry!!");

	//echo $id;
	
	
		$connect = mysqli_connect("localhost", "root", "") or die ("cannot connect");
		$mydb=mysqli_select_db($connect, "case_db") or die ("cannot connect");

		$query = mysqli_query($connect, "DELETE FROM `pending_cases` WHERE `pending_cases`.`admin_id` = '$id'") or die ("could not delete!!!");

		$query = mysqli_query($connect, "DELETE FROM `admin` WHERE `admin`.`admin_id` = '$id'") or die ("could not delete!!");

		echo "<script type='text/javascript'>alert('Succesfully delete!!'); window.location.replace('index.php');</script>";
 ?>

