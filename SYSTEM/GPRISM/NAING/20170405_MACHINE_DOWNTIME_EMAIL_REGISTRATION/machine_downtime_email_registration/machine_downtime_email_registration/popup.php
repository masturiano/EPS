<?php
include('db_conn.php');
		
$select_info = "select * from fullname"; // select table info
$query_exec = mysqli_query($conn,$select_info); // execute query
?>
<!DOCTYPE html>
<html>
	<head>
		<script>
			function sendDataToParent(group,fname){
				window.close(); 
				window.opener.document.getElementById('group').value = group;
				window.opener.document.getElementById('fname').value = fname;
			}
		</script>
	</head>
	<body>
		<table border=1>
			<thead>
				<td>ID</td>
				<td>FIRST NAME</td>
				<td>MIDDLE NAME</td>
				<td>LAST NAME</td>
			</thead>
		<?php
		while($row = mysqli_fetch_array($query_exec)){
			?>
			<tr>
				<td>
					<a href="#" onclick="sendDataToParent('<?=$row["group_code"];?>','<?=$row["firstname"];?>');" title="pull data"><?=$row['id'];?>
					</a>
				</td>
				<td><?=$row['firstname'];?></td>
				<td><?=$row['middlename'];?></td>
				<td><?=$row['lastname'];?></td>
			</tr>
			<?php
		}
		?>
			</tr>
		</table>
		<?php
?>
	</body>
</html>