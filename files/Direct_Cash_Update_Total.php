<?php
	session_start();
	include("./includes/connection.php");
	
	if (!isset($_SESSION['userinfo'])) {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}
	if (isset($_SESSION['userinfo'])) {
	    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
	        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
	            header("Location: ./index.php?InvalidPrivilege=yes");
	        } else {
	            //@session_start();
	            if (!isset($_SESSION['supervisor'])) {
	                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
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

	//get employee id
	if (isset($_SESSION['userinfo']['Employee_ID'])) {
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	} else {
	    $Employee_ID = '0';
	}
	$Grand_Total = 0;
	$select = mysqli_query($conn,"select Amount,Quantity from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
                    
			$Grand_Total +=(!empty($data['Quantity']) && $data['Quantity'] >0)? $data['Quantity']*$data['Amount']:$data['Amount'];
		}
	}
	echo $Grand_Total;
        
    ?>