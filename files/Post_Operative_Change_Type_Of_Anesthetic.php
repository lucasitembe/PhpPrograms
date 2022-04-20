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
	
	
	if(isset($_GET['Type_Of_Anesthetic'])){
		$Type_Of_Anesthetic = mysqli_real_escape_string($conn,$_GET['Type_Of_Anesthetic']);
	}else{
		$Type_Of_Anesthetic = '';
	}

	if(isset($_GET['cutting_time'])){
		$cutting_time = $_GET['cutting_time'];
	}else{
		$cutting_time = '';
	}
	
	if(isset($_GET['end_cutting_time'])){
		$end_cutting_time = $_GET['end_cutting_time'];
	}else{
		$end_cutting_time = '';
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
        
	if(isset($_GET['type_of_surgery'])){
            $type_of_surgery = $_GET['type_of_surgery'];
	}else{
            $type_of_surgery = 0;
	}
        
	if(isset($_GET['duration_of_surgery'])){
		$duration_of_surgery = $_GET['duration_of_surgery'];
	}else{
		$duration_of_surgery = 0;
	}



	$duration_of_surgery = '';

	if(!empty($end_cutting_time) && (!empty($end_cutting_time))){

		$date1 = new DateTime($cutting_time);
		$date2 = new DateTime($end_cutting_time);
		$diff = $date1->diff($date2);
		// $duration_of_surgery = $diff->d . " days, ";
		// $days = $diff->d;
		$duration_of_surgery.= $diff->h . " hours, ";
		$hrs = $diff->h;
		$duration_of_surgery.= $diff->i . " minutes,";
		$min = $diff->i;
		$duration_of_surgery.= $diff->s . " Seconds";
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
	    $update = mysqli_query($conn,"update tbl_post_operative_notes set Type_Of_Anesthetic = '$Type_Of_Anesthetic',duration_of_surgery='$duration_of_surgery', cutting_time = '$cutting_time', end_cutting_time = '$end_cutting_time',type_of_surgery='$type_of_surgery' where Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
	}
?>

