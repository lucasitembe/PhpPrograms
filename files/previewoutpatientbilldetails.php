<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Patients_Billing_Works'])) {
        if ($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Check_In_ID'])) {
    $Check_In_ID = $_GET['Check_In_ID'];
} else {
    $Check_In_ID = '';
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

if (strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes') {
    $payments_filter = "pp.payment_type = 'post' and ";
} else {
    $payments_filter = '';
}

$canZero = false;
if (strtolower($_SESSION['systeminfo']['enable_zeroing_price']) == 'yes') {
    $canZero = true;
}


?>


<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style> 
<?php
if (isset($_GET['Status']) && $_GET['Status'] == 'cld') {
    $select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name,sp.payment_method,sp.Sponsor_ID,
							emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, bd.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status, ad.Admision_ID
							from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_check_in_details cd, tbl_hospital_ward hp,tbl_beds bd where
							cd.Admission_ID = ad.Admision_ID and
							bd.Bed_ID = ad.Bed_ID and
							ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and
							pr.Registration_ID = ad.Registration_ID and 
							pr.Sponsor_ID = sp.Sponsor_ID and
							emp.Employee_ID= ad.Admission_Employee_ID and
							(ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') and
							cd.Check_In_ID = '$Check_In_ID'
							") or die(mysqli_error($conn));
} else {
    $select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.payment_method,sp.Sponsor_ID,
							emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, bd.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status, ad.Admision_ID
							from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_check_in_details cd, tbl_hospital_ward hp,tbl_beds bd where
							cd.Admission_ID = ad.Admision_ID and
							bd.Bed_ID = ad.Bed_ID and
							ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and
							pr.Registration_ID = ad.Registration_ID and 
							pr.Sponsor_ID = sp.Sponsor_ID and
							emp.Employee_ID= ad.Admission_Employee_ID and
							(ad.Admission_Status = 'Pending' or ad.Admission_Status = 'Admitted') and
							cd.Check_In_ID = '$Check_In_ID'
							") or die(mysqli_error($conn));
}
$num = mysqli_num_rows($select);

if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Registration_ID = $data['Registration_ID'];
        $Gender = $data['Gender'];
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Member_Number = $data['Member_Number'];
        $Payment_Method = $data['payment_method'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Employee_Name = $data['Employee_Name'];
        $Admission_Date_Time = $data['Admission_Date_Time'];
        $Bed_Name = $data['Bed_Name'];
        $Hospital_Ward_Name = $data['Hospital_Ward_Name'];
        $Sponsor_ID = $data['Sponsor_ID'];
        $Cash_Bill_Status = $data['Cash_Bill_Status'];
        $Credit_Bill_Status = $data['Credit_Bill_Status'];
        $Admision_ID = $data['Admision_ID'];

        //calculate age
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    }
} else {
    $Patient_Name = '';
    $Registration_ID = '';
    $Guarantor_Name='';
    $Gender = '';
    $Date_Of_Birth = '';
    $Member_Number = '';
    $Payment_Method = '';
    $Employee_Name = '';
    $Admission_Date_Time = '';
    $Bed_Name = '';
    $Hospital_Ward_Name = '';
    $Sponsor_ID = '';
    $Cash_Bill_Status = '';
    $Credit_Bill_Status = '';
    $Admision_ID = '';
}
// if (isset($_SESSION['userinfo'])) {
    // if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
        
    echo    "<a target='_blank' href='inpatientbillingdirectcash.php?Registration_ID=".$Registration_ID."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' class='art-button-green'>
            PAY BILL
        </a>";
     // }
// }
if (isset($_SESSION['userinfo']['Admission_Works'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
        echo "<a href='searchlistofoutpatientadmited.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>DISCHARGE PATIENT</a>";
        echo "<a href='searchlistofmortuaryadmited.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>DISCHARGE MORTUARY</a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_GET['Status']) && strtolower($_GET['Status']) == 'cld') {
        echo "<a href='clearedpatientbillingwork.php?ClearedPatientsBillingWorks=ClearedPatientsBillingWorks' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='billingwork.php?BillingWork=BillingWorkThisPage' class='art-button-green'>BACK</a>";
    }
}
 
echo "<br/><br/>";
//get last Patient_Bill_ID
$select = mysqli_query($conn,"select Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where 
							Registration_ID = '$Registration_ID' and
							Check_In_ID = '$Check_In_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Bill_ID = $data['Patient_Bill_ID'];
        $Folio_Number = $data['Folio_Number'];
    }
} else {
    $Patient_Bill_ID = 0;
    $Folio_Number = 0;
}
// echo $Patient_Bill_ID;
// exit;
//select diagnosis details outpatient
$diagnosis = "";
$Consultant_Name = "";
$Consultation_ID = '';

$select_con = mysqli_query($conn,"SELECT c.Consultation_ID,d.disease_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name
    	FROM tbl_consultation c,tbl_disease_consultation dc, tbl_disease d
    	WHERE c.Consultation_ID=dc.Consultation_ID AND d.Disease_ID = dc.Disease_ID
    	AND dc.diagnosis_type = 'diagnosis'
    	AND c.Patient_Payment_Item_List_ID IN (
    	    SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE
    		ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
    		pp.Folio_Number = '$Folio_Number' and
    		pp.Registration_ID = '$Registration_ID' and pp.Patient_Bill_ID = '$Patient_Bill_ID')") or die(mysqli_error($conn));
$no_of_rows = mysqli_num_rows($select_con);
if ($no_of_rows > 0) {
    while ($diagnosis_row = mysqli_fetch_array($select_con)) {
        $diagnosis .= $diagnosis_row['disease_code'] . "; ";
        $Consultant_Name = $diagnosis_row['Consultant_Name'] . "; ";
 
    }
}
$Consultation_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT consultation_ID FROM tbl_check_in_details WHERE Folio_Number='$Folio_Number' AND Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID'"))['consultation_ID'];
$select_con1 = mysqli_query($conn,"SELECT d.disease_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = w.Employee_ID) as Consultant_Name
    	FROM tbl_ward_round w,tbl_ward_round_disease dw, tbl_disease d
    	WHERE w.Round_ID=dw.Round_ID AND d.Disease_ID = dw.Disease_ID
    	AND dw.diagnosis_type = 'diagnosis'
        AND w.Process_Status = 'served'
    	AND w.Consultation_ID ='$Consultation_ID' ") or die(mysqli_error($conn));

$no_of_rows1 = mysqli_num_rows($select_con1);
if ($no_of_rows1 > 0) {
    while ($diagnosis_row1 = mysqli_fetch_array($select_con1)) {
        $diagnosis .= $diagnosis_row1['disease_code'] . "; ";
        $Consultant_Name .= $diagnosis_row1['Consultant_Name'] . "; ";
    }
}
?>

<fieldset>
    <table width="100%">
        <tr>
            <td width="25%"><b>Patient Name &nbsp;&nbsp;&nbsp;</b><?php echo ucwords(strtolower($Patient_Name)); ?></td>
            <td width="25%"><b>Patient Number &nbsp;&nbsp;&nbsp;</b><?php echo $Registration_ID; ?></td>
            <td width="20%"><b>Sponsor Name &nbsp;&nbsp;&nbsp;</b><?php echo strtoupper($Guarantor_Name); ?></td>
            <td width="15%"><b>Member Number &nbsp;&nbsp;&nbsp;</b><?php echo $Member_Number; ?></td>
            <td width="15%"><b>&nbsp;&nbsp;&nbsp;Folio Number &nbsp;&nbsp;&nbsp;</b><?php echo $Folio_Number; ?></td>
        </tr>
        <tr>
            <td><b>Gender &nbsp;&nbsp;&nbsp;</b><?php echo $Gender; ?></td>
            <td><b>Admitted By &nbsp;&nbsp;&nbsp;</b><?php echo $Employee_Name; ?></td>
            <td><b>Admission Date &nbsp;&nbsp;&nbsp;</b><?php echo $Admission_Date_Time; ?></td>
            <td colspan="2"><b>Ward & Room Number&nbsp;&nbsp;&nbsp;</b><?php echo $Hospital_Ward_Name . ' ~ ' . $Bed_Name; ?></td>
        </tr>
        <tr>
            <td><b>Disease code &nbsp;&nbsp;&nbsp;</b><?php echo $diagnosis; ?></td>
            <td colspan="3"><b>Consultant &nbsp;&nbsp;&nbsp;</b><?php echo $Consultant_Name; ?></td>
            <td></td>
        </tr>
    </table>
</fieldset>

<?php
//==================CHECKING FROM MORGUE, DONE BY FULL STACK DEVELOPERS===================
function getMorguePrices($item_condition,$charges_type){
$morguePrices=mysqli_query($conn,"SELECT mp.price,itm.Product_Name FROM tbl_morgue_prices mp,tbl_items itm WHERE item_condition='$item_condition' AND charges_type='$charges_type' AND itm.Item_ID=mp.Item_ID") or die(mysqli_error($conn));
$num=mysqli_num_rows($morguePrices);
while($prices =  mysqli_fetch_array($morguePrices)){
$price=$prices['price'];	
$Product_Name=$prices['Product_Name'];
$itemDetails=array(
'price'=>$price,
'Product_Name'=>$Product_Name
);
return $itemDetails;
}	
}

$morgueDetails=mysqli_query($conn,"SELECT ma.Date_Of_Death, ma.case_type, ad.Admission_Date_Time FROM tbl_mortuary_admission ma, tbl_admission ad WHERE Corpse_ID='$Registration_ID' AND ad.Admision_ID=ma.Admision_ID ORDER BY Admission_Date_Time DESC LIMIT 1") or die(mysqli_error($conn));
$num=mysqli_num_rows($morgueDetails);
if ($num > 0) {
 $Payment_Method="cash";
 //============OVERIDDING SPONSOR TO CASH=====================
 $Sponsor_ID=mysqli_fetch_array(mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE payment_method='$Payment_Method' ORDER BY Sponsor_ID ASC LIMIT 1"))['Sponsor_ID'];
 // echo $Sponsor_ID;
 // exit;
	 $Folio_Number =0;
	 include("./includes/Get_Patient_Transaction_Number.php");	
	

while($data =  mysqli_fetch_array($morgueDetails)){
	$case_type=$data['case_type'];
	$date_death=$data['Date_Of_Death'];
	$Admission_Date_Time=$data['Admission_Date_Time'];
	//echo $Date_Of_Birth; exit;
	//$Date_Of_Birth = strtotime($Date_Of_Birth);
	$date_death= strtotime($date_death);
	$date_death = date("Y-m-d",$date_death);
	
	$date1 = new DateTime($date_death);
	$date2 = new DateTime($Date_Of_Birth);
	$diff = $date1->diff($date2);
	$year = $diff->y;
	$month= $diff->m;
	$days= $diff->d ;
	//echo $month ." ".$days;
	$admission_date = strtotime($Admission_Date_Time);
	$Admission_Date_Time = date("Y-m-d",$admission_date );
	$admission_date1 = new DateTime($Admission_Date_Time);
	$leo = new DateTime($Today);
	$diff2 = $leo->diff($admission_date1)->format("%a");
//echo $year. " ".$month." ".$days; exit;
}
//=======================FOR HOSPITAL CASE==============================
if($case_type=="hospital"){
//=======================UNDER ONE MONTH====================================
if($year<1 && (($month == 1 && $days == 0) || $month < 1)){
$priceServices=getMorguePrices("bmc_under_1_month","services")['price'];
$priceKeeping=getMorguePrices("bmc_under_1_month","keeping")['price'];
$nameServices=getMorguePrices("bmc_under_1_month","services")['Product_Name'];
$nameKeeping=getMorguePrices("bmc_under_1_month","keeping")['Product_Name'];
// echo $year. " ".$month." ".$days;
}
else if(($year < 5  && $year > 0) || ($year == 0 && $month >= 1 && $days > 0)){
//===========================UNDER 5 YEARS, OVER 1 MONTH==================================
$priceServices=getMorguePrices("bmc_under_5","services")['price'];
$priceKeeping=getMorguePrices("bmc_over_1_month","keeping")['price'];
$nameServices=getMorguePrices("bmc_under_5","services")['Product_Name'];
$nameKeeping=getMorguePrices("bmc_over_1_month","keeping")['Product_Name'];
// echo $year. " ".$month." ".$days;	
}else if($year>=5){
//=========================OVER 5 YEARS=========================================
	// echo $year. " ".$month." ".$days;
$priceServices=getMorguePrices("bmc_above_5","services")['price'];
$priceKeeping=getMorguePrices("bmc_over_1_month","keeping")['price'];
$nameServices=getMorguePrices("bmc_above_5","services")['Product_Name'];
$nameKeeping=getMorguePrices("bmc_over_1_month","keeping")['Product_Name'];		
}	
}else{
//===============================OUTSIDE CASE===================================================
$priceServices=getMorguePrices("outside","services")['price'];
$priceKeeping=getMorguePrices("outside","keeping")['price'];	
$nameServices=getMorguePrices("outside","services")['Product_Name'];
$nameKeeping=getMorguePrices("outside","keeping")['Product_Name'];	
}
$keepingPrice=($diff2)*$priceKeeping;
$diff2=($diff2==0?"Within 24 hrs":$diff2=$diff2);
// echo $priceKeeping." ".$nameKeeping;
// exit;
?>
<fieldset>
    <table width="100%">
        <tr>
            <td width="70%">
                <fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Transaction_Items_Details'>
                    <legend>
					CASH BILL DETAILS
                    </legend>
                      <table width='100%'>
                      <tr><td colspan='7'></td></tr>
                                <tr>
                                    <td width="4%">SN</td>
                                    <td>ITEM NAME</td>
                                    <td width="10%" style="text-align: right;">PRICE</td>
                                    <td width="10%" style="text-align: right;">DAYS</td>
                                    <td width="10%" style="text-align: right;">SUB TOTAL</td>
                                </tr>
	                           <tr>
                                    <td width="4%">1</td>
                                    <td><?=strtoupper($nameServices)?></td>
                                    <td width="10%" style="text-align: right;"><?=number_format($priceServices,2)?></td>
                                    <td width="10%" style="text-align: right;"><?=$diff2?></td>
                                    <td width="10%" style="text-align: right;"><?=number_format($priceServices,2)?></td>
                                </tr>
		
								<tr>
                                    <td width="4%">2</td>
                                    <td><?=strtoupper($nameKeeping)?></td>
                                    <td width="10%" style="text-align: right;"><?=number_format($priceKeeping,2)?></td>
                                    <td width="10%" style="text-align: right;"><?=$diff2?></td>
                                    <td width="10%" style="text-align: right;"><?=number_format($keepingPrice,2)?></td>
									<?php $totalmorgueprice=$keepingPrice+$priceServices;?>
                                </tr>
					<tr><td colspan="5"><hr></td></tr>								
								<tr>
								<td><b>TOTAL</b></td>
								<td></td>
								<td></td>
								<td></td>
                                <td style="text-align:right"><b><?=number_format($totalmorgueprice,2)?></b>
								 <input id="Grand_Total" type="hidden" value="<?=$totalmorgueprice?>" />
								</td>

                                </tr>	
 <tr><td colspan="5"><hr></td></tr>									
                             </table>
                </fieldset>
            </td>
            <td id='Transaction_Summary_Area'>
                <fieldset style='overflow-y: scroll; height: 340px; background-color: white;'>
                    <legend> 
					BILL SUMMARY
                    </legend>

                    <table width="100%">
						                      <tr><td colspan='7'></td></tr>
                                <tr>
                                    <td width="4%">SN</td>
                                    <td width="40%">ITEM NAME</td>
                                    <td width="40%" style="text-align: left;">PRICE</td>
                                    <td width="40%" style="text-align: left;">DAYS</td>
                                    <td width="40%" style="text-align: right;">SUB TOTAL</td>
                                </tr>
	                           <tr>
                                    <td width="4%">1</td>
                                    <td width="40%"><?=strtoupper($nameServices)?></td>
                                    <td width="40%" style="text-align: left;"><?=number_format($priceServices,2)?></td>
                                    <td width="40%" style="text-align: left;"><?=$diff2?></td>
                                    <td width="40%" style="text-align: right;"><?=number_format($priceServices,2)?></td>
                                </tr>
		
								<tr>
                                    <td width="4%">2</td>
                                    <td style="text-align: left;"><?=strtoupper($nameKeeping)?></td>
                                    <td width="40%" style="text-align: left;"><?=number_format($priceKeeping,2)?></td>
                                    <td width="40%" style="text-align: left;"><?=$diff2?></td>
                                    <td width="40%" style="text-align: right;"><?=number_format($keepingPrice,2)?></td>
									<?php $totalmorgueprice=$keepingPrice+$priceServices;?>
                                </tr>
					<tr><td colspan="5"><hr></td></tr>								
								<tr>
								<td><b>TOTAL</b></td>
								<td></td>
								<td></td>
								<td></td>
                                <td style="text-align:right"><b><?=number_format($totalmorgueprice,2)?></b>
								 <input id="Grand_Total" type="hidden" value="<?=$totalmorgueprice?>" />
								</td>

                                </tr>	
 <tr><td colspan="5"><hr></td></tr>
                         <tr>
                            <td colspan="4" width="65%"><b>Advance Payments</b></td>
                            <td style="text-align: right;">
                                <?php
                                $Grand_Total_Direct_Cash = 0;
                                if (strtolower($Payment_Method) == 'cash') {
                                    //calculate cash payments
                                    $cal = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
													tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
													pp.Transaction_type = 'direct cash' and
													pp.Transaction_status <> 'cancelled' and
													pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
													pp.Patient_Bill_ID = '$Patient_Bill_ID' and
													pp.Folio_Number = '$Folio_Number' and
													pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                    $nms = mysqli_num_rows($cal);
									// echo $Patient_Bill_ID." ".$Folio_Number;
									// exit;
                                    if ($nms > 0) {
                                        while ($cls = mysqli_fetch_array($cal)) {
                                            $Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
                                        }
                                    }
                                    echo number_format($Grand_Total_Direct_Cash);
                                } else {
                                    echo "<i>(not applicable)</i>";
                                }
                                $Temp_Balance = ($totalmorgueprice - $Grand_Total_Direct_Cash);
                                ?>&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr><td colspan="5"><hr></td></tr>
                        <tr>
                            <td colspan="4"><b>Balance </b></td>
                            <td style="text-align: right;">
                                <?php
                                if ($Temp_Balance > 0) {
                                    echo number_format($Temp_Balance);
                                } else {
                                    echo 0;
                                }
                                ?>&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <?php
                        if ($Temp_Balance < 0) {
                            ?>
                            <tr>
                                <td colspan="4"><b>Refund Amount</b></td><td style="text-align: right;"><?php echo number_format(substr($Temp_Balance, 1)); ?>&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr><td colspan="5"><hr></td></tr>
                    </table>
                </fieldset><br/>
                <table width="100%">
                    <tr>
                        <td><input id="Transaction_Type" type="hidden" value="Cash_Bill_Details" />
                            <input type="button" name="Filter" id="Filter" value="PREVIEW BILL" class="art-button-green" onclick="Preview_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                        </td>
                        <td>
                            <?php
                                if ($Cash_Bill_Status == 'pending') {
                                    ?>
                                    <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                                    <?php
                                } else if ($Cash_Bill_Status == 'approved') {
                                    ?>
                                    <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill_Warning()">
                                    <?php
                                }
                            
                            ?>
                        </td>
                    </tr>
<tr><td id="Details_Area"></td></tr>
                </table>
            </td>
        </tr>

    </table>
</fieldset>
   <?php }else{
?>
<!-- =============================END OF MORGUE SECTION============================================= -->
<fieldset>
    <table width="100%">
        <tr>
            <td width="70%">
                <fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Transaction_Items_Details'>
                    <legend>
                        <?php
                        if (strtolower($Payment_Method) == 'cash') {
                            echo "CASH BILL DETAILS";
                        } else {
                            echo "CREDIT BILL DETAILS";
                        }
                        ?>
                    </legend>
                    <?php
                    $Grand_Total = 0;
                    if (isset($_SESSION['Sort_Mode']) && $_SESSION['Sort_Mode'] == 'Group_By_Receipt') {
                        if (strtolower($Payment_Method) == 'cash') {
                            $get_details = mysqli_query($conn,"select pp.Patient_Bill_ID, pp.Sponsor_ID, pp.Folio_Number, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Patient_Bill_ID from tbl_patient_payments pp where 
												Registration_ID = '$Registration_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Transaction_type = 'indirect cash' and
												(pp.Billing_Type = 'Inpatient Cash') and
												$payments_filter
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Folio_Number = '$Folio_Number' order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        } else {
                            $get_details = mysqli_query($conn,"select pp.Patient_Bill_ID, pp.Sponsor_ID, pp.Folio_Number, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Patient_Bill_ID from tbl_patient_payments pp where 
												Registration_ID = '$Registration_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Transaction_type = 'indirect cash' and
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Folio_Number = '$Folio_Number' order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }
                        $num = mysqli_num_rows($get_details);
                        if ($num > 0) {
                            $temp_rec = 0;
                            while ($row = mysqli_fetch_array($get_details)) {
                                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                                echo "<table width='100%'>";
                                echo "<tr><td colspan='6'><b>" . ++$temp_rec . '. Receipt Number ~ <i><label style="color: #0079AE;" onclick="View_Details(' . $row['Patient_Payment_ID'] . ',0);">' . $row['Patient_Payment_ID'] . '</label></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receipt Date ~ ' . $row['Payment_Date_And_Time'] . "</b></td></tr>";
                                ?>
                                <tr>
                                    <td width="4%">SN</td>
                                    <?php echo ($canZero) ? '<td width="4%">&nbsp;</td>' : '' ?>
                                    <td>ITEM NAME</td>
                                    <td width="10%" style="text-align: right;">PRICE</td>
                                    <td width="10%" style="text-align: right;">DISCOUNT</td>
                                    <td width="10%" style="text-align: right;">QUANTITY</td>
                                    <td width="10%" style="text-align: right;">SUB TOTAL</td>
                                </tr>
                                <tr><td colspan='6'><hr></td></tr>
                                <?php
                                $items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount,ppl.Patient_Payment_Item_List_ID from 
														tbl_items i, tbl_patient_payment_item_list ppl where
														i.Item_ID = ppl.Item_ID and
														ppl.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
                                $nm = mysqli_num_rows($items);
                                if ($nm > 0) {
                                    $temp = 0;
                                    $Sub_Total = 0;
                                    while ($dt = mysqli_fetch_array($items)) {
                                        echo '<tr>
											<td width="4%">' . ++$temp . '<b>.</b></td>';
                                        $chk = '';
                                        if ($dt['Price'] == 0) {
                                            $chk = 'checked="true" onclick="addPrice(' . $dt['Patient_Payment_Item_List_ID'] . ',this)"';
                                        }
                                        echo ($canZero) ? '<td width="4%"><input type="checkbox" class="zero_items" id="' . $dt['Patient_Payment_Item_List_ID'] . '" ' . $chk . '/></td>' : '';
                                        echo '<td><label for="' . $dt['Patient_Payment_Item_List_ID'] . '" style="display:block">' . ucwords(strtolower($dt['Product_Name'])) . '</label></td>
											<td>&nbsp;</td>  
                                                                                        <td width="10%" style="text-align: right">' . number_format($dt['Price']) . '</td>
											<td width="10%" style="text-align: right;">' . number_format($dt['Discount']) . '</td>
											<td width="10%" style="text-align: right;">' . $dt['Quantity'] . '</td>
											<td width="10%" style="text-align: right;">' . number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']) . '</td>
										</tr>';
                                        $Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                        $Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                    }
                                    echo "<tr><td colspan='8'><hr></td></tr>";
                                    echo "<tr><td colspan='7' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>" . number_format($Sub_Total) . "</b></td></tr>";
                                }
                                echo "</table>";
                            }
                        }
                    } else {
                        //get categories
                        if (strtolower($Payment_Method) == 'cash') {
                            $get_cat = mysqli_query($conn,"select ic.Item_category_ID, ic.Item_Category_Name from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												(pp.Billing_Type = 'Inpatient Cash') and
												$payments_filter
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
                        } else {
                            $get_cat = mysqli_query($conn,"select ic.Item_category_ID, ic.Item_Category_Name from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
                        }
                        $num = mysqli_num_rows($get_cat);
                        if ($num > 0) {
                            $temp_cat = 0;
                            while ($row = mysqli_fetch_array($get_cat)) {
                                $Item_category_ID = $row['Item_category_ID'];
                                echo "<table width='100%'>";
                                echo "<tr><td colspan='7'><b>" . ++$temp_cat . '. ' . strtoupper($row['Item_Category_Name']) . "</b></td></tr>";
                                ?>
                                <tr>
                                    <td width="4%">SN</td>
                                    <?php echo ($canZero) ? '<td width="4%">&nbsp;</td>' : '' ?>
                                    <td>ITEM NAME</td>
                                    <td width="10%" style="text-align: center;">RECEIPT#</td>
                                    <td width="10%" style="text-align: right;">PRICE</td>
                                    <td width="10%" style="text-align: right;">DISCOUNT</td>
                                    <td width="10%" style="text-align: right;">QUANTITY</td>
                                    <td width="10%" style="text-align: right;">SUB TOTAL</td>
                                </tr>
                                <!-- <tr><td colspan='7'><hr></td></tr> -->
                                <?php
                                if (strtolower($Payment_Method) == 'cash') {
                                    $items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Billing_Type = 'Inpatient Cash' and
												$payments_filter
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_category_ID = '$Item_category_ID' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                } else {
                                    $items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_category_ID = '$Item_category_ID' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                }

                                $nm = mysqli_num_rows($items);
                                if ($nm > 0) {
                                    $temp = 0;
                                    $Sub_Total = 0;
                                    while ($dt = mysqli_fetch_array($items)) {
                                        echo '<tr>
											<td width="4%">' . ++$temp . '<b>.</b></td>';
                                        $chk = '';
                                        if ($dt['Price'] == 0) {
                                            $chk = 'checked="true" onclick="addPrice(' . $dt['Patient_Payment_Item_List_ID'] . ',this)"';
                                        }
                                        echo ($canZero) ? '<td width="4%"><input type="checkbox" class="zero_items" id="' . $dt['Patient_Payment_Item_List_ID'] . '" ' . $chk . '/></td>' : '';
                                        echo '<td><label for="' . $dt['Patient_Payment_Item_List_ID'] . '" style="display:block">' . ucwords(strtolower($dt['Product_Name'])) . '</label></td>
											<td width="10%" style="text-align: center"><label style="color: #0079AE;" onclick="View_Details(' . $dt['Patient_Payment_ID'] . ',' . $dt['Patient_Payment_Item_List_ID'] . ');"><b>' . $dt['Patient_Payment_ID'] . '</b></label></td>
										    <td width="10%" style="text-align: right">' . number_format($dt['Price']) . '</td>
											<td width="10%" style="text-align: right;">' . number_format($dt['Discount']) . '</td>
											<td width="10%" style="text-align: right;">' . $dt['Quantity'] . '</td>
											<td width="10%" style="text-align: right;">' . number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']) . '</td>
										</tr>';
                                        $Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                        $Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
                                    }
                                    echo "<tr><td colspan='8'><hr></td></tr>";
                                    echo "<tr><td colspan='7' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>" . number_format($Sub_Total) . "</b></td></tr>";
                                }
                                echo "</table>";
                            }
                        }
                    }
                    ?>
                </fieldset>
            </td>
            <td id='Transaction_Summary_Area'>
                <fieldset style='overflow-y: scroll; height: 280px; background-color: white;'>
                    <legend>
                        <?php
                        if (strtolower($Payment_Method) == 'cash') {
                            echo "CASH BILL SUMMARY";
                        } else {
                            echo "CREDIT BILL SUMMARY";
                        }
                        ?>
                    </legend>

                    <table width="100%">
                            <!-- <tr>
                                    <td width="65%"><b>Bill Type </b></td><td><?php
                        if (strtolower($Payment_Method) == 'cash') {
                            echo "CASH";
                        } else {
                            echo "CREDIT";
                        }
                        ?></td>
                            </tr> -->
                        <tr><td><b>CATEGORY</b></td><td style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td></tr>
                        <?php
                        if (strtolower($Payment_Method) == 'cash') {
                            $get_cate = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												(pp.Billing_Type = 'Inpatient Cash') and
												$payments_filter
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
                        } else {
                            $get_cate = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID' group by ic.Item_Category_ID") or die(mysqli_error($conn));
                        }

                        $nms_slct = mysqli_num_rows($get_cate);
                        $tmp = 0;
                        if ($nms_slct > 0) {
                            $cont = 0;
                            while ($dts = mysqli_fetch_array($get_cate)) {
                                $Item_Category_Name = $dts['Item_Category_Name'];
                                $Item_Category_ID = $dts['Item_Category_ID'];
                                $Category_Grand_Total = 0;

                                //calculate total
                                if (strtolower($Payment_Method) == 'cash') {
                                    $items = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Billing_Type = 'Inpatient Cash' and
												$payments_filter
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_Category_ID = '$Item_Category_ID' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                } else {
                                    $items = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												(pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit') and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_Category_ID = '$Item_Category_ID' and
												pp.Folio_Number = '$Folio_Number' and
												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                }
                                $nums = mysqli_num_rows($items);
                                if ($nums > 0) {
                                    while ($t_item = mysqli_fetch_array($items)) {
                                        $Category_Grand_Total += (($t_item['Price'] - $t_item['Discount']) * $t_item['Quantity']);
                                    }
                                }
                                echo "<tr><td>" . ++$cont . '<b>. </b>' . ucwords(strtolower($Item_Category_Name)) . "</td><td style='text-align: right;'>" . number_format($Category_Grand_Total) . "&nbsp;&nbsp;&nbsp;</td></tr>";
                            }
                        }
                        ?>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                            <td><b>Bill Status</b></td>
                            <?php
                            if (strtolower($Payment_Method) == 'cash') {
                                echo "<td style='text-align: right;'>" . ucwords(strtolower($Cash_Bill_Status)) . "&nbsp;&nbsp;&nbsp;</td>";
                            } else {
                                echo "<td style='text-align: right;'>" . ucwords(strtolower($Credit_Bill_Status)) . "&nbsp;&nbsp;&nbsp;</td>";
                            }
                            ?>
                        </tr>
                        <tr>
                            <td width="65%"><b>Total Amount Required </b></td><td style="text-align: right;"><?php echo number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp; <input type="text" id="Grand_Total" value="<?= $Grand_Total ?>" hidden="hidden"></td>
                        </tr>
                        <tr>
                            <td width="65%"><b>Advance Payments</b></td>
                            <td style="text-align: right;">
                                <?php
                                $Grand_Total_Direct_Cash = 0;
                                if (strtolower($Payment_Method) == 'cash') {
                                    //calculate cash payments
                                    $cal = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
													tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
													pp.Transaction_type = 'Direct cash' and
													pp.Transaction_status <> 'cancelled' and
													pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
													pp.Patient_Bill_ID = '$Patient_Bill_ID' and
													pp.Folio_Number = '$Folio_Number' and
													pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                    $nms = mysqli_num_rows($cal);
                                    if ($nms > 0) {
                                        while ($cls = mysqli_fetch_array($cal)) {
                                            $Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
                                        }
                                    }
                                    echo number_format($Grand_Total_Direct_Cash);
                                } else {
                                    echo "<i>(not applicable)</i>";
                                }
                                $Temp_Balance = ($Grand_Total - $Grand_Total_Direct_Cash);
                                ?>&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>
                        <tr>
                            <td><b>Balance </b></td>
                            <td style="text-align: right;">
                                <?php
                                if ($Temp_Balance > 0) {
                                    echo number_format($Temp_Balance);
                                } else {
                                    echo 0;
                                }
                                ?>&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <?php
                        if ($Temp_Balance < 0) {
                            ?>
                            <tr>
                                <td><b>Refund Amount</b></td><td style="text-align: right;"><?php echo number_format(substr($Temp_Balance, 1)); ?>&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr><td colspan="2"><hr></td></tr>
                    </table>
                </fieldset><br/>
                <table width="100%">
                    <tr>
                        <td>
                            <input type="button" name="Filter" id="Filter" value="PREVIEW BILL" class="art-button-green" onclick="Preview_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                        </td>
                        <td>
                            <?php
                            if (strtolower($Payment_Method) == 'cash') {
                                if ($Cash_Bill_Status == 'pending') {
                                    ?>
                                    <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                                    <?php
                                } else if ($Cash_Bill_Status == 'approved') {
                                    ?>
                                    <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill_Warning()">
                                    <?php
                                }
                            } else {
                                if ($Credit_Bill_Status == 'pending') {
                                    ?>
                                    <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                                    <?php
                                } else if ($Credit_Bill_Status == 'approved') {
                                    ?>
                                    <input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill_Warning()">
                                    <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="2"><hr></td></tr>
                    <tr>
                        <td>
                            <?php if (strtolower($Payment_Method) == 'cash') { ?>
                                <input type="button" name="Preview_Direct_Cash" id="Preview_Direct_Cash" value="PREVIEW ADVANCE PAYMENTS" class="art-button-green" onclick="Preview_Advance_Payments(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                            <?php } else { ?>
                                <input type="button" name="Preview_Direct_Cash" id="Preview_Direct_Cash" value="PREVIEW ADVANCE PAYMENTS" class="art-button-green" onclick="Preview_Advance_Payments_Warning();">
<?php } ?>
                        </td>
                        <td>
                            <input type="button" name="Add_Item" id="Add_Item" value="ADD ITEMS" class="art-button-green" onclick="Add_More_Items(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>)">
                        </td>
                    </tr>
                </table>
                <?php
                //Check if patient has cash bill
                if (strtolower($Payment_Method) != 'cash' && strtolower($Cash_Bill_Status) == 'pending') {
                    $slct = mysqli_query($conn,"select Patient_Payment_ID from tbl_patient_payments pp where
        									Registration_ID = '$Registration_ID' and
        									Folio_Number = '$Folio_Number' and
        									Check_In_ID = '$Check_In_ID' and
        									Billing_Type = 'Inpatient Cash' and
        									pp.payment_type = 'post' and
        									Patient_Bill_ID = '$Patient_Bill_ID' limit 1") or die(mysqli_error($conn));
                    $n_slct = mysqli_num_rows($slct);
                    if ($n_slct > 0) {
                        $Cont_Val = strtolower($Cash_Bill_Status);
                        echo "<span id=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='color: red;'>ALERT!!.. Selected patient has pending cash bill</b></span>";
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php if (strtolower($Payment_Method) == 'cash') { ?>
                    <select name="Transaction_Type" id="Transaction_Type" onchange="Display_Transaction(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>); Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                        <option selected="selected" value="Cash_Bill_Details">Cash Bill Details</option>
                    </select>
<?php } else { ?>
                    <select name="Transaction_Type" id="Transaction_Type" onchange="Display_Transaction(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>); Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                        <option selected="selected" value="Credit_Bill_Details">Credit Bill Details</option>
                        <option value="Cash_Bill_Details">Cash Bill Details</option>
                    </select>
<?php } ?>
                <select name="Receipt_Mode" id="Receipt_Mode" onchange="Sort_Mode(<?php echo $Patient_Bill_ID; ?>,<?php echo $Folio_Number; ?>,<?php echo $Sponsor_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Registration_ID; ?>);">
                    <option selected="selected" value="Group_By_Category">Group by Category</option>
                    <option value="Group_By_Receipt" <?php
if (isset($_SESSION['Sort_Mode']) && strtolower($_SESSION['Sort_Mode']) == 'group_by_receipt') {
    echo 'selected="selected"';
}
?>>Group by Receipt</option>
                </select>
                <?php if ($canZero) { ?>
                    <input type='button'  onclick="confirmZering()" class="art-button-green" value="Zero Item(s) Prices"/>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i style="font-size:15px; color:red">~~ Use checkboxes to set the item price to zero ~~</i>
                    <!-- <span style="color: #0079AE;"><b><i>* * * CLICK RECEIPT NUMBER FOR CANCELLING OR EDITING OPTION * * *</i></b></span> -->
<?php } ?>

            </td>
        </tr>
    </table>
</fieldset>
	<?php } ?>
<div id="Get_Patient_Details" style="width:50%;">

</div>

<div id="Add_Items">
    <center id="Details_Area">

    </center>	
</div>

<div id="Preview_Transaction_Details" style="width:50%;">

</div>
<div id="MessageAlert">
    Advance Payments Not Applicable For Credit Bills
</div>

<div id="Preview_Advance">

</div>

<div id="Approval_Warning_Message">

</div>

<div id="Not_Ready_To_Bill">
    System inaonyesha mgonjwa bado anaendelea na matibabu. Si mda sahihi wa kumtoa
</div>
<div id="Body_Not_Ready_To_Bill">
    Mwili bado haujaruhusiwa kutoka!!
</div>

<div id="Patient_Already_Cleared">
    Selected Bill already cleared.
</div>

<div id="Zero_Price_Alert">
    <center>Process fail!. Selected item missing price</center>
</div>

<div id="No_Items_Found">
    <center>Process fail!. No items found</center>
</div>

<div id="Zero_Price_Or_Quantity_Alert">
    <center>Process fail!. Some items missing Price or Quantity.</center>
</div>

<div id="Something_Wrong">
    <center>Process fail!. Please try again</center>
</div>

<div id="Unsuccessful_Dialogy">
    <center>Process Fail! Please try again</center>
</div>

<div id="Successful_Dialogy">
    <center>Selected items added successfully</center>
</div>

<div id="Verify_Remove_Item" style="width:25%;">
    <span id="Remove_Selected_Area">

    </span>
</div>

<div id="Editing_Transaction" style="width:25%;">
    <span id="Edit_Area">

    </span>
</div>


<div id="List_OF_Doctors">
    <center>
        <table width="100%">
            <tr>
                <td>
                    <input type="text" name="Doc_Name" id="Doc_Name" placeholder="~~~ ~~~ Enter Doctor Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Doctors()" oninput="Search_Doctors()">
                </td>
            </tr>
            <tr>
                <td>
                    <fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Doctors_Area'>
                        <table width="100%">
                            <?php
                            $counter = 0;
                            $get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
                            $doctors_num = mysqli_num_rows($get_doctors);
                            if ($doctors_num > 0) {
                                while ($data = mysqli_fetch_array($get_doctors)) {
                                    ?>
                                    <tr>
                                        <td style='text-align: right;'>
                                            <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')"><?php echo ++$counter; ?></label>
                                        </td>
                                        <td>
                                            <label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                                        </td>
                                    </tr>
        <?php
    }
}
?>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </center>
</div>
<div id="Change_Item" style="width:50%;" >
    <center id='Edit_Items_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<div id="No_Enough_Payments">
    <center>
        Malipo zaidi yanahitajika
    </center>
</div>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script>
                                                $(document).ready(function () {
                                                    $("#Get_Patient_Details").dialog({autoOpen: false, width: "90%", height: 630, title: 'INPATIENT BILLING DETAILS', modal: true});
                                                });
</script>

<script>
    $(document).ready(function () {
        $("#Preview_Transaction_Details").dialog({autoOpen: false, width: "80%", height: 500, title: 'TRANSACTION DETAILS', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#MessageAlert").dialog({autoOpen: false, width: 600, height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Preview_Advance").dialog({autoOpen: false, width: "70%", height: 450, title: 'ADVANCE PAYMENTS', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Approval_Warning_Message").dialog({autoOpen: false, width: "70%", height: 450, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Not_Ready_To_Bill").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#Body_Not_Ready_To_Bill").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#Zero_Price_Alert").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#No_Items_Found").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#Something_Wrong").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>
<script>
    $(document).ready(function () {
        $("#Zero_Price_Or_Quantity_Alert").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Patient_Already_Cleared").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Refund_Required").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#No_Enough_Payments").dialog({autoOpen: false, width: "40%", height: 150, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Error_During_Process").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Credit_Bill").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Add_Items").dialog({autoOpen: false, width: "90%", height: 500, title: 'ADD MORE ITEMS', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Successful_Dialogy").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Unsuccessful_Dialogy").dialog({autoOpen: false, width: "40%", height: 110, title: 'eHMS 2.0 ~ Alert Message', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Verify_Remove_Item").dialog({autoOpen: false, width: '40%', height: 220, title: 'REMOVE ITEM', modal: true});
    });
</script>

<script>
    $(document).ready(function () {
        $("#Editing_Transaction").dialog({autoOpen: false, width: '80%', height: 200, title: 'EDIT ITEM', modal: true});
    });
</script>


<script>
    $(document).ready(function () {
        $("#List_OF_Doctors").dialog({autoOpen: false, width: '30%', height: 350, title: 'DOCTORS LIST', modal: true});
    });
</script>


<script>
    $(document).ready(function () {
        $("#Change_Item").dialog({autoOpen: false, width: '30%', height: 500, title: 'CHANGE ITEM', modal: true});
    });
</script>



<script type="text/javascript">
    function Get_Item_Name(Item_Name, Item_ID) {
        document.getElementById("Item_Name").value = Item_Name;
        document.getElementById("Item_ID").value = Item_ID;
        document.getElementById("Quantity").value = '';
        document.getElementById("Quantity").focus();
    }
</script>

<script type="text/javascript">
    function Save_Information_Verify() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectVerify = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }

        myObjectVerify.onreadystatechange = function () {
            dataVerify = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {
                var feedback = dataVerify;
                if (feedback == 'yes') {
                    Save_Information(Registration_ID);
                } else if (feedback == 'not') {
                    $("#Zero_Price_Or_Quantity_Alert").dialog("open");
                } else if (feedback == 'no') {
                    $("#No_Items_Found").dialog("open");
                } else {
                    $("#Something_Wrong").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........

        myObjectVerify.open('GET', 'Inpatient_Verify_Information.php?Registration_ID=' + Registration_ID, true);
        myObjectVerify.send();
    }
</script>

<script type="text/javascript">
    function Save_Information(Registration_ID) {
        Patient_Bill_ID = '<?php echo $Patient_Bill_ID; ?>';
        Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        Folio_Number = '<?php echo $Folio_Number; ?>';
        Check_In_ID = '<?php echo $Check_In_ID; ?>';
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var sms = confirm("Are you sure you want to add selected items?");
        if (sms == true) {
            if (window.XMLHttpRequest) {
                myObjectSave = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectSave = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSave.overrideMimeType('text/xml');
            }

            myObjectSave.onreadystatechange = function () {
                dataSave = myObjectSave.responseText;
                if (myObjectSave.readyState == 4) {
                    var feedbacks = dataSave;
                    if (feedbacks == 'yes') {
                        $("#Add_Items").dialog("close");
                        Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID)
                        $("#Successful_Dialogy").dialog("open");
                    } else {
                        $("#Unsuccessful_Dialogy").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........

            myObjectSave.open('GET', 'Save_Information_Inpatient.php?Registration_ID=' + Registration_ID + '&Transaction_Type=' + Transaction_Type + '&Check_In_ID=' + Check_In_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Patient_Bill_ID=' + Patient_Bill_ID, true);
            myObjectSave.send();
        }
    }
</script>

<script type="text/javascript">
    function Calculate_Grand_Total() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectGrand = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectGrand = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectGrand.overrideMimeType('text/xml');
        }

        myObjectGrand.onreadystatechange = function () {
            dataGrandTotal = myObjectGrand.responseText;
            if (myObjectGrand.readyState == 4) {
                document.getElementById('Grand_Total_Area').innerHTML = dataGrandTotal;
            }
        }; //specify name of function that will handle server response........

        myObjectGrand.open('GET', 'Inpatient_Calculate_Grand_Total.php?Registration_ID=' + Registration_ID, true);
        myObjectGrand.send();
    }
</script>
<script type="text/javascript">
    function Add_Selected_Item() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Item_ID = document.getElementById("Item_ID").value;
        var Quantity = document.getElementById("Quantity").value;
        var Discount = document.getElementById("Discount").value;
        var Check_In_Type = document.getElementById("Check_In_Type").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Price = document.getElementById("Price").value;

        if (Price != 0 && Item_ID != null && Item_ID != '' && Check_In_Type != null && Check_In_Type != '' && Registration_ID != '' && Registration_ID != null && Quantity != null && Quantity != '' && Quantity != 0) {
            if (window.XMLHttpRequest) {
                myObjectAddSelectedItem = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddSelectedItem = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddSelectedItem.overrideMimeType('text/xml');
            }

            myObjectAddSelectedItem.onreadystatechange = function () {
                data921 = myObjectAddSelectedItem.responseText;
                if (myObjectAddSelectedItem.readyState == 4) {
                    document.getElementById("Cached_Items").innerHTML = data921
                    Calculate_Grand_Total();
                    document.getElementById("Price").value = '';
                    document.getElementById("Item_Name").value = '';
                    document.getElementById("Discount").value = '';
                    document.getElementById("Quantity").value = '';
                }
            }; //specify name of function that will handle server response........

            myObjectAddSelectedItem.open('GET', 'Inpatient_Add_More_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Discount=' + Discount + '&Check_In_Type=' + Check_In_Type + '&Transaction_Type=' + Transaction_Type + '&Sponsor_ID=' + Sponsor_ID, true);
            myObjectAddSelectedItem.send();
        } else {
            if ((Price == null || Price == '' || Price == 0) && Item_ID != null && Item_ID != '') {
                $("#Zero_Price_Alert").dialog("open");
                return false
            }
            if (Item_ID == null || Item_ID == '') {
                document.getElementById("Item_Name").style = 'border: 3px solid red';
            } else {
                document.getElementById("Item_Name").style = 'border: 3px solid white';
            }

            if (Check_In_Type == null || Check_In_Type == '') {
                document.getElementById("Check_In_Type").style = 'border: 3px solid red';
            } else {
                document.getElementById("Check_In_Type").style = 'border: 3px solid white';
            }

            if (Quantity == null || Quantity == '' || Quantity == 0) {
                document.getElementById("Quantity").style = 'border: 3px solid red';
            } else {
                document.getElementById("Quantity").style = 'border: 3px solid white';
            }
        }
    }
</script>

<script type="text/javascript">
    function Remove_Item(Item_Cache_ID, Product_Name) {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var sms = confirm("Are you sure you want to remove " + Product_Name + "?");
        if (sms == true) {
            if (window.XMLHttpRequest) {
                myObjectRemove = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectRemove.overrideMimeType('text/xml');
            }

            myObjectRemove.onreadystatechange = function () {
                dataRemove = myObjectRemove.responseText;
                if (myObjectRemove.readyState == 4) {
                    document.getElementById('Cached_Items').innerHTML = dataRemove;
                    Calculate_Grand_Total();
                }
            }; //specify name of function that will handle server response........

            myObjectRemove.open('GET', 'Inpatient_Remove_Selected_Item.php?Item_Cache_ID=' + Item_Cache_ID + '&Registration_ID=' + Registration_ID, true);
            myObjectRemove.send();
        }
    }
</script>
<script type="text/javascript">
    function Get_Item_Price(Item_ID, Guarantor_Name) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;

        if (window.XMLHttpRequest) {
            myObjectPrice = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPrice = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPrice.overrideMimeType('text/xml');
        }

        myObjectPrice.onreadystatechange = function () {
            data = myObjectPrice.responseText;

            if (myObjectPrice.readyState == 4) {
                document.getElementById('Price').value = data;
                document.getElementById("Quantity").value = 1;
            }
        }; //specify name of function that will handle server response........

        myObjectPrice.open('GET', 'Get_Items_Price_Inpatient.php?Item_ID=' + Item_ID + '&Guarantor_Name=' + Guarantor_Name + '&Transaction_Type=' + Transaction_Type, true);
        myObjectPrice.send();
    }
</script>
<script type="text/javascript">
    function Preview_Patient_Details(Check_In_ID) {
        if (window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }

        myObjectPreview.onreadystatechange = function () {
            data2000 = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById("Get_Patient_Details").innerHTML = data2000;
                $("#Get_Patient_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectPreview.open('GET', 'Preview_Patient_Bill_Details.php?Check_In_ID=' + Check_In_ID, true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function View_Details(Patient_Payment_ID, Patient_Payment_Item_List_ID) {
        if (window.XMLHttpRequest) {
            myObjectViewDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectViewDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectViewDetails.overrideMimeType('text/xml');
        }

        myObjectViewDetails.onreadystatechange = function () {
            data = myObjectViewDetails.responseText;
            if (myObjectViewDetails.readyState == 4) {
                document.getElementById("Preview_Transaction_Details").innerHTML = data;
                $("#Preview_Transaction_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectViewDetails.open('GET', 'Preview_Transaction_Details.php?Patient_Payment_ID=' + Patient_Payment_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
        myObjectViewDetails.send();
    }
</script>
<script>
    function Patient_List_Search() {
        var Patient_Name = document.getElementById("Search_Patient").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        document.getElementById("Patient_Number").value = '';

        if (window.XMLHttpRequest) {
            myObjectSearchPatient = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function () {
            data28 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........

        myObjectSearchPatient.open('GET', 'Revenue_Center_Pharmacy_List_Iframe.php?Patient_Name=' + Patient_Name + '&date_From=' + date_From + '&date_To=' + date_To + '&Billing_Type=' + Billing_Type, true);
        myObjectSearchPatient.send();
    }
</script>

<script>
    function Patient_List_Search2() {
        var Patient_Number = document.getElementById("Patient_Number").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        document.getElementById("Search_Patient").value = '';

        if (window.XMLHttpRequest) {
            myObjectSearchPatient = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function () {
            data218 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data218;
            }
        }; //specify name of function that will handle server response........

        myObjectSearchPatient.open('GET', 'Revenue_Center_Pharmacy_List_Iframe.php?Patient_Number=' + Patient_Number + '&date_From=' + date_From + '&date_To=' + date_To + '&Billing_Type=' + Billing_Type, true);
        myObjectSearchPatient.send();
    }
</script>

<script type="text/javascript">
    function Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;

        if (window.XMLHttpRequest) {
            myObjectMode = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectMode = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMode.overrideMimeType('text/xml');
        }
        myObjectMode.onreadystatechange = function () {
            data288 = myObjectMode.responseText;
            if (myObjectMode.readyState == 4) {
                document.getElementById('Transaction_Items_Details').innerHTML = data288;
                Summary_Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID);
            }
        }; //specify name of function that will handle server response........

        myObjectMode.open('GET', 'Sort_Mode_Display.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID, true);
        myObjectMode.send();
    }
</script>



<script type="text/javascript">
    function Summary_Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Admision_ID = '<?php echo $Admision_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectSummary = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSummary = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSummary.overrideMimeType('text/xml');
        }
        myObjectSummary.onreadystatechange = function () {
            data9999 = myObjectSummary.responseText;
            if (myObjectSummary.readyState == 4) {
                document.getElementById('Transaction_Summary_Area').innerHTML = data9999;
            }
        }; //specify name of function that will handle server response........

        myObjectSummary.open('GET', 'Sort_Mode_Summary_Display.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID + '&Admision_ID=' + Admision_ID, true);
        myObjectSummary.send();
    }
</script>


<script type="text/javascript">
    function Preview_Advance_Payments(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Admision_ID = '<?php echo $Admision_ID; ?>';

        if (window.XMLHttpRequest) {
            myObjectPreviewAdvance = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreviewAdvance = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreviewAdvance.overrideMimeType('text/xml');
        }
        myObjectPreviewAdvance.onreadystatechange = function () {
            data987 = myObjectPreviewAdvance.responseText;
            if (myObjectPreviewAdvance.readyState == 4) {
                document.getElementById("Preview_Advance").innerHTML = data987;
                $("#Preview_Advance").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPreviewAdvance.open('GET', 'Preview_Advance_Payments.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID, true);
        myObjectPreviewAdvance.send();
    }
</script>

<script type="text/javascript">
    function Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectDisplay = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDisplay = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplay.overrideMimeType('text/xml');
        }
        myObjectDisplay.onreadystatechange = function () {
            data99929 = myObjectDisplay.responseText;
            if (myObjectDisplay.readyState == 4) {
                document.getElementById('Transaction_Summary_Area').innerHTML = data99929;
            }
        }; //specify name of function that will handle server response........

        myObjectDisplay.open('GET', 'Sort_Mode_Summary_Display.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID + '&Admision_ID=' + Admision_ID, true);
        myObjectDisplay.send();
    }
</script>

<script type="text/javascript">
    function Add_More_Items(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        if (window.XMLHttpRequest) {
            myObjectDisplay = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDisplay = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplay.overrideMimeType('text/xml');
        }
        myObjectDisplay.onreadystatechange = function () {
            mydata = myObjectDisplay.responseText;
            if (myObjectDisplay.readyState == 4) {
                document.getElementById('Details_Area').innerHTML = mydata;
                $("#Add_Items").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectDisplay.open('GET', 'Patient_Billing_Add_More_Items.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID, true);
        myObjectDisplay.send();
    }
</script>

<script type="text/javascript">
    function Preview_Patient_Bill(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;

        if (confirm("Click Ok for normal receipt and cancel for pdf")) {

        } else {

        }
        window.open('previewpatientbill.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Registration_ID=' + Registration_ID + '&Transaction_Type=' + Transaction_Type, '_blank');
    }
</script>

<script type="text/javascript">
    function Approve_Patient_Bill(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
		
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        var Confirm_Message = confirm("Are you sure you want to approve selected bill?");
        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObjectVerifyItems = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectVerifyItems = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectVerifyItems.overrideMimeType('text/xml');
            }
            myObjectVerifyItems.onreadystatechange = function () {
                data01 = myObjectVerifyItems.responseText;
                if (myObjectVerifyItems.readyState == 4) {
                    var feedback = data01;
                    if (feedback == 'yes') {
                        Approve_Bill_Process(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID);
                    } else if (feedback == 'not') {
                        $("#Not_Ready_To_Bill").dialog("open"); //not ready to bill
                    } else if (feedback == 'true') {
                        $("#Patient_Already_Cleared").dialog("open");
                    } else if (feedback == 'mortuary_not') {
						$("#Body_Not_Ready_To_Bill").dialog("open");
                        //alert("Mwili bado haujaruhusiwa kutoka!!");
                    } else {
                        $("#Approval_Warning_Message").dialog("open"); //something happened
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectVerifyItems.open('GET', 'Approve_Patient_Bill_Verify.php?Transaction_Type=' + Transaction_Type + '&Admision_ID=' + Admision_ID, true);
            myObjectVerifyItems.send();
        }
    }
</script>

<script type="text/javascript">
    function Approve_Bill_Process(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Admision_ID = '<?php echo $Admision_ID; ?>';
        // var totalmorgueprice = '<?php echo $totalmorgueprice; ?>';
        var Grand_Total = $("#Grand_Total").val();
        if (window.XMLHttpRequest) {
            myObjectApprove = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectApprove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectApprove.overrideMimeType('text/xml');
        }
        myObjectApprove.onreadystatechange = function () {
            data99991 = myObjectApprove.responseText;
            if (myObjectApprove.readyState == 4) {
                var feedback = data99991;
                if (feedback == '100') { //refund required
				alert("Bill Cleared Successfully");
                    Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                    $("#Refund_Required").dialog("open");
                } else if (feedback == '200') { //no enough paymments to clear bill
                    $("#No_Enough_Payments").dialog("open");
                } else if (feedback == '300') { //error occur during the process
                    $("$Error_During_Process").dialog("open");
                } else if (feedback == '400') { //credit bill ~ no complexity
                    Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                    $("#Credit_Bill").dialog("open");
                }else{
					// alert("Bill Cleared Successfully");
				}
            }
        }; //specify name of function that will handle server response........

        myObjectApprove.open('GET', 'Approve_Patient_Bill.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID + '&Admision_ID=' + Admision_ID + '&Grand_Total=' + Grand_Total, true);
        myObjectApprove.send();
    }
</script>

<script type="text/javascript" language="javascript">
    function getItemsList(Item_Category_ID) {
        document.getElementById("Search_Product").value = '';
        document.getElementById("Price").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Quantity").value = '';
        var Guarantor_Name = '<?php echo $Payment_Method; ?>';
        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }
        //alert(data);

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID + '&Guarantor_Name=' + Guarantor_Name, true);
        myObject.send();
    }
</script>

<script type="text/javascript">
    function getItemsListFiltered(Item_Name, Guarantor_Name) {
        document.getElementById("Price").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Quantity").value = '';
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if (Item_Category_ID == '' || Item_Category_ID == null) {
            Item_Category_ID = 'All';
        }

        if (window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function () {
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET', 'Get_List_Of_Items_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Guarantor_Name=' + Guarantor_Name, true);
        myObject.send();
    }
</script>

<script type="text/javascript">
    function Remove_Transaction(Patient_Payment_ID, Patient_Payment_Item_List_ID, Item_Name) {
        if (window.XMLHttpRequest) {
            myObjectContent = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectContent = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectContent.overrideMimeType('text/xml');
        }

        myObjectContent.onreadystatechange = function () {
            data9834 = myObjectContent.responseText;
            if (myObjectContent.readyState == 4) {
                document.getElementById("Remove_Selected_Area").innerHTML = data9834;
                $("#Verify_Remove_Item").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectContent.open('GET', 'Remove_Selected_Item_Contents.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID + '&Patient_Payment_ID=' + Patient_Payment_ID + '&Item_Name=' + Item_Name, true);
        myObjectContent.send();
    }
</script>

<script type="text/javascript">
    function Edit_Transaction(Patient_Payment_ID, Patient_Payment_Item_List_ID, Item_Name) {
        if (window.XMLHttpRequest) {
            myObjectRemoveItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRemoveItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveItem.overrideMimeType('text/xml');
        }

        myObjectRemoveItem.onreadystatechange = function () {
            dataEdit = myObjectRemoveItem.responseText;
            if (myObjectRemoveItem.readyState == 4) {
                document.getElementById("Edit_Area").innerHTML = dataEdit;
                $("#Editing_Transaction").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveItem.open('GET', 'Patient_Billing_Edit_Transaction.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID + '&Patient_Payment_ID=' + Patient_Payment_ID, true);
        myObjectRemoveItem.send();
    }
</script>

<script type="text/javascript">
    function Remove_Selected_Item(Patient_Payment_ID, Patient_Payment_Item_List_ID) {
        var Patient_Bill_ID = '<?php echo $Patient_Bill_ID; ?>';
        var Folio_Number = '<?php echo $Folio_Number; ?>';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        var Check_In_ID = '<?php echo $Check_In_ID; ?>';
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectRemoveItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRemoveItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemoveItem.overrideMimeType('text/xml');
        }

        myObjectRemoveItem.onreadystatechange = function () {
            data = myObjectRemoveItem.responseText;
            if (myObjectRemoveItem.readyState == 4) {
                $("#Verify_Remove_Item").dialog("close");
                Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID)
                View_Details(Patient_Payment_ID, 0);
            }
        }; //specify name of function that will handle server response........
        myObjectRemoveItem.open('GET', 'Patient_Billing_Remove_Selected_Item.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
        myObjectRemoveItem.send();
    }
</script>


<script type="text/javascript">
    function getDoctor() {
        /*var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
         if(window.XMLHttpRequest) {
         mm = new XMLHttpRequest();
         }
         else if(window.ActiveXObject){ 
         mm = new ActiveXObject('Micrsoft.XMLHTTP');
         mm.overrideMimeType('text/xml');
         }
         
         if (document.getElementById('Patient_Direction').value =='Direct To Doctor Via Nurse Station' || document.getElementById('Patient_Direction').value =='Direct To Doctor') {
         document.getElementById('Doctors_List').style.visibility = "";
         mm.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
         mm.open('GET','Patient_Billing_Select_Patient_Direction.php?Type_Of_Check_In='+Type_Of_Check_In+'&Patient_Direction=doctor',true);
         mm.send();
         }
         else{
         mm.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
         mm.open('GET','Patient_Billing_Select_Patient_Direction.php?Patient_Direction=clinic',true);
         mm.send();
         document.getElementById('Doctors_List').style.visibility = "hidden";
         }
         }
         function AJAXP3(){
         var data3 = mm.responseText;
         document.getElementById('Consultant').innerHTML = data3;
         }*/

        var Patient_Direction = document.getElementById('Patient_Direction').value;
        var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
        if (window.XMLHttpRequest) {
            myObjectFilter = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }

        myObjectFilter.onreadystatechange = function () {
            dataFilter = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById("Consultant_Area").innerHTML = dataFilter;
            }
        }; //specify name of function that will handle server response........

        if (Patient_Direction == 'Direct To Doctor Via Nurse Station' || Patient_Direction == 'Direct To Doctor') {
            document.getElementById('Doctors_List').style.visibility = "";
            myObjectFilter.open('GET', 'Patient_Billing_Select_Patient_Direction.php?Type_Of_Check_In=' + Type_Of_Check_In + '&Patient_Direction=doctor', true);
        } else {
            document.getElementById('Doctors_List').style.visibility = "hidden";
            myObjectFilter.open('GET', 'Patient_Billing_Select_Patient_Direction.php?Patient_Direction=clinic', true);
        }
        myObjectFilter.send();
    }
</script>

<script type="text/javascript">
    function Cancel_Edit_Process() {
        $("#Editing_Transaction").dialog("close");
    }
</script>


<script type="text/javascript">
    function Get_Doctor() {
        var Direction = document.getElementById("Patient_Direction").value;
        if (Direction != null && Direction != '' && (Direction == 'Direct To Doctor Via Nurse Station' || Direction == 'Direct To Doctor')) {
            $("#List_OF_Doctors").dialog("open");
        }
    }
</script>

<script type="text/javascript">
    function Get_Selected_Doctor(Doctror_Name) {
        document.getElementById("Consultant").value = Doctror_Name;
        // document.getElementById("Doc_Name").value = '';
        // Search_Doctors();
        $("#List_OF_Doctors").dialog("close");
    }
</script>


<script type="text/javascript">
    function Search_Doctors() {
        var Doctror_Name = document.getElementById("Doc_Name").value;
        if (window.XMLHttpRequest) {
            myObject_Search_Doctor = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObject_Search_Doctor = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Doctor.overrideMimeType('text/xml');
        }

        myObject_Search_Doctor.onreadystatechange = function () {
            data = myObject_Search_Doctor.responseText;
            if (myObject_Search_Doctor.readyState == 4) {
                document.getElementById('Doctors_Area').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Doctor.open('GET', 'Search_Doctors.php?Doctror_Name=' + Doctror_Name, true);
        myObject_Search_Doctor.send();
    }
</script>
<script type="text/javascript">
    function Close_Dialog() {
        $("#Verify_Remove_Item").dialog("close");
    }
</script>

<script type="text/javascript">
    function Preview_Advance_Payments_Warning() {
        $("#MessageAlert").dialog("open");
    }
</script>

<script type="text/javascript">
    function Open_Item_Dialogy(Sponsor_ID) {
        var Billing_Type = document.getElementById("Billing_Type").value;
        if (window.XMLHttpRequest) {
            myObjectChangeItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectChangeItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectChangeItem.overrideMimeType('text/xml');
        }

        myObjectChangeItem.onreadystatechange = function () {
            data2922 = myObjectChangeItem.responseText;
            if (myObjectChangeItem.readyState == 4) {
                document.getElementById('Edit_Items_Area').innerHTML = data2922;
                $("#Change_Item").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectChangeItem.open('GET', 'Patient_Billing_Change_Item.php?Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type, true);
        myObjectChangeItem.send();
    }
</script>

<script type="text/javascript">
    function Get_Selected_Item(Item_Name, Item_ID, Sponsor_ID) {
        document.getElementById("Pro_Name").value = Item_Name;
        document.getElementById("Pro_ID").value = Item_ID;
        var Billing_Type = document.getElementById("Billing_Type").value;
        Get_Price(Item_ID, Sponsor_ID);
    }
</script>

<script type="text/javascript">
    function Get_Price(Item_ID, Sponsor_ID) {
        var Billing_Type = document.getElementById("Billing_Type").value;
        if (window.XMLHttpRequest) {
            myObjectEditedPrice = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectEditedPrice = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectEditedPrice.overrideMimeType('text/xml');
        }
        myObjectEditedPrice.onreadystatechange = function () {
            data5050 = myObjectEditedPrice.responseText;
            if (myObjectEditedPrice.readyState == 4) {
                document.getElementById('Edited_Price').value = data5050;
                $("#Change_Item").dialog("close");
            }
        }; //specify name of function that will handle server response........

        myObjectEditedPrice.open('GET', 'Patient_Billing_Get_Price.php?Item_ID=' + Item_ID + '&Sponsor_ID=' + Sponsor_ID + '&Billing_Type=' + Billing_Type, true);
        myObjectEditedPrice.send();
    }
</script>


<script type="text/javascript">
    function Get_Items_List_Filtered() {
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        var Item_Name = document.getElementById("Search_Product_Name").value;
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';

        if (Item_Category_ID == '' || Item_Category_ID == null) {
            Item_Category_ID = 'All';
        }

        if (window.XMLHttpRequest) {
            myObjectSearchEdit = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchEdit = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchEdit.overrideMimeType('text/xml');
        }

        myObjectSearchEdit.onreadystatechange = function () {
            data97 = myObjectSearchEdit.responseText;
            if (myObjectSearchEdit.readyState == 4) {
                document.getElementById('Items_Area').innerHTML = data97;
            }
        }; //specify name of function that will handle server response........
        myObjectSearchEdit.open('GET', 'Get_Items_List_Filtered.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectSearchEdit.send();
    }
</script>

<script type="text/javascript">
    function Get_Items_List(Item_Category_ID) {
        document.getElementById("Search_Product_Name").value = '';
        var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
        if (window.XMLHttpRequest) {
            myObjectSearchEdit2 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearchEdit2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchEdit2.overrideMimeType('text/xml');
        }

        myObjectSearchEdit2.onreadystatechange = function () {
            data456 = myObjectSearchEdit2.responseText;
            if (myObjectSearchEdit2.readyState == 4) {
                document.getElementById('Items_Area').innerHTML = data456;
            }
        }; //specify name of function that will handle server response........
        myObjectSearchEdit2.open('GET', 'Get_Items_List.php?Item_Category_ID=' + Item_Category_ID + '&Sponsor_ID=' + Sponsor_ID, true);
        myObjectSearchEdit2.send();
    }
</script>
<script>
    function confirmZering() {
        var count = 0;
        var i = 1;
        $(".zero_items").each(function () {
            if ($(this).is(':checked')) {
                var id = $(this).attr('id');
                if (i == 1) {
                    dataInfo = id;
                } else {
                    dataInfo += '^$*^%$' + id;
                }

                i = i + 1;
                count = count + 1;
            }
            //this.checked=true;
        });
        if (count == 0) {
            alertMsg("Select Item(s) to zero price", "No Item Selected", 'error', 0, false, false, "", true, "Ok", true, .5, true);
            exit;
        }

        var msg = '';

        if (count == 1) {
            msg = 'the selected item';
        } else {
            msg = count + ' selected items';
        }

        confirmAction('<h3 style="text-align:center;font-weight:100">Are you sure you want to zero price for ' + msg + '?</h3>', 'Please make sure you know what you are doing', 'confirmation', 450, false, false, 'Yes', "No", .5, zeroItems);
    }
</script>
<script>
    function zeroItems() {
        var dataInfo = '';
        var count = 0;
        var i = 1;
        $(".zero_items").each(function () {
            if ($(this).is(':checked')) {
                var id = $(this).attr('id');
                if (i == 1) {
                    dataInfo = id;
                } else {
                    dataInfo += '^$*^%$' + id;
                }

                i = i + 1;
                count = count + 1;
            }
            //this.checked=true;
        });
        if (count == 0) {
            alertMsg("Select Item(s) to zero price", "No Item Selected", 'error', 0, false, false, "", true, "Ok", true, .5, true);
            exit;
        }

        $.ajax({
            type: 'POST',
            url: 'zero_item_price.php',
            data: 'action=zeroprice&dataInfos=' + dataInfo,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                if (html == '1') {
                    document.location.reload();
                } else {
                    alertMsg("An error has occured! Please Contact administrator for support", "Process Failed", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                }
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (html, jjj) {
                alert(html);
            }
        });

    }
</script>
<script>
    function addPrice(ppil, element) {
        var status = $(element).is(':checked');
        if (!status) {
            $.ajax({
                type: 'POST',
                url: 'zero_item_price.php',
                data: 'action=unzeroprice&ppil=' + ppil,
                beforeSend: function (xhr) {
                    $("#progressStatus").show();
                },
                success: function (html) {
                    if (html == '1') {
                        document.location.reload();
                    } else {
                        alertMsg("An error has occured! Please Contact administrator for support", "Process Failed", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                    }
                }, complete: function () {
                    $("#progressStatus").hide();
                }, error: function (html, jjj) {
                    alert(html);
                }
            });
        }
    }
</script>
<?php
include("./includes/footer.php");
?>