<?php

include("./includes/connection.php");
$Control = 1;
$TransactionID = mysqli_real_escape_string($conn, $_POST['transactionID']);
$resultcheck = mysqli_query($conn, "SELECT BillAmount, AmountPaid,AuthorizationCode,TerminalID FROM billdetails WHERE  BillID= '$TransactionID'") or die(mysqli_error($conn));
$resultdata = mysqli_fetch_assoc($resultcheck);
$BillAmount = $resultdata['BillAmount'];
$AmountPaid = $resultdata['AmountPaid'];
if ((int) $resultdata['BillAmount'] == (int) $resultdata['AmountPaid']) {
    $AuthorizationCode = $resultdata['AuthorizationCode'];
    $terminal_id = $resultdata['TerminalID'];
    mysqli_query($conn, "UPDATE tbl_bank_transaction_cache SET Transaction_Status = 'Completed', Payment_Code ='$AuthorizationCode' WHERE Transaction_ID = '$TransactionID'") or die(mysqli_error($conn));
    $ql = "select tc.Transaction_Date_Time,tc.Employee_ID,tc.Registration_ID, eb.Branch_ID,tc.Payment_Code,tc.Transaction_ID from tbl_bank_transaction_cache tc
                                                    join tbl_branch_employee eb  ON tc.Employee_ID=eb.Employee_ID 
                                                    where Transaction_ID = '$TransactionID' and Transaction_Status = 'Completed'  order by Transaction_ID desc limit 1";
    $check_if_payment_code_exists = mysqli_query($conn, $ql) or die(mysqli_error($conn));

    if (mysqli_num_rows($check_if_payment_code_exists) > 0) {
        $Control = 1;
        $detals = mysqli_fetch_assoc($check_if_payment_code_exists);
        $Emp_ID = $detals['Employee_ID'];
        $Branch_ID = $detals['Branch_ID'];
        $R_ID = $detals['Registration_ID'];
        $Payment_Code = $detals['Payment_Code'];
        $Transaction_ID = $detals['Transaction_ID'];

        //get payments details
//        $dql = "SELECT Auth_No,Payment_Code,Transaction_Date FROM tbl_bank_api_payments_details WHERE Payment_Code = '$Payment_Code'";
//        echo $dql . ";=>";
//        $results_details = mysqli_query($conn, $dql) or die(mysqli_error($conn));
//        while ($obj_details = mysqli_fetch_assoc($results_details)) {
//            echo '====>in00000=>';
////            $obj_details = mysqli_fetch_assoc($results_details);
//            $P_Code = $obj_details['Payment_Code'];
//            $T_Date = $obj_details['Transaction_Date'];
//        }
        $P_Code = $Payment_Code;
        $T_Date = $detals['Transaction_Date_Time'];
        $E_Creator = $Emp_ID;
        $T_Ref = '';
        $Transaction_type = "Direct cash";

        //generate bill
        $slct = mysqli_query($conn, "select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$R_ID' AND Status='active' order by Patient_Bill_ID desc limit 1") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($slct);
        if ($nm > 0) {
            while ($dts = mysqli_fetch_array($slct)) {
                $Patient_Bill_ID = $dts['Patient_Bill_ID'];
            }
        } else {

            $insert_bill = mysqli_query($conn, "insert into tbl_patient_bill(Registration_ID) values('$R_ID')") or die(mysqli_error($conn));
            if ($insert_bill) {
                //get inserted Patient Bill Id
                $slct = mysqli_query($conn, "select Patient_Bill_ID from tbl_patient_bill where Registration_ID = '$R_ID' AND Status='active' order by Patient_Bill_ID limit 1") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slct);
                if ($nm > 0) {
                    while ($dts = mysqli_fetch_array($slct)) {
                        $Patient_Bill_ID = $dts['Patient_Bill_ID'];
                    }
                }
            }
        }

        //get check in id
        $select = mysqli_query($conn, "select Check_In_ID from tbl_check_in where Registration_ID = '$R_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
        $nums = mysqli_num_rows($select);
        if ($nums > 0) {
            while ($rows = mysqli_fetch_array($select)) {
                $Check_In_ID = $rows['Check_In_ID'];
            }
        } else {

            //generate check in id
            $ql1 = "INSERT INTO tbl_check_in(Registration_ID, Visit_Date, Employee_ID, 
                                                                            Check_In_Date_And_Time, Check_In_Status, Branch_ID, 
                                                                            Saved_Date_And_Time, Check_In_Date, Type_Of_Check_In, Folio_Status) 
                                                                    VALUES ('$R_ID',(select now()),'$Emp_ID',
                                                                                    (select now()),'saved','$Branch_ID',
                                                                                    (select now()),(select now()),'Afresh','generated')";
            $inserts = mysqli_query($conn, $ql1) or die(mysqli_error($conn));
            if ($inserts) {
                $select = mysqli_query($conn, "select Check_In_ID from tbl_check_in where Registration_ID = '$R_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
                while ($rows = mysqli_fetch_array($select)) {
                    $Check_In_ID = $rows['Check_In_ID']; //new check in id
                }
            }
        }


        $ql = "select Price, Discount, Quantity, Edited_Quantity "
                . "from tbl_item_list_cache where Transaction_ID = '$Transaction_ID' and (Status = 'active' or Status = 'approved') and Transaction_Type = 'Cash'";
        $get_total = mysqli_query($conn, $ql) or die(mysqli_error($conn));

        $num_p = mysqli_num_rows($get_total);
        if ($num_p > 0) {
            $Total_pending = 0;
            $Qty = 0;
            while ($th = mysqli_fetch_array($get_total)) {
                if ($th['Edited_Quantity'] > 0) {
                    $Qty = $th['Edited_Quantity'];
                } else {
                    $Qty = $th['Quantity'];
                }
                $Total_pending += (($th['Price'] - $th['Discount']) * $Qty);
            }

            $get_items = mysqli_query($conn, "select p.Registration_ID, p.Folio_Number, p.Sponsor_ID, p.Sponsor_Name, p.Billing_Type, ilc.Price, ilc.Discount, ilc.Edited_Quantity, ilc.Quantity, ilc.Item_ID,
                                ilc.Check_In_Type,ilc.finance_department_id,ilc.clinic_location_id,ilc.Clinic_ID, ilc.Consultant, ilc.Consultant_ID, ilc.Payment_Item_Cache_List_ID from 
                                tbl_payment_cache p, tbl_item_list_cache ilc where
                                ilc.Payment_Cache_ID = p.Payment_Cache_ID and
                                ilc.Transaction_ID = '$Transaction_ID' and
                                (ilc.Status = 'active' or ilc.Status = 'approved') and
                                ilc.Transaction_Type = 'Cash'") or die(mysqli_error($conn));
            $numz = mysqli_num_rows($get_items);

            if ($numz > 0) {

                while ($row = mysqli_fetch_array($get_items)) {
                    $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
                    //get quantity
                    if ($row['Edited_Quantity'] > 0) {
                        $Qty = $row['Edited_Quantity'];
                    } else {
                        $Qty = $row['Quantity'];
                    }
                    $Billing_Type = $row['Billing_Type'];
                    if ($Billing_Type == "Inpatient Cash" || $Billing_Type == "Inpatient Credit") {
                        $Billing_Type = "Inpatient Cash";
                    } else {
                        $Billing_Type = "Outpatient Cash";
                    }

                    if ($Control == 1) {
                        $sql = "INSERT INTO tbl_patient_payments(Registration_ID, Supervisor_ID, Employee_ID,Payment_Date_And_Time, Folio_Number,
                            Sponsor_ID, Billing_Type,Receipt_Date, branch_id,Check_In_ID,payment_mode,Payment_Code,auth_code,terminal_id,Patient_Bill_ID,Transaction_Ref,Transaction_Date,ePayment_Invoice_Creator,Transaction_type,payment_type )
                            VALUES ('$R_ID','$Emp_ID','$Emp_ID',(select now()),'" . $row['Folio_Number'] . "','" . $row['Sponsor_ID'] . "','$Billing_Type',(select now()),'
                            " . $Branch_ID . "','" . $Check_In_ID . "','CRDB','$Payment_Code','$Payment_Code','$terminal_id','$Patient_Bill_ID','$T_Ref','$T_Date','$E_Creator','$Transaction_type','pre')";

                        //$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        $sql_check_if_receipt_exist_result = mysqli_query($conn, "SELECT Payment_Code FROM tbl_patient_payments WHERE Payment_Code='$P_Code' AND Registration_ID='$R_ID'") or die(mysqli_error($conn));
                        if (mysqli_num_rows($sql_check_if_receipt_exist_result) <= 0) {
                            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                        }

                        if ($result) {
                            //get receipt number
                            $dkt = "select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where 
                                                Registration_ID='$R_ID' AND Payment_Code='$P_Code' AND
                                                Employee_ID = '$Emp_ID'";
                            $get_receipt = mysqli_query($conn, $dkt) or die(mysqli_error($conn));
                            if ($get_receipt) {
                                while ($ddt = mysqli_fetch_array($get_receipt)) {
                                    $Patient_Payment_ID = $ddt['Patient_Payment_ID'];
                                    $Payment_Date_And_Time = $ddt['Payment_Date_And_Time'];
                                }
                            } else {
                                $Patient_Payment_ID = 0;
                                $Payment_Date_And_Time = '';
                            }
                           echo '=====>in1';


                            $Clinic_ID = $row['Clinic_ID'];
                            $finance_department_id = $row['finance_department_id'];
                            $clinic_location_id = $row['clinic_location_id'];
                            $insert_items = "INSERT INTO tbl_patient_payment_item_list(
                                                                            Check_In_Type, Item_ID, Discount, Price, 
                                                                            Quantity, Patient_Direction, Consultant, Consultant_ID, 
                                                                            Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id,clinic_location_id) 
                                                                                    values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
                                                                                            ,'" . $Qty . "','others','" . $row['Consultant'] . "','" . $row['Consultant_ID'] . "'
                                                                                            ,'" . $Patient_Payment_ID . "',(select now()),'$Clinic_ID','$finance_department_id','$clinic_location_id')";


                            //AOID ITEMS PAYMENT DUBLICATION 
                            $Item_ID = $row['Item_ID'];
                            $quantity_to_check = $row['Quantity'];
                            $qld = "SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID' "
                                    . "AND Item_ID='$Item_ID' AND Quantity='$quantity_to_check'";
//                                    echo $qld;
                            $sql_select_payment_id = mysqli_query($conn, $qld) or die(mysqli_error($conn));
                            if (mysqli_num_rows($sql_select_payment_id) <= 0) {
                                $result2 = mysqli_query($conn, $insert_items) or die(mysqli_error($conn));
                               echo '=======>in2';
                            }

                            if ($result2) {
                                $sql2 = "update tbl_item_list_cache set Patient_Payment_ID = '$Patient_Payment_ID', 
                                                                                         Payment_Date_And_Time = '$Payment_Date_And_Time', Status = 'paid', ePayment_Status = 'Served' where 
                                                                                         Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'";
                                $upg = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
                                if ($upg) {
                                   echo '=============>in3';
                                }
                            }
                            $Control++;
                        }
                    } else {

                        $Clinic_ID = $row['Clinic_ID'];
                        $finance_department_id = $row['finance_department_id'];
                        $clinic_location_id = $row['clinic_location_id'];
                        $insert_items = "INSERT INTO tbl_patient_payment_item_list(
                                    Check_In_Type, Item_ID, Discount, Price,Quantity, Patient_Direction, Consultant, Consultant_ID, 
                                    Patient_Payment_ID, Transaction_Date_And_Time,Clinic_ID,finance_department_id,clinic_location_id) 
                                    values('" . $row['Check_In_Type'] . "','" . $row['Item_ID'] . "','" . $row['Discount'] . "','" . $row['Price'] . "'
                                    ,'" . $Qty . "','others','" . $row['Consultant'] . "','" . $row['Consultant_ID'] . "'
                                    ,'" . $Patient_Payment_ID . "',(select now()),'$Clinic_ID','$finance_department_id','$clinic_location_id')";

                        //AOID ITEMS PAYMENT DUBLICATION 
                        $Item_ID = $row['Item_ID'];
                        $quantity_to_check = $row['Quantity'];
                        $sql_select_payment_id = mysqli_query($conn, "SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Item_ID' AND Quantity='$quantity_to_check'") or die(mysqli_error($conn));
                        if (mysqli_num_rows($sql_select_payment_id) <= 0) {
                            $result2 = mysqli_query($conn, $insert_items) or die(mysqli_error($conn));
                            echo '=============>in4';
                        }
                        if ($result2) {
                            $sql4 = "update tbl_item_list_cache set Patient_Payment_ID = '$Patient_Payment_ID', Payment_Date_And_Time = '$Payment_Date_And_Time', Status = 'paid', ePayment_Status = 'Served' where "
                                    . "Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'";
                            $fn = mysqli_query($conn, $sql4) or die(mysqli_error($conn));
                            if ($fn) {
                               echo '=============>in5';
                            }
                        }
                        $Control++;
                    }
                }
                if ($Control > 1) {
                    mysqli_query($conn, "UPDATE tbl_bank_api_payments_details SET Payment_Receipt = '$Patient_Payment_ID' WHERE Payment_Code = '$Payment_Code'") or die(mysqli_error($conn));
                    echo $Payment_Code;
                } else {
                    echo "fail-1";
                }
            }
        } else {
            echo "fail-2";
        }
    } else {
        echo "fail-3";
    }
} else {
    echo 'fail-4';
}