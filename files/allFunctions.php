<?php
    declare(strict_types=1);
    require 'patientDetails.php';

    function getPatientDetails($Registration_ID){
        $data = new patientDetails();
        return $data->getAllPatientDetails($Registration_ID);
    }

    function getPatientDirectCash($Registration_ID,$Patient_Bill_ID){
        $data = new patientDetails();
        return $data->getAllPatientDirectCash($Registration_ID,$Patient_Bill_ID);         
    }

    function getPatientTotalBill($Registration_ID,$Patient_Bill_ID){
        $data = new patientDetails();
        return $data->getPatientTotalBillAll($Registration_ID,$Patient_Bill_ID);
    }

    function getPatientServiceBilled($Registration_ID,$Patient_Bill_ID){
        $data = new patientDetails();
        return $data->getPatientAllServiceBilled($Registration_ID,$Patient_Bill_ID);
    }

    function getMsamahaComments($Registration_ID, $Payment_Cache_ID){
        $data = new patientDetails();
        return $data->getPtMsamahaComments($Registration_ID, $Payment_Cache_ID);
    }

    function getClinicAttandance($filter, $Clinic_ID){
        $data = new patientDetails();
        return $data->getClinicAttandanceConsultation($filter, $Clinic_ID);
    }

    function get_Sponsor($Sponsor, $SponsorStatus){
        $data = new patientDetails();
        return $data->fetchSponsor($Sponsor, $SponsorStatus);
    }
    
   
    function getEmployeeAssignedClinic($Employee_ID,$Clinic_ID,$templates_status, $ClinicStatus){       
        $data = new patientDetails();
        return $data->fetchEmployeeAssignedClinic($Employee_ID,$Clinic_ID,$templates_status, $ClinicStatus);
    }

    function Get_OutPatient_CLinic_List($startdate, $enddate, $Sponsor_ID, $Clinic_ID, $Nature_Of_List){       
        $data = new patientDetails(); 
        return $data->OutPatient_CLinic_List($startdate, $enddate, $Sponsor_ID, $Clinic_ID, $Nature_Of_List);
    }

    function getAdmissioncount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage ,$admisiontime, $agetype,$patitent_type, $reporttype, $valuesType){
        
        $data = new patientDetails();
        return $data->FetchAdmissionCount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage ,$admisiontime, $agetype,$patitent_type, $reporttype, $valuesType);
    }

    function getTransferCount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage , $admisiontime, $agetype,$patitent_type, $transferType, $valuesType){
        $data = new patientDetails();
        return $data->FetchTransferCount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage ,$admisiontime, $agetype,$patitent_type, $transferType, $valuesType);
    }

    function getDischargeCount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage , $admisiontime, $agetype,$patitent_type, $dischargeType, $valuesType){
        $data = new patientDetails();
        return $data->FetchDischargeCount($startdate, $enddate, $Sponsor_ID, $Hospital_Ward_ID,  $startage, $endage ,$admisiontime, $agetype,$patitent_type, $dischargeType, $valuesType);
    }
    
    function getCurrentPatientAge($patient_date_of_birth){
        $data = new patientDetails();
        return $data->fetchPatientCurrentAge($patient_date_of_birth);
    }
    function getHospitalaWard($Hospital_Ward_ID, $wardType, $ward_status ){
        $data = new patientDetails();
        return $data->fetchHospitalaWard($Hospital_Ward_ID, $wardType, $ward_status );
    }

    function getRoundDisease($consultation_ID, $patientstatus){
        $data = new patientDetails();
        return $data->fetchRoundDisease($consultation_ID, $patientstatus);
    }

    function getWardRoom($Hospital_Ward_ID){
        $data= new patientDetails();
        return $data->fetchWardRoom($Hospital_Ward_ID);
    }

    function getPatientAdmWard($Registration_ID, $Admision_ID){
        $data = new patientDetails();
        return $data->fetchPatientAdmWard($Registration_ID, $Admision_ID);
    }

    function getnurseRequestExcemption($Registration_ID,$Patient_Bill_ID){
        $data = new patientDetails();
        return $data->fetchNurseRequestExcemption($Registration_ID, $Patient_Bill_ID);
    }
    function getBillByCategory($Patient_Bill_ID,$Registration_ID, $Billing_Type){
        $data= new patientDetails();
        return $data->fetchBillByCategory($Patient_Bill_ID,$Registration_ID, $Billing_Type);
    }

    function getBillItems($Patient_Bill_ID,$Registration_ID, $Billing_Type, $Item_category_ID){
        $data= new patientDetails();
        return $data->fetchBillItems($Patient_Bill_ID,$Registration_ID, $Billing_Type, $Item_category_ID);
    }
    function getRowExemption($Registration_ID,$Patient_Bill_ID){
        $data = new patientDetails();
        return $data->fetchRowExemption($Registration_ID, $Patient_Bill_ID);
    }

    function getConsultationAdmin($Check_In_ID){
        $data = new patientDetails();
        return $data->fetchConsultationAdmin($Check_In_ID);
    }

    function getAdmisionDetails($Check_In_ID, $patientStatus){
        $data = new patientDetails();
        return $data->fetchAdmisionDetails($Check_In_ID, $patientStatus);
    }
    
    function getDataByReceipt($Registration_ID, $Patient_Bill_ID, $Check_In_ID, $Billing_Type){
        $data=new patientDetails();
        return $data->fetchDataByReceipt($Registration_ID, $Patient_Bill_ID, $Check_In_ID, $Billing_Type);
    }

    function getItemByReceipt($Patient_Payment_ID){
        $data=new patientDetails();
        return $data->fetchItemByReceipt($Patient_Payment_ID);
    }

    function PatientTotalBills($Registration_ID,$Patient_Bill_ID, $Billing_Type){
        $data=new patientDetails();
        return $data->fetchPatientTotalBill($Registration_ID, $Patient_Bill_ID, $Billing_Type);
    }

    function getEmployeeDetails($Employee_ID){
        $data= new patientDetails();
        return $data->fetchEmployeeDetails($Employee_ID);
    }

    function getExemptionCreteria($seachValue, $msamahaitemId){
        $data= new patientDetails();
        return $data->fetchExemptionCreteria($seachValue, $msamahaitemId);
    }
?>
