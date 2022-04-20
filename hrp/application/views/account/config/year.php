<h2 style="padding:0px; margin: 0px;">Manage Years</h2><hr/>
<div class="formdata">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Edit Year'; }else{ echo 'Add New year';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('account/year/'.$edit); ?>
        
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
                    <td>Year<span>*</span></td>
                    <td><input type="text" name="name"  value="<?php echo (isset ($yearinfo) ? $yearinfo[0]->Name:set_value('name')) ;?>"/>
                    <?php echo form_error('name'); ?>
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
        <th style="width:50px;">S/No</th><th>Year</th><th style="width:100px;">Action</th>
</tr>

<?php 
$i=1;
if(count($yearlist) > 0){
 foreach ($yearlist as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->Name; ?></td>
    <td><?php echo anchor('account/year/'.$value->id,'Edit','style="color:blue;"'); ?></td>
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="3" align="center">No data found</td></tr>';
}
?>
 </table>
   
</div>