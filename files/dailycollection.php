<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
	$filter = '';
	if(isset($_GET['Employee_ID'])){
		$Employee_ID = $_GET['Employee_ID'];
	}else{
		$Employee_ID = 0;
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
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	//get sponsor
    if($Sponsor_ID == '0'){
        $Guarantor = "ALL";
    }else{
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Guarantor = strtoupper($row['Guarantor_Name']);
            }
        }else{
            $Guarantor = 'ALL';
        }
    }

    //get employees
    if($Employee_ID == '0'){
        $Employees = 'EMPLOYEES : ALL';
    }else{
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Employees = 'EMPLOYEE : '.$row['Employee_Name'];
            }
        }else{
            $Employees = 'EMPLOYEES : ALL';
        }
    }

	$date_from = strtotime($Start_Date); // Convert date to a UNIX timestamp  
	$date_to = strtotime($End_Date); // Convert date to a UNIX timestamp

	
	//CREATE FILTER
	if($Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and";
	}

	if($Sponsor_ID != 0){
		$filter .= " pp.Sponsor_ID = '$Sponsor_ID' and";
	}
	$htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>DAILY COLLECTION REPORT</b></td></tr>
                <tr><td style="text-align:center"><b>SPONSOR : '.$Guarantor.'</b></td></tr>
                <tr><td style="text-align:center"><b>START DATE : '.date("d-m-Y", strtotime($Start_Date)).'</b></td></tr>
                <tr><td style="text-align:center"><b>END DATE : '.date("d-m-Y", strtotime($End_Date)).'</b></td></tr>
                <tr><td style="text-align:center"><b>'.$Employees.'</b></td></tr>
            </table>';

	$htm .= '<table width=100% border=1 style="border-collapse: collapse;">
				<thead>
			    <tr>
			        <td width="5%"><b>SN</b></td>
			        <td><b>TRANSACTIONS DATE</b></td>
			        <td width="15%" style="text-align: right;"><b>CASH</b></td>
			        <td width="15%" style="text-align: right;"><b>CREDIT</b></td>
			        <td width="15%" style="text-align: right;"><b>CANCELLED</b></td>
			        <td width="15%" style="text-align: right;"><b>TOTAL</b></td>
			    </tr></thead>';

	$temp = 0;
	$no_of_days = 0;
	$Grand_Total_Cash = 0;
	$Grand_Total_Credit = 0;
	$Grand_Total_Msamaha = 0;
	$Grand_Total_Cancelled = 0;

	// Loop from the start date to end date and output all dates inbetween  
	for ($i=$date_from; $i<=$date_to; $i+=86400) {
		$Total_Cash = 0;
		$Total_Credit = 0;
		$Total_Msamaha = 0;
		$Total_Cancelled = 0;
		$no_of_days++;
		$Current_Date = date("Y-m-d", $i);
		
		//get details
		$select = mysqli_query($conn,"select pp.auth_code,pp.Pre_Paid,sp.Exemption,pp.Billing_Type, pp.Transaction_status, sum((price - discount)*quantity) as Amount, pp.payment_type,pp.Payment_Code,pp.manual_offline
								from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_sponsor sp where
								pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
								sp.Sponsor_ID = pp.Sponsor_ID and
								$filter
								pp.Receipt_Date = '$Current_Date'
								group BY ppl.patient_payment_id order by pp.Patient_Payment_ID") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($data = mysqli_fetch_array($select)){
				// if(isset($_SESSION['systeminfo']['Inpatient_Prepaid']) && strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) == 'yes'){
				// 	if(((strtolower($data['Billing_Type']) == 'outpatient cash'&&$data['Pre_Paid']==0) || (strtolower($data['Billing_Type']) == 'patient from outside' ) || (strtolower($data['Billing_Type']) == 'inpatient cash'&& $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'){
				// 		$Total_Cash += $data['Amount'];
				// 	}else if(($data['Exemption']!='yes') && (strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit') && strtolower($data['Transaction_status']) != 'cancelled'){
				// 		$Total_Credit += $data['Amount'];
				// 	}else if(strtolower($data['Transaction_status']) == 'cancelled'){
				// 		$Total_Cancelled += $data['Amount'];
				// 	}else  if(($data['Exemption']=='yes') && ((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient credit'))){
	            //         $Total_Msamaha += $data['Amount'];;
			            
                //     } 
				// }else{
					// if(((strtolower($data['Billing_Type']) == 'outpatient cash'&&$data['Pre_Paid']==0) || (strtolower($data['Billing_Type']) == 'patient from outside')  || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'){
						if(((strtolower($data['Billing_Type']) == 'outpatient cash'&& $data['Pre_Paid']==0) ||(strtolower($Billing_Type) =="patient from outside"  && $data['Pre_Paid'] == '0') || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'pre')) && strtolower($data['Transaction_status']) != 'cancelled'  && ($data['auth_code'] != '' || $data['manual_offline'] = 'manual' || ($data['Payment_Code'] != '' && ($data['payment_mode']== 'CRDB' || $data['payment_mode']== 'CRDB..' )))){
						$Total_Cash += $data['Amount'];
					}
					// else if(($data['Exemption']!='yes') && (strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'post')) && strtolower($data['Transaction_status']) != 'cancelled'){
					else if((strtolower($data['Billing_Type']) == 'outpatient cash' && $data['Pre_Paid'] == '1') ||  (strtolower($data['Billing_Type']) == 'outpatient credit' || strtolower($data['Billing_Type']) == 'inpatient credit' || (strtolower($data['Billing_Type']) == 'inpatient cash' && $data['payment_type'] == 'post')) && strtolower($data['Transaction_status']) != 'cancelled'){
						$Total_Credit += $data['Amount'];
					}else if(strtolower($data['Transaction_status']) == 'cancelled'){
						$Total_Cancelled += $data['Amount'];
					}else  if(($data['Exemption']=='yes') && ((strtolower($data['Billing_Type']) == 'outpatient credit') or (strtolower($data['Billing_Type']) == 'inpatient credit'))){
	                    $Total_Msamaha += $data['Amount'];;
			            
                    } 
				// }
			}
		}

	    $htm .= '<tr>
				        <td>'.++$temp.'</td>
				        <td>'.date("d-m-Y", strtotime($Current_Date))."--".date("l", strtotime($Current_Date)).'</td>
				        <td style="text-align: right;">'.number_format($Total_Cash).'</td>
				        <td style="text-align: right;">'.number_format($Total_Credit).'</td>
				        <td style="text-align: right;">'.number_format($Total_Cancelled).'</td>
				        <td style="text-align: right;">'.number_format($Total_Cash + $Total_Credit).'</td>
				    </tr>';

		//get grand total
		$Grand_Total_Cash += $Total_Cash;
		$Grand_Total_Credit += $Total_Credit;
		$Grand_Total_Cancelled += $Total_Cancelled;
		$Grand_Total_Msamaha += $Total_Msamaha;

	}

	$htm .= '<tr>
				<td colspan="2"><b>GRAND TOTAL</b></td>
				<td style="text-align: right;"><b>'.number_format($Grand_Total_Cash).'</b></td>
				<td style="text-align: right;"><b>'.number_format($Grand_Total_Credit).'</b></td>
				<td style="text-align: right;"><b>'.number_format($Grand_Total_Cancelled).'</b></td>
				<td style="text-align: right;"><b>'.number_format($Grand_Total_Cash + $Grand_Total_Credit).'</b></td>
			</tr>
			<tr><td colspan="6" style="text-align: left;"><b>NUMBER OF DAYS : '.$no_of_days.'</b></td></tr>
		</table>';

	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
?>