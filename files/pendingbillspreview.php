<?php
session_start();
include("./includes/connection.php");
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $E_Name = '';
}
if (isset($_GET['Hospital_Ward_ID'])) {
    $Hospital_Ward_ID = mysqli_real_escape_string($conn,$_GET['Hospital_Ward_ID']);
} else {
    $Hospital_Ward_ID = 0;
}
if (isset($_GET['Item_Category_ID'])) {
    $Item_Category_ID = mysqli_real_escape_string($conn,$_GET['Item_Category_ID']);
    if($Item_Category_ID!="All"){
       $category_fiter="and ic.Item_Category_ID='$Item_Category_ID'"; 
       $sql_select_category_name_result=mysqli_query($conn,"SELECT Item_Category_Name FROM tbl_item_category WHERE Item_Category_ID='$Item_Category_ID'") or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_select_category_name_result)>0){
           $category_name_disp=mysqli_fetch_assoc($sql_select_category_name_result)['Item_Category_Name'];
       }
    }else{
           $category_name_disp="ALL CATEGORIES";
       }
} else {
     $category_name_disp="ALL CATEGORIES";
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

$filter = " (ad.Admission_Status = 'Pending' or ad.Admission_Status = 'Admitted') and hw.Hospital_Ward_ID = '$Hospital_Ward_ID' and ";

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

//Get sponsor name
if($Sponsor_ID == 0){
    $Sponsor_Name = "All";
}else{
    $slct_sponsor = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
    $n = mysqli_num_rows($slct_sponsor);
    if($n > 0){
        $result = mysqli_fetch_assoc($slct_sponsor);
        $Sponsor_Name = $result['Guarantor_Name'];
    }
}

if($Sponsor_ID != null && $Sponsor_ID != '' && $Sponsor_ID != 0){
    $filter .= " pr.Sponsor_ID = '$Sponsor_ID' and ";
}

if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = mysqli_real_escape_string($conn,$_GET['Transaction_Type']);
}else{
    $Transaction_Type = '';
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

$htm = "<table width ='100%' height = '30px'>
        <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
        <tr><td>&nbsp;</td></tr></table><br/>";
$htm .= "<span style='font-size: x-small;'>PENDING BILLS REPORT<br/>WARD NAME : ".strtoupper($Hospital_Ward_Name)."</span><br/>";
$htm .= "<span style='font-size: x-small;'>SPONSOR NAME : ".strtoupper($Sponsor_Name)."</span><br/>";
$htm .= "<span style='font-size: x-small;'>CATEGORY : ".strtoupper($category_name_disp)."</span><br/>";
$htm .= "<span style='font-size: x-small;'>PRINTED DATE : ".@date("d F Y H:i:s",strtotime($original_Date))."</span><br/><br/>";


     $htm .= '<table width="100%" border=1 style="border-collapse: collapse;">
                <thead><tr>
                <td width="3%"><b><span style="font-size: x-small;">SN</span></b></td>
                <td><b><span style="font-size: x-small;">PATIENT NAME</span></b></td>
                <td width="6%"><b><span style="font-size: x-small;">PATIENT#</span></b></td>
                <td width="9%"><b><span style="font-size: x-small;">SPONSOR</span></b></td>
                <td width="12%"><b><span style="font-size: x-small;">PATIENT AGE</span></b></td>
                <td width="6%"><b><span style="font-size: x-small;">GENDER</span></b></td>
                <td width="7%"><b><span style="font-size: x-small;">WARD</span></b></td>
                <td width="6%" style="text-align: right"><b><span style="font-size: x-small;">NO. DAYS</span></b></td>
                <td style="text-align: right;" width="7%"><b><span style="font-size: x-small;">CASH</span></b></td>
                <td style="text-align: right;" width="7%"><b><span style="font-size: x-small;">CREDIT</span></b></td>
                <td style="text-align: right;" width="7%"><b><span style="font-size: x-small;">EXEMPTION</span></b></td>
                <td style="text-align: right;" width="7%"><b><span style="font-size: x-small;">AMOUNT PAID</span></b></td>
                <td style="text-align: right;" width="7%"><b><span style="font-size: x-small;">BALANCE</span></b></td>
                <td style="text-align: right;" width="7%"><b><span style="font-size: x-small;">REFUND</span></b></td>
                </tr></thead>';
        $temp = 0;
        $General_Total_Paid = 0;
        $General_Total_Cash_Needed = 0;
        $General_Total_Credit_Needed = 0;
        $General_Total_Exemption = 0;


        $select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.Exemption, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay, pending_set_time, pending_setter
                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
                                cd.Admission_ID = ad.Admision_ID and
                                pr.Sponsor_ID = sp.Sponsor_ID and
                                pr.Registration_ID = ad.Registration_ID and
                                hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                $filter
                                ad.Discharge_Clearance_Status = 'not cleared'") or die(mysqli_error($conn));

        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($row = mysqli_fetch_array($select)) {
                $Total_Paid = 0;
                $Grand_Total_Direct_Cash = 0;
                $Total_Cash_Needed = 0;
                $Total_Credit_Needed = 0;
                $Total_Exemption = 0;

                $Registration_ID = $row['Registration_ID'];
                $Check_In_ID = $row['Check_In_ID'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Exemption = $row['Exemption'];

                //calculate age
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months";
                //$age .= $diff->d . " Days";  //pending_set_time,NoOfDay
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
                                        pp.Billing_Type<>'Outpatient Cash' and
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                                        pp.Folio_Number = '$Folio_Number' and
                                        pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                    $nms = mysqli_num_rows($cal);
                    if($nms > 0){
                        while ($cls = mysqli_fetch_array($cal)) {
                            $Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                            $General_Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
                        }
                    }
                }
//                else{
//                    if($Transaction_Type == 'Cash_Bill_Details'){
//                        //calculate cash payments
//                        $cal = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
//                                            tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
//                                            pp.Transaction_type = 'direct cash' and
//                                            pp.Transaction_status <> 'cancelled' and
//                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
//                                            pp.Patient_Bill_ID = '$Patient_Bill_ID' and
//                                            pp.Folio_Number = '$Folio_Number' and
//                                            pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
//                        $nms = mysqli_num_rows($cal);
//                        if($nms > 0){
//                            while ($cls = mysqli_fetch_array($cal)) {
//                                $Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
//                                $General_Total_Paid += (($data['Price'] - $data['Discount']) * $data['Quantity']);
//                            }
//                        }
//                    }
//                }
                else{
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
                $htm .= '<tr>
                            <td style="width:5%;"><span style="font-size: x-small;">'.++$temp.'</span></td>
                            <td><span style="font-size: x-small;">'.ucwords(strtolower($row['Patient_Name'])).'</span></td>
                            <td><span style="font-size: x-small;">'.$row['Registration_ID'].'</span></td>
                            <td><span style="font-size: x-small;">'.$row['Guarantor_Name'].'</span></td>
                            <td><span style="font-size: x-small;">'.$age.'</span></td>
                            <td><span style="font-size: x-small;">'.$row['Gender'].'</span></td>
                            <td><span style="font-size: x-small;">'.$row['Hospital_Ward_Name'].'</span></td>
                            <td style="text-align: right"><span style="font-size: x-small;">'.$NoOfDay.'</span></td>
                            <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Cash_Needed).'</span></td>
                            <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Credit_Needed).'</span></td>
                            <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Exemption).'</span></td>
                            <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Paid).'</span></td>
                            <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($balance_total).'</span></td>
                            <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($refund_total).'</span></td>
                        </tr>';
            }
        }
        
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
        $htm .= '
                    <tr><td style="text-align: left;" colspan="8"><b><span style="font-size: x-small;">GRAND TOTAL</span></b></td>
                    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($General_Total_Cash_Needed).'</span></b></td>
                    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($General_Total_Credit_Needed).'</span></b></td>
                    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($General_Total_Exemption).'</span></b></td>
                    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($General_Total_Paid).'</span></b></td>
                    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($balance_general).'</span></b></td>
                    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($refund_general).'</span></b></td>
                </table>';

    ini_set('memory_limit', '1256M');

    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>