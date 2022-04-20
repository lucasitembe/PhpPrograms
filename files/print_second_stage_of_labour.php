<?php
include("./includes/connection.php");
include("MPDF/mpdf.php");

if (isset($_GET['patient_id'])) {
  $patientId = $_GET['patient_id'];
    // getPatientDetails($patientId);
}


$htm = "<center><img src='./branchBanner/branchBanner.png'>";
$htm .= "<p style='font-weight:bold; text-align:center;'>SECOND STAGE OF LABOUR</p>";

$today = Date("Y-m-d");
$intact = "";
$rapture = "";
$done = "";
$notDone = "";
$inductionYes = "";
$inductionYes = "";
// select second stage of labour
$select_second_stage_of_labour = "SELECT * FROM tbl_second_stage_of_labour WHERE patient_id='$patientId' AND date_time='$today'";
if ($result_second_stage = mysqli_query($conn,$select_second_stage_of_labour)) {
  while ($row_second_stage = mysqli_fetch_assoc($result_second_stage)) {

    $time_began = $row_second_stage['time_began'];
    $date_of_birth = $row_second_stage['date_of_birth'];
    $duration = $row_second_stage['duration'];
    $mode_of_delivery = $row_second_stage['mode_of_delivery'];
    $drug = $row_second_stage['drugs'];
    $remarks = $row_second_stage['remarks'];
  }
}

$Patient_Name = "";
$Gender = "";
$Guarantor_Name = "";

// function getPatientDetails($patientId)
// {

#get patient details
$sql = "SELECT Patient_Name,Gender,Guarantor_Name 
            FROM tbl_patient_registration tp 
            JOIN  tbl_sponsor ts 
            ON tp.Sponsor_ID=ts.Sponsor_ID
            WHERE Registration_ID='$patientId'";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
while ($row = mysqli_fetch_assoc($result)) {
  extract($row);
  $Patient_Name = $Patient_Name;
  $Gender = $Gender;
  $Guarantor_Name = $Guarantor_Name;
}
// }
?>

<?php 
$htm .= '<style media="screen">
table{
  border:none !important;
}
table tr,td{
  border:none !important;
}
  .input-label{
    text-align:right !important;
    width: 10%;
  }
  .input{
    width: 20%;
  }
</style>
<center>
<fieldset>
  <legend align=center>
    <div style="text-align:center;height:34px;margin:0px;padding:0px;font-weight:bold">
    <p style="color:#36A8FF;margin:0px;padding:0px; "><span style="margin-right:3px;">' . $Patient_Name . '|</span><span style="margin-right:3px;">' . $Gender . ' |</span> <span style="margin-right:3px;">' . $age . ' | </span> <span style="margin-right:3px;">' . $Guarantor_Name . '</span> </p>
  </div>
  </legend>
  <form class="" action="" method="post" id="second_stage">
    <center>
    <table width="70%">
    <tr>
      <td class="input-label">Time Begane</td>
      <td class="input">' . $time_began . ' </td>
    </tr>
    <tr>
      <td class="input-label">Date And Time Of Birth</td>
      <td class="input">' . $date_of_birth . '</td>
    </tr>
    <tr>
      <td class="input-label">Duration</td>
      <td class="input">' . $duration . ' </td>
    </tr>
    <tr>
      <td class="input-label">Mode Of Delivery</td>
      <td class="input">' . $mode_of_delivery . '</td>
    </tr>
    <tr>
      <td class="input-label">drugs</td>
      <td class="input">
        ' . $drug . ' </td>
    </tr>
    <tr>
      <td class="input-label">Remarks</td>
      <td class="input">
      ' . $remarks . '
      </td>
    </tr>
    </table>
  </center>
  </form>
  
</fieldset>
</center>';
?>
<?php
$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;


?>
