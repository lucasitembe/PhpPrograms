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
            $sql = "select Anal_lessor as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Haemoral'){
        	$sql = "select Haemoral as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'PR'){
        	$sql = "select PR as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Sigmoid_Colon'){
        	$sql = "select Symd as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Dex_colon'){
        	$sql = "select Dex_colon as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Splenic_Flexure'){
        	$sql = "select Splenic as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Ple_Tran_Col'){
        	$sql = "select Ple_Tran_Col as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Hepatic_Flexure'){
        	$sql = "select Hepatic_Flexure as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Ascending_Colon'){
        	$sql = "select Ascending_Colon as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Caecum'){
        	$sql = "select Caecum as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID == 'Terminal_Ileum'){
        	$sql = "select Terminal_Ileum as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
        }else if($Temp_ID=='Rectum'){
		//$sql = "update tbl_ogd_post_operative_notes set rectum = '$Temp_Data' where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";
		        	$sql = "select rectum  as value from tbl_ogd_post_operative_notes where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'";

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