<?php
include("./includes/header.php");
include("./includes/connection.php");
if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admission_id'])) {
  $admision_id = $_GET['admission_id'];
}
if (isset($_GET['Registration_ID'])) {
  $patient_id = $_GET['Registration_ID'];
}

if (isset($_GET['Employee_ID'])) {
  $Employee_ID = $_GET['Employee_ID'];
}

// get patient details
if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
  $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $patient_id . "' AND
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
$time_now=mysqli_query($conn,"SELECT CURDATE()");
$age = date_diff(date_create($DOB), date_create('today'))->y;
?>
<script type="text/javascript" src="js/DataTables/angular.js"></script>

<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID; ?>&patient_id=<?= $patient_id; ?>&admision_id=<?= $admision_id ?>" class="art-button-green">BACK</a>

<!-- main div -->
<div ng-app="myApp" ng-controller="myCtl" class="container-fluid">
  <center>
    <fieldset>
      <legend style="font-weight:bold"align=center>
        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
          <p style="margin:0px;padding:0px;">General examination form</p>
          <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
        </div>
    </legend>
<!-- {{title}} -->
<!-- form div -->
<div ng-show="formDiv">

    <form role="form" class="form-horizontal" name="genrelForm">
      <fieldset>
        <legend style="font-weight:bold"align=left>On Examination</legend>
        <div class="radio">
          <b style="font-size:15px;">Pale</b>
          <label><input type="radio" ng-model="Pale" value="yes">Yes</label>
          <label><input type="radio" ng-model="Pale" value="no">No</label>
          <input type="hidden" ng-model="Registration_ID" id="Registration_ID" value="<?= $patient_id; ?>">
          <input type="hidden" ng-model="Employee_ID" id="Employee_ID" value="<?= $Employee_ID; ?>">
          <input type="hidden" ng-model="consultation_id" id="consultation_id" value="<?= $consultation_id; ?>">
          <input type="hidden" ng-model="admission_id" id="admission_id" value="<?= $admision_id; ?>">
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

          <b style="font-size:15px;">Jaundice</b>
          <label><input type="radio" ng-model="Jaundice" value="yes">Yes</label>
          <label><input type="radio" ng-model="Jaundice" value="no">No</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

          <b style="font-size:15px;">Oedema</b>
          <label><input type="radio" ng-model="Oedema" value="yes">Yes</label>
          <label><input type="radio" ng-model="Oedema" value="no">No</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

          <b style="font-size:15px;">Dyspnocic</b>
          <label><input type="radio" ng-model="Dyspnocic" value="yes">Yes</label>
          <label><input type="radio" ng-model="Dyspnocic" value="no">No</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

        </div>



      </fieldset>
      <!-- end of On Examination -->
      <hr>
      <fieldset>
        <legend style="font-weight:bold"align=center>ABDOMINAL EXAMINATION</legend>




        <fieldset>
          <legend style="font-weight:bold"align=left>Inspection</legend>
          <b style="font-size:15px;">Shape of abdomen:</b>
          <label><input type="radio" ng-model="Shape_of_abdomen" value="round">round</label>
          <label><input type="radio" ng-model="Shape_of_abdomen" value="oval">oval</label>
          <label><input type="radio" ng-model="Shape_of_abdomen" value="pendulum abdomen">pendulum abdomen</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

          <b style="font-size:15px;">Previous scar:</b>
          <label><input type="radio" ng-model="Previous_scar" value="yes">Yes:--->
            <input type="text" ng-model="Previous_scar_yes_times" id="Previous_scar_yes_times" placeholder="times">
          </label>
          <label><input type="radio" ng-model="Previous_scar" value="no">No</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

          <b style="font-size:15px;">Bundles ring:</b>
          <label><input type="radio" ng-model="Bundles_ring" value="yes">Yes</label>
          <label><input type="radio" ng-model="Bundles_ring" value="no">No</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

          <b style="font-size:15px;">Fetal movement:</b>
          <label><input type="radio" ng-model="Fetal_movement" value="yes">Yes</label>
          <label><input type="radio" ng-model="Fetal_movement" value="no">No</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;


        <b style="font-size:15px;">Skin changes:</b>
        <label><input type="radio" ng-model="Skin_hanges" value="yes">Yes</label>
        <label><input type="radio" ng-model="Skin_hanges" value="no">No</label>
        &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;
      </fieldset>
        <!-- end of Inspection -->


        <fieldset>
          <legend style="font-weight:bold"align=left>Palpation</legend>
          <div class="form-group">
            <label class="control-label col-sm-2" for="FH">FH:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" ng-model="FH" id="FH" placeholder="Enter cm">
            </div>
          </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="Number_of_fetus">Number of fetus:</label>
          <div class="col-sm-4">
          <label><input type="radio" ng-model="Number_of_fetus" value="Single fetus">Single fetus</label>
          <label><input type="radio" ng-model="Number_of_fetus" value="More than fetus">More than fetus</label>
        </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="Lie">Lie:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" ng-model="Lie" id="Lie" placeholder="Enter Lie">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="Presentation">Presentation:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" ng-model="Presentation" id="Presentation" placeholder="Enter presentation">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="Position">Position:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" ng-model="Position" id="Position" placeholder="Enter position">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="Head_level">Head level:</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" ng-model="Head_level" id="Head_level" placeholder="Enter head level">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="Contraction">Contraction:</label>
          <div class="col-sm-4">
          <label><input type="radio" ng-model="Contraction" value="Mild">Mild</label>
          <label><input type="radio" ng-model="Contraction" value="Moderate">Moderate</label>
          <label><input type="radio" ng-model="Contraction" value="Strong">Strong</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;
        </div>
        </div>

        </fieldset>
        <!-- end of Palpation -->


        <fieldset>
          <legend style="font-weight:bold"align=left>Auscultation</legend>
          <div class="form-group">
            <label class="control-label col-sm-2" for="FHR">FHR:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" name="FHR" ng-model="FHR" id="FHR" placeholder="Enter b/min" required>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2" for="FH">Auscultation:</label>
            <div class="col-sm-4">
            <label><input type="radio" ng-model="Auscultation_strength" value="Strong">Strong</label>
            <label><input type="radio" ng-model="Auscultation_strength" value="Weak">Weak</label><br>
            <label><input type="radio" ng-model="Auscultation_shape" value="Regular">Regular</label>
            <label><input type="radio" ng-model="Auscultation_shape" value="Irregular">Irregular</label>
            &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;
          </div>
          </div>

        </fieldset>
        <!-- end of Auscultation -->



      </fieldset>
      <!-- end abdominal examination -->

      <fieldset>
        <legend style="font-weight:bold"align=center>PV EXAMINATION</legend>
        <div class="form-group">
          <label class="control-label col-sm-2" for="State_of_vulva">State of vulva:</label>
          <div class="col-sm-4">
          <label><input type="radio" ng-model="State_of_vulva" value="Oedema">Oedema</label>
          <label><input type="radio" ng-model="State_of_vulva" value="Warts">Warts</label>
          <label><input type="radio" ng-model="State_of_vulva" value="FGM">FGM</label>
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;
          &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

          <label><input type="hidden" ng-model="Pv_discharge" value="Pv discharge">Pv discharge</label>
          <label><input type="radio" ng-model="Pv_discharge" value="no">No</label>
          <label><input type="radio" ng-model="Pv_discharge" value="yes">Yes:---></label>
          <!-- <label><input type="checkbox" ng-model="State_of_vulva_Colour" value="Colour">Colour</label> -->
         </div>
         <div class="col-sm-2">
           <input type="text" class="form-control" ng-model="Pv_discharge_yes_colour" id="Pv_discharge_yes_colour" placeholder=" colour"><br>
           <input type="text" class="form-control" ng-model="Pv_discharge_yes_smell" id="Pv_discharge_yes_smell" placeholder=" smell">
         </div>
       </div>

         <div class="form-group">
           <label class="control-label col-sm-2" for="State_of_vagina">State of vagina:</label>
           <div class="col-sm-4">
           <label><input type="radio" ng-model="State_of_vagina" value="Moist and warm">Moist and warm</label>
           <label><input type="radio" ng-model="State_of_vagina" value="Dry and hot">Dry and hot</label>
         </div>
         </div>

         <div class="form-group">
           <label class="control-label col-sm-2" for="State_of_Cx">State of Cx:</label>
           <div class="col-sm-4">
           <label><input type="radio"  ng-model="State_of_Cx_Soft" value="Soft">Soft</label>
           <label><input type="radio"  ng-model="State_of_Cx_Rigid" value="Rigid">Rigid</label>
           <label><input type="radio"  ng-model="State_of_Cx_Thin" value="Thin">Thin</label>
           <label><input type="radio"  ng-model="State_of_Cx_Thick" value="Thick">Thick</label>
           <label><input type="radio"  ng-model="State_of_Cx_Swollen" value="Swollen">Swollen</label>
         </div>
         </div>

         <div class="form-group">
           <label class="control-label col-sm-2" for="Cervix">Cervix:</label>

           <div class="col-sm-2">
             <input type="text" class="form-control" ng-model="Cervical_dilatation" id="Cervical_dilatation" placeholder=" Cervical dilatation">
           </div>

           <label class="control-label col-sm-2" for="Affacement">Affacement:</label>
           <div class="col-sm-4">
           <label><input type="radio" ng-model="Affacement" value="Affaced"  id="Affacement">Affaced</label>
           <label><input type="radio" ng-model="Affacement" value="Not affaced"  id="Affacement">Not affaced</label>
          </div>

        </div>


        <div class="form-group">
          <label class="control-label col-sm-2" for="Membranes">Membranes:</label>


          <div class="col-sm-4" id="Affacement">
          <label><input type="radio" ng-model="Membranes" value="Intact">Intact</label>
          <label><input type="radio" ng-model="Membranes" value="Rupture">Rupture:---></label>
         </div>
         <label class="control-label col-sm-2" for="Membranes_Date_and_Time">date and time:</label>
         <div class="col-sm-2">
           <input type="text" class="form-control date" ng-model="Membranes_Date_and_Time" id="Membranes_Date_and_Time">
         </div><br><br>

         <label class="control-label col-sm-2" for="State_of_liquor">state of liquor:</label>
         <div class="col-sm-2">
           <input type="text" class="form-control" ng-model="liquor_colour" id="liquor_colour" placeholder="colour"><br>
           <input type="text" class="form-control" ng-model="liquor_smell" id="liquor_smell" placeholder="smell">
         </div>

       </div>

       <div class="form-group">
         <label class="control-label col-sm-2" for="Presenting_part">Presenting part:</label>
         <div class="col-sm-4">
         <label><input type="radio" ng-model="Presenting_part" value="Vertex">Vertex</label>
         <label><input type="radio" ng-model="Presenting_part" value="Blow">Blow</label>
         <label><input type="radio" ng-model="Presenting_part" value="Face">Face</label>
         <label><input type="radio" ng-model="Presenting_part" value="Breech">Breech:---></label>
         &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp;&nbsp; &nbsp;&nbsp;

         <label><input type="hidden" ng-model="Breech">type:</label>
        </div>
        <div class="col-sm-2">
          <input type="text" class="form-control" ng-model="Breech_type" id="Breech_type" placeholder=" breech type"><br>

        </div>
      </div>

      </fieldset>
      <!-- end PV Examination -->

      <fieldset>
        <legend style="font-weight:bold"align=center>PELVIS ASSESSMENTS</legend>
        <div class="form-group">
          <label class="control-label col-sm-2" for="Sacro_promontory">Sacro promontory:</label>
          <div class="col-sm-4">
          <label><input type="radio" ng-model="Sacro_promontory" value="Reached">Reached</label>
          <label><input type="radio" ng-model="Sacro_promontory" value="Not reached">Not reached</label>
         </div>
       </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="Sacral_curve">Sacral curve:</label>
          <div class="col-sm-4">
          <label><input type="radio" ng-model="Sacral_curve" value="Normal">Normal</label>
          <label><input type="radio" ng-model="Sacral_curve" value="Flat">Flat</label>
         </div>
       </div>

      <div class="form-group">
        <label class="control-label col-sm-2" for="Ischial_spine">Ischial spine:</label>
        <div class="col-sm-4">
        <label><input type="radio" ng-model="Ischial_spine" value="Brunt">Brunt</label>
        <label><input type="radio" ng-model="Ischial_spine" value="Prominent">Prominent</label>
       </div>
     </div>

     <div class="form-group">
       <label class="control-label col-sm-2" for="Pubic_angle">Pubic angle:</label>
       <div class="col-sm-4">
       <label><input type="radio" ng-model="Pubic_angle" value="90">90</label>
       <label><input type="radio" ng-model="Pubic_angle" value="< 90">< 90</label>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2" for="Knuckles">Knuckles :</label>
      <div class="col-sm-4">
      <label><input type="radio" ng-model="Knuckles" value="4 knuckles">4 knuckles</label>
      <label><input type="radio" ng-model="Knuckles" value="< 4 knuckles"> < 4 knuckles</label>
     </div>
   </div>

   <div class="form-group">
     <label class="control-label col-sm-2" for="Remark">Remark:</label>
     <div class="col-sm-4">
     <label><input type="radio" ng-model="Remark" value="Adquate pelvis">Adquate pelvis</label>
     <label><input type="radio" ng-model="Remark" value="Inadquate pelvis">Inadquate pelvis</label>
    </div>
  </div>
  <hr>
  <div class="form-group">
   <label class="control-label col-sm-2" for="Opinions">Midwifery Opinions:</label>
   <div class="col-sm-4">
   <textarea class="form-control"  rows="5" ng-model="Midwifery_Opinions" id="Midwifery_Opinions"></textarea>
   </div>
  </div>

  <div class="form-group">
   <label class="control-label col-sm-2" for="Plans">Plans:</label>
   <div class="col-sm-4">
   <textarea class="form-control" rows="5" ng-model="Plans" id="Plans"></textarea>
   </div>
  </div>
  <span style="background-color:green;color:white;font-size:16px;">{{success}}</span>
    <input type="button" class="art-button-green" ng-hide="!genrelForm.$valid" ng-click="saveGeneralExamination()" value="Save">
    <input type="button" class="art-button-green" ng-hide="genrelForm.$valid" ng-click="showTable()" value="Preview">
      </fieldset>
      <!-- end PELVIS ASSESSMENTS -->
    </form>
</div>
<!-- end of form div -->

<!-- table div -->
<div ng-show="tableDiv">
  <!-- start row -->
  <div class="row">
    <div class="col-md-8">

    </div>
    <div class="col-md-4">
      <input type="button" class="art-button-green"  ng-click="showForm()" value="Form">
      <!-- <input type="button" class="art-button-green"  ng-click="showLastYearTable()" value="Refres"> -->
    </div>
  </div>
  <!-- end row -->


    <fieldset>
      <legend>GENEREL EXAMINATION DETAILS</legend>
      <table class="table" id="refreshTb">
        <th>#</th>
        <th>ALL YEARS</th>

        <?php
          $sn = 1;
          $select_year = mysqli_query($conn,"SELECT DISTINCT(YEAR(saved_time)) as 'year' FROM tbl_general_examination WHERE  YEAR(saved_time) !='0' and  Registration_ID = $patient_id");

          while($y = mysqli_fetch_assoc($select_year))
          {
             $year = $y['year'];
              echo "
              <tr>
                  <td>".$sn."</td>
                  <td><input type='button' id='year' class='btn btn-link' ng-click='getYear()' value='".$year."' data-toggle=\"modal\" data-target=\"#myModal\"></td>
              </tr>";
                $sn++;
          }

         ?>

      </table>

      <!-- ************************Modal*************************************************** -->
      <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color:#037CB0;font-weight:bold;color:white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="font-weight:bold;color:white;">GENERAL EXAMINATION DETAILS</h4>
        </div>
        <div class="modal-body" ing-init="getGeneralExamination()">
          <!-- body -->
          <div ng-repeat="g in generals">
          <!-- On Examination -->
          <fieldset>
            <legend style="font-weight:bold"align=left>On Examination</legend>
            <div class="row">
              <div class="col-md-3"><b>Pale</b>: {{g.Pale}}</div>
              <div class="col-md-3"><b>Jaundice</b>: {{g.Jaundice}}</div>
              <div class="col-md-3"><b>Oedema</b>: {{g.Oedema}}</div>
              <div class="col-md-3"><b>Dyspnocic</b>: {{g.Dyspnocic}}</div>
            </div>
          </fieldset><br>

          <!-- Inspection -->
          <fieldset>
            <legend style="font-weight:bold"align=left>Inspection</legend>
            <div class="row">
              <div class="col-md-6"><b>Shape of abdomen</b>: {{g.Shape_of_abdomen}}</div>
              <div class="col-md-3"><b>Previous scar</b>: {{g.Previous_scar}}</div>
              <div class="col-md-3"><b>time</b>: {{g.Previous_scar_yes_times}}</div>
            </div><br>
            <hr>
            <div class="row">
              <div class="col-md-3"><b>Bundles ring</b>: {{g.Bundles_ring}}</div>
              <div class="col-md-3"><b>Fetal movement</b>: {{g.Fetal_movement}}</div>
              <div class="col-md-3"><b>Skin changes</b>: {{g.Skin_hanges}}</div>
            </div>
          </fieldset><br>

          <!-- Palpation -->
          <fieldset>
            <legend style="font-weight:bold"align=left>Palpation</legend>
            <div class="row">
              <div class="col-md-6"><b>FH</b>: {{g.FH}}</div>
              <div class="col-md-3"><b>No of Fetus</b>: {{g.Number_of_fetus}}</div>
              <div class="col-md-3"><b>Lie</b>: {{g.Lie}}</div>
              <div class="col-md-3"><b>Presentation</b>: {{g.Presentation}}</div>
            </div><br>
            <hr>
            <div class="row">
              <div class="col-md-3"><b>Position</b>: {{g.Position}}</div>
              <div class="col-md-3"><b>Head level</b>: {{g.Head_level}}</div>
              <div class="col-md-3"><b>Contraction</b>: {{g.Contraction}}</div>
            </div>
          </fieldset><br>

          <!-- Auscultation -->
          <fieldset>
            <legend style="font-weight:bold"align=left>Auscultation</legend>
            <div class="row">
              <div class="col-md-3"><b>FHR</b>: {{g.FHR}}</div>
              <div class="col-md-3">{{g.Auscultation_strength}}</div>
              <div class="col-md-3">{{g.Auscultation_shape}}</div>
            </div>
          </fieldset><br>

          <!-- PV EXAMINATION -->
          <fieldset>
            <legend style="font-weight:bold"align=left>PV EXAMINATION</legend>
            <div class="row">
              <div class="col-md-3"><b>State of vulva</b>: {{g.State_of_vulva}}</div>
            </div><br>
            <hr>
            <div class="row">
              <div class="col-md-3"><b>Pv discharge</b>: {{g.Pv_discharge}}</div>
              <div class="col-md-3"><b>colour</b>: {{g.Pv_discharge_yes_colour}}</div>
              <div class="col-md-3"><b>smell</b>: {{g.Pv_discharge_yes_smell}}</div>
            </div><br>
            <hr>
            <div class="row">
              <div class="col-md-3"><b>State of vagina</b>: {{g.State_of_vagina}}</div>
              </div><br>
            <hr>
            <div class="row">
              <div class="col-md-12"><b>State of Cx</b>: {{g.State_of_Cx_Soft}} {{g.State_of_Cx_Rigid}}  {{g.State_of_Cx_Thin}} {{g.State_of_Cx_Thin}} {{g.State_of_Cx_Swollen}} {{g.State_of_Cx_Swollen}}</div>
            </div><br>
            <hr>
            <div class="row">
              <div class="col-md-3"><b>Cervical dilatation</b>: {{g.Cervical_dilatation}}</div>
              <div class="col-md-3"><b>Affacement</b>: {{g.Affacement}}</div>
            </div><br>
            <hr>
            <div class="row">
              <div class="col-md-3"><b>Membranes</b>: {{g.Membranes}}</div>
              <div class="col-md-3"><b>date and time</b>: {{g.Membranes_Date_and_Time}}</div>
              <div class="col-md-3"><b>state of liquor</b>: {{g.Cervical_dilatation}}</div>
              <div class="col-md-3"><b>Affacement</b>: {{g.Affacement}}</div>
            </div><br>
            <hr>
            <div class="row">
              <div class="col-md-3"><b>Presenting part</b>: {{g.Presenting_part}}</div>
              <div class="col-md-3"><b>type</b>: {{g.Breech_type}}</div>
            </div><br>
          </fieldset><br>

          <!-- PELVIS ASSESSMENTS -->
          <fieldset>
            <legend style="font-weight:bold"align=left>PELVIS ASSESSMENTS</legend>
            <div class="row">
              <div class="col-md-3"><b>Sacro promontory</b>: {{g.Sacro_promontory}}</div>
              <div class="col-md-3"><b>Sacral curve</b>: {{g.Sacral_curve}}</div>
              <div class="col-md-3"><b>Ischial spine</b>: {{g.Ischial_spine}}</div>
            </div><br>
            <hr>
            <div class="row">
              <div class="col-md-3"><b>Pubic angle</b>: {{g.Pubic_angle}}</div>
              <div class="col-md-3"><b>Knuckles</b>: {{g.Knucklese}}</div>
              <div class="col-md-3"><b>Remark</b>: {{g.Remark}}</div>
            </div><br>
          </fieldset><br>

          <!-- Midwifery Opinions -->
          <fieldset>
            <legend style="font-weight:bold"align=left>Midwifery Opinions</legend>
            <div class="row">
              <div class="col-md-8">{{g.Midwifery_Opinions}}</div>
            </div>
          </fieldset><br>

          <!-- Plans -->
          <fieldset>
            <legend style="font-weight:bold"align=left>Plans</legend>
            <div class="row">
              <div class="col-md-8">{{g.Plans}}</div>
            </div>
          </fieldset><br>


        </div>
          <!-- end body -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
</fieldset>
<!-- ************************ end Modal*************************************************** -->

</div>
<!-- end of table div -->




<!-- last years table div -->
<div ng-show="tableLastYearDiv">
  <!-- start row -->
  <div class="row">
    <div class="col-md-8">

    </div>
    <div class="col-md-4">
      <input type="button" class="art-button-green"  ng-click="showForm()" value="Form">
      <input type="button" class="art-button-green"  ng-click="showTable()" value="Current">
    </div>
  </div>
  <!-- end row -->
  <h4>Last year table</h4>

</div>
<!-- end of last years table div -->


<!-- main fieldset  -->
</fieldset>
</center>
</div>
<!-- end of main div -->


<?php
include("./includes/footer.php");
?>
<script type="text/javascript" src="general_examination.js"></script>
<script src="css/jquery.datetimepicker.js"></script>

<!-- date picker -->
<script type="text/javascript">
$(document).ready(function(e){
  $('.date').datetimepicker({value: '', step: 2});
})
</script>
