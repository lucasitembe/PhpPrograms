<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Order_ID']) && $_GET['Section'] = 'Edit'){
		/*$_SESSION['General_Order_ID'] = $_GET['Order_ID'];
		if(isset($_GET['Order_ID'])){ $Order_ID = $_GET['Order_ID']; } else{ $Order_ID = 0; };
		
		//get sub department id
		$select = mysqli_query($conn,"select Sub_Department_ID from tbl_store_orders where Store_Order_ID = '$Order_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Sub_Department_ID = $data['Sub_Department_ID'];
			}
		}else{
			$Sub_Department_ID = 0;
		}

		//lock if there is any order based on sub department
		$lock = mysqli_query($conn,"update tbl_store_orders set Control_Status = 'locked' where 
								Store_Order_ID <> '$Order_ID' and
								Sub_Department_ID = '$Sub_Department_ID' and
								Order_Status = 'pending'") or die(mysqli_error($conn));

		//change store status from submitted to pending
		$update = mysqli_query($conn,"update tbl_store_orders set Order_Status = 'pending', Control_Status = 'pending' where Store_Order_ID = '$Order_ID'") or die(mysqli_error($conn));
		header("Location: ./storeordering.php?status=new&NPO=True&StoreOrder=StoreOrderThisPage");*/
		
		$Order_ID = $_GET['Order_ID'];
		$_SESSION['Edit_General_Order_ID'] = $_GET['Order_ID'];
		$update = mysqli_query($conn,"update tbl_store_orders set Control_Status = 'editing' where Store_Order_ID = '$Order_ID' and Order_Status = 'Submitted'") or die(mysqli_error($conn));
		header("Location: ./editstoreorder.php?status=Edit&StoreOrder=StoreOrderThisPage");
	}else if(isset($_GET['Store_Order_ID']) && $_GET['Section'] = 'PreviewSupervisor'){
		$_SESSION['General_Order_ID'] = $_GET['Store_Order_ID'];
		header("Location: ./storeorderpreview.php?StoreOrderPreview=StoreOrderPreviewThisPage");
	}else{
		header("Location: ./storesubmittedorders.php?PendingOrders=PendingOrdersThisPage");
	}
?> 