<script type="text/javascript">
   
    $(document).ready(function(){
        // Smart Wizard     	
 $("#district").chained("#city");

function deletedata(){
    conf=confirm('Are you sure you want to delete ? ');
    if(conf){
        return true;
    }else{
        return false;
    }
}
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
            <td valign="top" >
                <?php
              $in_data=employee_basic_data($id);
              
              ?>
                <h3 style=" color: green; padding: 0px 0px 0px 10px; margin: 5px;">Employee ID: <?php echo $in_data[0]->EmployeeId; ?>  &nbsp; &nbsp; &nbsp; Employee Name : <?php echo $in_data[0]->FirstName.' '.$in_data[0]->LastName; ?></h3>
              
<div class="formdata" style=" width: 800px;">
    <div class="formdata-title">
        <?php $edit=$id;?>
      Salary Information
    </div>
    <div class="formdata-content">
        
        <?php echo form_open_multipart('hr/salary/'.$edit); ?>
        
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
                    <td>Salary Grade<span>*</span></td>
                    <td>
                        <select name="paygrade">
                            <option value="">Select Grade</option>
                            <?php
                            $sel=(isset ($salaryinfo) ? $salaryinfo[0]->SalaryGrade:set_value('paygrade'));
                            foreach ($salarygrade as $key => $value) { ?>
                            <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name;?></option>
                                  <?php      }       ?>
                        </select>
                    <?php echo form_error('paygrade'); ?>
                    </td>
                    </tr>
                    
                <tr>
                    <td>Amount<span>*</span></td>
                    <td><input type="text" name="amount" value="<?php echo (isset ($salaryinfo) ? $salaryinfo[0]->Amount:set_value('amount')) ;?>"/>
                    <?php echo form_error('amount'); ?>
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
            </td>
        </tr>
    </table>
</div>

