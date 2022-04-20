<?php
include("./includes/connection.php");
	$Supplier_ID = mysqli_real_escape_string($conn,$_GET['Supplier_ID']);
	$Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
	$End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);

	$filter_voucher=" ";
    $filter_against_order=" ";
	$filter_without_order=" ";
	if(isset($Supplier_ID) && $Supplier_ID !==''){
		$filter_voucher=" AND v.Supplier_ID=$Supplier_ID ";
        $filter_against_order=" AND  po.Supplier_ID=$Supplier_ID ";
		$filter_without_order=" AND  gpo.Supplier_ID=$Supplier_ID ";
	}
$html  = "<table width ='100%' height = '30px'>";
$html .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td></tr>";
$html .= "<tr> <td style='text-align:center;'><b> Supplier Ledger From {$Start_Date} To {$End_Date} <b></td></tr>";
$html .= "<tr> <td><br></td></tr>";
$html .="</table>";

$html .="<table width='100%' border='1' style='border-collapse:collapse;'>";

            $brought_forward=0;
            $today=$Start_Date;
            //balance without purchase order
            $withoutp_bf_balance=mysqli_query($conn,"SELECT SUM(gpoi.Container_Qty * gpoi.Items_Per_Container * gpoi.Price)AS total_amount FROM tbl_grn_without_purchase_order_items gpoi, tbl_grn_without_purchase_order gpo WHERE gpoi.Grn_ID=gpo.Grn_ID AND date(gpo.Grn_Date_And_Time) < date('$today') $filter_without_order") or die(mysqli_error($conn));
            //balance with(against) purchase order
            $againstp_bf_balance=mysqli_query($conn,"SELECT SUM(poi.Quantity_Received * poi.Price) as total_amount FROM tbl_purchase_order_items poi, tbl_purchase_order po,tbl_grn_purchase_order gpo WHERE gpo.Grn_Purchase_Order_ID=po.Grn_Purchase_Order_ID AND po.Grn_Purchase_Order_ID=poi.Grn_Purchase_Order_ID AND poi.Grn_Status='RECEIVED' AND date(gpo.Created_Date_Time) < date('$today') $filter_against_order") or die(mysqli_error($conn));
            $total_voucher_amount=mysqli_query($conn,"SELECT SUM(v.amount) total_amount FROM  tbl_voucher v WHERE v.voucher_date < '$today' $filter_voucher   AND payee_type='supplier'") or die(mysqli_error($conn));
            //die(mysqli_fetch_assoc($total_voucher_amount)['total_amount'].', '.(mysqli_fetch_assoc($withoutp_bf_balance)['total_amount'].', '.mysqli_fetch_assoc($againstp_bf_balance)['total_amount']));
            //if((mysqli_num_rows($withoutp_bf_balance) > 0 ) && (mysqli_num_rows($total_voucher_amount) > 0) && (//mysqli_num_rows($againstp_bf_balance) > 0)){
                $brought_forward=(mysqli_fetch_assoc($total_voucher_amount)['total_amount']-(mysqli_fetch_assoc($withoutp_bf_balance)['total_amount'] + mysqli_fetch_assoc($againstp_bf_balance)['total_amount']));
            //}

$html .="    <tr style='color:white;'><th colspan='8' style='text-align:right;'>B/F: ".number_format($brought_forward)."</th></tr>
        <tr style='color:white;'><th>SN</th><th>GRN No/ Voucher No</th><th>Supplier</th><th>Prepared Date</th><th>Narration</th><th>Debt</th><th>Payments</th><th>Balance</th></tr>";
 
            $count=1;
            $results=mysqli_query($conn,"SELECT v.voucher_ID AS voucher_order_ID,v.transaction_time AS time, v.Supplier_ID AS Supplier, date(v.transaction_time) AS trans_date, v.amount, v.narration, 'voucher' as selected_from FROM tbl_voucher v WHERE v.voucher_date BETWEEN '$Start_Date' AND '$End_Date'  $filter_voucher   AND payee_type='supplier' UNION ALL SELECT gpo.Grn_Purchase_Order_ID AS voucher_order_ID,gpo.Created_Date_Time AS time, gpo.supplier_id AS Supplier, gpo.Created_Date AS trans_date, 'not_found' AS amount,'any_notes against' AS narration, 'against_order' as selected_from FROM tbl_grn_purchase_order gpo, tbl_purchase_order po WHERE gpo.Purchase_Order_ID=po.Purchase_Order_ID and gpo.Created_Date BETWEEN '$Start_Date' AND '$End_Date' $filter_against_order UNION ALL SELECT gpo.Grn_ID AS voucher_order_ID,Grn_Date_And_Time AS time, GPO.Supplier_ID AS Supplier, date(gpo.Grn_Date_And_Time) AS trans_date, 'not_found' AS amount, 'any_notes out' AS narration,'without_order' AS selected_from FROM tbl_grn_without_purchase_order gpo WHERE date(gpo.Grn_Date_And_Time) BETWEEN '$Start_Date' AND '$End_Date' $filter_without_order ORDER BY time ASC ")or die(mysqli_error($conn));
            $debit_amount=0;
            $credit_amount=0;
        if(mysqli_num_rows($results) > 0){    
            while($row=mysqli_fetch_assoc($results)){
                extract($row);
                $Supplier_ID=$Supplier;
                $Supplier=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Supplier_Name FROM tbl_supplier WHERE Supplier_ID=$Supplier"))['Supplier_Name'];
                $report_type="";
                if($selected_from == 'voucher'){
                    $brought_forward+=$amount;
                    $credit_amount+=$amount;
                    $report_type="voucher";
                }else if($selected_from == 'without_order'){
                    $selected_amount=mysqli_query($conn,"SELECT SUM(gpoi.Container_Qty * gpoi.Items_Per_Container * gpoi.Price) AS total_amount FROM tbl_grn_without_purchase_order_items gpoi, tbl_grn_without_purchase_order gpo WHERE gpoi.Grn_ID=gpo.Grn_ID  AND gpo.Grn_ID = $voucher_order_ID AND  gpo.Supplier_ID=$Supplier_ID ") or die(mysqli_error($conn));
                    $amount=mysqli_fetch_assoc($selected_amount)['total_amount'];
                    $brought_forward-=$amount;
                    $debit_amount+=$amount;
                    $report_type="without_order";
                }else if($selected_from == 'against_order'){
                    $selected_amount=mysqli_query($conn,"SELECT SUM(poi.Quantity_Received * poi.Price) as total_amount FROM tbl_purchase_order_items poi, tbl_purchase_order po WHERE po.Purchase_Order_ID=poi.Purchase_Order_ID AND poi.Grn_Status='RECEIVED' AND po.Grn_Purchase_Order_ID = $voucher_order_ID AND  po.Supplier_ID=$Supplier_ID") or die(mysqli_error($conn));
                    $amount=mysqli_fetch_assoc($selected_amount)['total_amount'];
                    $brought_forward-=$amount;
                    $debit_amount+=$amount;
                    $report_type="against_order";
                }
                $html .="<tr><td>".$count."</td><td onclick='view_invoice(\"{$voucher_order_ID}\",\"{$report_type}\")'>".$voucher_order_ID."</td><td>{$Supplier}</td><td style='text-align:center;'>".$trans_date."</td><td>".$narration."</td><td  style='text-align: right;'>".number_format((($selected_from !='voucher')?$amount:0))."</td><td  style='text-align: right;'>".number_format((($selected_from=='voucher')?$amount:0))."</td><td  style='text-align: right;'>".number_format(($brought_forward))."</td><tr>";
                $count++;
            }
            $html .="<tr><td colspan='5' style='text-align:center;'><b>Total Amount</b></td><td  style='text-align: right;'><b>".number_format($debit_amount)."</b></td><td  style='text-align: right;'><b>".number_format($credit_amount)."</b></td><td  style='text-align: right;'><b>".number_format(($brought_forward))."</b></td><tr>";
        }else{
           $html .="<tr><td colspan='8' style='text-align:center;height:100px; line-height:50px; font-size:18px;'>No Data Found</td></tr>";
        }

    $html .="</table>";

    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4-L');
    $mpdf->SetFooter('  |Page {PAGENO} of {nb}| Powered By GPITG LTD {DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($html);
    $mpdf->Output();
?>
    