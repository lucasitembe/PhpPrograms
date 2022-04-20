<select name='Store_Need' id='Store_Need'>
<?php
	session_start();
	include("./includes/connection.php");

	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	//check if any selected item availabe 
	$select = mysqli_query($conn,"select Store_Need from tbl_purchase_cache where Employee_ID = '$Employee_ID' limit 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Store_Need = $data['Store_Need'];

			//get sub department name
			$slt = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($slt);
			if($no > 0){
				while ($row = mysqli_fetch_array($slt)) {
					$Sub_Department_Name = $row['Sub_Department_Name'];
				}
			}else{
				$Sub_Department_Name = 'Unknown Location';
			}
			echo "<option value='".$Store_Need."'>".$Sub_Department_Name."</option>";
		}
	}else{
		echo "<option value='' selected='selected'></option>";
		$select2 = mysqli_query($conn,"select Sub_Department_Name, Sub_Department_ID from
								tbl_sub_department sdep, tbl_department dep where
								sdep.Department_ID = dep.Department_ID and
								dep.Department_Location = 'Storage And Supply' order by Sub_Department_Name") or die(mysqli_error($conn));

		$numz = mysqli_num_rows($select2);
		if($numz > 0){
			echo "<option selected='selected'></option>";
			while($data = mysqli_fetch_array($select2)){
				echo "<option value='".$data['Sub_Department_ID']."'>".$data['Sub_Department_Name']."</option>";
			}
		}
	}
?>