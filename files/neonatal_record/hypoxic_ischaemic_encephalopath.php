<?php
include('header.php');
include('../includes/connection.php');




if (isset($_GET['Registration_ID'])  && isset($_GET['consultation_ID'])) {
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
  $employee_ID = $_GET['Employee_ID'];
}else{
  header("Location:../../index.php");
}

// get patient details
if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
  $select_patien_details = mysqli_query($conn,"
   SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
     FROM
       tbl_patient_registration pr,
       tbl_sponsor sp
     WHERE
       pr.Registration_ID = '" . $registration_id . "' AND
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


<!-- scripts link-->
<script type="text/javascript" src="../js/DataTables/angular.js"></script>

<!-- css link -->
<link rel="stylesheet" href="../js/DataTables/datatables.min.css">


<style media="screen">
  .container-fluid{
  margin-top:20px;
  /* background-color:white; */
  }
  .col{
    padding: 5px;
    /* border-spacing: 5px; */
  }
  th{
    text-align: left;
  }

  .modal-lg{
    width: 95%;
  }

  span{
    color: #00b3b3;
    font-weight: bold;
  }
</style>

<a href="neonatal_record.php?consultation_ID=<?= $consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>


<!-- *********************** Main Div *******************************************************************-->
<div class="container-fluid" ng-app="ThompsonModule" ng-controller="ThompsonCtl">

  <center>
    <fieldset>
      <legend style="font-weight:bold"align=center>
        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
          <p style="margin:0px;padding:0px;">HYPOXIC ISCHAEMIC ENCEPHALOPATHY SCORE <br>
            Based on Thompson score
          </p>
          <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;color:blue;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;color:blue;"><?= $Gender ?> |</span> <span style="margin-right:3px;color:blue;"><?= $age ?> | </span> <span style="margin-right:3px;color:blue;"><?= $Guarantor_Name ?></span> </p>
        </div>
    </legend>


<!--******************************* Form Div ************************************************************ -->
<div ng-hide="divForm">
  <div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <!-- <input type="button" class="btn btn-sm art-button"  ng-click="showForm()" value="FORM"> -->
        <input type="button" class="btn btn-sm art-button"  ng-click="showTable()" value="PREVIEW" style="float:right;">
    </div>
  </div>

<fieldset>
  <legend>Based on Thompson score</legend>

  <form role="form" class="form-horizontal" name="thompsonFm">

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
        <li><a data-toggle="tab" href="#menu1">Signs</a></li>
    </ul>

      <div class="tab-content">
        <!-- general details -->
        <div id="home" class="tab-pane fade in active">
              <div class="form-group">
                <label class="control-label col-sm-2" for="name">Name:B/O</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="name" ng-model="baby_name" placeholder="Enter name">
                  <input type="hidden" class="form-control" id="Employee_ID"  value="<?= $employee_ID;?>">
                  <input type="hidden" class="form-control" id="Registration_ID" value="<?=$registration_id;?>">
                  <input type="hidden" name="Admision_ID" class="form-control" id="Admision_ID" value="<?php echo $Admision_ID;?>"/><br>
                  <input type="hidden" name="consultation_id" class="form-control" id="consultation_id" value="<?php echo $consultation_id;?>"/><br>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="birth_weight">Birth Weight:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="birth_weight" ng-model="birth_weight" placeholder="Enter (kg)">
                </div>
              </div>

              <div class="form-group">
                <div class="radio">
                  <b>Sex:</b>&nbsp;&nbsp;&nbsp;
                  <label><input type="radio" ng-model="sex"  value="M">Male</label>
                  &nbsp;&nbsp;&nbsp;
                  <label><input type="radio" ng-model="sex"  value="F">Female</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                  <b>APGAR SCORE:</b>&nbsp;&nbsp;&nbsp;
                  <label><input type="text" ng-model="apgar_score1min" placeholder="1 min"></label>
                  &nbsp;&nbsp;&nbsp;
                  <label><input type="text" ng-model="apgar_score5min" placeholder="5 min"></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                  <b>Referral:</b>&nbsp;&nbsp;&nbsp;
                  <label><input type="radio" ng-model="referral" id="refYes" ng-change="checkReferral()" value="yes">Yes</label>
                  &nbsp;&nbsp;&nbsp;
                  <label><input type="radio" ng-model="referral"id="refNo" ng-change="checkReferralNo()"  value="no">No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                  <!-- referral from -->
                  <div ng-show="isRefferal">
                    <label class="control-label col-sm-2" for="referral_from">From:</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="referral_from" ng-model="referral_from" placeholder="Enter referral from">
                    </div>
                  </div>

                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-sm-2" for="history_or_dx">History / Dx:</label>
                <div class="col-sm-10">
                <textarea class="form-control" rows="2" id="history_or_dx" ng-model="history_or_dx"></textarea>
              </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="birth_date">Date of Birth:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control date" id="birth_date" ng-model="birth_date">
                </div>
              </div>

        </div>
        <!-- end general details -->

        <!-- signs -->
        <div id="menu1" class="tab-pane fade">
          <!-- Tone -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Tone:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectTone" ng-change="disableDate(this.selectTone)" ng-options="x for (x, y) in Tone" required></select>
         </div>
          </div>

          <!-- LOC -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">LOC:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectLOC" ng-change="disableDate(this.selectLOC)" ng-options="x for (x, y) in Loc" required></select>
         </div>
          </div>

          <!-- LOC -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Fits:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectFits" ng-change="disableDate(this.selectFits)" ng-options="x for (x, y) in Fits" required></select>
         </div>
          </div>

          <!-- LOC -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Posture:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectPosture" ng-change="disableDate(this.selectPosture)" ng-options="x for (x, y) in Posture" required></select>
         </div>
          </div>


          <!-- LOC -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Moro:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectMoro" ng-change="disableDate(this.selectMoro)" ng-options="x for (x, y) in Moro" required></select>
         </div>
          </div>


          <!-- LOC -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Grasp:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectGrasp" ng-change="disableDate(this.selectGrasp)" ng-options="x for (x, y) in Grasp" required></select>
         </div>
          </div>

          <!-- LOC -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Suck:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectSuck" ng-change="disableDate(this.selectSuck)" ng-options="x for (x, y) in Suck" required></select>
         </div>
          </div>

          <!-- Respiratory -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Respiratory:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectRespiratory" ng-change="disableDate(this.selectRespiratory)" ng-options="x for (x, y) in Respiratory" required></select>
         </div>
          </div>

          <!-- Fontanelle -->
          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Fontanelle:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectFontanelle" ng-change="disableDate(this.selectFontanelle)" ng-options="x for (x, y) in Fontanelle" required></select>
         </div>
          </div>

          <!-- day -->

          <div class="form-group">
           <label class="control-label col-sm-2" for="sel1">Day:</label>
           <div class="col-sm-5">
           <select class="form-control" id="sel1" ng-model="selectDay" ng-change="disableDate(this.selectDay)" ng-options="x for x in days" required></select>
         </div>
          </div>


          <div class="form-group" ng-show="isDate">
            <label class="control-label col-sm-2" for="birth_date">Date:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control date" id="birth_date" ng-model="birth_date">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="remarks">Remarks:</label>
            <div class="col-sm-5">
            <textarea class="form-control" rows="2" id="remarks" ng-model="remarks"></textarea>
          </div>
          </div>
            <h2><span class="label label-success">{{success}}</span></h2>
            <h2><span class="label label-danger">{{error}}</span></h2>
          <input type="button" class="btn btn-sm art-button" value="Save" ng-click="saveThomposon()" ng-show="thompsonFm.$valid">
          <!-- <input type="button" class="btn btn-sm art-button" value="Preview" ng-show="!thompsonFm.$valid"> -->
        </div>
        <!-- end signs -->
      </div>


</form>
</fieldset>

</div>
<!--******************************* End Form Div ************************************************************ -->



<!--********************************** Table Div ************************************************************* -->
<div ng-hide="tableDiv">
  <div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <input type="button" class="btn btn-sm art-button"  ng-click="showForm()" value="FORM" style="float:right;">
        <input type="button" class="btn btn-sm art-button"  ng-click="showLastYearTable()" value="PREVIOUS" style="float:right;">
    </div>
  </div>

    <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <div ng-init="getBasicInfo()">
        <tr ng-repeat="b in basics">
          <th colspan="4">Name  B/O: <span style="color:#009999;">{{b.baby_name}}</span></th>
          <th colspan="3">Birth weight: <span style="color:#009999;">{{b.birth_weight}}</span></th>
          <th colspan="3">Sex:  <span style="color:#009999;">{{b.sex}}</span></th>
          <th colspan="4">File No: <span style="color:#009999;">{{b.Registration_ID}}</span></th>
        </tr>
        <tr ng-repeat="b in basics">
          <th colspan="4">Date of birth: <span style="color:#009999;">{{b.birth_date}}</span></th>
          <th colspan="3">APGAR SCORE: <span style="color:#009999;">1min: {{b.apgar_score1min}} 5min: {{b.apgar_score5min}}</span></th>
          <th colspan="3">Referral: <span style="color:#009999;">{{b.referral}}</span></th>
          <th colspan="4">From: <span style="color:#009999;">{{b.referral_from}}</span></th>
        </tr>
        <tr ng-repeat="b in basics">
          <th>History / Dx</th>
          <th colspan="13"><span style="color:#009999;float:left;">{{b.history_or_dx}}</span></th>
        </tr>
      </div>
        <tr>
          <th rowspan="2" style="background-color:#71c3ff;">SIGN</th>
          <th colspan="4" style="background-color:#71c3ff;">SCORE</th>
          <th style="background-color:#71c3ff;">Day</th>
          <th style="background-color:#71c3ff;">1</th>
          <th style="background-color:#71c3ff;">2</th>
          <th style="background-color:#71c3ff;">3</th>
          <th style="background-color:#71c3ff;">4</th>
          <th style="background-color:#71c3ff;">5</th>
          <th style="background-color:#71c3ff;">6</th>
          <th style="background-color:#71c3ff;">7</th>
          <th style="background-color:#71c3ff;">Remarks</th>
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">0</th>
          <th  style="background-color:#71c3ff;">1</th>
          <th style="background-color:#71c3ff;">2</th>
          <th style="background-color:#71c3ff;">3</th>
          <th style="background-color:#71c3ff;">Date</th>
          <th style="background-color:#71c3ff;">{{tones1.saved_time}}</th>
          <th style="background-color:#71c3ff;">{{tones2.saved_time}}</th>
          <th style="background-color:#71c3ff;">{{tones3.saved_time}}</th>
          <th style="background-color:#71c3ff;">{{tones4.saved_time}}</th>
          <th style="background-color:#71c3ff;">{{tones5.saved_time}}</th>
          <th style="background-color:#71c3ff;">{{tones6.saved_time}}</th>
          <th style="background-color:#71c3ff;">{{tones7.saved_time}}</th>
          <th style="background-color:#71c3ff;">{{tones7.saved_time}}</th>

        </tr>
      </thead>
      <tbody>
        <div ng-init="initilizer()">
        <tr>
          <th style="background-color:#71c3ff;">Tone</th>
          <td>Normal</td>
          <td>Hyper</td>
          <td>Hypo</td>
          <td>Flaccid</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{tones1.tone}}</td>
          <td>{{tones2.tone}}</td>
          <td>{{tones3.tone}}</td>
          <td>{{tones4.tone}}</td>
          <td>{{tones5.tone}}</td>
          <td>{{tones6.tone}}</td>
          <td>{{tones7.tone}}</td>
          <td rowspan="9">{{tones7.remarks}}</td>
        </tr>
       </div>
        <tr>
          <th style="background-color:#71c3ff;">LOC</th>
          <td>Normal</td>
          <td>Hyper alert state</td>
          <td>Lethargic</td>
          <td>Comatose</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{locs1.loc}}</td>
          <td>{{locs2.loc}}</td>
          <td>{{locs3.loc}}</td>
          <td>{{locs4.loc}}</td>
          <td>{{locs5.loc}}</td>
          <td>{{locs6.loc}}</td>
          <td>{{locs7.loc}}</td>
          <!-- <td></td> -->
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">Fits</th>
          <td>Normal</td>
          <td>Infrequent < 3/day</td>
          <td>Frequent >2/day</td>
          <td>-</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{fits1.fits}}</td>
          <td>{{fits2.fits}}</td>
          <td>{{fits3.fits}}</td>
          <td>{{fits4.fits}}</td>
          <td>{{fits5.fits}}</td>
          <td>{{fits6.fits}}</td>
          <td>{{fits7.fits}}</td>
          <!-- <td></td> -->
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">Posture</th>
          <td>Normal</td>
          <td>Fisting, cycling</td>
          <td>Strong distal flexion</td>
          <td>Decerebrate</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{postures1.posture}}</td>
          <td>{{postures2.posture}}</td>
          <td>{{postures3.posture}}</td>
          <td>{{postures4.posture}}</td>
          <td>{{postures5.posture}}</td>
          <td>{{postures6.posture}}</td>
          <td>{{postures7.posture}}</td>
          <!-- <td></td> -->
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">Moro</th>
          <td>Normal</td>
          <td>Absent</td>
          <td>-</td>
          <td>-</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{moros1.moro}}</td>
          <td>{{moros2.moro}}</td>
          <td>{{moros3.moro}}</td>
          <td>{{moros4.moro}}</td>
          <td>{{moros5.moro}}</td>
          <td>{{moros6.moro}}</td>
          <td>{{moros7.moro}}</td>
          <!-- <td></td> -->
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">Grasp</th>
          <td>Normal</td>
          <td>Poor</td>
          <td>Absent</td>
          <td>-</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{grasps1.grasp}}</td>
          <td>{{grasps2.grasp}}</td>
          <td>{{grasps3.grasp}}</td>
          <td>{{grasps4.grasp}}</td>
          <td>{{grasps5.grasp}}</td>
          <td>{{grasps6.grasp}}</td>
          <td>{{grasps7.grasp}}</td>
          <!-- <td></td> -->
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">Suck</th>
          <td>Normal</td>
          <td>Poor</td>
          <td>Absent + bites</td>
          <td>-</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{sucks1.suck}}</td>
          <td>{{sucks2.suck}}</td>
          <td>{{sucks3.suck}}</td>
          <td>{{sucks4.suck}}</td>
          <td>{{sucks5.suck}}</td>
          <td>{{sucks6.suck}}</td>
          <td>{{sucks7.suck}}</td>
          <!-- <td></td> -->
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">Respiratory</th>
          <td>Normal</td>
          <td>Hyperventilation</td>
          <td>Brief Apnoea</td>
          <td>Ventilation</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{respiratories1.respiratory}}</td>
          <td>{{respiratories2.respiratory}}</td>
          <td>{{respiratories3.respiratory}}</td>
          <td>{{respiratories4.respiratory}}</td>
          <td>{{respiratories5.respiratory}}</td>
          <td>{{respiratories6.respiratory}}</td>
          <td>{{respiratories7.respiratory}}</td>
          <!-- <td></td> -->
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">Fontanelle</th>
          <td>Normal</td>
          <td>Full, non-tense</td>
          <td>Tense</td>
          <td>-</td>
          <td style="background-color:#71c3ff;"></td>
          <td>{{fontanelle1.fontanelle}}</td>
          <td>{{fontanelle2.fontanelle}}</td>
          <td>{{fontanelle3.fontanelle}}</td>
          <td>{{fontanelle4.fontanelle}}</td>
          <td>{{fontanelle5.fontanelle}}</td>
          <td>{{fontanelle6.fontanelle}}</td>
          <td>{{fontanelle7.fontanelle}}</td>
          <!-- <td></td> -->
        </tr>

        <tr>
          <th style="background-color:#71c3ff;">Total score per day</th>
          <td colspan="5" style="background-color:#71c3ff;"></td>
          <td style="background-color:#71c3ff;font-weight:bold;">{{d1Total}}</td>
          <td style="background-color:#71c3ff;font-weight:bold;">{{d2Total}}</td>
          <td style="background-color:#71c3ff;font-weight:bold;">{{d3Total}}</td>
          <td style="background-color:#71c3ff;font-weight:bold;">{{d4Total}}</td>
          <td style="background-color:#71c3ff;font-weight:bold;">{{d5Total}}</td>
          <td style="background-color:#71c3ff;font-weight:bold;">{{d6Total}}</td>
          <td style="background-color:#71c3ff;font-weight:bold;">{{d7Total}}</td>
          <td style="background-color:#71c3ff;"></td>
        </tr>
      </tbody>
    </table>
    </div>

    <table class="table table-hover">
        <tr>
          <td style="background-color:#FFE4C4;font-weight:bold;">2-10</td>
          <td style="background-color:#FFA07A;font-weight:bold;">11-14</td>
          <td style="background-color:#D2691E;font-weight:bold;">15-22</td>
        </tr>
        <tr>
          <td style="background-color:#FFE4C4;font-weight:bold;">Mild HIE</td>
          <td style="background-color:#FFA07A;font-weight:bold;">Moderate HIE</td>
          <td style="background-color:#D2691E;font-weight:bold;">Severe HIE</td>
        </tr>
        <br>
        <tr>
          <td colspan="3">
            -This score shall be used on every child with signs or suspicion of perinatal/ birth asphyxia.<br>
            -Preterm/LBW should be <b>excluded</b><br>
            <ul>
              <li>Check patient at least once a day</li>
              <li>Check for every sign</li>
              <li>Assign score for every sign and write corresponding number in the day column</li>
            </ul>


            <h5>KEY</h5>
            TONE- describe global muscle tone
            <b>Flaccid</b>- floppy  &nbsp;&nbsp;&nbsp; <b>LOC</b> - look at level of consciousness&nbsp;&nbsp;&nbsp;
            <b>Moro</b>- check reflex when falling back Grasp- check grasping reflex&nbsp;&nbsp;&nbsp;
            <b>Suck</b>- check sucking reflex&nbsp;&nbsp;&nbsp;
            <b>Fits</b>- look for seizure-like condition&nbsp;&nbsp;&nbsp;
            <b>respiratory</b>- describe the breathing&nbsp;&nbsp;&nbsp;
            <b>Posture</b>- describe body position, decerebrate&nbsp;&nbsp;&nbsp;
            <b>Fontanelle</b>- describe the status&nbsp;&nbsp;&nbsp;
          </td>
        </tr>
    </table>
</div>
<!--********************************** End Table Div ************************************************************* -->



<!--********************************** Last Years Table Preview ****************************************************** -->
<div ng-hide="lastYears">
  <div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <input type="button" class="btn btn-sm art-button"  ng-click="showForm()" value="FORM" style="float:right;">
        <input type="button" class="btn btn-sm art-button"  ng-click="showTable()" value="CURRENT" style="float:right;">
    </div>
  </div>

  <center>
    <fieldset>
      <legend>PREVIOUS HYPOXIC ISCHAEMIC ENCEPHALOPATHY SCORE</legend>
      <table class="table">
        <th>#</th>
        <th>ALL YEARS</th>

        <?php
          $sn = 1;
          $select_year = mysqli_query($conn,"SELECT DISTINCT(YEAR(birth_date)) as 'year' FROM tbl_hypoxic_ischaemic_encephalopath WHERE  YEAR(birth_date) !='0' and  Registration_ID = $registration_id");

          while($y = mysqli_fetch_assoc($select_year))
          {
             $year = $y['year'];
              echo "
              <tr>
                  <td>".$sn."</td>
                  <td><input type='button' id='year' class='btn btn-link' ng-click='getAll()' value='".$year."' data-toggle=\"modal\" data-target=\"#myModal\"></td>
              </tr>";
                $sn++;
          }

         ?>

      </table>

              <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header" style="background-color:#037CB0;font-weight:bold;color:white;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-weight:bold;color:white;">HYPOXIC ISCHAEMIC ENCEPHALOPATHY SCORE</h4>
                <p style="font-weight:bold;color:white;">Based on Thompson score</p>
              </div>
              <div class="modal-body">
                <a  href="print_hypoxic_ischaemic_encephalopath.php?Registration_ID=<?=$registration_id;?>&delivery_year=<?=$year?>" class="art-button-green" target="_blank">PDF</a>
                    <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <!-- <div ng-init="getBasicInfo()"> -->
                        <tr ng-repeat="b in basics1">
                          <th colspan="4">Name  B/O: <span style="color:#009999;">{{b.baby_name}}</span></th>
                          <th colspan="3">Birth weight: <span style="color:#009999;">{{b.birth_weight}}</span></th>
                          <th colspan="3">Sex:  <span style="color:#009999;">{{b.sex}}</span></th>
                          <th colspan="4">File No: <span style="color:#009999;">{{b.Registration_ID}}</span></th>
                        </tr>
                        <tr ng-repeat="b in basics1">
                          <th colspan="4">Date of birth: <span style="color:#009999;">{{b.birth_date}}</span></th>
                          <th colspan="3">APGAR SCORE: <span style="color:#009999;">1min: {{b.apgar_score1min}} 5min: {{b.apgar_score5min}} </span></th>
                          <th colspan="3">Referral: <span style="color:#009999;">{{b.referral}}</span></th>
                          <th colspan="4">From: <span style="color:#009999;">{{b.referral_from}}</span></th>
                        </tr>
                        <tr ng-repeat="b in basics1">
                          <th>History / Dx</th>
                          <th colspan="13"><span style="color:#009999;float:left;">{{b.history_or_dx}}</span></th>
                        </tr>
                      <!-- </div> -->
                        <tr>
                          <th rowspan="2" style="background-color:#71c3ff;">SIGN</th>
                          <th colspan="4" style="background-color:#71c3ff;">SCORE</th>
                          <th style="background-color:#71c3ff;">Day</th>
                          <th style="background-color:#71c3ff;">1</th>
                          <th style="background-color:#71c3ff;">2</th>
                          <th style="background-color:#71c3ff;">3</th>
                          <th style="background-color:#71c3ff;">4</th>
                          <th style="background-color:#71c3ff;">5</th>
                          <th style="background-color:#71c3ff;">6</th>
                          <th style="background-color:#71c3ff;">7</th>
                          <th style="background-color:#71c3ff;">Remarks</th>
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">0</th>
                          <th  style="background-color:#71c3ff;">1</th>
                          <th style="background-color:#71c3ff;">2</th>
                          <th style="background-color:#71c3ff;">3</th>
                          <th style="background-color:#71c3ff;">Date</th>
                          <th style="background-color:#71c3ff;">{{tones11.saved_time}}</th>
                          <th style="background-color:#71c3ff;">{{tones21.saved_time}}</th>
                          <th style="background-color:#71c3ff;">{{tones31.saved_time}}</th>
                          <th style="background-color:#71c3ff;">{{tones41.saved_time}}</th>
                          <th style="background-color:#71c3ff;">{{tones51.saved_time}}</th>
                          <th style="background-color:#71c3ff;">{{tones61.saved_time}}</th>
                          <th style="background-color:#71c3ff;">{{tones71.saved_time}}</th>
                          <th style="background-color:#71c3ff;">{{tones71.saved_time}}</th>

                        </tr>
                      </thead>
                      <tbody>
                        <div>
                        <tr>
                          <th style="background-color:#71c3ff;">Tone</th>
                          <td>Normal</td>
                          <td>Hyper</td>
                          <td>Hypo</td>
                          <td>Flaccid</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{tones11.tone}}</td>
                          <td>{{tones21.tone}}</td>
                          <td>{{tones31.tone}}</td>
                          <td>{{tones41.tone}}</td>
                          <td>{{tones51.tone}}</td>
                          <td>{{tones61.tone}}</td>
                          <td>{{tones71.tone}}</td>
                          <td colspan="9">{{tones71.remarks}}</td>
                        </tr>
                       </div>
                        <tr>
                          <th style="background-color:#71c3ff;">LOC</th>
                          <td>Normal</td>
                          <td>Hyper alert state</td>
                          <td>Lethargic</td>
                          <td>Comatose</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{locs11.loc}}</td>
                          <td>{{locs21.loc}}</td>
                          <td>{{locs31.loc}}</td>
                          <td>{{locs41.loc}}</td>
                          <td>{{locs51.loc}}</td>
                          <td>{{locs61.loc}}</td>
                          <td>{{locs71.loc}}</td>
                          <!-- <td></td> -->
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">Fits</th>
                          <td>Normal</td>
                          <td>Infrequent < 3/day</td>
                          <td>Frequent >2/day</td>
                          <td>-</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{fits11.fits}}</td>
                          <td>{{fits21.fits}}</td>
                          <td>{{fits31.fits}}</td>
                          <td>{{fits41.fits}}</td>
                          <td>{{fits51.fits}}</td>
                          <td>{{fits61.fits}}</td>
                          <td>{{fits71.fits}}</td>
                          <!-- <td></td> -->
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">Posture</th>
                          <td>Normal</td>
                          <td>Fisting, cycling</td>
                          <td>Strong distal flexion</td>
                          <td>Decerebrate</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{postures11.posture}}</td>
                          <td>{{postures21.posture}}</td>
                          <td>{{postures31.posture}}</td>
                          <td>{{postures41.posture}}</td>
                          <td>{{postures51.posture}}</td>
                          <td>{{postures61.posture}}</td>
                          <td>{{postures71.posture}}</td>
                          <!-- <td></td> -->
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">Moro</th>
                          <td>Normal</td>
                          <td>Absent</td>
                          <td>-</td>
                          <td>-</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{moros11.moro}}</td>
                          <td>{{moros21.moro}}</td>
                          <td>{{moros31.moro}}</td>
                          <td>{{moros41.moro}}</td>
                          <td>{{moros51.moro}}</td>
                          <td>{{moros61.moro}}</td>
                          <td>{{moros71.moro}}</td>
                          <!-- <td></td> -->
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">Grasp</th>
                          <td>Normal</td>
                          <td>Poor</td>
                          <td>Absent</td>
                          <td>-</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{grasps11.grasp}}</td>
                          <td>{{grasps21.grasp}}</td>
                          <td>{{grasps31.grasp}}</td>
                          <td>{{grasps41.grasp}}</td>
                          <td>{{grasps51.grasp}}</td>
                          <td>{{grasps61.grasp}}</td>
                          <td>{{grasps71.grasp}}</td>
                          <!-- <td></td> -->
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">Suck</th>
                          <td>Normal</td>
                          <td>Poor</td>
                          <td>Absent + bites</td>
                          <td>-</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{sucks11.suck}}</td>
                          <td>{{sucks21.suck}}</td>
                          <td>{{sucks31.suck}}</td>
                          <td>{{sucks41.suck}}</td>
                          <td>{{sucks51.suck}}</td>
                          <td>{{sucks61.suck}}</td>
                          <td>{{sucks71.suck}}</td>
                          <!-- <td></td> -->
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">Respiratory</th>
                          <td>Normal</td>
                          <td>Hyperventilation</td>
                          <td>Brief Apnoea</td>
                          <td>Ventilation</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{respiratories11.respiratory}}</td>
                          <td>{{respiratories21.respiratory}}</td>
                          <td>{{respiratories31.respiratory}}</td>
                          <td>{{respiratories41.respiratory}}</td>
                          <td>{{respiratories51.respiratory}}</td>
                          <td>{{respiratories61.respiratory}}</td>
                          <td>{{respiratories71.respiratory}}</td>
                          <!-- <td></td> -->
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">Fontanelle</th>
                          <td>Normal</td>
                          <td>Full, non-tense</td>
                          <td>Tense</td>
                          <td>-</td>
                          <td style="background-color:#71c3ff;"></td>
                          <td>{{fontanelle11.fontanelle}}</td>
                          <td>{{fontanelle21.fontanelle}}</td>
                          <td>{{fontanelle31.fontanelle}}</td>
                          <td>{{fontanelle41.fontanelle}}</td>
                          <td>{{fontanelle51.fontanelle}}</td>
                          <td>{{fontanelle61.fontanelle}}</td>
                          <td>{{fontanelle71.fontanelle}}</td>
                          <!-- <td></td> -->
                        </tr>

                        <tr>
                          <th style="background-color:#71c3ff;">Total score per day</th>
                          <td colspan="5" style="background-color:#71c3ff;"></td>
                          <td style="background-color:#71c3ff;font-weight:bold;">{{d1Total1}}</td>
                          <td style="background-color:#71c3ff;font-weight:bold;">{{d2Total1}}</td>
                          <td style="background-color:#71c3ff;font-weight:bold;">{{d3Total1}}</td>
                          <td style="background-color:#71c3ff;font-weight:bold;">{{d4Total1}}</td>
                          <td style="background-color:#71c3ff;font-weight:bold;">{{d5Total1}}</td>
                          <td style="background-color:#71c3ff;font-weight:bold;">{{d6Total1}}</td>
                          <td style="background-color:#71c3ff;font-weight:bold;">{{d7Total1}}</td>
                          <td style="background-color:#71c3ff;"></td>
                        </tr>
                      </tbody>
                    </table>
                    </div>

                    <table class="table table-hover">
                        <tr>
                          <td style="background-color:#FFE4C4;font-weight:bold;">2-10</td>
                          <td style="background-color:#FFA07A;font-weight:bold;">11-14</td>
                          <td style="background-color:#D2691E;font-weight:bold;">15-22</td>
                        </tr>
                        <tr>
                          <td style="background-color:#FFE4C4;font-weight:bold;">Mild HIE</td>
                          <td style="background-color:#FFA07A;font-weight:bold;">Moderate HIE</td>
                          <td style="background-color:#D2691E;font-weight:bold;">Severe HIE</td>
                        </tr>
                        <br>
                        <tr>
                          <td colspan="3">
                            -This score shall be used on every child with signs or suspicion of perinatal/ birth asphyxia.<br>
                            -Preterm/LBW should be <b>excluded</b><br>
                            <ul>
                              <li>Check patient at least once a day</li>
                              <li>Check for every sign</li>
                              <li>Assign score for every sign and write corresponding number in the day column</li>
                            </ul>


                            <h5>KEY</h5>
                            TONE- describe global muscle tone
                            <b>Flaccid</b>- floppy  &nbsp;&nbsp;&nbsp; <b>LOC</b> - look at level of consciousness&nbsp;&nbsp;&nbsp;
                            <b>Moro</b>- check reflex when falling back Grasp- check grasping reflex&nbsp;&nbsp;&nbsp;
                            <b>Suck</b>- check sucking reflex&nbsp;&nbsp;&nbsp;
                            <b>Fits</b>- look for seizure-like condition&nbsp;&nbsp;&nbsp;
                            <b>respiratory</b>- describe the breathing&nbsp;&nbsp;&nbsp;
                            <b>Posture</b>- describe body position, decerebrate&nbsp;&nbsp;&nbsp;
                            <b>Fontanelle</b>- describe the status&nbsp;&nbsp;&nbsp;
                          </td>
                        </tr>
                    </table>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>
    </fieldset>
</center>

</div>
<!--**********************************End  Last Years Table Preview ****************************************************** -->



</fieldset>
</center>
</div>
<!-- *********************** End Main Div *******************************************************************-->



<!-- scripts -->
<script type="text/javascript" src="../js/DataTables/angular-datatables.min.js"></script>
<script type="text/javascript" src="../js/DataTables/angular-datatables.buttons.min.js"></script>
<script type="text/javascript" src="../js/DataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../js/DataTables/pdfmake.min.js"></script>
<script type="text/javascript" src="../js/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>


<!--******************************* ANGULAR *******************************************************-->
<script type="text/javascript" src="hypoxic_ischaemic_encephalopath.js"></script>


<!--********************************* END *********************************************************-->




<!-- <script src="css/jquery.timepicker.js"></script> -->
<script src="../css/jquery.datetimepicker.js"></script>
<script type="text/javascript">
  $(document).ready(function(e){

    $('.date').datetimepicker({value: '', step: 2});
  })
</script>



<?php
    include("../includes/footer.php");
?>
