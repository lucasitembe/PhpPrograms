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

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}
	
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}
	
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

    //get consultation_ID
    $select = mysqli_query($conn,"select pc.consultation_id from tbl_payment_cache pc, tbl_item_list_cache ilc where
    						pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
    						ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
    	while ($data = mysqli_fetch_array($select)) {
    		$consultation_ID = $data['consultation_id'];
    	}
    }else{
    	$consultation_ID = 0;
    }

	if($Employee_ID != '' && $Payment_Item_Cache_List_ID != '' && $consultation_ID != 0){
	    //check if data available into tbl_bronchoscopy_notes
	    $select = mysqli_query($conn,"select Bronchoscopy_Notes_ID from tbl_bronchoscopy_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Bronchoscopy_Notes_ID = $data['Bronchoscopy_Notes_ID'];
	    	}
	    }else{
	    	$insert = mysqli_query($conn,"insert into tbl_bronchoscopy_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

	    //Select Bronchoscopy_Notes_ID
	    $select = mysqli_query($conn,"select Bronchoscopy_Notes_ID from tbl_bronchoscopy_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
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
            $sql = "select vocal_cords as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Trachea'){
        	$sql = "select Trachea as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Carina'){
        	$sql = "select Carina as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Rt_Bronchial_tree'){
        	$sql = "select Rt_Bronchial_tree as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Rt_UL_Bronchus'){
        	$sql = "select Rt_UL_Bronchus as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Rt_ML_Bronchus'){
        	$sql = "select Rt_ML_Bronchus as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Rt_LL_Bronchus'){
        	$sql = "select Rt_LL_Bronchus as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Lt_Bronchial_tree'){
        	$sql = "select Lt_Bronchial_tree as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Lt_UL_Bronchus'){
        	$sql = "select Lt_UL_Bronchus as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Lt_LL_Bronchus'){
        	$sql = "select Lt_LL_Bronchus as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID == 'Liangula'){
        	$sql = "select Liangula as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID=='Biopsy'){
            $sql = "select Biopsy  as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID=='Impression'){
            $sql = "select Impression  as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";
        }else if($Temp_ID=='Bal'){
		    $sql = "select Bal as value from tbl_bronchoscopy_notes where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'";

	}
        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        $nm = mysqli_num_rows($result);
        if($num > 0){
        	while ($row = mysqli_fetch_array($result)) {
        		echo $row['value'];
        	}
        }
	}
?>