<?php
	session_start();
	include("./includes/connection.php");

	//get employee name
	if (isset($_SESSION['userinfo']['Employee_Name'])) {
	    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	} else {
	    $Employee_Name = '';
	}

	//get employee id
	if (isset($_SESSION['userinfo']['Employee_ID'])) {
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	} else {
	    $Employee_ID = '';
	}
	//get branch id
	if (isset($_SESSION['userinfo']['Branch_ID'])) {
	    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	} else {
	    $Branch_ID = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['Sub_Department_ID'])){
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}else{
		$Sub_Department_ID = null;
	}

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = '';
	}	

	if(isset($_GET['Quantity'])){
		$Quantity = $_GET['Quantity'];
	}else{
		$Quantity = '';
	}	

	if(isset($_GET['Billing_Type'])){
		$Billing_Type = $_GET['Billing_Type'];
	}else{
		$Billing_Type = '';
	}	

	if(isset($_GET['Guarantor_Name'])){
		$Guarantor_Name = $_GET['Guarantor_Name'];
	}else{
		$Guarantor_Name = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}	

	if(isset($_GET['Dosage'])){
		$Dosage = $_GET['Dosage'];
	}else{
		$Dosage = '';
	}

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}

	if(isset($_GET['consultation_ID'])){
		$consultation_ID = $_GET['consultation_ID'];
	}else{
		$consultation_ID = 0;
	}

	$select_consultation = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in_details where consultation_ID = '$consultation_ID'")or die(mysqli_error($conn));
        if(mysqli_num_rows($select_consultation)> 0){
            while($data = mysqli_fetch_assoc($select_consultation)){
                $Check_In_ID = $data['Check_In_ID'];
            }
        }
	//generate payment_type
	$payment_type = 'post';
	// if(strtolower($Guarantor_Name) != 'cash' && $Billing_Type == 'Inpatient Cash'){
	// 	$payment_type = 'pre';
	// }
	

	if($consultation_ID != 0 && $consultation_ID != ''){
		//select Payment_Cache_ID
		
		$select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'post operative'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Payment_Cache_ID = $data['Payment_Cache_ID'];
			}
		}else{

			$consultation_ID_current = mysqli_query($conn, "SELECT Check_In_Details_ID FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Admit_Status = 'admitted' AND consultation_ID = '$consultation_ID' ORDER BY Check_In_Details_ID DESC LIMIT 1");
			
			if(mysqli_num_rows($consultation_ID_current)>0){
					$payment_method = mysqli_fetch_assoc(mysqli_query($conn, "SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['payment_method'];

					if($payment_method == 'credit'){
						$Billing_Type = 'Inpatient Credit';
					}else{
						$Billing_Type = 'Inpatient Cash';
					}
			}else{
				$payment_method = mysqli_fetch_assoc(mysqli_query($conn, "SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['payment_method'];

				if($payment_method == 'credit'){
					$Billing_Type = 'Outpatient Credit';
				}else{
					$Billing_Type = 'Outpatient Cash';
				}
			}
			$insert = mysqli_query($conn,"INSERT into tbl_payment_cache(Registration_ID, Employee_ID, consultation_ID,
									Payment_Date_And_Time, Sponsor_ID, Sponsor_Name,
									Billing_Type, Receipt_Date, Order_Type, branch_id, Check_In_ID)
								values('$Registration_ID','$Employee_ID','$consultation_ID',
									(select now()),'$Sponsor_ID','$Guarantor_Name',
									'$Billing_Type',(select now()),'post operative','$Branch_ID', '$Check_In_ID')") or die(mysqli_error($conn));
			if($insert){
				$select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'post operative'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($select);
				if($num > 0){
					while ($data = mysqli_fetch_array($select)) {
						$Payment_Cache_ID = $data['Payment_Cache_ID'];
					}
				}else{
					$Payment_Cache_ID = 0;
				}
			}else{
				$Payment_Cache_ID = 0;
			}
		}

		//get price				
		if (isset($_GET['Item_ID']) && ($_GET['Item_ID'] != '') && isset($_GET['Sponsor_ID']) && ($_GET['Sponsor_ID'] != '')) {
		    $Item_ID = $_GET['Item_ID'];
		    $Billing_Type = $_GET['Billing_Type'];
		    $Guarantor_Name = $_GET['Guarantor_Name'];


		    // if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
		    //     $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
		    //     $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
		    // } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
		    //     $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
		    //     $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
		    // }

		    $Select_Price = "SELECT Items_Price as price from tbl_item_price
		                                    where Item_ID = '$Item_ID' AND Sponsor_ID = '$Sponsor_ID'";
		    $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

		    if (mysqli_num_rows($itemSpecResult) > 0) {
		        $row = mysqli_fetch_assoc($itemSpecResult);
		        $Price = $row['price'];
		        // if ($Price == 0) {
		        //     $Select_Price = "SELECT Items_Price as price from tbl_general_item_price ig
		        //                                 where ig.Item_ID = '$Item_ID'";
		        //     $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

		        //     if (mysqli_num_rows($itemGenResult) > 0) {
		        //         $row = mysqli_fetch_assoc($itemGenResult);
		        //         $Price = $row['price'];
		        //     } else {
		        //         $Price = 0;
		        //     }
		        // }

				if($Payment_Cache_ID != 0 AND $Price > 0){
					$insert = mysqli_query($conn,"INSERT into tbl_item_list_cache(Check_In_Type, Item_ID, Discount,
											Price, Quantity, Patient_Direction,
											Consultant, Consultant_ID, Payment_Cache_ID,
											Transaction_Date_And_Time, Doctor_Comment, Sub_Department_ID,
											Transaction_Type, payment_type)
										values('Pharmacy','$Item_ID','0',
											'$Price','$Quantity','Others',
											'$Employee_Name','$Employee_ID','$Payment_Cache_ID',
											(select now()),'$Dosage','$Sub_Department_ID',
											'$Transaction_Type','$payment_type')") or die(mysqli_error($conn));
				}
		    }
			//  else {
		    //     $Select_Price = "SELECT Items_Price as price from tbl_general_item_price ig
		    //                                 where ig.Item_ID = '$Item_ID'";
		    //     $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

		    //     if (mysqli_num_rows($itemGenResult) > 0) {
		    //         $row = mysqli_fetch_assoc($itemGenResult);
		    //         $Price = $row['price'];
		    //     } else {
		    //         $Price = 0;
		    //     }
		    // }

		}

		//insert selected item

	}

	//display medications ordered
	$select = mysqli_query($conn,"SELECT ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Transaction_Type, ilc.Price, ilc.Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where
							i.Item_ID = ilc.Item_ID and
							ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
							ilc.Check_In_Type = 'Pharmacy'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		$temp = 0;
?>
		<table width="100%">
			<tr>
				<td width="5%"><b>SN</b></td>
				<td><b>MEDICATION NAME</b></td>
				<td width="10%"><b>TYPE</b></td>
				<td width="10%"><b>PRICE</b></td>
				<td width="10%"><b>QTY</b></td>
				<td width="4%"></td>
			</tr>
<?php
		while ($row = mysqli_fetch_array($select)) {
?>
			<tr>
				<td><?php echo ++$temp; ?></td>
				<td><?php echo $row['Product_Name']; ?></td>
				<td><?php echo $row['Transaction_Type']; ?></td>
				<td><?php echo $row['Price']; ?></td>
				<td><?php echo $row['Quantity']; ?></td>
			<?php
                if(strtolower($row['Status']) != 'active'){
                    echo '<td><input type="button" name="Remove_Medication" id="Remove_Medication" value="X" onclick="Remove_Medication_Warning('.$row['Payment_Item_Cache_List_ID'].')" ></td>';
                }else{
                    echo '<td><input type="button" name="Remove_Medication" id="Remove_Medication" value="X" onclick="Remove_Medication('.$row['Payment_Item_Cache_List_ID'].')"></td>';
                }
            ?>
			</tr>
<?php
		}
	}else{
		echo "<br/><br/><br/><br/><center><h3>NO MEDICATION ORDERED</h3></center>";
	}
?>