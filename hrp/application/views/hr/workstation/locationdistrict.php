<h2 style="padding:0px; margin: 0px;">Manage Location</h2><hr/>


<div class="formdata col-md-6 col-md-offset-3">
    <div class="formdata-title">
        <?php $edit='';
        $re=$region;
        if(isset($id)){$edit=$id; echo 'Edit District Information'; }else{ echo 'Add New District';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('hr/locationdistrict/'.$re.'/'.$edit); ?>
        
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
                    <td>Name<span>*</span></td>
                    <td><input type="text" name="name" value="<?php echo (isset ($locationdata) ? $locationdata[0]->Name:set_value('name')) ;?>"/>
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


<div class="table_list col-md-8 col-md-offset-2 "  style="padding-top: 10px;" >
    <table class="table table-bordered view_data" cellspacing="0" cellpadding="0">
        <th style="width:50px;">S/No</th><th>Name</th><th style="width:100px;">Action</th>
</tr>

<?php 
$i=1;
if(count($location) > 0){
 foreach ($location as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->Name; ?></td>
    <td><?php echo anchor('hr/locationdistrict/'.$re.'/'.$value->id,'Edit','style="color:blue;"'); ?></td>
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="3" align="center">No data found</td></tr>';
}
?>
 </table>
 
</div>
