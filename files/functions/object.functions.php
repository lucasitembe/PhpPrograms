<?php
include("../includes/connection.php");
function ret($conn,$query){
    $sql_query = mysqli_query($conn,$query);
    $result = array();
    while($data = mysqli_fetch_assoc($sql_query)){
        array_push($result,$data);
    }
    return json_encode($result);
}
//CREATING RECEIPT FUNCTION
    function createPaymentGeneral($conn, $Registration_ID, $Payment_Cache_ID, $Items){
        // die("SELECT pc.Sponsor_ID, ci.Folio_Number, pc.Billing_Type, c.Check_In_ID FROM tbl_payment_cache pc, tbl_consultation c, tbl_check_in ci WHERE pc.Payment_Cache_ID = '$Payment_Cache_ID' AND pc.Registration_ID = '$Registration_ID' AND c.consultation_ID = pc.consultation_id AND ci.Check_In_ID = c.Check_In_ID");
        $Select_details = mysqli_query($conn, "SELECT pc.Sponsor_ID, ci.Folio_Number, pc.Billing_Type, c.Check_In_ID FROM tbl_payment_cache pc, tbl_consultation c, tbl_check_in ci WHERE pc.Payment_Cache_ID = '$Payment_Cache_ID' AND pc.Registration_ID = '$Registration_ID' AND c.consultation_ID = pc.consultation_id AND ci.Check_In_ID = c.Check_In_ID");
    }


//CREATE CONSULTATION ID
    function createConcultationGeneral($conn, $Registration_ID, $Check_In_ID, $Patient_Payment_Item_List_ID, $Clinic_ID, $Employee_ID){
        $Insert_Nurse_consultation = mysqli_query($conn, "INSERT INTO tbl_consultation(employee_ID, Registration_ID, Check_InID, Patient_Payment_Item_List_ID, Clinic_ID,Process_Status, Consultation_Date_And_Time, Clinic_consultation_date_time) VALUES('$Employee_ID', '$Registration_ID', '$Check_In_ID', '$Patient_Payment_Item_List_ID', '$Clinic_ID', 'pending', NOW(), NOW())");
        if($Insert_Nurse_consultation){
            $Consultation_ID = mysqli_insert_id($conn);
            $Update_check_in_details = mysqli_query($conn, "UPDATE tbl_check_in_details SET consultation_ID = '$Consultation_ID' WHERE Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
                if($Update_check_in_details){
                }
            return $Consultation_ID;
        }
    }


//SELECT WARDS FUNCTION
    function SelectActiveGeneralWards($conn, $Ward_Type){
        // die("SELECT Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward WHERE ward_status = 'active' AND ward_type = '$Ward_Type'");
            return ret($conn, "SELECT Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward WHERE ward_status = 'active' AND ward_type = '$Ward_Type'");
    }
?>