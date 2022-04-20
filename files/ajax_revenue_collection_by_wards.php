<?php
include("includes/connection.php");

$Date_From = $_GET['Date_From'];
$Date_To = $_GET['Date_To'];
$Sponsor_ID = $_GET['Sponsor_ID'];
$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
$filter = '';

$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
    $This_date_today = $Today." 00:00";
    $This_date_today_end = $Today." 23:59";
}

if($Hospital_Ward_ID != 'All'){
    $filter_ward = " AND Hospital_Ward_ID = '$Hospital_Ward_ID'";
}else{
    $filter_ward = "";
}

if($Sponsor_ID != 'All'){
    $filter .= " AND pp.Sponsor_ID = '$Sponsor_ID'";
}

if(!empty($Date_To) AND !empty($Date_From)){
    $filter .= " AND pp.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'"; 
}else{
    $Date_From = $This_date_today;
    $Date_To = $This_date_today_end;
    $filter .= " AND pp.Payment_Date_And_Time BETWEEN '$This_date_today' AND NOW()";
}

$Sn = 1;
$display = '';

$Select_ward = mysqli_query($conn, "SELECT Hospital_Ward_Name, Hospital_Ward_ID FROM tbl_hospital_ward WHERE ward_status = 'active' $filter_ward AND Hospital_Ward_ID IN(SELECT Hospital_Ward_ID FROM tbl_patient_payments WHERE Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' GROUP BY Hospital_Ward_ID) ORDER BY Hospital_Ward_Name ASC ") or die(mysqli_error($conn));
if(mysqli_num_rows($Select_ward)>0){
    while($dt = mysqli_fetch_assoc($Select_ward)){
        $Hospital_Ward_Name  = $dt['Hospital_Ward_Name'];
        $Hospital_Ward_ID  = $dt['Hospital_Ward_ID'];

            $Credit_Transaction = 0;
            $Cash_Transaction = 0;
            $Msamaha_Transaction = 0;
            $Subtotal_Transaction = 0;

            // $Select_receipt = mysqli_query($conn, "SELECT Transaction_status, Patient_Payment_ID, payment_type, Billing_Type, Pre_Paid, Sponsor_ID FROM tbl_patient_payments WHERE  ppl.Hospital_Ward_ID='$Hospital_Ward_ID' $filter") or die(mysqli_error($conn));
            // die("SELECT pp.Transaction_status, pp.Hospital_Ward_ID, pp.payment_type, pp.Billing_Type, pp.Pre_Paid, pp.Sponsor_ID, ppl.Discount, ppl.Price, ppl.Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Hospital_Ward_ID ='$Hospital_Ward_ID' $filter");

            $Select_revenue = mysqli_query($conn, "SELECT pp.Transaction_status, pp.Hospital_Ward_ID, pp.payment_type, pp.Billing_Type, pp.Pre_Paid, pp.Sponsor_ID, ppl.Discount, ppl.Price, ppl.Quantity FROM tbl_patient_payment_item_list ppl,tbl_patient_payments pp WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Hospital_Ward_ID ='$Hospital_Ward_ID' $filter") or die(mysqli_error($conn));
                if(mysqli_num_rows($Select_revenue)){
                    while($rev = mysqli_fetch_assoc($Select_revenue)){
                        $Transaction_status = $rev['Transaction_status'];
                        $payment_type = $rev['payment_type'];
                        $Billing_Type = $rev['Billing_Type'];
                        $Pre_Paid = $rev['Pre_Paid'];
                        $Sponsor_ID = $rev['Sponsor_ID'];
                        $Discount = $rev['Discount'];
                        $Price = $rev['Price'];
                        $Quantity = $rev['Quantity'];

                        $sql_check_if_exempted_result=mysqli_query($conn,"SELECT Exemption FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
                        $Exemption=mysqli_fetch_assoc($sql_check_if_exempted_result)['Exemption'];
                            if($Exemption == 'no' && ((strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre') && strtolower($Transaction_status) != 'cancelled')){
                                $Cash_Transaction += $Quantity*($Price-$Discount);

                            }elseif(($Exemption=='yes') && ((strtolower($Billing_Type) == 'inpatient credit'))&& strtolower($Transaction_status) != 'cancelled'){
                                $Msamaha_Transaction += $Quantity*($Price-$Discount);

                            }elseif(($Exemption=='no') && ((strtolower($Billing_Type) == 'inpatient credit'))&& strtolower($Transaction_status) != 'cancelled'){
                                $Credit_Transaction += $Quantity*($Price-$Discount);
                            }

                        $Subtotal_Transaction = $Msamaha_Transaction + $Cash_Transaction + $Credit_Transaction;
                    }
                }

                $display .= "<tr>
                                    <td style='text-align: center;'>".$Sn."</td>
                                    <td>".$Hospital_Ward_Name."</td>
                                    <td style='text-align: right;'>".number_format($Cash_Transaction)."</td>
                                    <td style='text-align: right;'>".number_format($Credit_Transaction)."</td>
                                    <td style='text-align: right;'>".number_format($Msamaha_Transaction)."</td>
                                    <td style='text-align: right;'>".number_format($Subtotal_Transaction)."</td>
                            </tr>";


        $Total_cash += $Cash_Transaction;
        $Total_credit += $Credit_Transaction;
        $Total_msamaha += $Msamaha_Transaction;
        $Total_Total += $Subtotal_Transaction;
$Sn++;
    }

    $display .= "<tr style='background: #dedede;'>
                        <td colspan='2' style='text-align: right;'><h4>SUB TOTAL</h4></td>
                        <td style='text-align: right;'><h4>".number_format($Total_cash)."</h4></td>
                        <td style='text-align: right;'><h4>".number_format($Total_credit)."</h4></td>
                        <td style='text-align: right;'><h4>".number_format($Total_msamaha)."</h4></td>
                        <td style='text-align: right;'><h4>".number_format($Total_Total)."</h4></td>
                </tr>
                </table>";

}else{
    $display .="
                <tr>
                    <td colspan='6' style='text-align: center;'><h4>NO COLLECTION MADE FOR THE SPECIFIC PERIOD</td>
                </tr>";
}

echo $display;


mysqli_close($conn);
?>