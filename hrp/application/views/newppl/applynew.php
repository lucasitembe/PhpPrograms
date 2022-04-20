
<div style="font-size: 13px/20px normal">
    
  <script type="text/javascript">
   
    $(document).ready(function(){
        // Smart Wizard     	
 $("#district").chained("#city");
	
    });	
</script>
<h2 style="padding:0px; margin: 0px;">Application</h2><hr/>


<div class="formdata" style=" width: 800px;">
    <div class="formdata-title">
        <?php $edit= (isset($id) ? $id : '');
        echo  'Apply for : '.$vacancy_info[0]->Title; ?>
    </div>
    <div class="formdata-content" >
        
        <?php echo form_open_multipart('hrnew/applynew/'.$edit); ?>
        
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
                    <td><input type="text" name="fname" value="<?php echo set_value('fname') ;?>"/>
                    <?php echo form_error('fname'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Middle Name</td>
                    <td><input type="text" name="mname" value="<?php echo set_value('mname') ;?>"/>
                    <?php echo form_error('mname'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Last Name<span>*</span></td>
                    <td><input type="text" name="lname" value="<?php echo set_value('lname') ;?>"/>
                    <?php echo form_error('lname'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Date of Birth<span>*</span></td>
                    <td><input type="text" name="dob" value="<?php echo set_value('dob') ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].dob,'yyyy-mm-dd',this)" />       
                    <?php echo form_error('dob'); ?>
                    </td>
                    </tr>
                   
                     <tr>
                    <td>Gender<span>*</span></td>
                    <td>
                        <?php $sel= set_value('sex') ;?>
                        <select name="sex">
                            <option value=""> Select Gender</option>
                            <option <?php echo ($sel =='M')? 'selected="selected"':''; ?> value="M"> Male</option>
                            <option <?php echo ($sel =='F')? 'selected="selected"':''; ?> value="F"> Female </option>
                            
                        </select>
                    <?php echo form_error('sex'); ?>
                    </td>
                    </tr>
                <tr>
                    
                    <td>Email<span></span></td>
                    <td><input type="text" name="email" value="<?php echo set_value('email') ;?>"/>
                    <?php echo form_error('email'); ?>
                    </td>
                </tr>
                
                <tr>
                    
                    <td>Mobile<span></span></td>
                    <td><input type="text" name="mobile" value="<?php echo set_value('mobile') ;?>"/>
                    <?php echo form_error('mobile'); ?>
                    </td>
                </tr>
                    
                        </table>
                    </td>
                    <td>
                        <table>
                
                    
                     <tr>
                    <td>Marital Status<span>*</span></td>
                    <td>
                        <?php $sel= set_value('marital') ;?>
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
                        <?php $sel= set_value('education') ;?>
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
                    <td>Application Letter<span>*</span></td>
                    <td>
                        <input type="file" name="file" />
                     <?php if(isset ($photo)){ ?>
                         <div  class="error" ><?php echo $photo; ?></div>
                                            <?php } ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Current CV<span>*</span></td>
                    <td>
                        <input type="file" name="file1" />
                     <?php if(isset ($photo1)){ ?>
                         <div  class="error" ><?php echo $photo1; ?></div>
                                            <?php } ?>
                    </td>
                    </tr>
                        </table>
                        
                    </td>
                </tr>
               
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" value="Save" name="SAVE"/>
                            <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>

<p style="height: 80px;">&nbsp;</p>

</div>