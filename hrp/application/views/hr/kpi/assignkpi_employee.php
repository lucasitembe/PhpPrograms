<h2 style="padding:0px; margin: 0px;"> Record KPIs</h2><hr/>


<div class="formdata" style=" width: 1000px;">
    <div class="formdata-title">
    Record KPIs   
    </div>
    <div class="formdata-content" style="width: 97%;">
        
        <?php echo form_open('hr/assignkpi_employee/','style="width:100%;"'); ?>
        
        <table style=" width: 100%;">
             <?php 
 
            if($this->session->flashdata('message') !=''){
            	?>
            	<tr>
            	<td colspan="4">
            	<div class="message">
            	<?php echo $this->session->flashdata('message'); ?>
            	</div>
            	</td>
            	</tr>
            	<?php
            }else if(isset($error_in)){
            	?>
            	<tr>
            	<td colspan="4" >
            	<div class="message">
            	<?php echo $error_in; ?>
            	</div>
            	</td>
            	</tr>
            	
            	<?php 
            }
            ?>
                <tr>
                    <td>Employee ID<span>*</span></td>
                    <td>
            <input type="text" id="employee" name="employee" value="<?php echo $this->session->userdata('employee_id'); ;?>"/>
                   <?php echo form_error('employee'); ?>
                    </td>
                    
                     <td> Date<span>*</span></td>
                    <td>
                        <input type="text" name="date" value="<?php echo set_value('date') ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].date,'yyyy-mm-dd',this)" />       
                   
                    <?php echo form_error('date'); ?>
                    </td>
                    <td>&nbsp;</td>
                    <td><input type="submit" value="Load KPIs"/></td>
                </tr>
              
                
        </table>
        <?php echo form_close() ?>
    </div>
</div>

<div style="height: 10px;">&nbsp;</div>
  <?php
  if(isset ($id)){
              $in_data=employee_basic_data($id);
              
   ?>
 <?php echo form_open('hr/assignkpi_employee/','style="width:100%;padding:30px 0px;"'); ?>
<input type="hidden" name="employee" value="<?php echo $employee; ?>"/>
<input type="hidden" name="date" value="<?php echo $date; ?>"/>
<table style="width: 100%; margin-left: 0px;" class="formdata">
    <tr>
        <td style="width: 100px;" valign="top">
           <img src="<?php echo base_url().'uploads/photo/'.employee_photo($id); ?>" style="width: 150px; border: 2px solid #CCCCCC; height: 180px;"/>
        </td>
        <td valign="top" >
            <h3 style=" color: green; padding: 0px 0px 0px 10px; margin: 5px;">Employee ID: <?php echo $in_data[0]->EmployeeId; ?>  &nbsp; &nbsp; &nbsp; Employee Name : <?php echo $in_data[0]->FirstName.' '.$in_data[0]->LastName; ?></h3>
            <div >
                <?php
                if(count($kpicategory) > 0){
                    $ip=0;
    foreach ($kpicategory as $key => $value) { ?>
                <div style="float: left; width: 480px; padding: 10px;  margin: 0px 10px 20px 10px; border: 1px solid #ccc;">
                    <div style="margin-left: 20px; font-weight: bold; font-size: 17px;">KPI Category : <?php
                    $ct = $this->HR->kpicategorylist($value->kpi_category);
                    echo $ct[0]->name; ?></div>
                    <table style="width: 100%;" class="view_data" cellspacing="0" cellpadding="0">
                        <tr><th style="width: 40px;">S/No</th><th>Indicator</th><th style="width: 100px;">Status</th></tr>
             <?php
              $list = $this->db->query("SELECT i.id,i.name,kp.kpi_indicator FROM kpi_indicator as i, kpi_employee as kp WHERE employee_auto=$id AND Employee='$employee' AND i.id=kp.kpi_indicator ORDER BY id ASC ")->result();
              
            if(count($list) > 0){
                $i=1;
                $ip++;
                foreach ($list as $k => $v) { ?>
                        <tr>
                            <td align="right"><?php echo $i++; ?>  &nbsp; </td>
                            <td><?php echo $v->name; ?></td>
                            <td><select style="width: 100px;" name="indicator_<?php echo $v->id ?>">
                                    <option value=""> --Select --</option>
                                    <?php
                                    $sel = set_value('indicator_'.$v->id);
                                     foreach ($kpistatus as $kk => $vv) { ?>
                                    <option <?php echo (($sel ==$vv->id) ? 'selected="selected"':''); ?> value="<?php echo $vv->id; ?>"><?php echo $vv->name;?></option>
                                     <?php } ?>
                                </select></td>
                        </tr>
              <?php   }
                } ?>
                    </table>
               
                    
                </div>
                <div style="<?php echo ($ip%2 == 0 ? 'clear:both;':'');  ?>"></div>
     <?php } }          ?>
                
                <div style="clear: both;"></div>       
                <div style=" text-align: right; margin: 10px 20px 20px 0px;"><input type="submit" name="record_kpi" value="Record Indicators"/></div>
        
    </tr>
            </div>
            
        </td>
    </tr>
    
</table>
 
 
 <?php 
 echo form_close();
 
 } ?>