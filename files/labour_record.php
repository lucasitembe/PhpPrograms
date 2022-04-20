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
$time_now=mysqli_query($conn,"SELECT CURDATE()");
$age = date_diff(date_create($DOB), date_create('today'))->y;
// get labour record of a particula user
// die("SELECT * FROM  tbl_labour_record2 WHERE Registration_ID='$Registration_ID' AND admission_id='$admision_id'");
$select_labour_record = "SELECT * FROM  tbl_labour_record2 WHERE Registration_ID='$Registration_ID' AND admission_id='$admision_id'";
if ($result_labour_record = mysqli_query($conn,$select_labour_record)) {
  if ($num = mysqli_num_rows($result_labour_record) > 0) {
    while ($row_labour = mysqli_fetch_assoc($result_labour_record)) {
      $summary_Antenatal  = $row_labour['summary_Antenatal'];
      $abnormalities = $row_labour['abnormalities'];
      $lmp = $row_labour['lmp'];
      $edd  = $row_labour['edd'];
      $ga = $row_labour['ga'];
      $general_condition = $row_labour['general_condition'];
      $fundamental_height = $row_labour['fundamental_height'];
      $blood_pressure = $row_labour['blood_pressure'];
      $size_fetus = $row_labour['size_fetus'];
      $lie = $row_labour['lie'];
      $oedema = $row_labour['oedema'];
      $presentation = $row_labour['presentation'];
      $acetone = $row_labour['acetone'];
      $protein = $row_labour['protein'];
      $liquor = $row_labour['liquor'];
      $height = $row_labour['height'];
      $meconium = $row_labour['meconium'];
      $estimation_presentation = $row_labour['estimation_presentation'];
      $membrane = $row_labour['membrane'];
      $last_recorded = $row_labour['last_recorded'];
      $blood_group = $row_labour['blood_group'];
      $cervic_state = $row_labour['cervic_state'];
      $presenting_part = $row_labour['presenting_part'];
      $levels = $row_labour['levels'];
      $position = $row_labour['position'];
      $moulding = $row_labour['moulding'];
      $caput = $row_labour['caput'];
      $bony = $row_labour['bony'];
      $membranes_liquor = $row_labour['membranes_liquor'];
      $sacral_promontory = $row_labour['sacral_promontory'];
      $sacral_curve = $row_labour['sacral_curve'];
      $Lachial_spines = $row_labour['Lachial_spines'];
      $subpubic_angle = $row_labour['subpubic_angle'];
      $sacral_tuberosites = $row_labour['sacral_tuberosites'];
      $summary = $row_labour['summary'];
      $remarks = $row_labour['remarks'];
      $dilation = $row_labour['dilation'];
      $temperature = $row_labour['temperature'];
      $admission_reason = $row_labour['admission_reason'];
      $admission_from = $row_labour['admission_from'];
      $date_time = $row_labour['date_time'];
      $lv_children = $row_labour['lv_children'];
      $gravida = $row_labour['gravida'];
      $para = $row_labour['para'];
      
    }

  }

}

?>

<!-- <a href="patograph_record.php?consultation_id=<?= $consultation_id;?>&patient_id=<?=$patient_id;?>&admission_id=<?=$admision_id?>" class="art-button-green">BACK</a> -->
<a href="print_labour_record_test.php?consultation_id=<?= $consultation_id; ?>&Registration_ID=<?= $Registration_ID; ?>&admission_id=<?= $admision_id ?>" class="art-button-green" target="_blank">PREVIEW RECORDS</a>
<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id ?>" class="art-button-green">BACK</a>


    <style>
        input[type="radio"]{
            width: 20px; 
            height: 30px;
            cursor: pointer;
    }
    </style>


<center>
  <fieldset>
    <legend style="font-weight:bold"align=center>
      <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
        <p style="margin:0px;padding:0px;">LABOUR RECORD</p>
        <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
      </div>
  </legend>
    <form class="" action="" method="post" id="labour_form">

      <input type="hidden" name="Registration_ID" id="Registration_ID" value="<?= $patient_id ?>">
      <input type="hidden" name="admission_id" id="admission_id" value="<?= $admision_id ?>">
        <?php
        if(mysqli_num_rows($result_labour_record)>0){
          ?>


<fieldset style="width:90%;">
            <legend align="center" style="font-weight:bold">ADMISSION</legend>
        <table class="table table-striped table-hover" style="width:90%;">
        <tbody>
          <tr>
          <td>ADMISSION REASON</td>
            <td>
              <input type="text" name="admission_reason" id="admission_reason" value="<?php echo $admission_reason;?>"  class="input form-control"> 
            </td>
            <td>FROM</td>
                <td>
                  <select class="form-control" id="from">
                    <option value="<?php echo $admission_from;?>" ><?php echo $admission_from;?></option>
                    <option value="">Select</option>
                    <option value="Home">Home</option>
                    <option value="Hospital Transfer">Hospital Transfer</option>
                    <option value="Antenatal Ward">Antenatal Ward</option>
                    <option value="Clinic">Clinic</option>
                  </select>
                </td>
          </tr>
          <tr>
                <td>Summary Of Antenatal</td>
                <td><textarea name="summary_Antenatal" id="summary_Antenatal" class="form-control"><?php echo $summary_Antenatal;?></textarea>
                </td>
                <td>Abnormalities</td>
                <td><textarea name="abnormalities" id="abnormalities" class="form-control"><?php echo $abnormalities;?></textarea>
                </td>
          </tr>
        </tbody>
      </table>
      
      
      <br/>
      <table class="table table-hover table-striped" style="width:90%;">
      <tbody>
        <tr style="background-color:#bdb5ac;">
          <td>LMP</td>
          <td><input type="text" class="form-control" id="lmp" value="<?php echo $lmp;?>" name="lmp"></td>
          <td>EDD</td>
          <td><input type="text" class="form-control" id="edd" value="<?php echo $edd;?>" name="edd"></td>
          <td>GA</td>
          <td><input type="text" class="form-control" id="ga"value="<?php echo $ga;?>" name="ga"></td>
        </tr>
        <tr style="background-color:#bdb5ac;">
          <td>GRAVIDA</td>
          <td><input type="text" class="form-control" id="gravida" value="<?php echo $gravida;?>" name="gravida"></td>
          <td>PARA</td>
          <td><input type="text" class="form-control" id="para" value="<?php echo $para;?>" name="para"></td>
          <td>LIVING CHILDREN</td>
          <td><input type="text" class="form-control" id="lv_children" value="<?php echo $lv_children;?>" name="lv_children"></td>
        </tr>
      <tbody>
      </table>
      <table style="width:90%;margin-top:20px;" class="table table-striped table-hover" id='colum-addition'>
        <thead style="background-color:#bdb5ac">
        <!-- bdb5ac -->
        <!-- e6eded -->
        <!-- a3c0cc -->
            <tr>
                <th style="text-align:center;" colspan="7"><b>OBSTETRIC HISTORY</b></th>
                <!-- <th style="text-align:center;" colspan="4"><b>GRAVIDA</b></th>
                <th style="text-align:center;" colspan="2"><b>PARA</b></th>
                <th style="text-align:center;" colspan="5"><b>LIVING CHILDREN</b></th> -->
            </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>YEAR</b></td>
            <td><b>COMPLICATION</b></td>
            <td><b>METHOD</b></td>
            <td><b>WT</b></td>
            <td colspan="2" style="text-align:center;" ><b>ALIVE</b></td>
            <!-- <td><b>YEAR</b></td>
            <td><b>COMPLICATIONS</b></td>
            <td><b>METHOD</b></td>
            <td><b>WT</b></td>
            <td colspan="2" style="text-align:center;" ><b>ALIVE</b></td> -->
            <td><b>ACTION</b></td>
          </tr>
          <tr>
            <td><input type="date" id="history_year" name="history_year[]" class="form-control"></td>
            <td><textarea rows="1" id="history_complication" name="history_complication[]" class="form-control"></textarea></td>
            <td><input type="text" id="gravida_method" name="gravida_method[]" class="form-control"></td>
            <td><input type="text" id="gravida_wt" name="gravida_wt[]" class="form-control"></td>
            <td>Yes<input type="radio" id="gravida_alive" name="gravida_alive[]" value="Yes"></td>
            <td>No<input type="radio" id="gravida_alive1" name="gravida_alive[]" value="No"></td>
            <!-- <td><input type="date" id="para_year" name="para_year[]" class="form-control"></td>
            <td><textarea rows="1" id="para_complication" name="para_complication[]" class="form-control"></textarea></td>
            <td><input type="text" id="living_method" name="living_method[]" class="form-control"></td>
            <td><input type="text" id="living_wt" name="living_wt[]" class="form-control"></td>
            <td>Yes<input type="radio" id="living_alive" name="living_alive[]"value="Yes"></td>
            <td>No<input type="radio" id="living_alive" name="living_alive[]" value="No"></td> -->
            <td><input type="button" class="art-button-green" value="ADD" id="add_new_row"/></td>
          </tr>
          <input type='hidden' id='rowCount' value='1'>
        </tbody>
        </table>
        </fieldset>
      
      <fieldset style="width:90%;margin-top:20px;"">
        <legend align="center" style="font-weight:bold;margin-top:20px;">EXAMINATION</legend>
        <table style="width:90%;" class="table table-striped table-hover">
          <!-- <thead style="background-color:#bdb5ac">
            <tr>
              <th colspan="4">EXAMINATION</th>
            </tr>
          </thead> -->
          <tr>
            <td>General Condition</td>
            <td><input type="text" name="general_condition" id="general_condition" value="<?php echo $general_condition;?>"class="form-control"></td>
            <td style="text-align:right;  ">Fundal Height</td>
            <td><input type="text" name="fundamental_height" class="form-control" value="<?php echo $fundamental_height;?>" id="fundamental_height" > </td>
          </tr>
          <tr>
            <td>Temperature</td>
            <td><input type="text" name="temperature" id="temperature"value="<?php echo $temperature;?>" class="form-control"></td>
            <td style="text-align:right;  ">Size Of Fetus</td>
            <td><input type="text" name="size_fetus" class="form-control" value="<?php echo $size_fetus;?>" id="size_fetus" > </td>
          </tr>
          <tr>
            <td>Blood Pressure</td>
            <td><input type="text" name="blood_pressure" id="blood_pressure"value="<?php echo $blood_pressure;?>" class="form-control"></td>
            <td style="text-align:right;  ">Lie</td>
            <td><input type="text" name="lie" class="form-control"value="<?php echo $lie;?>"  id="lie" > </td>
          </tr>
          <tr>
            <td>Oedema</td>
            <td><input type="text" name="oedema" id="oedema"value="<?php echo $oedema;?>" class="form-control"></td>
            <td style="text-align:right;  ">presentation</td>
            <td><input type="text" name="presentation" class="form-control" value="<?php echo $presentation;?>" id="presentation" > </td>
          </tr>
          <tr>
            <td>Urine:</td>
            <td>Acetone<input type="text" name="acetone" id="acetone"value="<?php echo $acetone;?>" class="form-control">Proten<input type="text"value="<?php echo $protein;?>" name="protein" id="protein" class="form-control"></td>
            <td style="text-align:right;  ">Liquor</td>
            <td>
              <select id="liquor" name="liquor" class="form-control">
                <option value="<?php echo $liquor;?>"><?php echo $liquor;?></option>
                <option value="Membranes">Membranes</option>
                <option value="Intact">Intact</option>
                <option value="Clear">Clear</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Height</td>
            <td><input type="text" name="height" id="height"value="<?php echo $height;?>" class="form-control"></td>
            <td style="text-align:right;  ">Meconium Stained</td>
            <td><input type="text" name="meconium" class="form-control" value="<?php echo $meconium;?>" id="meconium"> </td>
          </tr>
          <tr>
            <td>Height</td>
            <td><input type="text" name="height" id="height"value="<?php echo $height;?>" class="form-control"></td>
            <td style="text-align:right;  ">Meconium Stained</td>
            <td><input type="text" name="meconium" class="form-control" value="<?php echo $meconium;?>" id="meconium"> </td>
          </tr>
          <tr>
            <td>Ho Estimation Of Presentation</td>
            <td><input type="text" name="estimation_presentation"value="<?php echo $estimation_presentation;?>" id="estimation_presentation" class="form-control"></td>
            <td style="text-align:right;  ">If Membrane Ruptured</td>
            <td><input type='text' name='membrane' id='membrane' style="text-align: center;" value="<?php echo $membrane;?>" autocomplete='off' style="text-align: center;"readonly="readonly" class="form-control"></td>
          </tr>
          <tr>
            <td>Last Recorded A/N</td>
            <td><input type="text" name="last_recorded" id="last_recorded"value="<?php echo $last_recorded;?>" class="form-control"></td>
            <td style="text-align:right;">Blood Group</td>
            <td><input type="text" name="blood_group" class="form-control" value="<?php echo $blood_group;?>" id="blood_group"> </td>
        </table>
      </fieldset>
      <fieldset style="width:90%;margin-top:20px;">
        <legend align="center" style="font-weight:bold;margin-top:20px;">INITIAL VAGINAL EXAMINATION AND PELVIC ASSESSMENT</legend>

        <!-- INITIAL EXAMINATION -->
        

        <table style="width:90%;" class="table table-striped table-hover">
          <!-- <thead style="background-color:#bdb5ac">
            <tr>
              <th colspan="4">EXAMINATION</th>
            </tr>
          </thead> -->
          <tr>
          <td>DATE AND TIME </td>
            <td> <input type='text' name='date_time' id='date_time' style="text-align: center;" value="<?php echo $date_time;?>"autocomplete='off' style="text-align: center;"readonly="readonly" class="form-control"></td>
            <td style="text-align:right;">Bony Pelvis</td>
            <td><input type="text" name="bony"id="bony"value="<?php echo $bony;?>" class="form-control"> </td>
          </tr>
          <tr>
          <td>Cervic:state</td>
            <td><select id="cervic_state" name="cervic_state" class="form-control">
              <option value="<?php echo $cervic_state;?>"><?php echo $cervic_state;?></option>
              <option value="Effected">Effected</option>
              <option value="Partially Effected">Partially Effected</option>
              <option value="Not Effected">Not Effected</option>

            </select> </td>
            <td style="text-align:right;">Sacral promontory</td>
            <td><select class="form-control" name="sacral_promontory" id="sacral_promontory">
              <option value="<?php echo $sacral_promontory;?>"><?php echo $sacral_promontory;?></option>
              <option value="Not">Not</option>
              <option value="Just">Just </option>
              <option value="Easy Reached">Easy Reached</option>
            </select> </td>
          </tr>
          <tr>
          <td>Dilation</td>
            <td><input type="text" name="dilation" id="dilation" value="<?php echo $dilation;?>" class="form-control">  </td>
            <td style="text-align:right;">sSacral curve</td>
            <td><select class="form-control" id="sacral_curve" name="sacral_curve">
              <option value="<?php echo $sacral_curve;?>"><?php echo $sacral_curve;?></option>
              <option value="Flat">Flat</option>
              <option value="Normal">Normal</option>
              </select> </td>
          </tr>
          <tr>
          <td>Presenting Part</td>
            <td><input type="text"value="<?php echo $presenting_part;?>" name="presenting_part" id="presenting_part" class="form-control"> </td>
            <td style="text-align:right;">Lachial Spines</td>
            <td><select class="form-control" id="Lachial_spines" name="Lachial_spines">
              <option value="<?php echo $Lachial_spines;?>"><?php echo $Lachial_spines;?></option>
              <option value="Prominent">Prominent</option>
              <option value="Normal">Normal</option>
              </select> </td>
          </tr>
          <tr>
          <td>Level</td>
            <td><input type="text" name="levels" id="levels" value="<?php echo $levels;?>"id="levels" class="form-control"> </td>
            <td style="text-align:right;">Subpubic Angle</td>
            <td><select class="form-control" id="subpubic_angle"name="subpubic_angle">
              <option value="<?php echo $subpubic_angle;?>"><?php echo $subpubic_angle;?></option>
              <option value="Narrow">Narrow</option>
              <option value="Normal">Normal</option>
            </select> </td>
          </tr>
          <tr>
            <td>Position</td>
            <td><input type="text" name="position"value="<?php echo $position;?>" id="position" class="form-control"> </td>
            <td style="text-align:right;">Sacral Tuberosites</td>
            <td><input type="text" class="form-control" id="sacral_tuberosites"value="<?php echo $sacral_tuberosites;?>" name="sacral_tuberosites"> </td>
          </tr>
          <tr>
          <td>Moulding</td>
            <td><input type="text" name="moulding"value="<?php echo $moulding;?>" id="moulding" class="form-control"> </td>
            <td  style="text-align:right;">Summary</td>
            <td><textarea name="summary" id="summary"class="form-control"><?php echo $summary;?></textarea> </td>
          </tr>
          <tr>
          <td>Caput</td>
            <td><input type="text" name="caput" id="caput"value="<?php echo $caput;?>" class="form-control"> </td>
            <td style="text-align:right;">Membranes/Liquor</td>
            <td><input type="text" name="membranes_liquor"value="<?php echo $membranes_liquor;?>" class="form-control" id="membranes_liquor" > </td>
          </tr>
          <tr>
          

            <td colspan="3">Consultants's/Registra's Opinion</td>
            <!-- <td><input type="text" name="dilation" value=""> </td>
           </tr> -->
             <td><textarea name="remarks" id="remarks" rows="2" cols="80" class="form-control"><?php echo $remarks;?></textarea> </td>
            </tr>
        </table>
        <!-- END OF INITIAL EXAMINATION -->
        
       
        <br/>
      </fieldset>
      <span style="margin-top:10px;">
        <!-- <input =type="submit" class="art-button-green" name="save" id="save" value="SAVE"> -->
        <input type="button" class="art-button-green" onclick="update_labour()" value="UPDATE">
      </span>
      <!-- <span>
        <input type="submit" class="art-button-green" name="save" id="preview" value="PREVIEW">
      </span> -->
      </center>
    </form>
          <?php
            }else{
          ?>
            <fieldset style="width:90%;">
            <legend align="center" style="font-weight:bold">ADMISSION</legend>
        <table class="table table-striped table-hover" style="width:90%;">
        <tbody>
          <tr>
          <td>ADMISSION REASON</td>
            <td>
              <input type="text" name="admission_reason" id="admission_reason" value="<?php echo $admission_reason;?>"  class="input form-control"> 
            </td>
            <td>FROM</td>
                <td>
                  <select class="form-control" id="from">
                    <option value="">Select</option>
                    <option value="Home">Home</option>
                    <option value="Hospital Transfer">Hospital Transfer</option>
                    <option value="Antenatal Ward">Antenatal Ward</option>
                    <option value="Clinic">Clinic</option>
                  </select>
                </td>
          </tr>
          <tr>
                <td>Summary Of Antenatal</td>
                <td><textarea name="summary_Antenatal" id="summary_Antenatal" value="<?php echo $summary_Antenatal;?>" class="form-control"></textarea>
                </td>
                <td>Abnormalities</td>
                <td><textarea name="abnormalities" id="abnormalities" value="<?php echo $abnormalities;?>" class="form-control"></textarea>
                </td>
          </tr>
        </tbody>
      </table>
      
      
      <br/>
      <table class="table table-hover table-striped" style="width:90%;">
      <tbody>
        <tr style="background-color:#bdb5ac;">
          <td>LMP</td>
          <td><input type="text" class="form-control" id="lmp" value="<?php echo $lmp;?>" name="lmp"></td>
          <td>EDD</td>
          <td><input type="text" class="form-control" id="edd" value="<?php echo $edd;?>"name="edd"></td>
          <td>GA</td>
          <td><input type="text" class="form-control" id="ga"value="<?php echo $ga;?>" name="ga"></td>
        </tr>
      <tbody>
      </table>
      <table style="width:90%;margin-top:20px;" class="table table-striped table-hover" id='colum-addition'>
        <thead style="background-color:#bdb5ac">
        <!-- bdb5ac -->
        <!-- e6eded -->
        <!-- a3c0cc -->
            <tr>
                <th style="text-align:center;" colspan="2"><b>OBSTETRIC HISTORY</b></th>
                <th style="text-align:center;" colspan="4"><b>GRAVIDA</b></th>
                <th style="text-align:center;" colspan="2"><b>PARA</b></th>
                <th style="text-align:center;" colspan="5"><b>LIVING CHILDREN</b></th>
            </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>YEAR</b></td>
            <td><b>COMPLICATION</b></td>
            <td><b>METHOD</b></td>
            <td><b>WT</b></td>
            <td colspan="2" style="text-align:center;" ><b>ALIVE</b></td>
            <td><b>YEAR</b></td>
            <td><b>COMPLICATIONS</b></td>
            <td><b>METHOD</b></td>
            <td><b>WT</b></td>
            <td colspan="2" style="text-align:center;" ><b>ALIVE</b></td>
            <td><b>ACTION</b></td>
          </tr>
          <tr>
            <td><input type="date" id="history_year" name="history_year[]" class="form-control"></td>
            <td><textarea rows="1" id="history_complication" name="history_complication[]" class="form-control"></textarea></td>
            <td><input type="text" id="gravida_method" name="gravida_method[]" class="form-control"></td>
            <td><input type="text" id="gravida_wt" name="gravida_wt[]" class="form-control"></td>
            <td>Yes<input type="radio" id="gravida_alive" name="gravida_alive[]" value="yes"></td>
            <td>No<input type="radio" id="gravida_alive1" name="gravida_alive[]" value="yes"></td>
            <td><input type="date" id="para_year" name="para_year[]" class="form-control"></td>
            <td><textarea rows="1" id="para_complication" name="para_complication[]" class="form-control"></textarea></td>
            <td><input type="text" id="living_method" name="living_method[]" class="form-control"></td>
            <td><input type="text" id="living_wt" name="living_wt[]" class="form-control"></td>
            <td>Yes<input type="radio" id="living_alive" name="living_alive[]" value="yes"></td>
            <td>No<input type="radio" id="living_alive" name="living_alive[]" value="yes"></td>
            <td><input type="button" class="art-button-green" value="ADD" id="add_new_row"/></td>
          </tr>
          <input type='hidden' id='rowCount' value='1'>
          
        </tbody>
        </table>
        </fieldset>
      
      <fieldset style="width:90%;margin-top:20px;"">
        <legend align="center" style="font-weight:bold;margin-top:20px;">EXAMINATION</legend>
        <table style="width:90%;" class="table table-striped table-hover">
          <!-- <thead style="background-color:#bdb5ac">
            <tr>
              <th colspan="4">EXAMINATION</th>
            </tr>
          </thead> -->
          <tr>
            <td>General Condition</td>
            <td><input type="text" name="general_condition" id="general_condition" value="<?php echo $general_condition;?>"class="form-control"></td>
            <td style="text-align:right;  ">Fundamental Height</td>
            <td><input type="text" name="fundamental_height" class="form-control" value="<?php echo $fundamental_height;?>" id="fundamental_height" > </td>
          </tr>
          <tr>
            <td>Temperature</td>
            <td><input type="text" name="temperature" id="temperature"value="<?php echo $temperature;?>" class="form-control"></td>
            <td style="text-align:right;  ">Size Of Fetus</td>
            <td><input type="text" name="size_fetus" class="form-control" value="<?php echo $size_fetus;?>" id="size_fetus" > </td>
          </tr>
          <tr>
            <td>Blood Pressure</td>
            <td><input type="text" name="blood_pressure" id="blood_pressure"value="<?php echo $blood_pressure;?>" class="form-control"></td>
            <td style="text-align:right;  ">Lie</td>
            <td><input type="text" name="lie" class="form-control"value="<?php echo $lie;?>"  id="lie" > </td>
          </tr>
          <tr>
            <td>Oedema</td>
            <td><input type="text" name="oedema" id="oedema"value="<?php echo $oedema;?>" class="form-control"></td>
            <td style="text-align:right;  ">presentation</td>
            <td><input type="text" name="presentation" class="form-control" value="<?php echo $presentation;?>" id="presentation" > </td>
          </tr>
          <tr>
            <td>Urine:</td>
            <td>Acetone<input type="text" name="acetone" id="acetone"value="<?php echo $acetone;?>" class="form-control">Proten<input type="text"value="<?php echo $lmp;?>" name="protein" id="protein" class="form-control"></td>
            <td style="text-align:right;  ">Liquor</td>
            <td>
              <select id="liquor" name="liquor" value="<?php echo $lmp;?>"class="form-control">
                <option value="<?php echo $liquor;?>"></option>
                <option value="Membranes">Membranes</option>
                <option value="Intact">Intact</option>
                <option value="Clear">Clear</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Height</td>
            <td><input type="text" name="height" id="height"value="<?php echo $height;?>" class="form-control"></td>
            <td style="text-align:right;  ">Meconium Stained</td>
            <td><input type="text" name="meconium" class="form-control" value="<?php echo $meconium;?>" id="meconium"> </td>
          </tr>
          <tr>
            <td>Height</td>
            <td><input type="text" name="height" id="height"value="<?php echo $height;?>" class="form-control"></td>
            <td style="text-align:right;  ">Meconium Stained</td>
            <td><input type="text" name="meconium" class="form-control" value="<?php echo $meconium;?>" id="meconium"> </td>
          </tr>
          <tr>
            <td>Ho Estimation Of Presentation</td>
            <td><input type="text" name="estimation_presentation"value="<?php echo $estimation_presentation;?>" id="estimation_presentation" class="form-control"></td>
            <td style="text-align:right;  ">If Membrane Ruptured</td>
            <td><input type='text' name='membrane' id='membrane' style="text-align: center;" value="<?php echo $membrane;?>" autocomplete='off' style="text-align: center;"readonly="readonly" class="form-control"></td>
          </tr>
          <tr>
            <td>Last Recorded A/N</td>
            <td><input type="text" name="last_recorded" id="last_recorded"value="<?php echo $last_recorded;?>" class="form-control"></td>
            <td style="text-align:right;">Blood Group</td>
            <td><input type="text" name="blood_group" class="form-control" value="<?php echo $blood_group;?>" id="blood_group"> </td>
        </table>
      </fieldset>
      <fieldset style="width:90%;margin-top:20px;">
        <legend align="center" style="font-weight:bold;margin-top:20px;">INITIAL VAGINAL EXAMINATION AND PELVIC ASSESSMENT</legend>

        <!-- INITIAL EXAMINATION -->
        

        <table style="width:90%;" class="table table-striped table-hover">
          <!-- <thead style="background-color:#bdb5ac">
            <tr>
              <th colspan="4">EXAMINATION</th>
            </tr>
          </thead> -->
          <tr>
          <td>DATE AND TIME </td>
            <td> <input type='text' name='date_time' id='date_time' style="text-align: center;" value="<?php echo $date_time;?>"autocomplete='off' style="text-align: center;"readonly="readonly" class="form-control"></td>
            <td style="text-align:right;">Bony Pelvis</td>
            <td><input type="text" name="bony"id="bony"value="<?php echo $bony;?>" class="form-control"> </td>
          </tr>
          <tr>
          <td>Cervic:state</td>
            <td><select id="cervic_state" name="cervic_state"value="<?php echo $cervic_state;?>" class="form-control">
              <option value=""><?php echo $lmp;?></option>
              <option value="Effected">Effected</option>
              <option value="Partially Effected">Partially Effected</option>
              <option value="Not Effected">Not Effected</option>

            </select> </td>
            <td style="text-align:right;">Sacral promontory</td>
            <td><select class="form-control" name="sacral_promontory" id="sacral_promontory">
              <option value="<?php echo $lmp;?>"><?php echo $sacral_promontory;?></option>
              <option value="Not">Not</option>
              <option value="Just">Just </option>
              <option value="Easy Reached">Easy Reached</option>
            </select> </td>
          </tr>
          <tr>
          <td>Dilation</td>
            <td><input type="text" name="dilation" id="dilation" value="<?php echo $dilation;?>" class="form-control">  </td>
            <td style="text-align:right;">sSacral curve</td>
            <td><select class="form-control" id="sacral_curve" name="sacral_curve">
              <option value="<?php echo $sacral_curve;?>"><?php echo $sacral_curve;?></option>
              <option value="Flat">Flat</option>
              <option value="Normal">Normal</option>
              </select> </td>
          </tr>
          <tr>
          <td>Presenting Part</td>
            <td><input type="text"value="<?php echo $presenting_part;?>" name="presenting_part" id="presenting_part" class="form-control"> </td>
            <td style="text-align:right;">Lachial Spines</td>
            <td><select class="form-control" id="Lachial_spines" name="Lachial_spines">
              <option value="<?php echo $Lachial_spines;?>"><?php echo $Lachial_spines;?></option>
              <option value="Prominent">Prominent</option>
              <option value="Normal">Normal</option>
              </select> </td>
          </tr>
          <tr>
          <td>Level</td>
            <td><input type="text" name="levels" value="<?php echo $levels;?>"id="levels" class="form-control"> </td>
            <td style="text-align:right;">Subpubic Angle</td>
            <td><select class="form-control" id="subpubic_angle"value="<?php echo $subpubic_angle;?>" name="subpubic_angle">
              <option value="<?php echo $lmp;?>"><?php echo $lmp;?></option>
              <option value="Narrow">Narrow</option>
              <option value="Normal">Normal</option>
            </select> </td>
          </tr>
          <tr>
            <td>Position</td>
            <td><input type="text" name="position"value="<?php echo $position;?>" id="position" class="form-control"> </td>
            <td style="text-align:right;">Sacral Tuberosites</td>
            <td><input type="text" class="form-control" id="sacral_tuberosites"value="<?php echo $sacral_tuberosites;?>" name="sacral_tuberosites"> </td>
          </tr>
          <tr>
          <td>Moulding</td>
            <td><input type="text" name="moulding"value="<?php echo $moulding;?>" id="moulding" class="form-control"> </td>
            <td  style="text-align:right;">Summary</td>
            <td><textarea name="summary" id="summary"class="form-control"><?php echo $summary;?></textarea> </td>
          </tr>
          <tr>
          <td>Caput</td>
            <td><input type="text" name="caput" id="caput"value="<?php echo $caput;?>" class="form-control"> </td>
            <td style="text-align:right;">Membranes/Liquor</td>
            <td><input type="text" name="membranes_liquor"value="<?php echo $membranes_liquor;?>" class="form-control" id="membranes_liquor" > </td>
          </tr>
          <tr>
          

            <td colspan="3">Consultants's/Registra's Opinion</td>
            <!-- <td><input type="text" name="dilation" value=""> </td>
           </tr> -->
             <td><textarea name="remarks" id="remarks" rows="2" cols="80" class="form-control"><?php echo $remarks;?></textarea> </td>
            </tr>
        </table>
        <!-- END OF INITIAL EXAMINATION -->
        
       
        <br/>
      </fieldset>
      <span>
        <!-- <input =type="submit" class="art-button-green" name="save" id="save" value="SAVE"> -->
        <input type="button" class="art-button-green" onclick="save_labour()" value="SAVE">
      </span>
      <!-- <span>
        <input type="submit" class="art-button-green" name="save" id="preview" value="PREVIEW">
      </span> -->
    </form>
          <?php
            }
          ?>
<?php
include("./includes/footer.php");
?>
<script src="css/jquery.datetimepicker.js"></script>
<script type="text/javascript">

// save labour record
$("#save").click(function(e){
e.preventDefault();
var labour_data = $("#labour_form").serialize();

  $.ajax({
    url:"save_labour_record.php",
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


$("#preview").click(function(e){
  e.preventDefault()
  var Registration_ID = $("#Registration_ID").val();
  var admission_id = $("#admission_id").val();

window.open("print_labour_record_test.php?Registration_ID="+Registration_ID+"&admision_id="+admission_id,'_blank')
})
</script>
<script>
 function save_labour(){
    var admission_reason=$("#admission_reason").val();
    var Registration_ID=$("#Registration_ID").val();
    var from=$("#from").val();
    var summary_Antenatal=$("#summary_Antenatal").val();
    var abnormalities=$("#abnormalities").val();
    var lmp=$("#lmp").val();
    var edd=$("#edd").val();
    var ga=$("#ga").val();
    var general_condition=$("#general_condition").val();
    var fundamental_height=$("#fundamental_height").val();
    var temperature=$("#temperature").val();
    var blood_pressure=$("#blood_pressure").val();
    var size_fetus=$("#size_fetus").val();
    var lie=$("#lie").val();
    var oedema=$("#oedema").val();
    var presentation=$("#presentation").val();
    var acetone=$("#acetone").val();
    var protein=$("#protein").val();
    var liquor=$("#liquor").val();
    var height=$("#height").val();
    var meconium=$("#meconium").val();
    var estimation_presentation=$("#estimation_presentation").val();
    var membrane=$("#membrane").val();
    var last_recorded=$("#last_recorded").val();
    var blood_group=$("#blood_group").val();
    var date_time=$("#date_time").val();
    var cervic_state=$("#cervic_state").val();
    var presenting_part=$("#presenting_part").val();
    var levels=$("#levels").val();
    var position=$("#position").val();
    var moulding=$("#moulding").val();
    var caput=$("#caput").val();
    var bony=$("#bony").val();
    var sacral_promontory=$("#sacral_promontory").val();
    var sacral_curve=$("#sacral_curve").val();
    var Lachial_spines=$("#Lachial_spines").val();
    var subpubic_angle=$("#subpubic_angle").val();
    var sacral_tuberosites=$("#sacral_tuberosites").val();
    var summary=$("#summary").val();
    var remarks=$("#remarks").val();
    var admission_id=$("#admission_id").val();
    var membranes_liquor=$("#membranes_liquor").val();
    // alert(Registration_ID);
    // alert(admission_id);
    // alert(from);
    var from=$("#from").val();
    var summary_Antenatal=$("#summary_Antenatal").val();
    var abnormalities=$("#abnormalities").val();
    var lmp=$("#lmp").val();
    var edd=$("#edd").val();
    var ga=$("#ga").val();
    var dilation=$("#dilation").val();


    var history_year=[];
    var history_year1 = document.getElementsByName('history_year[]');
    for (var i = 0; i <history_year1.length; i++) {
      var inp=history_year1[i];
      history_year.push(inp.value);
    }
    var history_complication=[];
    var history_complication1 = document.getElementsByName('history_complication[]');
    for (var i = 0; i <history_complication1.length; i++) {
      var inp=history_complication1[i];
      history_complication.push(inp.value);
    }
    var gravida_method=[];
    var gravida_method1 = document.getElementsByName('gravida_method[]');
    for (var i = 0; i <gravida_method1.length; i++) {
      var inp=gravida_method1[i];
      gravida_method.push(inp.value);
    }
    var gravida_wt=[];
    var gravida_wt1 = document.getElementsByName('gravida_wt[]');
    for (var i = 0; i <gravida_wt1.length; i++) {
      var inp=gravida_wt1[i];
      gravida_wt.push(inp.value);
    }
    var gravida_alive=[];
    var gravida_alive1 = document.getElementsByName('gravida_alive[]');
    for (var i = 0; i <gravida_alive1.length; i++) {
      var inp=gravida_alive1[i];
      gravida_alive.push(inp.value);
    }
    var para=$("#para").val();
    var lv_children=$("#lv_children").val();
    var gravida=$("#gravida").val();
    // var para_year=[];
    // var para_year1 = document.getElementsByName('para_year[]');
    // for (var i = 0; i <para_year1.length; i++) {
    //   var inp=para_year1[i];
    //   para_year.push(inp.value);
    // }
    // var para_complication=[];
    // var para_complication1 = document.getElementsByName('para_complication[]');
    // for (var i = 0; i <para_year1.length; i++) {
    //   var inp=para_complication1[i];
    //   para_complication.push(inp.value);
    // }
    // var living_method=[];
    // var living_method1 = document.getElementsByName('living_method[]');
    // for (var i = 0; i <living_method1.length; i++) {
    //   var inp=living_method1[i];
    //   living_method.push(inp.value);
    // }
    // var living_wt=[];
    // var living_wt1 = document.getElementsByName('living_wt[]');
    // for (var i = 0; i <living_wt1.length; i++) {
    //   var inp=living_wt1[i];
    //   living_wt.push(inp.value);
    // }
    // var living_alive=[];
    // var living_alive1 = document.getElementsByName('living_alive[]');
    // for (var i = 0; i <living_alive1.length; i++) {
    //   if (living_alive1[i].checked === true) {
    //     var inp = living_alive1[i];
    //     }
    //   living_alive.push(inp.value);
    // }
    if(general_condition !='' || fundamental_height !='' || temperature !='' || blood_pressure !='' || size_fetus !='' || lie !='' || oedema !='' || presentation !='' || acetone !='' || protein !='' || liquor !='' || height !='' || meconium !='' || from !='' || summary_Antenatal !='' || abnormalities !='' || lmp !='' || edd !='' || ga !=''){
    if(confirm("Are you Sure you want to Save Labour Records")){
      $.ajax({
                type:'post',
                url: 'save_labour_record.php',
                data : {
                     Registration_ID:Registration_ID,
                     admission_reason:admission_reason,
                     from:from,
                     admission_id:admission_id,
                     summary_Antenatal:summary_Antenatal,
                     abnormalities:abnormalities,
                     lmp:lmp,
                     edd:edd,
                     ga:ga,
                     general_condition:general_condition,
                     fundamental_height:fundamental_height,
                     blood_pressure:blood_pressure,
                     size_fetus:size_fetus,
                     lie:lie,
                     oedema:oedema,
                     presentation:presentation,
                     acetone:acetone,
                     protein:protein,
                     liquor:liquor,
                     height:height,
                     meconium:meconium,
                     estimation_presentation:estimation_presentation,
                     membrane:membrane,
                     last_recorded:last_recorded,
                     blood_group:blood_group,
                     date_time:date_time,
                     cervic_state:cervic_state,
                     presenting_part:presenting_part,
                     levels:levels,
                     position:position,
                     moulding:moulding,
                     caput:caput,
                     bony:bony,
                     membranes_liquor:membranes_liquor,
                     sacral_promontory:sacral_promontory,
                     sacral_curve:sacral_curve,
                     Lachial_spines:Lachial_spines,
                     subpubic_angle:subpubic_angle,
                     sacral_tuberosites:sacral_tuberosites,
                     summary:summary,
                     remarks:remarks,
                     history_year:history_year,
                     history_complication:history_complication,
                     gravida_method:gravida_method,
                     gravida_wt:gravida_wt,
                    //  para_year:para_year,
                    //  para_complication:para_complication,
                    //  living_method:living_method,
                    //  living_wt:living_wt,
                     temperature:temperature,
                     dilation:dilation,
                     lv_children:lv_children,
                     gravida:gravida,
                     para:para

                    //  living_alive:living_alive
               },
               success : function(response){
                //  alert(response);
                 $("#from").val("");
                 $("#summary_Antenatal").val("");
                 $("#abnormalities").val("");
                 $("#lmp").val("");
                 $("#edd").val("");
                 $("#ga").val("");
                 $("#general_condition").val("");
                 $("#fundamental_height").val("");
                 $("#blood_pressure").val("");
                 $("#size_fetus").val("");
                 $("#lie").val("");
                 $("#oedema").val("");
                 $("#presentation").val("");
                 $("#acetone").val("");
                 $("#protein").val("");
                 $("#liquor").val("");
                 $("#proheighttein").val("");
                 $("#meconium").val("");
                 $("#height").val("");
                 $("#estimation_presentation").val("");
                 $("#membrane").val("");
                 $("#last_recorded").val("");
                 $("#blood_group").val("");
                 $("#date_time").val("");
                 $("#cervic_state").val("");
                 $("#presenting_part").val("");
                 $("#levels").val("");
                 $("#position").val("");
                 $("#moulding").val("");
                 $("#bony").val("");
                 $("#membranes_liquor").val("");
                 $("#sacral_promontory").val("");
                 $("#sacral_curve").val("");
                 $("#Lachial_spines").val("");
                 $("#subpubic_angle").val("");
                 $("#sacral_tuberosites").val("");
                 $("#summary").val("");
                 $("#remarks").val("");
                 location.reload(true)
               }
           });
    }
    }else{
            alert("Please fill atleast one of the Labour Record");
        }
  }

  // for updating labour records

  function update_labour(){
    var admission_reason=$("#admission_reason").val();
    var Registration_ID=$("#Registration_ID").val();
    var from=$("#from").val();
    var summary_Antenatal=$("#summary_Antenatal").val();
    var abnormalities=$("#abnormalities").val();
    var lmp=$("#lmp").val();
    var edd=$("#edd").val();
    var ga=$("#ga").val();
    var general_condition=$("#general_condition").val();
    var fundamental_height=$("#fundamental_height").val();
    var temperature=$("#temperature").val();
    var blood_pressure=$("#blood_pressure").val();
    var size_fetus=$("#size_fetus").val();
    var lie=$("#lie").val();
    var oedema=$("#oedema").val();
    var presentation=$("#presentation").val();
    var acetone=$("#acetone").val();
    var protein=$("#protein").val();
    var liquor=$("#liquor").val();
    var height=$("#height").val();
    var meconium=$("#meconium").val();
    var estimation_presentation=$("#estimation_presentation").val();
    var membrane=$("#membrane").val();
    var last_recorded=$("#last_recorded").val();
    var blood_group=$("#blood_group").val();
    var date_time=$("#date_time").val();
    var cervic_state=$("#cervic_state").val();
    var presenting_part=$("#presenting_part").val();
    var levels=$("#levels").val();
    var position=$("#position").val();
    var moulding=$("#moulding").val();
    var caput=$("#caput").val();
    var bony=$("#bony").val();
    var sacral_promontory=$("#sacral_promontory").val();
    var sacral_curve=$("#sacral_curve").val();
    var Lachial_spines=$("#Lachial_spines").val();
    var subpubic_angle=$("#subpubic_angle").val();
    var sacral_tuberosites=$("#sacral_tuberosites").val();
    var summary=$("#summary").val();
    var remarks=$("#remarks").val();
    var admission_id=$("#admission_id").val();
    var membranes_liquor=$("#membranes_liquor").val();
    var from=$("#from").val();
    var summary_Antenatal=$("#summary_Antenatal").val();
    var abnormalities=$("#abnormalities").val();
    var lmp=$("#lmp").val();
    var edd=$("#edd").val();
    var ga=$("#ga").val();
    var dilation=$("#dilation").val();
    var para=$("#para").val();
    var lv_children=$("#lv_children").val();
    var gravida=$("#gravida").val();

   
    // if(general_condition !='' || fundamental_height !='' || temperature !='' || blood_pressure !='' || size_fetus !='' || lie !='' || oedema !='' || presentation !='' || acetone !='' || protein !='' || liquor !='' || height !='' || meconium !='' || from !='' || summary_Antenatal !='' || abnormalities !='' || lmp !='' || edd !='' || ga !=''){
    if(confirm("Are you Sure you want to Update Labour Records")){
      $.ajax({
                type:'post',
                url: 'update_labour_record.php',
                data : {
                     Registration_ID:Registration_ID,
                     admission_reason:admission_reason,
                     from:from,
                     admission_id:admission_id,
                     summary_Antenatal:summary_Antenatal,
                     abnormalities:abnormalities,
                     lmp:lmp,
                     edd:edd,
                     ga:ga,
                     general_condition:general_condition,
                     fundamental_height:fundamental_height,
                     blood_pressure:blood_pressure,
                     size_fetus:size_fetus,
                     lie:lie,
                     oedema:oedema,
                     presentation:presentation,
                     acetone:acetone,
                     protein:protein,
                     liquor:liquor,
                     height:height,
                     meconium:meconium,
                     estimation_presentation:estimation_presentation,
                     membrane:membrane,
                     last_recorded:last_recorded,
                     blood_group:blood_group,
                     date_time:date_time,
                     cervic_state:cervic_state,
                     presenting_part:presenting_part,
                     levels:levels,
                     position:position,
                     moulding:moulding,
                     caput:caput,
                     bony:bony,
                     membranes_liquor:membranes_liquor,
                     sacral_promontory:sacral_promontory,
                     sacral_curve:sacral_curve,
                     Lachial_spines:Lachial_spines,
                     subpubic_angle:subpubic_angle,
                     sacral_tuberosites:sacral_tuberosites,
                     summary:summary,
                     remarks:remarks,
                     temperature:temperature,
                     dilation:dilation,
                     lv_children:lv_children,
                     gravida:gravida,
                     para:para
               },
               success : function(response){
                //  alert(response);
                 location.reload(true)
               }
           });
    }
  }
    $('#date_time').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
    });
    $('#date_time').datetimepicker({value:'',step:05});
    $('#membrane').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:'now'
    });
    $('#membrane').datetimepicker({value:'',step:05});
</script>
<script>
  $('#add_new_row').click(function () {
    var rowCount = parseInt($('#rowCount').val()) + 1;
    var newRow = "<tr class='addnewrow tr" + rowCount + "'><td><input name='history_year[]' class='txtbox form-control' type='date' class='adjuvant form-control' id='" + rowCount + " ' style='width:100%'></td><td><textarea name='history_complication[]' class='duration txtbox form-control ' type='text' rows='1' id='" + rowCount + " ' style='width:100%'></textarea></td><td><input name='gravida_method[]' class='txtbox form-control' type='text' class='duration' id='" + rowCount + " ' style='width:100%' class='form-control'></td><td><input name='gravida_wt[]' class='txtbox form-control' type='text' class='duration' id='" + rowCount + " ' style='width:100%' class='form-control'></td><td><input name='gravida_alive"+ rowCount + "[]' class='txtbox' type='radio' class='duration' id='" + rowCount + " ' style='width:100%' class='form-control' value='yes'></td><td><input name='gravida_alive"+ rowCount + "[]' class='txtbox' type='radio' class='duration' value='No' id='" + rowCount + " ' style='width:100%' class='form-control'></td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x' style='background-color:#white;width:95%;height:30px;'></td></tr>";
    $('#colum-addition').append(newRow);
    document.getElementById('rowCount').value = rowCount;
  });

  $(document).on('click', '.remove', function () {
    var id = $(this).attr('row_id');
    //alert(id);
    $('.tr' + id).remove().fadeOut();
  });
</script>
