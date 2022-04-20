<?php
    include("./includes/connection.php");
    
    $Registration_ID = '';
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['folio'])){
        $folio = $_GET['folio'];
    }else{
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    $Registration_ID = '';
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['Registration_ID'])){
	$Select_Patient = "select First_Name, Second_Name, Last_Name, Hospital_Ward_Name,Folio_Number
        from tbl_patient_registration pr,tbl_Hospital_Ward hw,tbl_admission ad where pr.registration_id = '$Registration_ID'
        AND ad.registration_id = pr.registration_id AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID";
	$result = mysqli_query($conn,$Select_Patient);
	$row = mysqli_fetch_array($result);
	$patient_name = ucfirst($row['First_Name'])." ".ucfirst($row['Second_Name'])." ".ucfirst($row['Last_Name']);
        //$folio = $row['Folio_Number'];
        $Hospital_Ward_Name = $row['Hospital_Ward_Name'];
    }else{
	$patient_name ='';
    }
    
    $pdf = "<table width ='100%' height = '30px'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr></table>";
    $pdf.="<table width ='100%'>
    <tr>
        <td><center><b>IPD CASH BILL</center></b></td>
    </tr>
    <tr>
        <td><span style='font-size: x-small;'><b>Patient :</b> $patient_name</span></td>
    </tr>
    <tr>
        <td><span style='font-size: x-small;'><b>Folio No :</b> $folio</span></td>
    </tr>
    <tr>
        <td><span style='font-size: x-small;'><b>Ward :</b> $Hospital_Ward_Name</span></td>
    </tr>
    <tr>
    <td><hr></td>
    </tr>
    </table>";
    $pdf.= '<center><table width =100% border=0>';
    $pdf.= '<tr><td width=5%>
		<span style="font-size: x-small;"><b>SN</b></span>
		</td><td width="10%"><span style="font-size: x-small;"><b>Date</b></span></td>
			<td width="10%"><span style="font-size: x-small;"><b>RECEIPT</b></span></td>
			<td width="10%" style="text-align: right;">
			<span style="font-size: x-small;"><b>CANCELED</b></span></td>
                        <td width="15%" style="text-align: right;">
			<span style="font-size: x-small;">
			<b>BILLED</b></span></td>
			    <td width="15%" style="text-align: right;">
			    <span style="font-size: x-small;">
			    <b>PAID</b></span></td>
                            <td width="15%" style="text-align: right;">
			    <span style="font-size: x-small;">
			    <b>BALANCE</b></span></td>
				</tr>';
    $Select_payments = "SELECT Payment_Date_And_Time, Patient_Payment_ID AS receipt_number,Transaction_status,Transaction_type,
                        (SELECT SUM((price-Discount)*Quantity) FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID = receipt_number) AS Amount
                        FROM tbl_patient_payments WHERE Registration_ID=$Registration_ID AND Folio_Number = $folio
			AND (Billing_Type = 'Outpatient Cash' OR Billing_Type = 'Inpatient Cash')";
    $results = mysqli_query($conn,$Select_payments);
    $temp=1;
    $amount_billed = 0;
    $amount_paid = 0;
    $amount = 0;
    $ballance = 0;
$inactive_amount = 0;
    $inactive_sum = 0;
    while($row = mysqli_fetch_array($results)){
        if($row['Transaction_status']=='active'){
	    if($row['Transaction_type'] == 'Direct cash'){
	     $amount_paid+= $row['Amount'];
	     $amount_p = $row['Amount'];
	     $amount_b =0;
	    }else{
	     $amount_billed+= $row['Amount'];
	     $amount_b = $row['Amount'];
	     $amount_p =0;
	     $inactive_amount = 0;
	    }
	}else{
	    $inactive_amount = $row['Amount'];
	    $inactive_sum+=$inactive_amount;
	    $amount_p =0;
	    $amount_b =0;
	}
        $ballance = $amount_billed-$amount_paid;
	$pdf.= "<tr><td colspan=7><hr height = '3px'></td></tr>";
	$pdf.= '<tr><td><span style="font-size: x-small;">'.$temp.'</span></td>';
        $pdf.= "<td><span style='font-size: x-small;'>".$row['Payment_Date_And_Time']."</span></td>";
        $pdf.= "<td style='text-align: center'><span style='font-size: x-small;'>".$row['receipt_number']."</span></td>";
	$pdf.= "<td style='text-align: right'><span style='font-size: x-small;'>".number_format($inactive_amount)."</span></td>";
        $pdf.= "<td style='text-align: right'><span style='font-size: x-small;'>".number_format($amount_b)."</span></td>";
        $pdf.= "<td style='text-align: right'><span style='font-size: x-small;'>".number_format($amount_p)."</span></td>";
        $pdf.= "<td style='text-align: right'><span style='font-size: x-small;'>".number_format($ballance)."</span></td>";
	$pdf.= "</tr>";
        $temp++;
    }
    
    $pdf.= "<tr><td colspan=7><hr></td></tr>";
    $pdf.= "<tr><td colspan=3 style='text-align: right;'><b> TOTAL :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
    <td style='text-align: right;'><span style='font-size: x-small;'><b>".number_format($inactive_sum)."</b></span></td>
    <td style='text-align: right;'><span style='font-size: x-small;'><b>".number_format($amount_billed)."</b></span></td>
    <td style='text-align: right;'><span style='font-size: x-small;'><b>".number_format($amount_paid)."</b></span></td>
    <td style='text-align: right;'><span style='font-size: x-small;'><b>".number_format($ballance)."</b></span></td></tr>";
    $pdf.= "<tr><td colspan=7><hr></td></tr></table></center>";
    
    
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 

    $mpdf->WriteHTML($pdf);
    $mpdf->Output();
?>