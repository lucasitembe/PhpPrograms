<?php
require_once('includes/connection.php');


if (isset($_POST['field_type'])) {
    $field_type = $_POST['field_type'];
} else {
    $field_type = '';
}
if (isset($_POST['Tumour'])) {
    $Tumour = $_POST['Tumour'];
} else {
    $Tumour = '';
}
if (isset($_POST['Skin_length'])) {
    $Skin_length = $_POST['Skin_length'];
} else {
    $Skin_length = '';
}
if (isset($_POST['Skin'])) {
    $Skin = $_POST['Skin'];
} else {
    $Skin = '';
}
if (isset($_POST['Depth'])) {
    $Depth = $_POST['Depth'];
} else {
    $Depth = '';
}
if (isset($_POST['SSD'])) {
    $SSD = $_POST['SSD'];
} else {
    $SSD = '';
}
if (isset($_POST['Gantry'])) {
    $Gantry = $_POST['Gantry'];
} else {
    $Gantry = '';
}
if (isset($_POST['Coll'])) {
    $Coll = $_POST['Coll'];
} else {
    $Coll = '';
}
if (isset($_POST['Cough'])) {
    $Cough = $_POST['Cough'];
} else {
    $Cough = '';
}

if (isset($_POST['body_position'])) {
    $body_position = $_POST['body_position'];
} else {
    $body_position = '';
}
if (isset($_POST['head_position'])) {
    $head_position = $_POST['head_position'];
} else {
    $head_position = '';
}
if (isset($_POST['number_alphabet'])) {
    $number_alphabet = $_POST['number_alphabet'];
} else {
    $number_alphabet = '';
}
if (isset($_POST['arm_position'])) {
    $arm_position = $_POST['arm_position'];
} else {
    $arm_position = '';
}
if (isset($_POST['leg_position'])) {
    $leg_position = $_POST['leg_position'];
} else {
    $leg_position = '';
}
if (isset($_POST['blocks'])) {
    $blocks = $_POST['blocks'];
} else {
    $blocks = '';
}
if (isset($_POST['technique'])) {
    $technique = $_POST['technique'];
} else {
    $technique = '';
}
if (isset($_POST['separation'])) {
    $separation = $_POST['separation'];
} else {
    $separation = '';
}
if (isset($_POST['depth'])) {
    $depth = $_POST['depth'];
} else {
    $depth = '';
}
if (isset($_POST['number_of_site'])) {
    $number_of_site = $_POST['number_of_site'];
} else {
    $number_of_site = '';
}
if (isset($_POST['number_fields'])) {
    $number_fields = $_POST['number_fields'];
} else {
    $number_fields = '';
}
if (isset($_POST['number_phases'])) {
   $number_phases = $_POST['number_phases'];
} else {
   $number_phases = '';
}
if (isset($_POST['Radiotherapy_ID'])) {
    $Radiotherapy_ID = $_POST['Radiotherapy_ID'];
 } else {
    $Radiotherapy_ID = '';
 }
if (isset($_POST['Registration_ID'])) {
    $Registration_ID = $_POST['Registration_ID'];
} else {
    $Registration_ID = '';
}
if (isset($_POST['Employee_ID'])) {
    $Employee_ID = $_POST['Employee_ID'];
} else {
    $Employee_ID = '';
}
if (isset($_POST['Diagnosis'])) {
    $Diagnosis = $_POST['Diagnosis'];
} else {
    $Diagnosis = '';
}
if (isset($_POST['name_of_site'])) {
    $name_of_site = $_POST['name_of_site'];
} else {
    $name_of_site = '';
}

$Mask = $_POST['Mask'];
$number_Mask = $_POST['number_Mask'];

$Comment = mysqli_real_escape_string($conn,str_replace("'", "&#39;", $_POST['Comment']));
$Comment = mysqli_real_escape_string($conn,str_replace("...", ".", $_POST['Comment']));
$Comment = mysqli_real_escape_string($conn,str_replace("   ", " ", $_POST['Comment']));
$Comment = mysqli_real_escape_string($conn,str_replace("***", " ", $_POST['Comment']));
 $beam_devices = implode(', ', $_POST['beam_devices']);

   $mysqli_check_simulation_data=mysqli_query($conn,"SELECT position_immobilization_ID FROM tbl_position_immobilization WHERE Registration_ID='$Registration_ID' AND number_phases='$number_phases'");
   if(mysqli_num_rows($mysqli_check_simulation_data) > 0){
       
         $position_immobilization_ID =mysqli_fetch_assoc(mysqli_query($conn,"SELECT position_immobilization_ID FROM tbl_position_immobilization WHERE Registration_ID='$Registration_ID' AND date(date_time)=CURDATE()"))['position_immobilization_ID'];
       
         $save_mobilization = mysqli_query($conn,"UPDATE tbl_position_immobilization SET number_Mask = '$number_Mask', Employee_ID='$Employee_ID',body_position='$body_position',head_position='$head_position',legs_position='$leg_position',blocks='$blocks',techniques='$technique',separation='$separation',depth='$depth', number_site='$number_of_site',number_field='$number_fields',Radiotherapy_ID='$Radiotherapy_ID', beam_devices='$beam_devices', Mask = '$Mask', number_alphabet = '$number_alphabet', name_of_site='$name_of_site',Diagnosis='$Diagnosis',Comment = '$Comment', date_time=NOW() WHERE position_immobilization_ID='$position_immobilization_ID'") or die(mysqli_error($conn));

            
    echo "hpa".$position_immobilization_ID;
  $totalfield_type = sizeof($field_type);
                    $num = 1;
                    for($i=0;$i<$totalfield_type;$i++) {

                        $field_type_name = $field_type[$i];
                        $Tumour_name  = $Tumour[$i];
                        $Skin_name = $Skin[$i];
						$Skin_length_name = $Skin_length[$i];
                        $Depth_name = $Depth[$i];
                        $SSD_name = $SSD[$i];
                        $Gantry_name = $Gantry[$i];
                        $Coll_name = $Coll[$i];
                        $Cough_name = $Cough[$i];
                        
                   }

                   echo $data_all;

                   $update_phase = mysqli_query($conn, "UPDATE tbl_radiotherapy_phases SET Phase_Status = 'Submitted' WHERE Treatment_Phase='$number_phases' AND Radiotherapy_ID='$Radiotherapy_ID' AND Phase_Status = 'active'") or die(mysqli_error($conn));
       
   }else {
       
         $save_mobilization = mysqli_query($conn,"INSERT INTO tbl_position_immobilization(Registration_ID,Employee_ID,body_position,head_position,legs_position,blocks,techniques,separation,depth,number_site,number_field,beam_devices,name_of_site,Diagnosis,date_time,Radiotherapy_ID,number_phases, Comment, Mask, number_alphabet, number_Mask)VALUES('$Registration_ID','$Employee_ID','$body_position','$head_position','$leg_position','$blocks','$technique','$separation','$depth','$number_of_site','$number_fields','$beam_devices','$name_of_site','$Diagnosis',NOW(), '$Radiotherapy_ID','$number_phases', '$Comment', '$Mask', '$number_alphabet', '$number_Mask')") or die(mysqli_error($conn));

    $position_immobilization_ID = mysqli_insert_id($conn);
    
    echo "hapa".$position_immobilization_ID;
  $totalfield_type = sizeof($field_type);

                    for($i=0;$i<$totalfield_type;$i++) {

                        $field_type_name = $field_type[$i];
                        $Tumour_name  = $Tumour[$i];
                        $Skin_name = $Skin[$i];
                        $Depth_name = $Depth[$i];
                        $SSD_name = $SSD[$i];
                        $Gantry_name = $Gantry[$i];
                        $Coll_name = $Coll[$i];
                        $Cough_name = $Cough[$i];
						$Skin_length_name = $Skin_length[$i];

                        
                        $sql_attache=mysqli_query($conn,"INSERT INTO tbl_fields_position(Employee_ID,field_name,f_s_on_skin,f_s_on_tumour,ssd_sad,depth,gantry_angle,coll_angle,cough_angle,position_immobilization_ID,date_time,Skin_length) VALUES('$Employee_ID','$field_type_name','$Skin_name','$Tumour_name','$SSD_name','$Depth_name','$Gantry_name','$Coll_name','$Cough_name','$position_immobilization_ID',NOW(),'$Skin_length_name')") or die(mysqli_error($conn));  
                        
                   }
                   $update_phase = mysqli_query($conn, "UPDATE tbl_radiotherapy_phases SET Phase_Status = 'Submitted' WHERE Treatment_Phase='$number_phases' AND Radiotherapy_ID='$Radiotherapy_ID' AND Phase_Status = 'active'") or die(mysqli_error($conn));
       
   }
 

mysqli_close($conn);
//field_type:field_type,Tumour:Tumour,Skin:Skin,Depth:Depth,SSD:SSD,Gantry:Gantry,Coll:Coll,Cough:Cough