<?php
include("./includes/connection.php");
include("MPDF/mpdf.php");
if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admision_id'])) {
  $admision_id = $_GET['admision_id'];
}
if (isset($_GET['patient_id'])) {
  $patient_id = $_GET['patient_id'];
}

$htm = "<img src='./branchBanner/branchBanner.png'>";
$htm .= "<p style='font-weight:bold; text-align:center;'> ANTENATAL RECORD</p>";
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
      $varicobe_veins = $row_atenal['varicobe_veins'];
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


  } else {
    echo mysqli_error($conn);
  }

} else {
  echo mysqli_error($conn);
}



$htm .= "<br/ >";

$htm .= "<style media=' screen '>
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
</style>";


$htm .= '<center>
    <form id = "antenal_form"> 

    <div style="text-align:center;height:34px;margin:0px;padding:0px;font-weight:bold">
    <p style = "margin:0px;padding:0px;">ANTENATAL RECORD </p>
    <p style = "color:#33BAFD;margin:0px;padding:0px;"> <span style="margin-right:3px;"> ' . $Patient_Name . ' |</span>
    <span style="margin-right:3px;">' . $Gender . '|</span> 
    <span style="margin-right:3px;">' . $age . '| </span> 
    <span style="margin-right:3px;">' . $Guarantor_Name . '
    </span>
     </p>
  </div>
  

  <div class="title">

    <p>FIRST ANTENAL EXAMINATION</p>
    <p>1ST VISIT</p>
    <p><span>DATE:</span>
      <span >
        ' . $date_of_first_visit
  . '
      </span>
     </p>
  </div>
  <table class="table-top" style="width:24%;">
    <tr>
      <td>HEIGHT:</td>
      <td class="td-input">
      ' . $height . '
      </td>
    </tr>
    <tr>
      <td>WEIGHT:</td>
      <td class="td-input">
      ' . $weight . '
      </td>
    </tr>
    <tr>
      <td class="td-input">BP:</td>
      <td>' . $bp . '
      </td>
    </tr>
    <tr>
      <td class="td-input">FULSE:</td>
      <td>
      ' . $fulse . '
      </td>
    </tr>
    <tr>
      <td class="td-input">TEMP:</td>
      <td>' . $temp . '
      </td>
    </tr>

  </table>
  <table class="table-top" style="width:24%;">
    <tr>
      <td>INVESTIGATIONS:</td>
      <td class="td-input">
      ' . $investigations . '
      </td>
    </tr>
    <tr>
      <td>HB:</td>
      <td class="td-input">
      ' . $hb . '
      </td>
    </tr>
    <tr>
      <td class="td-input">BLOODGROUP:</td>
      <td>
      ' . $bloodgroup . '
      </td>
    </tr>
    <tr>
      <td class="td-input">VDRL:</td>
      <td>' . $vdrl . '</td>
    </tr>
    <tr>
      <td class="td-input">BLISA:</td>
      <td>
      ' . $blisa . '
      </td>
    </tr>

  </table>

  <table class="table-top" style="width:48%;">
    <tr>
      <td colspan="4" style="font-weight:bold;height:30px;">URINALYSIS:</td>
    </tr>
    <tr>
      <td class="td-label">COLOUR:</td>
      <td class="td-input">
      ' . $colour . '
      </td>
      <td class="td-label">BLOOD:</td>
      <td class="td-input">
       ' . $blood . '
       </td>
    </tr>
    <tr>
      <td class="td-label">SPECIFIC GRAVITY:</td>
      <td class="td-input">
      ' . $specific_gravity . '
      </td>
      <td class="td-label">KETONES:</td>
      <td class="td-input">
      ' . $keytones . '
      </td>
    </tr>
    <tr>
      <td class="td-label" >PH:</td>
      <td class="td-input">
      ' . $ph . '
      </td>
      <td class="td-label">UROBILINOGEN:</td>
      <td class="td-input">
      ' . $urobilinogen . '
      </td>
    </tr>
    <tr>
      <td class="td-label">ALUMIN:</td>
      <td  class="td-input">
      ' . $alumin . '
      </td>
      <td class="td-label">LEUCOCETES:</td>
      <td class="td-input">
      ' . $leucocetes . '
      </td>
    </tr>
    <tr>
      <td class="td-label" >SUGAR:</td>
      <td class="td-input">
      ' . $sugar . '
      </td>

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
        <td style=";">' . $head . '</td>
        <td style="%;">NECK</td>
        <td style=";">' . $neck . '</td>
        <td style="%;">EYES</td>
        <td style=";">' . $eyes . '</td>
        <td style="%;">EARS</td>
        <td style=";">' . $ears . '</td>
        <td style="%;">TEETH</td>
        <td style=";">' . $teeth . '</td>
        <td style="%;">BREAST</td>
        <td style=";">' . $breast . '</td>
        <td style="%;">AXILLA</td>
        <td style=";">' . $axilla . '</td>
      </tr>

      <tr>
        <th colspan="14">2. ABNORMAL</th>
      </tr>
      <tr>
        <td>SIZE</td><td>' . $size . '</td>
        <td>SHAPE</td><td>' . $shape . '</td>
        <td>SCAR</td><td>' . $scar . '</td>
        <td>SKIN</td><td>' . $skin . '</td>
        <td>OVAL/PENDULUS</td><td>' . $oval_pendulus . '</td>

      </tr>

      <tr>
        <th colspan="14">3. ABNORMAL PALPATION</th>
      </tr>
      <tr>
        <td>FUNDAL HEIGHT</td><td>' . $fundal_height . '</td>
        <td>LIE</td><td>' . $lie . '</td>
        <td>PRESENTING PARTS</td><td>' . $presenting_part . '</td>
        <td>POSITION</td><td>' . $position . '</td>
        <td>DEEP PELVIC PALPATION</td><td>' . $deep_pelvic_palpation . '</td>
         <td colspan="2">ENGAGEMENT IN RELATIONSHIP TO BRIM</td>
         <td>' . $engagement_in_relationship_to_brim . '</td>

      </tr>

      <tr>
        <th colspan="14">4. AUSCALTATION</th>
      </tr>
      <tr>
        <td>FETAL HEART RATE</td><td>' . $fetal_heart_rate . 'a</td>
        <td>SONICARD</td><td>' . $sonicard . '</td>
        <td>FETOSCOPE</td><td>' . $fetoscope . '</td>

      </tr>

      <tr>
        <th colspan="14">5. GENITALIA</th>
      </tr>
      <tr>
        <td>EXTERNAL</td><td>' . $external . '</td>
        <td>HERPES</td>' . $herpes . '</td>
        <td>WARTS</td><td>' . $warts . '</td>
        <td>HAEMORRHOIDS</td><td>' . $haemorrhoids . '</td>
        <td>ANY OTHER</td><td>' . $any_other . '</td>
         <td colspan="2">WARCOBE VEINS</td><td>' . $warcobe_veins . '</td>

      </tr>
    <tr></tr>  
      <tr>
        <th colspan="14">6. LOWER LIMBS</th>
      </tr>
      <tr>
        <td>oedema</td><td>' . $odema . '</td>
        <td>VARICOBE VEIN</td>
        <td>' . $varicobe_veins . '</td>
        <td>OTHER ABDOMALITIES</td><td>
        ' . $other_abdomalities . '</td>

      </tr>

    </table>
  </fieldset>


  <fieldset>
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
    <tbody>'
?>
      <?php
      $select_subsquent_info = "SELECT * FROM subsquent_antenatal_examination WHERE patient_id = '$patient_id' AND admission_id='$admision_id'";
      $result = mysqli_query($conn,$select_subsquent_info);
      $num = mysqli_num_rows($result);
      if ($num > 0) {
        $sn = 1;
        while ($row_sub = mysqli_fetch_assoc($result)) {
          // echo $row_sub['sub_date'];
          $htm .= "<tr><td>" . $sn++ . "</td><td>" . $row_sub['sub_date'] . "</td><td>" .
            $row_sub['pregnancy_weeks_by_date'] . "</td><td>" .
            $row_sub['pregnancy_weeks_by_size'] . "</td><td>" .
            $row_sub['presentation_in_relation_to_the_brim'] . "</td><td>" .
            $row_sub['poetal_heart_rate'] . "</td><td></td><td>"
            . $row_sub['sugar'] . "</td><td>" . $row_sub['up'] . "</td><td>" .
            $row_sub['weight'] . "</td><td>
            " . $row_sub['remarks'] . "
          </td><td></td></tr>";
        }
      }


      $htm .= '</tbody>
    </table>

  </fieldset>
  </from>
</center>';
      ?>
<?php

$mpdf = new mPDF('s', 'A4');

$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;

// include("./includes/footer.php");
?>