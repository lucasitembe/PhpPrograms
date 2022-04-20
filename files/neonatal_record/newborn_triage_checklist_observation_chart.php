<?php
include('header.php');
include('../includes/connection.php');
require_once'triage/assets.php';

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])) {
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
  $employee_ID = $_GET['Employee_ID'];
  $delivery_year = $_GET['delivery_year'];
}else{
  header("Location:../../index.php");
}



?>

<!-- script links-->
  <script src="triage/scripts.js" charset="utf-8"></script>


<!-- css links -->
  <link rel="stylesheet" href="triage/styles.css"/>

<?php echo Assets::btnPrintObser($employee_ID,$registration_id,$delivery_year); ?>
<?php echo Assets::btnBackNewBornChecklistPerYearRecords($consultation_id,$employee_ID,$registration_id,$admission_id,$delivery_year); ?>

<div class="container-fluid">

          <form class="form-horizontal" role="form">
          <fieldset>
            <legend>OBSERVATION FORM</legend>
            <div class="form-group">
              <label class="control-label col-sm-2" for="temp">Temp:</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="temp" id="temp" required>
                <input type="hidden" name="Registration_ID"  id="Registration_ID" value="<?php echo $registration_id;?>"/>
                <input type="hidden" name="Employee_ID"  id="Employee_ID" value="<?php echo $employee_ID;?>"/>
                <input type="hidden" name="delivery_year"  id="delivery_year" value="<?php echo $delivery_year;?>"/>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="respiration">Respiration:</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="respiration" value="" id="respiration" required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="weight">Weight:</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="weight" id="weight" placeholder="   Please fill this field after every 24hrs.">

              </div>
            </div>
            <center>
            <div class="radio">
                <label><b>Feeding:</b></label>
                <label><input type="radio" name="feeding" value="well">Well</label>
                <label><input type="radio" name="feeding" value="not well">Not well</label>
            </div>
            <div class="radio">
              <label><b>Movements:</b></label>
                <label><input type="radio" name="movements" value="normal">Normal</label>
                <label><input type="radio" name="movements" value="abnormal">Abnormal</label>
            </div>
          </center>
          <div class="form-group">

          <label class="control-label col-sm-2" for="comment">Comment:</label>
          <div class="col-sm-6">
          <textarea class="form-control" rows="2" name="comment" id="comment"></textarea>
        </div>
          </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
              <input type="button" class="btn btn-sm art-button" id="observationInfo" onclick="saveObservationDetails(this.value)"  value="Save">
              </div>
            </div>
          </fieldset>
        </form><br><br>
        <!-- end of form -->


        <fieldset>
          <legend>Observation chart for newborns AT RISK – 6hourly check-up for 48hours</legend>
          <table class="table table-striped">
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
            </tr>

            <!-- row1 -->

              <?php
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



                  echo "<tr>";
                  echo "<td style='font-size: 14px;font-weight: bold;'><b>".$sn." hours</b></td>";
                  echo "<td style='font-size: 14px;font-weight: bold;'>".$saved_time."</td>";

                  //temperature
                  if ($temp < 36 || $temp > 37.5) {
                    echo "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$temp."</td>";
                  }else{
                    echo "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$temp."</td>";
                  }


                  //respiration
                  if ($respiration < 30 || $respiration > 60) {
                    echo "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$respiration."</td>";
                  }else {
                    echo "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$respiration."</td>";
                  }

                  //feeding
                  if ($feeding == 'not well') {
                    echo "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$feeding."</td>";
                  }else {
                    echo "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$feeding."</td>";
                  }

                  //movements
                  if ($movements == 'abnormal') {
                    echo "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$movements."</td>";
                  }else {
                    echo "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$movements."</td>";
                  }



                  //weight

                  if ($weight > $birth_weight) {
                        echo "<td style='background-color:#4ce600; font-size: 14px;font-weight: bold;'>".$weight."</td>";
                  }elseif($weight < $birth_weight && $weight !=0)
                  {
                    $birth_weight_p = 100;
                    $weight_p = ($weight*$birth_weight_p)/$birth_weight;
                    $weight_loss_P = $birth_weight_p - $weight_p;

                    //check if current weight loss > 10% of birth weigth
                    if($weight_loss_P >  10)
                    {
                      echo "<td style='background-color:#ff3333;font-size: 14px;font-weight: bold;'>".$weight."</td>";
                    }
                    else {
                        echo "<td style='background-color:#ff4d4d;font-size: 14px;font-weight: bold;'>".$weight."</td>";
                    }

                  }
                  else {
                    echo "<td style='background-color:#b4b4b4; font-size: 14px;font-weight: bold;'>".$weight."</td>";
                  }

                  echo "<td style='font-size: 14px;font-weight: bold;'>".$employee_Name3."</td>";
                  echo "</tr>";
                  $sn = $sn + 5;
                  $sn++;
                }

               ?>

               <tr>
                 <td colspan="8"= style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>
                     <h4 style='color:white;font-weight: bold;'>Admit at/Referral to facility with NCU, if:</h4>
                     Temperature:&nbsp; < 36°C or > 37.5°C&nbsp;&nbsp;&nbsp;&nbsp;
                     Respiration rate:&nbsp;<30/min or >60/min&nbsp;&nbsp;&nbsp;&nbsp;
                     Feeding:&nbsp;not well (2)&nbsp;&nbsp;&nbsp;&nbsp;
                     Movements:&nbsp;abnormal (2)&nbsp;&nbsp;&nbsp;&nbsp;
                     Weight:&nbsp;Weight loss >10% of birth weight&nbsp;&nbsp;
                 </td>

               </tr>

               <tr>
                 <td colspan="8"= style='background-color:yellow;font-size: 16px;font-weight: bold;'>
                     If weight >4kg: check RBG regularly, continue with routine use of NTC card (use of yellow observation chart is not needed in this case)
                 </td>
               </tr>
               <tr>
                 <td colspan="8"= style='font-size: 16px;font-weight: bold;'>
                   <?php
                   $sql_comment = mysqli_query($conn,"SELECT comment FROM tbl_newborn_triage_checklist_observation WHERE Registration_ID ='$registration_id' AND YEAR(saved_time) = '$delivery_year' ORDER BY saved_time DESC LIMIT 1");
                   $comment = mysqli_fetch_assoc($sql_comment)['comment'];
                   echo $comment; ?>
                 </td>

               </tr>

          </table>
        </fieldset>

</div>


<?php
    include("../includes/footer.php");
?>
