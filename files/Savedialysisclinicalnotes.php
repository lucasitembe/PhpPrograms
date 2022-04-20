<?php 
include("./includes/connection.php");
include("middleware/dialysisi_function.php");
@session_start();
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Registration_ID=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
$Today=date('Y-m-d');
// $Today='select now()';
// $date1 = new DateTime($Today);
// $today_start_date=mysqli_query($conn,"SELECT cast(current_date() as datetime)");
// while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
//     $Today=$start_dt_row['cast(current_date() as datetime)'];
// }

// $Today_Date = mysqli_query($conn,"SELECT CURDATE() as today");
// while($row = mysqli_fetch_array($Today_Date)){
//     $Today = $row['today'];
// }
$checkExist=  mysqli_query($conn,"SELECT dialysis_details_ID FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' AND date(Attendance_Date)=CURDATE() DESC LIMIT 1");
$numberRows=  mysqli_num_rows($checkExist);

if (isset($_POST['Payment_Item_Cache_List_ID'])){
   $Payment_Item_Cache_List_ID=$_POST['Payment_Item_Cache_List_ID'];
} else {
   $Payment_Item_Cache_List_ID='';
}

if (isset($_POST['Payment_Cache_ID'])){
   $Payment_Cache_ID=$_POST['Payment_Cache_ID']; 
} else {
   $Payment_Cache_ID=''; 
}


//  mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='active', Process_Status='served' WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Status='notsaved'") or die(mysqli_error($conn));
     
    
function UpdateCache($Payment_Item_Cache_List_ID){
    global $Employee_ID,$Payment_Cache_ID,$conn;

    $sqlUpdateCache = "UPDATE tbl_item_list_cache SET Process_Status='served',ServedDateTime=NOW(),ServedBy='$Employee_ID',Dialysis_Status='1' WHERE Payment_Cache_ID='$Payment_Cache_ID' AND (Check_In_Type ='Dialysis' OR Sub_Department_ID IN (SELECT Sub_Department_ID FROM tbl_sub_department WHERE Department_ID IN (SELECT Department_ID FROM tbl_department WHERE Department_Location='Dialysis')))";
    $query=  mysqli_query($conn,$sqlUpdateCache) or die(mysqli_error($conn));
    
//if (isset($_SESSION['userinfo']['Employee_ID'])) {
//    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
//} else {
//    $Employee_ID = 0;
//}
//
//    
//     $setUpdates = "UPDATE tbl_item_list_cache SET Status='served' WHERE Payment_Item_Cache_List_ID='" . $Payment_Item_Cache_List_ID . "'";
//                        $updateQuery = mysqli_query($conn,$setUpdates) or die(mysqli_error($conn));
//                        if ($updateQuery) {
//                            $checkPAymentStatus = "SELECT * FROM tbl_item_list_cache as ilc INNER JOIN tbl_payment_cache as pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID WHERE Payment_Item_Cache_List_ID='" . $Payment_Item_Cache_List_ID . "'";
//                            $paymentQuery = mysqli_query($conn,$checkPAymentStatus) or die(mysqli_error($conn));
//                            $rows = mysqli_fetch_assoc($paymentQuery);
//                            if (($rows['Billing_Type'] == 'Outpatient Credit') && ($rows['Transaction_Type'] == 'Credit')) {
//                                $Billing_Type = 'Outpatient Credit';
//                            } else if (($rows['Billing_Type'] == 'Inpatient Credit') && ($rows['Transaction_Type'] == 'Cash')) {
//                                $Billing_Type = 'Inpatient Cash';
//                            } else if (($rows['Billing_Type'] == 'Inpatient Credit') && ($rows['Transaction_Type'] == 'Credit')) {
//                                $Billing_Type = 'Inpatient Credit';
//                            } else if (($rows['Billing_Type'] == 'Inpatient Cash') && ($rows['Transaction_Type'] == 'Cash')) {
//                                $Billing_Type = 'Inpatient Cash';
//                            } else {
//                                $Billing_Type = "";
//                            }
//
//                            if (!empty($Billing_Type)) {
//                                $has_no_folio = false;
//                                $Folio_Number = '';
//                                $Registration_ID = $_POST['Registration_ID'];
//                                $sql_check = mysqli_query($conn,"select Check_In_ID from tbl_check_in
//                                where Registration_ID = '$Registration_ID'
//                                    order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
//
//
//                                $Check_In_ID = mysqli_fetch_assoc($sql_check)['Check_In_ID'];
//
//                                $sql_sponsor = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
//                                $Sponsor_ID = mysqli_fetch_assoc($sql_sponsor)['Sponsor_ID'];
//
//                                $select = mysqli_query($conn,"select Folio_Number,Sponsor_ID,Sponsor_Name,Claim_Form_Number,Billing_Type from tbl_patient_payments where Registration_ID = '" . $Registration_ID . "' AND Check_In_ID = '" . $Check_In_ID . "'  AND Sponsor_ID = '" . $Sponsor_ID . "' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
//
//
//
//                                if (mysqli_num_rows($select)) {
//                                    $rows_info = mysqli_fetch_array($select);
//                                    $Folio_Number = $rows_info['Folio_Number'];
//                                    $Sponsor_Name = $rows_info['Sponsor_Name'];
//                                    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
//                                    $Claim_Form_Number = $rows_info['Claim_Form_Number'];
//                                    //$Billing_Type = $Billing_Type;
//                                    //get last check in id
//                                } else {
//                                    include("./includes/Folio_Number_Generator_Emergency.php");
//                                    $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
//                                    $rows_info = mysqli_fetch_array($select);
//
//                                    $Sponsor_Name = $rows_info['Guarantor_Name'];
//                                    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
//                                    $Claim_Form_Number = $rows_info['Claim_Form_Number'];
//
//                                    if (strtolower($Sponsor_Name) == 'cash') {
//                                        $Billing_Type = "Inpatient Cash";
//                                    } else {
//                                        $Billing_Type = "Inpatient Credit";
//                                    }
//
//                                    $has_no_folio = true;
//                                }
//
//                                //get supervisor id
//                                if (isset($_SESSION['userinfo'])) {
//                                    $Supervisor_ID = $_SESSION['userinfo']['Employee_ID'];
//                                } else {
//                                    $Supervisor_ID = 0;
//                                }
//
//                                //get the last bill id
//                                $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
//                                $nums = mysqli_num_rows($select);
//                                if ($nums > 0) {
//                                    while ($row = mysqli_fetch_array($select)) {
//                                        $Patient_Bill_ID = $row['Patient_Bill_ID'];
//                                    }
//                                } else {
//                                    //insert data to tbl_patient_bill
//                                    $insert = mysqli_query($conn,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
//                                    if ($insert) {
//                                        $select = mysqli_query($conn,"select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$Registration_ID' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
//                                        $nums = mysqli_num_rows($select);
//                                        while ($row = mysqli_fetch_array($select)) {
//                                            $Patient_Bill_ID = $row['Patient_Bill_ID'];
//                                        }
//                                    }
//                                }
//                                //end of fetching patient bill id
//                                //insert data to tbl_patient_payments
//                                $insert = mysqli_query($conn,"insert into tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,
//                                                Payment_Date_And_Time,Folio_Number,Check_In_ID,Claim_Form_Number,Sponsor_ID,
//                                                Sponsor_Name,Billing_Type,Receipt_Date,branch_id,Patient_Bill_ID)
//                                            values('$Registration_ID','$Supervisor_ID','$Employee_ID',(select now()),
//                                            '$Folio_Number','$Check_In_ID','$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
//                                            '$Billing_Type',(select now()),'$Branch_ID','$Patient_Bill_ID')") or die(mysqli_error($conn));
//
//                                if ($insert) {
//                                    //get the last patient_payment_id & date
//                                    $select_details = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments where Registration_ID = '$Registration_ID' and Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
//                                    $num_row = mysqli_num_rows($select_details);
//                                    if ($num_row > 0) {
//                                        $details_data = mysqli_fetch_assoc($select_details);
//                                        $Patient_Payment_ID = $details_data['Patient_Payment_ID'];
//                                        $Receipt_Date = $details_data['Receipt_Date'];
//                                    } else {
//                                        $Patient_Payment_ID = 0;
//                                        $Receipt_Date = '';
//                                    }
//
//                                    //get data from tbl_item_list_cache
//                                    $Item_ID = $rows['Item_ID'];
//                                    $Discount = $rows['Discount'];
//                                    $Price = $rows['Price'];
//                                    $Quantity = $rows['Quantity'];
//                                    $Consultant = $rows['Consultant'];
//                                    $Consultant_ID = $rows['Consultant_ID'];
//
//
//                                    //insert data to tbl_patient_payment_item_list
//                                    if ($Patient_Payment_ID != 0 && $Receipt_Date != '') {
//                                        $insert = mysqli_query($conn,"insert into tbl_patient_payment_item_list(Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,status,Patient_Payment_ID,Transaction_Date_And_Time,ServedDateTime,ServedBy,ItemOrigin)
//                                                        values('Dialysis','$Item_ID','$Discount','$Price','$Quantity','others','$Consultant','$Consultant_ID','Served','$Patient_Payment_ID',(select now()),(select now()),'$Employee_ID','Doctor')") or die(mysqli_error($conn));
//                                        if ($insert) {
//                                            $finalResult = mysqli_query($conn,"update tbl_item_list_cache set Patient_Payment_ID = '$Patient_Payment_ID', Payment_Date_And_Time = '$Receipt_Date' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
//
////                                            if ($finalResult) {
////                                                $message = "Sucessfully submitted to Results Team!";
////                                            }
//                                        }
//
//                                        //check if this user has folio 
//
//                                        if ($has_no_folio) {
//                                            $result_cd = mysqli_query($conn,"SELECT Check_In_Details_ID FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID IS NOT NULL ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));
//                                            $Check_In_Details_ID = mysqli_fetch_assoc($result_cd)['Check_In_Details_ID'];
//                                            $update_checkin_details = "UPDATE tbl_check_in_details SET Folio_Number='$Folio_Number'
//                                                    WHERE Check_In_Details_ID='$Check_In_Details_ID' ";
//                                            mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
//                                        }
//                                    }
//                                }
//                            }
//                        }
//   
   
   
}

// @dnm
if(isset($_POST['prepareTempNeckLine'])){
    // print_r($_POST);
    $Registration_ID = $_POST['Registration_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Get_US_Neck = $_POST['Get_US_Neck'];
    $Get_catheter = $_POST['Get_catheter'];
    $Get_sterile = $_POST['Get_sterile'];
    $Lignocaine = $_POST['Lignocaine'];
    $Sterile_KY_gel = $_POST['Sterile_KY_gel'];
    $Syringes = $_POST['Syringes'];
    $Normal_saline = $_POST['Normal_saline'];
    $Heparin = $_POST['Heparin'];
    $Surgical_Blade = $_POST['Surgical_Blade'];
    $Povidone = $_POST['Povidone'];
    $Sterile_gloves = $_POST['Sterile_gloves'];
    $Female_condom = $_POST['Female_condom'];
    $Sutures = $_POST['Sutures'];
    $Dressing_Blandle = $_POST['Dressing_Blandle'];
    $Suture_set = $_POST['Suture_set'];
    $prepare_procedure_remarks = $_POST['prepare_procedure_remarks'];
    $Lie_patient = $_POST['Lie_patient'];
    $Explain_what_you_are_doing = $_POST['Explain_what_you_are_doing'];
    $Sterile_technique = $_POST['Sterile_technique'];
    $Paint = $_POST['Paint'];
    $Drape_the_patient = $_POST['Drape_the_patient'];
    $Drape_the_US_probe = $_POST['Drape_the_US_probe'];
    $Arrange = $_POST['Arrange'];
    $Look_for_the_vein = $_POST['Look_for_the_vein'];
    $Veno = $_POST['Veno'];
    $Check_the_position = $_POST['Check_the_position'];
    $Small_Cut = $_POST['Small_Cut'];
    $Dilate_the_track = $_POST['Dilate_the_track'];
    $Insert_the_catheter = $_POST['Insert_the_catheter'];
    $Suture_the_cathete = $_POST['Suture_the_cathete'];
    $Heparin_lock = $_POST['Heparin_lock'];
    $Get_ride = $_POST['Get_ride'];
    $Control_CXR = $_POST['Control_CXR'];
    $Counsel_the_patient = $_POST['Counsel_the_patient'];
    $procedure_remarks = $_POST['procedure_remarks'];
    $procedure_date = date('Y-m-d');

    $data = array(array(
        "Registration_ID"=>$Registration_ID,
        "Employee_ID"=>$Employee_ID, 
        "Get_US_Neck"=>$Get_US_Neck, 
        "Get_catheter"=>$Get_catheter, 
        "Get_sterile"=>$Get_sterile, 
        "Lignocaine"=>$Lignocaine, 
        "Sterile_KY_gel"=>$Sterile_KY_gel, 
        "Syringes"=>$Syringes, 
        "Normal_saline"=>$Normal_saline, 
        "Heparin"=>$Heparin, 
        "Surgical_Blade"=>$Surgical_Blade, 
        "Povidone"=>$Povidone, 
        "Sterile_gloves"=>$Sterile_gloves, 
        "Female_condom"=>$Female_condom, 
        "Sutures"=>$Sutures, 
        "Dressing_Blandle"=>$Dressing_Blandle, 
        "Suture_set"=>$Suture_set, 
        "prepare_procedure_remarks"=>$prepare_procedure_remarks,
        "Lie_patient"=>$Lie_patient, 
        "Explain_what_you_are_doing"=>$Explain_what_you_are_doing, 
        "Sterile_technique"=>$Sterile_technique, 
        "Paint"=>$Paint, 
        "Drape_the_patient"=>$Drape_the_patient, 
        "Drape_the_US_probe"=>$Drape_the_US_probe, 
        "Arrange"=>$Arrange, 
        "Look_for_the_vein"=>$Look_for_the_vein, 
        "Veno"=>$Veno, 
        "Check_the_position"=>$Check_the_position, 
        "Small_Cut"=>$Small_Cut, 
        "Dilate_the_track"=>$Dilate_the_track, 
        "Insert_the_catheter"=>$Insert_the_catheter, 
        "Suture_the_cathete"=>$Suture_the_cathete, 
        "Heparin_lock"=>$Heparin_lock, 
        "Get_ride"=>$Get_ride, 
        "Control_CXR"=>$Control_CXR, 
        "Counsel_the_patient"=>$Counsel_the_patient, 
        "procedure_remarks"=>$procedure_remarks, 
        "procedure_date"=>$procedure_date
    ));

    // $checkProcedure=  mysqli_query($conn,"SELECT temporary_neck_line_id FROM tbl_dialysis_temporary_neck_line WHERE Registration_ID='$Registration_ID'");// AND procedure_date='$procedure_date'
    // $num_rows=  mysqli_num_rows($checkProcedure);
    // $temporary_neck_line_id = mysqli_fetch_assoc($checkProcedure)['temporary_neck_line_id'];

    // if($num_rows>0){
    //     $sql ="UPDATE `tbl_dialysis_temporary_neck_line` SET `Get_US_Neck`='$Get_US_Neck',`Get_catheter`='$Get_catheter',`Get_sterile`='$Get_sterile',`Lignocaine`='$Lignocaine',`Sterile_KY_gel`='$Sterile_KY_gel',`Syringes`='$Syringes',`Normal_saline`='$Normal_saline',`Heparin`='$Heparin',`Surgical_Blade`='$Surgical_Blade',`Povidone`='$Povidone',`Sterile_gloves`='$Sterile_gloves',`Female_condom`='$Female_condom',`Sutures`='$Sutures',`Dressing_Blandle`='$Dressing_Blandle',`Suture_set`='$Suture_set',`prepare_procedure_remarks`='$prepare_procedure_remarks',`procedure_remarks`='$procedure_remarks' WHERE temporary_neck_line_id = '$temporary_neck_line_id'";
    // }else{
    //     $sql = "INSERT INTO `tbl_dialysis_temporary_neck_line` (`Registration_ID`, `Employee_ID`, `Get_US_Neck`, `Get_catheter`, `Get_sterile`, `Lignocaine`, `Sterile_KY_gel`, `Syringes`, `Normal_saline`, `Heparin`, `Surgical_Blade`, `Povidone`, `Sterile_gloves`, `Female_condom`, `Sutures`, `Dressing_Blandle`, `Suture_set`, `prepare_procedure_remarks`, `procedure_date`) VALUE ('$Registration_ID', '$Employee_ID', '$Get_US_Neck', '$Get_catheter', '$Get_sterile', '$Lignocaine', '$Sterile_KY_gel', '$Syringes', '$Normal_saline', '$Heparin', '$Surgical_Blade', '$Povidone', '$Sterile_gloves', '$Female_condom', '$Sutures', '$Dressing_Blandle', '$Suture_set', '$prepare_procedure_remarks', '$procedure_date')";
    // }

    // $query=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if(Save_temporary_neck_line_data(json_encode($data)) > 0){ 
        echo 'ok';
    }else{
        echo mysqli_error($conn);
    }
}

// if(isset($_POST['procedureTempNeckLine'])){
//     $Registration_ID = $_POST['Registration_ID'];
//     $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
//     $Lie_patient = $_POST['Lie_patient'];
//     $Explain_what_you_are_doing = $_POST['Explain_what_you_are_doing'];
//     $Sterile_technique = $_POST['Sterile_technique'];
//     $Paint = $_POST['Paint'];
//     $Drape_the_patient = $_POST['Drape_the_patient'];
//     $Drape_the_US_probe = $_POST['Drape_the_US_probe'];
//     $Arrange = $_POST['Arrange'];
//     $Look_for_the_vein = $_POST['Look_for_the_vein'];
//     $Veno = $_POST['Veno'];
//     $Check_the_position = $_POST['Check_the_position'];
//     $Small_Cut = $_POST['Small_Cut'];
//     $Dilate_the_track = $_POST['Dilate_the_track'];
//     $Insert_the_catheter = $_POST['Insert_the_catheter'];
//     $Suture_the_cathete = $_POST['Suture_the_cathete'];
//     $Heparin_lock = $_POST['Heparin_lock'];
//     $Get_ride = $_POST['Get_ride'];
//     $Control_CXR = $_POST['Control_CXR'];
//     $Counsel_the_patient = $_POST['Counsel_the_patient'];
//     $procedure_remarks = $_POST['procedure_remarks'];
//     $procedure_date = date('Y-m-d');

//     $checkProcedure=  mysqli_query($conn,"SELECT temporary_neck_line_id FROM tbl_dialysis_temporary_neck_line WHERE Registration_ID='$Registration_ID'");// AND procedure_date='$procedure_date'
//     $num_rows=  mysqli_num_rows($checkProcedure);
//     $temporary_neck_line_id = mysqli_fetch_assoc($checkProcedure)['temporary_neck_line_id'];

//     if($num_rows>0){
//         $sql ="UPDATE `tbl_dialysis_temporary_neck_line` SET `Lie_patient`='$Lie_patient',`Explain_what_you_are_doing`='$Explain_what_you_are_doing',`Sterile_technique`='$Sterile_technique',`Paint`='$Paint',`Drape_the_patient`='$Drape_the_patient',`Drape_the_US_probe`='$Drape_the_US_probe',`Arrange`='$Arrange',`Look_for_the_vein`='$Look_for_the_vein',`Veno`='$Veno',`Check_the_position`='$Check_the_position',`Small_Cut`='$Small_Cut',`Dilate_the_track`='$Dilate_the_track',`Insert_the_catheter`='$Insert_the_catheter',`Suture_the_cathete`='$Suture_the_cathete',`Heparin_lock`='$Heparin_lock',`Get_ride`='$Get_ride',`Control_CXR`='$Control_CXR',`Counsel_the_patient`='$Counsel_the_patient',`procedure_remarks`='$procedure_remarks' WHERE temporary_neck_line_id = '$temporary_neck_line_id'";
//     }else{
//     $sql = "INSERT INTO `tbl_dialysis_temporary_neck_line`(`Registration_ID`, `Employee_ID`, `Lie_patient`, `Explain_what_you_are_doing`, `Sterile_technique`, `Paint`, `Drape_the_patient`, `Drape_the_US_probe`, `Arrange`, `Look_for_the_vein`, `Veno`, `Check_the_position`, `Small_Cut`, `Dilate_the_track`, `Insert_the_catheter`, `Suture_the_cathete`, `Heparin_lock`, `Get_ride`, `Control_CXR`, `Counsel_the_patient`, `procedure_remarks`, `procedure_date`) VALUES ('$Registration_ID', '$Employee_ID', '$Lie_patient', '$Explain_what_you_are_doing', '$Sterile_technique','$Paint', '$Drape_the_patient', '$Drape_the_US_probe', '$Arrange', '$Look_for_the_vein', '$Veno', '$Check_the_position', '$Small_Cut', '$Dilate_the_track', '$Insert_the_catheter', '$Suture_the_cathete', $Heparin_lock', '$Get_ride', '$Control_CXR', '$Counsel_the_patient', '$procedure_remarks', '$procedure_date')";
//     }

//     $query=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
//     if($query){ 
//         echo 'ok';
//     }else{
//         echo mysqli_error($conn);
//     }
// }


if(isset($_POST['SubmitIncidents'])){
    $machine= mysqli_real_escape_string($conn,$_POST['machine']);
    $Registration_ID= mysqli_real_escape_string($conn,$_POST['Registration_ID']);
    $event= mysqli_real_escape_string($conn,$_POST['event']);
    $action= mysqli_real_escape_string($conn,$_POST['action']);
    $event_date= mysqli_real_escape_string($conn,$_POST['event_date']);
    $Employee_ID= mysqli_real_escape_string($conn,$_POST['Employee_ID']);
    $incident_type = "Patient";
    if(!empty($machine)){
        $incident_type = "Machine";
    }

    $incident_data = array(array("Registration_ID"=>$Registration_ID,
    "Employee_ID"=>$Employee_ID,
    "event"=>$event,
    "action"=>$action, 
    "event_date"=>$event_date,
    "machine"=>$machine, 
    "incident_type"=>$incident_type));

    // $sql ="INSERT INTO `tbl_dialysis_incident_records`(`Registration_ID`, `Employee_ID`, `event`, `action`, `event_date`) VALUES ('$Registration_ID','$Employee_ID','$event','$action','$event_date')";
    // $query=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if(Save_incident_data(json_encode($incident_data)) > 0){ 
        echo 'ok';
    }else{
        echo 'error: '.mysqli_error($conn);
    }
}

if(isset($_POST['saveDoctorsOder'])){
    $data_result_summary = array(array(
        "Registration_ID"=>mysqli_real_escape_string($conn,$_POST['Registration_ID']),
        "indication"=>mysqli_real_escape_string($conn,$_POST['indication']),
        "diagnosis"=>mysqli_real_escape_string($conn,$_POST['diagnosis']),
        "medication"=>mysqli_real_escape_string($conn,$_POST['medication']),
        "mode"=>mysqli_real_escape_string($conn,$_POST['mode']),
        "access"=>mysqli_real_escape_string($conn,$_POST['access']),
        "duration"=>mysqli_real_escape_string($conn,$_POST['duration']),
        "uf_ufr"=>mysqli_real_escape_string($conn,$_POST['uf_ufr']),
        "qb"=>mysqli_real_escape_string($conn,$_POST['qb']),
        "dialysate"=>mysqli_real_escape_string($conn,$_POST['dialysate']),
        "bath"=>mysqli_real_escape_string($conn,$_POST['bath_na']),
        "bath_k"=>mysqli_real_escape_string($conn,$_POST['bath_k']),
        "bath_hco3"=>mysqli_real_escape_string($conn,$_POST['bath_hco3']),
        "amount_of_heparine"=>mysqli_real_escape_string($conn,$_POST['amount_of_heparine']),
        "dialysis_type"=>mysqli_real_escape_string($conn,$_POST['dialysis_type']),
        "sessioncicle"=>mysqli_real_escape_string($conn,$_POST['sessioncicle']),
        "sessioncicleunits"=>mysqli_real_escape_string($conn,$_POST['sessioncicleunits']),
        "sessiontime"=>mysqli_real_escape_string($conn,$_POST['sessiontime']),
        "sessiontimeunits"=>mysqli_real_escape_string($conn,$_POST['sessiontimeunits']),
        "duartions"=>mysqli_real_escape_string($conn,$_POST['duartions']),
        "durationunits"=>mysqli_real_escape_string($conn,$_POST['durationunits']),
        "ordered_by"=>mysqli_real_escape_string($conn,$_POST['ordered_by']),
        "remarks"=>mysqli_real_escape_string($conn,$_POST['remarks'])
    ));
    if(Save_Doctors_Oder(json_encode($data_result_summary)) > 0){ 
        echo 'ok';
    }else{
        echo 'error: '.mysqli_error($conn);
    }
}

if(isset($_POST['savePatientResultsummary'])){
    $data_result_summary = array(array(
        "Patient_reg"=>mysqli_real_escape_string($conn,$_POST['Registration_ID']),
        "wcc"=>mysqli_real_escape_string($conn,$_POST['wcc']),
        "hb"=>mysqli_real_escape_string($conn,$_POST['hb']),
        "mcv"=>mysqli_real_escape_string($conn,$_POST['mcv']),
        "platelets"=>mysqli_real_escape_string($conn,$_POST['platelets']),
        "esr"=>mysqli_real_escape_string($conn,$_POST['esr']),
        "na"=>mysqli_real_escape_string($conn,$_POST['na']),
        "k"=>mysqli_real_escape_string($conn,$_POST['k']),
        "pre_urea"=>mysqli_real_escape_string($conn,$_POST['pre_urea']),
        "pre_creatinine"=>mysqli_real_escape_string($conn,$_POST['pre_creatinine']),
        "urr"=>mysqli_real_escape_string($conn,$_POST['urr']),
        "post_urea"=>mysqli_real_escape_string($conn,$_POST['post_urea']),
        "post_creatinine"=>mysqli_real_escape_string($conn,$_POST['post_creatinine']),
        "glucose"=>mysqli_real_escape_string($conn,$_POST['glucose']),
        "ast"=>mysqli_real_escape_string($conn,$_POST['ast']),
        "alp"=>mysqli_real_escape_string($conn,$_POST['alp']),
        "total_bill"=>mysqli_real_escape_string($conn,$_POST['total_bill']),
        "total_protein"=>mysqli_real_escape_string($conn,$_POST['total_protein']),
        "albumin"=>mysqli_real_escape_string($conn,$_POST['albumin']),
        "calcium"=>mysqli_real_escape_string($conn,$_POST['calcium']),
        "phosphate"=>mysqli_real_escape_string($conn,$_POST['phosphate']),
        "pth"=>mysqli_real_escape_string($conn,$_POST['pth']),
        "ferritin"=>mysqli_real_escape_string($conn,$_POST['ferritin']),
        "iron"=>mysqli_real_escape_string($conn,$_POST['iron']),
        "transferrin_saturation"=>mysqli_real_escape_string($conn,$_POST['transferrin_saturation']),
        "serology"=>mysqli_real_escape_string($conn,$_POST['serology']),
        "hiv_test"=>mysqli_real_escape_string($conn,$_POST['hiv_test']),
        "hepatitis_b"=>mysqli_real_escape_string($conn,$_POST['hepatitis_b']),
        "hepatitis_c"=>mysqli_real_escape_string($conn,$_POST['hepatitis_c']),
        "urr2"=>mysqli_real_escape_string($conn,$_POST['urr2']),
        "ktv"=>mysqli_real_escape_string($conn,$_POST['ktv'])
    ));
    if(Save_patient_result_summary(json_encode($data_result_summary)) > 0){ 
        echo 'ok';
    }else{
        echo 'error: '.mysqli_error($conn);
    }
}

// $round_time=date('Y-m-d');
if(isset($_POST['saveMonthlyHd'])){
    $Registration_ID= mysqli_real_escape_string($conn,$_POST['Registration_ID']);
    $Employee_ID =  $_SESSION['userinfo']['Employee_ID'];
    $Cause_of_CKD= mysqli_real_escape_string($conn,$_POST['Cause_of_CKD']);
    $Other_co_morbidities= mysqli_real_escape_string($conn,$_POST['Other_co_morbidities']);
    $Date_commencing_HD= mysqli_real_escape_string($conn,$_POST['Date_commencing_HD']);
    $Prescription= mysqli_real_escape_string($conn,$_POST['Prescription']);
    $previous_transplant= mysqli_real_escape_string($conn,$_POST['previous_transplant']);
    $parathyroidectomy= mysqli_real_escape_string($conn,$_POST['parathyroidectomy']);
    $Antihypertensives_DM= mysqli_real_escape_string($conn,$_POST['Antihypertensives_DM']);
    $alpha_Vitamin_D =  mysqli_real_escape_string($conn,$_POST['alpha_Vitamin_D']);
    $Phosphate_Binder= mysqli_real_escape_string($conn,$_POST['Phosphate_Binder']);
    $EPO= mysqli_real_escape_string($conn,$_POST['EPO']);
    $Iron= mysqli_real_escape_string($conn,$_POST['Iron']);
    $Other_Medications= mysqli_real_escape_string($conn,$_POST['Other_Medications']);
    $Concerns_from_last_routine= mysqli_real_escape_string($conn,$_POST['Concerns_from_last_routine']);
    $Complaints_today= mysqli_real_escape_string($conn,$_POST['Complaints_today']);
    $Examination= mysqli_real_escape_string($conn,$_POST['Examination']);
    $Dry_weight= mysqli_real_escape_string($conn,$_POST['Dry_weight']);
    $URR= mysqli_real_escape_string($conn,$_POST['URR']);
    $Vascular_access= mysqli_real_escape_string($conn,$_POST['Vascular_access']);
    $Kt_V= mysqli_real_escape_string($conn,$_POST['Kt_V']);
    $Venous_pressure= mysqli_real_escape_string($conn,$_POST['Venous_pressure']);
    $Clinically= mysqli_real_escape_string($conn,$_POST['Clinically']);
    $Arterial_pressure= mysqli_real_escape_string($conn,$_POST['Arterial_pressure']);
    $Anaemia= mysqli_real_escape_string($conn,$_POST['Anaemia']);
    $Fluid_status_Hemodynamic= mysqli_real_escape_string($conn,$_POST['Fluid_status_Hemodynamic']);
    $Hemoglobin= mysqli_real_escape_string($conn,$_POST['Hemoglobin']);
    $Dry_weight_Fluid_status= mysqli_real_escape_string($conn,$_POST['Dry_weight_Fluid_status']);
    $Iron_status= mysqli_real_escape_string($conn,$_POST['Iron_status']);
    $Fluid_and_salt= mysqli_real_escape_string($conn,$_POST['Fluid_and_salt']);
    $PTH= mysqli_real_escape_string($conn,$_POST['PTH']);
    $Phosphate= mysqli_real_escape_string($conn,$_POST['Phosphate']);
    $Potassium= mysqli_real_escape_string($conn,$_POST['Potassium']);
    $Calcium= mysqli_real_escape_string($conn,$_POST['Calcium']);
    $Sodium= mysqli_real_escape_string($conn,$_POST['Sodium']);
    $ALP= mysqli_real_escape_string($conn,$_POST['ALP']);
    $Albumin= mysqli_real_escape_string($conn,$_POST['Albumin']);
    $Knowledge_on_dietary= mysqli_real_escape_string($conn,$_POST['Knowledge_on_dietary']);
    $Hepatitis_B_vaccination= mysqli_real_escape_string($conn,$_POST['Hepatitis_B_vaccination']);
    $Pneumovac= mysqli_real_escape_string($conn,$_POST['Pneumovac']);
    $Tranaminase_ASAT= mysqli_real_escape_string($conn,$_POST['Tranaminase_ASAT']);
    $Tranaminase_ALT= mysqli_real_escape_string($conn,$_POST['Tranaminase_ALT']);
    $HIV_HEP_B_HEPC_status= mysqli_real_escape_string($conn,$_POST['HIV_HEP_B_HEPC_status']);
    $Transplant_plans= mysqli_real_escape_string($conn,$_POST['Transplant_plans']);
    $Assessment_Plans= mysqli_real_escape_string($conn,$_POST['Assessment_Plans']);
    
    $round_date=  date('Y-m-d',strtotime($_POST['Monthly_Round_Date']));
    
    $checkRound=  mysqli_query($conn,"SELECT monthly_round_id FROM tbl_dialysis_monthly_rounds WHERE Registration_ID='$Registration_ID' AND round_date='$round_date'");
    $number_rows=  mysqli_num_rows($checkRound);
    $monthly_round_id = mysqli_fetch_assoc($checkRound)['monthly_round_id'];

    if($number_rows>0){
        $sql="UPDATE `tbl_dialysis_monthly_rounds` SET `Cause_of_CKD`='$Cause_of_CKD',`Other_co_morbidities`='$Other_co_morbidities',`Date_commencing_HD`='$Date_commencing_HD',`Prescription`='$Prescription',`previous_transplant`='$previous_transplant',`parathyroidectomy`='$parathyroidectomy',`Antihypertensives_DM`='$Antihypertensives_DM',`alpha_Vitamin_D`='$alpha_Vitamin_D',`Phosphate_Binder`='$Phosphate_Binder',`EPO`='$EPO',`Iron`='$Iron',`Other_Medications`='$Other_Medications',`Concerns_from_last_routine`='$Concerns_from_last_routine',`Complaints_today`='$Complaints_today',`Examination`='$Examination',`Dry_weight`='$Dry_weight',`URR`='$URR',`Vascular_access`='$Vascular_access',`Kt_V`='$Kt_V',`Venous_pressure`='$Venous_pressure',`Clinically`='$Clinically',`Arterial_pressure`='$Arterial_pressure',`Anaemia`='$Anaemia',`Fluid_status_Hemodynamic`='$Fluid_status_Hemodynamic',`Hemoglobin`='$Hemoglobin',`Dry_weight_Fluid_status`='$Dry_weight_Fluid_status',`Iron_status`='$Iron_status',`Fluid_and_salt`='$Fluid_and_salt',`PTH`='$PTH',`Phosphate`='$Phosphate',`Potassium`='$Potassium',`Calcium`='$Calcium',`Sodium`='$Sodium',`ALP`='$ALP',`Albumin`='$Albumin',`Knowledge_on_dietary`='$Knowledge_on_dietary',`Hepatitis_B_vaccination`='$Hepatitis_B_vaccination',`Pneumovac`='$Pneumovac',`Tranaminase_ASAT`='$Tranaminase_ASAT',`Tranaminase_ALT`='$Tranaminase_ALT',`HIV_HEP_B_HEPC_status`='$HIV_HEP_B_HEPC_status',`Transplant_plans`='$Transplant_plans',`Assessment_Plans`='$Assessment_Plans',`round_date`='$round_date' WHERE monthly_round_id='$monthly_round_id'";    
    }else{
        $sql ="INSERT INTO `tbl_dialysis_monthly_rounds`( `Registration_ID`, `Employee_ID`, `Cause_of_CKD`, `Other_co_morbidities`, `Date_commencing_HD`, `Prescription`, `previous_transplant`, `parathyroidectomy`, `Antihypertensives_DM`, `alpha_Vitamin_D`, `Phosphate_Binder`, `EPO`, `Iron`, `Other_Medications`, `Concerns_from_last_routine`, `Complaints_today`, `Examination`, `Dry_weight`, `URR`, `Vascular_access`, `Kt_V`, `Venous_pressure`, `Clinically`, `Arterial_pressure`, `Anaemia`, `Fluid_status_Hemodynamic`, `Hemoglobin`, `Dry_weight_Fluid_status`, `Iron_status`, `Fluid_and_salt`, `PTH`, `Phosphate`, `Potassium`, `Calcium`, `Sodium`, `ALP`, `Albumin`, `Knowledge_on_dietary`, `Hepatitis_B_vaccination`, `Pneumovac`, `Tranaminase_ASAT`, `Tranaminase_ALT`, `HIV_HEP_B_HEPC_status`, `Transplant_plans`, `Assessment_Plans`, `round_date`) VALUES ('$Registration_ID', '$Employee_ID', '$Cause_of_CKD', '$Other_co_morbidities', '$Date_commencing_HD', '$Prescription', '$previous_transplant', '$parathyroidectomy', '$Antihypertensives_DM', '$alpha_Vitamin_D', '$Phosphate_Binder', '$EPO', '$Iron', '$Other_Medications', '$Concerns_from_last_routine', '$Complaints_today', '$Examination', '$Dry_weight', '$URR', '$Vascular_access', '$Kt_V', '$Venous_pressure', '$Clinically', '$Arterial_pressure', '$Anaemia', '$Fluid_status_Hemodynamic', '$Hemoglobin', '$Dry_weight_Fluid_status', '$Iron_status', '$Fluid_and_salt', '$PTH', '$Phosphate', '$Potassium', '$Calcium', '$Sodium', '$ALP', '$Albumin', '$Knowledge_on_dietary', '$Hepatitis_B_vaccination', '$Pneumovac', '$Tranaminase_ASAT', '$Tranaminase_ALT', '$HIV_HEP_B_HEPC_status', '$Transplant_plans', '$Assessment_Plans', '$round_date')";
    }

    $query=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if($query){ 
        echo 'ok';
    }else{
        echo mysqli_error($conn);
    }
}


// $session_time=date('Y-m-d H:m:s');
if(isset($_POST['SubmitVitals'])){
$hosp;
$BP_previous_post_sit= mysqli_real_escape_string($conn,$_POST['BP_previous_post_sit']);
$BP_previous_post_stand= mysqli_real_escape_string($conn,$_POST['BP_previous_post_stand']);
$Weight_previous_post_sit=  mysqli_real_escape_string($conn,$_POST['Weight_previous_post_sit']);
$Weight_previous_post_stand=  mysqli_real_escape_string($conn,$_POST['Weight_previous_post_stand']);
$Weight_Gain= mysqli_real_escape_string($conn,$_POST['Weight_Gain']);
$Time_On= mysqli_real_escape_string($conn,$_POST['Time_On']);
$BP_Pre_sit=  mysqli_real_escape_string($conn,$_POST['BP_Pre_sit']);
$BP_Pre_stand=  mysqli_real_escape_string($conn,$_POST['BP_Pre_stand']);
$weight_pre_sit=  mysqli_real_escape_string($conn,$_POST['weight_pre_sit']);
$weight_pre_stand=  mysqli_real_escape_string($conn,$_POST['weight_pre_stand']);
$Weight_removal=  mysqli_real_escape_string($conn,$_POST['Weight_removal']);
$Time_Off=  mysqli_real_escape_string($conn,$_POST['Time_Off']);
$Post_Pre_sit=  mysqli_real_escape_string($conn,$_POST['Post_Pre_sit']);
$Post_Pre_stand=  mysqli_real_escape_string($conn,$_POST['Post_Pre_stand']);
$Weight_Post_sit= mysqli_real_escape_string($conn,$_POST['Weight_Post_sit']);
$Weight_Post_stand= mysqli_real_escape_string($conn,$_POST['Weight_Post_stand']);
$Area=  mysqli_real_escape_string($conn,$_POST['Area']);
$Station=  mysqli_real_escape_string($conn,$_POST['Station']);
$Machine=  mysqli_real_escape_string($conn,$_POST['Machine']);
// @dnm
$Diagnosis=  mysqli_real_escape_string($conn,$_POST['Diagnosis']);
$Management=  mysqli_real_escape_string($conn,$_POST['Management']);
$Remarks=  mysqli_real_escape_string($conn,$_POST['Remarks']);
$Dry_Weight = mysqli_real_escape_string($conn,$_POST['Dry_Weight']);
$Pulse_previous_post=  mysqli_real_escape_string($conn,$_POST['Pulse_previous_post']);
$Respiration_previous_post=  mysqli_real_escape_string($conn,$_POST['Respiration_previous_post']);
$Temperature_previous_post=  mysqli_real_escape_string($conn,$_POST['Temperature_previous_post']);
$Pulse_pre=  mysqli_real_escape_string($conn,$_POST['Pulse_pre']);
$Respiration_pre=  mysqli_real_escape_string($conn,$_POST['Respiration_pre']);
$Temperature_pre=  mysqli_real_escape_string($conn,$_POST['Temperature_pre']);
$Pulse_post=  mysqli_real_escape_string($conn,$_POST['Pulse_post']);
$Respiration_post=  mysqli_real_escape_string($conn,$_POST['Respiration_post']);
$Temperature_post=  mysqli_real_escape_string($conn,$_POST['Temperature_post']);
$Management=  mysqli_real_escape_string($conn,$_POST['Management']);
$dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);

// $dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);

// end dnm
$status=$_POST['hosp'];
if($status=='Yes'){
   $hosp='yes'; 
}else{
  $hosp='no';    
}

// if($numberRows>0){
//    $insert_query="UPDATE tbl_dialysis_details SET Pulse_post='$Pulse_post',Respiration_post='$Respiration_post',Temperature_post='$Temperature_post',Pulse_pre='$Pulse_pre',Respiration_pre='$Respiration_pre',Temperature_pre='$Temperature_pre',Pulse_previous_post='$Pulse_previous_post',Respiration_previous_post='$Respiration_previous_post',Temperature_previous_post='$Temperature_previous_post', bpPrevious_Post_sit='$BP_previous_post_sit',bpPrevious_Post_stand='$BP_previous_post_stand',Weight_Previous_Post_sit='$Weight_previous_post_sit',Weight_Previous_Post_stand='$Weight_previous_post_stand',Weight_Gain='$Weight_Gain',Time_On='$Time_On',bpPre_sit='$BP_Pre_sit',bpPre_stand='$BP_Pre_stand',Weight_Pre_sit='$weight_pre_sit',Weight_Pre_stand='$weight_pre_stand',Weight_removal='$Weight_removal',Time_Off='$Time_Off',bpPost_sit='$Post_Pre_sit',bpPost_stand='$Post_Pre_stand',Weight_Post_sit='$Weight_Post_sit',Weight_Post_stand='$Weight_Post_stand',Area='$Area',Station='$Station',Machine='$Machine',Diagnosis='$Diagnosis',Management='$Management',Remarks='$Remarks',Dry_Weight='$Dry_Weight',Hosp='$hosp',session_time='$session_time'  WHERE Patient_reg='$Registration_ID' AND Attendance_Date='$Today'";
   
// }  else {
   
//     $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Attendance_Date,bpPrevious_Post_sit,bpPrevious_Post_stand,Weight_Previous_Post_sit,Weight_Previous_Post_stand,Weight_Gain,Time_On,bpPre_sit,bpPre_stand,Weight_Pre_sit,Weight_Pre_stand,Weight_removal,Time_Off,bpPost_sit,bpPost_stand,Weight_Post_sit,Weight_Post_stand,Area,Station,Machine,Diagnosis,Management,Remarks,Dry_Weight,Hosp,Nurse_ID,Patient_Payment_ID,session_time,Pulse_previous_post,Respiration_previous_post,Temperature_previous_post,Pulse_pre,Respiration_pre,Temperature_pre,Pulse_post,Respiration_post,Temperature_post) VALUES ('$Registration_ID',NOW(),'$BP_previous_post_sit','$BP_previous_post_stand','$Weight_previous_post_sit','$Weight_previous_post_stand','$Weight_Gain','$Time_On','$BP_Pre_sit','$BP_Pre_stand','$weight_pre_sit','$weight_pre_stand','$Weight_removal','$Time_Off','$Post_Pre_sit','$Post_Pre_stand','$Weight_Post_sit','$Weight_Post_stand','$Area','$Station','$Machine','$Diagnosis','$Management','$Remarks','$Dry_Weight','$hosp','$Employee_ID','$Payment_Cache_ID','$session_time','$Pulse_previous_post','$Respiration_previous_post','$Temperature_previous_post','$Pulse_pre','$Respiration_pre','$Temperature_pre','$Pulse_post','$Respiration_post','$Temperature_post')";

// }

// $query=  mysqli_query($conn,$insert_query) or die(mysqli_error($conn));

$data_two = array(array(
    "Patient_reg"=>$Registration_ID,
    "bpPrevious_Post_sit"=>$BP_previous_post_sit,
    "bpPrevious_Post_stand"=>$BP_previous_post_stand,
    "Weight_Previous_Post_sit"=>$Weight_previous_post_sit,
    "Weight_Previous_Post_stand"=>$Weight_previous_post_stand,
    "Weight_Gain"=>$Weight_Gain,
    "Time_On"=>$Time_On,
    "bpPre_sit"=>$BP_Pre_sit,
    "bpPre_stand"=>$BP_Pre_stand,
    "Weight_Pre_sit"=>$weight_pre_sit,
    "Weight_Pre_stand"=>$weight_pre_stand,
    "Weight_removal"=>$Weight_removal,
    "Time_Off"=>$Time_Off,
    "bpPost_sit"=>$Post_Pre_sit,
    "bpPost_stand"=>$Post_Pre_stand,
    "Weight_Post_sit"=>$Weight_Post_sit,
    "Weight_Post_stand"=>$Weight_Post_stand,
    "Area"=>$Area,
    "Station"=>$Station,
    "Machine"=>$Machine,
    "Diagnosis"=>$Diagnosis,
    "Management"=>$Management,
    "Remarks"=>$Remarks,
    "Dry_Weight"=>$Dry_Weight,
    "Hosp"=>$hosp,
    "Nurse_ID"=>$Employee_ID,
    "Patient_Payment_ID"=>$Payment_Cache_ID,
    "Pulse_previous_post"=>$Pulse_previous_post,
    "Respiration_previous_post"=>$Respiration_previous_post,
    "Temperature_previous_post"=>$Temperature_previous_post,
    "Pulse_pre"=>$Pulse_pre,
    "Respiration_pre"=>$Respiration_pre,
    "Temperature_pre"=>$Temperature_pre,
    "Pulse_post"=>$Pulse_post,
    "Respiration_post"=>$Respiration_post,
    "Temperature_post"=>$Temperature_post,
    "dialysis_details_ID"=>$dialysis_details_ID
));
    //  if(Save_Vital_Data(json_encode($data_two)) > 0){ 
//      UpdateCache($Payment_Item_Cache_List_ID);
//      echo 'Successfully Saved';
//  }else{
//      echo mysqli_error($conn);
//  }
    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Attendance_Date FROM tbl_dialysis_vitals WHERE dialysis_details_ID='$dialysis_details_ID' AND Patient_reg='$Registration_ID'"));
    $Attendance_Date_check = $result['Attendance_Date'];
    if($Attendance_Date_check > 0){
        
    $Attendance_Date_check = $result['Attendance_Date'];
    $update_vital=mysqli_query($conn,"UPDATE `tbl_dialysis_vitals` SET `bpPrevious_Post_sit`='$BP_previous_post_sit',`bpPrevious_Post_stand`='$BP_previous_post_stand',`Weight_Previous_Post_sit`='$Weight_previous_post_sit',`Weight_Previous_Post_stand`='$Weight_previous_post_stand',`Weight_Gain`='$Weight_Gain',`Time_On`='$Time_On',`bpPre_sit`='$BP_Pre_sit',`bpPre_stand`='$BP_Pre_stand',`Weight_Pre_sit`=''$weight_pre_sit,`Weight_Pre_stand`='$weight_pre_stand',`Weight_removal`='$Weight_removal',`Time_Off`='$Time_Off',`bpPost_sit`='$Post_Pre_sit',`bpPost_stand`='$Post_Pre_stand',`Weight_Post_sit`='$Weight_Post_sit',`Weight_Post_stand`='$Weight_Post_stand',`Area`='$Area',`Station`='$Station',`Machine`='$Machine',`Diagnosis`='$Diagnosis',`Management`='$Management',`Remarks`='$Remarks',`Dry_Weight`='$Dry_Weight',`Nurse_ID`='$Employee_ID',`Pulse_previous_post`='$Pulse_previous_post',`Respiration_previous_post`='$Respiration_previous_post',`Temperature_previous_post`='$Temperature_previous_post',`Pulse_pre`='$Pulse_pre',`Respiration_pre`='$Respiration_pre',`Temperature_pre`='$Temperature_pre',`Pulse_post`='$Pulse_post',`Respiration_post`='$Respiration_post',`Temperature_post`='$Temperature_post',Management='$Management',dialysis_details_ID='$dialysis_details_ID' WHERE Patient_reg='$Registration_ID' AND dialysis_details_ID='$dialysis_details_ID'");
        if($update_vital){
            echo "Data Updated Successfully";
            UpdateCache($Payment_Item_Cache_List_ID);
        }else{
            echo mysqli_error($conn);
        }
    }else{
        if(Save_Vital_Data(json_encode($data_two)) > 0){ 
            UpdateCache($Payment_Item_Cache_List_ID);
            echo 'Successfully Saved';
        }else{
            echo mysqli_error($conn);
        }
    }

 
}else if(isset ($_POST['SaveMachineAccess'])){
$Alarm_Test;
$Air_Detector;
$UF_System;
$Conductivity_Machine=  mysqli_real_escape_string($conn,$_POST['Conductivity_Machine']);
$Conductivity_Manual=  mysqli_real_escape_string($conn,$_POST['Conductivity_Manual']);
$pH_Machine=  mysqli_real_escape_string($conn,$_POST['pH_Machine']);
$pH_Manual=  mysqli_real_escape_string($conn,$_POST['pH_Manual']);
$Temperature_Machine=  mysqli_real_escape_string($conn,$_POST['Temperature_Machine']);
$Temperature_Initial=  mysqli_real_escape_string($conn,$_POST['Temperature_Initial']);
$UF_Initial=  mysqli_real_escape_string($conn,$_POST['UF_Initial']);
$Positive_Presence=  mysqli_real_escape_string($conn,$_POST['Positive_Presence']);
$Negative_Residual=  mysqli_real_escape_string($conn,$_POST['Negative_Residual']);
$Dialyzer_ID=  mysqli_real_escape_string($conn,$_POST['Dialyzer_ID']);
$dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);
$Consultant_employee=  mysqli_real_escape_string($conn,$_POST['Consultant_employee']);
if(isset($_POST['Alarm_Test'])){
    $Alarm_Test='Pass';
}  else {
    $Alarm_Test='Fail';
}

if(isset($_POST['Air_Detector'])){
  $Air_Detector='Yes';  
}  else {
 $Air_Detector='No';   
}

if(isset($_POST['UF_System'])){
 $UF_System='Pass';   
}  else {
  $UF_System='Fail';    
}

// if($numberRows>0){
//    $insert_query="UPDATE tbl_dialysis_details SET Conductivity_Machine='$Conductivity_Machine',Conductivity_manual='$Conductivity_Manual',pH_Machine='$pH_Machine',pH_Manual='$pH_Manual',Temperature_Machine='$Temperature_Machine',Temperature_Initial='$Temperature_Initial',Alarm_Test='$Alarm_Test',Air_Detector='$Air_Detector',Positive_Presence='$Positive_Presence',Negative_Residual='$Negative_Residual',Dialyzer_ID='$Dialyzer_ID',UF_System_initial='$UF_Initial',UF_System='$UF_System'  WHERE  Patient_reg='$Registration_ID' AND Attendance_Date='$Today'";
   
// }  else {
   
//     $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Attendance_Date,Conductivity_Machine,Conductivity_manual,pH_Machine,pH_Manual,Temperature_Machine,Temperature_Initial,Alarm_Test,Air_Detector,Positive_Presence,Negative_Residual,Dialyzer_ID,UF_System_initial,UF_System) VALUES ('$Registration_ID',NOW(),'$Conductivity_Machine','$Conductivity_Manual','$pH_Machine','$pH_Manual','$Temperature_Machine','$Temperature_Initial','$Alarm_Test','$Air_Detector','$Positive_Presence','$Negative_Residual','$Dialyzer_ID','$UF_Initial','$UF_System')";

// }

// $query=  mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
$datas = array(array(
   "Patient_reg"=>$Registration_ID,"Conductivity_Machine"=>$Conductivity_Machine,
    "Conductivity_manual"=>$Conductivity_Manual,"pH_Machine"=>$pH_Machine,"pH_Manual"=>$pH_Manual,
    "Temperature_Machine"=>$Temperature_Machine,"Temperature_Initial"=>$Temperature_Initial,
    "Alarm_Test"=>$Alarm_Test,"Air_Detector"=>$Air_Detector,"Positive_Presence"=>$Positive_Presence,
    "Negative_Residual"=>$Negative_Residual,"Dialyzer_ID"=>$Dialyzer_ID,"UF_System_initial"=>$UF_Initial,
    "UF_System"=>$UF_System,"dialysis_details_ID"=>$dialysis_details_ID,"Consultant_employee"=>$Consultant_employee
));

 if(Save_Machine_Data(json_encode($datas))>0){ 
     UpdateCache($Payment_Item_Cache_List_ID);
     echo 'Successfully Saved';
 }
 else{
     echo mysqli_error($conn);
 }

 
}else if(isset ($_POST['HeparainSave'])){ 
$Type=  mysqli_real_escape_string($conn,$_POST['Type']);
$Initial_Bolus=  mysqli_real_escape_string($conn,$_POST['Initial_Bolus']);
$Unit_Hr=  mysqli_real_escape_string($conn,$_POST['Unit_Hr']);
$Infusion_Bolus=  mysqli_real_escape_string($conn,$_POST['Infusion_Bolus']);
$Stop_time= mysqli_real_escape_string($conn,$_POST['Stop_time']);
$CVC_Post=  mysqli_real_escape_string($conn,$_POST['CVC_Post']);
$Arterial=  mysqli_real_escape_string($conn,$_POST['Arterial']);
$Venous=  mysqli_real_escape_string($conn,$_POST['Venous']);
$dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);
$Consultant_employee=  mysqli_real_escape_string($conn,$_POST['Consultant_employee']);
//  if($numberRows>0){
//    $insert_query="UPDATE tbl_dialysis_details SET Type='$Type',Initial_Bolus='$Initial_Bolus',Unit_Hr='$Unit_Hr',Stop_time='$Stop_time',CVC_Pos='$CVC_Post',Arterial='$Arterial',Infusion_Bolus='$Infusion_Bolus',Venous='$Venous'  WHERE  Patient_reg='$Registration_ID' AND Attendance_Date='$Today'";
// }  else {
//     $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Attendance_Date,Type,Initial_Bolus,Unit_Hr,Stop_time,CVC_Pos,Arterial,Infusion_Bolus,Venous) VALUES ('$Registration_ID',NOW(),'$Type','$Initial_Bolus','$Unit_Hr','$Stop_time','$CVC_Post','$Arterial','$Infusion_Bolus','$Venous')";
// }
// $query=  mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
$data_three = array(array(
    "Patient_reg"=>$Registration_ID,
    "Type"=>$Type,
    "Initial_Bolus"=>$Initial_Bolus,
    "Unit_Hr"=>$Unit_Hr,
    "Stop_time"=>$Stop_time,
    "CVC_Pos"=>$CVC_Post,
    "Arterial"=>$Arterial,
    "Infusion_Bolus"=>$Infusion_Bolus,
    "Venous"=>$Venous,
    "dialysis_details_ID"=>$dialysis_details_ID,
    "Consultant_employee"=>$Consultant_employee
));

 if(Save_Heparain_Data(json_encode($data_three)) > 0){
     UpdateCache($Payment_Item_Cache_List_ID);
     echo 'Successfully Saved';
 }else{
     echo mysqli_error($conn);
 }

} elseif (isset ($_POST['SaveDialysisbtn'])) {
    $Dialyzer_1=  mysqli_real_escape_string($conn,$_POST['Dialyzer_1']);
    $Dialyzer_2=  mysqli_real_escape_string($conn,$_POST['Dialyzer_2']);
    $Dialyzer_3=  mysqli_real_escape_string($conn,$_POST['Dialyzer_3']);
    $Dialyzer_4=  mysqli_real_escape_string($conn,$_POST['Dialyzer_4']);
    $Dialysate_1=  mysqli_real_escape_string($conn,$_POST['Dialysate_1']);
    $Dialysate_2=  mysqli_real_escape_string($conn,$_POST['Dialysate_2']);
    $Dialysate_3=  mysqli_real_escape_string($conn,$_POST['Dialysate_3']);
    $Dialysate_4=  mysqli_real_escape_string($conn,$_POST['Dialysate_4']);
    $Sodium_1=  mysqli_real_escape_string($conn,$_POST['Sodium_1']);
    $Sodium_2=  mysqli_real_escape_string($conn,$_POST['Sodium_2']);
    $Sodium_3=  mysqli_real_escape_string($conn,$_POST['Sodium_3']);
    $Sodium_4=  mysqli_real_escape_string($conn,$_POST['Sodium_4']);
    $UD_1=  mysqli_real_escape_string($conn,$_POST['UD_1']);
    $UD_2=  mysqli_real_escape_string($conn,$_POST['UD_2']);
    $UD_3=  mysqli_real_escape_string($conn,$_POST['UD_3']);
    $UD_4=  mysqli_real_escape_string($conn,$_POST['UD_4']);
    $Temp_1=  mysqli_real_escape_string($conn,$_POST['Temp_1']);
    $Temp_2=  mysqli_real_escape_string($conn,$_POST['Temp_2']);
    $Temp_3=  mysqli_real_escape_string($conn,$_POST['Temp_3']);
    $Temp_4=  mysqli_real_escape_string($conn,$_POST['Temp_4']);
  
    if($numberRows>0){
    $insert_query="UPDATE tbl_dialysis_details SET Dialyzer_1='$Dialyzer_1',Dialyzer_2='$Dialyzer_2',Dialyzer_3='$Dialyzer_3',Dialyzer_4='$Dialyzer_4',Dialysate_1='$Dialysate_1',Dialysate_2='$Dialysate_2',Dialysate_3='$Dialysate_3',Dialysate_4='$Dialysate_4',Sodium_1='$Sodium_1',Sodium_2='$Sodium_2',Sodium_3='$Sodium_3',Sodium_4='$Sodium_4',UD_1='$UD_1',UD_2='$UD_2',UD_3='$UD_3',UD_4='$UD_4',Temp_1='$Temp_1',Temp_2='$Temp_2',Temp_3='$Temp_3',Temp_4='$Temp_4'  WHERE  Patient_reg='$Registration_ID'";

    }  else {
    $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Dialyzer_1,Dialyzer_2,Dialyzer_3,Dialyzer_4,Dialysate_1,Dialysate_2,Dialysate_3,Dialysate_4,Sodium_1,Sodium_2,Sodium_3,Sodium_4,UD_1,UD_2,UD_3,UD_4,Temp_1,Temp_2,Temp_3,Temp_4) VALUES ('$Registration_ID','$Dialyzer_1','$Dialyzer_2','$Dialyzer_3','$Dialyzer_4','$Dialysate_1','$Dialysate_2','$Dialysate_3','$Dialysate_4','$Sodium_1','$Sodium_2','$Sodium_3','$Sodium_4','$UD_1','$UD_2','$UD_3','$UD_4','$Temp_1','$Temp_2','$Temp_3','$Temp_4')";
    }
    
    $query= mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    if($query){ 
        UpdateCache($Payment_Item_Cache_List_ID);
        echo 'Successfully Saved';
    } else {
        echo mysqli_error($conn);
    }  
    
} else if (isset ($_POST['AccessOrdersbtn'])) {
    $Orderstextarea=  mysqli_real_escape_string($conn,$_POST['Orderstextarea']);
    $Arterial_Needle_Gauge=  mysqli_real_escape_string($conn,$_POST['Arterial_Needle_Gauge']);
    $Venos_Needle_Gauge=  mysqli_real_escape_string($conn,$_POST['Venos_Needle_Gauge']);
    $Arterial_Needle_Length=  mysqli_real_escape_string($conn,$_POST['Arterial_Needle_Length']);
    $Vonos_Needle_Length=  mysqli_real_escape_string($conn,$_POST['Vonos_Needle_Length']);
    $Arterial_Static_Pressuer=  mysqli_real_escape_string($conn,$_POST['Arterial_Static_Pressuer']);
    $Vonos_Static_Pressuer=  mysqli_real_escape_string($conn,$_POST['Vonos_Static_Pressuer']);
    $Arterial_Bleeding_Stopped=  mysqli_real_escape_string($conn,$_POST['uchunguzi_titi']);
    $Vonos_Bleeding_Stopped=  mysqli_real_escape_string($conn,$_POST['Buje']);
    $dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);
    $Consultant_employee=  mysqli_real_escape_string($conn,$_POST['Consultant_employee']);
    $data_four = array(array(
        "Patient_reg"=>$Registration_ID,
        'Attendance_Date'=>$Today,
        "AccessOrdersNotes"=>$Orderstextarea,
        "Arterial_Needle_Gauge"=>$Arterial_Needle_Gauge,
        "Arterial_Needle_Length"=>$Arterial_Needle_Length,
        "Arterial_Static_Pressuer"=>$Arterial_Static_Pressuer,
        "Arterial_Bleeding_Stopped"=>$Arterial_Bleeding_Stopped,
        "Venous_Needle_Gauge"=>$Venos_Needle_Gauge,
        "Venous_Needle_Length"=>$Vonos_Needle_Length,
        "Venous_Static_Pressuer"=>$Vonos_Static_Pressuer,
        "Venous_Bleeding_Stopped"=>$Vonos_Bleeding_Stopped,
        "Consultant_employee"=>$Consultant_employee,
        "dialysis_details_ID"=>$dialysis_details_ID
    ));

    // if($numberRows>0){
    // $insert_query="UPDATE tbl_dialysis_details SET AccessOrdersNotes='$Orderstextarea',Arterial_Needle_Gauge='$Arterial_Needle_Gauge',Arterial_Needle_Length='$Arterial_Needle_Length',Arterial_Static_Pressuer='$Arterial_Static_Pressuer',Arterial_Bleeding_Stopped='$Arterial_Bleeding_Stopped',Venous_Needle_Gauge='$Venos_Needle_Gauge',Venous_Needle_Length='$Vonos_Needle_Length',Venous_Static_Pressuer='$Vonos_Static_Pressuer',Venous_Bleeding_Stopped='$Vonos_Bleeding_Stopped'  WHERE  Patient_reg='$Registration_ID' AND Attendance_Date='$Today'";

    // }  else {
    // $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Attendance_Date,AccessOrdersNotes,Arterial_Needle_Gauge,Arterial_Needle_Length,Arterial_Static_Pressuer,Arterial_Bleeding_Stopped,Venous_Needle_Gauge,Venous_Needle_Length,Venous_Static_Pressuer,Venous_Bleeding_Stopped) VALUES ('$Registration_ID',NOW(),'$Orderstextarea','$Arterial_Needle_Gauge','$Arterial_Needle_Length','$Arterial_Static_Pressuer','$Arterial_Bleeding_Stopped','$Venos_Needle_Gauge','$Vonos_Needle_Length','$Vonos_Static_Pressuer','$Vonos_Bleeding_Stopped')";
    // }
    
    // $query= mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    if(Save_Access_Data(json_encode($data_four)) > 0){ 
        UpdateCache($Payment_Item_Cache_List_ID);
        echo 'Successfully Saved';
    } else {
        echo mysqli_error($conn);
    } 
    
} else if (isset($_POST['SaveNotesbtn'])) {

   $txtNotes=  mysqli_real_escape_string($conn,$_POST['txtNotes']); 
   $dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']); 
   $Consultant_employee=  mysqli_real_escape_string($conn,$_POST['Consultant_employee']); 
    
//    if($numberRows>0){
//     $insert_query="UPDATE tbl_dialysis_details SET Notes='$txtNotes', Doctor_ID='$Employee_ID'  WHERE  Patient_reg='$Registration_ID' AND Attendance_Date='$Today'";

//     }  else {
//     $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Attendance_Date,Notes,Doctor_ID,Patient_Payment_ID) VALUES ('$Registration_ID',NOW(),'$txtNotes','$Employee_ID','$Payment_Cache_ID')";
//     }

//     $query= mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    $data_ten = array(array(
        "Patient_reg"=>$Registration_ID,
        'Attendance_Date'=>$Today,
        "Notes"=>$txtNotes,
        "Doctor_ID"=>$Employee_ID,
        "Patient_Payment_ID"=>$Payment_Cache_ID,
        "Consultant_employee"=>$Consultant_employee,
        "dialysis_details_ID"=>$dialysis_details_ID
    ));

    if(Save_dialysis_Doctor_Notes(json_encode($data_ten))>0){ 
        UpdateCache($Payment_Item_Cache_List_ID);
        echo 'Successfully Saved';
    } else {
        echo mysqli_error($conn);
    }  
  
    
} else if(isset ($_POST['SaveDataCollectionbtn'])) {
    
 $Temp_Pre_Assessment=  mysqli_real_escape_string($conn,$_POST['Temp_Pre_Assessment']);
 $Resp_Pre_Assessment=  mysqli_real_escape_string($conn,$_POST['Resp_Pre_Assessment']);
 $GI_Pre_Assessment=  mysqli_real_escape_string($conn,$_POST['GI_Pre_Assessment']);
 $Cardiac_Pre_Assessment=  mysqli_real_escape_string($conn,$_POST['Cardiac_Pre_Assessment']);
 $Edema_Pre_Assessment=  mysqli_real_escape_string($conn,$_POST['Edema_Pre_Assessment']);
 $Mental_Pre_Assessment=  mysqli_real_escape_string($conn,$_POST['Mental_Pre_Assessment']);
 $Mobility_Pre_Assessment=  mysqli_real_escape_string($conn,$_POST['Mobility_Pre_Assessment']);
 $Access_Pre_Assessment=  mysqli_real_escape_string($conn,$_POST['Access_Pre_Assessment']);
 $Temp_Time=  mysqli_real_escape_string($conn,$_POST['Temp_Time']);
 $Resp_Time=  mysqli_real_escape_string($conn,$_POST['Resp_Time']);
 $GI_Time=  mysqli_real_escape_string($conn,$_POST['GI_Time']); 
 $Cardiac_Time=  mysqli_real_escape_string($conn,$_POST['Cardiac_Time']);
 $Edema_Time=  mysqli_real_escape_string($conn,$_POST['Edema_Time']);
 $Mental_Time=  mysqli_real_escape_string($conn,$_POST['Mental_Time']);
 $Mobility_Time=  mysqli_real_escape_string($conn,$_POST['Mobility_Time']);
 $Access_Time= mysqli_real_escape_string($conn,$_POST['Access_Time']);
 $Temp_Initials=  mysqli_real_escape_string($conn,$_POST['Temp_Initials']);
 $Resp_Initials=  mysqli_real_escape_string($conn,$_POST['Resp_Initials']);
 $GI_Initials=  mysqli_real_escape_string($conn,$_POST['GI_Initials']);
 $Cardiac_Initials=  mysqli_real_escape_string($conn,$_POST['Cardiac_Initials']);
 $Edema_Initials=  mysqli_real_escape_string($conn,$_POST['Edema_Initials']);
 $Mental_Initials=  mysqli_real_escape_string($conn,$_POST['Mental_Initials']);
 $Mobility_Initials=  mysqli_real_escape_string($conn,$_POST['Mobility_Initials']);
 $Access_Initials= mysqli_real_escape_string($conn,$_POST['Access_Initials']);
 $Temp_Post= mysqli_real_escape_string($conn,$_POST['Temp_Post']);
 $Resp_Post= mysqli_real_escape_string($conn,$_POST['Resp_Post']);
 $GI_Post= mysqli_real_escape_string($conn,$_POST['GI_Post']);
 $Cardiac_Post=  mysqli_real_escape_string($conn,$_POST['Cardiac_Post']);
 $Edema_Post=  mysqli_real_escape_string($conn,$_POST['Edema_Post']);
 $Mental_Post=  mysqli_real_escape_string($conn,$_POST['Mental_Post']);
 $Mobility_Post= mysqli_real_escape_string($conn,$_POST['Mobility_Post']);
 $Access_Post= mysqli_real_escape_string($conn,$_POST['Access_Post']);
 $Temp_Initials2= mysqli_real_escape_string($conn,$_POST['Temp_Initials2']);
 $Resp_Initials2= mysqli_real_escape_string($conn,$_POST['Resp_Initials2']);
 $GI_Initials2=  mysqli_real_escape_string($conn,$_POST['GI_Initials2']);
 $Cardiac_Initials2=  mysqli_real_escape_string($conn,$_POST['Cardiac_Initials2']);
 $Edema_Initials2=  mysqli_real_escape_string($conn,$_POST['Edema_Initials2']);
 $Mental_Initials2=  mysqli_real_escape_string($conn,$_POST['Mental_Initials2']);
 $Mobility_Initials2=  mysqli_real_escape_string($conn,$_POST['Mobility_Initials2']);
 $Access_Initials2=  mysqli_real_escape_string($conn,$_POST['Access_Initials2']);

 $dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);
 $Consultant_employee=  mysqli_real_escape_string($conn,$_POST['Consultant_employee']);
//  if($numberRows>0){
//     $insert_query="UPDATE tbl_dialysis_details SET Temp_Pre_Assessment='$Temp_Pre_Assessment',Resp_Pre_Assessment='$Resp_Pre_Assessment',GI_Pre_Assessment='$GI_Pre_Assessment',Cardiac_Pre_Assessment='$Cardiac_Pre_Assessment',Edema_Pre_Assessment='$Edema_Pre_Assessment',Mental_Pre_Assessment='$Mental_Pre_Assessment',Mobility_Pre_Assessment='$Mobility_Pre_Assessment',Access_Pre_Assessment='$Access_Pre_Assessment',"
//             . "Temp_Time='$Temp_Time',Resp_Time='$Resp_Time',GI_Time='$GI_Time',Cardiac_Time='$Cardiac_Time',Edema_Time='$Edema_Time',Mental_Time='$Mental_Time',Mobility_Time='$Mobility_Time',Access_Time='$Access_Time',Temp_Initials='$Temp_Initials',Resp_Initials='$Resp_Initials',GI_Initials='$GI_Initials',Cardiac_Initials='$Cardiac_Initials',Edema_Initials='$Edema_Initials',Mental_Initials='$Mental_Initials',Mobility_Initials='$Mobility_Initials',Access_Initials='$Access_Initials',Temp_Post='$Temp_Post',Resp_Post='$Resp_Post',GI_Post='$GI_Post',Cardiac_Post='$Cardiac_Post',Edema_Post='$Edema_Post',Mobility_Post='$Mobility_Post',Access_Post='$Access_Post',Resp_Initials2='$Resp_Initials2',GI_Initials2='$GI_Initials2',Cardiac_Initials2='$Cardiac_Initials2',Edema_Initials2='$Edema_Initials2',Mental_Initials2='$Mental_Initials2',Mobility_Initials2='$Mobility_Initials2',Access_Initials2='$Access_Initials2'  WHERE  Patient_reg='$Registration_ID' AND Attendance_Date='$Today'";

//     }  else {
//     $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Attendance_Date,Temp_Pre_Assessment,Resp_Pre_Assessment,GI_Pre_Assessment,Cardiac_Pre_Assessment,Edema_Pre_Assessment,Mental_Pre_Assessment,Mobility_Pre_Assessment,Access_Pre_Assessment,Temp_Time,Resp_Time,GI_Time,Cardiac_Time,Edema_Time,Mental_Time,Mobility_Time,Access_Time,Temp_Initials,Resp_Initials,GI_Initials,Cardiac_Initials,Edema_Initials,Mental_Initials,Mobility_Initials,Access_Initials,Temp_Post,Resp_Post,GI_Post,Cardiac_Post,Edema_Post,Mobility_Post,Access_Post,Resp_Initials2,GI_Initials2,Cardiac_Initials2,Edema_Initials2,Mental_Initials2,Mobility_Initials2,Access_Initials2) VALUES "
//             . "('$Registration_ID',NOW(),'$Temp_Pre_Assessment','$Resp_Pre_Assessment','$GI_Pre_Assessment','$Cardiac_Pre_Assessment','$Edema_Pre_Assessment','$Mental_Pre_Assessment','$Mobility_Pre_Assessment','$Access_Pre_Assessment','$Temp_Time','$Resp_Time','$GI_Time','$Cardiac_Time','$Edema_Time','$Mental_Time','$Mobility_Time','$Access_Time','$Temp_Initials','$Resp_Initials','$GI_Initials','$Cardiac_Initials','$Edema_Initials','$Mental_Initials','$Mobility_Initials','$Access_Initials','$Temp_Post','$Resp_Post','$GI_Post','$Cardiac_Post','$Edema_Post','$Mobility_Post','$Access_Post','$Resp_Initials2','$GI_Initials2','$Cardiac_Initials2','$Edema_Initials2','$Mental_Initials2','$Mobility_Initials2','$Access_Initials2')";
//     }
    
//     $query= mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    $data_six = array(array(
        "Patient_reg"=>$Registration_ID,
        "Temp_Pre_Assessment"=>$Temp_Pre_Assessment,
        "Resp_Pre_Assessment"=>$Resp_Pre_Assessment,
        "GI_Pre_Assessment"=>$GI_Pre_Assessment,
        "Cardiac_Pre_Assessment"=>$Cardiac_Pre_Assessment,
        "Edema_Pre_Assessment"=>$Edema_Pre_Assessment,
        "Mental_Pre_Assessment"=>$Mental_Pre_Assessment,
        "Mobility_Pre_Assessment"=>$Mobility_Pre_Assessment,
        "Access_Pre_Assessment"=>$Access_Pre_Assessment,
        "Temp_Time"=>$Temp_Time,
        "Resp_Time"=>$Resp_Time,
        "GI_Time"=>$GI_Time,
        "Cardiac_Time"=>$Cardiac_Time,
        "Edema_Time"=>$Edema_Time,
        "Mental_Time"=>$Mental_Time,
        "Mobility_Time"=>$Mobility_Time,
        "Access_Time"=>$Access_Time,
        "Temp_Initials"=>$Temp_Initials,
        "Resp_Initials"=>$Resp_Initials,
        "GI_Initials"=>$GI_Initials,
        "Cardiac_Initials"=>$Cardiac_Initials,
        "Edema_Initials"=>$Edema_Initials,
        "Mental_Initials"=>$Mental_Initials,
        "Mobility_Initials"=>$Mobility_Initials,
        "Access_Initials"=>$Access_Initials,
        "Temp_Post"=>$Temp_Post,
        "Resp_Post"=>$Resp_Post,
        "GI_Post"=>$GI_Post,
        "Cardiac_Post"=>$Cardiac_Post,
        "Edema_Post"=>$Edema_Post,
        "Mobility_Post"=>$Mobility_Post,
        "Access_Post"=>$Access_Post,
        "Resp_Initials2"=>$Resp_Initials2,
        "GI_Initials2"=>$GI_Initials2,
        "Cardiac_Initials2"=>$Cardiac_Initials2,
        "Edema_Initials2"=>$Edema_Initials2,
        "Mental_Initials2"=>$Mental_Initials2,
        "Mobility_Initials2"=>$Mobility_Initials2,
        "Access_Initials2"=>$Access_Initials2,
        "Consultant_employee"=>$Consultant_employee,
        "dialysis_details_ID"=>$dialysis_details_ID
    ));

    if(Save_Data_Collection(json_encode($data_six))>0){ 
        UpdateCache($Payment_Item_Cache_List_ID);
        echo 'Successfully Saved';
    } else {
        echo mysqli_error($conn);
    }  
 
 
} else if(isset ($_POST['SaveObservationChartbtn'])){
  $Time=  mysqli_real_escape_string($conn,$_POST['Time']);
  $BP=  mysqli_real_escape_string($conn,$_POST['BP']);
  $HR=  mysqli_real_escape_string($conn,$_POST['HR']);
  $QB=  mysqli_real_escape_string($conn,$_POST['QB']);
  $QD=  mysqli_real_escape_string($conn,$_POST['QD']);
  $AP=  mysqli_real_escape_string($conn,$_POST['AP']);
  $VP=  mysqli_real_escape_string($conn,$_POST['VP']);
  $FldRmvd=  mysqli_real_escape_string($conn,$_POST['FldRmvd']);
  $Heparin=  mysqli_real_escape_string($conn,$_POST['Heparin']);
  $Saline=  mysqli_real_escape_string($conn,$_POST['Saline']);
  $UFR=  mysqli_real_escape_string($conn,$_POST['UFR']);
  $TMP=  mysqli_real_escape_string($conn,$_POST['TMP']);
  $BVP=  mysqli_real_escape_string($conn,$_POST['BVP']);
  $Access=mysqli_real_escape_string($conn,$_POST['Access']);
  $Notes=  mysqli_real_escape_string($conn,$_POST['Notes']);
  $Patient_reg=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
  $dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);

  $data_seve = array(array(
    "Patient_reg"=>$Patient_reg,
    "Time"=>$Time,
    "BP"=>$BP,
    "HR"=>$HR,
    "QB"=>$QB,
    "QD"=>$QD,
    "AP"=>$AP,
    "VP"=>$VP,
    "FldRmvd"=>$FldRmvd,
    "Saline"=>$Saline,
    "UFR"=>$UFR,
    "TMP"=>$TMP,
    "BVP"=>$BVP,
    "Access"=>$Access,
    "Notes"=>$Notes,
    "dialysis_details_ID"=>$dialysis_details_ID
    ) );
  
//    if($numberRows>0){
//     $insert_query="UPDATE tbl_dialysis_details SET Time_1='$Time_1',Time_2='$Time_2',Time_3='$Time_3',Time_4='$Time_4',Time_5='$Time_5',Time_6='$Time_6',Time_7='$Time_7',Time_8='$Time_8',Time_9='$Time_9',Time_10='$Time_10',Time_11='$Time_11',Time_12='$Time_12',BP_1='$BP_1',BP_2='$BP_2',BP_3='$BP_3',BP_4='$BP_4',BP_5='$BP_5',BP_6='$BP_6',BP_7='$BP_7',BP_8='$BP_8',BP_9='$BP_9',BP_10='$BP_10',BP_11='$BP_11',BP_12='$BP_12',"
//             . "HR_1='$HR_1',HR_2='$HR_2',HR_3='$HR_3',HR_4='$HR_4',HR_5='$HR_5',HR_6='$HR_6',HR_7='$HR_7',HR_8='$HR_8',HR_9='$HR_9',HR_10='$HR_10',HR_11='$HR_11',HR_12='$HR_12',QB_1='$QB_1',QB_2='$QB_2',QB_3='$QB_3',QB_4='$QB_4',QB_5='$QB_5',QB_6='$QB_6',QB_7='$QB_7',QB_8='$QB_8',QB_9='$QB_9',QB_10='$QB_10',QB_11='$QB_11',QB_12='$QB_12',QD_1='$QD_1',QD_2='$QD_2',QD_3='$QD_3',QD_4='$QD_4',QD_5='$QD_5',QD_6='$QD_6',QD_7='$QD_7',QD_8='$QD_8',QD_9='$QD_9',QD_10='$QD_10',QD_11='$QD_11',QD_12='$QD_12',AP_1='$AP_1',AP_2='$AP_2',AP_3='$AP_3',AP_4='$AP_4',AP_5='$AP_5',AP_6='$AP_6',AP_7='$AP_7',AP_8='$AP_8',AP_9='$AP_9',AP_10='$AP_10',AP_11='$AP_11',AP_12='$AP_12',VP_1='$VP_1',VP_2='$VP_2',VP_3='$VP_3',VP_4='$VP_4',VP_5='$VP_5',VP_6='$VP_6',VP_7='$VP_7',VP_8='$VP_8',VP_9='$VP_9',VP_10='$VP_10',VP_11='$VP_11',VP_12='$VP_12',FldRmvd_1='$FldRmvd_1',FldRmvd_2='$FldRmvd_2',FldRmvd_3='$FldRmvd_3',FldRmvd_4='$FldRmvd_4',FldRmvd_5='$FldRmvd_5',FldRmvd_6='$FldRmvd_6',FldRmvd_7='$FldRmvd_7',FldRmvd_8='$FldRmvd_8',FldRmvd_9='$FldRmvd_9',FldRmvd_10='$FldRmvd_10',FldRmvd_11='$FldRmvd_11',FldRmvd_12='$FldRmvd_12',Heparin_1='$Heparin_1',Heparin_2='$Heparin_2',Heparin_3='$Heparin_3',Heparin_4='$Heparin_4',Heparin_5='$Heparin_5',Heparin_6='$Heparin_6',Heparin_7='$Heparin_7',Heparin_8='$Heparin_8',Heparin_9='$Heparin_9',Heparin_10='$Heparin_10',Heparin_11='$Heparin_11',Heparin_12='$Heparin_12',Saline_1='$Saline_1',Saline_2='$Saline_2',Saline_3='$Saline_3',Saline_4='$Saline_4',Saline_5='$Saline_5',Saline_6='$Saline_6',Saline_7='$Saline_7',Saline_8='$Saline_8',Saline_9='$Saline_9',Saline_10='$Saline_10',Saline_11='$Saline_11',Saline_12='$Saline_12',UFR_1='$UFR_1',UFR_2='$UFR_2',UFR_3='$UFR_3',UFR_4='$UFR_4',UFR_5='$UFR_5',UFR_6='$UFR_6',UFR_7='$UFR_7',UFR_8='$UFR_8',UFR_9='$UFR_9',UFR_10='$UFR_10',UFR_11='$UFR_11',UFR_12='$UFR_12',TMP_1='$TMP_1',TMP_2='$TMP_2',TMP_3='$TMP_3',TMP_4='$TMP_4',TMP_5='$TMP_5',TMP_6='$TMP_6',TMP_7='$TMP_7',TMP_8='$TMP_8',TMP_9='$TMP_9',TMP_10='$TMP_10',TMP_11='$TMP_11',TMP_12='$TMP_12',BVP_1='$BVP_1',BVP_2='$BVP_2',BVP_3='$BVP_3',BVP_4='$BVP_4',BVP_5='$BVP_5',BVP_6='$BVP_6',BVP_7='$BVP_7',BVP_8='$BVP_8',BVP_9='$BVP_9',BVP_10='$BVP_10',BVP_11='$BVP_11',BVP_12='$BVP_12',Access_1='$Access_1',Access_2='$Access_2',Access_3='$Access_3',Access_4='$Access_4',Access_5='$Access_5',Access_6='$Access_6',Access_7='$Access_7',Access_8='$Access_8',Access_9='$Access_9',Access_10='$Access_10',Access_11='$Access_11',Access_12='$Access_12',Notes_1='$Notes_1',Notes_2='$Notes_2',Notes_3='$Notes_3',Notes_4='$Notes_4',Notes_5='$Notes_5',Notes_6='$Notes_6',Notes_7='$Notes_7',Notes_8='$Notes_8',Notes_9='$Notes_9',Notes_10='$Notes_10',Notes_11='$Notes_11',Notes_12='$Notes_12' WHERE  Patient_reg='$Registration_ID' AND Attendance_Date='$Today'";

//     }  else {
//     $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Attendance_Date,Time_1,Time_2,Time_3,Time_4,Time_5,Time_6,Time_7,Time_8,Time_9,Time_10,Time_11,Time_12,BP_1,BP_2,BP_3,BP_4,BP_5,BP_6,BP_7,BP_8,BP_9,BP_10,BP_11,BP_12,HR_1,HR_2,HR_3,HR_4,HR_5,HR_6,HR_7,HR_8,HR_9,HR_10,HR_11,HR_12,QB_1,QB_2,QB_3,QB_4,QB_5,QB_6,QB_7,QB_8,QB_9,QB_10,QB_11,QB_12,QD_1,QD_2,QD_3,QD_4,QD_5,QD_6,QD_7,QD_8,QD_9,QD_10,QD_11,QD_12,AP_1,AP_2,AP_3,AP_4,AP_5,AP_6,AP_7,AP_8,AP_9,AP_10,AP_11,AP_12,VP_1,VP_2,VP_3,VP_4,VP_5,VP_6,VP_7,VP_8,VP_9,VP_10,VP_11,VP_12,FldRmvd_1,FldRmvd_2,FldRmvd_3,FldRmvd_4,FldRmvd_5,FldRmvd_6,FldRmvd_7,FldRmvd_8,FldRmvd_9,FldRmvd_10,FldRmvd_11,FldRmvd_12,Heparin_1,Heparin_2,Heparin_3,Heparin_4,Heparin_5,Heparin_6,Heparin_7,Heparin_8,Heparin_9,Heparin_10,Heparin_11,Heparin_12,Saline_1,Saline_2,Saline_3,Saline_4,Saline_5,Saline_6,Saline_7,Saline_8,Saline_9,Saline_10,Saline_11,Saline_12,UFR_1,UFR_2,UFR_3,UFR_4,UFR_5,UFR_6,UFR_7,UFR_8,UFR_9,UFR_10,UFR_11,UFR_12,TMP_1,TMP_2,TMP_3,TMP_4,TMP_5,TMP_6,TMP_7,TMP_8,TMP_9,TMP_10,TMP_11,TMP_12,BVP_1,BVP_2,BVP_3,BVP_4,BVP_5,BVP_6,BVP_7,BVP_8,BVP_9,BVP_10,BVP_11,BVP_12,Access_1,Access_2,Access_3,Access_4,Access_5,Access_6,Access_7,Access_8,Access_9,Access_10,Access_11,Access_12,Notes_1,Notes_2,Notes_3,Notes_4,Notes_5,Notes_6,Notes_7,Notes_8,Notes_9,Notes_10,Notes_11,Notes_12) VALUES"
//             . " ('$Registration_ID',NOW(),'$Time_1','$Time_2','$Time_3','$Time_4','$Time_5','$Time_6','$Time_7','$Time_8','$Time_9','$Time_10','$Time_11','$Time_12','$BP_1','$BP_2','$BP_3','$BP_4','$BP_5','$BP_6','$BP_7','$BP_8','$BP_9','$BP_10','$BP_11','$BP_12','$HR_1','$HR_2','$HR_3','$HR_4','$HR_5','$HR_6','$HR_7','$HR_8','$HR_9','$HR_10','$HR_11','$HR_12','$QB_1','$QB_2','$QB_3','$QB_4','$QB_5','$QB_6','$QB_7','$QB_8','$QB_9','$QB_10','$QB_11','$QB_12','$QD_1','$QD_2','$QD_3','$QD_4','$QD_5','$QD_6','$QD_7','$QD_8','$QD_9','$QD_10','$QD_11','$QD_12','$AP_1','$AP_2','$AP_3','$AP_4','$AP_5','$AP_6','$AP_7','$AP_8','$AP_9','$AP_10','$AP_11','$AP_12','$VP_1','$VP_2','$VP_3','$VP_4','$VP_5','$VP_6','$VP_7','$VP_8','$VP_9','$VP_10','$VP_11','$VP_12','$FldRmvd_1','$FldRmvd_2','$FldRmvd_3','$FldRmvd_4','$FldRmvd_5','$FldRmvd_6','$FldRmvd_7','$FldRmvd_8','$FldRmvd_9','$FldRmvd_10','$FldRmvd_11','$FldRmvd_12','$Heparin_1','$Heparin_2','$Heparin_3','$Heparin_4','$Heparin_5','$Heparin_6','$Heparin_7','$Heparin_8','$Heparin_9','$Heparin_10','$Heparin_11','$Heparin_12','$Saline_1','$Saline_2','$Saline_3','$Saline_4','$Saline_5','$Saline_6','$Saline_7','$Saline_8','$Saline_9','$Saline_10','$Saline_11','$Saline_12','$UFR_1','$UFR_2','$UFR_3','$UFR_4','$UFR_5','$UFR_6','$UFR_7','$UFR_8','$UFR_9','$UFR_10','$UFR_11','$UFR_12','$TMP_1','$TMP_2','$TMP_3','$TMP_4','$TMP_5','$TMP_6','$TMP_7','$TMP_8','$TMP_9','$TMP_10','$TMP_11','$TMP_12','$BVP_1','$BVP_2','$BVP_3','$BVP_4','$BVP_5','$BVP_6','$BVP_7','$BVP_8','$BVP_9','$BVP_10','$BVP_11','$BVP_12','$Access_1','$Access_2','$Access_3','$Access_4','$Access_5','$Access_6','$Access_7','$Access_8','$Access_9','$Access_10','$Access_11','$Access_12','$Notes_1','$Notes_2','$Notes_3','$Notes_4','$Notes_5','$Notes_6','$Notes_7','$Notes_8','$Notes_9','$Notes_10','$Notes_11','$Notes_12')";
//     }
    
    // $query= mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    if(Save_Observation_Chart(json_encode($data_seve))> 0){ 
        UpdateCache($Payment_Item_Cache_List_ID);
        echo 'ok';
    } else {
        echo mysqli_error($conn);
    }  
  
}elseif (isset ($_POST['SaveMedicationChartbtn'])) {
    $Ancillary_1=  mysqli_real_escape_string($conn,$_POST['Ancillary_1']);
    $Ancillary_2=  mysqli_real_escape_string($conn,$_POST['Ancillary_2']);
    $Ancillary_3=  mysqli_real_escape_string($conn,$_POST['Ancillary_3']);
    $Ancillary_4=  mysqli_real_escape_string($conn,$_POST['Ancillary_4']);
    $Ancillary_5=  mysqli_real_escape_string($conn,$_POST['Ancillary_5']);
    $Ancillary_6=  mysqli_real_escape_string($conn,$_POST['Ancillary_6']);
    $Ancillary_7=  mysqli_real_escape_string($conn,$_POST['Ancillary_7']);
    $Indication_1=  mysqli_real_escape_string($conn,$_POST['Indication_1']);
    $Indication_2=  mysqli_real_escape_string($conn,$_POST['Indication_2']);
    $Indication_3=  mysqli_real_escape_string($conn,$_POST['Indication_3']);
    $Indication_4=  mysqli_real_escape_string($conn,$_POST['Indication_4']);
    $Indication_5=  mysqli_real_escape_string($conn,$_POST['Indication_5']);
    $Indication_6=  mysqli_real_escape_string($conn,$_POST['Indication_6']);
    $Indication_7=  mysqli_real_escape_string($conn,$_POST['Indication_7']);
    $Dose_1=  mysqli_real_escape_string($conn,$_POST['Dose_1']);
    $Dose_2=  mysqli_real_escape_string($conn,$_POST['Dose_2']);
    $Dose_3=  mysqli_real_escape_string($conn,$_POST['Dose_3']);
    $Dose_4=  mysqli_real_escape_string($conn,$_POST['Dose_4']);
    $Dose_5=  mysqli_real_escape_string($conn,$_POST['Dose_5']);
    $Dose_6=  mysqli_real_escape_string($conn,$_POST['Dose_6']);
    $Dose_7=  mysqli_real_escape_string($conn,$_POST['Dose_7']);
    $Route_1=  mysqli_real_escape_string($conn,$_POST['Route_1']);
    $Route_2=  mysqli_real_escape_string($conn,$_POST['Route_2']);
    $Route_3=  mysqli_real_escape_string($conn,$_POST['Route_3']);
    $Route_4=  mysqli_real_escape_string($conn,$_POST['Route_4']);
    $Route_5=  mysqli_real_escape_string($conn,$_POST['Route_5']);
    $Route_6=  mysqli_real_escape_string($conn,$_POST['Route_6']);
    $Route_7=  mysqli_real_escape_string($conn,$_POST['Route_7']);
    $Time_chart_1=  mysqli_real_escape_string($conn,$_POST['Time_chart_1']);
    $Time_chart_2=  mysqli_real_escape_string($conn,$_POST['Time_chart_2']);
    $Time_chart_3=  mysqli_real_escape_string($conn,$_POST['Time_chart_3']);
    $Time_chart_4=  mysqli_real_escape_string($conn,$_POST['Time_chart_4']);
    $Time_chart_5=  mysqli_real_escape_string($conn,$_POST['Time_chart_5']);
    $Time_chart_6=  mysqli_real_escape_string($conn,$_POST['Time_chart_6']);
    $Time_chart_7=  mysqli_real_escape_string($conn,$_POST['Time_chart_7']);
    $Initials_charts_1=  mysqli_real_escape_string($conn,$_POST['Initials_charts_1']);
    $Initials_charts_2=  mysqli_real_escape_string($conn,$_POST['Initials_charts_2']);
    $Initials_charts_3=  mysqli_real_escape_string($conn,$_POST['Initials_charts_3']);
    $Initials_charts_4=  mysqli_real_escape_string($conn,$_POST['Initials_charts_4']);
    $Initials_charts_5=  mysqli_real_escape_string($conn,$_POST['Initials_charts_5']);
    $Initials_charts_6=  mysqli_real_escape_string($conn,$_POST['Initials_charts_6']);
    $Initials_charts_7=  mysqli_real_escape_string($conn,$_POST['Initials_charts_7']);
    $Ancillary_2_1=  mysqli_real_escape_string($conn,$_POST['Ancillary_2_1']);
    $Ancillary_2_2=  mysqli_real_escape_string($conn,$_POST['Ancillary_2_2']);
    $Ancillary_2_3=  mysqli_real_escape_string($conn,$_POST['Ancillary_2_3']);
    $Ancillary_2_4=  mysqli_real_escape_string($conn,$_POST['Ancillary_2_4']);
    $Ancillary_2_5=  mysqli_real_escape_string($conn,$_POST['Ancillary_2_5']);
    $Ancillary_2_6=  mysqli_real_escape_string($conn,$_POST['Ancillary_2_6']);
    $Ancillary_2_7=  mysqli_real_escape_string($conn,$_POST['Ancillary_2_7']);
    $Indication_1_1=  mysqli_real_escape_string($conn,$_POST['Indication_1_1']);
    $Indication_1_2=  mysqli_real_escape_string($conn,$_POST['Indication_1_2']);
    $Indication_1_3=  mysqli_real_escape_string($conn,$_POST['Indication_1_3']);
    $Indication_1_4=  mysqli_real_escape_string($conn,$_POST['Indication_1_4']);
    $Indication_1_5=  mysqli_real_escape_string($conn,$_POST['Indication_1_5']);
    $Indication_1_6=  mysqli_real_escape_string($conn,$_POST['Indication_1_6']);
    $Indication_1_7=  mysqli_real_escape_string($conn,$_POST['Indication_1_7']);
    $Dose_1_1=  mysqli_real_escape_string($conn,$_POST['Dose_1_1']);
    $Dose_1_2=  mysqli_real_escape_string($conn,$_POST['Dose_1_2']);
    $Dose_1_3=  mysqli_real_escape_string($conn,$_POST['Dose_1_3']);
    $Dose_1_4=  mysqli_real_escape_string($conn,$_POST['Dose_1_4']);
    $Dose_1_5=  mysqli_real_escape_string($conn,$_POST['Dose_1_5']);
    $Dose_1_6=  mysqli_real_escape_string($conn,$_POST['Dose_1_6']);
    $Dose_1_7=  mysqli_real_escape_string($conn,$_POST['Dose_1_7']);
    $Route_1_1=  mysqli_real_escape_string($conn,$_POST['Route_1_1']);
    $Route_1_2=  mysqli_real_escape_string($conn,$_POST['Route_1_2']);
    $Route_1_3=  mysqli_real_escape_string($conn,$_POST['Route_1_3']);
    $Route_1_4=  mysqli_real_escape_string($conn,$_POST['Route_1_4']);
    $Route_1_5=  mysqli_real_escape_string($conn,$_POST['Route_1_5']);
    $Route_1_6=  mysqli_real_escape_string($conn,$_POST['Route_1_6']);
    $Route_1_7=  mysqli_real_escape_string($conn,$_POST['Route_1_7']);
    $Time_1_1=  mysqli_real_escape_string($conn,$_POST['Time_1_1']);
    $Time_1_2=  mysqli_real_escape_string($conn,$_POST['Time_1_2']);
    $Time_1_3=  mysqli_real_escape_string($conn,$_POST['Time_1_3']);
    $Time_1_4=  mysqli_real_escape_string($conn,$_POST['Time_1_4']);
    $Time_1_5=  mysqli_real_escape_string($conn,$_POST['Time_1_5']);
    $Time_1_6=  mysqli_real_escape_string($conn,$_POST['Time_1_6']);
    $Time_1_7=  mysqli_real_escape_string($conn,$_POST['Time_1_7']);
    $Initials_1=  mysqli_real_escape_string($conn,$_POST['Initials_1']);
    $Initials_2=  mysqli_real_escape_string($conn,$_POST['Initials_2']);
    $Initials_3=  mysqli_real_escape_string($conn,$_POST['Initials_3']);
    $Initials_4=  mysqli_real_escape_string($conn,$_POST['Initials_4']);
    $Initials_5=  mysqli_real_escape_string($conn,$_POST['Initials_5']);
    $Initials_6=  mysqli_real_escape_string($conn,$_POST['Initials_6']);
    $Initials_7=  mysqli_real_escape_string($conn,$_POST['Initials_7']);
    
    if($numberRows>0){
    $insert_query="UPDATE tbl_dialysis_details SET Ancillary_1='$Ancillary_1',Ancillary_2='$Ancillary_2',Ancillary_3='$Ancillary_3',Ancillary_4='$Ancillary_4',Ancillary_5='$Ancillary_5',Ancillary_6='$Ancillary_6',Ancillary_7='$Ancillary_7',Indication_1='$Indication_1',Indication_2='$Indication_2',Indication_3='$Indication_3',Indication_4='$Indication_4',Indication_5='$Indication_5',Indication_6='$Indication_6',Indication_7='$Indication_7',Dose_1='$Dose_1',Dose_2='$Dose_2',Dose_3='$Dose_3',Dose_4='$Dose_4',Dose_5='$Dose_5',Dose_6='$Dose_6',Dose_7='$Dose_7',Route_1='$Route_1',Route_2='$Route_2',Route_3='$Route_3',Route_4='$Route_4',Route_5='$Route_5',Route_6='$Route_6',Route_7='$Route_7',Time_chart_1='$Time_chart_1',Time_chart_2='$Time_chart_2',Time_chart_3='$Time_chart_3',Time_chart_4='$Time_chart_4',Time_chart_5='$Time_chart_5',Time_chart_6='$Time_chart_6',Time_chart_7='$Time_chart_7',Initials_charts_1='$Initials_charts_1',Initials_charts_2='$Initials_charts_2',Initials_charts_3='$Initials_charts_3',Initials_charts_4='$Initials_charts_4',Initials_charts_5='$Initials_charts_5',Initials_charts_6='$Initials_charts_6',Initials_charts_7='$Initials_charts_7',Ancillary_2_1='$Ancillary_2_1',Ancillary_2_2='$Ancillary_2_2',Ancillary_2_3='$Ancillary_2_3',Ancillary_2_4='$Ancillary_2_4',Ancillary_2_5='$Ancillary_2_5',Ancillary_2_6='$Ancillary_2_6',Ancillary_2_7='$Ancillary_2_7',Indication_1_1='$Indication_1_1',Indication_1_2='$Indication_1_2',Indication_1_3='$Indication_1_3',Indication_1_4='$Indication_1_4',Indication_1_5='$Indication_1_5',Indication_1_6='$Indication_1_6',Indication_1_7='$Indication_1_7',Dose_1_1='$Dose_1_1',Dose_1_2='$Dose_1_2',Dose_1_3='$Dose_1_3',Dose_1_4='$Dose_1_4',Dose_1_5='$Dose_1_5',Dose_1_6='$Dose_1_6',Dose_1_7='$Dose_1_7',Route_1_1='$Route_1_1',Route_1_2='$Route_1_2',Route_1_3='$Route_1_3',Route_1_4='$Route_1_4',Route_1_5='$Route_1_5',Route_1_6='$Route_1_6',Route_1_7='$Route_1_7',Time_1_1='$Time_1_1',Time_1_2='$Time_1_2',Time_1_3='$Time_1_3',Time_1_4='$Time_1_4',Time_1_5='$Time_1_5',Time_1_6='$Time_1_6',Time_1_7='$Time_1_7',Initials_1='$Initials_1',Initials_2='$Initials_2',Initials_3='$Initials_3',Initials_4='$Initials_4',Initials_5='$Initials_5',Initials_6='$Initials_6',Initials_7='$Initials_7' WHERE  Patient_reg='$Registration_ID' AND Attendance_Date='$Today'";

    } else {
        
    $insert_query="INSERT INTO tbl_dialysis_details (Patient_reg,Ancillary_1,Ancillary_2,Ancillary_3,Ancillary_4,Ancillary_5,Ancillary_6,Ancillary_7,Indication_1,Indication_2,Indication_3,Indication_4,Indication_5,Indication_6,Indication_7,Dose_1,Dose_2,Dose_3,Dose_4,Dose_5,Dose_6,Dose_7,Route_1,Route_2,Route_3,Route_4,Route_5,Route_6,Route_7,Time_chart_1,Time_chart_2,Time_chart_3,Time_chart_4,Time_chart_5,Time_chart_6,Time_chart_7,Initials_charts_1,Initials_charts_2,Initials_charts_3,Initials_charts_4,Initials_charts_5,Initials_charts_6,Initials_charts_7,Ancillary_2_1,Ancillary_2_2,Ancillary_2_3,Ancillary_2_4,Ancillary_2_5,Ancillary_2_6,Ancillary_2_7,Indication_1_1,Indication_1_2,Indication_1_3,Indication_1_4,Indication_1_5,Indication_1_6,Indication_1_7,Dose_1_1,Dose_1_2,Dose_1_3,Dose_1_4,Dose_1_5,Dose_1_6,Dose_1_7,Route_1_1,Route_1_2,Route_1_3,Route_1_4,Route_1_5,Route_1_6,Route_1_7,Time_1_1,Time_1_2,Time_1_3,Time_1_4,Time_1_5,Time_1_6,Time_1_7,Initials_1,Initials_2,Initials_3,Initials_4,Initials_5,Initials_6,Initials_7) VALUES ('$Registration_ID','$Ancillary_1','$Ancillary_2','$Ancillary_3','$Ancillary_4','$Ancillary_5','$Ancillary_6','$Ancillary_7','$Indication_1','$Indication_2','$Indication_3','$Indication_4','$Indication_5','$Indication_6','$Indication_7','$Dose_1','$Dose_2','$Dose_3','$Dose_4','$Dose_5','$Dose_6','$Dose_7','$Route_1','$Route_2','$Route_3','$Route_4','$Route_5','$Route_6','$Route_7','$Time_chart_1','$Time_chart_2','$Time_chart_3','$Time_chart_4','$Time_chart_5','$Time_chart_6','$Time_chart_7','$Initials_charts_1','$Initials_charts_2','$Initials_charts_3','$Initials_charts_4','$Initials_charts_5','$Initials_charts_6','$Initials_charts_7','$Ancillary_2_1','$Ancillary_2_2','$Ancillary_2_3','$Ancillary_2_4','$Ancillary_2_5','$Ancillary_2_6','$Ancillary_2_7','$Indication_1_1','$Indication_1_2','$Indication_1_3','$Indication_1_4','$Indication_1_5','$Indication_1_6','$Indication_1_7','$Dose_1_1','$Dose_1_2','$Dose_1_3','$Dose_1_4','$Dose_1_5','$Dose_1_6','$Dose_1_7','$Route_1_1','$Route_1_2','$Route_1_3','$Route_1_4','$Route_1_5','$Route_1_6','$Route_1_7','$Time_1_1','$Time_1_2','$Time_1_3','$Time_1_4','$Time_1_5','$Time_1_6','$Time_1_7','$Initials_1','$Initials_2','$Initials_3','$Initials_4','$Initials_5','$Initials_6','$Initials_7')";
  
    }
    
    $query= mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    if($query){
        UpdateCache($Payment_Item_Cache_List_ID);
        echo 'Successfully Saved';
    } else {
        echo mysqli_error($conn);
    }  
  
    
    
    
}   

?>
