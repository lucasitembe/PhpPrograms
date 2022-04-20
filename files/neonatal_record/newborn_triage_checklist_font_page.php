<?php
include('header.php');
include('../includes/connection.php');
require_once'save_postnatal_record.php';
require_once'triage/assets.php';


if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])) {
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
  $employee_ID = $_GET['Employee_ID'];
}else{
  header("Location:../../index.php");
}

?>

<!-- script links-->
  <script src="triage/scripts.js" charset="utf-8"></script>


<!-- css links -->
  <link rel="stylesheet" href="triage/styles.css"/>

<?php echo Assets::btnNewBornChecklistPerYear($consultation_id,$employee_ID,$registration_id,$admission_id);?>
<!-- Func for Back Button -->
<?php echo Assets::btnBackToNeonatalRecords($consultation_id,$employee_ID,$registration_id,$admission_id);?>


<!-- Beggining of div container -->
<div class="container">
  <form method="post">
  <fieldset style="width:95%;margin-left:200px;margin-right:100px;">
      <legend align="center" style="font-weight:bold">NEWBORN TRIAGE CHECKLIST</legend>
      <table>

        <!-- ro2 -->
        <tr>
          <td><b>Respiration:</b></td>
          <td >
            <input type="text" name="respirationNo" class="form-control" id="respirationNo"/> &nbsp;<b>or</b><br>
            <input type="radio" name="respiration" id="difficult in breathing" value="difficult in breathing">&nbsp;<b>Difficult in breathing:</b><br>
            <input type="radio" name="difficultInBreathingType" id="grunting" value="grunting">&nbsp;Grunting<br>
            <input type="radio" name="difficultInBreathingType" id="nasalFlaring" value="nasal flaring">&nbsp;Nasal flaring<br>
            <input type="radio" name="difficultInBreathingType" id="chestIndrawing" value="chest indrawing">&nbsp;Chest indrawing<br><br>
            <input type="radio" name="respiration" id="NormalBreathing" value="normal breathing">&nbsp;<b>Normal breathing</b><br><br>
          </td>

          <td><b>Temp:</b></td>
          <td >
            <input type="text" name="temp" class="form-control" id="temp" required>
            <input type="hidden" name="Registration_ID" class="form-control" id="Registration_ID" value="<?php echo $registration_id;?>"/><br>
            <input type="hidden" name="Employee_ID" class="form-control" id="Employee_ID" value="<?php echo $employee_ID;?>"/><br>
            <input type="hidden" name="Admision_ID" class="form-control" id="Admision_ID" value="<?php echo $Admision_ID;?>"/><br>
            <input type="hidden" name="consultation_id" class="form-control" id="consultation_id" value="<?php echo $consultation_id;?>"/><br>
            <br><br>
            <b>Comment:</b><br>
              <textarea class="form-control" rows="2" id="comment"></textarea>
          </td>

          <td><b>Skin & Circulation:</b></td>
          <td>
            <input type="radio" name="skinCirculation" id="centralCyanosis" value="central cyanosis">&nbsp;Central cyanosis<br>
            <input type="radio" name="skinCirculation" id="pallor" value="pallor">&nbsp;Pallor<br>
            <input type="radio" name="skinCirculation" id="capillaryRefillG" value="capillary refill >3sec">&nbsp;Capillary refill >3sec<br>
            <input type="radio" name="skinCirculation" id="normalColour" value="normal colour">&nbsp;Normal colour<br>
            <input type="radio" name="skinCirculation" id="capillaryRefillL" value="capillary refill <= 3sec">&nbsp;Capillary refill <= 3sec<br><br>

            <b>PMTCT:</b>&nbsp;
            1<input type="radio" name="pmtct" value="1" id="pmtct1">&nbsp;
            2<input type="radio" name="pmtct" value="2" id="pmtct2"><br><br>

            <b>Delivery Date:</b><input type="text" name="deliveryDateAndTime" id="deliveryDateAndTime" class="form-control input-label date" >
          </td>

        </tr>

        <!-- row3 -->
        <tr>
          <td><b>Movements:</b></td>
          <td>
            <input type="radio" name="movements" id="nMovementAtAll" value="no movement at all">&nbsp;No movement at all<br>
            <input type="radio" name="movements" id="movementOnlyStimulated" value="movement only stimulated">&nbsp;Movement only stimulated<br>
            <input type="radio" name="movements" id="normalMovement" value="normal movement">&nbsp;Normal movement<br>
          </td>

          <td><b>Others:</b></td>
          <td>
            <input type="radio" name="others" id="congenitalMalformation" value="congenital malformation">&nbsp;Congenital malformation<br>
            <input type="radio" name="others" id="convulsion" value="convulsion">&nbsp;Convulsion<br>
            <input type="radio" name="others" id="none" value="none">&nbsp;None<br>
          </td>

            <td><b>Evaluation Stage:</b></td>
            <td>
              <select class="form-control" name="evaluationStage" id="evaluationStage" onchange="selectEvaluation(this.value)">
               <option value="select" selected>--Select--</option>
               <option value="firstEvaluation">1st Evaluation</option>
               <option value="secondEvaluation">2nd Evaluation</option>
               <option value="thirdEvaluation">3rd Evaluation</option>
             </select><br>
             <input type="button" class="btn btn-sm art-button" id="newbornInfo" onclick="saveNewbornTriage(this.value)"  value="Save">
            </td>
        </tr>
        <!-- row4 -->
        <tr>
            <!-- 1st evaluation -->
            <td id="first1" style="display: none;"><b>Maternal Factors:</b></td>
            <td id="firstIn1" style="display: none;">
              <input type="radio" name="maternalFactors" id="prom" value="PROM >18HRS">&nbsp;PROM >18HRS<br>
              <input type="radio" name="maternalFactors" id="foulSmelling" value="Foul smelling amniotic fluid">&nbsp;Foul smelling amniotic fluid<br>
              <input type="radio" name="maternalFactors" id="maternalPyrexia" value="Maternal pyrexia >38.0°C">&nbsp;Maternal pyrexia >38.0°C<br>
              <input type="radio" name="maternalFactors" id="none" value="none">&nbsp;None<br><br>
            </td>

            <td id="first2" style="display: none;"><b>APGAR 5min:</b></td>
            <td id="firstIn2" style="display: none;"><input type="text" name="apgar" class="form-control" id="apgar"/></td>

            <td id="first3" style="display: none;"><b>Birth Weight:</b></td>
            <td id="firstIn3" style="display: none;"><input type="text" name="birthWeight" class="form-control" id="birthWeight"/></td>
            <!-- End of 1st evaluation -->

            <!-- 2nd evaluation -->
            <td id="second1" style="display: none;"><b>Maternal Factors:</b></td>
            <td id="secondIn1" style="display: none;">
              <input type="radio" name="maternalFactors2" id="prom2" value="PROM >18HRS">&nbsp;PROM >18HRS<br>
              <input type="radio" name="maternalFactors2" id="foulSmelling2" value="Foul smelling amniotic fluid">&nbsp;Foul smelling amniotic fluid<br>
              <input type="radio" name="maternalFactors2" id="maternalPyrexia2" value="Maternal pyrexia >38.0°C">&nbsp;Maternal pyrexia >38.0°C<br>
              <input type="radio" name="maternalFactors2" id="none2" value="none">&nbsp;None<br><br><br>
            </td>

            <td id="second2" style="display: none;"><b>Feeding:</b></td>
            <td id="secondIn2" style="display: none;">
              <input type="radio" name="feeding" id="notSucking" value="not sucking">&nbsp;Not sucking<br>
              <input type="radio" name="feeding" id="notSuckingWell" value="not sucking well">&nbsp;Not sucking well<br>
              <input type="radio" name="feeding" id="vomitAfterEachFeed" value="vomit after each feed">&nbsp;Vomit after each feed<br>
              <input type="radio" name="feeding" id="breastFeedingProblem" value="breast feeding problem">&nbsp;Breast feeding problem<br>
              <input type="radio" name="feeding" id="normal" value="normal">&nbsp;Normal<br><br>
            </td>

            <td id="second3" style="display: none;"><b>Umbilicus:</b></td>
            <td id="secondIn3" style="display: none;">
              <input type="radio" name="umbilicus" id="bleeding" value="bleeding">&nbsp;Bleeding<br>
              <input type="radio" name="umbilicus" id="noBleeding" value="no bleeding">&nbsp;No bleeding<br><br>
            </td>
            <!-- End of evaluation -->


            <!-- 3rd evaluation -->
            <td id="third1" style="display: none;"><b>Feeding:</b></td>
            <td id="thirdIn1" style="display: none;">
              <input type="radio" name="feeding2" id="notSucking2" value="not sucking">&nbsp;Not sucking<br>
              <input type="radio" name="feeding2" id="notSuckingWell2" value="not sucking well">&nbsp;Not sucking well<br>
              <input type="radio" name="feeding2" id="vomitAfterEachFeed2" value="vomit after each feed">&nbsp;Vomit after each feed<br>
              <input type="radio" name="feeding2" id="breastFeedingProblem2" value="breast feeding problem">&nbsp;Breast feeding problem<br>
              <input type="radio" name="feeding2" id="normal2" value="normal">&nbsp;Normal<br><br>
            </td>

            <td id="third2" style="display: none;"><b>Umbilicus:</b></td>
            <td id="thirdIn2" style="display: none;">
              <input type="radio" name="umbilicus2" id="bleeding2" value="bleeding">&nbsp;Bleeding<br>
              <input type="radio" name="umbilicus2" id="noBleeding2" value="no bleeding">&nbsp;No bleeding<br><br>
            </td>
            <td id="third3" style="display: none;"><b>Current Weight:</b></td>
            <td id="thirdIn3" style="display: none;"><input type="text" name="currentWeight" class="form-control" id="currentWeight"/></td>
            <!-- End of evaluation-->

        </tr>
      </table>
  </fieldset>
   </form>
</div>
<!-- End of div container -->


  <?php echo Assets::js(); ?>
<?php
    include("../includes/footer.php");
?>
