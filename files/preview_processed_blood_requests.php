<?php

include("./includes/connection.php");
@session_start();

 $Employee_Name_Print = $_SESSION['userinfo']['Employee_Name'];

if (isset($_SESSION['userinfo'])) {

} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$data = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";

$consultation_ID = 0;
if (isset($_GET['consultation_ID'])) {
    $consultation_ID = trim($_GET['consultation_ID']);
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = trim($_GET['Registration_ID']);
    $Blood_Transfusion_ID = $_GET['Blood_Transfusion_ID'];

}

//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information

    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"SELECT pr.Patient_Name,pr.Sponsor_ID,pr.Registration_ID,pr.Date_Of_Birth, pr.Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward, pr.Member_Number,pr.Member_Card_Expire_Date, pr.Phone_Number, pr.Religion_ID, pr.Tribe,sp.Guarantor_Name,sp.Claim_Number_Status, sp.Postal_Address,sp.Benefit_Limit from tbl_patient_registration pr, tbl_sponsor sp where pr.Sponsor_ID = sp.Sponsor_ID and pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
   // $no = mysqli_num_rows($select_Patient);
    if (mysqli_num_rows($select_Patient)> 0) {
        while ($row = mysqli_fetch_assoc($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Country = $row['Country'];
            $Patient_Picture = $row['Patient_Picture'];
            $Deseased = ucfirst(strtolower($row['Diseased']));
            $Sponsor_Postal_Address = $row['Postal_Address'];
            $Benefit_Limit = $row['Benefit_Limit'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Religion_Name = $row['Religion_Name'];
            $Tribe = $row['Tribe'];
            // $data .= $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
    } else {
        $Registration_ID = 'NO DATA';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Deseased = '';
        $Sponsor_Postal_Address = '';
        $Benefit_Limit = '';
        $Patient_Picture = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $Tribe = '';
        $Religion_Name = '';
        $age = 0;
    }


//$Consultation_Date_And_Time=$docResult['Consultation_Date_And_Time'];

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ' ';
}


$showItemStatus = true;
 $display = "";


if($Tribe > 0){
    $Tribe = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tribe_name FROM tbl_tribe WHERE tribe_id = '$Tribe'"))['tribe_name'];
}
$sql_select_admission_ward_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID=(SELECT Hospital_Ward_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status<>'Discharged' ORDER BY Admision_ID DESC LIMIT 1)") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_admission_ward_result)>0){
    $Hospital_Ward_Name=mysqli_fetch_assoc($sql_select_admission_ward_result)['Hospital_Ward_Name'];
}else{
    $Hospital_Ward_Name = 'NOT ADMITTED';
}
$select_blood_tranfusion = mysqli_query($conn, "SELECT em.Employee_Name, bt.Saved_date, bt.Process_Status, bt.time_to_be_given, bt.Blood_Transfusion_ID, bt.reason_for_transfusion, bt.Priority, bt.hour_days, bt.Specimen_Details, bt.Within, bt.to_be_given, bt.operation_on, bt.amount_blood, bt.dr_group, bt.previous_transfusion, bt.Employee_ID, bt.Clinical_History FROM tbl_blood_transfusion_requests bt, tbl_employee em WHERE em.Employee_ID = bt.Employee_ID AND bt.Blood_Transfusion_ID= '$Blood_Transfusion_ID'");
                
if(mysqli_num_rows($select_blood_tranfusion) > 0){

              while($rows = mysqli_fetch_array($select_blood_tranfusion)){
                  $Blood_Transfusion_ID = $rows['Blood_Transfusion_ID'];
                  $Priority = $rows['Priority'];
                  $hour_days = $rows['hour_days'];
                  $Specimen_Details = $rows['Specimen_Details'];
                  $to_be_given = $rows['to_be_given'];
                  $Within = $rows['Within'];
                  $operation_on = $rows['operation_on'];
                  $Doctor_collected = $rows['Employee_ID'];
                  $amount_blood = $rows['amount_blood'];
                  $dr_group = $rows['dr_group'];
                  $previous_transfusion = $rows['previous_transfusion'];
                  $Clinical_History = $rows['Clinical_History'];
                  $Employee_Name_huyu = $rows['Employee_Name'];
                  $reason_for_transfusion = $rows['reason_for_transfusion'];
                  $Saved_date = $rows['Saved_date'];
                  $time_to_be_given = $rows['time_to_be_given'];
                  $Process_Status = $rows['Process_Status'];
                  $This_Employee_ID = $rows['Employee_ID'];

              }
                  $select_previous = mysqli_query($conn, "SELECT btp.Employee_ID, btp.Submitted_By, btp.Submitted_At, btp.Rh1, btp.Rh2, btp.Rh3, btp.Rh4, btp.donor1, btp.donor2, btp.donor3, btp.donor4, btp.group1, btp.group2, btp.group3, btp.group4, btp.Quality, btp.Processed_Date_Time, btp.Pt_Hb, btp.pt_Group, btp.pt_Rh, btp.Blood_Transfusion_ID, btp.Comments, btp.Coombs, em.Employee_Name FROM tbl_employee em, tbl_blood_transfusion_processing btp WHERE btp.Blood_Transfusion_ID = '$Blood_Transfusion_ID' AND em.Employee_ID = btp.Employee_ID");

                  while($btr = mysqli_fetch_assoc($select_previous)){
                      $Rh1 = $btr['Rh1'];
                      $Rh2 = $btr['Rh2'];
                      $Rh3 = $btr['Rh3'];
                      $Rh4 = $btr['Rh4'];
                      $donor1 = $btr['donor1'];
                      $donor2 = $btr['donor2'];
                      $donor3 = $btr['donor3'];
                      $donor4 = $btr['donor4'];
                      $group1 = $btr['group1'];
                      $group2 = $btr['group2'];
                      $group3 = $btr['group3'];
                      $group4 = $btr['group4'];
                      $Quality = $btr['Quality'];
                      $Pt_Hb = $btr['Pt_Hb'];
                      $pt_Group = $btr['pt_Group'];
                      $pt_Rh = $btr['pt_Rh'];
                      $This_Employee_Name = $btr['Employee_Name'];
                      $Blood_Transfusion_ID = $btr['Blood_Transfusion_ID'];
                      $Comments = $btr['Comments'];
                      $Coombs = $btr['Coombs'];
                      $Processed_Date_Time = $btr['Processed_Date_Time'];
                      $Submitted_By = $btr['Submitted_By'];
                      $Submitted_At = $btr['Submitted_At'];
                      $Processor = $btr['Employee_ID'];

                      $Submitted_Employee = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Submitted_By'"))['Employee_Name'];
                  }
              
}

$data .= '<fieldset style="width:99%;height:460px ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll">
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center  " id="outpatient">
        <b align="center">BLOOD TRANSFUSION FORM</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Patient Name</b></td><td colspan="" style="font-size: 11px;">' . $Patient_Name . '</td>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Country</b></td><td colspan="" style="font-size: 11px;">' . $Country . '</td>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Region</b></td><td colspan="" style="font-size: 11px;">' . $Region . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;"><b>Registration #</b></td><td>' . $Registration_ID . '</td><td style="text-align:right"><b>Phone #:</b></td><td style="">' . $Phone_Number . '</td>
                <td style="text-align:right"><b>Address</b></td><td style="">' . $District . ',&nbsp;'. $Ward .'</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;"><b>Age</b></td><td style="">' . date("j F, Y", strtotime($age)) . ' </td>
                <td style="width:10%;text-align:right;" ><b>Tribe</b></td><td colspan="" style="font-size: 11px;"> ' . $Tribe . '</td>
                <td style="text-align:right"><b>Gender:</b></td><td style="">' . $Gender . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;" ><b>Insurance</b></td><td colspan="" style="font-size: 11px;"> ' . $Guarantor_Name . $sponsoDetails . '</td>
                <td style="width:10%;text-align:right;" ><b>Ward</b></td><td colspan="" style="font-size: 11px;">' . $Hospital_Ward_Name . '</td>
                <td style="width:10%;text-align:right;" ><b>Ordered By</b></td><td colspan="" style="font-size: 11px;">' . $Employee_Name_huyu . '</td>
            </tr>
        </table>
    </div>
    <div style="padding:5px; width:99%;font-size:12px;border:1px solid #000;  background:#ccc;text-align:center  " id="outpatient">
        <b align="center">BLOOD TRANSFUSION DETAILS</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Priority</b></td><td colspan="" style="font-size: 11px;">' . $Priority . '</td>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Requested Time</b></td><td colspan="" style="font-size: 11px;">' . $Saved_date . '</td>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Operation On</b></td><td colspan="" style="font-size: 11px;">' . $operation_on . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;"><b>Specimen Details</b></td><td colspan="5">' . $Specimen_Details . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;"><b>Clinical History</b></td><td colspan="5">' . $Clinical_History . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;"><b>Transfusion To be Given</b></td><td style="">'.$to_be_given.' '.$time_to_be_given.' '.$hour_days.'</td>
                <td style="width:10%;text-align:right;" ><b>Amount of Blood Required</b></td><td colspan="" style="font-size: 11px;"> ' . $amount_blood . '</td>
                <td style="text-align:right"><b>Group If Known</b></td><td style="">' . $dr_group . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;"><b>Previous Transfusion</b></td><td colspan="5">' . $previous_transfusion . '</td>
            </tr>
            <tr>
            <td style="width:10%;text-align:right;"><b>Reason for Transfusion</b></td><td colspan="5">' . $reason_for_transfusion . '</td>
        </tr>
        </table>
    </div>
    <div style="padding:5px; width:99%;font-size:12px;border:1px solid #000;  background:#ccc;text-align:center  " id="outpatient">
        <b align="center">FOR LABORATORY USE</b>
    </div>
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Specimen Quality</b></td><td colspan="" style="font-size: 11px;">' . $Quality . '</td>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Coombs</b></td><td colspan="" style="font-size: 11px;">' . $Coombs . '</td>
                <td style="width:10%;text-align:right; style="font-size: 11px;" "><b>Case No</b></td><td colspan="" style="font-size: 11px;">' . $Blood_Transfusion_ID . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;"><b>Comments</b></td><td colspan="5">' . $Comments . '</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right;"><b>Patient HB</b></td><td style="">'.$Pt_Hb.' Gm/dl</td>
                <td style="width:10%;text-align:right;" ><b>Patient Group</b></td><td colspan="" style="font-size: 11px;"> ' . $pt_Group . '</td>
                <td style="text-align:right"><b>Rh</b></td><td style="">' . $pt_Rh . '</td>
            </tr>
        </table>
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr style="background: #dedede;">
                <th style="width: 2%;">SN</th>
                <th style="text-align: center;">Donor&#39;s Serial No</th>
                <th style="text-align: center;">Group</th>
                <th style="text-align: center;">Rh</th>
            </tr>
            <tr>
                <th style="width: 2%;">1</th>
                <td>'.$donor1.'</td>
                <td style="text-align: center;">'.$group1.'</td>
                <td style="text-align: center;">'.$Rh1.'</td>
            </tr>';
if(!empty($donor2)){
    $data .='<tr>
                <th style="width: 2%;">2</th>
                <td>'.$donor2.'</td>
                <td style="text-align: center;">'.$group2.'</td>
                <td style="text-align: center;">'.$Rh2.'</td>
            </tr>';
}
if(!empty($donor3)){
    $data .='<tr>

                <th style="width: 2%;">3</th>   
                <td>'.$donor3.'</td>
                <td style="text-align: center;">'.$group3.'</td>
                <td style="text-align: center;">'.$Rh3.'</td>
            </tr>';
}
if(!empty($donor4)){
    $data .='<tr>
                <th style="width: 2%;">4</th>
                <td>'.$donor4.'</td>
                <td style="text-align: center;">'.$group4.'</td>
                <td style="text-align: center;">'.$Rh4.'</td>
            </tr>';
}
        $data .='</table>';

        $sql_select_list_of_approver_result=mysqli_query($conn,"SELECT employee_signature,Employee_Name FROM  tbl_employee WHERE Employee_ID = '$Processor'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_list_of_approver_result)>0){
            while($approver_rows=mysqli_fetch_assoc($sql_select_list_of_approver_result)){
                $employee_signature=$approver_rows['employee_signature'];
                if($employee_signature==""||$employee_signature==null){
                    $signature="________________________";
                }else{
                    $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                }
            }
        }

        $sql_select_list_of_approver=mysqli_query($conn,"SELECT employee_signature,Employee_Name FROM  tbl_employee WHERE Employee_ID = '$Submitted_By'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_list_of_approver)>0){
            while($approver=mysqli_fetch_assoc($sql_select_list_of_approver)){
                $employee_signature_=$approver['employee_signature'];
                $employee_name_=$approver['Employee_Name'];
                if($employee_signature_==""||$employee_signature_==null){
                    $signature_="________________________";
                }else{
                    $signature_="<img src='../esign/employee_signatures/$employee_signature_' style='height:25px'>";
                }
            }
        }

        $data .='<table class="userinfo" border="no-border" style="border:none !important;" width="100%" style="margin-left:2px;">
        <tr>
            <td style="width:16%;text-align:right; font-size: 11px;"><b>Submitted By</b></td><td colspan="" style="font-size: 11px;">' . ucfirst($employee_name_) . '</td>
            <td style="width:10%;text-align:right; font-size: 11px;"><b>Date & Time</b></td><td colspan="" style="font-size: 11px;">' . $Submitted_At . '</td>
            <td style="width:7%;text-align:right; font-size: 11px;"><b>Signature</b></td><td colspan="" style="font-size: 11px;">' . $signature_ . '</td>
        </tr>
        <tr>
        <td style="width:16%;text-align:right; font-size: 11px;"><b>Processed By</b></td><td colspan="" style="font-size: 11px;" style="font-size: 11px;">' . ucfirst($This_Employee_Name) . '</td>
        <td style="width:10%;text-align:right; font-size: 11px;"><b>Date & Time</b></td><td colspan="" style="font-size: 11px;">' . $Processed_Date_Time . '</td>
        <td style="width:7%;text-align:right; font-size: 11px;"><b>Signature</b></td><td colspan="" style="font-size: 11px;">' . $signature . '</td>
    </tr>';
        $data .='</table>';
$data .= '</fieldset>';



include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
$mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name_Print)).' - {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);
$mpdf->Output();
