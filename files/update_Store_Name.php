<?php
	include("./includes/connection.php");

	if(isset($_GET['Sub_Department_ID'])){
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}else{
		$Sub_Department_ID = 0;
	}

	//get sub department name
	$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Sub_Department_Name = $data['Sub_Department_Name'];
		}
	}else{
		$Sub_Department_Name = 'Unknown ';
	}
?>
<select name='Store_ID' id='Store_ID' required='required'>
	<option value="<?php echo $Sub_Department_ID; ?>" selected="selected"><?php echo $Sub_Department_Name; ?></option>
</select>