<div class="formdata" style="width: 100%;">
    <div class="formdata-title" >
        Employee Information
    </div>
    <div class="formdata-content" style="margin: 0px;   padding-top: 0px; ">
        
        <?php echo form_open('hr/employeelist/','style="width:97%;"'); ?>
        
        <table border="0" style="width: 100%;" >
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
                    <td>First Name</td>
                    <td><input type="text" class="form-control" name="fname" value="<?php echo set_value('fname') ;?>"/></td>
                    <td>Last Name</td>
                    <td><input type="text" class="form-control" name="lname" value="<?php echo set_value('lname') ;?>"/></td>
                    <td>Employee ID</td>
                    <td><input type="text" class="form-control" name="employee" value="<?php echo set_value('employee') ;?>"/></td>
                     <td>Work Station</td>
                    <td><select name="station" class="form-control">
                            <option value=""></option>
                               <?php
                                        foreach ($station as $key => $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php }  ?>
                        </select></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><select name="sex" class="form-control">
                            <option value=""></option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select></td>
                    <td>Position</td>
                    <td><select name="position" class="form-control">
                            <option value=""></option>
                            <?php
                                        foreach ($position as $key => $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php }  ?>
                        </select></td>
                    <td>Department</td>
                    <td><select name="department" class="form-control">
                             <option value=""></option>
                            <?php
                                        foreach ($department as $key => $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php }  ?>
                        </select></td>
                    <td>Employee Status</td>
                    <td>
                        <select name="status" class="form-control">
                            <option value=""></option>
                            <option value="1">Current Employee Only</option>
                            <option value="2">Current & Past Employee</option>
                            <option value="3">Past Employee Only</option>
                        </select>
                    </td>
                </tr>
              
                
                <tr class="submit">
                    <td colspan="8"><div> 
                            <input type="submit" class="btn" value="Search" name="Search"/>
                             <input type="button" class="btn"  value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>



<div class="table_list" style="padding-top: 5px; width: 100%;">
    <div style="text-align: right; margin-right: 40px; color: brown; font-weight: bold;">Total Record Entered: <?php echo $employeetotalnumber; ?></div>
    <table class="view_data table" cellspacing="0" cellpadding="0" style="width: 100%;">
        <th style="width:50px;">S/No</th>
        <th style="width:100px;">EmployeeID</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Sex</th>
        <th>Postal</th>
        <th>Location</th>
        <th>Mobile</th>
        <th>Email</th>
</tr>

<?php 
$i=1;
if(count($employeelist) > 0){
 foreach ($employeelist as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo anchor('hr/personalinfo/'.$value->id,$value->EmployeeId); ?></td>
    <td><?php echo anchor('hr/personalinfo/'.$value->id,$value->FirstName); ?></td>
    <td><?php echo $value->MiddleName;?></td>
    <td><?php echo anchor('hr/personalinfo/'.$value->id,$value->LastName); ?></td>
     <td><?php echo $value->Sex;?></td>
    <td><?php echo $value->Postal;?></td>
    <td><?php $lo='';if($value->District > 0){$city=$this->HR->district(null,$value->District); $lo.=$city[0]->Name; } if($value->Region > 0){$region=$this->HR->regions($value->Region); $lo.=', '.$region[0]->Name;}echo $lo;?></td>
    <td><?php echo $value->Mobile;?></td>
    <td><?php echo $value->Email;?></td>
    
    
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