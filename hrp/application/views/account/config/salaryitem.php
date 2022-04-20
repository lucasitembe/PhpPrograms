<script type="text/javascript">
$(document).ready(function(){
   var cat = $('#category').val();
   if(cat == 2){
       $("#percent").show();
   }else{
     $("#percent").hide();  
   }
   
   $("#category").change(function(){
       var cat = $('#category').val();
   if(cat == 2){
       $("#percent").show();
   }else{
     $("#percent").hide();  
   }
   });
});
<?php
$acc1 = file_get_contents("http://localhost/Final_One/gaccounting/Api/ledger");
$acc2 = json_decode($acc1);
?>
</script>
<h2 style="padding:0px; margin: 0px;">Salary Items</h2><hr/>
<div class="formdata">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Edit Item'; }else{ echo 'Add New Item';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('account/salaryitem/'.$edit); ?>
        
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
                    <td>Category<span>*</span></td>
                    <td>
                        <select name="category" id="category">
                            <option value=""> --Select--</option>
                            <?php 
                                        foreach ($salarycategory as $key => $value) { 
                                            $sel = (isset ($payee) ? $payee[0]->Category:set_value('category'));
                                            ?>
                            
                            <option <?php echo ($sel ==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php }
                            ?>
                        </select>
                    <?php echo form_error('category'); ?>
                    </td>
                </tr>


                <tr>
                    <td>Item Name<span>*</span></td>
                    <td><input type="text" name="name"  value="<?php echo (isset ($payee) ? $payee[0]->Name:set_value('name')) ;?>"/>
                    <?php echo form_error('name'); ?>
                    </td>
                </tr>
                <tr id="percent" style="display: none;">
                    <td>Deduction Percentage<span>*</span></td>
                    <td><input type="text" name="percent"  value="<?php echo (isset ($payee) ? $payee[0]->Percent:set_value('percent')) ;?>"/>
                    <?php echo form_error('percent'); ?>
                    </td>
                </tr>

                  <tr>
                    <td>Ledger<span>*</span></td>
                    <td>
                        <select required="required" name='ledger' width=20% id='ledger_id'  >                              
                                    <option selected="selected" disabled="disabled" value="">--Select ledger--</option>
                                    <?php foreach ($acc2 as $acc) { ?>
                                        <option value="<?php echo $acc->ledger_id; ?>"><?php echo $acc->ledger_name; ?></option>
                                    <?php } ?>
                                </select>
                    <?php echo form_error('category'); ?>
                    </td>
                </tr>
                
                
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" value="Save"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>

<div class="table_list"  style="padding-top:  20px;">
    <table class="view_data" cellspacing="0" cellpadding="0">
        <th style="width:50px;">S/No</th><th>Category</th><th>Item Name</th><th style="width:150px;">Action</th>
</tr>

<?php 
$i=1;
if(count($payeelist) > 0){
 foreach ($payeelist as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php $cat = $this->account->salarycategory($value->Category); echo $cat[0]->Name ; ?></td>
    <td><?php echo $value->Name; ?></td>
    <td><?php echo anchor('account/salaryitem/'.$value->id,'Edit','style="color:blue;"').' &nbsp; &nbsp; ';
    echo (($value->id == 3 ) ? ' | &nbsp; '.anchor('account/configureovertime/'.$value->id,'Config','style="color:blue;"'):'');
    echo (($value->id > 10 && $value->Category == 2  ) ? ' | &nbsp; '.anchor('account/salaryitemconfig/'.$value->id,'Config','style="color:blue;"'):'');
    ?></td>
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="3" align="center">No data found</td></tr>';
}
?>
 </table>
   
</div>