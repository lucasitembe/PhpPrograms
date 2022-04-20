<?php

@session_start();
include("./includes/connection.php");
include("./mpdf/mpdf.php");
$temp = 1;
$total = 0;
$Title = '';

$Branch = $_GET['Branch'];
$Date_From = $_GET['date_From'];
$Date_To = $_GET['date_To'];
$Insurance = $_GET['Insurance'];
$Payment_Type = $_GET['Payment_Type'];



$select_Filtered_Patients = "
                select pr.Patient_Name, pr.Registration_ID,pr.Member_Number, sp.Guarantor_Name, pp.Folio_Number,PP.Patient_Bill_ID, pp.Billing_Process_Status, pp.Billing_Process_Employee_ID 
                from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp 
                where pp.patient_payment_id = ppl.patient_payment_id and
                pp.registration_id = pr.registration_id and
                pp.receipt_date between '$Date_From' and '$Date_To' and
                sp.sponsor_id = pp.sponsor_id and
                pp.Bill_ID = '0' and
				pp.Transaction_status <> 'cancelled' and
                (pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') and              
                pp.sponsor_id = (select sponsor_id from tbl_sponsor where Guarantor_Name = '$Insurance') and
                pp.branch_id = (select branch_id from tbl_branches where branch_name = '$Branch')
                GROUP BY pr.Registration_ID, pp.Folio_Number, PP.Patient_Bill_ID order by pp.Folio_Number ";

$htm = "<table width ='100%'>
		<tr>
		    <td colspan='3'>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		    <tr>
		    <td width='20%'></td>
		    <td>
		    <center>
		    <span style='font-size: small;'><b>" . strtoupper($Insurance) . " CONFIDENTIAL<br>
		    HEALTH PROVIDER IN / OUT PATIENT CLAIM FORM</b></span>
		    </center>
		    </td>
                    
		    <td width='20%' style='text-align: right'>
		    <span style='font-size: x-small;'>Form 2A&B<br>
		    Regulation 18(1)</span>
		    </td>
                    </tr>
                    <tr>
                    <td colspan='3' style='text-align: center;font-size: small;'><b>FROM $Date_From TO $Date_To</b></td>
                    </tr>
                    </table>";

$htm .= '<br/><center><table width =100% border=0>';
$htm .= '<tr id="thead"><td width=5%><b>SN</b></td><td width="25%"><b>Patient Name</b></td>
                <td width="7%" style="text-align: right;"><b>Patient#</b></td>
                <td width="9%" style="text-align: center;"><b>Member Number</b></td>
                <td width="8%" style="text-align: right;"><b>Folio Number</b></td>
                <td width="15%" style="text-align: right;"><b>Amount</b></td>
			</tr>';

$results = mysqli_query($conn,$select_Filtered_Patients);
while ($row = mysqli_fetch_array($results)) {
    //get first served date
    $Folio_Number = $row['Folio_Number'];
    $Guarantor_Name = $row['Guarantor_Name'];
    $Member_Number = $row['Member_Number'];
    $Patient_Bill_ID = $row['Patient_Bill_ID'];
    $Registration_ID = $row['Registration_ID'];

    $htm .= '<tr><td>' . $temp . '</td>';
    $htm .= "<td>" . ucfirst($row['Patient_Name']) . "</td>";
    $htm .= "<td style='text-align: right;'>" . $row['Registration_ID'] . "</td>";
    $htm .= "<td style='text-align: center;'>" . $Member_Number . "</td>";
    $htm .= "<td style='text-align: right;'>" . $row['Folio_Number'] . "</a></td>";


    //generate amount based on folio number
    $select_total = mysqli_query($conn,"select sum((price - discount)*quantity) as Amount from
					tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr where
					pp.patient_payment_id = ppl.patient_payment_id and
					pr.registration_id = pp.registration_id and
					pp.Folio_Number = '$Folio_Number' and
					pp.Patient_Bill_ID = '$Patient_Bill_ID' and
					pp.Bill_ID = '0' and
					pp.Transaction_status <> 'cancelled' and
					pp.Registration_ID = '$Registration_ID' and
					(pp.Billing_Type = 'Inpatient Credit' or pp.Billing_Type = 'Outpatient Credit') and
					pp.Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Insurance' limit 1)") or die(mysqli_error($conn));

    $num_rows = mysqli_num_rows($select_total);
    if ($num_rows > 0) {
        while ($dt = mysqli_fetch_array($select_total)) {
            $Amount = $dt['Amount'];
        }
    } else {
        $Amount = 0;
    }
    $htm .= "<td style='text-align: right;'>" . number_format($Amount) . "</td>";
    $htm .= "</tr>";
    $total = $total + $Amount;
    $temp++;
}

$htm .= "<tr><td colspan=8><hr></td></tr>";
$htm .= "<tr><td colspan=8 style='text-align: right;'><b> TOTAL : " . number_format($total) . "</td></tr>";
$htm .= "<tr><td colspan=8 ><hr></td></tr>";
$htm .= "</table></center>";

echo $htm;
?>

<script>
	window.print(false);
	CheckWindowState();

	function PrintWindow() {                    
		window.print();            
		CheckWindowState();
	}

    function CheckWindowState()    {           
        if(document.readyState=="complete") {
            window.close(); 
        } else {           
            setTimeout("CheckWindowState()", 2000);
        }
    }
</script>
