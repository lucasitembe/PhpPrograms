<?php

session_start();

include("../includes/connection.php");
//include '../functions/database.php';
include("items.php");
$Employee = $_SESSION['userinfo']['Employee_ID'];
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'ViewItem') {
        $item = $_POST['item'];
        $sponsor = $_POST['Sponsor'];

        if (isset($_POST['Billing_Type'])) {
            $Billing_Type = $_POST['Billing_Type'];
        } else {
            $Billing_Type = '';
        }
        if ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
            $sponsor_ID = mysql_query("SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='CASH'");
            $result = mysql_fetch_assoc($sponsor_ID);
            $qr2 = "select Items_Price from tbl_item_price WHERE Item_ID='$item' AND Sponsor_ID='" . $result['Sponsor_ID'] . "'";
        } else {
            $qr2 = "select Items_Price from tbl_item_price WHERE Item_ID='$item' AND Sponsor_ID='$sponsor'";
        }
        $result_item = mysql_query($qr2);
        $row2 = mysql_fetch_assoc($result_item);

        if ($row2['Items_Price'] == 0) {
            $qr3 = "select Items_Price from tbl_general_item_price WHERE Item_ID='$item'";
            $result_item = mysql_query($qr3);
            $row3 = mysql_fetch_assoc($result_item);
            echo number_format($row3['Items_Price']);
        } else {
            echo number_format($row2['Items_Price']);
        }

//         Edit item starts here
    } elseif ($_POST['action'] == 'UpdateItem') {
        $item = $_POST['item'];
        $Old_ID=$_POST['Old_ID'];
        $Receipt_Date=$_POST['Receipt_Date'];
        $Price = mysql_real_escape_string($_POST['Price']);
        $Quantity = mysql_real_escape_string($_POST['Quantity']);
        $Discount = mysql_real_escape_string($_POST['Discount']);
        $Item_List_ID = mysql_real_escape_string($_POST['Item_List_ID']);
        $Billing_Type = $_POST['Billing_Type'];
        $Patient_Payment_ID = $_POST['Patient_Payment_ID'];
        $Old_Sponsor_ID = $_POST['Sponsor_ID'];
        $displaySponsor=$_POST['displaySponsor'];
        $guarantorName=$_POST['guarantorName'];
        if($Old_Sponsor_ID==$displaySponsor){
          $Sponsor_ID = $_POST['Sponsor_ID'];  
        }  else {
          $Sponsor_ID=$_POST['displaySponsor']; 
        }

        mysql_query("START TRANSACTION");
        $update_sponsor=  mysql_query("UPDATE tbl_patient_payments SET Sponsor_ID='$Sponsor_ID',Sponsor_Name='$guarantorName' WHERE Patient_Payment_ID='$Patient_Payment_ID'") or die(mysql_error());
        if($update_sponsor){
     
        $insert = mysql_query("INSERT INTO tbl_transaction_items_history 
              (Patient_Payment_Item_List_ID,Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,
              Status,Patient_Payment_ID,Transaction_Date_And_Time,Process_Status,Nursing_Status,Sub_Department_ID,Signedoff_Date_And_Time,Record_Status,Employee_Edited_ID,
              remarks,ServedDateTime,ServedBy,ItemOrigin,changed_By,changed_Reasons,changed_Date) 
              SELECT Patient_Payment_Item_List_ID,Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,
              Status,Patient_Payment_ID,Transaction_Date_And_Time,Process_Status,Nursing_Status,Sub_Department_ID,Signedoff_Date_And_Time,Record_Status,Employee_Edited_ID,
              remarks,ServedDateTime,ServedBy,ItemOrigin,changed_By,changed_Reasons,changed_Date FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Item_List_ID'");

        $last_ID = mysql_insert_id();
        $reg_ID = mysql_query("SELECT Registration_ID FROM tbl_patient_payments WHERE Patient_Payment_ID='$Patient_Payment_ID'");
        $get_reg_ID = mysql_fetch_assoc($reg_ID);

        if ($insert) {

            $update_last = mysql_query("UPDATE tbl_transaction_items_history SET changed_By='$Employee',changed_Date=NOW(),changed_Reasons='Edited item' WHERE transaction_histry_ID='$last_ID'");


            $Check_in_type = mysql_query("SELECT Check_In_Type,Patient_Payment_ID,Item_ID,Sub_Department_ID,Status,Quantity FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Item_List_ID'");
            $row3 = mysql_fetch_assoc($Check_in_type);
            
            $itemListCache = mysql_query("SELECT Check_In_Type,Patient_Payment_ID,Item_ID,Edited_Quantity,Sub_Department_ID,Status,Quantity FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Item_ID='$Old_ID'");
            $row4 = mysql_fetch_assoc($itemListCache);

            $update_Cache_tbl = mysql_query("UPDATE tbl_item_list_cache SET Item_ID='$item',Discount='$Discount',Quantity='$Quantity',Edited_Quantity='$Quantity',Price='$Price' WHERE Patient_Payment_ID='" . $row3['Patient_Payment_ID'] . "' AND Check_In_Type='" . $row3['Check_In_Type'] . "' AND Item_ID='" . $row3['Item_ID'] . "'");

            if ($update_Cache_tbl) {

                $update = mysql_query("UPDATE tbl_patient_payment_item_list SET Item_ID='$item',Discount='$Discount',Quantity='$Quantity',Price='$Price' WHERE Patient_Payment_Item_List_ID='$Item_List_ID'") or die(mysql_error());
                $reg_ID = mysql_query("SELECT Registration_ID FROM tbl_patient_payments WHERE Patient_Payment_ID='$Patient_Payment_ID'");
                $get_reg_ID = mysql_fetch_assoc($reg_ID);

                if ($update) {
                    if ($row4['Check_In_Type'] == 'Pharmacy' && $row4['Status'] == 'dispensed') {
                        if ($row4['Edited_Quantity'] > 0) {
                            $Qty = $row4['Edited_Quantity'];
                        } else {
                            $Qty = $row4['Quantity'];
                        }

                        if ($item == $row4['Item_ID']) {
                            $get_balance = mysql_query("SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID='$item' AND Sub_Department_ID='" . $row4['Sub_Department_ID'] . "'") or die(mysql_error());
                            $balance = mysql_fetch_assoc($get_balance);
                            $real_balance = $balance['Item_Balance'];
                            $final_Balance = $Qty - $Quantity;

                            $sub_dept_Id = $row4['Sub_Department_ID'];
                            $Document_Number = $row4['Patient_Payment_ID'];
                            $Registration_ID = $get_reg_ID['Registration_ID'];
                            $postbalance = $real_balance + $final_Balance;

                            $Quantity = (int) $Quantity - (int) $Qty;
                            if ($Quantity > 0) {
                                $status = Update_Item_Balance($item, $sub_dept_Id, 'Dispensed', null, null, $Registration_ID, $Document_Number, Get_Time_Now(), abs($Quantity), false);
                            } else {
                                $status = Update_Item_Balance($item, $sub_dept_Id, 'Dispensed', null, null, $Registration_ID, $Document_Number, Get_Time_Now(), abs($Quantity), true);
                            }

                          

                            if ($status) {
                                $Updatepp = mysql_query("UPDATE tbl_patient_payments SET Billing_Type='$Billing_Type' WHERE Patient_Payment_ID='$Patient_Payment_ID'");

                                if ($Updatepp) {

                                    if ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
                                        $sponsor_ID = mysql_query("SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='CASH'");
                                        $sponsor_result = mysql_fetch_assoc($sponsor_ID);

                                        $AllItems = mysql_query("SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID'");

                                        while ($Item_ID_row = mysql_fetch_assoc($AllItems)) {
                                            $qr2 = mysql_query("select Items_Price from tbl_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Sponsor_ID='" . $sponsor_result['Sponsor_ID'] . "'");
                                            $specific_price = mysql_fetch_assoc($qr2);
                                            $Item_Price = $specific_price['Items_Price'];
                                            if ($Item_Price == 0) {
                                                $qr3 = mysql_query("select Items_Price from tbl_general_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                                $specific_price1 = mysql_fetch_assoc($qr3);
                                                $Item_Price = $specific_price1['Items_Price'];
                                            }

                                            $update_payment_tbl = mysql_query("UPDATE tbl_patient_payment_item_list SET Price='$Item_Price' WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Patient_Payment_ID='$Patient_Payment_ID'");

                                            if ($update_payment_tbl) {
                                                $update_Cache_tbl2 = mysql_query("UPDATE tbl_item_list_cache SET Price='$Item_Price' WHERE Patient_Payment_ID='" . $Patient_Payment_ID . "' AND Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                                if ($update_Cache_tbl2) {
                                                    mysql_query("COMMIT");
                                                    $sms = 'Edited successfully';
                                                    
                                                } else {
                                                    mysql_query("ROLLBACK");
                                                    $sms = mysql_error();
                                                }
                                            } else {
                                                mysql_query("ROLLBACK");
                                                $sms = mysql_error();
                                            }
                                        }

                                        echo $sms;
                                    } else {

                                        $AllItems = mysql_query("SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID'");

                                        while ($Item_ID_row = mysql_fetch_assoc($AllItems)) {
                                            $qr2 = mysql_query("select Items_Price from tbl_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Sponsor_ID='$Sponsor_ID'");
                                            $specific_price = mysql_fetch_assoc($qr2);
                                            $Item_Price = $specific_price['Items_Price'];

                                            if ($Item_Price == 0) {
                                                $qr3 = mysql_query("select Items_Price from tbl_general_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                                $specific_price1 = mysql_fetch_assoc($qr3);
                                                $Item_Price = $specific_price1['Items_Price'];
                                            }

                                            $update_payment_tbl = mysql_query("UPDATE tbl_patient_payment_item_list SET Price='$Item_Price' WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Patient_Payment_ID='$Patient_Payment_ID'");

                                            if ($update_payment_tbl) {
                                                $update_Cache_tbl2 = mysql_query("UPDATE tbl_item_list_cache SET Price='$Item_Price' WHERE Patient_Payment_ID='" . $Patient_Payment_ID . "' AND Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                                if ($update_Cache_tbl2) {
                                                    mysql_query("COMMIT");
                                                    $sms = 'Edited successfully';
                                                } else {
                                                    mysql_query("ROLLBACK");
                                                    $sms = mysql_error();
                                                }
                                            } else {
                                                mysql_query("ROLLBACK");
                                                echo mysql_error();
                                            }
                                        }
                                        echo $sms;
                                    }
                                } else {
                                    mysql_query("ROLLBACK");
                                    echo mysql_error();
                                }
                            } else {
                                mysql_query("ROLLBACK");
                                echo mysql_error();
                            }
                            //}
                        } else {
                            // if you want to subsititute items start here
                            $Previous_Item_ID = $row4['Item_ID'];
                            $Previous_Quantity = $Qty;
                            $Previous_Item = Get_Item($Previous_Item_ID);
                            
                            $Current_Item_ID = $item;
                            $Current_Quantity = $Quantity;   
                            $Current_Item = Get_Item($Current_Item_ID);
                            
                            $get_balance = mysql_query("SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID='" . $row4['Item_ID'] . "' AND Sub_Department_ID='" . $row4['Sub_Department_ID'] . "'");
                            $balance = mysql_fetch_assoc($get_balance);
                            $real_balance = $balance['Item_Balance'];

                            $postbalance1 = $Qty + $real_balance;

                            $get_balance = mysql_query("SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID='$item' AND Sub_Department_ID='" . $row3['Sub_Department_ID'] . "'");
                            $balance = mysql_fetch_assoc($get_balance);
                            $real_balance = $balance['Item_Balance'];
                            $final_Balance = $real_balance - $Quantity;
                            $sub_dept_Id = $row4['Sub_Department_ID'];
                            $Document_Number = $row4['Patient_Payment_ID'];
                            $Registration_ID = $get_reg_ID['Registration_ID'];

                            if ($Previous_Item['Can_Be_Stocked'] == 'yes') {
                                $status_one = Update_Item_Balance($Previous_Item_ID, $sub_dept_Id, 'Dispensed', null, null, $Registration_ID, $Document_Number, $Receipt_Date, abs($Previous_Quantity), true);
                            }
                            
                            if ($Current_Item['Can_Be_Stocked'] == 'yes') {
                                $status_two = Update_Item_Balance($Current_Item_ID, $sub_dept_Id, 'Dispensed', null, null, $Registration_ID, $Document_Number, $Receipt_Date, abs($Current_Quantity), false);
                            }

                            if ($status_one && $status_two) {
                                $Updatepp = mysql_query("UPDATE tbl_patient_payments SET Billing_Type='$Billing_Type' WHERE Patient_Payment_ID='$Patient_Payment_ID'");
                                if ($Updatepp) {
                                    if ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
                                        $sponsor_ID = mysql_query("SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='CASH'");
                                        $sponsor_result = mysql_fetch_assoc($sponsor_ID);

                                        $AllItems = mysql_query("SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID'");

                                        while ($Item_ID_row = mysql_fetch_assoc($AllItems)) {
                                            $qr2 = mysql_query("select Items_Price from tbl_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Sponsor_ID='" . $sponsor_result['Sponsor_ID'] . "'");
                                            $specific_price = mysql_fetch_assoc($qr2);
                                            $Item_Price = $specific_price['Items_Price'];
                                            if ($Item_Price == 0) {
                                                $qr3 = mysql_query("select Items_Price from tbl_general_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                                $specific_price1 = mysql_fetch_assoc($qr3);
                                                $Item_Price = $specific_price1['Items_Price'];
                                            }

                                            $update_payment_tbl = mysql_query("UPDATE tbl_patient_payment_item_list SET Price='$Item_Price' WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Patient_Payment_ID='$Patient_Payment_ID'");

                                            if ($update_payment_tbl) {
                                                $update_Cache_tbl2 = mysql_query("UPDATE tbl_item_list_cache SET Price='$Item_Price' WHERE Patient_Payment_ID='" . $Patient_Payment_ID . "' AND Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                                if ($update_Cache_tbl2) {
                                                    mysql_query("COMMIT");
                                                    $sms = 'Edited successfully';
                                                } else {
                                                    mysql_query("ROLLBACK");
                                                    $sms = mysql_error();
                                                }
                                            } else {
                                                mysql_query("ROLLBACK");
                                                $sms = mysql_error();
                                            }
                                        }

                                        echo $sms;
                                    } else {

                                        $AllItems = mysql_query("SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID'");

                                        while ($Item_ID_row = mysql_fetch_assoc($AllItems)) {
                                            $qr2 = mysql_query("select Items_Price from tbl_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Sponsor_ID='$Sponsor_ID'");
                                            $specific_price = mysql_fetch_assoc($qr2);
                                            $Item_Price = $specific_price['Items_Price'];

                                            if ($Item_Price == 0) {
                                                $qr3 = mysql_query("select Items_Price from tbl_general_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                                $specific_price1 = mysql_fetch_assoc($qr3);
                                                $Item_Price = $specific_price1['Items_Price'];
                                            }

                                            $update_payment_tbl = mysql_query("UPDATE tbl_patient_payment_item_list SET Price='$Item_Price' WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Patient_Payment_ID='$Patient_Payment_ID'");

                                            if ($update_payment_tbl) {
                                                $update_Cache_tbl2 = mysql_query("UPDATE tbl_item_list_cache SET Price='$Item_Price' WHERE Patient_Payment_ID='" . $Patient_Payment_ID . "' AND Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                                if ($update_Cache_tbl2) {
                                                    mysql_query("COMMIT");
                                                    $sms = 'Edited successfully';
                                                } else {
                                                    mysql_query("ROLLBACK");
                                                    $sms = mysql_error();
                                                }
                                            } else {
                                                mysql_query("ROLLBACK");
                                                echo mysql_error();
                                            }
                                        }
                                        echo $sms;
                                    }
                                } else {
                                    
                                }
                            }
                            // }
                        }//Substitute  ends here bana
                    } else {
                        $Updatepp = mysql_query("UPDATE tbl_patient_payments SET Billing_Type='$Billing_Type' WHERE Patient_Payment_ID='$Patient_Payment_ID'");
                        if ($Updatepp) {


                            if ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
                                $sponsor_ID = mysql_query("SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='CASH'");
                                $sponsor_result = mysql_fetch_assoc($sponsor_ID);

                                $AllItems = mysql_query("SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID'");

                                while ($Item_ID_row = mysql_fetch_assoc($AllItems)) {
                                    $qr2 = mysql_query("select Items_Price from tbl_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Sponsor_ID='" . $sponsor_result['Sponsor_ID'] . "'");
                                    $specific_price = mysql_fetch_assoc($qr2);
                                    $Item_Price = $specific_price['Items_Price'];
                                    if ($Item_Price == '0') {
                                        $qr3 = mysql_query("select Items_Price from tbl_general_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                        $specific_price1 = mysql_fetch_assoc($qr3);
                                        $Item_Price = $specific_price1['Items_Price'];
                                    }

                                    $update_payment_tbl = mysql_query("UPDATE tbl_patient_payment_item_list SET Price='$Item_Price' WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Patient_Payment_ID='$Patient_Payment_ID'");

                                    if ($update_payment_tbl) {
                                        $update_Cache_tbl2 = mysql_query("UPDATE tbl_item_list_cache SET Price='$Item_Price' WHERE Patient_Payment_ID='" . $Patient_Payment_ID . "' AND Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                        if ($update_Cache_tbl2) {
                                            mysql_query("COMMIT");
                                            $sms = 'Edited successfully';
                                        } else {
                                            mysql_query("ROLLBACK");
                                            $sms = mysql_error();
                                        }
                                    } else {
                                        mysql_query("ROLLBACK");
                                        $sms = mysql_error();
                                    }
                                }

                                echo $sms;
                            } else {

                                $AllItems = mysql_query("SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID'");

                                while ($Item_ID_row = mysql_fetch_assoc($AllItems)) {
                                    $qr2 = mysql_query("select Items_Price from tbl_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Sponsor_ID='$Sponsor_ID'");
                                    $specific_price = mysql_fetch_assoc($qr2);
                                    $Item_Price = $specific_price['Items_Price'];

                                    if ($Item_Price == 0) {
                                        $qr3 = mysql_query("select Items_Price from tbl_general_item_price WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                        $specific_price1 = mysql_fetch_assoc($qr3);
                                        $Item_Price = $specific_price1['Items_Price'];
                                    }

                                    $update_payment_tbl = mysql_query("UPDATE tbl_patient_payment_item_list SET Price='$Item_Price' WHERE Item_ID='" . $Item_ID_row['Item_ID'] . "' AND Patient_Payment_ID='$Patient_Payment_ID'");

                                    if ($update_payment_tbl) {
                                        $update_Cache_tbl2 = mysql_query("UPDATE tbl_item_list_cache SET Price='$Item_Price' WHERE Patient_Payment_ID='" . $Patient_Payment_ID . "' AND Item_ID='" . $Item_ID_row['Item_ID'] . "'");
                                        if ($update_Cache_tbl2) {
                                           mysql_query("COMMIT");
                                            $sms = 'Edited successfully';
                                        } else {
                                            mysql_query("ROLLBACK");
                                            $sms = mysql_error();
                                        }
                                    } else {
                                        mysql_query("ROLLBACK");
                                        echo mysql_error();
                                    }
                                }
                                echo $sms;
                            }
                        } else {
                            mysql_query("ROLLBACK");
                            echo mysql_error();
                        }
                       
                    }
                } else {
                    mysql_query("ROLLBACK");
                    echo mysql_error();
                }
            } else {
                mysql_query("ROLLBACK");
                echo mysql_error();
            }
        } else {
            mysql_query("ROLLBACK");
            echo mysql_error();
        }

        }  else {
            mysql_query("ROLLBACK");
            echo mysql_error();
          
        }


//         Delete Items starts here
    } elseif ($_POST['action'] == 'DeleteItem') {
        $Patient_Payment_Item_List_ID = mysql_real_escape_string($_POST['Patient_Payment_Item_List_ID']);

        $insert = mysql_query("INSERT INTO tbl_transaction_items_history 
              (Patient_Payment_Item_List_ID,Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,
              Status,Patient_Payment_ID,Transaction_Date_And_Time,Process_Status,Nursing_Status,Sub_Department_ID,Signedoff_Date_And_Time,Record_Status,Employee_Edited_ID,
              remarks,ServedDateTime,ServedBy,ItemOrigin,changed_By,changed_Reasons,changed_Date) 
              SELECT Patient_Payment_Item_List_ID,Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,
              Status,Patient_Payment_ID,Transaction_Date_And_Time,Process_Status,Nursing_Status,Sub_Department_ID,Signedoff_Date_And_Time,Record_Status,Employee_Edited_ID,
              remarks,ServedDateTime,ServedBy,ItemOrigin,changed_By,changed_Reasons,changed_Date FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'");

        $last_ID = mysql_insert_id();



        if ($insert) {
            $update_last = mysql_query("UPDATE tbl_transaction_items_history SET changed_By='$Employee',changed_Date=NOW(),changed_Reasons='Removed item' WHERE transaction_histry_ID='$last_ID'");
            $select_Cache = mysql_query("SELECT Patient_Payment_ID,Item_ID,Check_In_Type FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'");

            $row2 = mysql_fetch_assoc($select_Cache);

            if ($select_Cache) {
                $reg_ID = mysql_query("SELECT Registration_ID FROM tbl_patient_payments WHERE Patient_Payment_ID='" . $row2['Patient_Payment_ID'] . "'");
                $get_reg_ID = mysql_fetch_assoc($reg_ID);

                $update_Cache = mysql_query("UPDATE tbl_item_list_cache SET Status='active' WHERE Patient_Payment_ID='" . $row2['Patient_Payment_ID'] . "' AND Item_ID='" . $row2['Item_ID'] . "' AND Check_In_Type='" . $row2['Check_In_Type'] . "'");


                $deleteTestresults = mysql_query("DELETE FROM tbl_specimen_results WHERE payment_item_ID='" . $row2['Patient_Payment_ID'] . "'");

                if ($update_Cache) {
                    $Item_list_cache = mysql_query("SELECT Check_In_Type,Item_ID,Patient_Payment_ID,Quantity,Status,Sub_Department_ID,Edited_Quantity FROM tbl_item_list_cache WHERE Patient_Payment_ID='" . $row2['Patient_Payment_ID'] . "' AND Item_ID='" . $row2['Item_ID'] . "' AND Check_In_Type='" . $row2['Check_In_Type'] . "'");
                    $result = mysql_fetch_assoc($Item_list_cache);
                    if ($Item_list_cache) {
                        if ($result['Edited_Quantity'] > 0) {
                            $Qty = $result['Edited_Quantity'];
                        } else {
                            $Qty = $result['Quantity'];
                        }

                        if ($result['Check_In_Type'] == 'Pharmacy' && $result['Status'] == 'dispensed') {

                            $get_balance = mysql_query("SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID='" . $result['Item_ID'] . "' AND Sub_Department_ID='" . $result['Sub_Department_ID'] . "'");
                            $balance = mysql_fetch_assoc($get_balance);
                            $real_balance = $balance['Item_Balance'];
                            $final_Balance = $Qty + $real_balance;
                            $sub_dept_Id = $result['Sub_Department_ID'];
                            $Document_Number = $result['Patient_Payment_ID'];
                            $Registration_ID = $get_reg_ID['Registration_ID'];
                            $item = $result['Item_ID'];


                            $Quantity = (int) $Quantity - (int) $Qty;


                            if ($Quantity > 0) {
                                $status = Update_Item_Balance($item, $sub_dept_Id, 'Dispensed', null, null, $Registration_ID, $Document_Number, Get_Time_Now(), abs($Quantity), false);
                            } else {
                                $status = Update_Item_Balance($item, $sub_dept_Id, 'Dispensed', null, null, $Registration_ID, $Document_Number, Get_Time_Now(), abs($Quantity), true);
                            }

                            if ($status) {
                                $Payment_Item_Cache_List_ID = mysql_query("SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID='" . $row2['Patient_Payment_ID'] . "' AND Item_ID='" . $row2['Item_ID'] . "' AND Check_In_Type='" . $row2['Check_In_Type'] . "'");
                                $row5 = mysql_fetch_assoc($Payment_Item_Cache_List_ID);
                                $Delete = mysql_query("DELETE FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'");

                                if ($Delete) {
                                    $deleteResult = mysql_query("DELETE FROM tbl_specimen_results WHERE payment_item_ID='" . $row5['Payment_Item_Cache_List_ID'] . "'");
                                    $deleteResult2 = mysql_query("DELETE FROM tbl_test_results WHERE payment_item_ID='" . $row5['Payment_Item_Cache_List_ID'] . "'");
                                    if ($deleteResult) {
                                        echo 'Item removed successfully';
                                    } else {
                                        echo 'Item removing failure';
                                    }
                                } else {
                                    echo 'Item removing failure';
                                }
                            }
                        } else {
                            $get_balance = mysql_query("SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID='" . $result['Item_ID'] . "' AND Sub_Department_ID='" . $result['Sub_Department_ID'] . "'");
                            $balance = mysql_fetch_assoc($get_balance);
                            $real_balance = $balance['Item_Balance'];
                            $final_Balance = $Qty + $real_balance;
                            $sub_dept_Id = $result['Sub_Department_ID'];
                            $Document_Number = $result['Patient_Payment_ID'];
                            $Registration_ID = $get_reg_ID['Registration_ID'];
                            $item = $result['Item_ID'];

                            $Quantity = (int) $Quantity - (int) $Qty;


                            if ($Quantity > 0) {
                                $status = Update_Item_Balance($item, $sub_dept_Id, 'Dispensed', null, null, $Registration_ID, $Document_Number, Get_Time_Now(), abs($Quantity), false);
                            } else {
                                $status = Update_Item_Balance($item, $sub_dept_Id, 'Dispensed', null, null, $Registration_ID, $Document_Number, Get_Time_Now(), abs($Quantity), true);
                            }

                            $Payment_Item_Cache_List_ID = mysql_query("SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Patient_Payment_ID='" . $row2['Patient_Payment_ID'] . "' AND Item_ID='" . $row2['Item_ID'] . "' AND Check_In_Type='" . $row2['Check_In_Type'] . "'");
                            $row5 = mysql_fetch_assoc($Payment_Item_Cache_List_ID);

                            $Delete = mysql_query("DELETE FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'");
                            if ($Delete) {
                                $deleteResult = mysql_query("DELETE FROM tbl_specimen_results WHERE payment_item_ID='" . $row5['Payment_Item_Cache_List_ID'] . "'");
                                $deleteResult2 = mysql_query("DELETE FROM tbl_test_results WHERE payment_item_ID='" . $row5['Payment_Item_Cache_List_ID'] . "'");
                                if ($deleteResult) {

                                    echo 'Item removed successfully';
                                } else {
                                    echo 'Item removing failure';
                                }
                            } else {
                                echo 'Item removing failure';
                            }
                        }
                    } else {
                        
                    }
                } else {
                    echo mysql_error();
                }
            } else {
                echo mysql_error();
            }
        } else {
            echo mysql_error();
        }
    } elseif ($_POST['action'] == 'AddNewItem') {
        $Check_In_Type = mysql_real_escape_string($_POST['Check_In_Type']);
        $Item_ID = mysql_real_escape_string($_POST['Item_ID']);
        $Discount = mysql_real_escape_string($_POST['Discount']);
        $Price = mysql_real_escape_string($_POST['Price']);
        $Quantity = mysql_real_escape_string($_POST['Quantity']);
        $Patient_Direction = mysql_real_escape_string($_POST['Patient_Direction']);
        $Consultant = mysql_real_escape_string($_POST['Consultant']);
        $Patient_Payment_ID = mysql_real_escape_string($_POST['Patient_Payment_ID']);
        $Query = mysql_query("INSERT INTO tbl_patient_payment_item_list (Check_In_Type,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Patient_Payment_ID,Transaction_Date_And_Time) VALUES ('$Check_In_Type','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Patient_Payment_ID',NOW())");
        if ($Query) {
            $last_ID = mysql_insert_id();

            $insert = mysql_query("INSERT INTO tbl_transaction_items_history 
              (Patient_Payment_Item_List_ID,Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,
              Status,Patient_Payment_ID,Transaction_Date_And_Time,Process_Status,Nursing_Status,Sub_Department_ID,Signedoff_Date_And_Time,Record_Status,Employee_Edited_ID,
              remarks,ServedDateTime,ServedBy,ItemOrigin,changed_By,changed_Reasons,changed_Date) 
              SELECT Patient_Payment_Item_List_ID,Check_In_Type,Category,Item_ID,Item_Name,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,
              Status,Patient_Payment_ID,Transaction_Date_And_Time,Process_Status,Nursing_Status,Sub_Department_ID,Signedoff_Date_And_Time,Record_Status,Employee_Edited_ID,
              remarks,ServedDateTime,ServedBy,ItemOrigin,changed_By,changed_Reasons,changed_Date FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$last_ID'");

            if ($insert) {
                $last_Added = mysql_insert_id();
                $update_last = mysql_query("UPDATE tbl_transaction_items_history SET changed_By='$Employee',changed_Date=NOW(),changed_Reasons='Added Item' WHERE transaction_histry_ID='$last_Added'");
                if ($update_last) {
                    echo 'Added successfully';
                } else {
                    echo 'Item adding failed';
                }
            }
        } else {
            echo mysql_error();
        }
    } elseif ($_POST['action'] == 'SaveDirectItem') {
        $Patient_Payment_ID = $_POST['Patient_Payment_ID'];
        $item_name = mysql_real_escape_string($_POST['item_name']);
        $Item_price = mysql_real_escape_string($_POST['Item_price']);
        $Item_discount = mysql_real_escape_string($_POST['Item_discount']);
        $Item_Quantity = mysql_real_escape_string($_POST['Item_Quantity']);

        $update = mysql_query("UPDATE tbl_patient_payment_item_list SET Discount='$Item_discount',Price='$Item_price',Quantity='$Item_Quantity',Item_Name='$item_name' WHERE Patient_Payment_Item_List_ID='$Patient_Payment_ID'");
        if ($update) {
            echo 'Updated successfully';
        } else {
            echo mysql_error();
        }
    }
}
?>
