<?php
include("./includes/connection.php");
session_start();
if(!isset($_SESSION['userinfo'])){
	exit;
}

if(isset($_GET['disease_group_name'])){
	$disease_group_name = $_GET['disease_group_name'];
}else{
	$disease_group_name = 0;
}

	$select_group = "SELECT * FROM tbl_disease_group WHERE disease_group_name LIKE '%$disease_group_name%'";
	$group_result = mysqli_query($conn,$select_group);
	while($group_row = mysqli_fetch_assoc($group_result)){
		?>
		<tr><td value='<?php echo $group_row['disease_group_id']; ?>' style='width:10%'><?php echo $group_row['disease_group_id']; ?></td><td><?php echo $group_row['disease_group_name']; ?></td><td><input type='radio' id='grp' name='grp' onclick="changeGroup('<?php echo $group_row['disease_group_id']; ?>','<?php echo $group_row['disease_group_name']; ?>','group_name')"></td></tr>
		<?php
	}
?>