<?php
	session_start();
	include("./includes/connection.php");
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
//		if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
//		    if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
//			header("Location: ./index.php?InvalidPrivilege=yes");
//		    }
//		}else{
//		    header("Location: ./index.php?InvalidPrivilege=yes");
//		}
    }else{
		@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	$select = mysqli_query($conn,"select i.Product_Name, iic.Price, iic.Quantity, iic.Discount, iic.Check_In_Type, iic.Item_Cache_ID from tbl_items i, tbl_inpatient_items_cache iic where
							i.Item_ID = iic.Item_ID and
							iic.Employee_ID = '$Employee_ID' and
							iic.Registration_ID = '$Registration_ID' order by Item_Cache_ID desc") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	$Grand_Total = 0;
	if($num > 0){
		while ($data = mysqli_fetch_array($select)){
			$Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);		
		}
	}
?>
<b>GRAND TOTAL : </b><?php echo number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp;&nbsp;