<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_From = '';
    }
    
    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = '';
    }
    
    if(isset($_GET['Billing_Type'])){
        $Billing_Type_Value = $_GET['Billing_Type'];
    }else{
        $Billing_Type_Value = '';
    }
    
?>


        <?php
            if(isset($_SESSION['Pharmacy'])){
                $Sub_Department_Name = $_SESSION['Pharmacy'];
                $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
                while($row = mysqli_fetch_array($select_sub_department)){
                    $Sub_Department_ID = $row['Sub_Department_ID'];
                }
            }else{
                $Sub_Department_ID = '';
            }
        ?>
        <?php
            $total_cash = 0;
            $total_credit = 0;
            $total_cancelled = 0;
            $Grand_Total = 0;
            
            
            //get employee details (Cashiers)
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_item_list_cache ilc, tbl_employee emp where
                                            ilc.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ilc.Item_ID = ppl.Item_ID and
                                            pp.Receipt_Date between '$Date_From' and '$Date_To' and
                                            ilc.Check_In_Type = 'Pharmacy' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Outpatient'){
                                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_item_list_cache ilc, tbl_employee emp where
                                            ilc.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ilc.Item_ID = ppl.Item_ID and
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Receipt_Date between '$Date_From' and '$Date_To' and
                                            ilc.Check_In_Type = 'Pharmacy' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                                    $select_details = mysqli_query($conn,"select emp.Employee_ID, emp.Employee_Name from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_item_list_cache ilc, tbl_employee emp where
                                            ilc.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ilc.Item_ID = ppl.Item_ID and
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Receipt_Date between '$Date_From' and '$Date_To' and
                                            ilc.Check_In_Type = 'Pharmacy' group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
            
            
            
            $num = mysqli_num_rows($select_details);
            
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Employee_ID = $row['Employee_ID']; //cashier id
                    $Employee_Name = $row['Employee_Name']; //cashier name
                    
                    //filter all transactions based on selected cashier
                    if($Billing_Type_Value == 'All'){
                        $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                            from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_item_list_cache ilc where
                                            ilc.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ilc.Item_ID = ppl.Item_ID and
                                            ilc.Check_In_Type = 'Pharmacy' and
                                            pp.Receipt_Date between '$Date_From' and '$Date_To' and
                                            pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    }else if($Billing_Type_Value == 'Outpatient'){
                        $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                            from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_item_list_cache ilc where
                                            ilc.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ilc.Item_ID = ppl.Item_ID and
                                            ilc.Check_In_Type = 'Pharmacy' and
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Receipt_Date between '$Date_From' and '$Date_To' and
                                            pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    }else if($Billing_Type_Value == 'Inpatient'){
                        $select = mysqli_query($conn,"select pp.Billing_Type, pp.Transaction_status, ppl.Price, ppl.Quantity, ppl.Discount
                                            from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_item_list_cache ilc where
                                            ilc.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ilc.Item_ID = ppl.Item_ID and
                                            ilc.Check_In_Type = 'Pharmacy' and
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Receipt_Date between '$Date_From' and '$Date_To' and
                                            pp.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
                    }
                    $num_rows = mysqli_num_rows($select);
                    
                    
                    if($num_rows > 0){
                        while($data = mysqli_fetch_array($select)){
                            //calculate total
                            $Billing_Type = $data['Billing_Type'];
                            
                            $Transaction_status = $data['Transaction_status'];
                            $Price = $data['Price'];
                            $Quantity = $data['Quantity'];
                            $Discount = $data['Discount'];
                            
                            $total = (($Price - $Discount) * $Quantity);
                            
                            if(strtolower($Transaction_status) == 'cancelled'){
                                $total_cancelled = $total_cancelled + $total;
                            }else{
                                if(strtolower($Billing_Type) == 'outpatient cash' or strtolower($Billing_Type) == 'inpatient cash'){
                                    $total_cash = $total_cash + $total;
                                    $Grand_Total = $Grand_Total + $total;
                                }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit'){
                                    $total_credit = $total_credit + $total;
                                    $Grand_Total = $Grand_Total + $total;
                                }
                            }
                        }
                    }
                //$Grand_Total = $total_cash + $total_credit;
                $total_cash = 0;
                $total_credit = 0;
                $total_cancelled = 0;
                }
            }
        
        echo '<b>Grand Total </b>'.number_format($Grand_Total).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        ?>