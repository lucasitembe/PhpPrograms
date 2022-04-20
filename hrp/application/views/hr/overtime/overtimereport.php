<script type="text/javascript">

 function openwindow(url){
    //window.open(url,'Search Employee List','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=500,height=500,left=430,top=23');
    window.open(url,'Search','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23');
 }
</script>
<h2 style="padding:0px; margin: 0px;">Overtime Report List</h2><hr/>


<div class="formdata" style="width: 1024px;">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Searching Criteria'; }else{ echo 'Searching Criteria';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('hr/overtimereport/'.$edit,'style="width:100%;"'); ?>
        
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
                
                    <td>From<span>*</span></td>
                    <td>
                         <input type="text" name="from_date" id="date"/> <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].from_date,'yyyy-mm-dd',this)" />       
                        
                    <?php echo form_error('from_date'); ?>
                    </td>
                    <td>To<span>*</span></td>
                    <td>
                        <input type="text" name="to_date" id="to_date"/> <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].to_date,'yyyy-mm-dd',this)" />       
                 
                    <?php echo form_error('to_date'); ?>
                    </td>
                </tr>
                
               
                    </tr>
                <tr class="submit">
                    <td colspan="6"><div>
                            <input type="submit" name="search" value="Search"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>
<?php if(isset ($overtimereport)){ ?>
<p>&nbsp;</p>
<h3>Overtime Report List</h3><hr>
<div class="table_list"  style="padding-top:  20px;">
    <table class="view_data" cellspacing="1" cellpadding="1">
        <tr> <th style="width:50px;">S/No</th><th>Employee ID</th> <th>Name</th> <th>Overtime Hour(s)</th><th>Overtime Date</th><th>Action</th>
</tr>
<?php 
if(count($overtimereport) > 0){
    $i=1;
    foreach ($overtimereport as $key => $value) { ?>
<tr>
    <td><?php echo $i++; ?></td>
    <td><?php echo $value->Employee;
    $employee = check_employee_number($value->Employee);
    ?></td>
    <td><?php echo $employee[0]->FirstName.' '.$employee[0]->LastName ?></td>
    <td><?php echo $value->hours; ?></td>
    <td><?php echo $value->date; ?></td>
    <td><?php echo anchor('hr/deleteovertime/'.$value->id,'Delete','style="coloe:blue;"') ?></td>
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
<?php } ?>