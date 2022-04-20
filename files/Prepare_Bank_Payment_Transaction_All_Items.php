<?php
	session_start();
	include("./includes/connection.php");
	$Amount = 0;

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

	if(isset($_GET['Payment_Cache_ID'])){
		 $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = 0;
	}

	//calculate amount
	$get_amount = mysqli_query($conn,"select ilc.Quantity, ilc.Edited_Quantity, ilc.Price, ilc.Discount, pc.Registration_ID
                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash' and
                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                pc.Billing_Type = 'Outpatient Cash' and
                                ilc.ePayment_Status = 'pending'") or die(mysqli_error($conn));

	$no = mysqli_num_rows($get_amount);
	if($no > 0){
            
		while ($data = mysqli_fetch_array($get_amount)) {
			$Registration_ID = $data['Registration_ID'];
                        
            //generate Quantity
            if($data['Edited_Quantity'] != 0){
                $Qty = $data['Edited_Quantity'];
            }else{
                $Qty = $data['Quantity'];
            }

            $Total = (($data['Price'] - $data['Discount']) * $Qty);
            $Amount += $Total;
        }
	}else{
		$Registration_ID = 0;
	}


	if($Amount > 0 && $Registration_ID != 0 && $Registration_ID != null){
            
		$insert = mysqli_query($conn,"insert into tbl_bank_transaction_cache(
								Registration_ID, Amount_Required, Employee_ID, Transaction_Date_Time, Transaction_Date, Source)
							values ('$Registration_ID','$Amount','$Employee_ID',(select now()),(select now()),'Revenue Center')") or die(mysqli_error($conn));
		if($insert){
                  
			$select_result = mysqli_query($conn,"select Transaction_ID from tbl_bank_transaction_cache where 
											Registration_ID = '$Registration_ID' and 
											Employee_ID = '$Employee_ID' order by Transaction_ID desc limit 1") or die(mysqli_error($conn));
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
                                echo "imefika";
				if($mynum > 0){
                                    
					while ($data2 = mysqli_fetch_array($get_invoice)) {
						$Invoice_Number = $data2['Invoice_Number'];
					}

					//update code
					$update = mysqli_query($conn,"update tbl_bank_transaction_cache set Payment_Code = '$Invoice_Number' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
					if($update){
						//update item transaction number (tbl_item_list_cache)
						$select_items = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID
						                                from tbl_payment_cache pc, tbl_item_list_cache ilc where
						                                ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
						                                (ilc.Status = 'active' or ilc.Status = 'approved') and
						                                ilc.Transaction_Type = 'Cash' and
						                                pc.Payment_Cache_ID = '$Payment_Cache_ID' and
						                                pc.Billing_Type = 'Outpatient Cash' and
						                                ilc.ePayment_Status = 'pending'") or die(mysqli_error($conn));
						$num = mysqli_num_rows($select_items);
						if($num > 0){
							while ($data = mysqli_fetch_array($select_items)) {
								$Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
								$result = mysqli_query($conn,"update tbl_item_list_cache set Transaction_ID = '$Transaction_ID', ePayment_Status = 'Served' where
															Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
							}
						}

						if($result){
							$_SESSION['Transaction_ID'] = $Transaction_ID;
							header("Location: ./crdbtransactiondetails.php?Section=Revenue&CRDBTransactionDetails=CRDBTransactionDetailsThisPage");
						}
					}
				}
			}
		}
	}

?>