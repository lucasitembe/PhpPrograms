<?php
include("../includes/connection.php");
include("../MPDF/mpdf.php");

if ($_GET['Registration_ID']) {
  $registration_id = $_GET['Registration_ID'];
  //$employee_ID = $_GET['Employee_ID'];
  $delivery_year = $_GET['delivery_year'];

}

if (isset($_SESSION['userinfo'])) {
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
}


$htm .= "<center><img src='../branchBanner/branchBanner.png' width='100%' ></center>";

$select_basic = "SELECT * FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$registration_id' AND YEAR(birth_date) = '$delivery_year'";
$basic = mysqli_query($conn,$select_basic);
while ($r = mysqli_fetch_assoc($basic)) {
  $baby_name = $r['baby_name'];
  $birth_weight = $r['birth_weight'];
  $sex = $r['sex'];
  $Registration_ID = $r['Registration_ID'];
  $birth_date = $r['birth_date'];
  $apgar_score1min = $r['apgar_score1min'];
  $apgar_score5min = $r['apgar_score5min'];
  $referral = $r['referral'];
  $referral_from = $r['referral_from'];
  $history_or_dx = $r['history_or_dx'];

}


$htm .= '<div class="table-responsive">
<table class="table table-hover">
  <thead>
    <div>
    <tr>
      <th colspan="4">Name  B/O: <span style="color:#009999;">'.$baby_name.'</span></th>
      <th colspan="3">Birth weight: <span style="color:#009999;">'.$birth_weight.'</span></th>
      <th colspan="3">Sex:  <span style="color:#009999;">'.$sex.'</span></th>
      <th colspan="4">File No: <span style="color:#009999;">'.$Registration_ID.'</span></th>
    </tr>
    <tr>
      <th colspan="4">Date of birth: <span style="color:#009999;">'.$birth_date.'</span></th>
      <th colspan="3">APGAR SCORE: <span style="color:#009999;">1min:'.$apgar_score1min.' 5min:'.$apgar_score5min.'</span></th>
      <th colspan="3">Referral: <span style="color:#009999;">'.$referral.'</span></th>
      <th colspan="4">From: <span style="color:#009999;">'.$referral_from.'</span></th>
    </tr>
    <tr>
      <th>History / Dx</th>
      <th colspan="13"><span style="color:#009999;float:right;">'.$history_or_dx.'</span></th>
    </tr>
  </div>';

  $one  = "SELECT * FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$registration_id' AND day = 1 AND YEAR(birth_date) = '$delivery_year' ORDER BY birth_date ASC LIMIT 1";
  $two  = "SELECT * FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$registration_id' AND day = 2 AND YEAR(birth_date) = '$delivery_year' ORDER BY birth_date ASC LIMIT 1";
  $three  = "SELECT * FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$registration_id' AND day = 3 AND YEAR(birth_date) = '$delivery_year' ORDER BY birth_date ASC LIMIT 1";
  $four  = "SELECT * FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$registration_id' AND day = 4 AND YEAR(birth_date) = '$delivery_year' ORDER BY birth_date ASC LIMIT 1";
  $five  = "SELECT * FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$registration_id' AND day = 5 AND YEAR(birth_date) = '$delivery_year' ORDER BY birth_date ASC LIMIT 1";
  $six  = "SELECT * FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$registration_id' AND day = 6 AND YEAR(birth_date) = '$delivery_year' ORDER BY birth_date ASC LIMIT 1";
  $seven  = "SELECT * FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$registration_id' AND day = 7 AND YEAR(birth_date) = '$delivery_year' ORDER BY birth_date ASC LIMIT 1";


  // die($two);
  // $execute_tone3 = mysqli_query($conn,$three);
  // $execute_tone4 = mysqli_query($conn,$four);
  // $execute_tone5 = mysqli_query($conn,$five);
  // $execute_tone6 = mysqli_query($conn,$six);
  // $execute_tone7 = mysqli_query($conn,$seven);

  // **************************** execute day 1 **************************************************************************************************
  $execute_saved_time1 = mysqli_query($conn,$one);
  $execute_tone1 = mysqli_query($conn,$one);
  $execute_loc1 = mysqli_query($conn,$one);
  $execute_fits1 = mysqli_query($conn,$one);
  $execute_posture1 = mysqli_query($conn,$one);
  $execute_moro1 = mysqli_query($conn,$one);
  $execute_grasp1 = mysqli_query($conn,$one);
  $execute_suck1 = mysqli_query($conn,$one);
  $execute_fontanelle1 = mysqli_query($conn,$one);
  $execute_respiratory1 = mysqli_query($conn,$one);

  $saved_time1 = mysqli_fetch_assoc($execute_saved_time1)['saved_time'];
  $tone1 = mysqli_fetch_assoc($execute_tone1)['tone'];
  $loc1 = mysqli_fetch_assoc($execute_loc1)['loc'];
  $fits1 = mysqli_fetch_assoc($execute_fits1)['fits'];
  $posture1 = mysqli_fetch_assoc($execute_posture1)['posture'];
  $moro1 = mysqli_fetch_assoc($execute_moro1)['moro'];
  $grasp1 = mysqli_fetch_assoc($execute_grasp1)['grasp'];
  $suck1 = mysqli_fetch_assoc($execute_suck1)['suck'];
  $respiratory1 = mysqli_fetch_assoc($execute_respiratory1)['respiratory'];
  $fontanelle1 = mysqli_fetch_assoc($execute_fontanelle1)['fontanelle'];

  $total_day_one = 0;
  $total_day_one = $loc1 + $loc1 + $fits1 + $posture1 + $moro1 + $grasp1 + $suck1 + $respiratory1 + $fontanelle1;
  //************************************************************************************************************************************************

  // *********************************** execute day 2 *********************************************************************************************
  $execute_saved_time2 = mysqli_query($conn,$two);
  $execute_tone2 = mysqli_query($conn,$two);
  $execute_loc2 = mysqli_query($conn,$two);
  $execute_fits2 = mysqli_query($conn,$two);
  $execute_posture2 = mysqli_query($conn,$two);
  $execute_moro2 = mysqli_query($conn,$two);
  $execute_grasp2 = mysqli_query($conn,$two);
  $execute_suck2 = mysqli_query($conn,$two);
  $execute_fontanelle2 = mysqli_query($conn,$two);
  $execute_respiratory2 = mysqli_query($conn,$two);


  $saved_time2 = mysqli_fetch_assoc($execute_saved_time2)['saved_time'];
  $tone2 = mysqli_fetch_assoc($execute_tone2)['tone'];
  $loc2 = mysqli_fetch_assoc($execute_loc2)['loc'];
  $fits2 = mysqli_fetch_assoc($execute_fits2)['fits'];
  $posture2 = mysqli_fetch_assoc($execute_posture2)['posture'];
  $moro2 = mysqli_fetch_assoc($execute_moro2)['moro'];
  $grasp2 = mysqli_fetch_assoc($execute_grasp2)['grasp'];
  $suck2 = mysqli_fetch_assoc($execute_suck2)['suck'];
  $respiratory2 = mysqli_fetch_assoc($execute_respiratory2)['respiratory'];
  $fontanelle2 = mysqli_fetch_assoc($execute_fontanelle2)['fontanelle'];

  $total_day_two = 0;
  $total_day_two = $loc2 + $loc2 + $fits2 + $posture2 + $moro2 + $grasp2 + $suck2 + $respiratory2 + $fontanelle2;

// ***********************************************************************************************************************************************



// *********************************** execute day 3 *********************************************************************************************
$execute_saved_time3 = mysqli_query($conn,$three);
$execute_tone3 = mysqli_query($conn,$three);
$execute_loc3 = mysqli_query($conn,$three);
$execute_fits3 = mysqli_query($conn,$three);
$execute_posture3 = mysqli_query($conn,$three);
$execute_moro3 = mysqli_query($conn,$three);
$execute_grasp3 = mysqli_query($conn,$three);
$execute_suck3 = mysqli_query($conn,$three);
$execute_fontanelle3 = mysqli_query($conn,$three);
$execute_respiratory3 = mysqli_query($conn,$three);

$saved_time3 = mysqli_fetch_assoc($execute_saved_time3)['saved_time'];
$tone3 = mysqli_fetch_assoc($execute_tone3)['tone'];
$loc3 = mysqli_fetch_assoc($execute_loc3)['loc'];
$fits3 = mysqli_fetch_assoc($execute_fits3)['fits'];
$posture3 = mysqli_fetch_assoc($execute_posture3)['posture'];
$moro3 = mysqli_fetch_assoc($execute_moro3)['moro'];
$grasp3 = mysqli_fetch_assoc($execute_grasp3)['grasp'];
$suck3 = mysqli_fetch_assoc($execute_suck3)['suck'];
$respiratory3 = mysqli_fetch_assoc($execute_respiratory3)['respiratory'];
$fontanelle3 = mysqli_fetch_assoc($execute_fontanelle3)['fontanelle'];

$total_day_three = 0;
$total_day_three = $loc3 + $loc3 + $fits3 + $posture3 + $moro3 + $grasp3 + $suck3 + $respiratory3 + $fontanelle3;

// ***********************************************************************************************************************************************


// *********************************** execute day 4 *********************************************************************************************
$execute_saved_time4 = mysqli_query($conn,$four);
$execute_tone4 = mysqli_query($conn,$four);
$execute_loc4 = mysqli_query($conn,$four);
$execute_fits4 = mysqli_query($conn,$four);
$execute_posture4 = mysqli_query($conn,$four);
$execute_moro4 = mysqli_query($conn,$four);
$execute_grasp4 = mysqli_query($conn,$four);
$execute_suck4 = mysqli_query($conn,$four);
$execute_fontanelle4 = mysqli_query($conn,$four);
$execute_respiratory4 = mysqli_query($conn,$four);

$saved_time4 = mysqli_fetch_assoc($execute_saved_time4)['saved_time'];
$tone4 = mysqli_fetch_assoc($execute_tone4)['tone'];
$loc4 = mysqli_fetch_assoc($execute_loc4)['loc'];
$fits4 = mysqli_fetch_assoc($execute_fits4)['fits'];
$posture4 = mysqli_fetch_assoc($execute_posture4)['posture'];
$moro4 = mysqli_fetch_assoc($execute_moro4)['moro'];
$grasp4 = mysqli_fetch_assoc($execute_grasp4)['grasp'];
$suck4 = mysqli_fetch_assoc($execute_suck4)['suck'];
$respiratory4 = mysqli_fetch_assoc($execute_respiratory4)['respiratory'];
$fontanelle4 = mysqli_fetch_assoc($execute_fontanelle4)['fontanelle'];

$total_day_four = 0;
$total_day_four = $loc4 + $loc4 + $fits4 + $posture4 + $moro4 + $grasp4 + $suck4 + $respiratory4 + $fontanelle4;

// ***********************************************************************************************************************************************



// *********************************** execute day 5 *********************************************************************************************
$execute_saved_time5 = mysqli_query($conn,$five);
$execute_tone5 = mysqli_query($conn,$five);
$execute_loc5 = mysqli_query($conn,$five);
$execute_fits5 = mysqli_query($conn,$five);
$execute_posture5 = mysqli_query($conn,$five);
$execute_moro5 = mysqli_query($conn,$five);
$execute_grasp5 = mysqli_query($conn,$five);
$execute_suck5 = mysqli_query($conn,$five);
$execute_fontanelle5 = mysqli_query($conn,$five);
$execute_respiratory5 = mysqli_query($conn,$five);

$saved_time5 = mysqli_fetch_assoc($execute_saved_time5)['saved_time'];
$tone5 = mysqli_fetch_assoc($execute_tone5)['tone'];
$loc5 = mysqli_fetch_assoc($execute_loc5)['loc'];
$fits5 = mysqli_fetch_assoc($execute_fits5)['fits'];
$posture5 = mysqli_fetch_assoc($execute_posture5)['posture'];
$moro5 = mysqli_fetch_assoc($execute_moro5)['moro'];
$grasp5 = mysqli_fetch_assoc($execute_grasp5)['grasp'];
$suck5 = mysqli_fetch_assoc($execute_suck5)['suck'];
$respiratory5 = mysqli_fetch_assoc($execute_respiratory5)['respiratory'];
$fontanelle5 = mysqli_fetch_assoc($execute_fontanelle5)['fontanelle'];

$total_day_five = 0;
$total_day_five = $loc5 + $loc5 + $fits5 + $posture5 + $moro5 + $grasp5 + $suck5 + $respiratory5 + $fontanelle5;

// ***********************************************************************************************************************************************



// *********************************** execute day 6 *********************************************************************************************
$execute_saved_time6 = mysqli_query($conn,$six);
$execute_tone6 = mysqli_query($conn,$six);
$execute_loc6 = mysqli_query($conn,$six);
$execute_fits6 = mysqli_query($conn,$six);
$execute_posture6 = mysqli_query($conn,$six);
$execute_moro6 = mysqli_query($conn,$six);
$execute_grasp6 = mysqli_query($conn,$six);
$execute_suck6 = mysqli_query($conn,$six);
$execute_fontanelle6 = mysqli_query($conn,$six);
$execute_respiratory6 = mysqli_query($conn,$six);

$saved_time6 = mysqli_fetch_assoc($execute_saved_time6)['saved_time'];
$tone6 = mysqli_fetch_assoc($execute_tone6)['tone'];
$loc6 = mysqli_fetch_assoc($execute_loc6)['loc'];
$fits6 = mysqli_fetch_assoc($execute_fits6)['fits'];
$posture6 = mysqli_fetch_assoc($execute_posture6)['posture'];
$moro6 = mysqli_fetch_assoc($execute_moro6)['moro'];
$grasp6 = mysqli_fetch_assoc($execute_grasp6)['grasp'];
$suck6 = mysqli_fetch_assoc($execute_suck6)['suck'];
$respiratory6 = mysqli_fetch_assoc($execute_respiratory6)['respiratory'];
$fontanelle6 = mysqli_fetch_assoc($execute_fontanelle6)['fontanelle'];

$total_day_six = 0;
$total_day_six = $loc6 + $loc6 + $fits6 + $posture6 + $moro6 + $grasp6 + $suck6 + $respiratory6 + $fontanelle6;

// ***********************************************************************************************************************************************



// *********************************** execute day 6 *********************************************************************************************
$execute_saved_time7 = mysqli_query($conn,$seven);
$execute_tone7 = mysqli_query($conn,$seven);
$execute_loc7 = mysqli_query($conn,$seven);
$execute_fits7 = mysqli_query($conn,$seven);
$execute_posture7 = mysqli_query($conn,$seven);
$execute_moro7 = mysqli_query($conn,$seven);
$execute_grasp7 = mysqli_query($conn,$seven);
$execute_suck7 = mysqli_query($conn,$seven);
$execute_fontanelle7 = mysqli_query($conn,$seven);
$execute_remarks = mysqli_query($conn,$seven);

$saved_time7 = mysqli_fetch_assoc($execute_saved_time7)['saved_time'];
$tone7 = mysqli_fetch_assoc($execute_tone7)['tone'];
$loc7 = mysqli_fetch_assoc($execute_loc7)['loc'];
$fits7 = mysqli_fetch_assoc($execute_fits7)['fits'];
$posture7 = mysqli_fetch_assoc($execute_posture7)['posture'];
$moro7 = mysqli_fetch_assoc($execute_moro7)['moro'];
$grasp7 = mysqli_fetch_assoc($execute_grasp7)['grasp'];
$suck7 = mysqli_fetch_assoc($execute_suck7)['suck'];
$respiratory7 = mysqli_fetch_assoc($execute_respiratory7)['respiratory'];
$fontanelle7 = mysqli_fetch_assoc($execute_fontanelle7)['fontanelle'];
$remarks = mysqli_fetch_assoc($execute_remarks)['remarks'];

$total_day_seven = 0;
$total_day_seven = $loc7 + $loc7 + $fits7 + $posture7 + $moro7 + $grasp7 + $suck7 + $respiratory7 + $fontanelle7;

// ***********************************************************************************************************************************************


    $htm .='<tr>
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
      <th style="background-color:#71c3ff;">'.$saved_time1.'</th>
      <th style="background-color:#71c3ff;">'.$saved_time2.'</th>
      <th style="background-color:#71c3ff;">'.$saved_time3.'</th>
      <th style="background-color:#71c3ff;">'.$saved_time4.'</th>
      <th style="background-color:#71c3ff;">'.$saved_time5.'</th>
      <th style="background-color:#71c3ff;">'.$saved_time6.'</th>
      <th style="background-color:#71c3ff;">'.$saved_time7.'</th>
      <th style="background-color:#71c3ff;">'.$saved_time7.'</th>

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
      <td>'.$tone1.'</td>
      <td>'.$tone2.'</td>
      <td>'.$tone3.'</td>
      <td>'.$tone4.'</td>
      <td>'.$tone5.'</td>
      <td>'.$tone6.'</td>
      <td>'.$tone7.'</td>
      <td rowspan="9">'.$remarks.'</td>
    </tr>
   </div>
    <tr>
      <th style="background-color:#71c3ff;">LOC</th>
      <td>Normal</td>
      <td>Hyper alert state</td>
      <td>Lethargic</td>
      <td>Comatose</td>
      <td style="background-color:#71c3ff;"></td>
      <td>'.$loc1.'</td>
      <td>'.$loc2.'</td>
      <td>'.$loc3.'</td>
      <td>'.$loc4.'</td>
      <td>'.$loc5.'</td>
      <td>'.$loc6.'</td>
      <td>'.$loc7.'</td>
    </tr>

    <tr>
      <th style="background-color:#71c3ff;">Fits</th>
      <td>Normal</td>
      <td>Infrequent < 3/day</td>
      <td>Frequent >2/day</td>
      <td>-</td>
      <td style="background-color:#71c3ff;"></td>
      <td>'.$fits1.'</td>
      <td>'.$fits2.'</td>
      <td>'.$fits3.'</td>
      <td>'.$fits4.'</td>
      <td>'.$fits5.'</td>
      <td>'.$fits6.'</td>
      <td>'.$fits7.'</td>
    </tr>

    <tr>
      <th style="background-color:#71c3ff;">Posture</th>
      <td>Normal</td>
      <td>Fisting, cycling</td>
      <td>Strong distal flexion</td>
      <td>Decerebrate</td>
      <td style="background-color:#71c3ff;"></td>
      <td>'.$posture1.'</td>
      <td>'.$posture2.'</td>
      <td>'.$posture3.'</td>
      <td>'.$posture4.'</td>
      <td>'.$posture5.'</td>
      <td>'.$posture6.'</td>
      <td>'.$posture7.'</td>

    </tr>

    <tr>
      <th style="background-color:#71c3ff;">Moro</th>
      <td>Normal</td>
      <td>Absent</td>
      <td>-</td>
      <td>-</td>
      <td style="background-color:#71c3ff;"></td>
      <td>'.$moro1.'</td>
      <td>'.$moro2.'</td>
      <td>'.$moro3.'</td>
      <td>'.$moro4.'</td>
      <td>'.$moro5.'</td>
      <td>'.$moro6.'</td>
      <td>'.$moro7.'</td>

    </tr>

    <tr>
      <th style="background-color:#71c3ff;">Grasp</th>
      <td>Normal</td>
      <td>Poor</td>
      <td>Absent</td>
      <td>-</td>
      <td style="background-color:#71c3ff;"></td>
      <td>'.$grasp1.'</td>
      <td>'.$grasp2.'</td>
      <td>'.$grasp3.'</td>
      <td>'.$grasp4.'</td>
      <td>'.$grasp5.'</td>
      <td>'.$grasp6.'</td>
      <td>'.$grasp7.'</td>

    </tr>

    <tr>
      <th style="background-color:#71c3ff;">Suck</th>
      <td>Normal</td>
      <td>Poor</td>
      <td>Absent + bites</td>
      <td>-</td>
      <td style="background-color:#71c3ff;"></td>
      <td>'.$suck1.'</td>
      <td>'.$suck2.'</td>
      <td>'.$suck3.'</td>
      <td>'.$suck4.'</td>
      <td>'.$suck5.'</td>
      <td>'.$suck6.'</td>
      <td>'.$suck7.'</td>

    </tr>

    <tr>
      <th style="background-color:#71c3ff;">Respiratory</th>
      <td>Normal</td>
      <td>Hyperventilation</td>
      <td>Brief Apnoea</td>
      <td>Ventilation</td>
      <td style="background-color:#71c3ff;"></td>
      <td>'.$respiratory1.'</td>
      <td>'.$respiratory2.'</td>
      <td>'.$respiratory3.'</td>
      <td>'.$respiratory4.'</td>
      <td>'.$respiratory5.'</td>
      <td>'.$respiratory6.'</td>
      <td>'.$respiratory7.'</td>

    </tr>

    <tr>
      <th style="background-color:#71c3ff;">Fontanelle</th>
      <td>Normal</td>
      <td>Full, non-tense</td>
      <td>Tense</td>
      <td>-</td>
      <td style="background-color:#71c3ff;"></td>
      <td>'.$fontanelle1.'</td>
      <td>'.$fontanelle2.'</td>
      <td>'.$fontanelle3.'</td>
      <td>'.$fontanelle4.'</td>
      <td>'.$fontanelle5.'</td>
      <td>'.$fontanelle6.'</td>
      <td>'.$fontanelle7.'</td>

    </tr>

    <tr>
      <th style="background-color:#71c3ff;">Total score per day</th>
      <td colspan="5" style="background-color:#71c3ff;"></td>
      <td style="background-color:#71c3ff;font-weight:bold;">'.$total_day_one.'</td>
      <td style="background-color:#71c3ff;font-weight:bold;">'.$total_day_two.'</td>
      <td style="background-color:#71c3ff;font-weight:bold;">'.$total_day_three.'</td>
      <td style="background-color:#71c3ff;font-weight:bold;">'.$total_day_four.'</td>
      <td style="background-color:#71c3ff;font-weight:bold;">'.$total_day_five.'</td>
      <td style="background-color:#71c3ff;font-weight:bold;">'.$total_day_six.'</td>
      <td style="background-color:#71c3ff;font-weight:bold;">'.$total_day_seven.'</td>
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
</table>';

$mpdf = new mPDF('s', 'A4');

$mpdf->SetDisplayMode('fullpage');
$mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
 ?>
