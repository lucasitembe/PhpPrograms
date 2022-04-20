<?php
    @session_start();
    include("./includes/connection.php");
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }

    $temp = 1;
    $total_cash = 0;
    $total_credit = 0;
    $total_cancelled = 0;
    $Grand_Total = 0;
    $Grand_Total_Cancelled = 0;
	$transaction_channel_filter = "";
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['General_Ledger'])){
    	    if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
    		  header("Location: ./index.php?InvalidPrivilege=yes");
    	    }
    	}else{
    	    header("Location: ./index.php?InvalidPrivilege=yes");
    	}
    }else{
        @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
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
        $Billing_Type_Value = 'All'; 
    }
    
    if (isset($_GET['Sponsor_ID'])) {
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }
	
	 if(isset($_GET['transaction_channel'])){
        $transaction_channel = $_GET['transaction_channel'];
    }
	
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
    //get sponsor name
    if($Sponsor_ID != 0){
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
                $Guarantor_Name = $data['Guarantor_Name'];
            }
        }else{
            $Guarantor_Name = 'All';
        }
    }else{
        $Guarantor_Name = 'All';
    }
    //select printing date and time
    $select_Time_and_date = mysqli_query($conn,"select now() as datetime");
    while($row = mysqli_fetch_array($select_Time_and_date)){
	   $Date_Time = $row['datetime'];
    } 

    $htm = "<table align='center' width='100%'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
		    <tr><td style='text-align: center;'><b>CASH AND CREDIT REPORT- ALL PAYMENT</b></td></tr>

            <tr><td style='text-align:center'><b>START DATE : $Date_From</b></td></tr>
                <tr><td style='text-align:center'><b>END DATE : $Date_To</b></td></tr>
                <tr><td style='text-align:center'><b>SPONSOR : $Guarantor_Name</b></td></tr>
                <tr><td style='text-align:center'><b>BILLING TYPE: $Billing_Type_Value</b></td></tr>
		    </table><br/>";

    //get employee details
    $select_details = mysqli_query($conn,"SELECT * from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_details);
    if($num > 0){
    	while($row2 = mysqli_fetch_array($select_details)){
    	    $Employee_Name = $row2['Employee_Name'];
    	}
    }

?> 
<?php
    $htm .= "<table width=100% border=1 style='border-collapse: collapse;'>
            <tr id='thead'>
                    <td width=5%><b>SN</b></td>
                    <td><b>EMPLOYEE NAME</b></td>
                    <td><b>PHONE NUMBER</b></td>
                    <td style='text-align: right;'><b>CASH</b></td>
                    <td style='text-align: right;'><b>CREDIT</b></td>
                    <td style='text-align: right;'><b>CANCELLED</b></td>
                    <td style='text-align: right;'><b>TOTAL COLLECTED</b></td>
                </tr>";
            $htm .= '<tr><td colspan="7"><hr></td></tr>';
	
// $Grand_Total_Cash=0;
// $Grand_Total_Credit=0;
// 

    
    
    //sql statement
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
                
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type ,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }else if($Billing_Type_Value == 'Inpatient'){
                                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
                                            pp.Employee_ID = emp.Employee_ID and
                                            pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                            ts.Sponsor_ID=pp.Sponsor_ID and 
                                            (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' $transaction_channel_filter group by emp.Employee_ID order by emp.Employee_Name") or die(mysqli_error($conn));
            }
        }else{
            if($Billing_Type_Value == 'All'){
                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
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
                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
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
                    $select_details = mysqli_query($conn,"SELECT emp.Phone_Number,emp.Employee_ID,ts.Exemption, emp.Employee_Name, pp.payment_type ,pp.Payment_Code,pp.manual_offline,pp.payment_mode from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_employee emp,tbl_sponsor as ts where
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
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount ,pp.Payment_Code,pp.manual_offline,pp.payment_mode
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Outpatient'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline,pp.payment_mode
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Inpatient'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline,pp.payment_mode
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }
                    }else{
                        if($Billing_Type_Value == 'All'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline,pp.payment_mode
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Outpatient'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline,pp.payment_mode
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }else if($Billing_Type_Value == 'Inpatient'){
                            $select = mysqli_query($conn,"SELECT pp.Billing_Type, pp.auth_code, pp.Transaction_status,pp.Pre_Paid,ts.Exemption, pp.payment_type, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount,pp.Payment_Code,pp.manual_offline,pp.payment_mode
                                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp,tbl_sponsor as ts where
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                ts.Sponsor_ID=pp.Sponsor_ID and 
                                                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pp.Sponsor_ID = '$Sponsor_ID' and
                                                pp.Employee_ID = '$Employee_ID' $transaction_channel_filter group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }
                    }
                    
                    $num_rows = mysqli_num_rows($select);
                    
                    if($num_rows > 0){
                        $total=0;
                        while($data = mysqli_fetch_array($select)){
                            //calculate total
                            $Billing_Type = $data['Billing_Type'];
                            $payment_type = $data['payment_type'];
                            $auth_code = $data['auth_code'];
                            $manual_offline = $data['manual_offline'];
                            
                            $Transaction_status = $data['Transaction_status'];
                            $Pre_Paid = $data['Pre_Paid'];
                            $Payment_Code = $data['Payment_Code'];
                            //$Price = $data['Price'];
                            //$Quantity = $data['Quantity'];
                            //$Discount = $data['Discount'];
                            
                           $total = $data['Amount']; //(($Price - $Discount) * $Quantity);
                            
                            if(strtolower($Transaction_status) == 'cancelled'){
                                $total_cancelled = $total_cancelled + $total;
                            }else{
                            // if((strtolower($Billing_Type) == 'outpatient cash'&& $payment_type == 'pre') || strtolower($Billing_Type) =="patient from outside"||(strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre' && (!empty($auth_code)))){
                                // if(((strtolower($Billing_Type) == 'outpatient cash'&& $Pre_Paid==0) ||(strtolower($Billing_Type) =="patient from outside"  && $data['Pre_Paid'] == '0') || (strtolower($Billing_Type) == 'inpatient cash' && $payment_type == 'pre'))  && strtolower($Transaction_status) != 'cancelled'  && ($auth_code != '' || $Payment_Code != '')){
                                    if(((strtolower($Billing_Type) == 'outpatient cash'&& $Pre_Paid==0) ||(strtolower($Billing_Type) =="patient from outside"  && $data['Pre_Paid'] == '0') || (strtolower($Billing_Type) == 'inpatient cash' && $payment_type == 'pre')) && strtolower($Transaction_status) != 'cancelled'  && ($auth_code != '' || $data['manual_offline'] = 'manual' || ($data['Payment_Code'] != '' && ($data['payment_mode']== 'CRDB' || $data['payment_mode']== 'CRDB..' )))){
                                    $total_cash = $total_cash + $total;
                                }
                                // else if($data['Exemption']!='yes' && (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit' || ((strtolower(($Billing_Type) == 'outpatient cash') ||(strtolower($Billing_Type) == 'inpatient cash')) && $payment_type == 'post')) && $Transaction_status == 'active'){
                                elseif(((strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '1') || (strtolower($data['Billing_Type']) == 'outpatient credit') || (strtolower($data['Billing_Type']) == 'inpatient credit') || (strtolower($data['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')) && (strtolower($data['Transaction_status']) == 'active')){
                                    $total_credit = $total_credit + $total;
                                }
                                
                                //  if(($data['Exemption']=='yes') && (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit' || (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post'))){
                                //     $total_msamaha=$total_msamaha+$total;
                                     
                                //  }
                                 
                            }
                        }
                    }

              $htm .= "<tr>
                    <td>$temp</td>
                    <td>
                            $Employee_Name
                    </td>
                    <td>
                            $Phone_Number
                    </td>
                    <td style='text-align: right;'>
                            ".number_format($total_cash)."
                    </td>
                    <td style='text-align: right;'>
                            ".number_format($total_credit)."
                    </td>
                    <td style='text-align: right;'>
                            ".number_format($total_cancelled)."
                    </td>
                    <td style='text-align: right;'>
                            ".number_format($total_cash + $total_credit+$total_msamaha)."
                    </td>
                </tr>";

                    $grand_total_cash = $grand_total_cash + $total_cash;
                    $grand_total_credit = $grand_total_credit + $total_credit;
                    $grand_total_msamaha = $grand_total_msamaha + $total_msamaha;
                    $grand_total_cancelled = $grand_total_cancelled + $total_cancelled;
                    $general_grand_total = $general_grand_total + ($total_cash + $total_credit+$total_msamaha);

                    $temp++;
                    $total_cash = 0;
                    $total_msamaha=0;
                    $total_credit = 0;
                    $total_cancelled = 0;
                }
            }
            $htm .= "<tr><td colspan='7'><hr></td></tr>";
            $htm .= "<tr><td colspan='3' style='text-align: left;'>
                            <b>GRAND TOTAL</b>
                        </td>
                        <td style='text-align: right;'><b>".number_format($grand_total_cash)."</b></td>
                        <td style='text-align: right;'><b>".number_format($grand_total_credit)."</b></td>
                        <td style='text-align: right;'><b>".number_format($grand_total_cancelled)."</b></td>
                        <td style='text-align: right;'>
                            <b>".number_format($general_grand_total)."</b>
                        </td>
                    </tr>
                    <tr><td colspan='7'><hr></td></tr>";
            $htm .= '</table>';

    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    // $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($E_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By eHMS');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    
?>

 