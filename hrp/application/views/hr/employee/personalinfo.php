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
        Personal Information
    </div>
    <div class="formdata-content">
        
        <?php echo form_open_multipart('hr/personalinfo/'.$edit); ?>
        
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
                    <td>First Name<span>*</span></td>
                    <td><input type="text" name="fname" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->FirstName:set_value('fname')) ;?>"/>
                    <?php echo form_error('fname'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Middle Name</td>
                    <td><input type="text" name="mname" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->MiddleName:set_value('mname')) ;?>"/>
                    <?php echo form_error('mname'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Last Name<span>*</span></td>
                    <td><input type="text" name="lname" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->LastName:set_value('lname')) ;?>"/>
                    <?php echo form_error('lname'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Date of Birth<span>*</span></td>
                    <td><input type="text" name="dob" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->dob:set_value('dob')) ;?>"/>
                 <img
                                                            style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].dob,'yyyy-mm-dd',this)" />       
                    <?php echo form_error('dob'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>Region<span>*</span></td>
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
                         <td>District<span>*</span></td>
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
                     <tr>
                    <td>Gender<span>*</span></td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->Sex:set_value('sex')) ;?>
                        <select name="sex">
                            <option value=""> Select Gender</option>
                            <option <?php echo ($sel =='M')? 'selected="selected"':''; ?> value="M"> Male</option>
                            <option <?php echo ($sel =='F')? 'selected="selected"':''; ?> value="F"> Female </option>
                            
                        </select>
                    <?php echo form_error('sex'); ?>
                    </td>
                    </tr>
                
                        </table>
                    </td>
                    <td>
                        <table>
                <tr>
                    <td>Employee ID<span>*</span></td>
                    <td><input type="text" name="employee" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->EmployeeId:set_value('employee')) ;?>"/>
                    <?php echo form_error('employee'); ?>
                    </td>
                    </tr>
                    
                     <tr>
                    <td>Marital Status<span>*</span></td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->MaritalStatus:set_value('marital')) ;?>
                        <select name="marital">
                            <option value=""> Select Marital Status</option>
                            
                        <?php   foreach ($marital as $key => $value) { ?>
                            <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('marital'); ?>
                    </td>
                    </tr>
                      <tr>
                         <td>Education Level<span>*</span></td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->EducationLevel:set_value('education')) ;?>
                        <select name="education">
                            <option value=""> Select Education Level</option>
                            
                        <?php   foreach ($educationlevel as $key => $value) { ?>
                            <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('education'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Religion</td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->Religion:set_value('religion')) ;?>
                        <select name="religion">
                            <option value=""> Select Religion</option>
                            
                        <?php   foreach ($religion as $key => $value) { ?>
                            <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('religion'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Photo<span></span></td>
                    <td>
                        <input type="file" name="file" />
                     <?php if(isset ($photo)){ ?>
                         <div  class="error" ><?php echo $photo; ?></div>
                                            <?php } ?>
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