<?php 
 include("./includes/connection.php");
 $month = $_GET['month'];

 $year = $_GET['year'];
 $sponsor_id = $_GET['sponsor_id'];


$Item_Category_ID = $_GET['Item_Category_ID'];
if($sponsor_id != 'All'){
    $filter  =" AND pp.Sponsor_ID='$sponsor_id'";
    $Granter_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Guarantor_Name FROM  tbl_sponsor  WHERE Sponsor_ID='$sponsor_id' "))['Guarantor_Name'];
}else{
    $filter="";
    $Granter_Name='NHIF All';
}

$htm.= "<table width='100%'><tr><td><img src='./branchBanner/branchBanner.png' width=100% style='height:150px;'></td></tr>
<tr><th>SPONSOR NAME: $Granter_Name</th>
 </tr>
 <tr><th>BILL MONTH : $month-$year </th></tr>
</table>";
$category = mysqli_query($conn, "SELECT * FROM  tbl_item_subcategory  WHERE Item_Category_ID='$Item_Category_ID' AND enabled_disabled='enabled' ") or die(mysqli_error($conn));

    $num=1;
    $Gran_GrandTotalAmount=0;
    if(mysqli_num_rows($category)>0){
        while($row = mysqli_fetch_assoc($category)){
            $Item_Subcategory_ID = $row['Item_Subcategory_ID'];
            $Item_Subcategory_Name = $row['Item_Subcategory_Name'];
            $htm.="<h4 style='background-color:#ccc;'>$num  $Item_Subcategory_Name</h4>";
            $htm.="<table width='100%'>
            <tr><td><b>#</b></td><td><b>Service Name</b></td><td><b>Quantity</b></td><td style='text-align:right;'><b>Amount</b></td></tr>";
            
            $items =mysqli_query($conn, "SELECT i.Item_ID,Product_Name, COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM tbl_items i,  tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_check_in ci, tbl_bills b WHERE i.Item_Subcategory_ID = '$Item_Subcategory_ID'  AND i.Item_ID = ppl.Item_ID and ppl.Status<>'removed' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = b.Bill_ID AND ci.Check_In_ID = pp.Check_In_ID AND MONTHNAME(ci.Visit_Date) = '$month'  AND YEAR(ci.Visit_Date) = '$year' $filter AND b.e_bill_delivery_status = 1 AND  pp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') AND pp.Bill_ID IS NOT NULL GROUP BY i.Item_ID") or die(mysqli_error($conn));
            $i=1;
            $Quantity =0;
            $GrandTotalAmount =0;
            if(mysqli_num_rows($items)>0){
                while($rows = mysqli_fetch_assoc($items)){
                    $Product_Name =$rows['Product_Name'];
                    $Quantity = $rows['Quantity'];
                    $Total_Amount= $rows['Total_Amount'];
                    $htm.="<tr><td>$i</td><td>$Product_Name</td><td>".number_format($Quantity)."</td><td style='text-align:right;'>".number_format($Total_Amount)."</td></tr>";
                    $GrandTotalAmount +=$Total_Amount; 
                    $i++;
                }
            }
            $htm.=" <tr><td colspan='3'><b>Grand Total</b></td><td style='text-align:right;'><b>".number_format($GrandTotalAmount)."</b></td></tr>
            </table>";
            $num++;
            $Gran_GrandTotalAmount +=$GrandTotalAmount;
        }
    }
        // $htm.= "<tr><td><b>Total </b></td><td style='text-align:right;'><b>".number_format($Gran_GrandTotalAmount)."</b></td></tr></table>  ";


    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();