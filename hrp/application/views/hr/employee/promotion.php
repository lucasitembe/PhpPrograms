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
        <?php $edit=$id;
        if(isset ($edit_id)){
            $edit.='/'.$edit_id;
        }
        ?>
      Promotion Information
    </div>
    <div class="formdata-content">
        
        <?php echo form_open_multipart('hr/promotion/'.$edit); ?>
        
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
                    <td>New Position<span>*</span></td>
                    <td>
                        <select name="position">
                            <option value="">Select Position</option>
                            <?php 
                            $sel = (isset ($promotion) ? $promotion[0]->Position:set_value('position'));
                            foreach ($position as $key => $value) { ?>
                            <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                         <?php } ?> 
                        </select>   
                    <?php echo form_error('position'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>New Work Station<span>*</span></td>
                    <td>
                        <select name="workstation">
                            <option value="">Select WorkStation</option>
                            <?php 
                            $sel = (isset ($promotion) ? $promotion[0]->WorkStation:set_value('workstation'));
                            foreach ($workstation as $key => $value) { ?>
                            <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                         <?php } ?> 
                        </select>   
                    <?php echo form_error('workstation'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>New Department<span>*</span></td>
                    <td>
                        
                        <select  name="department">
                            <option value="">Select Department</option>
                            <?php 
                            $sel = (isset ($promotion) ? $promotion[0]->Department:set_value('department'));
                            foreach ($department as $key => $value) { ?>
                            <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                         <?php } ?>
                            
                        </select>
                    <?php echo form_error('department'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>Promotion Letter<span>*</span></td>
                    <td>
                        <input type="file" name="file" />
                     <?php 
                     
                     if(isset ($photo)){ ?>
                         <div  class="error" ><?php echo $photo; ?></div>
                                            <?php } ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Start Date</td>
                    <td><input type="text" name="startdate" value="<?php echo (isset ($promotion) ? $promotion[0]->Startdate:set_value('startdate')) ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].startdate,'yyyy-mm-dd',this)" />       
                    <?php echo form_error('startdate'); ?>
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
        <th style="width:50px;">S/No</th><th>Position</th><th>Work Station</th><th>Department</th><th>Start Date</th><th style="width:130px;">Promotion Letter</th>
</tr>

<?php 
$i=1;
if(count($promotionlist) > 0){
 foreach ($promotionlist as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo anchor('hr/promotion/'.$id.'/'.$value->id,$i++); ?></td>
    <td><?php  $p= $this->HR->position($value->Position); echo anchor('hr/promotion/'.$id.'/'.$value->id,$p[0]->Name);?></td>
    <td><?php $p= $this->HR->workstation($value->WorkStation);echo $p[0]->Name;?></td>
    <td><?php $p= $this->HR->department($value->Department);echo $p[0]->Name;?></td>
    <td><?php echo $value->Startdate;?></td>
    <td><?php echo anchor(base_url().'uploads/promotion/'.$value->PromotionLetter,'Download','target="_blank"') ;?></td>
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

