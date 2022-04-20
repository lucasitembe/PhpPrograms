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
if(isset($_POST['finance_department_id'])){
   $finance_department_id=$_POST['finance_department_id']; 
}else{
   $finance_department_id=""; 
}
if(isset($_POST['finance_department_name'])){
   $finance_department_name=$_POST['finance_department_name']; 
}else{
   $finance_department_name=""; 
}
if(isset($_POST['Department_ID'])){
   $Department_ID=$_POST['Department_ID']; 
}else{
   $Department_ID=""; 
}
if(isset($_POST['Department_Name'])){
   $Department_Name=$_POST['Department_Name']; 
}else{
   $Department_Name=""; 
}
?>
<div id="selected_sub_dept_progess_div"></div>
<div class="box box-primary">
    <table class="table">
        <tr>
            <td style="width:50px">
               S/No. 
            </td>
            <td style="width:30%">
               SUB DEPARTMENT 
            </td>
            <td style="width:15%">
               NO OF SERVICE 
            </td>
            <td style="width:15%">
               NO OF PATIENT 
            </td>
            
            <td>
               CASH 
            </td>
            <td>
               CREDIT 
            </td>
            <td>
               MSAMAHA 
            </td>
            <td>
               TOTAL 
            </td>
        </tr>
<?php
            //GET List of department that has quantity
            $count_sn=1;
            $total_cash=0;
            $total_msamaha=0;
            $total_credit=0;
            $grand_total_cash=0;
            $grand_total_credit=0;
            $grand_total_msamaha=0;
            $sql_select_list_of_department_that_has_value_result=mysqli_query($conn,"SELECT Sub_Department_Name,Sub_Department_ID FROM tbl_sub_department WHERE Department_ID='$Department_ID' AND Sub_Department_ID IN(SELECT Sub_Department_ID FROM tbl_patient_payment_item_list ppl WHERE Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND finance_department_id='$finance_department_id' GROUP BY Sub_Department_ID)") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_list_of_department_that_has_value_result)>0){
                while($department_rows=mysqli_fetch_assoc($sql_select_list_of_department_that_has_value_result)){
                    $Sub_Department_Name=$department_rows['Sub_Department_Name'];
                    $Sub_Department_ID=$department_rows['Sub_Department_ID']; 
                    $total_cash=0;
                    $total_msamaha=0;
                    $total_credit=0;
                    $number_of_service=0;
            //select all items
            $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT ppl.Item_ID,pp.payment_type,Billing_Type,Pre_Paid,Discount,Price,Quantity,Exemption FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_sponsor sp WHERE sp.Sponsor_ID=pp.Sponsor_ID AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
            if($sql_select_all_items_with_price_result){
               while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                  $payment_type=$items_rows['payment_type'];
                  $Billing_Type=$items_rows['Billing_Type'];
                  $Pre_Paid=$items_rows['Pre_Paid'];
//                  $Sponsor_ID=$items_rows['Sponsor_ID'];
                  $Discount=$items_rows['Discount'];
                  $Price=$items_rows['Price'];
                  $Quantity=$items_rows['Quantity'];
//                  $Check_In_Type=$items_rows['Check_In_Type'];
                  $Item_ID=$items_rows['Item_ID'];    
                  $Exemption=$items_rows['Exemption'];    
                  
//                  $sql_check_if_exempted_result=mysqli_query($conn,"SELECT Exemption FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
//                  $Exemption=mysqli_fetch_assoc($sql_check_if_exempted_result)['Exemption'];
                    ///////////////////////////////////////////////////////////////
                    
                if(($Exemption=='yes') && ((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit'))){
                    $total_msamaha+=($Quantity*($Price-$Discount));
                    $number_of_service++;
                    }else{
                        
                    if(((strtolower($Billing_Type) == 'outpatient cash'|| strtolower($Billing_Type)=="patient from outside") && $Pre_Paid == '0') or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                        $total_cash+=($Quantity*($Price-$Discount));
                        $number_of_service++;
                    }elseif((strtolower($Billing_Type) == 'outpatient credit') or (strtolower($Billing_Type) == 'inpatient credit')){
                         $total_credit+= ($Quantity*($Price-$Discount)); 
                         $number_of_service++;
                    }
                }
               ///////////////////////////////////////////////////////////////
                }
            }
            
            $number_of_patient=0;
            $sql_select_number_of_patient_result=mysqli_query($conn,"SELECT Registration_ID AS number_of_patient FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND Sub_Department_ID='$Sub_Department_ID' GROUP BY Registration_ID") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_number_of_patient_result)>0){
                $number_of_patient=mysqli_num_rows($sql_select_number_of_patient_result);
            }
            echo "<tr>
                    <td>$count_sn</td>
                    <td class='department_row' onclick='open_sub_department_detail($Department_ID,$finance_department_id,$Sub_Department_ID,\"$finance_department_name\",\"$Department_Name\",\"$Sub_Department_Name\")'><b>".strtoupper($Sub_Department_Name)."</b></td>
                    <td>".number_format($number_of_service)."</td>
                    <td>".number_format($number_of_patient)."</td>
                    <td>".number_format($total_cash)."</td>
                    <td>".number_format($total_credit)."</td>
                    <td>".number_format($total_msamaha)."</td>
                    <td><b>".number_format($total_cash+$total_credit)."</b></td>
                 </tr>"; 
                $grand_total_cash+=$total_cash;
                $grand_total_credit+=$total_credit;
                $grand_total_msamaha+=$total_msamaha;
                $count_sn++;
                }
            }
            ?>
        <tr><td colspan="4"><b>GRAND TOTAL</b></td><td><b><?= number_format($grand_total_cash) ?></b></td><td><b><?= number_format($grand_total_credit) ?></b></td><td><b><?= number_format($grand_total_msamaha) ?></b></td><td><b><?= number_format($grand_total_credit+$grand_total_cash) ?></b></td></tr>    
            <?php
?>