<?php
include("./includes/connection.php");
if(isset($_POST['action'])){
    if($_POST['action']=='save'){    
     $course_of_death=  mysqli_real_escape_string($conn,$_POST['course_of_death']);   
     $Relative=  mysqli_real_escape_string($conn,$_POST['relative_name']);
     $Relationship_type=  mysqli_real_escape_string($conn,$_POST['relationship_type']);
     $Phone_No_Relative=  mysqli_real_escape_string($conn,$_POST['relative_phone_number']);
     $Address=  mysqli_real_escape_string($conn,$_POST['relative_Address']);
     $Registration_ID=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
//     $Patient_ID=mysqli_real_escape_string($conn,$_POST['Patient_ID']);
     $death_date=  mysqli_real_escape_string($conn,$_POST['death_date_time']);
     $doctor_confirm_death=  mysqli_real_escape_string($conn,$_POST['Docto_confirm_death_name']);
     $place_of_death=  mysqli_real_escape_string($conn,$_POST['place_of_death']);
     $send_notsend_to_morgue= mysqli_real_escape_string($conn,$_POST['send_notsend_to_morgue']);
     $dead_after_before= mysqli_real_escape_string($conn,$_POST['dead_after_before']);
    
     
     $checkExist=  mysqli_query($conn,"SELECT Patient_ID FROM tbl_diceased_patients WHERE Patient_ID='$Registration_ID'");
     $num_rows= mysqli_num_rows($checkExist);
     if($num_rows>0){
       $query=  mysqli_query($conn,"UPDATE tbl_diceased_patients SET doctor_confirm_death='$doctor_confirm_death',place_of_death='$place_of_death',death_reason='$course_of_death',relative_name='$Relative',relationship_type='$Relationship_type',relative_phone_number='$Phone_No_Relative',relative_Address='$Address',death_date='$death_date',dead_after_before='$dead_after_before',send_notsend_to_morgue='$send_notsend_to_morgue'") or die(mysqli_error($conn));  
         
     }else{
      $query=mysqli_query($conn,"INSERT INTO tbl_diceased_patients (death_reason,relative_name,relationship_type,relative_phone_number,relative_Address,Patient_ID,death_date,doctor_confirm_death,place_of_death,dead_after_before,send_notsend_to_morgue) VALUES ('$course_of_death','$Relative','$Relationship_type','$Phone_No_Relative','$Address','$Registration_ID','$death_date','$doctor_confirm_death','$place_of_death','$dead_after_before','$send_notsend_to_morgue')") or die(mysqli_error($conn));
     }
     
     
     if($query){
//       Diseased
         $sql_select_disease_caused_death_from_cache_tbl_result=mysqli_query($conn,"SELECT disease_name,disease_code FROM tbl_disease_caused_death_cache WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_disease_caused_death_from_cache_tbl_result)>0){
            while($diseases_cache_rows=mysqli_fetch_assoc($sql_select_disease_caused_death_from_cache_tbl_result)){
                    $disease_name=$diseases_cache_rows['disease_name'];
                    $disease_code=$diseases_cache_rows['disease_code'];
                    mysqli_query($conn,"INSERT INTO tbl_disease_caused_death(Registration_ID,disease_name,disease_code) VALUES('$Registration_ID','$disease_name','$disease_code')")or die(mysqli_error($conn));
                    $success=true;
            }
        }
        $queryUpdate=  mysqli_query($conn,"UPDATE tbl_patient_registration SET Diseased='yes' WHERE Registration_ID='$Registration_ID'");
        if($queryUpdate){
            
            echo 'Information saved successfully';
        }
    }else{
        
       echo 'Information saving error!'; 
        
    }
     
     
    }elseif ($_POST['action']=='save_reason') {
       $Death_Reason=  mysqli_real_escape_string($conn,$_POST['Death_Reason']);
       $query=  mysqli_query($conn,"INSERT INTO tbl_deceased_reasons (deceased_reasons) VALUES ('$Death_Reason')");
       if($query){
        $resultquery=  mysqli_query($conn,"SELECT deceased_reasons_ID,deceased_reasons FROM tbl_deceased_reasons");
        
        while($result=  mysqli_fetch_assoc($resultquery)){
            echo '<option value="'.$result['deceased_reasons_ID'].'">'.$result['deceased_reasons'].'</option>';  
        }
           
       }else{
           
       }
       
        
    }
    
}
//Address='+relative_address,

//
?>
