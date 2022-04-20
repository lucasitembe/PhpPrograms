<?php
	include("./includes/connection.php");
	$count = 0;
	$select = mysqli_query($conn,"select Registration_ID, aina_ya_msamaha from tbl_msamaha") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while($row = mysqli_fetch_array($select)){
			//get msamaha id
			$Registration_ID = $row['Registration_ID'];
			$aina_ya_msamaha = $row['aina_ya_msamaha'];

			$get = mysqli_query($conn,"select msamaha_Items from tbl_msamaha_items where msamaha_aina = '$aina_ya_msamaha'") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($get);
			if($nm > 0){
				while ($dt = mysqli_fetch_array($get)) {
					$msamaha_Items = $dt['msamaha_Items'];
				}
			}else{
				$msamaha_Items = NULL;
			}

			if($Registration_ID > 0){
				$update = mysqli_query($conn,"update tbl_check_in set msamaha_Items = '$msamaha_Items', Anayependekeza_Msamaha = Employee_ID where Registration_ID = '$Registration_ID'");
				if($update){
					$count++;
				}
			}
		}
	}
	echo $count;
?>