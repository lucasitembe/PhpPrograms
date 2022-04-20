<?php 
    include 'common/common.interface.php';
    $Clinic_ID = $_GET['clinic'];
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    $Registration_ID = $_GET['Registration_ID'];
    $Patient_Type = $_GET['Patient_Type'];

    $Result = new CommonInterface();
    $Clinic_Details = $Result->getClinicTemplate($Clinic_ID);
    $Patient_Details = $Result->getSinglePatientDetailsForParticularConsultation($Registration_ID,$Patient_Payment_Item_List_ID);

    $Patient_Age = $Result->getCurrentPatientAge($Patient_Details[0]['Date_Of_Birth']);

    $age = explode(",",$Patient_Age);
    $age_ = $age[0];
    $age__ = explode(" ",$age_);
    $ageInYears = (int) $age__[0];

    if($Patient_Type == "Outpatient"){
        if($Clinic_Details[0]['template'] == "physiotherapy"){
            header("Location: physiotherapy_clinical_note_page.php?clinic=$Clinic_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Registration_ID=$Registration_ID&Patient_Type=$Patient_Type");
        }else if($Clinic_Details[0]['template'] == "occupationaltherapy" && (int) $age__[0] > 18){
            header("Location: occupational_therapy_notes.php?clinic=$Clinic_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Registration_ID=$Registration_ID&Patient_Type=$Patient_Type");
        }else if($Clinic_Details[0]['template'] == "occupationaltherapy" && (int) $age__[0] < 18){
            header("Location: occupational_therapy_notes_paedriatric.php?clinic=$Clinic_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Registration_ID=$Registration_ID&Patient_Type=$Patient_Type");
        }
    }
?>