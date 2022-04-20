<?php
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
include_once("./includes/connection.php");
if(isset($_POST['start_date'])){
    $start_date=$_POST['start_date'];
    $start_date = date_format(date_create($start_date),'Y-m-d');
}else{
    $start_date="";
}
if(isset($_POST['end_date'])){
   $end_date=$_POST['end_date'];
   $end_date = date_format(date_create($end_date),'Y-m-d');
}else{
   $end_date="";
}

if(isset($_POST['Patient_type'])){
    $Patient_type = $_POST['Patient_type'];
}else{
    $Patient_type = "";
}

$fileter = '';
if(isset($_POST['Sponsor_ID']) && !empty($_POST['Sponsor_ID'])){
   $Sponsor_ID=$_POST['Sponsor_ID'];
   $Sponsor_ID2=$_POST['Sponsor_ID'];
   $fileter = " AND pp.Sponsor_ID = '$Sponsor_ID'";

}else{
    $fileter = '';
    $Sponsor_ID = '';
    $Sponsor_ID2 ='';
}



$grand_total_cash=0;
$grand_total_credit=0;
$grand_total_msamaha=0;

//Lab subcategory
// $query_sub_cat = mysqli_query($conn, "SELECT Department_ID, Department_Name FROM tbl_department WHERE status='0'") or die(mysqli_error($conn));
$query_sub_cat = mysqli_query($conn, "SELECT Department_ID, Department_Name FROM tbl_department") or die(mysqli_error($conn));
         $count_sn=1;
if(mysqli_num_rows($query_sub_cat)>0){
   while ($row = mysqli_fetch_array($query_sub_cat)) {
     $Department_ID=$row['Department_ID'];
     $Department_Name=$row['Department_Name'];


?>
<tr class='rows_list'>
    <td><?php echo $count_sn; ?></td>
    <td class='department_row'><a href="revenue_collection_by_dep_excel.php?dep_name=<?=$Department_Name?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" target="_blank"><b><?php echo strtoupper($Department_Name); ?></b></a></td>
    <td colspan='7'>
        <table style="width: 100%;">
            <tr>

                <td style="width: 22%;"><a href="revenue_collection_by_dep_excel.php?dep_name=<?=$Department_Name?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" target="_blank"><b>SUB-DEPARTMENT</b></a></th>
                <td style='text-align:right;width: 13%;'><a href="revenue_collection_by_dep_excel.php?dep_name=<?=$Department_Name?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" target="_blank"><b>SERVICES</b></a></td>
                <td style='text-align:right;width: 13%;'><b>PATIENTS</b></td>
                <td style='text-align:right;width: 13%;'><b>CASH</b></td>
                <td style='text-align:right;width: 13%;'><b>CREDIT</b></td>
                <td style='text-align:right;width: 13%;'><b>MSAMAHA</b></td>
                <td style='text-align:right;width: 13%;'><b>TOTAL</b></td>
            </tr>
            <tbody>
            <?php
            $query_sub= mysqli_query($conn,"SELECT Sub_Department_ID,Sub_Department_Name FROM tbl_sub_department WHERE Department_ID='$Department_ID' AND Sub_Department_Status='active'") or die(mysqli_error($conn));
            if(mysqli_num_rows($query_sub)>0){
               while ($rowS = mysqli_fetch_array($query_sub)) {
                 $Sub_Department_ID = $rowS['Sub_Department_ID'];
                 $Sub_Department_Name = $rowS['Sub_Department_Name'];

                $subtotal_cash=0;
                $subtotal_credit=0;
                $subtotal_msamaha=0;
                $number_of_service=0;
                $number_of_patient=array();
                $grand_total_cash=0;
                $grand_total_credit=0;
                $grand_total_msamaha=0;
                $grand_total = 0;

                 $que = "SELECT pp.Registration_ID,ppl.Sub_Department_ID,dp.Department_Name,pp.payment_type,pp.Billing_Type,pp.Pre_Paid,pp.Sponsor_ID,(ppl.Quantity*(ppl.Price - ppl.Discount)) AS 'TOTAL',"
                        . "ppl.Discount,ppl.Price,ppl.Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_department dp ,tbl_sub_department sp "
                        . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND sp.Department_ID = dp.Department_ID  AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                        . "AND ppl.Sub_Department_ID='$Sub_Department_ID' $fileter";


                    $que_nm = "SELECT dp.Department_Name "
                    . "FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp,tbl_department dp ,tbl_sub_department sp "
                    . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND sp.Department_ID = dp.Department_ID  AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                    . "AND ppl.Sub_Department_ID='$Sub_Department_ID' $fileter";

                    // grand total
                    $sql_que_nm=mysqli_query($conn,$que_nm) or die(mysqli_error($conn));

                    while($dep=mysqli_fetch_assoc($sql_que_nm)){
                    $Department_Name = $dep['Department_Name'];
                    }

                    $que4 = "SELECT
                    SUM((ppl.Quantity*(ppl.Price - ppl.Discount))) AS 'GRAND_TOTAL'
                    FROM tbl_patient_payment_item_list ppl INNER JOIN
                    tbl_patient_payments pp
                    ON ppl.Patient_Payment_ID=pp.Patient_Payment_ID
                    INNER JOIN tbl_sub_department sp
                    ON ppl.Sub_Department_ID = sp.Sub_Department_ID
                    INNER JOIN tbl_items it
                    ON ppl.Item_ID = it.Item_ID
                    INNER JOIN tbl_patient_registration pr
                    ON pp.Registration_ID = pr.Registration_ID
                    INNER JOIN tbl_department dp
                    ON sp.Department_ID = dp.Department_ID
                    INNER JOIN tbl_item_subcategory isc
                    ON it.Item_Subcategory_ID = isc.Item_Subcategory_ID
                    INNER JOIN tbl_item_category ic
                    ON isc.Item_Category_ID = ic.Item_Category_ID
                    INNER JOIN tbl_sponsor spp
                    ON spp.Sponsor_ID  = pp.Sponsor_ID
                    WHERE
                    DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date'
                    AND dp.Department_Name ='$Department_Name'  AND spp.Exemption !='yes' AND (pp.Billing_Type ='Outpatient Credit' OR pp.Billing_Type ='Inpatient Credit' OR pp.Billing_Type ='Outpatient Cash' OR pp.Billing_Type ='Inpatient Cash' OR pp.Billing_Type ='Patient From Outside' OR pp.Pre_Paid='0' OR pp.payment_type='pre') $fileter";


                    //die($que4);
                    // grand total
                     $sql_que4=mysqli_query($conn,$que4) or die(mysqli_error($conn));
                     $grand_total = mysqli_fetch_assoc($sql_que4)['GRAND_TOTAL'];




                        $cnt= "SELECT COUNT(ppl.Item_ID) AS 'SERVICES' FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp
                        WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date'
                        AND ppl.Sub_Department_ID='$Sub_Department_ID' $fileter";

                        // die($que);

                // No of services
                $sql_cnt=mysqli_query($conn,$cnt) or die(mysqli_error($conn));
                $number_of_service = mysqli_fetch_assoc( $sql_cnt)['SERVICES'];

                $sql_select_all_items_with_price_result=mysqli_query($conn,$que) or die(mysqli_error($conn));

                if(mysqli_num_rows($sql_select_all_items_with_price_result)>0){

                    while($items_rows=mysqli_fetch_assoc($sql_select_all_items_with_price_result)){
                        $total =$items_rows['TOTAL'];
                       //$Department_Name =$items_rows['Department_Name'];
                       $Sub_Department_ID=$items_rows['Sub_Department_ID'];
                       $payment_type=$items_rows['payment_type'];
                       $Billing_Type=$items_rows['Billing_Type'];
                       $Pre_Paid=$items_rows['Pre_Paid'];
                       //$Sponsor_ID=$items_rows['Sponsor_ID'];
                       $Discount=$items_rows['Discount'];
                       $Price=$items_rows['Price'];
                       $Quantity=$items_rows['Quantity'];
                       $Registration_ID=$items_rows['Registration_ID'];
                       if(!in_array($Registration_ID, $number_of_patient)){
                           array_push($number_of_patient, $Registration_ID);
                       }


                       //print_r($number_of_patient);



                       // die($que0);




                    ///////////////////////////////////////////////////////////////
                    }



                    // $departmental_total=0;
                    $que1 = "SELECT SUM((ppl.Quantity*(ppl.Price - ppl.Discount))) AS 'MSAMAHA'"
                    . "FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp ,tbl_sponsor sp "
                    . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Sponsor_ID = sp.Sponsor_ID AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                    . "AND ppl.Sub_Department_ID='$Sub_Department_ID' AND sp.Exemption ='yes' $fileter";
                      // Msamaha
                     $sql_que1=mysqli_query($conn,$que1) or die(mysqli_error($conn));
                     $subtotal_msamaha = mysqli_fetch_assoc($sql_que1)['MSAMAHA'];
if($Patient_type == 'Outpatient'){
                    $que2 = "SELECT SUM((ppl.Quantity*(ppl.Price - ppl.Discount))) AS 'CREDIT'"
                    . "FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp ,tbl_sponsor sp "
                    . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Sponsor_ID = sp.Sponsor_ID AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                    . "AND ppl.Sub_Department_ID='$Sub_Department_ID' AND sp.Exemption !='yes' AND (pp.Billing_Type ='Outpatient Credit') $fileter";
                     // credit
                     $sql_que2=mysqli_query($conn,$que2) or die(mysqli_error($conn));
                     $subtotal_credit = mysqli_fetch_assoc($sql_que2)['CREDIT'];

                     $que3 = "SELECT SUM((ppl.Quantity*(ppl.Price - ppl.Discount))) AS 'CASH'"
                    . "FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp ,tbl_sponsor sp "
                    . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Sponsor_ID = sp.Sponsor_ID AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                    . "AND ppl.Sub_Department_ID='$Sub_Department_ID' AND sp.Exemption !='yes' AND pp.Billing_Type IN('Outpatient Cash','Patient From Outside')  $fileter";
                    $sql_que3=mysqli_query($conn,$que3) or die(mysqli_error($conn));
                     $subtotal_cash = mysqli_fetch_assoc($sql_que3)['CASH'];

}elseif($Patient_type == 'Inpatient'){
                    $que2 = "SELECT SUM((ppl.Quantity*(ppl.Price - ppl.Discount))) AS 'CREDIT'"
                    . "FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp ,tbl_sponsor sp "
                    . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Sponsor_ID = sp.Sponsor_ID AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                    . "AND ppl.Sub_Department_ID='$Sub_Department_ID' AND sp.Exemption !='yes' AND (pp.Billing_Type ='Inpatient Credit') $fileter";
                    // credit
                    $sql_que2=mysqli_query($conn,$que2) or die(mysqli_error($conn));
                    $subtotal_credit = mysqli_fetch_assoc($sql_que2)['CREDIT'];

                    $que3 = "SELECT SUM((ppl.Quantity*(ppl.Price - ppl.Discount))) AS 'CASH'"
                    . "FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp ,tbl_sponsor sp "
                    . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Sponsor_ID = sp.Sponsor_ID AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                    . "AND ppl.Sub_Department_ID='$Sub_Department_ID' AND sp.Exemption !='yes' AND pp.Billing_Type ='Inpatient Cash'  $fileter";
                    $sql_que3=mysqli_query($conn,$que3) or die(mysqli_error($conn));
                     $subtotal_cash = mysqli_fetch_assoc($sql_que3)['CASH'];
}else{
                    $que2 = "SELECT SUM((ppl.Quantity*(ppl.Price - ppl.Discount))) AS 'CREDIT'"
                    . "FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp ,tbl_sponsor sp "
                    . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Sponsor_ID = sp.Sponsor_ID AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                    . "AND ppl.Sub_Department_ID='$Sub_Department_ID' AND sp.Exemption !='yes' AND (pp.Billing_Type ='Outpatient Credit' OR pp.Billing_Type ='Inpatient Credit') $fileter";
                     // credit
                     $sql_que2=mysqli_query($conn,$que2) or die(mysqli_error($conn));
                     $subtotal_credit = mysqli_fetch_assoc($sql_que2)['CREDIT'];



                    $que3 = "SELECT SUM((ppl.Quantity*(ppl.Price - ppl.Discount))) AS 'CASH'"
                    . "FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp ,tbl_sponsor sp "
                    . "WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Sponsor_ID = sp.Sponsor_ID AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' "
                    . "AND ppl.Sub_Department_ID='$Sub_Department_ID' AND sp.Exemption !='yes' AND pp.Billing_Type IN('Outpatient Cash','Inpatient Cash','Patient From Outside')  $fileter";
                     
                    
                    //die($que3);
                    // cash
                     $sql_que3=mysqli_query($conn,$que3) or die(mysqli_error($conn));
                     $subtotal_cash = mysqli_fetch_assoc($sql_que3)['CASH'];
                    // $grand_total_credit+=$subtotal_credit;
                    // $grand_total_msamaha+=$subtotal_msamaha;
                    // $total1=$subtotal_cash+$subtotal_credit;
                    // $departmental_total=0;


                    // $grand_total = 0;
                    // $grand_total =$subtotal_cash + $subtotal_credit;
                }

              }

            //   $departmental_total+=$total1;




                echo "<tr>
                         <td style='text-align:left;width: 22%;'>
                         <a href='revenue_collection_by_dep_excel.php?sub_dep_id=".$Sub_Department_ID."&start_date=".$start_date."&end_date=".$end_date."&sponsor_id=".$Sponsor_ID2."' target='_blank'>".strtoupper($Sub_Department_Name)."</a>
                         </td>

                         <td style='text-align:right;width: 13%;'>
                         <a href='revenue_collection_by_dep_excel.php?sub_dep_id=".$Sub_Department_ID."&start_date=".$start_date."&end_date=".$end_date."&sponsor_id=".$Sponsor_ID2."' target='_blank'>".number_format($number_of_service)."</a>
                         </td>
                         <td style='text-align:right;width: 13%;'>".number_format(sizeof($number_of_patient))."</td>
                         <td style='text-align:right;width: 13%;'>".number_format($subtotal_cash)."</td>
                         <td style='text-align:right;width: 13%;'>".number_format($subtotal_credit)."</td>
                         <td style='text-align:right;width: 13%;'>".number_format($subtotal_msamaha)."</td>
                         <td style='text-align:right;width: 13%;'>".number_format($subtotal_cash+$subtotal_credit+$subtotal_msamaha)."</td>
                     </tr>";




               }  //end while of sub department





            //    echo "<tr>
            //              <td colspan='6' style='text-align:right;width: 13%;'>Grand Total</td>
            //              <td style='text-align:right;width: 13%;'>".number_format($grand_total)."</td>
            //          </tr>";
            } //end if of sub department
            ?>
            </tbody>
        </table>
   </td>
</tr>

<?php
       $count_sn++;
    } //end while of department
} //end if of department
?>
<!-- <tr><td colspan="8"></td><td style="width:5%"><input type='button' onclick='preview_revenue_collection_by_item_category()' value='PREVIEW' class='art-button-green'/></td></tr>  -->

