<h2 style="padding:0px; margin: 0px;">Loan Management</h2><hr/>
<div class="formdata">
    <div class="formdata-title">
        Create Loan
    </div>
    <div class="formdata-content">
        
        <?php 
        $ed='';
        if(isset($edit_data)){
            $ed.='/'.$edit_data;
        }
        echo form_open('account/createloan/'.$employee_id.$ed); ?>
        
        <table>
             <?php 
 
            if($this->session->flashdata('message') !=''){
            	?>
            	<tr>
            	<td colspan="2">
            	<div class="message">
            	<?php echo $this->session->flashdata('message'); ?>
            	</div>
            	</td>
            	</tr>
            	<?php
            }else if(isset($error_in)){
            	?>
            	<tr>
            	<td colspan="2" >
            	<div class="message">
            	<?php echo $error_in; ?>
            	</div>
            	</td>
            	</tr>
            	
            	<?php 
            }
            
            ?>
                <tr>
                    <td>Base Amount<span>*</span></td>
                    <td>
                        <input type="text" name="bamount" style="text-align: right;" value="<?php echo (isset ($loan_info)) ? $loan_info[0]->Base_Amount:set_value('bamount'); ?>"/>
                    <?php echo form_error('bamount'); ?>
                    </td>
                </tr>
                <tr>
                    <td>Interest Amount<span>*</span></td>
                    <td>
                        <input type="text" name="iamount" style="text-align: right;" value="<?php echo (isset ($loan_info)) ? $loan_info[0]->Interest:set_value('iamount'); ?>"/>
                    <?php echo form_error('iamount'); ?>
                    </td>
                </tr>
                <tr>
                    <td>Loan Term<span>*</span></td>
                    <td>
                        <input type="text" name="term" style="text-align: right;" value="<?php echo (isset ($loan_info)) ? $loan_info[0]->Terms:set_value('term'); ?>"/>Month(s)
                    <?php echo form_error('term'); ?>
                    </td>
                </tr>
                
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" value="Create Loan"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>




<div class="table_list"  style="padding-top:  20px;">
    <table class="view_data" cellspacing="0" cellpadding="0">
        <th style="width:50px;">S/No</th><th>Loan Number</th><th>Base Amount</th><th>Interest Amount</th><th>Total Loan Amount</th><th>Term (Month)</th><th>Installment Amount</th><th style="width:100px;">Action</th>
</tr>

<?php 
$i=1;
if(count($employee_loan) > 0){
 foreach ($employee_loan as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    
    <td><?php echo $value->Loan_Number; ?></td>
    <td align="right"><?php echo number_format($value->Base_Amount,2); ?> &nbsp; </td>
    <td align="right"><?php echo number_format($value->Interest,2); ?>  &nbsp; </td>
    <td align="right"><?php echo number_format($value->Loan_Amount,2); ?>  &nbsp; </td>
    <td align="right"><?php echo $value->Terms; ?>  &nbsp; </td>
    <td align="right"><?php echo number_format($value->Installment_Amount,2); ?>  &nbsp; </td>
    <td align="center"><?php echo ($value->is_approved == 1) ? 'Approved':  anchor('account/createloan/'.$employee_id.'/'.$value->id,'Edit'); ?>  &nbsp; </td>
    
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="8" align="center">No data found</td></tr>';
}
?>
 </table>
   
</div>