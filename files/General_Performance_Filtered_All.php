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

    if(isset($_GET['transaction_channel'])){
        $transaction_channel = $_GET['transaction_channel'];
    }else{
        $transaction_channel = '';
    }
    
    if(isset($_GET['Billing_Type'])){
        $Billing_Type_Value = $_GET['Billing_Type'];
    }else{
        $Billing_Type_Value = '';
    }
    
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
        $Employee_ID_value = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
        $Employee_ID_value = 'All';
    }

    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
        $Sponsor_ID_value = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = '';
        $Sponsor_ID_value = 'All';
    }

?>

<center>
    <table width=100% border=1>
        <?php
            $temp = 1;
            $total_cash = 0;
            $total_credit = 0;
            $total_msamaha = 0;
            $total_cancelled = 0;
            $grand_total_cash = 0;
            $grand_total_credit = 0;
            $grand_total_msamaha = 0;
            $grand_total_cancelled = 0;
            $general_grand_total = 0;

            echo "<tr id='thead'>
			    <td width=5%><b>SN</b></td>
			    <td><b>EMPLOYEE NAME</b></td>
			    <td><b>PHONE NUMBER</b></td>
			    <td style='text-align: right;'><b>CASH</b></td>
			    <td style='text-align: right;'><b>CREDIT</b></td>
			    <td style='text-align: right;'><b>CANCELLED</b></td>
			    <td style='text-align: right;'><b>TOTAL COLLECTED</b></td>
			</tr>";
        echo '<tr><td colspan="8"><hr></td></tr>';
        
        if($transaction_channel != "All"){
            if($transaction_channel == 'crdb_pos'){
                $transaction_channel_filter =" AND pp.auth_code NOT LIKE '77100%' AND pp.auth_code NOT LIKE 'FH%' AND pp.auth_code NOT LIKE 'BUH100%' AND  pp.auth_code NOT LIKE 'EC%'";
            }else if($transaction_channel == 'to_crdb'){
                $transaction_channel_filter = " AND (pp.auth_code LIKE 'BUH100%' OR pp.auth_code LIKE 'FH%') ";
            }else if($transaction_channel == 'to_nmb'){
                $transaction_channel_filter = " AND  (pp.auth_code LIKE '77100%' OR pp.auth_code LIKE 'EC%') ";
            }else if($transaction_channel == 'manual'){
                $transaction_channel_filter = " AND pp.manual_offline = 'manual' ";
            }
        }else{
            $transaction_channel_filter="";
        }

        // echo $transaction_channel_filter;
        // exit();
            




    //get employee details (Cashiers)
    if($Sponsor_ID == 0){
        if($Employee_ID == 0){    
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Outpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code ,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }else{
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Outpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            emp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }
            
    }else{
        if($Employee_ID == 0){    
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pp.Sponsor_ID = '$Sponsor_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Outpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pp.Sponsor_ID = '$Sponsor_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pp.Sponsor_ID = '$Sponsor_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }else{
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pp.Sponsor_ID = '$Sponsor_ID' and
                                            emp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Outpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pp.Sponsor_ID = '$Sponsor_ID' and
                                            emp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pp.Sponsor_ID = '$Sponsor_ID' and
                                            emp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }

    }        
            $num = mysqli_num_rows($select_details);
            
            if($num > 0){
                while($row = mysqli_fetch_array($select_details)){
                    $Employee_ID = $row['Employee_ID']; //cashier id
                    $Employee_Name = ucwords(strtolower($row['Employee_Name'])); //cashier name
                    $Phone_Number = ucwords(strtolower($row['Phone_Number'])); //cashier name
                    
                    //filter all transactions based on selected cashier
                    if($Sponsor_ID == 0){
                        if($Billing_Type_Value == 'All'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Outpatient'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Inpatient'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }
                    }else{
                        if($Billing_Type_Value == 'All'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Outpatient'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Inpatient'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }
                    }
                    // die($select);
                    $num_rows = mysqli_num_rows($select);
                    
                    if($num_rows > 0){
                        $total=0;
                        while($data = mysqli_fetch_array($select)){
                            //calculate total
                            $Billing_Type = $data['Billing_Type'];
                            $payment_type = $data['payment_type'];
                            $Payment_Code = $data['Payment_Code'];
                            $auth_code = $data['auth_code'];
                            $manual_offline = $data['manual_offline'];
                            
                            $Transaction_status = $data['Transaction_status'];
                            $Pre_Paid = $data['Pre_Paid'];
                            //$Price = $data['Price'];
                            //$Quantity = $data['Quantity'];
                            //$Discount = $data['Discount'];
                            
                           $total = $data['Amount']; //(($Price - $Discount) * $Quantity);
                            
                            if(strtolower($Transaction_status) == 'cancelled'){
                                $total_cancelled = $total_cancelled + $total;
                            }else{
                            // if((strtolower($Billing_Type) == 'outpatient cash'&& $Pre_Paid == '0') || (strtolower($Billing_Type) =="patient from outside" && $Pre_Paid='0')||(strtolower($Billing_Type) == 'inpatient cash' && $payment_type == 'pre')){
                                if(((strtolower($Billing_Type) == 'outpatient cash'&& $Pre_Paid==0) ||(strtolower($Billing_Type) =="patient from outside"  && $data['Pre_Paid'] == '0') || (strtolower($Billing_Type) == 'inpatient cash' && $payment_type == 'pre')) && strtolower($Transaction_status) != 'cancelled'  && ($auth_code != '' || $data['manual_offline'] = 'manual' || ($data['Payment_Code'] != '' && ($data['payment_mode']== 'CRDB' || $data['payment_mode']== 'CRDB..' )))){
                                    $total_cash = $total_cash + $total;
                                }
                                // else if($data['Exemption']!='yes' && (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit' || ((strtolower(($Billing_Type) == 'outpatient cash') ||(strtolower($Billing_Type) == 'inpatient cash')) && $payment_type == 'post')) && $Transaction_status == 'active'){
                                elseif(((strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '1') || (strtolower($data['Billing_Type']) == 'outpatient credit') || (strtolower($data['Billing_Type']) == 'inpatient credit') || (strtolower($data['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')) && (strtolower($data['Transaction_status']) == 'active')){
                                    $total_credit = $total_credit + $total;
                                }
                                
                                 if(($data['Exemption']=='yes') && (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit' || (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post'))){
                                    $total_msamaha=$total_msamaha+$total;
                                     
                                 }
                                 
                            }
                        }
                    }
        ?>
                <!--<tr>
                    <td><?php echo $temp; ?></td>
                    <td><b><?php echo $Employee_Name; ?></b></td>
                    <td style='text-align: right;'><b><?php //echo number_format($total_cash); ?></b></td>
                    <td style='text-align: right;'><b><?php //echo number_format($total_credit); ?></b></td>
                    <td style='text-align: right;'><b><?php //echo number_format($total_cancelled); ?></b></td>
                    <td style='text-align: right;'><b><?php //echo number_format($total_cash + $total_credit); ?></b></td>
                </tr>-->
                <tr>
                    <td><?php echo $temp; ?></td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Employee_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Billing_Type_Value; ?>','<?php echo $Sponsor_ID; ?>')">
                            <b><?php echo $Employee_Name; ?></b>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Employee_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Billing_Type_Value; ?>','<?php echo $Sponsor_ID; ?>')">
                            <b><?php echo $Phone_Number; ?></b>
                        </label>
                    </td>
                    <td style='text-align: right;'>
                        <label onclick="open_Dialog('<?php echo $Employee_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Billing_Type_Value; ?>','<?php echo $Sponsor_ID; ?>')">
                            <b><?php echo number_format($total_cash); ?></b>
                        </label>
                    </td>
                    <td style='text-align: right;'>
                        <label onclick="open_Dialog('<?php echo $Employee_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Billing_Type_Value; ?>','<?php echo $Sponsor_ID; ?>')">
                            <b><?php echo number_format($total_credit); ?></b>
                        </label>
                    </td>
                    <!-- <td style='text-align: right;'>
                        <label onclick="open_Dialog('< ?php echo $Employee_ID; ?>','< ?php echo $Date_From; ?>','< ?php echo $Date_To; ?>','< ?php echo $Billing_Type_Value; ?>','< ?php echo $Sponsor_ID; ?>')">
                            <b>< ?php echo number_format($total_msamaha); ?></b>
                        </label>
                    </td> -->
                    <td style='text-align: right;'>
                        <label onclick="open_Dialog('<?php echo $Employee_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Billing_Type_Value; ?>','<?php echo $Sponsor_ID; ?>')">
                            <b><?php echo number_format($total_cancelled); ?></b>
                        </label>
                    </td>
                    <td style='text-align: right;'>
                        <label onclick="open_Dialog('<?php echo $Employee_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Billing_Type_Value; ?>','<?php echo $Sponsor_ID; ?>')">
                            <b><?php echo number_format($total_cash + $total_credit+$total_msamaha); ?></b>
                        </label>
                    </td>
                </tr>
            <?php
                    $grand_total_cash = $grand_total_cash + $total_cash;
                    $grand_total_credit = $grand_total_credit + $total_credit;
                    $grand_total_msamaha = $grand_total_msamaha + $total_msamaha;
                    $grand_total_cancelled = $grand_total_cancelled + $total_cancelled;
                    // $general_grand_total = $general_grand_total + ($total_cash + $total_credit+$total_msamaha);
                    $general_grand_total = $general_grand_total + ($total_cash + $total_credit);

                    $temp++;
                    $total_cash = 0;
                    $total_msamaha=0;
                    $total_credit = 0;
                    $total_cancelled = 0;
                }
            }
            echo "<tr><td colspan='8'><hr></td></tr>";
            echo "<tr><td colspan='3' style='text-align: left;'>
                            <b>GRAND TOTAL</b>
                        </td>
                        <td style='text-align: right;'><b>".number_format($grand_total_cash)."</b></td>
                        <td style='text-align: right;'><b>".number_format($grand_total_credit)."</b></td>
                        <td style='text-align: right;'><b>".number_format($grand_total_cancelled)."</b></td>
                        <td style='text-align: right;'>
                            <b>".number_format($general_grand_total)."</b>
                        </td>
                    </tr>";
            echo '</table>';
            echo '<a href="generalperformanceallreport.php?Date_From='.$Date_From.'&Date_To='.$Date_To.'&Billing_Type='.$Billing_Type_Value.'&Employee_ID='.$Employee_ID_value.'&Sponsor_ID='.$Sponsor_ID_value.'&transaction_channel='.$transaction_channel.'" class="art-button-green" target="_blank">PREVIEW REPORT</a>';
            echo '<a href="generalperformanceallreport_excell.php?Date_From='.$Date_From.'&Date_To='.$Date_To.'&Billing_Type='.$Billing_Type_Value.'&Employee_ID='.$Employee_ID_value.'&Sponsor_ID='.$Sponsor_ID_value.'&transaction_channel='.$transaction_channel.'" class="art-button-green" target="_blank">EXCEL PREVIEW</a>';
        ?>
    </td>
</tr>
    </table>
</center>