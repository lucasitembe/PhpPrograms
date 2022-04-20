<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Hospital_Ward_ID'])){
		$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
	}else{
		$Hospital_Ward_ID = 0;
	}
?>
<select id="Bed_ID" name="Bed_ID">
	<option value="" selected="selected"></option>
<?php
	$select = mysqli_query($conn,"select Bed_ID, Bed_Name from tbl_beds where Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
			<option value="<?php echo $data['Bed_ID']; ?>"><?php echo $data['Bed_Name']; ?></option>
<?php
		}
	}
?>
</select>