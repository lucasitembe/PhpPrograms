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

?>

<!DOCTYPE html>
<!-- css linksand scripts links -->
<!-- <link rel="stylesheet" href="css/jquery.timepicker.css"> -->

<script src="js/DataTables/angular.js"></script>
<!-- end of links -->
<style media="screen">
  .container-fluid{
    padding-top: 30px;
  }
</style>


<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id; ?>&Employee_ID=<?= $Employee_ID; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id ?>" class="art-button-green">BACK</a>

<!-- main div -->
<div class="container-fluid" ng-app="obserModule" ng-controller="obserCtl">
  <center>
    <!-- form div -->
    <div ng-hide="formDiv">
      <fieldset>
        <legend style="font-weight:bold"align=center>
          <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
            <p style="margin:0px;padding:0px;">OBSERVATION
            <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
          </div>
      </legend>
      <form name="observation" method="post">
        <!-- row1 -->
        <div class="row">
          <!-- name -->
          <div class="col-md-4">
            <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control"  value="<?= $Patient_Name ?>" id="name" disabled>
            <input type="hidden" class="form-control" name="Registration_ID"  value="<?= $patient_id ?>" id="Registration_ID">
            <input type="hidden" class="form-control" name="Employee_ID"  value="<?= $Employee_ID ?>" id="Employee_ID">
            <input type="hidden" name="Admision_ID" class="form-control" id="Admision_ID" value="<?php echo $Admision_ID;?>"/><br>
            <input type="hidden" name="consultation_id" class="form-control" id="consultation_id" value="<?php echo $consultation_id;?>"/><br>
          </div>
          </div>
          <!-- ward no -->
          <div class="col-md-4">
            <div class="form-group">
            <label for="wardNo">Ward No:</label>
            <input type="number" class="form-control" ng-model="ward_no" placeholder="Enter ward number" id="wardNo">
          </div>
          </div>
        </div>
        <!-- row2 -->
        <div class="row">
          <!-- bp -->
          <div class="col-md-4">
            <div class="form-group">
            <label for="bp">BP:</label>
            <input type="text" class="form-control" ng-model="bp" placeholder="Enter BP" id="bp" required>
          </div>
          </div>
          <!-- pr -->
          <div class="col-md-4">
            <div class="form-group">
            <label for="pr">PR:</label>
            <input type="text" class="form-control" ng-model="pr" placeholder="Enter PR" id="pr" required>
          </div>
          </div>
        </div>
        <!-- row3 -->
        <div class="row">
          <!-- temp -->
          <div class="col-md-4">
            <div class="form-group">
            <label for="temp">Temp:</label>
            <input type="text" class="form-control" ng-model="temp" placeholder="Enter temperature" id="temp" required>
          </div>
          </div>
          <!-- fhr -->
          <div class="col-md-4">
            <div class="form-group">
            <label for="fhr">FHR:</label>
            <input type="text" class="form-control" ng-model="fhr" placeholder="Enter FHR" id="fhr" required>
          </div>
          </div>
        </div>
        <!-- row4 -->
        <div class="row">
          <!-- contraction -->
          <div class="col-md-4">
            <div class="form-group">
            <label for="contraction">Contraction:</label>
            <input type="text" class="form-control" ng-model="contraction" placeholder="Enter contraction" id="contraction" required>
          </div>
          </div>
          <!-- pve -->
          <div class="col-md-4">
            <div class="form-group">
            <label for="pve">PVE:</label>
            <input type="text" class="form-control" ng-model="pve" placeholder="Enter PVE" id="pve" required>
          </div>
          </div>
        </div>
        <!-- row5 -->
        <div class="row">
          <!-- date  -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="dt">Date:</label>
              <input type="text" class="date" name="observation_date" ng-model="observation_date"  id="dt">
            </div>
          </div>
          <!-- buttons -->
          <div class="col-md-4">
            <h4><span class="badge badge-success">{{success}}</span></h4>
            <span class="badge badge-danger" style="width:100px;">{{error}}</span>&nbsp;&nbsp;&nbsp;&nbsp;<br>
            <input type="button" class="art-button-green" ng-show="observation.$valid" ng-click="saveObservation()" value="{{btnName}}">
            <input type="button" class="art-button-green" ng-show="!observation.$valid" ng-click="showTable()" value="Preview">
          </div>
        </div>
      </form>

    </fieldset>
    </div>
    <!-- end of formDiv -->

    <!-- table div -->
    <div ng-hide="tbDiv" ng-init="getObservation()" style="background-color:white;padding-top:15px;">
    <div class="row">
      <div class="col-md-2">
          <input type="button" class="art-button-green" ng-click="showForm()" value="Form">
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <fieldset>
          <legend style="font-weight:bold"align=center>
            <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
              <p style="margin:0px;padding:0px;">OBSERVATION CHART
              <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
              <?php
                $wardno = mysqli_query($conn,"SELECT ward_no FROM tbl_labour_observation_chart WHERE Registration_ID = '$patient_id' ORDER BY saved_time ASC LIMIT 1");
                $wrdNo = mysqli_fetch_assoc($wardno)['ward_no'];
               ?>
               <!-- <h3 style="color:#037CB0;margin:0px;padding:0px; ">WARD No:< ?=$wrdNo ?></h3> -->
            </div>
        </legend>
      </fieldset>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
          <label>Search<input type="text" class="form-control" ng-model="search"></labe>
      </div>
    </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>DATE</th>
              <th>BP</th>
              <th>PR</th>
              <th>TEMP</th>
              <th>FHR</th>
              <th>CONTRACTION</th>
              <th>PVE</th>
              <th>WARD</th>
              <th>Performed By</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="ob in observations | filter : search">
              <td>{{ob.observation_date | date:'YYYY-M-d H:i'}}</td>
              <td>{{ob.bp}}</td>
              <td>{{ob.pr}}</td>
              <td>{{ob.temp}}</td>
              <td>{{ob.fhr}}</td>
              <td>{{ob.contraction}}</td>
              <td>{{ob.pve}}</td>
              <td>{{ob.ward_no}}</td>
              <td>{{ob.Employee_Name}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- end of tbDiv -->
  </center>
</div>
<!-- end of main div -->


<script src="css/jquery.datetimepicker.js"></script>
<!-- <script src="css/jquery.timepicker.js"></script> -->
<script type="text/javascript">
  $(document).ready(function(e){

    $('.date').datetimepicker({value: '', step: 2});
  })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

<script type="text/javascript">
  var app = angular.module('obserModule',[]);

  app.controller('obserCtl',function($scope,$http,$timeout){

    $scope.success ="";
    $scope.error = "";
    $scope.tbDiv = true;
    $scope.formDiv = false;
    $scope.btnName = "Save";
    $scope.observations = {};

    //showw showTable
    $scope.showTable = function()
    {
      $scope.tbDiv = false;
      $scope.formDiv = true;
    }

    //show form
    $scope.showForm = function()
    {
      $scope.tbDiv = true;
      $scope.formDiv = false;
    }

    //get observation
    $scope.getObservation = function()
    {
      var regid = document.getElementById('Registration_ID').value;
      var url = "http://192.168.112.1/ehmsbmc/files/save_observation_chart.php?action=get_data&regId="+regid+"";
      $http.get(url).then(function(res){
          $scope.observations = res.data;
          console.log($scope.observations);
      },
      function(res){
          $scope.error = res.data;
          console.log($scope.error);
      }
      );
    }

    //save observation data
    $scope.saveObservation = function()
    {
      var url = "http://192.168.112.1/ehmsbmc/files/save_observation_chart.php";
      if (confirm('Are sure want to save this?')) {
        $http.post(url,{
          "Admision_ID":document.getElementById('Admision_ID').value,
          "consultation_id":document.getElementById('consultation_id').value,
          "Registration_ID":document.getElementById('Registration_ID').value,
          "Employee_ID":document.getElementById('Employee_ID').value,
          "name":$scope.name,
          "temp":$scope.temp,
          "ward_no":$scope.ward_no,
          "bp":$scope.bp,
          "pr":$scope.pr,
          "fhr":$scope.fhr,
          "pve":$scope.pve,
          "contraction":$scope.contraction,
          "observation_date":$scope.observation_date,
          "action":"save_observation"
        }).then(function(res){
          $scope.success  = res.data;

          //load observation data
          $scope.getObservation();

          //clear inputs
          $scope.name ="";
          $scope.ward_no = "";
          $scope.bp = "";
          $scope.pr ="";
          $scope.fhr ="";
          $scope.pve ="";
          $scope.temp ="";
          $scope.contraction = "";
          $scope.observation_date = "";
          console.log($scope.success);

          //set timeout for success message
          $timeout(function(){
              $scope.success ="";
          },5000);
        },
       function(res){
         $scope.error = "Error: "+res.data;
         console.log($scope.error);
       });
      }//confirm
    }

  });
</script>




<!-- footer -->
<?php
include("./includes/footer.php");
?>
