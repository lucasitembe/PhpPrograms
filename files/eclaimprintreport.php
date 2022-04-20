<?php

@session_start();
require("nhif3/constants.php");
include("./includes/connection.php");
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

    $facility_code = mysqli_fetch_array(mysqli_query($conn,"SELECT facility_code FROM tbl_system_configuration"))['facility_code'];
    //nhif folio
    $Bill_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Bill_ID FROM tbl_patient_payments WHERE Check_In_ID = $Check_In_ID AND Bill_ID !='' LIMIT 1"))['Bill_ID'];
    $check_folio = mysqli_query($conn,"SELECT Folio_No , claim_month, claim_year, sent_date FROM tbl_claim_folio WHERE  Bill_ID = '$Bill_ID'");
    $display_folio = '';
    $claim_month = '';
    $claim_year = '';

    if(mysqli_num_rows($check_folio) > 0){
    $claim_info = mysqli_fetch_assoc($check_folio);
    $last_folio = $claim_info['Folio_No'];
    $claim_month = $claim_info['claim_month'];
    $claim_year = $claim_info['claim_year'];
    }else{
        // die('This Bill not approved yet, Has no folio');
    $last_folio = $Folio_Number;
    }
  //$last_folio = date("Y-m")."/".sprintf("%'.07d\n",$last_folio);
   $display_folio = $last_folio;
   $last_folio = sprintf("%'.07d\n",$last_folio);

   $serial_data = $facility_code.'/'.$claim_month.'/'.$claim_year.'/'.$display_folio;
// end of nhif folio



$htm = "<table width ='100%'>
            <tr>
            <td width='20%'><img src='images/stamp.png' width='105' height='105' style='float:left;'></td>
            <td>

            <center>

            <span style='font-size: small;'>
        <b>" . strtoupper($Guarantor_Name) . " CONFIDENTIAL<br>
            HEALTH PROVIDER IN / OUT PATIENT CLAIM FORM</b></span>
            </center>
            </td>
            <td width='20%' style='text-align: right'>
            <span style='font-size: x-small;'>Form 2A&B<br>
            Regulation 18(1)</span>
            </td></tr>
 <tr><td style='text-align: right;padding-top:-40px;' colspan='3'><span style='font-size: small;'>Folio No: ".$display_folio."&emsp;&emsp;&emsp;&emsp; Bill No: ".$Bill_ID."&emsp;&emsp;&emsp;&emsp; Serial no:" . $serial_data. "</span></td></tr>e
</table>";

    //get visit date
    $select_visit = mysqli_query($conn,"SELECT Visit_Date, signature from tbl_check_in where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_visit);
    if ($num > 0) {
        while ($row = mysqli_fetch_array($select_visit)) {
            $Visit_Date = $row['Visit_Date'];
            $signature = $row['signature'];
        }
    } else {
        $Visit_Date = '';
        $signature='';
    }
$select_Transaction_Items = mysqli_query($conn,"
            select * from
                tbl_patient_registration pr,tbl_check_in ch, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
                tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				ch.registration_id = pr.registration_id and
				(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
				pr.registration_id = pp.registration_id and
				pp.Patient_Bill_ID = '$Patient_Bill_ID' and
				e.employee_id = pp.employee_id and
				ic.item_category_id = ts.item_category_id and
				ts.item_subcategory_id = t.item_subcategory_id and
				t.item_id = ppl.item_id  and
				pr.registration_id = '$Registration_ID'  group by pp.Patient_Payment_ID, pp.Patient_Bill_ID order by pp.Patient_Payment_ID limit 1") or die(mysqli_error($conn));
                //and pp.folio_number = '$Folio_Number' and Billing_Process_Employee_ID is not null
$htm .= "<table width=100% style='padding-top:-25px;'>";
$htm .= "<tr><td colspan=12><hr style='color: #000000'></td></tr>";
while ($row = mysqli_fetch_array($select_Transaction_Items)) {
    $Patient_Name = $row['Patient_Name'];
    $Folio_Number = $row['Folio_Number'];
    $Sponsor_Name = $row['Sponsor_Name'];
    if (isset($signature)) {
        $p_signature = "<img width='120' height='60' style='padding: 0;' src='../esign/patients_signature/" . $signature . "' >";
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



//get authorisation number
        $AuthorizationNo = '';
        $select_visit = mysqli_query($conn,"SELECT AuthorizationNo FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));

   
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


        $select111 = mysqli_query($conn,"SELECT cd.Admission_ID, cd.consultation_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
        if(mysqli_num_rows($select111) > 0){
            $patient_type ='Inpatient';
            $patient_status = "IN";
            $consultation_ID = mysqli_fetch_assoc($select111)['consultation_ID'];
        }else{
            $patient_type ='Outpatient';
            $patient_status = "OUT";
        }

    $diagnosis_query = mysqli_query($conn,"SELECT efd.Disease_ID, efd.Disease_Code AS disease_code, efd.Consultant_ID as Employee_ID, efd.Consultation_Time AS Disease_Consultation_Date_And_Time, efd.Consultant_Name AS Consultant_Name, diagnosis_type FROM tbl_edited_folio_diseases efd WHERE Bill_ID = $Bill_ID AND efd.diagnosis_type IN('diagnosis','provisional_diagnosis') GROUP BY efd.disease_code , diagnosis_type ");
    if(mysqli_num_rows($diagnosis_query) > 0){
        $diagnosis_result = $diagnosis_query;
    }else{

        if($patient_type =='Inpatient'){
            $diagnosis_query = "SELECT wd.Disease_ID,   diagnosis_type,disease_name,Round_Disease_Date_And_Time,disease_code FROM tbl_ward_round_disease wd,tbl_ward_round wr, tbl_disease d    WHERE   wd.disease_ID = d.disease_ID AND    wr.Round_ID = wd.Round_ID AND    wr.consultation_ID ='$consultation_ID' GROUP BY wd.disease_code , diagnosis_type, wr.consultation_ID ";
            
        }else{
        
            $diagnosis_query = "SELECT d.nhif_code as disease_code,dc.diagnosis_type,c.Employee_ID, (SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = dc.Employee_ID) as Consultant_Name     FROM tbl_disease d,tbl_consultation c JOIN tbl_disease_consultation dc ON dc.Consultation_ID=c.consultation_ID WHERE d.Disease_ID = dc.Disease_ID     AND c.Registration_ID = '$Registration_ID'     AND dc.diagnosis_type IN ('provisional_diagnosis', 'diagnosis')    AND date(dc.Disease_Consultation_Date_And_Time) >= (SELECT Visit_Date FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID' ) GROUP BY dc.Disease_ID, diagnosis_type, dc.consultation_ID";
        }    
        $diagnosis_result = mysqli_query($conn,$diagnosis_query) or die(mysqli_error($conn));

    }
    $diagnosis = "";
    $provisional_diagnosis = "";
    $Consultant_Name = "";
    $consut_id = '';

    $consultant_list = [];
    $consultant_list_kada = [];

while ($diagnosis_row = mysqli_fetch_assoc($diagnosis_result)) {
    $diagnosis_type = $diagnosis_row['diagnosis_type'];

    //categorize diseases
    if($diagnosis_type == 'diagnosis'){
      $diagnosis .= $diagnosis_row['disease_code'] . "; ";
    }elseif ($diagnosis_type == 'provisional_diagnosis') {
      $provisional_diagnosis .= $diagnosis_row['disease_code'] . "; ";
    }
    $consut_id = $diagnosis_row['Employee_ID'];
	
	if(!in_array($consut_id, $consultant_list)){
      $kada =  mysqli_fetch_assoc(mysqli_query($conn,"SELECT kada FROM tbl_employee WHERE Employee_ID = '$consut_id'"))['kada'];
      array_push($consultant_list, $consut_id);
      array_push($consultant_list_kada, array('ConsultantID' => $consut_id,'ConsultantKada' => $kada));

    }

}

/* prioritize the consultants from high to bottom */
    
  $general_practitioner = [];
  $specialist_consultant = [];
  $super_specialist_consultant = [];

  foreach ($consultant_list_kada as $consultant_kada) {
    if($consultant_kada['ConsultantKada'] == 'super_specialist'){

      array_push($super_specialist_consultant, $consultant_kada['ConsultantID']);

    }else if($consultant_kada['ConsultantKada'] == 'specialist'){

      array_push($specialist_consultant, $consultant_kada['ConsultantID']);

    }else{

      array_push($general_practitioner, $consultant_kada['ConsultantID']);

    }
  }

  if(sizeof($super_specialist_consultant) > 0){

    $consut_id = $super_specialist_consultant[0];

  }else if(sizeof($specialist_consultant) > 0){

    $consut_id = $specialist_consultant[0];

  }else if(sizeof($general_practitioner) > 0){

    $consut_id = $general_practitioner[0];

  }else{

    die("Doctor Final Diagnosis is Missing");
    
  }


//get consaltant details
$select_consult_details = mysqli_query($conn,"SELECT emp.Employee_Name AS Consultant_Name,emp.employee_signature,emp.kada,emp.Phone_Number,emp.Employee_Number, doctor_license FROM tbl_employee emp WHERE emp.Employee_ID = '$consut_id'");
$consult_details = mysqli_fetch_assoc($select_consult_details);

$Consultant_Name = $consult_details['Consultant_Name'] . " ";
$qualification = $consult_details['kada'] . " ";
$doctor_license = $consult_details['doctor_license'] . " ";
$Phone_Number = $consult_details['Phone_Number'] . " ";
if (isset($consult_details['employee_signature'])) {
    $Consultant_signature = "<img width='120' height='25' style='padding: 0;' src='../esign/employee_signatures/" . $consult_details['employee_signature'] . "' >";
} else {
    $Consultant_signature = '________________';
}

$$diagnosis = '';
//$company_query = "SELECT Company_Name FROM tbl_company";
$hospital_details = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Name,Box_Address,Fax FROM tbl_system_configuration"));
//$company_result = mysqli_query($conn,$company_query);
//$company_row = mysqli_fetch_assoc($company_result);
//$company = $company_row['Company_Name'];
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
    <td colspan=4 valign='top' width='33%'><br><span style='font-size: small;'>11. Final Diagnosis(Code): $diagnosis<br>
    12. Patient Status: $patient_status<br>
    13. Patient's Vote No: $Employee_Vote_Number<br>
    14. Authorization No: $AuthorizationNo</span></td></tr>";

$htm .= "<tr><td colspan=4><span><b><span style='font-size: x-small;'>B. COST OF SERVICES :</span></b></span></td></tr></table>";

$select_category_details = mysqli_query($conn,"
            	select ic.Item_Category_ID, ic.Item_Category_Name from
		    	tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
				tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
				where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
				pr.registration_id = pp.registration_id and
				e.employee_id = pp.employee_id and
				ic.item_category_id = ts.item_category_id and
				ts.item_subcategory_id = t.item_subcategory_id  and
				t.item_id = ppl.item_id  and
				pp.registration_id = '$Registration_ID' and
                pp.Check_In_ID = '$Check_In_ID' and
				pp.Patient_Bill_ID = '$Patient_Bill_ID' group by ic.Item_category_ID  order by pp.Payment_Date_And_Time") or die(mysqli_error($conn));
                //and pp.folio_number = '$Folio_Number' and (pp. Billing_Process_Status = 'Approved'||  Billing_Process_Status = 'billed')
while ($cat = mysqli_fetch_array($select_category_details)) {
    $Item_Category_ID = $cat['Item_Category_ID'];
    $Item_Category_Name = $cat['Item_Category_Name'];
    $htm .= "<span style='font-size: x-small;'>" . $cat['Item_Category_Name'] . "</span><br/>";

    //display details
    $htm .= '<table width=100% border=1 style="border-collapse: collapse;">';
    $htm .= '<tr>
			    <td width=4%><span style="font-size: x-small;">SN</span></td>
			    <td width=4%><span style="font-size: x-small;">Codes</span></td>
			    <td width=52%><span style="font-size: x-small;">Item Description</span></td>
			    <td width=10% style="text-align: left;"><span style="font-size: x-small;">DEBT N<u>o</u></span></td>
			    
			    <td width=7% style="text-align: center;"><span style="font-size: x-small;">Quantity</span></td>
                
			</tr>';
    /***
     * 
     * <td width=7% style="text-align: right;"><span style="font-size: x-small;">Price</span></td>
			    <td width=7% style="text-align: right;"><span style="font-size: x-small;">Discount</span></td>
			    <td width=7% style="text-align: right;"><span style="font-size: x-small;">Amount</span></td>
                 */ 
    $select_Transaction_Items = mysqli_query($conn,"
			select ppl.Patient_Payment_Item_List_ID,t.Item_ID, t.item_kind, t.Generic_ID, t.Product_Code, t.Consultation_Type,ic.Item_Category_Name, pp.Patient_Payment_ID,pp.Patient_Signature,t.Product_Name,ppl.Billing_approval_status, pp.Claim_Form_Number, pp.Receipt_Date, ppl.Price, ppl.Quantity, ppl.Discount from
			tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
			tbl_employee e, tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
			where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
			(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit' ) and
			pr.registration_id = pp.registration_id and
			e.employee_id = pp.employee_id and
			ic.item_category_id = ts.item_category_id and
			ts.item_subcategory_id = t.item_subcategory_id and
			t.item_id = ppl.item_id  and
			pp.registration_id = '$Registration_ID' and
			pp.Patient_Bill_ID = '$Patient_Bill_ID'  and
            pp.Check_In_ID = '$Check_In_ID' and
			ic.Item_category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));

            // and pp.folio_number = '$Folio_Number' and  (pp. Billing_Process_Status = 'Approved'||  Billing_Process_Status = 'billed') 
    $num_items = mysqli_num_rows($select_Transaction_Items);
    if ($num_items > 0) {
        while ($dat = mysqli_fetch_array($select_Transaction_Items)) {
            $Consultation_Type = $dat['Consultation_Type'];
            $Billing_approval_status = $dat['Billing_approval_status'];
            $Patient_Signature = $dat['Patient_Signature'];
            $Patient_Payment_ID = $dat['Patient_Payment_ID'];
            $Patient_Payment_Item_List_ID = $dat['Patient_Payment_Item_List_ID'];
            $Item_ID = $dat['Item_ID'];
            $total = (($dat['Price'] - $dat['Discount']) * $dat['Quantity']);

            $Product_Code = $dat['Product_Code'];
            $Product_Name = $dat['Product_Name'];
            $item_kind = $dat['item_kind'];
            $Generic_ID = $dat['Generic_ID'];
            if($item_kind == 'brand'){
              $generic_result = mysqli_query($conn,"SELECT Product_Code, Product_Name FROM tbl_items WHERE Item_ID = '$Generic_ID'");
              $generic_data = mysqli_fetch_assoc($generic_result);
              $Product_Code = $generic_data['Product_Code'];
              $Product_Name = $generic_data['Product_Name'];
              //die(json_encode(array('code' => $Product_Code)));
            }
            $dosage = '';
            $select_dosage = mysqli_query($conn,"SELECT Doctor_Comment FROM tbl_item_list_cache WHERE Patient_Payment_ID = '$Patient_Payment_ID' AND Item_ID = '$Item_ID' AND Check_In_Type = 'Pharmacy' AND Status = 'dispensed'");
            if(mysqli_num_rows($select_dosage) > 0){
              $dosage = mysqli_fetch_assoc($select_dosage)['Doctor_Comment'];
              if(trim($dosage) != ''){
                $dosage = " :Dosage(".$dosage.")";
              }
            }

            $htm .= "<tr>";
            $htm .= "<td style='text-align: center;'><span style='font-size: x-small;'>" . $temp . "</span></td>";
            $htm .= "<td><span style='font-size: x-small;'>" . $Product_Code . "</span></td>";
            $htm .= "<td>";
            $initial_name="";
            if($Consultation_Type=="Surgery"){
                $initial_name="Surgery Name--";
            }
            if($Consultation_Type=="Procedure"){
               $initial_name="Procedure Name--";
            }
            if ($Billing_approval_status == 'yes') {
                $htm .= "<span style='font-size: x-small;'>$initial_name<b>" . $Product_Name. $dosage."</b></span>";
            } else {
                $htm .= "<span style='font-size: x-small;'>$initial_name<b>" . $Product_Name .$dosage."</b> </span>";
            }

            $get_details = mysqli_query($conn,"select Post_operative_ID,type_of_surgery,duration_of_surgery,Type_Of_Anesthetic   from tbl_post_operative_notes where     Payment_Item_Cache_List_ID IN (SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID') and     Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

            if(mysqli_num_rows($get_details)>0){
                $get_details_result_rows=mysqli_fetch_assoc($get_details);
                $type_of_surgery=$get_details_result_rows['type_of_surgery'];
                $duration_of_surgery=$get_details_result_rows['duration_of_surgery'];
                $Type_Of_Anesthetic=$get_details_result_rows['Type_Of_Anesthetic'];
                $Post_operative_ID=$get_details_result_rows['Post_operative_ID'];

                $selected_assistant_surgeons = mysqli_query($conn,"select kada,Employee_Name, pop.Employee_Type from tbl_employee emp, tbl_post_operative_participant pop where     emp.Employee_ID = pop.Employee_ID and     pop.Post_operative_ID = '$Post_operative_ID'") or die(mysqli_error($conn));
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
                        <br/><span style='font-size: x-small;'>Type Of Surgery--<b>$type_of_surgery</b></span>
                        <br/><span style='font-size: x-small;'>Duration Of Surgery--<b>$duration_of_surgery</b></span>
                        <br/><span style='font-size: x-small;'>Type of Anesthetic--<b>$Type_Of_Anesthetic</b></span>
                        <br/><br/><span style='font-size: x-small;'>Surgeon--<b>$Surgeons_selected</b></span>
                        <br/><br/><span style='font-size: x-small;'>Anaesthesiologist--<b>$Anaesthetics_selected</b></span>


                           ";
            }
            if($Consultation_Type=="Procedure"){
                $sql_select_procedure_detail_result=mysqli_query($conn,"SELECT type_of_procedure,duration_of_procedure,Type_Of_Anesthetic FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
                $proc_details_rows=mysqli_fetch_assoc($sql_select_procedure_detail_result);
                $type_of_procedure=$proc_details_rows['type_of_procedure'];
                $duration_of_procedure=$proc_details_rows['duration_of_procedure'];
                $Type_Of_Anesthetic=$proc_details_rows['Type_Of_Anesthetic'];
                $htm .= "
                        <br/><span style='font-size: x-small;'>Type Of Procedure--<b>$type_of_procedure</b></span>
                        <br/><span style='font-size: x-small;'>Duration Of Procedure--<b>$duration_of_procedure</b></span>
                        <br/><span style='font-size: x-small;'>Type of Anesthetic--<b>$Type_Of_Anesthetic</b></span>";
            }
            $htm .= "</td>";
            $htm .= "<td><span style='font-size: x-small;'>" . $dat['Patient_Payment_ID'] . "</span></td>";
            
            $htm .= "<td style='text-align: center;'><span style='font-size: x-small;'>" . $dat['Quantity'] . "</span></td>";
          /*  $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($dat['Price']) . "</span></td>";
            $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($dat['Discount']) . "</span></td>";
            $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>" . number_format($total) . "</span></td>*/
          $htm.="  </tr>";

            $Sub_Total += $total;
            $GrandTotal = $GrandTotal + $total;
            $temp++;
        }
    }
    /*$htm .= '<tr><td colspan=8 style="text-align: right;"><span style="font-size: x-small;"><b>Sub Total : ' . number_format($Sub_Total) . '</b></span></td></tr>'; */
    $Sub_Total = 0;
    $total = 0;
    $temp = 1;
    $htm .= '</table><br/>';
}
    /*
    $htm .= "<table width=100% border=1 style='border-collapse: collapse;'>
            <tr>
                <td colspan=7 style='text-align: right;'>
                <span style='font-size: x-small;'>
                    <b>Grand Total : " . number_format($GrandTotal) . "</b></span>
                </td>
            </tr>
            </table>";
    */

//Authenticating officer name
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $query = mysqli_query($conn,"SELECT Employee_Name, employee_signature FROM tbl_employee WHERE Employee_ID='$Employee_ID'");
    $result = mysqli_fetch_assoc($query);
    $Approved_Employee_Name = $result['Employee_Name'];
    if (isset($result['employee_signature'])) {
        $emp_signature = "<img width='120' height='60' style='padding: 0;' src='../esign/employee_signatures/" . $result['employee_signature'] . "' >";
    } else {
        $emp_signature = '________________';
    }

    // $query = mysqli_query($conn,"SELECT e.Employee_Name, e.employee_signature FROM tbl_employee e, tbl_bills b WHERE e.Employee_ID=b.Employee_ID AND b.Bill_ID = $Bill_ID");
    // $result = mysqli_fetch_assoc($query);
    // $Approved_Employee_Name = $result['Employee_Name'];
    // if (isset($result['employee_signature'])) {
    //     $emp_signature = "<img width='120' height='60' style='padding: 0;' src='../esign/employee_signatures/" . $result['employee_signature'] . "' >";
    // } else {
    //     $emp_signature = '________________';
    // }

$htm .= "<table width ='100%'>
		    <tr>
		       <td><b>C. Name of attending Clinician : </b>$Consultant_Name &emsp;<b><b>Qualifications: </b> ".str_replace('_',' ',$qualification)." &emsp;<b>Reg No: </b>$doctor_license &emsp;<br><br><b>Signature:</b>" . $Consultant_signature . " &emsp;<b>Mob No:</b> $Phone_Number</td>

		    </tr>
		    <tr>
			<td><br>
			<b>D: Patient Certification</b> <br/>
			I certify that I received the above named services. Name : <b>" . ucwords($Patient_Name) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature :<b>" . $p_signature . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tel $Phone_Number <br/><br/>
			<b>E: Description of Out/In-patient Management / any other additional information </b><br><br>
			----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
			<br>
			<b>F: Claimant Certification</b><br/>
			I certify that I provided the above services. Name  : <b>" . ucwords($Approved_Employee_Name) . "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature" . $emp_signature . "
            <br>
            Official Stamp <img src='images/stamp.png' width='100' height='100' style='float:right;'>
            <br>
			NB: Fill in the Triplicate and please submit the original form on monthly basis, and the claim should be attached with Monthly Report.<br>
			Any falsified information may subject you to prosecution in accordance.<br>

            </td>
            </tr>
    </table>
        <br>
        <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u> End Of Document <u>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</u><br>
        <span style='font-size:11px;'>
    ";

//echo $htm;
include("./MPDF/mpdf.php");

$mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 1, 12.7, 8, 8);
$mpdf->SetFooter('Printed By ' . strtoupper($Employee_Name) . '|{PAGENO}|{DATE d-m-Y} Powered By GPITG Ltd');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
?>