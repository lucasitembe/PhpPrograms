<h2 style="padding:0px; margin: 0px;">Work Station</h2><hr/>
<div class="formdata col-md-8 col-md-offset-2">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Edit Workstation Information'; }else{ echo 'Add New Workstation';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('hr/addworkstation/'.$edit); ?>
        
        <table style="float:center;">
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
                    <td><input type="text" name="name" class="form-control" value="<?php echo (isset ($workstation) ? $workstation[0]->Name:set_value('name')) ;?>"/>
                    <?php echo form_error('name'); ?>
                    </td>
                </tr>
                <tr>
                    <td>Description<span>*</span></td>
                    <td><textarea  class="form-control"  name="desc"><?php echo (isset ($workstation) ? $workstation[0]->Description:set_value('desc')) ;?></textarea>
                    <?php echo form_error('desc'); ?>
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