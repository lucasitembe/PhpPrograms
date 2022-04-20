<script type="text/javascript">

 function openwindow(url){
    //window.open(url,'Search Employee List','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=500,height=500,left=430,top=23');
    window.open(url,'Search','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23');
 }
</script>
<h2 style="padding:0px; margin: 0px;">Leave Management</h2><hr/>


<div class="formdata">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Edit Leave Roster Information'; }else{ echo 'Add Leave in Roster';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('hr/addroster/'.$edit); ?>
        
        <table>
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
                    <td>Employee ID<span>*</span></td>
                    <?php
                    $try = (set_value('name') ? set_value('name'): $this->session->userdata('employee_id'));
                    ?>
                    <td valign="top" ><input type="text" id="name" name="name" value="<?php echo (isset ($leaveinfo) ? $leaveinfo[0]->Employee: $try) ;?>"/>
                      <?php if(miltone_check("HR Manager")){ ?>
                        <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/search.png"
                                                            onclick='targetitem = document.forms[0].name; 
 window.open("employeelist_search/"+targetitem.name, "dataitem", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23");' />         
                        
                    <?php }  echo form_error('name'); ?>
                    </td>
                </tr>
                <tr>
                    <td>Leave Type<span>*</span></td>
                    <td>
                        <select name="leavetype">
                            <option value=""> -- Select --</option>
                            <?php foreach ($leavetype as $key => $value) { 
                                $sel=(isset ($leaveinfo) ? $leaveinfo[0]->LeaveType:set_value('leavetype'));
                                ?>
                            <option <?php echo ($value->id==$sel ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                        </select>
                    <?php echo form_error('leavetype'); ?>
                    </td>
                </tr>
              
                 <tr>
                         <td>From Date<span>*</span></td>
                    <td>
                        <input type="text" name="fromdate" value="<?php echo (isset ($leaveinfo) ? $leaveinfo[0]->Fromdate:set_value('fromdate')) ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].fromdate,'yyyy-mm-dd',this)" />       
                   
                    <?php echo form_error('fromdate'); ?>
                    </td>
                    </tr>
                    
                                    <tr>
                         <td>To Date<span>*</span></td>
                    <td>
                        <input type="text" name="todate" value="<?php echo (isset ($leaveinfo) ? $leaveinfo[0]->Todate:set_value('todate')) ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].todate,'yyyy-mm-dd',this)" />       
                   
                    <?php echo form_error('todate'); ?>
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