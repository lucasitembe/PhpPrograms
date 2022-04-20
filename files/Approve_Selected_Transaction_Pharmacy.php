<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Section'])){
        $Section_Link = "Section=".$_GET['Section']."&";
        $Section = $_GET['Section'];
    }else{
        $Section_Link = "";
        $Section = "";
    }
	if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
		    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
		    }else{
			@session_start();
			if(!isset($_SESSION['supervisor'])){ 
			    header("Location: ./supervisorauthentication.php?{$Section_Link}InvalidSupervisorAuthentication=yes");
			}
		    }
		}else{
		    header("Location: ./index.php?InvalidPrivilege=yes");
		}
    }else{
		@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	if(isset($_GET['Sub_Department_ID'])){
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}else{
		$Sub_Department_ID = NULL;
	}

    if (isset($_SESSION['userinfo']['Employee_ID'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

	if(isset($_GET['Section'])){
        $Section_Link = "Section=".$_GET['Section']."&";
        $Section = $_GET['Section'];
    }else{
        $Section_Link = "";
        $Section = "";
    }

    if(isset($_GET['Payment_Cache_ID']) && $Payment_Cache_ID != null && $Payment_Cache_ID != ''){
		$update = mysqli_query($conn,"update tbl_item_list_cache set Status = 'approved', Approved_By = '$Employee_ID', Approval_Date_Time = (select now()) where Payment_Cache_ID = '$Payment_Cache_ID' and Status = 'active' and Check_In_Type = 'Pharmacy'") or die(mysqli_error($conn));
		if($update){
			echo "yes";
		}else{
			echo "no";
		}
	}else{
		header("Location: ./approvepharmacy.php?{$Section_Link}Registration_ID=$Registration_ID&Payment_Cache_ID=$Payment_Cache_ID&ApprovePharmacy=ApprovePharmacyThisPage");
	}
?>