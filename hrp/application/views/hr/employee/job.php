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
        Job Information
    </div>
    <div class="formdata-content">
        
        <?php echo form_open_multipart('hr/job/'.$edit); ?>
        
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
                                     <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>Position<span>*</span></td>
                    <td>
                        <select name="position">
                            <option value="">Select Position</option>
                            <?php 
                            $sel = (isset ($jobinfo) ? $jobinfo[0]->Position:set_value('position'));
                            foreach ($position as $key => $value) { ?>
                            <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                         <?php } ?> 
                        </select>   
                    <?php echo form_error('position'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Work Station<span>*</span></td>
                    <td>
                        <select name="workstation">
                            <option value="">Select WorkStation</option>
                            <?php 
                            $sel = (isset ($jobinfo) ? $jobinfo[0]->WorkStation:set_value('workstation'));
                            foreach ($workstation as $key => $value) { ?>
                            <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                         <?php } ?> 
                        </select>   
                    <?php echo form_error('workstation'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>Department<span>*</span></td>
                    <td>
                        
                        <select  name="department">
                            <option value="">Select Department</option>
                            <?php 
                            $sel = (isset ($jobinfo) ? $jobinfo[0]->Department:set_value('department'));
                            foreach ($department as $key => $value) { ?>
                            <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                         <?php } ?>
                            
                        </select>
                    <?php echo form_error('department'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>Joined Date<span>*</span></td>
                    <td>
                        <input type="text" name="joindate" value="<?php echo (isset ($jobinfo) ? $jobinfo[0]->Joindate:set_value('joindate')) ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].joindate,'yyyy-mm-dd',this)" />       
                   
                    <?php echo form_error('joindate'); ?>
                    </td>
                    </tr>
                     
                
                        </table>
                    </td>
                    <td>
                        <table>
                            <tr><td><b style="color: brown;">Employement Contract</b></td></tr>
            <tr>
                         <td>Contract Type<span>*</span></td>
                    <td>
                        
                        <select  name="contract">
                            
                            <option value="">Select WorkStation</option>
                            <?php 
                            $sel = (isset ($jobinfo) ? $jobinfo[0]->ContractType:set_value('contract'));
                            foreach ($contracttype as $key => $value) { ?>
                            <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                         <?php } ?> 
                        </select>
                    <?php echo form_error('contract'); ?>
                    </td>
                    </tr>
                        
                 <tr>
                         <td>Start Date<span>*</span></td>
                    <td>
                        <input type="text" name="startdate" value="<?php echo (isset ($jobinfo) ? $jobinfo[0]->Startdate:set_value('startdate')) ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].startdate,'yyyy-mm-dd',this)" />       
                   
                    <?php echo form_error('startdate'); ?>
                    </td>
                    </tr>
                 <tr>
                         <td>End Date<span>*</span></td>
                    <td>
                        <input type="text" name="enddate" value="<?php echo (isset ($jobinfo) ? $jobinfo[0]->Enddate:set_value('enddate')); ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].enddate,'yyyy-mm-dd',this)" />       
                   
                    <?php echo form_error('enddate'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Contract</td>
                    <td>
                        <input type="file" name="file" />
                     <?php 
                     
                     if(isset ($photo)){ ?>
                         <div  class="error" ><?php echo $photo; ?></div>
                                            <?php } ?>
                    </td>
                    </tr>
                    <?php
                    if(isset ($jobinfo)){
                        if($jobinfo[0]->Contract != ''){ ?>
                    <tr>
                        <td colspan="2" align="center">
                            <?php echo anchor(base_url().'uploads/contract/'.$jobinfo[0]->Contract ,'Download','target="_blank"');
                            ?>
                        </td>
                    </tr>
                       <?php }
                            
                    }
                    ?>
                    
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