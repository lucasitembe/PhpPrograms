
<h1 align="center">CREDIT NOTE</h1>
<table class="table table-bordered">
    <tr><td><strong>Issuer Name</strong></td><td><?= $creditnote->suppliername ?></td><td style="text-align: right;"><strong>Address </strong></td><td style="text-align: right;"><?= $creditnote->address ?></td></tr>
    <tr><td><strong>Telephone</strong></td><td><?= $creditnote->telephone ?></td><td style="text-align: right;"><strong>Fax </strong></td><td style="text-align: right;"><?= $creditnote->fax ?></td></tr>
    <tr><td><strong>Email</strong></td><td><?= $creditnote->email ?></td><td style="text-align: right;"><strong>Credit Note Date </strong></td><td style="text-align: right;"><?= $creditnote->credit_note_date ?></td></tr>
    <tr><td><strong>Credit Note Number</strong></td><td><?= $creditnote->credit_note_number ?></td><td style="text-align: right;"><strong>Amount Reduced</strong></td><td style="text-align: right;"><?= number_format($creditnote->amount_to_reduce, 2) ?></td>
    <tr><td><strong>Tax (%)</strong></td><td><?= $creditnote->tax ?></td><td style="text-align: right;"><strong>Invoice #</strong></td><td style="text-align: right;"><?= $creditnote->invoice_id ?></td>
    <tr><td><strong>Invoice Date</strong></td><td><?= $creditnote->invoice_date ?></td><td style="text-align: right;"><strong>Invoice Amount</strong></td><td style="text-align: right;"><?= number_format($creditnote->Amount, 2) ?></td>
    <tr>
        <td colspan="2"><strong>New Invoice Amount ( <small><i>Without tax</i></small> )</strong></td><td colspan="2" style="text-align: right;"><?= number_format($creditnote->Amount - $creditnote->amount_to_reduce, 2) ?></td>
    </tr>
    <tr>
        <td><strong>Remarks</strong></td><td colspan="3"><?= $creditnote->remarks ?></td>

    </tr>


</table>

<br/><br/><br/>
<table width ='100%' class='nobordertable'>
    <tr>
        <td style="text-align: left;"><strong>Prepared By</strong> &nbsp;&nbsp;<?= $creditnote->fname . ' ' . $creditnote->lname ?></td>
        <td style="text-align: right;"><strong>Prepared On</strong> &nbsp;&nbsp;<?= $creditnote->trans_date_time ?></td>
        <!--<td width="30%" style="text-align: center;">_____________________________<br/><strong>Approved By</strong></td>-->
    </tr>
</table>
