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

	if(isset($_GET['Comment'])){
		$Comment = $_GET['Comment'];
	}else{
		$Comment = '';
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


	//generate payment_type
	$payment_type = 'post';
	// if(strtolower($Guarantor_Name) != 'cash' && $Billing_Type == 'Inpatient Cash'){
	// 	$payment_type = 'pre';
	// }

	$select_consultation = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in_details where consultation_ID = '$consultation_ID'")or die(mysqli_error($conn));
        if(mysqli_num_rows($select_consultation)> 0){
            while($data = mysqli_fetch_assoc($select_consultation)){
                $Check_In_ID = $data['Check_In_ID'];
            }
        }

	if($consultation_ID != 0 && $consultation_ID != ''){
		//select Payment_Cache_ID
		$select = mysqli_query($conn,"SELECT Payment_Cache_ID from tbl_payment_cache where consultation_ID = '$consultation_ID' and Order_Type = 'post operative'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Payment_Cache_ID = $data['Payment_Cache_ID'];
			}
		}else{
			$insert = mysqli_query($conn,"INSERT INTO tbl_payment_cache(Registration_ID, Employee_ID, consultation_ID,
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

		    $Select_Price = "SELECT Items_Price as price from tbl_item_price ip
		                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
		    $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

		    if (mysqli_num_rows($itemSpecResult) > 0) {
		        $row = mysqli_fetch_assoc($itemSpecResult);
		        $Price = $row['price'];
		    }
		}

		//insert selected item
		if($Payment_Cache_ID != 0 && $Price > 0){
			$insert = mysqli_query($conn,"INSERT into tbl_item_list_cache(Check_In_Type, Item_ID, Discount,
									Price, Quantity, Patient_Direction,
									Consultant, Consultant_ID, Payment_Cache_ID,
									Transaction_Date_And_Time, Doctor_Comment, Sub_Department_ID,
									Transaction_Type, payment_type)
								values('Laboratory','$Item_ID','0',
									'$Price','$Quantity','Others',
									'$Employee_Name','$Employee_ID','$Payment_Cache_ID',
									(select now()),'$Comment','$Sub_Department_ID',
									'$Transaction_Type','$payment_type')") or die(mysqli_error($conn));
		}
	}

	//display Laboratorys ordered
	$select = mysqli_query($conn,"select ilc.Payment_Item_Cache_List_ID, i.Product_Name, ilc.Transaction_Type, ilc.Price, ilc.Quantity, ilc.Status from tbl_item_list_cache ilc, tbl_items i where
							i.Item_ID = ilc.Item_ID and
							ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
							Check_In_Type = 'Laboratory'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		$temp = 0;
?>
		<table width="100%">
			<tr>
				<td width="5%"><b>SN</b></td>
				<td><b>INVESTIGATION NAME</b></td>
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
                    echo '<td><input type="button" name="Remove_Investigation" id="Remove_Investigation" value="X" onclick="Remove_Investigation_Warning('.$row['Payment_Item_Cache_List_ID'].')" ></td>';
                }else{
                    echo '<td><input type="button" name="Remove_Inv" id="Remove_Inv" value="X" onclick="Remove_Investigation('.$row['Payment_Item_Cache_List_ID'].')"></td>';
                }
            ?>
			</tr>
<?php
		}
	}else{
		echo "<br/><br/><br/><br/><center><h3>NO INVESTIGATION ORDERED</h3></center>";
	}
?>