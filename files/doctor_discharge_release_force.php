<?php
 @session_start();
 include("./includes/connection.php");
   
   
    if(isset($_GET['admission_ID']) && !empty($_GET['admission_ID'])){
	  $admission_ID = $_GET['admission_ID'];
    }else{
        $admission_ID=0;
    }
    if(isset($_GET['Discharge_Reason']) && !empty($_GET['Discharge_Reason'])){
	  $Discharge_Reason_ID = $_GET['Discharge_Reason'];
    }
	if(isset($_GET['deceased_diseases']) && !empty($_GET['deceased_diseases'])){
	  $deceased_diseases = $_GET['deceased_diseases'];
    }
	
	$deceased_diseases=explode(',',$deceased_diseases);
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Docto_confirm_death_name=$_GET['Docto_confirm_death_name'];
    $death_date_time=$_GET['death_date_time'];

    // die($Docto_confirm_death_name);

    if(isset($_GET['fromnurse'])){
        $Admission_Status='pending';
        $check_ifdoctordischarge = mysqli_query($conn, "SELECT Admission_Status FROM  tbl_admission where Admision_ID='$admission_ID' AND Discharge_Reason_ID >0") or die(mysqli_error($conn));
        if(mysqli_num_rows($check_ifdoctordischarge)>0){            
            $query=mysqli_query($conn,"UPDATE tbl_admission SET Admission_Status='$Admission_Status'  WHERE Admision_ID='$admission_ID'") or die(mysqli_error($conn));
            if($query){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            if(isset($_GET['death_date_time']) && (!empty($death_date_time)) && (!empty($Docto_confirm_death_name)) && isset($_GET['Docto_confirm_death_name'])&&$_GET['course_of_death']){
                $death_date_time=$_GET['death_date_time'];
                $Docto_confirm_death_name=$_GET['Docto_confirm_death_name'];
                $course_of_death=$_GET['course_of_death'];

                $query=mysqli_query($conn,"UPDATE tbl_admission SET course_of_death='$course_of_death',Docto_confirm_death_name='$Docto_confirm_death_name',death_date_time='$death_date_time',Discharge_Reason_ID=$Discharge_Reason_ID,pending_set_time=NOW(),pending_setter='$Employee_ID', Doctor_Status='initial_pending',Admission_Status='$Admission_Status' WHERE Admision_ID='$admission_ID'") or die(mysqli_error($conn));
                $success=false;
                if($query){
                    $Registration_ID=mysqli_fetch_assoc(mysqli_query($conn, "SELECT Registration_ID FROM tbl_admission WHERE Admision_ID='$admission_ID'"))['Registration_ID'];
                    $sql_select_disease_caused_death_from_cache_tbl_result=mysqli_query($conn,"SELECT * FROM tbl_disease_caused_death_cache WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        
                    //Insert into deceased patient table for reference Inpatient and Outpatient by Muga
                    $checkExist=  mysqli_query($conn,"SELECT Patient_ID FROM tbl_diceased_patients WHERE Patient_ID='$Registration_ID'");
                    $num_rows= mysqli_num_rows($checkExist);
                    if($num_rows>0){
                      $query=  mysqli_query($conn,"UPDATE tbl_diceased_patients SET doctor_confirm_death='$Docto_confirm_death_name',death_reason='$course_of_death', death_date='$death_date_time' , send_notsend_to_morgue='yes' WHERE Patient_ID='$Registration_ID'") or die(mysqli_error($conn));  
                      if($query){
                        $query= mysqli_query($conn, "UPDATE tbl_patient_registration SET Diseased='yes', Diseased_time='$death_date_time', Diseased_by='$Employee_ID' WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                        if($query){
                          echo 1;
                        }
                       
                        }else{
                            echo 0;
                        }
                    }else{
                     $query=mysqli_query($conn,"INSERT INTO tbl_diceased_patients (death_reason,Patient_ID,death_date,doctor_confirm_death, send_notsend_to_morgue) VALUES ('$course_of_death','$Registration_ID','$death_date_time','$Docto_confirm_death_name', 'yes')") or die(mysqli_error($conn));
                        if($query){
                        $query= mysqli_query($conn, "UPDATE tbl_patient_registration SET Diseased='yes', Diseased_time='$death_date_time', Diseased_by='$Employee_ID' WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                            if($query){
                                echo 1;
                            }else{
                                echo 0;
                            }
                        }
                    }
                    //End of comparison By Muga
        
                    if(mysqli_num_rows($sql_select_disease_caused_death_from_cache_tbl_result)>0){
                        while($diseases_cache_rows=mysqli_fetch_assoc($sql_select_disease_caused_death_from_cache_tbl_result)){
                          $disease_name=$diseases_cache_rows['disease_name'];
                          $disease_code=$diseases_cache_rows['disease_code'];
                          $select_disease =mysqli_query($conn, "SELECT disease_name FROM tbl_disease_caused_death WHERE Registration_ID='$Registration_ID' AND disease_name='$disease_name'") or die(mysqli_error($conn));
                          if(mysqli_num_rows($select_disease)>0){
  
                          }else{
                              mysqli_query($conn,"INSERT INTO tbl_disease_caused_death(Admision_ID, Registration_ID,disease_name,disease_code) VALUES('$admission_ID','$Registration_ID','$disease_name','$disease_code')")or die(mysqli_error($conn));
                          }
                          
                          $success=true;
                        }
                    }			
                }else{
                  echo "failed to update admission status";
                }
            
            }else{
              
                $query=mysqli_query($conn,"UPDATE tbl_admission SET Discharge_Reason_ID='$Discharge_Reason_ID', pending_set_time=NOW(),pending_setter='$Employee_ID', Admission_Status='$Admission_Status', Doctor_Status='initial_pending'  WHERE Admision_ID='$admission_ID'") or die(mysqli_error($conn));
                if($query){
                    echo 1;
                }else{
                    echo 0;
                }
            }
            
           
        }
    
       
    }else{
        if(isset($_GET['death_date_time']) && (!empty($death_date_time)) && (!empty($Docto_confirm_death_name)) && isset($_GET['Docto_confirm_death_name'])&&$_GET['course_of_death']){
            $death_date_time=$_GET['death_date_time'];
            $Docto_confirm_death_name=$_GET['Docto_confirm_death_name'];
            $course_of_death=$_GET['course_of_death'];

            $query=mysqli_query($conn,"UPDATE tbl_admission SET course_of_death='$course_of_death',Docto_confirm_death_name='$Docto_confirm_death_name',death_date_time='$death_date_time',Discharge_Reason_ID=$Discharge_Reason_ID,pending_set_time=NOW(),pending_setter='$Employee_ID', Doctor_Status='initial_pending' WHERE Admision_ID='$admission_ID'") or die(mysqli_error($conn));
            $success=false;
            if($query){
                $Registration_ID=mysqli_fetch_assoc(mysqli_query($conn, "SELECT Registration_ID FROM tbl_admission WHERE Admision_ID='$admission_ID'"))['Registration_ID'];
                $sql_select_disease_caused_death_from_cache_tbl_result=mysqli_query($conn,"SELECT * FROM tbl_disease_caused_death_cache WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
    
                //Insert into deceased patient table for reference Inpatient and Outpatient by Muga
                $checkExist=  mysqli_query($conn,"SELECT Patient_ID FROM tbl_diceased_patients WHERE Patient_ID='$Registration_ID'");
                $num_rows= mysqli_num_rows($checkExist);
                if($num_rows>0){
                  $query=  mysqli_query($conn,"UPDATE tbl_diceased_patients SET doctor_confirm_death='$Docto_confirm_death_name',death_reason='$course_of_death', death_date='$death_date_time' , send_notsend_to_morgue='yes' WHERE Patient_ID='$Registration_ID'") or die(mysqli_error($conn));  
                    if($query){
                      $query= mysqli_query($conn, "UPDATE tbl_patient_registration SET Diseased='yes', Diseased_time='$death_date_time', Diseased_by='$Employee_ID' WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                        if($query){
                          $success = true;
                        }
                    }
                }else{
                    $query=mysqli_query($conn,"INSERT INTO tbl_diceased_patients (death_reason,Patient_ID,death_date,doctor_confirm_death, send_notsend_to_morgue) VALUES ('$course_of_death','$Registration_ID','$death_date_time','$Docto_confirm_death_name', 'yes' )") or die(mysqli_error($conn));
                    if($query){
                        $query= mysqli_query($conn, "UPDATE tbl_patient_registration SET Diseased='yes', Diseased_time='$death_date_time', Diseased_by='$Employee_ID' WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                        if($query){
                          $success = true;
                        }
                    }
                //End of comparison By Muga
    
                if(mysqli_num_rows($sql_select_disease_caused_death_from_cache_tbl_result)>0){
                    while($diseases_cache_rows=mysqli_fetch_assoc($sql_select_disease_caused_death_from_cache_tbl_result)){
                        $disease_name=$diseases_cache_rows['disease_name'];
                        $disease_code=$diseases_cache_rows['disease_code'];
                        $disease_ID = $diseases_cache_rows['disease_ID'];
                        $select_disease =mysqli_query($conn, "SELECT disease_name FROM tbl_disease_caused_death WHERE Registration_ID='$Registration_ID' AND disease_name='$disease_name'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($select_disease)>0){

                        }else{
                            $query= mysqli_query($conn,"INSERT INTO tbl_disease_caused_death(Admision_ID, Registration_ID,disease_name,disease_code, disease_ID) VALUES('$admission_ID','$Registration_ID','$disease_name','$disease_code', '$disease_ID')")or die(mysqli_error($conn));
                            if($query){
                              $success=true;
                            }
                        }                           
                        $success=true;
                    }
                }			
            }
            
            $query=mysqli_query($conn,"UPDATE tbl_admission SET Discharge_Reason_ID='$Discharge_Reason_ID',pending_set_time=NOW(),pending_setter='$Employee_ID', Doctor_Status='initial_pending'  WHERE Admision_ID='$admission_ID'") or die(mysqli_error($conn));
        }else{
          echo "failed to update admission status";
        }
        
    }else{
          
        $query=mysqli_query($conn,"UPDATE tbl_admission SET Discharge_Reason_ID='$Discharge_Reason_ID',pending_set_time=NOW(),pending_setter='$Employee_ID', Doctor_Status='initial_pending'  WHERE Admision_ID='$admission_ID'") or die(mysqli_error($conn));
        if($query){
          $success=true;
        }
    }
        
    if($success==true){
        echo 1;
    }else{
        echo 0;
    }
    
}

?>