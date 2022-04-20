<?php 
    @session_start();
    require_once './includes/constants.php';
    include("./includes/connection.php");
    include_once("./functions/items.php");

    #SESSION HOLDED VALUES
    $employee_id = $_SESSION['userinfo']['Employee_ID'];
    $employee_name = $_SESSION['userinfo']['Employee_Name'];
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];

    #GET POSTED VALUES
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $Transaction_Type = $_GET['Transaction_Type'];
    $Registration_ID = $_GET['Registration_ID'];
    $Check_In_Type = $_GET['Check_In_Type'];
    $Check_In_ID = $_GET['Check_In_ID'];
    $Sub_Department_Name = $_SESSION['Pharmacy'];
    $selected_item = $_GET['selected_item'];
    $check_in_type = 'Pharmacy';
    $Guarantor_Name = $_GET['Guarantor_Name'];
    $Sponsor_ID = $_GET['Sponsor_ID'];
    $get_employee_name = $_GET['Employee_Name'];

    #GET SUBDEPARTMENT ID IF ITS NOT POSTED
    if (isset($_SESSION['Pharmacy_ID'])) {
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    } else {
        $sql = mysqli_query($conn,"SELECT Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name'");
        $no = mysqli_num_rows($sql);
        if ($no > 0) {
            while ($row = mysqli_fetch_array($sql)) { $Sub_Department_ID = $row['Sub_Department_ID']; }
        } else {
            $Sub_Department_ID = 0;
        }
    }

    #GET LAST CHECKIN ID
    $sql_select_check_id_result2=mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") 
                                                     or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_check_id_result2)>0){
        $Check_In_ID=mysqli_fetch_assoc($sql_select_check_id_result2)['Check_In_ID'];  
    }
    $payment_type = 'post';

    $select = mysqli_query($conn,"SELECT Claim_Form_Number,cd.Sponsor_ID,Guarantor_Name from tbl_check_in_details cd JOIN tbl_sponsor sp 
                                  ON cd.Sponsor_ID=sp.Sponsor_ID WHERE cd.Check_In_ID= '$Check_In_ID'") or die(mysqli_error($conn));
    $rows_info = mysqli_fetch_array($select);
    $Claim_Form_Number = $rows_info['Claim_Form_Number'];

    #CREATE INPATIENT BILL DEBIT NOTE
    $insert_details = mysqli_query($conn,"INSERT INTO tbl_patient_payments(Registration_ID, Supervisor_ID, Employee_ID,Payment_Date_And_Time, 
                                          Folio_Number,Claim_Form_Number, Sponsor_ID, Sponsor_Name,Billing_Type, Receipt_Date, 
                                          branch_id,Check_In_ID,Patient_Bill_ID,payment_type,Fast_Track,terminal_id,auth_code,manual_offline)
                                          VALUES ('$Registration_ID','$employee_id','$employee_id',NOW(),'$Folio_Number',
                                          '$Claim_Form_Number','$Sponsor_ID','$Guarantor_Name','Inpatient Cash',(select now()),
                                          '$Branch_ID','$Check_In_ID','$Patient_Bill_ID','$payment_type','$Fast_Track','$terminal_id','$auth_code',
                                          '$manual_offline')") or die(mysqli_error($conn));

    if ($insert_details) {
        //GET LAST PATIENT PAYMENT ID
        $select = mysqli_query($conn,"SELECT Patient_Payment_ID, Receipt_Date from tbl_patient_payments WHERE Registration_ID = '$Registration_ID' 
                                      AND Employee_ID = '$employee_id' order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($row = mysqli_fetch_array($select)) {
                $Patient_Payment_ID = $row['Patient_Payment_ID'];
                $Receipt_Date = $row['Receipt_Date'];
            }
        }

        #LOOP EVERY INCOMING ITEM
        foreach ($selected_item as $selected_item){
            $selectedItemid = $selected_item['id'];
            $dose_qty = $selected_item['doseqty'];
            $dispensed_qty = $selected_item['dispensedqty'];
            $dose_duration = $selected_item['dose_duration'];
            $Payment_Item_Cache_List_ID = $selected_item['Payment_Item_Cache_List_ID'];

            #GET ITEM AND UPDATE
            $select_item = mysqli_query($conn,"SELECT * FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' 
                                               AND Check_In_Type = 'Pharmacy' ");
            
            while($data = mysqli_fetch_array($select_item)){
                $Payment_Item_Cache_List_ID = $data['Payment_Item_Cache_List_ID'];
                $Item_ID = $data['Item_ID'];
                $Status = $data['Status'];
                $Payment_Cache_ID = $data['Payment_Cache_ID'];
                $dose = $data['dose'];
                $Quantity = $data['Quantity'];
                $Edited_Quantity = $data['Edited_Quantity'];
                $dispensed_quantity = $data['dispensed_quantity'];
                $clinic_location_id = $data['clinic_location_id'];
                $finance_department_id = $data['finance_department_id'];
                $Clinic_ID = $data['Clinic_ID'];
                $Consultant_ID = $data['Consultant_ID'];
                $Discount = $data['Discount'];
                $Sub_Department_ID = $data['Sub_Department_ID'];
                $Price = $data['Price'];
                
                $dose = $data['dose'];
                $total_dispensed = $dispensed_quantity + $dispensed_qty;
                
                if($total_dispensed < $dose_qty){
                    $sts = "partial dispensed";
                }else{
                    $sts = "dispensed";
                }
                if ($Edited_Quantity != 0) {
                    $Temp_Quantity = $Edited_Quantity;
                } else {
                    $Temp_Quantity = $Quantity;
                }
                $quantity = $Temp_Quantity;
                $subTotal = $Last_Buy_Price * $Temp_Quantity;

                #GET EMPLOYEE NAME
                $get_consultant_name = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employee_id'") or 
                                                           die(mysqli_error($conn));
                
                if(mysqli_num_rows($sql_select_consultant_name_result) > 0){
                    $Employee_Name=mysqli_fetch_assoc($sql_select_consultant_name_result)['Employee_Name'];
                }else{
                    $Employee_Name=""; 
                }

                #CREATE INPATIENT BILL FOR OUTPATIENTS
                $insert = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(Check_In_Type, Item_ID, Price, Discount,
                                              Quantity, Patient_Direction, Consultant,Consultant_ID, Patient_Payment_ID, Transaction_Date_And_Time,
                                              Sub_Department_ID,finance_department_id,clinic_location_id)
                                              VALUES ('Pharmacy','$Item_ID','$Price','$Discount',
                                              '$dispensed_qty','others','$get_employee_name',
                                              '$Consultant_ID', '$Patient_Payment_ID', NOW(),'$Sub_Department_ID','$finance_department_id',
                                              '$clinic_location_id')") or die(mysqli_error($conn));

                if ($insert) {
                    #UPDATE ITEM STATUS
                    $update = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status = '$sts',Dispensor='$employee_id',
                                                Dispense_Date_Time ='$original_Date', Edited_Quantity = $dispensed_qty,
                                                Patient_Payment_ID = '$Patient_Payment_ID',dosage_duration = '$dose_duration',
                                                Payment_Date_And_Time = '$Receipt_Date',Dispense_Date_Time = NOW(),
                                                dose = $dose_qty, dispensed_quantity = $total_dispensed where 
                                                Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));

                    if($update){
                        $insert_history = mysqli_query($conn,"INSERT INTO `tbl_partial_dispense_history`(`employee_id`, `patient_id`, `item_id`, 
                                                              `item_cache_list_id`, `dose_qty`, `dispensed_qty`) VALUES ('$employee_id', 
                                                              '$Registration_ID', '$Item_ID', '$Payment_Item_Cache_List_ID', '$dose_qty', '$dispensed_qty')") 
                                                              or die(mysqli_error($conn));
                    }

                    $slct = mysqli_query($conn,"SELECT Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' 
                                                and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
                    #GET LAST ITEM BALANCE
                    $nm = mysqli_num_rows($slct);
                    if ($nm > 0) {
                        while ($dt = mysqli_fetch_array($slct)) { $Pre_Balance = $dt['Item_Balance']; }
                    } else {
                        $rs1 = mysqli_query($conn," INSERT into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or 
                                                    die(mysqli_error($conn));
                        if (!$rs1) { $HAS_ERROR = true; }
                        $Pre_Balance = 0;
                    }

                    $update_balance = mysqli_query($conn,"UPDATE tbl_items_balance set Item_Balance = (Item_Balance - '$dispensed_qty'),
                                                          Item_Temporary_Balance  = (Item_Temporary_Balance - '$dispensed_qty')
                                                          where Sub_Department_ID = '$Sub_Department_ID' and
                                                          Item_ID = '$Item_ID'") or die(mysqli_error($conn));

                    if ($update_balance) {
                        //UPDATE TRANSACTION ITEM FROM tbl_patient_payment_item_list
                        $update_status = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list set Status = 'Served'
                                                    WHERE Patient_Payment_ID = '$Patient_Payment_ID' and
                                                    Item_ID = '$Item_ID'") or die(mysqli_error($conn));

                        if (!$update_status) { }
                        if (!$update) { 
                            $HAS_ERROR = true;
                        }else{ }

                        //INSERT DATA INTO tbl_stock_ledger_controler FOR AUDITION
                        $insert_audit = mysqli_query($conn,"INSERT into tbl_stock_ledger_controler(
                                                    Item_ID, Sub_Department_ID, Movement_Type, Registration_ID,
                                                    Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)
                                                    VALUES('$Item_ID','$Sub_Department_ID','Dispensed','$Registration_ID',
                                                    '$Pre_Balance',($Pre_Balance - $dispensed_qty),(SELECT now()),NOW(),'$Patient_Payment_ID')") 
                                                    or die(mysqli_error($conn));

                        if (!$insert_audit) { $HAS_ERROR = true; }
                    } else { 
                        $HAS_ERROR = true; 
                    }

                    #CHECK IF PATIENT HAS FOLIO
                    if ($has_no_folio) {
                        $result_cd = mysqli_query($conn,"SELECT Check_In_Details_ID FROM tbl_check_in_details WHERE Registration_ID = '$Registration_ID' AND Check_In_ID = '$Check_In_ID' AND consultation_ID IS NOT NULL ORDER BY Check_In_Details_ID DESC LIMIT 1") or die(mysqli_error($conn));
                        $Check_In_Details_ID = mysqli_fetch_assoc($result_cd)['Check_In_Details_ID'];
                        $update_checkin_details = "UPDATE tbl_check_in_details SET Folio_Number='$Folio_Number'
                        WHERE Check_In_Details_ID='$Check_In_Details_ID' ";
                        mysqli_query($conn,$update_checkin_details) or die(mysqli_error($conn));
                    }


                }

            }
            echo 'Dispensed Successfull';
        }
    
    }

?>