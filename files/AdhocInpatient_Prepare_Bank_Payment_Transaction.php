<?php
	session_start();
	include("./includes/connection.php");


	//get branch id
	if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
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
		$Registration_ID = 0;
	}

	if(isset($_GET['Section'])){
		$Section = $_GET['Section'];
	}else{
		$Section = '';
	}
	$select = mysqli_query($conn,"select sum((Price - Discount)*Quantity) as Amount from tbl_inpatient_items_list_cache where
							Employee_ID = '$Employee_ID' and
							Registration_ID = '$Registration_ID'") or die(mysqli_error($conn).'this');
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Amount = $data['Amount'];
		}
	}else{
		$Amount = 0;
	}
	if($Amount > 0){
		$insert = mysqli_query($conn,"insert into tbl_bank_transaction_cache(
								Registration_ID, Amount_Required, Employee_ID, Transaction_Date_Time, Transaction_Date) 
							values ('$Registration_ID','$Amount','$Employee_ID',(select now()),(select now()))") or die(mysqli_error($conn).'One');
		if($insert){
			$select_result = mysqli_query($conn,"select Transaction_ID from tbl_bank_transaction_cache where 
											Registration_ID = '$Registration_ID' and 
											Employee_ID = '$Employee_ID' order by Transaction_ID desc limit 1") or die(mysqli_error($conn).'two');
			$no = mysqli_num_rows($select_result);
			if($no > 0){
				while ($row = mysqli_fetch_array($select_result)) {
					$Transaction_ID = $row['Transaction_ID'];
				}
			}else{
				$Transaction_ID = 0;
			}
			if($Transaction_ID != 0){
				//get Invoice_Number
				$get_invoice = mysqli_query($conn,"select Invoice_Number from tbl_bank_invoice_numbers where Invoice_ID = '$Transaction_ID'") or die(mysqli_error($conn));
				$mynum = mysqli_num_rows($get_invoice);
				if($mynum > 0){
					while ($data2 = mysqli_fetch_array($get_invoice)) {
						$Invoice_Number = $data2['Invoice_Number'];
					}

					//update code
					$update = mysqli_query($conn,"update tbl_bank_transaction_cache set Payment_Code = '$Invoice_Number' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
					if($update){
						//get items
						$insert_cache = mysqli_query($conn,"select * from tbl_inpatient_items_list_cache where
													Employee_ID = '$Employee_ID' and
													Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
						$mn = mysqli_num_rows($insert_cache);
						if($mn > 0){
							while ($row2 = mysqli_fetch_array($insert_cache)) {
								$Check_In_Type = $row2['Type_Of_Check_In'];
								$Billing_Type = $row2['Billing_Type'];
								$Sponsor_ID = $row2['Sponsor_ID'];
								$Item_ID = $row2['Item_ID'];
								$Discount = $row2['Discount'];
								$Price = $row2['Price'];
								$Quantity = $row2['Quantity'];
								$Patient_Direction = $row2['Patient_Direction'];
								$Consultant = $row2['Consultant'];
								$Consultant_ID = $row2['Consultant_ID'];
								//move data
								$move = mysqli_query($conn,"insert into tbl_bank_items_detail_cache(
													Check_In_Type, Billing_Type, Sponsor_ID, 
													Item_ID, Discount, Price, Quantity, 
													Patient_Direction, Consultant, Consultant_ID, 
													Employee_ID, Registration_ID, Transaction_ID,
													Transaction_Date_And_Time, Transaction_Date,Branch_ID)

													VALUES ('$Check_In_Type','$Billing_Type','$Sponsor_ID',
															'$Item_ID','$Discount','$Price','$Quantity',
															'Others','$Consultant','$Consultant_ID',
															'$Employee_ID','$Registration_ID','$Transaction_ID',
															(select now()), (select now()), '$Branch_ID')") or die(mysqli_error($conn).'rrrr');
							}
							if($move){
								mysqli_query($conn,"delete from tbl_inpatient_items_list_cache where Employee_ID ='$Employee_ID'") or die(mysqli_error($conn));
								$_SESSION['Transaction_ID'] = $Transaction_ID;
								header("Location: ./crdbtransactiondetails.php?Section=$Section&CRDBTransactionDetails=CRDBTransactionDetailsThisPage");
							}
						}

					}
				}
			}
		}
	}

?>