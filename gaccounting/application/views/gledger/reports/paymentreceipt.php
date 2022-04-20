<h1 align="center">PAYMENT RECEIPT</h1>
<table class="table table-bordered">
    <tr><td ><strong>Payer Name</strong></td><td><?= $voucher->sponsor ?></td><td style="text-align: right;"><strong>Amount Paid</strong></td><td style="text-align: right;"><?= number_format($voucher->amount_paid,2) ?></td></tr>
    <tr><td><strong>Check #</strong></td><td><?= $voucher->check_number ?></td><td style="text-align: right;"><strong>Date</strong></td><td style="text-align: right;"><?= $voucher->cashbook_date ?></td>
</tr>

</table>

<br/><br/><br/>
<table width ='100%' class='nobordertable'>
<tr>
    <td width="30%" style="text-align: center;"><strong>Received By </strong><?= $voucher->fname . ' ' . $voucher->lname ?></td>
</tr>
</table>