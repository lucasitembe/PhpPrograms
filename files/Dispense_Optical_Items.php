<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = -1;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	//get sub department id & name
    if(isset($_SESSION['Optical_info'])){
        $Sub_Department_ID = $_SESSION['Optical_info'];
    }else{
    	$Sub_Department_ID = '';
    }

	if(isset($_GET['Payment_Cache_ID'])){
		//get all paid items
		$select = mysqli_query($conn,"SELECT Quantity, Edited_Quantity, Payment_Item_Cache_List_ID, Item_ID
                                from tbl_item_list_cache ilc where
                                Payment_Cache_ID = '$Payment_Cache_ID' and
                                Transaction_Type = '$Transaction_Type' and
                                Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select); echo $num;
		if($num > 0){
			//dispense process
			while ($data = mysqli_fetch_array($select)) {
				$Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
				$Item_ID = $data['Item_ID'];
				if($data['Edited_Quantity'] > 0){
					$Qty = $data['Edited_Quantity'];
				}else{
					$Qty = $data['Quantity'];
				}
				

				$dispense = mysqli_query($conn,"update tbl_item_list_cache set Dispense_Date_Time = (select now()), Dispensor = '$Employee_ID', Status = 'dispensed'
										where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' and Check_In_Type = 'Optical'") or die(mysqli_error($conn));
				if($dispense){
					//update balance
					$Update_Balance = mysqli_query($conn,"update tbl_items_balance set Item_Balance = (Item_Balance - '$Qty') where Sub_Department_ID = '$Sub_Department_ID' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
				}
			}
			header("Location: ./glassprocessingpatient.php?Registration_ID=$Registration_ID&Payment_Cache_ID=$Payment_Cache_ID&GlassProcessingPatient=GlassProcessingPatientThisPage");
		}else{
			header("Location: ./glassprocessingpatient.php?Registration_ID=$Registration_ID&Payment_Cache_ID=$Payment_Cache_ID&GlassProcessingPatient=GlassProcessingPatientThisPage");
		}
	}else{
		header("Location: ./glassprocessingpatient.php?Registration_ID=$Registration_ID&Payment_Cache_ID=$Payment_Cache_ID&GlassProcessingPatient=GlassProcessingPatientThisPage");
	}
?>