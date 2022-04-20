<?php
session_start();
include("./includes/connection.php");

if (isset($_GET['Hospital_Ward_ID'])) {
    $Hospital_Ward_ID = mysqli_real_escape_string($conn,$_GET['Hospital_Ward_ID']);
    if($Hospital_Ward_ID=="All"){
        $filter_ward="";
    }else{
        $filter_ward="and hw.Hospital_Ward_ID = '$Hospital_Ward_ID'";
    }
} else {
    $Hospital_Ward_ID = 0;
}
if (isset($_GET['Item_Category_ID'])) {
    $Item_Category_ID = mysqli_real_escape_string($conn,$_GET['Item_Category_ID']);
    if($Item_Category_ID!="All"){
       $category_fiter="and ic.Item_Category_ID='$Item_Category_ID'"; 
    }
} else {
    $category_fiter="";
}
if (isset($_GET['Item_Employee_ID'])) {
    $Item_Employee_ID = mysqli_real_escape_string($conn,$_GET['Item_Employee_ID']);
    if($Item_Employee_ID!="All"){
       $employee_fiter=" ad.Cash_Clearer_ID='$Item_Employee_ID' AND"; 
    }
} else {
    $employee_fiter="";
}
//get ward name
$slct = mysqli_query($conn,"select Hospital_Ward_Name from tbl_hospital_ward where Hospital_Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($slct);
if($num > 0){
    while ($rw = mysqli_fetch_array($slct)) {
        $Hospital_Ward_Name = $rw['Hospital_Ward_Name'];
    }
}else{
    $Hospital_Ward_Name = 'All Ward';
}


if(isset($_GET['Start_Date'])){
    $Start_Date = $_GET['Start_Date'];
}else{
    $Start_Date = '0000/00/00';
}

if(isset($_GET['End_Date'])){
    $End_Date = $_GET['End_Date'];
}else{
    $End_Date = '0000/00/00';
}
$filter = " (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') $filter_ward and ";
$filter .= " Clearance_Date_Time between '$Start_Date' and '$End_Date' and ";

if (isset($_GET['Patient_Name'])) {
    $Patient_Name = str_replace(" ", "%", mysqli_real_escape_string($conn,$_GET['Patient_Name']));
} else {
    $Patient_Name = '';
}

if($Patient_Name != null && $Patient_Name != ''){
    $filter .= " pr.Patient_Name like '%$Patient_Name%' and ";
}

if (isset($_GET['Patient_Number'])) {
    $Patient_Number = mysqli_real_escape_string($conn,$_GET['Patient_Number']);
} else {
    $Patient_Number = '';
}

if($Patient_Number != null && $Patient_Number != ''){
    $filter .= " pr.Registration_ID = '$Patient_Number' and ";
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
} else {
    $Sponsor_ID = 0;
}

if($Sponsor_ID != null && $Sponsor_ID != '' && $Sponsor_ID != 0){
    $filter .= " pr.Sponsor_ID = '$Sponsor_ID' and ";
}

if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = mysqli_real_escape_string($conn,$_GET['Transaction_Type']);
}else{
    $Transaction_Type = '';
}

/*if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
    $payments_filter = "payment_type = 'post' and ";
}else{
    $payments_filter = '';
}

if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
    $payments_filter2 = " pp.Billing_Type = 'Inpatient Cash' and payment_type = 'post' ";
}else{
    $payments_filter2 = " pp.Billing_Type = 'Inpatient Cash' ";
}*/

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}



$Title = '<tr><td colspan="16"><hr></tr>
            <tr>
                <td width="3%"><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width="6%"><b>PATIENT#</b></td>
                <td width="10%"><b>SPONSOR NAME</b></td>
                <td width="14%"><b>PATIENT AGE</b></td>
                <td width="7%"><b>GENDER</b></td>
                <td width="9%"><b>WARD</b></td>
                <td width="6%"><b>NO. DAYS</b></td>
                <td style="text-align: right;" width="7%"><b>CASH</b></td>
                <td style="text-align: right;" width="7%"><b>CREDIT</b></td>
                <td style="text-align: right;" width="7%"><b>EXEMPTION</b></td>
                <td style="text-align: right;" width="7%"><b>AMOUNT PAID</b></td>
                <td style="text-align: right;" width="7%"><b>DISCOUNT</b></td>
                <td style="text-align: right;" width="7%"><b>BILL EXCEMPTION</b></td>
                <td style="text-align: right;" width="7%"><b>DEBT</b></td>
                <td style="text-align: right;" width="7%"><b>REFUND</b></td>
                </tr>
            <tr><td colspan="16"><hr></tr>';
?>
<fieldset style='overflow-y: scroll; height: 400px; background-color:white'>
<legend style="background-color:#006400;color:white" align="right"><b>CLEARED BILLS REPORT (WARD NAME : <?php echo strtoupper($Hospital_Ward_Name); ?>)</b></legend>
<center><table width =100% border=0>
    <?php
        $temp = 0;
        $General_Total_Paid = 0;
        $General_Total_Cash_Needed = 0;
        $General_Total_Credit_Needed = 0;
        $General_Total_Exemption = 0;
        $General_Total_Discount = 0;
        $General_Total_Bill_Exemption = 0;
        $General_Total_Debt = 0;

        echo $Title;
        $select = mysqli_query($conn,"select ad.approve_bill_status,ad.Admision_ID, pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.Exemption, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time, Discharge_Date_Time) AS NoOfDay, pending_set_time, pending_setter
                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
                                cd.Admission_ID = ad.Admision_ID and
                                pr.Sponsor_ID = sp.Sponsor_ID and
                                pr.Registration_ID = ad.Registration_ID and
                                $filter $employee_fiter
                                hw.Hospital_Ward_ID = ad.Hospital_Ward_ID") or die(mysqli_error($conn));

        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($row = mysqli_fetch_array($select)) {
                $Total_Paid = 0;
                $Total_Cash_Needed = 0;
                $Total_Credit_Needed = 0;
                $Total_Exemption = 0;
                $Total_Discount = 0;
                $Total_Bill_Exemption = 0;
                $Total_Dept = 0;

                $Registration_ID = $row['Registration_ID'];
                $Check_In_ID = $row['Check_In_ID'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Admision_ID = $row['Admision_ID'];
                $Exemption = $row['Exemption'];

                //calculate age
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months, ";
                $age .= $diff->d . " Days";  //pending_set_time,NoOfDay
                $pending_set_time = $row['pending_set_time'];
                $NoOfDay = $row['NoOfDay'];

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
                            }else{
                                if(strtolower($Billing_Type) == 'inpatient cash'){
                                    $Total_Cash_Needed += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                                    $General_Total_Cash_Needed += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                                }else if((strtolower($Billing_Type) == 'inpatient credit' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Exemption) == 'yes'){
                                    $General_Total_Exemption += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                                    $Total_Exemption += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                                }else if((strtolower($Billing_Type) == 'inpatient credit' || strtolower($Billing_Type) == 'outpatient credit') && strtolower($Exemption) == 'no'){
                                    $General_Total_Credit_Needed += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                                    $Total_Credit_Needed += (($data['Price'] - $data['Discount']) * $data['Quantity']);
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
                    $cal = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
                                        tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
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
                        }
                    }
                }else{
                    if($Transaction_Type != 'Credit_Bill_Details'){
                        //calculate cash payments
                        $cal = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
                                            tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
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
                            }
                        }
                    }
                }
    ?>
            <?php 
                $balance_refund_total=$Total_Cash_Needed-$Total_Paid;
                if($balance_refund_total>=0){
                    $balance_total=$balance_refund_total;
                }else{
                   $balance_total=0; 
                }
                if($balance_refund_total<=0){
                    $refund_total=-$balance_refund_total;
                }else{
                   $refund_total=0; 
                }
                
                $approve_bill_status = $row['approve_bill_status'];
                if($approve_bill_status=="discount"){ 
                    $Total_Discount=$balance_total;
                }else if($approve_bill_status=="dept"){
                    $Total_Dept = $balance_total;
                }else if($approve_bill_status=="bill_excemption"){
                    $Total_Bill_Exemption = $balance_total;
                }else{
                    $Total_Dept = $balance_total;
                }
                    
               
                $General_Total_Discount += $Total_Discount;
                $General_Total_Bill_Exemption += $Total_Bill_Exemption;
                $General_Total_Debt += $Total_Dept;
                
                ?>
                <tr id="sss">
                    <td style="width:5%;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo ++$temp; ?><b>.</b></label></td>
                    <td><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo ucwords(strtolower($row['Patient_Name'])); ?></label></td>
                    <td><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo $row['Registration_ID']; ?></label></td>
                    <td><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo $row['Guarantor_Name']; ?></label></td>
                    <td><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo $age; ?></label></td>
                    <td><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo $row['Gender']; ?></label></td>
                    <td><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo $row['Hospital_Ward_Name']; ?></label></td>
                    <td><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo $NoOfDay; ?></label></td>
                    <td style="text-align: right;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo number_format($Total_Cash_Needed); ?></label></td>
                    <td style="text-align: right;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo number_format($Total_Credit_Needed); ?></label></td>
                    <td style="text-align: right;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo number_format($Total_Exemption); ?></label></td>
                    <td style="text-align: right;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo number_format($Total_Paid); ?></label></td>
                    <td style="text-align: right;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo number_format($Total_Discount); ?></label></td>
                    <td style="text-align: right;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo number_format($Total_Bill_Exemption); ?></label></td>
                    <td style="text-align: right;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo number_format($Total_Dept); ?></label></td>
                    <td style="text-align: right;"><label style="color: #037CB0;" onclick="Preview_Details(<?php echo $Registration_ID; ?>,<?php echo $Check_In_ID; ?>,<?php echo $Hospital_Ward_ID; ?>,<?php echo $Admision_ID; ?>,'<?php echo $Transaction_Type; ?>')"><?php echo number_format($refund_total); ?></label></td>
                </tr>
    <?php
                if (($temp % 31) == 0) {
                    echo $Title;
                }
            }
        }
?>
        </table>
    </fieldset>
    <fieldset>
        <table width="100%">
            <tr>
                <td style="text-align: right;" width="7%"><b>GRAND TOTAL</b></td>
                <td style="text-align: right;" width="15%"><b>CASH NEEDED : <?php echo number_format($General_Total_Cash_Needed); ?></b></td>
                <td style="text-align: right;" width="15%"><b>CREDIT NEEDED : <?php echo number_format($General_Total_Credit_Needed); ?></b></td>
                <td style="text-align: right;" width="15%"><b>EXCEMPTION : <?php echo number_format($General_Total_Exemption); ?></b></td>
                <td style="text-align: right;" width="20%"><b>AMOUNT PAID : <?php echo number_format($General_Total_Paid); ?></b></td>
                <?php 
                $balance_refund=$General_Total_Cash_Needed-$General_Total_Paid;
                if($balance_refund>=0){
                    $balance_general=$balance_refund;
                }else{
                   $balance_general=0; 
                }
                if($balance_refund<=0){
                    $refund_general=-$balance_refund;
                }else{
                   $refund_general=0; 
                }
                
                ?>
                <td style="text-align: right;" width="15%"><b>DEPT : <?php echo number_format($General_Total_Debt); ?></b></td>
                
            </tr>
            <tr>
                <td style="text-align: right;" width="15%" colspan="3"><b>DISCOUNT : <?php echo number_format($General_Total_Discount); ?></b></td>
                <td style="text-align: right;" width="15%" colspan="2"><b>BILL EXCEMPTION : <?php echo number_format($General_Total_Bill_Exemption); ?></b></td>
                <td style="text-align: right;" width="20%"><b>REFUND : <?php echo number_format($refund_general); ?></b></td>
            </tr>
        </table>
    </fieldset>
