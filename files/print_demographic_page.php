<?php
include("./includes/connection.php");
include("MPDF/mpdf.php");
session_start();

if (isset($_SESSION['userinfo'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
}

if (isset($_GET['patient_id'])) {
  $patient_id = $_GET['patient_id'];
}

// get patient name
// get patient details

$select_patien_details = mysqli_query($conn,"SELECT Patient_Name,Registration_ID,Gender,Date_Of_Birth,Occupation,Phone_Number,
  Religion_ID FROM tbl_patient_registration WHERE	Registration_ID = '$patient_id'")
or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row_name = mysqli_fetch_array($select_patien_details)) {
            $patient_name = $row_name['Patient_Name'];
            $Registration_ID = $row_name['Registration_ID'];
            $Gender = $row_name['Gender'];
            $DOB = $row_name['Date_Of_Birth'];
            $occupation = $row_name['Occupation'];
            $phone_number = $row_name['Phone_Number'];
            $religion_id = $row_name['Religion_ID'];
        }
    }


$age = date_diff(date_create($DOB), date_create('today'))->y;


// get patient related info from table admission
$query_get_user_related_info = "SELECT Admission_Employee_ID,Kin_Name,Kin_Relationship,Kin_Phone,Kin_Address,Hospital_Ward_ID,Kin_Phone FROM tbl_admission WHERE Registration_ID='$patient_id'" ;
if($result_user_info = mysqli_query($conn,$query_get_user_related_info)){
  if(($user_info_num=mysqli_num_rows($result_user_info)) > 0) {
    while ($row_user_detail = mysqli_fetch_assoc($result_user_info)) {

      $admission_employee_id = $row_user_detail['Admission_Employee_ID'];
      $kin_name = $row_user_detail['Kin_Name'];
      $kin_relationship = $row_user_detail['Kin_Relationship'];
      $kin_phone = $row_user_detail['Kin_Phone'];
      $kin_address = $row_user_detail['Kin_Address'];
      $hospital_ward_id = $row_user_detail['Hospital_Ward_ID'];
      $kin_phone = $row_user_detail['Kin_Phone'];
    }

    // Select consultant name for the particula patient
    $query_select_consultant_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$admission_employee_id'";
    if ($result_consultant = mysqli_query($conn,$query_select_consultant_name)) {
      while ($row_consultant = mysqli_fetch_assoc($result_consultant) ){
        $consltant_name = $row_consultant['Employee_Name'];
      }

    }

    // get hospital ward name
    $query_get_hospital_ward = "SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$hospital_ward_id'";
    if ($result_ward = mysqli_query($conn,$query_get_hospital_ward)) {
      while ($row_ward = mysqli_fetch_assoc($result_ward)) {
          $ward_name = $row_ward['Hospital_Ward_Name'];
      }

}


  }

}else{
  echo mysqli_error($conn);
}
// get patient labour details
  $query_select_patient_labour_info = "SELECT * FROM tbl_demographic WHERE patient_id='$patient_id'";
  if ($result_patient_labour_info = mysqli_query($conn,$query_select_patient_labour_info)) {
if (($num=mysqli_num_rows($result_patient_labour_info) )>0 ) {
  while ($row_info=mysqli_fetch_assoc($result_patient_labour_info)) {
      $obstretic = $row_info['obstretic'];
      $history = $row_info['history'];
      $gravida = $row_info['gravida'];
      $lmp = $row_info['lmp'];
      $para = $row_info['para'];
      $ga = $row_info['ga'];
      $edd = $row_info['edd'];
      $bloodgroup = $row_info['bloodgroup'];
      $weight = $row_info['weight'];
      $height = $row_info['height'];
      $living = $row_info['living'];
      $date_of_admission=$row_info['date_of_admission'];
      $date_of_first_attendance=$row_info['date_of_first_attendance'];
      $risk_factor=$row_info['risk_factor'];
      $previous_therapy = $row_info['previous_therapy'];
      $date_of_discharge=$row_info['date_of_discharge'];
      $medical_surgical_history = $row_info['medical_sergical_history'];
      $family_history = $row_info['family_history'];
      $diagnosis_reason_for_admission = $row_info['diagnosis_reason_for_admission'];
      $drug_allegies = $row_info['drug_allegies'];
      $living = $row_info['living'];
      $present_history = $row_info['present_history'];
      $labour_history = $row_info['labour_history'];
      $social_history = $row_info['social_history'];
      $date_of_1stvsit = $row_info['date_of_1stvsit'];
      $ga_at_1stvisit = $row_info['ga_at_1stvisit'];
      $number_ofanc_visit = $row_info['number_ofanc_visit'];

      $bp = $row_info['bp'];
      $bp2 = $row_info['bp2'];
      $hb = $row_info['hb'];
      $hb2 = $row_info['hb2'];
      $pmtct = $row_info['pmtct'];
      $pmtct2 = $row_info['pmtct2'];
      $vdrl = $row_info['vdrl'];
      $vdrl2 = $row_info['vdrl2'];
      $mrdt = $row_info['mrdt'];
      $mrdt2 = $row_info['mrdt2'];
      $urinalysis = $row_info['urinalysis'];
      $urinalysis2 = $row_info['urinalysis2'];
      $fefo = $row_info['fefo'];
      $sp = $row_info['sp'];
      $tt = $row_info['tt'];
      $mebendazole = $row_info['mebendazole'];
  }

}
  }

// get child history
$query_select_labour_history  = "SELECT * FROM tbl_child_labour_history WHERE Registration_ID = '$patient_id'";

$htm= "<style>
table{
  border-collapse:collapse;

}
tr{

  height:30px !important;
  cellspacing:5px;
}

tbody tr{
  border:solid thin grey ;
}
  th{
    text-align:Left !important;
    border:solid thin grey;
  }
  td{
    height:40px !important;

    font-size:16px;
  }
  .aside{
    float:left;
  }
  tbody tr{
    margin-bottom:1px !important;
  }
</style>";
$htm .= "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm .= "<center><h4>LABOUR ADDMISSION</h4></center>";
$htm .= "<center><table width='100%' border='1'>
  <tr>
  <td style='width:12%; text-align:left;'><b>PAT NO:</b></td><td style='text-align:left;'>".$patient_id."</td>
  <td style='width:10%; text-align:left;'><b>WARD:</b></td><td style='width:12%;text-align:left'>".$ward_name."</td>
  <td style='width:12%; text-align:left;'><b>NAME:</b></td><td style='width:12%;'>".$patient_name."</td>
  </tr>
  <tr>
  <td style='width:12%; text-align:left;'><b>AGE:</b></td><td style='width:15%;text-align:left; '>".$age."</td>
  <td style='width:10%; text-align:left;'><b>Consultant:</b></td><td style='width:15%;'>".$consltant_name."</td>
  <td style='width:15%; text-align:left;'><b>GENDER:</b></td><td style='width:15%;text-align:left'>".$Gender."</td>

  </tr>
  <tr>
  <td style='width:12%; text-align:left;'><b>Occupation:<b></td><td style='width:15%;'>".$occupation."</td>
  <td style='width:12%; text-align:left;'><b>Religion:</b></td><td style='width:15%;'>".$religion_id."</td>
  <td style='width:15%; text-align:left;'><b>Phone Number:</b></td><td style='width:15%;'>".$phone_number."</td>
  </tr>
</table><center><br />";




$htm .= "<center>
<table width='100%' border='1'>
  <tr>
  <td style=' width:10%;text-align:left'><b>KIN NAME:</b></td>
  <td style='width:12%;text-align:left'>".$kin_name."</td>

  <td style='width:10%;text-align:left'><b>RELATIONSHIP:</b></td>
  <td style='width:12%; text-align:left'>".$kin_relationship."</td>

  <td style='width:10%;text-align:right'><b>PLACE OF WORK:</b></td>
  <td style='width:15%;'>".$place_of_work."</td>
  </tr>
  <tr>
    <td style='text-align:left;width:10%'><b>KIN PHONE:</b></td>
    <td style='width:15%;text-align:left'>".$kin_phone."</td>
    <td><b></b></td><td></td>
    <td><b></b></td><td></td>
  </tr>
</table>
<center>
<br />";



$htm .="<table width='100%' border='1'>

  <tr>
    <td colspan='4' style='width:10%; text-align:left;'><b>OBSTRETIC HISTORY</b></td>

    <td style='width:10%; text-align:left;'><b>GRAVIDA:</b></td>
    <td style='width:15%;text-align:left;'>".$gravida."</td>
    </tr>
    <tr>
    <td style='width:10%; text-align:left;'><b>PARA:</b></td><td width='18%'>".$para."</td>
    <td style='width:10%; text-align:left;'><b>LMP:</b></td><td width='18%'>".$lmp."</td>
    <td style='width:10%; text-align:left;'><b>E.D.D:</b></td><td width='18%'>".$edd."</td>
  </tr>
  <tr>
    <td style='widht:15%; text-align:left;' ><b>GA:</b></td><td>".$ga."</td>
    <td style='widht:15%; text-align:left;'><b>BLOODGROUP:</b></td><td>".$bloodgroup."</td>

    <td style='widht:15%; text-align:left;'><b>WEIGHT:</b></td><td>".$weight."</td>

    </tr><tr>
    <td style='widht:15%; text-align:right;'><b>HEIGHT:</td><td>".$height."</td>
    <td width='20%' ><b>Living</b></td>
    <td width='20%' >".$living."</td>
  </tr>

</table>
<br />
<center>
<table width='100%' border='1'>
  <tr>
    <td style='width:16%;text-align:right;'><b>Date Of Admission:</b></td>
    <td style='width:18%; text-align:left'>".$date_of_admission."</td>
    <td style='widht:15%; text-align:right !important'><b>Date Of First Attendance:</b></td><td style='width:18%;'>".$date_of_first_attendance ."</td>
    <td style='widht:15%; text-align:right !important'><b>Risk Factor:</b></td>
    <td style='width:18%;text-slign:left'>".$risk_factor ."</td>
  </tr>
  <tr>
    <td style='widht:15%; text-align:right'><b>Prevous Therapy:</b></td><td style='width:18%;'>". $previous_therapy."</td>

  </tr>
</table>
</center>
</table>
</center>
</fieldset>

<br />";
$htm .= "<center>
  <fieldset style='padding:10px'>
    <table class='aside' style='width: 98%;
    ' border='1'>
    <tr>
      <td width='20%'><b>Present History</b></td>
      <td width='20%'>".$present_history."</td>

      <td width='20%'><b> Labour History</b></td>
      <td width='20%'>".$labour_history."</td>
    </tr>
      <tr>
      <td width='20%'><b> Social History</b></td>
      <td width='20%'>".$social_history."</td>

        <td width='20%'><b> Family History</b></td>
        <td width='20%'>".$family_history."</td>
      </tr>
      <tr>
        <td width='20%'><b>Medical & Surgical History</b></td>
        <td width='20%'>".$medical_surgical_history."</td>
      </tr>


      <tr>
        <td width='20%'><b>Diagnosis Reason For Admission</b></td>
        <td width='20%' >".$diagnosis_reason_for_admission."</td>

        <td width='20%' ><b>Drug Allegies</b></td>
        <td width='20%' >".$drug_allegies."</td>
      </tr>
      </table>

  </fieldset>
</center>
<br />
";

$htm .= '
<!-- done by Abdul -->
<center>
  <fieldset>
    <legend style=\'width:12%; text-align:center;font-size:16px;\'><b>HISTORY OF INDEX PREGNANCY (current pregnancy)</b></legend>
    <table width="100%" border="1">
      <tr>
        <td style=\'width:12%; text-align:left;font-size:16px;\'><b>Date of 1st ANC visit :</b>'.$date_of_1stvsit.'</td>

        <td style=\'width:12%; text-align:left;font-size:16px;\'><b>GA at 1st visit:</b>'.$ga_at_1stvisit.'</td>
        <td style=\'width:12%; text-align:left;font-size:16px;\'><b>Number of ANC visits :</b>'.$number_ofanc_visit.'</td>
      </tr>
    </table>
    <br/>
    <table class="table table-striped" width="100%" border="1">
      <!-- row1 -->
      <tr>
        <th style=\'width:12%; text-align:left;font-size:16px;\'></th>
        <th style=\'width:12%; text-align:left;font-size:16px;\'>BP</th>
        <th style=\'width:12%; text-align:left;font-size:16px;\'>HB</th>
        <th style=\'width:12%; text-align:left;font-size:16px;\'>PMTCT</th>
        <th style=\'width:12%; text-align:left;font-size:16px;\'>VDRL</th>
        <th style=\'width:12%; text-align:left;font-size:16px;\'>MRDT</th>
        <th style=\'width:12%; text-align:left;font-size:16px;\'>Urinalysis</th>
        <th colspan="4">Medication Given</th>
      </tr>
      <!-- row2 -->
      <tr>
        <td style=\'width:12%; text-align:left;font-size:16px;\'><b>1st Visit</b></td>
        <!-- bp -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$bp.'</td>
        <!-- hb -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$hb.'</td>
        <!-- pmtct -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$pmtct.'</td>
        <!-- vdrl -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$vdrl.'</td>
        <!-- mrdt -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$mrdt.'</td>
        <!-- urinalysis -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$urinalysis.'</td>
        <!-- fefo -->
        <td rowspan="2" style=\'width:12%; text-align:left;font-size:16px;\'>
          <b>Fefo</b><br/>'.$fefo.'
        </td>
        <!-- sp -->
        <td rowspan="2" style=\'width:12%; text-align:left;font-size:16px;\'>
          <b>SP</b><br/>
           '. $sp.'
        </td>
        <!-- tt -->
        <td rowspan="2" style=\'width:12%; text-align:left;font-size:16px;\'>
          <b>TT</b><br/>
          '.$tt.'
        </td>
        <!-- mebendazole -->
        <td rowspan="2" colspan="2" style=\'width:12%; text-align:left;font-size:16px;\'>
          <b>Mebendazole</b><br/>
          '.$mebendazole.'
        </td>

      </tr>
      <!-- row3 -->
      <tr>
        <td><b>Other Visit</b></td>
        <!-- bp2 -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$bp2.'</td>
        <!-- hb2 -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$hb2.'</td>
        <!-- pmtct2 -->
        <td style=\'width:12%; text-align:left;font-size:16px\'>'.$pmtct2.'</td>
        <!-- vdrl2 -->
        <td style=\'width:12%; text-align:left;font-size:16px\'>'.$vdrl2.' </td>
        <!-- mrdt2 -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$mrdt2.'</td>
        <!-- urinalysis2 -->
        <td style=\'width:12%; text-align:left;font-size:16px;\'>'.$urinalysis2.'</td>

      </tr>
    </table>
  </fieldset>

<!-- end done by Abdul -->
<br /><br />
<center>
  <fieldset>';

$htm .= "<center><table style='width:100%; ' border:1px solid !important; >";
$htm .= "<thead><tr>
  <th>Sn</th>
  <th>Year Of Birth</th>
  <th>Date And Time</th>
  <th>Matunity</th>
  <th>Sex</th>
  <th>History of previous pregnancy</th>
  <th>Mode Of Delivery</th>
  <th>Birth Weight</th>
  <th>Place Of Birth</th>
  <th>Breast Fed Duration</th>
  <th>Pueperium</th>
  <th>Present Child Condition</th>
  <th>Prepared By</th>
</tr></thead>
<tbody>
";

if($result_labour_history = mysqli_query($conn,$query_select_labour_history)) {

if ($num=mysqli_num_rows($result_labour_history) >0 ) {

  $tmp = 1;
  while ($row=mysqli_fetch_assoc($result_labour_history)) {
    $htm .= "<tr style='height:20px;'>
    <td style='text-align:center'> " .$tmp++ ."</td>
    <td style='text-align:center'>".$row['year_of_birth'] ."</td>
    <td style='text-align:center'>".$row['date_and_time'] ."</td>
    <td style='text-align:center'>" .$row['matunity']."</td>
    <td style='text-align:center'>" .$row['gender']."</td>
    <td style='text-align:center'>" .$row['history_of_pregnancy']."</td>
    <td style='text-align:center'>" .$row['mode_of_delivery']."</td>
    <td style='text-align:center'>" .$row['birth_weight']."</td>
    <td style='text-align:center'>" .$row['place_of_birth']."</td>
    <td style='text-align:center'>" .$row['breast_fed_duration']."</td>
    <td style='text-align:center'>" .$row['peuperium']."</td>
    <td style='text-align:center'>" .$row['present_child_condition']."</td>
    <td style='text-align:center'>" .$row['Employee_Name']."</td>
    </tr>";

  }

}
}else {
  // $htm .= mysqli_error($conn);
}


$htm .= "</tbody>";
$htm .= "</table></center>";


$mpdf = new mPDF('s', 'A4');

$mpdf->SetDisplayMode('fullpage');
$mpdf->SetFooter('Printed By '.strtoupper($Employee_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
  ?>
