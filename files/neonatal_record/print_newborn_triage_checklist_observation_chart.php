<?php
include("../includes/connection.php");
include("../MPDF/mpdf.php");



if ($_GET['Registration_ID']) {
  $registration_id = $_GET['Registration_ID'];
  $employee_ID = $_GET['Employee_ID'];
  $delivery_year = $_GET['delivery_year'];

}


$htm = '<link rel="stylesheet" href="triage/styles.css"/>';
$htm .= "<center><img src='../branchBanner/branchBanner.png' width='100%' ></center>";

$htm .= '<fieldset>
  <legend>Observation chart for newborns AT RISK – 6hourly check-up for 48hours</legend>
  <table border="1" class="table table-striped">
    <tr>
      <th>Hours after start of observation</th>
      <th>Time of check-up</th>
      <th>Temp (°C)</th>
      <th>Respiration (/min)</th>
      <th>Feeding</th>
      <th>Movements</th>
      <th>Weight (every 24hours)</th>
      <th>Checked by(name)</th>
      <!-- <th>Admit at/Referral to facility with NCU, if:</th> -->
    </tr>';

      $sql_employee_name3= mysqli_query($conn,"SELECT n.Employee_ID,n.Registration_ID,e.Employee_Name,e.Employee_ID FROM tbl_newborn_triage_checklist_observation n INNER JOIN tbl_employee e ON n.Employee_ID = e.Employee_ID WHERE n.Registration_ID ='$registration_id' AND  YEAR(saved_time) = '$delivery_year'");
      $employee_Name3 = mysqli_fetch_assoc($sql_employee_name3)['Employee_Name'];

      $sql_birth_weight= mysqli_query($conn,"SELECT birth_weight FROM tbl_newborn_triage_checklist_records   WHERE Registration_ID ='$registration_id' AND  YEAR(saved_time) = '$delivery_year'");
      $birth_weight = mysqli_fetch_assoc($sql_birth_weight)['birth_weight'];


        $sql_alldata = mysqli_query($conn,"SELECT * FROM tbl_newborn_triage_checklist_observation WHERE Registration_ID ='$registration_id' AND YEAR(saved_time) = '$delivery_year'");
        $sn = 0;
        while ($r = mysqli_fetch_assoc($sql_alldata)) {
          $comment = $r['comment'];
          $temp = $r['temperature'];
          $saved_time = $r['saved_time'];
          $respiration = $r['respiration'];
          $feeding = $r['feeding'];
          $movements = $r['movements'];
          $weight = $r['weight'];



          $htm .= "<tr>";
          $htm .= "<td style='font-size: 14px;font-weight: bold;'><b>".$sn." hours</b></td>";
          $htm .= "<td style='font-size: 14px;font-weight: bold;'>".$saved_time."</td>";

          //temperature
          if ($temp < 36 || $temp > 37.5) {
            $htm .= "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$temp."</td>";
          }else{
            $htm .= "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$temp."</td>";
          }


          //respiration
          if ($respiration < 30 || $respiration > 60) {
            $htm .= "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$respiration."</td>";
          }else {
            $htm .= "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$respiration."</td>";
          }

          //feeding
          if ($feeding == 'not well') {
            $htm .= "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$feeding."</td>";
          }else {
            $htm .= "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$feeding."</td>";
          }

          //movements
          if ($movements == 'abnormal') {
            $htm .= "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$movements."</td>";
          }else {
            $htm .= "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$movements."</td>";
          }



          //weight

          if ($weight > $birth_weight) {
                $htm .= "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$weight."</td>";
          }elseif($weight < $birth_weight && $weight !=0)
          {
            $birth_weight_p = 100;
            $weight_p = ($weight*$birth_weight_p)/$birth_weight;
            $weight_loss_P = $birth_weight_p - $weight_p;

            //check if current weight loss > 10% of birth weigth
            if($weight_loss_P >  10)
            {
              $htm .= "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$weight."</td>";
            }
            else {
                $htm .= "<td style='background-color:#ff4d4d;font-size: 14px;font-weight: bold;'>".$weight."</td>";
            }

          }
          else {
            $htm .= "<td style='background-color:#b4b4b4; font-size: 14px;font-weight: bold;'>".$weight."</td>";
          }

          $htm .= "<td style='font-size: 14px;font-weight: bold;'>".$employee_Name3."</td>";
          $htm .= "</tr>";
          $sn = $sn + 5;
          $sn++;
        }

    
      $htm .=' <tr>
         <td colspan="8"= style=\'background-color:#ff3333;font-size: 16px;font-weight: bold;\'>
             <h4 style=\'color:white;font-weight: bold;\'>Admit at/Referral to facility with NCU, if:</h4>
             Temperature:&nbsp; < 36°C or > 37.5°C&nbsp;&nbsp;&nbsp;&nbsp;
             Respiration rate:&nbsp;<30/min or >60/min&nbsp;&nbsp;&nbsp;&nbsp;
             Feeding:&nbsp;not well (2)&nbsp;&nbsp;&nbsp;&nbsp;
             Movements:&nbsp;abnormal (2)&nbsp;&nbsp;&nbsp;&nbsp;
             Weight:&nbsp;Weight loss >10% of birth weight&nbsp;&nbsp;
         </td>

       </tr>

       <tr>
         <td colspan="8"= style=\'background-color:yellow;font-size: 16px;font-weight: bold;\'>
             If weight >4kg: check RBG regularly, continue with routine use of NTC card (use of yellow observation chart is not needed in this case)
         </td>
       </tr>
       <tr>
         <td colspan="8"= style=\'font-size: 16px;font-weight: bold;\'>';

           $sql_comment = mysqli_query($conn,"SELECT comment FROM tbl_newborn_triage_checklist_observation WHERE Registration_ID ='$registration_id' AND YEAR(saved_time) = '$delivery_year' ORDER BY saved_time DESC LIMIT 1");
           $comment = mysqli_fetch_assoc($sql_comment)['comment'];
           $htm .= '<b>Comment:</b><br>'.$comment.'';
      $htm .= '</td>
       </tr>
  </table>
</fieldset>';


$mpdf = new mPDF('s', 'A4');

$mpdf->SetDisplayMode('fullpage');
$mpdf->SetFooter('Printed By '.strtoupper($employee_Name3).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;





 ?>
