<?php
include("./includes/connection.php");
include("MPDF/mpdf.php");
session_start();
if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admission_id'])) {
  $admision_id = $_GET['admission_id'];
}
if (isset($_GET['Registration_ID'])) {
  $Registration_ID = $_GET['Registration_ID'];
}
if(isset($_SESSION['userinfo']['Employee_Name'])){
  $E_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
  $E_Name = '';
}
if (isset($_GET['admission_id'])) {
  $admision_id = $_GET['admission_id'];
}

if (isset($_GET['patient_id'])) {
 $patient_id = $_GET['patient_id'];
}
$today = Date("Y-m-d");
$htm = "<img src='./branchBanner/branchBanner.png'>";
$htm .= "<p style='font-weight:bold; text-align:center;'> LABOUR RECORD</p>";
// get patient details
if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
  $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,pr.Member_Number,pr.Patient_Name,pr.Registration_ID,pr.Gender,sp.Guarantor_Name,pr.Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $Registration_ID . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
  $no = mysqli_num_rows($select_patien_details);
  if ($no > 0) {
    while ($row = mysqli_fetch_array($select_patien_details)) {
      $Member_Number = $row['Member_Number'];
      $Patient_Name = $row['Patient_Name'];
      $Registration_ID = $row['Registration_ID'];
      $Gender = $row['Gender'];
      $Guarantor_Name = $row['Guarantor_Name'];
      $Sponsor_ID = $row['Sponsor_ID'];
      $DOB = $row['Date_Of_Birth'];
    }
  } else {
    $Guarantor_Name = '';
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
  }
} else {
  $Member_Number = '';
  $Patient_Name = '';
  $Gender = '';
  $Registration_ID = 0;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;

// get labour record of a particula user

$select_labour_record = "SELECT lb.admission_id,reg.Patient_Name,lb.Employee_ID,emp.Employee_Name,lb.date_time, lb.today_date, lb.summary_Antenatal, lb.abnormalities, lb.lmp, lb.edd, lb.ga, lb.general_condition, lb.fundamental_height, lb.blood_pressure, lb.size_fetus, lb.lie, oedema, lb.presentation, lb.acetone, lb.protein, lb.liquor, lb.height, lb.meconium, lb.estimation_presentation, lb.membrane, lb.last_recorded, lb.blood_group, lb.cervic_state, lb.presenting_part, lb.levels, position, lb.moulding, lb.caput, lb.bony, lb.membranes_liquor, lb.sacral_promontory, lb.sacral_curve, lb.Lachial_spines, lb.subpubic_angle, lb.sacral_tuberosites, lb.summary, lb.remarks,lb.dilation, lb.temperature,lb.admission_reason,lb.admission_from FROM tbl_labour_record2 as lb,tbl_patient_registration as reg,tbl_employee as emp WHERE lb.Registration_ID='$Registration_ID' and reg.Registration_ID=lb.Registration_ID and emp.Employee_ID=lb.Employee_ID ORDER BY lb.date_time DESC";
$select_labour_history=mysqli_query($conn,"SELECT  lh.history_year, lh.history_complication, lh.gravida_method, lh.gravida_wt, lh.gravida_alive, lh.para_year, lh.para_complication, lh.living_method, lh.living_wt, lh.living_alive FROM tbl_labour_history lh,tbl_labour_record2 lr WHERE lr.labour_record_ID=lh.labour_record_ID and lr.Registration_ID='$Registration_ID' ");
if ($result_labour_record = mysqli_query($conn,$select_labour_record)) {
  if ($num = mysqli_num_rows($result_labour_record) > 0) {
    while ($row_labour = mysqli_fetch_assoc($result_labour_record)) {
        $date_time = $row_labour['date_time'];
        $admission_reason = $row_labour['admission_reason'];
        $abnormalities = $row_labour['abnormalities'];
        $admission_from = $row_labour['admission_from'];
        $summary_Antenatal = $row_labour['summary_Antenatal'];
        $general_condition = $row_labour['general_condition'];
        $fundamental_height = $row_labour['fundamental_height'];
        $temperature = $row_labour['temperature'];
        $presentation = $row_labour['presentation'];
        $blood_pressure = $row_labour['blood_pressure'];
        $oedema = $row_labour['oedema'];
        $acetone = $row_labour['acetone'];
        $protein = $row_labour['protein'];
        $Height = $row_labour['Height'];
        $estimation_presentation = $row_labour['estimation_presentation'];
        $last_recorded = $row_labour['last_recorded'];
        $size_fetus = $row_labour['size_fetus'];
        $lie = $row_labour['lie'];
        $liquor = $row_labour['liquor'];
        $meconium = $row_labour['meconium'];
        $membrane = $row_labour['membrane'];
        $blood_group = $row_labour['blood_group'];

        $date_time = $row_labour['date_time'];
            $bony = $row_labour['bony'];
            $cervic_state = $row_labour['cervic_state'];
            $blood_pressure = $row_labour['blood_pressure'];
            $sacral_curve = $row_labour['sacral_curve'];
            $sacral_promontory = $row_labour['sacral_promontory'];
            $dilation = $row_labour['dilation'];
            $presenting_part = $row_labour['presenting_part'];
            $Lachial_spines = $row_labour['Lachial_spines'];
            $levels = $row_labour['levels'];
            $subpubic_angle = $row_labour['subpubic_angle'];
            $position = $row_labour['position'];
            $sacral_tuberosites = $row_labour['sacral_tuberosites'];
            $moulding = $row_labour['moulding'];
            $summary = $row_labour['summary'];
            $caput = $row_labour['caput'];
            $membranes_liquor = $row_labour['membranes_liquor'];
            $remarks = $row_labour['remarks'];


           
$htm .= '<center>
  <fieldset>
    <legend style="font-weight:bold"align=center>
      <div style="height:34px;margin:0px;padding:0px;font-weight:bold; text-align:center;">
        <p style="color:#3DBCFD;margin:0px;padding:0px; "><span style="margin-right:3px;">' . $Patient_Name . '|</span><span style="margin-right:3px;">' . $Gender . '|</span>
        <span style="margin-right:3px;">' . $age . 'Years| </span>
        <span style="margin-right:3px;">' . $Guarantor_Name . '</span> </p>
      </div>
  </legend>
  </fieldset>
    <h4 style="text-align:center;background-color:#006400;font-size:20px;color:white;" > '.$date_time.'</h4>
        <table border="1" class="table table-bordered" style="width:100%;border-collapse: collapse;">
        <thead>
          <tr>
            <th colspan="4" style="font-weight:bold;height:30px;">ADMISSION:</th>
          </tr>
        </thead>
        <tr>
          <td class="td-label"style="width:20%">Admission Reason</td>
          <td style="width:30%">'.$admission_reason.'</td>
          <td class="td-label" style="width:20%">Admission From</td>
          <td class="td-input" style="width:30%">'.$admission_from.'</td>
        </tr>
        <tr>
          <td class="td-label"style="width:20%">Abnormalities</td>
          <td style="width:30%">'.$abnormalities.'</td>
          <td class="td-label" style="width:20%">Summary Of Antenatal</td>
          <td class="td-input" style="width:30%">'.$summary_Antenatal.'</td>
        </tr>
      </table>

      <table border="1" class="table table-bordered table-top" style="width:100%;border-collapse: collapse;">
          <thead>
            <tr>
              <th colspan="4" style="font-weight:bold;height:30px;">EXAMINATION</th>
            </tr>
          </thead>
          <tr>
            <td class="td-label"style="width:20%">General Condition</td>
            <td style="width:30%">'.$general_condition.'</td>
            <td class="td-label" style="width:20%">Fundamental Height:</td>
            <td class="td-input" style="width:30%">'.$fundamental_height.'</td>
          </tr>
          <tr>
            <td class="td-label"style="width:20%">Temperature</td>
            <td style="width:30%">'.$temperature.'</td>
            <td class="td-label" style="width:20%">Size Of Fetus</td>
            <td class="td-input" style="width:30%">'.$size_fetus.'</td>
          </tr>
          <tr>
            <td class="td-label"style="width:20%">Blood Pressure</td>
            <td style="width:30%">'.$blood_pressure.'</td>
            <td class="td-label" style="width:20%">Lie</td>
            <td class="td-input" style="width:30%">'.$lie.'</td>
          </tr>
          <tr>
            <td class="td-label"style="width:20%">Oedema</td>
            <td style="width:30%">'.$oedema.'</td>
            <td class="td-label" style="width:20%">presentation</td>
            <td class="td-input" style="width:30%">'.$presentation.'</td>
          </tr>
          <tr>
            <td class="td-label"style="width:20%">Urine:Acetone</td>
            <td style="width:30%">'.$acetone.'</td>
            <td class="td-label" style="width:20%">Urine:Proten</td>
            <td class="td-input" style="width:30%">'.$protein.'</td>
          </tr>
          <tr>
            <td class="td-label"style="width:20%">Liquor</td>
            <td style="width:30%">'.$liquor.'</td>
            <td class="td-label" style="width:20%">Height</td>
            <td class="td-input" style="width:30%">'.$Height.'</td>
          </tr>
          <tr>
            <td class="td-label"style="width:20%">Meconium Stained</td>
            <td style="width:30%">'.$meconium.'</td>
            <td class="td-label" style="width:20%">Ho Estimation Of Presentatio</td>
            <td class="td-input" style="width:30%">'.$estimation_presentation.'</td>
          </tr>
          <tr>
            <td class="td-label"style="width:20%">If Membrane Ruptured</td>
            <td style="width:30%">'.$membrane.'</td>
            <td class="td-label" style="width:20%">Last Recorded A/N</td>
            <td class="td-input" style="width:30%">'.$last_recorded.'</td>
          </tr>
          <tr>
            <td class="td-label"style="width:30%">Blood Group</td>
            <td style="width:70%" colspan="3" >'.$blood_group.'</td>
          </tr>
      </table>
      <br/>

    <table border="1" class="table table-bordered table-top" style="width:100%;border-collapse: collapse;" >
      <thead>
        <tr>
          <th colspan="4" style="font-weight:bold;height:30px;">INITIAL VAGINAL EXAMINATION AND PELVIC ASSESSMENT</th>
        </tr>
      </thead>
    <tr>
      <td class="td-label"style="width:20%">DATE AND TIME </td>
      <td style="width:30%">'.$date_time.'</td>
      <td class="td-label" style="width:20%">Bony Pelvis</td>
      <td class="td-input" style="width:30%">'.$bony.'</td>
    </tr>
    <tr>
      <td class="td-label"style="width:20%">Cervic:state</td>
      <td style="width:30%">'.$cervic_state.'</td>
      <td class="td-label" style="width:20%">Sacral promontory</td>
      <td class="td-input" style="width:30%">'.$sacral_promontory.'</td>
    </tr>
    <tr>
      <td class="td-label"style="width:20%">Dilation</td>
      <td style="width:30%">'.$dilation.'</td>
      <td class="td-label" style="width:20%">Sacral curve</td>
      <td class="td-input" style="width:30%">'.$sacral_curve.'</td>
    </tr>
    <tr>
      <td class="td-label"style="width:20%">Presenting Part</td>
      <td style="width:30%">'.$presenting_part.'</td>
      <td class="td-label" style="width:20%">Lachial Spines</td>
      <td class="td-input" style="width:30%">'.$Lachial_spines.'</td>
    </tr>
    <tr>
      <td class="td-label"style="width:20%">Level</td>
      <td style="width:30%">'.$levels.'</td>
      <td class="td-label" style="width:20%">Subpubic Angle</td>
      <td class="td-input" style="width:30%">'.$subpubic_angle.'</td>
    </tr>
    <tr>
      <td class="td-label"style="width:20%">Position</td>
      <td style="width:30%">'.$position.'</td>
      <td class="td-label" style="width:20%">Sacral Tuberosites</td>
      <td class="td-input" style="width:30%">'.$sacral_tuberosites.'</td>
    </tr>
    <tr>
      <td class="td-label"style="width:20%">Moulding</td>
      <td style="width:30%">'.$moulding.'</td>
      <td class="td-label" style="width:20%">Summary:</td>
      <td class="td-input" style="width:30%">'.$summary.'</td>
    </tr>
    <tr>
      <td class="td-label"style="width:20%">Caput</td>
      <td style="width:30%">'.$caput.'</td>
      <td class="td-label" style="width:20%">Membranes/Liquor</td>
      <td class="td-input" style="width:30%">'.$membranes_liquor.'</td>
    </tr>
    <tr>
      <td class="td-label"style="width:30%">Consultants Opinion</td>
      <td style="width:70%" colspan="3">'.$remarks.'</td>
    </tr>
  </table>
  </center>';
  ?>
  <?php 
   while($history = mysqli_fetch_assoc($select_labour_history)){
    $history_year = $history['history_year'];
    $history_complication = $history['history_complication'];
    $gravida_method = $history['gravida_method'];
    $gravida_wt = $history['gravida_wt'];
    $gravida_alive = $history['gravida_alive'];
    $para_year = $history['para_year'];
    $para_complication = $history['para_complication'];
    $living_method = $history['living_method'];
    $living_wt = $history['living_wt'];
    $living_alive = $history['living_alive'];$i=2;
  $htm .='<center>
  <table  border="1" class="table table-bordered" style="width:100%;border-collapse: collapse;">
  <thead>
        <tr>
          <th colspan="10" style="font-weight:bold;height:30px;">HISTORY</th>
        </tr>
      </thead>
        <thead style="background-color:#bdb5ac">
        <!-- bdb5ac -->
        <!-- e6eded -->
        <!-- a3c0cc -->
            <tr>
                <th style="text-align:center;" colspan="2"><b>OBSTETRIC HISTORY</b></th>
                <th style="text-align:center;" colspan="4"><b>GRAVIDA</b></th>
                <th style="text-align:center;" colspan="2"><b>PARA</b></th>
                <th style="text-align:center;" colspan="5"><b>LIVING CHILDREN</b></th>
            </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>YEAR</b></td>
            <td><b>COMPLICATION</b></td>
            <td><b>METHOD</b></td>
            <td><b>WT</b></td>
            <td style="text-align:center;" ><b>ALIVE</b></td>
            <td><b>YEAR</b></td>
            <td><b>COMPLICATIONS</b></td>
            <td><b>METHOD</b></td>
            <td><b>WT</b></td>
            <td style="text-align:center;" ><b>ALIVE</b></td>
          </tr>
          <tr>
            <td>'.$history_year.'</td>
            <td>'.$history_complication.'</td>
            <td>'.$gravida_method.'</td>
            <td>'.$gravida_wt.'</td>
            <td>'.$gravida_alive.'</td>
            <td>'.$para_year.'</td>
            <td>'.$para_complication.'</td>
            <td>'.$living_method.'</td>
            <td>'.$living_wt.'</td>
            <td>'.$living_alive.'</td>
          </tr>
        </tbody>
        </table>
  </center>';
}
  }
    
} else {
  $htm .= mysqli_error($conn);
}

}

?>
<?php
// $mpdf = new mPDF('s', 'A4');
// $mpdf->SetDisplayMode('fullpage');
// $mpdf->WriteHTML($htm);
// $mpdf->Output();
// exit;
$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
// include("./MPDF/mpdf.php");
// $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
// $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
// $mpdf->WriteHTML($htm);
// $mpdf->Output();

?>
