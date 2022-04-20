<?php
include('header.php');
include('../includes/connection.php');
require_once'triage/assets.php';

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])) {
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
  $employee_ID = $_GET['Employee_ID'];
  $delivery_year = $_GET['delivery_year'];
}else{
  header("Location:../../index.php");
}


?>

<link rel="stylesheet" href="triage/styles.css"/>
<!-- <style media="screen">
  th{
    background-color: #006400;
    color: white;
  }
</style> -->

<?php echo Assets::btnNewBornChecklistObservationChat($consultation_id,$employee_ID,$registration_id,$admission_id,$delivery_year); ?>
<?php echo Assets::btnPrint($employee_ID,$registration_id,$delivery_year); ?>
<?php echo Assets::btnBackNewBornChecklistPerYear($consultation_id,$employee_ID,$registration_id,$admission_id); ?>



<div class="container-fluid">

<?php
    $sql_first_time = mysqli_query($conn,"SELECT TIME(saved_time) as 'first_time' FROM tbl_newborn_triage_checklist_records WHERE YEAR(saved_time) = '$delivery_year' AND evaluation_stage = 'firstEvaluation' AND Registration_ID='$registration_id' ORDER BY TIME(saved_time) ASC LIMIT 1");
    $first_time = mysqli_fetch_assoc($sql_first_time)['first_time'];

    $sql_patient = mysqli_query($conn,"SELECT n.Registration_ID,p.Registration_ID,p.Patient_Name FROM tbl_newborn_triage_checklist_records n INNER JOIN tbl_patient_registration p ON n.Registration_ID = p.Registration_ID WHERE YEAR(saved_time) = '$delivery_year' AND n.Registration_ID = '$registration_id'");
    $Patient_Name = mysqli_fetch_assoc($sql_patient)['Patient_Name'];

    $sql_birthweight = mysqli_query($conn,"SELECT birth_weight FROM tbl_newborn_triage_checklist_records   WHERE YEAR(saved_time) = '$delivery_year' AND Registration_ID = '$registration_id'");
    $birth_weight = mysqli_fetch_assoc($sql_birthweight)['birth_weight'];

    $sql_date_birth = mysqli_query($conn,"SELECT DATE(saved_time) as 'date_birth' FROM tbl_newborn_triage_checklist_records WHERE YEAR(saved_time) = '$delivery_year' AND Registration_ID = '$registration_id'");
    $date_birth = mysqli_fetch_assoc($sql_date_birth)['date_birth'];

    $sql_pmtct= mysqli_query($conn,"SELECT pmtct FROM tbl_newborn_triage_checklist_records WHERE YEAR(saved_time) = '$delivery_year' AND Registration_ID = '$registration_id'");
    $pmtct = mysqli_fetch_assoc($sql_pmtct)['pmtct'];

    $sql_comment= mysqli_query($conn,"SELECT comment FROM tbl_newborn_triage_checklist_records WHERE YEAR(saved_time) = '$delivery_year' AND Registration_ID = '$registration_id'");
    $comment = mysqli_fetch_assoc($sql_comment)['comment'];
 ?>

 <fieldset>
   <legend>PATIENT DETAILS</legend>
      <table class="table">
        <tr>
          <td style='font-size: 16px;'><b>Name:</b> <?php echo $Patient_Name;?></td>
          <td style='font-size: 16px;'><b>Birth weight:</b> <?php echo $birth_weight."kg";?></td>
          <td style='font-size: 16px;'><b>Registration No.:</b> <?php echo $registration_id;?></td>
        </tr>
        <tr>
          <td style='font-size: 16px;'><b>Date of birth:</b> <?php echo $date_birth;?></td>
          <td style='font-size: 16px;'><b>PMTCT:</b> <?php echo $pmtct;?></td>
          <td style='font-size: 16px;'><b>Comment:</b> <?php echo $comment;?></td>
        </tr>
      </table>
 </fieldset>
  <fieldset>
    <legend>1st Evaluation After Golden Hour(60-90 min after birth)&nbsp;&nbsp;&nbsp;<b style='color:red;background-color:white;'>Time: <?php echo $first_time; ?></b></legend>
    <table class="table table-hover">
      <tr>
        <th>Birth Weight</th>
        <th>APGAR 5min</th>
        <th>Temp</th>
        <th>Maternal Factors</th>
        <th>Respiration</th>
        <th>Skin&Circulation</th>
        <th>Movements</th>
        <th>Others</th>
        <th>Checked by</th>
      </tr>

      <?php
      $sql_employee_name1= mysqli_query($conn,"SELECT n.Employee_ID,n.Registration_ID,e.Employee_Name,e.Employee_ID FROM tbl_newborn_triage_checklist_records n INNER JOIN tbl_employee e ON n.Employee_ID = e.Employee_ID WHERE n.Registration_ID ='$registration_id' AND evaluation_stage ='firstEvaluation' AND YEAR(saved_time) = '$delivery_year'");
      $employee_Name1 = mysqli_fetch_assoc($sql_employee_name1)['Employee_Name'];


        $first_evaluation = mysqli_query($conn,"SELECT *
                                                FROM tbl_newborn_triage_checklist_records
                                                WHERE Registration_ID ='$registration_id'
                                                AND YEAR(saved_time) = '$delivery_year'
                                                AND evaluation_stage = 'firstEvaluation' ORDER BY saved_time ASC LIMIT 1");
        while($row = mysqli_fetch_assoc($first_evaluation))
        {
          echo " <tr>";
            //birth_weight
            $birth_weight = $row['birth_weight'];
            if($birth_weight < 1.8)
            {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$birth_weight."</td>";
            }
            elseif( $birth_weight >= 1.8 &&  $birth_weight <= 2.4) {
              echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$birth_weight."</td>";
            }
            elseif($birth_weight >= 2.5 &&  $birth_weight <= 4.0) {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$birth_weight."</td>";
            }
            else if($birth_weight > 4.0)
            {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$birth_weight."</td>";
            }
            //End of birth_weight

            //APGAR 5min
            $apgar = $row['apgar'];
            if($apgar < 7)
            {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$apgar."</td>";
            }
            elseif($apgar >= 7 &&  $apgar <= 8)
            {
              echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$apgar."</td>";
            }
            elseif($apgar > 8)
            {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$apgar."</td>";
            }
            //End of apgar

            //temperature
            $temp = $row['temperature'];
            if($temp < 36.0 || $temp > 37.5)
            {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$temp."</td>";
            }
            elseif($temp >= 36.0  && $temp <= 36.4)
            {
              echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$temp."</td>";
            }
            elseif($temp >= 36.5 && $temp <= 37.5)
            {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$temp."</td>";
            }
            //End of temperature


            //Maternal Factors
            $maternal_factors = $row['maternal_factors'];
            if($maternal_factors == 'PROM >18HRS' || $maternal_factors =='Foul smelling amniotic fluid' || $maternal_factors == 'Maternal pyrexia >38.0°C')
            {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$maternal_factors."</td>";
            }
            elseif (empty($maternal_factors)) {
              echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$maternal_factors."</td>";
            }
            elseif ($maternal_factors == 'none') {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$maternal_factors."</td>";
            }
            //End of Maternal Factors

            //Respiration
            $respiration = $row['respiration'];
            if($respiration === 'normal breathing')
            {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
            }
            elseif($respiration < 30 || $respiration > 60 || $respiration == 'difficult in breathing')
            {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
            }
            elseif ($respiration == '') {
              echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
            }
            elseif($respiration >= 30 && $respiration <= 60)
            {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
            }

            //End of Respiration

            //skin&Circulation
            $skin_circulation = $row['skin_circulation'];
            if($skin_circulation == 'central cyanosis' || $skin_circulation == 'capillary refill >3sec' || $skin_circulation == 'pallor')
            {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
            }
            elseif(empty($skin_circulation))
            {
              echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
            }
            elseif($skin_circulation == 'normal colour' || $skin_circulation == 'capillary refill <= 3sec')
            {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
            }
            //End of skin&Circulation

            //movements
            $movements = $row['movements'];
            if($movements == 'no movement at all' || $movements == 'movement only stimulated')
            {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$movements."</td>";
            }
            elseif(empty($movements))
            {
              echo "<td style='background-color:yellow;'>".$movements."</td>";
            }
            elseif($movements == 'normal movement')
            {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$movements."</td>";
            }
            //End of movements


            //Others
            $others = $row['others'];
            if ($others == 'congenital malformation' || $others == 'convulsion') {
              echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$others."</td>";
            }
            elseif (empty($others)) {
              echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$others."</td>";
            }
            elseif ($others == 'none') {
              echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$others."</td>";

            }
            echo "<td>".$employee_Name1."</td>";

          echo "</tr>";


        }

       ?>

    </table>
  </fieldset><br><br>


  <fieldset>
    <?php
        $sql_second_time = mysqli_query($conn,"SELECT TIME(saved_time) as 'second_time' FROM tbl_newborn_triage_checklist_records WHERE YEAR(saved_time) = '$delivery_year' AND evaluation_stage = 'secondEvaluation' AND Registration_ID='$registration_id' ORDER BY TIME(saved_time) ASC LIMIT 1");
        $second_time = mysqli_fetch_assoc($sql_second_time)['second_time'];
     ?>
    <legend>2nd Evaluation After(4-8 after birth)&nbsp;&nbsp;<b style='color:red;background-color:white;'>Time: <?php echo $second_time; ?></b></legend>
    <table class="table table-hover">
      <tr>
        <th>Umbilicus</th>
        <th>Feeding</th>
        <th>Temp</th>
        <th>Maternal Factors</th>
        <th>Respiration</th>
        <th>Skin&Circulation</th>
        <th>Movements</th>
        <th>Others</th>
        <th>Checked by</th>
      </tr>
      <?php
            $sql_employee_name2= mysqli_query($conn,"SELECT n.Employee_ID,n.Registration_ID,e.Employee_Name,e.Employee_ID FROM tbl_newborn_triage_checklist_records n INNER JOIN tbl_employee e ON n.Employee_ID = e.Employee_ID WHERE n.Registration_ID ='$registration_id' AND evaluation_stage ='secondEvaluation' AND YEAR(saved_time) = '$delivery_year'");
            $employee_Name2 = mysqli_fetch_assoc($sql_employee_name2)['Employee_Name'];

            $second_evaluation = mysqli_query($conn,"SELECT *
                                                    FROM tbl_newborn_triage_checklist_records
                                                    WHERE Registration_ID ='$registration_id'
                                                    AND YEAR(saved_time) = '$delivery_year'
                                                    AND evaluation_stage = 'secondEvaluation' ORDER BY saved_time ASC LIMIT 1");
            while($row2 = mysqli_fetch_assoc($second_evaluation))
            {
              echo " <tr>";

              // if ($row2['evaluation_stage'] == 'secondEvaluation') {
              //   // code...
              // }
              //end main if

              //umbilicus
              $umbilicus = $row2['umbilicus'];
              if($umbilicus == 'bleeding')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$umbilicus."</td>";
              }
              elseif (empty($umbilicus)) {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$umbilicus."</td>";
              }
              elseif ($umbilicus == 'no bleeding') {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$umbilicus."</td>";
              }
              //End of umbilicus


              //feeding
              $feeding = $row2['feeding'];
              if($feeding == 'not sucking' || $feeding == 'not sucking well' || $feeding == 'vomit after each feed')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$feeding."</td>";
              }
              elseif ($feeding == 'breast feeding problem') {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$feeding."</td>";
              }
              elseif ($feeding == 'normal') {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$feeding."</td>";
              }

              //temperature
              $temp = $row2['temperature'];
              if($temp < 36.0 || $temp > 37.5 )
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$temp."</td>";
              }
              elseif($temp >= 36.0  && $temp <= 36.4)
              {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$temp."</td>";
              }
              elseif($temp >= 36.5 && $temp <= 37.5)
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$temp."</td>";
              }
              //End of temperature


              //Maternal Factors
              $maternal_factors = $row2['maternal_factors'];
              if($maternal_factors == 'PROM >18HRS' || $maternal_factors =='Foul smelling amniotic fluid' || $maternal_factors == 'Maternal pyrexia >38.0°C')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$maternal_factors."</td>";
              }
              elseif (empty($maternal_factors)) {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$maternal_factors."</td>";
              }
              elseif ($maternal_factors == 'none') {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$maternal_factors."</td>";
              }
              //End of Maternal Factors

              //Respiration
              $respiration = $row2['respiration'];
              if($respiration === 'normal breathing')
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
              }
              elseif($respiration < 30 || $respiration > 60 || $respiration == 'difficult in breathing')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
              }
              elseif ($respiration == '') {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
              }
              elseif($respiration >= 30 && $respiration <= 60)
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
              }

              //End of Respiration

              //skin&Circulation
              $skin_circulation = $row2['skin_circulation'];
              if($skin_circulation == 'central cyanosiss' || $skin_circulation == 'capillary refill >3sec' || $skin_circulation == 'pallor')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
              }
              elseif(empty($skin_circulation))
              {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
              }
              elseif($skin_circulation == 'normal colour' || $skin_circulation == 'capillary refill <= 3sec')
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
              }
              //End of skin&Circulation

              //movements
              $movements = $row2['movements'];
              if($movements == 'no movement at all' || $movements == 'movement only stimulated')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$movements."</td>";
              }
              elseif(empty($movements))
              {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$movements."</td>";
              }
              elseif($movements == 'normal movement')
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$movements."</td>";
              }
              //End of movements


              //Others
              $others = $row2['others'];
              if ($others == 'congenital malformation' || $others == 'convulsion') {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$others."</td>";
              }
              elseif (empty($others)) {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$others."</td>";
              }
              elseif ($others == 'none') {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$others."</td>";
              }

              echo "<td>".$employee_Name2."</td>";
              echo " </tr>";
            }

       ?>

    </table>
  </fieldset><br><br>



  <fieldset>
    <?php
        $sql_third_time = mysqli_query($conn,"SELECT TIME(saved_time) as 'third_time' FROM tbl_newborn_triage_checklist_records WHERE YEAR(saved_time) = '$delivery_year' AND evaluation_stage = 'thirdEvaluation' AND Registration_ID='$registration_id' ORDER BY TIME(saved_time) ASC LIMIT 1");
        $third_time = mysqli_fetch_assoc($sql_third_time)['third_time'];
     ?>
    <legend>3rd Evaluation After(20-24 hours after birth)&nbsp;&nbsp;&nbsp;<b style='color:red;background-color:white;'>Time: <?php echo $third_time; ?></b></legend>
    <table class="table table-hover">
      <tr>
        <th>Current Weight</th>
        <th>Feeding</th>
        <th>Temp</th>
        <th>Umbilicus</th>
        <th>Respiration</th>
        <th>Skin&Circulation</th>
        <th>Movements</th>
        <th>Others</th>
        <th>Checked by</th>
      </tr>
      <?php
            $sql_employee_name3= mysqli_query($conn,"SELECT n.Employee_ID,n.Registration_ID,e.Employee_Name,e.Employee_ID FROM tbl_newborn_triage_checklist_records n INNER JOIN tbl_employee e ON n.Employee_ID = e.Employee_ID WHERE n.Registration_ID ='$registration_id' AND evaluation_stage ='thirdEvaluation' AND YEAR(saved_time) = '$delivery_year'");
            $employee_Name3 = mysqli_fetch_assoc($sql_employee_name3)['Employee_Name'];

            $third_evaluation = mysqli_query($conn,"SELECT *
                                                    FROM tbl_newborn_triage_checklist_records
                                                    WHERE Registration_ID ='$registration_id'
                                                    AND YEAR(saved_time) = '$delivery_year'
                                                    AND evaluation_stage = 'thirdEvaluation' ORDER BY saved_time ASC LIMIT 1");
            while($row3 = mysqli_fetch_assoc($third_evaluation))
            {
              echo " <tr>";

              //current_weight
              $current_weight = $row3['current_weight'];
              if($current_weight < 1.8 || $current_weight > 4.0)
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$current_weight."</td>";
              }
              elseif( $current_weight >= 1.8 &&  $current_weight <= 2.4) {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$birth_weight."</td>";
              }
              elseif($current_weight >= 2.5 &&  $current_weight <= 4.0) {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$current_weight."</td>";
              }
              //End of current_weight


              //feeding
              $feeding = $row3['feeding'];
              if($feeding == 'not sucking' || $feeding == 'not sucking well' || $feeding == 'vomit after each feed')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$feeding."</td>";
              }
              elseif ($feeding == 'breast feeding problem') {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$feeding."</td>";
              }
              elseif ($feeding == 'normal') {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$feeding."</td>";
              }
                //End of feeding


              //temperature
              $temp3 = $row3['temperature'];
              if($temp3 < 36.0 || $temp3 > 37.5 )
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$temp3."</td>";
              }
              elseif($temp3 >= 36.0  && $temp3 <= 36.4)
              {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$temp3."</td>";
              }
              elseif($temp3 >= 36.5 && $temp3 <= 37.5)
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$temp3."</td>";
              }
              //End of temperature

              //umbilicus
              $umbilicus = $row3['umbilicus'];
              if($umbilicus == 'bleeding')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$umbilicus."</td>";
              }
              elseif (empty($umbilicus)) {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$umbilicus."</td>";
              }
              elseif ($umbilicus == 'no bleeding') {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$umbilicus."</td>";
              }
              //End of umbilicus


              //Respiration
              $respiration = $row3['respiration'];
              if($respiration === 'normal breathing')
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
              }
              elseif($respiration < 30 || $respiration > 60 || $respiration == 'difficult in breathing')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
              }
              elseif ($respiration == '') {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
              }
              elseif($respiration >= 30 && $respiration <= 60)
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$respiration."</td>";
              }

              //End of Respiration

              //skin&Circulation
              $skin_circulation = $row3['skin_circulation'];
              if($skin_circulation == 'central cyanosiss' || $skin_circulation == 'capillary refill >3sec' || $skin_circulation == 'pallor')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
              }
              elseif(empty($skin_circulation))
              {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
              }
              elseif($skin_circulation == 'normal colour' || $skin_circulation == 'capillary refill <= 3sec')
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$skin_circulation."</td>";
              }
              //End of skin&Circulation

              //movements
              $movements = $row3['movements'];
              if($movements == 'no movement at all' || $movements == 'movement only stimulated')
              {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$movements."</td>";
              }
              elseif(empty($movements))
              {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$movements."</td>";
              }
              elseif($movements == 'normal movement')
              {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$movements."</td>";
              }
              //End of movements


              //Others
              $others = $row3['others'];
              if ($others == 'congenital malformation' || $others == 'convulsion') {
                echo "<td style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>".$others."</td>";
              }
              elseif (empty($others)) {
                echo "<td style='background-color:yellow;font-size: 16px;font-weight: bold;'>".$others."</td>";
              }
              elseif ($others == 'none') {
                echo "<td style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>".$others."</td>";
              }

              echo "<td>".$employee_Name3."</td>";
              echo " </tr>";
            }

       ?>

    </table>
  </fieldset><br><br>

  <fieldset>
    <legend>ACTION TO TAKE</legend>
  <table class="table table-striped table-hover">
      <tr>
      <th style='background-color:#ff3333;font-size: 16px;font-weight: bold;'>HIGH RISK</th>
      <th style='background-color:yellow;font-size: 16px;font-weight: bold;'>AT RISK</th>
      <th style='background-color:#4ce600;font-size: 16px;font-weight: bold;'>NO RISK</th>
      <th>EVALUATION</th>
      </tr>
      <!-- 1st evaluation -->
      <tr>
        <td>
          Admit at/ refer to facility with NCU<br>
          Immediate administration of IM/IV antibiotics
        </td>

        <td>
          -Use observation chart<br>
          -Weight< 2.5kg: Admit at/ refer to facility with KMC<br>
          -For babies >4kg:<br>
            Monitor blood
            glucose every 2hours
            until mother has
            enough milk to feed
            her baby
        </td>

        <td>
          -Continue with routine observation with NTC card
        </td>
        <td>1st Evaluation</td>
      </tr>
      <!-- end of 1st evaluation -->

      <!-- 2nd evaluation -->
      <tr>
          <td>
            Admit at/ refer to facility with NCU<br>
            Immediate administration of IM/IV antibiotics
          </td>

          <td>
            -Use observation chart<br>
            -Close observation,improve thermal care
          </td>

          <td>
            Continue observation until at least 24hours
          </td>

          <td>2nd Evaluation</td>
      </tr>
      <!--end of  2nd evaluation -->

      <!-- 3rd evaluation -->
      <tr>
          <td>
            Admit at/ refer to facility with NCU<br>
            Immediate administration of IM/IV antibiotics
          </td>

          <td>
            Use observation chart<br>
            -Weight< 2.5kg:Admit at/ refer to facility with KMC
          </td>

          <td>
            -Prepare for counselling and discharge
          </td>

          <td>3rd Evaluation</td>
      </tr>
      <!--end of  3rd evaluation -->
  </table>
  </fieldset>

</div>



<?php
    include("../includes/footer.php");
?>
