<?php
include("./includes/connection.php");
session_start();
if (isset($_GET['Start_Date'])) {
    $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
} else {
    $Start_Date = '0000/00/00 00:00:00';
}

if (isset($_GET['End_Date'])) {
    $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
} else {
    $End_Date = '0000/00/00 00:00:00';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
} else {
    $Sponsor_ID = '';
}

//get Sponsor
if ($Sponsor_ID == 0) {
    $Sponsor = 'All';
} else {
    $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Sponsor = $row['Guarantor_Name'];
        }
    } else {
        $Sponsor = '0';
    }
}

if (isset($_GET['Employee_ID'])) {
    $Employee_ID = mysqli_real_escape_string($conn,$_GET['Employee_ID']);
} else {
    $Employee_ID = '';
}

//get employee
if ($Employee_ID == '0') {
    $Emp_Name = 'All';
} else {
    $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Emp_Name = $row['Employee_Name'];
        }
    }
}

if (isset($_GET['Section'])) {
    $Section = mysqli_real_escape_string($conn,$_GET['Section']);
} else {
    $Section = '';
}
//create Report_Type
if (strtolower($Section) == 'cashcredit') {
    $Report_Type = 'Cash And Credit';
} else if (strtolower($Section) == 'cash') {
    $Report_Type = 'Cash';
} else if (strtolower($Section) == 'credit') {
    $Report_Type = 'Credit';
} else {
    $Report_Type = 'Cancelled';
}

if (isset($_GET['Patient_Type'])) {
    $Patient_Type = mysqli_real_escape_string($conn,$_GET['Patient_Type']);
} else {
    $Patient_Type = '';
}

if (isset($_GET['Hospital_Ward_ID'])) {
    $Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
} else {
    $Hospital_Ward_ID = 'none';
}

//Get Ward Title
if ($Hospital_Ward_ID != 'none' && $Hospital_Ward_ID != '0') {
    //SELECT WARD
    $slct_ward = mysqli_query($conn,"select Hospital_Ward_Name from tbl_hospital_ward where Hospital_Ward_ID = '$Hospital_Ward_ID'") or die(mysqli_error($conn));
    $n_ward = mysqli_num_rows($slct_ward);
    if ($n_ward > 0) {
        while ($dzt = mysqli_fetch_array($slct_ward)) {
            $Ward = $dzt['Hospital_Ward_Name'];
            $Ward_Title = $dzt['Hospital_Ward_Name'] . " ";
        }
    } else {
        $Ward = '';
        $Ward_Title = '';
    }
} else if ($Hospital_Ward_ID == '0') {
    $Ward = 'ALL WARDS';
    $Ward_Title = 'ALL WARDS ';
} else {
    $Ward = '';
    $Ward_Title = '';
}
//get employee
//if (isset($_GET['clinic']) && $_GET['clinic'] != "ALL") {
//    $clinic_id=$_GET['clinic'];
//    $clinics = "select * from tbl_clinic where Clinic_ID = '$clinic_id'";
//    $clinic = mysqli_query($conn,$clinics);
//    $num = mysqli_num_rows($clinic);
//    if ($num > 0) {
//        while ($row = mysqli_fetch_array($select)) {
//            $Clinic_Name = $row['Clinic_Name'];
//        }
//    }
//} else {
//    $Clinic_Name = "ALL";
//}
//generate filter
$filter = "pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and ";
if (isset($_GET['clinic']) && $_GET['clinic'] != "ALL") {
    $Clinic_ID = mysqli_real_escape_string($conn,$_GET['clinic']);
   // $filter .= "('$Clinic_ID' IN (SELECT Clinic_ID FROM tbl_clinic_employee WHERE Employee_ID=ppl.Consultant_ID) OR ppl.Clinic_ID='$Clinic_ID') and ";
    $filter .= "ppl.Clinic_ID='$Clinic_ID' and ";
    $clinics = "select * from tbl_clinic where Clinic_ID = '$Clinic_ID'";
    $clinic = mysqli_query($conn,$clinics);
    $num = mysqli_num_rows($clinic);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($clinic)) {
            $Clinic_Name = $row['Clinic_Name'];
        }
    }
}else {
   $Clinic_ID = mysqli_real_escape_string($conn,$_GET['clinic']);
    $Clinic_Name = "ALL";
}

//dont complicate, understand slowly prog
if ($Patient_Type == 'Inpatient') {
    if (strtolower($Section) == 'cash') {
        $filter .= "Billing_Type = 'Inpatient Cash'  and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "(Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and Transaction_status = 'cancelled' and ";
    } else {
        $filter .= "((Billing_Type = 'Inpatient Cash' and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    }

    //filter according to ward selected
    if ($Hospital_Ward_ID != 'none' && $Hospital_Ward_ID != '0') {
        $filter .= " pp.Hospital_Ward_ID = '$Hospital_Ward_ID' and ";
    }
} else if ($Patient_Type == 'Outpatient') {
    if (strtolower($Section) == 'cash') {
        $filter .= "Billing_Type = 'Outpatient Cash' and Pre_Paid = '0' and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Outpatient Credit' and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and Transaction_status = 'cancelled' and ";
    } else {
        $filter .= "(Billing_Type = 'Outpatient Cash'and Pre_Paid = '0') or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
    }
} else {
    if (strtolower($Section) == 'cash') {
        $filter .= "((Billing_Type = 'Outpatient Cash' and Pre_Paid = '0')  or (Billing_Type = 'Inpatient Cash' and pp.payment_type='pre')) and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'credit') {
        $filter .= "(Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') and Transaction_status <> 'cancelled' and ";
    } elseif (strtolower($Section) == 'cancelled') {
        $filter .= "Transaction_status = 'cancelled' and ";
    } else {
        //$filter .= "Transaction_status <> 'cancelled' and ";
        $filter .= "((Billing_Type = 'Inpatient Cash' and pp.payment_type='pre') or Billing_Type = 'Inpatient Credit' or (Billing_Type = 'Outpatient Cash'and Pre_Paid = '0') or Billing_Type = 'Outpatient Credit') and Transaction_status <> 'cancelled' and ";
    }
}

if ($Sponsor_ID != 0) {
    $filter .= "pp.Sponsor_ID = '$Sponsor_ID' and ";
}

if ($Employee_ID != 0) {
    $filter .= "pp.Employee_ID = '$Employee_ID' and ";
}

    $htm  = "<table width ='100%' height = '10px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td></tr>
             </table>";
    $htm .= "<table width='100%'>
                <tr><td style='text-align: center;'><b>REVENUE COLLECTION SUMMARY REPORT FROM </b>". $Start_Date."<b> TO </b>".$End_Date."</td></tr>
                <tr><td style='text-align: center;'><b>REPORT TYPE : </b>CASH AND CREDIT,&nbsp;&nbsp;&nbsp;<b>SPONSOR : </b>". strtoupper($Sponsor)."</td></tr>
                <tr><td style='text-align: center;'><b>CLINIC : </b>". strtoupper($Clinic_Name).".&nbsp;&nbsp;&nbsp;</td></tr>
            </table>
            <table width='100%'>
                <tr><td colspan='9'><hr></td></tr>
                <tr>
                    <td width='5%'><b>SN</b></td><td  width='50%'><b>FINANCE DEPARTMENT NAME</b></td>
                    <td style='text-align: center' width='10%'><b>NO OF SERVICES</b></td>
                    <td style='text-align: right' width='10%'><b>CASH</b></td>
                    <td style='text-align: right' width='10%'><b>CREDIT</b></td>
                    <td style='text-align: right' width='10%'><b>MSAMAHA</b></td>
                </tr>
                <tr><td colspan='9'><hr></td></tr>";
            $temper=0;
            $Currency_Code='';
            $sql_select_main_department_result=mysqli_query($conn,"SELECT * FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_main_department_result)>0){
                $all_Grand_Total_Cash = 0;
                $all_Grand_Total_Fast_Track = 0;
                $all_Grand_Total_Credit = 0;
                $all_Grand_Quantity = 0;
                $all_Grand_Total_Msamaha=0;
                $all_Patient_No=0;
                $grand_grand_total_all=0;
                while($idara_rows=mysqli_fetch_assoc($sql_select_main_department_result)){
                    $finance_department_id=$idara_rows['finance_department_id'];
                    $finance_department_name=$idara_rows['finance_department_name'];
                    $htm .="<tr><td><b> ".(++$temper)."</b></td>
                            <td> <b><label> ".strtoupper($finance_department_name)."</label></b></td>";

                        ///////////////////////////////////////////////////////////////////////////////////
                    $get_categories = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from tbl_item_category ic order by ic.Item_Category_Name") or die(mysqli_error($conn));
                    $num_cat = mysqli_num_rows($get_categories);
                    if($num_cat > 0){
                        $Grand_Total_Cash = 0;
                        $Grand_Total_Fast_Track = 0;
                        $Grand_Total_Credit = 0;
                        $Grand_Quantity = 0;
                        $Grand_Total_Msamaha=0;
                        $Patient_No=0;
                        while ($cat = mysqli_fetch_array($get_categories)) {
                        $Item_Category_ID = $cat['Item_Category_ID'];
                        $Quantity = 0;
                        $Total_Cash = 0;
                        $Total_Fast_Track = 0;
                        $Total_Credit = 0;
                        $Total_Msamaha=0;
                        //get quantity, cash & credit transactions
                        $get_Quantiry = mysqli_query($conn,"select ppl.Quantity, ppl.Price, ppl.Discount, pp.Billing_Type,ts.Exemption, pp.payment_type, pp.Fast_Track, pp.Pre_Paid  from tbl_patient_payment_item_list ppl,
                                    tbl_item_category ic, tbl_item_subcategory isu, tbl_items i,tbl_patient_payments pp,tbl_sponsor as ts  where
                                    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                    ic.Item_Category_ID = isu.Item_Category_ID and
                                    isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                    ts.Sponsor_ID=pp.Sponsor_ID and
                                    $filter
                                    i.Item_ID = ppl.Item_ID and
                                    ic.Item_Category_ID = '$Item_Category_ID' AND finance_department_id='$finance_department_id'") or die(mysqli_error($conn));

                        $num_quantity = mysqli_num_rows($get_Quantiry);
                        if($num_quantity > 0){
                        while($Det = mysqli_fetch_array($get_Quantiry)){

                        $Quantity = $Quantity + $Det['Quantity'];
                        $Grand_Quantity = $Grand_Quantity + $Det['Quantity'];
                        $Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                        if(($Det['Exemption']=='yes') && ((strtolower($Det['Billing_Type']) == 'outpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient credit'))){
                        $Total_Msamaha += $Total;
                        $Grand_Total_Msamaha += $Total;
                        }  else {
                        $Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                        if((strtolower($Det['Billing_Type']) == 'outpatient cash' && $Det['Pre_Paid'] == '0') or (strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'pre')){
                        if($Det['Fast_Track'] == '1'){
                            $Total_Fast_Track += $Total;
                            $Grand_Total_Fast_Track += $Total;
                        }else{
                            $Total_Cash += $Total;
                            $Grand_Total_Cash += $Total;
                        }
                        }elseif((strtolower($Det['Billing_Type']) == 'outpatient credit') or (strtolower($Det['Billing_Type']) == 'inpatient credit')){
                            $Total_Credit += $Total;
                            $Grand_Total_Credit += $Total;
                        }
                    }
                }
            }
            //get total patients
            $get_patients = mysqli_query($conn,"select pp.Registration_ID from
                                            tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ic.Item_Category_ID = isu.Item_Category_ID and
                                            isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                            $filter
                                            i.Item_ID = ppl.Item_ID and
                                            ic.Item_Category_ID = '$Item_Category_ID' AND finance_department_id='$finance_department_id' group by pp.Registration_ID") or die(mysqli_error($conn));
         $Patient_No += mysqli_num_rows($get_patients);
        }

        $all_Grand_Total_Cash += $Grand_Total_Cash;
        $all_Grand_Total_Credit += $Grand_Total_Credit;
        $all_Grand_Quantity += $Grand_Quantity;
        $all_Grand_Total_Msamaha +=$Grand_Total_Msamaha;
        $all_Patient_No +=$Patient_No;

        $htm .="<td style='text-align: center;'><b>";
            if($_SESSION['systeminfo']['price_precision']=='yes'){
                $htm .=$Grand_Quantity; }else{
                $htm .=  $Grand_Quantity; }
                $htm .=" </b></td><td style='text-align: right';><b>";
            if($_SESSION['systeminfo']['price_precision']=='yes'){
                $htm .= $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cash, 2); }else{
                $htm .= $Currency_Code.'&nbsp;'.number_format($Grand_Total_Cash);
            }
            $htm .=" </b></td><td style='text-align: right;'><b>";
            if($_SESSION['systeminfo']['price_precision']=='yes'){
                $htm .= $Currency_Code.'&nbsp;'.number_format($Grand_Total_Credit, 2); }else{
                $htm .= $Currency_Code.'&nbsp;'.number_format($Grand_Total_Credit); }
                $htm .=" </b></td><td style='text-align: right;'><b>";
                 if($_SESSION['systeminfo']['price_precision']=='yes'){
                    $htm .= $Currency_Code.'&nbsp;'.number_format($Grand_Total_Msamaha, 2); }else{
                    $htm .= $Currency_Code.'&nbsp;'.number_format($Grand_Total_Msamaha); } ?></b></td>

                <?php
                    }
                }
                $grand_grand_total_all =$all_Grand_Total_Cash+$all_Grand_Total_Msamaha+$all_Grand_Total_Credit;

 							 	$select_rev_from_other_sources=mysqli_query($conn,"SELECT Price FROM tbl_other_sources_payment_item_list pil, tbl_other_sources_payments osp WHERE pil.Payment_ID=osp.Payment_ID AND osp.Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date'");
 								$service_number=mysqli_num_rows($select_rev_from_other_sources);
 								$total_rev_amount=0;
 								while ($row=mysqli_fetch_assoc($select_rev_from_other_sources)) {
 									$total_rev_amount+=$row['Price'];
 								}

 							 $htm .="<tr>
 							 	<td><b>".(++$temper)."</b></td> <td><b><label style='color: #0079AE;'>REVENUE FROM OTHER SOURCES</label></b></td><td style='text-align:center;'><b>".$service_number."</b></td><td style='text-align:right;'><b>".number_format($total_rev_amount)."</b></td>
 								<td style='text-align:right;'><b>0</b></td>
 								<td style='text-align:right;'><b>0</b></td>
 							 </tr>";

                $htm .="</tr><tr><td colspan='9'><hr></td></tr>
                        <tr>
                        <td colspan='2'><b>GRAND TOTAL</b></td>
                        <td style='text-align: center;'><b>";
                if($_SESSION['systeminfo']['price_precision']=='yes'){
                    $htm .= $all_Grand_Quantity+$service_number;
                }else{
                    $htm .= $all_Grand_Quantity+$service_number; }
                    $htm .=" </b></td><td style='text-align: right;'><b>";
                if($_SESSION['systeminfo']['price_precision']=='yes'){
                    $htm .= $Currency_Code.'&nbsp;'.number_format(($all_Grand_Total_Cash+$total_rev_amount), 2); }else{
                    $htm .= $Currency_Code.'&nbsp;'.number_format($all_Grand_Total_Cash+$total_rev_amount); }
                    $htm .=" </b></td><td style='text-align: right;'><b>";
                if($_SESSION['systeminfo']['price_precision']=='yes'){
                    $htm .= $Currency_Code.'&nbsp;'.number_format($all_Grand_Total_Credit, 2); }else{
                    $htm .= $Currency_Code.'&nbsp;'.number_format($all_Grand_Total_Credit); }
                    $htm .=" </b></td><td style='text-align: right;'><b>";
                if($_SESSION['systeminfo']['price_precision']=='yes'){
                    $htm .= $Currency_Code.'&nbsp;'.number_format($all_Grand_Total_Msamaha, 2); }else{
                    $htm .= $Currency_Code.'&nbsp;'.number_format($all_Grand_Total_Msamaha); }
                    $htm .=" </b></td>";?>
              <?php
                    $htm .=" </tr>";
                }
                $htm .=" </table>";
//echo $htm;
include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('  |Page {PAGENO} of {nb}| Powered By GPITG LTD {DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>
