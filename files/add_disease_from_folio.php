<?php
  include("./includes/connection.php");
  $disease_list=json_decode($_POST['disease_list']);
  $consultation_ID=$_POST['consultation_ID'];
  $Employee_ID=$_POST['Employee_ID'];
  $patient_status=$_POST['patient_status'];
  $Registration_ID=$_POST['Registration_ID'];

      
      //remove repeated diseases
      $sanitized_list=[];
      foreach ($disease_list as $value) {
        // code...
        if(!in_array($value,$sanitized_list)){
          array_push($sanitized_list,$value);
        }
      }
      $affected_row=0;
      $result='';
        $Employee_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_ID FROM tbl_consultation WHERE consultation_ID='$consultation_ID'"))['employee_ID'];
        if($patient_status == 'OUT'){
          foreach ($sanitized_list as $disease_ID) {
          $result=mysqli_query($conn,"INSERT INTO tbl_disease_consultation(disease_ID,consultation_ID,employee_ID,diagnosis_type,Disease_Consultation_Date_And_Time) VALUES($disease_ID,$consultation_ID,$Employee_ID,'diagnosis',(SELECT NOW()))") or die(mysqli_error($conn));
          if($result){
            $affected_row++;
          }
        }
        }else {
          $Patient_Payment_ID =  mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_ward_round WHERE Registration_ID = (SELECT Registration_ID FROM tbl_consultation WHERE consultation_ID = '$consultation_ID') AND Employee_ID = '$Employee_ID' AND consultation_ID = '$consultation_ID' ORDER BY `Ward_Round_Date_And_Time` DESC LIMIT 1 "))['Patient_Payment_ID'];

          mysqli_query($conn,"INSERT INTO tbl_ward_round(Employee_ID, Registration_ID, consultation_ID, Patient_Payment_ID, Ward_Round_Date_And_Time) VALUES('$Employee_ID',(SELECT Registration_ID FROM tbl_consultation WHERE consultation_ID = '$consultation_ID'),'$consultation_ID','$Patient_Payment_ID',(SELECT NOW()))") ;

          $Round_ID = mysqli_insert_id($conn);
          foreach ($sanitized_list as $disease_ID) {
          $result=mysqli_query($conn,"INSERT INTO tbl_ward_round_disease(disease_ID,Round_ID,diagnosis_type,Round_Disease_Date_And_Time) VALUES($disease_ID,'$Round_ID','diagnosis',(SELECT NOW()))") ;
          if($result){
            mysqli_query($conn,"UPDATE tbl_ward_round SET Process_Status = 'served' WHERE Round_ID = '$Round_ID'");
            $affected_row++;
          }
        }
        if($affected_row < 1){
          
            $Patient_Payment_ID =  mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Patient_Payment_ID= $consultation_ID"))['Patient_Payment_ID'];

            mysqli_query($conn,"INSERT INTO tbl_ward_round(Employee_ID, Registration_ID, consultation_ID, Patient_Payment_ID, Ward_Round_Date_And_Time) VALUES('$Employee_ID',(SELECT Registration_ID FROM tbl_consultation WHERE consultation_ID = '$consultation_ID'),'$consultation_ID','$Patient_Payment_ID',(SELECT NOW()))") or die(mysqli_error($conn).'hapaa '.$consultation_ID);

            $Round_ID = mysqli_insert_id($conn);
            foreach ($sanitized_list as $disease_ID) {
            $result=mysqli_query($conn,"INSERT INTO tbl_ward_round_disease(disease_ID,Round_ID,diagnosis_type,Round_Disease_Date_And_Time) VALUES($disease_ID,'$Round_ID','diagnosis',(SELECT NOW()))") or die(mysqli_error($conn));
            if($result){
              mysqli_query($conn,"UPDATE tbl_ward_round SET Process_Status = 'served' WHERE Round_ID = '$Round_ID'");
              $affected_row++;
            }

    }
        }
      }
      if($affected_row > 0){
        echo "ok";
      }else{
        echo "error";
      }
 ?>
