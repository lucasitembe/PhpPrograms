<?php
@session_start();
include("./includes/connection.php");
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }
    $filter = "  AND cid.ToBe_Admitted = 'yes' AND cid.Admit_Status = 'not admitted' ";


    if(isset($_GET['Patient_Name'])){
        $Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
    }else{
        $Patient_Name = '';
    }

    if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];
    }else{
        $Patient_Number = '';
    }
    
    if(isset($_GET['Ward_ID'])){
        $Ward_ID = $_GET['Ward_ID'];
    }else{
        $Ward_ID = '';
    }
    
    
    if(isset($_GET['Sponsor'])){
        $Sponsor = $_GET['Sponsor'];
    }else{
        $Sponsor = 'All';
    }
    
    if(isset($_GET['ward'])){
        $ward = $_GET['ward'];
    }else{
        $ward = 'All';
    }

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND sp.Sponsor_ID=$Sponsor";
    }
    
    if (!empty($Ward_ID) && $Ward_ID != 'All') {
        $filter .=" AND cid.Ward_suggested=$Ward_ID";
    }
    if ($Patient_Name != '' && $Patient_Name != null) {
        $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
    }

    if ($Patient_Number != '' && $Patient_Number != null) {
        $filter .="  AND pr.Registration_ID = '$Patient_Number'";
    }
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
    }
    $htm='';
    $htm = '<table align="center" width="100%">
    <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
    <tr><td style="text-align:center"><b>LIST OF PATIENT TO BE ADMITTED</b></td></tr>
    </table>';
    
    
    
    $htm .= '<table width=100% border=1 style="border-collapse: collapse;">
            <thead>
            <tr>
                <th style="width:5%;">SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>PHONE NUMBER</b></th>
                <th><b>DOCTOR/NURSE</b></th>
             </tr>
            </thead>';
            
            $patients_tobeadmitted = "
            SELECT * FROM 
                tbl_check_in_details cid,
                tbl_patient_registration pr,
                tbl_employee em,
                tbl_sponsor sp
                WHERE 
                    pr.Registration_ID = cid.Registration_ID AND
                    cid.Employee_ID = em.Employee_ID AND
                    cid.Sponsor_ID = sp.Sponsor_ID
                    $filter GROUP BY cid.Registration_ID order by cid.Check_In_Details_ID DESC 
        ";
    // die($patients_tobeadmitted);
    
$patients_tobeadmitted_qry = mysqli_query($conn,$patients_tobeadmitted) or die(mysqli_error($conn));
$sn = 1;
// die("====================================================12222>");

     
    
while($toadmit = mysqli_fetch_assoc($patients_tobeadmitted_qry)){

//AGE FUNCTION
 $age = floor( (strtotime(date('Y-m-d')) - strtotime($toadmit['Date_Of_Birth'])) / 31556926)." Years";
   // if($age == 0){
    
    $date1 = new DateTime($Today);
    $date2 = new DateTime($toadmit['Date_Of_Birth']);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age .= $diff->m." Months, ";
    $age .= $diff->d." Days";
    
    $patient_name = $toadmit['Patient_Name'];
    $sponsor = $toadmit['Guarantor_Name'];
    //$dob = $toadmit['Date_Of_Birth'];
    $gender = $toadmit['Gender'];
    $phone = $toadmit['Phone_Number'];
    $member_number = $toadmit['Member_Number'];
    $doctor = $toadmit['Employee_Name'];
    $Check_In_ID = $toadmit['Check_In_ID'];
    $Registration_ID = $toadmit['Registration_ID'];
            $suggested_ward=$toadmit['Ward_suggested'];
            $ward='&Ward_suggested='.$suggested_ward.'';
           
            if(isset($_GET['fromDoctorPage']) && $_GET['fromDoctorPage']=='fromDoctorPage'){
                           
            $admit_link = "admit.php?Registration_ID=".$Registration_ID."&Check_In_ID=".$Check_In_ID."&AdmitPatient=AdmitPatientThisPage&fromDoctorPage=fromDoctorPage".$ward;

            }  else { 
             $admit_link = "admit.php?Registration_ID=".$Registration_ID."&Check_In_ID=".$Check_In_ID."&AdmitPatient=AdmitPatientThisPage".$ward;
            }
    $link_style = "style='text-decoration:none;'";
    
    $htm.= "<tr>";
        $htm.= "<td>".$sn."</td>";
        $htm.= "<td>".ucwords(strtolower($patient_name))."</a></td>";
        $htm.= "<td>".$Registration_ID."</a></td>";
        $htm.= "<td>".$gender."</a></td>";
        $htm.= "<td>".$age."</a></td>";
        $htm.= "<td>".$sponsor."</a></td>";
        $htm.= "<td>".$phone."</a></td>";
        $htm.= "<td>".$doctor."</a></td>";
    $htm.= "</tr>";
    $sn++;
}
    
    
    $htm .= "</table>";
	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($E_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By eHMS');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;