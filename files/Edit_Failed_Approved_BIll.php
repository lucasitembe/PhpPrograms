<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <!--a href='Eclaim_Billing_Session_Control.php?Previous_Bills=True&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            APPROVED BILLS
        </a-->
        <?php
    }
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='billslist.php?Status=PreviousBills&Requisition=RequisitionThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>
<style>
    button{
        color:#FFFFFF!important;
        height:27px!important;
    }
</style>
<br/><br/>





<?php

//@session_start();
//require("nhif3/constants.php");
//include("./includes/connection.php");
$temp = 1;
$GrandTotal = 0;
$Sub_Total = 0;

//get employee details
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//get folio number
if (isset($_GET['Folio_Number'])) {
    $Folio_Number = $_GET['Folio_Number'];
} else {
    $Folio_Number = 0;
}

//get Patient Bill ID
if (isset($_GET['Patient_Bill_ID'])) {
    $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
} else {
    $Patient_Bill_ID = 0;
}

//get insurance name
if (isset($_GET['Insurance'])) {
    $Insurance = $_GET['Insurance'];
} else {
    $Insurance = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}

if (isset($_GET['Check_In_ID'])) {
    $Check_In_ID = $_GET['Check_In_ID'];
} else {
    $Check_In_ID = '';
}
if (isset($_GET['Bill_ID'])) {
    $Bill_ID = $_GET['Bill_ID'];
} else {
    $Bill_ID = '';
}


//get registration ID
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

//get insurance name
$select = mysqli_query($conn,"select Guarantor_Name from tbl_patient_registration pr, tbl_sponsor sp where
                            sp.Sponsor_ID = pr.Sponsor_ID and pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Guarantor_Name = $data['Guarantor_Name'];
    }
} else {
    $Guarantor_Name = '';
}

//Update folio number 
$updatefolio = mysqli_query($conn, "UPDATE tbl_patient_payments SET folio_number='$folio_number' WHERE Check_In_ID='$Check_In_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

//select printing date and time
$select_Time_and_date = mysqli_query($conn,"select now() as datetime");
while ($row = mysqli_fetch_array($select_Time_and_date)) {
    $Date_Time = $row['datetime'];
}
//select folio date to be used in creating a serial no
$foliodate = mysqli_fetch_assoc(mysqli_query($conn,"SELECT DATE(Payment_Date_And_Time) AS foliodate FROM tbl_patient_payments WHERE Folio_Number = '$Folio_Number' AND Patient_Bill_ID='$Patient_Bill_ID' ORDER BY Payment_Date_And_Time ASC LIMIT 1"))['foliodate'];
$timestamp = strtotime($foliodate);
$month = date("m", $timestamp);
$year = date("Y", $timestamp);

//nhif folio
$check_folio = mysqli_query($conn,"SELECT MONTH(sent_date) AS sent_date, Folio_No FROM tbl_claim_folio WHERE  Bill_ID = $Bill_ID");

$Derivery_Status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT e_bill_delivery_status FROM tbl_bills WHERE Bill_ID='$Bill_ID'"))['e_bill_delivery_status'];
echo $Derivery_Status;
if(mysqli_num_rows($check_folio) > 0){
  $last_folio = mysqli_fetch_assoc($check_folio)['Folio_No'];
}else{
  $last_folio = $Folio_Number;
}
  //$last_folio = date("Y-m")."/".sprintf("%'.07d\n",$last_folio);
   $last_folio = sprintf("%'.07d\n",$last_folio);
// end of nhif folio



$htm = "<table width ='100%'>
            <tr>
            <td width='20%'><img src='images/stamp.png' width='120' height='120' style='float:left;'></td>
            <td>

            <center>

            <span style='font-size: small;'>
        <b>" . strtoupper($Guarantor_Name) . " CONFIDENTIAL<br>
            HEALTH PROVIDER IN / OUT PATIENT CLAIM FORM</b></span>
            </center>
            </td>
            <td width='20%' style='text-align: right'>
            <span style=' ;'>Form 2A&B<br>
            Regulation 18(1)</span>
            </td></tr>
 <tr><td style='text-align: right;padding-top:-40px;' colspan='3'><span style='font-size: small;'>Serial no:" . $last_folio. "</span></td></tr>e
</table>";
$select_Transaction_Items = mysqli_query($conn,"
            select * from
                tbl_patient_registration pr,tbl_check_in ch, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
                tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                ch.registration_id = pr.registration_id and
                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
                pr.registration_id = pp.registration_id and
                pp.Patient_Bill_ID = '$Patient_Bill_ID' and ppl.Status<>'removed' and
                e.employee_id = pp.employee_id  and 
                ic.item_category_id = ts.item_category_id and
                ts.item_subcategory_id = t.item_subcategory_id and 
                t.item_id = ppl.item_id and
                pp.Check_In_ID='$Check_In_ID' and
                pr.registration_id = '$Registration_ID' and Billing_Process_Employee_ID is not null group by pp.Patient_Payment_ID, pp.Patient_Bill_ID order by pp.Patient_Payment_ID limit 1") or die(mysqli_error($conn));
$htm .= "<table width=100% style='padding-top:-25px;'>";
$htm .= "<tr><td colspan=12><hr style='color: #000000'></td></tr>";
while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    $Patient_Name = $row['Patient_Name'];
    $Folio_Number = $row['Folio_Number'];
    $Sponsor_Name = $row['Sponsor_Name'];
    if (isset($row['patient_signature'])) {
        $p_signature = "<img width='120' height='60' style='padding: 0;' src='../esign/patients_signature/" . $row['patient_signature'] . "' >";
    } else {
        $p_signature = '________________';
    }

    $Gender = $row['Gender'];
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $Member_Number = $row['Member_Number'];
    $Billing_Type = $row['Billing_Type'];
    $Occupation = $row['Occupation'];
    $Visit_Date = $row['Visit_Date'];
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Employee_Vote_Number = $row['Employee_Vote_Number'];
    $Phone_Number = $row['Phone_Number'];
    $Billing_Process_Employee_ID = $row['Billing_Process_Employee_ID'];
}

$date1 = new DateTime(Date("Y-m-d"));
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days";

//get visit date
$select_visit = mysqli_query($conn,"select Visit_Date from tbl_check_in where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select_visit);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select_visit)) {
        $Visit_Date = $row['Visit_Date'];
    }
} else {
    $Visit_Date = '';
}


//get authorisation number
$AuthorizationNo = '';

$AuthorizationNo = mysqli_fetch_assoc(mysqli_query($conn,"SELECT AuthorizationNo FROM tbl_check_in WHERE Check_In_ID ='$Check_In_ID'"))['AuthorizationNo'];
/*
$select_visit = mysqli_query($conn,"select c.AuthorizationNo from tbl_patient_payments pp, tbl_check_in c where
                                    c.Check_In_ID = pp.Check_In_ID and
                                    pp.Folio_Number = '$Folio_Number' and
                                    pp.Registration_ID = '$Registration_ID' and
                                    pp.Patient_Bill_ID = '$Patient_Bill_ID' and
                                    pp.Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));*/
$num = mysqli_num_rows($select_visit);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select_visit)) {
        if ($row['AuthorizationNo'] != null && $row['AuthorizationNo'] != '') {
            $AuthorizationNo = $row['AuthorizationNo'];
        }
    }
} else {
    $AuthorizationNo = '';
}



$select111 = mysqli_query($conn,"SELECT cd.Admission_ID, ad.Discharge_Date_Time, ad.Admission_Date_Time FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($select111) > 0){
    $patient_type ='Inpatient';
    $patient_status = "IN";
    $patient_status .= "<input type='button' class='art-button-green' value='Change Discharege date' onclick='change_discharge_dialogue(event);'>";
	$admission_data = mysqli_fetch_assoc($select111);
    $Discharge_Date_Time = $admission_data['Discharge_Date_Time'];
    $Admission_Date_Time = $admission_data['Admission_Date_Time'];
    $Admission_ID = $admission_data['Admission_ID'];
}else{
    $patient_type ='Outpatient';
    $patient_status = "OUT";
}

$diagnosis_query = mysqli_query($conn,"SELECT efd.Disease_ID, efd.Disease_Code AS disease_code, efd.Consultant_ID as Employee_ID, efd.Consultation_Time AS Disease_Consultation_Date_And_Time, efd.Consultant_Name AS Consultant_Name, diagnosis_type FROM tbl_edited_folio_diseases efd WHERE Bill_ID = $Bill_ID AND efd.diagnosis_type IN('diagnosis','provisional_diagnosis') GROUP BY efd.disease_code , diagnosis_type");
if(mysqli_num_rows($diagnosis_query) > 0){
    $diagnosis_result = $diagnosis_query;
}else{

$diagnosis_query = "SELECT d.nhif_code as disease_code,dc.diagnosis_type,c.Employee_ID, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = dc.Employee_ID) as Consultant_Name
     FROM tbl_disease d,tbl_consultation c JOIN tbl_disease_consultation dc ON dc.Consultation_ID=c.consultation_ID WHERE d.Disease_ID = dc.Disease_ID
     AND c.Registration_ID = '$Registration_ID'
     AND dc.diagnosis_type IN('provisional_diagnosis', 'diagnosis')
     AND date(dc.Disease_Consultation_Date_And_Time) >= (SELECT Visit_Date FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID') UNION ALL 
	 SELECT d.nhif_code as disease_code,dc.diagnosis_type,c.Employee_ID, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name FROM tbl_disease d,tbl_ward_round c JOIN tbl_ward_round_disease dc ON dc.Round_ID=c.Round_ID WHERE d.Disease_ID = dc.Round_ID AND c.Registration_ID = '$Registration_ID' AND dc.diagnosis_type IN('provisional_diagnosis', 'diagnosis') AND date(dc.Round_Disease_Date_And_Time) >= (SELECT Visit_Date FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID') GROUP BY disease_code , diagnosis_type";

    $diagnosis_result = mysqli_query($conn,$diagnosis_query) or die(mysqli_error($conn));
}
$diagnosis = "";
$provisional_diagnosis = "";
$Consultant_Name = "";
$consut_id = '';
while ($diagnosis_row = mysqli_fetch_assoc($diagnosis_result)) {
    $diagnosis_type = $diagnosis_row['diagnosis_type'];

    //categorize diseases
    if($diagnosis_type == 'diagnosis'){
      $diagnosis .= $diagnosis_row['disease_code'] . "; ";
    }elseif ($diagnosis_type == 'provisional_diagnosis') {
      $provisional_diagnosis .= $diagnosis_row['disease_code'] . "; ";
    }
    $consut_id = $diagnosis_row['Employee_ID'];

}

//get consaltant details
$select_consult_details = mysqli_query($conn,"SELECT emp.Employee_Name AS Consultant_Name,emp.employee_signature,emp.kada,emp.Phone_Number,emp.doctor_reg_no FROM tbl_employee emp WHERE emp.Employee_ID = '$consut_id'");
$consult_details = mysqli_fetch_assoc($select_consult_details);

$Consultant_Name = $consult_details['Consultant_Name'] . " ";
$qualification = $consult_details['kada'] . " ";
$doctor_reg_no = $consult_details['doctor_reg_no'] . " ";
$Phone_Number = $consult_details['Phone_Number'] . " ";
if (isset($consult_details['employee_signature'])) {
    $Consultant_signature = "<img width='120' height='25' style='padding: 0;' src='../esign/employee_signatures/" . $consult_details['employee_signature'] . "' >";
} else {
    $Consultant_signature = '________________';
}

//mvungi anafanya marekebisho ya inpatient SO NINAIFUNGA HII PROCESS KWA MDA
//select diagnosis details inpatients
/* $select_con = mysqli_query($conn,"SELECT d.disease_code, (SELECT Employee_Name FROM tbl_Employee WHERE Employee_ID = c.Employee_ID) as Consultant_Name
  FROM tbl_ward_round c,tbl_ward_round_disease dc, tbl_disease d
  WHERE c.Round_ID =dc.Round_ID AND d.Disease_ID = dc.Disease_ID
  AND dc.diagnosis_type = 'diagnosis'
  AND c.Patient_Payment_Item_List_ID IN (
  SELECT ppl.Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE
  ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
  pp.Folio_Number = '$Folio_Number' and
  pp.Registration_ID = '$Registration_ID' and pp.Patient_Bill_ID = '$Patient_Bill_ID')") or die(mysqli_error($conn));
  $no_of_rows = mysqli_num_rows($select_con);
  if($no_of_rows > 0){
  while($diagnosis_row = mysqli_fetch_array($select_con)){
  $diagnosis.=$diagnosis_row['disease_code']."; ";
  //$Consultant_Name = $diagnosis_row['Consultant_Name'];
  }
  } */
$$diagnosis = '';
//$company_query = "SELECT Company_Name FROM tbl_company";
$hospital_details = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Name,Box_Address,Fax FROM tbl_system_configuration"));
//$company_result = mysqli_query($conn,$company_query);
//$company_row = mysqli_fetch_assoc($company_result);
//$company = $company_row['Company_Name'];
$style="";
$authorisation_button = "";
if(strlen($AuthorizationNo)<12){
    $style = "style='background-color:red;font-size:20px;width:50%;'";
	$Member_Number = str_replace(' ','',$Member_Number);
    $authorisation_button = " <input type='button' value='NHIF-Authorize' onclick='forceAuthorization(\"$Member_Number\");' class='art-button-green' /> &emsp; <input type='button' value='Save' onclick='save_forced_authorization({$Check_In_ID})' class='art-button-green'>";

}else{
    $style = "readonly";

}
$htm .= "<tr><td colspan=4 width=33%><span style='font-size: small;'><b>A. PARTICULAR</b></span><br><span style='font-size: small;'>
    1. Name : ".$hospital_details['Hospital_Name']."<br>
    2. Accreditation No : ".$hospital_details['Fax']."<br>
    3. Address : ".$hospital_details['Box_Address']."<br>
    4. Patient Name : " . ucwords($Patient_Name) . "<br>
    5. Age : $age &nbsp;&nbsp;&nbsp; <br></span>
    </td>
    <td colspan=4 valign='top' width='33%'><br><span style='font-size: small;'>6 .Sex : $Gender<br>
        7. Membership No : $Member_Number<br>
        8. Occupation : $Occupation<br>
        9. Date of attendance : $Visit_Date<br>
      10.Preliminary Diagnosis(Code): $provisional_diagnosis<br></span>
    </td>
    <td colspan=4 valign='top' width='33%'><br><span style='font-size: small;'><nobr><span style='display:inline-block;'>11. Final Diagnosis(Code): $diagnosis</span><span style='display:inline-block;'>&emsp;<input type='button' class='art-button-green' style='font-size:20px;' value='Edit Diagnosis' onclick='Edit_Diagnosis({$Bill_ID});'></span></nobr><br>
    12. Patient Status: $patient_status<br>
    13. Patient's Vote No: $Employee_Vote_Number<br>
    <span style='display:inline-block;'><nobr>14. Authorization No:&emsp;</span> <span style='display:inline-block;'><input type='text'  ".$style." id='authorisation_number' value='".$AuthorizationNo."' ></span>".$authorisation_button."</span></nobr></td></tr>";
$htm .="<tr><td colspan='9'><br>
<span id='nhif_results' style='color:green; font-size:18px;font-weight:bold;'></span><br>
</td></tr>";
$htm .= "<tr><td colspan=4><span><b><span style=' ;'>B. COST OF SERVICES :</span></b></span></td></tr></table>";

$select_category_details = mysqli_query($conn,"
                select ic.Item_Category_ID, ic.Item_Category_Name from
                tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
                tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
                pr.registration_id = pp.registration_id  and
                e.employee_id = pp.employee_id and ppl.Status<>'removed' and
                ic.item_category_id = ts.item_category_id  and
                ts.item_subcategory_id = t.item_subcategory_id and
                (pp. Billing_Process_Status = 'Approved'||  Billing_Process_Status = 'billed') and
                t.item_id = ppl.item_id  and 
                pp.registration_id = '$Registration_ID' and
                pp.Check_In_ID = '$Check_In_ID' and
                pp.Patient_Bill_ID = '$Patient_Bill_ID' group by ic.Item_category_ID  order by pp.Payment_Date_And_Time") or die(mysqli_error($conn));

while ($cat = mysqli_fetch_array($select_category_details)) {
    $Item_Category_ID = $cat['Item_Category_ID'];
    $Item_Category_Name = $cat['Item_Category_Name'];
    $htm .= "<span style=' ;'>" . $cat['Item_Category_Name'] . "</span><br/>";

    //display details
    $htm .= '<table width=100% border=1 style="border-collapse: collapse;font-size:18px;">';
    $htm .= '<tr>
                <td width=4%><span style=" ;">SN</span></td>
                <td width=4%><span style=" ;">Codes</span></td>
                <td width=52%><span style=" ;">Item Description</span></td>
                <td width=10% style="text-align: left;"><span style=" ;">Receipt N<u>o</u></span></td>
                <td width=7% style="text-align: right;"><span style=" ;">Price</span></td>
                <td width=7% style="text-align: center;"><span style=" ;">Quantity</span></td>
                <td width=7% style="text-align: right;"><span style=" ;">Discount</span></td>
                <td width=7% style="text-align: right;"><span style=" ;">Amount</span></td>
            </tr>';

    $select_Transaction_Items = mysqli_query($conn,"
            select ppl.Patient_Payment_Item_List_ID,t.Item_ID,ppl.Check_In_Type,revenue_report_category, t.item_kind, t.Generic_ID, t.Product_Code, t.Consultation_Type,ic.Item_Category_Name, pp.Patient_Payment_ID,pp.Patient_Signature,t.Product_Name,ppl.Billing_approval_status, pp.Claim_Form_Number, pp.Receipt_Date, ppl.Price, ppl.Quantity, ppl.Discount from
            tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
            tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
            pr.registration_id = pp.registration_id  and
            e.employee_id = pp.employee_id  and
            ic.item_category_id = ts.item_category_id and ppl.Status<>'removed' and 
            ts.item_subcategory_id = t.item_subcategory_id and
            t.item_id = ppl.item_id  and
            pp.registration_id = '$Registration_ID' and
            pp.Patient_Bill_ID = '$Patient_Bill_ID' and
            (pp. Billing_Process_Status = 'Approved'||  Billing_Process_Status = 'billed') and
            pp.Check_In_ID = '$Check_In_ID' and
            ic.Item_category_ID = '$Item_Category_ID' AND pp.Bill_ID = '$Bill_ID'") or die(mysqli_error($conn));
    $num_items = mysqli_num_rows($select_Transaction_Items);
    if ($num_items > 0) {
        while ($dat = mysqli_fetch_array($select_Transaction_Items)) {
            $Consultation_Type = $dat['Consultation_Type'];
            $Billing_approval_status = $dat['Billing_approval_status'];
            $Patient_Signature = $dat['Patient_Signature'];
            $Patient_Payment_ID = $dat['Patient_Payment_ID'];
            $Check_In_Type = $dat['Check_In_Type'];
            $Patient_Payment_Item_List_ID = $dat['Patient_Payment_Item_List_ID'];
            $Item_ID = $dat['Item_ID'];
            $total = (($dat['Price'] - $dat['Discount']) * $dat['Quantity']);
            $ppil=$dat['Patient_Payment_Item_List_ID'];
            $Product_Code = $dat['Product_Code'];
            $Product_Name = $dat['Product_Name'];
            $item_kind = $dat['item_kind'];
            $Generic_ID = $dat['Generic_ID'];
            $revenue_report_category = $dat['revenue_report_category'];

            //change brand item to generic
            if($item_kind == 'brand'){
              $generic_result = mysqli_query($conn,"SELECT Product_Code, Product_Name FROM tbl_items WHERE Item_ID = '$Generic_ID'");
              $generic_data = mysqli_fetch_assoc($generic_result);
              $Product_Code = $generic_data['Product_Code'];
              $Product_Name = $generic_data['Product_Name'];
              //die(json_encode(array('code' => $Product_Code)));
            }

            //fing the restricted items

            $find_restricted = mysqli_query($conn,"SELECT iut.IsRestricted, ppl.Treatment_Authorization_No, ppl.Treatment_Authorizer FROM tbl_item_update_tem iut, tbl_items i, tbl_patient_payment_item_list ppl WHERE i.Product_Code = iut.ItemCode AND ppl.Item_ID = i.Item_ID AND  i.Item_ID = '$Item_ID' AND ppl.Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID AND iut.IsRestricted = 1");

            $item_restricted = mysqli_fetch_assoc($find_restricted);
            $add_approval_ref_no="";

            if($item_restricted['IsRestricted'] == 1 && $item_restricted['Treatment_Authorization_No'] ==''){
              $add_approval_ref_no = "<span style='display:inline-block;'><nobr><input type='text' name='Treatment_Authorization_No' id='Treatment_Authorization_No' value='' placeholder='Enter Approval Number'> <input type='button' name='btn_approve_ref_no' value='Save' class='art-button-green' onclick='Save_Approval_Reference(".$Patient_Payment_Item_List_ID.")'></nobr></span>";
            }
            $htm .= "<tr>";
            $htm .= "<td style='text-align: center;'><span style=' ;'>" . $temp . "</span></td>";
            $htm .= "<td><span style=' ;'>" . $Product_Code . "</span></td>";
            $htm .= "<td>";
            $initial_name="";
            if(strtolower($Check_In_Type)=='doctor room'  || strtolower($Check_In_Type)=='others' || $Check_In_Type=='IPD Services' || $revenue_report_category=='Consumable' || strtolower($Consultation_Type)=='others'){
                $exclude_button = "<input type='button' value='EXCLUDE' class='art-button RemoveItem' id='$ppil'>";
            }else{
                $exclude_button='';
            }
            if($Consultation_Type=="Surgery"){
                $initial_name="Surgery Name--";
            }
            if($Consultation_Type=="Procedure"){
               $initial_name="Procedure Name--";
            }
            if ($Billing_approval_status == 'yes') {
                $htm .= "<span style=' ;'>$initial_name<b>" . $Product_Name. $add_approval_ref_no."</b>".$exclude_button."</span>";
            } else {
                $htm .= "<span style=' ;'>$initial_name<b>" . $Product_Name . $add_approval_ref_no."</b>  *** ".$exclude_button."</span>";
            }

            $get_details = mysqli_query($conn,"select Post_operative_ID,type_of_surgery,duration_of_surgery,Type_Of_Anesthetic   from tbl_post_operative_notes where
                                Payment_Item_Cache_List_ID IN (SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID') and
                                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

            if(mysqli_num_rows($get_details)>0){
                $get_details_result_rows=mysqli_fetch_assoc($get_details);
                $type_of_surgery=$get_details_result_rows['type_of_surgery'];
                $duration_of_surgery=$get_details_result_rows['duration_of_surgery'];
                $Type_Of_Anesthetic=$get_details_result_rows['Type_Of_Anesthetic'];
                $Post_operative_ID=$get_details_result_rows['Post_operative_ID'];

                $selected_assistant_surgeons = mysqli_query($conn,"select kada,Employee_Name, pop.Employee_Type from tbl_employee emp, tbl_post_operative_participant pop where
                                            emp.Employee_ID = pop.Employee_ID and
                                            pop.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
        $nmz = mysqli_num_rows($selected_assistant_surgeons);
        $Surgeons_selected="";
        if($nmz > 0){
            while ($row = mysqli_fetch_array($selected_assistant_surgeons)) {
                $Employee_Type = $row['Employee_Type'];

                if($Employee_Type == 'Nurse'){
                    $Nurses_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }else if($Employee_Type == 'Assistant Surgeon'){
                    $Assistant_surgeons_selected .= ucwords(strtolower($row['Employee_Name'])).';    ';
                }else if($Employee_Type == 'Surgeon'){
                     $kada = $row['kada'];
                    $Surgeons_selected .= ucwords(strtolower($row['Employee_Name'])).' <span style="font-weight:normal">Qualification--</span>'.$kada.' ;    ';

                }else if($Employee_Type == 'Anaesthetics'){
                    $Anaesthetics_selected .= ucwords(strtolower($row['Employee_Name'])).' <span style="font-weight:normal">Qualification--</span>'.$kada.' ;    ';
                }
            }
        }
            }
            if($Consultation_Type=="Surgery"){
                $htm .= "
                        <br/><span style=' ;'>Type Of Surgery--<b>$type_of_surgery</b></span>
                        <br/><span style=' ;'>Duration Of Surgery--<b>$duration_of_surgery</b></span>
                        <br/><span style=' ;'>Type of Anesthetic--<b>$Type_Of_Anesthetic</b></span>
                        <br/><br/><span style=' ;'>Surgeon--<b>$Surgeons_selected</b></span>
                        <br/><br/><span style=' ;'>Anaesthesiologist--<b>$Anaesthetics_selected</b></span>


                           ";
            }
            if($Consultation_Type=="Procedure"){
                $sql_select_procedure_detail_result=mysqli_query($conn,"SELECT type_of_procedure,duration_of_procedure,Type_Of_Anesthetic FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
                $proc_details_rows=mysqli_fetch_assoc($sql_select_procedure_detail_result);
                $type_of_procedure=$proc_details_rows['type_of_procedure'];
                $duration_of_procedure=$proc_details_rows['duration_of_procedure'];
                $Type_Of_Anesthetic=$proc_details_rows['Type_Of_Anesthetic'];
                $htm .= "
                        <br/><span style=' ;'>Type Of Procedure--<b>$type_of_procedure</b></span>
                        <br/><span style=' ;'>Duration Of Procedure--<b>$duration_of_procedure</b></span>
                        <br/><span style=' ;'>Type of Anesthetic--<b>$Type_Of_Anesthetic</b></span>";
            }
            $htm .= "</td>";
            $htm .= "<td><span style=' ;'><a href='patientbillingtransactionedit.php?Patient_Payment_ID=".$dat['Patient_Payment_ID']."&Insurance=NHIF&Registration_ID=".$Registration_ID."&Selected=Selected&EditPayment=EditPaymentThisForm&from=ebill' target='blank'>" . $dat['Patient_Payment_ID'] . "</a></span></td>";
            $htm .= "<td style='text-align: right;'><span style=''>" . number_format($dat['Price']) . "</span></td>";
            $htm .= "<td style='text-align: center;'><span style=''>" . $dat['Quantity'] . "</span></td>";
            $htm .= "<td style='text-align: right;'><span style=''>" . number_format($dat['Discount']) . "</span></td>";
            $htm .= "<td style='text-align: right;'><span style=''>" . number_format($total) . "</span></td></tr>";

            $Sub_Total += $total;
            $GrandTotal = $GrandTotal + $total;
            $temp++;
        }
    }
    $htm .= '<tr><td colspan=8 style="text-align: right;"><span style=" ;"><b>Sub Total : ' . number_format($Sub_Total) . '</b></span></td></tr>';
    $Sub_Total = 0;
    $total = 0;
    $temp = 1;
    $htm .= '</table><br/>';
}
$htm .= "<table width=100% border=1 style='border-collapse: collapse; font-size:18px;'>
        <tr>
            <td colspan=7 style='text-align: right;'>
            <span style=' ;'>
                <b>Grand Total : " . number_format($GrandTotal) . "</b></span>
            </td>
        </tr>
        </table>";

//Authenticating officer name
$query = mysqli_query($conn,"SELECT Employee_Name,employee_signature FROM tbl_employee WHERE Employee_ID=$Billing_Process_Employee_ID");
$result = mysqli_fetch_assoc($query);
$Approved_Employee_Name = $result['Employee_Name'];
if (isset($result['employee_signature'])) {
    $emp_signature = "<img width='120' height='60' style='padding: 0;' src='../esign/employee_signatures/" . $result['employee_signature'] . "' >";
} else {
    $emp_signature = '________________';
}

$htm .= "<!--table width ='100%'>
            <tr>
               <td><b>C. Name of attending Clinician : </b>$Consultant_Name &emsp;<b><b>Qualifications: </b> ".str_replace('_',' ',$qualification)." &emsp;<b>Reg No: </b>$doctor_reg_no &emsp;<br><br><b>Signature:</b>" . $Consultant_signature . " &emsp;<b>Mob No:</b> $Phone_Number</td>

            </tr>
            <tr>
            <td><br>
            <b>D: Patient Certification</b> <br/>
            I certify that I received the above named services. Name : <b>" . ucwords($Patient_Name) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature :<b>" . $p_signature . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tel $Phone_Number <br/><br/>
            <b>E: Description of Out/In-patient Management / any other additional information </b><br><br>
            ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
            <br>
            <b>F: Claimant Certification</b><br/>
            I certify that I provided the above services. Name  : <b>" . ucwords($Approved_Employee_Name) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature" . $emp_signature . "Official Stamp <br>
            NB: Fill in the Triplicate and please submit the original form on monthly basis, and the claim should be attached with Monthly Report.<br>
            Any falsified information may subject you to prosecution in accordance.<br>

            </td>
            </tr>
    </table-->
        <!--br>
        <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u> End Of Document <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u><br>
        <span style='font-size:11px;'>
        Printed By  <b>". strtoupper($Employee_Name)."</b> at ".date('m/d/Y h:i:s a', time())."&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</span>
        <span style='font-size:9px;'>Powered By GPITG Ltd</span-->
    ";


// include("./MPDF/mpdf.php");

// $mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 1, 12.7, 8, 8);
// //$mpdf->SetFooter('Printed By ' . strtoupper($Employee_Name) . '|{PAGENO}|{DATE d-m-Y}');
// $mpdf->WriteHTML($htm);
// $mpdf->Output();
// exit;
?>
<div id='discharge_dialogue'>
	Admission Date<input type='text' id='update_ad_time' value='<?=$Admission_Date_Time;?>' readonly='readonly'>
	Discharge Date<input type='text' id='update_dis_time' value='<?=$Discharge_Date_Time;?>'>
	<input type='hidden' id='Admission_ID' value='<?=$Admission_ID;?>'>
	<input type='button' class='art-button-green' value='UPDATE' onclick='update_discharge_date(event);'>
</div>
<fieldset>
    <legend><b>Edit Failed Bills</b></legend>
     <div style="background-color: #fff; font-size: 20px;">
    <?php
        echo $htm;
     ?>
     <div id="diseases_list" style="font-size: 18px;">
        <table width=100% style="background: #fff;">
            <tr>
                <td style="width: 40%;" valign="top">
                    <table  width=100% style="font-size: 18px;">
                        <tr><td colspan="2"><b>Add Disease</b></td></tr>
                        <tr>
                            <td width=40%>Diagnosis Type</td>
                            <td width=60%>
                                <select id="DiagnosisType">
                                    <option value="">Select Diagnosis Type</option>
                                    <option value="provisional_diagnosis">Provisinal Diagnosis</option>
                                    <option value="diagnosis">Final Diagnosis</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width=65%><input type="text" style="width:100%;"  oninput="Search_Diseases('Name',this)" name="" placeholder="Enter Disease Name"></td>
                            <td><input type="text" style="width:100%;" oninput="Search_Diseases('Code',this)"   name="" placeholder="Enter Disease Code"></td>
                        </tr>
                        <tr style="background: #ddd;">
                            <td colspan="2">
                                <table width=100% style="font-size: 18px; ">
                                    <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Disease Name</th>
                                        <th>Disease Code </th>
                                        <th>Add</th>
                                    </tr>
                                    </thead>
                                    <tbody id="Search_Diseases_List" style="background: #fff;">
                                        
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width=5%></td>
                <td style="width: 55%;"  valign="top">
                    <table  width=100% style="font-size: 18px;">
                        <tr><td colspan="4"><b>Remove Disease</b></td></tr>
                         
                        <tr style="background: #ddd;"><td>Disease Name</td><td>Code</td><td>Type</td><td>Remove</td></tr>
                    <?php 
                        $select_for_edit = mysqli_query($conn,"SELECT * FROM tbl_edited_folio_diseases efd, tbl_disease d WHERE efd.Disease_Code = d.Disease_Code AND Bill_ID = $Bill_ID GROUP BY efd.disease_code , efd.diagnosis_type");
                        // $select_for_edit  =mysqli_fetch_assoc($select_for_edit);
                        while ($row = mysqli_fetch_assoc($select_for_edit)) {
                            $Disease_ID = $row['Disease_ID'];
                            $diagnosis_type = $row['diagnosis_type'];
                            echo "<tr><td width=50%>".$row['disease_name']."</td><td width=15%>".$row['Disease_Code']."</td><td width=20%>".str_replace("_", " ", $diagnosis_type)."</td><td width=10% style='text-align:center;'><input type='button' onclick='Remove_Disease(\"$Disease_ID\",\"$diagnosis_type\")' value='X' style='color:red;'></td><tr>";
                        }
                     ?>
                    </table>
                </td>
            </tr>
     </table>
     </div>

     </div>
</fieldset>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/token.js"></script>
<script type="text/javascript">

     $('.RemoveItem').on('click',function(){
       var ID=$(this).attr('id');
       if(confirm("Are you sure you want to remove this item?")){
        $.ajax({
        type:'POST',
        url:"CheckUnProcessedItem.php",
        data:"action=DeleteItem&Patient_Payment_Item_List_ID="+ID,
         success:function(html){
             alert(html);
            var url=window.location.href;
            location.href=url;
        }
        });
       }
    });
    function save_forced_authorization(Check_In_ID){
        var authorisation_number = $("#authorisation_number").val();
       // alert(Check_In_ID + ' and '+authorisation_number);
        $.ajax({
            url:'force_update_authorization_number.php',
            type:'post',
            data:{authorisation_number:authorisation_number,Check_In_ID:Check_In_ID},
            dataType:'json',
            success:function(result){
                if(result.code == 200){
                    alert("AUTHORIZATION NUMBER UPDATED SUCCESSIFULLY !!");
                }else{
                    alert("FAILS TO UPDATE AUTHORIZATION NUMBER !!");
                }
            }
        });
    }
    function Save_Approval_Reference(Payment_ID){
        var Treatment_Authorization_No = $("#Treatment_Authorization_No").val();
        var Employee_ID = "<?=$_SESSION['userinfo']['Employee_ID'];?>";
        if(Treatment_Authorization_No.trim() == ''){
            alert('WRITE APPROVAL REFERENCE NUMBER');
            return false;

        }

        $.ajax({
            url:' save_insurance_authorization_number.php',
            type:'post',
            data:{from:"edit_claim",Payment_ID:Payment_ID, Employee_ID:Employee_ID, Treatment_Authorization_No:Treatment_Authorization_No},
            success:function(results){
                console.log(results);
                location.reload();
            }
        });
    }

    function Edit_Diagnosis(Bill_ID){
        // alert(Bill_ID);
        $("#diseases_list").dialog("open");
    }

    function Search_Diseases(InputFrom, e){
        var Value = '';
        if(InputFrom == 'Name'){
            Value = $(e).val();
        }else if(InputFrom == 'Code'){
            Value = $(e).val();
        }
        // alert(Value);
        $.ajax({
            url:'get_diseases_edit_approved_bills.php',
            type:'post',
            data:{InputFrom:InputFrom, Value:Value},
            success:function(result){
                $("#Search_Diseases_List").html(result);
            }
        });
    }

    function Add_Disease(Disease_ID){
        var Editor = "<?=$_SESSION['userinfo']['Employee_ID']?>"
        var diagnosis_type = $("#DiagnosisType").val();
        var Bill_ID = "<?=$Bill_ID;?>";

        if(diagnosis_type == ''){
            alert("Select Diagnosis Type");
            return false;
        }

        $.ajax({
            url:'add_diseases_edit_approved_bills.php',
            type:'post',
            data:{Disease_ID:Disease_ID, diagnosis_type:diagnosis_type,Editor:Editor,Bill_ID:Bill_ID},
            success:function(result){
                if(result ==1){

                     location.reload();
                }else{
                    alert("Could not add diagnosis");
                }
                
            }
        });
        
    }

    function Remove_Disease(Disease_ID,diagnosis_type){
        var Bill_ID = "<?=$Bill_ID;?>";
        
        if(confirm("DO YOU WANT TO ADD REMOVE THIS DISEASE? ")){
        $.ajax({
            url:'remove_diseases_edit_approved_bills.php',
            type:'post',
            data:{Bill_ID:Bill_ID,Disease_ID:Disease_ID,diagnosis_type:diagnosis_type},
            success:function(result){
               location.reload();
            }
        });
        }
    }


    function change_discharge_dialogue(event){
        $("#discharge_dialogue").dialog('open');
    }

    function update_discharge_date(event){
        var update_dis_time = $('#update_dis_time').val();
        var Admission_ID = $('#Admission_ID').val();
        var Registration_ID = "<?=$Registration_ID;?>";
        $.ajax({
            url:'update_discherge_date.php',
            type:'post',
            data:{update_dis_time:update_dis_time, Admission_ID:Admission_ID, Registration_ID:Registration_ID},
            success:function(result){
                alert(result);
            }
        });
    }

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#diseases_list").dialog({autoOpen: false, width: '90%',height:'600', title: 'EDIT DISEASES', modal: true, position: 'middle'});
        $("#discharge_dialogue").dialog({autoOpen: false, width: '50%',height:'300', title: 'EDIT DISCHARGE DATE', modal: true, position: 'middle'});
    });
</script>
<?php
include("./includes/footer.php");
?>