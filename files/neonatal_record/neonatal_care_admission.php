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
<script type="text/javascript" src="../js/jquer.js"></script>
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

  span{
    color: #00b3b3;
    font-weight: bold;
  }
</style>
<a href="neonatal_record.php?consultation_ID=<?= $consultation_id;?>&Employee_ID=<?= $employee_ID;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>


<!-- main div -->
<div class="container-fluid" ng-app="neonatalCare" ng-controller="neonatalCtl">
<center>
  <fieldset>
    <legend style="font-weight:bold"align=center>
      <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
        <p style="margin:0px;padding:0px;">NEONATAL CARE ADMISSION FORM</p>
        <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;color:yellow;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;color:yellow;"><?= $Gender ?> |</span> <span style="margin-right:3px;color:yellow;"><?= $age ?> | </span> <span style="margin-right:3px;color:yellow;"><?= $Guarantor_Name ?></span> </p>
      </div>
  </legend>
</fieldset>

<!-- message -->
<?php
    if ($_GET['msg'] == 'saved') {
      echo '<div class="alert alert-success alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Success!</strong>Record Added Successfully
        </div>';
    }

    if ($_GET['msg'] == 'fail') {
      echo '<div class="alert alert-danger alert-dismissible">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Error:'.$_GET["error"].'</strong>
        </div>';
    }
 ?>
<!--**************************************************** form div ********************************************************************-->
<div ng-hide="divForm">


  <form name="neoCareFm" action="save_neonatal_care_admission.php" id="neoCareFm" role="form" class="form-horizontal"  method="post">

    <!-- data -->
    <fieldset align="left">
      <legend>DATA</legend>
      <!-- row1 -->
       <div class="row">
         <div class="col-md-6">
           <div class="form-group">
             <label class="control-label col-sm-*" for="name">Name:</label>
             <div class="col-sm-*">
               <input type="text" class="form-control" name="name_of_baby" id="name_of_baby" placeholder="Enter name">
               <input type="hidden" class="form-control" name="Employee_ID" id="Employee_ID" value ="<?= $employee_ID?>">
             </div>
           </div>
         </div>

         <div class="col-md-6">
           <div class="form-group">
             <label class="control-label col-sm-*" for="Registration_ID">File/Registration No:</label>
             <div class="col-sm-*">
               <input type="text" class="form-control" id="Registration_ID1" value ="<?= $registration_id?>" readonly>
               <input type="hidden" class="form-control" name="Registration_ID" id="Registration_ID"  value ="<?= $registration_id?>">
               <input type="hidden" name="Admision_ID" class="form-control" id="Admision_ID" value="<?php echo $Admision_ID;?>"/><br>
               <input type="hidden" name="consultation_id" class="form-control" id="consultation_id" value="<?php echo $consultation_id;?>"/><br>
             </div>
           </div>
         </div>
       </div>

       <!-- row2 -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="referral_from ">Referral from :</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="referral_from" id="referral_from" placeholder="Enter referral">
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="transer_from_maternity ">Transfer from maternity :</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="transer_from_maternity" id="transer_from_maternity" placeholder="Enter transer from maternity">
              </div>
            </div>
          </div>
        </div>

        <!-- row3 -->
         <div class="row">
           <div class="col-md-6">
             <div class="form-group">
               <label class="control-label col-sm-*" for="date_birth ">Date & time of birth:</label>
               <div class="col-sm-*">
                 <input type="text" class="date" name="date_birth" id="date_birth">
               </div>
             </div>
           </div>

           <div class="col-md-6">
             <div class="form-group">
               <label class="control-label col-sm-*" for="admission_date ">Date & time of admission:</label>
               <div class="col-sm-*">
                 <input type="text" class="date" name="admission_date" id="admission_date">
               </div>
             </div>
           </div>
         </div>

         <!-- row4 -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-sm-*" for="length_cm ">Length(cm):</label>
                <div class="col-sm-*">
                  <input type="text" class="form-control" name="length_cm" id="length_cm" placeholder="Enter length(cm)">
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-sm-*" for="head_circumference_cm">Head circumference (cm):</label>
                <div class="col-sm-*">
                  <input type="text" class="form-control" name="head_circumference_cm" id="head_circumference_cm" placeholder="Enter head circumference (cm)">
                </div>
              </div>
            </div>
          </div>

          <!-- row5 -->
           <div class="row">
             <div class="col-md-6">
               <div class="radio">
                Sex <label><input type="radio" value="M" name="gender">Male</label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" value="F" name="gender">Female</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                PMTCT <label><input type="radio" value="1" name="pmtct">1</label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" value="2" name="pmtct">2</label>&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;
                <input type="radio" value="not tested" name="pmtct">Not tested</label>&nbsp;&nbsp;&nbsp;&nbsp;
              </div>
             </div>

             <div class="col-md-6">
               <div class="form-group">
                 <label class="control-label col-sm-*" for="apgar_score">APGAR SCORE:</label>
                 <div class="col-sm-*">
                   <input type="text" class="form-control" name="apgar_score" id="apgar_score" placeholder="Enter APGAR SCORE">
                 </div>
               </div>
             </div>
           </div>

           <div class="row">
             <div class="col-md-6">
               <div class="form-group">
                 <label class="control-label col-sm-*" for="ga">GA(in weeks):</label>
                 <div class="col-sm-*">
                   <input type="text" class="form-control" name="ga" id="ga" placeholder="Enter (in weeks)">
                 </div>
               </div>
             </div>

             <div class="col-md-6">
               <div class="form-group">
                 <label class="control-label col-sm-*" for="ga">Birth Weight:</label>
                 <div class="col-sm-*">
                   <input type="text" class="form-control" name="birth_weight" id="birth_weight" placeholder="Enter (kg)">
                 </div>
               </div>
             </div>
           </div>

    </fieldset><br><br>
    <!-- end data -->



    <fieldset>
      <legend>HISTORY-PREVIOUS</legend>
      <!-- row1 -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="chronical_maternal_illiness">Chronical maternal illinesses:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="chronical_maternal_illiness" id="chronical_maternal_illiness" placeholder="Enter chronical maternal illinesses">
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="family_illnesses">family illnesses:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="family_illnesses" id="family_illnesses" placeholder="Enter family illnesses">
            </div>
          </div>
        </div>
      </div>

      <!-- row2 -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="gravida">Gravida:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="gravida" id="gravida" placeholder="Enter gravida (after delivery)">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="para">Para:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="para" id="para" placeholder="Enter para (after delivery)">
            </div>
        </div>
      </div>
    </div>

      <!-- row3 -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="number_of_living_children">Number of living children:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="number_of_living_children" id="number_of_living_children" placeholder="Enter number of living children">
            </div>
        </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="known_problem_of_living_children">Known problem of living children:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="known_problem_of_living_children" id="known_problem_of_living_children" placeholder="Enter known problem of living children">
            </div>
        </div>
        </div>
      </div>

      <!-- row4 -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="complication">Complication during previous pregnancies:</label>
            <textarea class="form-control" rows="2" id="complication_during_previous_pregnancies" name="complication_during_previous_pregnancies"></textarea>
          </div>
       </div>

       <div class="col-md-6">
         <div class="form-group">
         <label for="sel1">Marital status:</label>
         <select class="form-control" id="marital_status" name="marital_status">
           <option selected>--Select--</option>
           <option value="single">Single</option>
           <option value="not married">Not married</option>
           <option value="married">Married</option>
         </select>
        </div>
       </div>
      </div>
    </fieldset><br><br>
    <!-- end of history of previous -->



    <fieldset align="center">
      <legend>HISTORY OF PREGNANCY</legend>

      <fieldset align="left">
        <legend>Antenatal history</legend>
        <!-- row1 -->
        <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="lnmp">LNMP:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="lnmp" id="lnmp" placeholder="Enter LNMP">
              </div>
          </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="edd">EDD:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="edd" id="edd" placeholder="Enter EDD">
              </div>
          </div>
          </div>
        </div>

        <!-- row2 -->
        <div class="row">
          <div class="col-md-4">
            <div class="col-md-6">
              <div class="radio">
               <b>VDRL</b>: <label><input type="radio" value="levele" name="vdrl">Level</label>&nbsp;
               <label><input type="radio" value="neg" name="vdrl">Neg</label>&nbsp;
               <label><input type="radio" value="pos" name="vdrl">Pos</label>&nbsp;
               <label><input type="radio" value="N/A" name="vdrl">N/A</label>
             </div>
           </div>
          </div>

          <div class="col-md-4">
            <div class="radio">
             <b>Malaria</b>:
             <label><input type="radio" value="neg" name="malaria">Neg</label>&nbsp;
             <label><input type="radio" value="pos" name="malaria">Pos</label>&nbsp;
             <label><input type="radio" value="N/A" name="malaria">N/A</label>
           </div>
          </div>

          <div class="col-md-4">
            <div class="radio">
             <b>Hep. B</b>:
             <label><input type="radio" value="neg" name="hep_b">Neg</label>&nbsp;
             <label><input type="radio" value="pos" name="hep_b">Pos</label>&nbsp;
             <label><input type="radio" value="N/A" name="hep_b">N/A</label>
           </div>
          </div>
        </div>

        <!-- row1 -->
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label col-sm-*" for="hb">HB Level:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="hb_level" id="hb_level" placeholder="Enter HB">
              </div>
          </div>
        </div>

          <div class="col-md-4">
            <div class="radio">
             <b>Hypertension</b>:
             <label><input type="radio" value="yes" name="hypertension">Yes</label>&nbsp;
             <label><input type="radio" value="no" name="hypertension">No</label>&nbsp;
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label col-sm-*" for="blood_pressure">Blood pressure:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="blood_pressure" id="blood_pressure" placeholder="Enter Blood pressure">
              </div>
          </div>
          </div>
        </div>

        <!-- row1 -->
        <div class="row">
          <div class="col-md-6">
            <div class="radio">
             <b>Drug abuse</b>:
             <label><input type="radio" value="yes" name="drug_abuse">Yes</label>&nbsp;
             <label><input type="radio" value="no" name="drug_abuse">No</label>&nbsp;
             <label><input type="radio" value="Nlocal herbs" name="drug_abuse">Local herbs</label>
           </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="blood_group_rh">Blood group + Rh:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="blood_group_rh" id="blood_group_rh" placeholder="Enter Blood group + Rh">
              </div>
          </div>
          </div>
        </div>

        <!-- row -->
        <div class="row">
          <div class="col-md-6">
            <div class="radio">
             <b>ANC Attended</b>:
             <label><input type="radio" value="yes" name="anc_attended">Yes</label>&nbsp;
             <label><input type="radio" value="no" name="anc_attended">No</label>&nbsp;
           </div>
          </div>

          <div class="col-md-6">
            <div class="radio">
             <b>Where ANC done</b>:
             <label><input type="radio" value="at hospital" name="where_anc_done">At hospital</label>&nbsp;
             <label><input type="radio" value="health centre" name="where_anc_done">Health centre</label>&nbsp;
             <label><input type="radio" value="dispensary" name="where_anc_done">Dispensary</label>&nbsp;
           </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="number_of_visits">Number of ANC-visits:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="number_of_visits" id="number_of_visits" placeholder="Enter number of visits">
              </div>
          </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="ga_at_1st_visit">GA at 1st ANC-visit:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="ga_at_1st_visit" id="ga_at_1st_visit" placeholder="Enter GA at 1st ANC visit">
              </div>
          </div>
          </div>
        </div>

      </fieldset><br><br>
      <!-- end of antenatal history -->



      <fieldset align="left">
        <legend>Delivery history</legend>
        <!-- row1 -->
        <div class="row">
          <div class="col-md-6">
            <div class="radio">
             <b>Maternal fever >38 C</b>:
             <label><input type="radio" value="no" name="maternal_fever">No</label>&nbsp;
             <label><input type="radio" value="yes" name="maternal_fever">Yes</label>&nbsp;<b>If yes:---></b>
            </div>
          </div>
          <div class="col-md-6">
            <div class="radio">
             <b>PROM</b>:
             <label><input type="radio" value="no" name="prom">No</label>&nbsp;
             <label><input type="radio" value="yes" name="prom">Yes</label>&nbsp;<b>If yes:--->duration</b>
             <input type="text" class="form-control" name="prom_yes_hrs" id="prom_yes_hrs" placeholder="Enter (hrs)/days">
            </div>
          </div>
        </div>


        <!-- row1 -->
        <div class="row">
          <div class="col-md-6">
            <div class="radio">
             <b>AB treatment</b>:
             <label><input type="radio" value="no" name="ab_treatment">No</label>&nbsp;
             <label><input type="radio" value="yes" name="ab_treatment">Yes</label>&nbsp;<b>Drug:</b>
             <input type="text" class="form-control" name="ab_treatment_yes_drug" id="ab_treatment_yes_drug" placeholder="Enter drug">
            </div>
          </div>
        </div>

        <!-- row1 -->
        <div class="row">
          <div class="col-md-6">
            <div class="radio">
             <b>Amniotic fluid</b>:
             <label><input type="radio" value="clear" name="amniotic_fluid">clear</label>&nbsp;
             <label><input type="radio" value="yellow" name="amniotic_fluid">yellow</label>&nbsp;
             <label><input type="radio" value="green" name="amniotic_fluid">green</label>&nbsp;
             <label><input type="radio" value="meconium" name="amniotic_fluid">meconium</label>&nbsp;
             <label><input type="radio" value="bad smell" name="amniotic_fluid">bad smell?</label>&nbsp;
            </div>
          </div>
      </div>

      <!-- row1 -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Abnormalities of placenta</b>:
           <label><input type="radio" value="no" name="abnormalities_of_placenta">No</label>&nbsp;
           <label><input type="radio" value="yes" name="abnormalities_of_placenta">Yes</label>&nbsp;<b>If yes:---></b>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="abnormalities_of_placenta_yes">What:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="abnormalities_of_placenta_yes" id="abnormalities_of_placenta_yes" placeholder="Write something...">
            </div>
        </div>
        </div>
      </div>

      <!-- row1 -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Abnormal presentation</b>:
           <label><input type="radio" value="no" name="abnormal_presentation">No</label>&nbsp;
           <label><input type="radio" value="yes" name="abnormal_presentation">Yes</label>&nbsp;<b>If yes:---></b>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="abnormal_presentation_yes">What:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="abnormal_presentation_yes" id="abnormal_presentation_yes" placeholder="Write something...">
            </div>
        </div>
        </div>
      </div>



      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Mode of delivery</b>:
           <label><input type="radio" value="SVD" name="mode_of_delivery">SVD</label>&nbsp;
           <label><input type="radio" value="VM" name="mode_of_delivery">VM</label>&nbsp;
           <label><input type="radio" value="Breech" name="mode_of_delivery">Breech</label>&nbsp;
           <label><input type="radio" value="CS" name="mode_of_delivery">CS</label>&nbsp;
          </div>
        </div>

        <div class="col-md-6">
          <div class="radio">
           <b>If CS: ---></b>
           <label><input type="radio" value="spinal" name="cs">Spinal</label>&nbsp;
           <label><input type="radio" value="general anaesthesia" name="cs">General anaesthesia</label>&nbsp;
           <label><input type="radio" value="elective" name="cs">Elective</label>&nbsp;
           <label><input type="radio" value="emergency" name="cs">Emergency</label>&nbsp;
          </div>
        </div>
      </div>



      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="indication">Indication:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="indication" id="indication" placeholder="Enter indication">
            </div>
        </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="duration_of_cs">Duration of CS:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="duration_of_cs" id="duration_of_cs" placeholder="Enter duration">
            </div>
        </div>
        </div>
      </div>

      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*">Duration of labour:</label>
            <div class="col-sm-2">
              1st Stage <input type="text" class="form-control" name="duration_of_labour_stage1" id="duration_of_labour_stage1" placeholder="Enter (hrs)">
            </div><br>
            <div class="col-sm-2">
              2nd Stage<input type="text" class="form-control" name="duration_of_labour_stage2" id="duration_of_labour_stage2" placeholder="Enter (hrs)">
            </div><br>
            <div class="col-sm-2">
              3rd Stage<input type="text" class="form-control" name="duration_of_labour_stage3" id="duration_of_labour_stage3" placeholder="Enter (hrs)">
            </div><br>
        </div>
        </div>

          <div class="col-md-6">
            <div class="radio">
             <b>Obstructed labour</b>:
             <label><input type="radio" value="yes" name="obstructed_labour">Yes</label>&nbsp;
             <label><input type="radio" value="no" name="obstructed_labour">No</label>&nbsp;
            </div>
          </div>
      </div>

      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Place of delivery:</b>
           <label><input type="radio" value="at home" name="place_of_delivery">At home</label>&nbsp;
           <label><input type="radio" value="health centre" name="place_of_delivery">Health centre</label>&nbsp;
           <label><input type="radio" value="dispensary" name="place_of_delivery">Dispensary</label>&nbsp;
           <label><input type="radio" value="home delivery" name="place_of_delivery">Home delivery</label>&nbsp;
           <label><input type="radio" value="TBA" name="place_of_delivery">TBA</label>&nbsp;
           <label><input type="radio" value="BBA" name="place_of_delivery">BBA</label>&nbsp;
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*">Delivery attendant:</label>
            <div class="col-sm-*">
               <input type="text" class="form-control" name="delivery_attendant" id="delivery_attendant" placeholder="Enter attendant">
            </div>
        </div>
        </div>
      </div>

      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
          <label for="if_assisted_delivery">If assisted delivery,WHY?:</label>
          <textarea class="form-control" rows="2" id="if_assisted_delivery_why" name="if_assisted_delivery_why"></textarea>
          </div>
        </div>
      </div>


    </fieldset><br><br>
      <!-- end of delivery history -->

    <fieldset align="left">
      <legend>Postnatal history</legend>
      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Problems of baby after birth:</b>
           <label><input type="radio" value="DIB" name="problems_of_baby_after_birth">DIB</label>&nbsp;
           <label><input type="radio" value="declayed cry" name="problems_of_baby_after_birth">Declayed cry</label>&nbsp;
           <label><input type="radio" value="cord prolapse" name="problems_of_baby_after_birth">Cord prolapse</label>&nbsp;
           <label><input type="radio" value="meconium aspiration" name="problems_of_baby_after_birth">Meconium aspiration</label>&nbsp;
          </div>
        </div>
        <div class="col-md-6">
          <div class="radio">
           <b>Resuscitation after birth:</b>
           <label><input type="radio" value="DIB" name="resuscitation">No</label>&nbsp;
           <label><input type="radio" value="declayed cry" name="resuscitation">Yes</label>&nbsp;<b>if yes:</b>&nbsp;
           <label><input type="radio" value="ventilation" name="resuscitation_yes">Ventilation</label>&nbsp;
           <label><input type="radio" value="suction" name="resuscitation_yes">Suction</label>&nbsp;
           <label><input type="radio" value="O2 therapy" name="resuscitation_yes">O2 therapy</label>&nbsp;
          </div>
        </div>
      </div>

      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Eye prophylaxis</b>:
           <label><input type="radio" value="yes" name="eye_prophylaxis">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="eye_prophylaxis">No</label>&nbsp;
          </div>
        </div>

        <div class="col-md-6">
          <div class="radio">
           <b>Vitamin K given</b>:
           <label><input type="radio" value="yes" name="vitamin_K_given">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="vitamin_K_given">No</label>&nbsp;
          </div>
        </div>
      </div>

      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Drugs given:</b>:
           <label><input type="radio" value="no" name="drugs_given">No</label>&nbsp;
           <label><input type="radio" value="yes" name="drugs_given">Yes:</label>&nbsp;
           <b>Which:</b><input type="text"  name="drugs_given_yes_which" class="form-control" placeholder="Write something...">&nbsp;
          </div>
        </div>
        <div class="col-md-6">
          <div class="radio">
           <b>Feeding started within 1 hour</b>:
           <label><input type="radio" value="yes" name="feeding_started_within_1_hour">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="feeding_started_within_1_hour">No</label>&nbsp;
          </div>
        </div>
      </div>


    </fieldset>
    <!-- end of postnatal history -->
  </fieldset><br><br>
    <!-- end history of previous pregnancy -->

    <fieldset align="left">
      <legend>Chief complaints or Reasons for referral</legend>
      <div class="row">
        <div class="col-md-8">
          <div class="form-group">
            <label for="complication">(location, severity, duration, in words of caretaker):</label>
            <textarea class="form-control" rows="2" id="chief_complaints" name="chief_complaints"></textarea>
          </div>
        </div>
      </div>
    </fieldset><br><br>
<!-- end of Chief complaints or Reasons for referral -->




    <fieldset align="left">
      <legend>History of the baby</legend>
      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Fever?</b>:
           <label><input type="radio" value="yes" name="fever">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="fever">No</label>&nbsp;
          </div>
        </div>

        <div class="col-md-6">
          <div class="radio">
           <b>vomiting?</b>:
           <label><input type="radio" value="yes" name="vomiting">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="vomiting">No</label>&nbsp;
          </div>
        </div>
      </div>

      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>Feeding</b>:
           <label><input type="radio" value="EBF" name="feeding">EBF</label>&nbsp;
           <label><input type="radio" value="formula" name="feeding">Formula</label>&nbsp;
           <label><input type="radio" value="sucking well" name="feeding">Sucking well</label>&nbsp;
           <label><input type="radio" value="sucking poor" name="feeding">Sucking poor</label>&nbsp;
           <label><input type="radio" value="cup" name="feeding">Cup</label>&nbsp;
           <label><input type="radio" value="other" name="feeding">Other:</label>&nbsp;
          </div>
        </div>
        <div class="col-md-6">
          <div class="radio">
           <b>Enough breast milk</b>:
           <label><input type="radio" value="yes" name="enough_breast_milk">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="enough_breast_milk">No</label>&nbsp;
           <label><input type="radio" value="no yet (< 3days)" name="enough_breast_milk">No yet (< 3days)</label>&nbsp;
          </div>
        </div>
      </div>


      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="feeding_interval">feeding interval (hours):</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="feeding_interval" id="feeding_interval" placeholder="Enter (hours)">
            </div>
        </div>
        </div>

        <div class="col-md-6">
          <div class="radio">
           <b>Passage of urine</b>:
           <label><input type="radio" value="yes" name="passage_of_urine">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="passage_of_urine">No</label>&nbsp;
          </div>
        </div>
      </div>

      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="radio">
           <b>passage of stool</b>:
           <label><input type="radio" value="yes" name="passage_of_stool">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="passage_of_stool">No</label>&nbsp;

          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label col-sm-*" for="quality">Quality:</label>
            <div class="col-sm-*">
              <input type="text" class="form-control" name="quality" id="quality" placeholder="Enter quality">
            </div>
        </div>
        </div>
      </div>


      <!-- row -->
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="other_complaints">Other complaints:</label>
            <textarea class="form-control" rows="2" id="other_complaints" name="other_complaints"></textarea>
          </div>
        </div>

        <div class="col-md-6">
          <div class="radio">
           <b>Is baby recieve any vaccines (BCG and Polio 0)</b>:
           <label><input type="radio" value="yes" name="baby_recieve_any_vaccines">Yes</label>&nbsp;
           <label><input type="radio" value="no" name="baby_recieve_any_vaccines">No</label>&nbsp;

          </div>
        </div>

      </div>

    </fieldset><br><br>
    <!-- end of history of baby -->




    <fieldset align="center">
      <legend>PHYSICAL EXAMINATION</legend>

      <fieldset align="left">
        <legend>Vital signs</legend>
        <!-- row -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="Weight">Weight:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="weight" id="Weight" placeholder="Enter (kg)" required>
              </div>
          </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="temp">Temp:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="temp" id="temp" placeholder="Enter (‘C)" required>
              </div>
          </div>
          </div>
        </div>

        <!-- row -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="pulse">Pulse:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="pulse" id="pulse" placeholder="Enter (/min)" required>
              </div>
          </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="resp_rate">Resp rate:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="resp_rate" id="resp_rate" placeholder="Enter (/min)" required>
              </div>
          </div>
          </div>
        </div>

        <!-- row -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="SpO2">SpO2:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="SpO2" id="SpO2" placeholder="Enter (%)" required>
              </div>
          </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label col-sm-*" for="rbg">RBG:</label>
              <div class="col-sm-*">
                <input type="text" class="form-control" name="rbg" id="rbg" placeholder="Enter (mmol/L)" required>
              </div>
          </div>
          </div>
        </div>
      </fieldset><br><br>
      <!-- end of vital sign -->


      <fieldset align="left">
        <legend>Appearance</legend>

        <!-- row -->
        <div class="row">
          <div class="col-md-6">
            <div class="radio">

             <label><input type="radio" value="good" name="appearance_condition">Good</label>&nbsp;
             <label><input type="radio" value="stable" name="appearance_condition">Stable</label>&nbsp;
             <label><input type="radio" value="poor" name="appearance_condition">Poor</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

             <label><input type="radio" value="active" name="appearance_activeness">Active</label>&nbsp;
             <label><input type="radio" value="distressed" name="appearance_activeness">Distressed</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <label><input type="radio" value="malnourished" name="appearance_nourished">Malnourished</label>&nbsp;
             <label><input type="radio" value="well-nourished" name="appearance_nourished">Well-nourished</label>&nbsp;&nbsp;&nbsp;

             <b>Pathol. Cry</b>
             <label><input type="radio" value="yes" name="appearance_Pathol">Yes</label>&nbsp;
             <label><input type="radio" value="no" name="appearance_Pathol">No</label>&nbsp;
            </div>
          </div>
        </div>


        <!-- row -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="appearance_comment">Comment:</label>
              <textarea class="form-control" rows="2" id="appearance_comment" name="appearance_comment"></textarea>
            </div>
          </div>


        </div>
      </fieldset><br><br>
      <!-- end of appearance -->


      <fieldset align="left">
        <legend>Skin</legend>
        <!-- row -->
        <div class="row">
          <div class="col-md-*">
            <div class="radio">
              <b>Skin Temperature:</b>
             <label><input type="radio" value="warm" name="skin_temperature">Warm</label>&nbsp;
             <label><input type="radio" value="cold" name="skin_temperature">Cold</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

             <b>Skin Color:</b>
             <label><input type="radio" value="pink" name="skin_color">Pink</label>&nbsp;
             <label><input type="radio" value="pale" name="skin_color">Pale</label>&nbsp;
             <label><input type="radio" value="jaundiced" name="skin_color">Jaundiced</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

             <b>Turgor:</b>
             <label><input type="radio" value="normal" name="skin_turgor">Normal</label>&nbsp;
             <label><input type="radio" value="moderate" name="skin_turgor">Moderate</label>&nbsp;
             <label><input type="radio" value="severe" name="skin_turgor">Severe</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

             <b>Cyanosed:</b>
             <label><input type="radio" value="no" name="skin_cyanosed">No</label>&nbsp;&nbsp;
             <label><input type="radio" value="yes" name="skin_cyanosed">Yes</label>&nbsp;<b>if yes:</b>&nbsp;&nbsp;
             <label><input type="radio" value="central" name="skin_cyanosed_yes">Central</label>&nbsp;
             <label><input type="radio" value="peripheral" name="skin_cyanosed_yes">Peripheral</label>&nbsp;&nbsp;

             <b>Rashes:</b>
             <label><input type="radio" value="no" name="skin_rashes">No</label>&nbsp;&nbsp;
             <label><input type="radio" value="yes" name="skin_rashes">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


               <label><b>CTR:</b><input type="text" class="form-control" name="skin_ctr" id="ctr" placeholder="Enter (sec)"></label>

           </div>

            </div>
          </div>
        </fieldset><br><br>
        <!-- end of skin -->


        <fieldset align="left">
          <legend>Head I</legend>
          <!-- row -->
          <div class="row">
            <div class="col-md-*">
              <div class="radio">

              <label><input type="radio" value="micro" name="head1">Micro</label>&nbsp;
              <label><input type="radio" value="macro" name="head1">Macro</label>&nbsp;
              <label><input type="radio" value="normal" name="head1">Normal</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              <b>Shape:</b>
              <label><input type="radio" value="normal" name="head1_shape">Normal</label>&nbsp;
              <label><input type="radio" value="asym" name="head1_shape">Asym</label>&nbsp;
              <label><input type="radio" value="plagio" name="head1_shape">Plagio</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              <b>Fontanelle:</b>
              <label><input type="radio" value="normal" name="head1_fontanelle">Normal</label>&nbsp;
              <label><input type="radio" value="depressed" name="head1_fontanelle">Depressed</label>&nbsp;
              <label><input type="radio" value="bulging" name="head1_fontanelle">Bulging</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              <b>Sutures:</b>
              <label><input type="radio" value="normal" name="head1_sutures">Normal</label>&nbsp;
              <label><input type="radio" value="synostosis" name="head1_sutures">Synostosis</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              <b>Swelling/Trauma:</b>
              <label><input type="radio" value="no" name="head1_swelling_trauma">No</label>&nbsp;
              <label><input type="radio" value="yes" name="head1_swelling_trauma">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              <label><b>Size:</b><input type="text" class="form-control" name="head1_size" id="head1_size" placeholder="Enter (cm)"></label>

            </div>
            </div>
          </div>
        </fieldset><br><br>
        <!-- end of head1 -->


        <fieldset align="left">
          <legend>Head II</legend>
          <!-- row -->
          <div class="row">
            <div class="col-md-6">

              <div class="form-group">
                <label for="other_complaints">Other malformation(Eye,Ears,Nose,Mouth) :</label>
                <textarea class="form-control" rows="2" id="head2_other_malformation" name="head2_other_malformation"></textarea>
              </div>
            </div>

            <div class="col-md-6">
              <div class="radio">
                <br><br>
               <b>Eye discharge:</b>
               <label><input type="radio" value="yes" name="head2_eye_discharge">Yes</label>&nbsp;
               <label><input type="radio" value="no" name="head2_eye_discharge">No</label>&nbsp;
              </div>
            </div>
          </div>
        </fieldset><br><br>
        <!-- end of head2 -->


        <fieldset align="left">
          <legend>Neck</legend>
          <!-- row -->
          <div class="row">
            <div class="col-md-6">
              <div class="radio">

              <b>Lymphadenopathy:</b>
              <label><input type="radio" value="no" name="neck_lymphadenopathy">No</label>&nbsp;
              <label><input type="radio" value="yes" name="neck_lymphadenopathy">Yes</label>&nbsp;<b>if yes:</b>
              <label><input type="radio" value="symmetric" name="neck_lymphadenopathy_yes">Symmetric</label>&nbsp;
              <label><input type="radio" value="asymmetric" name="neck_lymphadenopathy_yes">Asymmetric</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              <b>Clavicle fractured:</b>
              <label><input type="radio" value="yes" name="neck_clavicle_fractured">Yes</label>&nbsp;
              <label><input type="radio" value="no" name="neck_clavicle_fractured">No</label>&nbsp;

            </div>
            </div>
          </div>
        </fieldset><br><br>
        <!-- end of neck -->

        <fieldset align="left">
          <legend>Breathing</legend>
          <!-- row -->
          <div class="row">
            <div class="col-md-*">
              <div class="radio">
                <b>Chest movement:</b>
                <label><input type="radio" value="norm" name="breathing_chest_movement">Norm</label>&nbsp;
                <label><input type="radio" value="path" name="breathing_chest_movement">Path</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Indrawing:</b>
                <label><input type="radio" value="yes" name="breathing_indrawing">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="breathing_indrawing">No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Breathing sounds:</b>
                <label><input type="radio" value="BBS" name="breathing_sounds">BBS</label>&nbsp;
                <label><input type="radio" value="VBS" name="breathing_sounds">VBS</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <label for="breathing_preterm">Preterm respiratory distress syndrome (RDS) score:<input type="text" class="form-control"  id="breathing_preterm" name="breathing_preterm"></label>

              </div>
            </div>
          </div>
        </fieldset><br><br>
        <!-- end of breathing -->

        <fieldset align="left">
          <legend>Heart</legend>
          <div class="row">
            <div class="col-md-*">
                <div class="radio">
                  <b>Rhythm:</b>
                  <label><input type="radio" value="regular" name="heart_rhythm">Regular</label>&nbsp;
                  <label><input type="radio" value="irregular" name="heart_rhythm">Irregular</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                  <b>Murmurs:</b>
                  <label><input type="radio" value="yes" name="heart_murmurs">Yes</label>&nbsp;
                  <label><input type="radio" value="no" name="heart_murmurs">No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                  <label for="heart_descibe">Describe:<textarea class="form-control" rows="2" id="heart_describe" name="heart_describe"></textarea></label>
                </div>
            </div>
          </div>
        </fieldset><br><br>
        <!-- end of heart -->


        <fieldset align="left">
          <legend>Abdomen</legend>
          <div class="row">
            <div class="col-md-*">
              <div class="checkbox">
                <b>Per Abdomen:</b>
                <label><input type="checkbox" value="flat" name="abdomen_flat">Flat</label>&nbsp;
                <label><input type="checkbox" value="sunken" name="abdomen_sunken">Sunken</label>&nbsp;
                <label><input type="checkbox" value="distended" name="abdomen_distended">Distended</label>&nbsp;
                <label><input type="checkbox" value="abdominal guarding(Tendemess)" name="abdomen_abdominal">Abdominal guarding(Tendemess)</label>&nbsp;
                <label><input type="checkbox" value="local resistance" name="abdomen_local">Local Resistance</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Umbillical cord/Umbillicus:</b>
                <label><input type="radio" value="normal" name="umbillical_cord">Normal</label>&nbsp;
                <label><input type="radio" value="pathol" name="umbillical_cord">Pathol</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              </div>
            </div>
          </div>

        </fieldset><br><br>
        <!-- end of abdomen -->


        <fieldset align="left">
          <legend>Genitalia</legend>
          <div class="row">
            <div class="col-md-*">
              <div class="radio">
                <b>Male:</b>
                <label><input type="radio" value="normal" name="genitalia_male">Normal</label>&nbsp;
                <label><input type="radio" value="hypospadias" name="genitalia_male">Hypospadias</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Testis(Descended):</b>
                <label><input type="radio" value="yes" name="genitalia_testis">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="genitalia_testis">No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Ambiguous genitalia:</b>
                <label><input type="radio" value="yes" name="genitalia_ambiguous">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="genitalia_ambiguous">No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Female:</b>
                <label><input type="radio" value="normal" name="genitalia_female">Normal</label>&nbsp;
                <label><input type="radio" value="hypospadias" name="genitalia_female">Pathol</label>&nbsp;&nbsp;<b>---></b>
                <label for="genitalia_female_describe">Describe:<textarea class="form-control" rows="2" id="genitalia_female_describe" name="genitalia_female_describe"></textarea></label>


              </div>
            </div>
          </div>

        </fieldset><br><br>
        <!-- end of genitalia -->


        <fieldset align="left">
          <legend>Anus</legend>
          <div class="row">
            <div class="col-md-*">
              <div class="radio">
                <b>Anus patent:</b>
                <label><input type="radio" value="yes" name="anus_patent">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="anus_patent">No</label>&nbsp;&nbsp;<b>If No Describe Position---></b>
                <label for="anus_patent_no_describe">Describe:<textarea class="form-control" rows="2" id="anus_patent_no_describe" name="anus_patent_no_describe"></textarea></label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Abdnormality (Prolapse,Fissure):</b>
                <label><input type="radio" value="yes" name="anus_abdnormality">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="anus_abdnormality">No</label>

              </div>
            </div>
          </div>

        </fieldset><br><br>
        <!-- end of anus -->


        <fieldset align="left">
          <legend>Back</legend>
          <div class="row">
            <div class="col-md-*">
              <div class="radio">
                <b>Posture:</b>
                <label><input type="radio" value="straight" name="back_posture">Straight</label>&nbsp;
                <label><input type="radio" value="c-shape" name="back_posture">C-Shape</label>&nbsp;
                <label><input type="radio" value="fixed" name="back_posture">Fixed</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                <b>Malformation:</b>
                <label><input type="radio" value="yes" name="back_malformation">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="back_malformation">No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>If yes---></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Hints for spinal bifida?(Porus,Hair):</b>
                <label><input type="radio" value="yes" name="back_malformation_hints">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="back_malformation_hints">No</label>

              </div>
            </div>
          </div>

        </fieldset><br><br>
        <!-- end of back -->



        <fieldset align="left">
          <legend>Neurology I Extremities</legend>
          <div class="row">
            <div class="col-md-*">
              <div class="radio">
                <b>Spotaneous movement:</b>
                <label><input type="radio" value="normal" name="neurology_spotaneous_movement">Normal(Variable)</label>&nbsp;
                <label><input type="radio" value="fisting" name="neurology_spotaneous_movement">Fisting</label>&nbsp;
                <label><input type="radio" value="strong flexion" name="neurology_spotaneous_movement">Strong Flexion</label>&nbsp;
                <label><input type="radio" value="asymmetric" name="neurology_spotaneous_movement">Asymmetric</label>&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Musde Tone:</b>
                <label><input type="radio" value="normal" name="neurology_musde_tone">Normal</label>&nbsp;
                <label><input type="radio" value="hypotonic" name="neurology_musde_tone">Hypotonic</label>&nbsp;
                <label><input type="radio" value="hypertonic" name="neurology_musde_tone">Hypertonic</label>&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


              </div>
            </div>
          </div>

        </fieldset><br><br>
        <!-- end of Neurology I Extremities -->


        <fieldset align="left">
          <legend>Neurology I Reflexes</legend>
          <div class="row">
            <div class="col-md-*">
              <div class="radio">
                <b>Glasping:</b>
                <label><input type="radio" value="poor" name="neurology_flexes_glasping">Poor</label>&nbsp;
                <label><input type="radio" value="yes" name="neurology_flexes_glasping">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="neurology_flexes_glasping">No</label>&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Sucking:</b>
                <label><input type="radio" value="poor" name="neurology_flexes_sucking">Poor</label>&nbsp;
                <label><input type="radio" value="yes" name="neurology_flexes_sucking">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="neurology_flexes_sucking">No</label>&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Traction:</b>
                <label><input type="radio" value="poor" name="neurology_flexes_traction">Poor</label>&nbsp;
                <label><input type="radio" value="yes" name="neurology_flexes_traction">Yes</label>&nbsp;
                <label><input type="radio" value="no" name="neurology_flexes_traction">No</label>&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <b>Moro:</b>
                <label><input type="radio" value="poor" name="neurology_flexes_moro">Poor</label>&nbsp;
                <label><input type="radio" value="normal" name="neurology_flexes_moro">Normal</label>&nbsp;
                <label><input type="radio" value="neg" name="neurology_flexes_moro">Neg</label>&nbsp;
                <label><input type="radio" value="asymmetric" name="neurology_flexes_moro">Asymmetric</label>&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

              </div>
            </div>
          </div>

        </fieldset><br><br>
        <!-- end of Neurology I Reflexes -->


        <fieldset align="left">
          <legend>Finnstroem score</legend>
          <div class="row">
            <div class="col-md-*">
              <label for="finnstroem_score">score:<textarea class="form-control" rows="2" id="finnstroem_score" name="finnstroem_score"></textarea></label>
            </div>
          </div>

        </fieldset><br><br>
        <!-- end of Finnstroem score -->


        <fieldset align="left">
          <legend>Additional Findings</legend>
          <div class="row">
            <div class="col-md-*">
              <label for="additional_findings"><textarea class="form-control" rows="2" id="additional_findings" name="additional_findings"></textarea></label>
            </div>
          </div>

        </fieldset><br><br>
        <!-- end of Additional Findings -->


        <fieldset align="left">
          <legend>Key Findings</legend>
          <div class="row">
            <div class="col-md-*">
              <label for="key_findings">(Positive & Negative)<textarea class="form-control" rows="2" id="key_findings" name="key_findings"></textarea></label>
            </div>
          </div>

        </fieldset>
        <!-- end of key Findings -->

    </fieldset>
    <!-- end of physical examination -->


            <fieldset align="left">
              <legend>Provisional Diagnoses(PDx)</legend>
              <div class="row">
                <div class="col-md-*">
                  <label for="provisional_diagnoses"><textarea class="form-control" rows="2" id="provisional_diagnoses" name="provisional_diagnoses"></textarea></label>
                </div>
              </div>

            </fieldset><br><br>
            <!-- end of Provisional Diagnoses(PDx)-->


            <fieldset align="left">
              <legend>Differential Diagnoses(DDx)</legend>
              <div class="row">
                <div class="col-md-*">
                  <label for="differential_diagnoses"><textarea class="form-control" rows="2" id="differential_diagnoses" name="differential_diagnoses"></textarea></label>
                </div>
              </div>

            </fieldset><br><br>
            <!-- end of Provisional Diagnoses(PDx)-->


            <fieldset align="center">
              <legend>MANAGEMENT</legend>

              <fieldset align="left">
                <legend>Investigation</legend>
                <div class="row">
                  <div class="col-md-*">
                    <label for="investigation"><textarea class="form-control" rows="2" id="investigation" name="investigation"></textarea></label>
                  </div>
                </div>

              </fieldset><br><br>
              <!-- end of investigation-->

              <fieldset align="left">
                <legend>Treatment</legend>
                <div class="row">
                  <div class="col-md-*">
                    <label for="treatment"><textarea class="form-control" rows="2" id="treatment" name="treatment"></textarea></label>
                  </div>
                </div>

              </fieldset><br><br>
              <!-- end of treatment-->

              <fieldset align="left">
                <legend>Supportive Care</legend>
                <div class="row">
                  <div class="col-md-*">
                    <label for="supportive_care"><textarea class="form-control" rows="2" id="supportive_care" name="supportive_care"></textarea></label>
                  </div>
                </div>

              </fieldset><br><br>
              <!-- end of supportive_care-->

              <fieldset align="left">
                <legend>Preventions</legend>
                <div class="row">
                  <div class="col-md-*">
                    <label for="preventions"><textarea class="form-control" rows="2" id="preventions" name="preventions"></textarea></label>
                  </div>
                </div>

              </fieldset><br><br>
              <!-- end of preventions-->

              <div class="row">
                <div class="col-md-6">
                    <h4 style="background-color:#88ff4d;color:#eeffe6;">{{success}}</h4>
                    <h4 style="background-color:#ff0000;color:#ffe6e6;">{{error}}</h4>
                </div>
                <div class="col-md-6">
                  <input type="submit" class="btn btn-sm art-button" name="submit"  value="Save">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="button" class="btn btn-sm art-button" name=""  ng-click="showTable()" value="Preview">
                </div>
              </div>

            </fieldset><br><br>
            <!-- end of management-->


  </form>
    </div><br><br>
    <!--******************************************* end of form div ************************************************************************-->

    <!--******************************************** table div ******************************************************************************-->
    <div ng-hide="tableDiv" style="background-color:white;">
      <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <input type="button" class="btn btn-sm art-button"  ng-click="showForm()" value="FORM">
            <input type="button" class="btn btn-sm art-button"  ng-click="showLastYearTable()" value="PREVIOUS">
        </div>
      </div>


        <!-- DATA -->
        <div class="row" ng-init="getData()">

          <fieldset>
            <legend>DATA</legend>
            <div class="row" ng-repeat="d in datas">
              <div class="col-md-2"><b>Name: </b><span>{{d.name_of_baby}}</span></div>
              <div class="col-md-2"><b>File No: </b><span>{{d.Registration_ID}}</span></div>
              <div class="col-md-2"><b>Referral from: </b><span>{{d.referral_from}}</span></div>
              <div class="col-md-2"><b>Transer from maternity: </b><span>{{d.transer_from_maternity}}</span></div>
              <div class="col-md-2"><b>Birth weight: </b><span>{{d.birth_weight+"(kg)"}}</span></div>
              <div class="col-md-2"><b>Length cm: </b><span>{{d.length_cm}}</span></div>
            </div><br>

            <div class="row" ng-repeat="d in datas">
              <div class="col-md-2"><b>Head circumference cm: </b><span>{{d.head_circumference_cm+"(cm)"}}</sapn></div>
              <div class="col-md-2"><b>APGAR score: </b><span>{{d.apgar_score}}</span></div>
              <div class="col-md-2"><b>GA(In weeks): </b><span>{{d.ga}}</span></div>
              <div class="col-md-2"><b>Date of birth: </b><span>{{d.date_birth}}</span></div>
              <div class="col-md-2"><b>Admission date: </b><span>{{d.admission_date}}</span></div>
              <div class="col-md-2"><b>PMTCT: </b><span>{{d.pmtct}}</span></div>
            </div><br>

            <div class="row" ng-repeat="d in datas">
              <div class="col-md-2"><b>Sex: </b><span>{{d.gender}}</span></div>
            </div>
          </fieldset>

        </div><br>
        <!-- end -->

        <!-- row -->
        <div class="row" ng-init="getPrevious()">
          <fieldset>
            <legend>HISTORY-PREVIOUS</legend>
            <div class="row" ng-repeat="p in previous">
              <div class="col-md-2"><b>Marital status: </b><span>{{p.marital_status}}</span></div>
              <div class="col-md-2"><b>Chronical maternal illinesses: </b><span>{{p.chronical_maternal_illiness}}</span></div>
              <div class="col-md-2"><b>Family illnesses: </b><span>{{p.family_illnesses}}</span></div>
              <div class="col-md-2"><b>Graviday: </b><span>{{p.gravida}}</span></div>
            </div><br>

            <div class="row" ng-repeat="p in previous">
              <div class="col-md-2"><b>Para: </b><span>{{p.para}}</span></div>
              <div class="col-md-2"><b>Number of living children: </b><span>{{p.number_of_living_children}}</span></div>
              <div class="col-md-2"><b>Known problem of living children: </b><span>{{p.known_problem_of_living_children}}</span></div>
              <div class="col-md-6"><b>Complication during previous pregnancies: </b><span>{{p.complication_during_previous_pregnancies}}</span></div>
            </div>

          </fieldset>
        </div><br>

        <!-- row -->
        <div class="row" ng-init="getAntenatal()">
          <fieldset>
            <legend>Antenatal History</legend>
            <div class="row" ng-repeat="a in antenatals">
              <div class="col-md-2"><b>LNMP: </b><span>{{a.lnmp}}</span></div>
              <div class="col-md-2"><b>EDD: </b><span>{{a.edd}}</span></div>
              <div class="col-md-2"><b>VDRL: </b><span>{{a.vdrl}}</span></div>
              <div class="col-md-2"><b>Malaria: </b><span>{{a.malaria}}</span></div>
              <div class="col-md-2"><b>Hep. B: </b><span>{{a.hep_b}}</span></div>
              <div class="col-md-2"><b>HB Level: </b><span>{{a.hb_level}}</span></div>
            </div><br>

            <div class="row" ng-repeat="a in antenatals">
              <div class="col-md-2"><b>Hypertension: </b><span>{{a.hypertension}}</span></div>
              <div class="col-md-2"><b>Blood pressure: </b><span>{{a.blood_pressure}}</span></div>
              <div class="col-md-2"><b>Drug abuse: </b><span>{{a.drug_abuse}}</span></div>
              <div class="col-md-2"><b>Blood group + Rh: </b><span>{{a.blood_group_rh}}</span></div>
              <div class="col-md-2"><b>ANC Attended: </b><span>{{a.anc_attended}}</span></div>
              <div class="col-md-2"><b>Where ANC done: </b><span>{{a.where_anc_done}}</span></div>
            </div><br>

            <div class="row" ng-repeat="a in antenatals">
              <div class="col-md-2"><b>Number of ANC-visits: </b><span>{{a.number_of_visits}}</span></div>
              <div class="col-md-2"><b>GA at 1st ANC-visit: </b><span>{{a.ga_at_1st_visit}}</span></div>
            </div>

          </fieldset>
        </div><br>


        <!-- row -->
        <div class="row" ng-init="getDelivery()">
          <fieldset>
            <legend>Delivery History</legend>
            <div class="row" ng-repeat="d in delivery">
              <div class="col-md-2"><b>Maternal fever >38 C: </b><span>{{d.maternal_fever}}</span></div>
              <div class="col-md-2"><b>PROM: </b><span>{{d.prom}}</span></div>
              <div class="col-md-2"><b>PROM duration: </b><span>{{d.prom_yes_hrs}}</span></div>
              <div class="col-md-2"><b>AB treatment: </b><span>{{d.ab_treatment}}</span></div>
              <div class="col-md-2"><b>Drug: </b><span>{{d.ab_treatment_yes_drug}}</span></div>
              <div class="col-md-2"><b>Amniotic fluid: </b><span>{{d.amniotic_fluid}}</span></div>
            </div><br>

            <div class="row" ng-repeat="d in delivery">
              <div class="col-md-2"><b>Abnormalities of placenta: </b><span>{{d.abnormalities_of_placenta}}</span></div>
              <div class="col-md-2"><b>What: </b><span>{{d.abnormalities_of_placenta_yes}}</span></div>
              <div class="col-md-2"><b>Abnormal presentation: </b><span>{{d.abnormal_presentation}}</span></div>
              <div class="col-md-2"><b>What: </b><span>{{d.abnormal_presentation_yes}}</span></div>
              <div class="col-md-2"><b>Mode of delivery: </b><span>{{d.mode_of_delivery}}</span></div>
              <div class="col-md-2"><b>If CS: </b><span>{{d.cs}}</span></div>
            </div><br>

            <div class="row" ng-repeat="d in delivery">
              <div class="col-md-2"><b>Duration of CS: </b><span></span></div>
              <div class="col-md-2"><b>Indication: </b><span>{{d.indication}}</span></div>
              <div class="col-md-2"><b>1st Stage: </b><span>{{d.duration_of_labour_stage1}}</span></div>
              <div class="col-md-2"><b>2nd Stage: </b><span>{{d.duration_of_labour_stage2}}</span></div>
              <div class="col-md-2"><b>3rd Stage: </b><span>{{d.duration_of_labour_stage3}}</span></div>
              <div class="col-md-2"><b>Obstructed labour: </b><span>{{d.obstructed_labour}}</span></div>
            </div><br>

            <div class="row" ng-repeat="d in delivery">
              <div class="col-md-2"><b>Place of delivery: </b><span>{{d.place_of_delivery}}</span></div>
              <div class="col-md-2"><b>Delivery attendant: </b><span>{{d.delivery_attendant}}</span></div>
              <div class="col-md-2"><b>If assisted delivery,WHY?: </b><span>{{d.if_assisted_delivery_why}}</span></div>
            </div>

          </fieldset>

        </div><br>


        <!-- row -->
        <div class="row" ng-init="getPostnatal()">
          <fieldset>
            <legend>Postnatal History</legend>
            <div class="row" ng-repeat="p in postnatals">
              <div class="col-md-2"><b>Problems of baby after birth: </b><span>{{p.problems_of_baby_after_birth}}</span></div>
              <div class="col-md-2"><b>Resuscitation after birth: </b><span>{{p.resuscitation}}</span></div>
              <div class="col-md-2"><b>if yes: </b><span>{{p.resuscitation_yes}}</span></div>
              <div class="col-md-2"><b>Eye prophylaxis: </b><span>{{p.eye_prophylaxis}}</span></div>
              <div class="col-md-2"><b>Vitamin K given: </b><span>{{p.vitamin_K_given}}</span></div>
              <div class="col-md-2"><b>Feeding started within 1 hour: </b><span>{{p.feeding_started_within_1_hour}}</span></div>
            </div><br>

            <div class="row" ng-repeat="p in postnatals">
              <div class="col-md-2"><b>Drugs given: </b><span>{{p.drugs_given}}</span></div>
              <div class="col-md-4"><b>Which: </b><span>{{p.drugs_given_yes_which}}</span></div>
              <div class="col-md-6"><b>Chief complaints or Reasons for referral: </b><span>{{p.chief_complaints}}</span></div>
              </div><br>
            </fieldset>
        </div>


        <!-- row -->
        <div class="row" ng-init="getBaby()">
          <fieldset>
            <legend>History of the Baby</legend>
            <div class="row" ng-repeat="p in babies">
              <div class="col-md-2"><b>Fever: </b><span>{{p.fever}}</span></div>
              <div class="col-md-2"><b>vomiting: </b><span>{{p.vomiting}}</span></div>
              <div class="col-md-2"><b>Feeding: </b><span>{{p.feeding}}</span></div>
              <div class="col-md-2"><b>Enough breast milk: </b><span>{{p.enough_breast_milk}}</span></div>
              <div class="col-md-2"><b>Feeding interval (hours): </b><span>{{p.feeding_interval}}</span></div>
              <div class="col-md-2"><b>Passage of urine: </b><span>{{p.passage_of_urine}}</span></div>
            </div><br>

            <div class="row" ng-repeat="p in babies">
              <div class="col-md-2"><b>Passage of stool: </b><span>{{p.passage_of_stool}}</span></div>
              <div class="col-md-2"><b>Quality: </b><span>{{p.quality}}</span></div>
              <div class="col-md-6"><b>Is baby recieve any vaccines (BCG and Polio 0): </b><span>{{p.baby_recieve_any_vaccines}}</span></div>
              <div class="col-md-2"><b>Other complaints: </b><span>{{p.other_complaints}}</span>
              </div><br>
            </fieldset>
        </div><br>





        <fieldset>
          <legend>PHYSICAL EXAMINATION</legend>
        <!-- row -->
        <div class="row" ng-init="getPhysical1()">
          <fieldset align="left">
            <legend>Vital Signs</legend>
            <div class="row" ng-repeat="p in examination1">
              <div class="col-md-2"><b>Weight: </b><span>{{p.weight+"(kg)"}}</span></div>
              <div class="col-md-2"><b>Temp: </b><span>{{p.temp+"('c)"}}</span></div>
              <div class="col-md-2"><b>Pulse: </b><span>{{p.pulse+"(/min)"}}</span></div>
              <div class="col-md-2"><b>Resp rate: </b><span>{{p.resp_rate+"(/min)"}}</span></div>
              <div class="col-md-2"><b>SpO2: </b><span>{{p.SpO2+"(%)"}}</span></div>
              <div class="col-md-2"><b>RBG: </b><span>{{p.rbg+"(mmol/L)"}}</span>
            </div>
          </fieldset><br>

          <fieldset align="left">
            <legend>Appearance</legend>
            <div class="row" ng-repeat="p in examination1">
              <div class="col-md-2"><b>General Appearance:</b></div>
              <div class="col-md-2"><span>{{p.appearance_condition}}</span></div>
              <div class="col-md-2"><span>{{p.appearance_activeness}}</span></div>
              <div class="col-md-2"><span>{{p.appearance_nourished}}</span></div>
              <div class="col-md-2"><b>Pathol. Cry: </b><span>{{p.appearance_Pathol}}</span></div>

            </div>
              <div class="row" ng-repeat="p in examination1">
                <div class="col-md-8"><b>Comment: </b><span>{{p.appearance_comment}}</span></div>
              </div>
          </fieldset><br>

          <fieldset align="left">
            <legend>Skin</legend>
            <div class="row" ng-repeat="p in examination1">
              <div class="col-md-2"><b>Skin Temperature: </b><span>{{p.skin_temperature}}</span></div>
              <div class="col-md-2"><b>Skin Color: </b><span>{{p.skin_color}}</span></div>
              <div class="col-md-2"><b>Turgor: </b><span>{{p.skin_turgor}}</span></div>
              <div class="col-md-2"><b>Cyanosed: </b><span>{{p.skin_cyanosed}}</span></div>
              <div class="col-md-2"><b>If yes: </b><span>{{p.skin_cyanosed_yes}}</span></div>
              <div class="col-md-2"><b>Rashes: </b><span>{{p.skin_rashes}}</span></div>
            </div>

            <div class="row" ng-repeat="p in examination1">
              <div class="col-md-2"><b>CTR: </b><span>{{p.skin_ctr}}</span></div>
            </div>
        </fieldset><br>


        <fieldset align="left">
          <legend>Head I</legend>
          <div class="row" ng-repeat="p in examination1">
            <div class="col-md-2"><b>Head I: </b><span>{{p.head1}}</span></div>
            <div class="col-md-2"><b>Shape: </b><span>{{p.head1_shape}}</span></div>
            <div class="col-md-2"><b>Fontanelle: </b><span>{{p.head1_fontanelle}}</span></div>
            <div class="col-md-2"><b>Sutures: </b><span>{{p.head1_sutures}}</span></div>
            <div class="col-md-2"><b> Swelling/Trauma: </b><span>{{p.head1_swelling_trauma}}</span></div>
            <div class="col-md-2"><b>Size: </b><span>{{p.head1_size}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Head II</legend>
          <div class="row" ng-repeat="p in examination1">
            <div class="col-md-6"><b>Other malformation(Eye,Ears,Nose,Mouth): </b><span>{{p.head2_other_malformation}}</span></div>
            <div class="col-md-2"><b>Eye discharge: </b><span>{{p.head2_eye_discharge}}</span></div>
          </div>
        </fieldset><br>


        <fieldset align="left">
          <legend>Neck</legend>
          <div class="row" ng-repeat="p in examination1">
            <div class="col-md-2"><b>Lymphadenopathy: </b><span>{{p.neck_lymphadenopathy}}</span></div>
            <div class="col-md-2"><b>If yes: </b><span>{{p.neck_lymphadenopathy_yes}}</span></div>
            <div ng-init="getPhysical2()">
              <div class="col-md-2" ng-repeat="p2 in examination2"><b>Clavicle fractured: </b><span>{{p2.neck_clavicle_fractured}}</span></div>
            </div>


          </div>
        </fieldset><br>

        <div ng-init="getPhysical2()">
        <fieldset align="left">
          <legend>Breathing</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-2"><b>Chest movement: </b><span>{{p.breathing_chest_movement}}</span></div>
            <div class="col-md-2"><b>Indrawing: </b><span>{{p.breathing_indrawing}}</span></div>
            <div class="col-md-2"><b>Breathing sounds: </b><span>{{p.breathing_sounds}}</span></div>
            <div class="col-md-4"><b>Preterm respiratory distress syndrome (RDS) score: </b><span>{{p.breathing_preterm}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Heart</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-2"><b>Rhythm: </b><span>{{p.heart_rhythm}}</span></div>
            <div class="col-md-2"><b>Murmurs: </b><span>{{p.heart_murmurs}}</span></div>
            <div class="col-md-8"><b>Describe: </b><span>{{p.heart_describe}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Abdomen</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-2"><b>Per Abdomen: </b><span>{{p.abdomen}}</span></div>
            <div class="col-md-2"><b>Umbillical cord/Umbillicus: </b><span>{{p.umbillical_cord}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Genitalia</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-2"><b>Male: </b><span>{{p.genitalia_male}}</span></div>
            <div class="col-md-2"><b>Testis(Descended): </b><span>{{p.genitalia_testis}}</span></div>
            <div class="col-md-2"><b>Ambiguous genitalia: </b><span>{{p.genitalia_ambiguous}}</span></div>
            <div class="col-md-2"><b>Female: </b><span>{{p.genitalia_female}}</span></div>
            <div class="col-md-4"><b>Describe: </b><span>{{p.genitalia_female_describe}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Anus</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-2"><b>Anus patent: </b><span>{{p.anus_patent}}</span></div>
            <div class="col-md-8"><b>If No Describe Position: </b><span>{{p.anus_patent_no_describe}}</span></div>
            <div class="col-md-2"><b>Abdnormality (Prolapse,Fissure): </b><span>{{p.anus_abdnormality}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Back</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-2"><b>Posture: </b><span>{{p.back_posture}}</span></div>
            <div class="col-md-2"><b>Malformation: </b><span>{{p.back_malformation}}</span></div>
            <div class="col-md-6"><b>If yes---> Hints for spinal bifida?(Porus,Hair): </b><span>{{p.back_malformation_hints}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Neurology I Extremities</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-2"><b>Spotaneous movement: </b><span>{{p.neurology_spotaneous_movement}}</span></div>
            <div class="col-md-2"><b>Musde tone: </b><span>{{p.neurology_musde_tone}}</span></div>

          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Neurology I Reflexes</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-2"><b>Glasping: </b><span>{{p.neurology_flexes_glasping}}</span></div>
            <div class="col-md-2"><b>Sucking: </b><span>{{p.neurology_flexes_sucking}}</span></div>
            <div class="col-md-2"><b>Traction: </b><span>{{p.neurology_flexes_traction}}</span></div>
            <div class="col-md-2"><b>Moro: </b><span>{{p.neurology_flexes_moro}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Finnstroem score</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-12"><span>{{p.finnstroem_score}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Additional Findings</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-12"><span>{{p.additional_findings}}</span></div>
          </div>
        </fieldset><br>

        <fieldset align="left">
          <legend>Key Findings</legend>
          <div class="row" ng-repeat="p in examination2">
            <div class="col-md-12"><span>{{p.key_findings}}</span></div>
          </div>
        </fieldset><br>

        </div>
      </div>
      </fieldset><br>

        <!-- row -->
        <div class="row" ng-init="getManagement()">
          <fieldset>
            <legend>Provisional Diagnoses(PDx)</legend>
            <div class="row" ng-repeat="p in management">
              <div class="col-md-12"><span>{{p.provisional_diagnoses}}</span></div>
            </div>
          </fieldset><br>

          <fieldset>
            <legend>Differential Diagnoses(DDx)</legend>
            <div class="row" ng-repeat="p in management">
              <div class="col-md-12"><span>{{p.differential_diagnoses}}</span></div>
            </div>
          </fieldset><br>

          <fieldset>
            <legend>Differential Diagnoses(DDx)</legend>
            <div class="row" ng-repeat="p in management">
              <div class="col-md-12"><span>{{p.differential_diagnoses}}</span></div>
            </div>
          </fieldset><br>

        </div><br>


        <fieldset>
          <legend>MANAGEMENT</legend>
          <!-- row -->
          <div class="row" ng-init="getManagement()">
            <fieldset align="left">
              <legend>Investigation</legend>
              <div class="row" ng-repeat="p in management">
                <div class="col-md-12"><span>{{p.investigation}}</span></div>
              </div>
            </fieldset><br>

            <fieldset align="left">
              <legend>Treatment</legend>
              <div class="row" ng-repeat="p in management">
                <div class="col-md-12"><span>{{p.treatment}}</span></div>
              </div>
            </fieldset><br>

            <fieldset align="left">
              <legend>Supportive Care</legend>
              <div class="row" ng-repeat="p in management">
                <div class="col-md-12"><span>{{p.supportive_care}}</span></div>
              </div>
            </fieldset><br>

            <fieldset align="left">
              <legend>Preventions</legend>
              <div class="row" ng-repeat="p in management">
                <div class="col-md-12"><span>{{p.preventions}}</span></div>
              </div>
            </fieldset><br>

          </div><br>
        </fieldset>


    </div>


    <!--******************************************* end of  table div ************************************************************-->

    <!--**************************************preview last years data  ******************************************************************-->
    <div ng-hide="lastYears">
      <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <input type="button" class="btn btn-sm art-button"  ng-click="showForm()" value="FORM">
            <input type="button" class="btn btn-sm art-button"  ng-click="showTable()" value="CURRENT">
        </div>
      </div>

        <fieldset>
          <legend>PREVIOUS NEONATAL CARE ADMISSION</legend>
          <table class="table">
            <th>#</th>
            <th>ALL YEARS</th>

            <?php
              $sn = 1;
              $select_year = mysqli_query($conn,"SELECT DISTINCT(YEAR(date_birth)) as 'year' FROM tbl_neonatal_care_data WHERE  YEAR(date_birth) !='0' and  Registration_ID = $registration_id");

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
                    <h4 class="modal-title" style="font-weight:bold;color:white;">NEONATAL CARE ADMISSION DETAILS</h4>
                  </div>
                  <div class="modal-body">
                    <!-- DATA -->
                    <div class="row" ng-init="getDataByYear(2021)">

                      <fieldset>
                        <legend>DATA</legend>
                        <div class="row" ng-repeat="d in datas1">
                          <div class="col-md-2"><b>Name: </b><span>{{d.name_of_baby}}</span></div>
                          <div class="col-md-2"><b>File No: </b><span>{{d.Registration_ID}}</span></div>
                          <div class="col-md-2"><b>Referral from: </b><span>{{d.referral_from}}</span></div>
                          <div class="col-md-2"><b>Transer from maternity: </b><span>{{d.transer_from_maternity}}</span></div>
                          <div class="col-md-2"><b>Birth weight: </b><span>{{d.birth_weight+"(kg)"}}</span></div>
                          <div class="col-md-2"><b>Length cm: </b><span>{{d.length_cm}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="d in datas1">
                          <div class="col-md-2"><b>Head circumference cm: </b><span>{{d.head_circumference_cm+"(cm)"}}</sapn></div>
                          <div class="col-md-2"><b>APGAR score: </b><span>{{d.apgar_score}}</span></div>
                          <div class="col-md-2"><b>GA(In weeks): </b><span>{{d.ga}}</span></div>
                          <div class="col-md-2"><b>Date of birth: </b><span>{{d.date_birth}}</span></div>
                          <div class="col-md-2"><b>Admission date: </b><span>{{d.admission_date}}</span></div>
                          <div class="col-md-2"><b>PMTCT: </b><span>{{d.pmtct}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="d in datas1">
                          <div class="col-md-2"><b>Sex: </b><span>{{d.gender}}</span></div>
                        </div>
                      </fieldset>

                    </div><br>
                    <!-- end -->

                    <!-- row -->
                    <div class="row" ng-init="getPreviousByYear(2021)">
                      <fieldset>
                        <legend>HISTORY-PREVIOUS</legend>
                        <div class="row" ng-repeat="p1 in previous1">
                          <div class="col-md-2"><b>Marital status: </b><span>{{p1.marital_status}}</span></div>
                          <div class="col-md-2"><b>Chronical maternal illinesses: </b><span>{{p1.chronical_maternal_illiness}}</span></div>
                          <div class="col-md-2"><b>Family illnesses: </b><span>{{p1.family_illnesses}}</span></div>
                          <div class="col-md-2"><b>Graviday: </b><span>{{p1.gravida}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="p1 in previous1">
                          <div class="col-md-2"><b>Para: </b><span>{{p1.para}}</span></div>
                          <div class="col-md-2"><b>Number of living children: </b><span>{{p1.number_of_living_children}}</span></div>
                          <div class="col-md-2"><b>Known problem of living children: </b><span>{{p1.known_problem_of_living_children}}</span></div>
                          <div class="col-md-6"><b>Complication during previous pregnancies: </b><span>{{p1.complication_during_previous_pregnancies}}</span></div>
                        </div>

                      </fieldset>
                    </div><br>

                    <!-- row -->
                    <div class="row" ng-init="getAntenatalByYear(2021)">
                      <fieldset>
                        <legend>Antenatal History</legend>
                        <div class="row" ng-repeat="a in antenatals1">
                          <div class="col-md-2"><b>LNMP: </b><span>{{a.lnmp}}</span></div>
                          <div class="col-md-2"><b>EDD: </b><span>{{a.edd}}</span></div>
                          <div class="col-md-2"><b>VDRL: </b><span>{{a.vdrl}}</span></div>
                          <div class="col-md-2"><b>Malaria: </b><span>{{a.malaria}}</span></div>
                          <div class="col-md-2"><b>Hep. B: </b><span>{{a.hep_b}}</span></div>
                          <div class="col-md-2"><b>HB Level: </b><span>{{a.hb_level}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="a in antenatals1">
                          <div class="col-md-2"><b>Hypertension: </b><span>{{a.hypertension}}</span></div>
                          <div class="col-md-2"><b>Blood pressure: </b><span>{{a.blood_pressure}}</span></div>
                          <div class="col-md-2"><b>Drug abuse: </b><span>{{a.drug_abuse}}</span></div>
                          <div class="col-md-2"><b>Blood group + Rh: </b><span>{{a.blood_group_rh}}</span></div>
                          <div class="col-md-2"><b>ANC Attended: </b><span>{{a.anc_attended}}</span></div>
                          <div class="col-md-2"><b>Where ANC done: </b><span>{{a.where_anc_done}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="a in antenatals1">
                          <div class="col-md-2"><b>Number of ANC-visits: </b><span>{{a.number_of_visits}}</span></div>
                          <div class="col-md-2"><b>GA at 1st ANC-visit: </b><span>{{a.ga_at_1st_visit}}</span></div>
                        </div>

                      </fieldset>
                    </div><br>


                    <!-- row -->
                    <div class="row" ng-init="getDeliveryByYear(2021)">
                      <fieldset>
                        <legend>Delivery History</legend>
                        <div class="row" ng-repeat="d in delivery1">
                          <div class="col-md-2"><b>Maternal fever >38 C: </b><span>{{d.maternal_fever}}</span></div>
                          <div class="col-md-2"><b>PROM: </b><span>{{d.prom}}</span></div>
                          <div class="col-md-2"><b>PROM duration: </b><span>{{d.prom_yes_hrs}}</span></div>
                          <div class="col-md-2"><b>AB treatment: </b><span>{{d.ab_treatment}}</span></div>
                          <div class="col-md-2"><b>Drug: </b><span>{{d.ab_treatment_yes_drug}}</span></div>
                          <div class="col-md-2"><b>Amniotic fluid: </b><span>{{d.amniotic_fluid}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="d in delivery1">
                          <div class="col-md-2"><b>Abnormalities of placenta: </b><span>{{d.abnormalities_of_placenta}}</span></div>
                          <div class="col-md-2"><b>What: </b><span>{{d.abnormalities_of_placenta_yes}}</span></div>
                          <div class="col-md-2"><b>Abnormal presentation: </b><span>{{d.abnormal_presentation}}</span></div>
                          <div class="col-md-2"><b>What: </b><span>{{d.abnormal_presentation_yes}}</span></div>
                          <div class="col-md-2"><b>Mode of delivery: </b><span>{{d.mode_of_delivery}}</span></div>
                          <div class="col-md-2"><b>If CS: </b><span>{{d.cs}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="d in delivery1">
                          <div class="col-md-2"><b>Duration of CS: </b><span></span></div>
                          <div class="col-md-2"><b>Indication: </b><span>{{d.indication}}</span></div>
                          <div class="col-md-2"><b>1st Stage: </b><span>{{d.duration_of_labour_stage1}}</span></div>
                          <div class="col-md-2"><b>2nd Stage: </b><span>{{d.duration_of_labour_stage2}}</span></div>
                          <div class="col-md-2"><b>3rd Stage: </b><span>{{d.duration_of_labour_stage3}}</span></div>
                          <div class="col-md-2"><b>Obstructed labour: </b><span>{{d.obstructed_labour}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="d in delivery1">
                          <div class="col-md-2"><b>Place of delivery: </b><span>{{d.place_of_delivery}}</span></div>
                          <div class="col-md-2"><b>Delivery attendant: </b><span>{{d.delivery_attendant}}</span></div>
                          <div class="col-md-2"><b>If assisted delivery,WHY?: </b><span>{{d.if_assisted_delivery_why}}</span></div>
                        </div>

                      </fieldset>

                    </div><br>


                    <!-- row -->
                    <div class="row" ng-init="getPostnatalByYear(2021)">
                      <fieldset>
                        <legend>Postnatal History</legend>
                        <div class="row" ng-repeat="p in postnatals1">
                          <div class="col-md-2"><b>Problems of baby after birth: </b><span>{{p.problems_of_baby_after_birth}}</span></div>
                          <div class="col-md-2"><b>Resuscitation after birth: </b><span>{{p.resuscitation}}</span></div>
                          <div class="col-md-2"><b>if yes: </b><span>{{p.resuscitation_yes}}</span></div>
                          <div class="col-md-2"><b>Eye prophylaxis: </b><span>{{p.eye_prophylaxis}}</span></div>
                          <div class="col-md-2"><b>Vitamin K given: </b><span>{{p.vitamin_K_given}}</span></div>
                          <div class="col-md-2"><b>Feeding started within 1 hour: </b><span>{{p.feeding_started_within_1_hour}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="p in postnatals1">
                          <div class="col-md-2"><b>Drugs given: </b><span>{{p.drugs_given}}</span></div>
                          <div class="col-md-4"><b>Which: </b><span>{{p.drugs_given_yes_which}}</span></div>
                          <div class="col-md-6"><b>Chief complaints or Reasons for referral: </b><span>{{p.chief_complaints}}</span></div>
                          </div><br>
                        </fieldset>
                    </div>


                    <!-- row -->
                    <div class="row" ng-init="getBabyByYear(2021)">
                      <fieldset>
                        <legend>History of the Baby</legend>
                        <div class="row" ng-repeat="p in babies1">
                          <div class="col-md-2"><b>Fever: </b><span>{{p.fever}}</span></div>
                          <div class="col-md-2"><b>vomiting: </b><span>{{p.vomiting}}</span></div>
                          <div class="col-md-2"><b>Feeding: </b><span>{{p.feeding}}</span></div>
                          <div class="col-md-2"><b>Enough breast milk: </b><span>{{p.enough_breast_milk}}</span></div>
                          <div class="col-md-2"><b>Feeding interval (hours): </b><span>{{p.feeding_interval}}</span></div>
                          <div class="col-md-2"><b>Passage of urine: </b><span>{{p.passage_of_urine}}</span></div>
                        </div><br>

                        <div class="row" ng-repeat="p in babies1">
                          <div class="col-md-2"><b>Passage of stool: </b><span>{{p.passage_of_stool}}</span></div>
                          <div class="col-md-2"><b>Quality: </b><span>{{p.quality}}</span></div>
                          <div class="col-md-6"><b>Is baby recieve any vaccines (BCG and Polio 0): </b><span>{{p.baby_recieve_any_vaccines}}</span></div>
                          <div class="col-md-2"><b>Other complaints: </b><span>{{p.other_complaints}}</span>
                          </div><br>
                        </fieldset>
                    </div><br>





                    <fieldset>
                      <legend>PHYSICAL EXAMINATION</legend>
                    <!-- row -->
                    <div class="row" ng-init="getPhysical1ByYear(2021)">
                      <fieldset align="left">
                        <legend>Vital Signs</legend>
                        <div class="row" ng-repeat="p in examination11">
                          <div class="col-md-2"><b>Weight: </b><span>{{p.weight+"(kg)"}}</span></div>
                          <div class="col-md-2"><b>Temp: </b><span>{{p.temp+"('c)"}}</span></div>
                          <div class="col-md-2"><b>Pulse: </b><span>{{p.pulse+"(/min)"}}</span></div>
                          <div class="col-md-2"><b>Resp rate: </b><span>{{p.resp_rate+"(/min)"}}</span></div>
                          <div class="col-md-2"><b>SpO2: </b><span>{{p.SpO2+"(%)"}}</span></div>
                          <div class="col-md-2"><b>RBG: </b><span>{{p.rbg+"(mmol/L)"}}</span>
                        </div>
                      </fieldset><br>

                      <fieldset align="left">
                        <legend>Appearance</legend>
                        <div class="row" ng-repeat="p in examination11">
                          <div class="col-md-2"><b>General Appearance:</b></div>
                          <div class="col-md-2"><span>{{p.appearance_condition}}</span></div>
                          <div class="col-md-2"><span>{{p.appearance_activeness}}</span></div>
                          <div class="col-md-2"><span>{{p.appearance_nourished}}</span></div>
                          <div class="col-md-2"><b>Pathol. Cry: </b><span>{{p.appearance_Pathol}}</span></div>

                        </div>
                          <div class="row" ng-repeat="p in examination11">
                            <div class="col-md-8"><b>Comment: </b><span>{{p.appearance_comment}}</span></div>
                          </div>
                      </fieldset><br>

                      <fieldset align="left">
                        <legend>Skin</legend>
                        <div class="row" ng-repeat="p in examination11">
                          <div class="col-md-2"><b>Skin Temperature: </b><span>{{p.skin_temperature}}</span></div>
                          <div class="col-md-2"><b>Skin Color: </b><span>{{p.skin_color}}</span></div>
                          <div class="col-md-2"><b>Turgor: </b><span>{{p.skin_turgor}}</span></div>
                          <div class="col-md-2"><b>Cyanosed: </b><span>{{p.skin_cyanosed}}</span></div>
                          <div class="col-md-2"><b>If yes: </b><span>{{p.skin_cyanosed_yes}}</span></div>
                          <div class="col-md-2"><b>Rashes: </b><span>{{p.skin_rashes}}</span></div>
                        </div>

                        <div class="row" ng-repeat="p in examination11">
                          <div class="col-md-2"><b>CTR: </b><span>{{p.skin_ctr}}</span></div>
                        </div>
                    </fieldset><br>


                    <fieldset align="left">
                      <legend>Head I</legend>
                      <div class="row" ng-repeat="p in examination11">
                        <div class="col-md-2"><b>Head I: </b><span>{{p.head1}}</span></div>
                        <div class="col-md-2"><b>Shape: </b><span>{{p.head1_shape}}</span></div>
                        <div class="col-md-2"><b>Fontanelle: </b><span>{{p.head1_fontanelle}}</span></div>
                        <div class="col-md-2"><b>Sutures: </b><span>{{p.head1_sutures}}</span></div>
                        <div class="col-md-2"><b> Swelling/Trauma: </b><span>{{p.head1_swelling_trauma}}</span></div>
                        <div class="col-md-2"><b>Size: </b><span>{{p.head1_size}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Head II</legend>
                      <div class="row" ng-repeat="p in examination11">
                        <div class="col-md-6"><b>Other malformation(Eye,Ears,Nose,Mouth): </b><span>{{p.head2_other_malformation}}</span></div>
                        <div class="col-md-2"><b>Eye discharge: </b><span>{{p.head2_eye_discharge}}</span></div>
                      </div>
                    </fieldset><br>


                    <fieldset align="left">
                      <legend>Neck</legend>
                      <div class="row" ng-repeat="p in examination11">
                        <div class="col-md-2"><b>Lymphadenopathy: </b><span>{{p.neck_lymphadenopathy}}</span></div>
                        <div class="col-md-2"><b>If yes: </b><span>{{p.neck_lymphadenopathy_yes}}</span></div>

                        <div>
                          <div class="col-md-2" ng-repeat="p2 in examination21"><b>Clavicle fractured: </b><span>{{p2.neck_clavicle_fractured}}</span></div>
                        </div>


                      </div>
                    </fieldset><br>

                    <div ng-init="getPhysical2ByYear(2021)">
                    <fieldset align="left">
                      <legend>Breathing</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-2"><b>Chest movement: </b><span>{{p2.breathing_chest_movement}}</span></div>
                        <div class="col-md-2"><b>Indrawing: </b><span>{{p2.breathing_indrawing}}</span></div>
                        <div class="col-md-2"><b>Breathing sounds: </b><span>{{p2.breathing_sounds}}</span></div>
                        <div class="col-md-4"><b>Preterm respiratory distress syndrome (RDS) score: </b><span>{{p2.breathing_preterm}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Heart</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-2"><b>Rhythm: </b><span>{{p2.heart_rhythm}}</span></div>
                        <div class="col-md-2"><b>Murmurs: </b><span>{{p2.heart_murmurs}}</span></div>
                        <div class="col-md-8"><b>Describe: </b><span>{{p2.heart_describe}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Abdomen</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-2"><b>Per Abdomen: </b><span>{{p2.abdomen}}</span></div>
                        <div class="col-md-2"><b>Umbillical cord/Umbillicus: </b><span>{{p2.umbillical_cord}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Genitalia</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-2"><b>Male: </b><span>{{p2.genitalia_male}}</span></div>
                        <div class="col-md-2"><b>Testis(Descended): </b><span>{{p2.genitalia_testis}}</span></div>
                        <div class="col-md-2"><b>Ambiguous genitalia: </b><span>{{p2.genitalia_ambiguous}}</span></div>
                        <div class="col-md-2"><b>Female: </b><span>{{p2.genitalia_female}}</span></div>
                        <div class="col-md-4"><b>Describe: </b><span>{{p2.genitalia_female_describe}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Anus</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-2"><b>Anus patent: </b><span>{{p2.anus_patent}}</span></div>
                        <div class="col-md-8"><b>If No Describe Position: </b><span>{{p2.anus_patent_no_describe}}</span></div>
                        <div class="col-md-2"><b>Abdnormality (Prolapse,Fissure): </b><span>{{p2.anus_abdnormality}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Back</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-2"><b>Posture: </b><span>{{p2.back_posture}}</span></div>
                        <div class="col-md-2"><b>Malformation: </b><span>{{p2.back_malformation}}</span></div>
                        <div class="col-md-6"><b>If yes---> Hints for spinal bifida?(Porus,Hair): </b><span>{{p2.back_malformation_hints}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Neurology I Extremities</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-2"><b>Spotaneous movement: </b><span>{{p2.neurology_spotaneous_movement}}</span></div>
                        <div class="col-md-2"><b>Musde tone: </b><span>{{p2.neurology_musde_tone}}</span></div>

                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Neurology I Reflexes</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-2"><b>Glasping: </b><span>{{p2.neurology_flexes_glasping}}</span></div>
                        <div class="col-md-2"><b>Sucking: </b><span>{{p2.neurology_flexes_sucking}}</span></div>
                        <div class="col-md-2"><b>Traction: </b><span>{{p2.neurology_flexes_traction}}</span></div>
                        <div class="col-md-2"><b>Moro: </b><span>{{p2.neurology_flexes_moro}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Finnstroem score</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-12"><span>{{p2.finnstroem_score}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Additional Findings</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-12"><span>{{p2.additional_findings}}</span></div>
                      </div>
                    </fieldset><br>

                    <fieldset align="left">
                      <legend>Key Findings</legend>
                      <div class="row" ng-repeat="p2 in examination21">
                        <div class="col-md-12"><span>{{p2.key_findings}}</span></div>
                      </div>
                    </fieldset><br>

                    </div>
                    </div>
                    </fieldset><br>

                    <!-- row -->
                    <div class="row" ng-init="getManagementByYear(2021)">
                      <fieldset>
                        <legend>Provisional Diagnoses(PDx)</legend>
                        <div class="row" ng-repeat="p2 in management1">
                          <div class="col-md-12"><span>{{p2.provisional_diagnoses}}</span></div>
                        </div>
                      </fieldset><br>

                      <fieldset>
                        <legend>Differential Diagnoses(DDx)</legend>
                        <div class="row" ng-repeat="p2 in management1">
                          <div class="col-md-12"><span>{{p2.differential_diagnoses}}</span></div>
                        </div>
                      </fieldset><br>

                      <fieldset>
                        <legend>Differential Diagnoses(DDx)</legend>
                        <div class="row" ng-repeat="p2 in management1">
                          <div class="col-md-12"><span>{{p2.differential_diagnoses}}</span></div>
                        </div>
                      </fieldset><br>

                    </div><br>


                    <fieldset>
                      <legend>MANAGEMENT</legend>
                      <!-- row -->
                      <div class="row">
                        <fieldset align="left">
                          <legend>Investigation</legend>
                          <div class="row" ng-repeat="p in management1">
                            <div class="col-md-12"><span>{{p.investigation}}</span></div>
                          </div>
                        </fieldset><br>

                        <fieldset align="left">
                          <legend>Treatment</legend>
                          <div class="row" ng-repeat="p in management1">
                            <div class="col-md-12"><span>{{p.treatment}}</span></div>
                          </div>
                        </fieldset><br>

                        <fieldset align="left">
                          <legend>Supportive Care</legend>
                          <div class="row" ng-repeat="p in management1">
                            <div class="col-md-12"><span>{{p.supportive_care}}</span></div>
                          </div>
                        </fieldset><br>

                        <fieldset align="left">
                          <legend>Preventions</legend>
                          <div class="row" ng-repeat="p in management1">
                            <div class="col-md-12"><span>{{p.preventions}}</span></div>
                          </div>
                        </fieldset><br>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
        </fieldset>
      </center>

<!-- <a href='previous_newborn_checklist_per_year_records.php?Registration_ID=".$registration_id."&delivery_year=".$year."&Employee_ID=".$employee_ID."&Admision_ID=".$admission_id."&consultation_ID=".$consultation_id."'>".$year."</a> -->
    </div>

    <!--************************************** end of preview last years data  ******************************************************************-->


  </div><br>
</div>
<!--************************************************** end of main div **************************************************************-->







<!-- scripts -->
<!-- <script type="text/javascript" src="../js/DataTables/angular-datatables.min.js"></script>
<script type="text/javascript" src="../js/DataTables/angular-datatables.buttons.min.js"></script>
<script type="text/javascript" src="../js/DataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../js/DataTables/pdfmake.min.js"></script>
<script type="text/javascript" src="../js/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script> -->
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="neonatal_care_admission.js"></script>
<!-- <script type="text/javascript" src="forms/neonatal_care_admission.js"></script> -->




<!-- angular -->
<!-- <script type="text/javascript">

</script> -->

<script src="../css/jquery.datetimepicker.js"></script>
<!-- <script src="css/jquery.timepicker.js"></script> -->
<script type="text/javascript">
  $(document).ready(function(e){

    $('.date').datetimepicker({value: '', step: 2});
  })
</script>



<?php
    include("../includes/footer.php");
?>
