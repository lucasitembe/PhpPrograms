<script type="text/javascript">

 function openwindow(url){
    //window.open(url,'Search Employee List','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=500,height=500,left=430,top=23');
    window.open(url,'Search','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23');
 }
</script>
<h2 style="padding:0px; margin: 0px;">Skip Loan Repayment</h2><hr/>


<div class="formdata" style="width: 1024px;">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Add Information'; }else{ echo 'Add Information';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('account/skiprepayment/'.$edit,'style="width:100%;"'); ?>
        
        <table style=" width: 90%;">
             <?php 
 
            if($this->session->flashdata('message') !=''){
            	?>
            	<tr>
            	<td colspan="6">
            	<div class="message">
            	<?php echo $this->session->flashdata('message'); ?>
            	</div>
            	</td>
            	</tr>
            	<?php
            }else if(isset($error_in)){
            	?>
            	<tr>
            	<td colspan="6" >
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
                    <td valign="top" ><input type="text" id="name" name="name" value="<?php echo set_value('name') ;?>"/>
                       <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/search.png"
                                                            onclick='targetitem = document.forms[0].name; 
                                                                window.open("employeelist_search/"+targetitem.name, "dataitem", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23");' />         
                    <?php echo form_error('name'); ?>
                    </td>
                
                    <td>Month<span>*</span></td>
                    <td>
                        <select name="month">
                            <option value=""> --Select--</option>
                            <?php 
                            $month=month_generator();
                                        foreach ($month as $key => $value) { 
                                            $sel = set_value('month');
                                            ?>
                            
                            <option <?php echo ($sel ==$key ? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php }
                            ?>
                        </select>
                    <?php echo form_error('month'); ?>
                    </td>
                    <td>Year<span>*</span></td>
                    <td>
                        <select name="year">
                            <option value=""> --Select--</option>
                            <?php 
                            
                                        foreach ($yearlist as $key => $value) { 
                                            $sel = set_value('year');
                                            ?>
                            
                            <option <?php echo ($sel ==$value->Name ? 'selected="selected"':''); ?> value="<?php echo $value->Name; ?>"><?php echo $value->Name; ?></option>
                                        <?php }
                            ?>
                        </select>
                    <?php echo form_error('year'); ?>
                    </td>
                </tr>
                
               
                    </tr>
                <tr class="submit">
                    <td colspan="6"><div>
                            <input type="submit" value="Save"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>
<p>&nbsp;</p>
<h3>Open Loan Skip Repayment Configuration</h3><hr>
<div class="table_list"  style="padding-top:  20px;">
    <table class="view_data" cellspacing="1" cellpadding="1">
        <tr> <th style="width:50px;">S/No</th><th>Employee ID</th> <th>Name</th> <th>Skip Month</th><th>Action</th>
</tr>
<?php 
if(count($skiprepayment)){
    $i=1;
  
    foreach ($skiprepayment as $key => $value) { ?>
<tr>
    <td><?php echo $i++; ?></td>
    <td><?php echo $value->Employee;
    $employee = check_employee_number($value->Employee);
    ?></td>
    <td><?php echo $employee[0]->FirstName.' '.$employee[0]->LastName ?></td>
    <td><?php echo month_generator($value->Month).', '.$value->Year; ?></td>
    <td><?php echo anchor('account/deleteskiprepay/'.$value->id,'Delete','style="coloe:blue;"') ?></td>
</tr>
   <?php  }
}else{
?>
<tr>
    <td colspan="5" align="center">No data found !</td>
</tr>
<?php } ?>
    </table>
</div>