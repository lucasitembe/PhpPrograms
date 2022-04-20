<?php
@session_start();
include("./includes/connection.php");
	$end_date = $_GET['end_date'];
	$start_date = $_GET['start_date'];
       
    $htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>REVENUE COLLECTION REPORT</b></td></tr>
                <tr><td style="text-align:center"><b>START DATE : '.date("d-m-Y h:i:s", strtotime($start_date)).'</b></td></tr>
                <tr><td style="text-align:center"><b>END DATE : '.date("d-m-Y h:i:s", strtotime($end_date)).'</b></td></tr>
            </table>';
    
    
    
    	$htm .= '<table width=100% border=1 style="border-collapse: collapse;">
				<thead>
			    <tr>
			        <td width="5%"><b>SN</b></td>
			        <td width="30%"><b>DEPARTMENT NAME</b></td>
			        <td width="15%" style="text-align: left;"><b>NO OF SERVICE</b></td>
			        <td width="15%" style="text-align: right;"><b>CASH</b></td>
			        <td width="15%" style="text-align: right;"><b>CREDIT</b></td>
			        <td width="15%" style="text-align: right;"><b>MSAMAHA</b></td>
			        <td width="15%" style="text-align: right;"><b>TOTAL</b></td>
			    </tr></thead>';
        
        
$cout_sn=1;
$grand_total_no_of_service=0;
$grand_total_cash=0;
$grand_total_credit=0;
$grand_total_msamaha=0;
$sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_working_department_result)>0){
    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
       $finance_department_id=$finance_dep_rows['finance_department_id'];
       $finance_department_name=$finance_dep_rows['finance_department_name'];
       $finance_department_code=$finance_dep_rows['finance_department_code'];
       
            $number_of_service=0;
            $ubtotal_cash=0;
            $ubtotal_credit=0;
            $subtotal_msamaha=0;
            //select all items
            $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT pp.payment_type,Billing_Type,Pre_Paid,Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_all_items_with_price_result)>0){
                
               while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                  $payment_type=$items_rows['payment_type'];
                  $Billing_Type=$items_rows['Billing_Type'];
                  $Pre_Paid=$items_rows['Pre_Paid'];
                  $Sponsor_ID=$items_rows['Sponsor_ID'];
                  $Discount=$items_rows['Discount'];
                  $Price=$items_rows['Price'];
                  $Quantity=$items_rows['Quantity'];
                  
                   $number_of_service++;
                   $sql_check_if_exempted_result=mysqli_query($conn,"SELECT Exemption FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                   $Exemption=mysqli_fetch_assoc($sql_check_if_exempted_result)['Exemption'];
///////////////////////////////////////////////////////////////
               	
                    
                    if(($Exemption=='yes') && ((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit'))){
	                    $subtotal_msamaha += ($Quantity*$Price);
                    }  else {
                      	
			if((strtolower($Billing_Type) == 'outpatient cash' && $Pre_Paid == '0') or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
			    $ubtotal_cash+=($Quantity*$Price);
                        }elseif((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit')){
                            $ubtotal_credit += ($Quantity*$Price);
                        }
                    }
               ///////////////////////////////////////////////////////////////
               }
            }
            $grand_total_no_of_service+=$number_of_service;
            $grand_total_cash+=$ubtotal_cash;
            $grand_total_credit+=$ubtotal_credit;
            $grand_total_msamaha+=$subtotal_msamaha;
             $htm .= '<tr>
                            <td>'.$cout_sn.'</td>
                            <td><b>'.strtoupper($finance_department_name).'--->'.$finance_department_code.'</b></td>
                            <td>'.number_format($number_of_service).'</td>
                            <td>'.number_format($ubtotal_cash).'</td>
                            <td>'.number_format($ubtotal_credit).'</td>
                            <td>'.number_format($subtotal_msamaha).'</td>
                            <td>'.number_format($ubtotal_cash+$ubtotal_credit).'</td>
                        </tr>';
                    $cout_sn++;
        }
         $htm .= '<tr>
                <td colspan="2"><b>GRAND TOTAL</b></td>
                <td><b>'. number_format($grand_total_no_of_service).'</b></td>
                <td><b>'. number_format($grand_total_cash).'</b></td>
                <td><b>'. number_format($grand_total_credit).'</b></td>
                <td><b>'. number_format($grand_total_msamaha).'</b></td>
                <td><b>'. number_format($grand_total_cash+$grand_total_credit).'</b></td>
            </tr>';
    }
    
    
    $htm .= "</table>";
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