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

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}
	if(isset($_GET['Clinic_ID'])){
		$Clinic_ID = $_GET['Clinic_ID'];
	}else{
		$Clinic_ID = '1';
	}
        if(isset($_GET['clinic_location_id'])){
		$clinic_location_id = $_GET['clinic_location_id'];
	}else{
		$clinic_location_id = '1';
	}
          if(isset($_GET['working_department'])){
		$working_department = $_GET['working_department'];
	}else{
		$working_department = '1';
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
	
	if(isset($_GET['Discount'])){
		$Discount = $_GET['Discount'];
	}else{
		$Discount = '';
	}
	
	if(isset($_GET['Check_In_Type'])){
		$Check_In_Type = $_GET['Check_In_Type'];
	}else{
		$Check_In_Type = '';
	}
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}
	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
                $sql_select_ward_id_result=mysqli_query($conn,"SELECT Hospital_Ward_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_ward_id_result)>0){
                    $ward_id_row=mysqli_fetch_assoc($sql_select_ward_id_result);
                    $Hospital_Ward_ID=$ward_id_row['Hospital_Ward_ID'];
                }
                
        }else{
		$Hospital_Ward_ID = '';
	}
?>

<?php
	//get item price
	if(isset($_GET['Transaction_Type'])){
		if($_GET['Transaction_Type'] == 'Credit_Bill_Details'){
			$Billing_Type = 'Inpatient Credit';
		}else{
			$Billing_Type = 'Inpatient Cash';
		}
	}else{
		$Billing_Type = 'Inpatient Cash';
	}
        if($_GET['Billing_Type']){
            $Billing_Type = $_GET['Billing_Type'];
        }
	$slct = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($slct);
	if($no > 0){
		while ($row2 = mysqli_fetch_array($slct)) {
			$Guarantor_Name = $row2['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}

	$Select_Price = '';
	if (isset($_GET['Item_ID']) && $_GET['Item_ID'] != '' && isset($_GET['Sponsor_ID']) && $_GET['Sponsor_ID'] != '') {
	    $Sponsor_ID2 = $Sponsor_ID;

	    if ($Billing_Type == 'Inpatient Cash') {
	        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
	        $Sponsor_ID2 = mysqli_fetch_assoc($sp)['Sponsor_ID'];
	    }

	    $Select_Price = "select Items_Price as price from tbl_item_price ip
	                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
	    $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

	    if (mysqli_num_rows($itemSpecResult) > 0) {
	        $row = mysqli_fetch_assoc($itemSpecResult);
			$Price = $row['price'];
            if ($Price == 0) {
                $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
                $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                if (mysqli_num_rows($itemGenResult) > 0) {
                    $row = mysqli_fetch_assoc($itemGenResult);
                    $Price = $row['price'];
                } else {
                     $Price = 0;
                }
            }
	    }else{
	        $Select_Price = "select Items_Price as price from tbl_general_item_price ig
	                                    where ig.Item_ID = '$Item_ID'";
	        $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

	        if (mysqli_num_rows($itemGenResult) > 0) {
	            $row = mysqli_fetch_assoc($itemGenResult);
	            $Price = $row['price'];
	        } else {
	            $Price = 0;
	        }
	    }
	}else{
	    $Price = 0;
	}


	//insert data into tbl_inpatient_items_cache 
	if($Employee_ID != 0 && $Item_ID != '' && $Registration_ID != ''){
		mysqli_query($conn,"delete from tbl_inpatient_items_cache where Employee_ID = '$Employee_ID' and Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));
		$insert = mysqli_query($conn,"insert into tbl_inpatient_items_cache(
								Registration_ID, Item_ID, Check_In_Type, 
								Price, Quantity, Discount, 
								Employee_ID, Billing_Type, Sponsor_ID,Clinic_ID,Hospital_Ward_ID,clinic_location_id,working_department)
							values('$Registration_ID','$Item_ID','$Check_In_Type',
								'$Price','$Quantity','$Discount',
								'$Employee_ID','$Billing_Type','$Sponsor_ID','$Clinic_ID','$Hospital_Ward_ID','$clinic_location_id','$working_department')") or die(mysqli_error($conn));
	}
?>

<table width="100%">
	<tr><td colspan="8"><hr></td></tr>
	<tr>
		<td width="4%"><b>Sn</b></td>
		<td width="12%"><b>Check In Type</b></td>
		<td><b>Item Name</b></td>
		<td width="8%" style="text-align: right;"><b>Price</b>&nbsp;&nbsp;</td>
		<td width="8%" style="text-align: right;"><b>Discount</b>&nbsp;&nbsp;</td>
		<td width="8%" style="text-align: right;"><b>Quantity</b>&nbsp;&nbsp;</td>
		<td width="10%" style="text-align: right;"><b>Sub Total</b>&nbsp;&nbsp;</td>
		<td width="4%"></td>
	</tr>
	<tr><td colspan="8"><hr></td></tr>

<?php
	$temp = 0;
	$select = mysqli_query($conn,"select i.Product_Name, iic.Price, iic.clinic_location_id, iic.working_department, iic.Quantity, iic.Discount, iic.Check_In_Type, iic.Item_Cache_ID from tbl_items i, tbl_inpatient_items_cache iic where
							i.Item_ID = iic.Item_ID and
							iic.Employee_ID = '$Employee_ID' and
							iic.Registration_ID = '$Registration_ID' order by Item_Cache_ID desc") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr>
				<td><?php echo ++$temp; ?></td>
				<td><?php echo $data['Check_In_Type']; ?></td>
                                <td><?php echo $data['Product_Name']; ?></td>
				<td style="text-align: right;"><?php echo number_format($data['Price']); ?>&nbsp;&nbsp;</td>
				<td style="text-align: right;"><?php echo number_format($data['Discount']); ?>&nbsp;&nbsp;</td>
				<td style="text-align: right;"><?php echo $data['Quantity']; ?>&nbsp;&nbsp;</td>
				<td style="text-align: right;"><?php echo number_format(($data['Price'] - $data['Discount']) * $data['Quantity']);?>&nbsp;&nbsp;</td>
				<td>
					<input type="button" name="Remove" id="Remove" value="X" onclick="Remove_Item('<?php echo $data['Item_Cache_ID']; ?>','<?php echo $data['Product_Name']; ?>')">
				</td>
			</tr>
<?php			
		}
	}
?>
</table>