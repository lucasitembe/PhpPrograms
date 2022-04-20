<h2 style="padding:0px; margin: 0px;">KPIs Category</h2><hr/>


<div class="formdata">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Edit KPIs Category '; }else{ echo 'Add New KPIs Category';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('hr/kpicategory/'.$edit); ?>
        
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
                    <td>KPI Category Name<span>*</span></td>
                    <td><input type="text" name="name" value="<?php echo (isset ($kpicategorydata) ? $kpicategorydata[0]->name:set_value('name')) ;?>"/>
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

<div style="height: 10px;">&nbsp;</div>


<div class="table_list" >
    <table class="view_data" cellspacing="0" cellpadding="0">
        <th style="width:50px;">S/No</th><th>Name</th><th style="width:100px;">Action</th>
</tr>

<?php 
$i=1;
if(count($kpicategorylist) > 0){
 foreach ($kpicategorylist as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->name; ?></td>
    <td><?php echo anchor('hr/kpicategory/'.$value->id,'Edit','style="color:blue;"'); ?></td>
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="3" align="center">No data found</td></tr>';
}
?>
 </table>
 
</div>
