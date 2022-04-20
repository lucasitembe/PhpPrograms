<div class="formdata" style="width: 100%;">
    <div class="formdata-title" >
        Searching Criteria
    </div>
    <div class="formdata-content" style="margin: 0px;   padding-top: 0px; ">
        
        <?php echo form_open('hr/trainingreport/','style="width:97%;"'); ?>
        
        <table border="0" style="width: 100%;">
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
                    <td>From Date</td>
                    <td><input type="text" name="fromdate" value="<?php echo (set_value('fromdate')) ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].fromdate,'yyyy-mm-dd',this)" />       </td>
                    
                    <td>To Date</td>
                    <td>
                                <input type="text" name="todate" value="<?php echo (set_value('todate')) ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].todate,'yyyy-mm-dd',this)" />       
                    
                    </td>
                    <td>Employee ID</td>
                    <td><input type="text" name="employee" value="<?php echo set_value('employee') ;?>"/></td>
                     <td>Training Type</td>
                    <td><select name="station">
                            <option value=""></option>
                               <?php
                                        foreach ($station as $key => $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php }  ?>
                        </select></td>
                </tr>
               
              
                
                <tr class="submit">
                    <td colspan="8"><div>
                            <input type="submit" value="Search" name="Search"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>



<div class="table_list" style="padding-top: 5px; width: 100%;">
    <table class="view_data" cellspacing="0" cellpadding="0" style="width: 100%;">
        <th style="width:50px;">S/No</th>
        <th style="width:100px;">Employee ID</th>
        <th>Employee Name</th>
        <th>Sex</th>
        <th>Training Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Action</th>
</tr>

<?php 
$i=1;
if(count($employeelist) > 0){
 foreach ($employeelist as $key => $value){ 
     $basic_info = check_employee_number($value->Employee);
     $leavetype= $this->HR->trainingtype($value->trainingtype);
    
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->Employee; ?></td>
    <td><?php echo $basic_info[0]->FirstName.' '.$basic_info[0]->LastName; ?></td>
     <td><?php echo $basic_info[0]->Sex;?></td>
    <td><?php echo $leavetype[0]->Name;?></td>
    <td><?php echo $value->startdate;?></td>
    <td><?php echo $value->enddate;?></td>
    <td><?php echo anchor('hr/add_to_training/'.$value->id,'Edit','style="color:blue;"');?></td>
     
    
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="10" align="center">No data found</td></tr>';
}
?>
 </table>
 <?php echo $links; ?> 
    
</div>