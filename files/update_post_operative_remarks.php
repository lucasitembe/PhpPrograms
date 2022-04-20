<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
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

	$post_operative_remarks = $_GET['post_operative_remarks'];


		if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = 0;
	}


	if($Employee_ID != '' && $Payment_Item_Cache_List_ID != '' && $consultation_ID != 0){
	    //check if data available into tbl_post_operative_notes
	    $select = mysqli_query($conn,"select Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Post_operative_ID = $data['Post_operative_ID'];
	    	}
	    }else{
	    	$insert = mysqli_query($conn,"INSERT into tbl_post_operative_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

		
	    //Select Post_operative_ID
	    $select = mysqli_query($conn,"SELECT Post_operative_ID from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num > 0){
	    	while ($data = mysqli_fetch_array($select)) {
	    		$Post_operative_ID = $data['Post_operative_ID'];
	    	}
	    }else{
	    	$Post_operative_ID = 0;
	    }

		if(!empty($post_operative_remarks)){
			// die("UPDATE tbl_post_operative_notes SET post_operative_remarks = '$post_operative_remarks' WHERE Post_operative_ID = '$Post_operative_ID'");
			$Remarks = mysqli_query($conn, "UPDATE tbl_post_operative_notes SET post_operative_remarks = '$post_operative_remarks' WHERE Post_operative_ID = '$Post_operative_ID'");
		}

	    //generate sql

	}
mysqli_close($conn);
?>

