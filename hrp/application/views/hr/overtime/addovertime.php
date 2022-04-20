<script type="text/javascript">

 function openwindow(url){
    //window.open(url,'Search Employee List','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=500,height=500,left=430,top=23');
    window.open(url,'Search','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23');
 }
</script>
<h2 style="padding:0px; margin: 0px;">Add Overtime Information</h2><hr/>


<div class="formdata" >
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Add Information'; }else{ echo 'Add Information';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('hr/addovertime/','style="width:100%;"'); ?>
        
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
                </tr>
                <tr>
                    <td>Overtime Hours<span>*</span></td>
                    <td>
                        <input type="text" name="hours"/>
                    <?php echo form_error('hours'); ?>
                    </td>
                </tr>
                <tr>
                    <td>Overtime Date<span>*</span></td>
                    <td>
                        <input type="text" name="date" id="date"/> <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].date,'yyyy-mm-dd',this)" />       
                 
                    <?php echo form_error('date'); ?>
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
