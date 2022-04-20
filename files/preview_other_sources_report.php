<?php
include("./includes/connection.php");
$Start_Date=$_GET['Start_Date'];
$End_Date=$_GET['End_Date'];
$customer_type=$_GET['customer_type'];

  $html="<table width ='100%' height = '40px'>
  <tr> <td> <img src='./branchBanner/branchBanner.png' width=100% height='100px'> </td></tr>
  </table>";
  $html.='<table width="100%">
  <tr><td style="text-align:center;">Other Source Transactions From <b>'.$Start_Date.'</b> to <b>'.$End_Date.'</b></td></tr>
  <tr><td style="text-align:center;">Category: <b>'.$customer_type.'S</b> </td></tr>
  </table>';
  $html.= '<table width="100%" style="background-color:#FFFFFF;">
  	<tr><td colspan="5"><hr></td></tr>
  	<tr>
  		<td width="5%"><b>SN</b></td>
  		<td><b>'.$customer_type.' NAME</b></td>
  		<td><b>ITEM NAME</b></td>
  		<td style="text-align: center" width="10%"><b>RECEIPT NO</b></td>
  		<td style="text-align: right" width="10%"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>
  	</tr><tr><td colspan="5"><hr></td></tr>';
    $results=mysqli_query($conn,"SELECT osp.Customer_ID, ospl.Price, ospl.Item_Name, osp.Payment_ID FROM tbl_other_sources_payments osp, tbl_other_sources_payment_item_list ospl WHERE osp.Payment_ID=ospl.Payment_ID AND customer_type='$customer_type' AND osp.Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date'") or die(mysqli_error($conn));
    $count=1;
    $total_amount=0;
    while ($row=mysqli_fetch_assoc($results)) {
      $name='';
      $Customer_ID=$row['Customer_ID'];
      if($customer_type =='SPONSOR'){
        $name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID=$Customer_ID "))['Guarantor_Name'];
       }else if($customer_type =='CUSTOMER'){
        $name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID=$Customer_ID AND registration_type='customer'"))['Patient_Name'];
      }
      $html.= "<tr><td>{$count}</td><td>{$name}</td><td>{$row['Item_Name']}</td><td style='text-align:center;'>{$row['Payment_ID']}</td><td style='text-align:right;'>".number_format($row['Price'])."&nbsp;&nbsp;</td></tr>";
      $total_amount+=$row['Price'];
      $count++;
    }
    $html.= "<tr><td colspan='5'><hr></td></tr>";
    $html.= "<tr><td style='text-align:center' colspan='4' ><b>Total Amount</b></td><td style='text-align:right;'>".number_format($total_amount)."&nbsp;&nbsp;</td></tr>";
    $html.= "<tr><td colspan='5'><hr></td></tr>";
    $html.= "</table>";
    $html.= "<br>";
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($html);
    $mpdf->Output();

?>
