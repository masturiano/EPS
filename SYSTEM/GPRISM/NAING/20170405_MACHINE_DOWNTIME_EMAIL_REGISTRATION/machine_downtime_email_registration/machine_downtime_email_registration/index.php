<?php
require_once("db_conn.php");

if(isset($_POST['execute']) && !empty($_POST['execute'])){
	// $first_name = $_POST['fname'];
	// $middle_name = $_POST['mname'];
	// $last_name = $_POST['lname'];


	// $select_count_fullname = "select count(*) as count from fullname where group_code = " . $_POST['group'] . ";"; // select table info
	// $query_exec = mysqli_query($conn,$select_count_fullname); // execute query
	// $row_count=mysqli_fetch_assoc($query_exec);
	// $count = $row_count['count'];
}
else{
	echo "<br/>";
	echo "Form not set";
}
?>
<DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css">
		<script src="popup.js"></script>
		<script>
			function HandlePopupResult(result) {
			    alert("result of popup is: " + result);
			}
		</script>
	</head>
	<body>
		<div id="wrapper">
			<div id="form">
				<form action="" method="POST">
					Group: <input type="text" name="group" id="group" value="" readonly="readonly"><br/>
					Firstname: <input type="text" name="fname" id="fname" required>
					<a href="popup.php?id=1" onClick="popup('popup.php?id=1', 'PopupPage', '408','408'); return false" target="_blank"  title="edit">Get data</a>
					<br/>
					Middlename: <input type="text" name="mname" id="mname" required><br/>
					Lastname: <input type="text" name="lname" id="lname" required><br/>
					<input type="submit" name="information" value="CHECK"><br/>
				</form>
			</div>
			<div id="form-data">
				<form action="" method="POST">
					<?php
					global $display_column_count;
					$display_column_count = 10;
					if(isset($_POST['information']) && !empty($_POST['information'])){
						$first_name = $_POST['fname'];
						$middle_name = $_POST['mname'];
						$last_name = $_POST['lname'];


						$select_count_fullname = "select count(*) as count from fullname where group_code = " . $_POST['group'] . ";"; // select table info
						$query_count_exec = mysqli_query($conn,$select_count_fullname); // execute query
						$row_count=mysqli_fetch_assoc($query_count_exec);
						$count = $row_count['count'];

						$select_fetch_fullname = "select * from fullname where group_code = " . $_POST['group'] . ";"; // select table info
						$query_fetch_exec = mysqli_query($conn,$select_fetch_fullname); 

						$f = 0;
						while($row_fetch=mysqli_fetch_array($query_fetch_exec)){
							$f++;
							?>
							Firstname Display: <input type="text" name="data_fname<?=$f?>" id="data_fname<?=$f?>" value="<?=$row_fetch['firstname']?>">
							Middlename Display: <input type="text" name="data_mname<?=$f?>" id="data_mname<?=$f?>">
							Lastname Display: <input type="text" name="data_lname<?=$f?>" id="data_lname<?=$f?>"><br/>
							<?php
						}
						if($f==11){$f=0;}
						$display_column_count = $display_column_count - $count;
						for($i=0;$i<=$display_column_count;$i++){ 
						?>
							Firstname Display: <input type="text" name="data_fname<?=$i?>" id="data_fname<?=$i?>" value="<?=$row_fetch['firstname']?>">
							Middlename Display: <input type="text" name="data_mname<?=$i?>" id="data_mname<?=$i?>">
							Lastname Display: <input type="text" name="data_lname<?=$i?>" id="data_lname<?=$i?>"><br/>
						<?php
						} 
					}
					else{
						for($i=0;$i<=$display_column_count;$i++){ 
						?>
							Firstname Display: <input type="text" name="data_fname<?=$i?>" id="data_fname<?=$i?>" value="">
							Middlename Display: <input type="text" name="data_mname<?=$i?>" id="data_mname<?=$i?>">
							Lastname Display: <input type="text" name="data_lname<?=$i?>" id="data_lname<?=$i?>"><br/>
						<?php 
						}
					}
					?>
					<input type="submit" name="execute" value="EXECUTE"><br/>
				</form>
			</div>
		</div>
	</body>
</html>