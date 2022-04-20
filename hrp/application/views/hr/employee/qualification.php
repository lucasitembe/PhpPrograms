<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
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
        <?php 
        $edit=$id;
        if(isset ($edit_id)){
            $edit.='/'.$edit_id;
        }
        ?>
Qualification Informations
    </div>
    <div class="formdata-content">
        
        <?php echo form_open_multipart('hr/qualification/'.$edit); ?>
        
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
                    <td>Education Level<span>*</span></td>
                    <td>
                        <?php $sel= (isset ($educationinfo) ? $educationinfo[0]->EducationLevel:set_value('education')) ;?>
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
                    <td>College/School<span>*</span></td>
                    <td>
                        <input type="text" name="college" value="<?php echo (isset ($educationinfo) ? $educationinfo[0]->College:set_value('college')); ?>"/>
                    <?php echo form_error('college'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>Attach Certificate<span>*</span></td>
                    <td>
                        <input type="file" name="file" />
                     <?php 
                     
                     if(isset ($photo)){ ?>
                         <div  class="error" ><?php echo $photo; ?></div>
                                            <?php } ?>
                    </td>
                    </tr>
                    <tr>
                    <td>Comment</td>
                    <td>
                        <textarea style="width: 300px;" name="comment"><?php echo (isset ($educationinfo) ? $educationinfo[0]->Comment:set_value('comment')); ?></textarea>
                    <?php echo form_error('comment'); ?>
                    </td>
                    </tr>
                     
                        </table>
                    </td>
                    <td>
                         
                    </td>
                </tr>
               
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" name="Save" value="Save"/>
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
        <th style="width:50px;">S/No</th><th style="width: 200px;">Education Level</th><th style="width: 200px;">College/School</th><th>Comment</th><th style="width:130px;">Certificate</th>
</tr>

<?php 
$i=1;
if(count($qualificationlist) > 0){
 foreach ($qualificationlist as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo anchor('hr/qualification/'.$id.'/'.$value->id,$i++); ?></td>
    <td><?php $lev=$this->HR->educationlevel($value->EducationLevel); echo anchor('hr/qualification/'.$id.'/'.$value->id,$lev[0]->Name);?></td>
    <td><?php echo $value->College;?></td>
    <td><?php echo $value->Comment;?></td>
    <td><?php echo anchor(base_url().'uploads/qualification/'.$value->Attachment,'Download','target="_blank"') ;?></td>
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

