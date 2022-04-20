<?php
include("./includes/header.php");
include("./includes/connection.php");


if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admission_id'])) {
  $admision_id = $_GET['admission_id'];
}
if (isset($_GET['patient_id'])) {
  $patient_id = $_GET['patient_id'];
}

if (isset($_GET['Employee_ID'])) {
  $Employee_ID = $_GET['Employee_ID'];
}

//get employee name
$sql_employee = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'");
$Employee_Name = mysqli_num_rows($sql_employee)['Employee_Name'];



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
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
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


// get patient labour details
  $query_select_patient_labour_info = "SELECT * FROM tbl_demographic WHERE patient_id='$patient_id'";
  if ($result_patient_labour_info = mysqli_query($conn,$query_select_patient_labour_info)) {
if (($num=mysqli_num_rows($result_patient_labour_info) )>0 ) {
  while ($row_info=mysqli_fetch_assoc($result_patient_labour_info)) {
      $obstretic = $row_info['obstretic'];
      $history = $row_info['history'];
      $gravida = $row_info['gravida'];
      $lmp = $row_info['lmp'];
      $edd = $row_info['edd'];
      $para = $row_info['para'];
      $ga = $row_info['ga'];
      $bloodgroup = $row_info['bloodgroup'];
      $weight = $row_info['weight'];
      $height = $row_info['height'];
      $date_of_admission=$row_info['date_of_admission'];
      $date_of_first_attendance=$row_info['date_of_first_attendance'];
      $risk_factor=$row_info['risk_factor'];
      $previous_therapy = $row_info['previous_therapy'];
      $date_of_discharge=$row_info['date_of_discharge'];
      $living=$row_info['living'];
      $medical_surgical_history = $row_info['medical_sergical_history'];
      $family_history = $row_info['family_history'];
      $present_history = $row_info['present_history'];
      $labour_history = $row_info['labour_history'];
      $diagnosis_reason_for_admission = $row_info['diagnosis_reason_for_admission'];
      $drug_allegies = $row_info['drug_allegies'];

      $history_of_pregnancy = $row_info['history_of_pregnancy'];
      if($history_of_pregnancy == 'full term delivery')
      {
        $fulltime = 'selected';
      }else if($history_of_pregnancy == 'abortion')
      {
        $abortion = 'selected';
      }

      $date_of_1stvsit = $row_info['date_of_1stvsit'];
      $ga_at_1stvisit = $row_info['ga_at_1stvisit'];
      $number_ofanc_visit = $row_info['number_ofanc_visit'];

      $bp = $row_info['bp'];
      $bp2 = $row_info['bp2'];
      $hb = $row_info['hb'];
      $hb2 = $row_info['hb2'];
      $pmtct = $row_info['pmtct'];
      $pmtct2 = $row_info['pmtct2'];
      $vdrl = $row_info['vdrl'];
      $vdrl2 = $row_info['vdrl2'];
      $mrdt = $row_info['mrdt'];
      $mrdt2 = $row_info['mrdt2'];
      $urinalysis = $row_info['urinalysis'];
      $urinalysis2 = $row_info['urinalysis2'];
      $fefo = $row_info['fefo'];
      $sp = $row_info['sp'];
      $tt = $row_info['tt'];
      $mebendazole = $row_info['mebendazole'];


  }

}
  }

?>
<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id;?>&Employee_ID=<?=$Employee_ID;?>&Registration_ID=<?=$patient_id;?>&admission_id=<?=$admision_id?>" class="art-button-green">BACK</a>
<br /><br />

<style media="screen">
input{
  padding-left: 5px !important;
}
  #left{
    width: 49%;
    float: left;
  }
  #right{
    width: 49%;
    float: right;
  }
  .bottom{
    float:  right;
    text-align: right !important;

  }
  table,tr,td{
      border:none !important;
  }
</style>


<form class="" action="" method="post" id="demographForm">

<input type="hidden" name="save_to_table" value="save_data">
<input type="hidden" name="Employee_Name" value="<?php echo $Employee_Name;?>">
<input type="hidden" name="save_to_labour" value="save_labour_data">
<input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id?>">
<input type="hidden" name="admission_id" value="<?=$admision_id?>">

<fieldset>
  <legend style="font-Weight:bold; " align=center><div style="width:25vw;height:40px; margin:0px !important;padding:0px !important; "><p style="text-align:center; height:10px; margin:0px !important;padding:0px !important;">Demographic</p> <p style="text-align:center; color:yellow; height:10px;"><?=$Patient_Name .'  |  '.$Gender .'  |  '. $age . 'yrs  |  '. $Guarantor_Name?> </p></div></legend>
  <center>

        <table width="90%" border="none">

      <table id="left" border="none">

        <tr>
          <td style="width:10%; text-align:right;">OBSTRETIC</td><td><input type="text" style="width:100%" value="<?php
           if(!empty($obstretic)) echo $obstretic;
          ?>" autocomplete="off" placeholder="OBSTRETIC" id="obstretic" name="obstretic"  ></td>
          <td style="width:10%; text-align:right;">HISTORY</td><td><input type="text" style="width:100%"  autocomplete="off" value="<?php
           if(!empty($history)) echo $history;
          ?>" placeholder="History" id="history" name="history"></td>
        </tr>
        <tr>
          <td style="widht:10%; text-align:right;">GRAVIDA</td><td><input type="text" style="width:100%;"  autocomplete="off" value="<?php
           if(!empty($gravida)) echo $gravida;
          ?>" placeholder="GRAVIDA" id="gravida" name="gravida"></td>
          <td style="widht:10%; text-align:right;">PARA</td><td><input type="text" style="width:100%;"  autocomplete="off" value="<?php
           if(!empty($para)) echo $para;
          ?>" placeholder="PARA" id="para" name="para"></td>
        </tr>
        <tr>
          <td style="widht:10%; text-align:right;">LMP</td><td><input type="text" style="width:100%;"  autocomplete="off" placeholder="LMP"
            value="<?php
             if(!empty($lmp)) echo $lmp;
            ?>" name="lmp" id="lmp"></td>
          <td style="widht:10%; text-align:right;">E.D.D</td><td><input type="text" style="width:100%;"  autocomplete="off" value="<?php
           if(!empty($edd)) echo $edd;
          ?>" placeholder="E.D.D" id="edd" name="edd"></td>
        </tr>
        <tr>
          <td style="widht:10%; text-align:right;" >GA</td><td><input type="text" style="width:100%;"  autocomplete="off" placeholder="GA"
            value="<?php
             if(!empty($ga)) echo $ga;
            ?>" id="ga" name="ga"></td>
          <td style="widht:10%; text-align:right;">BLOODGROUP</td><td><input type="text" style="width:100%;"  autocomplete="off" value="<?php
           if(!empty($bloodgroup)) echo $bloodgroup;
          ?>" placeholder="bloodgroup" id="bloodgroup" name="bloodgroup"></td>
        </tr>
        <tr>
          <td style="widht:10%; text-align:right;">WEIGHT</td><td><input type="text" style="width:100%;"  autocomplete="off" value="<?php
           if(!empty($weight)) echo $weight;
          ?>" placeholder="Weight" id="weight" name="weight"></td>
          <td style="widht:10%; text-align:right;">HEIGHT</td><td><input type="text"  autocomplete="off" placeholder="height" value="<?php
           if(!empty($height)) echo $height;
          ?>" id="height" style="width:100%;"name="height"></td>
        </tr>

      </table>

      <table id="right">

        <tr>
          <td style="width:15%;text-align:right;">Date Of Admission</td><td style="width:49%; text-align:right !important"><input type="text" class="date" style="width:100%; "  autocomplete="off" value="<?php
           if(!empty($date_of_admission)) echo $date_of_admission;
          ?>"  placeholder="Date of Admission" id="date_of_admission" name="date_of_admission"></td>

        </tr>
        <tr>
          <td style="widht:15%; text-align:right !important">Date Of First Attendance</td><td style="width:49%;"><input type="text" class="date" style="width:100%;"  autocomplete="off" placeholder="Date Of First Attendance" value="<?php
           if(!empty($date_of_first_attendance)) echo $date_of_first_attendance;
          ?>" name="date_of_first_attendance" id="date_of_first_attendance"></td>

        </tr>
        <tr>
          <td style="widht:15%; text-align:right !important">Risk Factor</td><td style="width:49%;"><input type="text" style="width:100%;" name="risk_factor" id="risk_factor"  autocomplete="off" value="<?php
           if(!empty($risk_factor)) echo $risk_factor;
          ?>" placeholder="Risk Factor"></td>
        <tr>
          <td style="widht:15%; text-align:right !important">Prevous Therapy</td><td style="width:49%;"><input type="text" style="width:100%;"  autocomplete="off" placeholder="Prevous Therapy" value="<?php
           if(!empty($previous_therapy)) echo $previous_therapy;
          ?>" name="previous_therapy" id="previous_therapy"></td>

        </tr>
        <tr>
          <td style="widht:15%; text-align:right !important">Date Of Discharge</td><td style="width:49%;">
            <input type="text" style="width:100%;" class="date"  autocomplete="off" placeholder="Date Of Discharge"  value="<?php
             if(!empty($date_of_discharge)) echo $date_of_discharge;
            ?>"  name="date_of_discharge" id="date_of_discharge"></td>
        </tr>
        <tr>
          <td style="widht:15%; text-align:right !important">Living</td><td style="width:49%;">
            <input type="text" style="width:100%;" class="form-control"  autocomplete="off" placeholder="Living"  value="<?php
             if(!empty($living)) echo $living;
            ?>"  name="living" id="living"></td>
        </tr>
      </table>

    </table>
  </center>
</fieldset>

<br />

<center>
  <fieldset style="padding:10px">
    <table id="left">
      <tr>
        <td class="bottom">Past Medical & Surgical History</td>
        <td><textarea name="medical_surgical_history" id="medical_surgical_history" rows="2" cols="80" style="padding-left:5px;" autocomplete="off" placeholder="Medical & Surgical History" value=""><?php
         if(!empty($medical_surgical_history)) echo $medical_surgical_history;
        ?></textarea>
          </td>
      </tr>

      <tr>
        <td class="bottom">Family History</td>
        <td><textarea name="family_history" id="family_history" rows="2" cols="80" style="padding-left:5px;" autocomplete="off" placeholder="Family History" value=""><?php
         if(!empty($family_history)) echo $family_history;
        ?></textarea></td>
      </tr>
      <tr>
        <td class="bottom">Presente History</td>
        <td><textarea name="present_history" id="present_history" rows="2" cols="80" style="padding-left:5px;" autocomplete="off" placeholder="Presente History" value=""><?php
         if(!empty($present_history)) echo $present_history;
        ?></textarea></td>
      </tr>
      <tr>
        <td class="bottom">Lobour History</td>
        <td><textarea name="labour_history" id="labour_history" rows="2" cols="80" style="padding-left:5px;" autocomplete="off" placeholder="Labour History" value=""><?php
         if(!empty($labour_history)) echo $labour_history;
        ?></textarea></td>
      </tr>
    </table>
      <table id="right">
      <tr>
        <td class="bottom">Diagnosis Reason For Admission</td>
        <td><textarea name="diagnosis_reason_for_admission" id="diagnosis_reason_for_admission" rows="2" cols="80" style="padding-left:5px;" autocomplete="off" placeholder="Diagnosis Reason For Admission" value=""><?php
         if(!empty($diagnosis_reason_for_admission)) echo $diagnosis_reason_for_admission;
        ?></textarea></td>
      </tr>
      <tr>
        <td class="bottom">Drug Allegies</td>
        <td><textarea name="drug_allegies" id="drug_allegies" rows="2" cols="80" style="padding-left:5px;" autocomplete="off" placeholder="Drug Allegies" value=""><?php
         if(!empty($drug_allegies)) echo $drug_allegies;
        ?></textarea></td>
      </tr>
      <tr>
        <td class="bottom">history of previous pregnancy</td>
        <td>
          <select name="history_of_pregnancy" required id="history_of_pregnancy" style="padding:4px; width: 100%;font-size:18px;font-weight: lighter ">
              <option value="">Select</option>
              <option value="full term delivery" <?php echo $fulltime; ?>>full term delivery</option>
              <option value="abortion" <?php echo $abortion; ?>>abortion</option>

          </select>
      </td>
      </tr>
    </table>

  </fieldset>
</center>
<br /><br />
<!-- done by Abdul -->
<center>
  <fieldset>
    <legend>HISTORY OF INDEX PREGNANCY (current pregnancy)</legend>
    <table width="100%">
      <tr>
        <td>Date of 1st ANC visit <input type="text" style="width:100%;" class="date"  autocomplete="off"   value="<?php
         if(!empty($date_of_1stvsit)) echo $date_of_1stvsit;
        ?>"  name="date_of_1stvsit" id="date_of_1stvsit"></td>

        <td>GA at 1st visit <input type="text" style="width:100%;"   autocomplete="off"   value="<?php
         if(!empty($ga_at_1stvisit)) echo $ga_at_1stvisit;
        ?>"  name="ga_at_1stvisit" id="ga_at_1stvisit"></td>
        <td>Number of ANC visits <input type="text" style="width:100%;"   autocomplete="off"   value="<?php
         if(!empty($number_ofanc_visit)) echo $number_ofanc_visit;
        ?>"  name="number_ofanc_visit" id="number_ofanc_visit"></td>
      </tr>
    </table>
    <br/>
    <table class="table table-striped" width="100%">
      <!-- row1 -->
      <tr>
        <th></th>
        <th>BP</th>
        <th>HB</th>
        <th>PMTCT</th>
        <th>VDRL</th>
        <th>MRDT</th>
        <th>Urinalysis</th>
        <th colspan="4">Medication Given</th>
      </tr>
      <!-- row2 -->
      <tr>
        <td><b>1st Visit</b></td>
        <!-- bp -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($bp)) echo $bp;
          ?>"  name="bp" id="bp">
        </td>
        <!-- hb -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($hb)) echo $hb;
          ?>"  name="hb" id="hb">
        </td>
        <!-- pmtct -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($pmtct)) echo $pmtct;
          ?>"  name="pmtct" id="pmtct">
        </td>
        <!-- vdrl -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($vdrl)) echo $vdrl;
          ?>"  name="vdrl" id="vdrl">
        </td>
        <!-- mrdt -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($mrdt)) echo $mrdt;
          ?>"  name="mrdt" id="mrdt">
        </td>
        <!-- urinalysis -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($urinalysis)) echo $urinalysis;
          ?>"  name="urinalysis" id="urinalysis">
        </td>
        <!-- fefo -->
        <td rowspan="2">
          <b>Fefo</b><br/>
          <textarea name="fefo" id="fefo" rows="2" cols="2" style="padding-left:5px;" autocomplete="off"  value=""><?php
           if(!empty($fefo)) echo $fefo;
          ?></textarea>
        </td>
        <!-- sp -->
        <td rowspan="2">
          <b>SP</b><br/>
          <textarea name="sp" id="sp" rows="2" cols="2" style="padding-left:5px;" autocomplete="off"  value=""><?php
           if(!empty($sp)) echo $sp;
          ?></textarea>
        </td>
        <!-- tt -->
        <td rowspan="2">
          <b>TT</b><br/>
          <textarea name="tt" id="tt" rows="2" cols="2" style="padding-left:5px;" autocomplete="off"  value=""><?php
           if(!empty($tt)) echo $tt;
          ?></textarea>
        </td>
        <!-- mebendazole -->
        <td rowspan="2" colspan="2">
          <b>Mebendazole</b><br/>
          <textarea name="mebendazole" id="mebendazole" rows="2" cols="4" style="padding-left:5px;" autocomplete="off"  value=""><?php
           if(!empty($mebendazole)) echo $mebendazole;
          ?></textarea>
        </td>

      </tr>
      <!-- row3 -->
      <tr>
        <td><b>Other Visit</b></td>
        <!-- bp2 -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($bp2)) echo $bp2;
          ?>"  name="bp2" id="bp2">
        </td>
        <!-- hb2 -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($hb2)) echo $hb2;
          ?>"  name="hb2" id="hb2">
        </td>
        <!-- pmtct2 -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($pmtct2)) echo $pmtct2;
          ?>"  name="pmtct2" id="pmtct2">
        </td>
        <!-- vdrl2 -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($vdrl2)) echo $vdrl2;
          ?>"  name="vdrl2" id="vdrl2">
        </td>
        <!-- mrdt2 -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($mrdt2)) echo $mrdt2;
          ?>"  name="mrdt2" id="mrdt2">
        </td>
        <!-- urinalysis2 -->
        <td>
          <input type="text" style="width:100%;"  autocomplete="off"   value="<?php
           if(!empty($urinalysis2)) echo $urinalysis2;
          ?>"  name="urinalysis2" id="urinalysis2">
        </td>

      </tr>
    </table>
  </fieldset>

<!-- end done by Abdul -->
<br /><br />
<center>
  <fieldset>
    <table width="100%">
      <tr>
        <td>  <input type="text" name="year_of_birth" id="year_of_birth" value="" placeholder="Year Of Birth"> </td>
        <td>  <input type="text" name="date_and_time" id="date_and_time" value="" placeholder="Date And Time" class="date"> </td>
        <td>  <input type="text" name="matunity" id="matunity" value=""  autocomplete="off" placeholder="Matunity"> </td>
        <td>
          <select class="" name="gender" id="gender" style="height:100% !important; text-align:center !important">
            <option value="">--Gender--</option>
            <option value="me">Male</option>
            <option value="ke">Female</option>
          </select>

        </td>
        <td>  <input type="text" name="mode_of_delivery" id="mode_of_delivery" value=""  autocomplete="off" placeholder="Mode Of Delivery"> </td>
        <td>  <input type="text" name="birth_weight" value=""  autocomplete="off" placeholder="Birth Weight" id="birth_weight" > </td>
        <td>  <input type="text" name="place_of_birth" id="place_of_birth" value="" placeholder="Place Of Birth"> </td>
        <td>  <input type="text" name="breast_fed_duration" id="breast-breast_fed_duration" value=""  autocomplete="off" placeholder="Breast Fed Duration"> </td>
        <td>  <input type="text" name="peuperium" id="peuperium" value=""  autocomplete="off" placeholder="Pueperium"> </td>
        <td>  <input type="text" name="present_child_condition" id="present_child_condition" value=""  autocomplete="off" placeholder="Present Child Condiotion "> </td>
        <td>  <input type="button" class="art-button-green" id="add" value="Add"> </td>
      </tr>
    </table>

  </fieldset>
</center>
<br />
<center>
  <fieldset style="height:250px;">
    <table width="100%" style="background:#fff; margin-top:6px;" id="history_of_labour">
      <thead><tr>
        <th>Sn</th>
        <th>Year Of Birth</th>
        <th>Date And Time</th>
        <th>Matunity</th>
        <th>Sex</th>
        <th>Mode Of Delivery</th>
        <th>Birth Weight</th>
        <th>Place Of Birth</th>
        <th>Breast Fed Duration</th>
        <th>Pueperium</th>
        <th>Present Condition Of Child</th>
        <th>Prepared By</th>
      </tr></thead>
      <tbody>
        <?php
        // query labour history if exist
        $query_select_labour_history  = "SELECT * FROM tbl_child_labour_history WHERE Registration_ID = '$patient_id'";
        if($result_labour_history = mysqli_query($conn,$query_select_labour_history)) {
          if ($num=mysqli_num_rows($result_labour_history) >0 ) {

            $tmp = 1;
            while ($row=mysqli_fetch_assoc($result_labour_history)) {
              echo "<tr style='height:20px;'>
              <td style='text-align:center'> " .$tmp++ ."</td>
              <td style='text-align:center'>".$row['year_of_birth'] ."</td>
              <td style='text-align:center'>".$row['date_and_time'] ."</td>
              <td style='text-align:center'>" .$row['matunity']."</td>
              <td style='text-align:center'>" .$row['gender']."</td>
              <td style='text-align:center'>" .$row['mode_of_delivery']."</td>
              <td style='text-align:center'>" .$row['birth_weight']."</td>
              <td style='text-align:center'>" .$row['place_of_birth']."</td>
              <td style='text-align:center'>" .$row['breast_fed_duration']."</td>
              <td style='text-align:center'>" .$row['peuperium']."</td>
              <td style='text-align:center'>" .$row['present_child_condition']."</td>
              <td style='text-align:center'>" .$row['Employee_Name']."</td>
              </tr>";
            }

          }

        }
        ?>
      </tbody>
    </table>

  </fieldset>
  <p style="margin-bottom:20px !important;">
    <a href="#" target="_blank" class="art-button-green" style="float:right" id="preview">Priview</a>
  <input type="submit" name="" style="width:100px; float:right; " class="art-button-green" id="save" value="Save">
  </p>
</center>
<br />

</form>


<!--  Form varification  and submission -->
<script type="text/javascript">
  //check for empty data
  var obstretic = $("#obstretic").val();
  var history = $("#history").val();
  var gravida = $("#gravida").val();
  var para = $("#para").val();
  var lmp = $("#lmp").val();
  var edd = $("#edd").val();
  var ga = $("#ga").val();
  var bloodgroup = $("#bloodgroup").val();
  var weight = $("#weight").val();
  var height = $("#height").val();
  var date_of_admission = $("#date_of_admission").val();
  var date_of_first_attendance = $("#date_of_first_attendance").val();
  var risk_factor = $("#risk_factor").val();
  var previous_therapy = $("#previous_therapy").val();
  var date_of_discharge = $("#date_of_discharge").val();
  var living = $("#living").val();
  var medical_surgical_history = $("#medical_surgical_history").val();
  var family_history = $("#family_history").val();
  var drug_allegies = $("#drug_allegies").val();
  var diagnosis_reason_for_admission = $("#diagnosis_reason_for_admission").val();

  // add these data to the table
  var year_of_birth = $("#year_of_birth").val();
  var date_and_time = $("#date_and_time").val();
  var matunity = $("#matunity").val();
  var gender = $("#gender").val();
  var mode_of_delivery = $("#mode_of_delivery").val();
  var birth_weight = $("#birth_weight").val();
  var place_of_birth = $("#place_of_birth").val();
  var breast_fed_duration = $("#breast_fed_duration").val();
  var peuperium = $("#peuperium").val();
  var present_child_condition = $("#present_child_condition").val();

  // this button  add data to the table
  $("#demographForm").submit(function(e){
    e.preventDefault();
    var demographicData = $(this).serialize();

    $.ajax({
      url:"save_demographic_data.php",
      type:"POST",
      data:demographicData,
      success:function(data){
        alert(data)
      }
    })
  })

  // Add data to the table below
  $("#add").click(function(e){
    var demographicData = $("#demographForm").serialize();
    $.ajax({
      url:"add_labour_history.php",
      type:"POST",
      data:demographicData,
      success:function(data){

        $("#history_of_labour tbody").append(data);
        location.reload(true);

      }
    })

  })


$("#preview").click(function(e){
  e.preventDefault();
  var demographicData = $("#demographForm").serialize();




  $.ajax({
    url:"print_demographic_page.php",
    type:"POST",
    data:demographicData,
    success:function(data){
      var patientId = $("#patient_id").val();
      window.open("print_demographic_page.php?patient_id="+patientId,'_blank');
      //var w = window.open("print_demographic_page.php");
      $(w.document.body).html(data);

    }
  })

})

</script>




<!-- <script src="css/jquery.js"></script> -->
<script src="css/jquery.datetimepicker.js"></script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.date').datetimepicker({value: '', step: 2});
  })


</script>
<?php
include("./includes/footer.php");
?>
