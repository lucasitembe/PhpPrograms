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
    
       if($consultation_ID == 0 || $consultation_ID == ""){
          
//            $Item_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_item_list_cache WHERE Item_ID='$Item_ID'"))['Item_ID']; 
            $Patient_Payment_Item_List_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list WHERE Item_ID='$Item_ID'"))['Patient_Payment_Item_List_ID'];
           $check_consultation_id = " SELECT Registration_ID,Patient_Payment_Item_List_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'";
            
         if(mysqli_num_rows($check_consultation_id) < 0){
           $mysqlresult= mysqli_query($conn,"INSERT INTO tbl_consultation(employee_ID,Registration_ID,Patient_Payment_Item_List_ID) VALUES('$Employee_ID','$Registration_ID','$Patient_Payment_Item_List_ID')");
            }
//           if($mysqlresult){
                
                $consultation_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1"))['consultation_ID'];
//           }
                
   
//         $consultation_ID=$mysql_consultation_ID;
      }
echo $Employee_ID."~~".$Payment_Item_Cache_List_ID."~~".$consultation_ID;
	if($Employee_ID != '' && $Payment_Item_Cache_List_ID != '' && $consultation_ID != 0){
//            echo "sdde";
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

        if($Temp_ID == 'Upper_Part'){
            $sql = "update tbl_git_post_operative_notes set Upper_Point = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'OG_Junction'){
            $sql = "update tbl_git_post_operative_notes set OG_Junction = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'Hiatus_Hernia'){
            $sql = "update tbl_git_post_operative_notes set Hiatus_Hernia = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'Other_Lesion'){
            $sql = "update tbl_git_post_operative_notes set Other_Lesson = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'Cardia'){
            $sql = "update tbl_git_post_operative_notes set Cardia = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'Fundus'){
            $sql = "update tbl_git_post_operative_notes set Fundus = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'Body'){
            $sql = "update tbl_git_post_operative_notes set Body = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'Antrum'){
            $sql = "update tbl_git_post_operative_notes set Antrum = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'Pylorus'){
            $sql = "update tbl_git_post_operative_notes set Pyloms = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'D1'){
            $sql = "update tbl_git_post_operative_notes set D1 = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'D2'){
            $sql = "update tbl_git_post_operative_notes set D2 = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'D3'){
            $sql = "update tbl_git_post_operative_notes set D3 = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
        }else if($Temp_ID == 'Middle_Part'){
			$sql = "update tbl_git_post_operative_notes set Middle_Point = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";

                }else if($Temp_ID=="Cardia_Fundus_Body_Antrum_Pylorus"){
                    			$sql = "update tbl_git_post_operative_notes set Cardia_Fundus_Body_Antrum_Pylorus = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
                } else if($Temp_ID=="upper_git_normal"){
                    $sql = "update tbl_git_post_operative_notes set upper_git_normal = '$Temp_Data' where Git_Post_operative_ID = '$Git_Post_operative_ID'";
                }else {
        	$sql = "select now()";
        }

        $result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
        if($result){
           echo "updated"; 
        }else {
            echo "Fail";
        }
	}
?>
