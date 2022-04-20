<?php
include("./includes/connection.php");
include("MPDF/mpdf.php");
session_start();
if ($_SESSION['userinfo']) {
    $E_Name = $_SESSION['userinfo']['Employee_Name'];

}

if (isset($_GET['patient_id'])) {
    $patientId = $_GET['patient_id'];
    // getPatientDetails($patientId);
}


$htm = "<center><img src='./branchBanner/branchBanner.png'>";

$today = Date("Y-m-d");
// get patient details
if (isset($_GET['patient_id']) && $_GET['patient_id'] != 0) {
    $select_patien_details = mysqli_query($conn,"
          SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
              FROM
                  tbl_patient_registration pr,
                  tbl_sponsor sp
              WHERE
                  pr.Registration_ID = '" . $patientId . "' AND
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

$complete = "";
$incomplete = "";
$select_third_stage = "SELECT * FROM tbl_third_stage_of_labour
                      WHERE patient_id='$patientId'
                      ORDeR BY  DATE(date_and_time) ASC LIMIT 1";

if ($result_third_stage = mysqli_query($conn,$select_third_stage)) {
    while ($row_third_stage = mysqli_fetch_assoc($result_third_stage)) {

        $method_of_delivery_of_placenter = $row_third_stage['methodology_delivery_placenter'];
        $date_and_time = $row_third_stage['date_and_time'];
        $duration = $row_third_stage['duration'];
        $placenta_weight = $row_third_stage['placenter_weight'];
        $colour = $row_third_stage['colour'];
        $cord = $row_third_stage['cord'];
        $state_of_cervix = $row_third_stage['state_of_cervix'];
        $episiotomy_tear = $row_third_stage['episiotomy_tear'];
        $repaired_with_suture = $row_third_stage['repaired_with_sutures'];
        $total_blood_loss = $row_third_stage['total_blood_loss'];
        $lochia = $row_third_stage['lochia'];
        $state_of_uterus = $row_third_stage['state_of_uterus'];
        $t = $row_third_stage['t'];
        $p = $row_third_stage['p'];
        $bp = $row_third_stage['bp'];
        $r = $row_third_stage['r'];
        $disposal = $row_third_stage['disposal'];
        $membrane = $row_third_stage['membranes'];
        $stage_of_placent = $row_third_stage['stage_of_placent'];
        $remarks = $row_third_stage['remarks'];
    }

    if ($stage_of_placent == "Complete") {
        $complete = "checked";
    } else if ($stage_of_placent == "Incomplete") {
        $icomplete = "checked";
    }

    if ($membrane == "Complete") {
        $membraneComplete = "checked";
    } else if ($membrane == "Incomplete") {
        $membraneIncomlete = "checked";
    }
    if ($disposal == "LAB") {
        $disposalLab = "checked";
    } else if ($disposal == "Incinerator") {
        $disposalInc = "checked";
    }
}

$membraneComplete = "";
$membraneIncomlete = "";

$htm .= '<br />
<br />
<style media="screen">
table {
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid black;
}
</style>
<center>
<fieldset>
  <legend align=center>
    <div style="text-align:center;height:34px;margin:0px;padding:0px;font-weight:bold">
    <p style="margin:0px;padding:0px;">Third Stage Of Labour</p>
    <p style="color:#3385CC;margin:0px;padding:0px; "><span style="margin-right:3px;">' . $Patient_Name . '|</span><span style="margin-right:3px;">' . $Gender . '|</span> <span style="margin-right:3px;">' . $age . '| </span> <span style="margin-right:3px;">' . $Guarantor_Name . '</span> </p>
  </div>
  </legend>
  <form class="" action="" method="post" id="third_stage" onsubmit="return validateForm();">
    <center>
    <table width="90%" border="1">
    <tr>
      <td class="input-label"><b>Method of Delivery Of The Placenter:</b></td>
      <td class="input">
        ' . $method_of_delivery_of_placenter . '
      </td>
      <td class="input-label"><b>Date And Time:</b> </td>
      <td class="input">' . $date_and_time . '</td>

      <td class="input-label"><b>Duration:</b></td>
      <td class="input">' . $duration . '</td>
    </tr>
    <tr>
      <td class="input-label"><b>Placenta Weight:</b></td>
      <td class="input">' . $placenta_weight . '</td>

      <td class="input-label"><b>Stage Of Placent:</b></td>
      <td class="input">
      <input type="text" name="stage_of_placent" value="'.$stage_of_placent.'">
      </td>

      <td class="input-label"><b>Colour:</b></td>
      <td class="input">' . $colour . ' </td>
    </tr>
    <tr>
      <td class="input-label"><b>Cord:</b></td>
        <td class="input">' . $cord . ' </td>

        <td class="input-label"><b>Membrane</b></td>
        <td class="input">

        <input type="text" name="membrane" value="'.$membrane.'">
       </td>


        <td class="input-label"><b>Disposal</b></td>
        <td>
          <input type="text" name="disposal" value="'.$disposal.'">
        </td>
      </tr>
      <tr>
        <td class="input-label"><b>State Of cervix</b></td>
        <td>' . $stage_of_placent . '</td>

        <td class="input-label"><b>Episiotomy/Tear</b></td>
        <td>
        ' . $episiotomy_tear . '</td>

        <td class="input-label"><b>Repaired With satures</b></td>
        <td>' . $repaired_with_suture . '</td>
      </tr>
      <tr>
        <td class="input-label"><b>Total Blood Loss:</b></td>
        <td>' . $total_blood_loss . '</td>

        <td class="input-label"><b>Lochia:</b></td>
        <td>' . $lochia . ' </td>

        <td class="input-label"><b>state_of_uterus:</b></td>
        <td>
          ' . $state_of_uterus . '
         </td>
      </tr>

      <tr>

        <td class="input-label-inside"><b>Post Delivery Observation:</b></td>
        <td class="input-label-inside">T</td>
        <td>' . $t . '</td>

        <td class="input-label-inside">p</td>
        <td>' . $p . '</td>

        <td class="input-label-inside">R</td>
        <td>' . $r . '</td>

        <td class="input-label-inside">BP</td>
        <td>' . $bp . '</td>
        <!-- </tr>
        </table>
        </td> -->
        </tr>


      <tr>
      <td class="input-label"><b>Remarks:</b></td>
      <td colspan="3" class="input">' . $remarks . '</td>
    </tr>

    </table>
  </center>
  </form>
</fieldset>
</center>';

$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;


?>
