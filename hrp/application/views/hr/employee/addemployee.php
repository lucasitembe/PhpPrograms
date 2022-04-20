
<script type="text/javascript">
   
    $(document).ready(function(){
        // Smart Wizard     	
 $("#district").chained("#city");
	
    });	
</script>
<h2 style="padding:0px; margin: 0px;">Manage Employee</h2><hr/>

<div class="col-md-10 col-md-offset-1">
<div class="formdata" style=" width: 800px;">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Edit Employee Information'; }else{ echo 'Add New Employee';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open_multipart('hr/addemployee/'.$edit); ?>
        
        <table class="table" style="width: 750px;" border="0">
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
                    <td  width="35%">First Name<span>*</span></td>
                    <td  width="65%"><input type="text" class="form-control" name="fname" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->FirstName:set_value('fname')) ;?>"/>
                    <?php echo form_error('fname'); ?>
                    </td>
                    </tr>
                <tr>
                    <td>Middle Name</td>
                    <td><input type="text" class="form-control" name="mname" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->MiddleName:set_value('mname')) ;?>"/>
                    <?php echo form_error('mname'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Last Name<span>*</span></td>
                    <td><input type="text" class="form-control" name="lname" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->LastName:set_value('lname')) ;?>"/>
                    <?php echo form_error('lname'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Date of Birth<span>*</span></td>
                    <td><input type="text" class="form-control" style="cursor: pointer;" onclick="displayCalendar(document.forms[0].dob,'yyyy-mm-dd',this)"  name="dob" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->dob:set_value('dob')) ;?>"/>
                    <!-- <img    style="cursor: pointer;" 
                         src="<?php //echo base_url(); ?>images/calendar.png"
                        onclick="displayCalendar(document.forms[0].dob,'yyyy-mm-dd',this)" />        -->
                    <?php echo form_error('dob'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td width="35%">Region<span>*</span></td>
                    <td width="65%">
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->Region:set_value('region')) ;?>
                        <select id="city" name="region" class="form-control">
                            <option value=""> Select Religion</option>
                            
                        <?php   foreach ($region as $key => $value) { ?>
                            <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('region'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>District<span>*</span></td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->District:set_value('district')) ;?>
                        <select id="district" name="district" class="form-control">
                            <option value=""> Select District</option>
                            
                        <?php   foreach ($district as $key => $value) { ?>
                            <option class="<?php echo $value->parent; ?>" <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('district'); ?>
                    </td>
                    </tr>
                     <tr>
                    <td>Gender<span>*</span></td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->Sex:set_value('sex')) ;?>
                        <select name="sex" class="form-control">
                            <option value=""> Select Gender</option>
                            <option <?php echo ($sel =='M')? 'selected="selected"':''; ?> value="M"> Male</option>
                            <option <?php echo ($sel =='F')? 'selected="selected"':''; ?> value="F"> Female </option>
                            
                        </select>
                    <?php echo form_error('sex'); ?>
                    </td>
                    </tr>
                    <tr>
                    <td>Religion</td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->Religion:set_value('religion')) ;?>
                        <select name="religion" class="form-control">
                            <option value=""> Select Religion</option>
                            
                        <?php   foreach ($religion as $key => $value) { ?>
                            <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('religion'); ?>
                    </td>
                    </tr>
                
                        </table>
                    </td>
                    <td>
                        <table>
                <tr>
                    <td width="50%">Employee ID<span>*</span></td>
                    <td width="50%"><input type="text" name="employee" class="form-control" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->EmployeeId:set_value('employee')) ;?>"/>
                    <?php echo form_error('employee'); ?>
                    </td>
                    </tr>
                    
                     <tr>
                    <td>Marital Status<span>*</span></td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->MaritalStatus:set_value('marital')) ;?>
                        <select name="marital" class="form-control">
                            <option value=""> Select Marital Status</option>
                            
                        <?php   foreach ($marital as $key => $value) { ?>
                            <option value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('marital'); ?>
                    </td>
                    </tr>
                     <tr>
                         <td>Education Level<span>*</span></td>
                    <td>
                        <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->EducationLevel:set_value('education')) ;?>
                        <select name="education" class="form-control">
                            <option value=""> Select Education Level</option>
                            
                        <?php   foreach ($educationlevel as $key => $value) { ?>
                            <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->Name; ?></option>
                                        <?php } ?>
                            
                        </select>
                    <?php echo form_error('education'); ?>
                    </td>
                    </tr>

                    <td>Employee Type<span>*</span></td>
                                    <td width=70%>
                                        <select name='Employee_Type' id='Employee_Type' required='required' class="form-control">
					    <option selected='selected'></option>
                                            <option>Accountant</option>
                                            <option>Billing Personnel</option>
                                            <option>Cashier</option>
                                            <option>Doctor</option>
                                            <option>Food Personnel</option>
                                            <option>IT Personnel</option>
                                            <option>Laboratory Technician</option>
                                            <option>Laundry Personnel</option>
                                            <option>Nurse</option>
                                            <option>Pharmacist</option>
                                            <option>Radiologist</option>
                                            <option>Receptionist</option>
                                            <option>Record Personnel</option>
                                            <option>Security Personnel</option>
                                            <option>Store Keeper</option>
                                            <option>Others</option> 
                                        </select>
                                         <?php echo form_error('Employee_Type'); ?>
                                    </td>
                    </tr>
                    <tr>
                    <td >Job Title<span>*</span></td>
                                    <td>
                                        <input type='text' name='Employee_Title' id='Employee_Title' class="form-control">
                                         <?php echo form_error('Employee_Title'); ?>
                                    </td>
                    </tr>
                    <tr>
                    <td >Job Code<span>*</span></td>
                                    <td>
                                        <select name='Job_Code' id='Job_Code' class="form-control">
										 <option>Anaesthesiologist</option>
                                            <option>Dentist</option>
											 <option>Gynecologist</option>
                                            <option>Nurse</option>
                                            <option>Optician</option>
											<option>Paedetrician</option>
											 <option>Radiographer</option>
											<option>Radiologist</option>
                                           <option>Sonographer</option>
                                           <option>Surgeon</option>
                                            <option>Sonographer/Radiographer</option>
                                            <option>Sonographer/Radiolographer/Radiologist</option>
                                            <option>Others</option> 
                                        </select>
                                         <?php echo form_error('Job_Code'); ?>
                                    </td>
                    </tr>
                    <tr>
                    <td>Department Name<span>*</span></td>
                                    <td>
					<select name='Employee_Department_Name' id='Employee_Department_Name' required='required' class="form-control">
					    <option selected='selected'></option>
					    <?php
                                                // $Select_Department = mysql_query("select Department_Name from tbl_department") or die(mysql_error());
						//while($row = mysql_fetch_array($Select_Department)){
						 //  echo "<option>".$row['Department_Name']."</option>";
                                                   foreach ($Department as $key => $value) { ?>
                                            <option value="<?php echo $value->Department_ID; ?>"><?php echo $value->Department_Name; ?></option>
                                                   
						   <?php }    
					    ?>
					</select>
                                         <?php echo form_error('Employee_Department_Name'); ?>
                                    </td>
                    </tr>
                    <tr>
                        <td>Branch Name<span>*</span></td>
                        <td><select name='Employee_Branch_Name' id='Employee_Branch_Name' class="form-control">
                            <?php
                                  	
//                                $data = mysql_query("select * from tbl_branches")or die(mysql_error());
//                                while($row = mysql_fetch_array($data)){
//                                    echo '<option>'.$row['Branch_Name'].'</option>';
//                                }
//                                   mysql_close($connection);
                                   foreach ($Branch as $key => $value) { ?>
                                     <option><?php echo $value->Branch_Name; ?></option>
                                                   
			          <?php } 
                            ?>
                        </select>
                            
                            
                              <?php echo form_error('Employee_Branch_Name'); ?>
                        </td>
                    </tr>
            
                     <tr>
                    <td>Photo<span></span></td>
                    <td>
                        <input type="file" name="file" class="form-control"/>
                     <?php if(isset ($photo)){ ?>
                         <div  class="error" ><?php echo $photo; ?></div>
                                            <?php } ?>
                    </td>
                    </tr>
                        </table>
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                        <tr>
                         <td width="55%">How do you want to set &nbsp; user login credentials?</td>
                         <td width="45%">
                            <label for="rad1" class="radio">
                                <input type="radio" id="rad1" name="select" value="manual" checked> Manual insert
                            </label>
                            <!-- <input type="radio" name="select" id="" > Automatic -->
                            <label for="rad2" class="radio">
                                <input type="radio" id="rad2" name="select" value="exported" onclick="getEHMSusers()"> Export from eHMS
                            </label>
                         </td>
                        </tr>
                            <span class="showHideTr">
                            <tr>
                                <td>User name<span>*</span></td>
                                <td><input type="text" id="username" class="form-control" name="username" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->EmployeeId:set_value('username')) ;?>"/>
                                <?php echo form_error('username'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Passsword<span>*</span></td>
                                <td><input type="password" id="password" class="form-control" name="password" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->EmployeeId:set_value('password')) ;?>"/>
                                <?php echo form_error('password'); ?>
                                </td>
                            </tr>  
                            <tr>
                                <td>User Group<span>*</span></td>
                                <td>
                                    <?php $sel= (isset ($employeeinfo) ? $employeeinfo[0]->group:set_value('group')) ;?>
                                    <select id="group" name="group" class="form-control">
                                        <option value=""> Select Group</option>
                                        
                                    <?php 
                                    $group = $this->db->get('groups')->result();
                                    foreach ($group as $key => $value) { ?>
                                        <option <?php echo ($sel==$value->id ? 'selected="selected"':''); ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                    <?php } ?>
                                        
                                    </select>
                                <?php echo form_error('group'); ?>
                                </td>
                            </tr>

                            <tr style="display:none;">
                                <td>eHMS user ID<span>*</span></td>
                                <td><input type="text" id="ehmsuserid" class="form-control" name="ehmsuserid" value="<?php echo (isset ($employeeinfo) ? $employeeinfo[0]->EmployeeId:set_value('ehmsuserid')) ;?>"/>
                                <?php echo form_error('ehmsuserid'); ?>
                                </td>
                            </tr>
                            </span>
                        </table>
                    </td>
                </tr>
               
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" class="btn" value="Save"/>
                            <input type="button" class="btn" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>

<p>&nbsp;</p>

</div>



<!--used to call ehms users changed on 14/01/2018 -->
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
} );
</script>

<script type="text/javascript">
    $('#rad2').click(function(){
        $("#myModal1").modal({backdrop: false});
        $('#myModal1').modal('show');
    });
</script>

<script type="text/javascript">
    $('#rad1').click(function(){
        $('input[name=ehmsuserid]').val('');
        $('input[name=username]').val('');
        $('input[name=password]').val('');

        $("#myModal1").modal({backdrop: false});
        $('#myModal1').modal('hide');
    });
</script>

<style>
#myModal1 {
top:3%;
left:50%;
outline: none;
}

.radio{
    cursor: pointer;
}
</style>

<!-- Modal -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #006400; color:#fff;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><b style="color:red;">X</b></button>
        <h4 class="modal-title">USERS FROM eHMS</h4>
      </div>
      <div class="modal-body">
      <?php
          $ehmsdb = $this->load->database('ehms', TRUE); // load ehms db

          $query = $ehmsdb->select('tbl_employee.Employee_ID, Employee_Name,Given_Password,Given_Username')
                            ->from('tbl_employee')
                            ->join('tbl_privileges','tbl_privileges.Employee_ID = tbl_employee.Employee_ID','inner')
                            ->order_by('Employee_Name','ASC')
                            ->get();
      ?>
        <p><b>Please, select user to proceed.</b></p><hr>

        <div style="height:400px;overflow-y: auto;overflow-x: auto;">
        <table class="table table-hover table-responsive table-bordered"  id="dataTable1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>eHMS use ID</th>
                    <th>Employee Name</th>
                    <th>Username</th>
                    <th style="display: none;"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($query->result() as $key => $row){?>
                <tr style="cursor: pointer;" class="tr">
                    <td><?= $key+1 ?></td>
                    <td><?= $row->Employee_ID ?></td>
                    <td><?= $row->Employee_Name ?></td>
                    <td><?= $row->Given_Username ?></td>
                    <td style="display: none;"><?= $row->Given_Password ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
//*
$(document).ready(function(){

// code to read selected table row cell data (values).
$(".tr").on('click',function(){
     // get the current row
     var currentRow=$(this).closest("tr"); 
     
     var col1=currentRow.find("td:eq(1)").text(); 
     var col2=currentRow.find("td:eq(2)").text(); 
     var col3=currentRow.find("td:eq(3)").text(); 
     var col4=currentRow.find("td:eq(4)").text(); 
    //  var data=col1+"\n"+col2+"\n"+col3+"\n"+col4;
    //  alert(data);

     $('input[name=ehmsuserid]').val(col1);
     $('input[name=username]').val(col3);
     $('input[name=password]').val(col4);


     $("#myModal1").modal({backdrop: false});
     $('#myModal1').modal('hide');
    
});
});
//*
</script>

<!-- changed on 14/01/2018 -->