<?php
@session_start();
include("./includes/connection.php");



$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if(isset($_POST['selected_item'])){
    $selected_item=$_POST['selected_item'];
    $Registration_ID=$_POST['Registration_ID'];
    $Sponsor_ID=$_POST['Sponsor_ID'];
    $Sponsor_Name=$_POST['Guarantor_Name'];
    $Check_In_Type=$_POST['Check_In_Type'];
    

    
    
    
    if(empty($Folio_Number) || $Folio_Number==0){
        include("./includes/Folio_Number_Generator_Emergency.php");

      }
      
      
      //include("./includes/Get_Patient_Check_In_Id.php");
        include("./includes/Get_Patient_Transaction_Number.php");
    //get branch id
    if (isset($_SESSION['userinfo']['Branch_ID'])) {
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    } else {
        $Branch_ID = 0;
    }
    $payment_type = 'post';
     $sql_select_check_id_result2=mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_check_id_result2)>0){
      $Check_In_ID=mysqli_fetch_assoc($sql_select_check_id_result2)['Check_In_ID'];  
    }
    
    $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp ON cd.Sponsor_ID=sp.Sponsor_ID  WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
    $rows_info = mysqli_fetch_array($select);

    $Claim_Form_Number = $rows_info['Claim_Form_Number'];
    
    ///create inpatient bill debit note
    
     $insert_details = mysqli_query($conn,"INSERT INTO tbl_patient_payments(
                                                        Registration_ID, Supervisor_ID, Employee_ID,
                                                        Payment_Date_And_Time, Folio_Number,
                                                        Claim_Form_Number, Sponsor_ID, Sponsor_Name,
                                                        Billing_Type, Receipt_Date, branch_id,Check_In_ID,Patient_Bill_ID,payment_type,Fast_Track,terminal_id,auth_code,manual_offline)
                                                        
                                                        VALUES ('$Registration_ID','$Employee_ID','$Employee_ID',
                                                            (select now()),'$Folio_Number',
                                                            '$Claim_Form_Number','$Sponsor_ID','$Sponsor_Name',
                                                            'Inpatient Cash',(select now()),'$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$payment_type','$Fast_Track','$terminal_id','$auth_code','$manual_offline')") or die(mysqli_error($conn));
    if ($insert_details) {
                        //get the last Patient Payment ID
                        $select = mysqli_query($conn,"select Patient_Payment_ID, Receipt_Date from tbl_patient_payments
                                                    where Registration_ID = '$Registration_ID' and
                                                        Employee_ID = '$Employee_ID' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if ($num > 0) {
                            while ($row = mysqli_fetch_array($select)) {
                                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                                $Receipt_Date = $row['Receipt_Date'];
                        }
                    }
                    foreach ($selected_item as $Payment_Item_Cache_List_ID){
                     $Payment_Item_Cache_List_ID;
                     echo "$Payment_Item_Cache_List_ID::::";
                    //insert data to tbl_patient_payment_item_list
                            $select_details = mysqli_query($conn,"select * from tbl_item_list_cache
                                                    where Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));

                            while ($dt = mysqli_fetch_array($select_details)) {
                                echo "ndani";
                                $Item_ID = $dt['Item_ID'];
                                $Price = $dt['Price'];
                                $Discount = $dt['Discount'];
                                $Quantity = $dt['Quantity'];
                                $Consultant_ID = $dt['Consultant_ID'];
                                $Sub_Department_ID = $dt['Sub_Department_ID'];
                                $Dosage = $dt['Dosage'];
                                $Clinic_ID = $dt['Clinic_ID'];
                                $finance_department_id = $dt['finance_department_id'];
                                $clinic_location_id = $dt['clinic_location_id'];
                                
                                 $sql_select_consultant_name_result=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_consultant_name_result)){
                                    $Employee_Name=mysqli_fetch_assoc($sql_select_consultant_name_result)['Employee_Name'];
                                }else{
                                   $Employee_Name=""; 
                                }
                                $insert = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(
                                                            Check_In_Type, Item_ID, Price, Discount,
                                                            Quantity, Patient_Direction, Consultant,
                                                            Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,Sub_Department_ID,finance_department_id,clinic_location_id)
                                                            
                                                            VALUES ('$Check_In_Type','$Item_ID','$Price','$Discount',
                                                            '$Quantity','others','$Employee_Name',
                                                            '$Consultant_ID', '$Patient_Payment_ID', (select now()),'$Sub_Department_ID','$finance_department_id','$clinic_location_id')") or die(mysqli_error($conn));
                            
                                if ($insert) {
                                    echo "imeinsert ndani,,,,>>$Payment_Item_Cache_List_ID";
                                //update tbl_item_list_cache
                                mysqli_query($conn,"update tbl_item_list_cache set
                                                    Patient_Payment_ID = '$Patient_Payment_ID',
                                                        Payment_Date_And_Time = '$Receipt_Date',
                                                            status='paid' where
                                                            Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));

                                //check if this user has folio 

                                if ($has_no_folio) {
                                    $result_cd = mysqli_query($conn,"SELECT Check_In_Details_ID FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID IS NOT NULL ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));
                                    $Check_In_Details_ID = mysqli_fetch_assoc($result_cd)['Check_In_Details_ID'];
                                    $update_checkin_details = "UPDATE tbl_check_in_details SET Folio_Number='$Folio_Number'
                                                    WHERE Check_In_Details_ID='$Check_In_Details_ID' ";
                                    mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
                                }

                               
                            }
                                }
                 }
    }
     
     
     
        
    
    
}
?>