<?php
session_start();
include("./includes/connection.php");
include("MPDF/mpdf.php");

//header("Access-Control-Allow-Origin: *");
if(strpos($_SERVER['HTTP_ORIGIN'], 'javascript') == false)
																			{
		header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
}

//**************************save general examination data ********************************************************************
$data=json_decode(file_get_contents("php://input"));


if ($data->action == 'save_general') {
    //echo "reached <br>";
    $Registration_ID = mysqli_real_escape_string($conn,trim($data->Registration_ID));
    $Employee_ID = mysqli_real_escape_string($conn,trim($data->Employee_ID));
    $consultation_id = mysqli_real_escape_string($conn,trim($data->consultation_id));
    $admission_id = mysqli_real_escape_string($conn,trim($data->admission_id));
    $Pale = mysqli_real_escape_string($conn,trim($data->Pale));
    $Jaundice = mysqli_real_escape_string($conn,trim($data->Jaundice));
    $Oedema = mysqli_real_escape_string($conn,trim($data->Oedema));
    $Dyspnocic = mysqli_real_escape_string($conn,trim($data->Dyspnocic));
    $Shape_of_abdomen = mysqli_real_escape_string($conn,trim($data->Shape_of_abdomen));
    $Previous_scar = mysqli_real_escape_string($conn,trim($data->Previous_scar));
    $Previous_scar_yes_times = mysqli_real_escape_string($conn,trim($data->Previous_scar_yes_times));
    //$Previous_scar = mysqli_real_escape_string($conn,trim($data->Previous_scar));
    $Bundles_ring = mysqli_real_escape_string($conn,trim($data->Bundles_ring));
    $Fetal_movement = mysqli_real_escape_string($conn,trim($data->Fetal_movement));
    $Skin_hanges = mysqli_real_escape_string($conn,trim($data->Skin_hanges));
    $FH = mysqli_real_escape_string($conn,trim($data->FH));
    $Number_of_fetus = mysqli_real_escape_string($conn,trim($data->Number_of_fetus));
    $Lie = mysqli_real_escape_string($conn,trim($data->Lie));
    $Presentation = mysqli_real_escape_string($conn,trim($data->Presentation));
    $Position = mysqli_real_escape_string($conn,trim($data->Position));
    $Head_level = mysqli_real_escape_string($conn,trim($data->Head_level));
    $Contraction = mysqli_real_escape_string($conn,trim($data->Contraction));
    $FHR = mysqli_real_escape_string($conn,trim($data->FHR));
    $Auscultation_strength = mysqli_real_escape_string($conn,trim($data->Auscultation_strength));
    $Auscultation_shape = mysqli_real_escape_string($conn,trim($data->Auscultation_shape));
    $State_of_vulva = mysqli_real_escape_string($conn,trim($data->State_of_vulva));
    $Pv_discharge = mysqli_real_escape_string($conn,trim($data->Pv_discharge));
    $Pv_discharge_yes_colour = mysqli_real_escape_string($conn,trim($data->Pv_discharge_yes_colour));
    $Pv_discharge_yes_smell = mysqli_real_escape_string($conn,trim($data->Pv_discharge_yes_smell));
    $State_of_vagina = mysqli_real_escape_string($conn,trim($data->State_of_vagina));
    $State_of_Cx_Soft = mysqli_real_escape_string($conn,trim($data->State_of_Cx_Soft));
    $State_of_Cx_Rigid = mysqli_real_escape_string($conn,trim($data->State_of_Cx_Rigid));
    $State_of_Cx_Thin = mysqli_real_escape_string($conn,trim($data->State_of_Cx_Thin));
    $State_of_Cx_Thick = mysqli_real_escape_string($conn,trim($data->State_of_Cx_Thick));
    $State_of_Cx_Swollen = mysqli_real_escape_string($conn,trim($data->State_of_Cx_Swollen));
    $Cervical_dilatation = mysqli_real_escape_string($conn,trim($data->Cervical_dilatation));
    $Affacement = mysqli_real_escape_string($conn,trim($data->Affacement));
    $Membranes = mysqli_real_escape_string($conn,trim($data->Membranes));
    $Membranes_Date_and_Time = mysqli_real_escape_string($conn,trim($data->Membranes_Date_and_Time));
    $liquor_colour = mysqli_real_escape_string($conn,trim($data->liquor_colour));
    $liquor_smell = mysqli_real_escape_string($conn,trim($data->liquor_smell));
    $Presenting_part = mysqli_real_escape_string($conn,trim($data->Presenting_part));
    $Breech_type = mysqli_real_escape_string($conn,trim($data->Breech_type));
    $Sacro_promontory = mysqli_real_escape_string($conn,trim($data->Sacro_promontory));
    $Sacral_curve = mysqli_real_escape_string($conn,trim($data->Sacral_curve));
    $Ischial_spine = mysqli_real_escape_string($conn,trim($data->Ischial_spine));
    $Pubic_angle = mysqli_real_escape_string($conn,trim($data->Pubic_angle));
    $Knuckles = mysqli_real_escape_string($conn,trim($data->Knuckles));
    $Remark = mysqli_real_escape_string($conn,trim($data->Remark));
    $Midwifery_Opinions = mysqli_real_escape_string($conn,trim($data->Midwifery_Opinions));
    $Plans = mysqli_real_escape_string($conn,trim($data->Plans));

  
    $save_data = "INSERT INTO tbl_general_examination(
      Registration_ID,Employee_ID,consultation_id,admission_id,
      Pale,Jaundice,Oedema,Dyspnocic,Shape_of_abdomen,Previous_scar,Previous_scar_yes_times,
      Bundles_ring,Fetal_movement,Skin_hanges,FH,Number_of_fetus,Lie,Presentation,Position,Head_level,
      Contraction,FHR,Auscultation_strength,Auscultation_shape,State_of_vulva,Pv_discharge,Pv_discharge_yes_colour,
      Pv_discharge_yes_smell,State_of_vagina,State_of_Cx_Soft,State_of_Cx_Rigid,State_of_Cx_Thin,State_of_Cx_Thick,
      State_of_Cx_Swollen,Cervical_dilatation,Affacement,Membranes,Membranes_Date_and_Time,liquor_colour,liquor_smell,
      Presenting_part,Breech_type,Sacro_promontory,Sacral_curve,Ischial_spine,Pubic_angle,Knuckles,Remark,Midwifery_Opinions,
      Plans
    )VALUES(
      '$Registration_ID','$Employee_ID','$consultation_id','$admission_id','$Pale','$Jaundice','$Oedema','$Dyspnocic',
      '$Shape_of_abdomen','$Previous_scar','$Previous_scar_yes_times','$Bundles_ring','$Fetal_movement','$Skin_hanges',
      '$FH','$Number_of_fetus','$Lie','$Presentation','$Position','$Head_level','$Contraction','$FHR','$Auscultation_strength',
      '$Auscultation_shape','$State_of_vulva','$Pv_discharge','$Pv_discharge_yes_colour','$Pv_discharge_yes_smell','$State_of_vagina',
      '$State_of_Cx_Soft','$State_of_Cx_Rigid','$State_of_Cx_Thin','$State_of_Cx_Thick','$State_of_Cx_Swollen',
      '$Cervical_dilatation','$Affacement','$Membranes','$Membranes_Date_and_Time','$liquor_colour','$liquor_smell','$Presenting_part',
      '$Breech_type','$Sacro_promontory','$Sacral_curve','$Ischial_spine','$Pubic_angle','$Knuckles','$Remark','$Midwifery_Opinions',
      '$Plans'
    )";

    $query = mysqli_query($conn,$save_data);

    if ($query) {
       echo "Saved Successfully!";
    }else{
      die("Failed: ".mysqli_error($conn));
    }


}
// ********************************************************* end *******************************************************************


// ************************************************* get examinition data **********************************************************
if ($_GET['action'] == 'get_exemination') {

  $Registration_ID = $_GET['Registration_ID'];
  $year = $_GET['year'];
  $select = "SELECT * FROM tbl_general_examination WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year'";

  $execute = mysqli_query($conn,$select);
  $output = array();
  while($r=mysqli_fetch_assoc($execute))
  {
    $output[] = $r;
  }
  echo json_encode($output);

}

// ************************************************** end **************************************************************************

 ?>
