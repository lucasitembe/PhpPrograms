<?php

//unit:unit,wedge:wedge,block:block,dose_per_fraction:dose_per_fraction,total_tumour_dose:total_tumour_dose,number_of_fraction:number_of_fraction,Dose_per_Fraction1:Dose_per_Fraction1,Time1:Time1,Cummutive_Dose1:Cummutive_Dose1,Dose_per_Fraction2:Dose_per_Fraction2,Time2:Time2,Cummutive_Dose2:Cummutive_Dose2,Dose_per_Fraction3:Dose_per_Fraction3,Time3:Time3,Cummutive_Dose3:Cummutive_Dose3,Dose_per_Fraction4:Dose_per_Fraction4,Time4:Time4,Cummutive_Dose4:Cummutive_Dose4

require_once('includes/connection.php');


if (isset($_POST['unit'])) {
    $unit = $_POST['unit'];
} else {
    $unit = '';
}
if (isset($_POST['wedge'])) {
    $wedge = $_POST['wedge'];
} else {
    $wedge = '';
}
if (isset($_POST['block'])) {
    $block = $_POST['block'];
} else {
    $block = '';
}
if (isset($_POST['dose_per_fraction'])) {
    $dose_per_fraction = $_POST['dose_per_fraction'];
} else {
    $dose_per_fraction = '';
}
if (isset($_POST['total_tumour_dose'])) {
    $total_tumour_dose = $_POST['total_tumour_dose'];
} else {
    $total_tumour_dose = '';
}
if (isset($_POST['number_of_fraction'])) {
    $number_of_fraction = $_POST['number_of_fraction'];
} else {
    $number_of_fraction = '';
}
if (isset($_POST['Dose_per_Fraction1'])) {
   echo $Dose_per_Fraction1 = $_POST['Dose_per_Fraction1'];
} else {
    $Dose_per_Fraction1 = '';
}
if (isset($_POST['Time1'])) {
    $Time1 = $_POST['Time1'];
} else {
    $Time1 = '';
}
if (isset($_POST['Cummutive_Dose1'])) {
    $Cummutive_Dose1 = $_POST['Cummutive_Dose1'];
} else {
    $Cummutive_Dose1 = '';
}
if (isset($_POST['Cummutive_Dose1'])) {
    $Cummutive_Dose1 = $_POST['Cummutive_Dose1'];
} else {
    $Cummutive_Dose1 = '';
}
if (isset($_POST['Dose_per_Fraction2'])) {
    $Dose_per_Fraction2 = $_POST['Dose_per_Fraction2'];
} else {
    $Dose_per_Fraction2 = '';
}
if (isset($_POST['Time2'])) {
    $Time2 = $_POST['Time2'];
} else {
    $Time2 = '';
}
if (isset($_POST['Cummutive_Dose2'])) {
    $Cummutive_Dose2 = $_POST['Cummutive_Dose2'];
} else {
    $Cummutive_Dose2 = '';
}
if (isset($_POST['Dose_per_Fraction3'])) {
   $Dose_per_Fraction3 = $_POST['Dose_per_Fraction3'];
} else {
    $Dose_per_Fraction3 = '';
}
if (isset($_POST['Time3'])) {
    $Time3 = $_POST['Time3'];
} else {
    $Time3 = '';
}
if (isset($_POST['Cummutive_Dose3'])) {
    $Cummutive_Dose3 = $_POST['Cummutive_Dose3'];
} else {
    $Cummutive_Dose3 = '';
}
if (isset($_POST['Dose_per_Fraction4'])) {
    $Dose_per_Fraction4 = $_POST['Dose_per_Fraction4'];
} else {
    $Dose_per_Fraction4= 'vv';
}
if (isset($_POST['Time4'])) {
    $Time4 = $_POST['Time4'];
} else {
    $Time4= '';
}
if (isset($_POST['Cummutive_Dose4'])) {
    $Cummutive_Dose4 = $_POST['Cummutive_Dose4'];
} else {
    $Cummutive_Dose4= '';
}
if (isset($_POST['Registration_ID'])) {
    $Registration_ID = $_POST['Registration_ID'];
} else {
    $Registration_ID= '';
}
if (isset($_POST['date'])) {
    $date = $_POST['date'];
} else {
    $date= '';
}
if (isset($_POST['Employee_ID'])) {
    $Employee_ID = $_POST['Employee_ID'];
} else {
    $Employee_ID= '';
}
if (isset($_POST['name_position_1'])) {
    $name_position_1 = $_POST['name_position_1'];
} else {
    $name_position_1= '';
}
if (isset($_POST['name_position_2'])) {
    $name_position_2 = $_POST['name_position_2'];
} else {
    $name_position_2= '';
}
if (isset($_POST['name_position_3'])) {
    $name_position_3 = $_POST['name_position_3'];
} else {
    $name_position_3= '';
}
if (isset($_POST['name_position_4'])) {
    $name_position_4 = $_POST['name_position_4'];
} else {
    $name_position_4= '';
}

//name_position_1:name_position_1,name_position_2:name_position_2,name_position_3:name_position_3,name_position_4:name_position_4


 $mysqli_check_simulation_data=mysqli_query($conn,"SELECT setup_devery_ID FROM tbl_machine_setup_delivery WHERE Registration_ID='$Registration_ID' AND date(wedge_date_time)=CURDATE()");
   if(mysqli_num_rows($mysqli_check_simulation_data) > 0){
       
        $setup_devery_ID= mysqli_fetch_assoc(mysqli_query($conn,"SELECT setup_devery_ID FROM tbl_machine_setup_delivery WHERE Registration_ID='$Registration_ID' AND date(wedge_date_time)=CURDATE()"))['setup_devery_ID'];
       
        $sql_save_data = mysqli_query($conn,"UPDATE tbl_machine_setup_delivery SET unit='$unit',Registration_ID='$Registration_ID',Employee_ID='$Employee_ID',wedge='$wedge',block='$block',dose_per_fraction='$dose_per_fraction',total_tomour_dose='$total_tumour_dose',number_of_fraction='$number_of_fraction',wedge_date_time=NOW() WHERE setup_devery_ID='$setup_devery_ID'");
 
      $setup_devery_ID = mysqli_insert_id();
// 
// 
     $totaldoseper_fraction = sizeof($Dose_per_Fraction1);

                    for($i=0;$i<$totaldoseper_fraction;$i++) {

                        echo $Dose_per_Fraction1_name = $Dose_per_Fraction1[$i];
                        $Cummutive_Dose1_name  = $Cummutive_Dose1[$i];
                        $Time1_name  =  $Time1[$i];
                       echo $Dose_per_Fraction2_name = $Dose_per_Fraction2[$i];
                        $Cummutive_Dose2_name = $Cummutive_Dose2[$i];
                        $Time2_name = $Time2[$i];
                       echo $Dose_per_Fraction3_name = $Dose_per_Fraction3[$i];
                        $Cummutive_Dose3_name = $Cummutive_Dose3[$i];
                      echo  $Time3_name = $Time3[$i];
                        
                      echo  $Dose_per_Fraction4_name = $Dose_per_Fraction4[$i];
                      echo "hapa hapa";
                      echo  $Cummutive_Dose4_name = $Cummutive_Dose4[$i];
                        $Time4_name = $Time4[$i];
                        $date_name = $date[$i];

                        
                        $sql_attache=mysqli_query($conn,"UPDATE tbl_treatment_delivery SET Employee_ID='$Employee_ID',Date_field='$date_name',Dose_per_Fraction1='$Dose_per_Fraction1_name',Time1='$Time1_name',Cummutive_Dose1='$Cummutive_Dose1_name',Dose_per_Fraction2='$Dose_per_Fraction2_name',Time2='$Time2_name',Cummutive_Dose2='$Cummutive_Dose2_name',Dose_per_Fraction3='$Dose_per_Fraction3_name',Time3='$Time3_name',Cummutive_Dose3='$Cummutive_Dose3_name',Dose_per_Fraction4='$Dose_per_Fraction4_name',Time4='$Time4_name',Cummutive_Dose4='$Cummutive_Dose4_name',date_and_time=NOW() WHERE setup_devery_ID='$setup_devery_ID'") or dei(mysqli_error($conn));  
                        
                   }
       
   }else{
       
          $check_if_delivery = mysqli_query($conn,"SELECT Registration_ID FROM tbl_machine_setup_delivery WHERE Registration_ID='$Registration_ID'");
        
          if(mysqli_num_rows($check_if_delivery) > 0){
              
           $setup_devery_ID=mysqli_fetch_assoc(mysqli_query($conn,"SELECT setup_devery_ID FROM tbl_machine_setup_delivery WHERE Registration_ID='$Registration_ID'"))['setup_devery_ID'];   
              
    
          }else{
              
          $sql_save_data = mysqli_query($conn,"INSERT INTO tbl_machine_setup_delivery(unit,Registration_ID,Employee_ID,wedge,block,dose_per_fraction,total_tomour_dose,number_of_fraction,wedge_date_time)VALUES('$unit','$Registration_ID','$Employee_ID','$wedge','$block','$dose_per_fraction','$total_tumour_dose','$number_of_fraction',NOW())");
 
          $setup_devery_ID = mysqli_insert_id();
          }
       
     
 
 
   $totaldoseper_fraction = sizeof($Dose_per_Fraction1);

                    for($i=0;$i<$totaldoseper_fraction;$i++) {

                        $Dose_per_Fraction1_name = $Dose_per_Fraction1[$i];
                        $Cummutive_Dose1_name  = $Cummutive_Dose1[$i];
                        $Time1_name  =  $Time1[$i];
                        $Dose_per_Fraction2_name = $Dose_per_Fraction2[$i];
                        $Cummutive_Dose2_name = $Cummutive_Dose2[$i];
                        $Time2_name = $Time2[$i];
                        $Dose_per_Fraction3_name = $Dose_per_Fraction3[$i];
                        $Cummutive_Dose3_name = $Cummutive_Dose3[$i];
                        $Time3_name = $Time3[$i];
                        $Dose_per_Fraction4_name = $Dose_per_Fraction4[$i];
                        $Cummutive_Dose4_name = $Cummutive_Dose4[$i];
                        $Time4_name = $Time4[$i];
                        $date_name = $date[$i];

                        
                        $sql_attache=mysqli_query($conn,"INSERT INTO tbl_treatment_delivery(setup_devery_ID,Employee_ID,Date_field,Dose_per_Fraction1,Time1,Cummutive_Dose1,Dose_per_Fraction2,Time2,Cummutive_Dose2,Dose_per_Fraction3,Time3,Cummutive_Dose3,Dose_per_Fraction4,Time4,Cummutive_Dose4,date_and_time) VALUES('$setup_devery_ID','$Employee_ID','$date_name','$Dose_per_Fraction1_name','$Time1_name','$Cummutive_Dose1_name','$Dose_per_Fraction2_name','$Time2_name','$Cummutive_Dose2_name','$Dose_per_Fraction3_name','$Time3_name','$Cummutive_Dose3_name','$Dose_per_Fraction4_name','$Time4_name','$Cummutive_Dose4_name',NOW())") or dei(mysqli_error($conn));  
                        
                   }
       
       
       
   }

   
            $check_if_exist = mysqli_query($conn,"SELECT setup_devery_ID FROM tbl_name_field_position WHERE Registration_ID='$Registration_ID'");
   
                   $num_rows = mysqli_num_rows($check_if_exist);
                   
                   if($num_rows > 0){
                       
                       echo "nothing to display";
                       
                   }else{
                       
                     $save_data = mysqli_query($conn,"INSERT INTO tbl_name_field_position (setup_devery_ID,Registration_ID,name_position_1,name_position_2,name_position_3,name_position_4)VALUES('$setup_devery_ID','$Registration_ID','$name_position_1','$name_position_2','$name_position_3','$name_position_4')");
                   }

  