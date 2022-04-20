<?php
include("./includes/connection.php");
session_start();
if(!isset($_SESSION['userinfo'])){
	exit;
}

if(isset($_GET['disease_group_id'])){
	$disease_group_id = $_GET['disease_group_id'];
}else{
	$disease_group_id = 0;
}

if(isset($_GET['disease_name'])){
	$disease_name = $_GET['disease_name'];
}else{
	$disease_name = 0;
}

if(isset($_GET['limit'])){
	$limit = $_GET['limit'];
}else{
	$limit = 100;
}

$select_disease = "SELECT * FROM tbl_disease WHERE disease_name LIKE '%$disease_name%' 
				   AND disease_ID NOT IN (Select dgm.disease_ID FROM tbl_disease_group_mapping dgm) limit $limit";
$disease_result = mysqli_query($conn,$select_disease);
while($group_row = mysqli_fetch_assoc($disease_result)){
	?>
	<tr><td style='width:10%'><?php echo $group_row['disease_ID']; ?></td><td><?php echo $group_row['disease_code']; ?></td><td><?php echo $group_row['disease_name']; ?></td><td><input type='button' value='>' onclick="addDiseaseToGroup('<?php echo $group_row['disease_ID']; ?>')"></td></tr>
	<?php
}
?>