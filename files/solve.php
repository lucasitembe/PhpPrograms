<?php
    include("./includes/connection.php");

//SELECT ITEM FROM LAB RESULTS

// $SELECT_lab = mysqli_query($conn, "SELECT * FROM `tbl_item_list_cache` WHERE Status IN ('active','paid')  AND Check_In_Type = 'Pharmacy' AND Dispensor > 0") or die(mysqli_error($conn));
//     $temp =1;
//     while($datas = mysqli_fetch_array($SELECT_lab)){
//         $Payment_Item_Cache_List_ID = $datas['Payment_Item_Cache_List_ID'];
    
    
//     //UPDATE STATUS INTO SAMPLE COLLECTED

//         $update_file = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Status = 'dispensed' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
//         if($update_file){
//             echo $temp."====> UPDATE tbl_item_list_cache SET Status = 'dispensed' WHERE Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID";
//             echo "<br>";
//             $temp ++;
//         }
//     }


    // $data_za_ndani = mysqli_query($conn, "SELECT * FROM `tbl_item_list_cache` WHERE `card_and_mobile_payment_transaction_id`='77100000225436'");
    // $tempp = 1;
    //     while($dt = mysqli_fetch_array($data_za_ndani)){
    //         $Payment_Item_Cache_List_ID = $dt['Payment_Item_Cache_List_ID'];

    //         $solve = mysqli_query($conn, "UPDATE `tbl_item_list_cache` SET `Status` = 'active', card_and_mobile_payment_status = 'unprocessed', card_and_mobile_payment_transaction_id = '' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'");
            
    //         if($solve){
    //             echo $tempp."=====>UPDATE `tbl_item_list_cache` SET `Status` = 'active', card_and_mobile_payment_status = 'unprocessed', card_and_mobile_payment_transaction_id = '' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'"."<br/>";
    //             $tempp++;
    //         }
    //     }


    // $data_za_surgery = mysqli_query($conn, "SELECT pc.Payment_Cache_ID, pc.Registration_ID, ilc.Dispensor,  pc.Sponsor_ID, pc.Billing_Type, pc.Folio_Number, ilc.Dispense_Date_Time FROM tbl_payment_cache pc, tbl_item_list_cache ilc WHERE pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Pharmacy' AND ilc.Status='dispensed' AND ilc.Dispensor > 0 AND ilc.Patient_Payment_ID ='4039859'");
    // $temppp=1;

    // while($rows = mysqli_fetch_array($data_za_surgery)){
    //     $Registration_ID = $rows['Registration_ID'];
    //     $Sponsor_ID = $rows['Sponsor_ID'];
    //     $Billing_Type = $rows['Billing_Type'];
    //     $Folio_Number = $rows['Folio_Number'];
    //     $Payment_Cache_ID = $rows['Payment_Cache_ID'];
    //     $ServedDateTime = $rows['Dispense_Date_Time'];
    //     $ServedBy = $rows['Dispensor'];
    //     $payment_type = 'post';

    //         $Patient_Bill_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Bill_ID FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' ORDER BY Patient_Payment_ID DESC LIMIT 1"))['Patient_Bill_ID'];

    //         $Check_In_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1"))['Check_In_ID'];

    //         if($Billing_Type == 'Inpatient Cash' || $Billing_Type == 'Outpatient Cash'){
    //             $Pre_Paid = 1;
    //         }else{
    //             $Pre_Paid = 0;
    //         }

    //         $Transaction_status = 'indirect cash';
    //         $INSERT_Payment_ID = mysqli_query($conn, "INSERT INTO tbl_patient_payments (Registration_ID, Folio_Number, Employee_ID, Payment_Date_And_Time, Receipt_Date, Check_In_ID, Sponsor_ID, Billing_Type, Transaction_status, payment_type, branch_id, Patient_Bill_ID, Pre_Paid) VALUES('$Registration_ID', '$Folio_Number', '$ServedBy', '$ServedDateTime', '$ServedDateTime', '$Check_In_ID', '$Sponsor_ID', '$Billing_Type', '$Transaction_status', '$payment_type', '1', '$Patient_Bill_ID', '$Pre_Paid')") or die(mysqli_error($conn));

    //         if($INSERT_Payment_ID){
    //             $Patient_Payment_ID = mysqli_insert_id($conn);
    //         }

    //     $select_sugery = mysqli_query($conn, "SELECT Payment_Item_Cache_List_ID, Price, Discount, Item_ID, Check_In_Type, Quantity, Dispense_Date_Time, finance_department_id, Clinic_ID, Sub_Department_ID FROM tbl_item_list_cache WHERE Payment_Cache_ID = '$Payment_Cache_ID' AND  Check_In_Type = 'Pharmacy' AND `Status`='dispensed' AND Dispensor>0 AND Patient_Payment_ID ='4039859'");
    //         if(mysqli_num_rows($select_sugery) > 0){
    //             while($surge = mysqli_fetch_array($select_sugery)){
    //                 $Payment_Item_Cache_List_ID = $surge['Payment_Item_Cache_List_ID'];
    //                 $Price = $surge['Price'];
    //                 $Discount = $surge['Discount'];
    //                 $Item_ID = $surge['Item_ID'];
    //                 $Check_In_Type = $surge['Check_In_Type'];
    //                 $Quantity = $surge['Quantity'];
    //                 $ServedDateTime = $surge['Dispense_Date_Time'];
    //                 $finance_department_id = $surge['finance_department_id'];
    //                 $Clinic_ID = $surge['Clinic_ID'];
    //                 $Sub_Department_ID = $surge['Sub_Department_ID'];
    //                 $item_origin = 'Doctor';

    //                 $INSERT_RECEIPT = mysqli_query($conn, "INSERT INTO tbl_patient_payment_item_list (Check_In_Type, Item_ID, Discount, Price, Quantity, Patient_Direction, Consultant_ID, Clinic_ID, Patient_Payment_ID, Transaction_Date_And_Time, Sub_Department_ID, ServedDateTime, ItemOrigin, finance_department_id) VALUES('$Check_In_Type', '$Item_ID', '$Discount', '$Price', '$Quantity', 'others', '$ServedBy', '$Clinic_ID', '$Patient_Payment_ID', '$ServedDateTime', '$Sub_Department_ID', '$ServedDateTime', '$item_origin', '$finance_department_id')") or die(mysqli_error($conn));

    //                 if($INSERT_RECEIPT){
    //                     $update_cache = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Patient_Payment_ID = '$Patient_Payment_ID' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
    //                 }


    //                 echo $temppp."=======>INSERT INTO tbl_patient_payment_item_list (Check_In_Type, Item_ID, Discount, Price, Quantity, Patient_Direction, Consultant_ID, Clinic_ID, Patient_Payment_ID, Transaction_Date_And_Time, Sub_Department_ID, ServedDateTime, ItemOrigin, finance_department_id) VALUES('$Check_In_Type', '$Item_ID', '$Discount', '$Price', '$Quantity', 'others', '$ServedBy', '$Clinic_ID', '$Patient_Payment_ID', '$ServedDateTime', '$Sub_Department_ID', '$ServedDateTime', '$item_origin', '$finance_department_id')";
    //                 echo "<br>";
    //             }
    //             $temppp++;
    //         }
    // }


    // $select_payment = mysqli_query($conn, "SELECT * FROM tbl_patient_payments WHERE Billing_Type LIKE 'Patient From Outside' AND payment_type = 'post' AND Sponsor_ID IN(SELECT Sponsor_ID From tbl_sponsor WHERE payment_method = 'credit')");
    // $num = 1;
    // while($data = mysqli_fetch_array($select_payment)){
    //     $Patient_Payment_ID = $data['Patient_Payment_ID'];
    //     $Billing_Type = $data['Billing_Type'];

    //     if($Patient_Payment_ID > 0){
    //         $data_solve = mysqli_query($conn, "UPDATE tbl_patient_payments SET Billing_Type = 'Outpatient Credit' WHERE Patient_Payment_ID = '$Patient_Payment_ID'");
    //     }

    //     if($data_solve){
    //         echo $num.", <br>";
    //     }
    //     $num++;
    // }



        // $select_payment = mysqli_query($conn, "SELECT tm.Payment_Number,tm.Payment_Date, tm.receiptNumber,tc.payment_direction FROM tbl_card_and_mobile_payment_transaction tc,tbl_mobile_payemts tm WHERE tc.bill_payment_code=tm.Payment_Number AND tc.transaction_status='pending' AND tm.Payment_Date BETWEEN '2021-10-01 00:00:00' AND NOW()");
        $select_payment = mysqli_query($conn, "SELECT tm.Payment_Number,tm.Payment_Date, tm.receiptNumber,tc.payment_direction FROM tbl_card_and_mobile_payment_transaction tc,tbl_mobile_payemts tm WHERE tc.bill_payment_code=tm.Payment_Number AND tc.transaction_status='pending' AND tc.bill_payment_code = '77100000329707'");
    $num = 1;
    $log ='';
    // if(mysqli_num_rows($select_payment) > 0){
    //     echo "IMEPITA";
    // }else{
    //     echo "IMEKWAMA";
    // }
    // exit();

    while($data = mysqli_fetch_array($select_payment)){
        $Payment_Number = $data['Payment_Number'];
        $receiptNumber = $data['receiptNumber'];
        $payment_direction = $data['payment_direction'];
        $Payment_Date = $data['Payment_Date'];


        $sql_select_all_pending_transaction_result = mysqli_query($conn, "SELECT Registration_ID,Employee_ID,card_and_mobile_payment_transaction_id,Payment_Cache_ID FROM tbl_card_and_mobile_payment_transaction WHERE transaction_status='pending' and bill_payment_code='$Payment_Number'") or die(mysqli_error($conn));

        if (mysqli_num_rows($sql_select_all_pending_transaction_result) > 0) {
            while ($transaction_id_rows = mysqli_fetch_assoc($sql_select_all_pending_transaction_result)) {
                $card_and_mobile_payment_transaction_id = "10001" . $transaction_id_rows['card_and_mobile_payment_transaction_id'];
                $Payment_Cache_ID = $transaction_id_rows['Payment_Cache_ID'];
                $Employee_ID = $transaction_id_rows['Employee_ID'];
                $Registration_ID = $transaction_id_rows['Registration_ID'];
                $sql_select_receipt_details_result = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID='$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
                if (mysqli_num_rows($sql_select_receipt_details_result) > 0) {
                    $check_in_id_rows = mysqli_fetch_assoc($sql_select_receipt_details_result);
                    $Check_In_ID = $check_in_id_rows['Check_In_ID'];
                    $log.= "===>11";

                    echo "Imefeli hapa 3";
                    $sql_other_payment_detail_result = mysqli_query($conn, "SELECT Folio_Number,Sponsor_ID,Billing_Type FROM tbl_payment_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
                    if (mysqli_num_rows($sql_other_payment_detail_result) > 0) {
                        $other_payment_deta_rows = mysqli_fetch_assoc($sql_other_payment_detail_result);
                        $Folio_Number = $other_payment_deta_rows['Folio_Number'];
                        $Sponsor_ID = $other_payment_deta_rows['Sponsor_ID'];
                        $Billing_Type = $other_payment_deta_rows['Billing_Type'];
                        $log.= "===>22=>";

                        echo "Imefeli hapa 4";
                        $log.= $Registration_ID . "==>";
                        $sql_get_bill_id_result = mysqli_query($conn, "SELECT Patient_Bill_ID FROM tbl_patient_bill WHERE Registration_ID='$Registration_ID' AND Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
                        $log.= mysqli_num_rows($sql_get_bill_id_result);
                        if (mysqli_num_rows($sql_get_bill_id_result) > 0) {
                            $Patient_Bill_ID = mysqli_fetch_assoc($sql_get_bill_id_result) ['Patient_Bill_ID'];
                        } else {
                            $Patient_Bill_ID = 0;
                        }
                            $log.= "===>33=";

                            echo "Imefeli hapa 5";
                            // $Patient_Bill_ID = mysqli_fetch_assoc($sql_get_bill_id_result) ['Patient_Bill_ID'];
                            // $sql_create_receipt_result = mysqli_query($conn, "INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$receiptNumber','Mobile Online',NOW(),CURDATE())") or die(mysqli_error($conn));
                            // if (!$sql_create_receipt_result) {
                            //     $an_error_occured = TRUE;
                            //     $log.= "yajuu~~!ERROR";
                            // }


                            //*****************************NEW*****************************************
                            $chq = mysqli_query($conn, "SELECT Check_In_Type FROM tbl_item_list_cache WHERE card_and_mobile_payment_transaction_id='$Payment_Number'") or die(mysqli_error($conn));
                            $checkintype = mysqli_fetch_assoc($chq) ['Check_In_Type'];
                            $sql_select_receipt_result = mysqli_query($conn, "SELECT auth_code FROM tbl_patient_payments WHERE Registration_ID='$Registration_ID' and auth_code='$Payment_Number'") or die(mysqli_error($conn));
                            if (mysqli_num_rows($sql_select_receipt_result) > 0) {
                                $Patient_Payment_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE auth_code='$Payment_Number' order by Patient_Payment_ID desc limit 1")) ['Patient_Payment_ID'];
                            } else {
                                if ($checkintype == "Direct Cash") {
                                    $sql_create_receipt_result = mysqli_query($conn, "INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date,payment_direction,Transaction_type) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$Payment_Number','Mobile Online','$Payment_Date',CURDATE(),'$payment_direction','Direct Cash')") or die(mysqli_error($conn));
                                    $Patient_Payment_ID = mysqli_insert_id($conn);
                                } else {
                                    $sql_create_receipt_result = mysqli_query($conn, "INSERT INTO tbl_patient_payments(Registration_ID,Supervisor_ID,Employee_ID,Folio_Number,Check_In_ID,Sponsor_ID,Billing_Type,payment_type,Patient_Bill_ID,auth_code,manual_offline,Payment_Date_And_Time,Receipt_Date,payment_direction) VALUES('$Registration_ID','$Employee_ID','$Employee_ID','$Folio_Number','$Check_In_ID','$Sponsor_ID','$Billing_Type','pre','$Patient_Bill_ID','$Payment_Number','Mobile Online','$Payment_Date',CURDATE(),'$payment_direction')") or die(mysqli_error($conn));
                                    $Patient_Payment_ID = mysqli_insert_id($conn);
                                }
                            }
                            $log.= "===>3";
                            // $Patient_Payment_ID = mysqli_insert_id($conn);
                            $log.= "===>$card_and_mobile_payment_transaction_id<<<====!";
                            echo "pay_id=>$Patient_Payment_ID";
                            if ($card_and_mobile_payment_transaction_id == "55") {
                                echo "breaking_point";
                                break;
                            }
                            $sql_select_receipt_items_result = mysqli_query($conn, "SELECT Edited_Quantity,Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Sub_Department_ID,Clinic_ID,finance_department_id,clinic_location_id FROM tbl_item_list_cache WHERE card_and_mobile_payment_transaction_id='$Payment_Number'") or die(mysqli_error($conn));
                            $log.= "~~returned" . mysqli_num_rows($sql_select_receipt_items_result) . "~returnedrowse~";
                            if (mysqli_num_rows($sql_select_receipt_items_result) > 0) {
                                while ($receipt_item_rows = mysqli_fetch_assoc($sql_select_receipt_items_result)) {
                                    $Check_In_Type = $receipt_item_rows['Check_In_Type'];
                                    $Category = $receipt_item_rows['Category'];
                                    $Item_ID = $receipt_item_rows['Item_ID'];
                                    $Discount = $receipt_item_rows['Discount'];
                                    $Price = $receipt_item_rows['Price'];
                                    $Quantity = $receipt_item_rows['Quantity'];
                                    $Edited_Quantity = $receipt_item_rows['Edited_Quantity'];
                                    $Patient_Direction = $receipt_item_rows['Patient_Direction'];
                                    $Consultant = $receipt_item_rows['Consultant'];
                                    $Consultant_ID = $receipt_item_rows['Consultant_ID'];
                                    $Sub_Department_ID = $receipt_item_rows['Sub_Department_ID'];
                                    $Clinic_ID = $receipt_item_rows['Clinic_ID'];
                                    $finance_department_id = $receipt_item_rows['finance_department_id'];
                                    $clinic_location_id = $receipt_item_rows['clinic_location_id'];
                                    $log.= "===>4~~!ERROR";
                                    echo "Imefeli hapa 6";
                                    // if ($Edited_Quantity > 0) {
                                    //     $Quantity = $Edited_Quantity;
                                    // }
                                    if(!empty($finance_department_id) || $finance_department_id == 0){
                                        $finance_department_id = 2;
                                    }
                                    if(!empty($Clinic_ID) || $Clinic_ID == 0){
                                        $Clinic_ID = 2;
                                    }
                                    $sql_select_receipt_item_result = mysqli_query($conn, "INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Patient_Payment_ID,Sub_Department_ID,finance_department_id,clinic_location_id,Transaction_Date_And_Time) VALUES('$Check_In_Type','$Category','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Consultant_ID','$Clinic_ID','$Patient_Payment_ID','$Sub_Department_ID','$finance_department_id','$clinic_location_id',NOW())") or die("concultant=========================>>" . mysqli_error($conn));
                                    if (!$sql_select_receipt_item_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>5~~!ERROR";
                                        echo "Imefeli hapa 7";
                                    }
                                    $sql_update_transaction_result = mysqli_query($conn, "UPDATE tbl_card_and_mobile_payment_transaction SET transaction_status='completed' WHERE bill_payment_code='$Payment_Number'") or die(mysqli_error($conn));
                                    if (!$sql_update_transaction_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>8~~!ERROR";
                                        echo "Imefeli hapa 8";
                                    }
                                    $sql_update_paid_item_result = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Patient_Payment_ID='$Patient_Payment_ID',card_and_mobile_payment_status='completed',Status='paid' WHERE card_and_mobile_payment_transaction_id='$Payment_Number'") or die(mysqli_error($conn));
                                    if (!$sql_update_paid_item_result) {
                                        $an_error_occured = TRUE;
                                        $log.= "===>7~~!ERROR";
                                        echo "Imefeli hapa 9";
                                    }else{
                                        echo "TAYARI HUKUUUU";
                                    }
                                }
                            }
                        }
                    }
                
            }
        }
    }



        // $select_sugery = mysqli_query($conn, "SELECT consultation_ID, employee_ID, Consultation_Date_And_Time, maincomplain, firstsymptom_date, history_present_illness, review_of_other_systems, general_observation, systemic_observation, provisional_diagnosis, diferential_diagnosis, Comment_For_Laboratory, Comment_For_Radiology, Comments_For_Procedure, Comments_For_Surgery, investigation_comments, diagnosis, remarks, course_of_injuries  FROM tbl_consultation WHERE consultation_ID NOT IN(SELECT consultation_ID FROM tbl_consultation_history)");

        // $temppp = 1;
        //     if(mysqli_num_rows($select_sugery) > 0){
        //         while($surge = mysqli_fetch_array($select_sugery)){
        //             $consultation_ID = $surge['consultation_ID'];
        //             $maincomplain = $surge['maincomplain'];
        //             $firstsymptom_date = $surge['firstsymptom_date'];
        //             $history_present_illness = $surge['history_present_illness'];
        //             $review_of_other_systems = $surge['review_of_other_systems'];
        //             $general_observation = $surge['general_observation'];
        //             $systemic_observation = $surge['systemic_observation'];
        //             $provisional_diagnosis = $surge['provisional_diagnosis'];
        //             $diferential_diagnosis = $surge['diferential_diagnosis'];
        //             $Comment_For_Laboratory = $surge['Comment_For_Laboratory'];
        //             $Comment_For_Radiology = $surge['Comment_For_Radiology'];
        //             $Comments_For_Procedure = $surge['Comments_For_Procedure'];
        //             $Comments_For_Surgery = $surge['Comments_For_Surgery'];
        //             $investigation_comments = $surge['investigation_comments'];
        //             $remarks = $surge['remarks'];
        //             $course_of_injuries = $surge['course_of_injuries'];
        //             $diagnosis = $surge['diagnosis'];
        //             $employee_ID = $surge['employee_ID'];
        //             $Consultation_Date_And_Time = $surge['Consultation_Date_And_Time'];

        //             $select_ID = mysqli_query($conn, "SELECT Employee_ID FROM tbl_employee WHERE Employee_ID = '$employee_ID'");
        //             if(mysqli_num_rows($select_ID) > 0){
        //                 $employee_ID = $employee_ID;
        //             }else{
        //                 $employee_ID = 17;
        //             }

        //             $general_observation = str_replace("'", "&#39;", $general_observation);
        //             $history_present_illness = str_replace("'", "&#39;", $history_present_illness);
        //             $maincomplain = str_replace("'", "&#39;", $maincomplain);
        //             $remarks = str_replace("'", "&#39;", $remarks);
        //             $systemic_observation = str_replace("'", "&#39;", $systemic_observation);
        //             $firstsymptom_date = str_replace("'", "&#39;", $firstsymptom_date);
        //             $Comment_For_Laboratory = str_replace("'", "&#39;", $Comment_For_Laboratory);
        //             $Comment_For_Radiology = str_replace("'", "&#39;", $Comment_For_Radiology);
        //             $Comments_For_Procedure = str_replace("'", "&#39;", $Comments_For_Procedure);
        //             $Comments_For_Surgery = str_replace("'", "&#39;", $Comments_For_Surgery);
        //             $investigation_comments = str_replace("'", "&#39;", $investigation_comments);

        //             $INSERT_RECEIPT = mysqli_query($conn, "INSERT INTO tbl_consultation_history (consultation_ID, employee_ID, maincomplain, firstsymptom_date, history_present_illness, review_of_other_systems, general_observation, systemic_observation, provisional_diagnosis, diferential_diagnosis, Comment_For_Laboratory, Comment_For_Radiology, Comments_For_Procedure, Comments_For_Surgery, investigation_comments, diagnosis, remarks, course_of_injuries, cons_hist_Date, Saved, consultation_type) VALUES('$consultation_ID', '$employee_ID', '$maincomplain', '$firstsymptom_date', '$history_present_illness', '$review_of_other_systems', '$general_observation', '$systemic_observation', '$provisional_diagnosis', '$diferential_diagnosis', '$Comment_For_Laboratory', '$Comment_For_Radiology', '$Comments_For_Procedure', '$Comments_For_Surgery', '$investigation_comments', '$diagnosis', '$remarks', '$course_of_injuries', '$Consultation_Date_And_Time', 'yes', '$new_consultation')") or die(mysqli_error($conn));

        //             if($INSERT_RECEIPT){
        //                 $update_cache = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Patient_Payment_ID = '$Patient_Payment_ID' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
        //             }


        //             echo $temppp."=======>THIS DATA WAS INSERTED WITH ID".$consultation_ID;
        //             echo "<br>";
        //         $temppp++;

        //         }
        //     }else{
        //         echo "FAILED TO GET DATA";
        //     }
    


// $SELECT_lab = mysqli_query($conn, "SELECT Check_In_Type, Payment_Item_Cache_List_ID FROM `tbl_item_list_cache` WHERE `Status`='paid' AND `ServedBy` > 0 ") or die(mysqli_error($conn));
//     $temp =1;
//     while($datas = mysqli_fetch_array($SELECT_lab)){
//         // $Surgery_Date_Time = $datas['Surgery_Date_Time'];
//         $Payment_Item_Cache_List_ID = $datas['Payment_Item_Cache_List_ID'];
//         $Check_In_Type = $datas['Check_In_Type'];
    
//         if($Check_In_Type == 'Pharmacy'){
//             $Status = 'dispensed';
//         }elseif($Check_In_Type == 'Laboratory'){
//             $Status = 'Sample Collected';
//         }elseif($Check_In_Type == 'Surgery' || $Check_In_Type == 'Radiology' || $Check_In_Type == 'Procedure' || $Check_In_Type == 'Nuclearmedicine'){
//             $Status = 'served';
//         }else{
//             $Status = 'paid';
//         }
    
//         //UPDATE STATUS INTO SAMPLE COLLECTED

//         $update_file = mysqli_query($conn, "UPDATE tbl_item_list_cache SET `Status` = '$Status' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
//         if($update_file){
//             echo $temp."====> UPDATE tbl_item_list_cache SET `Status` = '$Status' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'";
//             echo "<br>";
//             $temp ++;
//         }
//     }



// $SELECT_lab = mysqli_query($conn, "SELECT ilc.Payment_Item_Cache_List_ID, ilc.Item_ID, p.name FROM tbl_item_list_cache ilc, product p WHERE p.Item_ID = ilc.Item_ID") or die(mysqli_error($conn));
//     $temp =1;
//     while($datas = mysqli_fetch_array($SELECT_lab)){
//         $Payment_Item_Cache_List_ID = $datas['Payment_Item_Cache_List_ID'];
//         $Item_ID = $datas['Item_ID'];
//         $Product_Name = $datas['name'];
    

//         if($Item_ID > 0){
//             $Item_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Item_ID FROM tbl_items WHERE Product_Name LIKE '%$Product_Name%'"))['Item_ID'];

//             if(mysqli_num_rows($Item_Name)>0){
//                 $update_file = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Item_ID = '$Item_Name' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");
//             }
//         }
//         if($update_file){
//             echo $temp."====> UPDATE tbl_item_list_cache SET Item_ID = '$Item_Name' WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'";
//             echo "<br>";
//         }else{
//             echo $temp."================> FAILED WITH NO ".$Payment_Item_Cache_List_ID;
//             echo "<br>";
//         }
//         $temp ++;

//     }
?>