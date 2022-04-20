<?php
@session_start();
    include("./includes/connection.php");
    $temp = 1;
    $filter=' ';
    $limit =' LIMIT 25';
    if(isset($_GET['Start_Date'])){
     $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
    }else{
     $Start_Date = '';
    }
    if(isset($_GET['End_Date'])){
     $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
    }else{
     $End_Date = '';
    }
    if(!empty($Start_Date) && !empty($End_Date)){
      $filter=" WHERE osp.Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' ";
      $limit=" ";
    }
    $html ="<table width ='100%'>
    <tr> <td> <img src='./branchBanner/branchBanner.png' width=100% height='100px'> </td></tr>
    <tr><td style='text-align:center;'>Revenue From Other Sources collected from <b> {$Start_Date} </b> to <b> {$End_Date} </b> </td></tr>
    </table>";
    $html .= "<center><table width ='100%' style=' border-collapse:collapse;' border='1'>";
    $html .= "<thead><tr>
	    <th style = 'width:5%;'>SN</th>
      <th style='text-align: left;'>DATE & TIME</th>
      <th style='text-align: left;'>CUSTOMER/SPONSOR NAME</th>
      <th width=10% style='text-align: left;'>RECEIPT NO</th>
      <th>CHEQUE NUMBER</th>
      <th>CUSTOMER TYPE</th>
      <th>AMOUNT</th></tr></thead>";


    $select_list=mysqli_query($conn,"SELECT osp.Payment_ID,osp.Customer_ID,osp.Payment_Date_And_Time,osp.cheque_number,osp.customer_type from tbl_other_sources_payments osp $filter ORDER BY osp.Payment_Date_And_Time DESC $limit");
    while ($row=mysqli_fetch_assoc($select_list)) {
        $Customer_ID=$row['Customer_ID'];
        $Payment_ID=$row['Payment_ID'];
        if($row['customer_type']==='CUSTOMER'){
            $customer_name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID=$Customer_ID"))['Patient_Name'];
        }
        else if($row['customer_type']==='SPONSOR'){
            $customer_name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_Sponsor WHERE Sponsor_ID=$Customer_ID"))['Guarantor_Name'];
        }
        $amount=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM((Price-Discount) * Quantity) as amount FROM tbl_other_sources_payment_item_list WHERE Payment_ID=$Payment_ID"))['amount'];
        //$customer_name="empty";
        $html .= "<tr>
            <td style='text-align:center;'>".$temp."</td><td>".$row['Payment_Date_And_Time']."</td><td>".$customer_name."</td><td style='text-align:center;'>".$row['Payment_ID']."</a></td><td  style='text-align:center;'>".$row['cheque_number']."</td><td style='text-align:center;'>".$row['customer_type']."</td><td style='text-align:right;'>".number_format($amount)."</td>
        </tr>";
        $temp++;
        $total_amount += $amount;
    }
$html .= "<tr><td colspan='7' style='text-align:right;'><b>Total Amount: &nbsp;". number_format($total_amount)."</b></td></tr>";
$html .= "</table></center>";
include("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4-L');
$mpdf->SetFooter('|Page {PAGENO} of {nb}|Powered by GPITG {DATE d-m-Y}');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($html);
$mpdf->Output();
?>
