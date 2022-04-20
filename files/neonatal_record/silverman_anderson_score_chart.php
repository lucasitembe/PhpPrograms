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



<!-- main div -->
<div class="container-fluid" ng-app="silvermanModule" ng-controller="silvermanCtl">
  <fieldset ng-init="initilizer()">
    <legend style="font-weight:bold"align=center>
      <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
        <p style="margin:0px;padding:0px;">SILVERMAN-ANDERSON SCORE CHART</p>
        <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;color:yellow;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;color:yellow;"><?= $Gender ?> |</span> <span style="margin-right:3px;color:yellow;"><?= $age ?> | </span> <span style="margin-right:3px;color:yellow;"><?= $Guarantor_Name ?></span> </p>
      </div>
  </legend>

  <!-- form div -->
<div ng-hide="divForm">
  <div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <input type="button" class="btn btn-sm art-button"  ng-click="showTable()" value="PREVIEW" style="float:right;">
    </div>
  </div>
  <form class="form-horizontal" role="form" name="silvermanFm">
  <!-- tabs -->
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home">Basic Info</a></li>
      <li><a data-toggle="tab" href="#menu1">Item and Score</a></li>
    </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">

        <div class="form-group">
          <label class="control-label col-sm-2" for="patient_name">Patient's name:</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="patient_name" ng-model="patient_name" placeholder="Enter full name">
            <input type="hidden" class="form-control" id="Registration_ID"  value="<?=$registration_id;?>">
            <input type="hidden" class="form-control" id="Employee_ID"  value="<?=$employee_ID;?>">
            <input type="hidden" name="Admision_ID" class="form-control" id="Admision_ID" value="<?php echo $Admision_ID;?>"/><br>
            <input type="hidden" name="consultation_id" class="form-control" id="consultation_id" value="<?php echo $consultation_id;?>"/><br>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="admission_date">Date of Admission:</label>
          <div class="col-sm-6">
            <input type="text" class="form-control date" id="admission_date" ng-model="admission_date">
          </div>
        </div>


        <div class="form-group">
          <label class="control-label col-sm-2" for="Gestation_age_at_birth">Gestation age at birth:</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="Gestation_age_at_birth" ng-model="Gestation_age_at_birth" placeholder="Enter Gestation age at birth">
          </div>
        </div>

        <div class="form-group">
        <div class="radio">
          <label class="control-label col-sm-2" for="sexid"><b>Sex:</b></label>
         <div class="col-sm-6" id="sexid">
          <label><input type="radio" value="M" ng-model="sex">Male</label>&nbsp;&nbsp;&nbsp;
          <label><input type="radio" value="F" ng-model="sex">Female</label>&nbsp;&nbsp;&nbsp;
        </div>
        </div>
      </div>



      </div>

      <div id="menu1" class="tab-pane fade">

        <div class="form-group">
          <label class="control-label col-sm-2" for="sel1">Upper chest indrawing:</label>
          <div class="col-sm-6">
            <select class="form-control" id="sel1" ng-model="selectedUpper_chest" ng-options="x for (x, y) in Upper_chest" required></select>
          </div>
        </div>


        <div class="form-group">
          <label class="control-label col-sm-2" for="sel1">Lower chest indrawing:</label>
          <div class="col-sm-6">
            <select class="form-control" id="sel1" ng-model="selectedLower_chest" ng-options="x for (x, y) in Lower_chest" required></select>
          </div>
        </div>


        <div class="form-group">
          <label class="control-label col-sm-2" for="sel1">Xiphoid retraction:</label>
          <div class="col-sm-6">
            <select class="form-control" id="sel1" ng-model="selectedXiphoid_retraction" ng-options="x for (x, y) in Xiphoid_retraction" required></select>
          </div>
        </div>


        <div class="form-group">
          <label class="control-label col-sm-2" for="sel1">Nasal flaring:</label>
          <div class="col-sm-6">
            <select class="form-control" id="sel1" ng-model="selectedNasal_flaring" ng-options="x for (x, y) in Nasal_flaring" required></select>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="sel1">Expiratory grant:</label>
          <div class="col-sm-6">
            <select class="form-control" id="sel1" ng-model="selectedExpiratory_grant" ng-options="x for (x, y) in Expiratory_grant" required></select>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="sel1">Day:</label>
          <div class="col-sm-6">
            <select class="form-control" id="sel1" ng-model="selectedDay" ng-options="x for x in days" required></select>
          </div>
        </div>

        <h2><span class="label label-success">{{success}}</span></h2>
        <h2><span class="label label-danger">{{error}}</span></h2>
      <input type="button" class="btn btn-sm art-button" value="Save" ng-click="saveSilverman()" ng-show="silvermanFm.$valid" style="float:right;">

      </div>
    </div>
  <!-- end tabs -->
</form>

</div>
  <!-- end form div -->


  <!-- table div -->
  <div ng-hide="tableDiv">
    <div class="row">
      <div class="col-md-6"></div>
      <div class="col-md-6">
          <input type="button" class="btn btn-sm art-button"  ng-click="showForm()" value="FORM" style="float:right;">
          <input type="button" class="btn btn-sm art-button"  ng-click="showLastYearTable()" value="PREVIOUS" style="float:right;">
      </div>
    </div>

    <!-- table -->
    <div class="responsive">
      <table class="table table-hover">
        <thead>
          <tr ng-repeat="b in basics">
            <th colspan="6">Patient’s name: <span style="color:#009999;">{{b.patient_name}}</span></th>
            <th colspan="5">Registration No: <span style="color:#009999;">{{b.Registration_ID}}</span></th>
          </tr>
          <tr ng-repeat="b in basics">
            <th colspan="4">Date of Admission: <span style="color:#009999;">{{b.admission_date}}</span></th>
            <th colspan="5">Gestation age at birth: <span style="color:#009999;">{{b.Gestation_age_at_birth}}</span></th>
            <th colspan="2">Sex: <span style="color:#009999;">{{b.sex}}</span></th>
          </tr>
          <tr>
            <th rowspan="2" style="background-color:#71c3ff;">Item</th>
            <th colspan="3" style="background-color:#71c3ff;">Score</th>
            <th style="background-color:#71c3ff;">Day</th>
            <th style="background-color:#71c3ff;">1</th>
            <th style="background-color:#71c3ff;">2</th>
            <th style="background-color:#71c3ff;">3</th>
            <th style="background-color:#71c3ff;">4</th>
            <th style="background-color:#71c3ff;">5</th>
            <th style="background-color:#71c3ff;">6</th>
          </tr>
          <tr>
            <th style="background-color:#71c3ff;">0</th>
            <th style="background-color:#71c3ff;">1</th>
            <th style="background-color:#71c3ff;">2</th>
            <th style="background-color:#71c3ff;">Date</th>
            <th style="background-color:#71c3ff;">{{Upper_chest1.saved_time}}</th>
            <th style="background-color:#71c3ff;">{{Upper_chest2.saved_time}}</th>
            <th style="background-color:#71c3ff;">{{Upper_chest3.saved_time}}</th>
            <th style="background-color:#71c3ff;">{{Upper_chest4.saved_time}}</th>
            <th style="background-color:#71c3ff;">{{Upper_chest5.saved_time}}</th>
            <th style="background-color:#71c3ff;">{{Upper_chest6.saved_time}}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th style="background-color:#71c3ff;">Upper chest indrawing</th>
            <td>Synchronised</td>
            <td>Lags on inspiration</td>
            <td>See-Saw respiration</td>
            <td style="background-color:#71c3ff;"></td>
            <td>{{Upper_chest1.Upper_chest}}</td>
            <td>{{Upper_chest2.Upper_chest}}</td>
            <td>{{Upper_chest3.Upper_chest}}</td>
            <td>{{Upper_chest4.Upper_chest}}</td>
            <td>{{Upper_chest5.Upper_chest}}</td>
            <td>{{Upper_chest6.Upper_chest}}</td>
          </tr>

          <tr>
            <th style="background-color:#71c3ff;">Lower chest indrawing</th>
            <td>None</td>
            <td>Just visible</td>
            <td>Marked</td>
            <td style="background-color:#71c3ff;"></td>
            <td>{{Lower_chest1.Lower_chest}}</td>
            <td>{{Lower_chest2.Lower_chest}}</td>
            <td>{{Lower_chest3.Lower_chest}}</td>
            <td>{{Lower_chest4.Lower_chest}}</td>
            <td>{{Lower_chest5.Lower_chest}}</td>
            <td>{{Lower_chest6.Lower_chest}}</td>
        </tr>

          <tr>
            <th style="background-color:#71c3ff;">Xiphoid retraction</th>
            <td>None</td>
            <td>Just visible</td>
            <td>Marked</td>
            <td style="background-color:#71c3ff;"></td>
            <td>{{Xiphoid_retraction1.Xiphoid_retraction}}</td>
            <td>{{Xiphoid_retraction2.Xiphoid_retraction}}</td>
            <td>{{Xiphoid_retraction3.Xiphoid_retraction}}</td>
            <td>{{Xiphoid_retraction4.Xiphoid_retraction}}</td>
            <td>{{Xiphoid_retraction5.Xiphoid_retraction}}</td>
            <td>{{Xiphoid_retraction6.Xiphoid_retraction}}</td>
          </tr>


          <tr>
            <th style="background-color:#71c3ff;">Nasal flaring</th>
            <td>None</td>
            <td>Minimal</td>
            <td>Marked</td>
            <td style="background-color:#71c3ff;"></td>
            <td>{{Nasal_flaring1.Nasal_flaring}}</td>
            <td>{{Nasal_flaring2.Nasal_flaring}}</td>
            <td>{{Nasal_flaring3.Nasal_flaring}}</td>
            <td>{{Nasal_flaring4.Nasal_flaring}}</td>
            <td>{{Nasal_flaring5.Nasal_flaring}}</td>
            <td>{{Nasal_flaring6.Nasal_flaring}}</td>
          </tr>


          <tr>
            <th style="background-color:#71c3ff;">Expiratory grant</th>
            <td>None</td>
            <td>Minimal</td>
            <td>Audible with naked ear</td>
            <td style="background-color:#71c3ff;"></td>
            <td>{{Expiratory_grants1.Expiratory_grant}}</td>
            <td>{{Expiratory_grants2.Expiratory_grant}}</td>
            <td>{{Expiratory_grants3.Expiratory_grant}}</td>
            <td>{{Expiratory_grants4.Expiratory_grant}}</td>
            <td>{{Expiratory_grants5.Expiratory_grant}}</td>
            <td>{{Expiratory_grants6.Expiratory_grant}}</td>
        </tr>
          <tr>
            <th colspan="4" style="background-color:#71c3ff;"><h4>Total score</h4></th>
            <th style="background-color:#71c3ff;"></th>
            <th style="background-color:#71c3ff;">{{d1Total}}</th>
            <th style="background-color:#71c3ff;">{{d2Total}}</th>
            <th style="background-color:#71c3ff;">{{d3Total}}</th>
            <th style="background-color:#71c3ff;">{{d4Total}}</th>
            <th style="background-color:#71c3ff;">{{d5Total}}</th>
            <th style="background-color:#71c3ff;">{{d6Total}}</th>
          </tr>
        </tbody>
      </table>
    </div>
    <table class="table">
      <tr>
        <td>
            <h4>Interpretation of scores</h4>
            <h5>Score: </h5>
            <ul>
              <li>0-3: None to mild respiratory distress </li>
              <li>4-6: Moderate respiratory distress</li>
              <li>>6: Impending respiratory failure</li>
            </ul>
        </td>
        <td>Note: Infants with normal breathing tend to have scores closer to 0 (zero) whereas severly respiratory depressed infants get scores closer to 10 (maximum obtainable)</td>
      </tr>
    </table>
    <!-- end table -->



  </div>
  <!-- end table div -->


  <!-- last year table -->
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
         $select_year = mysqli_query($conn,"SELECT DISTINCT(YEAR(saved_time)) as 'year' FROM tbl_silverman_anderson_score_chart WHERE  YEAR(saved_time) !='0' and  Registration_ID = $registration_id");

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
 </center>

       <!-- Modal -->
      <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header" style="background-color:#037CB0;font-weight:bold;color:white;">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" style="font-weight:bold;color:white;">SILVERMAN-ANDERSON SCORE CHART</h4>
            </div>
            <div class="modal-body">
              <!-- table -->
              <div class="responsive">
                <table class="table table-hover">
                  <thead>
                    <tr ng-repeat="b in basics1">
                      <th colspan="6">Patient’s name: <span style="color:#009999;">{{b.patient_name}}</span></th>
                      <th colspan="5">Registration No: <span style="color:#009999;">{{b.Registration_ID}}</span></th>
                    </tr>
                    <tr ng-repeat="b in basics1">
                      <th colspan="4">Date of Admission: <span style="color:#009999;">{{b.admission_date}}</span></th>
                      <th colspan="5">Gestation age at birth: <span style="color:#009999;">{{b.Gestation_age_at_birth}}</span></th>
                      <th colspan="2">Sex: <span style="color:#009999;">{{b.sex}}</span></th>
                    </tr>
                    <tr>
                      <th rowspan="2" style="background-color:#71c3ff;">Item</th>
                      <th colspan="3" style="background-color:#71c3ff;">Score</th>
                      <th style="background-color:#71c3ff;">Day</th>
                      <th style="background-color:#71c3ff;">1</th>
                      <th style="background-color:#71c3ff;">2</th>
                      <th style="background-color:#71c3ff;">3</th>
                      <th style="background-color:#71c3ff;">4</th>
                      <th style="background-color:#71c3ff;">5</th>
                      <th style="background-color:#71c3ff;">6</th>
                    </tr>
                    <tr>
                      <th style="background-color:#71c3ff;">0</th>
                      <th style="background-color:#71c3ff;">1</th>
                      <th style="background-color:#71c3ff;">2</th>
                      <th style="background-color:#71c3ff;">Date</th>
                      <th style="background-color:#71c3ff;">{{Upper_chest11.saved_time}}</th>
                      <th style="background-color:#71c3ff;">{{Upper_chest21.saved_time}}</th>
                      <th style="background-color:#71c3ff;">{{Upper_chest31.saved_time}}</th>
                      <th style="background-color:#71c3ff;">{{Upper_chest41.saved_time}}</th>
                      <th style="background-color:#71c3ff;">{{Upper_chest51.saved_time}}</th>
                      <th style="background-color:#71c3ff;">{{Upper_chest61.saved_time}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th style="background-color:#71c3ff;">Upper chest indrawing</th>
                      <td>Synchronised</td>
                      <td>Lags on inspiration</td>
                      <td>See-Saw respiration</td>
                      <td style="background-color:#71c3ff;"></td>
                      <td>{{Upper_chest11.Upper_chest}}</td>
                      <td>{{Upper_chest21.Upper_chest}}</td>
                      <td>{{Upper_chest31.Upper_chest}}</td>
                      <td>{{Upper_chest41.Upper_chest}}</td>
                      <td>{{Upper_chest51.Upper_chest}}</td>
                      <td>{{Upper_chest61.Upper_chest}}</td>
                    </tr>

                    <tr>
                      <th style="background-color:#71c3ff;">Lower chest indrawing</th>
                      <td>None</td>
                      <td>Just visible</td>
                      <td>Marked</td>
                      <td style="background-color:#71c3ff;"></td>
                      <td>{{Lower_chest11.Lower_chest}}</td>
                      <td>{{Lower_chest21.Lower_chest}}</td>
                      <td>{{Lower_chest31.Lower_chest}}</td>
                      <td>{{Lower_chest41.Lower_chest}}</td>
                      <td>{{Lower_chest51.Lower_chest}}</td>
                      <td>{{Lower_chest61.Lower_chest}}</td>
                  </tr>

                    <tr>
                      <th style="background-color:#71c3ff;">Xiphoid retraction</th>
                      <td>None</td>
                      <td>Just visible</td>
                      <td>Marked</td>
                      <td style="background-color:#71c3ff;"></td>
                      <td>{{Xiphoid_retraction11.Xiphoid_retraction}}</td>
                      <td>{{Xiphoid_retraction21.Xiphoid_retraction}}</td>
                      <td>{{Xiphoid_retraction31.Xiphoid_retraction}}</td>
                      <td>{{Xiphoid_retraction41.Xiphoid_retraction}}</td>
                      <td>{{Xiphoid_retraction51.Xiphoid_retraction}}</td>
                      <td>{{Xiphoid_retraction61.Xiphoid_retraction}}</td>
                    </tr>

                    <tr>
                      <th style="background-color:#71c3ff;">Nasal flaring</th>
                      <td>None</td>
                      <td>Minimal</td>
                      <td>Marked</td>
                      <td style="background-color:#71c3ff;"></td>
                      <td>{{Nasal_flaring11.Nasal_flaring}}</td>
                      <td>{{Nasal_flaring21.Nasal_flaring}}</td>
                      <td>{{Nasal_flaring31.Nasal_flaring}}</td>
                      <td>{{Nasal_flaring41.Nasal_flaring}}</td>
                      <td>{{Nasal_flaring51.Nasal_flaring}}</td>
                      <td>{{Nasal_flaring61.Nasal_flaring}}</td>
                    </tr>


                    <tr>
                      <th style="background-color:#71c3ff;">Expiratory grant</th>
                      <td>None</td>
                      <td>Minimal</td>
                      <td>Audible with naked ear</td>
                      <td style="background-color:#71c3ff;"></td>
                      <td>{{Expiratory_grants11.Expiratory_grant}}</td>
                      <td>{{Expiratory_grants21.Expiratory_grant}}</td>
                      <td>{{Expiratory_grants31.Expiratory_grant}}</td>
                      <td>{{Expiratory_grants41.Expiratory_grant}}</td>
                      <td>{{Expiratory_grants51.Expiratory_grant}}</td>
                      <td>{{Expiratory_grants61.Expiratory_grant}}</td>
                  </tr>
                    <tr>
                      <th colspan="4" style="background-color:#71c3ff;"><h4>Total score</h4></th>
                      <th style="background-color:#71c3ff;"></th>
                      <th style="background-color:#71c3ff;">{{d1Total1}}</th>
                      <th style="background-color:#71c3ff;">{{d2Total1}}</th>
                      <th style="background-color:#71c3ff;">{{d3Total1}}</th>
                      <th style="background-color:#71c3ff;">{{d4Total1}}</th>
                      <th style="background-color:#71c3ff;">{{d5Total1}}</th>
                      <th style="background-color:#71c3ff;">{{d6Total1}}</th>
                    </tr>
                  </tbody>
                </table>
              </div>
              <table class="table">
                <tr>
                  <td>
                      <h4>Interpretation of scores</h4>
                      <h5>Score: </h5>
                      <ul>
                        <li>0-3: None to mild respiratory distress </li>
                        <li>4-6: Moderate respiratory distress</li>
                        <li>>6: Impending respiratory failure</li>
                      </ul>
                  </td>
                  <td>Note: Infants with normal breathing tend to have scores closer to 0 (zero) whereas severly respiratory depressed infants get scores closer to 10 (maximum obtainable)</td>
                </tr>
              </table>
              <!-- end table -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>

  </div>
  <!-- end last year table -->



</fieldset>
</center>
</div>
<!-- end main div -->





<!-- scripts -->
<script type="text/javascript" src="../js/DataTables/angular-datatables.min.js"></script>
<script type="text/javascript" src="../js/DataTables/angular-datatables.buttons.min.js"></script>
<script type="text/javascript" src="../js/DataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../js/DataTables/pdfmake.min.js"></script>
<script type="text/javascript" src="../js/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>


<!--******************************* ANGULAR *******************************************************-->
<script type="text/javascript" src="silverman_anderson_score_chart.js"></script>


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
