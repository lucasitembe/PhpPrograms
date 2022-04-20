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
	    //check if data available into tbl_ogd_post_operative_notes
	    $select = mysqli_query($conn,"select Ogd_Post_operative_ID from tbl_ogd_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Ogd_Post_operative_ID = $data['Ogd_Post_operative_ID'];
	    	}
	    }else{
	    	$insert = mysqli_query($conn,"insert into tbl_ogd_post_operative_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

	    //Select Ogd_Post_operative_ID
	    $select = mysqli_query($conn,"select Ogd_Post_operative_ID from tbl_ogd_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Ogd_Post_operative_ID = $data['Ogd_Post_operative_ID'];
	    	}
	    }else{
	    	$Ogd_Post_operative_ID = 0;
	    }

	    //generate sql
	    if($Temp_ID == 'Anal_lessor'){
            $sql = "update tbl_ogd_post_operative_notes set Anal_lessor = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Haemoral'){
        	$sql = "update tbl_ogd_post_operative_notes set Haemoral = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'PR'){
        	$sql = "update tbl_ogd_post_operative_notes set PR = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Symd'){
        	$sql = "update tbl_ogd_post_operative_notes set Symd = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Dex_colon'){
        	$sql = "update tbl_ogd_post_operative_notes set Dex_colon = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Splenic'){
        	$sql = "update tbl_ogd_post_operative_notes set Splenic = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Ple_Tran_Col'){
        	$sql = "update tbl_ogd_post_operative_notes set Ple_Tran_Col = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Rectum'){
		$sql = "update tbl_ogd_post_operative_notes set rectum = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
		} else {
        	$sql = "select now()";
        }
        
        mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}
?>

