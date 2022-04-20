<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
	  $temp = 1;
    $total = 0;
    $Title = '';
	
    $Branch = $_GET['Branch']; 
    $Date_From = $_GET['date_From'];
    $Date_To = $_GET['date_To'];
    $Insurance = $_GET['Insurance'];
    $Payment_Type = $_GET['Payment_Type'];


	//CREATE FILTER
	if($Employee_ID != 0){
		$filter .= " pp.Employee_ID = '$Employee_ID' and";
	}

	if($Sponsor_ID != 0){
		$filter .= " pp.Sponsor_ID = '$Sponsor_ID' and";
	}
	$htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>REVENUE COLLECTION BY FOLIO NUMBER</b></td></tr>
                <tr><td style="text-align:center"><b>SPONSOR : '.$Insurance.'</b></td></tr>
                <tr><td style="text-align:center"><b>START DATE : '.date("d-m-Y", strtotime($Date_From)).'</b></td></tr>
                <tr><td style="text-align:center"><b>END DATE : '.date("d-m-Y", strtotime($Date_To)).'</b></td></tr>
                <tr><td style="text-align:center"><b>'.$Employees.'</b></td></tr>
            </table>';
        
            $select_Filtered_Patients = "SELECT pr.Patient_Name, pr.Registration_ID, sp.Guarantor_Name, pp.Folio_Number,PP.Patient_Bill_ID, pp.Billing_Process_Status, pp.Billing_Process_Employee_ID 
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
                where pp.patient_payment_id = ppl.patient_payment_id and
                pp.registration_id = pr.registration_id and
                pp.receipt_date between '$Date_From' and '$Date_To' and
                sp.sponsor_id = pp.sponsor_id and
                pp.Bill_ID IS NULL and
		pp.Transaction_status <> 'cancelled' and
                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') and              
                pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
                pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
                GROUP BY pr.Registration_ID, pp.Folio_Number, PP.Patient_Bill_ID order by pp.Folio_Number ";
                    
                    
                    
    $htm.= '<table width =100% border=1>';
    $htm.= '<tr id="thead"><td width=5%><b>SN</b></td><td width="25%"><b>Patient Name</b></td>
                <td width="7%" style="text-align: right;"><b>Patient#</b></td>
                <td width="8%" style="text-align: right;"><b>Folio Number</b></td>
                <td width="9%" style="text-align: center;"><b>Sponsor</b></td>
                <td width="15%" style="text-align: center;"><b>First Served Date</b></td>
                <td width="15%" style="text-align: right;"><b>Amount</b></td>
			</tr>';
      
    $results = mysqli_query($conn,$select_Filtered_Patients);
    while($row = mysqli_fetch_array($results)){
    	//get first served date
        $Folio_Number = $row['Folio_Number'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Patient_Bill_ID = $row['Patient_Bill_ID'];
        $Registration_ID = $row['Registration_ID'];
        $first_saved_date = mysqli_query($conn,"SELECT Payment_Date_And_Time from  tbl_patient_payments
					    where Folio_Number = '$Folio_Number' and
					    Patient_Bill_ID = '$Patient_Bill_ID' and
						Sponsor_ID = (select sponsor_id from tbl_sponsor 
						where guarantor_name = '$Guarantor_Name' limit 1) 
        				and registration_ID = '$Registration_ID'
						AND receipt_date between '$Date_From' 
						and '$Date_To'
						order by patient_payment_id asc limit 1") or 
        die(mysqli_error($conn));
        $num2 = mysqli_num_rows($first_saved_date);
        if($num2 > 0){
            while($num_rows = mysqli_fetch_array($first_saved_date)){
            	$Payment_Date_And_Time = $num_rows['Payment_Date_And_Time'];
        	}

        }else{
            $Payment_Date_And_Time = '';
        }
     
        $htm.= '<tr><td>'.$temp.'</td>';
        $htm.= "<td>".ucfirst($row['Patient_Name'])."</td>";
        $htm.= "<td style='text-align: right;'>".$row['Registration_ID']."</td>";
        $htm.= "<td style='text-align: right;'><a href='foliosummaryreport.php?Folio_Number=".$row['Folio_Number']."&Insurance=".$row['Guarantor_Name']."&Registration_ID=".$row['Registration_ID']."&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Folio_Number']."</a></td>";
        $htm.= "<td style='text-align: center;'>".$row['Guarantor_Name']."</td>";
$htm.= "<td style='text-align: center;'>".$Payment_Date_And_Time."</td>";
	if($row['Billing_Process_Status'] == 'Approved'){
	    $Billing_Process_Employee_ID = $row['Billing_Process_Employee_ID'];
	    //get employee Name
	    $select_employee = mysqli_query($conn,"SELECT Employee_Name from tbl_Employee where Employee_ID = '$Billing_Process_Employee_ID'") or die(mysqli_error($conn));
	    while($select_emp = mysqli_fetch_array($select_employee)){
            $Employee_Name = $select_emp['Employee_Name'];
	    }
	  
	   
	}else{
	}
	
	//generate amount based on folio number
	$select_total = mysqli_query($conn,"SELECT sum((price - discount)*quantity) as Amount from
					tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr where
					pp.patient_payment_id = ppl.patient_payment_id and
					pr.registration_id = pp.registration_id and
					pp.Folio_Number = '$Folio_Number' and
					pp.Patient_Bill_ID = '$Patient_Bill_ID' and
					pp.Bill_ID IS NULL and
					pp.Transaction_status <> 'cancelled' and
					pp.Registration_ID = '$Registration_ID' and
					(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') 
					AND pp.receipt_date between '$Date_From' and '$Date_To' 
					AND pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Insurance' limit 1)") or die(mysqli_error($conn));
	
	$num_rows = mysqli_num_rows($select_total);
	if($num_rows > 0){
	    while($dt = mysqli_fetch_array($select_total)){
		$Amount = $dt['Amount'];
	    }
	}else{
	    $Amount = 0;
	}
	$htm.= "<td style='text-align: right;'>".number_format($Amount)."</td>";
	$htm.= "</tr>";
	$total = $total + $Amount;
	$temp++;
    }
    //$htm.= "<tr><td colspan='7'><hr></td></tr>";
    $htm.= "<tr><td colspan=7 style='text-align: right;'><b> TOTAL : ".number_format($total)."</td></tr>";
   // $htm.= "<tr><td colspan=7 ><hr></td></tr>";
$htm.="</table>";

	

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