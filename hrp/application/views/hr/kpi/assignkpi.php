<h2 style="padding:0px; margin: 0px;"> Record KPIs</h2><hr/>


<div class="formdata" style=" width: 1000px;">
    <div class="formdata-title">
    Record KPIs   
    </div>
    <div class="formdata-content" style="width: 97%;">
        
        <?php echo form_open('hr/assignkpi/','style="width:100%;"'); ?>
        
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
            <input type="text" id="employee" name="employee" value="<?php echo set_value('employee') ;?>"/>
                       <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/search.png"
                                                            onclick='targetitem = document.forms[0].employee; 
 window.open("employeelist_search/"+targetitem.name, "dataitem", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23");' /> 
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
 <?php echo form_open('hr/assignkpi/','style="width:100%;padding:30px 0px;"'); ?>
<input type="hidden" name="employee" value="<?php echo $employee; ?>"/>
<input type="hidden" name="date" value="<?php echo $date; ?>"/>
<table style="width: 100%; margin-left: 0px;" class="formdata">
    <tr>
        <td style="width: 100px;" valign="top">
           <img src="<?php echo base_url().'uploads/photo/'.employee_photo($id); ?>" style="width: 150px; border: 2px solid #CCCCCC; height: 180px;"/>
        </td>
        <td valign="top" >
            <h3 style=" color: green; padding: 0px 0px 0px 10px; margin: 5px;">Employee ID: <?php echo $in_data[0]->EmployeeId; ?>  &nbsp; &nbsp; &nbsp; Employee Name : <?php echo $in_data[0]->FirstName.' '.$in_data[0]->LastName; ?></h3>
            <div style="padding-left: 10px; padding-top: 10px;">
               <?php 
               if(count($category) > 0){
                   echo '<b style="color:green;text-transform:uppercase;"> Category Name : '.$category[0]->name.'</b>';
                   
if(isset ($error_found)){ ?>
<div class="message" style="width: 890px;"><?php echo $error_found; ?></div>
<?php } ?>
<table class="view_data"  cellspacing="0" cellpadding="0" style="width: 900px;">
    <tr>
                <th>Name</th><th style="width: 200px;">Status</th>
    </tr>
    <?php
    if(count($kpi_cat_list) > 0){
 foreach ($kpi_cat_list as $key => $value) { 
     $kpi_info = $this->HR->kpi_indicator_list($value->kpi_indicator);
     ?>
    <tr>
        <td><?php echo $kpi_info[0]->name ?></td>
        <td>
            
            <select name="indicator_<?php echo $value->kpi_indicator; ?>">
                <option value="">--------</option>
                <?php 
                     foreach ($kpistatus as $stat_key=>$stat_value){ ?>
                <option <?php echo (array_key_exists($value->kpi_indicator, $assigned_kpi) ? ($assigned_kpi[$value->kpi_indicator] == $stat_value->id ? 'selected="selected"':'' ) : '');?> value="<?php echo $stat_value->id; ?>"><?php echo $stat_value->name; ?></option>
                     <?php }   ?>
            </select>
            <?php echo form_error("indicator_".$value->kpi_indicator); ?>
        </td>
    </tr>
 <?php }  }else{  ?>
    <tr><td colspan="2" align="center" style="font-style: italic;">No KPIs found for this Category !!</td></tr>
    <?php } ?>
</table>
        <?php       }else{ ?>
                <div  style="font-style: italic;"> Please assign Position for this Employee First. Click <?php echo anchor('hr/job/'.$id,'here');?></div>
                <?php }    ?>
            </div> 
            <?php if(count($category) > 0){ ?>
                <div style=" text-align: right; width: 890px; margin: 10px 20px 20px 0px;"><input type="submit" name="record_kpi" value="Record Indicators"/></div>
        <?php } ?>
    </tr>
            </div>
            
        </td>
    </tr>
    
</table>
 
 
 <?php 
 echo form_close();
 
 } ?>