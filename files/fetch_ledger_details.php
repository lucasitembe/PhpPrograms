<?php
include("./includes/connection.php");
	$Sponsor_ID = mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);
	$Start_Date = mysqli_real_escape_string($conn,$_POST['Start_Date']);
	$End_Date = mysqli_real_escape_string($conn,$_POST['End_Date']);

	$filter_paymets=" ";
	$filter_invoice=" ";
	if(isset($Sponsor_ID) && $Sponsor_ID !==''){
		$filter_paymets=" AND osp.Customer_ID=$Sponsor_ID ";
		$filter_invoice=" AND  inv.sponsor_id=$Sponsor_ID ";
	}
?>
<table width='90%'>
        <?php
            $brought_forward=0;
            $today=$Start_Date;
            $select_bf_balance=mysqli_query($conn,"SELECT SUM(ospi.Price * ospi.Quantity) total_amount FROM tbl_other_sources_payment_item_list ospi, tbl_other_sources_payments osp WHERE osp.Payment_ID=ospi.Payment_ID $filter_paymets AND ospi.remarks='voucher_payment' AND osp.Receipt_Date < '$today' ") or die(mysqli_error($conn));
            $total_invoice_amount=mysqli_query($conn,"SELECT SUM(inv.amount) total_amount FROM  tbl_invoice inv WHERE inv.invoice_date < '$today' $filter_invoice ") or die(mysqli_error($conn));

            if((mysqli_num_rows($select_bf_balance) > 0 ) && (mysqli_num_rows($total_invoice_amount) > 0)){
                $brought_forward=(mysqli_fetch_assoc($select_bf_balance)['total_amount']-mysqli_fetch_assoc($total_invoice_amount)['total_amount']);
            }

        ?>
        <tr style='background-color:gray;color:white;'><th colspan='8' style='text-align:right;'>B/F: <?=number_format($brought_forward)?></th></tr>
        <tr style='background-color:gray;color:white;'><th>SN</th><th>Invoice No/Cheque No</th><th>Sponsor</th><th>Prepared Date</th><th>Narration</th><th>Debt</th><th>Payments</th><th>Balance</th></tr>
        <?php
            $count=1;
            $results=mysqli_query($conn,"SELECT inv.Invoice_ID,inv.sponsor_id AS Sponsor, inv.invoice_date, inv.narration, inv.amount, inv.trans_datetime as time, 'invoice' as selected_from FROM `tbl_invoice` inv WHERE  inv.invoice_date BETWEEN  '$Start_Date' AND '$End_Date' $filter_invoice UNION ALL SELECT osp.Payment_ID as Invoice_ID, osp.Customer_ID AS Sponsor, osp.Receipt_Date as invoice_date, osp.narration, ospi.Price * ospi.Quantity as amount, ospi.Transaction_Date_And_Time as time, 'payments' as selected_from FROM tbl_other_sources_payment_item_list ospi, tbl_other_sources_payments osp where ospi.remarks='voucher_payment' and osp.Payment_ID=ospi.Payment_ID $filter_paymets AND osp.Receipt_Date  BETWEEN  '$Start_Date' AND '$End_Date'  order by time asc ")or die(mysqli_error($conn));
            $debit_amount=0;
            $credit_amount=0;
        if(mysqli_num_rows($results) > 0){    
            while($row=mysqli_fetch_assoc($results)){
                extract($row);
                $Sponsor=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID=$Sponsor"))['Guarantor_Name'];
                $report_type="";
                if($selected_from == 'invoice'){
                    $brought_forward-=$amount;
                    $debit_amount+=$amount;
                    $report_type="invoice";
                }else if($selected_from == 'payments'){
                    $brought_forward+=$amount;
                    $credit_amount+=$amount;
                    $report_type="voucher";
                }
                echo "<tr><td>".$count."</td><td onclick='view_invoice(\"{$Invoice_ID}\",\"{$report_type}\")'><a href='#' style='display:block;'>".$Invoice_ID."</a></td><td>{$Sponsor}</td><td style='text-align:center;'>".$invoice_date."</td><td>".$narration."</td><td  style='text-align: right;'>".number_format((($selected_from=='invoice')?$amount:0))."</td><td  style='text-align: right;'>".number_format((($selected_from=='payments')?$amount:0))."</td><td  style='text-align: right;'>".number_format(($brought_forward))."</td><tr>";
                $count++;
            }
            echo "<tr><td colspan='5' style='text-align:center;'><b>Total Amount</b></td><td  style='text-align: right;'><b>".number_format($debit_amount)."</b></td><td  style='text-align: right;'><b>".number_format($credit_amount)."</b></td><td  style='text-align: right;'><b>".number_format(($brought_forward))."</b></td><tr>";
        }else{
            echo "<tr><td colspan='8' style='text-align:center;height:100px; line-height:50px; font-size:18px;'>No Data Found</td></tr>";
        }

        ?>
    </table>