<style>
    p{
        text-align: justify;
    }
</style>
<?php
//session_start();
include("./includes/connection.php");
session_start();
$Overstay_Form_ID = $_GET['Overstay_Form_ID'];
$Registration_ID = $_GET['Registration_ID'];
$consultation_ID = $_GET['consultation_ID'];
$Check_In_ID = $_GET['Check_In_ID'];
$Admision_ID = $_GET['Admision_ID'];

$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
}

if (!empty($Registration_ID)) {
    $sql_select_patient_information_result = mysqli_query($conn,"SELECT Patient_Name, Sponsor_ID, Date_Of_Birth,Region,District,Ward,village,Gender,Member_Number,Phone_Number FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_patient_information_result)>0){
        while($pat_details_rows=mysqli_fetch_assoc($sql_select_patient_information_result)){
            $Patient_Name =$pat_details_rows['Patient_Name'];
            $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
            $Region =$pat_details_rows['Region'];
            $District =$pat_details_rows['District'];
            $Ward =$pat_details_rows['Ward'];
            $village =$pat_details_rows['village'];
            $Gender =$pat_details_rows['Gender'];
            $Phone_Number = $pat_details_rows['Phone_Number'];
            $Member_Number = $pat_details_rows['Member_Number'];
            $Sponsor_ID = $pat_details_rows['Sponsor_ID'];

            $date1 = new DateTime($Today);
            $date2 = new DateTime($pat_details_rows['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
        }
    }

    $sql_select_admission_ward_result=mysqli_query($conn,"SELECT hw.Hospital_Ward_Name, ci.Check_In_ID, ci.AuthorizationNo FROM tbl_hospital_ward hw, tbl_admission ad, tbl_check_in ci, tbl_check_in_details cid WHERE hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND ad.Registration_ID='$Registration_ID' AND ad.Admision_ID = '$Admision_ID' AND ad.Admission_Status<>'Discharged' AND cid.consultation_ID = '$consultation_ID' AND ad.Admision_ID = cid.Admission_ID AND ci.Check_In_ID = cid.Check_In_ID") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_admission_ward_result)>0){
        while($wodini = mysqli_fetch_assoc($sql_select_admission_ward_result)){
            $Hospital_Ward_Name = $wodini['Hospital_Ward_Name'];
            $AuthorizationNo = $wodini['AuthorizationNo'];
            $Check_In_ID = $wodini['Check_In_ID'];
        }
    }else{
        $Hospital_Ward_Name = '<b>NOT ADMITTED</b>';
    }


    

  $date2= date('d, D, M, Y');
  $time= date('h:m:s');

                $select_conset_detail = mysqli_query($conn,"SELECT cd.Overstay_Form_ID, cd.consent_by, cd.Signed_at, em.Employee_Name, cd.consent_amputation, cd.behalf FROM tbl_consert_blood_forms_details cd, tbl_employee em WHERE Registration_ID = '$Registration_ID' AND consultation_id = '$consultation_id' AND em.Employee_ID = cd.Employee_ID");
                $datazangu = ($select_conset_detail > 0);
                if($datazangu){
                    while ($row = mysqli_fetch_array($select_conset_detail)) {
                        // `PROCEDURES`, `REPRESENTATIVE`, `WITNESS_NAME`, `DOCTOR`, `DATE_SIGNED`,
                        $consent_by=$row['consent_by'];
                        $Signed_at=$row['Signed_at'];
                        $Employee_Name=$row['Employee_Name'];
                        $Overstay_Form_ID=$row['Overstay_Form_ID'];
                        $consent_amputation = $row['consent_amputation'];
                        $behalf = $row['behalf'];

                    }
                }    
     }

$Current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Current_Employee_Name= $_SESSION['userinfo']['Employee_Name'];

           $select_Filtered_Doctors = mysqli_query($conn,
              "SELECT * from tbl_employee where
                   Employee_Type = 'doctor' order by employee_name") or die(mysqli_error($conn)); 
                   while($row = mysqli_fetch_array($select_Filtered_Doctors)){
           $select.="
           <option value=".$row['Employee_ID']."> Dr. ".$row['Employee_Name']." </option>
           ";
         }

         $select_magonjwa = mysqli_query($conn, "SELECT disease_code FROM tbl_disease_consultation dc, tbl_disease d WHERE d.disease_ID = dc.disease_ID AND consultation_ID = '$consultation_ID'");
         $idadi = mysqli_num_rows($select_magonjwa);
           while($disease = mysqli_fetch_assoc($select_magonjwa)){
               $disease_code = $disease['disease_code'];
               // $magonjwa = $disease_code;
               $magonjwa = $disease_code.", ".$magonjwa;
           }
    

           $hospital_info = mysqli_query($conn, "SELECT Hospital_Name, Box_Address, facility_code FROM tbl_system_configuration");
           while($data = mysqli_fetch_assoc($hospital_info)){
               $Hospital_Name = $data['Hospital_Name'];
               $Box_Address = $data['Box_Address'];
               $facility_code = $data['facility_code'];
           }

           $select_Filtered_Donors = mysqli_query($conn, "SELECT inp.Overstay_Form_ID, ad.Discharge_Date_Time, inp.consultation_ID, inp.Reason_For_Overstaying, inp.Employee_ID, inp.Check_In_ID, em.Employee_Name, hw.Hospital_Ward_Name, inp.Signed_Date_Time, ad.Admission_Date_Time FROM tbl_inpatient_overstaying inp, tbl_hospital_ward hw, tbl_ward_rooms wr, tbl_employee em, tbl_admission ad WHERE em.Employee_ID = inp.Employee_ID AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND ad.Admision_ID = inp.Admision_ID AND wr.ward_room_id AND inp.ward_room_id AND em.Employee_ID = inp.Employee_ID AND inp.Registration_ID = '$Registration_ID' AND inp.consultation_ID = '$consultation_ID' AND inp.Check_In_ID = '$Check_In_ID' GROUP BY inp.Overstay_Form_ID ORDER BY inp.Overstay_Form_ID DESC LIMIT 1") or die(mysqli_error($conn));


            while ($row = mysqli_fetch_array($select_Filtered_Donors)) {

                $Room_Type = $row['Room_Type'];
                $Employee_Name_signed = $row['Employee_Name'];
                $Hospital_Ward_Name = $row['Hospital_Ward_Name'];
                $Signed_Date_Time = $row['Signed_Date_Time'];
                $Admission_Date_Time = $row['Admission_Date_Time'];
                $Reason_For_Overstaying = $row['Reason_For_Overstaying'];
                $Overstay_Form_ID = $row['Overstay_Form_ID'];
                $Employee_ID_signed = $row['Employee_ID'];
                $Discharge_Date_Time = $row['Discharge_Date_Time'];
                if(empty($Discharge_Date_Time)){
                    $Discharge_Date_Time = $Today;
                }
            }

            $signature_check = mysqli_query($conn, "SELECT `signature` FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'");
            $Patientsignature = mysqli_fetch_assoc($signature_check)['signature'];

    $Clinic_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Clinic_Name FROM tbl_clinic c, tbl_consultation cn WHERE c.Clinic_ID = cn.Clinic_ID AND cn.consultation_ID = '$consultation_ID'"))['Clinic_Name'];
// esign/patients_signature signature/uploadwitnessign
     

     

    $select_conset_detail = mysqli_query($conn,"SELECT * FROM tbl_consert_blood_forms_details WHERE   Registration_ID = '$Registration_ID' AND Consent_ID = '$Consent_ID'");
    if(mysqli_num_rows($select_conset_detail) > 0){
        while ($row = mysqli_fetch_assoc($select_conset_detail)) {
            $consent_by = $row['consent_by'];
            $Signed_at = $row['Signed_at'];
            $consent_amputation = $row['consent_amputation'];
            $behalf = $row['behalf'];
    
        }
    }

    $employee_datas = mysqli_query($conn, "SELECT kada, Phone_Number, Employee_Job_Code, employee_signature FROM tbl_employee WHERE Employee_ID = '$Employee_ID_signed'");
    while($deal = mysqli_fetch_assoc($employee_datas)){
        $kada = $deal['kada'];
        $Employee_Job_Code = $deal['Employee_Job_Code'];
        $employee_signature = $deal['employee_signature'];
        $employee_Phone_Number = $deal['Phone_Number'];

    $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:20px'>";

    if(empty($employee_Phone_Number)){
        $employee_Phone_Number = '<b>NOT INSERTED</b>';
    }

    }
    //  $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
     $Patientsignature="<img src='../esign/patients_signature/$Patient_sgnature' style='height:25px'>";
     $witnessignature="<img src='../signaturesignatur/$witnessign' style='height:25px'>";


            $htm .= '
                <table align="center" width="100%" style="color:black;text-align:center; font-size: 12px; font-family: serif;">
                
                            <tr><td style="text-align:center; width: 100%;"><img src="./branchBanner/NHIF.png" width="120px"></td></tr>
                            <tr><td style="text-align:center; font-size: 16px;"><b>PATIENT OVERSTAY NOTIFICATION FORM</b></td></tr>
                            <tr><td style="text-align:center ;font-size: 13px; font-weight: bold;">This form should be utilized only when the Patient overstay form more than 5 days in ICU and 10 days in General Wards</td>               
                            </tr>
                        </table>';
                
            $htm .= '
                <table align="center" width="100%" style="color:black;text-align:center; text; font-family: serif;font-size: 12px;">
                    <tr>
                        <th colspan="6"> 				
                        A. Patient Information 
                        </th>
                    </tr>
                    <tr>
                        <td width="18%; font-size: 12px;">Name of the Patient:</td>
                        <td width="18%;"><b>'.$Patient_Name.'</b></td>
                        <td width="15%";>Hosp Reg. No:</td>
                        <td width="7%"><b>'.$Registration_ID.'</b></td>
                        <td width="5%";>Age:</td>
                        <td width="20%"><b>'.$age.'</b></td>
                        </td>
                    </tr>
                    <tr>
                        <td width="18%;">Membership Number:</td>
                        <td width="20%;"><b>'.$Member_Number.'</b></td>
                        <td width="15%";>Phone Number:</td>
                        <td colspan="2"><b>'.$Phone_Number.'</b></td>
                    </tr> 
                    <tr>
                        <td width="18%;">Service Authorization Number:</td>
                        <td width="20%;"><b>'.$AuthorizationNo.'</b></td>
                        <td width="15%";>Disease(s) Code:</td>
                        <td colspan="3"><b>'.$magonjwa.'</b></td>
                    </tr>       
                    <tr>
                        <td colspan="6"><br></td>
                    </tr>
                        </table>';


                        $htm .= '
                <table align="center" width="100%" style="color:black;text-align:center; text; font-family: serif;font-size: 12px;">
                    <tr>
                        <th colspan="6"> 				
                            B. Treating Health Facility (Hospital/Health Centre)
                        </th>
                    </tr>
                    <tr>
                        <td width="20%;" style="text-align: right;">Name of Health Facility:</td>
                        <td width="18%;"><b>'.$Hospital_Name.'</b></td>
                        <td width="20%;" style="text-align: right;">Accredential Number:</td>
                        <td width="7%"><b>'.$facility_code.'</b></td>
                        <td width="5%;" style="text-align: right;">Address:</td>
                        <td width="20%"><b>'.$Box_Address.'</b></td>
                        </td>
                    </tr>
                    <tr>
                        <td width="18%" style="text-align: right;">Department:</td>
                        <td width="20%;"><b>'.$Clinic_Name.'</b></td>
                        <td width="15%" style="text-align: right;">Ward Number:</td>
                        <td colspan="3"><b>'.$Hospital_Ward_Name.'</b></td>
                    </tr>  
                    <tr>
                        <td width="18%" style="text-align: right;">Admission Date:</td>
                        <td width="20%;"><b>'. date('d, M Y', strtotime($Admission_Date_Time)).'</b></td>
                        <td width="15%" style="text-align: right;">Notification Date:</td>
                        <td colspan="2"><b>'. date('d, M Y', strtotime($Signed_Date_Time)).'</b></td>
                    </tr>     
                    <tr>
                        <td colspan="6"><br></td>
                    </tr>
                    <tr>
                        <th colspan="6">Reason for Overstay more than ten (10) days</th>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: justify;">'.$Reason_For_Overstaying.'</td>
                    </tr>
                    <tr>
                        <td colspan="6"><br></td>
                    </tr>
                    <tr>
                        <td colspan="6"> Name of notifying officer: <b>'.$Employee_Name_signed.'</b> &nbsp;&nbsp;&nbsp;&nbsp; Designation: <b>'.$Employee_Job_Code.'</b> &nbsp;&nbsp;&nbsp;&nbsp;Qualification:  <b>'.$kada.'</b></td>
                    </tr>
                    <tr>
                    <td colspan="6">Signature: <b>'.$signature.'</b> &nbsp;&nbsp;&nbsp;&nbsp; Notification Date: <b>'.date('d, M Y', strtotime($Signed_Date_Time)).'</b></td>
                </tr>

                        </table>';


                $htm .="
                <table align='center' width='100%' style='color:black;text-align:center; text; font-family: serif;font-size: 14px;'>
                </table>";
    
                $htm .="<br>";
    
                $htm .= '
                <table align="center" width="100%" style="color:black;text-align:center; text; font-family: serif;">
                    <tr>
                        <th colspan="6"> 				
                        C. <i>Patient Certification (To be filled by a patient or relative)</i>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="6">I admit to have been admitted from <b> '.date("Y-m-d", strtotime($Admission_Date_Time)).'</b> to <b>'.$Discharge_Date_Time.'</b></td>
                    </tr>
                    <tr>
                        <td colspan="6">Name of the Patient: <b> '.$Patient_Name.'</b>  &nbsp;&nbsp;&nbsp;&nbsp; Signature: '.$Patientsignature.'   &nbsp;&nbsp;&nbsp;&nbsp; Date: <b> '.date('d, M Y', strtotime($Signed_Date_Time)).'</td>
                    </tr>
                </table>
                <br/>
                <br/>';

                $htm .="<p style='font-weight: bold; font-size: 12px; '>NOTE: This notification MUST be attached with NHIF 2A & B when submitted for payment; failure shall result to disqualification of the respective claim";
    // $htm .= "</table>";
	include("./MPDF/mpdf.php");
    $Employee_Name=$_SESSION['userinfo']['Employee_Name'];
    $mpdf=new mPDF('c','A4','','', 15,15,12,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;