<?php
session_start();
include("./includes/connection.php");
include './includes/constants.php';
$temp = 0;
$Grand_Total = 0;
if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_From = '';
}

if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = '';
}

if (isset($_GET['Report_Type'])) {
    $Report_Type = $_GET['Report_Type'];
} else {
    $Report_Type = '';
}

if (isset($_GET['Payment_Mode'])) {
    $Payment_Mode = $_GET['Payment_Mode'];
} else {
    $Payment_Mode = '';
}



$cashier_id = '';
if(isset($_GET['cashier_id'])){
    $cashier_id = $_GET['cashier_id'];
}
$filter_cashier ="";
if (!empty($cashier_id) && $cashier_id != 'All') {
    $filter_cashier = " AND pp.Employee_ID='$cashier_id'";
}


$limit = "LIMIT 500";
if (isset($_GET['number_recordes'])) {
    $number_recordes = $_GET['number_recordes'];
}

if ($number_recordes == '500_Rec')
    $limit = "LIMIT 500";
else if ($number_recordes == '300_Rec')
    $limit = "LIMIT 300";
else if ($number_recordes == '100_Rec')
    $limit = "LIMIT 100";
else if ($number_recordes == '50_Rec')
    $limit = "LIMIT 50";
else if ($number_recordes == 'All')
    $limit = "";
?>
   <?php 
   $htm = "
       <img src='./branchBanner/branchBanner.png'>
       <table width = 100%>
        <tr>
            <td colspan='10' style='text-align:center'>OFFLINE/MANUAL TRANSACTION REPORT FROM <b>$Date_From</b> TO <b>$Date_To</b></td>
        </tr>
        <tr>
            <td width='5%'><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width='10%'><b>PATIENT NUMBER</b></td>
            <td width='12%'><b>SPONSOR</b></td>
            <td width='15%' style='text-align: left;'><b>PREPARED DATE</b></td>
            <td width='15%' style='text-align: left;'><b>EMPLOYEE PREPARED</b></td>
             <td width='10%' style='text-align: left;'><b>RECEIPT NO.</b></td>
            <td width='10%' style='text-align: left;'><b>AUTH NO.</b></td>
            <td width='10%' style='text-align: left;'><b>MODE</b></td>
            <td width='10%' style='text-align: left;'><b>AMOUNT</b></td>
            
        </tr>
        ";
    ?>
        <?php 
            $sql_select_offline_transaction_result=mysqli_query($conn,"SELECT pp.Patient_Payment_ID,Patient_Name,Guarantor_Name,pr.Registration_ID,Employee_Name,Payment_Date_And_Time,auth_code,manual_offline
                     FROM tbl_patient_registration pr,tbl_sponsor sp,tbl_employee e,tbl_patient_payments pp 
                        WHERE pr.Registration_ID=pp.Registration_ID AND 
                              pr.Sponsor_ID=sp.Sponsor_ID AND
                              pp.Employee_ID=e.Employee_ID AND 
                              
                              pp.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' $filter_cashier
                              AND (pp.manual_offline='offline' OR pp.manual_offline='manual')GROUP BY pp.Patient_Payment_ID ORDER BY pp.Patient_Payment_ID DESC
                        ") or die(mysqli_error($conn));
            $count=1;
           $total=0;
            if(mysqli_num_rows($sql_select_offline_transaction_result)>0){
                while($offline_manul_rows=mysqli_fetch_assoc($sql_select_offline_transaction_result)){
                    
                   $Patient_Payment_ID=$offline_manul_rows['Patient_Payment_ID'];
                   $sql_select_paid_price_result=mysqli_query($conn,"SELECT Quantity,Price FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID'") or die(mysqli_error($conn));
                   $sub_total=0;
                   if(mysqli_num_rows($sql_select_paid_price_result)>0){
                       
                        while($price_rows=mysqli_fetch_assoc($sql_select_paid_price_result)){
                                $Quantity=$price_rows['Quantity'];
                                $Price=$price_rows['Price'];
                                $sub_total+=($Quantity*$Price);
                        }
                         
                    }
                   $Patient_Name=$offline_manul_rows['Patient_Name'];
                   $Guarantor_Name=$offline_manul_rows['Guarantor_Name'];
                   $Registration_ID=$offline_manul_rows['Registration_ID'];
                   $Employee_Name=$offline_manul_rows['Employee_Name'];
                   $Payment_Date_And_Time=$offline_manul_rows['Payment_Date_And_Time'];
                   $auth_code=$offline_manul_rows['auth_code'];
                   $manual_offline=$offline_manul_rows['manual_offline'];
                  
                   $htm.= "
                            <tr>
                                <td style='text-align:left'>$count</td>
                                <td style='text-align:left'>$Patient_Name</td>
                                <td style='text-align:left'>$Registration_ID</td>
                                <td style='text-align:left'>$Guarantor_Name</td>
                                <td style='text-align:left'>$Payment_Date_And_Time</td>
                                <td style='text-align:left'>$Employee_Name</td>
                                <td style='text-align:left'>$Patient_Payment_ID</td>
                                <td style='text-align:left'>$auth_code</td>
                                <td style='text-align:left'>$manual_offline</td>
                                <td style='text-align:left'>".number_format($sub_total,2)."</td>
                            </tr>
                        ";
                   $count++;
                   $total+=$sub_total;
                }
            }
        $htm.="
       
        <tr>
            <td colspan='8'></td>
            <td style='text-align: right'><b>TOTAL</b></td>
            <td><b>".number_format($total,2)."</b></td>
        </tr>
         
    </table>";
        
     
    
include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);

$mpdf->Output();
?>
