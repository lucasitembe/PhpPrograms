<h2 style="padding:0px; margin: 0px;">Manage Department</h2><hr/>


<div class="col-md-6 col-md-offset-3 formdata">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Edit Department Information'; }else{ echo 'Add New Department';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('hr/department/'.$edit); ?>
        
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
                    <td><input type="text" name="name" class="from-control" value="<?php echo (isset ($departmentdata) ? $departmentdata[0]->Name:set_value('name')) ;?>"/>
                    <?php echo form_error('name'); ?>
                    </td>
                </tr>
              
                
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" class="btn" value="Save"/>
                             <input type="button" class="btn" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>

<div style="height: 10px;">&nbsp;</div>


<div class="col-md-10 col-md-offset-1 table_list" style="padding-top:10px; padding-bottom:50px;" >
    <table class="table view_data" cellspacing="0" cellpadding="0">
        <th style="width:50px;">S/No</th><th>Name</th><th style="width:100px;">Action</th>
</tr>

<?php 
$i=1;
if(count($department) > 0){
 foreach ($department as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->Name; ?></td>
    <td>
    <a href="<?php echo site_url()?>/hr/department/<?php echo $value->id?>" class="btn"><span><i class="fa fa-edit"></i> Edit</span></a>
    
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="3" align="center">No data found</td></tr>';
}
?>
 </table>
 
</div>
