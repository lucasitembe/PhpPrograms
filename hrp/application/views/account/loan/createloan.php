<h2 style="padding:0px; margin: 0px;">Loan Management</h2><hr/>
<div class="formdata">
    <div class="formdata-title">
        Enter Employee Number
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('account/createloan/'); ?>
        
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
                    <td>Employee Number<span>*</span></td>
                    <td>
                        <input type="text" name="employee_id" id="employee_id" value="<?php echo set_value('employee_id'); ?>"/>
                        <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/search.png"
                                                            onclick='targetitem = document.forms[0].employee_id;
                                                                window.open("employeelist_search/"+targetitem.name, "dataitem", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23");' />         
                    <?php echo form_error('name'); ?>
                    <?php echo form_error('employee_id'); ?>
                    </td>
                </tr>
                
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" value="Load Loans"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>