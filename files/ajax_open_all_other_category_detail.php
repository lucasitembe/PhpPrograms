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
if(isset($_POST['Item_report_category'])){
   $Item_report_category=$_POST['Item_report_category']; 
}else{
   $Item_report_category=""; 
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <table class="table">
                <tr>
                    <th width="30%">ITEM NAME</th>
                    <th>CASH</th>
                    <th>CREDIT</th>
                    <th>MSAMAHA</th>
                    <th>TOTAL</th>
                </tr>
                <tbody>
                    <?php 
                        $grand_total_cash=0;
                        $grand_total_credit=0;
                        $grand_total_msamaha=0;
                        
                            //select all item under this category
                        $sql_select_all_items_under_this_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Status='Available' AND revenue_report_category='$Item_report_category' AND Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND finance_department_id='$finance_department_id')") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_all_items_under_this_category_result)>0){
                            while($category_items_rows=mysqli_fetch_assoc($sql_select_all_items_under_this_category_result)){
                                $Product_Name=$category_items_rows['Product_Name'];
                                $Item_ID=$category_items_rows['Item_ID'];
                                $ubtotal_cash=0;
                                $ubtotal_credit=0;
                                $subtotal_msamaha=0;
                            
                                   $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT ppl.Sub_Department_ID,pp.payment_type,Billing_Type,Pre_Paid,Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));
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
                                    $grand_total_cash+=$ubtotal_cash;
                                    $grand_total_credit+=$ubtotal_credit;
                                    $grand_total_msamaha+=$subtotal_msamaha;
                               echo "<tr>
                                        <td class='department_row'><b>".strtoupper($Product_Name)."</b></td>
                                        <td>".number_format($ubtotal_cash)."</td>
                                        <td>".number_format($ubtotal_credit)."</td>
                                        <td>".number_format($subtotal_msamaha)."</td>
                                        <td>".number_format($ubtotal_cash+$ubtotal_credit)."</td>
                                    </tr>";
                            }
                        }
                        if($Item_report_category=="Others"){
                            $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT ppl.Item_Name,ppl.Sub_Department_ID,pp.payment_type,Billing_Type,Pre_Paid,Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_all_items_with_price_result)>0){

                                       while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                                          $payment_type=$items_rows['payment_type'];
                                          $Billing_Type=$items_rows['Billing_Type'];
                                          $Pre_Paid=$items_rows['Pre_Paid'];
                                          $Sponsor_ID=$items_rows['Sponsor_ID'];
                                          $Discount=$items_rows['Discount'];
                                          $Price=$items_rows['Price'];
                                          $Quantity=$items_rows['Quantity'];
                                          $Item_Name=$items_rows['Item_Name'];
                                          
                                    $ubtotal_cash=$Price*$Quantity;
                                    $grand_total_cash+=$ubtotal_cash;
                                    $grand_total_credit+=$ubtotal_credit;
                                    $grand_total_msamaha+=$subtotal_msamaha;
                               echo "<tr>
                                        <td class='department_row'><b>".strtoupper($Item_Name)."</b></td>
                                        <td>".number_format($ubtotal_cash)."</td>
                                        <td>".number_format($ubtotal_credit)."</td>
                                        <td>".number_format($subtotal_msamaha)."</td>
                                        <td>".number_format($ubtotal_cash+$ubtotal_credit)."</td>
                                       </tr>";
                                }
                                       
                            }
                        }
                     ?>
                </tbody>
                <tr>
                    <td><b>GRAND TOTAL</b></td>
                    <td><b><?= number_format($grand_total_cash) ?></b></td>
                    <td><b><?= number_format($grand_total_credit) ?></b></td>
                    <td><b><?= number_format($grand_total_msamaha) ?></b></td>
                    <td><b><?= number_format($grand_total_cash+$grand_total_credit) ?></b></td>
                </tr>
            </table>
        </div>
    </div>
</div>
