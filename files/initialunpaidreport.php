<?php
	session_start();
	include("./includes/connection.php");
	
	$temp1 = 0;
	$temp2 = 0;
	$Paid = '<span style="font-size: small;"><b>PATIENTS LIST ~ OTHER PAYMENTS</b></span>';
	$Unpaid = '<span style="font-size: small;"><b>UNPAID PATIENTS LIST</b></span>
				<table width="70%" border="1" style="border-collapse: collapse;">
					<tr><td width = "5%"><span style="font-size: small;"><b>SN</span></td>
						<td width = "30%"><span style="font-size: small;"><b>PATIENT NAME</b></span></td>
						<td width = "30%"><span style="font-size: small;"><b>VISIT DATE & TIME</b></span></td>
						<td width = "30%"><span style="font-size: small;"><b>EMPLOYEE CHECKED IN</b></span></td>
					</tr>';

    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo']['Employee_Name'])){
    	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
    	$Employee_Name = '';
    }

    if(isset($_GET['Sponsor_ID'])){
    	$Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
    	$Sponsor_ID = 0;
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

	$htm = "<table width ='100%' height = '30px'>
			    <tr><td style='text-align: center;'>
				<img src='./branchBanner/branchBanner.png'>
			    </td></tr>
		    </table>";

    //get selected items
    $value = '';
    $slt = mysqli_query($conn,"select Item_ID from tbl_initial_items") or die(mysqli_error($conn));
    $slt_no = mysqli_num_rows($slt);
    if($slt_no > 0){
    	while ($row = mysqli_fetch_array($slt)) {
    		$value .= ','.$row['Item_ID'];
    	}
    }

	$Filter_Value = substr($value, 1);

    if($Sponsor_ID != 0){
    	$select = mysqli_query($conn,"select ci.Check_In_ID, ci.Registration_ID, ci.Check_In_Date_And_Time, ci.Employee_ID from tbl_check_in ci, tbl_patient_registration pr where 
    							ci.Registration_ID = pr.Registration_ID and
    							pr.Sponsor_ID = '$Sponsor_ID' and
    							ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' order by ci.Registration_ID") or die(mysqli_error($conn));
    	$no = mysqli_num_rows($select);

    	if($no > 0){
    		while ($data = mysqli_fetch_array($select)) {
    			$Check_In_ID = $data['Check_In_ID'];
    			$Registration_ID = $data['Registration_ID'];
    			$Employee_ID = $data['Employee_ID'];
    			$Check_In_Date_And_Time = $data['Check_In_Date_And_Time'];
    			$check = mysqli_query($conn,"select pp.Patient_Payment_ID from tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 
    			 						ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
    			 						pp.Check_In_ID = '$Check_In_ID' and
    			 						ppl.Item_ID IN ($Filter_Value) group by pp.Check_In_ID") or die(mysqli_error($conn));
    			$num_check = mysqli_num_rows($check);
    			if($num_check == 0){
    				$get_emp = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn)); //get employee checked in patient
    				while ($dz = mysqli_fetch_array($get_emp)) {
    					$Employee_Checked_In = $dz['Employee_Name'];
    				}
    				$get_details = mysqli_query($conn,"select Patient_Name from tbl_patient_registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    				$nm = mysqli_num_rows($get_details);
    				if($nm > 0){
    					while ($rw = mysqli_fetch_array($get_details)) {
    						$Patient_Name = $rw['Patient_Name'];
    					}
    				}else{
    					$Patient_Name = '';
    				}
?>
				
						<?php
							$result = mysqli_query($conn,"select Check_In_ID from tbl_patient_payments where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
							$this_num = mysqli_num_rows($result);

							$items =  mysqli_query($conn,"select pp.Patient_Payment_ID, pp.Billing_Type, emp.Employee_Name, i.Product_Name, ppl.Price, ppl.Discount, ppl.Quantity, ppl.Quantity from
													tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i, tbl_employee emp where
													pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
													ppl.Item_ID = i.Item_ID and
													emp.Employee_ID = pp.Employee_ID and
													pp.Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn)); //get transactions details
							//$T_num = mysqli_num_rows($items);

							if($this_num > 0){
								$Total_Paid = 0;
								$Paid .= '	<table width="100%" border=1 style="border-collapse: collapse;">
									<tr>
										<td width="5%"><span style="font-size: small;">'.++$temp1.'<b>.</b></span></td>
										<td style="text-align: left;" width="24%" colspan="2"><span style="font-size: small;">Patient Name ~ '.ucwords(strtolower($Patient_Name)).'</span></td>
										<td style="text-align: left;" width="43%" colspan="2"><span style="font-size: small;">Visit Date & Time ~ '.$Check_In_Date_And_Time.'</span></td>
										<td style="text-align: left;" width="28%" colspan="2"><span style="font-size: small;">Employee checked in ~ '.ucwords(strtolower($Employee_Checked_In)).'</span></td>
									</tr>
									<tr>
										<td width="5%"><span style="font-size: small;">SN</span></td>
										<td><span style="font-size: small;">Receipt#</span></td>
										<td width="12%"><span style="font-size: small;">Billing Type</span></td>
										<td><span style="font-size: small;">Item Name</span></td>
										<td width="6%" style="text-align: right;"><span style="font-size: small;">Quantity</span></td>
										<td style="text-align: right;"><span style="font-size: small;">Amount Paid</span></td>
										<td><span style="font-size: small;">Cashier Name</span></td></tr>
										';
						
								$counter = 0;
								while ($tm = mysqli_fetch_array($items)) {
									$Total_Paid += (($tm['Price'] - $tm['Discount']) * $tm['Quantity']);
									$Paid .=	'<tr>
											<td><span style="font-size: small;">'.++$counter.'</span></td>
											<td><span style="font-size: small;">'.$tm['Patient_Payment_ID'].'</span></td>
											<td><span style="font-size: small;">'.$tm['Billing_Type'].'</span></td>
											<td><span style="font-size: small;">'.$tm['Product_Name'].'</span></td>
											<td style="text-align: right;"><span style="font-size: small;">'.$tm['Quantity'].'</span></td>
											<td style="text-align: right;"><span style="font-size: small;">'.number_format((($tm['Price'] - $tm['Discount']) * $tm['Quantity'])).'</span></td>
											<td><span style="font-size: small;">'.ucwords(strtolower($tm['Employee_Name'])).'</span></td>
										</tr>';
						
								}
								$Paid .= '<tr><td colspan="2"><span style="font-size: small;"><b>TOTAL PAID</b></span></td>
												<td colspan="4" style="text-align: right;"><span style="font-size: small;"><b>'.number_format($Total_Paid).'</b></span></td></tr>';
								$Paid .= '</table><br/>';
							}else{ 
								$Unpaid .= '<tr>
												<td width="5%"><span style="font-size: small;">'.++$temp2.'<b>.</b></span></td>
												<td style="text-align: left;"><span style="font-size: small;">'.ucwords(strtolower($Patient_Name)).'</span></td>
												<td style="text-align: left;"><span style="font-size: small;">'.$Check_In_Date_And_Time.'</span></td>
												<td style="text-align: left;"><span style="font-size: small;">'.ucwords(strtolower($Employee_Checked_In)).'</span></td>
											</tr>';
							}
    			} 
    		}
    	}
    	$Unpaid .= '</table>';
    	$htm = $Paid.'<br/><br/>'.$Unpaid;
    }
    echo $htm;
    //echo $Paid;
  /*include("./MPDF/mpdf.php");

    $mpdf=new mPDF('','A4-L',0,'',12.7,12.7,14,12.7,8,8);
    $mpdf->SetFooter('Printed By '.strtoupper($Employee_Name).'|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;*/
?>
