<?php
  session_start();
  include("../includes/connection.php");
 //get all item details based on item id
 
    if(isset($_GET['RI'])){
	$Registration_ID = $_GET['RI'];
    }else{
	$Registration_ID = '';
    }
	if(isset($_GET['PPILI'])){
		$Patient_Payment_Item_List_ID = $_GET['PPILI'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	if(isset($_GET['PPI'])){
		$Patient_Payment_ID = $_GET['PPI'];
	}else{
		$Patient_Payment_ID = '';
	}
if(isset($_GET['Status_From'])){
	$Status_From = $_GET['Status_From'];
}else{
	$Status_From = '';
}
if(isset($_GET['II'])){
	$Item_ID = $_GET['II'];
}else{
	$Item_ID = '';
}

if(isset($_GET['hasResult'])){
  $select_extist = "
		SELECT Results_Status 
			FROM tbl_radiology_patient_tests
			WHERE
			    Results_Status='done' AND
				Registration_ID = '$Registration_ID' AND
				Item_ID = '$Item_ID' AND 
				Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'
				";
				
	$select_extist_qry = mysqli_query($conn,$select_extist) or die(mysqli_error($conn));
	
	$count = mysqli_num_rows($select_extist_qry);
	
	if($count > 0){
	  echo 1;
	}else{
	  echo 0;
	}
}				
?>
