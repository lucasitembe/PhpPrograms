<?php
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
if(isset($_POST['Check_In_Type'])){
   $Check_In_Type=$_POST['Check_In_Type']; 
}else{
   $Check_In_Type=""; 
}
if(isset($_POST['finance_department_id'])){
   $finance_department_id=$_POST['finance_department_id']; 
$sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_name FROM tbl_finance_department WHERE finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
$finance_department_name = mysqli_fetch_assoc($sql_select_working_department_result)['finance_department_name'];
}else{
   $finance_department_id=""; 
}
?>
<center>
    <table class="table" style="width:50%!important;text-align: center;background: #FFFFFF">
        <tr>
            <td colspan="4"><b><?= strtoupper($Check_In_Type) ?> - COLLECTIONS FROM <?= strtoupper($finance_department_name) ?></b></td>
        </tr>
        <tr>
            <td><b>FROM:</b></td>
            <td><b><?= $start_date ?></b></td>
            <td><b>TO:</b></td>
            <td><b><?= $end_date ?></b></td>
        </tr>
    </table>
</center>
 <div class="box box-primary" style="height:400px;overflow-y: scroll;overflow-x: auto">
    <table class="table table-hover">
        <tr>
            <td width="5%"><b>S/No.</b></td>
            <td><b>FINANCE DEPARTMENT</b></td>
            <td style='text-align: right;'><b>TOTAL</b></td>
        </tr>
        <tbody>
            <?php 
                    $count=1;
                    $pharmacy_grand_total=0;

                           $sub_department_subtotal_per_clinic=0;

                           $sql_select_department_result=mysqli_query($conn,"SELECT Sub_Department_ID, Sub_Department_Name FROM tbl_sub_department sd") or die(mysqli_error($conn));
                            // $sql_select_department_result = mysqli_query($conn, "SELECT ppl.Sub_Department_ID, sd.Sub_Department_Name FROM tbl_sub_department sd, tbl_patient_payment_item_list ppl WHERE sd.Sub_Department_ID = ppl.Sub_Department_ID AND ppl.Check_In_Type='Doctor Room' AND ppl.finance_department_id='1' AND ppl.Transaction_Date_And_Time BETWEEN '2021/10/01 00:00' AND '2021/12/31 23:59' AND Patient_Payment_ID IS NOT NULL GROUP BY ppl.Sub_Department_ID ") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_department_result)>0){
                                while($sub_d_rows=mysqli_fetch_assoc($sql_select_department_result)){
                                    $Sub_Department_ID=$sub_d_rows['Sub_Department_ID'];
                                    $Sub_Department_Name=$sub_d_rows['Sub_Department_Name'];
                                    //////////////////////////////////////////////
                                    $sub_department_subtotal=0;


                                    $sql_select_amount_result=mysqli_query($conn,"SELECT Price, Check_In_Type, Quantity, Discount FROM tbl_patient_payment_item_list WHERE finance_department_id='$finance_department_id' AND Sub_Department_ID = '$Sub_Department_ID' AND  Check_In_Type = '$Check_In_Type' AND Patient_Payment_ID IN(SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Pre_Paid = '0' AND Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Transaction_status <> 'cancelled')") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_amount_result)>0){

                                    echo "<tr><td>$count.</td><td>$Sub_Department_Name</td>";

                                             while($amount_rows=mysqli_fetch_assoc($sql_select_amount_result)){
                                                 $Price=$amount_rows['Price'];
                                                //  $Check_In_Type=$amount_rows['Check_In_Type'];
                                                 $Quantity=$amount_rows['Quantity'];
                                                 $Discount=$amount_rows['Discount'];


                                                // if($Check_In_Type=='Pharmacy'){
                                                    $sub_department_subtotal+=($Price*$Quantity); 
                                                // }
                                                 

                                             }
                                        $sub_department_subtotal_per_clinic+=$sub_department_subtotal;

                           $count++;
                           echo "
                           <td class='rows_list' style='text-align: right;'>".number_format($sub_department_subtotal)."</td>";
                                        }
                                        
                                    /////////////////////////////////////
                                }

                                // echo "<td>".number_format($sub_department_subtotal_per_clinic)."</td>";
                            }
                           echo "</tr>";                       

                    
                                ?>
                    <tr>
                        <td colspan="2"><b>GRAND TOTAL</b></td>                       
                      <td class='rows_list' style='text-align: right;'><b><?= number_format($sub_department_subtotal_per_clinic) ?></b></td>
                    </tr>
        </tbody>
    </table>
</div>