<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
	
	if(isset($_GET['Employee_ID'])){
		$Endoswrist_ID = $_GET['Employee_ID']; //selected Anaesthesia
	}else{
		$Endoswrist_ID = '';
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
    
     if($consultation_ID == 0 || $consultation_ID == ""){
//            $Item_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_item_list_cache WHERE Item_ID='$Item_ID'"))['Item_ID']; 
            $Patient_Payment_Item_List_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list WHERE Item_ID='$Item_ID'"))['Patient_Payment_Item_List_ID'];
            
             $check_consultation_id = " SELECT Registration_ID,Patient_Payment_Item_List_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'";
            
         if(mysqli_num_rows($check_consultation_id) < 0){
           $mysqlresult= mysqli_query($conn,"INSERT INTO tbl_consultation(employee_ID,Registration_ID,Patient_Payment_Item_List_ID) VALUES('$Employee_ID','$Registration_ID','$Patient_Payment_Item_List_ID')");
            }
                $consultation_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1"))['consultation_ID'];
         
   
//         $consultation_ID=$mysql_consultation_ID;
      }
    if($consultation_ID != 0 && $Registration_ID != 0 && $Employee_ID != '' && $Payment_Item_Cache_List_ID != ''){
	    //check if data available into tbl_git_post_operative_notes
	    $select = mysqli_query($conn,"select Git_Post_operative_ID from tbl_git_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Git_Post_operative_ID = $data['Git_Post_operative_ID'];
	    	}
	    }else{
	    	$insert = mysqli_query($conn,"insert into tbl_git_post_operative_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

	    //Select Git_Post_operative_ID
	    $select = mysqli_query($conn,"select Git_Post_operative_ID from tbl_git_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Git_Post_operative_ID = $data['Git_Post_operative_ID'];
	    	}
	    }else{
	    	$Git_Post_operative_ID = 0;
	    }

	    //check if selected Anaesthesia already added
	    $check = mysqli_query($conn,"select Employee_ID, Git_Post_operative_ID, Employee_Type from tbl_git_post_operative_participant where
	    						Git_Post_operative_ID = '$Git_Post_operative_ID' and
	    						Employee_ID = '$Endoswrist_ID'") or die(mysqli_error($conn));
	    $num_check = mysqli_num_rows($check);
	    if($num_check < 1){
	    	$add = mysqli_query($conn,"insert into tbl_git_post_operative_participant(Git_Post_operative_ID, Employee_ID, Employee_Type)
	    						values('$Git_Post_operative_ID','$Endoswrist_ID','Anaesthesia')") or die(mysqli_error($conn));
	    	if($add){
	    		$select = mysqli_query($conn,"select Employee_Name from tbl_employee emp, tbl_git_post_operative_participant pop where
	    								emp.Employee_ID = pop.Employee_ID and
	    								pop.Git_Post_operative_ID = '$Git_Post_operative_ID' and
	    								pop.Employee_Type = 'Anaesthesia'") or die(mysqli_error($conn));
	    		$nm = mysqli_num_rows($select);
	    		if($nm > 0){
	    			$values = '';
	    			while ($row = mysqli_fetch_array($select)) {
	    				$values.= ucwords(strtolower($row['Employee_Name'])).';    ';
	    			}
	    			echo $values;
	    		}
	    	}
	    }else{
				$select = mysqli_query($conn,"select Employee_Name from tbl_employee emp, tbl_git_post_operative_participant pop where
	    								emp.Employee_ID = pop.Employee_ID and
	    								pop.Git_Post_operative_ID = '$Git_Post_operative_ID' and
	    								pop.Employee_Type = 'Anaesthesia'") or die(mysqli_error($conn));
	    		$nm = mysqli_num_rows($select);
	    		if($nm > 0){
	    			$values = '';
	    			while ($row = mysqli_fetch_array($select)) {
	    				$values.= ucwords(strtolower($row['Employee_Name'])).';    ';
	    			}
	    			echo $values;
	    		}
	    }
	}
?>