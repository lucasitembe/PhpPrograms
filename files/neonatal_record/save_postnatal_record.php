<?php

include("../includes/connection.php");
if(isset($_POST['action']) == "save_postnatal_data")
{
// echo "<script> if(confirm('Are You Sure You Want Save')){";



    $registrationId = mysqli_real_escape_string($conn,trim($_POST['registrationId']));
    $parity = mysqli_real_escape_string($conn,trim($_POST['parity']));
    $living = mysqli_real_escape_string($conn,trim($_POST['living']));
    $pmtct = mysqli_real_escape_string($conn,trim($_POST['pmtct']));
    $DateAndTime = mysqli_real_escape_string($conn,trim($_POST['deliveryDateAndTime']));
    $niverapine = mysqli_real_escape_string($conn,trim($_POST['niverapine']));
    $niverapineTime = mysqli_real_escape_string($conn,trim($_POST['niverapineTime']));
    $babyCondition = mysqli_real_escape_string($conn,trim($_POST['babyCondition']));
    $anyAbnormalities = mysqli_real_escape_string($conn,trim($_POST['anyAbnormalities']));
    $apgarScore = mysqli_real_escape_string($conn,trim($_POST['apgarScore']));
    $bwt = mysqli_real_escape_string($conn,trim($_POST['bwt']));

    $bp = mysqli_real_escape_string($conn,trim($_POST['bp']));
    $temp = mysqli_real_escape_string($conn,trim($_POST['temp']));
    $pulse = mysqli_real_escape_string($conn,trim($_POST['pulse']));
    $restRate = mysqli_real_escape_string($conn,trim($_POST['restRate']));
    $physicalAppearance = mysqli_real_escape_string($conn,trim($_POST['physicalAppearance']));
    $hxOfFever = mysqli_real_escape_string($conn,trim($_POST['hxOfFever']));
    $pallor = mysqli_real_escape_string($conn,trim($_POST['pallor']));
    $pain = mysqli_real_escape_string($conn,trim($_POST['pain']));
    $plan = mysqli_real_escape_string($conn,trim($_POST['plan']));
    $anyComplains = mysqli_real_escape_string($conn,trim($_POST['complains']));
    $nipples = mysqli_real_escape_string($conn,trim($_POST['nipples']));
    $BreastSecreteMilk = mysqli_real_escape_string($conn,trim($_POST['BreastSecreteMilk']));
    $uteras = mysqli_real_escape_string($conn,trim($_POST['uteras']));
    $perinealPad = mysqli_real_escape_string($conn,trim($_POST['perinealPad']));
    $pvBleeding = mysqli_real_escape_string($conn,trim($_POST['pvBleeding']));
    $sourceOfBleeding1 = mysqli_real_escape_string($conn,trim($_POST['sourceOfBleeding']));
    $perinealTearType = mysqli_real_escape_string($conn,trim($_POST['perinealTearType']));
    $estimatedBloodLoss = mysqli_real_escape_string($conn,trim($_POST['estimatedBloodLoss']));
    $interpretation = mysqli_real_escape_string($conn,trim($_POST['interpretation']));
    //$complications = mysqli_real_escape_string($conn,trim($_POST['complications']));
    $aditionalFindings = mysqli_real_escape_string($conn,trim($_POST['aditionalFindings']));
    $complications = mysqli_real_escape_string($conn,trim($_POST['complications']));
    $employeeId = mysqli_real_escape_string($conn,trim($_POST['employeeId']));
    $fh = mysqli_real_escape_string($conn,trim($_POST['fh']));
    $wound = mysqli_real_escape_string($conn,trim($_POST['wound']));
    $modeOfDelivery = mysqli_real_escape_string($conn,trim($_POST['modeOfDelivery']));
    $ifcs = mysqli_real_escape_string($conn,trim($_POST['ifcs']));
    $Admision_ID = mysqli_real_escape_string($conn,trim($_POST['Admision_ID']));
    $consultation_id = mysqli_real_escape_string($conn,trim($_POST['consultation_id']));

    $modeOfDeliveryBaby = "";
    if($modeOfDelivery == 'cs')
    {
      $modeOfDeliveryBaby = $modeOfDelivery." ".$ifcs;
    }else {
      $modeOfDeliveryBaby = $modeOfDelivery;
    }
    //$date = date_format(create_date($deliveryDateAndTime,'YYYY-MM-DD HH:MI'));

    $sourceOfBleeding = $sourceOfBleeding1." ".$perinealTearType;



    $date=date_create($DateAndTime);
    $deliveryDateAndTime = date_format($date,"Y/m/d H:i");


    $sql = "INSERT INTO tbl_postnatal_after_delivery_records(
                                                            Registration_ID,Employee_ID,Admision_ID,consultation_id,Parity,Living,Pmtct,Niverapine,Niverapine_Time,mode_of_delivery,
                                                            Baby_Condition,Any_Abnormalities,Apgar_Score,bwt,
                                                            bp,temp,pulse,fh,wound,Rest_Rate,Physical_Appearance,
                                                            Hx_Of_Fever,Pallor,Pain,Plan,Complains,Nipples,
                                                            Breast_Secrete_Milk,Uteras,Perineal_Pad,Pv_Bleeding,
                                                            Source_Of_Bleeding,Estimated_Blood_Loss,Interpretation,
                                                            Complications,Additional_Findings,Date_Time_Of_Delivery
    )
    VALUES($registrationId,'$employeeId','$Admision_ID','$consultation_id','$parity','$living','$pmtct','$niverapine','$niverapineTime','$modeOfDeliveryBaby',
      '$babyCondition','$anyAbnormalities','$apgarScore','$bwt','$bp','$temp','$pulse','$fh','$wound',
    '$restRate','$physicalAppearance','$hxOfFever','$pallor','$pain','$plan','$anyComplains','$nipples','$BreastSecreteMilk','$uteras','$perinealPad','$pvBleeding',
    '$sourceOfBleeding','$estimatedBloodLoss','$interpretation','$complications','$aditionalFindings','$deliveryDateAndTime')";



          $query = mysqli_query($conn,$sql);
          if($query)
          {
            // echo "alert('Records Added Successfully!');";
            echo "Records Added Successfully!";
          } else{
            // echo "alert('Records Failed!');".mysqli_error($conn);
            echo "Records Failed!".mysqli_error($conn);
          }



// echo "}</script>";

}





if(isset($_POST['action2']) =="save_fluids")
{
    $bfuildGiven = mysqli_real_escape_string($conn,trim($_POST['bfuildGiven']));
    $employeeId = mysqli_real_escape_string($conn,trim($_POST['employeeId']));
    $registrationId = mysqli_real_escape_string($conn,trim($_POST['registrationId']));
    $mils = mysqli_real_escape_string($conn,trim($_POST['mils']));
    $plan = mysqli_real_escape_string($conn,trim($_POST['plan']));
    $dateAndTime = mysqli_real_escape_string($conn,trim($_POST['dateAndTime']));
    $postnatalaId = mysqli_real_escape_string($conn,trim($_POST['postnatalId']));
    $Admision_ID = mysqli_real_escape_string($conn,trim($_POST['Admision_ID']));
    $consultation_id = mysqli_real_escape_string($conn,trim($_POST['consultation_id']));



    $date=date_create($dateAndTime);
    $DateAndTime = date_format($date,"Y/m/d H:i");

    $sql = "INSERT INTO tbl_postnatal_fluids_and_blood_transfusion( Employee_ID,Registration_ID,Admision_ID,consultation_id,bfluid,mils,plan,date_and_time,Postnatal_ID)
            VALUES('$employeeId','$registrationId','$Admision_ID','$consultation_id','$bfuildGiven','$mils','$plan','$dateAndTime','$postnatalaId')";

            $query = mysqli_query($conn,$sql);

            if($query)
            {
              echo "Records Added Successfully!";
            }
            else{
              echo "Records Failed!".mysqli_error($conn);
            }


}



if(isset($_POST['action3']) == "save_urine")
{


  $employeeId = mysqli_real_escape_string($conn,trim($_POST['employeeId']));
  $registrationId = mysqli_real_escape_string($conn,trim($_POST['registrationId']));
  $dateAndTime = mysqli_real_escape_string($conn,trim($_POST['dateAndTime']));
  $postnatalaId = mysqli_real_escape_string($conn,trim($_POST['postnatalId']));
  $plan = mysqli_real_escape_string($conn,trim($_POST['plan']));
  $color = mysqli_real_escape_string($conn,trim($_POST['colord']));
  $amount = mysqli_real_escape_string($conn,trim($_POST['amount']));
  $Admision_ID = mysqli_real_escape_string($conn,trim($_POST['Admision_ID']));
  $consultation_id = mysqli_real_escape_string($conn,trim($_POST['consultation_id']));

  // $date=date_create($dateAndTime);
  // $DateAndTime = date_format($date,"Y/m/d H:i");


  $sql = "INSERT INTO tbl_postnatal_urine_output_monitoring(Employee_ID,Registration_ID,Admision_ID,consultation_id,Postnatal_ID,amount,color,plan,date_and_time)
          VALUES('$employeeId','$registrationId','$Admision_ID','$consultation_id','$postnatalaId','$amount','$color','$plan','$dateAndTime')";

  $query = mysqli_query($conn,$sql);

  if($query)
  {
    echo "Records Added Successfully!";
  }
  else{
    echo "Records Failed!".mysqli_error($conn);
  }
}
 ?>
