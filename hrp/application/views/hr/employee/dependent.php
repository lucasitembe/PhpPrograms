<script type="text/javascript">
   
    $(document).ready(function(){
        // Smart Wizard     	
 $("#district").chained("#city");


    });	
    function deletedata(){
   var conf=confirm('Are you sure you want to delete ? ');
  if(conf){
        return true;
    }else{
        return false;
    }
}
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
      Dependents Information
    </div>
    <div class="formdata-content">
        
        <?php echo form_open_multipart('hr/dependent/'.$edit); ?>
        
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
                    <td>Name<span>*</span></td>
                    <td><input type="text" name="name" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Name:set_value('name')) ;?>"/>
                    <?php echo form_error('name'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Relation<span>*</span></td>
                    <td><input type="text" name="relation" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->Relation:set_value('relation')) ;?>"/>
                    <?php echo form_error('relation'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Date of Birth</td>
                    <td><input type="text" name="dob" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->dob:set_value('dob')) ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].dob,'yyyy-mm-dd',this)" />       
                    <?php echo form_error('dob'); ?>
                    </td>
                    </tr>
                        </table>
                    </td>
                    <td>
                         
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
                
                <div style="height: 10px;"></div>       
                <div class="table_list" >
    <table class="view_data"  cellspacing="0" cellpadding="0">
        <th style="width:50px;">S/No</th><th>Name</th><th>Relation</th><th>Birth Date</th><th style="width:100px;">Action</th>
</tr>

<?php 
$i=1;
if(count($dependentsinfo) > 0){
 foreach ($dependentsinfo as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->Name;?></td>
    <td><?php echo $value->Relation;?></td>
    <td><?php echo $value->dob;?></td>
    <td><?php echo anchor('hr/dependentdelete/'.$id.'/'.$value->id,'Delete','style="color:blue;" onclick="return deletedata()"'); ?></td>
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="5" align="center">No data found</td></tr>';
}
?>
 </table>
 
</div>
            </td>
        </tr>
    </table>
</div>

