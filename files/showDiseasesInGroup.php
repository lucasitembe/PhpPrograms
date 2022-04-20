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

$select_qr = "SELECT * FROM tbl_disease d,tbl_disease_group_mapping dgm
			  WHERE dgm.disease_group_id=$disease_group_id AND d.disease_id = dgm.disease_id
			  AND d.disease_name LIKE '%$disease_name%' LIMIT 100";

$result =  mysqli_query($conn,$select_qr) or die(mysqli_error($conn));

while($row = mysqli_fetch_assoc($result)){
	?>
	<tr>
	<td style='width:10%'><?php echo $row['disease_ID'];?></td>
	<td style='width:10%'><?php echo $row['disease_code'];?></td>
	<td><?php echo $row['disease_name'];?></td>
	<td><input type='button' value='X' onclick="removeDiseaseFromGroup('<?php echo $row['disease_ID']; ?>')"></td>
	</tr>
	<?php
}
?>