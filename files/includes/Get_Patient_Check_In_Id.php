<?php
	//get branch id
	if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}

	//get the last check in id
    $select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    $nums = mysqli_num_rows($select);
    if($nums > 0){
    	while($rows = mysqli_fetch_array($select)) {
    		$Check_In_ID = $rows['Check_In_ID'];
    	}
    }else{
    	$inserts = mysqli_query($conn,"INSERT INTO tbl_check_in(Registration_ID, Visit_Date, Employee_ID, 
    								Check_In_Date_And_Time, Check_In_Status, Branch_ID, 
    								Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In, Folio_Status) 
    							VALUES ('$Registration_ID',(select now()),'$Employee_ID',
    									(select now()),'saved','$Branch_ID',
    									(select now()),(select now()),'Afresh','generated')") or die(mysqli_error($conn));
    	if($inserts){
    		$select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_ID = '$Registration_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
    		while($rows = mysqli_fetch_array($select)) {
	    		$Check_In_ID = $rows['Check_In_ID'];
	    	}	
    	}
    }
?>