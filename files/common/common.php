<?php 
    declare(strict_types=1);
    require 'common.interface.php';
    $Interface = new CommonInterface();

    if($_GET['load_out_patient_list']){
        $output = "";
        $count = 1;
        $clinic_id = $_GET['clinic_id'];
        $status = $_GET['status'];
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $patient_name = $_GET['patient_name'];
        $patient_number = $_GET['patient_number'];
        $patient_phone_number = $_GET['patient_phone_number'];

        $PatientList = $Interface->getOutpatientListByClinic($clinic_id,$status,$start_date,$end_date,$patient_name,$patient_number,$patient_phone_number);

        if(sizeof($PatientList) > 0){
            foreach($PatientList as $Patient){
                $patient_age = $Interface->getCurrentPatientAge($Patient['Date_Of_Birth']);
                $output .= "
                    <tr style='background-color: #fff;' onclick='toPhysiotherapyNotes({$Patient['Patient_Payment_Item_List_ID']},{$Patient['Registration_ID']},$clinic_id)'>
                        <td style='padding:8px'><center><a href='physiotherapy_clinical_note_page.php'>".$count++."</a></center></td>
                        <td style='padding:8px'>".ucwords($Patient['Patient_Name'])."</td>
                        <td style='padding:8px'>{$Patient['Registration_ID']}</td>
                        <td style='padding:8px'>{$patient_age}</td>
                        <td style='padding:8px'>{$Patient['Sponsor_Name']}</td>
                        <td style='padding:8px'>{$Patient['Gender']}</td>
                        <td style='padding:8px'>{$Patient['Phone_Number']}</td>
                    </tr>
                ";
            }
        }else{
            $output .= "
                <tr style='background-color: #fff;'>
                    <td style='padding:8px;text-align:center;font-weight:500;color:red' colspan='7'>NO PATIENT FOUND BETWEEN {$start_date} ~ {$end_date}</td>
                </tr>
            ";
        }
        echo $output;
    }

    if($_GET['load_inpatient_list']){
        $output = "";
        $count = 1;
        $hospital_ward_id = $_GET['hospital_ward_id'];
        $Clinic_ID = $_GET['Clinic_ID'];
        $patient_name = $_GET['patient_name'];
        $patient_number = $_GET['patient_number'];
        $PatientList = $Interface->getInpatientListByWard($hospital_ward_id,$patient_name,$patient_number);

        if(sizeof($PatientList) > 0){
            foreach($PatientList as $Patient){
                $patient_age = $Interface->getCurrentPatientAge($Patient['Date_Of_Birth']);
                $output .= "
                    <tr style='background-color: #fff;' onclick='toPhysiotherapyNotes({$Patient['Admision_ID']},{$Patient['Registration_ID']},{$Patient['consultation_ID']},{$Clinic_ID})'>
                        <td style='padding:8px'><center><a href='physiotherapy_clinical_note_page.php'>".$count++."</a></center></td>
                        <td style='padding:8px'>".ucwords($Patient['Patient_Name'])."</td>
                        <td style='padding:8px'>{$Patient['Registration_ID']}</td>
                        <td style='padding:8px'>{$patient_age}</td>
                        <td style='padding:8px'>{$Patient['Guarantor_Name']}</td>
                        <td style='padding:8px'>{$Patient['Gender']}</td>
                        <td style='padding:8px'>{$Patient['Phone_Number']}</td>
                    </tr>
                ";
            }
        }else{
            $output .= "<tr style='background-color: #fff;'><td style='padding:8px;text-align:center;font-weight:500;color:red' colspan='7'>NO PATIENT FOUND</td></tr>";
        }
        echo $output;
    }

    if($_GET['load_diseases']){
        $output = "";
        $disease_name = $_GET['disease_name'];
        $Consultation_ID = $_GET['Consultation_ID'];
        $other = $_GET['other'];
        $Diseases = $Interface->getDiseases($disease_name);

        if(sizeof($Diseases) > 0){
            foreach($Diseases as $diseases){
                $output .="
                    <tr style='background-color: #fff;'>
                        <td style='padding: 6px;padding-top: 6px;'><input name='disease' onclick='$other({$diseases['disease_ID']},$Consultation_ID)' type='radio'></td>
                        <td style='padding: 8px;'>{$diseases['disease_name']} ~ <b>{$diseases['disease_code']}</b></td>
                    </tr>
                ";
            }
        }else{
            $output .="<tr style='background-color: #fff;'><td style='padding: 8px;font-weight:500;' colspan='2'>NO DISEASE FOUND ~ {$disease_name}</td></tr>";
        }
        echo $output;
    }

    # if 1 provisional else 2 final
    if($_GET['load_diagnosis_for_inpatient']){
        $output = "";
        $type ="";
        $diagnosis__ = "";
        $count = 1;
        $Consultation_ID = $_GET['Consultation_ID'];
        $diagnosis_type = $_GET['diagnosis_type'];
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
        $patient_type = $_GET['patient_type'];
        if($diagnosis_type == "provisional_diagnosis"){
            $diagnosis__ = 1;
            $type = "provisional_diagnosis";
        }else{
            $diagnosis__ = 2;
            $type = "diagnosis";
        }

        $Diseases = $Interface->getDiseasesByConsultation($Consultation_ID,$diagnosis_type,$patient_type);
        if(sizeof($Diseases) > 0){
            foreach($Diseases as $disease){
                $output .= "
                    <tr style='background-color: #fff;'>
                        <td style='padding: 6px;text-align:center' width='10%'>".$count++."</td>
                        <td style='padding: 6px;'>{$disease['disease_name']}</td>
                        <td style='padding: 6px;' width='20%'>{$disease['disease_code']}</td>
                        <td style='padding: 2px;' width='20%'><a class='art-button-green' onclick='removeDiagnosis({$disease['disease_ID']},{$diagnosis__})' style='color:white'>REMOVE</a></td>
                    </tr>
                ";
            }
        }else{
            $output .= "<tr><td colspan='4' style='background-color:#fff;padding:8px;font-weight:500;text-align:center'>NO DIAGNOSIS ADDED YET</td></tr>";
        }
        echo $output;
    }

    if ($_POST['add_diagnosis_to_a_patient']) {
        $disease_id = $_POST['Diseases_ID'];
        $Consultation_ID = $_POST['Consultation_ID'];
        $Employee_ID = $_POST['Employee_ID'];
        $diagnosis_type = $_POST['diagnosis_type'];
        $patient_type = isset($_POST['patient_type']) ? 'inpatient' : "";

        echo $Interface->createNewDiagnosisForAPatient($Consultation_ID,$disease_id,$Employee_ID,$diagnosis_type,$patient_type);
    }

    if ($_POST['remove_disease_outpatient_list']) {
        $diagnosis_type = $_POST['type'];
        $disease_id = $_POST['disease_id'];
        $Consultation_ID = $_POST['Consultation_ID'];

        echo $Interface->removeDiseasesForOutpatientList($Consultation_ID,$disease_id,$diagnosis_type);
    }

    if($_GET['load_items']){
        $output = "";
        $sponsor_id = $_GET['Sponsor_ID'];
        $consultation_type = $_GET['consultation_type'];
        $procedure_name_ = $_GET['procedure_name_'];
        $ItemPrice = $Interface->getItemWithPrice($sponsor_id,$consultation_type,$procedure_name_,"");

        if(sizeof($ItemPrice) > 0){
            foreach($ItemPrice as $Item){
                $output .= "
                    <tr style='background-color: #fff;'>
                        <td style='padding: 6px;padding-top: 6px;'><input type='radio' name='items' onclick='getItemDetails({$Item['Item_ID']})'></td>
                        <td style='padding: 8px;'>{$Item['Product_Name']}</td>
                    </tr>
                ";
            }
        }else{
            $output .= "<tr style='background-color:#fff'><td colspan='2' style='text-align:center;padding:8px'>NO PROCEDURE FOUND</td></tr>";
        }
        echo $output;
    }

    if($_POST['get_single_item_details']){
        $Item_ID = $_POST['param'];
        $Sponsor_ID = $_POST['Sponsor_ID'];
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
        
        echo json_encode($Interface->getSingleItems($Item_ID,$Sponsor_ID,$Sub_Department_ID));
    }

    if($_POST['add_item_to_patient']){
        $Consultation_ID = $_POST['Consultation_ID'];
        $Sponsor_ID = $_POST['Sponsor_ID'];
        $Item_ID = $_POST['item_id'];
        $Registration_ID = $_POST['Registration_ID'];
        $Quantity = $_POST['quantity'];
        $Employee_ID = $_POST['Employee_ID'];
        $Check_In_Type = $_POST['Check_In_Type'];
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
        $Doctor_Comment = $_POST['Doctor_Comment'];
        $Procedure_Location = $_POST['Procedure_Location'];

        echo $Interface->AddItemForAPatientOutpatient($Consultation_ID,$Registration_ID,$Employee_ID,$Sponsor_ID,$Check_In_Type,$Item_ID,$Quantity,$Sub_Department_ID,$Doctor_Comment,$Procedure_Location);
    }

    if($_GET['load_already_added_items']){
        $output = "";
        $count = 1;
        $Consultation_ID = $_GET['Consultation_ID'];
        $Check_In_Type = $_GET['Check_In_Type'];
        $Registration_ID = $_GET['Registration_ID'];
        $Items_Details = $Interface->getAlreadyAddedItems($Registration_ID,$Check_In_Type,$Consultation_ID);

        if(sizeof($Items_Details) > 0){
            $cash_total = 0;
            $credit_total = 0;
            foreach ($Items_Details as $Item) {
                $formatted_Price = number_format((int) $Item['Price'],2);
                $check_transaction_cash = (strtolower($Item['Transaction_Type']) == "cash") ? (int) $Item['Price'] : 0;
                $check_transaction_ = (strtolower($Item['Transaction_Type']) == "cash") ? 0 : (int) $Item['Price'];

                $cash_total +=  $check_transaction_cash;
                $credit_total +=  $check_transaction_;

                $output .= "
                    <tr style='background-color: #fff;'>
                        <td style='text-align: center;padding:8px'>".$count++."</td>
                        <td style='text-align: start;padding:8px'>{$Item['Product_Name']}</td>
                        <td style='text-align: center;padding:8px'>{$Item['Quantity']}</td>
                        <td style='text-align: right;padding:8px'>".number_format($check_transaction_cash,2)."</td>
                        <td style='text-align: right;padding:8px'>".number_format($check_transaction_,2)."</td>
                        <td style='text-align: center;'><input type='button' class='art-button-green' onclick='removeAddedProcedure({$Item['Payment_Item_Cache_List_ID']})' value='REMOVE'/></td>
                    </tr>
                ";
            }

            $output .= "
                <tr>
                    <td colspan='3' style='padding:8px;font-weight:500'>TOTAL</td>
                    <td style='text-align:right;padding:8px;font-weight:500'>".number_format($cash_total,2)."</td>
                    <td style='text-align:right;padding:8px;font-weight:500'>".number_format($credit_total,2)."</td>
                </tr>
            ";
        
        }else{
            $output .= "
                <tr style='background-color: #fff;'>
                    <td style='text-align: center;padding:8px' colspan='8'>NO ADDED YET</td>
                </tr>
            ";
        }
        echo $output;
    }

    if($_POST['remove_the_selected_item_from_a_patient']){
        $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
        echo $Interface->deleteItemForAPatient($Payment_Item_Cache_List_ID);
    }

    if($_POST['authenticate_user']){
        $supervisor_username = $_POST['supervisor_username'];
        $supervisor_password = $_POST['supervisor_password'];

        echo $Interface->authenticateUser($supervisor_username,$supervisor_password);
    }


    if($_POST['save_physiotherapy_notes_outpatient']){
        $Patient_Payment_Item_List_ID = $_POST['Patient_Payment_Item_List_ID'];
        $Employee_ID = $_POST['Employee_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $main_complain = $_POST['main_complain'];
        $history_of_past_illness = $_POST['history_of_past_illness'];
        $past_medical_history = $_POST['past_medical_history'];
        $relevant_social_and_family_history = $_POST['relevant_social_and_family_history'];
        $case_type = $_POST['case_type'];
        $general_observation = $_POST['general_observation'];
        $local_examination = $_POST['local_examination'];
        $neurovascular = $_POST['neurovascular'];
        $functional = $_POST['functional'];
        $procedure_comments = $_POST['procedure_comments'];
        $evolution = $_POST['evolution'];

        echo $Interface->savePhysiotherapyNotesOutpatient(
            $Patient_Payment_Item_List_ID,$Registration_ID,
            $Employee_ID,$main_complain,$history_of_past_illness,$past_medical_history,
            $relevant_social_and_family_history,$case_type,
            $general_observation,$local_examination,$neurovascular,
            $functional,$procedure_comments,$evolution
        );
    }

    if($_POST['save_inpatient_note_for_physiotherapy']){
        $Admission_ID = $_POST['Admission_ID'];
        $Consultation_ID = $_POST['Consultation_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $Employee_ID = $_POST['Employee_ID'];
        $main_complain = $_POST['main_complain'];
        $findings = $_POST['findings'];
        $neurovascular = $_POST['neurovascular'];
        $functional = $_POST['functional'];
        $remarks = $_POST['remarks'];
        
        echo $Interface->saveInpatientNotes($Admission_ID,$Consultation_ID,$Registration_ID,$Employee_ID,$main_complain,$findings,$neurovascula,$functional,$remarks);
    }

    if($_POST['insert_psychiatric_assessment_form']){
        $Patient_Payment_Item_List_ID = $_POST['Patient_Payment_Item_List_ID'];
        $Consultation_ID = $_POST['Consultation_ID'];
        $Employee_ID = $_POST['Employee_ID'];
        $Sponsor_ID = $_POST['Sponsor_ID'];
        $Registration_ID = $_POST['Registration_ID'];
        $main_complain = $_POST['main_complain'];
        $medical_history = $_POST['medical_history'];
        $past_medical_history = $_POST['past_medical_history']; 
        $mental_status_examination = $_POST['mental_status_examination'];
        $gross_motor_skills = $_POST['gross_motor_skills'];
        $fine_motor_skills = $_POST['fine_motor_skills'];
        $memory_daily_task_faces_names = $_POST['memory_daily_task_faces_names'];
        $attention_divided = $_POST['attention_divided'];
        $problem_solving = $_POST['problem_solving'];
        $communication_receptive_expressive_abilities = $_POST['communication_receptive_expressive_abilities'];
        $psycho_emotion_changes = $_POST['psycho_emotion_changes'];
        $feeding = $_POST['feeding'];
        $grooming_washing = $_POST['grooming_washing'];
        $toileting = $_POST['toileting'];
        $dressing = $_POST['dressing'];
        $leisures = $_POST['leisures'];
        $productivity_works = $_POST['productivity_works'];
        $performance_context = $_POST['performance_context'];
        $goals = $_POST['goals'];
        $procedure_remarks = $_POST['procedure_remarks'];
        $progress_notes = $_POST['progress_notes'];
    }

    if($_GET['search_pharmacy_items']){
        $output = "";
        $Product_Name = $_GET['Product_Name'];
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
        $Result = $Interface->getItemWithPrice(13,"Pharmacy",$Product_Name,$Sub_Department_ID);

        if(sizeof($Result) > 0){
            foreach($Result as $Product ) : 
                $output .= "<tr><td style='padding: 5px;' width='10%'><center><input name='Items' onclick='selectMedication()' type='radio'></center></td> <td style='padding: 8px;' width='90%'>{$Product['Product_Name']}</span></td></tr>";
            endforeach;
        }else{
            $output = "<tr><td colspan='2' style='padding: 8px;text-align:center;color:red' width='90%'>NO PRODUCT FOUND WITH NAME ~ {$Product_Name}</span></td></tr>";
        }
        echo $output;
    }

    if($_GET['get_item_balance']){
        echo $Interface->getItemBalanceFromItem($_GET['Sub_Department_ID'],$_GET['Item_ID']);
    }

    if($_POST['add_pharmacy_item_for_inpatient']){
        $Patient_Transaction_Details = $_POST['Patient_Details'];
        echo $Interface->createItemToAPatient($Patient_Transaction_Details);
    }

    if($_POST['autoSavePatientNotes']){
        $inputObject = $_POST['inputObject'];
        echo $Interface->autoSaveNotes($inputObject);
    }
?>
