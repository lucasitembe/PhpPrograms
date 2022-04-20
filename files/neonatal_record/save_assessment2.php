<?php
include('../includes/connection.php');
include("../MPDF/mpdf.php");


// *********************** save data *******************************************************************
if (isset($_POST['discharge_head_circumference'])) {

  $Employee_ID = mysqli_real_escape_string($conn,trim($_POST['Employee_ID']));
  $Registration_ID = mysqli_real_escape_string($conn,trim($_POST['Registration_ID']));
  $breast_feeding_demo = mysqli_real_escape_string($conn,trim($_POST['breast_feeding_demo']));
  $breast_feeding_initial = mysqli_real_escape_string($conn,trim($_POST['breast_feeding_initial']));
  $breast_feeding_comments = mysqli_real_escape_string($conn,trim($_POST['breast_feeding_comments']));
  $breast_feeding_superv_demo = mysqli_real_escape_string($conn,trim($_POST['breast_feeding_superv_demo']));
  $breast_feeding_initials = mysqli_real_escape_string($conn,trim($_POST['breast_feeding_initials']));
  $breast_feeding_comments_second = mysqli_real_escape_string($conn,trim($_POST['breast_feeding_comments_second']));
  $cup_feeding_demo = mysqli_real_escape_string($conn,trim($_POST['cup_feeding_demo']));
  $cup_feeding_initial = mysqli_real_escape_string($conn,trim($_POST['cup_feeding_initial']));
  $cup_feeding_comment = mysqli_real_escape_string($conn,trim($_POST['cup_feeding_comment']));
  $cup_feeding_superv_demo = mysqli_real_escape_string($conn,trim($_POST['cup_feeding_superv_demo']));
  $cup_feeding_initials = mysqli_real_escape_string($conn,trim($_POST['cup_feeding_initials']));
  $cup_feeding_comments_second = mysqli_real_escape_string($conn,trim($_POST['cup_feeding_comments_second']));
  $prepare_milk_demo = mysqli_real_escape_string($conn,trim($_POST['prepare_milk_demo']));
  $prepare_milk_initial = mysqli_real_escape_string($conn,trim($_POST['prepare_milk_initial']));
  $prepare_milk_comment = mysqli_real_escape_string($conn,trim($_POST['prepare_milk_comment']));
  $prepare_milk_superv_demo = mysqli_real_escape_string($conn,trim($_POST['prepare_milk_superv_demo']));
  $prepare_milk_initials = mysqli_real_escape_string($conn,trim($_POST['prepare_milk_initials']));
  $prepare_milik_comment_second = mysqli_real_escape_string($conn,trim($_POST['prepare_milik_comment_second']));
  $nappy_change_demo = mysqli_real_escape_string($conn,trim($_POST['nappy_change_demo']));
  $nappy_change_initial = mysqli_real_escape_string($conn,trim($_POST['nappy_change_initial']));
  $nappy_change_comment = mysqli_real_escape_string($conn,trim($_POST['nappy_change_comment']));
  $nappy_change_superv_demo = mysqli_real_escape_string($conn,trim($_POST['nappy_change_superv_demo']));
  $nappy_change_initials = mysqli_real_escape_string($conn,trim($_POST['nappy_change_initials']));
  $nappy_change_comment_second = mysqli_real_escape_string($conn,trim($_POST['nappy_change_comment_second']));
  $bath_demo = mysqli_real_escape_string($conn,trim($_POST['bath_demo']));
  $bath_initial = mysqli_real_escape_string($conn,trim($_POST['bath_initial']));
  $bath_comment = mysqli_real_escape_string($conn,trim($_POST['bath_comment']));
  $bath_superv_demo = mysqli_real_escape_string($conn,trim($_POST['bath_superv_demo']));
  $bath_initials = mysqli_real_escape_string($conn,trim($_POST['bath_initials']));
  $bath_comment_second = mysqli_real_escape_string($conn,trim($_POST['bath_comment_second']));
  $cord_care_second_demo = mysqli_real_escape_string($conn,trim($_POST['cord_care_second_demo']));
  $cord_care_initial = mysqli_real_escape_string($conn,trim($_POST['cord_care_initial']));
  $cord_care_comment = mysqli_real_escape_string($conn,trim($_POST['cord_care_comment']));
  $cord_care_superv_demo = mysqli_real_escape_string($conn,trim($_POST['cord_care_superv_demo']));
  $cord_care_initials = mysqli_real_escape_string($conn,trim($_POST['cord_care_initials']));
  $cord_care_comment_second = mysqli_real_escape_string($conn,trim($_POST['cord_care_comment_second']));
  $give_medication_demo = mysqli_real_escape_string($conn,trim($_POST['give_medication_demo']));
  $give_medication_initial = mysqli_real_escape_string($conn,trim($_POST['give_medication_initial']));
  $give_medication_comments = mysqli_real_escape_string($conn,trim($_POST['give_medication_comments']));
  $give_medication_superv_demo = mysqli_real_escape_string($conn,trim($_POST['give_medication_superv_demo']));
  $give_medication_initials = mysqli_real_escape_string($conn,trim($_POST['give_medication_initials']));
  $give_medication_comment_second = mysqli_real_escape_string($conn,trim($_POST['give_medication_comment_second']));
  $behaviour_infant_demo = mysqli_real_escape_string($conn,trim($_POST['behaviour_infant_demo']));
  $behaviour_infant_initial = mysqli_real_escape_string($conn,trim($_POST['behaviour_infant_initial']));
  $behaviour_infant_behaviour_infant_comment = mysqli_real_escape_string($conn,trim($_POST['behaviour_infant_behaviour_infant_comment']));
  $behaviour_infant_superv_demo = mysqli_real_escape_string($conn,trim($_POST['behaviour_infant_superv_demo']));
  $behaviour_infant_initials = mysqli_real_escape_string($conn,trim($_POST['behaviour_infant_initials']));
  $behaviour_infant_comment_second = mysqli_real_escape_string($conn,trim($_POST['behaviour_infant_comment_second']));
  $bcg_one_top = mysqli_real_escape_string($conn,trim($_POST['bcg_one_top']));
  $bcg_second_top = mysqli_real_escape_string($conn,trim($_POST['bcg_second_top']));
  $oral_one = mysqli_real_escape_string($conn,trim($_POST['oral_one']));
  $oral_second = mysqli_real_escape_string($conn,trim($_POST['oral_second']));
  $injection_one = mysqli_real_escape_string($conn,trim($_POST['injection_one']));
  $injection_second = mysqli_real_escape_string($conn,trim($_POST['injection_second']));
  $hepatis_one = mysqli_real_escape_string($conn,trim($_POST['hepatis_one']));
  $hepatis_second = mysqli_real_escape_string($conn,trim($_POST['hepatis_second']));
  $date_and_time = mysqli_real_escape_string($conn,trim($_POST['date_and_time']));
  $signature = mysqli_real_escape_string($conn,trim($_POST['signature']));
  $bcg_vacination = mysqli_real_escape_string($conn,trim($_POST['bcg_vacination']));
  $bcg_batch_no = mysqli_real_escape_string($conn,trim($_POST['bcg_batch_no']));
  $bcg_data_given = mysqli_real_escape_string($conn,trim($_POST['bcg_data_given']));
  $bcg_signature = mysqli_real_escape_string($conn,trim($_POST['bcg_signature']));
  $oral_polio = mysqli_real_escape_string($conn,trim($_POST['oral_polio']));
  $oral_polio_batch_no = mysqli_real_escape_string($conn,trim($_POST['oral_polio_batch_no']));
  $oral_polio_data_given = mysqli_real_escape_string($conn,trim($_POST['oral_polio_data_given']));
  $oral_polio_signature = mysqli_real_escape_string($conn,trim($_POST['oral_polio_signature']));
  $hepatitisb = mysqli_real_escape_string($conn,trim($_POST['hepatitisb']));
  $hepatitis_batch_no = mysqli_real_escape_string($conn,trim($_POST['hepatitis_batch_no']));
  $hepatitis_data_given = mysqli_real_escape_string($conn,trim($_POST['hepatitis_data_given']));
  $hepatitis_signature = mysqli_real_escape_string($conn,trim($_POST['hepatitis_signature']));
  $yes_notification = mysqli_real_escape_string($conn,trim($_POST['yes_notification']));
  $no_notification = mysqli_real_escape_string($conn,trim($_POST['no_notification']));
  $birth_notification = mysqli_real_escape_string($conn,trim($_POST['birth_notification']));
  $date_of_discharge = mysqli_real_escape_string($conn,trim($_POST['date_of_discharge']));
  $discharge_weight = mysqli_real_escape_string($conn,trim($_POST['discharge_weight']));
  $discharge_head_circumference = mysqli_real_escape_string($conn,trim($_POST['discharge_head_circumference']));
  $Registration_ID = mysqli_real_escape_string($conn,trim($_POST['Registration_ID']));
  $Admision_ID = mysqli_real_escape_string($conn,trim($_POST['Admision_ID']));
  $breast2_feeding = mysqli_real_escape_string($conn,trim($_POST['breast2_feeding']));
  $breast2_feeding_initials = mysqli_real_escape_string($conn,trim($_POST['breast2_feeding_initials']));
  $breast2_feeding_comments = mysqli_real_escape_string($conn,trim($_POST['breast2_feeding_comments']));
  $formular_feeding = mysqli_real_escape_string($conn,trim($_POST['formular_feeding']));
  $formular_feeding_initials = mysqli_real_escape_string($conn,trim($_POST['formular_feeding_initials']));
  $formular_feeding_comments = mysqli_real_escape_string($conn,trim($_POST['formular_feeding_comments']));

// die("INSERT INTO tbl_assessment2(
//   Employee_ID,Registration_ID,Admision_ID, breast_feeding_demo, breast_feeding_initial, breast_feeding_comments,
//   breast_feeding_superv_demo,breast_feeding_initials,breast_feeding_comments_second,cup_feeding_demo,
//   cup_feeding_initial,cup_feeding_comment,cup_feeding_superv_demo,cup_feeding_initials,cup_feeding_comments_second,
//   prepare_milk_demo,prepare_milk_initial,prepare_milk_comment,prepare_milk_superv_demo,prepare_milk_initials,
//   prepare_milik_comment_second,nappy_change_demo,nappy_change_initial,nappy_change_comment,nappy_change_superv_demo,
//   nappy_change_initials,nappy_change_comment_second,bath_demo,bath_initial,bath_comment,bath_superv_demo,
//   bath_initials,bath_comment_second,cord_care_second_demo,breast2_feeding,breast2_feeding_initials,breast2_feeding_comments,
//   formular_feeding,formular_feeding_initials,formular_feeding_comments)
//   VALUES('$Employee_ID','$Registration_ID','$Admision_ID','$breast_feeding_demo','$breast_feeding_initial',
//   '$breast_feeding_comments','$breast_feeding_superv_demo','$breast_feeding_initials','$breast_feeding_comments_second',
//   '$cup_feeding_demo','$cup_feeding_initial','$cup_feeding_comment','$cup_feeding_superv_demo','$cup_feeding_initials',
//   '$cup_feeding_comments_second','$prepare_milk_demo','$prepare_milk_initial','$prepare_milk_comment','$prepare_milk_superv_demo',
//   '$prepare_milk_initials',
//   '$prepare_milik_comment_second','$nappy_change_demo','$nappy_change_initial','$nappy_change_comment','$nappy_change_superv_demo',
//   '$nappy_change_initials',
//   '$nappy_change_comment_second','$bath_demo','$bath_initial','$bath_comment','$bath_superv_demo','$bath_initials','$bath_comment_second',
//   '$cord_care_second_demo','$breast2_feeding','$breast2_feeding_initials','$breast2_feeding_comments','$formular_feeding',
//   '$formular_feeding_initials','$formular_feeding_comments')");

$insert_into_assessment2 = "INSERT INTO tbl_assessment2(
  Employee_ID,Registration_ID,Admision_ID, breast_feeding_demo, breast_feeding_initial, breast_feeding_comments,
  breast_feeding_superv_demo,breast_feeding_initials,breast_feeding_comments_second,cup_feeding_demo,
  cup_feeding_initial,cup_feeding_comment,cup_feeding_superv_demo,cup_feeding_initials,cup_feeding_comments_second,
  prepare_milk_demo,prepare_milk_initial,prepare_milk_comment,prepare_milk_superv_demo,prepare_milk_initials,
  prepare_milik_comment_second,nappy_change_demo,nappy_change_initial,nappy_change_comment,nappy_change_superv_demo,
  nappy_change_initials,nappy_change_comment_second,bath_demo,bath_initial,bath_comment,bath_superv_demo,
  bath_initials,bath_comment_second,cord_care_second_demo,breast2_feeding,breast2_feeding_initials,breast2_feeding_comments,
  formular_feeding,formular_feeding_initials,formular_feeding_comments)
  VALUES('$Employee_ID','$Registration_ID','$Admision_ID','$breast_feeding_demo','$breast_feeding_initial',
  '$breast_feeding_comments','$breast_feeding_superv_demo','$breast_feeding_initials','$breast_feeding_comments_second',
  '$cup_feeding_demo','$cup_feeding_initial','$cup_feeding_comment','$cup_feeding_superv_demo','$cup_feeding_initials',
  '$cup_feeding_comments_second','$prepare_milk_demo','$prepare_milk_initial','$prepare_milk_comment','$prepare_milk_superv_demo',
  '$prepare_milk_initials',
  '$prepare_milik_comment_second','$nappy_change_demo','$nappy_change_initial','$nappy_change_comment','$nappy_change_superv_demo',
  '$nappy_change_initials',
  '$nappy_change_comment_second','$bath_demo','$bath_initial','$bath_comment','$bath_superv_demo','$bath_initials','$bath_comment_second',
  '$cord_care_second_demo','$breast2_feeding','$breast2_feeding_initials','$breast2_feeding_comments','$formular_feeding',
  '$formular_feeding_initials','$formular_feeding_comments')";



if ($result = mysqli_query($conn,$insert_into_assessment2)) {

  $assessment_ID = mysqli_insert_id($conn);

  $insert_into_assessment2b = "INSERT INTO tbl_assessment2b(
  assessment_ID,Registration_ID,cord_care_initial, cord_care_comment,  cord_care_superv_demo,  cord_care_initials,  cord_care_comment_second,
  give_medication_demo,  give_medication_initial,  give_medication_comments,  give_medication_superv_demo,  give_medication_initials,
  give_medication_comment_second,  behaviour_infant_demo,  behaviour_infant_initial,  behaviour_infant_behaviour_infant_comment, behaviour_infant_superv_demo,
  behaviour_infant_initials,  behaviour_infant_comment_second,  bcg_one_top,  bcg_second_top,  oral_one,  oral_second,  injection_one,  injection_second,
  hepatis_one,  hepatis_second,  date_and_time,  signature,  bcg_vacination,  bcg_batch_no,  bcg_data_given,  bcg_signature,  oral_polio,  oral_polio_batch_no,
  oral_polio_data_given,  oral_polio_signature,  hepatitisb,  hepatitis_batch_no,  hepatitis_data_given,  hepatitis_signature,  yes_notification,no_notification,
  birth_notification,  date_of_discharge,  discharge_weight,  discharge_head_circumference
  ) VALUES(
    '$assessment_ID',
    '$Registration_ID',
   '$cord_care_initial',
  '$cord_care_comment',
  '$cord_care_superv_demo',
  '$cord_care_initials',
  '$cord_care_comment_second',
  '$give_medication_demo',
  '$give_medication_initial',
  '$give_medication_comments',
  '$give_medication_superv_demo',
  '$give_medication_initials',
  '$give_medication_comment_second','$behaviour_infant_demo',
  '$behaviour_infant_initial','$behaviour_infant_behaviour_infant_comment',
  '$behaviour_infant_superv_demo','$behaviour_infant_initials',
  '$behaviour_infant_comment_second','$bcg_one_top','$bcg_second_top','$oral_one',
  '$oral_second','$injection_one','$injection_second','$hepatis_one',
  '$hepatis_second','$date_and_time','$signature','$bcg_vacination',
  '$bcg_batch_no','$bcg_data_given','$bcg_signature','$oral_polio',
  '$oral_polio_batch_no','$oral_polio_data_given','$oral_polio_signature',
  '$hepatitisb','$hepatitis_batch_no','$hepatitis_data_given',
  '$hepatitis_signature','$yes_notification','$no_notification',
  '$birth_notification','$date_of_discharge','$discharge_weight',
  '$discharge_head_circumference')";

  $query = mysqli_query($conn,$insert_into_assessment2b);

  if ($query) {
      echo "Saved Successfully!";
  }else{
    die('Failed to save records'.mysqli_error($conn));
  }

}else {
  die('Error'.mysqli_error($conn));
}
}
//************************************ end save *************************************************************



// *********************************** print data ***********************************************************
$registration_id = 0;
if ($_GET['action'] == 'print_data') {

   $registration_id =  $_GET['Registration_ID'];

   $select_emp = mysqli_query($conn,"SELECT e.Employee_ID,e.Employee_Name,a.Employee_ID,a.Registration_ID
                                     FROM tbl_assessment2 a INNER JOIN tbl_employee e
                                     ON a.Employee_ID = e.Employee_ID
                                     WHERE a.Registration_ID = '$registration_id' ORDER BY saved_time DESC LIMIT 1");

   $E_Name = mysqli_fetch_assoc($select_emp)['Employee_Name'];



                $breast_feeding_demo = "";
                $select2 = "SELECT * FROM tbl_assessment2 WHERE Registration_ID = '$registration_id' ORDER BY saved_time ASC LIMIT 1";
                $select2_execute = mysqli_query($conn,$select2);
                while ($r = mysqli_fetch_assoc($select2_execute)) {
                     $breast_feeding_demo = $r['breast_feeding_demo'];
                     $breast_feeding_initial = $r['breast_feeding_initial'];
                     $breast_feeding_comments = $r['breast_feeding_comments'];
                     $breast_feeding_superv_demo = $r['breast_feeding_superv_demo'];
                     $breast_feeding_initials = $r['breast_feeding_initials'];
                     $breast_feeding_comments_second = $r['breast_feeding_comments_second'];
                     $cup_feeding_demo = $r['cup_feeding_demo'];
                     $cup_feeding_initial = $r['cup_feeding_initial'];
                     $cup_feeding_comment = $r['cup_feeding_comment'];
                     $cup_feeding_superv_demo = $r['cup_feeding_superv_demo'];
                     $cup_feeding_initials = $r['cup_feeding_initials'];
                     $cup_feeding_comments_second = $r['cup_feeding_comments_second'];
                     $prepare_milk_demo = $r['prepare_milk_demo'];
                     $prepare_milk_initial = $r['prepare_milk_initial'];
                     $prepare_milk_comment = $r['prepare_milk_comment'];
                     $prepare_milk_superv_demo = $r['prepare_milk_superv_demo'];
                     $prepare_milk_initials = $r['prepare_milk_initials'];
                     $prepare_milik_comment_second = $r['prepare_milik_comment_second'];
                     $nappy_change_demo = $r['nappy_change_demo'];
                     $nappy_change_initial = $r['nappy_change_initial'];
                     $nappy_change_comment = $r['nappy_change_comment'];
                     $nappy_change_superv_demo = $r['nappy_change_superv_demo'];
                     $nappy_change_initials = $r['nappy_change_initials'];
                     $nappy_change_comment_second = $r['nappy_change_comment_second'];
                     $bath_initial = $r['bath_initial'];
                     $bath_demo = $r['bath_demo'];
                     $bath_comment = $r['bath_comment'];
                     $bath_superv_demo = $r['bath_superv_demo'];
                     $bath_initials = $r['bath_initials'];
                     $bath_comment_second = $r['bath_comment_second'];
                     $cord_care_second_demo = $r['cord_care_second_demo'];
                     $breast2_feeding = $r['breast2_feeding'];
                     $breast2_feeding_initials = $r['breast2_feeding_initials'];
                     $breast2_feeding_comments = $r['breast2_feeding_comments'];
                     $formular_feeding = $r['formular_feeding'];
                     $formular_feeding_initials = $r['formular_feeding_initials'];
                     $formular_feeding_comments = $r['formular_feeding_comments'];




       }


       $select2b = "SELECT * FROM tbl_assessment2b WHERE Registration_ID = '$registration_id' ORDER BY saved_time ASC LIMIT 1";
       $select2b_execute = mysqli_query($conn,$select2b);
       while ($r2 = mysqli_fetch_assoc($select2b_execute)) {
       $cord_care_initial = $r2['cord_care_initial'];
       $cord_care_comment = $r2['cord_care_comment'];
       $cord_care_superv_demo = $r2['cord_care_superv_demo'];
       $cord_care_initials = $r2['cord_care_initials'];
       $cord_care_comment_second = $r2['cord_care_comment_second'];
       $give_medication_demo = $r2['give_medication_demo'];
       $give_medication_initial = $r2['give_medication_initial'];
       $give_medication_comments = $r2['give_medication_comments'];
       $give_medication_superv_demo = $r2['give_medication_superv_demo'];
       $give_medication_initials = $r2['give_medication_initials'];
       $give_medication_comment_second = $r2['give_medication_comment_second'];
       $behaviour_infant_demo = $r2['behaviour_infant_demo'];
       $behaviour_infant_initial = $r2['behaviour_infant_initial'];
       $behaviour_infant_behaviour_infant_comment = $r2['behaviour_infant_behaviour_infant_comment'];
       $behaviour_infant_superv_demo = $r2['behaviour_infant_superv_demo'];
       $behaviour_infant_initials = $r2['behaviour_infant_initials'];
       $behaviour_infant_comment_second = $r2['behaviour_infant_comment_second'];
       $bcg_one_top = $r2['bcg_one_top'];
       $bcg_second_top = $r2['bcg_second_top'];
       $oral_one = $r2['oral_one'];
       $oral_second = $r2['oral_second'];
       $injection_one = $r2['injection_one'];
       $injection_second = $r2['injection_second'];
       $hepatis_one = $r2['hepatis_one'];
       $hepatis_second = $r2['hepatis_second'];
       $date_and_time = $r2['date_and_time'];
       $signature = $r2['signature'];
       $bcg_vacination = $r2['bcg_vacination'];
       $bcg_batch_no = $r2['bcg_batch_no'];
       $bcg_data_given = $r2['bcg_data_given'];
       $bcg_signature = $r2['bcg_signature'];
       $oral_polio = $r2['oral_polio'];
       $oral_polio_batch_no = $r2['oral_polio_batch_no'];
       $oral_polio_data_given = $r2['oral_polio_data_given'];
       $oral_polio_signature = $r2['oral_polio_signature'];
       $hepatitisb = $r2['hepatitisb'];
       $hepatitis_batch_no = $r2['hepatitis_batch_no'];
       $hepatitis_data_given = $r2['hepatitis_data_given'];
       $hepatitis_signature = $r2['hepatitis_signature'];
       $yes_notification = $r2['yes_notification'];
       $no_notification = $r2['no_notification'];
       $birth_notification = $r2['birth_notification'];
       $date_of_discharge = $r2['date_of_discharge'];
       $discharge_weight = $r2['discharge_weight'];
       $discharge_head_circumference = $r2['discharge_head_circumference'];
     }


     $htm = ' <style media="screen">
        table{
          border-collapse: collapse;
          border: none !important;
        }
         tr, td{
          border:none !important;
        }

        .title{
          text-align: right !important;
        }

        .btn-title{
          width: 70%;

        }
      </style>';

       $htm .= "<center><img src='../branchBanner/branchBanner.png' width='100%' ></center>";

         $htm .= '<fieldset>
           <legend align=center style="font-weight:bold">ASSESSMENT</legend>
           <center align="center">

           <table style="width:100%" border="1">
                <thead>
                <tr>
                  <th>PARENT TEACHING</th>
                  <th>Demo</th>
                  <th>Initial</th>
                  <th>Comments</th>
                  <th>Superv Demo</th>
                  <th>Initials</th>
                  <th>Comments</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th class="title">Breast Feeding</th>
                    <td>'.$breast_feeding_demo.'</td>

                    <td>'.$breast_feeding_initial.'</td>
                    <td>'.$breast_feeding_comments.'</td>
                    <td>'.$breast_feeding_superv_demo.'</td>
                    <td>'.$breast_feeding_initials.'</td>
                    <td>'.$breast_feeding_comments_second.' </td>

                  </tr>
                  <tr>
                    <th class="title">Cup Feeding</th>
                    <td>'.$cup_feeding_demo.'</td>
                    <td>'.$cup_feeding_initial.'</td>
                    <td>'.$cup_feeding_comment.'</td>
                    <td>'.$cup_feeding_superv_demo.'</td>
                    <td>'.$cup_feeding_initials.'</td>
                    <td>'.$cup_feeding_comments_second.'</td>

                  </tr>
                  <tr>
                    <th class="title">Preparation Of Milk</th>
                    <td>'.$prepare_milk_demo.'</td>
                    <td>'.$prepare_milk_initial.'</td>
                    <td>'.$prepare_milk_comment.'</td>
                    <td>'.$prepare_milk_superv_demo.'</td>
                    <td>'.$prepare_milk_initials.'</td>
                    <td>'.$prepare_milik_comment_second.'</td>
                  </tr>
                  <tr>
                    <th class="title">Nappy Change</th>
                    <td>'.$nappy_change_demo.'</td>
                    <td>'.$nappy_change_initial.'</td>
                    <td>'.$nappy_change_comment.'</td>
                    <td>'.$nappy_change_superv_demo.'</td>
                    <td>'.$nappy_change_initials.'</td>
                    <td>'.$nappy_change_comment_second.' </td>
                  </tr>
                  <tr>
                    <th class="title">Bath</th>
                    <td>'.$bath_demo.'</td>
                    <td>'.$bath_initial.'</td>
                    <td>'.$bath_comment.'</td>
                    <td>'.$bath_superv_demo.' </td>
                    <td>'.$bath_initials.'</td>
                    <td>'.$bath_comment_second.' </td>
                  </tr>
                  <tr>
                    <th class="title">Cord Care</th>
                    <td>'.$cord_care_second_demo.'</td>
                    <td>'.$cord_care_initial.'</td>
                    <td>'.$cord_care_comment.'</td>
                    <td>'.$cord_care_superv_demo.'</td>
                    <td>'.$cord_care_initials.'</td>
                    <td>'.$cord_care_comment_second.'</td>
                  </tr>
                  <tr>
                    <th class="title">Giving Medication</th>
                    <td>'.$give_medication_demo.'</td>
                    <td>'.$give_medication_initial.'</td>
                    <td>'.$give_medication_comments.'</td>
                    <td>'.$give_medication_superv_demo.'</td>
                    <td>'.$give_medication_initials.'</td>
                    <td>'.$give_medication_comment_second.'</td>
                  </tr>
                  <tr>
                    <th class="title">Discussion</th>
                    <th style="text-align:center">Date</th>
                    <th style="text-align:center">Initials</th>

                    <th colspan="5" style="text-align:center">Comments</th>
                  </tr>

                  <tr>
                    <th class="title">Behaviour of Infant</th>
                    <td>'.$behaviour_infant_demo.'</td>
                    <td>'.$behaviour_infant_initial.'</td>
                    <td colspan="4">'.$behaviour_infant_behaviour_infant_comment.' </td>
                  </tr>
                  <tr>
                    <th class="title">Breast Feeding</th>
                    <td>'.$breast2_feeding.'</td>
                    <td>'.$breast2_feeding_initials.'</td>
                    <td colspan="4">'.$breast2_feeding_comments.'</td>
                  </tr>
                  <tr>
                    <th class="title">Formula Feeding</th>
                    <td>'.$formular_feeding.'</td>
                    <td>'.$formular_feeding_initials.'</td>
                    <td colspan="4">'.$formular_feeding_comments.'</td>
                  </tr>
                  <tr>
                    <th colspan="7"><b style="font-size:16px;">I here by give concert to have my baby immunized against the following:</b></th>
                  </tr>

                  <tr>
                    <th class="title">B.C.G 1</th>
                    <td>'.$bcg_one_top.'</td>
                    <th class="title">B.C.G 2</th>
                    <td>'.$bcg_second_top.'</td>
                  </tr>
                  <tr>
                    <th class="title">Polio Vaccine</th>
                  </tr>
                  <tr>
                    <th class="title">oral one</th>
                    <td>'.$oral_one.'</td>
                    <th class="title">oral second</th>
                    <td>'.$oral_second.'</td>
                  </tr>
                  <tr>
                    <th class="title">Injection one</th>
                    <td>'.$injection_one.'</td>
                    <th class="title">Injection second</th>
                    <td>'.$injection_second.'</td>
                  </tr>
                  <tr>
                    <th class="title">Hepatitis one</th>
                    <td>'.$hepatis_one.'</td>
                    <th class="title">Hepatitis second</th>
                    <td>'.$hepatis_second.'</td>
                  </tr>
                  <tr>
                    <th class="title">Date</th>
                    <td>'.$date_and_time.'</td>
                    <th class="title">Signature</th>
                    <td>'.$signature.'</td>
                  </tr>
                  <tr>
                  </tr>
                  <tr>
                    <th colspan="2">Vaccinations</th>
                    <th class="title">Batch No.</th>
                    <th class="title">Date Given</th>
                    <th class="title">Signature</th>
                  </tr>
                  <tr>
                    <th class="title">B.C.G Vacination</th>
                    <td>'.$bcg_vacination.'</td>
                    <td>'.$bcg_batch_no.'</td>
                    <td>'.$bcg_data_given.'</td>
                    <td>'.$bcg_signature.'</td>
                  </tr>
                  <tr>
                    <th class="title">Oral Polio</th>
                    <td>'.$oral_polio.'</td>
                    <td>'.$oral_polio_batch_no.'</td>
                    <td>'.$oral_polio_data_given.'</td>
                    <td>'.$oral_polio_signature.'</td>
                  </tr>
                  <tr>
                    <th class="title">Hepatitis B</th>
                    <td>'.$hepatitisb.'</td>
                    <td>'.$hepatitis_batch_no.'</td>
                    <td>'.$hepatitis_data_given.'</td>
                    <td>'.$hepatitis_signature.'</td>
                  </tr>
                  <tr>
                    <th class="title">Notifiaction of Birth</th>
                    <th class="title">YES</th>
                    <td>'.$yes_notification.'</td>
                    <th class="title">No</th>
                    <td>'.$no_notification.'</td>
                    <th class="title">Birth Notification(B)No.</th>
                    <td>'.$birth_notification.'</td>
                  </tr>
                  <tr>
                    <th class="title">Date of Discharge</th>
                    <td>'.$date_of_discharge.'</td>
                  </tr>
                  <tr>
                    <th class="title">Discharge Weight</th>
                    <td>'.$discharge_weight.'</td>
                  </tr>
                  <tr>
                    <th class="title">Discharge Head Circumference</th>
                    <td>'.$discharge_head_circumference.'</td>
                  </tr>
                </tbody>

            </table>
      </center>
     </fieldset>';




   $mpdf = new mPDF('s', 'A4');
   $mpdf->SetDisplayMode('fullpage');
   $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
   $mpdf->WriteHTML($htm);
   $mpdf->Output();
   exit;


 }








 ?>
