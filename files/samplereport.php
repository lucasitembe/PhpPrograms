<?php
    @session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
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
    }
    
    
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        $Patient_Payment_ID = 0;
    }
    $total = 0;
    $html = '<style>
	    @page{
		margin-top:20px;
		margin-left:0;
	    }
	    </style>
	    <center><img src="./branchBanner/branchBanner.png">
		<br/></center>'; 
    $html .= '<center><table width="100%">';
    $select_Transaction_Items = mysqli_query($conn,"select * from tbl_employee emp, tbl_patient_registration preg, tbl_patient_payments pp, tbl_patient_payment_item_list ppl
		where emp.employee_id = pp.employee_id and
		    preg.registration_id = pp.registration_id and
			pp.patient_payment_id = ppl.patient_payment_id and
			    pp.Patient_Payment_ID = '$Patient_Payment_ID' limit 1"); 
    $html .= '<tr><td colspan=4><center><b>Sales Receipt</b></center></td></tr>';
    while($row = mysqli_fetch_array($select_Transaction_Items)){ 
	$html .='<tr>
		    <td><b>Patient Name : </b></td>
		    <td>'.$row['First_Name']." ".$row['Middle_Name']." ".$row['Last_Name'].'</td>';
	$html .='<td><b>Registration Number : </b></td>
		    <td>'.$row['Registration_ID'].'</td></tr>'; 
	$html .='<tr>
		    <td><b>Folio Number : </b></td>
		    <td>'.$row['Folio_Number'].'</td>';
	$html .='<td><b>Claim Form Number : </b></td>
		    <td>'.$row['Claim_Form_Number'].'</td></tr>';
	$html .='<tr>
		<td><b>Sponsor Name : </b></td>
		    <td>'.$row['Sponsor_Name'].'</td>';
	$html .='<td><b>Receipt Number : </b></td>
		    <td>'.$row['Patient_Payment_ID'].'</td></tr>';
	
    }
    
    $html .= '</table></center><br/>'; 
    $html .= '<hr>';
    

    $select_Transaction_Items = mysqli_query($conn,
            "select * from tbl_employee emp, tbl_patient_registration preg, tbl_patient_payments pp, tbl_patient_payment_item_list ppl
		where emp.employee_id = pp.employee_id and
		    preg.registration_id = pp.registration_id and
			pp.patient_payment_id = ppl.patient_payment_id and
			    pp.Patient_Payment_ID = '$Patient_Payment_ID'"); 

    $html .= '<center><table width ="100%" border=0>';	    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
	$html .= "<tr><td><b>Check In Type : </b></td>";
        $html .= "<td style='text-align: left;'>".$row['Check_In_Type']."</td>";
	
	$html .= "<td><b>Location : </b></td>";
        $html .= "<td style='text-align: left;'>".$row['Patient_Direction']."</td>";
	
	$html .= "<td><b>Item / Service : </b></td>";
        $html .= "<td style='text-align: left;'>".$row['Item_Name']."</td></tr>";
	
	$html .= "<tr><td><b>Price : </b></td>";
        $html .= "<td style='text-align: left;'>".$row['Price']."</td>";
	
	$html .= "<td><b>Discount : </b></td>";
        $html .= "<td style='text-align: left;'>".$row['Discount']."</td>";
	
	$html .= "<td><b>Quantity : </b></td>";
        $html .= "<td style='text-align: left;'>".$row['Quantity']."</td></tr>";
	
        $html .= "<tr><td colspan=6 style='text-align: right;'> <b><i>Sub Total : ".number_format(($row['Price'] - $row['Discount'])*$row['Quantity']);
        $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity'])."</i>";
	$html .= "<tr><td colspan=6><hr></td></tr>";
    } 
        $html .= "<tr><td style='text-align: right; text-decoration: underline;' colspan=7><b> TOTAL : ".number_format($total)."</b></td></tr>";
?>

<?php
    $html .= "</table></center>";


  
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 

    $mpdf->WriteHTML($html);
    $mpdf->Output();
    exit;
?>