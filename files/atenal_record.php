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


// select patient info if exist one
$select_atenal_record = "SELECT * FROM tbl_atenal_record WHERE patient_id='$patient_id' AND admission_id='$admision_id'";

if ($result_atenal = mysqli_query($conn,$select_atenal_record)) {
  $num = mysqli_num_rows($result_atenal);
  if ($num > 0) {
    while ($row_atenal = mysqli_fetch_assoc($result_atenal)) {
      $date_of_first_visit = $row_atenal['atenal_date'];
      $patient_id = $row_atenal['patient_id'];
      $admission_id = $row_atenal['admission_id'];
      $height = $row_atenal['height'];
      $weight = $row_atenal['weight'];
      $bp = $row_atenal['bp'];
      $fulse = $row_atenal['fulse'];
      $temp = $row_atenal['temp'];
      $investigations = $row_atenal['investigations'];
      $hb = $row_atenal['hb'];
      $bloodgroup = $row_atenal['bloodgroup'];
      $vdrl = $row_atenal['vdrl'];
      $blisa = $row_atenal['blisa'];
      $colour = $row_atenal['colour'];
      $blood = $row_atenal['blood'];
      $specific_gravity = $row_atenal['specific_gravity'];
      $keytones = $row_atenal['keytones'];
      $ph = $row_atenal['ph'];
      $lie = $row_atenal['lie'];
      $urobilinogen = $row_atenal['urobilinogen'];
      $alumin = $row_atenal['alumin'];
      $leucocetes = $row_atenal['leucocetes'];
      $sugar = $row_atenal['sugar'];
      $head = $row_atenal['head'];
      $neck = $row_atenal['neck'];
      $eyes = $row_atenal['eye'];
      $ears = $row_atenal['ears'];
      $teeth = $row_atenal['teeth'];
      $breast = $row_atenal['breast'];
      $axilla = $row_atenal['axilla'];
      $size = $row_atenal['size'];
      $varicobe_veins= $row_atenal['varicobe_veins'];
      $shape = $row_atenal['shape'];
      $scar = $row_atenal['scar'];
      $skin = $row_atenal['skin'];
      $oval_pendulus = $row_atenal['oval_pendulus'];
      $fundal_height = $row_atenal['fundal_height'];
      $presenting_part = $row_atenal['presenting_part'];
      $position = $row_atenal['position'];
      $deep_pelvic_palpation = $row_atenal['deep_pelvic_palpation'];
      $engagement_in_relationship_to_brim = $row_atenal['engagement_in_relationship_to_brim'];
      $fetal_heart_rate = $row_atenal['fetal_heart_rate'];
      $sonicard = $row_atenal['sonicard'];
      $fetoscope = $row_atenal['fetoscope'];
      $external = $row_atenal['external'];
      $herpes = $row_atenal['herpes'];
      $warts = $row_atenal['warts'];
      $haemorrhoids = $row_atenal['haemorrhoids'];
      $any_other = $row_atenal['any_other'];
      $varcobe_veins = $row_atenal['warcobe_veins'];
      $odema = $row_atenal['odema'];
      $other_abdomalities = $row_atenal['other_abdomalities'];
    }


  }else {
    echo mysqli_error($conn);
  }

}else {
  echo mysqli_error($conn);
}

?>
<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id;?>&patient_id=<?=$patient_id;?>&admission_id=<?=$admision_id?>" class="art-button-green">BACK</a>

<br/ >

<style media="screen">
  .input{
    width: 100%;
  }
  .td-input{
    width: 10%;

  }
  .table-top{
      vertical-align: top;
    display: inline-block;
  }
  .td-label{
    width: 10%;
  }
  .title{
    margin-left: 25px;
    text-align: left;
    }

  .title p{
    font-weight: bold;
    margin:0px;
    padding: 0px;
  }
  th{
    text-align: left ;
  }
  table td input{
    padding-left: 3px !important;
  }

  #tbl th{
    ;
  }
  #tbl td{
    text-align: center;
  }
  #tbl tr{
    height: 20px !important;
    border: 1px solid grey;
  }

  #fill td{
    width:10%
  }
  #fill .f{
    width: 5%;
  }
  table tr,td{
    border: none !important;
  }
</style>
<center>
<form id="antenal_form">

  <input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id?>">
    <input type="hidden" name="admission_id" id="admission_id" value="<?=$admision_id?>">
    <input type="hidden" name="consultation_id" value="<?=$consultation_id?>">
<fieldset style="width:95vw;">
  <legend align=center style="width:30vw">
    <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
    <p style="margin:0px;padding:0px;">ANTENATAL RECORD</p>
    <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
  </div>
 </legend>
  <div class="title">
    <p>FIRST ANTENAL EXAMINATION</p>
    <p>1ST VISIT</p>
    <p><span>DATE:</span>
      <span >
        <input style="width:20% !important;" type="text" name="a_date" class="date"
        value="<?php if(!empty($date_of_first_visit)) echo $date_of_first_visit; ?>">
      </span>
     </p>
  </div>
  <table class="table-top" style="width:24%;">
    <tr>
      <td>HEIGHT:</td>
      <td class="td-input"><input type="text" name="height" placeholder="" class="input" value="<?php if(!empty($height)) echo $height; ?>"> </td>
    </tr>
    <tr>
      <td>WEIGHT:</td>
      <td class="td-input"><input type="text" name="weight" placeholder="" class="input" value="<?php if(!empty($weight)) echo $weight; ?>"> </td>
    </tr>
    <tr>
      <td class="td-input">BP:</td>
      <td><input type="text" name="bp" placeholder="" class="input"
        value="<?php if(!empty($bp)) echo $bp; ?>"> </td>
    </tr>
    <tr>
      <td class="td-input">FULSE:</td>
      <td><input type="text" name="fulse" placeholder="" class="input" value="<?php if(!empty($fulse)) echo $fulse; ?>"> </td>
    </tr>
    <tr>
      <td class="td-input">TEMP:</td>
      <td><input type="text" name="temp" placeholder="" class="input"
         value="<?php if(!empty($temp)) echo $temp; ?>"> </td>
    </tr>

  </table>
  <table class="table-top" style="width:24%;">
    <tr>
      <td>INVESTIGATIONS:</td>
      <td class="td-input"><input type="text" name="investigations" placeholder="" class="input" value="<?php if(!empty($investigations)) echo $investigations; ?>"> </td>
    </tr>
    <tr>
      <td>HB:</td>
      <td class="td-input"><input type="text" name="hb" placeholder="" class="input" value="<?php if(!empty($hb)) echo $hb; ?>"> </td>
    </tr>
    <tr>
      <td class="td-input">BLOODGROUP:</td>
      <td><input type="text" name="bloodgroup" placeholder="" class="input" value="<?php if(!empty($bloodgroup)) echo $bloodgroup; ?>"> </td>
    </tr>
    <tr>
      <td class="td-input">VDRL:</td>
      <td><input type="text" name="vdrl" placeholder="" class="input"
        value="<?php if(!empty($vdrl)) echo $vdrl; ?>"> </td>
    </tr>
    <tr>
      <td class="td-input">BLISA:</td>
      <td><input type="text" name="blisa" placeholder="" class="input" value="<?php if(!empty($blisa)) echo $blisa; ?>"> </td>
    </tr>

  </table>

  <table class="table-top" style="width:48%;">
    <tr>
      <td colspan="4" style="font-weight:bold;height:30px;">URINALYSIS:</td>
    </tr>
    <tr>
      <td class="td-label">COLOUR:</td>
      <td class="td-input"><input type="text" name="colour" placeholder="" class="input" value="<?php if(!empty($colour)) echo $colour; ?>"> </td>
      <td class="td-label">BLOOD:</td>
      <td class="td-input"><input type="text" name="blood" placeholder="" class="input" value="<?php if(!empty($blood)) echo $blood; ?>"> </td>
    </tr>
    <tr>
      <td class="td-label">SPECIFIC GRAVITY:</td>
      <td class="td-input"><input type="text" name="specific_gravity" placeholder="" class="input" value="<?php if(!empty($specific_gravity)) echo $specific_gravity; ?>"> </td>
      <td class="td-label">KETONES:</td>
      <td class="td-input"><input type="text" name="keytones" placeholder="" class="input" value="<?php if(!empty($keytones)) echo $keytones; ?>"> </td>
    </tr>
    <tr>
      <td class="td-label" >PH:</td>
      <td class="td-input"><input type="text" name="ph" placeholder="" class="input" value="<?php if(!empty($ph)) echo $ph; ?>"> </td>
      <td class="td-label">UROBILINOGEN:</td>
      <td class="td-input"><input type="text" name="urobilinogen" placeholder="" class="input" value="<?php if(!empty($urobilinogen)) echo $urobilinogen; ?>"> </td>
    </tr>
    <tr>
      <td class="td-label">ALUMIN:</td>
      <td  class="td-input"><input type="text" name="alumin" placeholder="" class="input" value="<?php if(!empty($alumin)) echo $alumin; ?>"> </td>
      <td class="td-label">LEUCOCETES:</td>
      <td class="td-input"><input type="text" name="leucocetes" placeholder="" class="input" value="<?php if(!empty($leucocetes)) echo $leucocetes; ?>"> </td>
    </tr>
    <tr>
      <td class="td-label" >SUGAR:</td>
      <td class="td-input"><input type="text" name="sugar" placeholder="" class="input" value="<?php if(!empty($sugar)) echo $sugar; ?>"> </td>

    </tr>

  </table>

</fieldset>
</center>

<center>
  <fieldset>
    <legend style="font-weight:bold">INSPECTION</legend>
    <table style="width:100%;">
      <tr>
        <th colspan="14">1. GENERAL</th>
      </tr>
      <tr>
        <td style="%;">HEAD</td>
        <td style=";"><input type="text" name="head" value="<?php if(!empty($head)) echo $head; ?>"> </td>
        <td style="%;">NECK</td>
        <td style=";"><input type="text" name="neck" value="<?php if(!empty($neck)) echo $neck; ?>"> </td>
        <td style="%;">EYES</td>
        <td style=";"><input type="text" name="eyes" value="<?php if(!empty($eyes)) echo $eyes; ?>"> </td>
        <td style="%;">EARS</td>
        <td style=";"><input type="text" name="ears" value="<?php if(!empty($ears)) echo $ears; ?>"> </td>
        <td style="%;">TEETH</td>
        <td style=";"><input type="text" name="teeth" value="<?php if(!empty($teeth)) echo $teeth; ?>"> </td>
        <td style="%;">BREAST</td>
        <td style=";"><input type="text" name="breast" value="<?php if(!empty($breast)) echo $breast; ?>"> </td>
        <td style="%;">AXILLA</td>
        <td style=";"><input type="text" name="axilla" value="<?php if(!empty($axilla)) echo $axilla; ?>"> </td>
      </tr>

      <tr>
        <th colspan="14">2. ABNORMAL</th>
      </tr>
      <tr>
        <td>SIZE</td><td><input type="text" name="size" value="<?php if(!empty($size)) echo $size; ?>"> </td>
        <td>SHAPE</td><td><input type="text" name="shape" value="<?php if(!empty($shape)) echo $shape; ?>"> </td>
        <td>SCAR</td><td><input type="text" name="scar" value="<?php if(!empty($scar)) echo $scar; ?>"> </td>
        <td>SKIN</td><td><input type="text" name="skin" value="<?php if(!empty($skin)) echo $skin; ?>"> </td>
        <td>OVAL/PENDULUS</td><td><input type="text" name="oval_pendulus" value="<?php if(!empty($oval_pendulus)) echo $oval_pendulus; ?>"> </td>

      </tr>

      <tr>
        <th colspan="14">3. ABNORMAL PALPATION</th>
      </tr>
      <tr>
        <td>FUNDAL HEIGHT</td><td><input type="text" name="fundal_height" value="<?php if(!empty($fundal_height)) echo $fundal_height; ?>"> </td>
        <td>LIE</td><td><input type="text" name="lie" value="<?php if(!empty($lie)) echo $lie; ?>"> </td>
        <td>PRESENTING PARTS</td><td><input type="text" name="presenting_part" value="<?php if(!empty($presenting_part)) echo $presenting_part; ?>"> </td>
        <td>POSITION</td><td><input type="text" name="position" value="<?php if(!empty($position)) echo $position; ?>"> </td>
        <td>DEEP PELVIC PALPATION</td><td><input type="text" name="deep_pelvic_palpation" value="<?php if(!empty($deep_pelvic_palpation)) echo $deep_pelvic_palpation; ?>">
         </td>
         <td colspan="2">ENGAGEMENT IN RELATIONSHIP TO BRIM</td><td><input type="text" name="engagement_in_relationship_to_brim" value="<?php if(!empty($engagement_in_relationship_to_brim)) echo $engagement_in_relationship_to_brim; ?>"></td>

      </tr>

      <tr>
        <th colspan="14">4. AUSCALTATION</th>
      </tr>
      <tr>
        <td>FETAL HEART RATE</td><td><input type="text" name="fetal_heart_rate" value="<?php if(!empty($fetal_heart_rate)) echo $fetal_heart_rate; ?>"> </td>
        <td>SONICARD</td><td><input type="text" name="sonicard" value="<?php if(!empty($sonicard)) echo $sonicard; ?>"> </td>
        <td>FETOSCOPE</td><td><input type="text" name="fetoscope" value="<?php if(!empty($fetoscope)) echo $fetoscope; ?>"> </td>

      </tr>

      <tr>
        <th colspan="14">5. GENITALIA</th>
      </tr>
      <tr>
        <td>EXTERNAL</td><td><input type="text" name="external" value="<?php if(!empty($external)) echo $external; ?>"> </td>
        <td>HERPES</td><td><input type="text" name="herpes" value="<?php if(!empty($herpes)) echo $herpes; ?>"> </td>
        <td>WARTS</td><td><input type="text" name="warts" value="<?php if(!empty($warts)) echo $warts; ?>"> </td>
        <td>HAEMORRHOIDS</td><td><input type="text" name="haemorrhoids" value="<?php if(!empty($haemorrhoids)) echo $haemorrhoids; ?>"> </td>
        <td>ANY OTHER</td><td><input type="text" name="any_other" value="<?php if(!empty($any_other)) echo $any_other; ?>">
         </td>
         <td colspan="2">WARCOBE VEINS</td><td><input type="text" name="warcobe_veins" value="<?php if(!empty($warcobe_veins)) echo $warcobe_veins; ?>"></td>

      </tr>
      <tr>
        <th colspan="14">6. LOWER LIMBS</th>
      </tr>
      <tr>
        <td>oedema</td><td><input type="text" name="oedema" value="<?php if(!empty($odema)) echo $odema; ?>"> </td>
        <td>VARICOBE VEIN</td><td><input type="text" name="varicobe_veins" value=""> </td>
        <td>OTHER ABDOMALITIES</td><td><input type="text" name="other_abdomalities" value="<?php if(!empty($other_abdomalities)) echo $other_abdomalities; ?>"> </td>

      </tr>

    </table>
  </fieldset>


  <fieldset>
    <legend style="font-weight:bold">SUBSQUENT ANTENATAL EXAMINATIONS</legend>

    <table id="fill">
      <tr>
        <td class="f">DATE</td><td><input class="date" type="text" name="date" value=""> </td>
        <td class="f">SUGAR</td><td><input type="text" name="sugar" value=""> </td>
        <td class="f">WEIGHT</td><td><input type="text" name="weight" value=""> </td>
        <td class="f">UP</td><td><input type="text" name="up" value=""> </td>
        <td class="f">REMARKS</td><td><input type="text" name="remarks" value=""> </td>
        <!-- <td class="f">SEEN BY</td><td><input type="text" name="seen_by" value=""> </td> -->
      </tr>
      <tr>
        <td>PREGENANCY WEEK BY DATE</td><td><input type="text" name="pregnancy_weeks_by_date" value=""></td>
          <td>PREGENANCY WEEK BY SIZE</td><td><input type="text" name="pregenancy_weeks_by_size" value=""></td>
            <td>PRESENTTION IN RELATION TO THE BRIM</td><td><input type="text" name="presentation_in_relation_to_the_brim" value=""></td>

            <td>POETAL HEART RATE</td><td><input type="text" name="poetal_heart_rate" value=""></td>
            <td><button style="width:40%; height:30px !important; color:#fff !important" type="button" class="art-button-green" id="add" name="button">Add</button> </td>
      </tr>
    </table>
    <br />
    <style media="screen">

        #tbl{border-collapse:collapse}
        #tbl tbody tr{border:1px solid grey}

    </style>
    <table id="tbl" style="background:#fff" width="100%">
      <thead><tr>
        <th rowspan="2" style="width:5px;">Sn</th>
        <th rowspan="2">DATE</th>
        <th rowspan="2">PREGENANCY WEEKS BY DATES</th>
        <th rowspan="2">PREGENANCY WEEKS BY SIZE</th>

        <th rowspan="2">PRESENTTION IN RELATION TO THE BRIM</th>
        <th rowspan="2">POETAL HEART RATE</th>
        <th colspan="2">TIME</th>
        <th rowspan="2">UP</th>
        <th rowspan="2">WEIGHT</th>
        <th rowspan="2">REMARKS</th>
        <th rowspan="2">SEEN BY</th>

      </tr>
      <tr>

        <th>SUGAR</th>
        <th>ACETURE</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $select_subsquent_info = "SELECT * FROM subsquent_antenatal_examination WHERE patient_id = '$patient_id' AND admission_id='$admision_id'";
      $result = mysqli_query($conn,$select_subsquent_info);
      $num = mysqli_num_rows($result);
      if ($num > 0) {
        $sn = 1;
        while ($row_sub = mysqli_fetch_assoc($result)) {
          // echo $row_sub['sub_date'];
          echo "<tr><td>".$sn++."</td><td>".$row_sub['sub_date']."</td><td>".
          $row_sub['pregnancy_weeks_by_date']."</td><td>".
          $row_sub['pregnancy_weeks_by_size']."</td><td>".
          $row_sub['presentation_in_relation_to_the_brim']."</td><td>".
          $row_sub['poetal_heart_rate']."</td><td></td><td>"
          .$row_sub['sugar']."</td><td>".$row_sub['up']."</td><td>".
          $row_sub['weight']."</td><td>
            ".$row_sub['remarks']."
          </td><td></td></tr>";
        }
      }

      ?>
    </tbody>
    </table>

  </fieldset>
  <p><span><input type="submit" id='antenatal_save' name="submit" class="art-button-green" value="SAVE ">
  </span> <span><input type="submit" id='antenatal_pdf' name="submit" class="art-button-green"  value="PREVIEW ">
  </span></p>
  </from>
</center>
<?php
include("./includes/footer.php");
?>

<script src="css/jquery.datetimepicker.js"></script>

<script type="text/javascript">

  $("#antenatal_save").click(function(e){
    e.preventDefault();
    var antanatal_data = $("#antenal_form").serialize();
    $.ajax({
      url:"save_antenatal_record.php",
      type:"POST",
      data:antanatal_data,
      success:function(data){
        alert(data);
      }
    })
  })

  $(document).ready(function(e){
    $('.date').datetimepicker({value: '', step: 2});
  })

$("#add").click(function(e){
  e.preventDefault();
  var antanatal_data = $("#antenal_form").serialize();
  $.ajax({
    type:"POST",
    url:"subsequent_antenatal_examinations.php",
    data:antanatal_data,
    success:function(data){
      $("#tbl").append(data)
    }
  })
})


// preview pdf

$("#antenatal_pdf").click(function(e){
  e.preventDefault();
  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

window.open("print_antenatal_record.php?patient_id="+patient_id+"&admision_id="+admission_id,'_blank')
  // alert("print report");

})
</script>
