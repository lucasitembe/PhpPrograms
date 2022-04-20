<?php 
    // declare(strict_types = 1);
    include 'common.repo.config.php';

    class CommonInterface extends CommonRepo{
        function getOutpatientListByClinic($clinic_id,$status,$start_date,$end_date,$patient_name,$patient_number,$patient_phone_number){
            return json_decode($this->fetchOutpatientList($clinic_id,$status,$start_date,$end_date,$patient_name,$patient_number,$patient_phone_number),true);
        }

        function getCurrentPatientAge($patient_date_of_birth){
            return $this->fetchPatientCurrentAge($patient_date_of_birth);
        }

        function getCurrentDateTime(){
            return $this->fetchCurrentDateTime();
        }

        function getWardsAssignedToEmployee($employee_id){
            return json_decode($this->fetchWardsAssignedToEmployee($employee_id),true);
        }

        function getInpatientListByWard($hospital_ward_id,$patient_name,$patient_number){
            return json_decode($this->fetchInpatientListByWard($hospital_ward_id,$patient_name,$patient_number),true);
        }

        function getVitals(){
            return json_decode($this->fetchVitals(),true);
        }

        function getDiseases($disease_name){
            return json_decode($this->fetchDiseases($disease_name),true);
        }

        function createConsultationForPatient($patient_payment_item_list_id,$registration_id,$employee_id){
            return json_decode($this->createConsultation($patient_payment_item_list_id,$registration_id,$employee_id),true);
        }

        function getDiseasesByConsultation($consultation_id,$diagnosis,$patient_type){
            return json_decode($this->fetchDiseasesAddedInConsultation($consultation_id,$diagnosis,$patient_type),true);
        }

        function createNewDiagnosisForAPatient($consultation_id,$disease_id,$employee_id,$diagnosis_type,$patient_type){
            return json_decode($this->createNewDiagnosis($consultation_id,$disease_id,$employee_id,$diagnosis_type,$patient_type),true);
        }

        function removeDiseasesForOutpatientList($consultation_id,$disease_id,$diagnosis_type){
            return $this->removeDiseasesForOutpatient($consultation_id,$disease_id,$diagnosis_type);
        }

        function getItemWithPrice($sponsor_id,$consultation_type,$procedure_name_,$Sub_Department_ID){
            return json_decode($this->fetchItems($sponsor_id,$consultation_type,$procedure_name_,$Sub_Department_ID),true);
        }

        function getSingleItems($item_id,$sponsor_id,$Sub_Department_ID){
            return json_decode($this->fetchSingleItemDetails($item_id,$sponsor_id,$Sub_Department_ID),true);
        }

        function getSubdepartmentLocation($department_location){
            return json_decode($this->fetchSubdepartment($department_location),true);
        }


        function AddItemForAPatientOutpatient($Consultation_ID,$Registration_ID,$Employee_ID,$Sponsor_ID,$Check_In_Type,$Item_ID,$Quantity,$Sub_Department_ID,$Doctor_Comment,$Procedure_Location){
            return $this->createItemForPatient($Consultation_ID,$Registration_ID,$Employee_ID,$Sponsor_ID,$Check_In_Type,$Item_ID,$Quantity,$Sub_Department_ID,$Doctor_Comment,$Procedure_Location);
        }

        function getAlreadyAddedItems($Registration_ID,$Check_In_Type,$Consultation_ID){
            return json_decode($this->fetchActiveItemGivenToAPatient($Registration_ID,$Check_In_Type,$Consultation_ID),true);
        }

        function deleteItemForAPatient($Payment_Item_Cache_List_ID){
            return $this->removeItemFromAPatient($Payment_Item_Cache_List_ID);
        }

        function getEmployeeAssignedClinic($Employee_ID,$Clinic_ID,$template){
            return json_decode($this->fetchEmployeeAssignedClinic($Employee_ID,$Clinic_ID,$template),true);
        }

        function authenticateUser($Supervisor_Username,$Supervisor_Password){
            return $this->userAuthentication($Supervisor_Username,$Supervisor_Password);
        }

        function getSinglePatientDetailsForParticularConsultation($Registration_ID,$Patient_Payment_Item_List_ID){
            return json_decode($this->fetchSinglePatientDetailsForParticularConsultation($Registration_ID,$Patient_Payment_Item_List_ID),true);
        }

        function getClinicTemplate($Clinic_ID){
            return json_decode($this->fetchClinicTemplate($Clinic_ID),true);
        }

        function savePhysiotherapyNotesOutpatient($Patient_Payment_Item_List_ID,$Registration_ID,$Employee_ID,$main_complain,$history_of_past_illness,$past_medical_history,$relevant_social_and_family_history,$case_type,$general_observation,$local_examination,$neurovascular,$functional,$procedure_comments,$evolution){
            return $this->createPhysiotherapyNotesOutpatient($Patient_Payment_Item_List_ID,$Registration_ID,$Employee_ID,$main_complain,$history_of_past_illness,$past_medical_history,$relevant_social_and_family_history,$case_type,$general_observation,$local_examination,$neurovascular,$functional,$procedure_comments,$evolution);
        }

        function getPatientInformationByRegistrationNumber($Registration_ID){
            return json_decode($this->fetchPatientInformationByRegistrationNumber($Registration_ID),true);
        }

        function saveInpatientNotes($Admission_ID,$Consultation_ID,$Registration_ID,$Employee_ID,$main_complain,$finding,$neurovascula,$functional,$remarks){
            return $this->createInpatientNotes($Admission_ID,$Consultation_ID,$Registration_ID,$Employee_ID,$main_complain,$finding,$neurovascula,$functional,$remarks);
        }

        function getItemBalanceFromItem($Sub_Department_ID,$Item_ID){
            return $this->fetchBalanceForItem($Sub_Department_ID,$Item_ID);
        }

        function createItemToAPatient($Patient_Transaction_Details){
            return $this->addItemFormInpatient($Patient_Transaction_Details);
        }

        function autoSaveNotes($inputObject){
            return $this->autoSavePatientNotes($inputObject);
        }
    }
?>