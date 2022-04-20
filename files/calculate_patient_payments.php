<?php

	$Grand_Total = 0;
	//Get Receipts
	$select_res = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time, Billing_Type from
								tbl_patient_payments where
								Registration_ID = '$Registration_ID' and
								Check_In_ID = '$Check_In_ID' and
								Sponsor_ID = '$Sponsor_ID' and
								Transaction_status <> 'cancelled' and
								((Billing_Type = 'Outpatient Cash' and Pre_Paid = '0') or (Billing_Type = 'Inpatient Cash' and payment_type = 'pre'))") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select_res);
	if($nm > 0){
		while ($row = mysqli_fetch_array($select_res)) {
			$count = 0;
			$Total = 0;
			$Patient_Payment_ID = $row['Patient_Payment_ID'];
			$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
			$Billing_Type = $row['Billing_Type'];

						$slct = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Discount, ppl.Quantity from tbl_patient_payment_item_list ppl, tbl_items i where
											ppl.Patient_Payment_ID = '$Patient_Payment_ID' and
											ppl.Item_ID = i.Item_ID") or die(mysqli_error($conn));
						$nmz = mysqli_num_rows($slct);
						if($nmz > 0){
							while ($dt = mysqli_fetch_array($slct)) {
								$Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
								$Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
							}
						}

		}
	}
?>
