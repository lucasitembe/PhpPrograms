<?php
	session_start();
	include("./includes/connection.php");
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
	
	if(isset($_GET['Store_Order_ID'])){
		$Store_Order_ID = $_GET['Store_Order_ID'];
	}else{
		$Store_Order_ID = 0;
	}

	//update selected order
	$update = mysqli_query($conn,"UPDATE tbl_store_orders
                           SET Order_Status = 'Approved', Approval_Date_Time = (select now()), Supervisor_ID = '$Employee_ID'
                           WHERE
						   Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));

	if($update){
		if(isset($_SESSION['Order_Preview'])){
			unset($_SESSION['Order_Preview']);
		}
		header("Location: ./storepreviousorders.php?PreviousOrders=PreviousOrdersThisPage");
	}else{
		header("Location: ./storeorderpreview.php?StoreOrderPreview=StoreOrderPreviewThisPage");
	}
?>