<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
        $E_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $E_Name = '';
    }

	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}
	
	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}
	
	if(isset($_GET['Billing_Type'])){
		$Billing_Type = $_GET['Billing_Type'];
	}else{
		$Billing_Type = 'All';
	}
	
	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = '0';
	}

	//get employee
    if($Employee_ID == '0'){
        $Emp_Name = 'All';
    }else{
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while($row = mysqli_fetch_array($select)){
                $Emp_Name = $row['Employee_Name'];
            }
        }
    }
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor = $_GET['Sponsor_ID'];
	}else{
		$Sponsor = '0';
	}

	//get Sponsor
    if($Sponsor == '0'){
        $Sponsor_Name = 'All';
    }else{
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while($row = mysqli_fetch_array($select)){
                $Sponsor_Name = $row['Guarantor_Name'];
            }
        }else{
            $Sponsor_Name = 'ALL';
        }
    }
    if(isset($_GET['Cash_Option'])){
		$Cash_Option = $_GET['Cash_Option'];
	}else{
		$Cash_Option = 'no';
	}

	//check if cash details not needed
	$C_Msamaha = '';
	$C_Cash = '';
	if($Cash_Option == 'yes'){
		//get Cash Sponsor id
		$slct = mysqli_query($conn,"select Sponsor_ID from tbl_sponsor where Guarantor_Name = 'CASH'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($slct);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($slct)) {
				$C_Cash = $dt['Sponsor_ID'];
			}
		}
	}

	if(isset($_GET['Msamaha_Option'])){
		$Msamaha_Option = $_GET['Msamaha_Option'];
	}else{
		$Msamaha_Option = 'no';
	}

	//check if msamaha not needed
	if($Msamaha_Option == 'yes'){
		$C_Msamaha = 'no';
	}

	$filter = " pp.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and pp.Transaction_status <> 'cancelled' and ";

	if($Employee_ID != null && $Employee_ID != '' && $Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and ";
	}
	

	if($Billing_Type == 'Outpatient'){
		$Bill_Type = 'Outpatient';
	}else if($Billing_Type == 'Inpatient'){
		$Bill_Type = 'Inpatient';
	}else{
		$Bill_Type = 'All';
	}


	$htm = "<table width ='100%' height = '30px'>
		<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
	    <tr><td>&nbsp;</td></tr></table>";
		
	$htm .= '<center><table width="100%">
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">REVENUE COLLECTION BY SPONSOR REPORT</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">FROM <b>'.@date("d F Y H:i:s",strtotime($Start_Date)).'</b> TO <b>'.@date("d F Y H:i:s",strtotime($End_Date)).'</b></span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">PATIENT TYPE : '.strtoupper($Bill_Type).',&nbsp;&nbsp;&nbsp;&nbsp;SPONSOR : '.strtoupper($Sponsor_Name).'</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">EMPLOYEE NAME : '.strtoupper($Emp_Name).'</span></td></tr>
	        </table></center>';

	$htm .= '<table width="100%" border=1 style="border-collapse: collapse;">';
	$Title = "<thead><tr>
			    <td width=5%><b><span style='font-size: x-small;'>SN<span></b></td>
			    <td><b><span style='font-size: x-small;'>SPONSOR NAME<span></b></td>
			    <td style='text-align: right;' width='15%'><b><span style='font-size: x-small;'>CASH<span></b></td>
			    <td style='text-align: right;' width='15%'><b><span style='font-size: x-small;'>CREDIT<span></b></td>
			    <td style='text-align: right;' width='15%'><b><span style='font-size: x-small;'>MSAMAHA<span></b></td>
			</tr></thead>";
	$htm .= $Title;
	$temp = 0;
	$Grand_Total_Cash = 0;
	$Grand_Total_Credit = 0;
	$Grand_Total_Msamaha = 0;
	
	//get sponsors
	if($Sponsor != null && $Sponsor != '' && $Sponsor != '0'){
		$get_sponsors = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID, Exemption from tbl_sponsor where Sponsor_ID = '$Sponsor' order by Guarantor_Name") or die(mysqli_error($conn));
	}else{
		$get_sponsors = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID, Exemption from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
	}

	$num_sponsors = mysqli_num_rows($get_sponsors);
	if($num_sponsors > 0){
		while ($sp_details = mysqli_fetch_array($get_sponsors)) {
			$Sponsor_ID = $sp_details['Sponsor_ID'];
			$Exemption = $sp_details['Exemption'];
			$Total_Cash = 0;
			$Total_Credit = 0;
			$Total_Msamaha = 0;

			if($Billing_Type == 'Outpatient'){
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Sponsor_ID from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
										$filter
										(pp.Billing_Type = 'Outpatient Cash' or pp.Billing_Type = 'Outpatient Credit') and
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
										pp.Sponsor_ID = '$Sponsor_ID'")	or die(mysqli_error($conn));
			}else if($Billing_Type == 'Inpatient'){
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Sponsor_ID from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 
										$filter
										(pp.Billing_Type = 'Inpatient Cash' or pp.Billing_Type = 'Inpatient Credit') and
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 
										pp.Sponsor_ID = '$Sponsor_ID'")	or die(mysqli_error($conn));
			}else{
				$select = mysqli_query($conn,"select ppl.Price, ppl.Discount, ppl.Quantity, pp.Billing_Type, pp.payment_type, pp.Sponsor_ID from
										tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 
										$filter
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and 
										pp.Sponsor_ID = '$Sponsor_ID'")	or die(mysqli_error($conn));
			}

			while ($row = mysqli_fetch_array($select)) {
				$Amount = (($row['Price'] - $row['Discount']) * $row['Quantity']);
				if((strtolower($row['Billing_Type']) == 'outpatient cash') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')){
					if($C_Cash != ''){
						if(strtolower($row['Billing_Type']) == 'outpatient cash' && strtolower($row['Billing_Type']) != 'inpatient cash' && strtolower($row['payment_type']) != 'pre' && $C_Cash != $row['Sponsor_ID']){
							$Total_Cash += $Amount;
				        	$Grand_Total_Cash += $Amount;
						}
					}else{
				        $Total_Cash += $Amount;
				        $Grand_Total_Cash += $Amount;
				    }
			    }else if(((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')) && strtolower($Exemption) == 'no'){
					$Total_Credit += $Amount;
					$Grand_Total_Credit += $Amount;
			    }else if(((strtolower($row['Billing_Type']) == 'outpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient credit') or (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')) && strtolower($Exemption) == 'yes'){
			        if($C_Msamaha == ''){
				        $Total_Msamaha += $Amount;
				        $Grand_Total_Msamaha += $Amount;
				    }
			    }
			}

			$htm .=	'<tr>
					    <td><span style="font-size: x-small;">'.++$temp.'</span></td>
					    <td><span style="font-size: x-small;">'.strtoupper($sp_details['Guarantor_Name']).'</span></td>
					    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Cash).'</span></td>
					    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Credit).'</span></td>
					    <td style="text-align: right;"><span style="font-size: x-small;">'.number_format($Total_Msamaha).'</span></td>
					</tr>';

		}
	}
	$htm .= '<tr>
			    <td colspan="2"><b><span style="font-size: x-small;">GRAND TOTAL</span></b></td>
			    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($Grand_Total_Cash).'</span></b></td>
			    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($Grand_Total_Credit).'</span></b></td>
			    <td style="text-align: right;"><b><span style="font-size: x-small;">'.number_format($Grand_Total_Msamaha).'</span></b></td>
			</tr></table>';
	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>