<?php
include("./includes/connection.php");
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
    $start_date=$_POST['start_date'];
    $end_date=$_POST['end_date'];
    $consultation_ID=$_POST['consultation_ID'];
    

    $systoric_pressure_value=array();
    $diastolic_pressure_value=array();
    $Blood_Pressure_value=array();
    $Pulse_Blood_value=array();
    $Temperature_value=array();
    $Resp_Bpressure_value=array();
    $oxygen_saturation_value=array();
    $blood_transfusion_value=array();
    $body_weight_value=array();
    
    $sql_select_vital_sign_result=mysqli_query($conn,"SELECT body_weight,blood_transfusion,date,Blood_Pressure,Pulse_Blood,Temperature,Resp_Bpressure,oxygen_saturation FROM tbl_nursecommunication_observation WHERE Registration_ID='$Registration_ID' AND consultation_ID='$consultation_ID' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY Observation_ID ASC") or die(mysqli_error($conn));

    if(mysqli_num_rows($sql_select_vital_sign_result)>0){
        
        while($vitals_rows=mysqli_fetch_assoc($sql_select_vital_sign_result)){
            $date_v=$vitals_rows['date'];
            $Blood_Pressure_v=$vitals_rows['Blood_Pressure'];
            $Pulse_Blood_v=$vitals_rows['Pulse_Blood'];
            $Temperature_v=$vitals_rows['Temperature'];
            $Resp_Bpressure_v=$vitals_rows['Resp_Bpressure'];
            $oxygen_saturation_v=$vitals_rows['oxygen_saturation'];
            $blood_transfusion_v=$vitals_rows['blood_transfusion'];
            $body_weight_v=$vitals_rows['body_weight'];
            
            
            $systoric_pressure=array();
            $diastolic_pressure=array();
            $Pulse_Blood=array();
            $Temperature=array();
            $Resp_Bpressure=array();
            $oxygen_saturation=array();
            $blood_transfusion=array();
            $body_weight=array();
         
            //$Blood_Pressure=array($date_v,$Blood_Pressure_v);
            ///get syto and diato
           $Blood_Pressure_array=explode("/",$Blood_Pressure_v);
           $systoric_pressure_v=$Blood_Pressure_array[1];
           $diastolic_pressure_v=$Blood_Pressure_array[0];
            if($diastolic_pressure_v>0){
                $systoric_pressure=array($date_v,$systoric_pressure_v);
                $diastolic_pressure=array($date_v,$diastolic_pressure_v);
            }
            if($Pulse_Blood_v>0){
                $Pulse_Blood=array($date_v,$Pulse_Blood_v);
            }
            if($Temperature_v>0){
               $Temperature=array($date_v,$Temperature_v); 
            }
            if($Resp_Bpressure_v>0){
              $Resp_Bpressure=array($date_v,$Resp_Bpressure_v);  
            }
            
            if($oxygen_saturation_v>0){
               $oxygen_saturation=array($date_v,$oxygen_saturation_v); 
            }
            
            if($blood_transfusion_v>0){
               $blood_transfusion=array($date_v,$blood_transfusion_v); 
            }
            
            if($body_weight_v>0){
               $body_weight=array($date_v,$body_weight_v); 
            }
            
            if((sizeof($systoric_pressure)>0)){
               array_push($systoric_pressure_value, $systoric_pressure);
               array_push($diastolic_pressure_value, $diastolic_pressure); 
            }
            if((sizeof($Temperature)>0)){
                array_push($Temperature_value, $Temperature);
            }
            if((sizeof($Pulse_Blood)>0)){
                array_push($Pulse_Blood_value, $Pulse_Blood);
            }
            if((sizeof($Resp_Bpressure)>0)){
                array_push($Resp_Bpressure_value, $Resp_Bpressure);
            }
            if((sizeof($oxygen_saturation)>0)){
                array_push($oxygen_saturation_value, $oxygen_saturation);
            }
            if((sizeof($body_weight)>0)){
                array_push($body_weight_value, $body_weight);
            }
            if((sizeof($blood_transfusion)>0)){
                array_push($blood_transfusion_value, $blood_transfusion);
            }
//            if((sizeof($systoric_pressure)>0)){}
//            array_push($Blood_Pressure_value, $Blood_Pressure);
            
            
            
            
        }
    }
    $all_saved_data_result=array($systoric_pressure_value,$diastolic_pressure_value,$Pulse_Blood_value,$Temperature_value,$Resp_Bpressure_value,$oxygen_saturation_value,$blood_transfusion_value,$body_weight_value);
    $all_saved_data_result_decoded= json_encode($all_saved_data_result);
    print_r($all_saved_data_result_decoded);
}