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
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <table class="table">
                <tr>
                    <th width="30%">LABORATORY CATEGORY</th>
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
                        
                        //Lab subcategory
                                 $query_sub_cat = mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID WHERE i.`Consultation_Type`='Laboratory' AND revenue_report_category='Laboratory'  AND Item_ID IN(SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND finance_department_id='$finance_department_id') GROUP BY its.Item_Subcategory_ID ") or die(mysqli_error($conn));

                                if(mysqli_num_rows($query_sub_cat)>0){
                                   while ($row = mysqli_fetch_array($query_sub_cat)) {
                                     $Item_Subcategory_ID=$row['Item_Subcategory_ID'];
                                     $Item_Subcategory_Name=$row['Item_Subcategory_Name'];
                                
                               
                                $ubtotal_cash=0;
                                $ubtotal_credit=0;
                                $subtotal_msamaha=0;
                            ////calculate amount from each unit
                            //select all items
                                   $sql_select_all_items_with_price_result=mysqli_query($conn,"SELECT ppl.Sub_Department_ID,pp.payment_type,Billing_Type,Pre_Paid,Sponsor_ID,Discount,Price,Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND ppl.finance_department_id='$finance_department_id' AND Item_ID IN(SELECT Item_ID FROM tbl_items WHERE Item_Subcategory_ID='$Item_Subcategory_ID' AND revenue_report_category='Laboratory') AND ppl.Check_In_Type='Laboratory'") or die(mysqli_error($conn));
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
                                        <td class='department_row' onclick='open_selected_laboratory_detail($Item_Subcategory_ID,$finance_department_id,\"$finance_department_name\",\"$Item_Subcategory_Name\")'><b>".strtoupper($Item_Subcategory_Name)."</b></td>
                                        <td>".number_format($ubtotal_cash)."</td>
                                        <td>".number_format($ubtotal_credit)."</td>
                                        <td>".number_format($subtotal_msamaha)."</td>
                                        <td>".number_format($ubtotal_cash+$ubtotal_credit)."</td>
                                    </tr>";
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
