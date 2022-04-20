<?php 
    include 'repo.config.php';

    class PharmacyInterface extends PharmacyRepo{
        function getPatientDetailsPharmacy($Payment_Cache_ID){
            return json_decode($this->getPatientDetails($Payment_Cache_ID),true);
        }

        function sendToCashier($Payment_Cache_ID,$Transaction_Type,$newSub_Department_ID,$Check_In_Type,$selectedItem){
            return $this->sendToMedicationCashier($Payment_Cache_ID,$Transaction_Type,$newSub_Department_ID,$Check_In_Type,$selectedItem);
        }
    
        function displayPharmacyItems($Payment_Cache_ID,$Registration_ID,$Billing_Type,$Transaction_Type,$Sub_Department_ID){
            return json_decode($this->getPharmacyItems($Payment_Cache_ID,$Registration_ID,$Billing_Type,$Transaction_Type,$Sub_Department_ID),true);
        }

        function getCurrentPatientAge($patient_date_of_birth){
            return $this->fetchPatientCurrentAge($patient_date_of_birth);
        }
    
        function getDiseasesCode($Consultation_ID,$diagnosis_type){
            return json_decode($this->getConsultationDiseasesCode($Consultation_ID,$diagnosis_type),true);
        }
    
        function cashDispenseItems($Employee_ID,$Registration_ID,$selectedItem,$Sub_Department_ID){
            return $this->dispenseItemsPharmacy($Employee_ID,$Registration_ID,$selectedItem,$Sub_Department_ID);
        }
    
        function billAndDispenseMedication($Payment_Cache_ID,$Transaction_Type,$Billing_Type,$Registration_ID,$Check_In_Type,$Sub_Department_ID,$selectedItem,$Folio_Branch_ID,$Sponsor_ID,$Employee_ID){
            return $this->billAndDispenseMedicationCredit($Payment_Cache_ID,$Transaction_Type,$Billing_Type,$Registration_ID,$Check_In_Type,$Sub_Department_ID,$selectedItem,$Folio_Branch_ID,$Sponsor_ID,$Employee_ID);
        }
    
        function getDispenser(){
            return json_decode($this->getAllDispensers(),true);
        }
    
        function getSponsors($Sponsor_ID,$filter){
            return json_decode($this->readSponsorDetails($Sponsor_ID,$filter),true);
        }
    
        function filterDispenseMedicationReport($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Employee_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID){
            return json_decode($this->filterQuantityDispenseMedicationReport($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Employee_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID),true);
        }
    
        function getTodayDateTime(){
            return $this->getTodayDateTimeFromDB();
        }
    
        function getConsultantName($Consultant_ID){
            return $this->getConsultant($Consultant_ID); 
        }
    
        function getQuantityDispensedReport($dates_From,$dates_To,$Sponsor_ID,$search_item,$sub_department_id){
            return json_decode($this->getQuantityDispensed($dates_From,$dates_To,$Sponsor_ID,$search_item,$sub_department_id),true);
        } 
    
        function getQuantityDispensedPatientDetailsReport($Start_Date,$End_Date,$Sub_Department_ID,$Item_ID,$Sponsor_ID){
            return json_decode($this->getQuantityDispensedPatientDetails($Start_Date,$End_Date,$Sub_Department_ID,$Item_ID,$Sponsor_ID),true);
        }
    
        function getItem($Item_ID){
            return $this->getItemName($Item_ID);
        }
    
        function getPatientDemographicDetails($Registration_ID){
            return json_decode($this->patientDemographicDetails($Registration_ID),true);
        }
    
        function getConsultants(){
            return json_decode($this->consultants(),true);
        }
    
        function getClinicList(){
            return json_decode($this->clinicLists(),true);
        }
    
        function getWorkingDepartment(){
            return json_decode($this->workingDepartment(),true);
        }
    
        function pharmacyConfigurations(){
            return json_decode($this->configuration(),true);
        }

        function notDispensed($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID){
            return json_decode($this->notDispensedMedication($Start_Date,$End_Date,$Search_Patient,$Sponsor_ID,$Bill_Type,$Payment_Mode,$Sub_Department_ID),true);
        }

        function getDispenseListReturn($Start_date,$end_date,$Patient_Name,$Patient_Number,$Bill_Type){
            return json_decode($this->getDispenseList($Start_date,$end_date,$Patient_Name,$Patient_Number,$Bill_Type),true);
        }

        function getMedicationForReturnPatient($Payment_Cache_ID){
            return json_decode($this->medicationForReturnPatient($Payment_Cache_ID),true);
        }

        function checkIfReasonsForRemovalConfigured(){
            return $this->checkReasonsForIfConfigured();
        }

        function fetchRemovalReasons(){
            return json_decode($this->reasonForRemoval(),true);
        }

        function removePharmacyItem($reason_for_remove,$Patient_Payment_Item_List,$Item_ID){
            return $this->removeItem($reason_for_remove,$Patient_Payment_Item_List,$Item_ID);
        }

        function fetchItemPerStatus($Payment_Cache_ID){
            return json_decode($this->itemPerStatus($Payment_Cache_ID),true);
        }

        function changeItemStatus($Patient_Payment_Item_List){
            return $this->updateItemStatus($Patient_Payment_Item_List);
        }

        function fetchAllReasonsForRemoval(){
            return json_decode($this->allReasonForItemRemoval(),true);
        }

        function addReason($new_reasons){
            return $this->addNewReasons($new_reasons);
        }

        function disableEnable($Id,$status){
            return $this->enableAndDisableReasons($Id,$status);
        }

        function balanceForDuplicate($Payment_Cache_ID){
            return json_decode($this->checkBalanceForDuplicateItems($Payment_Cache_ID),true);
        }

         function returnMedicationToPharmacy($Payment_Cache_ID,$return_medication,$Employee_ID,$Sub_Department_ID,$Registration_ID){
             return json_decode($this->returnMedication($Payment_Cache_ID,$return_medication,$Employee_ID,$Sub_Department_ID,$Registration_ID),true);
         }

        function getSummaryPatientFile(){  }

        function isRestricted($Sponsor_ID,$Item_ID){
            return $this->checkIfIsInRestrictMode($Sponsor_ID,$Item_ID);
        }

        function storeVerificationDetails($Item_ID,$Registration_ID,$Payment_Item_Cache_List_ID,$treatment_authorization_no,$Employee_ID){
            return $this->storeDetail($Item_ID,$Registration_ID,$Payment_Item_Cache_List_ID,$treatment_authorization_no,$Employee_ID);
        }

        # get single or multiple sponsor details 
        function fetchSponsorDetails($Sponsor_ID,$filter){
            return $this->readSponsorDetails($Sponsor_ID,$filter);
        }

        function fetchOutpatientList_($Patient_Name,$Patient_Number,$Phone_Number){
            return json_decode($this->fetchOutpatientList($Patient_Name,$Patient_Number,$Phone_Number),true);
        }

        function fetchSingleInpatientDetails_($Patient_Name,$Patient_Number){
            return json_decode($this->fetchSingleInpatientDetails($Patient_Name,$Patient_Number),true);
        }

        function fetchPharmacySubDepartmentByEmployee_($Employee_ID){
            return json_decode($this->fetchPharmacySubDepartmentByEmployee($Employee_ID),true);
        }

        function authenticateUser_($AuthDetails){
            return $this->authenticateUser($AuthDetails);
        }
    }
?>