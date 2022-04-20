<?php
include("../includes/connection.php");

if(isset($_POST['action']) == 'save_newborn')
{
  $Registration_ID = mysqli_real_escape_string($conn,trim($_POST['Registration_ID']));
  $Employee_ID = mysqli_real_escape_string($conn,trim($_POST['Employee_ID']));
  $Admision_ID = mysqli_real_escape_string($conn,trim($_POST['Admision_ID']));
  $consultation_id = mysqli_real_escape_string($conn,trim($_POST['consultation_id']));
  $temp = mysqli_real_escape_string($conn,trim($_POST['temp']));
  $respiration = mysqli_real_escape_string($conn,trim($_POST['respiration']));
  $respirationNo = mysqli_real_escape_string($conn,trim($_POST['respirationNo']));
  $difficultInBreathingType = mysqli_real_escape_string($conn,trim($_POST['difficultInBreathingType']));
  $comment = mysqli_real_escape_string($conn,trim($_POST['comment']));
  $skinCirculation = mysqli_real_escape_string($conn,trim($_POST['skinCirculation']));
  $pmtct = mysqli_real_escape_string($conn,trim($_POST['pmtct']));
  $deliveryDateAndTime = mysqli_real_escape_string($conn,trim($_POST['deliveryDateAndTime']));
  $movements = mysqli_real_escape_string($conn,trim($_POST['movements']));
  $others = mysqli_real_escape_string($conn,trim($_POST['others']));
  $evaluationStage = mysqli_real_escape_string($conn,trim($_POST['evaluationStage']));
  $maternalFactors = mysqli_real_escape_string($conn,trim($_POST['maternalFactors']));
  $apgar = mysqli_real_escape_string($conn,trim($_POST['apgar']));
  $birthWeight = mysqli_real_escape_string($conn,trim($_POST['birthWeight']));
  $maternalFactors2 = mysqli_real_escape_string($conn,trim($_POST['maternalFactors2']));
  $feeding = mysqli_real_escape_string($conn,trim($_POST['feeding']));
  $umbilicus = mysqli_real_escape_string($conn,trim($_POST['umbilicus']));
  $feeding2 = mysqli_real_escape_string($conn,trim($_POST['feeding2']));
  $umbilicus2 = mysqli_real_escape_string($conn,trim($_POST['umbilicus2']));
  $currentWeight = mysqli_real_escape_string($conn,trim($_POST['currentWeight']));


  //Respiration
  $inserted_respiration = "";
  if ($respiration == 'normal breathing') {
    $inserted_respiration = $respiration;

  }elseif ($respiration == 'difficult in breathing') {
    $inserted_respiration = $respiration."-".$difficultInBreathingType;

  }else{
    $inserted_respiration = $respirationNo;
  }





  if($evaluationStage == 'firstEvaluation')
  {
      $first_evalaution = "INSERT INTO tbl_newborn_triage_checklist_records(
                           Registration_ID,Employee_ID,Admision_ID,consultation_id,birth_weight,respiration,movements,
                           maternal_factors,others,temperature,skin_circulation,comment,pmtct,
                           evaluation_stage,apgar,delivery_date
                           )
                           VALUES('$Registration_ID','$Employee_ID','$Admision_ID','$consultation_id','$birthWeight','$inserted_respiration','$movements',
                           '$maternalFactors','$others','$temp','$skinCirculation','$comment','$pmtct','$evaluationStage',
                           '$apgar','$deliveryDateAndTime')";

      $execute1 = mysqli_query($conn,$first_evalaution);
      if($execute1)
      {
        echo "First Evaluation Recorded Successfully!";
      }else {
        echo "Failed to Save a Record".mysqli_error($conn);

      }

  }

  if($evaluationStage == 'secondEvaluation')
  {
    $second_evaluation = "INSERT INTO tbl_newborn_triage_checklist_records(
                          Registration_ID,Employee_ID,respiration,movements,
                          maternal_factors,feeding,others,temperature,skin_circulation,
                          evaluation_stage,umbilicus)
                          VALUES('$Registration_ID','$Employee_ID','$inserted_respiration','$movements',
                          '$maternalFactors2','$feeding','$others','$temp','$skinCirculation','$evaluationStage',
                          '$umbilicus')";

      $execute2 = mysqli_query($conn,$second_evaluation);
      if($execute2)
      {
          echo "Second Evaluation Recorded Successfully!";
      }else {
          echo "Failed to Save a Record".mysqli_error($conn);

      }

  }


  if($evaluationStage == 'thirdEvaluation')
  {
    $third_evaluation = "INSERT INTO tbl_newborn_triage_checklist_records(
                          Registration_ID,Employee_ID,respiration,movements,
                          feeding,others,temperature,skin_circulation,
                          evaluation_stage,umbilicus,current_weight)
                          VALUES('$Registration_ID','$Employee_ID','$inserted_respiration','$movements',
                          '$feeding2','$others','$temp','$skinCirculation','$evaluationStage',
                          '$umbilicus2','$currentWeight')";

      $execute3 = mysqli_query($conn,$third_evaluation);
      if($execute3)
      {
          echo "Third Evaluation Recorded Successfully!";
      }else {
          echo "Failed to Save a Record".mysqli_error($conn);

      }

  }



}
//end of save newborn



if (isset($_POST['action1'])) {

    if ($_POST['action1'] == 'save_observation') {

      $Registration_ID = mysqli_real_escape_string($conn,trim($_POST['Registration_ID']));
      $Employee_ID = mysqli_real_escape_string($conn,trim($_POST['Employee_ID']));
      $temp = mysqli_real_escape_string($conn,trim($_POST['temp']));
      $respiration = mysqli_real_escape_string($conn,trim($_POST['respiration']));
      $delivery_year = mysqli_real_escape_string($conn,trim($_POST['delivery_year']));
      $weight = mysqli_real_escape_string($conn,trim($_POST['weight']));
      $feeding = mysqli_real_escape_string($conn,trim($_POST['feeding']));
      $comment = mysqli_real_escape_string($conn,trim($_POST['comment']));
      $movements = mysqli_real_escape_string($conn,trim($_POST['movements']));


      $sql_observation = "INSERT INTO tbl_newborn_triage_checklist_observation(
                          Registration_ID,Employee_ID,delivery_year,temperature,respiration,weight,feeding,
                          movements,comment
                          )
                          VALUES('$Registration_ID','$Employee_ID','$delivery_year','$temp','$respiration',
                          '$weight','$feeding','$movements','$comment')";

       $save_observation = mysqli_query($conn,$sql_observation);
       if ($save_observation) {
         echo "Observation Records Added Successfully!";
       }
    }

}

 ?>
