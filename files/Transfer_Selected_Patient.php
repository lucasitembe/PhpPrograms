<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	if(isset($_GET['Destination_ID'])){
		$Destination_ID = $_GET['Destination_ID'];
	}else{
		$Destination_ID = '';
	}

	if(isset($_GET['Transfer_Type'])){
		$Transfer_Type = $_GET['Transfer_Type'];
	}else{
		$Transfer_Type = '';
	}


	if(strtolower($Transfer_Type) == 'transfer to doctor'){
		$Destnation = 'doctor';
		//get doctor name
		$get_det = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Destination_ID'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($get_det);
		if($nm > 0){
			while ($row = mysqli_fetch_array($get_det)) {
				$Value_Name = $row['Employee_Name'];
			}
		}else{
			$Value_Name = '';
		}
	}else{
		//get clinic name
		$get_det = mysqli_query($conn,"select Clinic_Name from tbl_clinic where Clinic_ID = '$Destination_ID'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($get_det);
		if($nm > 0){
			while ($row = mysqli_fetch_array($get_det)) {
				$Value_Name = $row['Clinic_Name'];
			}
		}else{
			$Value_Name = '';
		}
		$Destnation = 'clinic';
	}

	$Pre_Details = mysqli_query($conn,"insert into tbl_patient_payment_item_list_transfer select * from tbl_patient_payment_item_list where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
	if($Pre_Details){
		if($Destnation == 'doctor'){
			$update = mysqli_query($conn,"update tbl_patient_payment_item_list set Consultant_ID = '$Destination_ID', Patient_Direction = 'Direct To Doctor', Clinic_ID = NULL, Consultant = '$Value_Name' where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'");
			if($update){
				echo "yes";
			}else{
				echo "no";
			}
		}else{
			$update = mysqli_query($conn,"update tbl_patient_payment_item_list set Clinic_ID = '$Destination_ID', Patient_Direction = 'Direct To Clinic', Consultant_ID = NULL, Consultant = '$Value_Name' where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'");
			if($update){
				echo "yes";
			}else{
				echo "no";
			}
		}
	}else{
		echo "no";
	}
?>
