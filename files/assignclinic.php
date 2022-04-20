<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Appointment_Works']!='yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
            echo "<a href='listofemployeetoassignclinic.php?EditEmployee=EditEmployeeThisForm".((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'')."' class='art-button-green'>BACK</a>";
    }
    
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID'];
    }else{
	$Employee_ID = 0;
    }
?> 
 <?php
    $Select_Assigned_Branches = mysqli_query($conn,"select * from tbl_Employee where employee_id = '$Employee_ID'");
    $no=mysqli_num_rows($Select_Assigned_Branches);
    if($no>0){
	while($row = mysqli_fetch_array($Select_Assigned_Branches)){
	    $Employee_Name = $row['Employee_Name'];
	    $Employee_Title = $row['Employee_Title'];
	}
    }else{
	$Employee_Name = 'Unknown Employee Name';
	$Employee_Title = 'Unknown Employee Title';
    }
?>
<style>
.financial:hover{
  background: #fff;
  cursor: pointer;
}
</style>
<center>
    <table width=70%>
	<tr><td>
	<fieldset>
            <legend align="center" ><b>EMPLOYEE PARTICULARS</b></legend><center>
	<table width=40%>
	    <tr>
	    <td><b>Employee Name</b></td><td><input type='text' disabled='disabled' size=30 value='<?php echo $Employee_Name; ?>'></td>
	</tr>
	<tr>
	    <td><b>Employee Title</b></td><td><input type='text' disabled='disabled' size=30 value='<?php echo $Employee_Title; ?>'></td>
	</tr>
	</center></table>
	</td>    
	</tr>
	
 
 
 <?php
	if(isset($_POST['submittedAddClinicForm'])){
		$Clinic_Name = $_POST['Clinic_Name']; 
		
		$sql = "insert into tbl_clinic_employee(Clinic_ID,Employee_ID)
				values((select clinic_id from tbl_clinic where clinic_name = '$Clinic_Name'),'$Employee_ID')";
		
		if(!mysqli_query($conn,$sql)){
		    $error = '1062yes';
		    if(mysql_errno()."yes" == $error){ 
			$controlforminput = "<b>Duplication! Employee Already Assigned To ".$Clinic_Name." Clinic</b>";
		    }
		}
		else {
		    $controlforminput = '<b>Process Successful</b>';
		}
	}
?>

 
<tr><td>
 <fieldset>
            <legend align="center" ><b>ASSIGNED CLINICS</b></legend>
<iframe width='100%' src="Assigned_Clinics_iframe.php?Employee_ID=<?php echo $Employee_ID; ?>&AssignedClinics=AssignedClinicsThisPage<?php echo ((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'');?>" height=170px></iframe>
</center></td></tr>
 <tr><td style='text-align: center;'><span style='color: green; text-align: center;'><?php echo $controlforminput; ?></span></td></tr>
  <tr>
    <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<td style='text-align: center;'>
	    <b>SELECT CLINIC</b>
     
	    <select name='Clinic_Name' id='Clinic_Name'>
		<?php
		    $data = mysqli_query($conn,"select * from tbl_clinic");
		    while($row = mysqli_fetch_array($data)){
			echo '<option>'.$row['Clinic_Name'].'</option>';
		    }
		?>    
	    </select>
		<input type='submit' name='submit' id='submit' value='   ADD CLINIC   ' class='art-button-green'>
		<input type='hidden' name='submittedAddClinicForm' value='true'/> 
    <input type="button" class="art-button-green" name="button" value="ASSIGN TO FINANCIAL DEPARTMENT" onclick="open_assignment_dialog();">

	</td>
    </form>
</tr>
 </table></center>

<div id="finance_department_assignment" style="display:none;overflow-y:auto;">
  <table width='100%'>
    <tr>
      <td colspan="3"><b>NOTE:</b>These Departments will Appear At Doctor's Page</td>
    </tr>
    <tr>
      <td>SN</td>
      <td>FINANCIAL DEPARTMENT NAME</td>
      <td>FINANCIAL DEPARTMENT CODE</td>
      <td>ASSIGN</td>
    </tr>
    <?php
      $select_financial_departments = mysqli_query($conn,"SELECT * FROM tbl_finance_department WHERE enabled_disabled = 'enabled'");
      $count = 1;
      while ($row = mysqli_fetch_assoc($select_financial_departments)) {
        $department_id = $row['finance_department_id'];
        $check_existance =  mysqli_query($conn,"SELECT * FROM tbl_assign_finance_department WHERE Employee_ID = '$Employee_ID' AND finance_department_id = '$department_id' ");
        $curr_status = "";
        if(mysqli_num_rows($check_existance) > 0){
          $curr_status = "checked";
        }
        echo "
        <tr class='financial'>
          <td>{$count}</td>
          <td>".$row['finance_department_name']."</td>
          <td>".$row['finance_department_code']."</td>
          <td><input type='checkbox' name='assign_box' onclick='assign_financial_department(\"$department_id\",this)' $curr_status></td>
        </tr>";
        $count++;
      }
     ?>
  </table>
</div>
 
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript">
  function open_assignment_dialog(){
    $("#finance_department_assignment").dialog({
             title: 'FINANCIAL DEPARTMENT',
             width: '50%',
             height: 500,
             modal: true,
         });
  }

  function assign_financial_department(department_id,e){
    var Employee_ID = "<?=$Employee_ID?>";
    var action = '';
    //$(e).prop('checked', false);
    if($(e).is(':checked')){
      action = 'check';
    }else{
      action = 'uncheck';
    }
    jQuery.ajax({
      url:'assign_financial_department.php',
      type:'post',
      data:{Employee_ID:Employee_ID,department_id:department_id,action:action},
      dataType:'json',
      success:function(results){
        if(results.message == 'ok'){
          alert("DEPARTMENT ASSEGNED SUCCESSIFULLY!!");
        }else if(results.message == 'error'){
          alert("DEPARTMENT ASSEGNED SUCCESSIFULLY!!");
        }else if(results.message == 'exists'){
          alert("THIS DEPARTMENT IS ALREADY ASSIGNED!!");
        }else if(results.message == 'del_ok'){
          alert("DEPARTMENT REMOVED SUCCESSIFULLY!!");
        }else if(results.message == 'del_error'){
          alert("DEPARTMENT REMOVED SUCCESSIFULLY!!");
        }
      }

    });
  }
</script>
<?php
    include("./includes/footer.php");
?>
