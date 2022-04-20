

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
  $select_patien_details = mysqli_query($conn," SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
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
<!DOCTYPE html>
<!-- css linksand scripts links -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
<script type="text/javascript" src="js/DataTables/angular.js">

</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script> -->
<!-- end of links -->
<style media="screen">
  .container-fluid{
    padding-top: 30px;
  }

  .modal-lg{
    width: 95%;
  }
</style>
<!-- Buttons -->

<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id ?>" class="art-button-green">BACK</a>
<!-- End buttons -->



<div class="container-fluid" ng-app="anteModule" ng-controller="anteCtl">

  <!-- form div -->
  <div ng-hide="fmDiv">
    <center>
      <form name="antenatal" method="post">
      <fieldset>
        <legend style="font-weight:bold"align=center>
          <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
            <p style="margin:0px;padding:0px;">ANTENATAL CHECKLIST</p>
            <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
          </div>
      </legend>

        <!-- row1 -->
        <div class="row">
          <!-- patient name -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="pname">Patient name:</label>
              <input type="text" class="form-control" name="patient_name"  value="<?= $Patient_Name ?>" id="pname">
              <input type="hidden" class="form-control" name="Registration_ID"  value="<?= $Registration_ID ?>" id="Registration_ID">
              <input type="hidden" class="form-control" name="Employee_ID"  value="<?= $Employee_ID ?>" id="Employee_ID">
              <input type="hidden" name="Admision_ID" class="form-control" id="Admision_ID" value="<?php echo $Admision_ID;?>"/><br>
              <input type="hidden" name="consultation_id" class="form-control" id="consultation_id" value="<?php echo $consultation_id;?>"/><br>
            </div>
          </div>

          <!-- age -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="age">Age:</label>
              <input type="text" class="form-control" name="age"  value="<?= $age ?>" id="age">
            </div>
          </div>

          <!-- marital status -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="mstatus">Marital status:</label>
              <select class="form-control" ng-model="marital_status" id="mstatus">
              <option selected>--select--</option>
              <option  value="single">Singe</option>
              <option value="married">Married</option>
            </select>
            </div>
          </div>
        </div>
        <!-- end of row1 -->

        <!-- row2 -->
        <div class="row">
          <!-- lnmp -->
          <div class="col-md-4">
              <div class="form-group">
                <label for="lnmp">LNMP:</label>
                <input type="text" class="form-control" name="lnmp" ng-model="lnmp" placeholder="Enter lnmp" id="lnmp">
              </div>
          </div>

          <!-- living -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="living">Living:</label>
              <input type="text" class="form-control" name="living" ng-model="living" placeholder="Enter living" id="living">
            </div>
          </div>

          <!-- address -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="address">Address:</label>
              <input type="text" class="form-control" name="address" ng-model="address" placeholder="Enter address" id="address">
            </div>
          </div>
        </div>
        <!-- end of row2 -->

        <!-- row3 -->
        <div class="row">
          <!-- edd -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="edd">EDD:</label>
              <input type="text" class="form-control" name="edd" ng-model="edd" placeholder="Enter edd" id="edd">
            </div>
          </div>

          <!-- ga -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="ga">GA:</label>
              <input type="text" class="form-control" name="ga" ng-model="ga" placeholder="Enter ga" id="ga">
            </div>
          </div>

          <!-- gravida -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="gravida">GRAVIDA:</label>
              <input type="text" class="form-control" name="gravida" ng-model="gravida" placeholder="Enter gravida" id="gravida">
            </div>
          </div>
        </div>
        <!-- end of row3 -->

        <!-- row4 -->
        <div class="row">
          <!-- para -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="para">PARA:</label>
              <input type="text" class="form-control" name="para" ng-model="para" placeholder="Enter para" id="para">
            </div>
          </div>

          <!-- abortion -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="abortion">Abortion:</label>
              <input type="text" class="form-control" name="abortion" ng-model="abortion" placeholder="Enter abortion" id="abortion">
            </div>
          </div>

          <!-- date time of admissin -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="dt">Date of admission:</label>
              <input type="text" class="date" name="admission_date" ng-model="admission_date"  id="dt">
            </div>
          </div>
        </div>
        <!-- end of row4 -->
    </fieldset><br><br>


  <!-- VITAL SIGNS -->
    <fieldset>
      <legend style="font-weight:bold"align=center>VITAL SIGNS</legend>
      <!-- row1 -->
      <div class="row">
        <!-- BT(°C) -->
        <div class="col-md-4">
          <div class="form-group">
            <label for="bt">BT(°C):</label>
            <input type="text" class="form-control" name="bt" ng-model="bt" placeholder="Enter BT(°C)" id="bt" required>
          </div>
        </div>

        <!-- BP(mmHg) -->
        <div class="col-md-4">
          <div class="form-group">
            <label for="bp">BP(mmHg):</label>
            <input type="text" class="form-control" name="bp" ng-model="bp" placeholder="Enter BP(mmHg)" id="bp" required>
          </div>
        </div>

        <!-- PR(b/min) -->
        <div class="col-md-4">
          <div class="form-group">
            <label for="pr">PR(b/min):</label>
            <input type="text" class="form-control" name="pr" ng-model="pr" placeholder="Enter pr" id="pr" required>
          </div>
        </div>
      </div>
      <!-- end of row1 -->


      <!-- row2 -->
      <div class="row">
        <!-- RR(br/min) -->
        <div class="col-md-4">
          <div class="form-group">
            <label for="rr">RR(br/min):</label>
            <input type="text" class="form-control" name="rr" ng-model="rr" placeholder="Enter RR(br/min)" id="rr" required>
          </div>
        </div>

        <!-- WEIGHT(kg) -->
        <div class="col-md-4">
          <div class="form-group">
            <label for="weight">WEIGHT(kg):</label>
            <input type="text" class="form-control" name="weight" ng-model="weight" placeholder="Enter WEIGHT(kg)" id="weight" required>
          </div>
        </div>
      </div>
      <!-- end of row2 -->
    </fieldset><br><br>



    <!-- PHYSICAL EXAMINATION -->
      <fieldset>
        <legend style="font-weight:bold"align=center>PHYSICAL EXAMINATION</legend>
        <!-- row1 -->
        <div class="row">
          <!-- signs of anemia -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="senemia">Signs of anemia:</label>
              <select class="form-control" ng-model="signs_of_anemia" id="senemia">
              <option selected>--select--</option>
              <option  value="yes">Yes</option>
              <option value="no">No</option>
            </select>
            </div>
          </div>

            <!-- legs -->
            <!-- <div class="col-md-4">
              <div class="form-group">
                <label for="legs">Legs:</label>
                <select class="form-control" ng-model="legs" id="legs">
                <option selected>--select--</option>
                <option  value="yes">Yes</option>
                <option value="no">No</option>
              </select>
              </div>
            </div> -->

            <!-- breast abnormalities -->
            <div class="col-md-4">
              <div class="form-group">
                <label for="babnormalities">Breast abnormalities:</label>
                <select class="form-control" ng-model="breast_abnormalities" id="babnormalities">
                <option selected>--select--</option>
                <option  value="yes">Yes</option>
                <option value="no">No</option>
              </select>
              </div>
            </div>

        </div>
        <!-- end of row1 -->

        <!-- row2 -->
        <div class="row">
          <h5 class="label label-primary">LEGS</h5><br>
          <!-- Oedema -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="Oedema">Oedema:</label>
              <select class="form-control" ng-model="Oedema" id="Oedema">
              <option selected>--select--</option>
              <option  value="yes">Yes</option>
              <option value="no">No</option>
            </select>
            </div>
          </div>

          <!-- Varicose vein -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="Varicose">Varicose vein:</label>
              <select class="form-control" ng-model="varicose_vein" id="Varicose">
              <option selected>--select--</option>
              <option  value="yes">Yes</option>
              <option value="no">No</option>
            </select>
            </div>
          </div>

          <!-- Any deformity -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="deformity">Any deformity:</label>
              <select class="form-control" ng-model="any_deformity" id="deformity">
              <option selected>--select--</option>
              <option  value="yes">Yes</option>
              <option value="no">No</option>
            </select>
            </div>
          </div>

        </div>
        <!-- end of row2 -->
      </fieldset>
      <br><br>


      <!-- ABDOMINAL EXAMINATION -->
        <fieldset>
          <legend style="font-weight:bold"align=center>ABDOMINAL EXAMINATION</legend>
          <!-- row1 -->
          <div class="row">
            <!-- Fetal Lie -->
              <div class="col-md-4">
                <div class="form-group">
                  <label for="flie">Fetal lie</label>
                  <input type="text" class="form-control" name="fetal_lie" ng-model="fetal_lie" placeholder="Enter Fetal lie" id="flie">
                </div>
              </div>
              <!-- Fetal presentation -->
              <div class="col-md-4">
                <div class="form-group">
                  <label for="fpresentation">Fetal presentation</label>
                  <input type="text" class="form-control" name="fetal_presentation" ng-model="fetal_presentation" placeholder="Enter Fetal presentation" id="fpresentation">
                </div>
              </div>
              <!-- Fetal Heart Rate(b/min) -->
              <div class="col-md-4">
                <div class="form-group">
                  <label for="fetal_heart_rate">Fetal Heart Rate(b/min)</label>
                  <input type="text" class="form-control" name="fetal_heart_rate" ng-model="fetal_heart_rate" placeholder="Enter Fetal Heart Rate(b/min)" id="fetal_heart_rate">
                </div>
              </div>
          </div>
          <!-- end of row1 -->

          <!-- row2 -->
          <div class="row">
            <!-- Head level -->
              <div class="col-md-4">
                <div class="form-group">
                  <label for="head_level">Head level</label>
                  <input type="text" class="form-control" name="head_level" ng-model="head_level" placeholder="Enter Head level" id="head_level">
                </div>
              </div>
              <!-- contractions -->
              <div class="col-md-4">
                <div class="form-group">
                  <label for="contractions">Contractions</label>
                  <input type="text" class="form-control" name="contractions" ng-model="contractions" placeholder="Enter contractions" id="contractions">
                </div>
              </div>
              <!-- save button -->
              <div class="col-md-4">
                <span class="badge badge-success" style="width:100px;">{{success}}</span>
                <span class="badge badge-danger" style="width:100px;">{{error}}</span>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" class="art-button-green" ng-show="antenatal.$valid" ng-click="saveAntenatalChecklist()" value="{{btnName}}">
                <input type="button" class="art-button-green" ng-show="!antenatal.$valid" ng-click="showTable()" value="Preview">
              </div>
          </div>
          <!-- end of row2 -->
        </fieldset>
    </form>
    </center>
  </div>
  <!-- end of form div -->



  <!-- table div -->
  <div ng-hide="tbDiv" ng-init="previewAntenatal()" style="background-color:white;padding-top:15px;">

    <div class="row">
      <div class="col-md-6">
        <a href="save_postnatal_antenatal_checklist.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID; ?>&Registration_ID=<?= $Registration_ID; ?>&admission_id=<?= $admision_id ?>&printAction=print_preview" class="art-button-green" target="_blank">PREVIEW RECORDS</a>
      </div>
      <div class="col-md-6">
          <input type="button" class="btn btn-sm art-button"  ng-click="showForm()" value="FORM" style="float:right;">
          <input type="button" class="btn btn-sm art-button"  ng-click="showLastYearTable()" value="Previous" style="float:right;">
      </div>
    </div>

    <span ng-show="isLoading">{{loading }}</span>

    <fieldset>
      <legend style="align:center;" align="center">ANTENATAL CHECKLIST DETAILS</legend>
      <?php

        $sql_marital_status = mysqli_query($conn,"SELECT marital_status FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
        $marital_status = mysqli_fetch_assoc($sql_marital_status)['marital_status'];

        $sql_lnmp = mysqli_query($conn,"SELECT lnmp FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
        $lnmp = mysqli_fetch_assoc($sql_lnmp)['lnmp'];

        $sql_edd = mysqli_query($conn,"SELECT edd FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
        $edd = mysqli_fetch_assoc($sql_edd)['edd'];

        $sql_ga = mysqli_query($conn,"SELECT ga FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
        $ga = mysqli_fetch_assoc($sql_ga)['ga'];

        $sql_gravida = mysqli_query($conn,"SELECT gravida FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
        $gravida = mysqli_fetch_assoc($sql_gravida)['gravida'];

        $sql_para = mysqli_query($conn,"SELECT para FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
        $para = mysqli_fetch_assoc($sql_para)['para'];

        $sql_lliving = mysqli_query($conn,"SELECT living FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
        $living = mysqli_fetch_assoc($sql_lliving)['living'];

        $sql_arbotion = mysqli_query($conn,"SELECT abortion FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
        $arbotion = mysqli_fetch_assoc($sql_arbotion)['abortion'];

        $sql_admission_date = mysqli_query($conn,"SELECT admission_date FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'");
        $admission_date = mysqli_fetch_assoc($sql_admission_date)['admission_date'];

        $sql_address = mysqli_query($conn,"SELECT address FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' ");
        $address = mysqli_fetch_assoc($sql_address)['address'];
       ?>
      <table border="1" class="table">
        <tr>
          <td><b>Patient Name:</b> <?= $Patient_Name?></td>
          <td><b>Age:</b> <?= $age?></td>
          <td><b>Marital Status:</b> <?= $marital_status?></td>
          <td><b>LNMP:</b> <?= $lnmp?></td>
          <td><b>EDD:</b> <?= $edd?></td>
        </tr>
        <tr>
          <td><b>GA:</b> <?= $ga?></td>
          <td><b>GRAVIDA:</b> <?= $gravida?></td>
          <td><b>PARA:</b> <?= $para?></td>
          <td><b>LIVING:</b> <?= $living?></td>
          <td><b>ABORTION:</b> <?= $arbotion?></td>
        </tr>
        <tr>
          <td><b>ADDRESS:</b> <?= $address?></td>
          <td><b>DATE OF ADMISSION:</b> <?= $admission_date?></td>
        </tr>
      </table>
      <!-- search input -->
      <label for="search">Search<input class="form-control input-sm" ng-model="search" id="search" type="text"></label>


     <div class="table-responsive">
        <table id="mytb" class="table table-striped table-hover">
           <thead>
             <tr>
               <th rowspan="2">DATE TIME</th>
               <th colspan="5">VITAL SIGNS</th>
               <th colspan="6">PHYSICAL EXAMINATION</th>
               <th colspan="5">ABDOMINAL EXAMINATION</th>
               <th rowspan="2">Checked By</th>
             </tr>
             <tr>
               <th>BT(°C)</th>
               <th>BP(mmHg)</th>
               <th>PR(b/min)</th>
               <th>RR(br/min)</th>
               <th>WEIGHT(kg)</th>
               <th>Signs of anaemia</th>
               <th>Breast abnormalities</th>
               <!-- <th>Legs</th> -->
               <th>Oedema</th>
               <th>Varicose vein</th>
               <th>Any Deformity</th>
               <th>Fetal Lie</th>
               <th>Fetal presentation</th>
               <th>Fetal Heart Rate(b/min)</th>
               <th>Head Level</th>
               <th>Contractions</th>
             </tr>
           </thead>
           <tbody>
           <tr ng-repeat="x in showData | filter : search">
             <td>{{x.save_time}}</td>
             <td>{{x.bt}}</td>
             <td>{{x.bp}}</td>
             <td>{{x.pr}}</td>
             <td>{{x.rr}}</td>
             <td>{{x.weight}}</td>
             <td>{{x.signs_of_anemia}}</td>
             <td>{{x.breast_abnormalities}}</td>
             <!-- <td>{{x.legs}}</td> -->
             <td>{{x.Oedema}}</td>
             <td>{{x.varicose_vein}}</td>
             <td>{{x.any_deformity}}</td>
             <td>{{x.fetal_lie}}</td>
             <td>{{x.fetal_presentation}}</td>
             <td>{{x.fetal_heart_rate}}</td>
             <td>{{x.head_level}}</td>
             <td>{{x.contractions}}</td>
             <td>{{x.Employee_Name}}</td>
           </tr>
           </tbody>
         </table></div>

    </fieldset>
  </div>
  <!-- end of table div -->

  <!-- last years table div -->
  <div ng-hide="lastYearsDiv" ng-init="previewAntenatalByYear(2023)">
    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-6">
          <input type="button" class="btn btn-sm art-button"  ng-click="showForm()" value="FORM" style="float:right;">
          <input type="button" class="btn btn-sm art-button"  ng-click="showTable()" value="CURRENT" style="float:right;">
      </div>
    </div>


      <fieldset>
        <legend>PREVIOUS ANTENATAL CHECKLIST DETAILS</legend>
        <table class="table">
          <th>#</th>
          <th>ALL YEARS</th>

          <?php
            $sn = 1;
            $select_year = mysqli_query($conn,"SELECT DISTINCT(YEAR(saved_time)) as 'year' FROM tbl_postinatal_antenatal_records WHERE  YEAR(saved_time) !='0' and  Registration_ID = $Registration_ID");
            $year = 0;
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
      </fieldset>
                <!-- Modal -->
        <div id="myModal" class="modal fade">
          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header" style="background-color:#037CB0;font-weight:bold;color:white;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-weight:bold;color:white;">PREVIOUS ANTENATAL CHECKLIST DETAILS</h4>
              </div>
              <div class="modal-body">
                <?php

                  $sql_marital_status = mysqli_query($conn,"SELECT marital_status FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' AND YEAR(saved_time) = '$year'  ORDER BY DATE(saved_time) ASC LIMIT 1");
                  $marital_status = mysqli_fetch_assoc($sql_marital_status)['marital_status'];

                  $sql_lnmp = mysqli_query($conn,"SELECT lnmp FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  AND YEAR(saved_time) = '$year' ORDER BY DATE(saved_time) ASC LIMIT 1");
                  $lnmp = mysqli_fetch_assoc($sql_lnmp)['lnmp'];

                  $sql_edd = mysqli_query($conn,"SELECT edd FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' AND YEAR(saved_time) = '$year'  ORDER BY DATE(saved_time) ASC LIMIT 1");
                  $edd = mysqli_fetch_assoc($sql_edd)['edd'];

                  $sql_ga = mysqli_query($conn,"SELECT ga FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' AND YEAR(saved_time) = '$year' ORDER BY DATE(saved_time) ASC LIMIT 1");
                  $ga = mysqli_fetch_assoc($sql_ga)['ga'];

                  $sql_gravida = mysqli_query($conn,"SELECT gravida FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' AND YEAR(saved_time) = '$year'  ORDER BY DATE(saved_time) ASC LIMIT 1");
                  $gravida = mysqli_fetch_assoc($sql_gravida)['gravida'];

                  $sql_para = mysqli_query($conn,"SELECT para FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' AND YEAR(saved_time) = '$year'  ORDER BY DATE(saved_time) ASC LIMIT 1");
                  $para = mysqli_fetch_assoc($sql_para)['para'];

                  $sql_lliving = mysqli_query($conn,"SELECT living FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' AND YEAR(saved_time) = '$year'  ORDER BY DATE(saved_time) ASC LIMIT 1");
                  $living = mysqli_fetch_assoc($sql_lliving)['living'];

                  $sql_arbotion = mysqli_query($conn,"SELECT abortion FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' AND YEAR(saved_time) = '$year'  ORDER BY DATE(saved_time) ASC LIMIT 1");
                  $arbotion = mysqli_fetch_assoc($sql_arbotion)['abortion'];

                  $sql_admission_date = mysqli_query($conn,"SELECT admission_date FROM tbl_postinatal_antenatal_records  WHERE Registration_ID ='$Registration_ID' AND YEAR(saved_time) = '$year'");
                  $admission_date = mysqli_fetch_assoc($sql_admission_date)['admission_date'];

                  $sql_address = mysqli_query($conn,"SELECT address FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'AND YEAR(saved_time) = '$year' ");
                  $address = mysqli_fetch_assoc($sql_address)['address'];
                 ?>
                <table border="1" class="table">
                  <tr>
                    <td><b>Patient Name:</b> <?= $Patient_Name?></td>
                    <td><b>Age:</b> <?= $age?></td>
                    <td><b>Marital Status:</b> <?= $marital_status?></td>
                    <td><b>LNMP:</b> <?= $lnmp?></td>
                    <td><b>EDD:</b> <?= $edd?></td>
                  </tr>
                  <tr>
                    <td><b>GA:</b> <?= $ga?></td>
                    <td><b>GRAVIDA:</b> <?= $gravida?></td>
                    <td><b>PARA:</b> <?= $para?></td>
                    <td><b>LIVING:</b> <?= $living?></td>
                    <td><b>ABORTION:</b> <?= $arbotion?></td>
                  </tr>
                  <tr>
                    <td><b>ADDRESS:</b> <?= $address?></td>
                    <td><b>DATE OF ADMISSION:</b> <?= $admission_date?></td>
                  </tr>
                </table>
                <!-- search input -->
                <label for="search">Search<input class="form-control input-sm" ng-model="search" id="search" type="text"></label>


               <div class="table-responsive">
                  <table id="mytb" class="table table-striped table-hover">
                     <thead>
                       <tr>
                         <th rowspan="2">DATE TIME</th>
                         <th colspan="5">VITAL SIGNS</th>
                         <th colspan="6">PHYSICAL EXAMINATION</th>
                         <th colspan="5">ABDOMINAL EXAMINATION</th>
                         <th rowspan="2">Checked By</th>
                       </tr>
                       <tr>
                         <th>BT(°C)</th>
                         <th>BP(mmHg)</th>
                         <th>PR(b/min)</th>
                         <th>RR(br/min)</th>
                         <th>WEIGHT(kg)</th>
                         <th>Signs of anaemia</th>
                         <th>Breast abnormalities</th>
                         <!-- <th>Legs</th> -->
                         <th>Oedema</th>
                         <th>Varicose vein</th>
                         <th>Any Deformity</th>
                         <th>Fetal Lie</th>
                         <th>Fetal presentation</th>
                         <th>Fetal Heart Rate(b/min)</th>
                         <th>Head Level</th>
                         <th>Contractions</th>
                       </tr>
                     </thead>
                     <tbody>
                     <tr ng-repeat="x in showData1 | filter : search">
                       <td>{{x.save_time}}</td>
                       <td>{{x.bt}}</td>
                       <td>{{x.bp}}</td>
                       <td>{{x.pr}}</td>
                       <td>{{x.rr}}</td>
                       <td>{{x.weight}}</td>
                       <td>{{x.signs_of_anemia}}</td>
                       <td>{{x.breast_abnormalities}}</td>
                       <!-- <td>{{x.legs}}</td> -->
                       <td>{{x.Oedema}}</td>
                       <td>{{x.varicose_vein}}</td>
                       <td>{{x.any_deformity}}</td>
                       <td>{{x.fetal_lie}}</td>
                       <td>{{x.fetal_presentation}}</td>
                       <td>{{x.fetal_heart_rate}}</td>
                       <td>{{x.head_level}}</td>
                       <td>{{x.contractions}}</td>
                       <td>{{x.Employee_Name}}</td>
                     </tr>
                     </tbody>
                   </table></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>



  </div>
  <!-- end last years table div -->

</div>
<!-- end main div -->



<script src="css/jquery.datetimepicker.js"></script>
<!-- <script src="css/jquery.timepicker.js"></script> -->
<script type="text/javascript">
  $(document).ready(function(e){

    $('.date').datetimepicker({value: '', step: 2});
  })
</script>

<script type="text/javascript">
  var app = angular.module('anteModule',[]);

  app.controller('anteCtl',function($scope,$http,$timeout){
    $scope.title = "ANTENATAL CHECKLIST";



    $scope.fmDiv = false;
    $scope.tbDiv = true;
    $scope.btnName = "Save";
    $scope.success = "";
    $scope.error = "";
    $scope.showData = "";
    $scope.showData1 = "";
    $scope.loading = '<div class="spinner-border text-info"></div>';
    $scope.isLoading = false;
    $scope.lastYearsDiv = true;

    $scope.getAll = function()
    {
      var y = document.querySelector('#year').value;
      $scope.previewAntenatalByYear(y);
    }

  $scope.showLastYearTable = function()
  {
    $scope.fmDiv = true;
    $scope.tbDiv = true;
    $scope.lastYearsDiv = false;
  }

 //show form
   $scope.showForm = function()
   {
     $scope.fmDiv = false;
     $scope.tbDiv = true;
     $scope.lastYearsDiv = true;
   }

//show table
   $scope.showTable = function()
   {
     $scope.fmDiv = true;
     $scope.tbDiv = false;
     $scope.lastYearsDiv = true;
   }



   $scope.previewAntenatal = function()
   {
     // $scope.isLoading = true;
     var regid = document.getElementById('Registration_ID').value;
     var url = "http://192.168.112.1/ehmsbmc/files/save_postnatal_antenatal_checklist.php?action=showTb&regId="+regid+"";
     $http.get(url).then(function(res){
       $scope.isLoading = false;
       $scope.showData = res.data;
       console.log($scope.showData);
     },function(res){
       $scope.error = res.data;
     });

   }

    // by year
   $scope.previewAntenatalByYear = function(y)
   {
     // $scope.isLoading = true;
     var regid = document.getElementById('Registration_ID').value;
     var url = "http://192.168.112.1/ehmsbmc/files/save_postnatal_antenatal_checklist.php?action=showTb1&regId="+regid+"&year="+y+"";
     $http.get(url).then(function(res){
       $scope.isLoading = false;
       $scope.showData1 = res.data;
       console.log($scope.showData1);
     },function(res){
       $scope.error = res.data;
     });

   }



    //function to save antenatal checklist datata
    $scope.saveAntenatalChecklist = function(){
      // $scope.btnName = '<span class="spinner-grow spinner-grow-sm"></span>Loading..';
      var url = "http://192.168.112.1/ehmsbmc/files/save_postnatal_antenatal_checklist.php";

      if(confirm('Are want to save this?'))
      {

        $http.post(url,{
            "Admision_ID":document.getElementById('Admision_ID').value,
            "consultation_id":document.getElementById('consultation_id').value,
            "marital_status":$scope.marital_status,
            "admission_date":$scope.admission_date,
            "Registration_ID":document.getElementById('Registration_ID').value,
            "Employee_ID":document.getElementById('Employee_ID').value,
            "lnmp":$scope.lnmp,
            "living":$scope.living,
            "edd":$scope.edd,
            "ga":$scope.ga,
            "gravida":$scope.gravida,
            "para":$scope.para,
            "abortion":$scope.abortion,
            "bt":$scope.bt,
            "bp":$scope.bp,
            "pr":$scope.pr,
            "rr":$scope.rr,
            "weight":$scope.weight,
            "signs_of_anemia":$scope.signs_of_anemia,
            "legs":$scope.legs,
            "breast_abnormalities":$scope.breast_abnormalities,
            "Oedema":$scope.Oedema,
            "varicose_vein":$scope.varicose_vein,
            "any_deformity":$scope.any_deformity,
            "fetal_lie":$scope.fetal_lie,
            "fetal_presentation":$scope.fetal_presentation,
            "fetal_heart_rate":$scope.fetal_heart_rate,
            "head_level":$scope.head_level,
            "contractions":$scope.contractions,
            "address":$scope.address,
            "action":"save"

        }).then(function(res){
          $scope.previewAntenatal();
          $scope.success = res.data;
          console.log($scope.success);
          $scope.marital_status
          $scope.lnmp ="";
          $scope.living ="";
          $scope.edd ="";
          $scope.ga ="";
          $scope.gravida ="";
          $scope.para ="";
          $scope.abortion ="";
          $scope.bt ="";
          $scope.bp ="";
          $scope.pr ="";
          $scope.rr ="";
          $scope.weight ="";
          $scope.signs_of_anemia ="";
          $scope.legs ="";
          $scope.breast_abnormalities ="";
          $scope.Oedema ="";
          $scope.varicose_vein ="";
          $scope.any_deformity ="";
          $scope.fetal_lie ="";
          $scope.fetal_presentation ="";
          $scope.fetal_heart_rate ="";
          $scope.head_level ="";
          $scope.contractions ="";
          $timeout(function(){
            $scope.success ="";
          },5000);

        },function(res){
          $scope.error = "Error:"+res.status;
        });
      }
    }


  });





$('.date').datetimepicker({value: '', step: 2});
  $(document).ready(function(e){

    $('.date').datetimepicker({value: '', step: 2});
  })
</script>

<!-- scripts links -->

<!-- <script src="app.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>


<!-- footer -->
<?php
include("./includes/footer.php");
?>
