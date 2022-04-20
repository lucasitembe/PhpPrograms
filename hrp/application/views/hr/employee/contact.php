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
        
        <?php echo form_open_multipart('hr/contact/'.$edit); ?>
        
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
                <tr>
                    <td>Street</td>
                    <td><input type="text" name="street" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Street:set_value('street')) ;?>"/>
                    <?php echo form_error('street'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Postal Code</td>
                    <td><input type="text" name="postal" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Postal:set_value('postal')) ;?>"/>
                    <?php echo form_error('postal'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>Region</td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->Region:set_value('region')) ;?>
                        <select id="city" name="region">
                            <option value=""> Select Religion</option>
                            
                        <?php   foreach ($region as $key => $value) { ?>
                            <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('region'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>District</td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->District:set_value('district')) ;?>
                        <select id="district" name="district">
                            <option value=""> Select District</option>
                            
                        <?php   foreach ($district as $key => $value) { ?>
                            <option class="<?php echo $value->parent; ?>" <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('district'); ?>
                    </td>
                    </tr>
                    
                
                        </table>
                    </td>
                    <td>
                        <table>
                <tr>
                    <td>Home Telephone</td>
                    <td><input type="text" name="hometele" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->LandLine:set_value('hometele')) ;?>"/>
                    <?php echo form_error('hometele'); ?>
                    </td>
                    </tr>
                        
                <tr>
                    <td>Mobile</td>
                    <td><input type="text" name="mobile" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Mobile:set_value('mobile')) ;?>"/>
                    <?php echo form_error('mobile'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Email:set_value('email')) ;?>"/>
                    <?php echo form_error('email'); ?>
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