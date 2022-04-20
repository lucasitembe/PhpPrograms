<script type="text/javascript">
function change_status(id){
    $("#status_div_"+id).show();
}
$(document).ready(function(){
    $("#change").click(function(){
        var change= $("#new_s").val();
        if(change == ''){
            alert("Select New Status");
            return false;
        }else{
            var con = confirm("Are you sure you want to change this status ?");
            if(con){
                return true
            }else{
                return false;
            }
        }
        
    }) ;
});
</script>
<div class="formdata" style="width: 100%;">
    <div class="formdata-title" >
        Searching Criteria
    </div>
    <div class="formdata-content" style="margin: 0px;   padding-top: 0px; ">
        
        <?php 
        
                $lnn = isset ($search_c) ? $search_c : '';
               
        echo form_open('hr/candidates/'.$lnn,'style="width:97%;"'); ?>
        
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
                    <td>First Name</td>
                    <td><input type="text" class="form-control" name="fname"/></td>
                    <td>Last Name</td>
                    <td><input type="text" class="form-control" name="lname"/></td>
                    <td>Gender</td>
                    <td><select name="sex" class="form-control">
                            <option value=""></option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select></td>
                        
                       <td>Education</td>
                    <td><select name="education" class="form-control">
                            <?php $sel= set_value('education') ;?>
                            <option value=""></option>
                            <?php   foreach ($educationlevel as $key => $value) { ?>
                            <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                        </select></td>  
                     
                </tr>
                <tr>
                    <td>Application Status</td>
                    <td>
                        <?php $sel= set_value('status'); ?>
                        <select name="status" class="form-control">
                            <option <?php echo ($sel== 0 ? 'selected="selected"':''); ?> value="0">New</option>
                            <option <?php echo ($sel== 1 ? 'selected="selected"':''); ?> value="1">Called for Interview</option>
                            <option <?php echo ($sel== 2 ? 'selected="selected"':''); ?> value="2">Attend Interview</option>
                            <option <?php echo ($sel== 3 ? 'selected="selected"':''); ?> value="3">Accepted</option>
                            <option <?php echo ($sel== 4 ? 'selected="selected"':''); ?> value="4">Reject</option>
                        </select>
                    </td>
                    
                    
                </tr>
              
                
                <tr class="submit">
                    <td colspan="8"><div>
                            <input type="submit" class="btn" value="Search" name="Search"/>
                             <input type="button" class="btn" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>



<div class="table_list" style="padding-top: 5px; width: 100%;">
    <table class=" table view_data" cellspacing="0" cellpadding="0" style="width: 100%;">
        <th style="width:50px;">S/No</th>
        <th>Name</th>
        <th>Sex</th>
        <th>Education Level</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>App. Letter</th>
        <th>CV</th>
        <th>Status</th>
</tr>

<?php 
$i=1;
if(count($candidate_list) > 0){
 foreach ($candidate_list as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->FirstName.' '.$value->MiddleName.' '.$value->LastName;  ?></td>
   <td><?php echo $value->Sex;?></td>
    <td><?php 
   $edu =  $this->HR->educationlevel($value->EducationLevel);
    echo $edu[0]->Name;?></td>
    
  
    <td><?php echo $value->Mobile;?></td>
    <td><?php echo $value->Email;?></td>
    <td><?php echo anchor(base_url('uploads/application/letter/'.$value->Letter),'Download','download');?></td>
    <td><?php echo anchor(base_url('uploads/application/cv/'.$value->CV),'Download','download');?></td>
    
    <td><?php echo application_status($value->status);?> &nbsp; <img id="image_<?php echo $value->id; ?>" src="<?php echo base_url('images/edit.png') ?>" style="cursor: pointer;" onclick="change_status('<?php echo $value->id; ?>')"/>
        <div style="display: none;" id="status_div_<?php echo $value->id; ?>">
            <?php 
            echo form_open('hr/candidates/','style="width:50px;"');
            ?>
            <input type="hidden" name="app_id" value="<?php echo $value->id; ?>"/>
            <select name="new_status" style="width: 100px;" id="new_s">
                <option value="">Change</option>
            <?php
            foreach (application_status() as $k => $v) { ?>
                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
            <?php }   ?>
            </select>
            <input type="submit" value="Change" id="change" name="change"/>
                <?php
            echo form_close();
            ?>
        </div>   
    
    </td>
    
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="10" align="center">No data found</td></tr>';
}
?>
 </table>
</div>
