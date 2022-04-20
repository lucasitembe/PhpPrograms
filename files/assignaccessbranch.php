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
    
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID'];
    }else{
	$Employee_ID = 0;
    }
    
    if(isset($_SESSION['userinfo'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'  && !isset($_GET['HRWork'])){ 
		echo "<a href='employeepage.php?EmployeeManagement=EmployeeManagementThisPage' class='art-button-green'>BACK</a>";
	}
	if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
		echo "<a href='human_resource.php?HRWork=HRWorkThisPage' class='art-button-green'>BACK</a>";
	}
    }
?>


 <?php
    $Select_Assigned_Branches = mysqli_query($conn,"select * from tbl_employee where employee_id = '$Employee_ID'");
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
	if(isset($_POST['submittedAddAccessBranchForm'])){
		$Branch_Name = $_POST['Branch_Name']; 
		
		$sql = "insert into tbl_branch_employee(Employee_ID,Branch_ID)
				values('$Employee_ID',(select branch_id from tbl_branches where branch_name = '$Branch_Name'))";
		
		if(!mysqli_query($conn,$sql)){
		    $error = '1062yes';
		    if(mysql_errno()."yes" == $error){ 
			$controlforminput = "<b>Duplication! User Already Assigned To ".$Branch_Name."</b>";
		    }
		}
		else {
		    $controlforminput = '<b>Process Successful</b>';
		}
	}
?>

 
 
<tr><td>
 <fieldset>
            <legend align="center" ><b>ASSIGNED ACCESS BRANCHES</b></legend>
<iframe width='100%' src="assignaccessbranchiframe.php?Employee_ID=<?php echo $Employee_ID; ?>&AssignAccessBranch=AssignAccessBranchThisPage<?php echo ((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'');?>" height=170px></iframe>
</center></td></tr>
 <tr><td style='text-align: center;'><span style='color: green; text-align: center;'><?php echo $controlforminput; ?></span></td></tr>
  <tr>
    <form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<td style='text-align: center;'>
	    <b>SELECT BRANCH</b>
     
	    <select name='Branch_Name' id='Branch_Name'>
		<?php
		    $data = mysqli_query($conn,"select * from tbl_branches");
		    while($row = mysqli_fetch_array($data)){
			echo '<option>'.$row['Branch_Name'].'</option>';
		    }
		?>    
	    </select>
		<input type='submit' name='submit' id='submit' value='   ADD   ' class='art-button-green'>
		<input type='hidden' name='submittedAddAccessBranchForm' value='true'/> 
	</td>
    </form>
</tr>
 </table></center> 

<?php
    include("./includes/footer.php");
?>