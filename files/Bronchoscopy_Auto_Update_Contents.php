<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

	if(isset($_GET['Temp_ID'])){
		$Temp_ID = $_GET['Temp_ID'];
	}else{
		$Temp_ID = '';
	}
	
	if(isset($_GET['Temp_Data'])){
		$Temp_Data = $_GET['Temp_Data'];
	}else{
		$Temp_Data = '';
	}
	
	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}

	
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

    //Get Patient_Payment_Item_List_ID
    //get Payment_Cache_ID
    $select = mysqli_query($conn,"select pc.consultation_id, pc.Payment_Cache_ID from 
                            tbl_item_list_cache ilc, tbl_payment_cache pc where
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and 
                            Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Payment_Cache_ID = $data['Payment_Cache_ID'];
            $consultation_ID = $data['consultation_id'];
        }
    }else{
        $Payment_Cache_ID = 0;
        $consultation_ID = 0;
    }

	if($Employee_ID != '' && $Payment_Item_Cache_List_ID != '' && $consultation_ID != 0){
	    //check if data available into tbl_Bronchoscopy_notes
	    $select = mysqli_query($conn,"select Bronchoscopy_Notes_ID from tbl_Bronchoscopy_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Bronchoscopy_Notes_ID = $data['Bronchoscopy_Notes_ID'];
	    	}
	    }else{
	    	$insert = mysqli_query($conn,"insert into tbl_Bronchoscopy_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

	    //Select Bronchoscopy_Notes_ID
	    $select = mysqli_query($conn,"select Bronchoscopy_Notes_ID from tbl_Bronchoscopy_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Bronchoscopy_Notes_ID = $data['Bronchoscopy_Notes_ID'];
	    	}
	    }else{
	    	$Bronchoscopy_Notes_ID = 0;
	    }

	    //generate sql
        if($Temp_ID == 'vocal_cords'){
            $sql = "update tbl_Bronchoscopy_notes set vocal_cords = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Trachea'){
        	$sql = "update tbl_Bronchoscopy_notes set Trachea = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Carina'){
        	$sql = "update tbl_Bronchoscopy_notes set Carina = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Rt_Bronchial_tree'){
        	$sql = "update tbl_Bronchoscopy_notes set Rt_Bronchial_tree = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Rt_UL_Bronchus'){
        	$sql = "update tbl_Bronchoscopy_notes set Rt_UL_Bronchus = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Rt_ML_Bronchus'){
        	$sql = "update tbl_Bronchoscopy_notes set Rt_ML_Bronchus = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Rt_LL_Bronchus'){
        	$sql = "update tbl_Bronchoscopy_notes set Rt_LL_Bronchus = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Lt_Bronchial_tree'){
        $sql = "update tbl_Bronchoscopy_notes set Lt_Bronchial_tree = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Lt_UL_Bronchus'){
            $sql = "update tbl_Bronchoscopy_notes set Lt_UL_Bronchus = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Lt_LL_Bronchus'){
            $sql = "update tbl_Bronchoscopy_notes set Lt_LL_Bronchus = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Liangula'){
			$sql = "update tbl_Bronchoscopy_notes set Liangula = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
		}else if($Temp_ID == 'Impression'){
            $sql = "update tbl_Bronchoscopy_notes set Impression = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Biopsy'){
            $sql = "update tbl_Bronchoscopy_notes set Biopsy = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Bal'){
            $sql = "update tbl_Bronchoscopy_notes set Bal = '$Temp_Data' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
		} else {
        	$sql = "select now()";
        }

        
        mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}
?>

