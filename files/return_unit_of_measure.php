<?php
include("./includes/connection.php");

function unitOfMeasure($itemId){
	global $conn;
  $select = mysqli_query($conn,"SELECT Unit_Of_Measure from tbl_items where Item_ID = '$itemId'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			return $data['Unit_Of_Measure'];
		}
	}
}


?>
