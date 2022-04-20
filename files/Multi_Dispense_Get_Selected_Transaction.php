<?php
	session_start();
	include("./includes/connection.php");

	if (isset($_SESSION['userinfo'])) {
	    if (isset($_SESSION['userinfo']['Pharmacy'])) {
	        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
	            header("Location: ./index.php?InvalidPrivilege=yes");
	        } else {
	            @session_start();
	            if (!isset($_SESSION['Pharmacy_Supervisor'])) {
	                header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
	            }
	        }
	    } else {
	        header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	} else {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = '';
	}

	if(isset($_GET['Status'])){
		$Status = $_GET['Status'];
	}else{
		$Status = '';
	}

	//Get Employee_ID
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    //Get Sub_Department_ID
    if (isset($_SESSION['Pharmacy_ID'])) {
	    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
	} else {
	    $Sub_Department_ID = 0;
	}

    if($Status == 'remove'){
    	mysqli_query($conn,"delete from tbl_multi_dispense_cache where
						Registration_ID = '$Registration_ID' and
						Transaction_Type = '$Transaction_Type' and
						Employee_ID = '$Employee_ID' and
						Payment_Cache_ID = '$Payment_Cache_ID' and
						Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    }else{
	    //check if available
	    $select = mysqli_query($conn,"select Dispense_Cache_ID from tbl_multi_dispense_cache where
	    						Registration_ID = '$Registration_ID' and
	    						Transaction_Type = '$Transaction_Type' and
	    						Employee_ID = '$Employee_ID' and
	    						Payment_Cache_ID = '$Payment_Cache_ID' and
								Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num < 1){
	    	mysqli_query($conn,"insert into tbl_multi_dispense_cache(Registration_ID, Transaction_Type, Payment_Cache_ID, Employee_ID,Sub_Department_ID)
	    				values('$Registration_ID','$Transaction_Type','$Payment_Cache_ID','$Employee_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
	    }
	}
?>