<?php
include("./includes/header.php");
include("./includes/connection.php");

if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admision_id'])) {
  $admision_id = $_GET['admision_id'];
}
if (isset($_GET['patient_id'])) {
  $patient_id = $_GET['patient_id'];
}

// get patient details
if (isset($_GET['patient_id']) && $_GET['patient_id'] != 0) {
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

$age = date_diff(date_create($DOB), date_create('today'))->y;

// select second stage of labour
$select_second_stage_of_labour = "SELECT * FROM tbl_second_stage_of_labour WHERE patient_id='$patient_id' AND admission_id='$admision_id'";
if ($result_second_stage = mysqli_query($conn,$select_second_stage_of_labour)) {
  while ($row_second_stage = mysqli_fetch_assoc($result_second_stage)) {

    $time_began = $row_second_stage['time_began'];
    $date_of_birth = $row_second_stage['date_of_birth'];
    $duration = $row_second_stage['duration'];
    $mode_of_delivery = $row_second_stage['mode_of_delivery'];
    $drug = $row_second_stage['drugs'];
    $remarks = $row_second_stage['remarks'];
  }
}
?>
<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id ?>" class="art-button-green">BACK</a>
<br />
<br />
<style media="screen">
table{
  border:none !important;
}
table tr,td{
  border:none !important;
}
  .input-label{
    text-align:right !important;
    width: 10%;
  }
  .input{
    width: 20%;
  }
</style>
<center>
<fieldset>
  <legend align=center>
    <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
    <p style="margin:0px;padding:0px;">Fisrt Stage Of Labour</p>
    <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
  </div>
  </legend>
  <form class="" action="" method="post" id="second_stage">
    <input type="hidden" id="patient_id" name="patient_id" value="<?= $patient_id ?>">
    <input type="hidden" name="admission_id" value="<?= $admision_id ?>">
    <input type="hidden" name="consultaion_id" value="<?= $consultation_id ?>">

    <center>
    <table width="70%">
    <tr>
      <td class="input-label">Time Begane</td>
      <td class="input"><input type="text" name="time_began" class="date" value="<?php if (!empty($time_began)) echo $time_began; ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">Date And Time Of Birth</td>
      <td class="input"><input type="text" class="date" name="date_of_birth" value="<?php if (!empty($date_of_birth)) echo $date_of_birth; ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">Duration</td>
      <td class="input"><input type="text" name="duration" value="<?php if (!empty($duration)) echo $duration; ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">Mode Of Delivery</td>
      <td class="input"><input type="text" name="mode_of_delivery" value="<?php if (!empty($mode_of_delivery)) echo $mode_of_delivery; ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">drugs</td>
      <td class="input"><input type="text" name="drug" value="<?php if (!empty($drug)) echo $drug; ?>"> </td>
    </tr>
    <tr>
      <td class="input-label">Remarks</td>
      <td class="input"><textarea name="remarks" rows="2" cols="40"><?php if (!empty($remarks)) echo $remarks; ?></textarea> </td>
    </tr>
    </table>
  </center>
  </form>
  <br />
  <span><button type="button" class="art-button-green" style="width:45px !important; height:30px !important; color:#fff !important" name="button" id="save">Save</button>
  </span>
  <span><button type="button" class="art-button-green" style="width:45px !important; height:30px !important; color:#fff !important" name="button" id="preview">Prieview</button>
  </span>
</fieldset>
</center>
<?php
include("./includes/footer.php");
?>
<script src="css/jquery.datetimepicker.js"></script>

<script type="text/javascript">

// save labour record
$("#save").click(function(e){
e.preventDefault();
var labour_data = $("#second_stage").serialize();

  $.ajax({
    url:"save_second_stage.php",
    type:"POST",
    data:labour_data,
    success:function(data){
      alert(data);
    }

  })


})

$(document).ready(function(e){
  $('.date').datetimepicker({value: '', step: 2});
})


$("#preview").click(function(){
  
  var patientId = $("#patient_id").val();

  window.open("print_second_stage_of_labour.php?patient_id="+patientId,'_blank');
})

</script>
