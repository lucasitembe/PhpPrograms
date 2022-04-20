<?php
	include("./includes/connection.php");
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
		$Billing_Type_Value = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	//get employee name
	$select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($row = mysqli_fetch_array($select)) {
			$Employee_Name = $row['Employee_Name'];
		}
	}else{
		$Employee_Name = '';
	}
?>


<!--<legend align='center' style="background-color:#006400;color:white;padding:5px;"><b>DISPENSED LIST <?php echo strtoupper($Page_Title); ?> </b></legend>-->
<center>
    
        <?php
		$temp = 0;
		$total_cancelled = 0;
		$total_credit = 0;
		$total_cash = 0;
		$cancel_control = 'no';
   
            if($Sponsor_ID == 0){ 
                if($Billing_Type_Value == 'All'){
                    $select = mysqli_query($conn,"select pp.Patient_Payment_ID, sp.Guarantor_Name, pr.Patient_Name, pr.Registration_ID, pp.Billing_Type, pp.Transaction_status,sp.Exemption,pp.Pre_Paid,pp.Cancel_transaction_reason, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.payment_type
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        pp.Registration_ID = pr.Registration_ID and
                                        sp.Sponsor_ID = pr.Sponsor_ID and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                        pp.Employee_ID = '$Employee_ID' group by pp.Patient_Payment_ID order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                }else if($Billing_Type_Value == 'Outpatient'){
                    $select = mysqli_query($conn,"select pp.Patient_Payment_ID, sp.Guarantor_Name, pr.Patient_Name, pr.Registration_ID, pp.Billing_Type, pp.Transaction_status,sp.Exemption,pp.Pre_Paid,pp.Cancel_transaction_reason, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.payment_type
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        pp.Registration_ID = pr.Registration_ID and
                                        sp.Sponsor_ID = pr.Sponsor_ID and
                                        (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                        pp.Employee_ID = '$Employee_ID' group by pp.Patient_Payment_ID order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                }else if($Billing_Type_Value == 'Inpatient'){
                    $select = mysqli_query($conn,"select pp.Patient_Payment_ID, sp.Guarantor_Name, pr.Patient_Name, pr.Registration_ID, pp.Billing_Type, pp.Transaction_status,sp.Exemption,pp.Pre_Paid,pp.Cancel_transaction_reason, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.payment_type
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        pp.Registration_ID = pr.Registration_ID and
                                        sp.Sponsor_ID = pr.Sponsor_ID and
                                        (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                        pp.Employee_ID = '$Employee_ID' group by pp.Patient_Payment_ID order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                }
            }else{
                if($Billing_Type_Value == 'All'){
                    $select = mysqli_query($conn,"select pp.Patient_Payment_ID, sp.Guarantor_Name, pr.Patient_Name, pr.Registration_ID, pp.Billing_Type, pp.Transaction_status,sp.Exemption,pp.Pre_Paid,pp.Cancel_transaction_reason, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.payment_type
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        pp.Registration_ID = pr.Registration_ID and
                                        sp.Sponsor_ID = pr.Sponsor_ID and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                        pp.Sponsor_ID = '$Sponsor_ID' and
                                        pp.Employee_ID = '$Employee_ID' group by pp.Patient_Payment_ID order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                }else if($Billing_Type_Value == 'Outpatient'){
                    $select = mysqli_query($conn,"select pp.Patient_Payment_ID, sp.Guarantor_Name, pr.Patient_Name, pr.Registration_ID, pp.Billing_Type, pp.Transaction_status,sp.Exemption,pp.Pre_Paid,pp.Cancel_transaction_reason, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.payment_type
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        pp.Registration_ID = pr.Registration_ID and
                                        sp.Sponsor_ID = pr.Sponsor_ID and
                                        (pp.Billing_Type = 'Outpatient Credit' or pp.Billing_Type = 'Outpatient Cash') and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                        pp.Sponsor_ID = '$Sponsor_ID' and
                                        pp.Employee_ID = '$Employee_ID' group by pp.Patient_Payment_ID order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                }else if($Billing_Type_Value == 'Inpatient'){
                    $select = mysqli_query($conn,"select pp.Patient_Payment_ID, sp.Guarantor_Name, pr.Patient_Name, pr.Registration_ID, pp.Billing_Type, pp.Transaction_status,sp.Exemption,pp.Pre_Paid,pp.Cancel_transaction_reason, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, pp.payment_type
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        pp.Registration_ID = pr.Registration_ID and
                                        sp.Sponsor_ID = pr.Sponsor_ID and
                                        (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Inpatient Cash') and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' and
                                        pp.Sponsor_ID = '$Sponsor_ID' and
                                        pp.Employee_ID = '$Employee_ID' group by pp.Patient_Payment_ID order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
                }
            }
?>
<br/>
<center>
	<table width='80%'style='background-color:white'>
		<tr>
			<td style="text-align: right;"><b>Employee Name ~ </b></td><td style="text-align: left;"><?php echo $Employee_Name; ?></td>
			<td style="text-align: right;"><b>Start Date ~ </b></td><td style="text-align: left;"><?php echo $Date_From; ?></td>
			<td style="text-align: right;"><b>End Date ~ </b></td><td style="text-align: left;"><?php echo $Date_To; ?></td>
		</tr>
	</table>
</center>
<br/>
<?php
			$num_rows = mysqli_num_rows($select);
                if($num_rows > 0){
                	echo "<fieldset style='overflow-y: scroll; height: 300px; background-color:white;' id='Items_Fieldset'>";
                	echo "<table width='100%' style='background-color:white;'>";
                	echo "<tr><td colspan=7><hr></td></tr>";
                	echo "<tr>
                    			<td width='5%'>&nbsp;&nbsp;<b>SN</b></td>
                    			<td><b>PATIENT NAME</b></td>
                    			<td width='10%'><b>PATIENT#</b></td>
                    			<td width='15%'><b>SPONSOR NAME</b></td>
                    			<td width='12%'><b>BILLING TYPE</b></td>
                                        <td width='12%'><b>CANCEL REASON</b></td>
                    			<td width='10%' style='text-align: right;'><b>RECEIPT#</b></td>
                    			<td width='10%' style='text-align: right;'><b>AMOUNT&nbsp;&nbsp;</b></td>
                    		</tr>";
                	echo "<tr><td colspan=7><hr></td></tr>";
                    while($data = mysqli_fetch_array($select)){

                        //display transaction, calculate credit, cash and cancelled total
                        if(strtolower($data['Transaction_status']) == 'cancelled'){
				$cancel_control = 'yes';
                    ?>
				<tr style="color: red;">
				    <td><b>&nbsp;&nbsp;<?php echo ++$temp; ?></b></td>
				    <td><b><?php echo $data['Patient_Name']; ?></b></td>
				    <td><b><?php echo $data['Registration_ID']; ?></b></td>
				    <td><b><?php echo $data['Guarantor_Name']; ?></b></td>
				    <td><b><?php echo $data['Billing_Type']; ?></b></td>
                                    <td><b><?php echo $data['Cancel_transaction_reason']; ?></b></td>
                                    <!--pp.Cancel_transaction_reason,-->
				    <td style='text-align: right;'><b><?php echo $data['Patient_Payment_ID']; ?></b></td>
				    <td style='text-align: right;'><b><?php echo number_format($data['Amount']); ?>&nbsp;&nbsp;</b></td>
				</tr>
                    <?php
				$total_cancelled = $total_cancelled + $data['Amount'];
                        }else{
                            $Pre_Paid=$data['Pre_Paid'];
                            $Billing_Type=$data['Billing_Type'];
                            $payment_type=$data['payment_type'];
                    
//                            if((strtolower($Billing_Type) == 'outpatient cash'&& $Pre_Paid == '0') || strtolower($Billing_Type) =="patient from outside"||(strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
//                                $total_cash = $total_cash + $data['Amount'];
//                            }else if(strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' ||  (strtolower($data['Billing_Type']) == 'inpatient cash' && strtolower($data['payment_type']) == 'post')){
//                                $total_credit = $total_credit + $data['Amount'];
//                            }
                            if((strtolower($Billing_Type) == 'outpatient cash'&& $Pre_Paid == '0') || strtolower($Billing_Type) =="patient from outside"||(strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                    $total_cash = $total_cash + $data['Amount'];
                                    ?>
                        <tr>
                            <td>&nbsp;&nbsp;<?php echo ++$temp; ?></td>
                            <td><?php echo $data['Patient_Name']; ?></td>
                            <td><?php echo $data['Registration_ID']; ?></td>
                            <td><?php echo $data['Guarantor_Name']; ?></td>
                            <td><?php echo $data['Billing_Type']; ?></td>
                             <td><?php echo $data['Cancel_transaction_reason']; ?></td>
                            <td style='text-align: right;'><?php echo $data['Patient_Payment_ID']; ?></td>
                            <td style='text-align: right;'><?php echo number_format($data['Amount']); ?>&nbsp;&nbsp;</td>
                        </tr>
                    <?php
                            }else if($data['Exemption']!='yes' && (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit')){
                                 $total_credit = $total_credit + $data['Amount'];
                                 ?>
                        <tr>
                            <td>&nbsp;&nbsp;<?php echo ++$temp; ?></td>
                            <td><?php echo $data['Patient_Name']; ?></td>
                            <td><?php echo $data['Registration_ID']; ?></td>
                            <td><?php echo $data['Guarantor_Name']; ?></td>
                            <td><?php echo $data['Billing_Type']; ?></td>
                             <td><?php echo $data['Cancel_transaction_reason']; ?></td>
                            <td style='text-align: right;'><?php echo $data['Patient_Payment_ID']; ?></td>
                            <td style='text-align: right;'><?php echo number_format($data['Amount']); ?>&nbsp;&nbsp;</td>
                        </tr>
                    <?php
                            }

                             if(($data['Exemption']=='yes') && (strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit' || (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post'))){
                                   $total_msamaha=$total_msamaha+$data['Amount'];

                             }
                        }
                    }
                	echo '</table></fieldset><br/>';
        ?>
                <center>
                    <table width="70%" style='background-color:white;'>
                        <tr>
                            <td width="33%"style='text-align: left;'><b>Total Cash ~ </b><?php echo number_format($total_cash); ?></td>
                            <td width="33%"style='text-align: center;'><b>Total Credit ~ </b><?php echo number_format($total_credit); ?></td>
                            <td width="33%"style='text-align: right;'><span style='color: red;'><b>Total Cancelled ~ </b><?php echo number_format($total_cancelled); ?></span></td>
                        </tr>
                    </table>
                </center>
	<?php if($cancel_control ==  'yes'){ ?>
		<span style='font-size: small; color: red;'>PLEASE NOTE: Red color implies cancelled transactions!</span><br/>
        <?php
		}
                }else{
                    echo 'No Transaction Found';
                }
        ?>
               