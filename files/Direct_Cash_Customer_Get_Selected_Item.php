<?php
	session_start();
	include("./includes/connection.php");

	if (!isset($_SESSION['userinfo'])) {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}
	if (isset($_SESSION['userinfo'])) {
	    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
	        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
	            header("Location: ./index.php?InvalidPrivilege=yes");
	        } else {
	            //@session_start();
	            if (!isset($_SESSION['supervisor'])) {
	                header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
	            }
	        }
	    } else {
	        header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	} else {
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
	}

	//get employee id
	if (isset($_SESSION['userinfo']['Employee_ID'])) {
	    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	} else {
	    $Employee_ID = '0';
	}

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = '';
	}

	if(isset($_GET['Item_Description'])){
		$Item_Description = $_GET['Item_Description'];
	}else{
		$Item_Description = '';
	}

	if(isset($_GET['Amount'])){
		$Amount = str_replace(',', '', $_GET['Amount']);
	}else{
		$Amount = '';
	}if(isset($_GET['Quantity'])){
		$Quantity = str_replace(',', '', $_GET['Quantity']);
	}else{
		$Quantity = 1;
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}
	if(isset($_GET['Clinic_ID'])){
		$Clinic_ID = $_GET['Clinic_ID'];
	}else{
		$Clinic_ID = '';
	}
        ///////////////////////check if patient have bill ..if not create one
        $Patient_Bill_ID=0;
$Patient_Payment_ID=0;
//Get employee id & branch id
	if(isset($_SESSION['userinfo'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Employee_ID = 0;
		$Branch_ID = 0;
	}

       $Today_Date = mysqli_query($conn,"select now() as today");
	while ($row = mysqli_fetch_array($Today_Date)) {
	    $original_Date = $row['today'];
	    $new_Date = date("Y-m-d", strtotime($original_Date));
	    $Today = $new_Date;
	}

        	if($Registration_ID != null && $Registration_ID != '' && $Registration_ID != 0 && isset($_SESSION['userinfo'])){
		//check if no pending bill
		$check = mysqli_query($conn,"select Registration_ID from tbl_other_sources_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($check);
        if($no == 0){
        	$insert2 = mysqli_query($conn,"insert into tbl_other_sources_prepaid_details(Registration_ID,Employee_ID,Date_Time,Patient_Bill_ID) values('$Registration_ID','$Employee_ID',(select now()),'$Patient_Bill_ID')") or die(mysqli_error($conn));
			}else{
                    $Patient_Bill_ID=mysqli_fetch_assoc($select_bill_id)['Patient_Bill_ID'];
                    $check_for_pennding_bill_result=mysqli_query($conn,"select Registration_ID from tbl_other_sources_prepaid_details where Registration_ID = '$Registration_ID' and Status = 'pending'") or die(mysqli_error($conn));
                    $no = mysqli_num_rows($check);
                    if($no <= 0){
                        //insert into tbl_prepaid_details table
                                 $insert2 = mysqli_query($conn,"insert into tbl_other_sources_prepaid_details(Registration_ID,Employee_ID,Date_Time,Patient_Bill_ID) values('$Registration_ID','$Employee_ID',(select now()),'$Patient_Bill_ID')") or die(mysqli_error($conn));
                    }         
                }
            }
	
        ////////////////////
?>
	<table width="100%">
       <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>ITEM NAME</b></td>
            <td><b>ITEM DESCRIPTION</b></td>
            <td width="9%" style="text-align: right;"><b>QUANTITY</b></td>
            <td width="9%" style="text-align: right;"><b>AMOUNT</b></td>
            <td width="5%"></td>
        </tr>
<?php
	//delete previous details
	mysqli_query($conn,"delete from tbl_direct_cash_cache where Employee_ID = '$Employee_ID' and Registration_ID <> '$Registration_ID'") or die(mysqli_error($conn));

	//insert data
	$insert = mysqli_query($conn,"insert into tbl_direct_cash_cache(Item_ID, Item_Description, Registration_ID,Quantity,Employee_ID, Amount,Clinic_ID)
							values('$Item_ID','$Item_Description','$Registration_ID','$Quantity','$Employee_ID','$Amount','$Clinic_ID')") or die(mysqli_error($conn));

	//display items
	$select = mysqli_query($conn,"select dcc.Cache_ID, dcc.Item_Description, dcc.Amount, dcc.Quantity, i.Product_Name
							from tbl_direct_cash_cache dcc, tbl_items i where 
							dcc.Registration_ID = '$Registration_ID' and
							dcc.Item_ID = i.Item_ID and
							Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr>
	            <td><?php echo ++$temp; ?></td>
	            <td><?php echo ucwords(strtolower($data['Product_Name'])); ?></td>
	            <td><?php echo $data['Item_Description']; ?></td>
                <td><?php echo (!empty($data['Quantity']) && $data['Quantity'] >0)? $data['Quantity']:1 ?>
                </td>
	            <td style="text-align: right;"><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($data['Amount'], 2) : number_format($data['Amount'])); ?>
	            </td>
	            <td style="text-align: center;">
	            	<input type="button" value="X" onclick="Remove_Item('<?php echo $data['Product_Name']; ?>',<?php echo $data['Cache_ID']; ?>);" style="color: red;">
	            </td>
	        </tr>
<?php
		}
	}
?>
</table>