    

<?php
        $id= $invoice[0]['client_id'];                                    
        $this->db->select('*');
        $this->db->from('tbl_clients s');
        $this->db->where('client_id',$id);

        $query = $this->db->get()->result_array();
        
    

                                            ?>
                                            

<table width ='100%'>
<tr style='border:0;'>
<td td width ='50%' style='padding-left:20px; border:0;' >
    <p><b>Adress:</b>P.O Box 2356</p>
        <p><b>City:</b>Dar es Salaam </p>
            <p><b>Phone:</b>+255 750024</p>
                <p><b>Fax:</b>5674</p>

</td>
<td td width ='50%' style='padding-left:200px; border:0;'>
<p><b>Date:</b> <?php echo $invoice[0]['invoice_date'] ;?></p>
        <p><b>Invoice No:</b> <?php echo $invoice[0]['invoice_id'] ;?> </p>
            <p><b>Customer Id:</b><?php echo $invoice[0]['user_id'] ;?></p>
            
</td>
</tr>
<tr style='border:0;'>
    <td td width ='50%' style='padding-left:20px; padding-top:20px; padding-bottom:20px; border:0;' >
    <p color='#34f'><b>Bill To:</b></p>
        <p><b>Client Name:</b> <?php echo $query[0]['client_name'];
?> </p>
            <p><b>Phone: <?php echo $query[0]['contact_phone'];
?></b></p>
                <p><b>Fax:</b><?php echo $query[0]['fax'];
?></p>

<p><b>Email:</b><?php echo $query[0]['email'];
?></p>

</td>


    </tr>



</table>

<!-- invoice content -->

<table class="table">
    <thead>
        <tr>
            <th width="5%">sn</th>
            <th>Description</th>
            <th width="20%" style='text-align: right;'>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
    $i = 1;
    if(count($invoice) > 0){
        $total_amount = 0;
    
                       
          foreach ($invoice as $dt) {
            $total_amount += $dt['amount'];

                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . $dt['narration'] . "</td>";
                                     echo "<td style='text-align: right;'>" . number_format($dt['amount'],2) ."</td>";
                                    
                                    echo "</tr>";


    }?>
        <tr>
            
            <th colspan="2">Total</th>
            <th style='text-align: right;'><?= number_format($total_amount,2) ?></th>
        </tr>
    <?php }//end of checking the invoice count  ?>
    </tbody>
</table>


<table>

    <tr style='border:0;'>
<td td width ='50%' style='padding-left:20px; padding-top:200px; border:0;' >
    <p><b>Prepared By:</b> <?php echo $invoice[0]['fname'] ." ".$invoice[0]['lname'];?></p>
       
</td>
<td td width ='50%' style='padding-top:200px; padding-left:100px; border:0;' >
    <p><b>Signature:</b> __________________</p>
       
</td>
    </tr>

</table>

                                        

