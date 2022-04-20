<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = 0;
	}

	if(isset($_GET['Hospital_Ward_ID'])){
		$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
	}else{
		$Hospital_Ward_ID = 0;
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID = 0;
	}

	if (isset($_GET['Transaction_Type'])) {
	    $Transaction_Type = $_GET['Transaction_Type'];
	}else{
	    $Transaction_Type = '';
	}
        $category_name_disp="ALL CATEGORIES";
if (isset($_GET['Item_Category_ID'])) {
    $Item_Category_ID = mysqli_real_escape_string($conn,$_GET['Item_Category_ID']);
    if($Item_Category_ID!="All"){
       $category_fiter="and ic.Item_Category_ID='$Item_Category_ID'"; 
       $sql_select_category_name_result=mysqli_query($conn,"SELECT Item_Category_Name FROM tbl_item_category WHERE Item_Category_ID='$Item_Category_ID'") or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_select_category_name_result)>0){
           $category_name_disp=mysqli_fetch_assoc($sql_select_category_name_result)['Item_Category_Name'];
       }else{
           $category_name_disp="ALL CATEGORIES";
       }
    }
} else {
    $category_fiter="";
}
	//get ward name
	$slct = mysqli_query($conn,"select Hospital_Ward_Name from tbl_hospital_ward where Hospital_Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($slct);
	if($num > 0){
	    while ($rw = mysqli_fetch_array($slct)) {
	        $Hospital_Ward_Name = $rw['Hospital_Ward_Name'];
	    }
	}else{
	    $Hospital_Ward_Name = '';
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
	while ($row = mysqli_fetch_array($Today_Date)) {
	    $original_Date = $row['today'];
	    $new_Date = date("Y-m-d", strtotime($original_Date));
	    $Today = $new_Date;
	    $age = '';
	}

	$select = mysqli_query($conn,"select ad.Discharge_Date_Time,ad.imbalance_bill_approval_reason,ad.Admission_Date_Time,ad.excemption_number,ad.excemtion_attachment,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.Exemption, 
							hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay
                            from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
                            cd.Admission_ID = ad.Admision_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            pr.Registration_ID = ad.Registration_ID and
                            hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                            pr.Registration_ID = '$Registration_ID' and
                            ad.Admision_ID = '$Admision_ID' ") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Gender = $data['Gender'];
			$Guarantor_Name = $data['Guarantor_Name'];
			$NoOfDay = $data['NoOfDay'];
			$Exemption = $data['Exemption'];
			$excemption_number = $data['excemption_number'];
			$excemtion_attachment = $data['excemtion_attachment'];
			$Admission_Date_Time = $data['Admission_Date_Time'];
            $Discharge_Date_Time = $data['Discharge_Date_Time'];
            $imbalance_bill_approval_reason = $data['imbalance_bill_approval_reason'];
		}

		//Get patient age
		$date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
	}else{
		$Patient_Name = '';
		$Date_Of_Birth = '';
		$Gender = '';
		$Guarantor_Name = '';
		$NoOfDay = '0';
		$Exemption = 'no';
	}
?>
<table width="100%">
	<tr><td colspan="6"><hr></td></tr>
	<tr>
		<td width="8%" style="text-align: right;">Patient Name</td>
		<td><input type="text" value="<?php echo ucwords(strtolower($Patient_Name)); ?>" readonly="readonly"></td>
		<td style="text-align: right;">Gender</td>
		<td><input type="text" value="<?php echo ucwords(strtolower($Gender)); ?>" readonly="readonly"></td>
		<td style="text-align: right;">Age</td>
		<td><input type="text" value="<?php echo $age; ?>" readonly="readonly"></td>
	</tr>
	<tr>
		<td width="8%" style="text-align: right;">Sponsor Name</td>
		<td><input type="text" value="<?php echo strtoupper($Guarantor_Name); ?>" readonly="readonly"></td>
		<td style="text-align: right;">Ward Name</td>
		<td><input type="text" value="<?php echo ucwords(strtolower($Hospital_Ward_Name)); ?>" readonly="readonly"></td>
		<td style="text-align: right;">No Of Days </td>
		<td><input type="text" value="<?php echo ucwords(strtolower($NoOfDay)); ?>" readonly="readonly"></td>
	</tr>
        <tr>
            <td style="text-align: right;">CATEGORY </td>
            <td><input type="text" value="<?php echo $category_name_disp; ?>" readonly="readonly"></td>
            <?php if($excemtion_attachment!=0){ ?>
            <td style="text-align: right;">Excemption Number</td>
            <td><input type="text" value="<?= $excemption_number ?>" readonly="readonly"></td>
            <td style="text-align: right;">Excemption Attachment</td>
            <td>
                <object data="data/test.pdf"  width="300" height="200">
                    <a href="bill_excemption_attachment/<?= $excemtion_attachment ?>" target="_blank"><i class="fa fa-paperclip fa-2x" aria-hidden="true"></i></a>
                </object>
            </td>
           <?php  } ?>
            <td style="text-align: right;">Admission Date:-</td>
            <td> <b><?= $Admission_Date_Time ?></b></td>
            <td style="text-align: right;">Discharged Date:-</td>
            <td> <b><?= $Discharge_Date_Time ?></b></td>
        </tr>
        
        <tr>
            <td colspan="3" style="text-align: right;"> Reason:- </td>
            <td colspan="3" style="text-align: left;"> <b> <?= $imbalance_bill_approval_reason ?></b> </td>
        </tr>
	<tr><td colspan="6"><hr></td></tr>
	<!--<tr><td colspan="6" style="text-align: right;"><input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green"></td></tr>-->
</table>
<b>TRANSACTIONS DETAILS</b><hr>
<fieldset style="overflow-y: scroll; height: 350px; background-color: white;">

<?php
	$General_Total_Paid = 0;
    $General_Total_Cash_Needed = 0;
    $General_Total_Credit_Needed = 0;
    $General_Total_Exemption = 0;
	
	$Total_Paid = 0;
    $Total_Cash_Needed = 0;
    $Total_Credit_Needed = 0;
    $Total_Exemption = 0;
	
	$Cash_temp = 0;
	$Credit_temp = 0;
	$Exemption_temp = 0;
	$Paid_temp = 0;

    $Cash_Needed_Details = '<table width="100%">
								<tr>
									<td width="4%"><b>SN</b></td><td><b>ITEM NAME</b></td><td width="10%"><b>TRANSACTION#</b></td><td width="12%"><b>TRANSACTION DATE</b></td>
									<td width="7%" style="text-align: right;"><b>PRICE</b></td><td width="7%" style="text-align: right;"><b>DISCOUNT</b></td>
									<td width="7%" style="text-align: right;"><b>QUANTITY</b></td><td width="7%" style="text-align: right;"><b>SUB TOTAL</b></td>
								</tr>';
    $Credit_Needed_Details = '<table width="100%">
								<tr>
									<td width="4%"><b>SN</b></td><td><b>ITEM NAME</b></td><td width="10%"><b>TRANSACTION#</b></td><td width="12%"><b>TRANSACTION DATE</b></td>
									<td width="7%" style="text-align: right;"><b>PRICE</b></td><td width="7%" style="text-align: right;"><b>DISCOUNT</b></td>
									<td width="7%" style="text-align: right;"><b>QUANTITY</b></td><td width="7%" style="text-align: right;"><b>SUB TOTAL</b></td>
								</tr>';
    $Exemption_Details = '<table width="100%">
								<tr>
									<td width="4%"><b>SN</b></td><td><b>ITEM NAME</b></td><td width="10%"><b>TRANSACTION#</b></td><td width="12%"><b>TRANSACTION DATE</b></td>
									<td width="7%" style="text-align: right;"><b>PRICE</b></td><td width="7%" style="text-align: right;"><b>DISCOUNT</b></td>
									<td width="7%" style="text-align: right;"><b>QUANTITY</b></td><td width="7%" style="text-align: right;"><b>SUB TOTAL</b></td>
								</tr>';
    $Paid_Details = '<table width="100%">
								<tr>
									<td width="4%"><b>SN</b></td><td><b>ITEM NAME</b></td><td width="10%"><b>TRANSACTION#</b></td><td width="12%"><b>TRANSACTION DATE</b></td>
									<td width="7%" style="text-align: right;"><b>PRICE</b></td><td width="7%" style="text-align: right;"><b>DISCOUNT</b></td>
									<td width="7%" style="text-align: right;"><b>QUANTITY</b></td><td width="7%" style="text-align: right;"><b>SUB TOTAL</b></td>
								</tr>';

    //Calculate total
    $get_det = mysqli_query($conn,"select Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where 
                Registration_ID = '$Registration_ID' and
                Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
    $num = mysqli_num_rows($get_det);
    if($num > 0){
        while ($data = mysqli_fetch_array($get_det)) {
            $Patient_Bill_ID = $data['Patient_Bill_ID'];
            $Folio_Number = $data['Folio_Number'];
        }
    }else{
        $Patient_Bill_ID = 0;
        $Folio_Number = 0;
    }

	if(strtolower($Guarantor_Name) == 'cash'){
        $get_details = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.payment_type, pp.Billing_Type from tbl_patient_payments pp where 
                                Registration_ID = '$Registration_ID' and
                                pp.Transaction_status <> 'cancelled' and
                                pp.Transaction_type = 'indirect cash' and
                                (pp.Billing_Type = 'Inpatient Cash') and
                                pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                                pp.Folio_Number = '$Folio_Number' order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
    }else{
        if($Transaction_Type == 'Cash_Bill_Details'){
            $get_details = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.payment_type, pp.Billing_Type from tbl_patient_payments pp where 
                                Registration_ID = '$Registration_ID' and
                                pp.Transaction_status <> 'cancelled' and
                                pp.Transaction_type = 'indirect cash' and
                                (pp.Billing_Type = 'Inpatient Cash') and
                                pp.payment_type = 'post' and
                                pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                                pp.Folio_Number = '$Folio_Number' order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
        } else if($Transaction_Type == 'Credit_Bill_Details'){
            $get_details = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.payment_type, pp.Billing_Type from tbl_patient_payments pp where 
                                Registration_ID = '$Registration_ID' and
                                pp.Transaction_status <> 'cancelled' and
                                pp.Transaction_type = 'indirect cash' and
                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
                                pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                                pp.Folio_Number = '$Folio_Number' order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
        }else{
            $get_details = mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.payment_type, pp.Billing_Type from tbl_patient_payments pp where 
                                Registration_ID = '$Registration_ID' and
                                pp.Transaction_status <> 'cancelled' and
                                pp.Transaction_type = 'indirect cash' and
                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                                pp.Folio_Number = '$Folio_Number' order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
        }
    }

    while ($dtz = mysqli_fetch_array($get_details)) {
        $Patient_Payment_ID = $dtz['Patient_Payment_ID'];
        $Payment_Date_And_Time = $dtz['Payment_Date_And_Time'];
        $payment_type = $dtz['payment_type'];
        $Billing_Type = $dtz['Billing_Type'];

        $items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount from 
                                tbl_items i, tbl_patient_payment_item_list ppl,tbl_item_category ic,tbl_item_subcategory isc where
                                i.Item_ID = ppl.Item_ID and
                                i.Item_Subcategory_ID=isc.Item_Subcategory_ID and
                                isc.Item_Category_ID=ic.Item_Category_ID and
                                ppl.Patient_Payment_ID = '$Patient_Payment_ID' $category_fiter") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($items);
        if($nm > 0){
            while ($data = mysqli_fetch_array($items)) {
                if(strtolower($payment_type) == 'pre' && strtolower($Billing_Type) == 'inpatient cash'){
                    $Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                    $General_Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                	$Paid_Details .= '<tr id="sss">
											<td>'.++$Paid_temp.'</td><td>'.$data['Product_Name'].'</td><td>'.$Patient_Payment_ID.'</td><td>'.$Payment_Date_And_Time.'</td>
											<td style="text-align: right;">'.number_format($data['Price']).'</td><td style="text-align: right;">'.number_format($data['Discount']).'</td>
											<td style="text-align: right;">'.$data['Quantity'].'</td><td style="text-align: right;">'.number_format(($data['Price'] - $data['Discount']) * $data['Quantity']).'</td>
										</tr>';
                }else{
                    if(strtolower($Billing_Type) == 'inpatient cash'){
                        $Total_Cash_Needed += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                        $General_Total_Cash_Needed += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                        $Cash_Needed_Details .= '<tr id="sss">
														<td>'.++$Cash_temp.'</td><td>'.$data['Product_Name'].'</td><td>'.$Patient_Payment_ID.'</td><td>'.$Payment_Date_And_Time.'</td>
														<td style="text-align: right;">'.number_format($data['Price']).'</td><td style="text-align: right;">'.number_format($data['Discount']).'</td>
														<td style="text-align: right;">'.$data['Quantity'].'</td><td style="text-align: right;">'.number_format(($data['Price'] - $data['Discount']) * $data['Quantity']).'</td>
													</tr>';
                    }else if((strtolower($Billing_Type) == 'inpatient credit' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Exemption) == 'yes'){
                        $General_Total_Exemption += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                        $Total_Exemption += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                        $Exemption_Details .= '<tr id="sss">
													<td>'.++$Exemption_temp.'</td><td>'.$data['Product_Name'].'</td><td>'.$Patient_Payment_ID.'</td><td>'.$Payment_Date_And_Time.'</td>
													<td style="text-align: right;">'.number_format($data['Price']).'</td><td style="text-align: right;">'.number_format($data['Discount']).'</td>
													<td style="text-align: right;">'.$data['Quantity'].'</td><td style="text-align: right;">'.number_format(($data['Price'] - $data['Discount']) * $data['Quantity']).'</td>
												</tr>';
                    }else if((strtolower($Billing_Type) == 'inpatient credit' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Exemption) == 'no'){
                        $General_Total_Credit_Needed += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                        $Total_Credit_Needed += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                        $Credit_Needed_Details .= '<tr id="sss">
														<td>'.++$Credit_temp.'</td><td>'.$data['Product_Name'].'</td><td>'.$Patient_Payment_ID.'</td><td>'.$Payment_Date_And_Time.'</td>
														<td style="text-align: right;">'.number_format($data['Price']).'</td><td style="text-align: right;">'.number_format($data['Discount']).'</td>
														<td style="text-align: right;">'.$data['Quantity'].'</td><td style="text-align: right;">'.number_format(($data['Price'] - $data['Discount']) * $data['Quantity']).'</td>
													</tr>';
                    }else{
                        //Continue....
                    }
                }
            }
        }
    }

    //Get direct cash amount
    if(strtolower($Guarantor_Name) == 'cash'){
        //calculate cash payments
        $cal = mysqli_query($conn,"select pp.Payment_Date_And_Time, Product_Name, Item_Name, ppl.Price, ppl.Quantity, ppl.Discount from 
                            tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
                            i.Item_ID = ppl.Item_ID and
                            pp.Transaction_type = 'direct cash' and
                            pp.Transaction_status <> 'cancelled' and
                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            pp.Billing_Type<>'Outpatient Cash' and
                            pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                            pp.Folio_Number = '$Folio_Number' and
                            pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $nms = mysqli_num_rows($cal);
        if($nms > 0){
            while ($data = mysqli_fetch_array($cal)) {
                $Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                $General_Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                $Paid_Details .= '<tr id="sss">
									<td>'.++$Paid_temp.'</td><td>'.$data['Product_Name'].'-'.$data['Item_Name'].'</td><td>'.$Patient_Payment_ID.'</td><td>'.$data['Payment_Date_And_Time'].'</td>
									<td style="text-align: right;">'.number_format($data['Price']).'</td><td style="text-align: right;">'.number_format($data['Discount']).'</td>
									<td style="text-align: right;">'.$data['Quantity'].'</td><td style="text-align: right;">'.number_format(($data['Price'] - $data['Discount']) * $data['Quantity']).'</td>
								</tr>';
            }
        }
    }else{
        if($Transaction_Type != 'Credit_Bill_Details'){
            //calculate cash payments
            $cal = mysqli_query($conn,"select pp.Payment_Date_And_Time, Product_Name, Item_Name, ppl.Price, ppl.Quantity, ppl.Discount from 
                                tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
                                i.Item_ID = ppl.Item_ID and
                                pp.Transaction_type = 'direct cash' and
                                pp.Transaction_status <> 'cancelled' and
                                pp.Billing_Type<>'Outpatient Cash' and
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                                pp.Folio_Number = '$Folio_Number' and
                                pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
            $nms = mysqli_num_rows($cal);
            if($nms > 0){
                while ($data = mysqli_fetch_array($cal)) {
                    $Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                    $General_Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                    $Paid_Details .= '<tr id="sss">
											<td>'.++$Paid_temp.'</td><td>'.$data['Product_Name'].'-'.$data['Item_Name'].'</td><td>'.$Patient_Payment_ID.'</td><td>'.$data['Payment_Date_And_Time'].'</td>
											<td style="text-align: right;">'.number_format($data['Price']).'</td><td style="text-align: right;">'.number_format($data['Discount']).'</td>
											<td style="text-align: right;">'.$data['Quantity'].'</td><td style="text-align: right;">'.number_format(($data['Price'] - $data['Discount']) * $data['Quantity']).'</td>
										</tr>';
                }
            }
        }
    }
?>
<b>Cash</b><hr>
<?php echo $Cash_Needed_Details; ?>
<tr><td colspan="8"><hr></td></tr>
<tr><td colspan="7"><b>GRAND TOTAL</b></td><td style="text-align: right;"><b><?php echo number_format($Total_Cash_Needed); ?></b></td></tr>
</table><br/>

<b>Credit</b><hr>
<?php echo $Credit_Needed_Details; ?>
<tr><td colspan="8"><hr></td></tr>
<tr><td colspan="7"><b>GRAND TOTAL</b></td><td style="text-align: right;"><b><?php echo number_format($Total_Credit_Needed); ?></b></td></tr>
</table><br/>

<b>Exemption</b><hr>
<?php echo $Exemption_Details; ?>
<tr><td colspan="8"><hr></td></tr>
<tr><td colspan="7"><b>GRAND TOTAL</b></td><td style="text-align: right;"><b><?php echo number_format($Total_Exemption); ?></b></td></tr>
</table><br/>

<b>Paid Transactions</b><hr>
<?php echo $Paid_Details; ?>
<tr><td colspan="8"><hr></td></tr>
<tr><td colspan="7"><b>GRAND TOTAL</b></td><td style="text-align: right;"><b><?php echo number_format($Total_Paid); ?></b></td></tr>
</table><br/>

</fieldset>
<hr>