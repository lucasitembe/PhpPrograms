<?php
include("./includes/connection.php");
//finance_department_id
session_start();
if(isset($_POST['weight'])){
    $weight = mysqli_real_escape_string($conn,  $_POST['weight']);
}else{
    $weight="";
}
if(isset($_POST['height'])){
    $height = mysqli_real_escape_string($conn,  $_POST['height']);
}else{
    $height="";
}
if(isset($_POST['surface'])){
    $surface = mysqli_real_escape_string($conn,  $_POST['surface']);
}else{
    $surface="";
}
if(isset($_POST['stage'])){
    $stage = mysqli_real_escape_string($conn,  $_POST['stage']);
}else{
    $stage="";
}
if(isset($_POST['diagnosis'])){
    $diagnosis = mysqli_real_escape_string($conn,  $_POST['diagnosis']);
}else{
    $diagnosis="";
}
if(isset($_POST['checkedvalue'])){
    $checkedvalue =  $_POST['checkedvalue'];
}else{
    $checkedvalue="";
}

if(isset($_POST['dosead'])){
    $dosead = mysqli_real_escape_string($conn,  $_POST['dosead']);
}else{
    $dosead="";
}
if(isset($_POST['allergies'])){
    $allergies = mysqli_real_escape_string($conn,  $_POST['allergies']);
}else{
    $allergies="";
}
if(isset($_POST['Registration_ID'])){
    $Registration_ID = mysqli_real_escape_string($conn,  $_POST['Registration_ID']);
}else{
    $Registration_ID="";
}
if(isset($_POST['selected_items'])){
  $selected_items =   $_POST['selected_items'];
}else{
    $selected_items="";
}
if(isset($_POST['selected_physician'])){
    $selected_physician =  $_POST['selected_physician'];
}else{
    $selected_physician="";
}
if(isset($_POST['selected_treatment'])){
    $selected_treatment = $_POST['selected_treatment'];
}else{
    $selected_treatment="";
}
if(isset($_POST['selected_drug'])){
    $selected_drug =   $_POST['selected_drug'];
}else{
    $selected_drug = "";
}

if(isset($_POST['dose'])){
    $doctor_dose =  $_POST['dose'];
}else{
    $doctor_dose='';
}
if(isset($_POST['cancer_ID'])){
    $cancer_protocal_id = mysqli_real_escape_string($conn,  $_POST['cancer_ID']);
}else{
    $cancer_protocal_id = "";
}


$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Employee_name = $_SESSION['userinfo']['Employee_Name'];
$finance_department_id = $_SESSION['finance_department_id'];
              //include("../includes/connection.php");
   
            
         // die("SELECT Protocal_status FROM tbl_cancer_patient_details WHERE  Registration_ID='$Registration_ID' AND cancer_type_id='$cancer_protocal_id' ");
        // echo $cancer_protocal_id;
            $Protocal_status_select = mysqli_query($conn, "SELECT Protocal_status FROM tbl_cancer_patient_details WHERE  Registration_ID='$Registration_ID' AND cancer_type_id='$cancer_protocal_id' " ) or die(mysqli_error($conn));
           while($status_rw = mysqli_fetch_assoc($Protocal_status_select)){
                $Protocal_status = $status_rw['Protocal_status'];
           }
           // if(mysqli_num_rows($Protocal_status_select)>0){
             if($Protocal_status=="Onprogress"){
              echo "This protocal is on progress cancel it to assign again";
             }else if($Protocal_status=="Pending") {
              echo "This Protocal is pending please activate  or cancel protocal first";
            }else{
                $mysql_regist_details = mysqli_query($conn,"INSERT INTO tbl_cancer_patient_details(Registration_ID,cancer_type_id,weight,height,body_surface,diagnosis,weight_adjustment,allergies,dose_adjustment,stage,date_and_time,Employee_ID)VALUES('$Registration_ID','$cancer_protocal_id','$weight','$height','$surface','$diagnosis','$checkedvalue','$allergies','$dosead',' $stage',NOW(), '$Employee_ID' )") or die(mysqli_error($conn));
                $Patient_protocal_details_ID =mysqli_insert_id($conn);

                //insert adjavant information
                foreach ($selected_items as $adjuvant_nameone){
                    (int)$adjuvant_nameone;
                        $mysql_select = mysqli_query($conn,"SELECT cancer_type_id,adjuvant,duration,adjuvantstrenth FROM tbl_adjuvant_duration WHERE adjuvant_ID='$adjuvant_nameone'")or die(mysqli_error($conn));
                        if(mysqli_num_rows($mysql_select)>0){
                            while($rows=mysqli_fetch_assoc($mysql_select)){
                                $adjuvant =$rows['adjuvant'];
                                $duration =$rows['duration'];
                                $adjuvantstrenth = $rows['adjuvantstrenth'];
                                $cancer_type_id =$rows['cancer_type_id'];                        
                                $mysql_insert=mysqli_query($conn,"INSERT INTO tbl_patient_adjuvant_duration(Registration_ID,cancer_type_id,adjuvant,duration,adjuvantstrenth, date_and_time,  Patient_protocal_details_ID )VALUES('$Registration_ID','$cancer_type_id','$adjuvant','$duration', '$adjuvantstrenth',NOW(),'  $Patient_protocal_details_ID ')") or die(mysqli_error($conn));
                            }
                        }else{
                            echo "nothing to display";
                        }
                    }
                //======end of insert adjavant

                //======insert data physician
                foreach ($selected_physician as $physician_nameone){
                    (int)$physician_nameone;
                        $mysql_select = mysqli_query($conn,"SELECT physician_volume,physician_type,physician_minutes,cancer_type_id, physician_ID, Physician_Item_name FROM tbl_physician WHERE physician_ID='$physician_nameone'")or die(mysqli_error($conn));
                        if(mysqli_num_rows($mysql_select)>0){
                            while($rows=mysqli_fetch_assoc($mysql_select)){
                                $physician_volume =$rows['physician_volume'];
                                $physician_type =$rows['physician_type'];
                                $physician_minutes =$rows['physician_minutes'];
                                $cancer_type_id =$rows['cancer_type_id'];
                                $physician_ID = $rows['physician_ID'];
                                $Physician_Item_name = $rows['Physician_Item_name'];
                                $mysql_insert=mysqli_query($conn,"INSERT INTO tbl_patient_physician(Registration_ID,cancer_type_id,physician_volume,physician_type,physician_minutes,date_and_time,Patient_protocal_details_ID,physician_ID, Physician_Item_name)VALUES('$Registration_ID','$cancer_type_id','$physician_volume','$physician_type','$physician_minutes',NOW(), '$Patient_protocal_details_ID','$physician_ID', '$Physician_Item_name')") or die(mysqli_error($conn));
                            }
                        }else{
                            echo "nothing to display";
                        }
                    }
                //=======end insert  physician 


                foreach ($selected_treatment as $treatment_nameone){
                    (int)$treatment_nameone;
                        $mysql_select = mysqli_query($conn,"SELECT treatment_ID, cancer_type_id,supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions,date_and_time FROM tbl_supportive_treatment WHERE treatment_ID='$treatment_nameone'")or die(mysqli_error($conn));
                        if(mysqli_num_rows($mysql_select)>0){
                            while($rows=mysqli_fetch_assoc($mysql_select)){
                                $cancer_type_id =$rows['cancer_type_id'];
                                $supportive_treatment =$rows['supportive_treatment'];
                                $Dose =$rows['Dose'];
                                $Route =$rows['Route'];
                                $Administration_Time =$rows['Administration_Time'];
                                $Frequence =$rows['Frequence'];
                                $Medication_Instructions =$rows['Medication_Instructions'];
                                $date_and_time =$rows['date_and_time'];
                                $treatment_ID = $rows['treatment_ID'];

                                $mysql_insert=mysqli_query($conn,"INSERT INTO tbl_patient_supportive_treatment(Registration_ID,cancer_type_id,supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions,date_and_time,Patient_protocal_details_ID,treatment_ID)VALUES('$Registration_ID','$cancer_type_id','$supportive_treatment','$Dose','$Route','$Administration_Time','$Frequence','$Medication_Instructions',NOW(), '$Patient_protocal_details_ID','$treatment_ID')") or die(mysqli_error($conn));
                            }
                       }
                }
             

                //===================chemo drud
                for ($i = 0; $i < sizeof($doctor_dose); $i++){
               // foreach ($selected_drug as $drug_nameone){
                    $selected_drug[$i];
                    $doctor_dose[$i];
                        $mysql_selects= mysqli_query($conn,"SELECT chemotherapy_ID, cancer_type_id,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency FROM tbl_chemotherapy_drug WHERE chemotherapy_ID='".$selected_drug[$i]."'")or die(mysqli_error($conn));

                        if(mysqli_num_rows($mysql_selects)>0){
                        while($rows=mysqli_fetch_assoc($mysql_selects)){
                            $cancer_type_id =$rows['cancer_type_id'];
                            $Chemotherapy_Drug=$rows['Chemotherapy_Drug'];
                               $chemotherapy_ID =$rows['chemotherapy_ID'];
                                $Volume =$rows['Volume'];
                                $Route =$rows['Route'];
                                $Admin_Time=$rows['Admin_Time'];
                                $Frequency =$rows['Frequency'];

                                $mysql_insert=mysqli_query($conn,"INSERT INTO tbl_patient_chemotherapy_drug(Registration_ID,cancer_type_id,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,date_and_time,Patient_protocal_details_ID, chemotherapy_ID)VALUES('$Registration_ID','$cancer_type_id','$Chemotherapy_Drug','$doctor_dose[$i]','$Volume','$Route','$Admin_Time', '$Frequency',NOW(),'$Patient_protocal_details_ID','$chemotherapy_ID')") or die(mysqli_error($conn));
                               
                            }
                         }
                        
                        }
                        echo "Protocal assigned successful";
                    }
           

