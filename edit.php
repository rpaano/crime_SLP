<?php

	$id = $_GET['id'] or die ('Illegal Entry!!') or $_GET['form'];
	//echo $id;

	$connect = mysqli_connect("localhost", "root", "") or die ("cannot connect");
	$mydb=mysqli_select_db($connect, "case_db") or die ("cannot connect");

	$plahold = "";
    
    date_default_timezone_set('Asia/Manila');

	$date = date('Y-m-d\TH:i');
	$brgycasenumber = "";
	$complainant = "";
	$respondent = "";
	$natureofcase = "";
	//$disposition_remarks = "";
	$status = "";

	if ($_SERVER['REQUEST_METHOD'] == "POST"){

		$dates = $_POST['dates']; 
		$sec = strtotime($dates); 
		$dates = date("Y-F-d h:i a", $sec);
		//echo $dates;
		$brgycasenumber = $_POST['brgycasenumber'];
		$complainant = $_POST['complainant'];
		$respondent = $_POST['respondent'];
		$natureofcase = $_POST['natureofcase'];
		$disposition_remarks = $_POST['disposition_remarks'];
		$status = $_POST['status'];
		

		//echo $status;


		if ($dates != NULL and $brgycasenumber != NULL and $complainant != NULL and $respondent != NULL and $natureofcase != NULL and $disposition_remarks != NULL){

			if (($status != "Closed Case" or $disposition_remarks != "Pending")and($status != "Pending Case" or $disposition_remarks != "Dismissed") and ($status != "Pending Case" or $disposition_remarks != "Withdrawn")){

				$query = mysqli_query($connect, "UPDATE `admin` SET `date_time` = '$dates', `brgycasenumber` = '$brgycasenumber', `complainant` = '$complainant', `respondent` = '$respondent', `natureofcase` = '$natureofcase', `disposition_remarks` = '$disposition_remarks' WHERE `admin`.`admin_id` = '$id';") or die ("could not edit");

				
				//echo $id;


				$query1 = mysqli_query($connect, "UPDATE `pending_cases` SET `status` = '$status' WHERE `pending_cases`.`admin_id` = '$id';") or die ("could not edit");

				echo "<script type='text/javascript'>alert('Succesfully Edited!!'); window.location.replace('index.php');</script>";
			}
			else
			{
				echo "<script type='text/javascript'>alert('Pending Case cannot be select if Disposition/Remarks is Dismissed or Withdrawn and Disposition/Remarks is Pending while the Status is Closed Case'); </script>";
			}

		}else{
			echo "<script type='text/javascript'>alert('Please Fill-up all Fields');</script>";
			$myDateTime = DateTime::createFromFormat('Y-F-d h:i a', $dates);
			$dates = $myDateTime->format('Y-m-d\TH:i');
			$date = $dates;


		}
			

	}else{

		$query = mysqli_query($connect, "SELECT * FROM `admin` inner join `pending_cases` ON `admin`.`admin_id` = `pending_cases`.`admin_id` and `admin`.`admin_id` = '$id'") or die ("could not select");

		while($row = mysqli_fetch_array($query)){
			$myDateTime = DateTime::createFromFormat('Y-F-d h:i a', $row['date_time']);
			$date = $myDateTime->format('Y-m-d\TH:i');
			//echo $date;
			$brgycasenumber = $row['brgycasenumber'];
			$complainant = $row['complainant'];
			$respondent = $row['respondent'];
			$natureofcase = $row['natureofcase'];
			$disposition_remarks = $row['disposition_remarks'];
			//echo $disposition_remarks;
			$status = $row['status'];
		}

	}



?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style>

	* {box-sizing: border-box;}

	body {
	  margin: 0;
	  font-family: Arial, Helvetica, sans-serif;
	}

	.topnav {
	  overflow: hidden;
	  background-color: #f7f4f4;
	}

	.topnav a {
	  float: left;
	  display: block;
	  color: black;
	  text-align: center;
	  padding: 14px 16px;
	  text-decoration: none;
	  font-size: 17px;
	}

	.topnav a:hover {
	  background-color: #ddd;
	  color: black;
	}

	.topnav a.active {
	  background-color: #4CAF50;
	  color: white;
	}

	.topnav .search-container {
	  float: right;
	}

	.topnav input[type=text] {
	  padding: 6px;
	  margin-top: 8px;
	  font-size: 17px;
	  border: none;
	}

	.topnav .search-container button {
	  float: right;
	  padding: 6px 10px;
	  margin-top: 8px;
	  margin-right: 16px;
	  background: #ddd;
	  font-size: 17px;
	  border: none;
	  cursor: pointer;
	}

	.topnav .search-container button:hover {
	  background: #ccc;
	}

	@media screen and (max-width: 600px) {
	  .topnav .search-container {
	    float: none;
	  }
	  .topnav a, .topnav input[type=text], .topnav .search-container button {
	    float: none;
	    display: block;
	    text-align: left;
	    width: 100%;
	    margin: 0;
	    padding: 14px;
	  }
	  .topnav input[type=text] {
	    border: 1px solid #ccc;  
	  }
	}

	input[type=text], select,textarea,input[type=datetime-local] {
	    width: 100%;
	    padding: 12px 20px;
	    margin: 8px 0;
	    display: inline-block;
	    border: 1px solid #ccc;
	    border-radius: 4px;
	    box-sizing: border-box;
	}

	input[type=submit] {
	    width: 100%;
	    background-color: #4CAF50;
	    color: white;
	    padding: 14px 20px;
	    margin: 8px 0;
	    border: none;
	    border-radius: 4px;
	    cursor: pointer;
	}

	input[type=submit]:hover {
	    background-color: #45a049;
	}

	.the {
	    border-radius: 5px;
	    background-color: #f2f2f2;
	    padding: 20px;
	}


	

</style>



<body>

	<h2 align="center">Admin Data</h2>
	
	<div class="topnav">
	  <a href="index.php">Home</a>
	  <a href="insert.php">Insert Data</a>
	  <a class="active" href="#">Update</a>
	  <a href="#contact">Contact</a>

	  
	</div>
<br>
	<div class="the">
	<form action="edit.php?id=<?php echo $id;?>" method="POST" enctype="multipart/form-data" id="<?php echo $id ?>">

		

			<label for="fname">Date & Time</label>
		    <input id="fname" type="datetime-local"  value="<?= $date ?>" name="dates">

		    <label for="fname">Brgy. Case Number</label>
		    <input type="text" id="fname" name="brgycasenumber" placeholder="Case Number" value="<?= $brgycasenumber?>">

		    <label for="fname">Complainant</label>
		    <input type="text" id="fname" name="complainant" placeholder="Full Name of Complainant" value="<?= $complainant ?>">

		    <label for="fname">Respondent</label>
		    <input type="text" id="fname" name="respondent" placeholder="Full Name of Respondent" value="<?= $respondent?>">
<!--
		    <label for="fname">Nature of Case</label>
		    <input type="text" id="fname" name="natureofcase" placeholder="Type of Case" value="<?= $natureofcase ?>">

		    <label for="lname">Disposition/Remarks</label>
		    <textarea type="text" id="lname" name="disposition_remarks" placeholder="" ><?php if($disposition_remarks) echo $disposition_remarks; ?></textarea>
-->
		    <label for="country">Nature of Case</label>
		    <select id="country" name="natureofcase" >
		      <option value="Criminal" <?php if ($natureofcase == 'Criminal') echo ' selected="selected"'; ?>>Criminal</option>
		      <option value="Civil" <?php if ($natureofcase == 'Civil') echo ' selected="selected"'; ?>>Civil</option>
		      <option value="Miscellaneous " <?php if ($natureofcase == 'Miscellaneous ') echo ' selected="selected"'; ?>>Miscellaneous</option>
		    </select>

		    <label for="country">Disposition/Remarks</label>
		    <select id="country" name="disposition_remarks" >
		      <option value="Mediation" <?php if ($disposition_remarks == 'Mediation') echo ' selected="selected"'; ?>>Mediation</option>
		      <option value="Conciliation" <?php if ($disposition_remarks == 'Conciliation') echo 'selected="selected"'; ?>>Conciliation</option>
		      <option value="Withdrawn" <?php if ($disposition_remarks == 'Withdrawn') echo 'selected="selected"'; ?>>Withdrawn</option>
		      <option value="Dismissed" <?php if ($disposition_remarks == 'Dismissed') echo 'selected="selected"'; ?>>Dismissed</option>
		      <option value="Pending" <?php if ($disposition_remarks == 'Pending') echo 'selected="selected"'; ?>>Pending</option>
		    </select>
		    <label for="country">Status</label>
		    <select id="country" name="status" >
		      <option value="Closed Case" <?php if ($status == 'Closed Case') echo 'selected="selected"'; ?>>Closed Case</option>
	      	<option value="Pending Case" <?php if ($status == 'Pending Case') echo 'selected="selected"'; ?>>Pending Case</option>
		    </select>

  
    <input type="submit" value="Submit">

	</form>
	</div>

	
</body>
</html>