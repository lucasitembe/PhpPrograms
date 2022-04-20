<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

	if(isset($_GET['Temp_ID'])){
		$Temp_ID = mysqli_real_escape_string($conn,$_GET['Temp_ID']);
	}else{
		$Temp_ID = '';
	}
	
	if(isset($_GET['Temp_Data'])){
		$Temp_Data = mysqli_real_escape_string($conn,$_GET['Temp_Data']);
	}else{
		$Temp_Data = '';
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

		if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = 0;
	}

	/*//get consultation_ID
	$select = mysqli_query($conn,"select consultation_ID from tbl_consultation where Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $consultation_ID = $data['consultation_ID'];
        }
    }else{
        $consultation_ID = 0;
    }*/
	if($Employee_ID != '' && $Payment_Item_Cache_List_ID != '' && $consultation_ID != 0){
	    //check if data available into tbl_post_operative_notes
	    $select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Post_operative_ID = $data['Post_operative_ID'];
	    	}
	    }else{
	    	$insert = mysqli_query($conn,"insert into tbl_post_operative_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

	    //Select Post_operative_ID
	    $select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Post_operative_ID = $data['Post_operative_ID'];
	    	}
	    }else{
	    	$Post_operative_ID = 0;
	    }

	    //generate sql
	    if($Temp_ID == 'Procedure_And_Description'){
            $sql = "update tbl_post_operative_notes set Procedure_Description = '$Temp_Data' where Post_operative_ID = '$Post_operative_ID'";
        }else if($Temp_ID == 'Identification'){
        	$sql = "update tbl_post_operative_notes set Identification_Of_Prosthesis = '$Temp_Data' where Post_operative_ID = '$Post_operative_ID'";
        }else if($Temp_ID == 'Estimated_Blood'){
        	$sql = "update tbl_post_operative_notes set Estimated_Blood_loss = '$Temp_Data' where Post_operative_ID = '$Post_operative_ID'";
        }else if($Temp_ID == 'Problems'){
        	$sql = "update tbl_post_operative_notes set Complications = '$Temp_Data' where Post_operative_ID = '$Post_operative_ID'";
        }else if($Temp_ID == 'Drains'){
        	$sql = "update tbl_post_operative_notes set Drains = '$Temp_Data' where Post_operative_ID = '$Post_operative_ID'";
        }else if($Temp_ID == 'Specimen'){
        	$sql = "update tbl_post_operative_notes set Specimen_sent = '$Temp_Data' where Post_operative_ID = '$Post_operative_ID'";
        }else if($Temp_ID == 'Postoperative'){
        	$sql = "update tbl_post_operative_notes set Postoperative_Orders = '$Temp_Data' where Post_operative_ID = '$Post_operative_ID'";
        }else{
        	$sql = "select now()";
        }
        mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}
?>

