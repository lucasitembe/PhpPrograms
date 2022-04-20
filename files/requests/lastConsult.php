<?php
   include("../includes/connection.php");
    session_start();
	
	$Patient_Payment_Item_List_ID =0;
	
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }
  //$Patient_Payment_ID;
    $consultation_query = "SELECT MAX(consultation_ID) as consultation_ID FROM tbl_consultation WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'";
	
	//die($consultation_query);
	
    $consultation_query_result = mysqli_query($conn,$consultation_query) or die(mysqli_error($conn));
    
    if(@mysqli_num_rows($consultation_query_result)>0){
	$row = mysqli_fetch_assoc($consultation_query_result);
	$consultation_ID = $row['consultation_ID'];
	if($consultation_ID==NULL){
	    $consultation_ID = 0;
	}
    }else{
	$consultation_ID = 0;
    }
	
	echo $consultation_ID;
?>