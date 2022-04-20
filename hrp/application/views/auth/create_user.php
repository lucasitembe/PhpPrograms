<h2>Create New User</h2><hr/>

<div class="formdata">
        <div class="formdata-title">
            Please Enter all required information 

        </div>
     <div class="formdata-content">
         <?php if(isset($message) && $message !=''){ ?>
<div class="message">
	<?php echo $message;// $this->session->flashdata('message') ;?>
</div>
         <?php } ?>
	
    <?php echo form_open("auth/create_user");?>
    <table class="form" cellpadding="1" cellspacing="1">
    <tr>
      <td class="label">Employee ID<span>*</span>:</td>
      <td class="input"><?php echo form_input($EmployeeID); ?>
      <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/search.png"
                                                            onclick='targetitem = document.forms[0].EmployeeID; 
                                                                window.open("<?php echo site_url(); ?>/hr/employeelist_search/"+targetitem.name, "dataitem", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23");' />         
                    <?php echo form_error('EmployeeID'); ?>
      
      </td>
    </tr>
    <tr>
      <td class="label">First Name<span>*</span>:</td>
      <td class="input"><?php echo form_input($first_name); echo form_error('first_name');?></td>
    </tr>
      <tr>
      <td class="label">Last Name<span>*</span>:</td>
      <td class="input"><?php echo form_input($last_name); echo form_error('last_name');?></td>
      </tr>
      
      <tr><td class="label"> Company Name<span>*</span>:</td>
     <td class="input"> <?php echo form_input($company); echo form_error('company');?></td>
      </tr>
       <tr>
      <td class="label">Phone:</td>
     <td class="input"> <?php echo form_input($phone1); echo form_error('phone1')?></td>  
      </tr>
      
      <tr>
      <td class="label">
      Email: <span>*</span></td>
     <td class="input"> <?php echo form_input($email); echo form_error('email');?></td>
      </tr>
      <tr>
      <td class="label">
      Username: <span>*</span></td>
     <td class="input"> <?php echo form_input($username); echo form_error('username');?></td>
      </tr>
      <tr>
       <tr>
      <td class="label">Privilege/Group:<span>*</span></td>
     <td class="input">
     <select name="group">
     <?php 
     foreach($group as $key=>$value){
         if($value->id != 2){
     	echo '<option value="'.$value->id.'">'.$value->name.'</option>';
         }
     }
     ?>
     </select>
     <?php echo form_error('group');?>
     </td>  
      </tr>
       <tr>
      <td class="label">Department:<span>*</span></td>
     <td class="input">
     <select name="department">
     <?php 
     foreach($department as $key=>$value){
         
     	echo '<option value="'.$value->id.'">'.$value->Name.'</option>';
         
     }
     ?>
     </select>
     <?php echo form_error('department');?>
     </td>  
      </tr>
   
 
      <tr>
      <td class="label">Password:<span>*</span></td>
      <td class="input"><?php echo form_input($password); echo form_error('password');?></td>
      </tr>
      <tr>
      <td class="label">Confirm Password:<span>*</span></td>
      <td class="input"><?php echo form_input($password_confirm); echo form_error('password_confirm')?>
      </td>
      </tr>
      
      <tr class="submit">
      <td colspan="2" align="center">
      <?php echo form_submit('submit', 'Create User');?>
      </td>
      </tr>

    </table>  
    <?php echo form_close();?>
    
</div>
</div>
  