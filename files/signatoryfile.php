<?php 

    function getAllSignature($consultation_ID, $Registration_ID,$Bill_ID,$typecode,$conn){
        global $conn;
            $signturearray = array();
            $selectConsulttion = mysqli_query($conn, "SELECT doctor_license, employee_signature, kada  FROM tbl_consultation_history ch, tbl_employee e WHERE consultation_ID = '$consultation_ID'  AND e.Employee_ID=ch.employee_ID") or die(mysqli_error($conn));
            if(mysqli_num_rows($selectConsulttion)>0){
                while($crw = mysqli_fetch_assoc($selectConsulttion)){
                    $doctor_license =  $crw['doctor_license'];
                    $kada = $crw['kada'];
                    $employee_signature = $crw['employee_signature'];
                    if (isset($employee_signature)) {
                        $path = '../esign/employee_signatures/'. $employee_signature;
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        //'data:image/' . $type . ';base64,' .
                        $Encodedsignature = base64_encode($data);
                    } else {
                        $Consultant_signature = '________________';
                        $Encodedsignature=base64_encode($Consultant_signature);
                    }
                    $empsignatory1 =array(
                        "Signatory"=>'PRACTITIONER',
                        "SignatoryID"=>$doctor_license,
                        "SignatureData"=>$Encodedsignature
                    );
                    
                }
                array_push($signturearray, $empsignatory1);
            }
            
        
            $selectConsulttionround =  mysqli_query($conn,"SELECT doctor_license, employee_signature, kada  FROM tbl_ward_round wr JOIN tbl_employee e ON wr.employee_ID=e.employee_ID  WHERE wr.consultation_ID='$consultation_ID' AND wr.Registration_ID='$Registration_ID' AND Process_Status ='served' GROUP BY wr.employee_ID ") or die(mysqli_error($conn));
            if(mysqli_num_rows($selectConsulttionround)>0){
                while($crw = mysqli_fetch_assoc($selectConsulttionround)){
                    $doctor_license =  $crw['doctor_license'];
                    $kada = $crw['kada'];
                    $employee_signature = $crw['employee_signature'];
                    if (isset($employee_signature)) {
                        $path = '../esign/employee_signatures/'. $employee_signature;
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        //'data:image/' . $type . ';base64,' .
                        $Encodedsignature = base64_encode($data);
                       
                    } else {
                        $Consultant_signature = '________________';
                        $Encodedsignature=base64_encode($Consultant_signature);
                    }
                    $empsignatory2 =array(
                        "Signatory"=>'PRACTITIONER',
                        "SignatoryID"=>$doctor_license,
                        "SignatureData"=>$Encodedsignature
                    );
                    
                }
                array_push($signturearray, $empsignatory2);
            }
            

        $selectpatient = mysqli_query($conn, "SELECT Member_Number, patient_signature  FROM tbl_patient_registration  WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($selectpatient)>0){
            while($crw = mysqli_fetch_assoc($selectpatient)){
                $Member_Number =  $crw['Member_Number'];
                $kada = $crw['kada'];
                $patient_signature = $crw['patient_signature'];
                if (isset($patient_signature)) {
                    $path = '../esign/patients_signature/'.$patient_signature;
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    //'data:image/' . $type . ';base64,' .
                    $Encodedsignature = base64_encode($data);
                } else {
                    $Consultant_signature = '________________';
                    $Encodedsignature=base64_encode($Consultant_signature);
                }
                $patientsignatory =array(
                    "Signatory"=>"PATIENT",
                    "SignatoryID"=>$Member_Number,
                    "SignatureData"=>$Encodedsignature
                );
                
            }
            array_push($signturearray, $patientsignatory);
        }
        // $allsignatory = array_merge($patientsignatory, $empsignatory1);
        
        return $signturearray;
    }