<?php
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
include_once("./includes/connection.php");
if(isset($_POST['start_date'])){
    $start_date=$_POST['start_date'];
}else{
    $start_date=""; 
}
if(isset($_POST['end_date'])){
   $end_date=$_POST['end_date']; 
}else{
   $end_date=""; 
}

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
            $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT Transaction_status,pp.payment_type,Billing_Type,Pre_Paid,Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_all_items_with_price_result)>0){
                
               while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                  $payment_type=$items_rows['payment_type'];
                  $Billing_Type=$items_rows['Billing_Type'];
                  $Pre_Paid=$items_rows['Pre_Paid'];
                  $Sponsor_ID=$items_rows['Sponsor_ID'];
                  $Discount=$items_rows['Discount'];
                  $Price=$items_rows['Price'];
                  $Quantity=$items_rows['Quantity'];
                  
                   
                   $sql_check_if_exempted_result=mysqli_query($conn,"SELECT Exemption FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                   $Exemption=mysqli_fetch_assoc($sql_check_if_exempted_result)['Exemption'];
///////////////////////////////////////////////////////////////
               	
                    
                    if(($Exemption=='yes') && ((strtolower($Billing_Type) == 'outpatient credit') || (strtolower($Billing_Type) == 'inpatient credit'))&& strtolower($Transaction_status) != 'cancelled'){
	                    $subtotal_msamaha += ($Quantity*($Price-$Discount));
                            $number_of_service++;
                    }  else {
                      	
			if(((strtolower($Billing_Type) == 'outpatient cash' && $Pre_Paid == '0') || (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre') ||strtolower($Billing_Type) =="patient from outside")&& strtolower($Transaction_status) != 'cancelled'){
			    $ubtotal_cash+=($Quantity*($Price-$Discount));
                            $number_of_service++;
                        }elseif(((strtolower($Billing_Type) == 'outpatient credit') || (strtolower($Billing_Type) == 'inpatient credit'))&& strtolower($Transaction_status) != 'cancelled' || (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post') || (strtolower($Billing_Type) == 'outpatient cash' && $Pre_Paid == '1')){
                            $ubtotal_credit += ($Quantity*($Price-$Discount));
                            $number_of_service++;
                        }
                    }
               ///////////////////////////////////////////////////////////////
               }
            }
            //select number of patient
            $number_of_patient=0;
            $sql_select_number_of_patient_result=mysqli_query($conn,"SELECT Registration_ID AS number_of_patient FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' GROUP BY Registration_ID") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_number_of_patient_result)>0){
//                $number_of_patient=mysqli_fetch_assoc($sql_select_number_of_patient_result)['number_of_patient'];
                $number_of_patient=mysqli_num_rows($sql_select_number_of_patient_result);
               //die($number_of_patient);
            }
            $grand_total_no_of_service+=$number_of_service;
            $grand_total_cash+=$ubtotal_cash;
            $grand_total_credit+=$ubtotal_credit;
            $grand_total_msamaha+=$subtotal_msamaha;
            echo "<tr class='rows_list'>
                    <td>$cout_sn.</td>
                    <td class='department_row' onclick='open_selected_department_details(\"$finance_department_name\",$finance_department_id)'><b>".strtoupper($finance_department_name)."--->$finance_department_code</b></td>
                    <td>".number_format($number_of_service)."</td>
                    <td>".number_format($number_of_patient)."</td>
                    <td>".number_format($ubtotal_cash)."</td>
                    <td>".number_format($ubtotal_credit)."</td>
                    <td>".number_format($subtotal_msamaha)."</td>
                    <td>".number_format($ubtotal_cash+$ubtotal_credit)."</td>
                </tr>";
            $cout_sn++;
        }
        echo "<tr>
                <td colspan='2'><b>GRAND TOTAL</b></td>
                <td><b>". number_format($grand_total_no_of_service)."</b></td>
                <td><b></b></td>
                <td><b>". number_format($grand_total_cash)."</b></td>
                <td><b>". number_format($grand_total_credit)."</b></td>
                <td><b>". number_format($grand_total_msamaha)."</b></td>
                <td><b>". number_format($grand_total_cash+$grand_total_credit)."</b></td>
            </tr>";
    }