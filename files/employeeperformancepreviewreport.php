<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    $temp2 = 0;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    /*if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }*/
    
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
    
    if(isset($_GET['Transaction_Type'])){
        $Transaction_Type = $_GET['Transaction_Type'];
    }else{
        $Transaction_Type = 'all'; 
    }
    
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        $Patient_Payment_ID = 0;
    }
    $total = 0;
    
	$transaction_channel = $_GET['transaction_channel'];
   
   if($transaction_channel != "All"){
       if($transaction_channel=="CRDB"){
           $transaction_channel_filter=" AND pp.auth_code NOT LIKE '77100%' AND pp.auth_code NOT LIKE 'BUH100%'";
	   }else if($transaction_channel=="CRDBmobile"){
		   $transaction_channel_filter ="and pp.auth_code LIKE 'BUH100%'";
       }else if($transaction_channel=="NMB"){
		  //  $table_card_mobile = ",tbl_card_and_mobile_payment_transaction crd ";
			$transaction_channel_filter ="and pp.auth_code LIKE '77100%'";
           // $transaction_channel_filter="AND crd.payment_direction='to_nmb'";  
       }else{
		   $transaction_channel_filter ="and pp.manual_offline LIKE 'offline%'";
	   }
   }
    //select printing date and time
    $select_Time_and_date = mysqli_query($conn,"select now() as datetime");
    while($row = mysqli_fetch_array($select_Time_and_date)){
	$Date_Time = $row['datetime'];
    } 
    $htm = "<center><table width ='100%' height = '30px'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>Employee Performance Report</b></span></td></tr>
		    ";

    //get employee details
    $select_details = mysqli_query($conn,"SELECT * from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select_details);
    if($num > 0){
	while($row2 = mysqli_fetch_array($select_details)){
	    $Employee_Name = $row2['Employee_Name'];
	}
    }
    
    $htm .= "<tr><td style='text-align: center;'><b>Employee Name : </b>".$Employee_Name."</td></tr>
            <tr><td style='text-align: center;'><b>Transaction Type : </b>".$Transaction_Type."</td></tr>
    	    <tr><td style='text-align: center;'><b>Date From : </b>".$Date_From."</td></tr>
    	    <tr><td style='text-align: center;'><b>Date To : </b>".$Date_To."</td></tr></table></center>";
?> 
<?php
    $htm .="<table width=100%><tr><td colspan=8><hr></td></tr>
		<tr><td width='5%'>Sn</td>
		    <td width='23%'>Date And Time</td>
		    <td>Patient Name</td>
		    <td width='12%'>Receipt N<u>o</u></td>
		    <td style='text-align: right;' width='12%'>Cash</td>
		    <td style='text-align: right;' width='12%'>Credit</td>
		    <td style='text-align: right;' width='12%'>Cancelled</td>
                    <td style='text-align: right;' >Cancel Reason</td>
                    </tr>
            <tr><td colspan=8><hr>";
		    
		    
    if($Transaction_Type == 'All'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cash'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                pp.Billing_Type  in ('Outpatient Cash','patient from outside','Inpatient Cash') and pp.Pre_Paid = '0'  and pp.payment_type = 'pre' and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' $transaction_channel_filter group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Credit'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid 
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit' or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'post')) and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cancelled'){
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = '$Employee_ID' and
                pr.Registration_ID = pp.Registration_ID and
                pp.Transaction_status = 'cancelled' and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }else{
        $select_Filtered_Employees = mysqli_query($conn,
                "select pr.Patient_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time, pp.Billing_Type, pp.Transaction_status,pp.Cancel_transaction_reason, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid 
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
                where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pp.employee_id = 0 and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }
    //declare all total
    $Cash_Total = 0;
    $Credit_Total = 0;
    $Cancelled_Total = 0;
    $Grand_total_cancelled = 0;
    $Grand_total_credit = 0;
    $Grand_total_cash = 0;
    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        $htm .= "<tr><td>".$temp."</td>";
        $htm .= "<td>".$row['Payment_Date_And_Time']."</td>";
        $htm .= "<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
        $htm .= "<td style='text-align: left;'>".$row['Patient_Payment_ID']."</td>";
        if(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0' || (strtolower($row['Billing_Type']) == 'patient from outside' && $row['Pre_Paid'] == '0')) or (strtolower($row['Billing_Type']) == 'inpatient cash'  && strtolower($row['payment_type']) == 'pre')) and (strtolower($row['Transaction_status']) == 'active')){
            $htm .= "<td style='text-align: right;'>".number_format($row['Total'])."</td>";
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm.="<td>".$row['Cancel_transaction_reason']."</td></tr>";
            $Cash_Total = $Cash_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0' || (strtolower($row['Billing_Type']) == 'patient from outside' && $row['Pre_Paid'] == '0')) or (strtolower($row['Billing_Type']) == 'inpatient cash')) and (strtolower($row['Transaction_status']) == 'cancelled')){
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>".number_format($row['Total'])."</td>";
            $htm.="<td>".$row['Cancel_transaction_reason']."</td></tr>";
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') or (strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash'  && strtolower($row['payment_type']) == 'post')) and (strtolower($row['Transaction_status']) == 'active')){
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>".number_format($row['Total'])."</td>"; 
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm.="<td>".$row['Cancel_transaction_reason']."</td></tr>";
            $Credit_Total = $Credit_Total + $row['Total'];
        }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') or (strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit')) and (strtolower($row['Transaction_status']) == 'cancelled')){
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>0</td>";
            $htm .= "<td style='text-align: right;'>".number_format($row['Total'])."</td>";
            $htm.="<td>".$row['Cancel_transaction_reason']."</td></tr>";
            $Cancelled_Total = $Cancelled_Total + $row['Total'];
        }
        //$htm .= "<tr><td colspan=7><hr></td></tr>";
        $temp++;        
    }
    $htm .= "<tr><td colspan=8><hr></td></tr>";
    $htm .= "<tr><td colspan=4 style='text-align: right;'><b> Sub Total</b></td>";
    $htm .= "<td style='text-align: right;'><b>".number_format($Cash_Total)."</b></td>";
    $htm .= "<td style='text-align: right;'><b>".number_format($Credit_Total)."</b></td>";
    $htm .= "<td style='text-align: right;'><b>".number_format($Cancelled_Total)."</b></td></tr>";
    $htm .= "<tr><td colspan=8><hr></td></tr>";
    $htm .= "<tr><td colspan=8 style='text-align: right;'><b>Grand Total ".number_format($Cash_Total + $Credit_Total)."</b></td></tr>";
    $htm .= "<tr><td colspan=8><hr></td></tr></table>";

    $Cash_Total = 0;
    $Credit_Total = 0;
    $Cancelled_Total = 0;

    
    
    
    
    //get summary
    //select categories based on transactions above
    if($Transaction_Type == 'All'){
        $get_category = mysqli_query($conn,
                "select ic.Item_Category_ID, ic.Item_Category_Name
                from tbl_patient_payment_item_list ppl, tbl_items i, tbl_item_subcategory isu, tbl_item_category ic, tbl_patient_payments pp
                where ppl.Item_ID = i.Item_ID and
        		isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
        		pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
        		isu.Item_Category_ID = ic.Item_Category_ID and
                pp.employee_id = '$Employee_ID' and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by ic.Item_Category_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cash'){
        $get_category = mysqli_query($conn,
                "select ic.Item_Category_ID, ic.Item_Category_Name
                from tbl_patient_payment_item_list ppl, tbl_items i, tbl_item_subcategory isu, tbl_item_category ic, tbl_patient_payments pp
                where ppl.Item_ID = i.Item_ID and
        		isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
        		pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
        		isu.Item_Category_ID = ic.Item_Category_ID and
                pp.employee_id = '$Employee_ID' and
                ((pp.Billing_Type = 'Outpatient Cash' and pp.Pre_Paid = '0') or (pp.Billing_Type = 'patient from outside' and pp.Pre_Paid = '0') or (pp.Billing_Type = 'Inpatient Cash' && pp.payment_type = 'pre')) and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by ic.Item_Category_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Credit'){
        $get_category = mysqli_query($conn,
                "select ic.Item_Category_ID, ic.Item_Category_Name 
                from tbl_patient_payment_item_list ppl, tbl_items i, tbl_item_subcategory isu, tbl_item_category ic, tbl_patient_payments pp
                where ppl.Item_ID = i.Item_ID and
        		isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
        		pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
        		isu.Item_Category_ID = ic.Item_Category_ID and
                pp.employee_id = '$Employee_ID' and
                (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit' or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'post')) and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by ic.Item_Category_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }elseif($Transaction_Type == 'Cancelled'){
        $get_category = mysqli_query($conn,
                "select ic.Item_Category_ID, ic.Item_Category_Name 
                from tbl_patient_payment_item_list ppl, tbl_items i, tbl_item_subcategory isu, tbl_item_category ic, tbl_patient_payments pp
                where ppl.Item_ID = i.Item_ID and
        		isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
        		pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
        		isu.Item_Category_ID = ic.Item_Category_ID and
                pp.employee_id = '$Employee_ID' and
                pp.Transaction_status = 'cancelled' and
                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by ic.Item_Category_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }else{
        $get_category = mysqli_query($conn,
                "select ic.Item_Category_ID, ic.Item_Category_Name
        		from tbl_patient_payment_item_list ppl, tbl_items i, tbl_item_subcategory isu, tbl_item_category ic, tbl_patient_payments pp
        		where ppl.Item_ID = i.Item_ID and
        		isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
        		pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
        		isu.Item_Category_ID = ic.Item_Category_ID and
        		pp.employee_id = 0 and
        		pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by ic.Item_Category_ID order by pp.Payment_Date_And_Time") or die(mysqli_error($conn)); 
    }
    
    $category_no = mysqli_num_rows($get_category);
    if($category_no > 0){

    	$htm .= '<br/><br/><br/><table width="100%">';
    	$htm .= '<tr><td width="5%"><b>SN</b></td>';
    	$htm .= '<td width="55%"><b>SUB CATEGORY NAME</b></td>';
    	$htm .= '<td width="12%" style="text-align: right;"><b>CASH</b></td>';
    	$htm .= '<td width="12%" style="text-align: right;"><b>CREDIT</b></td>';
    	$htm .= '<td width="15%" style="text-align: right;"><b>CANCELLED</b></td></tr><tr><td colspan=5><hr></td></tr>';
    	while($data = mysqli_fetch_array($get_category)){    	    
        	    $Item_Category_ID = $data['Item_Category_ID'];
                //get sub categories

                if($Transaction_Type == 'All'){
                    $get_sub_categories = mysqli_query($conn,
                        "select isu.Item_Subcategory_ID, isu.Item_Subcategory_Name
                        from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                        where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        ppl.Item_ID = i.Item_ID and
                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                        isu.Item_Category_ID = ic.Item_Category_ID and
                        pp.employee_id = '$Employee_ID' and
                        pr.Registration_ID = pp.Registration_ID and
                        ic.Item_Category_ID = '$Item_Category_ID' and
                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by isu.Item_Subcategory_ID") or die(mysqli_error($conn));
                }elseif($Transaction_Type == 'Cash'){
                    $get_sub_categories = mysqli_query($conn,
                        "select isu.Item_Subcategory_ID, isu.Item_Subcategory_Name
                        from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                        where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        ppl.Item_ID = i.Item_ID and
                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                        isu.Item_Category_ID = ic.Item_Category_ID and
                        pp.employee_id = '$Employee_ID' and
                        pr.Registration_ID = pp.Registration_ID and
                        ic.Item_Category_ID = '$Item_Category_ID' and
                        ((pp.Billing_Type = 'Outpatient Cash' and pp.Pre_Paid = '0') or (pp.Billing_Type = 'patient from outside' and pp.Pre_Paid = '0') or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'pre')) and
                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by isu.Item_Subcategory_ID") or die(mysqli_error($conn)); 
                }elseif($Transaction_Type == 'Credit'){
                    $get_sub_categories = mysqli_query($conn,
                        "select isu.Item_Subcategory_ID, isu.Item_Subcategory_Name 
                        from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                        where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        ppl.Item_ID = i.Item_ID and
                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                        isu.Item_Category_ID = ic.Item_Category_ID and
                        pp.employee_id = '$Employee_ID' and
                        pr.Registration_ID = pp.Registration_ID and
                        ic.Item_Category_ID = '$Item_Category_ID' and
                        ((pp.Billing_Type = 'Outpatient Cash' and pp.Pre_Paid = '1') or pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit' or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'post')) and
                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by isu.Item_Subcategory_ID") or die(mysqli_error($conn)); 
                }elseif($Transaction_Type == 'Cancelled'){
                    $get_sub_categories = mysqli_query($conn,
                        "select isu.Item_Subcategory_ID, isu.Item_Subcategory_Name
                        from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                        where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        ppl.Item_ID = i.Item_ID and
                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                        isu.Item_Category_ID = ic.Item_Category_ID and
                        pp.employee_id = '$Employee_ID' and
                        pr.Registration_ID = pp.Registration_ID and
                        ic.Item_Category_ID = '$Item_Category_ID' and
                        pp.Transaction_status = 'cancelled' and
                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by isu.Item_Subcategory_ID") or die(mysqli_error($conn)); 
                }else{
                    $get_sub_categories = mysqli_query($conn,
                        "select isu.Item_Subcategory_ID, isu.Item_Subcategory_Name
                        from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                        where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                        ppl.Item_ID = i.Item_ID and
                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                        isu.Item_Category_ID = ic.Item_Category_ID and
                        pp.employee_id = 0 and
                        ic.Item_Category_ID = '$Item_Category_ID' and
                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by isu.Item_Subcategory_ID") or die(mysqli_error($conn)); 
               }

               $nm = mysqli_num_rows($get_sub_categories);
               if($nm > 0){
                    while ($dt = mysqli_fetch_array($get_sub_categories)) {
                        $Item_Subcategory_ID = $dt['Item_Subcategory_ID'];
                        $Item_Subcategory_Name = $dt['Item_Subcategory_Name'];
                        
                        //calculate total & display result
                        if($Transaction_Type == 'All'){
                        $calculate_summary_total = mysqli_query($conn,
                            "select pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                            from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            ppl.Item_ID = i.Item_ID and
                            isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                            isu.Item_Category_ID = ic.Item_Category_ID and
                            pp.employee_id = '$Employee_ID' and
                            pr.Registration_ID = pp.Registration_ID and
                            ic.Item_Category_ID = '$Item_Category_ID' and
                            isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                        }elseif($Transaction_Type == 'Cash'){
                        $calculate_summary_total = mysqli_query($conn,
                            "select pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                            from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            ppl.Item_ID = i.Item_ID and
                            isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                            isu.Item_Category_ID = ic.Item_Category_ID and
                            pp.employee_id = '$Employee_ID' and
                            pr.Registration_ID = pp.Registration_ID and
                            ic.Item_Category_ID = '$Item_Category_ID' and
                            isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                            ((pp.Billing_Type = 'Outpatient Cash' and pp.Pre_Paid = '0') or (pp.Billing_Type = 'patient from outside' and pp.Pre_Paid = '0') or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'pre')) and
                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn)); 
                        }elseif($Transaction_Type == 'Credit'){
                        $calculate_summary_total = mysqli_query($conn,
                            "select pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid 
                            from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            ppl.Item_ID = i.Item_ID and
                            isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                            isu.Item_Category_ID = ic.Item_Category_ID and
                            pp.employee_id = '$Employee_ID' and
                            pr.Registration_ID = pp.Registration_ID and
                            ic.Item_Category_ID = '$Item_Category_ID' and
                            isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                            (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Inpatient Credit' or (pp.Billing_Type = 'Inpatient Cash' and pp.payment_type = 'post')) and
                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn)); 
                        }elseif($Transaction_Type == 'Cancelled'){
                        $calculate_summary_total = mysqli_query($conn,
                            "select pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid 
                            from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            ppl.Item_ID = i.Item_ID and
                            isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                            isu.Item_Category_ID = ic.Item_Category_ID and
                            pp.employee_id = '$Employee_ID' and
                            pr.Registration_ID = pp.Registration_ID and
                            ic.Item_Category_ID = '$Item_Category_ID' and
                            isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                            pp.Transaction_status = 'cancelled' and
                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn)); 
                        }else{
                        $calculate_summary_total = mysqli_query($conn,
                            "select pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid
                            from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items  i, tbl_item_subcategory isu, tbl_item_category ic
                            where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                            ppl.Item_ID = i.Item_ID and
                            isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                            isu.Item_Category_ID = ic.Item_Category_ID and
                            pp.employee_id = 0 and
                            ic.Item_Category_ID = '$Item_Category_ID' and
                            isu.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                            pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn)); 
                       }
                
                        //get total
                        while($row = mysqli_fetch_array($calculate_summary_total)){
                            if(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0' || (strtolower($row['Billing_Type']) == 'patient from outside' && $row['Pre_Paid'] == '0')) or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')) and (strtolower($row['Transaction_status']) == 'active')){
                                $Cash_Total = $Cash_Total + $row['Total'];
                                $Grand_total_cash = $Grand_total_cash + $row['Total'];
                            }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '0' || (strtolower($row['Billing_Type']) == 'patient from outside' && $row['Pre_Paid'] == '0')) or (strtolower($row['Billing_Type']) == 'inpatient cash')) and (strtolower($row['Transaction_status']) == 'cancelled')){
                                $Cancelled_Total = $Cancelled_Total + $row['Total'];
                                $Grand_total_cancelled = $Grand_total_cancelled + $row['Total'];
                            }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') or (strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash'  && strtolower($row['payment_type']) == 'post')) and (strtolower($row['Transaction_status']) == 'active')){
                                $Credit_Total = $Credit_Total + $row['Total'];
                                $Grand_total_credit = $Grand_total_credit + $row['Total'];
                            }elseif(((strtolower($row['Billing_Type']) == 'outpatient cash' && $row['Pre_Paid'] == '1') or (strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit')) and (strtolower($row['Transaction_status']) == 'cancelled')){
                                $Cancelled_Total = $Cancelled_Total + $row['Total'];
                                $Grand_total_cancelled = $Grand_total_cancelled + $row['Total'];
                            }
                        }
                        //end of get total
        
                        $htm .= '<tr><td>'.++$temp2.'</td>';
                        $htm .= '<td>'.ucwords(strtolower($dt['Item_Subcategory_Name'])).'</td>';
                        $htm .= '<td style="text-align: right;">'.number_format($Cash_Total).'</td>';
                        $htm .= '<td style="text-align: right;">'.number_format($Credit_Total).'</td>';
                        $htm .= '<td style="text-align: right;">'.number_format($Cancelled_Total).'</td></tr>';
                        $Cash_Total = 0;
                        $Credit_Total = 0;
                        $Cancelled_Total = 0;
                    }
               }
        	    
	}
	$htm .= '<tr><td colspan=5><hr></td></tr>';
	$htm .= '<tr><td colspan=2 style="text-align: left;"><b>TOTAL</b></td><td style="text-align: right;"><b>'.number_format($Grand_total_cash).'</b></td><td style="text-align: right;"><b>'.number_format($Grand_total_credit).'</b></td><td style="text-align: right;"><b>'.number_format($Grand_total_cancelled).'</b></td></tr>';
	$htm .= '<tr><td colspan=5><hr></td></tr>';
	$htm .= '<tr><td colspan=3 style="text-align: left;"><b>GRAND TOTAL</td><td colspan=2  style="text-align: right;"><b>'.number_format($Grand_total_credit + $Grand_total_cash).'</b></td></tr>';
	$htm .= '<tr><td colspan=5><hr></td></tr>';
    }
?>


<?php
    //echo $htm;
    $htm .= "</table>";
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,20,40,15,35, 'P'); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>