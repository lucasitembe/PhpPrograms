
<h1 align="center">PAYMENT VOUCHER</h1>
<table class="table table-bordered">
    <tr><td><strong>Payee Name</strong></td><td><?= $voucher->suppliername ?></td><td style="text-align: right;"><strong>Amount Paid</strong></td><td style="text-align: right;"><?= number_format($voucher->amount_paid,2) ?></td></tr>
    <tr><td><strong>Check #</strong></td><td><?= $voucher->check_number ?></td><td style="text-align: right;"><strong>Date</strong></td><td style="text-align: right;"><?= $voucher->cashbook_date ?></td>
</tr>

</table>

<br/><br/><br/>
<table width ='100%' class='nobordertable'>
<tr>
    <td width="30%" style="text-align: center;"><?= $voucher->fname . ' ' . $voucher->lname ?><br/><strong>Prepared By</strong></td>
    <td width="30%" style="text-align: center;">_____________________________<br/><strong>Checked by</strong></td>
    <td width="30%" style="text-align: center;">_____________________________<br/><strong>Approved By</strong></td>
</tr>
</table>
