<script type="text/javascript">
   
    $(document).ready(function(){
        // Smart Wizard     	
 $("#district").chained("#city");
	
    });	
</script>
<div>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" style="width: 200px;">
                <?php
                $this->load->view('hr/employee/menu');
                ?>
            </td>
            <td valign="top">
                <?php
              $in_data=employee_basic_data($id);
              
              ?>
                <h3 style=" color: green; padding: 0px 0px 0px 10px; margin: 5px;">Employee ID: <?php echo $in_data[0]->EmployeeId; ?>  &nbsp; &nbsp; &nbsp; Employee Name : <?php echo $in_data[0]->FirstName.' '.$in_data[0]->LastName; ?></h3>
              
<div class="formdata" style=" width: 800px;">
    <div class="formdata-title">
        <?php $edit=$id;?>
        Contact Details
    </div>
    <div class="formdata-content">
        
        <?php echo form_open_multipart('hr/emergency/'.$edit); ?>
        
        <table style="width: 750px;" border="0">
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
                 <tr><td>
                        <table>
                            <tr><td colspan="2"><b style="color:brown;">First Contact</b></td></tr>
                <tr>
                    <td>Name<span>*</span></td>
                    <td><input type="text" name="name" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Name_1:set_value('name')) ;?>"/>
                    <?php echo form_error('name'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Relation<span>*</span></td>
                    <td><input type="text" name="relation" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Relation_1:set_value('relation')) ;?>"/>
                    <?php echo form_error('relation'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Home Telephone</td>
                    <td><input type="text" name="hometele" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->LandLine_1:set_value('hometele')) ;?>"/>
                    <?php echo form_error('hometele'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Mobile</td>
                    <td><input type="text" name="mobile" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Mobile_1:set_value('mobile')) ;?>"/>
                    <?php echo form_error('mobile'); ?>
                    </td>
                    </tr>
                     
                
                        </table>
                    </td>
                    <td>
                           <table>
                            <tr><td colspan="2"><b style="color:brown;">Second Contact</b></td></tr>
                <tr>
                    <td>Name<span>*</span></td>
                    <td><input type="text" name="name2" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Name_2:set_value('name2')) ;?>"/>
                    <?php echo form_error('name2'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Relation<span>*</span></td>
                    <td><input type="text" name="relation2" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Relation_2:set_value('relation2')) ;?>"/>
                    <?php echo form_error('relation2'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Home Telephone</td>
                    <td><input type="text" name="hometele2" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->LandLine_2:set_value('hometele2')) ;?>"/>
                    <?php echo form_error('hometele2'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Mobile</td>
                    <td><input type="text" name="mobile2" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Mobile_2:set_value('mobile2')) ;?>"/>
                    <?php echo form_error('mobile2'); ?>
                    </td>
                    </tr>
                     
                
                        </table>
                        
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
            </td>
        </tr>
    </table>
</div>