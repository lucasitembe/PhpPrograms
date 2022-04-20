<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
		    if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
		    }
		}else{
		    header("Location: ./index.php?InvalidPrivilege=yes");
		}
    }else{
		@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

	$Grand_Total = 0;
	$Colnum = 0;
	$Control = 'no'; //controls employees allowed to edit transactions

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = 0;
	}

	if($Patient_Payment_Item_List_ID == 0){
		//get via Patient_Payment_ID
		$select = mysqli_query($conn,"select Patient_Payment_Item_List_ID from tbl_patient_payment_item_list where Patient_Payment_ID = '$Patient_Payment_ID' limit 1") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Patient_Payment_Item_List_ID = $data['Patient_Payment_Item_List_ID'];
			}
		}else{
			$Patient_Payment_Item_List_ID = 0;
		}
	}

//get details
$select = mysqli_query($conn,"select pr.Patient_Name,em.Employee_Name,pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, 
							pp.Payment_Date_And_Time, pp.Patient_Payment_ID, pp.Folio_Number, pp.Billing_Type
							from tbl_patient_payments pp,tbl_employee em, tbl_patient_registration pr, tbl_sponsor sp, tbl_patient_payment_item_list ppl where
							pp.Sponsor_ID = sp.Sponsor_ID and
                                                        pp.Employee_ID = em.Employee_ID and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Registration_ID = pr.Registration_ID and
							ppl.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID';
							") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Registration_ID = $data['Registration_ID'];
        $Gender = $data['Gender'];
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Member_Number = $data['Member_Number'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Patient_Payment_ID = $data['Patient_Payment_ID'];
        $Folio_Number = $data['Folio_Number'];
        $Payment_Date_And_Time = $data['Payment_Date_And_Time'];
        $Billing_Type = $data['Billing_Type'];
        $Employee_Name = $data['Employee_Name'];
    }
} else {
    $Patient_Name = '';
    $Registration_ID = '';
    $Gender = '';
    $Date_Of_Birth = '';
    $Member_Number = '';
    $Guarantor_Name = '';
    $Patient_Payment_ID = '';
    $Folio_Number = '';
    $Payment_Date_And_Time = '';
    $Billing_Type = '';
    $Employee_Name = '';
}


	//check edit privileges
	if(strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes' && ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit')){
		$Control = 'yes';
		$Colnum = 2;
	}else if(strtolower($_SESSION['userinfo']['Modify_Cash_information']) == 'yes' && ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash')){
		$Control = 'yes';
		$Colnum = 2;
	}
?>
<br/>
<table width="100%">
    <tr>
        <td width="25%"><b>Patient Name &nbsp;&nbsp;&nbsp;</b><?php echo ucwords(strtolower($Patient_Name)); ?></td>
        <td width="25%"><b>Patient Number &nbsp;&nbsp;&nbsp;</b><?php echo $Registration_ID; ?></td>
        <td width="25%"><b>Sponsor Name &nbsp;&nbsp;&nbsp;</b><?php echo strtoupper($Guarantor_Name); ?></td>
        <td width="25%"><b>Member Number &nbsp;&nbsp;&nbsp;</b><?php echo $Member_Number; ?></td>
    </tr>
    <tr>
        <td><b>Folio Number &nbsp;&nbsp;&nbsp;</b><?php echo $Folio_Number; ?></td>
        <td><b>Receipt Number &nbsp;&nbsp;&nbsp;</b><?php echo $Patient_Payment_ID; ?></td>
        <td><b>Receipt Date &nbsp;&nbsp;&nbsp;</b><?php echo $Payment_Date_And_Time; ?></td>
        <td><b>Billing Type &nbsp;&nbsp;&nbsp;</b><?php echo $Billing_Type; ?></td>
    </tr>
    <tr>
        <td><b>Employee name&nbsp;&nbsp;&nbsp;</b><?php echo $Employee_Name; ?></td>
        <td colspan="2"></td>
    </tr>
    <!-- <tr><td colspan="4"><hr></td></tr> -->
</table><br/>

<fieldset style='overflow-y: scroll; height: 280px; background-color: white;'>
	<table width="100%">
		<tr>
			<td width="4%"><b>SN</b></td>
			<td><b>ITEM NAME</b></td>
			<td width="10%" style="text-align: right;"><b>PRICE</b></td>
			<td width="10%" style="text-align: right;"><b>DISCOUNT</b></td>
			<td width="10%" style="text-align: right;"><b>QUANTITY</b></td>
			<td width="10%" style="text-align: right;"><b>SUB TOTAL</b></td>
	<?php
		//display remove and edit buttons if required
		if($Control == 'yes'){
			echo '<td width="12%" colspan="2" style="text-align: center;"></td>';
		}
	?>
		</tr>
	<?php
		$select = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, ppl.Patient_Payment_Item_List_ID from 
								tbl_items i, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
								i.Item_ID = ppl.Item_ID and
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								pp.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			$temp = 0;
			while ($row = mysqli_fetch_array($select)) {
	?>
					<tr>
						<td><?php echo ++$temp; ?><b>.</b></td>
						<td><?php echo $row['Product_Name']; ?></td>
						<td style="text-align: right;"><?php echo number_format($row['Price']); ?></td>
						<td style="text-align: right;"><?php echo number_format($row['Discount']); ?></td>
						<td style="text-align: right;"><?php echo $row['Quantity']; ?></td>
						<td style="text-align: right;"><?php echo number_format(($row['Price'] - $row['Discount']) * $row['Quantity']); ?></td>
					<?php if($Control == 'yes'){ ?>
							<td style="text-align: center;"><input type="button" value="EDIT" name="Edit_Button" id="Edit_Button" onclick="Edit_Transaction(<?php echo $Patient_Payment_ID; ?>,<?php echo $row['Patient_Payment_Item_List_ID']; ?>,'<?php echo $row['Product_Name']; ?>')"></td>
							<td style="text-align: center;"><input type="button" value="REMOVE" name="Remove_Button" id="Remove_Button" onclick="Remove_Transactions(<?php echo $Patient_Payment_ID; ?>,<?php echo $row['Patient_Payment_Item_List_ID']; ?>,'<?php echo $row['Product_Name']; ?>')"></td>
					<?php } ?>
					</tr>
	<?php
					$Grand_Total += (($row['Price'] - $row['Discount']) * $row['Quantity']);
			}
			echo '<tr>
						<td colspan="'.($Colnum + 6).'"><hr></td>
					</tr>';
			echo '<tr>
						<td colspan="5" style="text-align: right;"><b>GRAND TOTAL</b></td>
						<td style="text-align: right;"><b>'.number_format($Grand_Total).'</b></td>
					</tr>';
			echo '<tr>
						<td colspan="'.($Colnum + 6).'"><hr></td>
					</tr>';
		}
	?>
	</table>
</fieldset>