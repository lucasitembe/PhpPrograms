
<?php
if (count($debtnote) > 0) {
    ?>
    <div class="table-responsive"> 
        <table class="table nobordertable"> 
            <tr> 
                <th>Debit Note No</th> 
                <th>Debit Note Date</th> 
                <th>Invoice No</th>  
            </tr> 
            <tr>
                <td><?= $debtnote[0]->debt_note_id ?></td>
                <td><?= $debtnote[0]->debt_note_date ?></td>
                <td><?= $debtnote[0]->invoice_no ?></td>
            </tr>         
        </table> 
        <table class="table nobordertable"> 
            <tr>
                <td><b>Supplier:</b> <?= $debtnote[0]->suppliername ?></td>
            </tr>
            <tr>
                <td><b>Address:</b> <?= $debtnote[0]->address ?></td>
            </tr>
            <tr>
                <td><b>Phone no:</b> <?= $debtnote[0]->contact_phone ?></td>
            </tr>  
            <tr>
                <td><b>Fax:</b> <?= $debtnote[0]->fax ?></td>
            </tr>   
        </table> 
        <table class="table nobordertable"> 
            <legend></legend>
            <tr>
                <td><b>Remark(s): </b><?= $debtnote[0]->remarks ?></td>
            </tr>

        </table>
        <?php $new_amount = $debtnote[0]->Amount - $debtnote[0]->amount_to_reduce; ?>
        <table class="table nobordertable"> 
            <legend></legend>
            <tr>
                <td style="text-align: right"><b>Old Invoice Amount: </b><?= $debtnote[0]->Amount ?></td>
            </tr>
            <tr>
                <td style="text-align: right"><b>Reduced Amount: </b><?= $debtnote[0]->amount_to_reduce ?></td>
            </tr>
            <tr>
                <td style="text-align: right"><b>New Invoice Figure: </b><?= $new_amount ?></td>
            </tr>
            <tr>
                <td style="text-align: right"><b>Tax (%): </b><?= $debtnote[0]->tax ?></td>
            </tr>
            <tr>
                <td style="text-align: right"><b>New Grant Total: </b><?= $new_amount ?></td>
            </tr>     
        </table>  
    </div>

    <?php
    echo '<br/>';
}
?>