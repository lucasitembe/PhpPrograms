<?php
include("./includes/connection.php");
	if(isset($_POST['update_dis_time'])){
		$Discharge_Date_Time = $_POST['update_dis_time'];
		$Admission_ID = $_POST['Admission_ID'];
		$Registration_ID = $_POST['Registration_ID'];
		
		$update = mysqli_query($conn, "UPDATE tbl_admission SET Discharge_Date_Time = '$Discharge_Date_Time' WHERE Admision_ID = '$Admission_ID' AND Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

		if($update){
			echo 'updated';
		}else{
			echo 'failed';
		}
	}

	if(isset($_POST['dialogue'])){
		$Admission_ID = $_POST['Admission_ID'];
		$Registration_ID = $_POST['Registration_ID'];
		$dischragesql = mysqli_query($conn, "SELECT Discharge_Date_Time,Admission_Date_Time FROM   tbl_admission  WHERE Admision_ID = '$Admission_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
		if(mysqli_num_rows($dischragesql)>0){
			while($row = mysqli_fetch_assoc($dischragesql)){
				$Admission_Date_Time = $row['Admission_Date_Time'];
				$Discharge_Date_Time = $row['Discharge_Date_Time'];
			}
		}

		?>
		 
			Admission Date<input type='text' id='update_ad_time' value='<?=$Admission_Date_Time;?>' readonly='readonly'>
			Discharge Date<input type='text' id='DischargeDateTime' value='<?=$Discharge_Date_Time;?>'>
			<input type='hidden' id='Admission_ID' value='<?=$Admission_ID;?>'>
			<input type='button' class='art-button-green' value='UPDATE' onclick="update_discharge_date(<?php echo $Admission_ID; ?>);">
		
		<?php
	}
?>
