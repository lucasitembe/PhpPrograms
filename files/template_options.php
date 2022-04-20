<?php
//receive the patient data according tp department department

if(isset($_GET)){
    $Registration_ID=$_GET['Registration_ID'];
    $Check_In_Type=$_GET['Check_In_Type'];
    $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
    $template=$_GET['template'];
    $Status_From=$_GET['Status_From'];
}else{
    $Registration_ID='';
    $Result_Datetime='';
    $Check_In_Type='';
    $Patient_Payment_ID='';
    $Status_From='';
    $template='';
}

//route patient file information to the corresponding template according to the department
//route to laboratory template
if($template == "Laboratory"){
    header("location:patient_file_laboratory_departmental_result.php?Registration_ID=$Registration_ID&Result_Datetime=".$_GET['Result_Datetime']."&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=".$_GET['Status_From']."&template=$template");
}

//route to doctors review
if($template == "Consultation"){
  header("location:patient_file_doctor_review.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}

if($template == "Cecap"){
    header("location:cecap_template.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}

//route to Rch Template
if($template == "Rch"){
    header("location:powercharts_antinatal.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}

//route to Dental Template
if($template == "Dental"){
    header("location:#dental_template.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}
//route to Radiology Template
if($template == "Radiology"){
    header("location:#radiology_template.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}
//route to Dialysis Template
if($template == "Dialysis"){
    header("location:#dialysis_template.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}
//route to Ear Template
if($template == "Ear"){
    header("location:#ear_template.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}
//route to Optical Template
if($template == "Optical"){
    header("location:#optical_template.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}


//route to Dressing Template
if($template == "Dressing"){
    header("location:#dressing_template.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}
//route to Hiv Template
if($template == "Hiv"){
    header("location:powercharts_hiv.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Result_Datetime=$Result_Datetime&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}
//route to Family Planning Template
if($template == "Family Planning"){
    header("location:powercharts_family_planning.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");  
}

//route to Family Planning Template
if($template == "Physiotherapy"){
    header("location:#physiotherapy_template.php?Registration_ID=$Registration_ID&consultation_ID=".$_GET['consultation_ID']."&Check_In_Type=$Check_In_Type&Patient_Payment_ID=$Patient_Payment_ID&Status_From=$Status_From&template=$template&section=$template");
}

else{
    echo "<h1>No ".$template." template defined.</h1>";
}

?>