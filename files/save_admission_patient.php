<?php
include("./includes/connection.php");

     
 
  $result =  mysqli_real_escape_string($conn,$_POST['result']);
  $Docto_confirm_death_name=  mysqli_real_escape_string($conn,$_POST['Docto_confirm_death_name']);
  
   $data = mysqli_query($conn,"select * from tbl_patient_registration where Registration_ID='$result'");
        while ($row = mysqli_fetch_array($data)) {
            $Patient_Name = $row['Patient_Name'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Phone_Number = $row['Phone_Number'];
            $Registration_Date = $row['Registration_Date'];
      
        $query=mysqli_query($conn,"INSERT INTO tbl_diceased_patients (death_reason,relative_name,relationship_type,relative_phone_number,Patient_ID,death_date,doctor_confirm_death,place_of_death,dead_after_before,send_notsend_to_morgue) VALUES ('unknown','$Patient_Name','$Emergence_Contact_Name','$Emergence_Contact_Number','$result',(select now()),'$Docto_confirm_death_name','hospital','dead_after','yes')") or die(mysqli_error($conn));
        $query_checkin=mysqli_query($conn,"INSERT INTO tbl_check_in (Registration_ID,Visit_Date,Employee_ID,Check_In_Date_And_Time,Branch_ID,Check_In_Date,Type_Of_Check_In) VALUES ('$result','$Registration_Date','$Docto_confirm_death_name',(select now()),'1','$Registration_Date','Afresh')") or die(mysqli_error($conn));
        }
  
     
    
     
     
     if($query){
// 
       mysqli_query($conn,"INSERT INTO tbl_disease_caused_death(Registration_ID,disease_name,disease_code) VALUES('$result',' ',' ')")or die(mysqli_error($conn));
          $success=true;
            
        }
        $queryUpdate=  mysqli_query($conn,"UPDATE tbl_patient_registration SET Diseased='yes' WHERE Registration_ID='$result'");
        if($queryUpdate){
            
                              $direction = "./motuary_admission.php?Registration_ID=" . $result . "&Section=Reception&PostPaid=PostPaidThisForm";
                            echo $direction; 
        }
    
