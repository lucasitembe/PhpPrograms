 <h2>Configuration for <?php echo $item[0]->Name; ?></h2><hr/>
   
    <div class="formdata">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Manage Configuration'; }else{ echo 'Manage Configuration';} ?>
    </div>
    <div class="formdata-content">

        <?php echo form_open('account/configureovertime/'.$edit); ?>
        
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
                    <td>Amount per Hours <span>*</span></td>
                    <td><input type="text" name="hours_amount" value="<?php echo $item[0]->Percent ?>"/>
                    <?php echo form_error('hours_amount');?>
                    </td>
                </tr>
                 <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" value="Save"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
    <?php echo form_close(); ?>
    </div>
    </div>